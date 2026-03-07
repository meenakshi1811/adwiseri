<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemoRequests extends Model
{
    use HasFactory;
    protected $table = "demo_requests";
    protected $primaryKey = "id";
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'city',
        'status',
        'terms_accepted_at',
       'company_name',
       'job_title',
       'how_did_hear',
    ];
}
