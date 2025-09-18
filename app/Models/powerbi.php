<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class powerbi extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'url',
    ];
}
