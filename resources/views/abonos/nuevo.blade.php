<x-adminlte-modal id="modalNuevo" size="lg" title="Nuevo Abono" theme="primary" icon="fas fa-plus-circle" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-4">
            <img src="{{ asset('img/abono.jpg') }}" alt="" width="75%" height="auto" class="shadow m-auto">
        </div>
        <div class="col-lg-8">
            <form novalidate>
                <small class="fs-6 fw-semibold bg-warning border-bottom p-1 d-block mb-2">Los campos con * son obligatorios</small>
                <div class="form-group">
                    <x-adminlte-input name="monto" id="monto" placeholder="Importe de abono">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-dollar-sign">*</i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <x-adminlte-input name="nota" id="nota" placeholder="Nota de abono">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </form>
        </div>
        <div class="col-lg-12">
            <span class="bg-info text-center p-1 d-block">Elige los pedidos a abonar</span>
            @php
                $heads = ['[]', 'Folio', 'Total', 'Estado'];
            @endphp
            <x-adminlte-datatable id="contenedorPedidosAbono" theme="light" :heads="$heads" striped hoverable compressed beautify>

            </x-adminlte-datatable>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="success" label=" Guardar" id="registrar" icon="fas fa-save"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cancelar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>