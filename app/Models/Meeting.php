<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'meeting_date',
        'venue',
        'status',
        'agenda_status',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
    ];

    public function agendaItems()
    {
        return $this->hasMany(AgendaItem::class)->orderBy('order');
    }
}
