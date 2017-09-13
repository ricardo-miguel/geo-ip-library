<?php

    /**
    * Plugin Name: Geo IP Library
    * Plugin URI: http://ricardomiguel.cl/
    * Description: Provides several PHP geolocation functions by scoping <a href="http://chir.ag/projects/geoiploc/">Chir's geolocation library</a>.
    * Version: 0.7.5
    * Author: <a href="http://ricardomiguel.cl">Ricardo Miguel</a>.
    * License: EULA
    */

    /**
     * Avoid direct file access
     */
    defined('ABSPATH') or die('No script kiddies, please!');

    /**
     * Set current version
     */
    define('GILVER', '0.7.5');

    /**
     * Set common constants
     */
    define('GILURL', plugin_dir_url(__FILE__));
    define('GILPATH', plugin_dir_path(__FILE__));

    /**
     * Load plugin core
     */
    require(GILPATH . 'includes/core.php');
    
    /**
     * Initializes plugin
     * @return void
     */
    function geoIPLibraryInit() {
        $instance = new GeoIPLibrary();
        $instance->run();
    }

    add_action('init', 'geoIPLibraryInit');

?>