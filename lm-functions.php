<?php
/**
 * Registers zeen101's LiveMarket Functions
 *
 * @package zeen101's LiveMarket
 * @since 1.0.0
 */

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

function widget_formatted_livemarket_advertisements( $page = 0, $limit = 10, $category = '', $advertiser = '' ) {
	$page     = apply_filters( 'livemarket_widget_advertisements_page', $page ); //0 is the first page
	$limit    = apply_filters( 'livemarket_widget_advertisements_limit', $limit ); //Get 10 advertisements
	$category = apply_filters( 'livemarket_widget_advertisements_category', $category ); //Get Category
	$advertiser = apply_filters( 'livemarket_widget_advertisements_advertiser', $advertiser ); //Get advertiser
	
	return formatted_livemarket_advertisements( $page, $limit, true, false, true, $category, $advertiser );
}

function widget_formatted_livemarket_advertisement_signup_link() {
	$settings = get_livemarket_settings();
	$text = apply_filters( 'livemarket_widget_advertisements_signup_link_text', __( 'Advertise Here Today!', 'livemarket' ) );
	return '<p class="livemarket_signup_link"><a href="https://my.livemarket.pub/publication/' . $settings['publication_id'] . '/advertise/" target="_blank">' . $text . '</a></p>';
}

function shortcode_formatted_livemarket_advertisements( $page = 0, $limit = 10, $show_more = true, $category = '', $advertiser = '' ) {
	$page = apply_filters( 'livemarket_shortcode_advertisements_page', $page  ); //0 is the first page
	$limit = apply_filters( 'livemarket_shortcode_advertisements_limit', $limit ); //Get 10 advertisements
	$category = apply_filters( 'livemarket_shortcode_advertisements_category', $category ); //Get Category
	$advertiser = apply_filters( 'livemarket_shortcode_advertisements_advertiser', $advertiser ); //Get advertiser
	
	return formatted_livemarket_advertisements( $page, $limit, false, true, $show_more, $category, $advertiser );
}

function shortcode_formatted_livemarket_advertisement_signup_link() {
	$settings = get_livemarket_settings();
	$text = apply_filters( 'livemarket_shortcode_advertisements_signup_link_text', __( 'Advertise Here Today!', 'livemarket' ) );
	return '<a href="https://my.livemarket.pub/publication/' . $settings['publication_id'] . '/advertise/" target="_blank">' . $text . '</a>';
}

/**
 * Default content filter, adds the LiveMarket shortcode to the page set
 * if that page has no content.
 *
 * @since 1.0.0
 *
 * @return string new content.
 */
function formatted_livemarket_advertisements( $page = 0, $limit = 10, $widget = false, $shortcode = false, $show_more = true, $category = '', $advertiser = '' ) {
	
	$settings = get_livemarket_settings();
	$dateformat = get_option( 'date_format' );
	
	$advertisements = get_livemarket_advertisements( $page, $limit, $category, $advertiser );
	$permalink = rtrim( get_permalink( $settings['livemarket_page'] ), '/' );

	if ( !empty( $advertisements->success ) && !empty( $advertisements->data ) ) {
		$return  = '';
		foreach( $advertisements->data->advertisements as $advertisement ) {
			
			$track_ids[] = $advertisement->id;
			
			if ( get_option( 'permalink_structure' ) ) {
				$offer_link      = $permalink . '/' . $advertisement->slug;
				$advertiser_link = $permalink . '/contributor/' . $advertisement->user_id;
			} else {
				$offer_link      = $permalink . '/' . http_build_query( array( 'offer' => $advertisement->slug ) );
				$advertiser_link = $permalink . '/' . http_build_query( array( 'contributor' => $advertisement->user_id ) );
			}
			
			$return .= '<div class="livemarket_item">';
			$return .= '<h3 class="livemarket_title"><a href="' . $offer_link . '">' . $advertisement->title . '</a></h3>';
			$return .= '<p class="livemarket_meta_wrap"><span class="livemarket_meta livemarket_companyname">' . __( 'by', 'livemarket' ). ' <a href="' . $advertiser_link . '">' . $advertisement->displayname . '</a></span> ';
			$return .= '<span class="livemarket_meta livemarket_date"> - ' . date_i18n( $dateformat, strtotime( get_date_from_gmt( $advertisement->created_at ) ) ) . '</span></p>';
			$return .= '</div>';
		}
		if ( !empty( $track_ids ) ) {
			livemarket_track_impressions( $track_ids );
		}
		
		$return .= '<p class="livemarket_view_more">';
		if ( $widget ) {
			if ( get_option( 'permalink_structure' ) ) {
				$category_link = $permalink . '/category/' . $category;
			} else {
				$category_link = $permalink . '/' . http_build_query( array( 'category' => $category ) );
			}
			$return .= '<span class="all"><a href="' . $category_link . '">' . __( 'View All', 'livemarket' ) . '</a></span>';
		}
		
		if ( $shortcode && $show_more ) {
			$next_page = $page + 1;
			if ( $limit < $advertisements->data->total && ( $next_page * $limit < $advertisements->data->total ) ) {
				$return .= '<span class="more"><a href="#" data-page="' . $next_page . '" data-limit="' . $limit . '">' . __( 'View More', 'livemarket' ) . '</a></span>';
			}
		}
		$return .= '</p>';
	} else {
		$return = '<h1 class="error">' . __( 'Unable to find marketplace offers.', 'livemarket' ) . '</h1>';
	}
	
	return $return;
	
}

/**
 * Display latest livemarket item on mobile
 *
 * @since 1.4.2
 *
 */
add_action( 'wp_footer', 'livemarket_mobile_display' );

function livemarket_mobile_display() {

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
	if ( is_plugin_active( 'leaky-paywall/leaky-paywall.php') ) {

		$lp_settings = get_leaky_paywall_settings();

		if ( get_the_ID() == $lp_settings['page_for_login'] ) {
			return;
		}

		if ( get_the_ID() == $lp_settings['page_for_subscription'] ) {
			return;
		}

		if ( get_the_ID() == $lp_settings['page_for_register'] ) {
			return;
		}
	}

	$settings = get_livemarket_settings();
	$post = get_post( $settings['livemarket_page'] );

	if ( empty( $settings['api_key'] ) ) {
		return;
	}

	if ( get_the_ID() == $post->ID ) {
		return;
	}

	?>
		<div class="livemarket-mobile-footer">
			<span class="close">X</span>
			<?php echo do_shortcode('[livemarket limit=1 show_more="false"]'); ?>
		</div>

		<script>
			( function( $ )  {

				$(document).ready( function() {
					
					$('.livemarket-mobile-footer .close').click(function() {
						$('.livemarket-mobile-footer').remove();
					});

				});

			})( jQuery );

		</script>
	<?php 
}