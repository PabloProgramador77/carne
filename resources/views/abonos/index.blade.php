@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-6">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-users"></i> Abonos de {{ $cliente->nombre }}</h1>
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
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-hand-holding-usd"></i> Abonos</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-2 my-2">
                <x-adminlte-button theme="primary" data-toggle="modal" data-target="#modalNuevo" icon="fas fa-plus-circle" title="Nuevo abono" label="" id="nuevo"></x-adminlte-button>
                <x-adminlte-button theme="success" data-toggle="modal" data-target="#modalLiquidar" icon="fas fa-dollar-sign" title="Liquidar deuda" class="mx-5" data-value="{{ $cliente->id }}, {{ $cliente->deuda }}" id="liquidar"></x-adminlte-button>
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center rounded d-block">Agrega nuevos abonos con el botón <i class="fas fa-plus-circle"></i> o administra los existentes con los botones correspondientes.</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $abonos ) > 0 )
                @php
                    $heads = ['Folio', 'Importe', 'Nota', 'Fecha', ''];
                @endphp
                <x-adminlte-datatable id="contenedorAbonos" theme="light" :heads="$heads" striped hoverable compressed beautify>
                    @foreach( $abonos as $abono )
                        <tr>
                            <td>{{ $abono->id }}</td>
                            <td>$ {{ number_format( $abono->monto, 2 ) }}</td>
                            <td>{{ $abono->nota ? : 'Sin nota' }}</td>
                            <td>{{ $abono->created_at }}</td>
                            <td>
                                <x-adminlte-button class="shadow editar" icon="fas fa-edit" theme="info" data-toggle="modal" data-target="#modalEditar" data-id="{{ $abono->id }}" data-value="{{ $abono->id }}, {{ $abono->monto }}, {{ $abono->nota }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow borrar" icon="fas fa-trash" theme="danger" data-id="{{ $abono->id }}" data-value="{{ $abono->monto }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow ver" icon="fas fa-info-circle" theme="secondary" data-toggle="modal" data-target="#modalVer" data-id="{{ $abono->id }}" data-value="{{ $abono->monto }}, {{ $abono->nota }}, {{ $abono->cliente->nombre }}, {{ $abono->cliente->deuda }}, {{ $abono->created_at }}"></x-adminlte-button>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                
            @else
                <tr>
                    <td colspan="4" class="text-danger text-center"><i class="fas fa-info-circle"></i> Sin abonos registrados.</td>
                </tr>
            @endif

        </div>

    </section>

    @include('abonos.nuevo')
    @include('abonos.editar')
    @include('abonos.liquidar')
    @include('abonos.abono')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/abonos/create.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/abonos/read.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/abonos/update.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/abonos/delete.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/abonos/liquidar.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/abonos/liquidacion.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/abonos/imprimir.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/abonos/suma.js') }}" type="text/javascript"></script>

@stop