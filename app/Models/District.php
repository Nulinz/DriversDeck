<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel naming convention)
    protected $table = 'district';

    // Primary key (optional if 'id')
    protected $primaryKey = 'id';

    // Mass assignable fields
    protected $fillable = [
        'district',
        'status',
    ];

    // Automatically handle created_at & updated_at
    public $timestamps = true;

    /**
     * Scope to get only active districts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    
    
}
