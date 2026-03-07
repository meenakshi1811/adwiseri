<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryResidencyHistory extends Model
{
    protected $table = 'enquiry_residency_history';

    protected $fillable = [
        'enquiry_id',
        'country',
        'duration',
        'visa_category'
    ];

    public function enquiry()
    {
        return $this->belongsTo(VisaEnquiry::class,'enquiry_id');
    }
}