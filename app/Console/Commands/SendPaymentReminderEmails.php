<?php

namespace App\Console\Commands;

use App\Mail\PaymentReminderMail;
use App\Models\Invoice_settings;
use App\Models\PaymentARs;
use App\Models\PaymentReminderSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminderEmails extends Command
{
    protected $signature = 'payments:send-reminders';

    protected $description = 'Send payment reminder emails to clients based on subscriber payment reminder settings.';

    public function handle()
    {
        $settings = PaymentReminderSetting::all();

        foreach ($settings as $setting) {
            if (!$this->shouldRunForFrequency($setting)) {
                continue;
            }

            $subscriber = User::find($setting->user_id);
            if (!$subscriber) {
                continue;
            }

            $rows = $this->outstandingRowsForSubscriber($subscriber->id, $setting->client_group);
            $invoiceSetting = Invoice_settings::where('user_id', $subscriber->id)->first();
            $paymentLink = trim((string) ($invoiceSetting?->payment_link ?? ''));

            $sentCount = 0;

            foreach ($rows as $row) {
                if (empty($row->client_email) || (float) $row->outstanding_amount <= 0) {
                    continue;
                }

                $dueDateObject = $row->due_date ? Carbon::parse($row->due_date) : null;
                if ($dueDateObject && $dueDateObject->isFuture()) {
                    continue;
                }

                $outstandingAmount = number_format((float) $row->outstanding_amount, 2, '.', '');
                $serviceDescription = (string) ($row->service_description ?: '-');
                $dueDate = $dueDateObject ? $dueDateObject->format('d-m-Y') : '-';

                $payload = [
                    'subscriber_name' => (string) $subscriber->name,
                    'client_first_name' => $this->firstName((string) $row->client_name),
                    'client_name' => (string) $row->client_name,
                    'name' => (string) $row->client_name,
                    'currency_symbol' => $this->currencySymbol((string) $subscriber->currency),
                    'amount' => $outstandingAmount,
                    'outstanding_amount' => $outstandingAmount,
                    'invoice_no' => (string) $row->invoice_no,
                    'invoice_id' => (string) $row->invoice_no,
                    'service_description' => $serviceDescription,
                    'application_service' => $serviceDescription,
                    'payment_due_date' => $dueDate,
                    'due_date' => $dueDate,
                    'payment_link' => $paymentLink,
                ];

                $mail = Mail::to($row->client_email);
                if ($setting->email_to === 'client_bcc_subscriber' && !empty($subscriber->email)) {
                    $mail->bcc($subscriber->email);
                }

                $mail->send(new PaymentReminderMail($subscriber, $payload));
                $sentCount++;
            }

            $setting->last_sent_at = now();
            $setting->save();

            $this->info('Processed payment reminders for subscriber_id ' . $subscriber->id . ' (' . $sentCount . ' overdue outstanding invoice reminders sent).');
        }

        return Command::SUCCESS;
    }

    private function outstandingRowsForSubscriber(int $subscriberId, string $clientGroup)
    {
        $query = PaymentARs::query()
            ->from('payment_ar')
            ->join('clients', 'clients.id', '=', 'payment_ar.client_id')
            ->leftJoin('internal_invoices', function ($join) use ($subscriberId) {
                $join->on('internal_invoices.invoice_no', '=', 'payment_ar.invoice_no')
                    ->where('internal_invoices.subscriber_id', '=', $subscriberId);
            })
            ->where('payment_ar.subscriber_id', $subscriberId)
            ->whereRaw('LOWER(payment_ar.type) = ?', ['ar'])
            ->groupBy('payment_ar.client_id', 'payment_ar.invoice_no', 'payment_ar.service_description', 'clients.name', 'clients.email', 'internal_invoices.due_date')
            ->selectRaw('payment_ar.client_id, payment_ar.invoice_no, payment_ar.service_description, clients.name as client_name, clients.email as client_email, internal_invoices.due_date, SUM(payment_ar.amount - payment_ar.paid_amount) as outstanding_amount')
            ->havingRaw('SUM(payment_ar.amount - payment_ar.paid_amount) > 0');

        if ($clientGroup === 'over_500') {
            $query->havingRaw('SUM(payment_ar.amount - payment_ar.paid_amount) > 500');
        }

        if ($clientGroup === 'over_100') {
            $query->havingRaw('SUM(payment_ar.amount - payment_ar.paid_amount) > 100');
        }

        return $query->get();
    }

    private function shouldRunForFrequency(PaymentReminderSetting $setting): bool
    {
        $lastSentAt = $setting->last_sent_at ? Carbon::parse($setting->last_sent_at) : null;

        if (!$lastSentAt) {
            return true;
        }

        if ($setting->email_frequency === 'daily') {
            return $lastSentAt->lte(now()->subDay());
        }

        if ($setting->email_frequency === 'weekly') {
            return $lastSentAt->lte(now()->subWeek());
        }

        if ($setting->email_frequency === 'monthly') {
            return $lastSentAt->lte(now()->subMonth());
        }

        return $lastSentAt->lte(now()->subMonths(3));
    }

    private function firstName(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName));
        return $parts[0] ?? $fullName;
    }

    private function currencySymbol(string $currency): string
    {
        if (preg_match('/\((.*?)\)/', $currency, $match)) {
            return $match[1] ?? '';
        }

        return $currency;
    }
}
