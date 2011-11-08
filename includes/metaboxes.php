<?php

/**
 * PlugPress Admin Metaboxes
 */


/**
 * HTML metabox
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_html_metabox($context, $args) {
		$args = $args['args'];
		$box = $args['box'];
		$data = $box->data;

		echo $data;
}


/**
 * Generate the plugins box metabox
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_plugins_box_metabox($context, $args) {
	$args = $args['args'];
	$box = $args['box'];
	$data = $box->data;
	$content = '';

	if (count($data) > 0) {
		$content = '<table class="">';
		$row_num = 1;

		if (isset($args['options']['items_per_row']) === false) {
			$args['options']['items_per_row'] = 5;
		}

		foreach($data as $row) {
			if ($row_num === 1) { $content .= '<tr>'; }

			$infos = '{name: "'. esc_attr($row->name) .'", thumbnail: "'. $row->icon . '", price: ' . $row->price . ', short_description: "' . $row->shortdescription . '"}';
			$content .= '<td class="plugpress-td-plugin-normal">';
			$content .= '<a href="admin.php?page=plugpress-browse&ppsubpage=plugindetail&ppslug='. $row->slug .'"><span class="plugpress-span-plugin-normal" plugpress="' . htmlspecialchars($infos) .'"><img src="' . $row->icon . '" class="plugpress-img-plugin-normal" /><br/><b>'. esc_html($row->name) .'</b></span></a></td>';

			$row_num++;

			if ($row_num > $args['options']['items_per_row']) {
				$content .= '</tr>';
				$row_num = 1;
			}
		}
		$content .= '</table>';
		$slug = '';
		if ($box->options['category'] != '') {
			$slug = '&ppslug=' . $box->options['category'];
		}

		$footer = '';
		if (isset($box->options['page']) && isset($box->options['pagecount'])) {
			$footer = $this->generatePagination(
				$box->options['page'],
				$box->options['pagecount'],
				'admin.php?page=plugpress-browse&ppsubpage=plugins'. $slug .'&pppage=');
		}
		elseif (isset($box->options['seemore'])) {
			$url_page = isset($box->options['seemore']['page']) ? $box->options['seemore']['page'] : 'plugpress-browse';
			$url_ppsubpage = isset($box->options['seemore']['ppsubpage']) ?  '&ppsubpage=' . $box->options['seemore']['ppsubpage'] : '';
			$url_ppslug = isset($box->options['seemore']['ppslug']) ?  '&ppslug=' . $box->options['seemore']['ppslug'] : '';
			$url_pppage = isset($box->options['seemore']['pppage']) ?  '&pppage=' . $box->options['seemore']['pppage'] : '';

			$footer = '<a href="admin.php?page=' . $url_page . $url_ppsubpage . $url_ppslug . $url_pppage .'">'. esc_html($box->options['seemore']['text']) .' &raquo;</a>';
		}

		$footerclass = '';
		if (isset($box->options['footerclass'])) {
			$footerclass = $box->options['footerclass'];
		}

		$content .= '<div class="plugpress-box-prefooter ' . $footerclass .'">' . $footer . '</div>';
	}
	else {
		$content = '<p>' . esc_html(__('Sorry, that category does not contain any plugins yet.', 'plugpress')) . '</p>';
	}

	echo $content;
}

/**
 * Generate the plugins list metabox
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_plugins_list_metabox($context, $args) {
	$args = $args['args'];
	$box = $args['box'];
	$data = $box->data;
	$content = '';

	if (count($data) > 0) {
		$content = '<table class="">';

		if (isset($args['options']['items_per_row']) === false) {
			$args['options']['items_per_row'] = 5;
		}

		foreach($data as $row) {
			$rating = PlugPress_Misc::getStars($row->rating);
			$content .= '<tr>';
			$content .= '<td class="plugpress-td-plugin-normal">';
			$content .= '<a href="admin.php?page=plugpress-browse&ppsubpage=plugindetail&ppslug='. $row->slug .'"><img src="' . $row->icon . '" class="plugpress-img-plugin-normal plugpress-img-plugin-listing" /></a></td>';
			$content .= '<td class="plugpress-td-plugin-normal" style="padding:10px;"><a href="admin.php?page=plugpress-browse&ppsubpage=plugindetail&ppslug='. $row->slug .'"><b>'. esc_html($row->name) .'</b></a><br />' . esc_html($row->shortdescription);
			$content .= '<div class="plugpress-plugin-addinfo">' . esc_html(__('Version', 'plugpress')) . ': ' . $row->version . ' &nbsp; &nbsp;' . esc_html(__('Rating', 'plugpress')) . ': ';
			$content .= ($rating == '' ? esc_html(__('N/A', 'plugpress')) : $rating .  ' (' . number_format($row->numrating) . ')');
			$content .= ' &nbsp; &nbsp; ' . esc_html(__('Purchases', 'plugpress')) . ': ' . number_format($row->purchases);
			$content .='</div>'.'</td>';
			$content .= '</tr>';
		}
		$content .= '</table>';
		$slug = '';
		if ($box->options['category'] != '') {
			$slug = '&ppslug=' . $box->options['category'];
		}
		$pagination = $this->generatePagination(
				$box->options['page'],
				$box->options['pagecount'],
				'admin.php?page=plugpress-browse&ppsubpage=plugins'. $slug .'&pppage=');

		$content .= '<div class="plugpress-box-prefooter">' . $pagination . '</div>';
	}
	else {
		$content = '<p>' . esc_html(__('Sorry, that category does not contain any plugins yet.', 'plugpress')) . '</p>';
	}

	echo $content;
}

/**
 * Generate the themes box metabox
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_themes_box_metabox($context, $args) {
	$args = $args['args'];
	$box = $args['box'];
	$data = $box->data;
	$content = '';

	if (count($data) > 0) {
		$content = '<table class="">';
		$row_num = 1;

		if (isset($args['options']['items_per_row']) === false) {
			$args['options']['items_per_row'] = 3;
		}


		foreach($data as $row) {
			if ($row_num === 1) { $content .= '<tr>'; }

			$infos = '{name: "'. esc_attr($row->name) .'", thumbnail: "'. $row->icon . '", price: ' . $row->price . ', short_description: "' . $row->shortdescription . '"}';
			$content .= '<td class="plugpress-td-theme-normal"><div class="plugpress-colorTip-wrapper">';
			$content .= '<a href="admin.php?page=plugpress-browse&ppsubpage=themedetail&ppslug='. $row->slug .'"><span class="plugpress-span-theme-normal"><img src="' . $row->icon . '" class="plugpress-img-theme-normal" /><br/><b>'. esc_html($row->name) .'</b></span></a></div></td>';

			$row_num++;

			if ($row_num > $args['options']['items_per_row']) {
				$content .= '</tr>';
				$row_num = 1;
			}
		}
		$content .= '</table>';
		$slug = '';
		if ($box->options['category'] != '') {
			$slug = '&ppslug=' . $box->options['category'];
		}

		$footer = '';
		if (isset($box->options['page']) && isset($box->options['pagecount'])) {
			$footer = $this->generatePagination(
				$box->options['page'],
				$box->options['pagecount'],
				'admin.php?page=plugpress-browse&ppsubpage=themes'. $slug .'&pppage=');
		}
		elseif (isset($box->options['seemore'])) {
			$url_page = isset($box->options['seemore']['page']) ? $box->options['seemore']['page'] : 'plugpress-browse';
			$url_ppsubpage = isset($box->options['seemore']['ppsubpage']) ?  '&ppsubpage=' . $box->options['seemore']['ppsubpage'] : '';
			$url_ppslug = isset($box->options['seemore']['ppslug']) ?  '&ppslug=' . $box->options['seemore']['ppslug'] : '';
			$url_pppage = isset($box->options['seemore']['pppage']) ?  '&pppage=' . $box->options['seemore']['pppage'] : '';

			$footer = '<a href="admin.php?page=' . $url_page . $url_ppsubpage . $url_ppslug . $url_pppage .'">'. esc_html($box->options['seemore']['text']) .' &raquo;</a>';
		}

		$footerclass = '';
		if (isset($box->options['footerclass'])) {
			$footerclass = $box->options['footerclass'];
		}

		$content .= '<div class="plugpress-box-prefooter ' . $footerclass .'">' . $footer . '</div>';
	}
	else {
		$content = '<p>' . esc_html(__('Sorry, that category does not contain any themes yet.', 'plugpress')) . '</p>';
	}

	echo $content;
}