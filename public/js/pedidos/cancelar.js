jQuery.noConflict();
jQuery(document).ready(function(){

    $("#cancelar").on('click', function(e){

        e.preventDefault();

        Swal.fire({

            icon: 'warning',
            title: '¿En verdad deseas cancelar el pedido de '+$(this).attr('data-value')+'?',
            html: 'Los datos no podrán ser recuperados de ninguna manera.',
            allowOutsideClick: false,
            confirmButtonText: 'Si, cancelalo',
            showConfirmButton: true,
            showDenyButton: true,

        }).then((resultado)=>{

            if( resultado.isConfirmed ){

                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({

                    type: 'POST',
                    url: '/pedido/cancelar',
                    data:{

                        'id' : $(this).attr('data-id'),
                        '_token' : csrfToken,

                    },
                    dataType: 'json',
                    encode: true

                }).done(function(respuesta){

                    if( respuesta.exito ){

                        Swal.fire({

                            icon: 'success',
                            title: 'Pedido cancelado.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,

                        }).then((resultado)=>{

                            if( resultado.dismiss === Swal.DismissReason.timer ){

                                window.location.href = '/pedidos';

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

                        }).then((resultado)=>{

                            if( resultado.dismiss == Swal.DismissReason.timer ){

                                window.location.href = '/pedidos';

                            }

                        });

                    }

                });

            }

        });

    });

});