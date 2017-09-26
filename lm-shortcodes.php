<?php
/**
 * Registers zeen101's LiveMarket Shortcodes
 *
 * @package zeen101's LiveMarket
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
			return '<h1 class="error">' . __( 'You Must Enter a Valid LiveMarket API Token in the Live Market Plugin', 'livemarket' ) . '</h1>';
		}
		
		$defaults = array(
			'limit'       => 10,
			'market_name' => '',
			'show_signup' => true,
			'show_more'   => true,
		);
		$atts = shortcode_atts( $defaults, $atts );
		
		$advertisement_slug = get_query_var( 'store' );
		
		if ( empty( $advertisement_slug ) ) {	
			$results  = '<div class="livemarket_list">';
			$results .= !empty( $atts['market_name'] ) ? '<div class="livemarket_name">' . $atts['market_name'] . '</div>' : '';
			$results .= shortcode_formatted_livemarket_advertisements( 0, $atts['limit'], $atts['show_more'] ); //Page, Limit, Show View More Link
			$results .= '</div>';
			if ( !empty( $atts['show_signup'] ) ) {
				$results .=  '<div class="livemarket_signup_link">';
				$results .= shortcode_formatted_livemarket_advertisement_signup_link();
				$results .=  '</div>';
			}
		} else {
			$advertisement = get_livemarket_advertisement( $advertisement_slug );
			if ( !empty( $advertisement->success ) && !empty( $advertisement->data ) ) {
				$return = livemarket_track_view( $advertisement->data->slug );
				$results  = '<div class="livemarket_content" data-slug="' . $advertisement->data->slug . '">';
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
