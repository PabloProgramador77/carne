jQuery.noConflict();
jQuery(document).ready(function(){

    $("#imprimir").attr('disabled', true);

    $(".ver").on('click', function(e){

        e.preventDefault();

        $("#clientePedido").val('');
        $("#totalPedido").val('');
        $("#idPedido").val('');
        $("#fechaPedido").val('');

        var cliente = $(this).attr('data-value').split(',')[0];
        var total = $(this).attr('data-value').split(',')[1];
        var fecha = $(this).attr('data-value').split(',')[2];
        var id = $(this).attr('data-id');

        if( id === null || id === '' ){

            $("#imprimir").attr('disabled', true);

            Swal.fire({

                icon: 'error',
                title: 'Error de lectura',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,

            });

        }else{

            $("#clientePedido").text( cliente );
            $("#totalPedido").text('$ '+ total + ' MXN' );
            $("#fechaPedido").text( fecha );
            $("#idPedido").val( id );

            $("#imprimir").attr('disabled', false);

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'POST',
                url: '/pedido/buscar',
                data:{

                    'id' : id,
                    '_token' : csrfToken,

                },
                dataType: 'json',
                encode: true,

            }).done( function( respuesta ){

                if( respuesta.exito ){

                    if( respuesta.productos.length > 0){

                        var html = '<thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Monto</th></tr></thead>';

                        respuesta.productos.forEach( function( producto ){

                            html += '<tr>';
                            html += '<td>'+producto.nombre+'</td>';
                            html += '<td>$ '+producto.precio+' MXN</td>';
                            html += '<td>'+producto.cantidad+'</td>';
                            html += '<td>$ '+( producto.precio * producto.cantidad)+' MXN</td>';
                            html += '</tr>';

                        });

                        $("#contenedorProductos").empty();
                        $("#contenedorProductos").append( html );

                        $("#imprimir").attr('disabled', false);

                    }else{

                        Swal.fire({

                            icon: 'error',
                            title: 'Sin coincidencias en la búsqueda de productos',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
    
                        });

                        $("#imprimir").attr('disabled', true);

                    }

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

        }

    });

});