<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    protected $table = 'notify';

    protected $fillable = [
        'f_id',
        'prime_table',
        'type',
        'cat',
        'title',
        'body',
        'status',
        'c_by'
    ];
}
