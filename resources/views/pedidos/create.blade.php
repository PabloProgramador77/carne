@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-7">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-shopping-cart"></i> Pedido de {{ $cliente->nombre }}</h1>
                <input type="hidden" name="idPedido" id="idPedido" value="{{ $pedido->id }}">
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
            </div>

            <div class="col-lg-5 my-2">
                <x-adminlte-button theme="success" icon="fas fa-hand-holding-usd" title="Terminar pedido" label=" Terminar" class="shadow" id="guardar"></x-adminlte-button>
                <x-adminlte-button theme="danger" icon="fas fa-ban" title="Cancelar pedido" label=" Cancelar" class="shadow" id="cancelar" data-id="{{ $pedido->id }}" data-value="{{ $pedido->cliente->nombre }}"></x-adminlte-button>
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center d-block rounded">Introduce la cantidad en los producto a vender y para terminar el pedido presiona el botón <i class="fas fa-hand-holding-usd"></i>Terminar. O si deseas cancelar el pedido presiona el botón <i class="fas fa-ban"></i>Cancelar</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
    
            @if( count( $productos ) > 0 )
                @foreach( $productos as $producto )
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <x-adminlte-card theme-mode="outline" title="{{ $producto->nombre }}" header-class="rounded-bottom border-primary" class="shadow">
                            <x-slot name="toolsSlot">
                                <img src="{{ asset('/img/carne.jpg') }}" alt="Carne" width="75%" height="auto">
                                <p class="p-1 bg-info rounded text-center"><b>$ {{ $producto->precio }}</b> MXN</p>
                                <div class="form-group pt-3">
                                    <x-adminlte-input name="cantidad" id="cantidad" placeholder="Peso/Cantidad del producto" data-value="{{ $producto->id }}, {{ $producto->precio }}">
                                        <x-slot name="prependSlot">
                                            <div class="input-group-text text-info">
                                                <i class="fas fa-balance-scale"></i>
                                            </div>
                                        </x-slot>
                                    </x-adminlte-input>
                                </div>
                            </x-slot>
                        </x-adminlte-card>
                    </div>
                @endforeach
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

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/pedido.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pedidos/cancelar.js') }}" type="text/javascript"></script>

@stop