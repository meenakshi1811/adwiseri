<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_jobs extends Model
{
    use HasFactory;
    protected $table = "client_jobs";
    protected $primaryKey = "id";
    protected $fillable = [
        'category',
        'sub_category',
        'job',
    ];
}
