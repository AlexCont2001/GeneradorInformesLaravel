<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudiantesController extends Controller
{
    public function index(Request $request){
        try {
            $curso_id = $request->input('curso_id');
            //dd("aca");
            $query = Estudiante::query();
            if ($curso_id) {
                $query->where('curso_id', $curso_id);
            }
            $estudiantes = $query->orderBy('id', 'ASC')->get();
            return view('estudiantes.index', compact('estudiantes', 'curso_id'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function crear(Request $request){
        $curso_id = $request->input('curso_id');
        return view('estudiantes.crear', compact('curso_id'));
    }

    public function guardar(Request $request){
        $request->validate([
            'nombres' => 'required|string|min:2|max:255',
            'apellido_paterno' => 'required|string|min:2|max:255',
            'apellido_materno' => 'required|string|min:2|max:255',
            'rut' => 'required|string|min:2|max:15',
            'promedio' => 'required|numeric|min:0|max:7',
            'curso_id' => 'required|numeric|min:1'
        ]);
        Estudiante::create($request->all());
        return redirect()->route('estudiantes.index',['curso_id'=>$request->curso_id])->with('success', 'Estudiante agregado correctamente!');
    }

    public function editar(Request $request, Estudiante $estudiante){
        $curso_id = $request->input('curso_id');
        return view('estudiantes.editar', compact('estudiante','curso_id'));
    }

    public function modificar(Request $request, Estudiante $estudiante){
        $request->validate([
            'nombres' => 'required|string|min:2|max:255',
            'apellido_paterno' => 'required|string|min:2|max:255',
            'apellido_materno' => 'required|string|min:2|max:255',
            'rut' => 'required|string|min:2|max:15',
            'promedio' => 'required|numeric|min:0|max:7',
            'curso_id' => 'required|numeric|min:1'
        ]);
        try {
            $estudiante->update($request->all());
            return redirect()->route('estudiantes.index',['curso_id'=>$estudiante->curso_id])->with('success', 'Estudiante modificado correctamente!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function eliminar(Estudiante $estudiante){
        try {
            $curso_id = $estudiante->curso_id;
            $estudiante->delete();
            return redirect()->route('estudiantes.index',['curso_id'=>$curso_id])->with('success', 'Estudiante eliminado correctamente!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
