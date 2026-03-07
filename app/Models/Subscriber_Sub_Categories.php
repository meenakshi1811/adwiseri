<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber_Sub_Categories extends Model
{
    use HasFactory;
    protected $table = "subscriber_sub_categories";
    protected $primaryKey = "id";
    protected $fillable = [
        'category_name',
        'sub_category_name',
    ];
}
