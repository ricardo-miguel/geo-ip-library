<?php

   /* * * * * * * * * * * * * * * * * * * * * * * *
    *           < GeoIPLibraryScraper >           *
    *                                             *
    * Everything related to downloading and       *
    * extracting the source library file.         *
    *                                             *
    * * * * * * * * * * * * * * * * * * * * * * * *
    * This is a built-in script, please do not    *
    * modify if is not really necessary.          *
    * * * * * * * * * * * * * * * * * * * * * * * */

    class GeoIPLibraryScraper {

        /**
         * Physical path to library compressed file
         * @var string
         */
        protected $file   = GILPATH . 'lib/geoiploc.tar.gz';

        /**
         * Source URI of library compressed file to download for
         * @var string
         */
        protected $source = 'http://chir.ag/projects/geoiploc/autogen/geoiploc.tar.gz';

        /**
         * Initializes scraping related actions
         * @return void
         */
        function run() {
            add_action('wp_ajax_geo_ip_get_library', array($this, 'geo_ip_get_library'));
            add_action('wp_ajax_geo_ip_extract_library', array($this, 'geo_ip_extract_library'));
            add_action('wp_ajax_geo_ip_update_latest', array($this, 'geo_ip_update_latest'));
        }

        /**
         * Gets compressed library file from original project source and saves it in /lib/ folder
         * @return void
         */
        function geo_ip_get_library() {
            try {
                file_put_contents($this->file, fopen($this->source, "r"));
                if(filesize($this->file) > 200000) {
                    $response = array(
                        "success" => true,
                        "file" => $this->file
                    );
                    wp_send_json($response);
                } else {
                    wp_send_json(array("success" => false, "exception" => "FILE_SIZE"));
                }
            } catch(Exception $e) {
                wp_send_json(array("success" => false, "exception" => "FILE_DOWNLOAD"));
            }
        }

        /**
         * Unzips downloaded library file within same folder
         * @return void
         */
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
                    wp_send_json(array("success" => false, "exception" => "FILE_EXTRACTION"));
                }
            } else {
                wp_send_json(array("success" => false, "exception" => "FILE_NOT_FOUND"));
            }
        }

        /**
         * Retrieves latest update date and library file size
         * @return void
         */
        function geo_ip_update_latest() {
            $now = date('c');
            $string_now = strtolower(date('\o\n l, jS F \a\t H:i:s', strtotime($now))) . ' (GMT+0)';
            $file_size = round(filesize(GILPATH . 'lib/geoiploc.php') / 1000) . ' KB';
            if(get_option('geo_ip_library_latest_update'))
                update_option('geo_ip_library_latest_update', $now);
            else
                add_option('geo_ip_library_latest_update', $now);
            wp_send_json(array("success" => true, "date" => $now, "string_date" => $string_now, "size" => $file_size));
        }

    }

?>