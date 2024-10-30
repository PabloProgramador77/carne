<x-adminlte-modal id="modalPermisos" size="xl" title="Permisos de Rol" theme="primary" icon="fas fa-user-cog" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-3">
            <img src="{{ asset('img/cliente.jpg') }}" alt="" width="100%" height="auto" class="shadow">
        </div>
        <div class="col-lg-9">
            <form novalidate>
                <small class="fs-6 fw-semibold bg-warning border-bottom p-1 d-block mb-2">Elige los permisos que tendrá el rol de usuario. Podrás agregar o eliminarlos como necesites.</small>
                <div class="form-group">
                    <x-adminlte-input name="rolPermisos" id="rolPermisos" readonly="true">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-user-tag">*</i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="form-group row">
                    <span class="d-block col-lg-12 bg-light border mb-2">Permisos de usuario</span>
                    @foreach( $permisos as $permiso )
                        <div class="form-check form-switch col-lg-4 col-md-6 col-sm-12 mb-2">
                            <input type="checkbox" name="permiso" class="form-check-input" role="switch" id="{{ $permiso->id }}" data-value="{{ $permiso->name }}"/>
                            <label for="{{ $permiso->id }}" class="form-check-label">{{ $permiso->name }}</label>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="idRolPermisos" id="idRolPermisos">
            </form>
        </div>
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="success" label=" Guardar" id="guardar" icon="fas fa-save"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cancelar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>