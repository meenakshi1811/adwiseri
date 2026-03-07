<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportDispatchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_setting_id',
        'user_id',
        'frequency',
        'delivery_mode',
        'modules_hash',
        'period_start',
        'period_end',
        'file_name',
        'recipients',
        'status',
        'triggered_by',
        'sent_at',
        'error_message',
    ];
}
