<?php

namespace App\Http\Controllers;

use App\Models\Ponderacion;
use Illuminate\Http\Request;

class PonderacionesController extends Controller
{
    public function index(Request $request)
    {
        $curso_id = $request->input('curso_id');
        $ponderaciones = Ponderacion::with(['curso', 'asignatura'])
            ->where('curso_id', $curso_id)
            ->orderBy('id', 'ASC')
            ->get();
        return view('ponderaciones.index',compact('ponderaciones','curso_id'));
    }
}
