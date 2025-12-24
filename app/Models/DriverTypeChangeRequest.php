<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DriverTypeChangeRequest extends Model
{
    protected $table = 'driver_type_change_requests';
    
    protected $fillable = [
        'driver_id',
        'previous_type',
        'change_type_to',
        'request_status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with Driver
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    // Scopes for different statuses
    public function scopePending($query)
    {
        return $query->where('request_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('request_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('request_status', 'rejected');
    }

    // Helper method to get formatted date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d-m-Y');
    }
}

