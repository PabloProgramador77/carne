@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-6">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-users"></i> Cajas</h1>
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-cash-register"></i> Panel de Administrador</p>
            </div>
            
            <div class="col-lg-4 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-cash-register"></i> Cajas</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-2 my-2">
                <x-adminlte-button theme="primary" data-toggle="modal" data-target="#modalNuevo" icon="fas fa-plus-circle" title="Nueva caja" label=""></x-adminlte-button>
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center rounded d-block">Agrega nueva caja con el botón <i class="fas fa-plus-circle"></i> o administra las existentes con los botones correspondientes.</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $cajas ) > 0 )
                @php
                    $heads = ['Folio', 'Caja', 'Total', 'Monto de apertura', ''];
                @endphp
                <x-adminlte-datatable id="contenedorCajas" theme="light" :heads="$heads" striped hoverable compressed beautify>
                    @foreach( $cajas as $caja )
                        <tr>
                            <td>{{ $caja->id }}</td>
                            <td>{{ $caja->nombre }}</td>
                            <td>$ {{ number_format( $caja->total, 2 ) }}</td>
                            <td>$ {{ number_format( $caja->apertura) }}</td>
                            <td>
                                <x-adminlte-button class="shadow editar" icon="fas fa-edit" theme="info" data-toggle="modal" data-target="#modalEditar" data-id="{{ $caja->id }}" data-value="{{ $caja->id }}, {{ $caja->nombre }}, {{ $caja->total }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow borrar" icon="fas fa-trash" theme="danger" data-id="{{ $caja->id }}" data-value="{{ $caja->nombre }}"></x-adminlte-button>
                                <a href="{{ url('/gastos') }}/{{ $caja->id }}" class="btn btn-secondary shadow gastos" title="Gastos"><i class="fas fa-hand-holding-usd"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                
            @else
                <tr>
                    <td colspan="4" class="text-danger text-center"><i class="fas fa-info-circle"></i> Sin cajas registradas</td>
                </tr>
            @endif

        </div>

    </section>

    @include('cajas.nuevo')
    @include('cajas.editar')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cajas/create.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cajas/read.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cajas/update.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cajas/delete.js') }}" type="text/javascript"></script>

@stop