<?php
//
// PlugPress Menu
//
?>

<h3 class="plugpress-nav-tab-wrapper">
<span>&nbsp; &nbsp;</span>
<a href="<?php echo admin_url('admin.php?page=plugpress-browse'); ?>" class="plugpress-nav-tab<?php if ($plugpress->admin->tab == 'home'){ echo ' plugpress-nav-tab-active'; } ?>"><?php esc_html_e('Home', 'plugpress'); ?></a>
<a href="<?php echo admin_url('admin.php?page=plugpress-browse&ppsubpage=plugins'); ?>" class="plugpress-nav-tab<?php if ($plugpress->admin->tab == 'plugins'){ echo ' plugpress-nav-tab-active'; } ?>"><?php esc_html_e('Browse Plugins', 'plugpress'); ?></a>
<?php if (current_user_can('install_themes')) : ?>
<a href="<?php echo admin_url('admin.php?page=plugpress-browse&ppsubpage=themes'); ?>" class="plugpress-nav-tab<?php if ($plugpress->admin->tab == 'themes'){ echo ' plugpress-nav-tab-active'; } ?>"><?php echo esc_html_e('Browse Themes', 'plugpress'); ?></a>
<a href="http://www.plugpress.com/support" target="_blank" class="plugpress-nav-tab"><?php esc_html_e('Help', 'plugpress'); ?></a>
<?php /*<a href="<?php echo admin_url('admin.php?page=plugpress&ppsubpage=feedback'); ?>" class="plugpress-nav-tab<?php if ($plugpressView->internal->controller == 'feedback'){ echo ' plugpress-nav-tab-active'; } ?>"><?php esc_html_e('Feedback', 'plugpress'); ?></a>*/ ?>
<?php endif; ?>
</h3>

