<?php
/**
 * PlugPress Browse Admin
 */


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

		if ( is_wp_error( $this->data ) ) {
			$msg = PlugPress_Admin::get_error_message( $this->data );
			$plugpress->admin->error = $msg;

			include( $plugpress->admin->admin_dir . 'views/error.php' );
		}
		elseif ( $this->context == 'home' ) {
			plugpress_split_metaboxes( $this->data );
			include( $plugpress->admin->admin_dir . 'views/left-right-content.php' );
		}
		elseif ( $this->context == 'search' ) {
			plugpress_split_metaboxes( $this->data );
			include( $plugpress->admin->admin_dir . 'views/left-right-content.php' );
		}
		elseif ( $this->context == 'plugins' ) {
			plugpress_split_metaboxes( $this->data );
			include( $plugpress->admin->admin_dir . 'views/left-right-content.php' );
		}
		elseif ( $this->context == 'themes' ) {
			plugpress_split_metaboxes( $this->data );
			include( $plugpress->admin->admin_dir . 'views/left-right-content.php' );
		}
		elseif ( $this->context == 'plugindetail' ) {
			plugpress_plugin_metaboxes( $this->data );
			$plugpress->plugin = $this->data->content->plugin;

			$plugpress->admin->header = $plugpress->plugin->name;
			$plugpress->admin->icon = $plugpress->plugin->icon;

			include( $plugpress->admin->admin_dir . 'views/plugindetail.php' );
		}
		elseif ( $this->context == 'themedetail' ) {
			plugpress_theme_metaboxes( $this->data );
			$plugpress->theme = $this->data->content->theme;

			$plugpress->admin->header = $plugpress->theme->name;
			#$plugpress->admin->icon = $plugpress->theme->icon;

			include( $plugpress->admin->admin_dir . 'views/themedetail.php' );
		}
		else {
			wp_die( __( 'Cheatin&#8217; uh?' ) );
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
		elseif ( $_GET['ppsubpage'] == 'search' ) {
			$this->context = 'search';
			$plugpress->admin->tab = 'plugins';

			// Slug
			$query = $_GET['ppq'];

			// Page
			$page = 1;
			if ( isset( $_GET['pppage'] ) && ctype_digit($_GET['pppage'] ) ) $page = $_GET['pppage'];

			#var_dump($query, $page);exit;

			$this->data = $serv->search_plugins( $query, $page );
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

			// Slug
			$slug = $_GET['ppslug'];

			$this->data = $serv->get_theme_information( $slug );
		}
		else {
			wp_die( __( 'Cheatin&#8217; uh?' ) );
		}
	}

	/**
	 * Setup contextual help
	 */
	public function setup_help() {
		global $current_screen;

		$help_link =
			'<p><b>' . __( 'For more information:', 'plugpress' ) . '</b></p>' .
			'<p><a href="http://www.plugpress.com">' .__( 'PlugPress Website', 'plugpress' ) . '</a></p>' .
			'<p><a href="http://www.plugpress.com/support">' .__( 'Support and Documentation', 'plugpress' ) . '</a></p>';

		if ( $this->context == 'home' ) {
			# '<p>' . __( '', 'plugpress' ) . '</p>' .
			$help =
				'<p>' . __( 'Welcome to the PlugPress Home.' ) . '</p>' .
				'<p>' . __( 'You can now start browsing plugins and themes to make your website more powerful.', 'plugpress' ) . '</p>' .
				'<p>' . __( 'This screen is a resume of what you will find via PlugPress. This page is updated every time we have something new that could improve your website so make sure you come back often.', 'plugpress' ) . '</p>' .
				'<p>' . __( 'You can choose to browse a specific item types by clicking on the <b>Browse Plugins</b> or <b>Browse Themes</b> tab. To view the complete documentation for PlugPress, simply click on the <b>Help</b> tab.', 'plugpress' ) . '</p>';

			add_contextual_help( $current_screen, $help . $help_link);
		}
		elseif ( $this->context == 'plugins' ) {
			$help =
				'<p>' . __( 'Welcome to the PlugPress Plugins.' ) . '</p>' .
				'<p>' . __( 'You can now browse the thousands of plugins available via PlugPress', 'plugpress' ) . '</p>' .
				'<p>' . __( 'On the right side, we organized different groupings to help you find the plugins you need according to the right category.', 'plugpress' ) . '</p>';

			add_contextual_help( $current_screen, $help . $help_link);
		}
		elseif ( $this->context == 'themes' ) {
			$help =
				'<p>' . __( 'Welcome to the PlugPress Themes.' ) . '</p>' .
				'<p>' . __( 'You can now browse the hundreds of themes available via PlugPress', 'plugpress' ) . '</p>' .
				'<p>' . __( 'On the right side, we organized different groupings to help you find the themes you need according to the right category.', 'plugpress' ) . '</p>';

			add_contextual_help( $current_screen, $help . $help_link);
		}
		elseif ( $this->context == 'plugindetail' ) {
			$help =
				'<p>' . __( 'Welcome to the PlugPress Plugin Detail.' ) . '</p>' .
				'<p>' . __( 'This page explains exactly what the plugin is all about and contains all the information you need in order to make a decision whether you should buy or not this plugin.', 'plugpress' ) . '</p>';

			add_contextual_help( $current_screen, $help . $help_link);
		}
		elseif ( $this->context == 'themedetail' ) {
			$help =
				'<p>' . __( 'Welcome to the PlugPress Theme Detail.' ) . '</p>' .
				'<p>' . __( 'This page explains exactly what the theme is all about and contains all the information you need in order to make a decision whether you should buy or not this theme.', 'plugpress' ) . '</p>';

			add_contextual_help( $current_screen, $help . $help_link);
		}

	}

}


global $plugpress;
if ( 'PlugPress' == get_class( $plugpress ) ) {
	$plugpress->admin->browse = new PlugPress_Browse_Admin();
}

endif;
