<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Researcher_files extends Model
{
    use HasFactory;

    protected $table = 'researcher_files';

    protected $fillable = [
        //'research_title_id', // This matches the foreign key column
        'filename',
        'file_path',
        'file_type',   
        'uploaded_by'  
    ];

    // ✅ CORRECT RELATIONSHIP: A file belongs to ONE research title
    public function research()
    {
        return $this->belongsTo(Research_title::class, 'research_title_id');
    }
}