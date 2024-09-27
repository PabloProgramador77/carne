<x-adminlte-modal id="modalTerminar" size="md" title="Nota de Pedido" theme="info" icon="fas fa-info-circle" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-12">
            <form>
                <x-adminlte-textarea name="notaPedido" id="notaPedido" placeholder="Nota de pedido (OPCIONAL)">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-info">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                    </x-slot>
                </x-adminlte-textarea>
            </form>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="primary" label=" Imprimir" id="imprimirPedido" icon="fas fa-print"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cerrar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>