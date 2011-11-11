<?php
/**
 * PlugPress Account Admin
 */


// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'PlugPress_Account_Admin' ) ) :

/**
 * Loads PlugPress Browse Admin
 *
 */
class PlugPress_Account_Admin {

	public $purchases = null;
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
		global $plugpress;
		$plugpress->admin->header = __( 'PlugPress - My Account', 'plugpress' );
	}

	/**
	 * Setup actions to hookup
	 */
	private function setup_actions() {
		add_action( 'plugpress_account_view', array( &$this, 'view' ) );
	}

	/**
	 * View
	 *
	 */
	public function view() {
		global $plugpress;

		include( $plugpress->admin->admin_dir . 'views/account.php' );
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

		$this->purchases = $serv->get_user_purchases($plugpress->admin->website_url, $plugpress->admin->website_key);
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


		# '<p>' . __( '', 'plugpress' ) . '</p>' .
		$help =
			'<p>' . __( 'Welcome to the PlugPress Account.' ) . '</p>' .
			'<p>' . __( 'If this is your first time here, start by creating an account or linking to an existing account.  Simply follow the steps!', 'plugpress' ) . '</p>' .
			'<p>' . __( 'When linked to an account, this screen displays everything you bought from PlugPress. It enables you to install within a couple of clicks every purchase you have in your account. ', 'plugpress' ) . '</p>';

		add_contextual_help( $current_screen, $help . $help_link);
	}

}


global $plugpress;
if ( 'PlugPress' == get_class( $plugpress ) ) {
	$plugpress->admin->account = new PlugPress_Account_Admin();
}

endif;
