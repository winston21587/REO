<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadableResource extends Model
{
    protected $fillable = [
        'code',
        'title',
        'description',
        'file_path',
        'file_size',
        'file_extension',
        'is_mandatory',
    ];
}
