<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalificacionesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $curso_id = $request->input('curso_id');
            $asignatura_id = $request->input('asignatura_id');
            $calificaciones = Calificacion::with(['ponderacion', 'estudiante'])
                ->whereHas('estudiante', function ($q) use ($curso_id) {
                    $q->where('curso_id', $curso_id);
                })
                ->whereHas('ponderacion', function ($q) use ($asignatura_id) {
                    $q->where('asignatura_id', $asignatura_id);
                })
                ->orderBy('id', 'ASC')
                ->get();
            return view('calificaciones.index', compact('calificaciones', 'curso_id'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function modificar(Request $request)
    {
        $datos = $request->input('calificaciones');

        DB::transaction(function () use ($datos) {
            foreach ($datos as $id => $valores) {
                $cal = Calificacion::find($id);
                if ($cal) {
                    $cal->update($valores); 
                }
            }
        });

        return redirect()->back()->with('success', 'Calificaciones actualizadas correctamente.');
    }
}
