<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscription';

 protected $fillable = [
        'f_id',
        'type',
        'plan',
        't_id',
        'amount',
        'paid_sts',
        'exp_date',
        'status',
        'c_by',
        'payment_screenshot',
        ];
    protected $dates = [
        'exp_date',
        'created_at',
        'updated_at'
    ];

    // Relationship with Corporate
    public function corporate()
    {
        return $this->belongsTo(Corporate::class, 'f_id', 'id');
    }
}