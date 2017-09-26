<?php
    /**
     * HTMLizer | ver. 0.5 beta
     * This script allows to include html files as templates with
     * variables within brackets, which are replaced by anything
     * defined in an associative array.
     * 
     * @author Ricardo Miguel | http://ricardomiguel.cl
     */
    class HTMLizer {

        /**
         * Build HTML code with variables
         * Any bracket reference in HTML code which are not defined
         * by the array will be deleted.
         *
         * @param  string $html HTML code
         * @param  array  $vars Associative array with variable definitions
         * @return string|bool Parsed HTML code or FALSE if it fails
         */
        private function build($html = '', $vars = []) {
            if(empty($html) || empty($vars))
                return false;

            foreach($vars as $key => $value) {
                $replace = '{' . $key . '}';
                $html = str_replace($replace, $value, $html);
            }

            $html = preg_replace('/\{(\S+)\}/', '', $html);

            return $html;
        }

        /**
         * Returns parsed HTML from given HTML string
         *
         * @param  string $html_string HTML code as string
         * @param  array  $vars Associative array containing variable key names and their definitions
         * @return string|bool Parsed HTML code or FALSE if it fails
         */
        public function build_from_string($html_string = '', $vars = []) {
            return $this->build($html_string, $vars);
        }

        /**
         * Returns parsed HTML from given HTML file path or URI
         *
         * @param  string $html_path HTML file path or URI
         * @param  array  $vars Associative array containing variable key names and their definitions
         * @return string|bool Parsed HTML code or FALSE if it fails
         */
        public function build_from_file($html_path = '', $vars = []) {
            $html_string = file_get_contents($html_path);
            return $this->build($html_string, $vars);
        }
    }
?>