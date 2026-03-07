<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;
    protected $table = "tickets";
    protected $primaryKey = "id";
    protected $fillable = [
        'ticket_no',
        'client_id',
        'issue',
        'response',
        'status',
        'related_to',
        'served_by',
        'subscriber_id'
    ];

    public function servedBy(){
        return $this->belongsTo(User::class,'served_by');
    }
    public function subscriber(){
        return $this->belongsTo(User::class,'subscriber_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

}
