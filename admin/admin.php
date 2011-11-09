<?php

/**
 * Main PlugPress Admin Class
 *
 * @package PlugPress
 * @subpackage Administration
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'PlugPress_Admin' ) ) :
/**
 * PlugPress admin area
 *
 * @package PlugPress
 * @subpackage Administration
 */
class PlugPress_Admin {

	public $admin_dir = '';
	public $admin_url = '';
	public $images_url = '';
	public $styles_url = '';
	public $js_url = '';


	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setup_globals();
		$this->includes();
		$this->setup_actions();
	}

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

	}

	private function includes() {
		include $this->admin_dir . 'browse.php';
		#include $this->admin_dir . 'account.php';
	}

	private function setup_actions() {

		// Attach the PlugPress admin_init action to the WordPress admin_init action.
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Add menus item to
		add_action( 'admin_menu', array( $this, 'admin_menus' ) );

	}

	public function admin_menus() {
		add_menu_page(__('Browse and find WordPress add-ons', 'plugpress'),
					__('PlugPress', 'plugpress'),
					'manage_options',
					'plugpress-browse',
					array(&$this, 'menu_browse2'),
					$this->images_url . 'icon16.png',
					62
		);

		add_submenu_page('plugpress-browse',
			__('Browse and Find', 'plugpress'),
			__('Browse and Find', 'plugpress'),
			'manage_options',
			'plugpress-browse2',
			'plugpress_admin_browse' #array(&$this, 'menu_browse')
		);

		add_submenu_page('plugpress-browse',
						__('Access your PlugPress account', 'plugpress'),
						__('My Account', 'plugpress'),
						'manage_options',
						'plugpress-account',
						array(&$this, 'menu_my_account')
		);
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
	 * PlugPress admin_init handling
	 */
	public function admin_init() {
		do_action( 'plugpress_admin_init' );
	}

	/**
	 * When clicking the browse menu
	 */
	public function menu_browse2() {

	}
	public function menu_browse() {
		#print 'before ';
#		include $this->admin_dir . 'browse.php';
		#print 'after1 ';
		#plugpress_admin_browse();
		#print 'after2 ';
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