<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contactus extends Model
{
    use HasFactory;
    protected $table = "contactus";
    protected $primaryKey = "id";
    protected $fillable = [
        'contact_no',
        'alternate_no',
        'location',
        'email',
        'website',
        'banner',
        'general_enquiries'
    ];
}
