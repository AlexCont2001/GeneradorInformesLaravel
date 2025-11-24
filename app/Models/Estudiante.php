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
        'promedio',
        'curso_id'
    ];
    public $timestamps = false;
}
