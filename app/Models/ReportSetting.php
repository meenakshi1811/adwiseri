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
        'emails'
    ];

    protected $casts = [
        'modules' => 'array'
    ];
}