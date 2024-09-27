jQuery.noConflict();
jQuery(document).ready(function(){

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

});