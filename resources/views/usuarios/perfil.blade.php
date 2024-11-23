@extends('home')
@section('contenido')

    <section class="container-fluid p-2 my-2 bg-white rounded shadow">

        <div class="container-fluid row border-bottom">

            <div class="col-lg-4">
                <h1 class="fs-3 fw-semibold"><i class="fas fa-user-circle"></i> Mi Perfil</h1>
                <p class="fs-6 fw-semibold text-secondary"><i class="fas fa-user-shield"></i> Panel de Administrador</p>
            </div>
            
            <div class="col-lg-5 my-2">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-user-circle"></i> Mi Perfil</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-12">
                <small class="p-1 bg-warning text-center d-block rounded"><b><i class="fas fa-info-circle"></i>Administra los datos del perfil como creas necesario. La dirección y telefono capturados serán impresos en los tickets emitidos por el sistema.</b></small>
            </div>

        </div>

        <div class="container-fluid row p-5">
            <div class="col-lg-4 col-md-3 col-sm-6 text-center m-1">
                <img src="{{ asset('img/logo-min.png') }}" alt="Logo" width="70%" height="auto" class="shadow rounded-pill m-1 p-4 border">
                <p class="d-block text-center p-1 border-bottom"><b>{{ auth()->user()->name }}<b></p>
                <small class="d-block text-center p-1 border bg-info"><b>{{ auth()->user()->getRoleNames()->first() }}</b></small>
                <small class="text-center p-1 m-1 w-50">{{ auth()->user()->email }}</small>
                <small class="text-center p-1 m-1 w-50">{{ count(auth()->user()->permissions) }} permisos de usuario</small>
            </div>
            <div class="col-lg-8 col-md-9 col-sm-6 border p-4 m-1">
                <form novalidate>
                    <div class="form-group">
                        <x-adminlte-input name="nombre" id="nombre" placeholder="Nombre de usuario" value="{{ auth()->user()->name }}">
                            <x-slot name="prependSlot">
                                <div class="input-group-text text-info">
                                    <i class="fas fa-user">*</i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="form-group">
                        <x-adminlte-input name="email" id="email" placeholder="Email de usuario" value="{{ auth()->user()->email }}">
                            <x-slot name="prependSlot">
                                <div class="input-group-text text-info">
                                    <i class="fas fa-envelope">*</i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="form-group">
                        <x-adminlte-input name="telefono" id="telefono" placeholder="Telefono de usuario" value="{{ ( auth()->user()->telefono ? : 'Sin telefono' ) }}">
                            <x-slot name="prependSlot">
                                <div class="input-group-text text-info">
                                    <i class="fas fa-phone"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="form-group">
                        <x-adminlte-input name="direccion" id="direccion" placeholder="Dirección de usuario" value="{{ ( auth()->user()->direccion ? : 'Sin dirección' ) }}">
                            <x-slot name="prependSlot">
                                <div class="input-group-text text-info">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="form-group">
                        @can('editar-perfil')
                       <x-adminlte-button theme="primary" icon="fas fa-save" label="Guardar" class="shadow" id="aceptar"></x-adminlte-button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>

    </section>

    <script src="{{ asset('js/jquery-3.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetAlert.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/usuarios/perfil.js') }}" type="text/javascript"></script>

@stop