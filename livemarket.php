<?php
/**
 * Main PHP file used to for initial calls to zeen101's LiveMarket classes and functions.
 *
 * @package zeen101's LiveMarket
 * @since 1.0.0
 */
 
/*
Plugin Name: LiveMarket
Plugin URI: https://livemarket.pub/
Author: ZEEN101
Version: 1.7.2
Author URI: https://zeen101.com/
Tags: livemarket, live market, live ads, advertising, recurring revenue, ads, advertisement, advertise
Description: Create a new ad revenue stream with promotions your audience actually wants to see 
Text Domain: livemarket
*/
	
define( 'LIVEMARKET_NAME', 			'LiveMarket for WordPress' );
define( 'LIVEMARKET_SLUG', 			'livemarket' );
define( 'LIVEMARKET_VERSION',		'1.7.2' );
define( 'LIVEMARKET_DB_VERSION',	'1.0.0' );
define( 'LIVEMARKET_URL',			plugin_dir_url( __FILE__ ) );
define( 'LIVEMARKET_PATH', 			plugin_dir_path( __FILE__ ) );
define( 'LIVEMARKET_BASENAME',		plugin_basename( __FILE__ ) );
define( 'LIVEMARKET_REL_DIR',		dirname( LIVEMARKET_BASENAME ) );
define( 'LIVEMARKET_SITE_URL',      'https://my.livemarket.pub/' );
define( 'LIVEMARKET_API_URL',		'https://my.livemarket.pub/api/' );

/**
 * Instantiate LiveMarket class, require helper files
 *
 * @since 1.0.0
 */
function livemarket_plugins_loaded() {
	
	require_once( 'lm-class.php' );

	// Instantiate the LiveMarket class
	if ( class_exists( 'LiveMarket' ) ) {
		
		global $livemarket;
		$livemarket = new LiveMarket();
		
		require_once( 'lm-ajax.php' );
		require_once( 'lm-functions.php' );
		require_once( 'lm-api.php' );
		require_once( 'lm-shortcodes.php' );
		require_once( 'lm-widgets.php' );
		require_once( 'lm-flyout.php' );
		
		if ( isset( $_GET['clear_livemarket_cache'] ) ) {
			
			clear_livemarket_cache();
			
		}

		//Internationalization
		load_plugin_textdomain( 'livemarket', false, LIVEMARKET_REL_DIR . '/i18n/' );
			
	}

}
add_action( 'plugins_loaded', 'livemarket_plugins_loaded', 4815162342 ); //wait for the plugins to be loaded before init
