<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $table = 'license'; // optional if table name is default plural 'licenses'
    
    // Fillable columns (optional)
    protected $fillable = ['d_id', 'cov'];
}
