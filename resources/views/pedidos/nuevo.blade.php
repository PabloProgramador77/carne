<x-adminlte-modal id="modalNuevo" size="xl" title="Nuevo Pedido" theme="primary" icon="fas fa-plus-circle" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-12">
            <small class="p-1 bg-warning d-block text-center rounded">Elige el cliente para crear el nuevo pedido y presiona el botón <i class="fas fa-shopping-cart"></i> para comenzar el pedido</small>
        </div>
        
        @foreach( $clientes as $cliente )
            <div class="col-lg-2 col-md-6 col-sm-12">
                <x-adminlte-card theme-mode="outline" title="{{ $cliente->nombre }}" header-class="rounded-bottom border-primary">
                    <x-slot name="toolsSlot">
                        <img src="{{ asset('/img/cliente.jpg') }}" alt="Cliente" width="75%" height="auto" class="">
                        @if( $cliente->telefono === NULL || $cliente->telefono === '' )
                            <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block">Sin telefono agregado.</small>
                        @else
                            <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block"><b>Tel:</b> {{ $cliente->telefono }}</small>
                        @endif

                        @if( $cliente->domicilio === NULL || $cliente->domicilio === '' )
                            <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block">Sin domicilio agregado.</small>
                        @else
                            <small class="fs-6 fw-semibold text-secondary col-lg-12 d-block">{{ $cliente->domicilio }}</small>
                        @endif
                    </x-slot>
                    <x-slot name="footerSlot">
                        <a href="{{ url('pedido/cliente') }}/{{ $cliente->id }}" class="btn btn-success shadow rounded" title="Iniciar pedido"><i class="fas fa-shopping-cart"></i></a>
                    </x-slot>
                </x-adminlte-card>
            </div>
        @endforeach
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label=" Cancelar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>