<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_required',
        'is_multiple',
        'file_type',
        'is_viewable_for_reviewer',
        'is_downloadable_for_reviewer'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_multiple' => 'boolean',
        'is_viewable_for_reviewer' => 'boolean',
        'is_downloadable_for_reviewer' => 'boolean'
    ];
}
