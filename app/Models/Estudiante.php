<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $fillable = [
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'rut',
        'promedio'
    ];
    public $timestamps = false;
    
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'estudiante_id'); 
    }

}
