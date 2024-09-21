@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-7">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-drumstick-bite"></i> Productos de {{ $cliente->nombre }}</h1>
                <input type="hidden" name="idCliente" id="idCliente" value="{{ $cliente->id }}">
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
            </div>
            
            <div class="col-lg-3 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="/clientes"><i class="fas fa-users"></i> Clientes</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-user"></i> Cliente</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-2 my-2">
                <x-adminlte-button theme="success" icon="fas fa-save" title="Guardar precios" label=" Guardar" class="shadow" id="guardar"></x-adminlte-button>
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center d-block mb-2">Introduce el precio de los productos a venderle al cliente y para termina presiona el botón <i class="fas fa-save"></i>Guardar</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $cliente->productos ) > 0 )

                @if( count( $productos ) > 0 )
                    @foreach( $productos as $producto )
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <x-adminlte-card theme-mode="outline" title="{{ $producto->nombre }}" header-class="rounded-bottom border-primary" class="shadow">
                                <x-slot name="toolsSlot">
                                    <img src="{{ asset('/img/carne.jpg') }}" alt="Carne" width="75%" height="auto">
                                
                                    @if( $producto->descripcion === NULL || $producto->descripcion === '' )
                                        <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block">Sin descripción agregado al producto.</small>
                                    @else
                                        <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block">{{ $producto->descripcion }}</small>
                                    @endif
                                    <div class="form-group pt-3">
                                        @foreach( $cliente->productos as $pc )

                                            @php
                                                $precio = '';
                                            @endphp

                                            @if( $producto->id === $pc->id )
                                                
                                                @php 
                                                    $precio = $pc->pivot->precio; 
                                                @endphp
                                                @break
                                        
                                            @endif

                                        @endforeach
                                        <x-adminlte-input name="precio" id="precio" placeholder="Precio para el cliente" data-id="{{ $cliente->id }}" data-value="{{ $producto->id }}" value="{{ isset( $precio ) ? $precio : '' }}">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text text-info">
                                                    <i class="fas fa-dollar-sign">*</i>
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

            @else
                @if( count( $productos ) > 0 )
                    @foreach( $productos as $producto )
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <x-adminlte-card theme-mode="outline" title="{{ $producto->nombre }}" header-class="rounded-bottom border-primary">
                                <x-slot name="toolsSlot">
                                    <img src="{{ asset('/img/carne.jpg') }}" alt="Carne" width="75%" height="auto">
                                
                                    @if( $producto->descripcion === NULL || $producto->descripcion === '' )
                                        <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block">Sin descripción agregado al producto.</small>
                                    @else
                                        <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block">{{ $producto->descripcion }}</small>
                                    @endif
                                    <div class="form-group pt-3">
                                        <x-adminlte-input name="precio" id="precio" placeholder="Precio para el cliente" data-id="{{ $cliente->id }}" data-value="{{ $producto->id }}">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text text-info">
                                                    <i class="fas fa-dollar-sign">*</i>
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
            @endif

        </div>

    </section>

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/clientes/precio.js') }}" type="text/javascript"></script>

@stop