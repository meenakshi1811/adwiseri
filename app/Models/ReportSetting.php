<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSetting extends Model
{
    use HasFactory;

    protected $table = 'report_settings';

    protected $fillable = [
        'user_id',
        'modules',
        'frequency',
        'delivery_mode',
        'emails',
        'last_sent_at',
        'last_sent_status',
        'last_sent_message',
        'last_file_name'
    ];

    protected $casts = [
        'modules' => 'array',
        'last_sent_at' => 'datetime'
    ];
}