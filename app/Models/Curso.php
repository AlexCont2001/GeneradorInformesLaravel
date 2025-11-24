<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    public function ponderaciones()
    {
        return $this->hasMany(Ponderacion::class, 'curso_id');
    }
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'curso_id');
    }
}
