@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-6">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-users"></i> Prestamos de {{ $cliente->nombre }}</h1>
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
                <x-adminlte-input class="col-lg-3" name="deuda" id="deuda" readonly="true" value="{{ $cliente->deuda }}">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-success">
                            <i class="fas fa-dollar-sign"> Deuda Actual:</i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <input type="hidden" name="idCliente" id="idCliente" value="{{ $cliente->id }}">
            </div>
            
            <div class="col-lg-4 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="/clientes"><i class="fas fa-users"></i> Clientes</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-hand-holding-usd"></i> Prestamos</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-2 my-2">
                <x-adminlte-button theme="primary" data-toggle="modal" data-target="#modalNuevo" icon="fas fa-plus-circle" title="Nuevo prestamo" label=""></x-adminlte-button>
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center rounded d-block">Agrega nuevos prestamos con el botón <i class="fas fa-plus-circle"></i> o administra los existentes con los botones correspondientes.</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $prestamos ) > 0 )
                @php
                    $heads = ['Folio', 'Importe', 'Nota', 'Fecha', ''];
                @endphp
                <x-adminlte-datatable id="contenedorprestamos" theme="light" :heads="$heads" striped hoverable compressed beautify>
                    @foreach( $prestamos as $prestamo )
                        <tr>
                            <td>{{ $prestamo->id }}</td>
                            <td>$ {{ number_format( $prestamo->monto, 2 ) }}</td>
                            <td>{{ $prestamo->nota ? : 'Sin nota' }}</td>
                            <td>{{ $prestamo->created_at }}</td>
                            <td>
                                <x-adminlte-button class="shadow editar" icon="fas fa-edit" theme="info" data-toggle="modal" data-target="#modalEditar" data-id="{{ $prestamo->id }}" data-value="{{ $prestamo->id }}, {{ $prestamo->monto }}, {{ $prestamo->nota }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow borrar" icon="fas fa-trash" theme="danger" data-id="{{ $prestamo->id }}" data-value="{{ $prestamo->monto }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow ver" icon="fas fa-info-circle" theme="secondary" data-toggle="modal" data-target="#modalVer" data-id="{{ $prestamo->id }}" data-value="{{ $prestamo->monto }}, {{ $prestamo->nota }}, {{ $prestamo->cliente->nombre }}, {{ $prestamo->cliente->deuda }}, {{ $prestamo->created_at }}"></x-adminlte-button>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                
            @else
                <tr>
                    <td colspan="4" class="text-danger text-center"><i class="fas fa-info-circle"></i> Sin prestamos registrados.</td>
                </tr>
            @endif

        </div>

    </section>

    @include('prestamos.nuevo')
    @include('prestamos.editar')
    @include('prestamos.prestamo')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/prestamos/create.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/prestamos/read.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/prestamos/update.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/prestamos/delete.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/prestamos/imprimir.js') }}" type="text/javascript"></script>

@stop