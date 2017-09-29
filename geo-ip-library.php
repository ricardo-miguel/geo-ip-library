<?php

    /**
    * Plugin Name: Geo IP Library
    * Plugin URI: http://ricardomiguel.cl/projects/geo-ip-library
    * Description: Provides several PHP geolocation functions and shortcodes by using <a href="http://chir.ag/projects/geoiploc/">Chir's geolocation library</a>.
    * Version: 0.8.9
    * Author: <a href="http://ricardomiguel.cl">Ricardo Miguel</a>.
    * Text Domain: geo-ip-library
    * Domain path: /languages
    * License: GPLv3
    * License URI: https://www.gnu.org/licenses/gpl-3.0-standalone.html
    *
    *
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
     */
    defined('ABSPATH') or die('No script kiddies, please!');

    /**
     * Set current version
     */
    define('GEO_IP_LIBRARY_VERSION', '0.8.9');

    /**
     * Set common constants
     */
    define('GEO_IP_LIBRARY_URL', plugin_dir_url(__FILE__));
    define('GEO_IP_LIBRARY_PATH', plugin_dir_path(__FILE__));

    /**
     * Load plugin core
     */
    require(GEO_IP_LIBRARY_PATH . 'includes/core.php');
    
    /**
     * Initializes plugin
     * @return void
     */
    function geo_ip_library_init() {
        $geo_ip_library = new GeoIPLibrary();
        $geo_ip_library->init();
    }

    add_action('init', 'geo_ip_library_init');

?>