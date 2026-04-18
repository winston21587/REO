<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewerFileRemark extends Model
{
    protected $fillable = [
        'research_title_id',
        'reviewer_id',
        'researcher_file_id',
        'remarks',
    ];

    public function researchTitle()
    {
        return $this->belongsTo(Research_title::class, 'research_title_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function file()
    {
        return $this->belongsTo(researcher_files::class, 'researcher_file_id');
    }
}
