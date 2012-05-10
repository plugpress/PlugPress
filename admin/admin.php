<?php

/**
 * Main PlugPress Admin Class
 *
 * @package PlugPress
 * @subpackage Administration
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'PlugPress_Admin' ) ) :
/**
 * PlugPress Admin Area
 *
 */
class PlugPress_Admin {

	public $error = null;

	public $admin_dir = '';
	public $admin_url = '';
	public $images_url = '';
	public $styles_url = '';
	public $js_url = '';
	public $tab = '';


	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setup_globals();
		$this->includes();
		$this->setup_actions();
	}

	/**
	 * Setup global variables for the class
	 *
	 * @global type $plugpress
	 */
	private function setup_globals() {
		global $plugpress;

		$plugpress->username = get_site_option('plugpress_account_user', null);

		// Admin url
		$this->admin_dir  = trailingslashit( $plugpress->plugin_dir . 'admin' );

		// Admin url
		$this->admin_url  = trailingslashit( $plugpress->plugin_url . 'admin' );

		// Admin images URL
		$this->images_url = trailingslashit( $this->admin_url . 'images' );

		// Admin images URL
		$this->styles_url = trailingslashit( $this->admin_url . 'styles' );

		// Admin javascript URL
		$this->js_url = trailingslashit( $this->admin_url . 'js' );

		// Website URL
		$this->website_url = get_bloginfo( 'url' );

		// Website "secret" key
		$this->website_key = md5( LOGGED_IN_KEY ) . md5( $this->website_url );

		$this->header = __( 'PlugPress', 'plugpress' );
		$this->icon = $this->images_url . 'icon32.png';
		$this->tab = 'home';
	}

	/**
	 * Include files needed to make PlugPress work
	 */
	private function includes() {
		global $wp_version;
		require( $this->admin_dir . 'filters.php' );	// Admin filters

		wp_register_style('plugpress.css', $this->styles_url . 'plugpress.css');
		wp_enqueue_style('plugpress.css');

		if (version_compare($wp_version, '3.2.1', '<=')) {
			wp_register_style('plugpress.wp-3.2.1.css', $this->styles_url . 'plugpress.wp-3.2.1.css');
			wp_enqueue_style('plugpress.wp-3.2.1.css');
		}

		wp_register_script('plugpress.js', $this->js_url . 'plugpress.js', array(), false, true);
		wp_enqueue_script('plugpress.js');

		wp_register_script('jquery.carouFredSel.js', $this->js_url . 'jquery.carouFredSel.js', array(), false, true);
		wp_enqueue_script('jquery.carouFredSel.js');

		wp_register_style('prettyPhoto.css', $this->styles_url . 'prettyPhoto.css');
		wp_enqueue_style('prettyPhoto.css');

		wp_register_script('jquery.prettyPhoto.js', $this->js_url . 'jquery.prettyPhoto.js', array(), false, true);
		wp_enqueue_script('jquery.prettyPhoto.js');

		#wp_enqueue_script( 'postbox' );
	}

	/**
	 * Setup actions to hookup.
	 */
	private function setup_actions() {

		// Attach the PlugPress admin_init action to the WordPress admin_init action.
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Add menus item to
		add_action( 'admin_menu', array( $this, 'admin_menus' ) );

	}

	/**
	 * Add menus to the WordPress admin section
	 */
	public function admin_menus() {
		$browse_page = add_menu_page( __( 'Browse and find WordPress add-ons', 'plugpress' ),
					__( 'PlugPress', 'plugpress'),
					'manage_options',
					'plugpress-browse',
					array( &$this, 'menu_browse' ),
					$this->images_url . 'icon16.png',
					null
		);

		$browse_page_alt = add_submenu_page( 'plugpress-browse',
				__( 'Browse and Find', 'plugpress' ),
				__( 'Browse and Find', 'plugpress' ),
				'manage_options',
				'plugpress-browse',
				array( &$this, 'menu_browse' )
		);

		$account_page = add_submenu_page( 'plugpress-browse',
				__('Access your PlugPress account', 'plugpress'),
				__('My Account', 'plugpress'),
				'manage_options',
				'plugpress-account',
				array( &$this, 'menu_my_account' )
		);

		add_action( 'load-' . $browse_page_alt, array( &$this, 'include_browse' ) );
		add_action( 'load-' . $browse_page, array( &$this, 'include_browse' ) );
		add_action( 'load-' . $account_page, array( &$this, 'include_account' ) );

		/*
		add_submenu_page('plugpress-browse',
						__('Manage installed plugins on your website', 'plugpress'),
						__('Installed Plugins', 'plugpress'),
						'manage_options',
						'plugins.php'
		);
		*/
		/*
		add_submenu_page('plugpress-browse',
						__('Manage PlugPress options', 'plugpress'),
						__('Options', 'plugpress'),
						'manage_options',
						'plugpress-options',
						array(self::$singleton, 'manageRequest')
		);
		*/
	}

	/**
	 * Manage the include of browse page
	 */
	public function include_browse() {
		include $this->admin_dir . 'browse.php';

		add_action( 'plugpress_admin_init', 'plugpress_admin_browse' );
	}

	/**
	 * Manage the include of account page
	 */
	public function include_account() {
		include $this->admin_dir . 'account.php';

		add_action( 'plugpress_admin_init', 'plugpress_admin_account' );
	}

	/**
	 * PlugPress admin_init handling
	 */
	public function admin_init() {
		do_action( 'plugpress_admin_init' );
	}

	/**
	 * When clicking the browse menu
	 */
	public function menu_browse() {
		do_action( 'plugpress_browse_view' );
	}

	/**
	 * When clicking the my account menu
	 */
	public function menu_my_account() {
		do_action( 'plugpress_account_view' );
	}

	/**
	 * Generate error message to output.
	 *
	 * @param WP_ERROR $err
	 * @return string
	 */
	public static function get_error_message($err) {
		$msg = __( 'Unknown error.', 'plugpress' );

		if ( is_wp_error( $err ) ) {
			foreach( $err->errors as $tag => $description ) {
				if ( 'http_request_failed' == $tag ) {
					$msg = esc_html( __( 'PlugPress is not able to get data from its own server. ' .
							'The problem is usually caused by web hosts who block remote connections. ' .
							'Please check with your host then contact the PlugPress team.', 'plugpress' ) );
					break;
				}
				$msg .= esc_html( $description ) . '<br />';
			}
		}

		return $msg;
	}
}

/**
 * Setup PlugPress Admin
 */
function plugpress_admin() {
	global $plugpress;

	$plugpress->admin = new PlugPress_Admin();
}


endif;