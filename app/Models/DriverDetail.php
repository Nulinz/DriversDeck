<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverDetail extends Model
{
    use HasFactory;

    protected $table = 'driver_details'; // if your table name is different from model name

    protected $fillable = [
        'd_id',
        'c_ad',
        'c_city',
        'c_state',
        'c_pin',
        'about',
        'exp_year',
        'exp_mon',
        'p_com_name',
        'rel_date',
        'com_location',
        'contact_number',
        'current_salary',
        'pf',
        'expert_salary',
        'job_loc',
        'agreement',
        'years',
        'status',
    ];

    // relationship to Driver model
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'd_id');
    }
}
