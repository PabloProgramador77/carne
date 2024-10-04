<x-adminlte-modal id="modalEditar" size="lg" title="Editar prestamo" theme="info" icon="fas fa-edit" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-5">
            <img src="{{ asset('img/abono.jpg') }}" alt="" width="100%" height="auto" class="shadow">
        </div>
        <div class="col-lg-7">
            <form novalidate>
                <small class="fs-6 fw-semibold bg-warning border-bottom p-1 d-block mb-2">Edita el importe del prestamo como creas necesario.</small>
                <div class="form-group">
                    <x-adminlte-input name="montoEditar" id="montoEditar" placeholder="Importe de prestamo">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-dollar-sign">*</i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <x-adminlte-input name="notaEditar" id="notaEditar" placeholder="Nota de prestamo">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <input type="hidden" name="idPrestamo" id="idPrestamo">
            </form>
        </div>
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="success" label=" Guardar" id="actualizar" icon="fas fa-save"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cancelar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>