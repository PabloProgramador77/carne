jQuery.noConflict();
jQuery(document).ready( function(){

    $("#corte").on('click', function( e ){

        e.preventDefault();

        $("#imprimirCorte").attr('disabled', true);
        $("#caja").attr('disabled', true);

        var procesamiento;

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({

            title: 'Buscando pedidos',
            html: 'Un momento por favor',
            timer: 9975,
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: ()=>{

                $.ajax({

                    type: 'POST',
                    url: '/corte/nuevo',
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
                                showConfirmButton: true,
    
                            });

                            var total = 0;
                            var efectivo = 0;
                            var gastos = 0;
                            var abonos = 0;

                            var html = '<thead><tr><th>Cliente</th><th>Total</th><th>Estatus</th><th>Fecha</th></thead>';

                            respuesta.pedidos.forEach( function( pedido ){

                                if( pedido.estado === 'Pagado' ){

                                    efectivo += parseFloat( pedido.total );

                                }

                                html += '<tr>';
                                html += '<td>'+pedido.nombre+'</td>';
                                html += '<td>$ '+pedido.total+' MXN</td>';
                                html += '<td>'+pedido.estado+'</td>';
                                html += '<td>'+pedido.created_at+'</td>';
                                html += '</tr>';

                                total += parseFloat( pedido.total );

                            });

                            if( respuesta.gastos.length > 0 ){

                                html += '<thead><tr><th>Folio</th><th>Gasto</th><th>Total</th><th>Fecha</th></thead>';

                                respuesta.gastos.forEach( function( gasto ){

                                    efectivo -= parseFloat( gasto.monto );
                                    
                                    html += '<tr>';
                                    html += '<td>'+gasto.id+'</td>';
                                    html += '<td>'+gasto.descripcion+'</td>';
                                    html += '<td>$ '+gasto.monto+' MXN</td>';
                                    html += '<td>'+gasto.created_at+'</td>';
                                    html += '</tr>';

                                    gastos += parseFloat( gasto.monto );

                                });

                            }

                            if( respuesta.abonos.length > 0 ){

                                html += '<thead><tr><th>Folio</th><th>Abono</th><th>Nota</th><th>Fecha</th></tr></thead>';

                                respuesta.abonos.forEach( function( abono ){

                                    efectivo += parseFloat( abono.monto );

                                    html += '<tr>';
                                    html += '<td>'+abono.id+'</td>';
                                    html += '<td>'+abono.monto+'</td>';
                                    html += '<td>'+(abono.nota ? abono.nota : 'Sin nota')+'</td>';
                                    html += '<td>'+abono.created_at+'</td>';
                                    html += '</tr>';

                                    abonos += parseFloat( abono.monto );

                                });

                            }

                            html += '<tr class="bg-primary text-center p-1"><td colspan="4">Total de Ventas: $ '+total+' MXN</td></tr>';
                            html += '<tr class="bg-warning text-center p-1"><td colspan="4">Total de Gastos: $ '+gastos+' MXN</td></tr>';
                            html += '<tr class="bg-info text-center p-1"><td colspan="4">Total de Abonos: $ '+abonos+'</td></tr>';

                            if( efectivo <= 0 ){

                                html += '<tr class="bg-danger text-center p-1"><td colspan="4">Total de corte: $ '+efectivo+' MXN</td></tr>';

                            }else{

                                html += '<tr class="bg-success text-center p-1"><td colspan="4">Total de corte: $ '+efectivo+' MXN</td></tr>';

                            }
                            

                            $("#contenedorPedidosCorte").empty();
                            $("#contenedorPedidosCorte").append( html );

                            if( efectivo > 0 ){

                                $("#imprimirCorte").attr('disabled', false);
                                $("#caja").attr('disabled', false);
                                
                            }

                        }else{
                        
                            Swal.fire({

                                icon: 'error',
                                title: 'No hay pedidos para el corte',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
    
                            });

                            $("#imprimirCorte").attr('disabled', true);
                            $("#caja").attr('disabled', true);

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