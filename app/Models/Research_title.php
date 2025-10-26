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
        'user_id',
        'Adviser',
    ];
    // Relationship: each research title belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
{
    return $this->belongsTo(User::class, 'user_id');
}

    // Relationship: each research title belongs to a researcher file
public function files()
{
    return $this->belongsToMany(
            Researcher_files::class,
                'research_title_files',
      'research_title_id',
      'researcher_file_id')
    ->withTimestamps();
}

public function appointment()
{
    return $this->hasMany(Appointment::class, 'research_title_id');
}

}
