@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-6">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-users"></i> Gastos de Caja {{ $caja->nombre }}</h1>
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-cash-register"></i> Panel de Administrador</p>
            </div>
            
            <div class="col-lg-4 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-hand-holding-usd"></i> Gastos de Caja</li>
                    </ol>
                    <input type="hidden" name="idCaja" id="idCaja" value="{{ $caja->id }}">
                </nav>
            </div>

            <div class="col-lg-2 my-2">
                <x-adminlte-button theme="primary" data-toggle="modal" data-target="#modalNuevo" icon="fas fa-plus-circle" title="Nuevo gasto"></x-adminlte-button>
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center rounded d-block">Agrega nuevo gasto con el botón <i class="fas fa-plus-circle"></i> o administra las existentes con los botones correspondientes.</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $gastos ) > 0 )
                @php
                    $heads = ['Folio', 'Importe', 'Descripción', 'Fecha', ''];
                @endphp
                <x-adminlte-datatable id="contenedorgastos" theme="light" :heads="$heads" striped hoverable compressed beautify>
                    @foreach( $gastos as $gasto )
                        <tr>
                            <td>{{ $gasto->id }}</td>
                            <td>$ {{ number_format( $gasto->monto, 2 ) }}</td>
                            <td>{{ $gasto->descripcion }}</td>
                            <td>{{ $gasto->created_at }}</td>
                            <td>
                                <x-adminlte-button class="shadow editar" icon="fas fa-edit" theme="info" data-toggle="modal" data-target="#modalEditar" data-id="{{ $gasto->id }}" data-value="{{ $gasto->id }}, {{ $gasto->monto }}, {{ $gasto->descripcion }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow borrar" icon="fas fa-trash" theme="danger" data-id="{{ $gasto->id }}" data-value="{{ $gasto->monto }}"></x-adminlte-button>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                
            @else
                <tr>
                    <td colspan="4" class="text-danger text-center"><i class="fas fa-info-circle"></i> Sin gastos registradas</td>
                </tr>
            @endif

        </div>

    </section>

    @include('gastos.nuevo')
    @include('gastos.editar')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/gastos/create.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/gastos/read.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/gastos/update.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/gastos/delete.js') }}" type="text/javascript"></script>

@stop