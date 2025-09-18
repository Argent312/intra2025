<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class titulacion extends Model
{
    protected $fillable = [
        'nombre_reservante', 'motivo_reunion','participantes', 'fecha_inicio', 'fecha_fin',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
    ];
}
