<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacancyApplied extends Model
{
    use HasFactory;

    protected $table = 'vacancy_applied';

    protected $fillable = [
        'user_id',
        'vacancy_id',
         'status',
        'rejection_reason',
    ];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

   public function driver()
    {
        return $this->belongsTo(Driver::class, 'user_id', 'id');
    }
}
