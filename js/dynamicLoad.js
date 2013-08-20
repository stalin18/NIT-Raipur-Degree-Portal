

   	$( document ).ready( function() {  
	
		$('a.dynamicLoad').bind('click' , function( e ) {
            e.preventDefault();   // prevent the browser from following the link
            e.stopPropagation();  // prevent the browser from following the link
		
		
            $( '#part1' ).load( $( this ).attr( 'href' ) , function(){ $('#loading').remove(); });
	    window.location.hash = $(this).attr('href').split('=')[1];
	    
			
        });


    

     
	
    });
