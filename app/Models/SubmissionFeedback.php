<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionFeedback extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'missing_requirements' => 'array',
    ];

    public function researchTitle()
    {
        return $this->belongsTo(Research_title::class, 'research_title_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
