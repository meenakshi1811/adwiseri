<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;

    protected $table ='offers';

    protected $fillable =[
        'user_id',
        'discount_type',
        'discount_value',
        'subscriber_type',
        'offer_start_date',
        'offer_end_date'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
