<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class union extends Model
{
    protected $fillable = [
        'procesos_id',
        'roles_id',
        'directions_id',
        'area_id',
    ];

}
