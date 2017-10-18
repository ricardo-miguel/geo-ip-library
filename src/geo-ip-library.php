<?php
/**
 * Plugin Name: Geo IP Library
 * Plugin URI: //ricardomiguel.cl/projects/geo-ip-library
 * Description: Provides simple country location features by using <a href="//chir.ag/projects/geoiploc/">Chir's geolocation library</a>.
 * Version: 0.9.7
 * Author: Ricardo Miguel
 * Author URI: //ricardomiguel.cl
 * Text Domain: geo-ip-library
 * Domain Path: /languages
 * License: GPLv3
 * License URI: //www.gnu.org/licenses/gpl-3.0-standalone.html
 *
 * @package geo-ip-library
 */

/**
 * Geo IP Library is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Geo IP Library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Geo IP Library. If not, see https://www.gnu.org/licenses/gpl-3.0-standalone.html.
 */

/**
 * Avoid direct file access
 *
 * @since   0.1
 */
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );

/**
 * Set current version
 *
 * @since   0.1
 */
define( 'GEO_IP_LIBRARY_VERSION', '0.9.7' );

/**
 * Set common constants
 *
 * @since   0.1
 */
define( 'GEO_IP_LIBRARY_URL', plugin_dir_url( __FILE__ ) );
define( 'GEO_IP_LIBRARY_PATH', plugin_dir_path( __FILE__ ) );
define( 'GEO_IP_LIBRARY_DIR', basename( dirname( __FILE__ ) ) );

/**
 * Load plugin core
 *
 * @since   0.1
 */
require( GEO_IP_LIBRARY_PATH . 'includes/class-core.php' );

/**
 * Initializes plugin
 *
 * @since   0.1
 * @return  void
 */
function geo_ip_library_init() {
	$geo_ip_library = new GeoIPLibrary\Core();
	$geo_ip_library->init();
}

add_action( 'init', 'geo_ip_library_init' );
