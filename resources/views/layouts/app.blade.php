<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <main class="py-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-2">
                        <nav class="navbar navbar-expand-lg bg-body-tertiary">
                            <div class="container-fluid">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Calificaciones
                                            </a>
                                            <ul class="dropdown-menu p-3" style="min-width: 600px;">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>1])}}">Matemáticas</a>
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>2])}}">Lenguaje</a>
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>3])}}">Historia</a>
                                                    </div>
                                                    <div class="col-3">
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>4])}}">Ciencias Naturales</a>
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>5])}}">Inglés</a>
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>6])}}">Tecnología</a>
                                                    </div>
                                                    <div class="col-3">
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>7])}}">Educación Física</a>
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>8])}}">Taller</a>
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>9])}}">Orientación</a>
                                                    </div>
                                                    <div class="col-3">
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>10])}}">Religión</a>
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>11])}}">Música</a>
                                                        <a class="dropdown-item" href="{{route('calificaciones.index',['curso_id'=>$curso_id,'asignatura_id'=>12])}}">Artes Visuales</a>
                                                    </div>
                                                </div>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="col-2">
                        <nav class="navbar navbar-expand-lg bg-body-tertiary">
                            <div class="container-fluid">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Ponderaciones
                                            </a>
                                            <ul class="dropdown-menu p-3" style="min-width: 600px;">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id])}}">Matemáticas</a>
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id])}}">Lenguaje</a>
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id])}}">Historia</a>
                                                    </div>
                                                    <div class="col-3">
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id])}}">Ciencias Naturales</a>
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id])}}">Inglés</a>
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id])}}">Tecnología</a>
                                                    </div>
                                                    <div class="col-3">
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id])}}">Educación Física</a>
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id])}}">Taller</a>
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id])}}">Orientación</a>
                                                    </div>
                                                    <div class="col-3">
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id,])}}">Religión</a>
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id,])}}">Música</a>
                                                        <a class="dropdown-item" href="{{route('ponderaciones.index',['curso_id'=>$curso_id,])}}">Artes Visuales</a>
                                                    </div>
                                                </div>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
