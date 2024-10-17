jQuery.noConflict();
jQuery(document).ready(function(){

    $("#actualizar").attr('disabled', true);

    $(".editar").on('click', function(e){

        e.preventDefault();

        $("#nombreEditar").val('');
        $("#idCaja").val('');

        var nombre = $(this).attr('data-value').split(',')[1];
        var id = $(this).attr('data-value').split(',')[0];

        console.log( 'Caja: ' + nombre );

        if( id === null || id === '' ){

            $("#actualizar").attr('disabled', true);

            Swal.fire({

                icon: 'error',
                title: 'Error de lectura',
                allowOutsideClick: false,
                showConfirmButton: true,

            });

        }else{

            $("#nombreEditar").val( nombre );
            $("#idCaja").val( id );

            $("#actualizar").attr('disabled', false);

        }

    });

});