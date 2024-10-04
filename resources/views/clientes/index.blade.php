@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-5">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-users"></i> Mis Clientes</h1>
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

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center rounded d-block">Agrega nuevos clientes con el botón <i class="fas fa-plus-circle"></i> o administra los existentes con los botones correspondientes.</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $clientes ) > 0 )
                @php
                    $heads = ['Folio', 'Nombre', 'Deuda', 'Telefono', 'Domicilio', ''];
                @endphp
                <x-adminlte-datatable id="contenedorClientes" theme="light" :heads="$heads" striped hoverable compressed beautify>
                    @foreach( $clientes as $cliente )
                        <tr>
                            <td>{{ $cliente->id }}</td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>$ {{ $cliente->deuda }}</td>
                            <td>{{ $cliente->telefono ? : 'Sin telefono' }}</td>
                            <td>{{ $cliente->domicilio ? : 'Sin domicilio' }}</td>
                            <td>
                                <x-adminlte-button class="shadow editar" icon="fas fa-edit" theme="info" data-toggle="modal" data-target="#modalEditar" data-id="{{ $cliente->id }}" data-value="{{ $cliente->nombre }}, {{ $cliente->telefono }}, {{ $cliente->domicilio }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow borrar" icon="fas fa-trash" theme="danger" data-id="{{ $cliente->id }}" data-value="{{ $cliente->nombre }}"></x-adminlte-button>
                                <a href="{{ url('/cliente/productos') }}/{{ $cliente->id }}" class="btn btn-secondary shadow rounded" title="Productos de cliente"><i class="fas fa-drumstick-bite"></i></a>
                                <a href="{{ url('/cliente/pedidos') }}/{{ $cliente->id }}" class="btn btn-success shadow rounded" title="Pedidos de cliente"><i class="fas fa-shopping-cart"></i></a>
                                <a href="{{ url('/cliente/abonos') }}/{{ $cliente->id }}" class="btn btn-warning shadow rounded" title="Abonos de cliente"><i class="fas fa-hand-holding-usd"></i></a>
                                <a href="{{ url('/cliente/prestamos') }}/{{ $cliente->id }}" class="btn btn-primary shadow rounded" title="Abonos de cliente"><i class="fas fa-hand-holding-usd"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                
            @else
                <tr>
                    <td colspan="5" class="text-danger"><i class="fas fa-info-circle"></i> Sin clientes registrados.</td>
                </tr>
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