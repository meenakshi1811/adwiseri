<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    use HasFactory;
    protected $table = "states";
    protected $primaryKey = "id";
    protected $fillable = [
        'country_name',
        'state_name',
    ];
}
