<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ponderacion extends Model
{
    protected $table = 'ponderaciones';
    public $timestamps = false;
    
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'ponderacion_id'); 
    }
}
