<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    protected $table ='services';

    protected $fillable =[
        'subscriber_id',
        'user_id',
        'service_name',
        'fees',
        'status'
    ];

    public function subscriber(){
        return $this->belongsTo(User::class,'subscriber_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    // A service can have multiple invoices
    public function invoices()
    {
        return $this->hasMany(Invoices::class, 'user_id', 'user_id');
    }

}
