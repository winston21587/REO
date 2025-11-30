<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'section',
        'content',
        'order',
        'protocol_id',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
