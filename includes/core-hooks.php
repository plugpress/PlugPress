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


