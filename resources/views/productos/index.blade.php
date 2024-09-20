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

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $productos ) > 0 )
                @foreach( $productos as $producto )
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <x-adminlte-card theme-mode="outline" title="{{ $producto->nombre }}" header-class="rounded-bottom border-primary">
                            <x-slot name="toolsSlot">
                                <img src="{{ asset('/img/carne.jpg') }}" alt="Carne" width="75%" height="auto" class="">
                                @if( $producto->descripcion === NULL || $producto->descripcion === '' )
                                    <small class="fs-6 fw-semibold text-secondary d-block">Sin descripción agregada al producto.</small>
                                @else
                                    <small class="fs-6 fw-semibold text-secondary d-block">{{ $producto->descripcion }}</small>
                                @endif
                                
                            </x-slot>
                            <x-slot name="footerSlot">
                                <x-adminlte-button class="shadow editar" theme="info" icon="fas fa-edit" data-toggle="modal" data-target="#modalEditar" data-id="{{ $producto->id }}" data-value="{{ $producto->nombre }}, {{ $producto->descripcion }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow borrar" theme="danger" icon="fas fa-trash" data-id="{{ $producto->id }}" data-value="{{ $producto->nombre }}"></x-adminlte-button>
                            </x-slot>
                        </x-adminlte-card>
                    </div>
                @endforeach
            @else
                <div class="col-lg-4 mx-auto">
                    <x-adminlte-card theme-mode="outline" theme="danger" title="Sin productos registrados">
                        <x-slot name="toolsSlot">
                            <img src="{{ asset('/img/carne.jpg') }}" alt="Carne" width="100%" height="auto" class="">
                            <small class="bg-danger p-1 rounded d-block text-center">Por favor registra productos en el catalogo</small>
                        </x-slot>
                    </x-adminlte-card>
                </div>
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