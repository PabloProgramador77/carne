<x-adminlte-modal id="modalEditar" size="md" title="Editar Caja" theme="info" icon="fas fa-edit" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-5">
            <img src="{{ asset('img/caja.jpg') }}" alt="" width="100%" height="auto" class="shadow">
        </div>
        <div class="col-lg-7">
            <form novalidate>
                <small class="fs-6 fw-semibold bg-warning border-bottom p-1 d-block mb-2">Los campos con * son obligatorios</small>
                <div class="form-group">
                    <x-adminlte-input name="nombreEditar" id="nombreEditar" placeholder="Nombre de caja">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-cash-register">*</i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <input type="hidden" name="idCaja" id="idCaja">
            </form>
        </div>
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="success" label=" Actualizar" id="actualizar" icon="fas fa-save"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cancelar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>