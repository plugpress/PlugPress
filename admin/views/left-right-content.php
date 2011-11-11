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
