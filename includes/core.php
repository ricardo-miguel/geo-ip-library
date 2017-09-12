<?php

    require(GILPATH . 'includes/admin.php');
    require(GILPATH . 'includes/scraper.php');

    if(!function_exists('getCountryFromIP'))
        require(GILPATH . 'lib/geoiploc.php');

    class GeoIPLibrary {

        function run() {
            $scraper = new GeoIPLibraryScraper();
            $scraper->run();

            $admin = new GeoIPLibraryAdmin();
            $admin->run();
        }

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

        public static function get_client_country_code($ip = '') {
            if(empty($ip))
                return getCountryFromIP(GeoIPLibrary::get_client_address());
            else
                return getCountryFromIP($ip);
        }

        public static function get_client_country_name($ip = '') {
            if(empty($ip))
                return getCountryFromIP(GeoIPLibrary::get_client_address(), 'name');
            else
                return getCountryFromIP($ip, 'name');
        }
        
    }

?>