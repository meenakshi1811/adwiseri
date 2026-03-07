<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaEnquiry extends Model
{
    use HasFactory;

    protected $table = 'visa_enquiries';

    protected $fillable = [
        'subscriber_id',
        'full_name',
        'dob',
        'email',
        'contact_no',
        'marital_status',
        'address',
        'country_pref_1',
        'country_pref_2',
        'country_pref_3',
        'visa_category',
        'qualification',
        'institution',
        'passing_year',
        'grade',
        'english_test',
        'overall_score',
        'test_date',
        'spouse_name',
        'spouse_email',
        'spouse_dob',
        'spouse_contact',
        'funding',
        'place',
        'sign_name',
        'signature'
    ];

    public function residencyHistory()
    {
        return $this->hasMany(EnquiryResidencyHistory::class,'enquiry_id');
    }

    public function travelHistory()
    {
        return $this->hasMany(EnquiryTravelHistory::class,'enquiry_id');
    }

    public function refusalHistory()
    {
        return $this->hasMany(EnquiryRefusalHistory::class,'enquiry_id');
    }

    public function workExperience()
    {
        return $this->hasMany(EnquiryWorkExperience::class,'enquiry_id');
    }

    public function children()
    {
        return $this->hasMany(EnquiryChild::class,'enquiry_id');
    }

    public function fundingSources()
    {
        return $this->hasMany(EnquiryFundingSource::class,'enquiry_id');
    }

}