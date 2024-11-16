jQuery.noConflict();
jQuery(document).ready(function(){

    $("#imprimir").on('click', function(e){

        e.preventDefault();

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({

            type: 'POST',
            url: '/abono/imprimir',
            data:{

                'id' : $("#idAbono").val(),
                '_token' : csrfToken,

            },
            dataType: 'json',
            encode: true,

        }).done( function( respuesta ){

            if( respuesta.exito ){

                Swal.fire({

                    icon: 'success',
                    title: 'Abono reimpreso',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,

                }).then( function( resultado){

                    if( resultado.dismiss === Swal.DismissReason.timer ){

                        window.location.href = '/cliente/abonos/'+$("#idCliente").val();

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