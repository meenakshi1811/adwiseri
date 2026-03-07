<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependants extends Model
{
    use HasFactory;

    protected $table ='dependants';

    protected $fillable =[
        'name',
        'client_id',
        'subscriber_id',
        'dob',
        'passport_no',
        'relation',
        'gender'
    ];
    public function client(){
        return $this->belongsTo(Clients::class,'client_id');
    }

    public function subscriber(){
        return $this->belongsTo(User::class,'subscriber_id');
    }

    // Mutator for formatting name before saving
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }
}
