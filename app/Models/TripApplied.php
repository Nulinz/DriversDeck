<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripApplied extends Model
{
   protected $table = 'trip_applied';
protected $fillable = ['trip_id', 'd_id', 'salary_perday', 'wait_charge', 'food', 'start_time', 'end_time', 'start_loc', 'end_loc', 'crnt_loc', 'trip_code', 'status', 'report_sts', 'reason', 'remarks', 'c_on', 'c_by'];


// TripApplied.php

  public function driver()
    {
        return $this->belongsTo(Driver::class, 'd_id', 'id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }



}
