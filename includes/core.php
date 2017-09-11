<?php

    require(GILPATH . 'includes/admin.php');
    require(GILPATH . 'includes/scraper.php');

    if(!function_exists('getCountryFromIP'))
        require(GILPATH . 'lib/geoiploc.php');

    class GeoIPLibrary {

        function __construct() {
            $admin = new GeoIPLibraryAdmin();
            $admin->run();
        }

        function run() {
            if(isset($_POST['update_geo_ip_library'])) {
                $scraper = new GeoIPLibraryScraper();
                $scraper->update_library();
            }
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

        public static function get_client_country_code() {
            return getCountryFromIP(GeoIPLibrary::get_client_address());
        }

        public static function get_client_country_name() {
            return getCountryFromIP(GeoIPLibrary::get_client_address(), 'name');
        }
        
    }

?>