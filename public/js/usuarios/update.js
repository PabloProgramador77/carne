jQuery.noConflict();
jQuery(document).ready(function(){

    $("#actualizar").on('click', function(e){

        e.preventDefault();

        let procesamiento;
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({

            title: 'Actualizando usuario',
            html: 'Un momento por favor',
            timer: 9975,
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: ()=>{

                $.ajax({

                    type: 'POST',
                    url: '/usuario/actualizar',
                    data:{

                        'nombre' : $("#nombreEditar").val(),
                        'email' : $("#emailEditar").val(),
                        'rol' : $("#rolEditar").val(),
                        'id' : $("#id").val(),
                        '_token' : csrfToken,

                    },
                    dataType: 'json',
                    encode: true

                }).done(function(respuesta){

                    if( respuesta.exito ){

                        Swal.fire({

                            icon: 'success',
                            title: 'Usuario actualizado',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,

                        }).then((resultado)=>{

                            if( resultado.dismiss === Swal.DismissReason.timer ){

                                window.location.href = '/usuarios';

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

                                window.location.href = '/usuarios';

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

                        window.location.href = '/usuarios';

                    }

                });

            }

        });

    });
    
});