<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Calificacion;
use App\Models\Curso;
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
            $curso = Curso::find($curso_id);
            $asignatura = Asignatura::find($asignatura_id);
            return view('calificaciones.index', compact('calificaciones', 'curso_id', 'curso', 'asignatura'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function modificar(Request $request)
    {
        $datos = $request->input('calificaciones');
        $curso_id = (int)$request->input('curso_id');
        $estudiantesModificados = [];
        DB::transaction(function () use ($datos, &$estudiantesModificados) {
            foreach ($datos as $id => $valores) {
                $cal = Calificacion::find($id);
                if ($cal) {
                    $cal->fill($valores); 
                    if ($cal->isDirty()) {
                        $cal->save(); 
                        $estudiantesModificados[] = $cal->estudiante_id;
                    }
                }
            }
        });
        $ponderacion_id = (int)$request->input('ponderacion_id');
        $ids = implode(',', $estudiantesModificados);
        if (!empty($ids)) {
            $ponderacion_id = $request->input('ponderacion_id'); 
            DB::statement('CALL sp_calcularPromediosAsignatura(?, ?)', [$ponderacion_id, $ids]);
            DB::statement('CALL sp_calcularPromedioGeneralEstudiante(?)', [$curso_id]);
            DB::statement('CALL sp_calcularPromedioCurso(?)', [$curso_id]);
        }
        return redirect()->back()->with('success', 'Calificaciones actualizadas correctamente.');
    }
}
