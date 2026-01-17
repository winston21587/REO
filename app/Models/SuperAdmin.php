<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',   // not really sure abt this
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
