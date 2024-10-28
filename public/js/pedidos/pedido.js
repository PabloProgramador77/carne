jQuery.noConflict();
jQuery(document).ready( function(){

    $("#imprimirPedido").on('click', function( e ){

        e.preventDefault();

        var pesos = new Array();

        $("input[name=cantidad]").each( function(){

            if( $(this).val() !== null && $(this).val() !== '' ){

                pesos.push({

                    'producto' : $(this).attr('data-value').split(',')[0],
                    'precio' : $(this).attr('data-value').split(',')[1],
                    'cantidad' : $(this).val(),

                });

            }

        });

        if( pesos.length > 0 ){

            var procesamiento;

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            Swal.fire({

                title: 'Imprimiendo pedido',
                html: 'Un momento por favor: <b></b>',
                timer: 29975,
                allowOutsideClick: false,
                didOpen: ()=>{
    
                    Swal.showLoading();
                    const b = Swal.getHtmlContainer().querySelector('b');
                    procesamiento = setInterval(()=>{
    
                        b.textContent = Swal.getTimerLeft();
    
                    }, 100);
    
                    $.ajax({
    
                        type: 'POST',
                        url: '/pedido/pesos',
                        data:{
    
                            'pesos' : pesos,
                            'nota' : $("#notaPedido").val(),
                            'pedido' : $("#idPedido").val(),
                            '_token' : csrfToken,
    
                        },
                        dataType: 'json',
                        encode: true
    
                    }).done(function(respuesta){
    
                        if( respuesta.exito ){
    
                            Swal.fire({
    
                                icon: 'success',
                                title: 'Pedido terminado',
                                allowOutsideClick: false,
                                showConfirmButton: true
    
                            }).then((resultado)=>{
    
                                if( resultado.isConfirmed ){
    
                                    window.location.href = '/pedidos';
    
                                }
    
                            });
    
                        }else{
    
                            Swal.fire({
    
                                icon: 'error',
                                title: respuesta.mensaje,
                                allowOutsideClick: false,
                                showConfirmButton: true
    
                            }).then((resultado)=>{
    
                                if( resultado.isConfirmed ){
    
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
                        showConfirmButton: true
    
                    }).then((resultado)=>{
    
                        if( resultado.isConfirmed ){
    
                            window.location.href = '/pedidos';
    
                        }
    
                    });
    
                }
    
            });

        }else{

            Swal.fire({
    
                icon: 'info',
                title: 'Por favor introduce almenos el peso en un producto',
                allowOutsideClick: false,
                showConfirmButton: true

            });

        }

    });

});