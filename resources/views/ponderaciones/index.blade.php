@extends('layouts.app')

@section('content')
    <div class="row">

        <div class="col-12">
            <h3 class="text-center mb-2 mt-2">Listado Ponderaciones</h3>
            <h6 class="text-center mb-3">[{{$curso_id}}° {{  $ponderaciones[0]->curso->nombre}}]</h6>
            <div class="row justify-content-center">
                <div class="col-10">
                    <form action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="text-center  mb-4" onclick="return confirm('¿¿Estás seguro ??')">
                            <button type="submit" class="btn btn-success">Guardar todas las ponderaciones</button>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table table-dark">
                                    <th>Asignatura</th>
                                    <th>N1 %</th>
                                    <th>N2 %</th>
                                    <th>N3 %</th>
                                    <th>N4 %</th>
                                    <th>N5 %</th>
                                    <th>N6 %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ponderaciones as $pon)
                                    <tr>
                                        <td>
                                            {{ $pon->asignatura->nombre ?? '' }}
                                        </td>
                                        <td>
                                            <input type="number" name="ponderaciones[{{ $pon->id }}][n1_per]"
                                                value="{{ $pon->n1_per }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="ponderaciones[{{ $pon->id }}][n2_per]"
                                                value="{{ $pon->n2_per }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="ponderaciones[{{ $pon->id }}][n3_per]"
                                                value="{{ $pon->n3_per }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="ponderaciones[{{ $pon->id }}][n4_per]"
                                                value="{{ $pon->n4_per }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="ponderaciones[{{ $pon->id }}][n5_per]"
                                                value="{{ $pon->n5_per }}" min="0" max="7" step="0.01"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="ponderaciones[{{ $pon->id }}][n6_per]"
                                                value="{{ $pon->n6_per }}" min="0" max="7" step="0.01"
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
