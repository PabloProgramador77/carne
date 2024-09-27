@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-5">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-shopping-cart"></i> Mis cortes</h1>
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
            </div>
            
            <div class="col-lg-5 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-cash-register"></i> Cortes</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center rounded d-block">Para agregar un nuevo corte ve a la sección de Pedidos, y para ver los datos del corte presiona el botón <i class="fas fa-info-circle"></i></small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $cortes ) > 0 )
                @php
                    $heads = ['Folio', 'Total', 'Fecha', ''];
                @endphp
                <x-adminlte-datatable id="contenedorCorte" theme="light" :heads="$heads" striped hoverable compressed beautify>
                    @foreach( $cortes as $corte )
                        <tr>
                            <td>{{ $corte->id }}</td>
                            <td>$ {{ number_format( $corte->total, 2 ) }}</td>
                            <td>{{ $corte->created_at }}</td>
                            <td>
                                <x-adminlte-button class="shadow ver" icon="fas fa-info-circle" theme="info" data-toggle="modal" data-target="#modalVer" data-id="{{ $corte->id }}" data-value="{{ $corte->id }}, {{ $corte->total }}, {{ $corte->created_at }}"></x-adminlte-button>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                
            @else
                <tr>
                    <td colspan="4" class="text-danger"><i class="fas fa-info-circle"></i> Sin cortes registrados.</td>
                </tr>
            @endif

        </div>

    </section>

    @include('cortes.corte')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cortes/read.js') }}" type="text/javascript"></script>

@stop