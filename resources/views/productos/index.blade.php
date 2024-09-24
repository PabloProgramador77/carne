@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-5">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-drumstick-bite"></i> Mis Productos</h1>
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
            </div>
            
            <div class="col-lg-5 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-drumstick-bite"></i> Productos</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-2 my-2">
                <x-adminlte-button theme="primary" data-toggle="modal" data-target="#modalNuevo" icon="fas fa-plus-circle" title="Nuevo producto" label=""></x-adminlte-button>
            </div>

            <div class="col-lg-12">
                <small class="rounded fs-5 fw-semibold text-center bg-warning d-block">Agregar productos nuevos con el botón <i class="fas fa-plus-circle"></i> o administra los existentes con sus botones correspondientes.</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $productos ) > 0 )

                @php
                    $heads = ['Folio', 'Nombre', 'Descripción', ''];
                @endphp
                <x-adminlte-datatable id="contenedorProductos" :heads="$heads" theme="light" striped hoverable beautify compressed>
                    @foreach( $productos as $producto )
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->descripcion ? $producto->descripcion : 'Sin descripción' }}</td>
                            <td>
                                <x-adminlte-button class="shadow editar" theme="info" data-id="{{ $producto->id }}" data-toggle="modal" data-target="#modalEditar" data-value="{{ $producto->nombre }}, {{ $producto->descripcion }}" icon="fas fa-edit" ></x-adminlte-button>
                                <x-adminlte-button class="shadow borrar" theme="danger" data-id="{{ $producto->id }}" data-value="{{ $producto->nombre }}" icon="fas fa-trash"></x-adminlte-button>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                
            @else
                <tr>
                    <td colspan="4" text-color="danger"><i class="fas fa-info-circle"></i> Sin productos registrados.</td>
                </tr>
            @endif

        </div>

    </section>

    @include('productos.nuevo')
    @include('productos.editar')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/productos/create.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/productos/read.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/productos/update.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/productos/delete.js') }}" type="text/javascript"></script>

@stop