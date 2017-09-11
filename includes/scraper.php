<?php

    class GeoIPLibraryScraper {

        private $file   = GILPATH . 'lib/geoiploc.tar.gz';
        private $source = 'http://chir.ag/projects/geoiploc/autogen/geoiploc.tar.gz';

        public function update_library() {
            if($this->get_library())
                return $this->extract_library();
            else
                return false;
        }

        private function get_library() {
            try {
                file_put_contents($this->file, fopen($this->source, "r"));
                return true;
            } catch(Exception $e) {
                return false;
            }
        }

        private function extract_library() {
            if(file_exists($this->file)) {
                try {
                    $phar = new PharData($this->file);
                    $phar->extractTo(GILPATH . 'lib', 'geoiploc.php', true);
                    return true;
                } catch(Exception $e) {
                    return false;
                }
            } else {
                return false;
            }
        }

    }

?>