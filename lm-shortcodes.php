<?php
/**
 * Registers zeen101's Live Market Shortcodes
 *
 * @package zeen101's Live Market
 * @since 1.0.0
 */
	
if ( !function_exists( 'do_livemarket' ) ) { 

	/**
	 * Shortcode for zeen101's Leaky Paywall
	 * Prints out the zeen101's Leaky Paywall
	 *
	 * @since 1.0.0
	 */
	function do_livemarket( $atts ) {
		
		$settings = get_livemarket_settings();
		
		if ( empty( $settings['api_key'] ) ) {
			return '<h1 class="error">' . __( 'You Must Enter a Valid Live Market API Key in the Live Market Plugin', 'livemarket' ) . '</h1>';
		}
		
		$store = get_query_var( 'store' );
		
		if ( empty( $store ) ) {
			$advertisements = get_livemarket_advertisements();
			if ( !empty( $advertisements->success ) && !empty( $advertisements->data ) ) {
				$results = '<ul>';
				foreach( $advertisements->data as $advertisement ) {
					if ( get_option( 'permalink_structure' ) ) {
						$results .= '<li><a href="' . get_permalink( $settings['livemarket_page'] ) . $advertisement->id . '">' . $advertisement->title . '</a></li>';
					} else {
						$results .= '<li><a href="' . get_permalink( $settings['livemarket_page'] ) . '?store=' . $advertisement->id . '">' . $advertisement->title . '</a></li>';
					}
				}
				$results .= '</ul>';
			} else {
				return '<h1 class="error">' . __( 'Unable to find marketplace stores.', 'livemarket' ) . '</h1>';
			}
		} else {
			$advertisement = get_livemarket_advertisement( $store );
			if ( !empty( $advertisement->success ) && !empty( $advertisement->data ) ) {
				$results = '<h3>' . $advertisement->data->title . '</h3>';
				$results .= $advertisement->data->content;
				$results .= '<a href="' . $advertisement->data->url . '">Click here</a>';
			} else {
				return '<h1 class="error">' . __( 'Unable to find marketplace store.', 'livemarket' ) . '</h1>';
			}
		}
		
		return $results;
		
	}
	add_shortcode( 'livemarket', 'do_livemarket' );
	
}