<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_user_id',
        'audience',
        'template_key',
        'template_name',
        'custom_name',
        'subject',
        'body',
    ];
}
