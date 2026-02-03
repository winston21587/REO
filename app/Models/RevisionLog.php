<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_title_id',
        'user_id',
        'message',
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
