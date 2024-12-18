jQuery.noConflict();
jQuery(document).ready( function(){

    $("#ventas").on('click', function( e ){

        e.preventDefault();

        var procesamiento;

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({

            title: 'Buscando pedidos',
            html: 'Un momento por favor: <b></b>',
            timer: 9975,
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: ()=>{

                $.ajax({

                    type: 'POST',
                    url: '/pedidos/ventas',
                    data:{

                        '_token' : csrfToken,

                    },
                    dataType: 'json',
                    encode: true

                }).done(function(respuesta){

                    if( respuesta.exito ){

                        if( respuesta.pedidos.length > 0 ){

                            Swal.fire({

                                icon: 'success',
                                title: 'Pedidos encontrados',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
    
                            });

                            var total = 0;

                            var html = '<thead><tr><th>Cliente</th><th>Total</th><th>Fecha</th><th>Estado</th></thead>';

                            respuesta.pedidos.forEach( function( pedido ){

                                html += '<tr>';
                                html += '<td>'+pedido.nombre+'</td>';
                                html += '<td>$ '+pedido.total+' MXN</td>';
                                html += '<td>'+pedido.created_at+'</td>';
                                html += '<td>'+pedido.estado+'</td>';
                                html += '</tr>';

                                total += parseFloat( pedido.total );

                            });

                            html += '<tr class="bg-success text-center p-1"><td colspan="4">Total de Ventas: $ '+total+' MXN</td></tr>';

                            $("#contenedorPedidosVenta").empty();
                            $("#contenedorPedidosVenta").append( html );

                        }else{
                        
                            Swal.fire({

                                icon: 'error',
                                title: 'No hay pedidos registrados',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
    
                            });

                        }

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