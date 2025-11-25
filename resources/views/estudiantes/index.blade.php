@extends('layouts.app')

@section('content')
    <div class="row">
        @session('success')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession
        <div class="col-12">
            <h3 class="text-center mb-5">Listado de Estudiantes</h3>
            <div class="row mb-3">
                <div class="col-1"></div>
                <div class="col-3">
                    <a href="{{ route('estudiantes.crear', ['curso_id' => $curso_id]) }}" class="btn btn-success">Nuevo
                        Estudiante</a>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-10">
                    <table class="table table-bordered  table-striped">
                        <thead class="table-dark">
                            <tr class="text-white">
                                <th>ID</th>
                                <th>Nombres</th>
                                <th>Apellido Paterno</th>
                                <th>Apellido Materno</th>
                                <th>Rut</th>
                                <th>Promedio</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($estudiantes as $estudiante)
                                <tr>
                                    <td>{{ $estudiante->id }}</td>
                                    <td>{{ $estudiante->nombres }}</td>
                                    <td>{{ $estudiante->apellido_paterno }}</td>
                                    <td>{{ $estudiante->apellido_materno }}</td>
                                    <td>{{ $estudiante->rut }}</td>
                                    <td>{{ $estudiante->promedio }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('estudiantes.editar', ['estudiante' => $estudiante->id, 'curso_id' => $curso_id]) }}"
                                            class="btn btn-warning">Edit</a>
                                        <form action="{{ route('estudiantes.eliminar', ['estudiante' => $estudiante->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('¿¿Estás seguro que quieres eliminar este estudiante??')">Eliminar</button>
                                        </form>
                                        <a href="" class="btn btn-primary">Exportar Word</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay estudiantes registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
