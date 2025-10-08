<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReoUsers extends Model
{
    protected $fillable = [
        'email',
        'name',
        'password',
        'role',
        'contact_number',

    ];

//    protected $guarded = ['id']; can also do but it only makes the id unfillable and not everything else

}
