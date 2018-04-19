<?php
/*
Plugin Name: WP SEO Image Optimiser
Plugin URI: https://TOCOME
Description: A plugin that sanetises your image title and copies it to the various other fields whichs is good for SEO purposes.
Author: Rafaël De Jongh & Yogensia
Author URI: https://www.rafaeldejongh.com
Author URI: https://www.yogensia.com
Version: 1.0
*/

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


// Settings page
function seoio_settings_api_init() {
	// Add the section to media settings page
	add_settings_section(
		'seoio_image_meta',
		'SEO Image Optimizer',
		'seoio_settings_callback',
		'media'
	);

	// Add the fields to the new section
	add_settings_field(
		'seoio_title_to_alt',
		'Alternative text',
		'seoio_title_to_alt_callback',
		'media',
		'seoio_image_meta'
	);

	add_settings_field(
		'seoio_title_to_desc',
		'Description',
		'seoio_title_to_desc_callback',
		'media',
		'seoio_image_meta'
	);

	add_settings_field(
		'seoio_title_to_caption',
		'Caption',
		'seoio_title_to_caption_callback',
		'media',
		'seoio_image_meta'
	);

	add_settings_field(
		'seoio_overwrite_data',
		'Overwrite Fields',
		'seoio_overwrite_data_callback',
		'media',
		'seoio_image_meta'
	);

	register_setting( 'media', 'seoio_title_to_alt' );
	register_setting( 'media', 'seoio_title_to_desc' );
	register_setting( 'media', 'seoio_title_to_caption' );
	register_setting( 'media', 'seoio_overwrite_data' );
}
add_action( 'admin_init', 'seoio_settings_api_init', 1 );


// Callback functions
function seoio_settings_callback() {
	echo '<p>Choose how to auto-populate the alt, description and caption fields of new image uploads.</p>';
}

function seoio_title_to_alt_callback() {
	echo '<input name="seoio_title_to_alt" type="checkbox" id="seoio_title_to_alt" value="1"';

	$seoio_title_to_alt = get_option( 'seoio_title_to_alt' );

	if ( $seoio_title_to_alt == 1 ) {
		echo ' checked';
	}

	echo '/>';
	echo '<label for="seoio_title_to_alt">Copy title to alternative text</label>';
}

function seoio_title_to_desc_callback() {
	echo '<input name="seoio_title_to_desc" type="checkbox" id="seoio_title_to_desc" value="1"';

	$seoio_title_to_desc = get_option( 'seoio_title_to_desc' );

	if ( $seoio_title_to_desc == 1 ) {
		echo ' checked';
	}

	echo '/>';
	echo '<label for="seoio_title_to_desc">Copy title to description</label>';
}

function seoio_title_to_caption_callback() {
	echo '<input name="seoio_title_to_caption" type="checkbox" id="seoio_title_to_caption" value="1"';

	$seoio_title_to_caption = get_option( 'seoio_title_to_caption' );

	if ( $seoio_title_to_caption == 1 ) {
		echo ' checked';
	}

	echo '/>';
	echo '<label for="seoio_title_to_caption">Copy title to caption</label>';
}

function seoio_overwrite_data_callback() {
	echo '<input name="seoio_overwrite_data" type="checkbox" id="seoio_overwrite_data" value="1"';

	$seoio_overwrite_data = get_option( 'seoio_overwrite_data' );

	if ( $seoio_overwrite_data == 1 ) {
		echo ' checked';
	}

	echo '/>';
	echo '<label for="seoio_overwrite_data">Allow to overwrite fields when auto-populating alt text, caption or descriptions of existing images.<br />';
	echo '<span style="color:#a00;"><strong>Important:</strong> Enabling this may cause irreversible loss of data. Make a backup first!</span></label>';
}
