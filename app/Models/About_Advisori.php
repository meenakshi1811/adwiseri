<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About_Advisori extends Model
{
    use HasFactory;
    protected $table = "about_advisori";
    protected $primaryKey = "id";
    protected $fillable = [
        'banner',
        'image',
        'heading',
        'content',
    ];
}
