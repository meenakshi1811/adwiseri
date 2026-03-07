<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryChild extends Model
{
    protected $table = 'enquiry_children';

    protected $fillable = [
        'enquiry_id',
        'child_name',
        'child_age',
        'child_gender',
        'child_dob'
    ];

    public function enquiry()
    {
        return $this->belongsTo(VisaEnquiry::class,'enquiry_id');
    }
}