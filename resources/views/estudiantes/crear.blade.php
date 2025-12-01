@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-2">
        <a href="{{ route('estudiantes.index', ['curso_id' => $curso_id]) }}" class="btn btn-primary">Volver</a>

    </div>
    <div class="col-10"></div>
</div>
<div class="row ">
    <div class="col-3"></div>
    <div class="col-6">

        <h3 class="text-center ">Crear Estudiante</h3>
        <form action="{{route('estudiantes.guardar')}}" method="POST">
            @csrf
            <div class="form-group mb-4">
                <label for="nombres" class="mb-2">Nombres</label>
                <input type="text" class="form-control @error('nombres') is-invalid @enderror" id="nombres"  name="nombres" value="{{old('nombres')}}">
                @error('nombres')
                    <div class="invalid-feedback">{{$message}}</div>
                @enderror    
            </div>
            <div class="form-group mb-4">
                <label for="apellido_paterno" class="mb-2">Apellido Paterno</label>
                <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror" id="apellido_paterno" name="apellido_paterno" value="{{old('apellido_paterno')}}">
                @error('apellido_paterno')
                    <div class="invalid-feedback">{{$message}}</div>
                @enderror  
            </div>
            <div class="form-group mb-4">
                <label for="apellido_materno" class="mb-2">Apellido Materno</label>
                <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror" id="apellido_materno" name="apellido_materno" value="{{old('apellido_materno')}}">
                @error('apellido_materno')
                    <div class="invalid-feedback">{{$message}}</div>
                @enderror  
            </div>
            <div class="form-group mb-4">
                <label for="rut" class="mb-2">Rut</label>
                <input type="text" class="form-control @error('rut') is-invalid @enderror" id="rut" name="rut" aria-describedby="rutHelp" value="{{old('rut')}}">
                <small id="rutHelp" class="form-text text-muted">Debe ingresar rut completo con puntos y guión</small>
                @error('rut')
                    <div class="invalid-feedback">{{$message}}</div>
                @enderror  
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control" id="promedio" name="promedio" value=0>
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control" id="curso_id" name="curso_id" value={{$curso_id}}>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-success" type="submit">Guardar</button>
            </div>
        </form>
    </div>
    <div class="col-3"></div>
</div>

@endsection