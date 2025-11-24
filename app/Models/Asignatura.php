<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    //
    public function ponderaciones()
    {
        return $this->hasMany(Ponderacion::class, 'asignatura_id'); 
    }
}
