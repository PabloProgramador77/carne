@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-5">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-drumstick-bite"></i> Mis Clientes</h1>
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
            </div>
            
            <div class="col-lg-5 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-users"></i> Clientes</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-2 my-2">
                <x-adminlte-button theme="primary" data-toggle="modal" data-target="#modalNuevo" icon="fas fa-plus-circle" title="Nuevo cliente" label=""></x-adminlte-button>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $clientes ) > 0 )
                @foreach( $clientes as $cliente )
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <x-adminlte-card theme-mode="outline" title="{{ $cliente->nombre }}" header-class="rounded-bottom border-primary">
                            <x-slot name="toolsSlot">
                                <img src="{{ asset('/img/cliente.jpg') }}" alt="Cliente" width="75%" height="auto" class="">
                                @if( $cliente->telefono === NULL || $cliente->telefono === '' )
                                    <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block">Sin telefono agregado al cliente.</small>
                                @else
                                    <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block"><b>Tel:</b> {{ $cliente->telefono }}</small>
                                @endif

                                @if( $cliente->domicilio === NULL || $cliente->domicilio === '' )
                                    <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block">Sin domicilio agregado al cliente.</small>
                                @else
                                    <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block">{{ $cliente->domicilio }}</small>
                                @endif
                            </x-slot>
                            <x-slot name="footerSlot">
                                <x-adminlte-button class="shadow editar" theme="info" icon="fas fa-edit" data-toggle="modal" data-target="#modalEditar" data-id="{{ $cliente->id }}" data-value="{{ $cliente->nombre }}, {{ $cliente->telefono }}, {{ $cliente->domicilio }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow borrar" theme="danger" icon="fas fa-trash" data-id="{{ $cliente->id }}" data-value="{{ $cliente->nombre }}"></x-adminlte-button>
                                <a href="{{ url('cliente/productos') }}/{{ $cliente->id }}" class="btn btn-secondary shadow rounded" title="Agregar productos a cliente"><i class="fas fa-drumstick-bite"></i></a>
                            </x-slot>
                        </x-adminlte-card>
                    </div>
                @endforeach
            @else
                <div class="col-lg-4 mx-auto">
                    <x-adminlte-card theme-mode="outline" theme="danger" title="Sin clientes registrados">
                        <x-slot name="toolsSlot">
                            <img src="{{ asset('/img/cliente.jpg') }}" alt="Cliente" width="100%" height="auto" class="">
                            <small class="bg-danger p-1 rounded d-block text-center">Por favor registra clientes en el catalogo</small>
                        </x-slot>
                    </x-adminlte-card>
                </div>
            @endif

        </div>

    </section>

    @include('clientes.nuevo')
    @include('clientes.editar')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/clientes/create.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/clientes/read.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/clientes/update.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/clientes/delete.js') }}" type="text/javascript"></script>

@stop