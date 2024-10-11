<x-adminlte-modal id="modalVentas" size="xl" title="Consulta de Ventas" theme="secondary" icon="fas fa-info-circle" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-12">
            <small class="p-1 bg-info d-block text-center rounded">A continuación se muestran todos los pedidos que se han realizado desde el último corte.</small>
        </div>
        @php
            $heads = ['Cliente', 'Total', 'Fecha', 'Estado'];
        @endphp
        <x-adminlte-datatable id="contenedorPedidosVenta" :heads="$heads" theme="light" striped hoverable bordered compressed beautify>
            
        </x-adminlte-datatable>
        
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>