jQuery.noConflict();
jQuery(document).ready(function(){

    $("#actualizar").attr('disabled', true);

    $(".editar").on('click', function(e){

        e.preventDefault();

        $("#nombreEditar").val('');
        $("#telefonoEditar").val('');
        $("#domicilioEditar").val('');
        $("#id").val('');

        var nombre = $(this).attr('data-value').split(',')[0];
        var telefono = $(this).attr('data-value').split(',')[1];
        var domicilio = $(this).attr('data-value').split(',')[2];
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

            }).then( (resultado)=>{

                if( resultado.dismiss === Swal.DismissReason.timer ){

                    window.location.href = '/clientes';
                    
                }

            });

        }else{

            $("#nombreEditar").val( nombre );
            $("#telefonoEditar").val( telefono );
            $("#domicilioEditar").val( domicilio );
            $("#id").val( id );

            $("#actualizar").attr('disabled', false);

        }

        

    });

});