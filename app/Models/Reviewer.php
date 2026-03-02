<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
    protected $fillable = [
        'user_id',
        'college',
        'expertise',
        'training_completed',
        'external_user',
    ];

    protected $casts = [
        'expertise' => 'array',
        'training_completed' => 'boolean',
        'external_user' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
