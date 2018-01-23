<?php
/**
 * Registers zeen101's LiveMarket API Functions
 *
 * @package zeen101's LiveMarket
 * @since 1.0.0
 */

/**
 * zeen101's LiveMarket API for retrieving all the user's publications
 *
 * @since 1.0.0
 *
 * @return mixed Value set for the issuem options.
 */
function get_livemarket_publications() {

	$settings = get_livemarket_settings();
	
	$args = array(
		'headers' => array(
			'Authorization' => 'Bearer ' . $settings['api_key'],
		)
	);
	$results = wp_remote_get( LIVEMARKET_API_URL . 'publications/', $args );
	$body = wp_remote_retrieve_body( $results );
	return json_decode( $body );
	
}


/**
 * zeen101's LiveMarket API for retrieving all a publications advertisements
 *
 * @since 1.0.0
 *
 * @return mixed Value set for the issuem options.
 */
function get_livemarket_advertisements( $page = 0, $limit = 10 ) {

	$settings = get_livemarket_settings();
	
	$args = array(
		'headers' => array(
			'Authorization' => 'Bearer ' . $settings['api_key'],
		)
	);
	$query = array(
		'page' => $page,
		'limit' => $limit,
	);
	$query = http_build_query( $query );
	$results = wp_remote_get( LIVEMARKET_API_URL . 'publication/' . $settings['publication_id'] . '/advertisements?' . $query, $args );		$body = wp_remote_retrieve_body( $results );
	return json_decode( $body );
	
}

/**
 * zeen101's LiveMarket API for retrieving a specific advertisement
 *
 * @since 1.0.0
 *
 * @return mixed Value set for the issuem options.
 */
function get_livemarket_advertisement( $advertisement_slug ) {

	$settings = get_livemarket_settings();
	
	$args = array(
		'headers' => array(
			'Authorization' => 'Bearer ' . $settings['api_key'],
		)
	);
	$results = wp_remote_get( LIVEMARKET_API_URL . 'publication/' . $settings['publication_id'] . '/advertisement/' . $advertisement_slug, $args );
	$body = wp_remote_retrieve_body( $results );
	return json_decode( $body );
	
}

/**
 * zeen101's LiveMarket API for tracking impressions
 *
 * @since 1.0.0
 *
 * @return mixed Value set for the issuem options.
 */
function livemarket_track_impressions( $advertisement_ids = array(), $data = array() ) {

	$settings = get_livemarket_settings();
	
	$args = array(
		'headers' => array(
			'Authorization' => 'Bearer ' . $settings['api_key'],
		),
		'body' => array( 'advertisements' => $advertisement_ids )
	);
	$results = wp_remote_post( LIVEMARKET_API_URL . 'publication/' . $settings['publication_id'] . '/advertisement/impressions', $args );
	$body = wp_remote_retrieve_body( $results );
	return json_decode( $body );
	
}

/**
 * zeen101's LiveMarket API for tracking views
 *
 * @since 1.0.0
 *
 * @return mixed Value set for the issuem options.
 */
function livemarket_track_view( $advertisement_slug, $data = array() ) {

	$settings = get_livemarket_settings();
	
	$args = array(
		'headers' => array(
			'Authorization' => 'Bearer ' . $settings['api_key'],
		),
		'body' => $data
	);
	$results = wp_remote_post( LIVEMARKET_API_URL . 'publication/' . $settings['publication_id'] . '/advertisement/' . $advertisement_slug . '/view', $args );
	$body = wp_remote_retrieve_body( $results );
	$f = fopen( 'output.txt', 'w' );
	fwrite( $f, $body );
	fclose( $f );
	return json_decode( $body );
	
}

/**
 * zeen101's LiveMarket API for tracking clicks
 *
 * @since 1.0.0
 *
 * @return mixed Value set for the issuem options.
 */
function livemarket_track_click( $advertisement_slug, $data = array() ) {
	
	$settings = get_livemarket_settings();
	
	$args = array(
		'headers' => array(
			'Authorization' => 'Bearer ' . $settings['api_key'],
		),
		'body' => $data
	);
	$results = wp_remote_post( LIVEMARKET_API_URL . 'publication/' . $settings['publication_id'] . '/advertisement/' . $advertisement_slug . '/click', $args );
	$body = wp_remote_retrieve_body( $results );
	return json_decode( $body );
	
}