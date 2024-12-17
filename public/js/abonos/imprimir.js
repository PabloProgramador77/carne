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
                    showConfirmButton: true,

                }).then( function( resultado){

                    if( resultado.isConfirmed ){

                        window.open('http://127.0.0.1:8000/tickets/reimpresionAbono'+$("#idAbono").val()+'.pdf', '_blank');

                        setTimeout( function(){
                            window.location.href = '/abonos';
                        }, 2000);

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