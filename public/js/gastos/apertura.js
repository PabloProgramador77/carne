jQuery.noConflict();
jQuery( document ).ready( function(){

    $("#apertura").on('click', function( e ){

        Swal.fire({

            title: 'Ingresa el monto de caja para gastos',
            input: 'text',
            inputLabel: 'Importe de Gastos',
            inputPlaceholder: 'Ej: 3250.75, 1000',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',

        }).then( ( resultado)=>{

            if( resultado.isConfirmed && resultado.value ){

                let procesamiento;
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                Swal.fire({

                    title: 'Registrando importe de gastos',
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
                            url: '/caja/importe',
                            data:{
        
                                'importe' : resultado.value,
                                'caja' : $("#idCaja").val(),
                                '_token' : csrfToken,
        
                            },
                            dataType: 'json',
                            encode: true
        
                        }).done(function(respuesta){
        
                            if( respuesta.exito ){
        
                                Swal.fire({
        
                                    icon: 'success',
                                    title: 'Importe registrado',
                                    allowOutsideClick: false,
                                    showConfirmButton: true
        
                                }).then((resultado)=>{
        
                                    if( resultado.isConfirmed ){
        
                                        window.location.href = '/cajas';
        
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
        
                                        window.location.href = '/cajas';
        
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
        
                                window.location.href = '/cajas';
        
                            }
        
                        });
        
                    }
        
                });

            }

        });

    });

});