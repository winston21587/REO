<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TitleLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function research_title()
    {
        return $this->belongsTo(Research_title::class, 'research_title_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
