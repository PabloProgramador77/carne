jQuery.noConflict();
jQuery(document).ready(function(){

    $("#imprimir").on('click', function(e){

        e.preventDefault();

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({

            type: 'POST',
            url: '/prestamo/imprimir',
            data:{

                'id' : $("#idPrestamo").val(),
                '_token' : csrfToken,

            },
            dataType: 'json',
            encode: true,

        }).done( function( respuesta ){

            if( respuesta.exito ){

                Swal.fire({

                    icon: 'success',
                    title: 'Prestamo reimpreso',
                    allowOutsideClick: false,
                    showConfirmButton: true,

                }).then( function( resultado){

                    if( resultado.isConfirmed ){

                        window.location.href = '/cliente/prestamos/'+$("#idCliente").val();

                    }

                });

            }else{

                Swal.fire({

                    icon: 'error',
                    title: respuesta.mensaje,
                    allowOutsideClick: false,
                    showConfirmButton: true,

                });

                $("#imprimir").attr('disabled', true);

            }

        });

    });

});