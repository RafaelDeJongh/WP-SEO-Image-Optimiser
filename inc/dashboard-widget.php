<?php
/*
  WP SEO Image Optimiser - Improve the SEO of your images.
  Copyright (C) 2018 Rafaël De Jongh & Yogensia

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see http://www.gnu.org/licenses/.
*/



/**
 * Do not open this file directly.
 */
if ( ! defined('ABSPATH') ) exit;



/**
 * Add dashboard widget with summary about images that need attention.
 */
function seoio_setup_dashboard_widget() {
	global $wp_meta_boxes;

	wp_add_dashboard_widget( 'seoio_widget', 'WP SEO Image Optimiser', 'seoio_dashboard_widget' );
}
add_action( 'wp_dashboard_setup', 'seoio_setup_dashboard_widget' );

function seoio_dashboard_widget() {
	$missing_alt_imgs = seoio_query_missing_alt();

	$count = $missing_alt_imgs->post_count;

	$review_img_link = get_admin_url( false, '/tools.php?page=seoio-list-exixting-images' );

	if ( $count > 1 ) {
		echo "<p>There are $count images with missing alt-text attributes, <a href='" .
		$review_img_link . "'>click here to review them</a>.</p>";
	} elseif ( $count == 1 ) {
		echo "<p>There is one image with missing alt-text attributes, <a href='" .
		$review_img_link . "'>click here to review it</a>.</p>";
	} else {
		echo '<p>All images on your site have alt-text attributes, good job!';
	}
}
