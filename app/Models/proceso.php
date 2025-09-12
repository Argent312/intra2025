<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class proceso extends Model
{
    protected $fillable = [
        'nombre_proceso', 'codigo', 'version', 'estado', 'tipo',
    ];

    
}
