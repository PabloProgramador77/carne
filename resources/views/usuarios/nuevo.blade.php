<x-adminlte-modal id="modalNuevo" size="lg" title="Nuevo Usuario" theme="primary" icon="fas fa-plus-circle" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-5">
            <img src="{{ asset('img/cliente.jpg') }}" alt="" width="100%" height="auto" class="shadow">
        </div>
        <div class="col-lg-7">
            <form novalidate>
                <small class="fs-6 fw-semibold bg-warning border-bottom p-1 d-block mb-2">Los campos con * son obligatorios</small>
                <div class="form-group">
                    <x-adminlte-input name="nombre" id="nombre" placeholder="Nombre de usuario">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-user">*</i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="form-group">
                    <x-adminlte-input name="email" id="email" placeholder="Email de usuario">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-envelope">*</i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="form-group">
                    <x-adminlte-select id="rol" name="rol">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-user-tag">*</i>
                            </div>
                        </x-slot>
                        <option value="0">Elige un rol de usuario</option>
                        @foreach( $roles as $rol)
                            @if( $rol->name !== 'Developer' )
                                <option value="{{ $rol->name }}">{{ $rol->name }}</option>
                            @endif
                        @endforeach
                    </x-adminlte-select>
                </div>
            </form>
        </div>
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="success" label=" Guardar" id="registrar" icon="fas fa-save"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cancelar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>