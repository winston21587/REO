<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Researcher_files extends Model
{
    use HasFactory;
    protected $table = 'researcher_files';
    protected $fillable = ['filename', 'filepath', 'filetype'];
}