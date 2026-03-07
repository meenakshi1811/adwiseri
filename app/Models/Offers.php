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
        'discount_value'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
