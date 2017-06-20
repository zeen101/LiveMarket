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
		$dateformat = get_option( 'date_format' );
		
		if ( empty( $settings['api_key'] ) ) {
			return '<h1 class="error">' . __( 'You Must Enter a Valid Live Market API Key in the Live Market Plugin', 'livemarket' ) . '</h1>';
		}
		
		$store = get_query_var( 'store' );
		
		if ( empty( $store ) ) {
			$advertisements = get_livemarket_advertisements();
			if ( !empty( $advertisements->success ) && !empty( $advertisements->data ) ) {
				$results  = '<div class="livemarket_list">';
				foreach( $advertisements->data as $advertisement ) {
					if ( get_option( 'permalink_structure' ) ) {
						$link = get_permalink( $settings['livemarket_page'] ) . $advertisement->id;
					} else {
						$link = get_permalink( $settings['livemarket_page'] ) . '?store=' . $advertisement->id;
					}
					$results .= '<p>';
					$results .= '<span class="livemarket_title"><a href="' . $link . '">' . $advertisement->title . '</a></span><br />';
					$results .= '<span class="livemarket_meta livemarket_companyname">' . __( 'by', 'livemarket' ). ' ' . $advertisement->displayname . '</span>';
					$results .= '<span class="livemarket_meta livemarket_date"> - ' . date_i18n( $dateformat, strtotime( get_date_from_gmt( $advertisement->created_at ) ) ) . '</span>';
					$results .= '</p>';
				}
				$results .= '</div>';
			} else {
				return '<h1 class="error">' . __( 'Unable to find marketplace stores.', 'livemarket' ) . '</h1>';
			}
		} else {
			$advertisement = get_livemarket_advertisement( $store );
			if ( !empty( $advertisement->success ) && !empty( $advertisement->data ) ) {
				$results  = '<div class="livemarket_content">';
				$results .= '<h3>' . $advertisement->data->title . '</h3>';
				$results .= '<p><span class="livemarket_meta livemarket_companyname">' . __( 'by', 'livemarket' ). ' ' . $advertisement->data->displayname . '</span>';
				$results .= '<span class="livemarket_meta livemarket_date"> - ' . date_i18n( $dateformat, strtotime( get_date_from_gmt( $advertisement->data->created_at ) ) ) . '</span></p>';
				$results .= $advertisement->data->content;
				$results .= '<p class="livemarket-more-button-wrapper"><a href="' . $advertisement->data->url . '" class="livemarket-more-button">' . __( 'Find Out More', 'livemarket' ) . '</a></p>';
				$results .= '</div>';
			} else {
				return '<h1 class="error">' . __( 'Unable to find marketplace store.', 'livemarket' ) . '</h1>';
			}
		}
		
		return $results;
		
	}
	add_shortcode( 'livemarket', 'do_livemarket' );
	
}