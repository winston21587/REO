<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // ✅ Add 'user_id' and 'appointment_date' here
    protected $fillable = [
        'research_title_id',
        'user_id',
        'appointment_date',
        'stage',
        'status', // If you have a status column
        'remarks' // If you have remarks
    ];

    // Optional: Define relationships if you haven't already
    public function research()
    {
        return $this->belongsTo(Research_title::class, 'research_title_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}