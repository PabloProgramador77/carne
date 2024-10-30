jQuery.noConflict();
jQuery(document).ready(function(){

    $("#actualizar").attr('disabled', true);

    $(".editar").on('click', function(e){

        e.preventDefault();

        $("#nombreEditar").val('');
        $("#emailEditar").val('');
        $("#id").val('');

        var nombre = $(this).attr('data-value').split(',')[0];
        var email = $(this).attr('data-value').split(',')[1];
        var id = $(this).attr('data-id');
        var rol = $(this).attr('data-value').split(',')[2];

        if( id === null || id === '' ){

            $("#actualizar").attr('disabled', true);

            Swal.fire({

                icon: 'error',
                title: 'Error de lectura',
                allowOutsideClick: false,
                showConfirmButton: true,

            });

        }else{

            $("#nombreEditar").val( nombre );
            $("#emailEditar").val( email );
            $("#id").val( id );

            $("#rolEditar").prepend('<option value="'+rol+'">'+rol+'</option>');
            $("#rolEditar").val(rol);
            $("#rolEditar option[value='"+rol+"']:not(:first)").remove();

            $("#actualizar").attr('disabled', false);

        }

        

    });

});