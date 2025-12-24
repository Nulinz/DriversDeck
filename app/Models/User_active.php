<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User_active extends Authenticatable
{
    protected $table = 'user_active';
    protected $fillable = [
        'name',
        'designation',
        'mail',
        'contact',
        'password',
        'img',
        'status',
        'c_by',
        'created_at',
        'updated_at'
    ];


    // Define any relationships or additional methods if needed
}
