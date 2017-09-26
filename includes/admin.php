<?php

   /* * * * * * * * * * * * * * * * * * * * * * * *
    *            < GeoIPLibraryAdmin >            *
    *                                             *
    * This is the admin core. All needed actions  *
    * filters for management are settled here.    *
    *                                             *
    * * * * * * * * * * * * * * * * * * * * * * * *
    * This is a built-in script, please do not    *
    * modify if is not really necessary.          *
    * * * * * * * * * * * * * * * * * * * * * * * */

    class GeoIPLibraryAdmin {

        /**
         * Initializes administration related actions
         * @return void
         */
        function run() {
            add_action('current_screen', array($this, 'assets'));
            add_action('admin_menu', array($this, 'menu'));            
        }

        /**
         * Load front-end assets (like stylesheets, scripts, etc.)
         * @return void
         */
        function assets() {
            $current_screen = get_current_screen();
            if($current_screen->id == "tools_page_geo-ip-library-settings") {
                wp_enqueue_style('gil-admin-book-style', GILURL . 'assets/css/book.min.css');
                wp_enqueue_style('gil-admin-style', GILURL . 'assets/css/admin.min.css');
                wp_enqueue_script('gil-timeago-script', GILURL . 'assets/js/jquery.timeago.js', 'jquery');
                wp_enqueue_script('gil-admin-script', GILURL . 'assets/js/admin.min.js', 'jquery');
            }
        }

        /**
         * Defines how admin is shown at dashboard
         * @return void
         */
        function menu() {
            add_submenu_page(
                'tools.php',
                'Geo IP Library',
                'Geo IP Library',
                'manage_options',
                'geo-ip-library-settings',
                array($this, 'settings')
            );
        }

        /**
         * Settings page (mostly info)
         * @return void
         */
        function settings() {
            require(GILPATH . 'includes/htmlizer.php');
            $latest_update         = (get_option('geo_ip_library_latest_update')) ? get_option('geo_ip_library_latest_update') : null;
            $string_latest_update  = (is_null($latest_update)) ? 'Never' : strtolower(date('\o\n l, jS F \a\t H:i:s', strtotime($latest_update))) . ' (GMT+0)';
            $file_size             = round(filesize(GILPATH . 'lib/geoiploc.php') / 1000) . ' KB';

            $vars = array(
                "plugin_version" => GILVER,
                "latest_update" => $latest_update,
                "string_latest_update" => $string_latest_update,
                "php_version" => phpversion(), 
                "file_size" => $file_size
            );

            $HTMLizer = new HTMLizer();
            echo $HTMLizer->build_from_file(GILPATH . 'includes/templates/admin.html', $vars);
        }

    } 

?>