<?php

namespace App\Console\Commands;

use App\Models\ReportSetting;
use App\Services\ScheduledReportService;
use Illuminate\Console\Command;

class SendScheduledReports extends Command
{
    protected $signature = 'reports:dispatch-scheduled';

    protected $description = 'Generate and send scheduled report PDFs as attachment or link based on report settings.';

    public function handle(ScheduledReportService $scheduledReportService)
    {
        $settings = ReportSetting::all();

        foreach ($settings as $setting) {
            if (!$scheduledReportService->shouldRunForSetting($setting)) {
                continue;
            }

            $result = $scheduledReportService->dispatchForSetting($setting, 'scheduled');
            $this->info('user_id ' . $setting->user_id . ': ' . $result['message']);
        }

        return Command::SUCCESS;
    }
}
