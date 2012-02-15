<?php
//
// Error page
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

	<div class="plugpress-error-global error2">
		<h3><?php echo _e( 'PlugPress could not complete the request correctly.', 'plugpress' ); ?></h3>
		<?php echo $plugpress->admin->error; ?>
	</div>
</div>

