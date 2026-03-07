<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyTimezones extends Model
{
    use HasFactory;
    protected $table = "timezones";
    protected $primaryKey = "id";
    protected $fillable = [
        'TimeZone',
        'CountryCode',
        'Coordinates',
        'Comments',
        'UTC offset',
        'UTC DST offset',
        'Notes',
    ];
}
