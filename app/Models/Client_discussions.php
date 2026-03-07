<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_discussions extends Model
{
    use HasFactory;
    protected $table = "client_discussions";
    protected $primaryKey = "id";
    protected $fillable = [
        'subscriber_id',
        'user_id',
        'client_id',
        'application_id',
        'user_name',
        'client_name',
        'communication_type',
        'communication_date',
        'discussion',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
