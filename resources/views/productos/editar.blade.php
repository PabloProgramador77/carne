<x-adminlte-modal id="modalEditar" size="lg" title="Editar Producto" theme="primary" icon="fas fa-edit" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-5">
            <img src="{{ asset('img/carne.jpg') }}" alt="" width="100%" height="auto" class="shadow">
        </div>
        <div class="col-lg-7">
            <form novalidate>
                <p class="fs-6 fw-semibold bg-light border-bottom p-1">Cambia los datos como creas necesario. Los campos con * son obligatorios</p>
                <div class="form-group">
                    <x-adminlte-input name="nombreEditar" id="nombreEditar" placeholder="Nombre de producto">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-drumstick-bite">*</i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="form-group">
                    <x-adminlte-textarea name="descripcionEditar" id="descripcionEditar" placeholder="Descripción de producto (OPCIONAL)" label-text="text-info">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-edit"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-textarea>
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