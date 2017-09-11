<?php

    /**
    * Plugin Name: Geo IP Library
    * Plugin URI: http://ricardomiguel.cl/
    * Description: Provides several PHP geolocalization functions by scoping <a href="http://chir.ag/projects/geoiploc/">Chir's geoiploc library</a>.
    * Version: 0.5.1
    * Author: <a href="http://ricardomiguel.cl">Ricardo Miguel</a>.
    * License: EULA
    */

    /* Avoid direct access */
    defined('ABSPATH') or die('No script kiddies, please!');

    /* Current version */
    define('GILVER', '0.5.1');

    /* Common constants */
    define('GILURL', plugin_dir_url(__FILE__));
    define('GILPATH', plugin_dir_path(__FILE__));

    /* Load core */
    require(GILPATH . 'includes/core.php');
    
    /* Init */
    function geoIPLibraryInit() {
        $instance = new GeoIPLibrary();
        $instance->run();
    }

    add_action('init', 'geoIPLibraryInit');

?>