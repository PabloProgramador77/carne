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
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,

            }).then((resultado)=>{

                if( resultado.dismiss === Swal.DismissReason.timer ){

                    window.location.href = '/cortes';

                }

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

                    if( (respuesta.pedidos && respuesta.pedidos.length > 0) || ( respuesta.gastos && respuesta.gastos.length > 0 ) || ( respuesta.abonos && respuesta.abonos.length > 0 ) ){

                        var html = '<thead><tr><th>Folio</th><th>Cliente</th><th>Total</th><th>Fecha</th></tr></thead>';

                        if( respuesta.pedidos && respuesta.pedidos.length > 0 ){
                        
                            respuesta.pedidos.forEach( function( pedido ){

                                html += '<tr>';
                                html += '<td>'+pedido.id+'</td>';
                                html += '<td>'+pedido.nombre+'</td>';
                                html += '<td>$ '+pedido.total+'</td>';
                                html += '<td>'+pedido.created_at+'</td>';
                                html += '</tr>';
    
                            });

                        }

                        if( respuesta.gastos && respuesta.gastos.length > 0 ){

                            html += '<tr><td><b>Folio</b></td><td><b>Gasto</b></td><td><b>Importe</b></td><td><b>Fecha</b></td></tr>';

                            respuesta.gastos.forEach( function( gasto ){

                                html += '<tr>';
                                html += '<td>'+gasto.id+'</td>';
                                html += '<td>'+gasto.descripcion+'</td>';
                                html += '<td>$ '+gasto.monto+'</td>';
                                html += '<td>'+gasto.created_at+'</td>';
                                html += '</tr>';

                            });

                        }

                        if( respuesta.abonos && respuesta.abonos.length > 0 ){
                        
                            html += '<tr><td><b>Folio</b></td><td><b>Abono</b></td><td><b>Importe</b></td><td><b>Fecha</b></td></tr>';

                            respuesta.abonos.forEach( function( abono ){

                                html += '<tr>';
                                html += '<td>'+abono.id+'</td>';
                                html += '<td>'+abono.nota+'</td>';
                                html += '<td>$ '+abono.monto+'</td>';
                                html += '<td>'+abono.created_at+'</td>';
                                html += '</tr>';

                            });

                        }
                        
                        $("#contenedorPedidos").empty().append( html );

                        $("#imprimirCorte").attr('disabled', false);

                    }else{
                    
                        Swal.fire({

                            icon: 'info',
                            title: 'Sin coincidencias en la búsqueda de pedidos del corte',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
    
                        }).then( (resultado)=>{

                            if( resultado.dismiss === Swal.DismissReason.timer ){

                                window.location.href = '/cortes';

                            }
                            
                        });

                    }

                }else{

                    $("#imprimirCorte").attr('disabled', true);

                    Swal.fire({

                        icon: 'error',
                        title: respuesta.mensaje,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,

                    }).then( (resultado)=>{

                        if( resultado.dismiss === Swal.DismissReason.timer ){

                            window.location.href = '/cortes';

                        }
                        
                    });

                }

            });

        }

    });

});