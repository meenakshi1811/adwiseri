<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedbacks extends Model
{
    use HasFactory;

    protected $table ='feedbacks';

    protected $fillable =[
           'subscriber_id',
             'user_id',
            'feedback',
            'rating'

    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
