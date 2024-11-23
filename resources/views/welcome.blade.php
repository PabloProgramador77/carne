<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Carniceria La Higienica</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head>
    <body class="d-flex h-100 text-center text-bg-dark">
        <div class="fluid-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <header class="mb-auto">
                <div>
                    <img src="{{ asset('/img/logo-min-removebg-preview.png') }}" class="float-md-middle rounded-pill p-2 border border-secondary" width="200px" height="auto" alt="" srcset="">
                </div>
            </header>

            <main class="py-5 my-2">
                <h1>Carniceria La Higienica</h1>
                <p class="lead">Centro de Administración y Registro de Notas</p>
                <p class="lead">
                    @if (Route::has('login'))
                        <nav class="-mx-3 flex flex-1 justify-end">
                            @auth
                                <a href="{{ url('/') }}" class="btn btn-info shadow rounded">
                                    <b>Continuar</b>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary shadow rounded">
                                    Iniciar Sesión
                                </a>
                            @endauth
                        </nav>
                    @endif
                </p>
            </main>

            <footer class="mt-auto text-white-50">
                <p class="fs-6">Diseñado y desarrollado por <a href="https://pabloprogramador.com.mx" class="text-white">PabloProgramador</a> para <u>Carniceria La Higienica</u></p>
                <span class="fs-6 text-light text-center d-block">Versión 1.1.3</span>
            </footer>
        </div>
    </body>
</html>
