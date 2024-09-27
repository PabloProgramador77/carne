<x-adminlte-modal id="modalLiquidar" size="lg" title="Liquidar Deuda" theme="success" icon="fas fa-dollar-sign" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-5">
            <img src="{{ asset('img/abono.jpg') }}" alt="" width="100%" height="auto" class="shadow">
        </div>
        <div class="col-lg-7">
            <form novalidate>
                <small class="fs-6 fw-semibold bg-warning border-bottom p-1 d-block mb-2">Confirma que estas recibiendo la cantidad que se muestra a continuación:</small>
                <div class="form-group">
                    <x-adminlte-input name="montoDeuda" id="montoDeuda" placeholder="Importe de abono" readonly="true">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <x-adminlte-input name="notaLiquidar" id="notaLiquidar" placeholder="Liquidación" readonly="true">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-info">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </form>
            <input type="hidden" name="idClienteDeuda" id="idClienteDeuda">
        </div>
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="success" label=" Liquidar" id="liquidacion" icon="fas fa-hand-holding-usd"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cancelar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>