jQuery.noConflict();
jQuery(document).ready( function(){

    $(".cobrar, .pagar").on('click', function( e ){

        e.preventDefault();

        var procesamiento;
        var pedido = $(this).attr('data-id');

        if( $(this).attr('id') === 'cobrar' ){

            var estado = 'Entregado';

        }else{

            var estado = 'Pagado';

        }

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({

            title: 'Actualizando pedido',
            html: 'Un momento por favor: <b></b>',
            timer: 9975,
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: ()=>{
                
                $.ajax({

                    type: 'POST',
                    url: '/pedido/pagar',
                    data:{

                        'pedido' : pedido,
                        'estado' : estado,
                        '_token' : csrfToken,

                    },
                    dataType: 'json',
                    encode: true

                }).done(function(respuesta){

                    if( respuesta.exito ){

                        Swal.fire({

                            icon: 'success',
                            title: 'Pedido '+ estado,
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

                            if( resultado.dismiss === Swal.DismissReason.timer ){

                                window.location.href = '/pedidos';

                            }

                        });

                    }

                });

            },
            willClose: ()=>{

                clearInterval(procesamiento);

            }

        }).then((resultado)=>{

            if( resultado.dismiss == Swal.DismissReason.timer ){

                Swal.fire({

                    icon: 'warning',
                    title: 'Hubo un inconveniente. Trata de nuevo.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,

                }).then((resultado)=>{

                    if( resultado.dismiss === Swal.DismissReason.timer ){

                        window.location.href = '/pedidos';

                    }

                });

            }

        });

    });

});