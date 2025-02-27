@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-5">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-shopping-cart"></i> Pedido de {{ $cliente->nombre }}</h1>
                <input type="hidden" name="idPedido" id="idPedido" value="{{ $pedido->id }}">
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
            </div>

            <div class="col-lg-5 my-2">
                <x-adminlte-button theme="success" icon="fas fa-hand-holding-usd" title="Terminar pedido" label=" Terminar" class="shadow" id="guardar" data-toggle="modal" data-target="#modalTerminar"></x-adminlte-button>
                <x-adminlte-button theme="danger" icon="fas fa-ban" title="Cancelar pedido" label=" Cancelar" class="shadow" id="cancelar" data-id="{{ $pedido->id }}" data-value="{{ $pedido->cliente->nombre }}"></x-adminlte-button>
            </div>
            <div class="col-lg-2 my-2">
                <x-adminlte-input id="total" name="total" readonly="true" class="border border-success shadow" value="0">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>                 
            </div>
            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center d-block rounded">Introduce la cantidad en los producto a vender y para terminar el pedido presiona el botón <i class="fas fa-hand-holding-usd"></i>Terminar. O si deseas cancelar el pedido presiona el botón <i class="fas fa-ban"></i>Cancelar</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            @if( count( $productos ) > 0 )
                @php
                    $heads = ['Producto', 'Descripción', 'Precio', 'Peso'];
                @endphp
                
                <div class="col-lg-10 col-md-9 col-sm-8">
                    <x-adminlte-datatable id="contenedorProductos" theme="light" :heads="$heads" striped hoverable compressed beautify>
                        @foreach( $productos as $producto )
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>
                                @if( $producto->descripcion === NULL || $producto->descripcion === '')
                                    Sin descripción
                                @else
                                    {{ $producto->descripcion }}
                                @endif
                            </td>
                            <td>$ {{ number_format($producto->precio, 1) }}</td>
                            <td>
                                <x-adminlte-input name="cantidad" id="cantidad" placeholder="Peso/Cantidad del producto" data-id="{{ $producto->id }}" data-value="{{ $producto->id }}, {{ $producto->precio }}, {{ $producto->nombre }}">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text text-info">
                                            <i class="fas fa-balance-scale"></i>
                                        </div>
                                    </x-slot>
                                    <x-slot name="appendSlot">
                                        <x-adminlte-button class="shadow conversor" theme="outline-info" icon="fas fa-calculator" data-toggle="modal" data-target="#modalConversor" data-value="{{ $producto->id }}, {{ $producto->nombre }}, {{ $producto->precio }}"></x-adminlte-button>
                                    </x-slot>
                                </x-adminlte-input>
                            </td>
                        </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-4 p-1 border rounded shadow">
                    <p class="p-1 border-bottom text-center fw-semibold"><b>Vista de Pedido</b></p>
                    <div class="container-fluid">
                        <table class="container-fluid bg-info overflow-hidden" id="contenedorProductosPedido">
                            <thead class="container-fluid">
                                <th class="text-center p-1 border border-white">Producto</th>
                                <th class="text-center p-1 border border-white">Importe</th>
                            </thead>
                        </table>
                    </div>
                </div>
                
            @else
                <div class="col-lg-4 mx-auto">
                    <x-adminlte-card theme-mode="outline" theme="danger" title="Sin prodcutos registrados">
                        <x-slot name="toolsSlot">
                            <img src="{{ asset('/img/carne.jpg') }}" alt="Carne" width="100%" height="auto" class="">
                            <small class="bg-danger p-1 rounded d-block text-center">Por favor registra productos en el catalogo</small>
                        </x-slot>
                    </x-adminlte-card>
                </div>
            @endif

        </div>

    </section>

    @include('pedidos.terminar')
    @include('pedidos.conversor')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/pedido.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/cobrar.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/cancelar.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/total.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/conversor.js') }}" type="text/javascript"></script>

@stop