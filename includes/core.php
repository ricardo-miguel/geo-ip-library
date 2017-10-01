<?php

   /* * * * * * * * * * * * * * * * * * * * * * * *
    *              < GeoIPLibrary >               *
    *                                             *
    * This is the core. All needed actions and    *
    * filters are settled here.                   *
    *                                             *
    * * * * * * * * * * * * * * * * * * * * * * * *
    * This is a built-in script, please do not    *
    * modify if is not really necessary.          *
    * * * * * * * * * * * * * * * * * * * * * * * */

    require(GEO_IP_LIBRARY_PATH . 'includes/scraper.php');
    require(GEO_IP_LIBRARY_PATH . 'includes/shortcode.php');
    require(GEO_IP_LIBRARY_PATH . 'includes/admin.php');

    if(!function_exists('getCountryFromIP'))
        require(GEO_IP_LIBRARY_PATH . 'lib/geoiploc.php');

    class GeoIPLibrary {

        /**
         * Loads all needed plugin classes and/or components
         *
         * @since   0.1
         * @return  void
         */
        function init() {
            $scraper = new GeoIPLibraryScraper();
            $scraper->init();

            $shortcode = new GeoIPLibraryShortcode();
            $shortcode->init();

            $admin = new GeoIPLibraryAdmin();
            $admin->init();

            $this->i18n();
        }

        /**
         * Load text domain for i18n
         *
         * @since   0.8
         * @return  void
         */
        function i18n() {
            load_plugin_textdomain('geo-ip-library', false, GEO_IP_LIBRARY_DIR . '/languages/');
        }

        /**
         * Return current client IP address. Bypassing proxies, forwarding and network masks.
         *
         * @since   0.1
         * @return  string
         */
        public static function get_client_address() {
            $client_address;
            if (getenv('HTTP_CLIENT_IP'))
                $client_address = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
                $client_address = getenv('HTTP_X_FORWARDED_FOR');
            else if(getenv('HTTP_X_FORWARDED'))
                $client_address = getenv('HTTP_X_FORWARDED');
            else if(getenv('HTTP_FORWARDED_FOR'))
                $client_address = getenv('HTTP_FORWARDED_FOR');
            else if(getenv('HTTP_FORWARDED'))
                $client_address = getenv('HTTP_FORWARDED');
            else if(getenv('REMOTE_ADDR'))
                $client_address = getenv('REMOTE_ADDR');
            return $client_address;
        }

        /**
         * Returns current or specified client country ISO 3166-1 alpha-2 code
         *
         * @since   0.2
         * @param   string  $ip IP address
         * @return  void
         */
        public static function get_client_country_code($ip = '') {
            if(empty($ip))
                return getCountryFromIP(GeoIPLibrary::get_client_address());
            else
                return getCountryFromIP($ip);
        }

        /**
         * Returns current or specified client country name
         *
         * @since   0.2
         * @param   string  $ip IP address
         * @return  void
         */
        public static function get_client_country_name($ip = '') {
            if(empty($ip))
                return getCountryFromIP(GeoIPLibrary::get_client_address(), 'name');
            else
                return getCountryFromIP($ip, 'name');
        }
        
    }

?>