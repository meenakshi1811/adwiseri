<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSubscriptions extends Model
{
    use HasFactory;
    protected $table = "email_subscriptions";
    protected $primaryKey = "id";
    protected $fillable = [
        'email',
    ];
}
