<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class proceso extends Model
{
    protected $fillable = [
        'nombre_proceso', 'codigo', 'version', 'estado', 'tipo',
    ];

    public function roles()
        {
        return $this->belongsToMany(Role::class, 'unions', 'procesos_id', 'roles_id');
    }


    public function areas()
    {
        return $this->belongsToMany(Area::class, 'unions', 'procesos_id', 'area_id');
    }

    public function directions()
    {
        return $this->belongsToMany(Direction::class, 'unions', 'procesos_id', 'directions_id');
    }
}
