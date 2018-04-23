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

// Do not open this file directly.
if ( ! defined('ABSPATH') ) die();

// Fix metadata on image upload.
function image_fix_metadata( $post_ID ) {
	if ( wp_attachment_is_image( $post_ID ) ) {
		// Get post title.
		$image_title = get_post( $post_ID )->post_title;
		$image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ', $image_title );
		$image_title = ucwords( strtolower( $image_title ) );

		$image_meta = array(
			'ID'             => $post_ID,
			'post_title'     => $image_title,
			//'post_excerpt'   => $image_title,
			//'post_content'   => $image_title
		);

		// Update main fields.
		wp_update_post( $image_meta );

		// Update alt text.
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $image_title );
	}
}
add_action( 'add_attachment', 'image_fix_metadata' );

// Loop Through Current Media Library.
function seoio_list_exixting_images() {
	$existing_images = new WP_Query( array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'post_status'    => 'inherit',
		'posts_per_page' => -1
	));

	foreach( $existing_images->posts as $image ) {
		// Check if alt tag exists - if not copy it over from the title and sanitize both title and alt.
		$images[] = $image->post_title;

		$alt_text = get_post_meta( $image->ID, '_wp_attachment_image_alt' );
		//$caption = wp_get_attachment_caption( $image->ID );

		$style = 'style="color:#444"';

		if ( empty($alt_text[0]) ) {
			$style = 'style="color:#F00"';
		}

		$alt = 'MISSING!';

		if ( ! empty($alt_text[0]) ) {
			$alt = $alt_text[0];
		}

		echo '<p>';
		echo "<h3>Title: $image->post_title</h3>";
		echo "<strong>Original Name:</strong> $image->post_name<br />";
		echo "<strong>Content:</strong> $image->post_content<br />";
		echo "URL: <a href='$image->guid'>$image->guid</a><br />";
		echo "<span $style><strong>Alt-Text:</strong> $alt</span><br />";
		echo "<strong>Caption:</strong> $caption";
		echo '</p>';

		//var_dump($image);
	}
}
//add_action( 'admin_init', 'list_exixting_images' );

/**
 * Setup Admin Panel Pages
 */
function seoio_register_pages() {

	add_submenu_page(
		'tools.php',
		'WP Img SEO Optimizer',
		'WPSEOIO: List Images',
		'manage_options',
		'seoio-list-exixting-images',
		'seoio_list_exixting_images'
	);

}
add_action( 'admin_menu', 'seoio_register_pages' );

/**
 * 
 */
// Add the column
function filename_column( $cols ) {
	$cols["alttext"] = "Alternative Text";
	return $cols;
}

// Display filenames
function filename_value( $column_name, $id ) {
	$alt_text = get_post_meta( $id, '_wp_attachment_image_alt' );

		if ( ! empty($alt_text[0]) ) {
			$alt = $alt_text[0];
		}
	echo $alt;
}

// Register the column as sortable & sort by name
function filename_column_sortable( $cols ) {
	$cols["alttext"] = "alttext";
	return $cols;
}

function my_slice_orderby( $query ) {
	if( ! is_admin() )
		return;

	$orderby = $query->get( 'orderby');

	if( 'alttext' == $orderby ) {
		$query->set('meta_key','_wp_attachment_image_alt');
		$query->set('orderby','meta_value');
	}
}
add_action( 'pre_get_posts', 'my_slice_orderby' );

// Hook actions to admin_init
function hook_new_media_columns() {
	add_filter( 'manage_media_columns', 'filename_column' );
	add_action( 'manage_media_custom_column', 'filename_value', 10, 2 );
	add_filter( 'manage_upload_sortable_columns', 'filename_column_sortable' );
}
add_action( 'admin_init', 'hook_new_media_columns' );
