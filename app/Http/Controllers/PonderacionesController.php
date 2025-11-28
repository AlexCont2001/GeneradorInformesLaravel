<?php

namespace App\Http\Controllers;

use App\Models\Ponderacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function modificar(Request $request)
    {   
        $datos = $request->input('ponderaciones');
        $curso_id = (int)$request->input('curso_id');
        $ponderacionesModificadas = [];
        DB::transaction(function () use ($datos, &$ponderacionesModificadas) {
            foreach ($datos as $id => $valores) {
                $pon = Ponderacion::find($id);
                if ($pon) {
                    $pon->fill($valores); 
                    if($pon->isDirty()){
                        $pon->save(); 
                        $ponderacionesModificadas[] = $pon->id;
                    }
                }
            }
        });
        $ponderacion_ids = implode(',', $ponderacionesModificadas);
        if (!empty($ponderacion_ids)) {
            DB::statement('CALL sp_calcularPromediosPonderacion(?)', [$ponderacion_ids]);
            DB::statement('CALL sp_calcularPromedioGeneralEstudiante(?)', [$curso_id]);
            DB::statement('CALL sp_calcularPromedioCurso(?)', [$curso_id]);
        }

        return redirect()->back()->with('success', 'Ponderaciones actualizadas correctamente.');
    }
}
