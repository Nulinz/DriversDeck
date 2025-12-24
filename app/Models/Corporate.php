<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Corporate extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'corporate';

    protected $fillable = [
        'type',
        'name',
        'gender',
        'location',
        'district',
        'ref_code',        // Added this field
        'otp',
        'c_type',
        'c_name',
        'contact',
        'mail',
        'c_num',
        'a_num',
        'c_mail',
        'ad_1',
        'ad_2',
        'city',
        'state',
        'pin',
        'pan',
        'gst',
        'no_veh',
        'no_driver',
        'no_vac',
        'subscription',    // Added this field
        'logo',
        'status',
        'active_status',
        'c_by'            // Added this field
    ];

    // Relationship with trips
    public function trips()
    {
        return $this->hasMany(Trip::class, 'c_by', 'id');
    }

    // Relationship with subscription
    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'f_id', 'id');
    }

    // Relationship with subscriptions (multiple)
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'f_id', 'id');
    }

    // Scope for active corporates only
    public function scopeActive($query)
    {
        return $query->where('active_status', 'active');
    }

    // Scope for inactive corporates only
    public function scopeInactive($query)
    {
        return $query->where('active_status', 'inactive');
    }

    // Check if corporate is active
    public function isActive()
    {
        return $this->active_status === 'active';
    }

    // Check if corporate is inactive
    public function isInactive()
    {
        return $this->active_status === 'inactive';
    }

    // Check if subscription is active
    public function hasActiveSubscription()
    {
        return $this->subscription && 
               $this->subscription->status === 'active' && 
               $this->subscription->exp_date > now();
    }

    // Get subscription status
    public function getSubscriptionStatus()
    {
        if (!$this->subscription) {
            return 'No Subscription';
        }

        if ($this->subscription->exp_date < now()) {
            return 'Expired';
        }

        return ucfirst($this->subscription->status);
    }
}