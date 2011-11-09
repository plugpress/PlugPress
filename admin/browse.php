<?php
/**
 * PlugPress Browse Admin
 */

#print 'called';

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'PlugPress_Browse_Admin' ) ) :

/**
 * Loads PlugPress Browse Admin
 *
 */
class PlugPress_Browse_Admin {

	public $data = null;
	public $context = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setup_globals();
		$this->setup_actions();

		#$this->home_metaboxes();
		$this->get_context_data();

		$this->setup_help();
	}

	/**
	 * Setup globals
	 * @global type $plugpress
	 */
	private function setup_globals() {
		$plugpress->admin->header = __('PlugPress', 'plugpress');
	}

	/**
	 * Setup actions to hookup
	 */
	private function setup_actions() {
		add_action( 'plugpress_browse_view', array( &$this, 'view' ) );
	}

	/**
	 * View
	 *
	 */
	public function view() {
		global $plugpress;

		if ( $this->context == 'home' ) {
			$this->split_metaboxes();
			include( $plugpress->admin->admin_dir . 'views/left-right-content.php' );
		}
		elseif ( $this->context == 'plugins' ) {
			$this->split_metaboxes();
			include( $plugpress->admin->admin_dir . 'views/left-right-content.php' );
		}
		elseif ( $this->context == 'themes' ) {
			$this->split_metaboxes();
			include( $plugpress->admin->admin_dir . 'views/left-right-content.php' );
		}
		elseif ( $this->context == 'plugindetail' ) {
			plugpress_plugin_metaboxes( $this->data );
			$plugpress->plugin = $this->data->content->plugin;

			$plugpress->admin->header = $plugpress->plugin->name;
			$plugpress->admin->icon = $plugpress->plugin->icon;

			#print $plugpress->plugin->icon;


			include( $plugpress->admin->admin_dir . 'views/plugindetail.php' );
		}
		else {
			print 'woooooooo! ' . $this->context;
		}
	}

	/**
	 * Get data depending on the context
	 *
	 */
	private function get_context_data() {
		global $plugpress;

		require( $plugpress->plugin_dir . 'includes/server.php' ) ;
		// Call external data (if needed)
		$serv = new PlugPress_Server();

		if ( isset( $_GET['ppsubpage'] ) === false ) {
			$this->context = 'home';
			$this->data = $serv->get_home_data();
		}
		elseif ( $_GET['ppsubpage'] == 'plugins' ) {
			$this->context = 'plugins';
			$plugpress->admin->tab = 'plugins';

			// Slug
			$category = $_GET['ppslug'];
			if ( isset( $_GET['ppslug'] ) === false ) $category = 'all';

			// Page
			$page = 1;
			if ( isset( $_GET['pppage'] ) && ctype_digit($_GET['pppage'] ) ) $page = $_GET['pppage'];

			$this->data = $serv->get_plugins( $page, $category );
		}
		elseif ( $_GET['ppsubpage'] == 'themes' ) {
			$this->context = 'themes';
			$plugpress->admin->tab = 'themes';

			// Slug
			$category = $_GET['ppslug'];
			if ( isset( $_GET['ppslug'] ) === false ) $category = 'all';

			// Page
			$page = 1;
			if ( isset( $_GET['pppage'] ) && ctype_digit( $_GET['pppage'] ) ) $page = $_GET['pppage'];

			$this->data = $serv->get_themes( $page, $category );
		}
		elseif ( $_GET['ppsubpage'] == 'plugindetail' ) {
			$this->context = 'plugindetail';
			$plugpress->admin->tab = 'plugins';

			// Slug
			$slug = $_GET['ppslug'];

			$this->data = $serv->get_plugin_information( $slug );
		}
		elseif ( $_GET['ppsubpage'] == 'themedetail' ) {
			$this->context = 'themedetail';
			$plugpress->admin->tab = 'themes';
			$this->data = null;
		}
		else {
			wp_die( __( 'Cheatin&#8217; uh?' ) );
		}
	}


	/**
	 * Generates the metaboxes needed for split (left-right) view
	 */
	public function split_metaboxes() {
		plugpress_split_metaboxes( $this->data );
	}

	/**
	 * Setup contextual help
	 */
	public function setup_help() {
		global $current_screen;

		if ( $this->context == 'home' ) {
			add_contextual_help( $current_screen/*'toplevel_page_plugpress-browse'*/, '<p>Home help!!</p>' );
		}
		elseif ( $this->context == 'plugins' ) {
			add_contextual_help( $current_screen, '<p>plugins</p>' );
		}

	}

}


global $plugpress;
if ( 'PlugPress' == get_class( $plugpress ) ) {
	$plugpress->admin->browse = new PlugPress_Browse_Admin();
}

endif;
