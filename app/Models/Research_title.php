<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Research_title extends Model
{
    use HasFactory;
        // Explicitly specify the table name
    protected $table = 'research_title_information';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'Study_Protocol_title',
        'Research_Category',
        'Review_Type',
        'Created_by',
        'Official_Receipt_Number',
        'researcher_id',  // since researher belongs to user (one to one) just store it onto the user side
        'Adviser',
    ];
    // Relationship: each research title belongs to a researcher
    public function researcher()
    {
        return $this->belongsTo(Researcher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: each research title belongs to a researcher file
public function files()
{
    return $this->belongsToMany(
            researcher_files::class,
                'research_title_files',
      'research_title_id',
      'researcher_file_id')
    ->withTimestamps();
}

public function adminFiles()
{
    return $this->hasMany(researcher_files::class, 'research_title_id');
}

public function appointment()
{
    return $this->hasMany(Appointment::class, 'research_title_id');
}

    public function revisionLogs()
    {
        return $this->hasMany(RevisionLog::class, 'research_title_id')->orderBy('created_at', 'desc');
    }

    public function feedbacks()
    {
        return $this->hasMany(SubmissionFeedback::class, 'research_title_id')->orderBy('created_at', 'desc');
    }

    public function titleLogs()
    {
        return $this->hasMany(TitleLog::class, 'research_title_id')->orderBy('created_at', 'desc');
    }

    protected static function booted()
    {
        static::created(function ($researchTitle) {
            TitleLog::create([
                'research_title_id' => $researchTitle->id,
                'user_id' => auth()->id(), // Works if a user is logged in
                'action' => 'Submission Created',
                'description' => 'A new research title submission was created.'
            ]);
        });

        static::updated(function ($researchTitle) {
            $changes = $researchTitle->getChanges();
            
            // Ignore if only 'updated_at' changed
            if (count($changes) === 1 && isset($changes['updated_at'])) {
                return;
            }

            foreach ($changes as $key => $newValue) {
                if ($key === 'updated_at') continue;

                $oldValue = $researchTitle->getOriginal($key);

                // Create readable action name based on the column that changed
                $action = 'Updated ' . str_replace('_', ' ', $key);
                if (strtolower($key) === 'status') {
                    $action = 'Status Changed';
                }

                TitleLog::create([
                    'research_title_id' => $researchTitle->id,
                    'user_id' => auth()->id(),
                    'action' => $action,
                    'description' => "Changed from '{$oldValue}' to '{$newValue}'."
                ]);
            }
        });
    }
}
