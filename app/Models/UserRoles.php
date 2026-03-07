<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    use HasFactory;
    protected $table = "user_roles";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'subscriber_id',
        'name',
        'email',
        'read_only',
        'write_only',
        'update_only',
        'delete_only',
        'read_write_only',
    ];
}
