<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificaciones';

    protected $fillable = [
        'n1', 'n2', 'n3', 'n4', 'n5', 'n6', 'promedio', 'estudiante_id', 'curso_id'
    ];
    
    public $timestamps = false;

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id'); 
    }

    public function ponderacion()
    {
        return $this->belongsTo(Ponderacion::class, 'ponderacion_id');
    }
}
