<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador Informes</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body
    style="background-image: url('{{ asset('images/background.jpg') }}'); background-size: cover; background-position: center; height: 400px;">
    <div>
        <div class="container-fluid">
            <div class="row mt-2 mb-4 p-2">
                <div class="col-4">
                    <a href="" class="btn border border-dark ">Home</a>
                </div>
                <div class="col-1">
                    <a href="{{route('estudiantes.index',['curso_id'=>1])}}" class="btn btn-primary">Primero</a>
                </div>
                <div class="col-1">
                    <a href="{{route('estudiantes.index',['curso_id'=>2])}}" class="btn btn-warning">Segundo</a>
                </div>
                <div class="col-1">
                    <a href="{{route('estudiantes.index',['curso_id'=>3])}}" class="btn btn-secondary">Tercero</a>
                </div>
                <div class="col-1">
                    <a href="{{route('estudiantes.index',['curso_id'=>4])}}" class="btn btn-info">Cuarto</a>
                </div>
                <div class="col-1">
                    <a href="{{route('estudiantes.index',['curso_id'=>5])}}" class="btn btn-danger">Quinto</a>
                </div>
                <div class="col-1">
                    <a href="{{route('estudiantes.index',['curso_id'=>6])}}" class="btn btn-success">Sexto</a>
                </div>
                <div class="col-1">
                    <a href="{{route('estudiantes.index',['curso_id'=>7])}}" class="btn btn-light border border-dark">Séptimo</a>
                </div>
                <div class="col-1">
                    <a href="{{route('estudiantes.index',['curso_id'=>8])}}" class="btn btn-dark">Octavo</a>
                </div>
            </div>
            <div class="row justify-content-center align-items-center" style="height: 650px;">
                <div class="col-12 text-center">
                    <h1>Bienvenido al Generador de Informes de Estudiantes</h3>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
