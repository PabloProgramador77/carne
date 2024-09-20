@extends('home')
@section('contenido')

    <section class="container-fluid p-2 bg-white">

        <div class="container-fluid row">

            <div class="col-lg-12 border-bottom">
                <h1 class="fs-2 fw-semibold text-primary">Resumen Empresarial</h1>
            </div>
            
            <div class="col-lg-12 p-2 m-1 border row shadow">
                <p class="bg-light fw-semibold text-center col-lg-12">Información rápida del sistema</p>
                <div class="col-lg-3">
                    <x-adminlte-small-box title="0" text="Productos registrados" icon="fas fa-users" theme="info"></x-adminlte-small-box>
                </div>
                <div class="col-lg-3">
                    <x-adminlte-small-box title="0" text="Clientes registrados" icon="fas fa-shoe-prints" theme="primary"></x-adminlte-small-box>
                </div>
                <div class="col-lg-3">
                    <x-adminlte-small-box title="0" text="Pedidos realizados" icon="fas fa-socks" theme="secondary"></x-adminlte-small-box>
                </div>
                <div class="col-lg-3">
                    <x-adminlte-small-box title="0" text="Ventas realizadas" icon="fas fa-boxes" theme="teal"></x-adminlte-small-box>
                </div>
            </div>

        </div>

    </section>

@stop