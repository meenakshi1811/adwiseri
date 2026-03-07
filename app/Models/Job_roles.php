<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_roles extends Model
{
    use HasFactory;
    protected $table = "job_roles";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'job_role',
    ];
}
