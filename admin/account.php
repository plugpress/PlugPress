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

	public $user = null;
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

		add_action( 'wp_ajax_plugpress_unlink_account', array( &$this, 'unlink_callback' ) );

	}

	/**
	 * View
	 */
	public function view() {
		global $plugpress;

		if ( is_wp_error( $this->purchases ) ) {
			$msg = PlugPress_Admin::get_error_message( $this->purchases );
			$plugpress->admin->error = $msg;

			include( $plugpress->admin->admin_dir . 'views/error.php' );
		}
		else {
			include( $plugpress->admin->admin_dir . 'views/account.php' );
		}
	}

	/**
	 * Get data depending on the context
	 */
	private function get_context_data() {
		global $plugpress;

		require( $plugpress->plugin_dir . 'includes/server.php' ) ;
		// Call external data (if needed)
		$serv = new PlugPress_Server();

		$this->purchases = $serv->get_user_purchases($plugpress->admin->website_url, $plugpress->admin->website_key);

		$this->user = $serv->get_linked_user( $plugpress->admin->website_url, $plugpress->admin->website_key );

		if ( ! isset($plugpress->user ) ) {
			$plugpress->username = $this->user;
		}

		if ( strpos( $plugpress->username, ' ') === 0 ) {
			add_action( 'admin_notices', array( &$this, 'unconfirmedAccount' ) );
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


		# '<p>' . __( '', 'plugpress' ) . '</p>' .
		$help =
			'<p>' . __( 'Welcome to the PlugPress Account.', 'plugpress' ) . '</p>' .
			'<p>' . __( 'If this is your first time here, start by creating an account or linking to an existing account.  Simply follow the steps!', 'plugpress' ) . '</p>' .
			'<p>' . __( 'When linked to an account, this screen displays everything you bought from PlugPress. It enables you to install within a couple of clicks every purchase you have in your account. ', 'plugpress' ) . '</p>';

		add_contextual_help( $current_screen, $help . $help_link);
	}

	/**
	 * Unconfirmed account warning message
	 */
	public function unconfirmedAccount() {
		echo '<div class="updated"><p><b>' .  __( 'Your account is unconfirmed. Check your emails (including your SPAM) and confirm it by clicking on confirmation link.', 'plugpress' ) . '</b></p></div>';
	}

	/**
	 * Unlink an account (meaning delete the username saved in db)
	 */
	public static function unlink_callback() {
		$option_name = 'plugpress_account_user';
		$transient_name = $option_name . '_check';

		$user = delete_site_option($option_name);
		$user_check = delete_site_transient($transient_name);

		die();
	}

}


global $plugpress;
if ( 'PlugPress' == get_class( $plugpress ) ) {
	$plugpress->admin->account = new PlugPress_Account_Admin();
}

endif;
