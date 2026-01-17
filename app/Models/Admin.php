<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_type',
        'position',
        'expertise',
        'college',
        'training_completed',
        'external_user',
    ];

    protected $casts = [
        'expertise' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
