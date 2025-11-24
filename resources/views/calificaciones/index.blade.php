@extends('layouts.app')

@section('content')
    <div class="row">
        @session('success')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ $value }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession
        <div class="col-12">
            <h3 class="text-center mb-2 mt-2">Listado calificaciones</h3>
            <h6 class="text-center mb-3">[{{$curso_id}}° {{  $curso->nombre}}]</h6>
            <div class="row justify-content-center">
                <div class="col-10">
                    <form action="{{ route('calificaciones.modificar') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="text-center  mb-4" onclick="return confirm('¿¿Estás seguro ??')">
                            <button type="submit" class="btn btn-success">Guardar todas las calificaciones</button>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table table-dark">
                                    <th>Estudiante</th>
                                    <th>N1</th>
                                    <th>N2</th>
                                    <th>N3</th>
                                    <th>N4</th>
                                    <th>N5</th>
                                    <th>N6</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($calificaciones as $cal)
                                    <tr>
                                        <td>
                                            {{ $cal->estudiante->nombres ?? '' }}
                                            {{ $cal->estudiante->apellido_paterno ?? '' }}
                                            {{ $cal->estudiante->apellido_materno ?? '' }}
                                        </td>
                                        <td>
                                            <input type="number" name="calificaciones[{{ $cal->id }}][n1]"
                                                value="{{ $cal->n1 }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="calificaciones[{{ $cal->id }}][n2]"
                                                value="{{ $cal->n2 }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="calificaciones[{{ $cal->id }}][n3]"
                                                value="{{ $cal->n3 }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="calificaciones[{{ $cal->id }}][n4]"
                                                value="{{ $cal->n4 }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="calificaciones[{{ $cal->id }}][n5]"
                                                value="{{ $cal->n5 }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="calificaciones[{{ $cal->id }}][n6]"
                                                value="{{ $cal->n6 }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
