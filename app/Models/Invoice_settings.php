<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_settings extends Model
{
    use HasFactory;
    protected $table = "invoice_settings";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'country',
        'state',
        'city',
        'pincode',
        'tax',
        'discount',
        'description',
        'payment_link'
    ];
}
