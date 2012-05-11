<?php

/**
 * PlugPress Admin Metaboxes
 */



function plugpress_split_metaboxes($data) {
	#var_dump($data);
	if (is_array($data->content->left)) {
		$i = 0;
		foreach ($data->content->left as $box) {
			if ($box->type == 'plugins-box') {
				add_meta_box(
						'plugpress-plugin-box-' . $i,
						esc_html($box->name),
						'plugpress_plugins_box_metabox',
						'plugpress-split-left',
						'advanced',
						'default',
						array('box' => $box)
					);
			}
			elseif ($box->type == 'plugins-list') {
				add_meta_box(
						'plugpress-plugin-box-' . $i,
						esc_html($box->name),
						'plugpress_plugins_list_metabox',
						'plugpress-split-left',
						'advanced',
						'default',
						array('box' => $box)
					);
			}
			elseif ($box->type == 'themes-box') {
				add_meta_box(
						'plugpress-theme-box-' . $i,
						$box->name,
						'plugpress_themes_box_metabox',
						'plugpress-split-left',
						'advanced',
						'default',
						array('box' => $box)
					);
			}
			elseif ($box->type == 'html') {
				add_meta_box(
						'plugpress-html-box-' . $i,
						esc_html($box->name),
						'plugpress_html_metabox',
						'plugpress-split-left',
						'advanced',
						'default',
						array('box' => $box)
					);
			}
			$i++;
		}
	}

	if (is_array($data->content->right)) {
		$i = 0;
		foreach ($data->content->right as $box) {
			if ($box->type == 'plugin-category-list' || $box->type == 'theme-category-list') {
				add_meta_box(
						'plugpress-category-list-' . $i,
						esc_html($box->name),
						($box->type == 'plugin-category-list' ? 'plugpress_plugin_category_list_metabox' : 'plugpress_theme_category_list_metabox'),
						'plugpress-split-right',
						'advanced',
						'default',
						array('data' => $box->data)
					);
			}
			$i++;
		}
	}
}

/**
 * HTML metabox
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_html_metabox($context, $args) {
	$args = $args['args'];
	if ( isset( $args['box'] ) ) {
		$box = $args['box'];
		$data = $box->data;
	}
	else {
		$data = $args['data'];
	}

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

			$infos = '{name: "'. esc_attr($row->name) .'", thumbnail: "'. $row->icon . '", price: ' . $row->price . ', short_description: "' . $row->shortdescription . '", rating: ' . $row->rating . ', numrating: ' . $row->numrating . '}';
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
			$footer = PlugPress_Misc::generate_pagination(
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
		$content = '<p>' . esc_html(__('Sorry, no plugins matching your criterias were found.', 'plugpress')) . '</p>';
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
			$rating = PlugPress_Misc::get_stars($row->rating);
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
		$url = 'admin.php?page=plugpress-browse&ppsubpage=plugins'. $slug .'&pppage=';
		if ($box->options['category'] != '') {
			$slug = '&ppslug=' . $box->options['category'];
		}
		elseif ($box->options['search'] != '') {
			$url = 'admin.php?page=plugpress-browse&ppsubpage=search&ppq='. $box->options['search'] .'&pppage=';
		}
		$pagination = PlugPress_Misc::generate_pagination(
				$box->options['page'],
				$box->options['pagecount'],
				$url);

		$content .= '<div class="plugpress-box-prefooter">' . $pagination . '</div>';
	}
	else {
		$content = '<p>' . esc_html(__('Sorry, no plugins matching your criterias were found.', 'plugpress')) . '</p>';
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
			$footer = PlugPress_Misc::generate_pagination(
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

/**
 * Plugin category list
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_plugin_category_list_metabox($context, $args) {
	$args = $args['args'];
	$data = $args['data'];

	$type = 'unordered';
	plugpress_category_list_metabox('plugin', $data, $type);
}


/**
 * Theme category list
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_theme_category_list_metabox($context, $args) {
	$args = $args['args'];
	$data = $args['data'];

	$type = 'unordered';
	plugpress_category_list_metabox('theme', $data, $type);
}

/**
 * Generic category list metabox
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_category_list_metabox( $kind , $data, $type = 'unordered' ) {
	$content = '';
	$known_type = false;

	if ($kind == 'plugin' || $kind == 'theme') {
		$known_type = true;
	}

	if ($known_type) {
		$content = '<div class="plugpress-category-'. ($type == 'ordered' ? 'o' : 'u') . 'list">' . ($type == 'ordered' ? '<ol' : '<ul') . ' class="plugpress-ul-normal">';
		foreach($data as $row) {
			$content .= '<li><a href="admin.php?page=plugpress-browse&ppsubpage='. $kind .'s&ppslug='. $row->slug .'">'. esc_html($row->name) .'</a></li>';
		}
		$content .= ($type == 'ordered' ? '</ol>' : '</ul>') . '</div>';
	}

	echo $content;
}


/**
 * Generate the metaboxes for a plugin
 *
 * @param array $data
 */
function plugpress_plugin_metaboxes( $data ) {

	#var_dump($data->content->plugin);

	// Description
	add_meta_box(
			'plugpress-plugindetail-description',
			esc_html( __( 'Description', 'plugpress' ) ),
			'plugpress_html_metabox',
			'plugpress-split-left',
			'advanced',
			'default',
			array( 'data' => $data->content->plugin->description )
		);

	// Screenshots
	if ( is_array( $data->content->plugin->screenshots ) && count( $data->content->plugin->screenshots ) > 0 ) {
		add_meta_box(
				'plugpress-plugindetail-screenshots',
				esc_html( __( 'Screenshots', 'plugpress' ) ),
				'plugpress_plugin_screenshots_metabox',
				'plugpress-split-left',
				'advanced',
				'default',
				array(
					'data' => $data->content->plugin->screenshots,
					'httpstatic' => $data->content->httpstatic,
					'slug' => $data->content->plugin->slug
				)
			);
	}

	// FAQ
	if ( !empty( $data->content->plugin->faq ) ) {
	add_meta_box(
			'plugpress-plugindetail-faq',
			esc_html( __( 'FAQ', 'plugpress' ) ),
			'plugpress_html_metabox',
			'plugpress-split-left',
			'advanced',
			'default',
			array( 'data' => $data->content->plugin->faq )
		);
	}

	// Installation
	if ( !empty( $data->content->plugin->installation ) ) {
		add_meta_box(
				'plugpress-plugindetail-installation',
				esc_html( __( 'Installation', 'plugpress' ) ),
				'plugpress_html_metabox',
				'plugpress-split-left',
				'advanced',
				'default',
				array( 'data' => $data->content->plugin->installation )
			);
	}

	// Change log
	if ( !empty( $data->content->plugin->changelog ) ) {
		add_meta_box(
				'plugpress-plugindetail-changelog',
				esc_html( __( 'Change log', 'plugpress' ) ),
				'plugpress_html_metabox',
				'plugpress-split-left',
				'advanced',
				'default',
				array( 'data' => $data->content->plugin->changelog )
			);
	}

	// upgrade notice
	if ( !empty( $data->content->plugin->upgradenotice ) ) {
		add_meta_box(
				'plugpress-plugindetail-upgradenotice',
				esc_html( __( 'Upgrade notice', 'plugpress' ) ),
				'plugpress_html_metabox',
				'plugpress-split-left',
				'advanced',
				'default',
				array( 'data' => $data->content->plugin->upgradenotice )
			);
	}

	// Additional left content
	$i = 0;
	if ( isset($data->content->left) && is_array($data->content->left) ) {
		foreach( $data->content->left as $html ) {
			if ( $html->type == 'html' ) {
				add_meta_box(
						'plugpress-plugindetail-html-' . $i,
						$html->name,
						'plugpress_html_metabox',
						'plugpress-split-left',
						'advanced',
						'default',
						array( 'data' => $html->data )
					);
			}

			$i++;
		}
	}


	////////////////
	// RIGHT SIDE
	////////////////


	// Information
	add_meta_box(
			'plugpress-plugindetail-information',
			esc_html( __( 'Information', 'plugpress' ) ),
			'plugpress_plugin_information_metabox',
			'plugpress-split-right',
			'advanced',
			'default',
			array( 'data' => $data->content->plugin )
		);

	// Additional right content
	if ( isset($data->content->right) && is_array($data->content->right) ) {
		$i = 0;
		foreach( $data->content->right as $html ) {
			if ( $html->type == 'html' ) {
				add_meta_box(
						'plugpress-plugindetail-html-' . $i,
						$html->name,
						'plugpress_html_metabox',
						'plugpress-split-right',
						'advanced',
						'default',
						array( 'data' => $html->data )
					);
			}

			$i++;
		}
	}
}

/**
 * Displays Screenshots
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_plugin_screenshots_metabox($context, $args) {
	$args = $args['args'];
	$data = $args['data'];
	$httpstatic = $args['httpstatic'];
	$slug = $args['slug'];

	$content = '<div class="plugpress-thumbnail-carousel">';
	$content .= '<div id="plugpress-images">';
	foreach($data as $scr) {
		$content .= '<a rel="prettyPhoto[caroufredsel]" href="' . $httpstatic . 'plugins/' . $slug . '/screenshot-' . $scr['num'] . '.' . $scr['bext'] . '" desc="' . esc_attr($scr['description']) . '">';
		$content .= '<img src="' . $httpstatic . 'plugins/' . $slug . '/screenshot-' . $scr['num'] . '_small.' . $scr['sext'] . '" alt="no image" class="plugpress-carousel-image" />';
		$content .= '</a>';
	}
	$content .= '</div>';
	$content .= '<div class="plugpress-clearfix"></div>';
	$content .= '<div class="plugpress-thumbnail-pagination" id="plugpress-pagination"></div>';
	$content .= '</div>';

	echo  $content;
}


/**
 * Displays plugin information metabox content
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_plugin_information_metabox($context, $args) {
	$args = $args['args'];
	$data = $args['data'];

	$content = '<div class="plugpress-plugin-information">';
	$content .= '<b>' . esc_html(__('Version', 'plugpress')) . ':</b><br />';
	$content .=  esc_html($data->version) . '<br /><br />';
	$content .= '<b>' . esc_html(__('Author', 'plugpress')) . ':</b><br />';
	$content .= '<a href="' . $data->authorurl . '" target="_blank">' . esc_html($data->authorname) . '</a><br /><br />';
	$content .= '<b>' . esc_html(__('Requirements', 'plugpress')) . ':</b><br />';
	$content .= esc_html(__('WordPress', 'plugpress')) . ' ' . esc_html($data->wordpressrequired) . '+<br /><br />';
	$content .= '<b>' . esc_html(__('Tested up to', 'plugpress')) . ':</b><br />';
	$content .= esc_html(__('WordPress', 'plugpress')) . ' ' . esc_html($data->testedupto) . '<br /><br />';
	if ($data->purchases > 0) {
		$content .= '<b>' . esc_html(__('Purchases', 'plugpress')) . ':</b><br />';
		$content .= esc_html(number_format($data->purchases)) . '<br /><br />';
	}

	if ($data->rating > 0) {
		$content .= esc_html(__('Rating', 'plugpress')) . ': ';
		$content .= PlugPress_Misc::get_stars( $data->rating );
		$content .= '<br/><small>(' . esc_html( __( 'Number of votes', 'plugpress' ) ) . ': ' . number_format( $data->numrating ) . ')</small><br /><br />';
	}

	$content .= '<b>' . esc_html( __( 'Last Update', 'plugpress' ) ) . ':</b><br />';
	$content .=  esc_html( $data->lastmodified ) . '<br /><br />';

	$content .= '<b>' . esc_html(__('Price', 'plugpress')) . ':</b><br />';
	if ($data->price == 0) {
		$content .= esc_html(__('Free', 'plugpress'));
	}
	else {
		$content .= '$' . esc_html($data->price);
	}
	$content .= '<br /><br />';

	if ($data->wprepo == 0) {
		if ($data->support1 != 0 || $data->support6 != 0 || $data->support12 != 0) {
			$content .= '<b>' . esc_html(__('Support and updates', 'plugpress')) . ':</b><br />';

			if ($data->support1 != 0) {
				$content .= '$' . esc_html($data->support1) . ' ' . esc_html(__('per month', 'plugpress')) . '<br />';
			}

			if ($data->support6 != 0) {
				$content .= '$' . esc_html($data->support6) . ' ' . esc_html(__('per 6 months', 'plugpress')) . '<br />';
			}

			if ($data->support12 != 0) {
				$content .= '$' . esc_html($data->support12) . ' ' . esc_html(__('per 12 months', 'plugpress')) . '<br />';
			}
		}
	}
	$content .= '</div>';

	echo  $content;
}

/**
 * Generate the content for the page
 *
 * @param array $data
 */
function plugpress_theme_metaboxes( $data ) {

	#var_dump($data);

	// Description
	add_meta_box(
			'plugpress-themedetail-description',
			esc_html(__('Description', 'plugpress')),
			'plugpress_html_metabox',
			'plugpress-split-left',
			'advanced',
			'default',
			array('data' => $data->content->theme->description)
		);

	// Screenshots
	if (is_array($data->content->theme->screenshots) && count($data->content->theme->screenshots) > 0) {
		add_meta_box(
			'plugpress-themedetail-screenshots',
			esc_html(__('Screenshots', 'plugpress')),
			'plugpress_theme_screenshots',
			'plugpress-split-left',
			'advanced',
			'default',
			array(
				'data' => $data->content->theme->screenshots,
				'httpstatic' => $data->content->httpstatic,
				'slug' => $data->content->theme->slug
			)
		);
	}

	// FAQ
	if (!empty($data->content->theme->faq)) {
		add_meta_box(
			'plugpress-plugindetail-faq',
			esc_html(__('FAQ', 'plugpress')),
			'plugpress_html_metabox',
			'plugpress-split-left',
			'advanced',
			'default',
			array('data' => $data->content->theme->faq)
		);
	}

	// Installation
	if (!empty($data->content->theme->installation)) {
		add_meta_box(
			'plugpress-plugindetail-installation',
			esc_html(__('Installation', 'plugpress')),
			'plugpress_html_metabox',
			'plugpress-split-left',
			'advanced',
			'default',
			array( 'data' => $data->content->theme->installation )
		);
	}

	// change log
	if ( !empty( $data->content->theme->changelog ) ) {
		add_meta_box(
			'plugpress-plugindetail-changelog',
			esc_html( __( 'Change log', 'plugpress' ) ),
			'plugpress_html_metabox',
			'plugpress-split-left',
			'advanced',
			'default',
			array( 'data' => $data->content->theme->changelog )
		);
	}

	// upgrade notice
	if ( !empty( $data->content->theme->upgradenotice ) ) {
		add_meta_box(
			'plugpress-plugindetail-upgradenotice',
			esc_html(__('Upgrade notice', 'plugpress')),
			'plugpress_html_metabox',
			'plugpress-split-left',
			'advanced',
			'default',
			array( 'data' => $data->content->theme->upgradenotice )
		);
	}

	// Additional left content
	if ( isset( $data->content->left ) && is_array( $data->content->left ) ) {
		$i = 0;
		foreach($data->content->left as $html) {
			if ($html->type == 'html') {
				add_meta_box(
						'plugpress-plugindetail-html-' . $i,
						$html->name,
						'plugpress_html_metabox',
						'plugpress-split-left',
						'advanced',
						'default',
						array('data' => $html->data)
					);
			}

			$i++;
		}
	}


	////////////////
	// RIGHT SIDE
	////////////////


	// Information
	add_meta_box(
			'plugpress-plugindetail-information',
			esc_html(__('Information', 'plugpress')),
			'plugpress_theme_information_metabox',
			'plugpress-split-right',
			'advanced',
			'default',
			array('data' => $data->content->theme)
		);

	// Additional right content
	if ( isset( $data->content->right ) && is_array( $data->content->right ) ) {
		$i = 0;
		foreach($data->content->right as $html) {
			if ($html->type == 'html') {
				add_meta_box(
						'plugpress-plugindetail-html-' . $i,
						$html->name,
						'plugpress_html_metabox',
						'plugpress-split-right',
						'advanced',
						'default',
						array('data' => $html->data)
					);
			}

			$i++;
		}
	}
}



/**
 * Displays Screenshots
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_theme_screenshots( $context, $args ) {
	$args = $args['args'];
	$data = $args['data'];
	$httpstatic = $args['httpstatic'];
	$slug = $args['slug'];

	$content = '<div class="plugpress-thumbnail-carousel">';
	$content .= '<div id="plugpress-images">';
	foreach( $data as $scr ) {
		$content .= '<a rel="prettyPhoto[caroufredsel]" href="' . $httpstatic . 'themes/' . $slug . '/screenshot-' . $scr['num'] . '.' . $scr['bext'] . '" desc="' . esc_attr($scr['description']) . '">';
		$content .= '<img src="' . $httpstatic . 'themes/' . $slug . '/screenshot-' . $scr['num'] . '_small.' . $scr['sext'] . '" alt="no image" class="plugpress-carousel-image" />';
		$content .= '</a>';
	}
	$content .= '</div>';
	$content .= '<div class="plugpress-clearfix"></div>';
	$content .= '<div class="plugpress-thumbnail-pagination" id="plugpress-pagination"></div>';
	$content .= '</div>';

	echo  $content;
}

/*
	$content = '<div class="plugpress-thumbnail-carousel">';
	$content .= '<div id="plugpress-images">';
	foreach($data as $scr) {
		$content .= '<a rel="prettyPhoto[caroufredsel]" href="' . $httpstatic . 'plugins/' . $slug . '/screenshot-' . $scr['num'] . '.' . $scr['bext'] . '" desc="' . esc_attr($scr['description']) . '">';
		$content .= '<img src="' . $httpstatic . 'plugins/' . $slug . '/screenshot-' . $scr['num'] . '_small.' . $scr['sext'] . '" alt="no image" class="plugpress-carousel-image" />';
		$content .= '</a>';
	}
	$content .= '</div>';
	$content .= '<div class="plugpress-clearfix"></div>';
	$content .= '<div class="plugpress-thumbnail-pagination" id="plugpress-pagination"></div>';
	$content .= '</div>';

	echo  $content;
*/



/**
 * Displays Screenshots
 *
 * @param string $context box context
 * @param array $args
 */
function plugpress_theme_information_metabox( $context, $args ) {
	$args = $args['args'];
	$data = $args['data'];

	$content = '<div class="plugpress-plugin-information">';
	$content .= '<b>' . esc_html( __( 'Version', 'plugpress' ) ) . ':</b><br />';
	$content .=  esc_html( $data->version ) . '<br /><br />';
	$content .= '<b>' . esc_html( __( 'Author', 'plugpress' ) ) . ':</b><br />';
	$content .= '<a href="' . $data->authorurl . '" target="_blank">' . esc_html( $data->authorname ) . '</a><br /><br />';

	if (!empty($data->wordpressrequired)) {
		$content .= '<b>' . esc_html( __( 'Requirements', 'plugpress' ) ) . ':</b><br />';
		$content .=  esc_html( __( 'WordPress', 'plugpress' ) ) . ' ' . esc_html( $data->wordpressrequired ) . '+<br /><br />';
	}

	#if (isset($data->demourl)) {
	#	$content .= '<b>' . esc_html( __( 'Demo', 'plugpress' ) ) . ':</b><br />';
	#	$content .= '<a href="'. $data->demourl .'" target="_blank">' . esc_html( __( 'View theme in action', 'plugpress' ) ) . '</a><br /><br />';
	#}


	if ( $data->purchases > 0 ) {
		$content .= '<b>' . esc_html( __( 'Downloads', 'plugpress' ) ) . ':</b><br />';
		$content .= esc_html( number_format( $data->purchases ) ) . '<br /><br />';
	}

	if ($data->rating > 0) {
		$content .= '<b>' . esc_html(__('Rating', 'plugpress')) . ':</b> ';
		$content .= PlugPress_Misc::get_stars($data->rating);
		$content .= '<br/><small>(' . esc_html(__('Number of votes', 'plugpress')) . ': ' . number_format($data->numrating) . ')</small><br /><br />';
	}

	$content .= '<b>' . esc_html( __( 'Last Update', 'plugpress' ) ) . ':</b><br />';
	$content .=  esc_html( $data->lastmodified ) . '<br /><br />';

	$content .= '<b>' . esc_html(__('Price', 'plugpress')) . ':</b><br />';
	if ($data->price == 0) {
		$content .= esc_html(__('Free', 'plugpress'));
	}
	else {
		$content .= '$' . esc_html($data->price);
	}

	$content .= '</div>';

	echo  $content;
}