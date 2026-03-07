<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryFundingSource extends Model
{
    protected $table = 'enquiry_funding_sources';

    protected $fillable = [
        'enquiry_id',
        'funding_source'
    ];

    public function enquiry()
    {
        return $this->belongsTo(VisaEnquiry::class,'enquiry_id');
    }
}