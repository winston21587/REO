<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Researcher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'college',
        'department',
        'program',
        'institute',
        'external_user',
        'contact',
        'expertise',
        'training_completed',
    ];

    protected $casts = [
        'external_user' => 'boolean',
        // 'expertise' => 'array',
        'training_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function researchTitles()
    {
        return $this->hasMany(Research_title::class);
    }
}
