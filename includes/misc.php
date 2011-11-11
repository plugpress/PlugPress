<?php

/**
 * Misc class
 */
class PlugPress_Misc {


	/**
	 * Return stars in HTML depending on the rating
	 *
	 * @param float $rating 0 to 100 rating
	 * @return string HTML
	 */
	public static function get_stars($rating) {
		global $plugpress;

		if ($rating > 0) {
			if ($rating >= 15) {
				$content .= '<img src="'. $plugpress->admin->admin_url .'images/star.png" class="plugpress-star" alt="*" />';
			}
			elseif ($rating > 7) {
				$content .= '<img src="'. $plugpress->admin->admin_url .'images/halfstar.png" class="plugpress-star" alt="1/2" />';
			}

			if ($rating >= 35) {
				$content .= '<img src="'. $plugpress->admin->admin_url .'images/star.png" class="plugpress-star" alt="*" />';
			}
			elseif ($rating > 27) {
				$content .= '<img src="'. $plugpress->admin->admin_url .'images/halfstar.png" class="plugpress-star" alt="1/2" />';
			}

			if ($rating >= 55) {
				$content .= '<img src="'. $plugpress->admin->admin_url .'images/star.png" class="plugpress-star" alt="*" />';
			}
			elseif ($rating > 47) {
				$content .= '<img src="'. $plugpress->admin->admin_url .'images/halfstar.png" class="plugpress-star" alt="1/2" />';
			}

			if ($rating >= 75) {
				$content .= '<img src="'. $plugpress->admin->admin_url .'images/star.png" class="plugpress-star" alt="*" />';
			}
			elseif ($rating > 67) {
				$content .= '<img src="'. $plugpress->admin->admin_url .'images/halfstar.png" class="plugpress-star" alt="1/2" />';
			}

			if ($rating >= 95) {
				$content .= '<img src="'. $plugpress->admin->admin_url .'images/star.png" class="plugpress-star" alt="*" />';
			}
			elseif ($rating > 87) {
				$content .= '<img src="'. $plugpress->admin->admin_url .'images/halfstar.png" class="plugpress-star" alt="1/2" />';
			}
		}

		return $content;
	}

	/**
	 * Pagination generator
	 *
	 * @param int $page Actual page number
	 * @param int $pagecount Total page count
	 * @param string $url URL where the page number will be concatenated
	 * @return string HTML
	 */
	public static function generate_pagination($page, $pagecount, $url) {
		$html = '';

		if ($page > $pagecount) {
			$page = $pagecount;
		}

		if ($pagecount > 1) {
			$pagemax = (($page + 5) > $pagecount ? $pagecount : $page + 5);
			$pagemax = ($pagemax < 10 && $pagecount >= 10 ? 10 : $pagemax);
			$pagemin = (($pagemax - 10) > 0 ? $page - 10 : 1);

			if ($page == 1) {
				$html .= '&lt; ' .__('Previous', 'plugpress') . ' &nbsp;';
			}
			else {
				$html .= '<a href="'. $url . ($page - 1) .'">&lt; ' .__('Previous', 'plugpress') . '</a> &nbsp;';
			}

			for ($i = $pagemin ; $i <= $pagemax ; $i++) {
				if ($i == $page || ($i > $pagemax && $i >= $pagecount)) {
					$html .= $i . ' &nbsp;';
				}
				else {
					$html .= '<a href="'. $url . $i .'">' .$i . '</a> &nbsp;';
				}
			}

			if  ($page >= $pagecount) {
				$html .= __('Next', 'plugpress') . ' &gt;';
			}
			else {
				$html .= '<a href="'. $url . ($page + 1) .'">' .__('Next', 'plugpress') . ' &gt;</a>';
			}

		}

		return $html;
	}
}
