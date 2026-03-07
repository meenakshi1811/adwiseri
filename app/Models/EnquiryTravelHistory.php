<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryTravelHistory extends Model
{
    protected $table = 'enquiry_travel_history';

    protected $fillable = [
        'enquiry_id',
        'country',
        'duration'
    ];

    public function enquiry()
    {
        return $this->belongsTo(VisaEnquiry::class,'enquiry_id');
    }
}