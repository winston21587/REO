<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Researcher_files extends Model
{
    use HasFactory;

    protected $table = 'researcher_files';

    protected $fillable = [
        'research_title_id', // This matches the foreign key column
        'filename',
        'filepath',
        'filetype',   
        'uploaded_by',
        'category',
        'revision_number'
    ];

    public function research()
    {
        return $this->belongsTo(Research_title::class, 'research_title_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}