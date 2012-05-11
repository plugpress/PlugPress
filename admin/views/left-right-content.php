<?php
//
// Index view
//

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap">

<?php
global $plugpress;
require( $plugpress->admin->admin_dir . 'views/_header.php' );
require( $plugpress->admin->admin_dir . 'views/_menu.php' );
?>

<div id="plugpress-announcement-top">
	<?php echo $plugpressView->announcement->top; ?>
</div>

<div id="plugpress-content">
	<div id="plugpress-content-left">
		<div id="plugpress-left-boxes" class="metabox-holder">
			<div class='postbox-container plugpress-postbox-container'>
				<?php do_meta_boxes( 'plugpress-split-left', 'advanced', null ); ?>
			</div>
		</div>
	</div>
	<div id="plugpress-content-right">
		<div id="plugpress-right-boxes" class="metabox-holder">
			<div class='postbox-container plugpress-postbox-container'>
				<div style="margin-bottom:20px;text-align:right;">
					<form id="search-form" onsubmit="return plugpress_search()" method="get">
						<input type="text" style="font-weight:normal;width:100%" id="plugin-search" value="<?php echo isset($_GET['ppq']) ? esc_attr($_GET['ppq']) : ''; ?>" name="search" />
						<input type="submit" style="font-weight:normal;margin-top:8px" class="button" name="submit" value="<?php esc_html_e('Search plugins', 'plugpress'); ?>" />
					</form>
				</div>
				<?php do_meta_boxes( 'plugpress-split-right', 'advanced', null ); ?>
			</div>
		</div>
	</div>
</div>

<div id="plugpress-announcement-bottom">
	<?php echo $plugpressView->announcement->bottom; ?>
</div>

<div id="plugpress-footer">
	<?php echo $plugpressView->footer; ?>
</div>


</div>



<script type='text/javascript'>
	var plugpress_admin_url = '<?php echo $plugpress->admin->admin_url; ?>';

	jQuery(document).ready(function ($) {
		//Hack
		$('.handlediv').remove();
	});
</script>
