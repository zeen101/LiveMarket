<?php
/**
 * Registers zeen101's Live Market API Functions
 *
 * @package zeen101's Live Market
 * @since 1.0.0
 */
	
if ( !function_exists( 'get_livemarket_publications' ) ) {

	/**
	 * zeen101's Live Market API for retrieving all the user's publications
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
	
}
	
if ( !function_exists( 'get_livemarket_advertisements' ) ) {

	/**
	 * zeen101's Live Market API for retrieving all a publications advertisements
	 *
	 * @since 1.0.0
	 *
	 * @return mixed Value set for the issuem options.
	 */
	function get_livemarket_advertisements() {
	
		$settings = get_livemarket_settings();
		
		$args = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $settings['api_key'],
			)
		);
		$results = wp_remote_get( LIVEMARKET_API_URL . 'publication/' . $settings['publication_id'] . '/advertisements', $args );
		$body = wp_remote_retrieve_body( $results );
		return json_decode( $body );
		
	}
	
}
	
if ( !function_exists( 'get_livemarket_advertisement' ) ) {

	/**
	 * zeen101's Live Market API for retrieving a specific advertisement
	 *
	 * @since 1.0.0
	 *
	 * @return mixed Value set for the issuem options.
	 */
	function get_livemarket_advertisement( $advertisement_id ) {
	
		$settings = get_livemarket_settings();
		
		$args = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $settings['api_key'],
			)
		);
		$results = wp_remote_get( LIVEMARKET_API_URL . 'publication/' . $settings['publication_id'] . '/advertisement/' . $advertisement_id, $args );
		$body = wp_remote_retrieve_body( $results );
		return json_decode( $body );
		
	}
	
}
