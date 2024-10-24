jQuery.noConflict();
jQuery(document).ready(function(){

    //Botón de editar
    $("#actualizar").attr('disabled', true);

    $(".editar").on('click', function(e){

        e.preventDefault();

        $("#montoEditar").val('');
        $("#idAbono").val('');
        $("#notaEditar").val('');

        var monto = $(this).attr('data-value').split(',')[1];
        var id = $(this).attr('data-value').split(',')[0];
        var nota = $(this).attr('data-value').split(',')[2];

        if( id === null || id === '' ){

            $("#actualizar").attr('disabled', true);

            Swal.fire({

                icon: 'error',
                title: 'Error de lectura',
                allowOutsideClick: false,
                showConfirmButton: true,

            });

        }else{

            $("#montoEditar").val( monto );
            $("#idAbono").val( id );
            $("#notaEditar").val( nota );

            $("#actualizar").attr('disabled', false);

        }

        

    });

    /**Boton de información */
    $(".ver").on('click', function(e){

        e.preventDefault();

        var id = $(this).attr('data-id');
        var monto = $(this).attr('data-value').split(',')[0];
        var nota = $(this).attr('data-value').split(',')[1];
        var cliente = $(this).attr('data-value').split(',')[2];
        var deuda = $(this).attr('data-value').split(',')[3];
        var fecha = $(this).attr('data-value').split(',')[4];

        $("#detallesAbono").empty();
        
        var html = '<tr><th>Folio</th><th>Importe</th><th>Nota</th><th>Fecha</th></tr>';

        html += '<tr><td>'+id+'</td><td>$ '+monto+'</td><td>'+nota+'</td><td>'+fecha+'</td></tr>';

        $("#deudaCliente").empty().text( 'Saldo: $ '+deuda );
        $("#clienteAbono").empty().text( cliente );
        $("#idAbono").val( id );

        $("#detallesAbono").append( html );

    });

});