<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReminderSetting extends Model
{
    use HasFactory;

    protected $table = 'payment_reminder_settings';

    protected $fillable = [
        'user_id',
        'client_group',
        'email_frequency',
        'email_to',
        'last_sent_at',
    ];

    protected $casts = [
        'last_sent_at' => 'datetime',
    ];
}
