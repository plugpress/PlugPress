<?php
//
// Plugin Detail View
//

global $plugpress;
?>

<style type="text/css">
a.plugpress-thumbnail-prev, a.plugpress-thumbnail-next {background: url(<?php echo $plugpress->admin->admin_url; ?>images/prevnext.png) no-repeat transparent;}

a.plugpress-thumbnail-prev{background-position: -16px 0; }
a.plugpress-thumbnail-prev.disabled{background-position: 0 -32px !important; cursor: default;}
a.plugpress-thumbnail-prev:hover{background-position: 0 0;}
a.plugpress-thumbnail-next{background-position: -16px -16px;}
a.plugpress-thumbnail-next.disabled{background-position: -16px -32px !important; cursor: default;}
a.plugpress-thumbnail-next:hover{background-position: 0 -16px;}
a.plugpress-thumbnail-prev span, a.plugpress-thumbnail-next span{display: none;}

.plugpress-thumbnail-pagination a{background: url(<?php echo $plugpress->admin->admin_url; ?>images/green-balls.png) -16px 0 no-repeat transparent; width:16px; height:16px; margin:0 5px 0 0; display:inline-block;}
.plugpress-thumbnail-pagination a.selected{background-position: 0 0; cursor: default;}
.plugpress-thumbnail-pagination a span{display: none;}


/* PrettyPhoto*/
div.facebook .pp_top .pp_left{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) -88px -53px no-repeat}
div.facebook .pp_top .pp_middle{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/contentPatternTop.png) top left repeat-x}
div.facebook .pp_top .pp_right{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) -110px -53px no-repeat}
div.facebook .pp_content_container .pp_left{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/contentPatternLeft.png) top left repeat-y}
div.facebook .pp_content_container .pp_right{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/contentPatternRight.png) top right repeat-y}
div.facebook .pp_content{background:#fff}
div.facebook .pp_expand{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) -31px -26px no-repeat;cursor:pointer}
div.facebook .pp_expand:hover{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) -31px -47px no-repeat;cursor:pointer}
div.facebook .pp_contract{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) 0 -26px no-repeat;cursor:pointer}
div.facebook .pp_contract:hover{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) 0 -47px no-repeat;cursor:pointer}
div.facebook .pp_close{width:22px;height:22px;background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) -1px -1px no-repeat;cursor:pointer}
div.facebook .pp_details{position:relative}
div.facebook .pp_description{margin:0 37px 0 0}
div.facebook .pp_loaderIcon{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/loader.gif) center center no-repeat}
div.facebook .pp_arrow_previous{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) 0 -71px no-repeat;height:22px;margin-top:0;width:22px}
div.facebook .pp_arrow_previous.disabled{background-position:0 -96px;cursor:default}
div.facebook .pp_arrow_next{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) -32px -71px no-repeat;height:22px;margin-top:0;width:22px}
div.facebook .pp_arrow_next.disabled{background-position:-32px -96px;cursor:default}
div.facebook .pp_nav{margin-top:0}
div.facebook .pp_nav p{font-size:15px;padding:0 3px 0 4px}
div.facebook .pp_nav .pp_play{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) -1px -123px no-repeat;height:22px;width:22px}
div.facebook .pp_nav .pp_pause{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) -32px -123px no-repeat;height:22px;width:22px}
div.facebook .pp_next:hover{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/btnNext.png) center right no-repeat;cursor:pointer}
div.facebook .pp_previous:hover{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/btnPrevious.png) center left no-repeat;cursor:pointer}
div.facebook .pp_bottom .pp_left{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) -88px -80px no-repeat}
div.facebook .pp_bottom .pp_middle{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/contentPatternBottom.png) top left repeat-x}
div.facebook .pp_bottom .pp_right{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/sprite.png) -110px -80px no-repeat}
div.pp_pic_holder a:focus{outline:none}
div.pp_overlay{background:#000;display:none;left:0;position:absolute;top:0;width:100%;z-index:9500}
div.pp_pic_holder{display:none;position:absolute;width:100px;z-index:10000}
.pp_content{height:40px;min-width:40px}
* html .pp_content{width:40px}
.pp_content_container{position:relative;text-align:left;width:100%}
.pp_content_container .pp_left{padding-left:20px}
.pp_content_container .pp_right{padding-right:20px}
.pp_content_container .pp_details{float:left;margin:10px 0 2px}
.pp_description{display:none;margin:0}
.pp_social{float:left;margin:7px 0 0}
.pp_social .facebook{float:left;position:relative;top:-1px;margin-left:5px;width:55px;overflow:hidden}
.pp_social .twitter{float:left}
.pp_nav{clear:right;float:left;margin:3px 10px 0 0}
.pp_nav p{float:left;margin:2px 4px}
.pp_nav .pp_play,.pp_nav .pp_pause{float:left;margin-right:4px;text-indent:-10000px}
a.pp_arrow_previous,a.pp_arrow_next{display:block;float:left;height:15px;margin-top:3px;overflow:hidden;text-indent:-10000px;width:14px}
.pp_hoverContainer{position:absolute;top:0;width:100%;z-index:2000}
.pp_gallery{display:none;left:50%;margin-top:-50px;position:absolute;z-index:10000}
.pp_gallery div{float:left;overflow:hidden;position:relative}
.pp_gallery ul{float:left;height:35px;position:relative;white-space:nowrap;margin:0 0 0 5px;padding:0}
.pp_gallery ul a{border:1px rgba(0,0,0,0.5) solid;display:block;float:left;height:33px;overflow:hidden}
.pp_gallery ul a:hover,.pp_gallery li.selected a{border-color:#fff}
.pp_gallery ul a img{border:0}
.pp_gallery li{display:block;float:left;margin:0 5px 0 0;padding:0}
.pp_gallery li.default a{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/default_thumbnail.gif) 0 0 no-repeat;display:block;height:33px;width:50px}
.pp_gallery .pp_arrow_previous,.pp_gallery .pp_arrow_next{margin-top:7px!important}
a.pp_next{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/btnNext.png) 10000px 10000px no-repeat;display:block;float:right;height:100%;text-indent:-10000px;width:49%}
a.pp_previous{background:url(<?php echo $plugpress->admin->admin_url;?>images/facebook/btnNext.png) 10000px 10000px no-repeat;display:block;float:left;height:100%;text-indent:-10000px;width:49%}
a.pp_expand,a.pp_contract{cursor:pointer;display:none;height:20px;position:absolute;right:30px;text-indent:-10000px;top:10px;width:20px;z-index:20000}
a.pp_close{position:absolute;right:0;top:0;display:block;line-height:22px;text-indent:-10000px}
.pp_loaderIcon{display:block;height:24px;left:50%;position:absolute;top:50%;width:24px;margin:-12px 0 0 -12px}
#pp_full_res{line-height:1!important}
#pp_full_res .pp_inline{text-align:left}
#pp_full_res .pp_inline p{margin:0 0 15px}
div.ppt{color:#fff;display:none;font-size:17px;z-index:9999;margin:0 0 5px 15px}
div.facebook .pp_content .ppt,div.facebook #pp_full_res .pp_inline{color:#000}
.pp_top,.pp_bottom{height:20px;position:relative}
* html .pp_top,* html .pp_bottom{padding:0 20px}
.pp_top .pp_left,.pp_bottom .pp_left{height:20px;left:0;position:absolute;width:20px}
.pp_top .pp_middle,.pp_bottom .pp_middle{height:20px;left:20px;position:absolute;right:20px}
* html .pp_top .pp_middle,* html .pp_bottom .pp_middle{left:0;position:static}
.pp_top .pp_right,.pp_bottom .pp_right{height:20px;left:auto;position:absolute;right:0;top:0;width:20px}
.pp_fade,.pp_gallery li.default a img{display:none}
</style>


<div id="plugpress-boxes">
    <div id="plugpress-transaction" class="plugpress-modal" style="text-align:center">
        <?php /*<img src="<?php echo $plugpress->admin->admin_url;?>images/icon32.png" />*/ ?>
		<?php esc_html_e('PlugPress must open a new window to complete the transaction safely.', 'plugpress'); ?><br /><br />
		<a href="#" onclick="plugpress_buyit()" class="button-primary"><?php esc_html_e('Proceed'); ?></a>
		<span id="plugpress_seconds_left"></span>
    </div>

    <div id="plugpress-mask"></div>
</div>


<div class="wrap">

<?php
require( $plugpress->admin->admin_dir . 'views/_header.php' );
?>

<div style="clear:both"></div>

<small>
	<a href="<?php echo get_admin_url(null, 'admin.php?page=plugpress-browse') ?>" title="<?php esc_attr_e(__('PlugPress', 'plugpress')); ?>"><?php esc_html_e(__('PlugPress', 'plugpress')); ?></a> &gt;
	<a href="<?php echo get_admin_url(null, 'admin.php?page=plugpress-browse&ppsubpage=themes') ?>" title="<?php esc_attr_e(__('Themes', 'plugpress')); ?>"><?php esc_html_e(__('Themes', 'plugpress')); ?></a> &gt;
	<?php echo esc_html($plugpress->admin->header); ?>
</small>

<div style="clear:both">&nbsp;</div>

<div id="plugpress-announcement-top">
	<?php echo $plugpress->announcement->top; ?>
</div>


<div id="plugpress-content">
	<div id="plugpress-content-left">
		<div id="plugpress-left-boxes" class="metabox-holder">
			<div class='postbox-container plugpress-postbox-container'>
				<?php do_meta_boxes('plugpress-split-left', 'advanced', null); ?>
			</div>
		</div>
	</div>
	<div id="plugpress-content-right">
		<div class="plugpress-buynow-box">
			<a href="#plugpress-transaction" name="plugpress-modal" class="plugpress-buynow" style="background: url(<?php echo $plugpress->admin->admin_url; ?>images/bgbuttongreen.png)">
			<?php if ($plugpress->theme->price == 0) : ?>
				<?php esc_html_e(__('Get for Free!', 'plugpress')); ?>
			<?php else : ?>
				<?php esc_html_e(__('Buy Now', 'plugpress')); ?>
			<?php endif; ?>
			</a>
			<br />
			<?php if ($plugpress->theme->price != null && $plugpress->theme->price != '') : ?>
			<a href="<?php echo $plugpress->theme->demourl; ?>" target="_blank" class="plugpress-trynow" style="background: #7599B9;"><?php esc_html_e(__('Try it Now', 'plugpress')); ?></a>
			<?php endif; ?>
		</div>
		<div id="plugpress-right-boxes" class="metabox-holder">
			<div class='postbox-container plugpress-postbox-container'>
				<?php do_meta_boxes('plugpress-split-right', 'advanced', null); ?>
			</div>
		</div>
	</div>
</div>

<div id="plugpress-announcement-bottom">
	<?php echo $plugpress->announcement->bottom; ?>
</div>

<div id="plugpress-footer">
	<?php echo $plugpress->footer; ?>
</div>

</div>


<form id="plugpress-form-buy" action="" method="POST" target="_blank">
	<input type="hidden" name="username" value="<?php esc_attr_e($plugpress->username) ?>" />
	<input type="hidden" name="slug" value="<?php esc_attr_e($plugpress->theme->slug) ?>" />
	<input type="hidden" name="url" value="<?php esc_attr_e($plugpress->admin->website_url) ?>" />
	<input type="hidden" name="key" value="<?php esc_attr_e($plugpress->admin->website_key) ?>" />
</form>


<script type="text/javascript">
jQuery(document).ready(function($) {
	//Hack
	$('.handlediv').remove();

	$("#plugpress-images").carouFredSel({
		circular: false,
		infinite: false,
		auto : false,
		pagination: '#plugpress-pagination',
		prev : {
			button		: "#plugpress-thumbnail-prev",
			key			: "left",
			items		: 3,
			duration	: 500
		},
		next : {
			button		: "#plugpress-thumbnail-next",
			key			: "right",
			items		: 3,
			duration	: 500
		}
	});

	$("#plugpress-images a").prettyPhoto({theme: 'facebook', show_title: false, social_tools: '', gallery_markup: '', slideshow: false, deeplinking:false});

});

function plugpress_buyit() {
	var f = jQuery("#plugpress-form-buy");
	f.get(0).setAttribute('action', '<?php echo PLUGPRESS::WEBSITE_URL_SSL; ?>buy/theme');
	f.submit();
	jQuery('#plugpress-mask').hide();
	jQuery('.plugpress-modal').hide();
}
</script>
