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

if (!defined('ABSPATH')) exit;

function image_meta_upload( $post_ID ) {
	if ( wp_attachment_is_image( $post_ID ) ) {
		$image_title = get_post( $post_ID ) -> post_title;
		$image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ', $image_title );
		$image_title = ucwords( strtolower( $image_title ) );

		$image_meta = array(
			'ID'             => $post_ID,
			'post_title'     => $image_title,
			//'post_excerpt'   => $image_title,
			//'post_content'   => $image_title
		);

		update_post_meta( $post_ID, '_wp_attachment_image_alt', $image_title );

		wp_update_post( $image_meta );
	}
}
add_action( 'add_attachment', 'image_meta_upload' );

function test_stuff() {
	// Loop Through Current Media Library
	$currentMediaLibrary = new WP_Query( array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'post_status'    => 'inherit',
		'posts_per_page' => -1
	));

	foreach( $currentMediaLibrary -> posts as $image ) {
		// Check if alt tag exists - if not copy it over from the title and sanitize both title and alt.
		$images[] = $image -> post_title;
		if( $matches  = preg_grep ( '%\s*[-_\s]+\s*%', $images ) ) {

		}
	}
	//var_dump($matches);
}
add_action( 'admin_init', 'test_stuff' );
