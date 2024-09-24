<x-adminlte-modal id="modalVer" size="xl" title="Detalles de Corte" theme="info" icon="fas fa-info-circle" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-3">
            <p class="fs-5 fw-semibold p-1 border-bottom rounded" id="folioCorte"></p>
            <img src="{{ asset('img/corte.jpg') }}" alt="Cliente" width="100%" height="auto">
            <p class="fs-5 fw-semibold p-1 bg-success rounded text-center d-block" id="totalCorte"></p>
            <small class="p-1 bg-light rounded border d-block" id="fechaCorte"></small>
            <input type="hidden" name="idCorte" id="idCorte">
        </div>
        <div class="col-lg-9">
            @php
                $heads = ['Folio', 'Cliente', 'Total', 'Fecha'];
            @endphp
            <x-adminlte-datatable id="contenedorPedidos" :heads="$heads" theme="light" striped hoverable bordered compressed beautify>
                
            </x-adminlte-datatable>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="primary" label=" Imprimir" id="imprimirCorte" icon="fas fa-print"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cerrar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>