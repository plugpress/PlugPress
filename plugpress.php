<?php
/*
Plugin Name: <b>PlugPress</b>
Plugin URI: http://www.plugpress.com
Description: <strong>Find and install free or premium plugins and themes directly from your WordPress website.</strong>
Version: 0.8.3
Author: PlugPress.com
Author URI: http://www.plugpress.com
License: GPLv2
*/

// Redirect if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	include( './index.html' );
	exit;
}

if ( !class_exists( 'PlugPress' ) ) :

/**
 * PlugPress
 */
class PlugPress {

	#const WEBSITE_URL = 'http://devplugpress/';
	#const API_URL = 'http://api.devplugpress/';

	const WEBSITE_URL = 'https://www.plugpress.com/';
	const API_URL = 'http://api.plugpress.com/';

	public $version = '0.8.3';

	public $plugin_url = '';
	public $plugin_dir = '';



	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setup_globals();
		$this->includes();
	}

	/**
	 * Setup global variables for the class
	 */
	private function setup_globals() {
		$this->plugin_dir = plugin_dir_path( __FILE__ );
		$this->plugin_url = plugin_dir_url( __FILE__ );
	}

	/**
	 * Includes all needed files
	 */
	private function includes() {
		require( $this->plugin_dir . 'includes/core-hooks.php' );	// All filters and actions
		require( $this->plugin_dir . 'includes/metaboxes.php' );	// Metaboxes
		require( $this->plugin_dir . 'includes/misc.php' );			// Misc

		// load admin if needed
		if ( is_admin() ) {
			require( $this->plugin_dir . 'admin/admin.php' );
		}
	}
}

$GLOBALS['plugpress'] = new PlugPress();

endif;