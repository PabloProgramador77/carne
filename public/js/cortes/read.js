jQuery.noConflict();
jQuery( document ).ready( function( e ){

    $(".ver").on('click', function( e ){

        e.preventDefault();

        var id = $(this).attr('data-value').split(', ')[0];

        if( id === 0 || id === '' || id === null ){
            
            $("#imprimirCorte").attr('disabled', true);

            Swal.fire({

                icon: 'warning',
                title: 'Error de lectura',
                allowOutsideClick: false,
                showConfirmButton: true,

            });

        }else{

            var total = $(this).attr('data-value').split(',')[1];
            var fecha = $(this).attr('data-value').split(',')[2];

            $("#folioCorte").text( 'Folio: ' + id );
            $("#totalCorte").text( 'Total: $ ' + total );
            $("#fechaCorte").text( 'Fecha: ' + fecha );
            $("#idCorte").val( id );

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'POST',
                url: '/corte/buscar',
                data:{

                    '_token' : csrfToken,
                    'id' : id,

                },
                dataType: 'json',
                encode: true,

            }).done( function( respuesta ){

                if( respuesta.exito ){

                    if( respuesta.pedidos && respuesta.pedidos.length > 0 ){

                        var html = '<thead><tr><th>Folio</th><th>Cliente</th><th>Total</th><th>Fecha</th></tr></thead>';

                        respuesta.pedidos.forEach( function( pedido ){

                            html += '<tr>';
                            html += '<td>'+pedido.id+'</td>';
                            html += '<td>'+pedido.nombre+'</td>';
                            html += '<td>$ '+pedido.total+'</td>';
                            html += '<td>'+pedido.created_at+'</td>';
                            html += '</tr>';

                        });

                        $("#contenedorPedidos").empty().append( html );

                        $("#imprimirCorte").attr('disabled', false);

                    }else{
                    
                        Swal.fire({

                            icon: 'info',
                            title: 'Sin coincidencias en la búsqueda de pedidos del corte',
                            allowOutsideClick: false,
                            showConfirmButton: true,
    
                        });

                    }

                }else{

                    $("#imprimirCorte").attr('disabled', true);

                    Swal.fire({

                        icon: 'error',
                        title: respuesta.mensaje,
                        allowOutsideClick: false,
                        showConfirmButton: true,

                    });

                }

            });

        }

    });

});