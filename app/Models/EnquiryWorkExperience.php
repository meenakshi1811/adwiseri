<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryWorkExperience extends Model
{
    protected $table = 'enquiry_work_experience';

    protected $fillable = [
        'enquiry_id',
        'job_title',
        'employer',
        'work_country',
        'joining_date'
    ];

    public function enquiry()
    {
        return $this->belongsTo(VisaEnquiry::class,'enquiry_id');
    }
}