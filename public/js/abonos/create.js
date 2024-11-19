jQuery.noConflict();
jQuery(document).ready(function(){

    var pedidos = [];

    $("#nuevo").on('click', function(){

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({

            type: 'POST',
            url: '/abono/pedidos',
            data:{

                'cliente' : $("#idCliente").val(),
                '_token' : csrfToken,

            },
            dataType: 'json',
            encode: true

        }).done(function(respuesta){

            if( respuesta.exito ){

                var html = '<thead><tr><th>[]</th><th>Folio</th><th>Total</th><th>Estado</th><th>Fecha</th></tr></thead>';

                if( respuesta.pedidos.length > 0 ){

                    respuesta.pedidos.forEach( function( pedido ){

                        html += '<tr>';
                        html += '<td><input type="checkbox" name="pedido" id="pedido'+pedido.id+'" data-value="'+pedido.id+'" value="'+pedido.total+'"/></td>';
                        html += '<td>'+pedido.id+'</td>';
                        html += '<td>$ '+pedido.total+'</td>';
                        html += '<td><span class="bg-teal p-1 text-center rounded">'+pedido.estado+'</span></td>';
                        html += '<td>'+pedido.created_at+'</td>'
                        html += '</tr>';

                    });

                    $("#contenedorPedidosAbono").empty();
                    $("#contenedorPedidosAbono").append( html );

                    var abono = 0;

                    $("input[name=pedido][type=checkbox]").change( function(){

                        abono = 0;

                        $("input[name=pedido][type=checkbox]:checked").each( function(){

                            pedidos.push( $(this).attr('data-value') );
                            
                            abono += parseFloat( $(this).val() );

                        });

                        $("#monto").val( abono );

                    });

                }else{

                    html += '<tr><td><i class="fas fa-info-circle"></i> Sin pedidos para abonar</td></tr>';

                    $("#contenedorPedidosAbono").empty();
                    $("#contenedorPedidosAbono").append( html );

                }

                console.log( pedidos );
                
            }else{

                Swal.fire({

                    icon: 'error',
                    title: respuesta.mensaje,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,

                }).then((resultado)=>{

                    if( resultado.dismiss === Swal.DismissReason.timer ){

                        window.location.href = '/cliente/abonos/'+$("#idCliente").val();

                    }

                });

            }

        });

    });

    $("#registrar").on('click', function(e){

        e.preventDefault();

        let procesamiento;
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({

            icon: 'info',
            title: 'Registrando abono',
            html: 'Un momento por favor',
            timer: 29975,
            timerProgressBar: true,
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: ()=>{

                $.ajax({

                    type: 'POST',
                    url: '/abono/agregar',
                    data:{

                        'monto' : $("#monto").val(),
                        'nota' : $("#nota").val(),
                        'cliente' : $("#idCliente").val(),
                        'pedidos' : pedidos,
                        '_token' : csrfToken,

                    },
                    dataType: 'json',
                    encode: true

                }).done(function(respuesta){

                    if( respuesta.exito ){

                        Swal.fire({

                            icon: 'success',
                            title: 'Abono registrado',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,

                        }).then((resultado)=>{

                            if( resultado.dismiss === Swal.DismissReason.timer ){

                                window.location.href = '/cliente/abonos/'+$("#idCliente").val();

                            }

                        });

                    }else{

                        Swal.fire({

                            icon: 'error',
                            title: respuesta.mensaje,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,

                        }).then((resultado)=>{

                            if( resultado.dismiss === Swal.DismissReason.timer ){

                                window.location.href = '/cliente/abonos/'+$("#idCliente").val();

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
                    timer: 1500,
                    timerProgressBar: true,

                }).then((resultado)=>{

                    if( resultado.dismiss == Swal.DismissReason.timer ){

                        window.location.href = '/cliente/abonos/'+$("#idCliente").val();

                    }

                });

            }

        });

    });
    
});