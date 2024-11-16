jQuery.noConflict();
jQuery(document).ready(function(){

    $("#actualizar").attr('disabled', true);

    $(".editar").on('click', function(e){

        e.preventDefault();

        $("#nombreEditar").val('');
        $("#descripcionEditar").val('');
        $("#id").val('');

        var nombre = $(this).attr('data-value').split(',')[0];
        var descripcion = $(this).attr('data-value').split(',')[1];
        var id = $(this).attr('data-id');

        if( id === null || id === '' ){

            $("#actualizar").attr('disabled', true);

            Swal.fire({

                icon: 'error',
                title: 'Error de lectura',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,

            });

        }else{

            $("#nombreEditar").val( nombre );
            $("#descripcionEditar").val( descripcion );
            $("#id").val( id );

            $("#actualizar").attr('disabled', false);

        }

        

    });

});