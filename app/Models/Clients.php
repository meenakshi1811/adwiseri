<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;
    protected $table = "clients";
    protected $primaryKey = "id";

    protected $fillable =[
        'nationality',
        'counntry',
        'name',
        'subscriber_id'
    ];

    public function user(){

        return $this->belongsTo(User::class,'user_id');
    }

    public function docs(){
        return $this->hasMany(Client_Docs::class,'client_id');
    }
    public function applications(){
        return $this->hasMany(Applications::class,'client_id');
    }
    public function subscriber(){
        return $this->belongsTo(User::class,'subscriber_id');
    }
    public function dependants(){
        return $this->hasMany(Dependants::class,'client_id');
    }

    // Mutator for formatting name before saving
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

     // A client can have multiple invoices
     public function invoices()
     {
         return $this->hasMany(Invoices::class, 'user_id', 'user_id');
     }

     public function rules()
    {
        return [
            'subscriber' => 'required|exists:subscribers,id',
            'client_id' => [
                'required',
                Rule::exists('clients', 'id')->where(function ($query) {
                    $query->where('subscriber_id', request('subscriber'));
                }),
            ],
        ];
    }


}
