<x-adminlte-modal id="modalCorte" size="xl" title="Nuevo Corte" theme="warning" icon="fas fa-cash-register" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-12">
            <small class="p-1 bg-info d-block text-center rounded">A continuación se muestran todos los pedidos que van a ser parte del corte.</small>
        </div>
        @php
            $heads = ['Cliente', 'Total', 'Fecha'];
        @endphp
        <x-adminlte-datatable id="contenedorPedidos" :heads="$heads" theme="light" striped hoverable bordered compressed beautify>
            
        </x-adminlte-datatable>
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="success" label=" Imprimir" id="imprimirCorte" icon="fas fa-print"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>