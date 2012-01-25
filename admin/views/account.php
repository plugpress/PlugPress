<?php
//
// Account view
//


require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');

?>

<div class="wrap">

<?php
require( $plugpress->admin->admin_dir . 'views/_header.php' );
?>

<form id="plugpress-form-account" action="<?php echo $plugpress->plugin_url; ?>account/" method="POST" target="_blank">
	<input type="hidden" name="autofill" value="y" />
	<input type="hidden" name="username" value="<?php esc_attr_e($plugpress->username) ?>" />
	<input type="hidden" name="websiteurl" value="<?php esc_attr_e($plugpress->admin->website_url) ?>" />
	<input type="hidden" name="key" value="<?php esc_attr_e($plugpress->admin->website_key) ?>" />
</form>

<h3><?php _e('Basic Information') ?></h3>
<p><?php _e('The information below will enable you to link this WordPress installation with your PlugPress account.  Once you are linked, you will be able to download and update all purchases made from PlugPress.', 'plugpress') ?></p>

<table class="form-table">
<tr valign="top">
<th scope="row"><label for="website_linked"><?php _e('Linked to PlugPress account', 'plugpress') ?></label></th>
<td>
	<?php if ($plugpress->username == null) : ?>
	<span style="color:white;background:red;padding:3px;margin-right:20px;"><?php _e('None', 'plugpress') ?></span>
	<a href="#" onclick="plugpress_create_account();"><?php esc_html_e('Create New Account', 'plugpress') ?></a> <?php esc_html_e('or', 'plugpress') ?> <a href="#" onclick="plugpress_link_account();"><?php esc_html_e('Link to Existing Account', 'plugpress') ?></a>

	<script type="text/javascript">
		function plugpress_create_account() {
			var f = jQuery("#plugpress-form-account");
			f.get(0).setAttribute('action', '<?php echo PlugPress::WEBSITE_URL_SSL; ?>account/new');
			f.submit();
		}
		function plugpress_link_account() {
			var f = jQuery("#plugpress-form-account");
			f.get(0).setAttribute('action', '<?php echo PlugPress::WEBSITE_URL_SSL; ?>account/link');
			f.submit();
		}
	</script>

	<?php elseif ( strpos( $plugpress->username, ' ' ) === 0 ): ?>
		<b class="plugpress-red"><?php esc_html_e($plugpress->username) ?></b>
	<?php else: ?>
	<script type="text/javascript">
		function plugpress_unlink_account() {
			var f = jQuery("#plugpress-form-account");
			f.get(0).setAttribute('action', '<?php echo PlugPress::WEBSITE_URL_SSL; ?>account/unlink');
			f.submit();
			var data = {
				action: 'plugpress_unlink_account'
			}
			jQuery.post(ajaxurl, data, function(response) {
				//alert(response + data);
				setTimeout(function() { window.location.reload(); }, 2000);
			});

		}
	</script>
	<b><?php esc_html_e($plugpress->username) ?></b> &nbsp; <a href="#" onclick="if (confirm('<?php esc_attr_e('Are you sure you want to unlink your website from this account ?', 'plugpress') ?>')){plugpress_unlink_account();}"><?php esc_html_e('Unlink account', 'plugpress') ?></a>
	<?php endif; ?>
</td>
</tr>
<?php if ($plugpress->username == null) : ?>
<tr valign="top">
<th scope="row"><label for="website_url"><?php _e('Website URL', 'plugpress') ?></label></th>
<td><input name="website_url" type="text" id="website_url" value="<?php esc_attr_e($plugpress->admin->website_url) ?>" class="code" style="width:475px" disabled="disabled" />
</td>
</tr>
<tr valign="top">
<th scope="row"><label for="website_key"><?php _e('PlugPress Key phrase', 'plugpress') ?></label></th>
<td><input name="website_key" type="text" id="website_key" value="<?php esc_attr_e($plugpress->admin->website_key) ?>" class="code" style="width:475px" disabled="disabled" /></td>
</tr>
<?php endif; ?>
</table>
<br /><br />


<?php if ($plugpress->username != null && strpos( $plugpress->username, ' ' ) === false && $plugpress->admin->account->purchases != null) : ?>
<h3><?php _e('Plugins') ?></h3>
<p><?php _e('The following list contains every plugins you bought on PlugPress and their status.', 'plugpress') ?></p>

<div>
<?php if (is_array($plugpress->admin->account->purchases->plugins) && count($plugpress->admin->account->purchases->plugins) > 0): ?>

<table class="widefat">
<thead>
	<tr>
		<th><?php esc_html_e('Plugin Name', 'plugpress') ?></th>
		<th><?php esc_html_e('Lastest Version', 'plugpress') ?></th>
		<th><?php esc_html_e('Purchase Date', 'plugpress') ?></th>
		<?php /*<th><?php esc_html_e('Support and Updates', 'plugpress') ?></th> */ ?>
		<th><?php esc_html_e('Status', 'plugpress') ?></th>
	</tr>
</thead>
<tbody>
	<?php foreach($plugpress->admin->account->purchases->plugins as $plugin) : ?>
	<tr>
		<td><a href="admin.php?page=plugpress-browse&ppsubpage=plugindetail&ppslug=<?php echo $plugin->slug; ?>"<b><?php esc_html_e($plugin->name) ?></b></td>
		<td><?php esc_html_e($plugin->version) ?></td>
		<td><?php esc_html_e($plugin->purchasedate) ?></td>
		<?php /*
		<td>
			<?php if ($plugin->isactive) : ?>
			<?php esc_html_e('Yes', 'plugpress')  ?>
			<?php else: ?>
			<span class="plugpress-red plugpress-bold"><?php esc_html_e('No', 'plugpress')  ?></span>
			<?php endif; ?>
		</td>
		*/ ?>
		<?php

			$status = install_plugin_install_status( $plugin, true );

			$action = '';
			switch ( $status['status'] ) {
				case 'install':
					if ( $status['url'] ) {
						$action = '<a class="install-now" href="' . $status['url'] . '" title="' . esc_attr(sprintf(__('Install %s', 'plugpress'), $name ) ) . '">' . __('Install Now', 'plugpress') . '</a>';
					}
					else {
						$action = '<span title="' . esc_attr__('This plugin is already installed and is up to date', 'plugpress') . ' ">' . __( 'Installed' ) . '</span>';
					}
					break;
				case 'update_available':
					if ( $status['url'] )
						$action = '<a href="' . $status['url'] . '" title="' . esc_attr(sprintf(__('Update to version %s', 'plugpress'), $status['version'])) . '" class="plugpress-bold plugpress-orange">' . sprintf(__('Update Now', 'plugpress'), $status['version'] ) . '</a>';
					break;
				case 'latest_installed':
				case 'newer_installed':
					$action = '<span title="' . esc_attr__('This plugin is already installed and is up to date', 'plugpress') . ' ">' . __('Installed', 'plugpress') . '</span>';
					break;
			}
		?>
		<td>
			<?php
				if (!empty($action)) {
					echo $action;
				}
			?>
		</td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>

<?php else: ?>
	<p><em><?php esc_html_e('You do not have any plugins linked to your account yet.', 'plugpress') ?></em> <a href="admin.php?page=plugpress-browse"><?php esc_html_e('Browse plugins now', 'plugpress') ?> &raquo;</a></p>
<?php endif; ?>
</div>
<br /><br />



<h3><?php _e('Themes') ?></h3>
<p><?php _e('The following list contains every themes you bought on PlugPress.', 'plugpress') ?></p>

<div>
<?php if (is_array($plugpress->admin->account->purchases->themes) && count($plugpress->admin->account->purchases->themes) > 0): ?>

<table class="widefat">
<thead>
	<tr>
		<th><?php esc_html_e('Theme Name', 'plugpress') ?></th>
		<th><?php esc_html_e('Lastest Version', 'plugpress') ?></th>
		<th><?php esc_html_e('Purchase Date', 'plugpress') ?></th>
		<th><?php esc_html_e('Status', 'plugpress') ?></th>
	</tr>
</thead>
<tbody>
	<?php
		$themes = get_site_transient('update_themes');
		foreach($plugpress->admin->account->purchases->themes as $theme) :
	?>
	<tr>
		<td><a href="admin.php?page=plugpress-browse&ppsubpage=themedetail&ppslug=<?php echo $theme->slug; ?>"<b><?php esc_html_e($theme->name) ?></b></td>
		<td><?php esc_html_e($theme->version) ?></td>
		<td><?php esc_html_e($theme->purchasedate) ?></td>
		<td>
			<?php
				if (array_key_exists($theme->slug, $themes->checked)) {
					if (version_compare($themes->checked[$theme->slug], $theme->version, '<') ) {
						$action = '<a class="plugpress-bold plugpress-orange" id="install"	href="' . wp_nonce_url(self_admin_url('update.php?action=upgrade-theme&theme=' . $theme->slug), 'upgrade-theme_' . $theme->slug) . '">' . __('Update Now', 'plugpress') . '</a>';
					}
					elseif (version_compare($themes->checked[$theme->slug], $theme->version, '>') ) {
						$action = '<span title="' . esc_attr__('This theme is already installed and is newer than the actual version', 'plugpress') . ' ">' . sprintf(esc_html__('Newer version (%s) is installed.'), $themes->checked[$theme->slug]) . '</span>';
					}
					else {
						$action = '<span title="' . esc_attr__('This theme is already installed and is up to date', 'plugpress') . ' ">' . esc_html__('Installed', 'plugpress') . '</span>';
					}
				}
				else {
					$action = '<a class="install-now" id="install" href="' . wp_nonce_url(self_admin_url('update.php?action=install-theme&theme=' . $theme->slug), 'install-theme_' . $theme->slug) . '" title="' . esc_attr(sprintf(__('Install %s', 'plugpress'), $theme->name )) . '">' . __('Install Now', 'plugpress') . '</a>';
				}

				if (!empty($action)) {
					echo $action;
				}
			?>
		</td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>

<?php else: ?>
	<p><em><?php _e('You do not have any themes linked to your account yet.', 'plugpress') ?></em> <a href="admin.php?page=plugpress-browse"><?php esc_html_e('Browse themes now', 'plugpress') ?> &raquo;</a></p>
<?php endif; ?>
</div>
<br /><br />
<?php endif; ?>





