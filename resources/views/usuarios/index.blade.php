@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-5">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-users"></i> Mis Usuarios</h1>
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
            </div>
            
            <div class="col-lg-5 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-users"></i> Usuarios</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-2 my-2">
                <x-adminlte-button theme="primary" data-toggle="modal" data-target="#modalNuevo" icon="fas fa-plus-circle" title="Nuevo usuario" label=""></x-adminlte-button>
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center rounded d-block">Agrega nuevos usuarios con el botón <i class="fas fa-plus-circle"></i> o administra los existentes con los botones correspondientes.</small>
            </div>

        </div>

        <div class="container-fluid row p-2">
            
            @if( count( $usuarios ) > 0 )
                @php
                    $heads = ['Folio', 'Nombre', 'Email', 'Rol', 'Acciones', ''];
                @endphp

                <x-adminlte-datatable id="contenedorusuarios" theme="light" :heads="$heads" striped hoverable compressed beautify>
                    @foreach( $usuarios as $usuario )
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td><span class="bg-teal rounded p-1">{{ $usuario->getRoleNames()->first() ? : 'Sin rol' }}</span></td>
                            <td>
                                <x-adminlte-button class="shadow editar" icon="fas fa-edit" theme="info" data-toggle="modal" data-target="#modalEditar" data-id="{{ $usuario->id }}" data-value="{{ $usuario->name }}, {{ $usuario->email }}, {{ $usuario->getRoleNames()->first() }}"></x-adminlte-button>
                                <x-adminlte-button class="shadow borrar" icon="fas fa-trash" theme="danger" data-id="{{ $usuario->id }}" data-value="{{ $usuario->name }}"></x-adminlte-button>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                
            @else
                <tr>
                    <td colspan="5" class="text-danger"><i class="fas fa-info-circle"></i> Sin usuarios registrados.</td>
                </tr>
            @endif

        </div>

    </section>

    @include('usuarios.nuevo')
    @include('usuarios.editar')

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/usuarios/create.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/usuarios/read.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/usuarios/update.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/usuarios/delete.js') }}" type="text/javascript"></script>

@stop