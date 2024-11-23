@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-4">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-shopping-cart"></i> Mis pedidos</h1>
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
            </div>
            
            <div class="col-lg-5 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-shopping-cart"></i> Pedidos</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-3 my-2">
                @can('agregar-pedido')
                    <x-adminlte-button theme="primary" data-toggle="modal" data-target="#modalNuevo" icon="fas fa-plus-circle" title="Nuevo pedido"></x-adminlte-button>
                @endcan
                @can('corte-pedidos')
                    <x-adminlte-button theme="warning" data-toggle="modal" data-target="#modalCorte" icon="fas fa-cash-register" title="Corte de caja" class="mx-3" id="corte"></x-adminlte-button>
                @endcan
                @can('ventas-pedidos')
                <x-adminlte-button theme="secondary" data-toggle="modal" data-target="#modalVentas" icon="fas fa-info-circle" title="Consulta de ventas" id="ventas"></x-adminlte-button>
                @endcan
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center d-block rounded">Para crear un nuevo pedido presiona el botón <i class="fas fa-plus-circle"></i> o administra los pedidos existentes con sus botones correspondientes.</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            <div class="accordion accordion-flush container-fluid">
                @foreach( $pedidos->groupBy( function( $fecha ){
                    return \Carbon\Carbon::parse( $fecha->created_at)->format('Y-m-d');
                }) as $fecha => $grupo )
                    <div class="accordion-item" id="accordion-{{ $fecha }}">
                        <div class="accordion-header">
                            <a class="bg-light border-info border-bottom p-1 text-center accordion-button collapsed d-block" type="button" data-toggle="collapse" data-target="#flush-{{ $fecha }}" aria-controls="flush-{{ $fecha }}">
                                <b>{{ \Carbon\Carbon::parse($fecha)->format('d-m-Y') }}</b>
                            </a>
                        </div>
                        <div id="flush-{{ $fecha }}" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                @if( count( $grupo ) > 0 )
                                @php
                                    $heads = ['Estatus', 'Cliente', 'Total', 'Nota', 'Fecha', ''];
                                @endphp
                                    <x-adminlte-datatable id="contenedorPedidos" theme="light" :heads="$heads" striped hoverable compressed beautify>
                                        @foreach( $grupo as $pedido )
                                            <tr>
                                                <td><span class="bg-teal p-1 text-center rounded">{{ $pedido->estado }}</span></td>
                                                <td>{{ $pedido->cliente->nombre }}</td>
                                                <td>$ {{ number_format( $pedido->total, 2 ) }}</td>
                                                <td>{{ $pedido->nota ? : 'Sin nota' }}</td>
                                                <td>{{ $pedido->created_at }}</td>
                                                <td>
                                                    @if( $pedido->estado === 'Pendiente')
                                                        @can('ver-pedido')
                                                        <x-adminlte-button class="shadow ver" theme="info" icon="fas fa-info-circle" data-id="{{ $pedido->id }}" data-value="{{ $pedido->cliente->nombre }}, {{ $pedido->total }}, {{ $pedido->created_at }}" data-toggle="modal" data-target="#modalVer"></x-adminlte-button>
                                                        @endcan
                                                        @can('entregar-pedido')
                                                        <x-adminlte-button class="shadow cobrar" id="cobrar" theme="warning" icon="fas fa-hand-holding-usd" data-id="{{ $pedido->id }}" data-value="{{ $pedido->cliente->nombre }}, {{ $pedido->total }}, {{ $pedido->created_at }}" data-toggle="modal" data-target="#modalCobrar"></x-adminlte-button>
                                                        @endcan
                                                    @endif
                                                    @if( $pedido->estado === 'Entregado' )
                                                        @can('pagar-pedido')
                                                        <x-adminlte-button class="shadow pagar" id="pagar" theme="success" icon="fas fa-dollar-sign" data-id="{{ $pedido->id }}" data-value="{{ $pedido->cliente->nombre }}, {{ $pedido->total }}"></x-adminlte-button>
                                                        @endcan
                                                        @can('ver-pedido')
                                                        <x-adminlte-button class="shadow ver" theme="info" icon="fas fa-info-circle" data-id="{{ $pedido->id }}" data-value="{{ $pedido->cliente->nombre }}, {{ $pedido->total }}, {{ $pedido->created_at }}" data-toggle="modal" data-target="#modalVer"></x-adminlte-button>
                                                        @endcan
                                                    @endif
                                                    @if( $pedido->estado === 'Pagado')
                                                        @can('ver-pedido')
                                                        <x-adminlte-button class="shadow ver" theme="info" icon="fas fa-info-circle" data-id="{{ $pedido->id }}" data-value="{{ $pedido->cliente->nombre }}, {{ $pedido->total }}, {{ $pedido->created_at }}" data-toggle="modal" data-target="#modalVer"></x-adminlte-button>
                                                        @endcan
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </x-adminlte-datatable>
                                @else
                                    <tr>
                                        <td colspan="5" class="text-danger"><i class="fas fa-info-circle"></i> Sin pedidos registrados.</td>
                                    </tr>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
        </div>

    </section>

    @include('pedidos.nuevo')
    @include('pedidos.pedido')
    @include('pedidos.corte')
    @include('pedidos.ventas')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/read.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cortes/pedidos.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cortes/corte.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/update.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/ventas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/imprimir.js') }}" type="text/javascript"></script>

@stop