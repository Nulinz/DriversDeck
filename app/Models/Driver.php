<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'driver';

   protected $fillable = [
    'id',
    'type',
    'name',
    'phone',
    'otp',
    'l_no',
    'location',
    'district',
    'img',
    'subscription',
    'gender',
    'marital_status',
    'b_group',
    'ad_num',
    'ref_code',
    'status',
    'c_by'
];

   public function tripApplied()        
    {
        return $this->hasMany(TripApplied::class, 'd_id', 'id'); // Assuming driver.id = trip_applied.d_id
    }

    public function details()
{
    return $this->hasOne(DriverDetail::class, 'd_id', 'id');
}


public function appliedTrips()
{
    return $this->hasMany(TripApplied::class, 'd_id', 'id');
}
public function typeChangeRequests()
    {
        return $this->hasMany(DriverTypeChangeRequest::class, 'driver_id', 'id');
    }

    // Get latest type change request
    public function latestTypeChangeRequest()
    {
        return $this->hasOne(DriverTypeChangeRequest::class, 'driver_id', 'id')->latestOfMany();
    }

    // Get pending type change request
    public function pendingTypeChangeRequest()
    {
        return $this->hasOne(DriverTypeChangeRequest::class, 'driver_id', 'id')
                    ->where('request_status', 'pending');
    }

    // Check if driver has pending type change request
    public function hasPendingTypeChangeRequest()
    {
        return $this->typeChangeRequests()->where('request_status', 'pending')->exists();
    }
    public function isActive()
    {
        return $this->active_status === 'active';
    }

    // Check if driver is inactive
    public function isInactive()
    {
        return $this->active_status === 'inactive';
    }
        public function license()
{
    return $this->hasOne(License::class, 'd_id');
}
public function approvalReasons()
{
    return $this->hasMany(ApprovalReason::class, 'user_id')
                ->where('user_type', $this->type);
}

public function latestApprovalReason()
{
    return $this->hasOne(ApprovalReason::class, 'user_id')
                ->where('user_type', $this->type)
                ->latest();
}
}
