<?php

namespace App\Console\Commands;

use App\Models\Affiliates;
use App\Models\Applications;
use App\Models\Clients;
use App\Models\Internal_Invoices;
use App\Models\PaymentARs;
use App\Models\Referrals;
use App\Models\ReportSetting;
use App\Models\Used_referrals;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendScheduledReports extends Command
{
    protected $signature = 'reports:dispatch-scheduled';

    protected $description = 'Generate and send scheduled report PDFs as attachment or link based on report settings.';

    public function handle()
    {
        $settings = ReportSetting::all();

        foreach ($settings as $setting) {
            $user = User::find($setting->user_id);

            if (!$user || empty($setting->modules) || empty($setting->frequency) || empty($setting->delivery_mode)) {
                continue;
            }

            if (!$this->isDueToday($setting->frequency)) {
                continue;
            }

            [$startDate, $endDate] = $this->resolveDateRange($setting->frequency);
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
            ]);
            $pdf->save($filePath);

            $recipients = $this->extractRecipients($setting->emails, $user->email);
            $downloadLink = URL::temporarySignedRoute('scheduled_report_download', now()->addDays(7), ['file' => $fileName]);

            foreach ($recipients as $recipient) {
                Mail::send([], [], function ($message) use ($recipient, $setting, $filePath, $downloadLink, $startDate, $endDate) {
                    $message->to($recipient)
                        ->subject('Adwiseri Scheduled Report (' . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y') . ')');

                    if ($setting->delivery_mode === 'attachment') {
                        $message->attach($filePath);
                        $message->setBody('Please find your scheduled report attached.', 'text/html');
                    } else {
                        $message->setBody('Your scheduled report is ready. Download here: <a href="' . $downloadLink . '">' . $downloadLink . '</a>', 'text/html');
                    }
                });
            }

            $this->info('Scheduled report sent for user_id: ' . $setting->user_id);
        }

        return Command::SUCCESS;
    }

    private function isDueToday($frequency)
    {
        $today = now();

        if ($frequency === 'daily') {
            return true;
        }

        if ($frequency === 'weekly') {
            return $today->dayOfWeek === Carbon::MONDAY;
        }

        if ($frequency === 'monthly') {
            return $today->day === 1;
        }

        if ($frequency === 'quarterly') {
            return $today->day === 1 && in_array($today->month, [1, 4, 7, 10]);
        }

        return false;
    }

    private function resolveDateRange($frequency)
    {
        $endDate = now()->subDay()->endOfDay();

        if ($frequency === 'daily') {
            return [$endDate->copy()->startOfDay(), $endDate];
        }

        if ($frequency === 'weekly') {
            return [$endDate->copy()->subDays(6)->startOfDay(), $endDate];
        }

        if ($frequency === 'monthly') {
            return [$endDate->copy()->startOfMonth(), $endDate];
        }

        if ($frequency === 'quarterly') {
            $startMonth = $endDate->copy()->firstOfQuarter()->month;
            return [Carbon::create($endDate->year, $startMonth, 1)->startOfDay(), $endDate];
        }

        return [$endDate->copy()->startOfDay(), $endDate];
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
                ->get()
                ->toArray();
            $data[] = ['title' => 'Clients', 'rows' => $rows];
        }

        if (in_array('applications', $modules)) {
            $rows = Applications::where('subscriber_id', $subscriberId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'application_id', 'application_name', 'client_id', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            $data[] = ['title' => 'Applications', 'rows' => $rows];
        }

        if (in_array('invoices', $modules)) {
            $rows = Internal_Invoices::where('subscriber_id', $subscriberId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'invoice_id', 'client_name', 'status', 'total', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            $data[] = ['title' => 'Invoices', 'rows' => $rows];
        }

        if (in_array('payments', $modules)) {
            $rows = PaymentARs::where('subscriber_id', $subscriberId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'type', 'amount', 'paid_amount', 'payment_mode', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            $data[] = ['title' => 'Payments', 'rows' => $rows];
        }

        if (in_array('referrals', $modules)) {
            $rows = Referrals::whereIn('userid', $userIds)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'userid', 'type', 'commission_earnt', 'wallet_balance', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            $data[] = ['title' => 'Referrals', 'rows' => $rows];
        }

        if (in_array('wallets', $modules)) {
            $rows = Used_referrals::where('subscriber_id', $subscriberId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'referral_code', 'commission_earnt', 'wallet_balance', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            $data[] = ['title' => 'Wallets', 'rows' => $rows];
        }

        if (strtolower($user->user_type) === 'admin' && in_array('subscribers', $modules)) {
            $rows = User::where('user_type', 'Subscriber')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'name', 'email', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            $data[] = ['title' => 'Subscribers', 'rows' => $rows];
        }

        if (strtolower($user->user_type) === 'admin' && in_array('affiliates', $modules)) {
            $rows = Affiliates::whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'name', 'email', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            $data[] = ['title' => 'Affiliates', 'rows' => $rows];
        }

        return $data;
    }

    private function extractRecipients($emails, $fallbackEmail)
    {
        if (empty($emails)) {
            return [$fallbackEmail];
        }

        $items = array_filter(array_map('trim', explode(',', $emails)));

        if (empty($items)) {
            return [$fallbackEmail];
        }

        return array_values(array_unique($items));
    }
}
