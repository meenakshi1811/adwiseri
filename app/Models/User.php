<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'otp_at',
        'terms_accepted_at',
        'user_type',
        'referral',
        'membership',
        'wallet',
        'membership_expiry_date',
        'timezone',
        'dob'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'terms_accepted_at' =>'datetime'
    ];

    public function getFormattedDobAttribute()
    {
        // Get the user's country code (you can modify how you fetch the country code)
        $countryCode = (auth()->user()->country == 'United States') ? 'US' : '';


        // Define date formats based on the country
        $dateFormat = match (strtoupper($countryCode)) {
            'US' => 'd-m-Y', // MM/DD/YYYY for US
            default => 'd-m-Y', // DD-MM-YYYY for other countries
        };

        // Format and return the `dob` field
        return $this->dob ? Carbon::parse($this->dob)->format($dateFormat) : null;
    }

    public function getFormattedMembershipStartAttribute()
    {
        // Get the user's country code (you can modify how you fetch the country code)
        $countryCode = (auth()->user()->country == 'United States') ? 'US' : '';


        // Define date formats based on the country
        $dateFormat = match (strtoupper($countryCode)) {
            'US' => 'd-m-Y', // MM/DD/YYYY for US
            default => 'd-m-Y', // DD-MM-YYYY for other countries
        };

        // Format and return the `dob` field
        return $this->membership_start_date ? Carbon::parse($this->membership_start_date)->format($dateFormat) : null;
    }
    public function getFormattedMembershipExpiryAttribute()
    {
        // Get the user's country code (you can modify how you fetch the country code)
        $countryCode = (auth()->user()->country == 'United States') ? 'US' : '';


        // Define date formats based on the country
        $dateFormat = match (strtoupper($countryCode)) {
            'US' => 'd-m-Y', // MM/DD/YYYY for US
            default => 'd-m-Y', // DD-MM-YYYY for other countries
        };

        // Format and return the `dob` field
        return $this->membership_expiry_date ? Carbon::parse($this->membership_expiry_date)->format($dateFormat) : null;
    }
    public function subscriber(){
        return $this->hasMany(User::class,'added_by');
    }
    public function getAffiliate()
    {
        return $this->hasOne(Affiliates::class,'email','email');
    }
    public function getReferrals()
    {
        return $this->hasMany(Referrals::class,'referral_code','referral');
    }
    public function referrals()
    {
        return $this->hasMany(Referrals::class, 'referral_code', 'referral');
    }
    public function refs()
    {
        return $this->hasOne(Referrals::class, 'referral_code', 'referral')->latest();
    }
    public function getReferralUser(){
        return $this->hasOne(Referrals::class,'userid','id');
    }

    public function clientDiscussions(){
        return $this->hasMany(Client_discussions::class,'user_id');
    }

    public function clientDiscussionsBySubscriber(){
        return $this->hasMany(Client_discussions::class,'subscriber_id','id')->selectRaw('communication_type, count(*) as count')
        ->groupBy('communication_type');
    }
    public function affiliateTotalCommission(){
            return $this->hasMany(Referrals::class,'referral_code','referral');
    }
    public function applications(){
        return $this->hasMany(Applications::class,'subscriber_id','id');
    }
    public function clients(){
        return $this->hasMany(Clients::class, 'subscriber_id', 'id');
    }
    public function docs(){
        return $this->hasMany(Client_Docs::class,'user_id');
    }

    // Mutator for formatting name before saving
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

}
