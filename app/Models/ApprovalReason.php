<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalReason extends Model
{
    protected $table = 'approval_reasons';

    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'reason',
        'admin_id'
    ];

    // Get the user (Driver or Corporate)
    public function user()
    {
        if (in_array($this->user_type, ['acting', 'permanent'])) {
            return $this->belongsTo(Driver::class, 'user_id');
        }
        return $this->belongsTo(Corporate::class, 'user_id');
    }
}