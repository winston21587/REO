<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'college_id',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
