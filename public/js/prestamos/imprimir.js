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

                        window.open('http://127.0.0.1:8000/tickets/reimpresionPrestamo'+$("#idPrestamo").val()+'.pdf', '_blank');

                        setTimeout( function(){
                            window.location.href = '/prestamos';
                        }, 2000);

                    }

                });

            }else{

                Swal.fire({

                    icon: 'error',
                    title: respuesta.mensaje,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,

                });

                $("#imprimir").attr('disabled', true);

            }

        });

    });

});