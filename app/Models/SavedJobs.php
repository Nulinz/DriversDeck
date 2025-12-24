<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedJobs extends Model
{
   protected $table = 'saved_jobs';
   protected $fillable = ['type', 'trip_id', 'd_id', 'status', 'c_by'];
}
