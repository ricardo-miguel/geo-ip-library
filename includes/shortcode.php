<?php

   /* * * * * * * * * * * * * * * * * * * * * * * *
    *          < GeoIPLibraryShortcode >          *
    *                                             *
    * It defines and handles all available        *
    * shortcodes to use within pages and posts.   *
    *                                             *
    * * * * * * * * * * * * * * * * * * * * * * * *
    * This is a built-in script, please do not    *
    * modify if is not really necessary.          *
    * * * * * * * * * * * * * * * * * * * * * * * */

    class GeoIPLibraryShortcode {

        /**
         * Initializes scraping related actions
         * @return void
         */
        function run() {
            add_shortcode('geo-ip-library', array($this, 'shortcode'));
            if(!shortcode_exists('geo'))
                add_shortcode('geo', array($this, 'shortcode'));
        }

        /**
         * Main shortcode function.
         * Available as [geo-ip-library] and [geo] (if is not used by another plugin)
         * @param array $atts Shortcode attributes collection
         * @param string $content Content between opening and closing tags
         * @return void
         */
        function shortcode($atts = [], $content = null) {
            if(empty($atts['country']))
                return "<p><i><span style=\"font-weight: 600\">Geo IP Library:</span> Country not specified.</i></p>";

            if(empty($content))
                return "<p><i><span style=\"font-weight: 600\">Geo IP Library:</span> No content found. Check for closing tag.</i></p>";

            $client_country = GeoIPLibrary::get_client_country_code();
            $country_sanitized = strtoupper(trim($atts['country']));
            $countries = preg_split('/\s*,\s*/', $country_sanitized);
            if(in_array($client_country, $countries))
                return do_shortcode($content);

            return null;
        }

    }

?>