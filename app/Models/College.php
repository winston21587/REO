<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    // The table associated with the model.
    // Laravel assumes 'colleges' by default, which is correct.
    protected $table = 'colleges';

    protected $fillable = [
        'name',
        'color_assign',
        'code',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
