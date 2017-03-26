<?php
/**
 * Registers zeen101's Live Market Functions
 *
 * @package zeen101's Live Market
 * @since 1.0.0
 */
	
if ( !function_exists( 'get_livemarket_settings' ) ) {

	/**
	 * Helper function to get zeen101's Leaky Paywall settings for current site
	 *
	 * @since 1.0.0
	 *
	 * @return mixed Value set for the issuem options.
	 */
	function get_livemarket_settings() {
	
		global $livemarket;
		
		return $livemarket->get_settings();
		
	}
	
}

if ( !function_exists( 'default_livemarket_content_filter' ) ) {

	/**
	 * Default content filter, adds the Live Market shortcode to the page set
	 * if that page has no content.
	 *
	 * @since 1.0.0
	 *
	 * @return string new content.
	 */
	function default_livemarket_content_filter( $content ) {
		
		global $post;
		
		$settings = get_livemarket_settings();
		
		if ( !empty( $post ) ) {
			if ( $post->ID == $settings['livemarket_page'] && empty( $content ) ) {
				$content = '[livemarket]';
			}
		}
		
		return $content;
		
	}
	add_filter( 'the_content', 'default_livemarket_content_filter', 5 );
	
}
