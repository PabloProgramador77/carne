<x-adminlte-modal id="modalEditar" size="lg" title="Editar Usuario" theme="primary" icon="fas fa-edit" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-5">
            <img src="{{ asset('img/cliente.jpg') }}" alt="" width="100%" height="auto" class="shadow">
        </div>
        <div class="col-lg-7">
            <form novalidate>
                <small class="fs-6 fw-semibold bg-warning border-bottom p-1 d-block mb-2">Cambia los datos como creas necesario. Los campos con * son obligatorios</small>
                <div class="form-group">
                    <x-adminlte-input name="nombreEditar" id="nombreEditar" placeholder="Nombre de usuario">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-user">*</i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="form-group">
                    <x-adminlte-input name="emailEditar" id="emailEditar" placeholder="Email de usuario">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-envelope">*</i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="form-group">
                    <x-adminlte-select id="rolEditar" name="rolEditar">
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
                <input type="hidden" name="id" id="id">
            </form>
        </div>
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="success" label=" Actualizar" id="actualizar" icon="fas fa-save"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cancelar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>