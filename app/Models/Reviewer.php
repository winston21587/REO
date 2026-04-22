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
        'show_researcher_identity',
    ];

    protected $casts = [
        'expertise' => 'array',
        'training_completed' => 'boolean',
        'external_user' => 'boolean',
        'show_researcher_identity' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
