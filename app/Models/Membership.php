<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;
    protected $table = "membership";
    protected $primaryKey = "id";
    protected $fillable = [
        'plan_name',
        'data_limit',
        'client_limit',
        'reports',
        'analytics',
        'no_of_users',
        'no_of_branches',
        'price_per_year',
        'messaging',
        'invoicing',
        'multi_device_support',
        'secure_environment',
        'multi_currency_support',
    ];
}
