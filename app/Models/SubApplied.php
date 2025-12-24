<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubApplied extends Model
{
   protected $table = 'sub_applied';
   protected $fillable = ['p_id', 'd_id', 'status', 'c_on', 'c_by'];

   // SubApplied.php
public function driver()
{
    return $this->belongsTo(Driver::class, 'd_id', 'id');
}

  public function trip()
    {
        return $this->belongsTo(PermanentJobs::class, 'p_id', 'id');
    }

}
