@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-5">
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

            <div class="col-lg-2 my-2">
                <x-adminlte-button theme="primary" data-toggle="modal" data-target="#modalNuevo" icon="fas fa-plus-circle" title="Nuevo pedido"></x-adminlte-button>
                <x-adminlte-button theme="warning" data-toggle="modal" data-target="#modalCorte" icon="fas fa-cash-register" title="Corte de caja" class="mx-5" id="corte"></x-adminlte-button>
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center d-block rounded">Para crear un nuevo pedido presiona el botón <i class="fas fa-plus-circle"></i> o administra los pedidos existentes con sus botones correspondientes.</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $pedidos ) > 0 )
                @php
                    $heads = ['Folio', 'Cliente', 'Total', 'Nota', 'Fecha', ''];
                @endphp
                <x-adminlte-datatable id="contenedorPedidos" theme="light" :heads="$heads" striped hoverable compressed beautify>
                    @foreach( $pedidos as $pedido )
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->cliente->nombre }}</td>
                            <td>$ {{ number_format( $pedido->total, 2 ) }}</td>
                            <td>{{ $pedido->nota ? : 'Sin nota' }}</td>
                            <td>{{ $pedido->created_at }}</td>
                            <td>
                                <x-adminlte-button class="shadow ver" theme="info" icon="fas fa-info-circle" data-id="{{ $pedido->id }}" data-value="{{ $pedido->cliente->nombre }}, {{ $pedido->total }}, {{ $pedido->created_at }}" data-toggle="modal" data-target="#modalVer"></x-adminlte-button>
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

    </section>

    @include('pedidos.nuevo')
    @include('pedidos.pedido')
    @include('pedidos.corte')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/read.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cortes/pedidos.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cortes/corte.js') }}" type="text/javascript"></script>

@stop