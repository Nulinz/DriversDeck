<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $table = 'trip';

    protected $fillable = [
        'trip_img',
        'st_loc',
        'st_dest',
        'st_city',
        'end_city',
        'st_cord',
        'end_cord',
        'dest_cord',
        'title',
        'con_number',
        'alter_number',
        'st_date',
        'end_date',
        'st_time',
        'no_days',
        'veh_type',
        'veh_name',
         'veh_number',
        'driver_type',
        'd_type',
        'trip_img',
        'status',
        'c_by'
    ];

    // public function driver()
    // {
    //     return $this->belongsTo(Driver::class);
    // }

    // public function corporate()
    // {
    //     return $this->belongsTo(Corporate::class);
    // }

    public function corporate()
    {
        return $this->belongsTo(Corporate::class, 'c_by', 'id');
    }

    public function hiredApplication()
    {
        return $this->hasOne(TripApplied::class)->whereIn('status', ['Hired', 'Start', 'End']);
    }

    public function appliedDrivers()
    {
        return $this->hasMany(TripApplied::class, 'trip_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id'); // Use correct column name
    }
}
