<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    use HasFactory;
    protected $table = "activities";
    protected $primaryKey = "id";
    protected $fillable = [
        'client_id',
        'user_id',
        'activity_name',
        'activity_detail',
    ];

    public function user(){
        return $this->belongsTo(User::class,'subscriber_id');
    }
}
