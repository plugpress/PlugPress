<?php
/**
 * PlugPress filters and actions
 *
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

///////////////////////////////////////////////////////////////////////////////////////////////////
// Attach PlugPress to WordPress
///////////////////////////////////////////////////////////////////////////////////////////////////

add_action( 'init', 'plugpress_init', 10 );


///////////////////////////////////////////////////////////////////////////////////////////////////
// Admin
///////////////////////////////////////////////////////////////////////////////////////////////////

if ( is_admin() ) {
	// Actions
	add_action( 'plugpress_init', 'plugpress_admin' );

	add_action( 'wp_ajax_plugpress_unlink_account', 'plugpress_unlink_callback' );


	// Filters
	add_filter('plugins_api', 'plugpress_plugins_api', 10, 3);
	add_filter('pre_set_site_transient_update_plugins', 'plugpress_pre_set_site_transient_update_plugins', 10, 1);

	add_filter('themes_api', 'plugpress_themes_api', 10, 3);
	add_filter('pre_set_site_transient_update_themes', 'plugpress_pre_set_site_transient_update_themes', 10, 1);

}

///////////////////////////////////////////////////////////////////////////////////////////////////
// Main Actions
///////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Initialize the code after everything has been loaded
 */
function plugpress_init() {
	do_action( 'plugpress_init' );
}

/**
 * Unlink user callback in the database (ajax)
 */
function plugpress_unlink_callback() {
	$option_name = 'plugpress_account_user';
	$transient_name = $option_name . '_check';

	$user = delete_site_option($option_name);
	$user_check = delete_site_transient($transient_name);

	die();
}


