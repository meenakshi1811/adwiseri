<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'client_id',
        'subscriber_id',
        'user_id',
        'appointment_date',
        'appointment_time',
        'remarks',
        'send_via',
        'calendly_link',
        'calendly_event_uri',
        'status'
    ];

    public function client()
    {
        return $this->belongsTo(Clients::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(User::class,'subscriber_id');
    }
}