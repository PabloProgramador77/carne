jQuery.noConflict();
jQuery( document ).ready( function(){

    $("input[name=cantidad]").change( function(){

        var total = 0;

        $("#contenedorProductosPedido").empty();

        $("input[name=cantidad]").each( function(){

            if( $(this).val() === 0 || $(this).val() === '' || $(this).val() === null ){

                total += parseFloat( 0 );

            }else{

                total += parseFloat( $(this).val() * $(this).attr('data-value').split(',')[1] );

                var html = '<tbody class="container-fluid p-1 overflow-hidden">';
                html += '<tr><td class="text-center p-1 border">'+$(this).attr('data-value').split(',')[2]+'</td><td class="text-center p-1 border">'+parseFloat($(this).val()).toFixed(1)+' Kg/Gr</td></tr>';
                html += '</tbody>';

                $("#contenedorProductosPedido").append( html );

            }

        });

        console.log( total );
        $("#total").val( total );

    });

});