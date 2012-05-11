//
// Originally taken from http://tutorialzine.com/2010/07/colortips-jquery-tooltip-plugin/
//
(function ($) {
	$.fn.colorTip = function () {
		var settings = {color: 'plugpress-white', timeout: 0}

		//	Looping through all the elements and returning them afterwards.
		//	This will add chainability to the plugin.
		return this.each(function () {
			var elem = $(this);

			if (!elem.attr('plugpress')) return true;

			eval('var data=' + elem.attr('plugpress'));

			var stars= getStarsHtml(data.rating);
			stars = stars == '' ? 'N/A' : stars + ' (' + data.numrating + ')';

			if (data.name.length >= 48) {
				data.name = data.name.substr(0, 48) + '...';
			}
			var toptip_left = '<div class="plugpress-toptip-left"><img src="' + data.thumbnail + '" alt="" class="plugpress-toptip-left-image" /></div>';
			var toptip_right = '<div class="plugpress-toptip-right"><div class="plugpress-toptip-right-title">' + data.name + '</div>$' + data.price + '<div style="padding-top:5px">' + stars + '</div></div>';
			var toptip = '<div class="plugpress-toptip">'+toptip_left + toptip_right + '<div style="clear:both"></div></div>';
			var bottomtip = '<div class="plugpress-bottomtip"><div class="plugpress-bottomtip-description">'+ data.short_description +'</div></div>';
			var content = '<div class="plugpress-tip">' + toptip + bottomtip + '</div>';

			var tip = new Tip(content);
			elem.prepend(tip.generate()).addClass('plugpress-colorTipContainer');

			// set the default color
			elem.addClass(settings.color);

			// On mouseenter, show the tip, on mouseleave set the tip to be hidden
			elem.hover(function () {

				elem.addClass('plugpress-relative');

				tip.show();
			}, function () {
				tip.hide('1');
			});

			tip.tip.mouseenter(function () {
				tip.hide('2');
			});

		});
	}



	// Tip Class Definition
	function Tip(txt) {
		this.content = txt;
		this.shown = false;
	}
	Tip.prototype = {
		generate: function () {
			if (this.tip == null) {
				this.tip = $('<span class="plugpress-colorTip">' + this.content + '<span class="plugpress-pointyTipShadow"></span><span class="plugpress-pointyTip"></span></span>');
			}
			return this.tip;
		},
		show: function () {
			if (this.shown) {
				return;
			}

			//parent
			var parentpos = this.tip.parent().offset();

			// Center the tip and start a fadeIn animation
			this.tip.css('top',  parentpos.top - $(window).scrollTop() - (this.tip.outerHeight() + 10) + 'px');
			this.tip.css('left', (parentpos.left) + 'px');

			this.tip.css('z-index', 999999);
			this.tip.css('overflow', 'hidden');
			this.tip.fadeIn('fast');
			this.shown = true;
			//alert('tip = top: ' + parentpos.top + '. left: ' + parentpos.left + "\n" + 'top: ' + this.tip.css('top') + '. left: ' + this.tip.css('left'));
		},
		hide: function () {
			this.tip.hide();
			this.shown = false;
		}
	}
})(jQuery);



jQuery(document).ready(function ($) {
	$('[plugpress]').colorTip();


	// Modal window
	$('a[name=plugpress-modal]').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('href');

		//Get the screen height and width
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();

		//Set height and width to mask to fill up the whole screen
		var msk = $('#plugpress-mask');
		msk.css({
			'width':maskWidth,
			'height':maskHeight
		});

		//transition effect
		msk.fadeTo("fast",0.75);

		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();

		//console.log('w: ' + winW + ', h: ' + winH);

		//Set the popup window to center
		$(id).css('top',  winH/4 - $(id).height()/2);
		$(id).css('left', winW/2 - $(id).width()/2);

		//transition effect
		$(id).fadeIn(700);

		var callback = $(this).attr('callback');
		var sec = $(this).attr('seconds');
		var secondid = $(this).attr('secondid');

		if (callback) {
			if (!sec) sec = '10';
			if (!secondid) secondid = '';
			CountdownAndCall(callback, sec, secondid);
		}
	});

	//if mask is clicked
	$('#plugpress-mask').click(function () {
		$(this).hide();
		$('.plugpress-modal').hide();
	});
});


function getStarsHtml(rating) {
	var content='';
	if (rating > 0) {
		if (rating >= 15) {
			content += '<img src="'+plugpress_admin_url+'images/star.png" class="plugpress-star" alt="*" />';
		}
		else if (rating > 7) {
			content += '<img src="'+plugpress_admin_url+'images/halfstar.png" class="plugpress-star" alt="1/2" />';
		}

		if (rating >= 35) {
			content += '<img src="'+plugpress_admin_url+'images/star.png" class="plugpress-star" alt="*" />';
		}
		else if (rating > 27) {
			content += '<img src="'+plugpress_admin_url+'images/halfstar.png" class="plugpress-star" alt="1/2" />';
		}

		if (rating >= 55) {
			content += '<img src="'+plugpress_admin_url+'images/star.png" class="plugpress-star" alt="*" />';
		}
		else if (rating > 47) {
			content += '<img src="'+plugpress_admin_url+'images/halfstar.png" class="plugpress-star" alt="1/2" />';
		}

		if (rating >= 75) {
			content += '<img src="'+plugpress_admin_url+'images/star.png" class="plugpress-star" alt="*" />';
		}
		else if (rating > 67) {
			content += '<img src="'+plugpress_admin_url+'images/halfstar.png" class="plugpress-star" alt="1/2" />';
		}

		if (rating >= 95) {
			content += '<img src="'+plugpress_admin_url+'images/star.png" class="plugpress-star" alt="*" />';
		}
		else if (rating > 87) {
			content += '<img src="'+plugpress_admin_url+'images/halfstar.png" class="plugpress-star" alt="1/2" />';
		}
	}

	return content;
}

// Countdown and call a function
function CountdownAndCall(func_name, seconds, displayid){
	var sec = jQuery('#' + displayid);
	if (sec) {
		sec.text(seconds)
	}
	if (seconds <= 0) {
		if (func_name) {
			eval(func_name + '();');
		}
	}
	else {
		setTimeout('CountdownAndCall("' + func_name + '", ' + --seconds + ',"'+ displayid +'")', 1000);
	}
}

// Search
function plugpress_search() {
	var search = document.getElementById('plugin-search').value;
	if (search.length >= 1) {
		search = escape(search);
		var newurl = "admin.php?page=plugpress-browse&ppsubpage=search&ppq=" + search;
		top.location.href = newurl;
		return false;
	}
}


