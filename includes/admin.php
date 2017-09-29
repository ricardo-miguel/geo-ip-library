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
         * 
         * @since   0.0.2
         * @return  void
         */
        function init() {
            add_action('current_screen', array($this, 'assets'));
            add_action('admin_menu', array($this, 'menu'));            
        }

        /**
         * Load front-end assets (like stylesheets, scripts, etc.)
         * 
         * @since   0.0.2
         * @return  void
         */
        function assets() {
            $current_screen = get_current_screen();
            if($current_screen->id == "tools_page_geo-ip-library-settings") {
                wp_enqueue_style('geo-ip-library-admin-book-style', GEO_IP_LIBRARY_URL . 'assets/css/book.min.css');
                wp_enqueue_style('geo-ip-library-admin-style', GEO_IP_LIBRARY_URL . 'assets/css/admin.min.css');
                wp_enqueue_script('geo-ip-library-timeago-script', GEO_IP_LIBRARY_URL . 'assets/js/jquery.timeago.js', 'jquery');
                wp_enqueue_script('geo-ip-library-admin-script', GEO_IP_LIBRARY_URL . 'assets/js/admin.min.js', 'jquery');
            }
        }

        /**
         * Defines how admin is shown at dashboard
         * 
         * @return  void
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
         * 
         * @return  void
         */
        function settings() {
            require(GEO_IP_LIBRARY_PATH . 'includes/htmlizer.php');
            $latest_update         = (get_option('geo_ip_library_latest_update')) ? get_option('geo_ip_library_latest_update') : null;
            $string_latest_update  = (is_null($latest_update)) ? __('Never', 'geo-ip-library') : date('l, jS F H:i:s (eP)', strtotime($latest_update));
            $diff_latest_update    = date_diff(new DateTime($latest_update), new DateTime());
            $diff_total_hours      = ($diff_latest_update->d * 24) + $diff_latest_update->h;
            $file_size             = round(filesize(GEO_IP_LIBRARY_PATH . 'lib/geoiploc.php') / 1000) . ' KB';

            $vars = array(
                "GEO_IP_LIBRARY"        => __('Geo IP Library', 'geo-ip-library'),
                "SOURCE_LIBRARY_BY"     => sprintf(__('source library by %s', 'geo-ip-library'), '<a href="//chir.ag/projects/geoiploc/">Chirag Mehta</a>'),
                "SCRAPING_BY"           => sprintf(__('scraping by %s', 'geo-ip-library'), '<a href="//ricardomiguel.cl">Ricardo Miguel</a>'),
                "SOURCE"                => __('Source', 'geo-ip-library'),
                "LATEST_UPDATE"         => __('Latest update', 'geo-ip-library'),
                "LATEST_UPDATE_DATE"    => $latest_update,
                "LATEST_UPDATE_STRING"  => $string_latest_update,
                "DIFF_UPDATE"           => $diff_total_hours,
                "UPDATE_NOW"            => __('UPDATE NOW', 'geo-ip-library'),
                "SIZE"                  => __('Size', 'geo-ip-library'),
                "FILE_SIZE"             => $file_size,
                "PHP_VERSION"           => __('PHP Version', 'geo-ip-library'),
                "PHP_VERSION_FUNC"      => phpversion(),
                "SHORTCODE"             => __('Shortcode', 'geo-ip-library'),
                "SHORTCODE_DESCRIPTION" => __('Display different contents within posts and pages under specified country or countries when using [geo-ip-library] or [geo] tags. The available syntaxes are:', 'geo-ip-library'),
                "SYNTAX_CONTENT"        => __('{2-digit country code [, other countries]}', 'geo-ip-library'),
                
                
            );

            $HTMLizer = new HTMLizer();
            echo $HTMLizer->build_from_file(GEO_IP_LIBRARY_PATH . 'includes/templates/admin.html', $vars);
        }

    }

?>