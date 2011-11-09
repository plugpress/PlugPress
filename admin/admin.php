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

		$this->header = __('PlugPress', 'plugpress');
		$this->icon = $this->images_url . 'icon32.png';
		$this->tab = 'home';
	}

	/**
	 * Include files needed to make PlugPress work
	 */
	private function includes() {
		require( $this->admin_dir . 'filters.php' );	// Admin filters

		wp_register_style('plugpress.css', $this->styles_url . 'plugpress.css');
		wp_enqueue_style('plugpress.css');

		wp_register_script('plugpress.js', $this->js_url . 'plugpress.js', array(), false, true);
		wp_enqueue_script('plugpress.js');

		wp_register_script('jquery.carouFredSel.js', $this->js_url . 'jquery.carouFredSel.js', array(), false, true);
		wp_enqueue_script('jquery.carouFredSel.js');

		wp_register_style('PrettyPhoto.css', $this->styles_url . 'PrettyPhoto.css');
		wp_enqueue_style('PrettyPhoto.css');

		wp_register_script('jquery.PrettyPhoto.js', $this->js_url . 'jquery.PrettyPhoto.js', array(), false, true);
		wp_enqueue_script('jquery.PrettyPhoto.js');

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
					62
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

		add_action('load-' . $browse_page_alt, array( &$this, 'include_browse' ) );
		add_action('load-' . $browse_page, array( &$this, 'include_browse' ) );
		#add_action('load-' . $account_page, '' );
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
	 * Manage the
	 */
	public function include_browse() {
		include $this->admin_dir . 'browse.php';

		add_action( 'plugpress_admin_init', 'plugpress_admin_browse' );

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
		add_contextual_help('plugpress_page_plugpress-account', '<p>test3</p>');
		#print 'yes!!';exit;
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