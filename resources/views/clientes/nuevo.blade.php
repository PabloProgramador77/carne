<x-adminlte-modal id="modalNuevo" size="lg" title="Nuevo Cliente" theme="primary" icon="fas fa-plus-circle" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-5">
            <img src="{{ asset('img/cliente.jpg') }}" alt="" width="100%" height="auto" class="shadow">
        </div>
        <div class="col-lg-7">
            <form novalidate>
                <p class="fs-6 fw-semibold bg-light border-bottom p-1">Los campos con * son obligatorios</p>
                <div class="form-group">
                    <x-adminlte-input name="nombre" id="nombre" placeholder="Nombre de cliente">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-user">*</i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="form-group">
                    <x-adminlte-input name="telefono" id="telefono" placeholder="Telefono de cliente">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-phone"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="form-group">
                    <x-adminlte-input name="domicilio" id="domicilio" placeholder="Domicilio de cliente">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </form>
        </div>
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="success" label=" Guardar" id="registrar" icon="fas fa-save"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cancelar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>