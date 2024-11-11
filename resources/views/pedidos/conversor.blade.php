<x-adminlte-modal id="modalConversor" size="lg" title="Conversor de unidades" theme="info" icon="fas fa-calculator" static-backdrop scrollable>
    <div class="container-fluid row">
        <div class="col-lg-4">
            <img src="{{ asset('img/carne.jpg') }}" alt="Cliente" width="100%" height="auto" style="margin: 0 auto;">
            <input type="text" readonly="true" class="bg-success rounded text-center" id="totalProductoConversor"></input>
            <input type="text" readonly="true" class="bg-light rounded d-block" id="nombreProductoConversor"></small>
            <input type="hidden" name="idProductoConversor" id="idProductoConversor">
        </div>
        <div class="col-lg-8">
            <x-adminlte-input name="unidades" id="unidades" placeholder="Cantidad"></x-adminlte-input>
            <x-adminlte-input name="resultado" id="resultado" readonly="true" value="0"></x-adminlte-input>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="primary" label=" Aceptar" id="aceptar" icon="fas fa-check-circle"></x-adminlte-button>
        <x-adminlte-button theme="danger" label=" Cerrar" id="cancelar" data-dismiss="modal" icon="fas fa-window-close"></x-adminlte-button>
    </x-slot>
</x-adminlte-modal>