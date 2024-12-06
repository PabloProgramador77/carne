jQuery.noConflict();
jQuery(document).ready( function(){

    var total = 0;

    $(".conversor").on('click', function( e ){

        e.preventDefault();

        var id = $(this).attr('data-value').split(',')[0];
        var nombre = $(this).attr('data-value').split(',')[1];
        var precio = $(this).attr('data-value').split(',')[2];

        if( id === 0 || id === '' || id === null || id === undefined ){

            Swal.fire({

                icon: 'warning',
                title: 'Error de lectura del producto',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,

            });

            $("#aceptar").attr('disabled', true);

        }else{

            $("#totalProductoConversor").empty();
            $("#totalProductoConversor").val( precio );
            $("#nombreProductoConversor").empty();
            $("#nombreProductoConversor").val( nombre );
            $("#idProductoConversor").empty();
            $("#idProductoConversor").val( id );
            
            $("#unidades").val( 0 );
            $("#resultado").val( 0 );

            $("#aceptar").attr('disabled', false);

            $("#unidades").focus();

        }

    });

    $("#unidades").keyup(function(){

        var unidades = parseFloat( $(this).val() );

        total = parseFloat( (( unidades * 1 ) / $("#totalProductoConversor").val()) ).toFixed(2);

        $("#resultado").empty();
        $("#resultado").val( total );

    });

    $("#aceptar").on('click', function(){

        $("input[name=cantidad][data-id="+$("#idProductoConversor").val()+"]").val( total );

        $("#modalConversor").css('display', 'none');
        $(".modal-backdrop").remove();
        $("body").css('overflow', 'auto');

        var totalPedido = 0;

        $("input[name=cantidad]").each( function(){

            if( $(this).val() === 0 || $(this).val() === '' || $(this).val() === null ){

                totalPedido += parseFloat( 0 );

            }else{

                totalPedido += parseFloat( $(this).val() * $(this).attr('data-value').split(',')[1] );

            }

        });

        console.log( totalPedido );
        $("#total").val( totalPedido );

    });

});