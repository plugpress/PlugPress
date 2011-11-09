<?php

#print 'called';exit;

/**
 * PlugPress Browse Admin Class
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;



function plugpress_admin_browse() {
	global $current_screen;
	var_dump($current_screen);

	print 'yo';
}

function plugpress_tmp() {
	add_contextual_help( 'plugpress_page_plugpress-browse2', '<p>yessssssssssss</p>' );
}




if ( !class_exists( 'PlugPress_Browse_Admin' ) ) :
/**
 * Loads bbPress topics admin area
 *
 * @package bbPress
 * @subpackage Administration
 * @since bbPress (r2464)
 */
class PlugPress_Browse_Admin {

	public $home_data = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		print 'creating browse';
		$this->setup_globals();
		$this->setup_actions();
		$this->setup_help();
	}

	/**
	 * Setup globals
	 * @global type $plugpress
	 */
	private function setup_globals() {
		global $plugpress;
		require( $plugpress->plugin_dir . 'includes/server.php' ) ;

		// Call external data (if needed)
		$serv = new PlugPress_Server();
		$this->home_data = $serv->get_home_data();

	}

	private function setup_actions() {
		// Add meta boxes
		add_action( 'add_meta_boxes', array( $this, 'home_metaboxes' ) );

		add_action( 'admin_init', array( $this, 'setup_help' ) );
	}

	public function home_metaboxes() {
		#plugpress_split_metaboxes($this->home_data);
	}

	public function setup_help() {
		global $current_screen;
		#var_dump($current_screen);exit;
		add_contextual_help($current_screen, '<p>test</p>');
		add_contextual_help('plugpress-browse', '<p>test2</p>');
		add_contextual_help('plugpress_page_plugpress-browse2', '<p>test3</p>');
	}
}

/**
 * Setup PlugPres Browse Admin
 */
function plugpress_admin_browse2() {
	global $plugpress;

	if ( 'PlugPress' !== get_class( $plugpress ) ) {
		return;
	}

	$plugpress->admin->browse = new PlugPress_Browse_Admin();
}

endif;