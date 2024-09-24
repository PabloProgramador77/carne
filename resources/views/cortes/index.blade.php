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

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $cortes ) > 0 )
                @foreach( $cortes as $corte )
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <x-adminlte-card theme-mode="outline" title="Folio: {{ $corte->id }}" header-class="rounded-bottom border-primary">
                            <x-slot name="toolsSlot">
                                <img src="{{ asset('/img/corte.jpg') }}" alt="corte" width="75%" height="auto" class="">
                                <small class="fs-5 fw-semibold col-lg-12 d-block border border-light p-1 rounded"><b>Fecha:</b> {{ $corte->created_at }}</small>
                                <p class="fs-5 fw-semibold col-lg-12 d-block bg-success p-1 rounded text-center"><b>Total: $</b> {{ number_format( $corte->total, 2) }} MXN</p>
                            </x-slot>
                            <x-slot name="footerSlot">
                                <x-adminlte-button class="shadow ver" theme="info" icon="fas fa-info-circle" data-toggle="modal" data-target="#modalVer" data-id="{{ $corte->id }}" data-value="{{ $corte->id }}, {{ $corte->total }}, {{ $corte->created_at }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow imprimir" theme="success" icon="fas fa-print" data-id="{{ $corte->id }}"></x-adminlte-button>
                            </x-slot>
                        </x-adminlte-card>
                    </div>
                @endforeach
            @else
                <div class="col-lg-4 mx-auto">
                    <x-adminlte-card theme-mode="outline" theme="danger" title="Sin cortes registrados">
                        <x-slot name="toolsSlot">
                            <img src="{{ asset('/img/corte.jpg') }}" alt="corte" width="100%" height="auto" class="">
                            <small class="bg-danger p-1 rounded d-block text-center">Por favor registra cortes en el catalogo</small>
                        </x-slot>
                    </x-adminlte-card>
                </div>
            @endif

        </div>

    </section>

    @include('cortes.corte')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cortes/read.js') }}" type="text/javascript"></script>

@stop