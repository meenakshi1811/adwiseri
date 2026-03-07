<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application_assignments extends Model
{
    use HasFactory;
    protected $table = "application_assignments";
    protected $primaryKey = "id";
    protected $fillable = [
        'client_id',
        'application_id',
        'user_id',
        'user_name',
        'assigned_by',
    ];

    public function client(){
        return $this->belongsTo(Clients::class,'client_id');
    }
    public function application(){
        return $this->belongsTo(Applications::class,'application_id','application_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
