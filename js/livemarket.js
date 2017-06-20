var $livemarket_jquery = jQuery.noConflict();

$livemarket_jquery( document ).ready( function($) {
	$( '.livemarket_content a' ).on( 'click', function(e) {
	    e.preventDefault(); 
	    var url = $(this).attr( 'href' ); 
	    window.open( url, '_blank' );
	});
});