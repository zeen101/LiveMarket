var $livemarket_jquery = jQuery.noConflict();

$livemarket_jquery( document ).ready( function($) {
	if ( $( '.livemarket_content' ).length ) {
		var data = {
			'action': 'livemarket_track_view',
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
	}
	
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
	
	$( '.livemarket_shortcode_list' ).on( 'click', '.more a', function(e) {
	    e.preventDefault();
		var data = {
			'action': 'livemarket_shortcode_list',
			'nonce': livemarket_ajax.security,
			'page': $( this ).data( 'page' ),
			'limit': $( this ).data( 'limit' ),
		};
		$.ajax({
			url: livemarket_ajax.ajax_url, 
			data: data,
			dataType: 'JSON',
			success: function( response ) {
				$( 'div.livemarket_shortcode_list .more' ).remove();
				$( 'div.livemarket_shortcode_list' ).append( response.data.html );
			},
			error: function( errorThrown ) {
				console.log( errorThrown );
			},
			method: 'post'
		});
	});
	
	$( '.livemarket_widget_list' ).on( 'click', '.more a', function(e) {
	    e.preventDefault();
		var data = {
			'action': 'livemarket_widget_list',
			'nonce': livemarket_ajax.security,
			'page': $( this ).data( 'page' ),
			'limit': $( this ).data( 'limit' ),
		};
		$.ajax({
			url: livemarket_ajax.ajax_url, 
			data: data,
			dataType: 'JSON',
			success: function( response ) {
				$( 'div.livemarket_widget_list .more' ).remove();
				$( 'div.livemarket_widget_list' ).append( response.data.html );
			},
			error: function( errorThrown ) {
				console.log( errorThrown );
			},
			method: 'post'
		});
	});

	if ( $('.livemarket-flyout-container').length > 0 ) {
		if (document.cookie.split(';').filter(function(item) {
		    return item.indexOf('lmhideflyout=') >= 0
		}).length) {
			$('.livemarket-flyout-container').remove();
		} else {
			$('.livemarket-flyout-header').toggleClass('active');
		}
	}

	$('.livemarket-flyout-header-link').click(function(e) {
		e.preventDefault();
		$('.livemarket-flyout-header').toggleClass('active');
		$('.livemarket-flyout-content').toggleClass('active');
	});

	$('.livemarket-flyout-content-close').click(function(e) {
		e.preventDefault();
		$('.livemarket-flyout-header').toggleClass('active');
		$('.livemarket-flyout-content').toggleClass('active');
		$('.livemarket-flyout-container').remove();
		// set cookie not to show it
		document.cookie = "lmhideflyout=true; path=/";

	});

	setTimeout(
		function() 
		{

			if ( $('.livemarket-flyout-content').hasClass('active') ) {
				
			} else {
				$('.livemarket-flyout-header-link').trigger('click');
			}
		  
		}, 
	10000);

	
});