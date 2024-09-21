<x-adminlte-modal id="modalVer" size="xl" title="Detalles de Pedido" theme="info" icon="fas fa-info-circle" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-3">
            <p class="fs-5 fw-semibold p-1 border-bottom rounded" id="clientePedido"></p>
            <img src="{{ asset('img/cliente.jpg') }}" alt="Cliente" width="100%" height="auto">
            <p class="fs-5 fw-semibold p-1 bg-success rounded text-center d-block" id="totalPedido"></p>
            <small class="p-1 bg-light rounded border d-block" id="fechaPedido"></small>
            <input type="hidden" name="idPedido" id="idPedido">
        </div>
        <div class="col-lg-9">
            @php
                $heads = ['Producto', 'Precio', 'Cantidad', 'Monto'];
            @endphp
            <x-adminlte-datatable id="contenedorProductos" :heads="$heads" theme="light" striped hoverable bordered compressed beautify>
                
            </x-adminlte-datatable>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="primary" label=" Imprimir" id="imprimir" icon="fas fa-print"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cerrar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>