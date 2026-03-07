<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use User;

use Hash;

class Affiliates extends Authenticatable
{
    use HasFactory;
    protected $table ='affiliates';
    protected $fillable = ['name','phone','email','type','country','city','password','terms_accepted_at'];

    protected $hidden = [
        'password', 'remember_token',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
    public function getEmail()
    {
        // return $this->hasOne(User::class,'email','email');
        return $this->hasOneThrough(
            Referrals::class, // The model to access
            User::class, // The intermediate model
            'email', // Foreign key on the User model
            'referral_code', // Foreign key on the Referrals model
            'email', // Local key on the Affiliates model
            'referral' // Local key on the User model
        );

    }


    public function getRefferal()
    {
        $referral = $this->user ? $this->user->referral : null;

        return $referral ? Referrals::where('referral_code', $referral)->first() : null;

    }
   

}
