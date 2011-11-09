<?php
//
// PlugPress header
//
?>

<?php if (isset($plugpress->admin->icon)) : ?>
	<?php if ($plugpress->admin->icon != '') : ?>
	<div class="icon32">
		<img src="<?php echo $plugpress->admin->icon; ?>" style="width:32px; height:32px;" onerror="this.src='<?php echo $plugpress->admin->admin_url . 'images/icon32.png'; ?>'" />
	</div>
	<?php endif; ?>
<?php else : ?>
	<?php screen_icon(); ?>
<?php endif; ?>

<h2><?php esc_html_e($plugpress->admin->header); ?></h2>

