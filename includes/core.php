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

    require(GILPATH . 'includes/scraper.php');
    require(GILPATH . 'includes/shortcode.php');
    require(GILPATH . 'includes/admin.php');

    if(!function_exists('getCountryFromIP'))
        require(GILPATH . 'lib/geoiploc.php');

    class GeoIPLibrary {

        /**
         * Loads all needed plugin classes and/or components
         *
         * @return void
         */
        function run() {
            $scraper = new GeoIPLibraryScraper();
            $scraper->run();

            $shortcode = new GeoIPLibraryShortcode();
            $shortcode->run();

            $admin = new GeoIPLibraryAdmin();
            $admin->run();
        }

        /**
         * Return current client IP address. Bypassing proxies, forwarding and network masks.
         *
         * @return string
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
         * @param string $ip IP address
         * @return void
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
         * @param string $ip IP address
         * @return void
         */
        public static function get_client_country_name($ip = '') {
            if(empty($ip))
                return getCountryFromIP(GeoIPLibrary::get_client_address(), 'name');
            else
                return getCountryFromIP($ip, 'name');
        }
        
    }

?>