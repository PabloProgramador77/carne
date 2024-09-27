jQuery.noConflict();
jQuery(document).ready(function(){

    $("#liquidacion").attr('disabled', true);

    $("#liquidar").on('click', function(e){

        e.preventDefault();

        $("#montoDeuda").val('');
        $("#idClienteDeuda").val('');

        var deuda = $(this).attr('data-value').split(',')[1];
        var id = $(this).attr('data-value').split(',')[0];

        if( id === null || id === '' ){

            $("#liquidacion").attr('disabled', true);

            Swal.fire({

                icon: 'error',
                title: 'Error de lectura',
                allowOutsideClick: false,
                showConfirmButton: true,

            });

        }else{

            $("#montoDeuda").val( deuda );
            $("#idClienteDeuda").val( id );

            $("#liquidacion").attr('disabled', false);

        }

    });

});