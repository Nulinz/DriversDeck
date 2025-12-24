<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $table = 'vacancy';

    protected $fillable = [
        'location',
        'description',
        'contact_number',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function vacancyApplied()
    {
        return $this->hasMany(VacancyApplied::class);
    }

    // Get drivers who applied for this vacancy
    public function appliedDrivers()
    {
        return $this->belongsToMany(Driver::class, 'vacancy_applied', 'vacancy_id', 'user_id');
    }

    // Scope for active vacancies
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for inactive vacancies
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Check if vacancy is active
    public function isActive()
    {
        return $this->status === 'active';
    }

    // Check if vacancy is inactive
    public function isInactive()
    {
        return $this->status === 'inactive';
    }
}