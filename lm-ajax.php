<?php
/**
 * Registers zeen101's LiveMarket Ajax Functions
 *
 * @package zeen101's LiveMarket
 * @since 1.0.0
 */
 
function ajax_get_livemarket_shortcode_advertisements() {
	check_ajax_referer( 'livemarket-nonce', 'nonce' );
	
	$page = intval( $_REQUEST['page'] );
	$limit = intval( $_REQUEST['limit'] );
	$advertisements = formatted_livemarket_advertisements( $page, $limit, false, true );
	if ( !empty( $advertisements ) ) {
		wp_send_json_success( array( 'success'  => true, 'html' => $advertisements ) );
	} else {
		wp_send_json_error();
	}
}
add_action( 'wp_ajax_livemarket_shortcode_list', 'ajax_get_livemarket_shortcode_advertisements' );
add_action( 'wp_ajax_nopriv_livemarket_shortcode_list', 'ajax_get_livemarket_shortcode_advertisements' );
 
function ajax_get_livemarket_widget_advertisements() {
	check_ajax_referer( 'livemarket-nonce', 'nonce' );
	
	$page = intval( $_REQUEST['page'] );
	$limit = intval( $_REQUEST['limit'] );
	$advertisements = formatted_livemarket_advertisements( $page, $limit, true, false );
	if ( !empty( $advertisements ) ) {
		wp_send_json_success( array( 'success'  => true, 'html' => $advertisements ) );
	} else {
		wp_send_json_error();
	}
}
add_action( 'wp_ajax_livemarket_widget_list', 'ajax_get_livemarket_widget_advertisements' );
add_action( 'wp_ajax_nopriv_livemarket_widget_list', 'ajax_get_livemarket_widget_advertisements' );
 
function ajax_livemarket_track_view() {
	check_ajax_referer( 'livemarket-nonce', 'nonce' );

	$slug = sanitize_text_field( $_REQUEST['slug'] );
	
	$return = livemarket_track_view( $slug );
	wp_send_json_success();
}
add_action( 'wp_ajax_livemarket_track_view', 'ajax_livemarket_track_view' );
add_action( 'wp_ajax_nopriv_livemarket_track_view', 'ajax_livemarket_track_view' );
 
function ajax_livemarket_track_click() {
	check_ajax_referer( 'livemarket-nonce', 'nonce' );

	$slug = sanitize_text_field( $_REQUEST['slug'] );
	$target = esc_url( $_REQUEST['target'] );
	$anchor = sanitize_text_field( $_REQUEST['anchor'] );
	
	$return = livemarket_track_click( $slug, array( 'target' => $target, 'anchor' => $anchor ) );
	wp_send_json_success();
}
add_action( 'wp_ajax_livemarket_track_click', 'ajax_livemarket_track_click' );
add_action( 'wp_ajax_nopriv_livemarket_track_click', 'ajax_livemarket_track_click' );
