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

if ( !function_exists( 'formatted_livemarket_advertisements' ) ) {

	/**
	 * Default content filter, adds the Live Market shortcode to the page set
	 * if that page has no content.
	 *
	 * @since 1.0.0
	 *
	 * @return string new content.
	 */
	function formatted_livemarket_advertisements( $page = 0, $limit = 10 ) {
		
		$settings = get_livemarket_settings();
		$dateformat = get_option( 'date_format' );
		
		$advertisements = get_livemarket_advertisements( $page, $limit );

		if ( !empty( $advertisements->success ) && !empty( $advertisements->data ) ) {
			$return  = '';
			foreach( $advertisements->data->advertisements as $advertisement ) {
				if ( get_option( 'permalink_structure' ) ) {
					$link = get_permalink( $settings['livemarket_page'] ) . $advertisement->id;
				} else {
					$link = get_permalink( $settings['livemarket_page'] ) . '?store=' . $advertisement->id;
				}
				$return .= '<p>';
				$return .= '<span class="livemarket_title"><a href="' . $link . '">' . $advertisement->title . '</a></span><br />';
				$return .= '<span class="livemarket_meta livemarket_companyname">' . __( 'by', 'livemarket' ). ' ' . $advertisement->displayname . '</span> ';
				$return .= '<span class="livemarket_meta livemarket_date"> - ' . date_i18n( $dateformat, strtotime( get_date_from_gmt( $advertisement->created_at ) ) ) . '</span>';
				$return .= '</p>';
			}
			$return .= '<p>';
			$prev_page = $page - 1;
			$next_page = $page + 1;
			if ( 0 < $page ) {
				$return .= '<span class="newer"><a href="#" data-page="' . $prev_page . '" data-limit="' . $limit . '">' . __( 'Newer', 'livemarket' ) . '</a></span>';
			} else {
				$return .= '<span class="newer"><a class="hidden" href="#" data-page="0" data-limit="' . $limit . '">' . __( 'Newer', 'livemarket' ) . '</a></span>';
			}
			if ( 10 < $advertisements->data->total && ( $next_page * $limit < $advertisements->data->total ) ) {
				$return .= '<span class="older"><a href="#" data-page="' . $next_page . '" data-limit="' . $limit . '">' . __( 'Older', 'livemarket' ) . '</a></span>';
			} else {
				$return .= '<span class="older"><a class="hidden" href="#" data-page="' . $next_page . '" data-limit="' . $limit . '">' . __( 'Older', 'livemarket' ) . '</a></span>';
			}
		
			$return .= '</p>';
		} else {
			$return = '<h1 class="error">' . __( 'Unable to find marketplace stores.', 'livemarket' ) . '</h1>';
		}
		
		return $return;
		
	}
	
}
