<x-adminlte-modal id="modalCorte" size="xl" title="Nuevo Corte" theme="warning" icon="fas fa-cash-register" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-12">
            <small class="p-1 bg-info d-block text-center rounded">A continuación se muestran todos los pedidos que van a ser parte del corte.</small>
        </div>
        @php
            $heads = ['Cliente', 'Total', 'Fecha'];
        @endphp
        <x-adminlte-datatable id="contenedorPedidosCorte" :heads="$heads" theme="light" striped hoverable bordered compressed beautify>
            
        </x-adminlte-datatable>
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-select id="caja" name="caja">
            <x-slot name="prependSlot">
                <div class="input-group-text">
                    <i class="fas fa-cash-register"></i>
                </div>
            </x-slot>
            <option value="0">Elige una caja</option>
            @foreach( $cajas as $caja)
                <option value="{{ $caja->id }}">{{ $caja->nombre }}</option>
            @endforeach
        </x-adminlte-select>
        <x-adminlte-button theme="success" label=" Imprimir" id="imprimirCorte" icon="fas fa-print" class="shadow mx-5"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>