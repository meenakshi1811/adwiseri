<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentARs extends Model
{
    use HasFactory;


    protected $table ='payment_ar';

    protected $fillable =[
        "client_id",
        "application_id",
        "service_description",
        "amount",
        "paid_amount",
        "payment_mode",
        "payment_date",
        'subscriber_id',
        'invoice_no',
        'service_provider',
        'service_taken',
        'type'  // ar Payment Received and ap Advance payment

    ];

    public function client(){
        return $this->belongsTo(Clients::class,'client_id');
    }
    public function application(){
        return $this->belongsTo(Applications::class,'application_id');
    }
    public function subscriber(){
        return $this->belongsTo(User::class,'subscriber_id');
    }
}
