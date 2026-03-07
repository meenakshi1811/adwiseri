<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_Docs extends Model
{
    use HasFactory;
    protected $table = "client_docs";
    protected $primaryKey = "id";
    protected $fillable = [
        'client_id',
        'aadhar_card',
        'passport',
        'voter_card',
        'bank_passbook',
        'electricity_bill',
        'application_id'
    ];
    public function client(){
        return $this->belongsTo(Clients::class,'client_id');
    }
    public function application(){
        return $this->belongsTo(Applications::class,'application_id','application_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
