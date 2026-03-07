<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber_Categories extends Model
{
    use HasFactory;
    protected $table = "subscriber_categories";
    protected $primaryKey = "id";
    protected $fillable = [
        'category_name',
    ];
}
