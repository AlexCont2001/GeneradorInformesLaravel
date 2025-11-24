<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ponderacion extends Model
{
    protected $table = 'ponderaciones';
    public $timestamps = false;
    
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'ponderacion_id'); 
    }
}
