<x-adminlte-modal id="modalNuevo" size="xl" title="Nuevo Pedido" theme="primary" icon="fas fa-plus-circle" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-12">
            <small class="p-1 bg-warning d-block text-center rounded">Elige el cliente para crear el nuevo pedido y presiona el botón <i class="fas fa-shopping-cart"></i> para comenzar el pedido</small>
        </div>
        
        @if( count( $clientes ) > 0)

            @php
                $heads = ['Folio', 'Cliente', 'Telefono', 'Domicilio', ''];
            @endphp
            <x-adminlte-datatable id="contenedorClientesPedido" theme="light" :heads="$heads" striped hoverable compressed beautify>
                @foreach( $clientes as $cliente )
                    <tr>
                        <td>{{ $cliente->id }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ ( $cliente->telefono ? : 'Sin telefono' ) }}</td>
                        <td>{{ ( $cliente->domicilio ? : 'Sin domicilio') }}</td>
                        <td>
                            <a href="{{ url('pedido/cliente') }}/{{ $cliente->id }}" class="btn btn-success shadow rounded" title="Iniciar pedido"><i class="fas fa-shopping-cart"></i></a>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        @else
            <p class="p-1 text-danger text-center d-block"><i class="fas fa info-circle"></i> Sin clientes registrados</p>
        @endif
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label=" Cancelar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>