<?php
//
// PlugPress Filters Functions
//

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Overrides plugins_api to get informations about plugins handled by PlugPress.com
 *
 * @param mixed Result to return if nothing is modified
 * @param string $action Action called
 * @param array|object $args Optional. Arguments to serialize for the Plugin Info API.
 *
 * @return object plugins_api response object on success. Otherwise, false.
 *
 */
function plugpress_plugins_api($res, $action, $args) {
	global $plugpress;

	#var_dump($args);

	if (strpos($args->slug, 'plugpress-') === 0) {
		$request = wp_remote_post(PlugPress::API_URL . 'infoplugin',
				array(
					'timeout' => 15,
					'body' =>
						array(
							'action' => $action,
							'request' => serialize($args),
							'version' => '1.0',
							'lng' => get_bloginfo('language'),
							'key' => $plugpress->admin->website_key,
							'url' => $plugpress->admin->website_url
						)
					)
				);

		if ( is_wp_error($request) ) {
			$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.'), $request->get_error_message() );
		} else {
			$res = unserialize( wp_remote_retrieve_body( $request ) );
			if ( false === $res ) {
				$res = new WP_Error('plugins_api_failed', __('An unknown error occurred.'), $request['body']);
			}
		}
	}

	return $res;
}


/**
 * Overrides themes_api to get informations about plugins handled by PlugPress.com
 *
 * @param mixed Result to return if nothing is modified
 * @param string $action Action called
 * @param array|object $args Optional. Arguments to serialize for the Theme Info API.
 *
 * @return object themes_api response object on success. Otherwise, false.
 *
 */
function plugpress_themes_api($res, $action, $args) {
	global $plugpress;

	if (strpos($args->slug, 'plugpress-') === 0) {

		$request = wp_remote_post(PlugPress::API_URL . 'infotheme',
				array(
					'timeout' => 15,
					'body' =>
						array(
							'action' => $action,
							'request' => serialize($args),
							'version' => '1.0',
							'lng' => get_bloginfo('language'),
							'key' => $plugpress->admin->website_key,
							'url' => $plugpress->admin->website_url
						)
					)
				);


		#var_dump(/*wp_remote_retrieve_body*/( $request ));exit;

		if ( is_wp_error($request) ) {
			$res = new WP_Error('themes_api_failed', __('An Unexpected HTTP Error occurred during the API request.'), $request->get_error_message() );
		} else {
			$res = unserialize( wp_remote_retrieve_body( $request ) );
			if ( false === $res ) {
				$res = new WP_Error('themes_api_failed', __('An unknown error occurred.'), $request['body']);
			}
			else {
				#var_dump($res);exit;
			}
		}
	}

	return $res;
}


/**
 * Check plugin versions against the latest versions hosted on PlugPress.com.
 *
 * @param mixed $value Actual value or new value returned by WordPress.org
 *
 * @return mixed Value to store
 *
 */
function plugpress_pre_set_site_transient_update_plugins($value) {
	global $plugpress;

	// If plugins were not checked yet, don't continue
	if (!isset($value->checked)) {
		#return $value;
		return get_site_transient('plugpress_update_plugins');
	}

	include ABSPATH . WPINC . '/version.php'; // include an unmodified $wp_version

	$plugin_changed = false;
	$plugins = array();
	foreach ($value->checked as $file => $p) {
		$plugins[$file] = $p;
		$plugin_changed = true;
	}

	// Bail if we've checked in the last 12 hours and if nothing has changed
	if (!$plugin_changed)
		return $value;


	// Update last_checked for current to prevent multiple blocking requests if request hangs
	$current = $value;
	set_site_transient('plugpress_update_plugins', $current);

	$to_send = (object) compact('plugins', 'active');

	$options = array(
		'timeout' => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3),
		'body' => array(
			'action' => 'update-check',
			'plugins' => serialize( $to_send ),
			'key' => $plugpress->admin->website_key,
			'url' => $plugpress->admin->website_url
			),
		'user-agent' => 'PlugPress/' . PLUGPRESS_VERSION . '; ' . get_bloginfo( 'url' ) . '; WP-' . $wp_version
		);

	$raw_response = wp_remote_post(PlugPress::API_URL . 'plugins/updatecheck', $options);

	#var_dump(wp_remote_retrieve_body($raw_response));exit;

	if ( is_wp_error( $raw_response ) )
		return $value;

	if ( 200 != $raw_response['response']['code'] )
		return $value;

	$response = unserialize( wp_remote_retrieve_body( $raw_response ) );

	#var_dump(wp_remote_retrieve_body($raw_response));exit;
	#var_dump($response);exit;

	$new_option = new stdClass;
	$new_option->last_checked = time();
	$new_option->response = $response;
	/*
	if ( false !== $response ) {
		$url = urlencode( get_bloginfo( 'siteurl' ) );
		$key = urlencode( md5( LOGGED_IN_KEY ) . md5( $plugpress->admin->website_url ) );

		$new_reponse = array();
		foreach($response as $plugin) {
			$plugin->package .= "?u={$url}&k={$key}";
			$new_reponse[] = $plugin;
		}

		$new_option->response = $response;
	}
	else {
		$new_option->response = array();
	}
	*/

	set_site_transient( 'plugpress_update_plugins', $new_option );

	$response = array_merge( $value->response, $new_option->response );
	$value->response = $response;

	return $value;
}


/**
 * Check theme versions against the latest versions hosted on PlugPress.com.
 *
 * @param mixed $value Actual value or new value returned by WordPress.org
 *
 * @uses $wp_version Used to notify the WordPress version.
 *
 * @return mixed Returns null if update is unsupported. Returns false if check is too soon.
 */
function plugpress_pre_set_site_transient_update_themes($value) {
	global $plugpress;

	include ABSPATH . WPINC . '/version.php'; // include an unmodified $wp_version

	if (!function_exists('get_themes')) {
		require_once(ABSPATH . 'wp-includes/theme.php');
	}

	if (!isset($value->checked)) {
		#return $value;
		return get_site_transient('plugpress_update_themes');
	}

	#var_dump($value);

	$themes = array();
	$checked = array();

	$theme_changed = false;
	foreach ( $value->checked as $slug => $v ) {
		$themes[$slug] = $v;
		$theme_changed = true;
	}

	if (!$theme_changed)
		return $value;

	// Update last_checked for current to prevent multiple blocking requests if request hangs
	$last_update = new StdClass;
	$last_update->last_checked = time();
	set_site_transient( 'plugpress_update_themes', $last_update );

	#print serialize( $themes );exit;

	$options = array(
		'timeout' => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3),
		'body' => array(
			'themes' => serialize( $themes ),
			'key' => $plugpress->admin->website_key,
			'url' => $plugpress->admin->website_url
			),
		'user-agent' => 'PlugPress/' . PLUGPRESS_VERSION . '; ' . get_bloginfo( 'url' ) . '; WP-' . $wp_version
	);

	$raw_response = wp_remote_post(PlugPress::API_URL . 'themes/updatecheck', $options );

	if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) )
		return $value;

	$new_update = new stdClass;
	$new_update->last_checked = time();
	$new_update->checked = $checked;

	$response = unserialize( wp_remote_retrieve_body( $raw_response ) );
	if ( false !== $response ) {
		$new_update->response = $response;
	}

	set_site_transient( 'plugpress_update_themes', $new_update );

	$response = array_merge($value->response, $new_update->response);
	$value->response = $response;

	#var_dump();

	return $value;
}


/**
 * Before and upgrade install
 *
 * @param boolean $result Default result
 * @param array $hooks Hook extra
 * @return boolean True on success, otherwise WP_Error
 *
 */
function plugpress_upgrader_pre_install($result, $hooks) {

	#var_dump($hooks);exit;
}