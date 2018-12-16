<?php
/*
	WP SEO Image Optimizer - Improve the SEO of your images.
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

/* Do not open this file directly. */
if(!defined('ABSPATH'))die();

/* Fix metadata on image upload. */
function image_fix_metadata( $post_ID ) {
	if ( wp_attachment_is_image( $post_ID ) ) {
		// Format title.
		$image_title = get_post( $post_ID )->post_title;

		if ( get_option('seoio_clean_image_titles') ) {
			$image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ', $image_title );
		}

		if ( get_option('seoio_capitalize_image_titles') ) {
			$image_title = ucwords( strtolower( $image_title ) );
		}

		$image_meta = array(
			'ID'             => $post_ID,
			'post_title'     => $image_title
		);

		// Auto populate caption?
		if ( get_option('seoio_title_to_caption') ) {
			$image_meta += [ 'post_excerpt' => $image_title ];
		}

		// Auto populate description?
		if ( get_option('seoio_title_to_desc') ) {
			$image_meta += [ 'post_content' => $image_title ];
		}

		// Update main fields.
		wp_update_post( $image_meta );

		// Auto populate alt-text?
		if ( get_option('seoio_title_to_alt') ) {
			update_post_meta( $post_ID, '_wp_attachment_image_alt', $image_title );
		}
	}
}
add_action( 'add_attachment', 'image_fix_metadata' );

/* Query images with missing or empty alt-text. */
function seoio_query_missing_alt() {
	$query = new WP_Query( array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'post_status'    => 'inherit',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => '_wp_attachment_image_alt',
				'value'   => ''
			),
			array(
				'key'     => '_wp_attachment_image_alt',
				'compare' => 'NOT EXISTS'
			),
		),
	));
	return $query;
}

/* List images with missing alt-text. */
function seoio_list_existing_images() {
	$missing_alt_imgs = seoio_query_missing_alt();

	?>
	<div class="wrap">

		<h1><?php esc_attr_e( 'Image SEO Optimizer: Images with missing Alt-Text data', 'WPSEOIO' ); ?></h1>

		<div id="poststuff">
			<div id="post-body" class="metabox-holder">
				<style>
				#post-body-content {
					margin-bottom: 0;
				}
				.inside {
					display: flex;
					align-items: flex-start;
					margin: 0 0 1rem 0;
				}
				.inside p {
					margin: 0;
				}
				.inside img {
					width: 62px;
					height: auto;
					margin: 0 1rem 0 0;
				}
				.inside div {
					flex: 1;
				}
				</style>
				<?php
				foreach( $missing_alt_imgs->posts as $image ) {
					$edit_link = get_edit_post_link( $image->ID );
					?>
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<h2><span><?php echo $image->post_title . '<small> <a href="' . $edit_link . '">Edit</a></small>'; ?></span></h2>
								<div class="inside">
									<?php
										echo '<p>';
										echo '<a href="' . $edit_link . '" title="Edit &quot;' . $image->post_title . '&quot;">' .
										wp_get_attachment_image( $image->ID, 'thumbnail' ) . '</a>';
										echo '</p>';

										echo '<p>';
										echo "<strong>Alt-Text:</strong> MISSING!<br />";
										echo "<strong>Content:</strong> $image->post_content<br />";
										echo "<strong>Caption:</strong> $caption";
										echo '</p>';
									?>
								</div><!-- .inside -->
							</div><!-- .postbox -->
						</div><!-- .meta-box-sortables .ui-sortable -->
					</div><!-- post-body-content -->
				<?php } ?>
			</div><!-- #post-body .metabox-holder -->

			<br class="clear">

		</div>
	</div> <!-- .wrap -->
	<?php
}

/* Update images with missing alt-text. */
function seoio_update_existing_images() {
	$missing_alt_imgs = seoio_query_missing_alt();

	?>
	<div class="wrap">

		<h1><?php esc_attr_e( 'Image SEO Optimizer: Images with missing Alt-Text data', 'WPSEOIO' ); ?></h1>

		<div id="poststuff">
			<div id="post-body" class="metabox-holder">
				<style>
				#post-body-content {
					margin-bottom: 0;
				}
				.inside {
					display: flex;
					align-items: flex-start;
					margin: 0 0 1rem 0;
				}
				.inside p {
					margin: 0;
				}
				.inside img {
					width: 62px;
					height: auto;
					margin: 0 1rem 0 0;
				}
				.inside div {
					flex: 1;
				}
				</style>
				<?php
				foreach( $missing_alt_imgs->posts as $image ) {
					$edit_link = get_edit_post_link( $image->ID );
					?>
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<h2><span><?php echo $image->post_title . '<small> <a href="' . $edit_link . '">Edit</a></small>'; ?></span></h2>
								<div class="inside">
									<?php
										echo '<p>';
										echo '<a href="' . $edit_link . '" title="Edit &quot;' . $image->post_title . '&quot;">' .
										wp_get_attachment_image( $image->ID, 'thumbnail' ) . '</a>';
										echo '</p>';

										echo '<p>';
										echo "<strong>Alt-Text:</strong> TEST!<br />";
										echo "<strong>Content:</strong> $image->post_content<br />";
										echo "<strong>Caption:</strong> $caption";
										echo '</p>';
									?>
								</div><!-- .inside -->
							</div><!-- .postbox -->
						</div><!-- .meta-box-sortables .ui-sortable -->
					</div><!-- post-body-content -->
				<?php } ?>
			</div><!-- #post-body .metabox-holder -->

			<br class="clear">

		</div>
	</div> <!-- .wrap -->
	<?php
}

function seoio_register_pages() {
	add_submenu_page(
		'tools.php',
		'WP Img SEO Optimizer',
		'SEOIO: Review Images',
		'manage_options',
		'seoio-list-existing-images',
		'seoio_list_existing_images'
	);
	add_submenu_page(
		'tools.php',
		'WP Img SEO Optimizer',
		'SEOIO: Update Images',
		'manage_options',
		'seoio-update-existing-images',
		'seoio_update_existing_images'
	);
}
add_action( 'admin_menu', 'seoio_register_pages' );

/* Add Alt-text to the media library table. */

// Add the column.
function filename_column( $cols ) {
	$cols["alttext"] = "Alternative Text";
	return $cols;
}

// Display filenames.
function filename_value( $column_name, $id ) {
	$alt_text = get_post_meta( $id, '_wp_attachment_image_alt' );

		if ( ! empty($alt_text[0]) ) {
			$alt = $alt_text[0];
		}
	echo $alt;
}

// Register the column as sortable & sort by name.
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

// Hook actions to admin_init.
function hook_new_media_columns() {
	add_filter( 'manage_media_columns', 'filename_column' );
	add_action( 'manage_media_custom_column', 'filename_value', 10, 2 );
	add_filter( 'manage_upload_sortable_columns', 'filename_column_sortable' );
}
add_action( 'admin_init', 'hook_new_media_columns' );
