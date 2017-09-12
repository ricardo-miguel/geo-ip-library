<?php

    class GeoIPLibraryScraper {

        private $file   = GILPATH . 'lib/geoiploc.tar.gz';
        private $source = 'http://chir.ag/projects/geoiploc/autogen/geoiploc.tar.gz';

        function run() {
            add_action('wp_ajax_geo_ip_get_library', array($this, 'geo_ip_get_library'));
            add_action('wp_ajax_geo_ip_extract_library', array($this, 'geo_ip_extract_library'));
            add_action('wp_ajax_geo_ip_update_lastest', array($this, 'geo_ip_update_lastest'));
        }

        function geo_ip_get_library() {
            try {
                file_put_contents($this->file, fopen($this->source, "r"));
                $response = array(
                    "success" => true,
                    "file" => $this->file
                );
                wp_send_json($response);
            } catch(Exception $e) {
                wp_send_json(array("success" => false, "exception" => $e));
            }
        }

        function geo_ip_extract_library() {
            if(file_exists($this->file)) {
                try {
                    $phar = new PharData($this->file);
                    $phar->extractTo(GILPATH . 'lib', 'geoiploc.php', true);
                    $response = array(
                        "success" => true,
                        "file" => GILPATH . 'lib/' . 'geoiploc.php'
                    );
                    wp_send_json($response);
                } catch(Exception $e) {
                    wp_send_json(array("success" => false, "exception" => $e));
                }
            } else {
                wp_send_json(array("success" => false));
            }
        }

        function geo_ip_update_lastest() {
            $now = date('c');
            $string_now = strtolower(date('\o\n l, jS F \a\t H:i:s', strtotime($now))) . ' (GMT+0)';
            $file_size = round(filesize(GILPATH . 'lib/geoiploc.php') / 1000) . ' KB';
            if(get_option('geo_ip_library_lastest_update'))
                update_option('geo_ip_library_lastest_update', $now);
            else
                add_option('geo_ip_library_lastest_update', $now);
            wp_send_json(array("success" => true, "date" => $now, "string_date" => $string_now, "size" => $file_size));
        }

    }

?>