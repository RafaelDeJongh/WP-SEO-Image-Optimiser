<?php
/*
	Plugin Name: WP SEO Image Optimiser
	Plugin URI: https://TOCOME
	Description: A plugin that sanitizes image titles and copies them to other metadata fields to improve SEO.
	Author: Rafaël De Jongh & Yogensia
	Author URI: https://www.rafaeldejongh.com
	Author URI: https://www.yogensia.com
	Donate link: https:/TOCOME
	Requires at least: 4.7
	Tested up to: 4.9
	Stable tag: 1.0
	Version: 1.0
	Requires PHP: 5.2
	Text Domain: wp-seo-img-optim
	Domain Path: /languages
	License: GPL v3 or later
	Tags: images, seo
*/

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

if ( ! class_exists('WPImgSEO') ) {
	class WPImgSEO {
		function __construct() {
			$this->constants();
			$this->includes();
		}

		function constants() {
			if (!defined('SEOIO_VERSION')) define('SEOIO_VERSION', '1.0');
			if (!defined('SEOIO_URL'))     define('SEOIO_URL',     plugin_dir_url(__FILE__));
			if (!defined('SEOIO_DIR'))     define('SEOIO_DIR',     plugin_dir_path(__FILE__));
			if (!defined('SEOIO_FILE'))    define('SEOIO_FILE',    plugin_basename(__FILE__));
			if (!defined('SEOIO_SLUG'))    define('SEOIO_SLUG',    basename(dirname(__FILE__)));
		}

		function includes() {
			if (is_admin()) {
				require_once SEOIO_DIR .'inc/register-settings.php';
				require_once SEOIO_DIR .'inc/main.php';
			}
		}
	}

	$WPImgSEO = new WPImgSEO();
}
