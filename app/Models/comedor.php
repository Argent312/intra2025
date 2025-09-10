<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class comedor extends Model
{
    protected $fillable = [
        'nombre_reservante', 'motivo_reunion', 'fecha_inicio', 'fecha_fin',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
    ];
}
