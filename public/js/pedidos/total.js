jQuery.noConflict();
jQuery( document ).ready( function(){

    $("input[name=cantidad]").change( function(){

        var total = 0;

        $("input[name=cantidad]").each( function(){

            if( $(this).val() === 0 || $(this).val() === '' || $(this).val() === null ){

                total += parseFloat( 0 );

            }else{

                total += parseFloat( $(this).val() * $(this).attr('data-value').split(',')[1] );

            }

        });

        console.log( total );
        $("#total").val( total );

    });

});