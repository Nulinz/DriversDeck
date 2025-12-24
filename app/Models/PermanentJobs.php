<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermanentJobs extends Model
{
    protected $table = 'permanent_jobs';

    protected $fillable = [
        'job_type',
        'veh_type',
        'veh_name',
        'min_exp',
        'max_exp',
        'job_location',
        'join_date',
        'min_salary',
        'max_salary',
        'accommodation',
        'food',
        'aggrement',
        'a_years',
        'description',
        'status',
        'c_by'

    ];

    public function corporate()
    {
        return $this->belongsTo(Corporate::class, 'c_by', 'id');
    }
    public function driver()
    {
        return $this->hasMany(Driver::class, 'job_id', 'id');
    }

    public function subApplies()
    {
        return $this->hasMany(SubApplied::class, 'p_id');
    }
}
