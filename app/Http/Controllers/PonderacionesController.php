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
        DB::transaction(function () use ($datos) {
            foreach ($datos as $id => $valores) {
                $pon = Ponderacion::find($id);
                if ($pon) {
                    $pon->update($valores); 
                }
            }
        });

        return redirect()->back()->with('success', 'Ponderaciones actualizadas correctamente.');
    }
}
