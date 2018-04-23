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
