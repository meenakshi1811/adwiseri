<?php

namespace App\Services;

use App\Models\Affiliates;
use App\Models\Applications;
use App\Models\Clients;
use App\Models\Internal_Invoices;
use App\Models\PaymentARs;
use App\Models\Referrals;
use App\Models\ReportDispatchLog;
use App\Models\ReportSetting;
use App\Models\Used_referrals;
use App\Mail\ScheduledReportMail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class ScheduledReportService
{
    public function dispatchForSetting(ReportSetting $setting, $trigger = 'manual')
    {
        $user = User::find($setting->user_id);

        if (!$user || empty($setting->modules) || empty($setting->frequency) || empty($setting->delivery_mode)) {
            return ['status' => 'skipped', 'message' => 'Invalid report setting configuration.'];
        }

        [$startDate, $endDate] = $this->resolveDateRange($setting->frequency, $setting);
        $normalizedModules = (array) $setting->modules;
        sort($normalizedModules);
        $modulesHash = md5(json_encode($normalizedModules));

        $existingSuccess = ReportDispatchLog::where('report_setting_id', $setting->id)
            ->where('period_start', $startDate->toDateString())
            ->where('period_end', $endDate->toDateString())
            ->where('modules_hash', $modulesHash)
            ->where('delivery_mode', $setting->delivery_mode)
            ->where('status', 'sent')
            ->first();

        if ($existingSuccess) {
            return ['status' => 'skipped', 'message' => 'Report already sent for this period.'];
        }

        $subscriberId = (strtolower($user->user_type) === 'subscriber' || strtolower($user->user_type) === 'admin') ? $user->id : $user->added_by;
        $reportData = $this->buildReportData($setting->modules, $subscriberId, $startDate, $endDate, $user);

        $fileName = 'scheduled_report_' . $setting->user_id . '_' . now()->format('Ymd_His') . '.pdf';
        $reportDir = storage_path('app/reports');

        if (!file_exists($reportDir)) {
            mkdir($reportDir, 0755, true);
        }

        $filePath = $reportDir . '/' . $fileName;
        $pdf = PDF::loadView('reports.scheduled_report_pdf', [
            'reportData' => $reportData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'frequency' => $setting->frequency,
            'generatedFor' => $user,
        ])->setPaper('a4', 'portrait')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isPhpEnabled', true);
        $pdf->save($filePath);

        $recipients = $this->extractRecipients($setting->emails, $user->email);
        $downloadLink = URL::temporarySignedRoute('scheduled_report_download', now()->addDays(7), ['file' => $fileName]);

        try {
            $subject = 'Adwiseri Scheduled Report (' . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y') . ')';
            $sentRecipients = [];
            $failedRecipients = [];

            foreach ($recipients as $recipient) {
                $mailData = [
                    'subject' => $subject,
                    'recipient_name' => $user->name ?? 'User',
                    'start_date' => $startDate->format('d M Y'),
                    'end_date' => $endDate->format('d M Y'),
                    'download_link' => $downloadLink,
                ];

                try {
                    Mail::to($recipient)->send(new ScheduledReportMail($mailData, $filePath, $fileName));
                    $sentRecipients[] = $recipient;
                } catch (\Exception $mailException) {
                    $failedRecipients[] = $recipient;
                }
            }

            if (!empty($sentRecipients)) {
                $statusMessage = 'Report sent successfully.';
                if (!empty($failedRecipients)) {
                    $statusMessage = 'Report sent to some recipients only. Failed recipients: ' . implode(', ', $failedRecipients);
                }

                $log = ReportDispatchLog::create([
                    'report_setting_id' => $setting->id,
                    'user_id' => $setting->user_id,
                    'frequency' => $setting->frequency,
                    'delivery_mode' => $setting->delivery_mode,
                    'modules_hash' => $modulesHash,
                    'period_start' => $startDate->toDateString(),
                    'period_end' => $endDate->toDateString(),
                    'file_name' => $fileName,
                    'recipients' => json_encode($recipients),
                    'status' => 'sent',
                    'triggered_by' => $trigger,
                    'sent_at' => now(),
                    'error_message' => empty($failedRecipients) ? null : $statusMessage,
                ]);

                $setting->last_sent_at = $log->sent_at;
                $setting->last_sent_status = empty($failedRecipients) ? 'sent' : 'partial';
                $setting->last_sent_message = $statusMessage;
                $setting->last_file_name = $fileName;
                $setting->save();

                return ['status' => empty($failedRecipients) ? 'sent' : 'partial', 'message' => $statusMessage, 'file' => $fileName];
            }

            $failedMessage = 'Report could not be sent to recipients. Please verify recipient emails and SMTP configuration.';

            ReportDispatchLog::create([
                'report_setting_id' => $setting->id,
                'user_id' => $setting->user_id,
                'frequency' => $setting->frequency,
                'delivery_mode' => $setting->delivery_mode,
                'modules_hash' => $modulesHash,
                'period_start' => $startDate->toDateString(),
                'period_end' => $endDate->toDateString(),
                'file_name' => $fileName,
                'recipients' => json_encode($recipients),
                'status' => 'failed',
                'triggered_by' => $trigger,
                'error_message' => $failedMessage,
            ]);

            $setting->last_sent_status = 'failed';
            $setting->last_sent_message = $failedMessage;
            $setting->save();

            return ['status' => 'failed', 'message' => $failedMessage];
        } catch (\Exception $e) {
            ReportDispatchLog::create([
                'report_setting_id' => $setting->id,
                'user_id' => $setting->user_id,
                'frequency' => $setting->frequency,
                'delivery_mode' => $setting->delivery_mode,
                'modules_hash' => $modulesHash,
                'period_start' => $startDate->toDateString(),
                'period_end' => $endDate->toDateString(),
                'file_name' => $fileName,
                'recipients' => json_encode($recipients),
                'status' => 'failed',
                'triggered_by' => $trigger,
                'error_message' => $e->getMessage(),
            ]);

            $setting->last_sent_status = 'failed';
            $setting->last_sent_message = $e->getMessage();
            $setting->save();

            return ['status' => 'failed', 'message' => 'Report send failed: ' . $e->getMessage()];
        }
    }

    public function shouldRunForSetting(ReportSetting $setting)
    {
        $timezone = $this->resolveTimezone($setting);
        $now = now($timezone);

        if ((int) $now->format('G') !== 8 || (int) $now->format('i') >= 30) {
            return false;
        }

        if ($setting->frequency === 'daily') {
            return true;
        }

        if ($setting->frequency === 'weekly') {
            return $now->dayOfWeek === Carbon::MONDAY;
        }

        if ($setting->frequency === 'monthly') {
            return $now->day === 1;
        }

        if ($setting->frequency === 'quarterly') {
            return $now->day === 1 && in_array($now->month, [1, 4, 7, 10], true);
        }

        return false;
    }

    private function resolveDateRange($frequency, ReportSetting $setting)
    {
        $timezone = $this->resolveTimezone($setting);
        $now = now($timezone);

        if ($frequency === 'daily') {
            $period = $now->copy()->subDay();
            return [$period->copy()->startOfDay(), $period->copy()->endOfDay()];
        }

        if ($frequency === 'weekly') {
            $period = $now->copy()->subWeek();
            return [
                $period->copy()->startOfWeek(Carbon::MONDAY),
                $period->copy()->endOfWeek(Carbon::SUNDAY),
            ];
        }

        if ($frequency === 'monthly') {
            $period = $now->copy()->subMonthNoOverflow();
            return [$period->copy()->startOfMonth(), $period->copy()->endOfMonth()];
        }

        if ($frequency === 'quarterly') {
            $period = $now->copy()->subQuarter();
            return [$period->copy()->startOfQuarter(), $period->copy()->endOfQuarter()];
        }

        return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
    }

    private function resolveTimezone(ReportSetting $setting): string
    {
        $user = User::find($setting->user_id);
        $timezone = $user?->timezone ?: config('app.timezone', 'UTC');

        return in_array($timezone, timezone_identifiers_list(), true)
            ? $timezone
            : config('app.timezone', 'UTC');
    }

    private function buildReportData($modules, $subscriberId, $startDate, $endDate, $user)
    {
        $userIds = User::where('added_by', $subscriberId)->pluck('id')->toArray();
        $userIds[] = $subscriberId;

        $data = [];

        if (in_array('clients', $modules)) {
            $rows = Clients::where('subscriber_id', $subscriberId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'name', 'email', 'phone', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()->toArray();
            $data[] = ['title' => 'Clients', 'rows' => $rows];
        }

        if (in_array('applications', $modules)) {
            $rows = Applications::where('subscriber_id', $subscriberId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'application_id', 'application_name', 'client_id', 'application_status as status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()->toArray();
            $data[] = ['title' => 'Applications', 'rows' => $rows];
        }

        if (in_array('invoices', $modules)) {
            $rows = Internal_Invoices::where('subscriber_id', $subscriberId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select($this->resolveReportColumns('internal_invoices', [
                    'id' => ['id'],
                    'invoice_no' => ['invoice_no', 'invoice_id'],
                    'client_name' => ['client_name', 'to_name', 'name'],
                    'status' => ['status'],
                    'total' => ['total', 'amount'],
                    'created_at' => ['created_at'],
                ]))
                ->orderBy('created_at', 'desc')
                ->get()->toArray();
            $data[] = ['title' => 'Invoices', 'rows' => $rows];
        }

        if (in_array('payments', $modules)) {
            $rows = PaymentARs::where('subscriber_id', $subscriberId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'type', 'amount', 'paid_amount', 'payment_mode', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()->toArray();
            $data[] = ['title' => 'Payments', 'rows' => $rows];
        }

        if (in_array('referrals', $modules)) {
            $referralUserColumn = $this->resolveExistingColumn('referrals', ['userid', 'user_id']) ?? 'userid';

            $rows = Referrals::whereIn($referralUserColumn, $userIds)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select($this->resolveReportColumns('referrals', [
                    'id' => ['id'],
                    'userid' => ['userid', 'user_id'],
                    'type' => ['type'],
                    'commission_earnt' => ['commission_earnt', 'amount_added', 'total_amount'],
                    'wallet_balance' => ['wallet_balance'],
                    'created_at' => ['created_at'],
                ]))
                ->orderBy('created_at', 'desc')
                ->get()->toArray();
            $data[] = ['title' => 'Referrals', 'rows' => $rows];
        }

        if (in_array('wallets', $modules)) {
            $walletSubscriberColumn = $this->resolveExistingColumn('used_referrals', ['subscriber_id', 'user_id']) ?? 'subscriber_id';

            $rows = Used_referrals::where($walletSubscriberColumn, $subscriberId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select($this->resolveReportColumns('used_referrals', [
                    'id' => ['id'],
                    'referral_code' => ['referral_code'],
                    'commission_earnt' => ['commission_earnt', 'amount_added', 'total_amount'],
                    'wallet_balance' => ['wallet_balance'],
                    'created_at' => ['created_at'],
                ]))
                ->orderBy('created_at', 'desc')
                ->get()->toArray();
            $data[] = ['title' => 'Wallets', 'rows' => $rows];
        }

        if (strtolower($user->user_type) === 'admin' && in_array('subscribers', $modules)) {
            $rows = User::where('user_type', 'Subscriber')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'name', 'email', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()->toArray();
            $data[] = ['title' => 'Subscribers', 'rows' => $rows];
        }

        if (strtolower($user->user_type) === 'admin' && in_array('affiliates', $modules)) {
            $rows = Affiliates::whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'name', 'email', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()->toArray();
            $data[] = ['title' => 'Affiliates', 'rows' => $rows];
        }

        return $data;
    }


    private function resolveExistingColumn(string $table, array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (Schema::hasColumn($table, $candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function resolveReportColumns(string $table, array $columnMap): array
    {
        $columns = [];

        foreach ($columnMap as $alias => $candidates) {
            $selectedColumn = null;

            foreach ($candidates as $candidate) {
                if (Schema::hasColumn($table, $candidate)) {
                    $selectedColumn = $candidate;
                    break;
                }
            }

            if ($selectedColumn === null) {
                $columns[] = DB::raw('NULL as ' . $alias);
                continue;
            }

            $columns[] = $selectedColumn === $alias
                ? $selectedColumn
                : DB::raw($selectedColumn . ' as ' . $alias);
        }

        return $columns;
    }

    private function extractRecipients($emails, $fallbackEmail)
    {
        if (empty($emails)) {
            return [$fallbackEmail];
        }

        $items = array_filter(array_map('trim', explode(',', $emails)));

        return empty($items) ? [$fallbackEmail] : array_values(array_unique($items));
    }
}
