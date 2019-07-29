<?php
/**
 * Registers zeen101's LiveMarket Shortcodes
 *
 * @package zeen101's LiveMarket
 * @since 1.0.0
 */

/**
 * Shortcode for zeen101's Leaky Paywall
 * Prints out the zeen101's Leaky Paywall
 *
 * @since 1.0.0
 */
function do_livemarket( $atts ) {
	
	$settings = get_livemarket_settings();
	$dateformat = get_option( 'date_format' );
	$permalink = rtrim( get_permalink( $settings['livemarket_page'] ), '/' );
	
	if ( empty( $settings['api_key'] ) ) {
		return '<h1 class="error">' . __( 'You Must Enter a Valid LiveMarket API Token in the Live Market Plugin', 'livemarket' ) . '</h1>';
	}
	
	$defaults = array(
		'limit'       => 10,
		'market_name' => '',
		'subtext'     => '',
		'show_signup' => true,
		'signup_text' => __( 'Post Your Promotion Here For Free', 'livemarket' ),
		'show_more'   => true,
		'category'    => '',
		'advertiser'  => '',
		'classes'     => '',
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	if ( $category = get_query_var( 'category' ) ) {
		$atts['category'] = $category;
	}
	
	if ( $advertiser = get_query_var( 'contributor' ) ) {
		$atts['advertiser'] = $advertiser;
	}
	
	if ( $offer_slug = get_query_var( 'offer' ) ) {
		$advertisement = get_livemarket_advertisement( $offer_slug );
		if ( !empty( $advertisement->success ) && !empty( $advertisement->data ) ) {
			
			if ( get_option( 'permalink_structure' ) ) {
				$advertiser_link = $permalink . '/contributor/' . $advertisement->data->user_id;
			} else {
				$advertiser_link = $permalink . '/' . http_build_query( array( 'contributor' => $advertisement->data->user_id ) );
			}
			
			$return = livemarket_track_view( $advertisement->data->slug );
			$results  = '<div class="livemarket_content" data-slug="' . $advertisement->data->slug . '">';
			$results .= '<h3>' . $advertisement->data->title . '</h3>';
			$results .= '<p><span class="livemarket_meta livemarket_companyname">' . __( 'by', 'livemarket' ). ' <a href="' . $advertiser_link . '">' . $advertisement->data->displayname . '</a></span>';
			$results .= '<span class="livemarket_meta livemarket_date"> - ' . $advertisement->data->human_readable . '</span></p>';
			$results .= $advertisement->data->content;
			$results .= '<p class="livemarket-more-button-wrapper"><a href="' . $advertisement->data->url . '" class="livemarket-more-button">' . __( 'Find Out More', 'livemarket' ) . '</a></p>';
			$results .= '</div>';
		} else {
			return '<h1 class="error">' . __( 'Unable to find marketplace offer.', 'livemarket' ) . '</h1>';
		}
	} else if ( !empty( $atts['category'] ) ) {
		$results  = '<div class="livemarket_shortcode_list ' . $atts['classes'] . '">';
		$results .= !empty( $atts['market_name'] ) ? '<div class="livemarket_name">' . $atts['market_name'] . '</div>' : '';
		$results .= !empty( $atts['subtext'] ) ? '<span class="livemarket_subtext">' . $atts['market_name'] . '</span>' : '';
		$results .= shortcode_formatted_livemarket_advertisements( 0, $atts['limit'], $atts['show_more'], $atts['category'] ); //Page, Limit, Show View More Link
		$results .= '</div>';
		if ( !empty( $atts['show_signup'] ) ) {
			$results .=  '<div class="livemarket_signup_link livemarket_shortcode">';
			$results .= shortcode_formatted_livemarket_advertisement_signup_link( $atts['signup_text'] );
			$results .=  '</div>';
		}
	}  else if ( !empty( $atts['advertiser'] ) ) {
		$results  = '<div class="livemarket_shortcode_list ' . $atts['classes'] . '">';
		$results .= !empty( $atts['market_name'] ) ? '<div class="livemarket_name">' . $atts['market_name'] . '</div>' : '';
		$results .= !empty( $atts['subtext'] ) ? '<span class="livemarket_subtext">' . $atts['market_name'] . '</span>' : '';
		$results .= shortcode_formatted_livemarket_advertisements( 0, $atts['limit'], $atts['show_more'], false, $atts['advertiser'] ); //Page, Limit, Show View More Link
		$results .= '</div>';
		if ( !empty( $atts['show_signup'] ) ) {
			$results .=  '<div class="livemarket_signup_link livemarket_shortcode">';
			$results .= shortcode_formatted_livemarket_advertisement_signup_link( $atts['signup_text'] );
			$results .=  '</div>';
		}
	} else {
		$results  = '<div class="livemarket_shortcode_list ' . $atts['classes'] . '">';
		$results .= !empty( $atts['market_name'] ) ? '<div class="livemarket_name">' . $atts['market_name'] . '</div>' : '';
		$results .= !empty( $atts['subtext'] ) ? '<span class="livemarket_subtext">' . $atts['market_name'] . '</span>' : '';
		$results .= shortcode_formatted_livemarket_advertisements( 0, $atts['limit'], $atts['show_more'], false, false ); //Page, Limit, Show View More Link
		$results .= '</div>';
		if ( !empty( $atts['show_signup'] ) ) {
			$results .=  '<div class="livemarket_signup_link livemarket_shortcode">';
			$results .= shortcode_formatted_livemarket_advertisement_signup_link( $atts['signup_text'] );
			$results .=  '</div>';
		}
	}
	
	return $results;
	
}
add_shortcode( 'livemarket', 'do_livemarket' );