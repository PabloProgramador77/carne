@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-5">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-shopping-cart"></i> Pedidos de {{ $cliente->nombre }}</h1>
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
            </div>
            
            <div class="col-lg-5 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="/clientes"><i class="fas fa-users"></i> Clientes</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-shopping-cart"></i> Pedidos de Cliente</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center d-block rounded">Elige el pedido a ver y presiona el botón <i class="fas fa-info-circle"></i> para verlo a detalle o presion el botón <i class="fas fa-print"></i> para imprimirlo</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $pedidos ) > 0 )
                @php
                    $heads = ['Folio', 'Total', 'Estado', 'Fecha', ''];
                @endphp
                <x-adminlte-datatable id="contenedorPedidos" :heads="$heads" theme="light" striped hoverable compressed beautify>
                    @foreach( $pedidos as $pedido )
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>$ {{ number_format( $pedido->total, 2 ) }}</td>
                            <td><span class="bg-teal p-1 text-center rounded">{{ $pedido->estado }}</span></td>
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
                                @if( $pedido->estado === 'Pagado' || $pedido->estado === 'Corte')
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
                    <td colspan="4" class="text-danger"><i class="fas fa-info-circle"></i> Cliente sin pedidos registrados.</td>
                </tr>
            @endif

        </div>

    </section>

    @include('pedidos.pedido')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/read.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/imprimir.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/update.js') }}" type="text/javascript"></script>

@stop