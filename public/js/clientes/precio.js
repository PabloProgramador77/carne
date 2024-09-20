jQuery.noConflict();
jQuery(document).ready( function(){

    $("#guardar").on('click', function( e ){

        e.preventDefault();

        var precios = new Array();

        $("input[name=precio]").each( function(){

            if( $(this).val() !== null && $(this).val() !== '' ){

                precios.push({

                    'producto' : $(this).attr('data-value'),
                    'precio' : $(this).val(),

                });

            }

        });

        if( precios.length > 0 ){

            var procesamiento;

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            Swal.fire({

                title: 'Registrando precios',
                html: 'Un momento por favor: <b></b>',
                timer: 9975,
                allowOutsideClick: false,
                didOpen: ()=>{
    
                    Swal.showLoading();
                    const b = Swal.getHtmlContainer().querySelector('b');
                    procesamiento = setInterval(()=>{
    
                        b.textContent = Swal.getTimerLeft();
    
                    }, 100);
    
                    $.ajax({
    
                        type: 'POST',
                        url: '/cliente/precios',
                        data:{
    
                            'precios' : precios,
                            'cliente' : $("#idCliente").val(),
                            '_token' : csrfToken,
    
                        },
                        dataType: 'json',
                        encode: true
    
                    }).done(function(respuesta){
    
                        if( respuesta.exito ){
    
                            Swal.fire({
    
                                icon: 'success',
                                title: 'Precios registrados',
                                allowOutsideClick: false,
                                showConfirmButton: true
    
                            }).then((resultado)=>{
    
                                if( resultado.isConfirmed ){
    
                                    window.location.href = '/cliente/productos/'+$("#idCliente").val();
    
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
    
                                    window.location.href = '/cliente/productos/'+$("#idCliente").val();
    
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
    
                            window.location.href = '/cliente/productos/'+$("#idCliente").val();
    
                        }
    
                    });
    
                }
    
            });

        }else{

            Swal.fire({
    
                icon: 'info',
                title: 'Por favor introduce almenos el precio en un producto',
                allowOutsideClick: false,
                showConfirmButton: true

            });

        }

    });

});