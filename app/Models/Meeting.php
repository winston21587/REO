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

    public function attendees()
    {
        return $this->hasMany(MeetingAttendee::class);
    }

    /**
     * Scope query to meetings relevant to a specific reviewer user ID.
     */
    public function scopeForReviewer($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereHas('attendees', function ($aq) use ($userId) {
                $aq->where('user_id', $userId);
            })->orWhereHas('agendaItems.protocol.reviewers', function ($rq) use ($userId) {
                $rq->where('users.id', $userId);
            })->orWhereHas('agendaItems.protocol', function ($pq) use ($userId) {
                $pq->whereJsonContains('assigned_reviewers', (string) $userId)
                    ->orWhereJsonContains('assigned_reviewers', (int) $userId);
            });
        });
    }

    /**
     * Scope query to meetings containing protocols belonging to a specific researcher ID.
     */
    public function scopeForResearcher($query, $researcherId)
    {
        return $query->whereHas('agendaItems.protocol', function ($pq) use ($researcherId) {
            $pq->where('researcher_id', $researcherId);
        });
    }
}
