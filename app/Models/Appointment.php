<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_title_id',
        'appointed_by',
        'appointed_at',
        'notes',
    ];

    protected $dates = [
        'appointed_at',
        'created_at',
        'updated_at',
    ];

    public function researchTitle()
    {
        return $this->belongsTo(Research_title::class, 'research_title_id');
    }

    public function appointedBy()
    {
        return $this->belongsTo(User::class, 'appointed_by');
    }
}
