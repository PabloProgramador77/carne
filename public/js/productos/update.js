jQuery.noConflict();
jQuery(document).ready(function(){

    $("#actualizar").on('click', function(e){

        e.preventDefault();

        let procesamiento;
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({

            title: 'Actualizando producto',
            html: 'Un momento por favor',
            timer: 9975,
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: ()=>{

                $.ajax({

                    type: 'POST',
                    url: '/producto/actualizar',
                    data:{

                        'nombre' : $("#nombreEditar").val(),
                        'descripcion' : $("#descripcionEditar").val(),
                        'id' : $("#id").val(),
                        '_token' : csrfToken,

                    },
                    dataType: 'json',
                    encode: true

                }).done(function(respuesta){

                    if( respuesta.exito ){

                        Swal.fire({

                            icon: 'success',
                            title: 'Producto actualizado',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,

                        }).then((resultado)=>{

                            if( resultado.dismiss === Swal.DismissReason.timer ){

                                window.location.href = '/productos';

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

                                window.location.href = '/productos';

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

                        window.location.href = '/productos';

                    }

                });

            }

        });

    });
    
});