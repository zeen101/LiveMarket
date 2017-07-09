<?php
/**
 * Main PHP file used to for initial calls to zeen101's Live Market classes and functions.
 *
 * @package zeen101's Live Market
 * @since 1.0.0
 */
 
/*
Plugin Name: Live Market
Plugin URI: https://livemarket.pub/
Description: 
Author: zeen101 Development Team
Version: 1.0.0
Author URI: https://livemarket.pub/
Tags: 
Text Domain: livemarket
*/
	
define( 'LIVEMARKET_NAME', 			'Live Market for WordPress' );
define( 'LIVEMARKET_SLUG', 			'livemarket' );
define( 'LIVEMARKET_VERSION',		'1.0.0' );
define( 'LIVEMARKET_DB_VERSION',	'1.0.0' );
define( 'LIVEMARKET_URL',			plugin_dir_url( __FILE__ ) );
define( 'LIVEMARKET_PATH', 			plugin_dir_path( __FILE__ ) );
define( 'LIVEMARKET_BASENAME',		plugin_basename( __FILE__ ) );
define( 'LIVEMARKET_REL_DIR',		dirname( LIVEMARKET_BASENAME ) );
define( 'LIVEMARKET_API_URL',		'http://my.livemarket.pub/api/' );

/**
 * Instantiate Live Market class, require helper files
 *
 * @since 1.0.0
 */
function livemarket_plugins_loaded() {
	
	require_once( 'lm-class.php' );

	// Instantiate the Live Market class
	if ( class_exists( 'LiveMarket' ) ) {
		
		global $livemarket;
		$livemarket = new LiveMarket();
		
		require_once( 'lm-ajax.php' );
		require_once( 'lm-functions.php' );
		require_once( 'lm-api.php' );
		require_once( 'lm-shortcodes.php' );
		require_once( 'lm-widgets.php' );

		//Internationalization
		load_plugin_textdomain( 'livemarket', false, LIVEMARKET_REL_DIR . '/i18n/' );
			
	}

}
add_action( 'plugins_loaded', 'livemarket_plugins_loaded', 4815162342 ); //wait for the plugins to be loaded before init
