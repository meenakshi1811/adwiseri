<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateCommissionEarnt extends Model
{
    use HasFactory;

    protected $fillable = ['referral_code','total_earned','paid_amount','pending_amount','last_paid_at'];

    public function referrals()
    {
        return $this->hasMany(Referrals::class, 'referral_code', 'referral_code');
    }
    public function getUser()
    {
        return $this->hasOne(User::class,'referral','referral_code');
    }
}
