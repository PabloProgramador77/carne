@extends('home')
@section('contenido')

    <section class="container-fluid p-2 bg-white">

        <div class="container-fluid row">

            <div class="col-lg-12 border-bottom">
                <h1 class="fs-2 fw-semibold text-primary">Resumen de Negocio</h1>
            </div>
            
            <div class="col-lg-12 p-2 m-1 row">
                <p class="p-1 bg-info fw-semibold text-center col-lg-12">Información rápida del negocio</p>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <x-adminlte-small-box title="{{ count( $productos ) }}" text="Productos registrados" icon="fas fa-drumstick-bite" theme="info" class="shadow"></x-adminlte-small-box>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <x-adminlte-small-box title="{{ count( $clientes ) }}" text="Clientes registrados" icon="fas fa-users" theme="primary" class="shadow"></x-adminlte-small-box>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <x-adminlte-small-box title="{{ count( $pedidos ) }}" text="Pedidos realizados" icon="fas fa-shopping-cart" theme="warning" class="shadow"></x-adminlte-small-box>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    @php
                        $total = 0;
                    @endphp
                    @foreach( $pedidos as $pedido)
                        @php
                            $total += $pedido->total;
                        @endphp
                    @endforeach
                    @can('ver-ventas')
                        <x-adminlte-small-box title="$ {{ number_format( $total, 2) }} MXN" text="Total de Ventas" icon="fas fa-dollar-sign" theme="teal" class="shadow"></x-adminlte-small-box>    
                    @endcan
                    
                </div>
            </div>

        </div>

    </section>

@stop