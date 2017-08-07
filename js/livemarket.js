var $livemarket_jquery = jQuery.noConflict();

$livemarket_jquery( document ).ready( function($) {
	$( '.livemarket_content' ).on( 'click', 'a', function(e) {
	    e.preventDefault();
		var data = {
			'action': 'livemarket_track_click',
			'nonce': livemarket_ajax.security,
			'slug': $( '.livemarket_content' ).data( 'slug' ),
			'target': $( this ).attr( 'href' ),
			'anchor': $( this ).text()
		};
		$.ajax({
			url: livemarket_ajax.ajax_url, 
			data: data,
			dataType: 'JSON',
			method: 'post'
		});
	    var url = $(this).attr( 'href' ); 
	    window.open( url, '_blank' );
	});
	$( '.livemarket_list' ).on( 'click', '.more a', function(e) {
	    e.preventDefault();
		var data = {
			'action': 'livemarket_list',
			'nonce': livemarket_ajax.security,
			'page': $( this ).data( 'page' ),
			'limit': $( this ).data( 'limit' ),
		};
		$.ajax({
			url: livemarket_ajax.ajax_url, 
			data: data,
			dataType: 'JSON',
			success: function( response ) {
				$( 'div.livemarket_list .more' ).remove();
				$( 'div.livemarket_list' ).append( response.data.html );
			},
			error: function( errorThrown ) {
				console.log( errorThrown );
			},
			method: 'post'
		});
	});
});