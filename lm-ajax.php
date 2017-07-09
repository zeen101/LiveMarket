<?php
/**
 * Registers zeen101's Live Market Ajax Functions
 *
 * @package zeen101's Live Market
 * @since 1.0.0
 */
 
function ajax_get_livemarket_advertisements() {
	check_ajax_referer( 'livemarket-nonce', 'nonce' );
	
	$page = intval( $_REQUEST['page'] );
	$limit = intval( $_REQUEST['limit'] );
	$advertisements = formatted_livemarket_advertisements( $page, $limit );
	if ( !empty( $advertisements ) ) {
		wp_send_json_success( array( 'success'  => true, 'html' => $advertisements ) );
	} else {
		wp_send_json_error();
	}
}
add_action( 'wp_ajax_livemarket_list', 'ajax_get_livemarket_advertisements' );
add_action( 'wp_ajax_nopriv_livemarket_list', 'ajax_get_livemarket_advertisements' );
 
function ajax_livemarket_track_click() {
	check_ajax_referer( 'livemarket-nonce', 'nonce' );

	$id = intval( $_REQUEST['id'] );
	$target = esc_url( $_REQUEST['target'] );
	$anchor = sanitize_text_field( $_REQUEST['anchor'] );
	
	$return = livemarket_track_click( $id, array( 'target' => $target, 'anchor' => $anchor ) );
	error_log( print_r( $return, true ) );
	wp_send_json_success();
}
add_action( 'wp_ajax_livemarket_track_click', 'ajax_livemarket_track_click' );
add_action( 'wp_ajax_nopriv_livemarket_track_click', 'ajax_livemarket_track_click' );