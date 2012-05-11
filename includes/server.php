<?php

/**
 * PlugPress Server calls
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'PlugPress_Server' ) ) :

/**
 * PlugPress Server
 */
class PlugPress_Server {

	protected $home_cache_delay = 1;
	protected $user_linked_cache_delay = 1;
	protected $user_purchases_cache_delay = 1;
	protected $plugin_information_cache_delay = 1;
	protected $plugins_cache_delay = 1;
	protected $theme_information_cache_delay = 1;
	protected $themes_cache_delay = 1;


	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_cache();
	}

	/**
	 * Set cache delay
	 */
	private function set_cache() {
		$this->home_cache_delay = 1;
		$this->user_linked_cache_delay = 1;
		$this->user_purchases_cache_delay = 1;
		$this->plugin_information_cache_delay = 1;
		$this->plugins_cache_delay = 1;
		$this->theme_information_cache_delay = 1;
		$this->themes_cache_delay = 1;
	}

	/**
	 * Get the data to display on the homepage
	 *
	 * Try to get the content from the cache.  If the content does not exist or if
	 * the cache has expired, request it directly from PlugPress.com
	 *
	 * @return StdClass|boolean Data on success, otherwise FALSE.
	 */
	public function get_home_data() {
		$transient_name = 'plugpress_home_data';
		$content = get_transient( $transient_name );

		$cache_valid = false;
		if (is_object($content)) {
			$cache_valid = isset( $content->last_checked ) && $this->home_cache_delay > ( time() - $content->last_checked );
		}

		if ( !is_object( $content ) || $cache_valid === false ) {
			$content = new StdClass;
			$content->last_checked = time();
			$raw_response = wp_remote_post( PlugPress::API_URL . 'home',
					array(
						'version' => '1.0',
						'lng' => get_bloginfo( 'language' )
					)
				);

			#var_dump( wp_remote_retrieve_body( $raw_response ) );exit;

			if ( is_wp_error( $raw_response ) ) {
				#var_dump( $raw_response );
				return $raw_response;
			}
			else {
				$content->value = unserialize( wp_remote_retrieve_body( $raw_response ) );
				set_transient( $transient_name, $content, $this->home_cache_delay );
			}
		}

		return $content->value;
	}

	/**
	 * Get user linked to the website
	 *
	 * @param string $website_url Website URL
	 * @param string $website_key Website secret key
	 *
	 * @return StdClass|boolean Data on success, otherwise FALSE.
	 */
	public function get_linked_user( $website_url, $website_key ) {
		$option_name = 'plugpress_account_user';
		$transient_name = $option_name . '_check';

		$user = get_site_option( $option_name, null );
		$user_check = get_site_transient( $transient_name );

		if ( $user_check === false || $user === null ) {
			$raw_response = wp_remote_post( PlugPress::API_URL . 'userlinked',
					array( 'body' =>
						array (
							'version' => '1.0',
							'lng' => get_bloginfo( 'language' ),
							'key' => $website_key,
							'url' => $website_url
						)
					)
				);

			#var_dump(wp_remote_retrieve_body( $raw_response ));

			if ( is_wp_error( $raw_response ) ) {
				return $raw_response;
			}
			else {
				$content = unserialize( wp_remote_retrieve_body( $raw_response ) );
				if ( isset( $content->username ) ) {
					$user = $content->username;

					#var_dump($user);

					// If username start with space, error!
					if ( null != $user && strpos( $user, ' ' ) !== 0 ) {
						$u = update_site_option( $option_name, $user );
						$t = set_site_transient( $transient_name, true, $this->user_linked_cache_delay );
					}
				}
				else {
					delete_site_option( $option_name );
					delete_site_transient( $transient_name );
				}
			}
		}

		return $user;
	}

	/**
	 * Get user purchases
	 *
	 * @param string $website_url Website URL
	 * @param string $website_key Website secret key
	 *
	 * @return StdClass|boolean Data on success, otherwise FALSE.
	 */
	public function get_user_purchases( $website_url, $website_key ) {
		$option_name = 'plugpress_account_purchases';
		$transient_name = $option_name . '_check';

		$purchases = get_site_option( $option_name, null );
		$purchases_check = get_site_transient( $transient_name );

		#var_dump($purchases, $purchases_check);

		if ( $purchases_check === false || $purchases === null ) {
			$raw_response = wp_remote_post( PLUGPRESS::API_URL . 'purchases',
					array( 'body' =>
						array (
							'version' => '1.0',
							'lng' => get_bloginfo( 'language' ),
							'key' => $website_key,
							'url' => $website_url
						)
					)
				);


			#var_dump(wp_remote_retrieve_body($raw_response));exit;

			if ( is_wp_error( $raw_response ) ) {
				return $raw_response;
			}
			else {
				$content = unserialize( wp_remote_retrieve_body( $raw_response ) );
				if (isset($content->purchases)) {
					$purchases = $content->purchases;
					update_site_option( $option_name, $purchases );
					set_site_transient( $transient_name, true, $this->user_purchases_cache_delay );
				}
			}
		}
		return $purchases;
	}

	/**
	 * Get the data from a specific plugin
	 *
	 * Try to get the content from the cache.  If the content does not exist or if
	 * the cache has expired, request it directly from PlugPress.com
	 *
	 * @param string $slug Plugin slug
	 *
	 * @return class Data on success, otherwise FALSE.
	 */
	public function get_plugin_information( $slug ) {
		$transient_name = 'plugpress_plugin_'. $slug .'_data';
		$content = get_transient( $transient_name );

		$cache_valid = false;
		if(is_object($content)) {
			$cache_valid = isset( $content->last_checked ) && $this->plugin_information_cache_delay > ( time() - $content->last_checked );
		}

		if ( !is_object( $content ) || $cache_valid === false ) {
			$content = new StdClass;
			$content->last_checked = time();
			$raw_response = wp_remote_post( PLUGPRESS::API_URL . 'plugin/' . $slug,
					array(
						'body' => array(
							'version' => '1.0',
							'lng' => get_bloginfo( 'language' )
							)
						)
					);

			#var_dump(wp_remote_retrieve_body($raw_response));

			if ( is_wp_error( $raw_response ) ) {
				return $raw_response;
			}
			else {
				$content->value = unserialize( wp_remote_retrieve_body( $raw_response ) );
				set_transient( $transient_name, $content, $this->plugin_information_cache_delay );
			}
		}

		return $content->value;
	}

	/**
	 * Get plugins
	 *
	 * Try to get the content from the cache.  If the content does not exist or if
	 * the cache has expired, request it directly from PlugPress.com
	 *
	 * @param int $page Page to display
	 * @param string $category Category slug
	 *
	 * @return class Data on success, otherwise FALSE.
	 */
	public function get_plugins( $page = 1, $category = '' ) {
		$transient_name = 'plugpress_plugins_'. $category .'_data_' . $page;
		$content = get_transient( $transient_name );

		$cache_valid = false;
		if ( is_object( $content ) ) {
			$cache_valid = isset( $content->last_checked ) && $this->plugins_cache_delay > ( time() - $content->last_checked );
		}

		if ( !is_object( $content ) || $cache_valid === false ) {
			$content = new StdClass;
			$content->last_checked = time();
			$raw_response = wp_remote_post( PLUGPRESS::API_URL . 'plugins/' . $category,
					array( 'body' =>
						array(
							'page' => $page,
							'version' => '1.0',
							'lng' => get_bloginfo( 'language' )
						)
					)
				);

			#var_dump(wp_remote_retrieve_body($raw_response));

			if ( is_wp_error( $raw_response ) ) {
				return $raw_response;
			}
			else {
				$content->value = unserialize( wp_remote_retrieve_body( $raw_response ) );
				set_transient( $transient_name, $content, $this->plugins_cache_delay );
			}
		}

		return $content->value;
	}

	/**
	 * Search plugins
	 *
	 * @param string $query Query
	 * @param int $page Page to display
	 *
	 * @return class Data on success, otherwise FALSE.
	 */
	public function search_plugins( $query, $page = 1 ) {

		$content = new StdClass;
		$content->last_checked = time();
		$raw_response = wp_remote_post( PLUGPRESS::API_URL . 'searchplugins/',
				array( 'body' =>
					array(
						'q' => $query,
						'page' => $page,
						'version' => '1.0',
						'lng' => get_bloginfo( 'language' )
					)
				)
			);

		#var_dump(wp_remote_retrieve_body($raw_response));

		if ( is_wp_error( $raw_response ) ) {
			return $raw_response;
		}
		else {
			$content->value = unserialize( wp_remote_retrieve_body( $raw_response ) );
		}


		return $content->value;
	}

	/**
	 * Get the data for a specific theme
	 *
	 * Try to get the content from the cache.  If the content does not exist or if
	 * the cache has expired, request it directly from PlugPress.com
	 *
	 * @param string $slug Theme slug
	 *
	 * @return class Data on success, otherwise FALSE.
	 */
	public function get_theme_information( $slug ) {
		$transient_name = 'plugpress_theme_'. $slug .'_data';
		$content = get_transient( $transient_name );

		$cache_valid = false;
		if ( is_object( $content ) ) {
			$cache_valid = isset( $content->last_checked ) && $this->theme_information_cache_delay > ( time() - $content->last_checked );
		}

		if ( !is_object( $content ) || $cache_valid === false ) {
			$content = new StdClass;
			$content->last_checked = time();
			$raw_response = wp_remote_post( PLUGPRESS::API_URL . 'theme/' . $slug,
					array(
						'body' => array(
							'version' => '1.0',
							'lng' => get_bloginfo( 'language' )
							)
						)
					);

			#var_dump(wp_remote_retrieve_body($raw_response));

			if ( is_wp_error( $raw_response ) ) {
				return $raw_response;
			}
			else {
				$content->value = unserialize( wp_remote_retrieve_body( $raw_response ) );
				set_transient( $transient_name, $content, $this->theme_information_cache_delay );
			}
		}

		return $content->value;
	}

	/**
	 * Get the data for themes
	 *
	 * Try to get the content from the cache.  If the content does not exist or if
	 * the cache has expired, request it directly from PlugPress.com
	 *
	 * @param string $category Category slug
	 * @param int $page Page to display
	 *
	 * @return class Data on success, otherwise FALSE.
	 */
	public function get_themes( $page = 1, $category = '' ) {
		$transient_name = 'plugpress_plugins_'. $category .'_data_' . $page;
		$content = get_transient( $transient_name );

		$cache_valid = false;
		if ( is_object( $content ) ) {
			$cache_valid = isset( $content->last_checked ) && $this->themes_cache_delay > ( time() - $content->last_checked );
		}

		if ( !is_object( $content ) || $cache_valid === false ) {
			$content = new StdClass;
			$content->last_checked = time();
			$raw_response = wp_remote_post( PLUGPRESS::API_URL . 'themes/' . $category,
					array( 'body' =>
						array(
							'page' => $page,
							'version' => '1.0',
							'lng' => get_bloginfo( 'language' )
						)
					)
				);

			#var_dump(wp_remote_retrieve_body($raw_response));

			if ( is_wp_error( $raw_response ) ) {
				return $raw_response;
			}
			else {
				$content->value = unserialize( wp_remote_retrieve_body( $raw_response ) );
				set_transient( $transient_name, $content, $this->themes_cache_delay );
			}
		}

		return $content->value;
	}

}

endif;