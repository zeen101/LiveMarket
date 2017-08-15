<?php
/**
 * Registers zeen101's LiveMarket Functions
 *
 * @package zeen101's LiveMarket
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
	 * Default content filter, adds the LiveMarket shortcode to the page set
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

if ( !function_exists( 'widget_formatted_livemarket_advertisements' ) ) {
	
	function widget_formatted_livemarket_advertisements( $page = 0, $limit = 10 ) {
		$page = apply_filters( 'livemarket_widget_advertisements_page', $page ); //0 is the first page
		$limit = apply_filters( 'livemarket_widget_advertisements_limit', $limit ); //Get 10 advertisements
		
		return formatted_livemarket_advertisements( $page, $limit, true, false );
	}
	
}

if ( !function_exists( 'widget_formatted_livemarket_advertisement_signup_link' ) ) {
	
	function widget_formatted_livemarket_advertisement_signup_link() {
		$settings = get_livemarket_settings();
		$text = apply_filters( 'livemarket_widget_advertisements_signup_link_text', sprintf( __( 'Advertise with %s', 'livemarket' ), get_bloginfo( 'name' ) ) );
		return '<p class="livemarket_signup_link"><a href="https://my.livemarket.pub/publication/' . $settings['publication_id'] . '/advertise/" target="_blank">' . $text . '</a></p>';
	}
}

if ( !function_exists( 'shortcode_formatted_livemarket_advertisements' ) ) {
	
	function shortcode_formatted_livemarket_advertisements( $page = 0, $limit = 10 ) {
		$page = apply_filters( 'livemarket_shortcode_advertisements_page', $page  ); //0 is the first page
		$limit = apply_filters( 'livemarket_shortcode_advertisements_limit', $limit ); //Get 10 advertisements
		
		return formatted_livemarket_advertisements( $page, $limit, false, true );
	}
	
}

if ( !function_exists( 'shortcode_formatted_livemarket_advertisement_signup_link' ) ) {
	
	function shortcode_formatted_livemarket_advertisement_signup_link() {
		$settings = get_livemarket_settings();
		$text = apply_filters( 'livemarket_shortcode_advertisements_signup_link_text', sprintf( __( 'Advertise with %s', 'livemarket' ), get_bloginfo( 'name' ) ) );
		return '<a href="https://my.livemarket.pub/publication/' . $settings['publication_id'] . '/advertise/" target="_blank">' . $text . '</a>';
	}
}

if ( !function_exists( 'formatted_livemarket_advertisements' ) ) {

	/**
	 * Default content filter, adds the LiveMarket shortcode to the page set
	 * if that page has no content.
	 *
	 * @since 1.0.0
	 *
	 * @return string new content.
	 */
	function formatted_livemarket_advertisements( $page = 0, $limit = 10, $widget = false, $shortcode = false ) {
		
		$settings = get_livemarket_settings();
		$dateformat = get_option( 'date_format' );
		
		$advertisements = get_livemarket_advertisements( $page, $limit );

		if ( !empty( $advertisements->success ) && !empty( $advertisements->data ) ) {
			$return  = '';
			foreach( $advertisements->data->advertisements as $advertisement ) {
				$track_ids[] = $advertisement->id;
				if ( get_option( 'permalink_structure' ) ) {
					$link = rtrim( get_permalink( $settings['livemarket_page'] ), '/' ) . '/' . $advertisement->slug;
				} else {
					$link = rtrim( get_permalink( $settings['livemarket_page'] ), '/' ) . '/' . '?store=' . $advertisement->slug;
				}
				$return .= '<p class="livemarket_item">';
				$return .= '<span class="livemarket_title"><a href="' . $link . '">' . $advertisement->title . '</a></span><br />';
				$return .= '<span class="livemarket_meta livemarket_companyname">' . __( 'by', 'livemarket' ). ' ' . $advertisement->displayname . '</span> ';
				$return .= '<span class="livemarket_meta livemarket_date"> - ' . date_i18n( $dateformat, strtotime( get_date_from_gmt( $advertisement->created_at ) ) ) . '</span>';
				$return .= '</p>';
			}
			if ( !empty( $track_ids ) ) {
				livemarket_track_impressions( $track_ids );
			}
			
			$return .= '<p class="livemarket_view_more">';
			if ( $widget ) {
				$return .= '<span class="all"><a href="' . get_permalink( $settings['livemarket_page'] ) . '">' . __( 'View All', 'livemarket' ) . '</a></span>';
			}
			
			if ( $shortcode ) {
				$next_page = $page + 1;
				if ( $limit < $advertisements->data->total && ( $next_page * $limit < $advertisements->data->total ) ) {
					$return .= '<span class="more"><a href="#" data-page="' . $next_page . '" data-limit="' . $limit . '">' . __( 'View More', 'livemarket' ) . '</a></span>';
				}
			}
			$return .= '</p>';
		} else {
			$return = '<h1 class="error">' . __( 'Unable to find marketplace stores.', 'livemarket' ) . '</h1>';
		}
		
		return $return;
		
	}
	
}
