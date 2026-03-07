<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryRefusalHistory extends Model
{
    protected $table = 'enquiry_refusal_history';

    protected $fillable = [
        'enquiry_id',
        'country',
        'refusal_date',
        'refusal_reason'
    ];

    public function enquiry()
    {
        return $this->belongsTo(VisaEnquiry::class,'enquiry_id');
    }
}