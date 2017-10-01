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
         * Initializes shortcode related actions
         * 
         * @since   0.5
         * @return  void
         */
        function init() {
            add_shortcode('geo-ip', array($this, 'shortcode'));
            if(!shortcode_exists('geo'))
                add_shortcode('geo', array($this, 'shortcode'));
        }

        /**
         * Main shortcode function.
         * Available as [geo-ip-library] and [geo] (if is not used by another plugin)
         * 
         * @since   0.5
         * @param   array   $atts       Shortcode attributes collection
         * @param   string  $content    Content between opening and closing tags
         * @return  void
         */
        function shortcode($atts = [], $content = null) {
            if(empty($atts['country']))
                return "<p><i><b>" . __('Geo IP Library','geo-ip-library') . ":</b> " . __('Country not specified.', 'geo-ip-library') . "</i></p>";

            if(empty($content))
                return "<p><i><b>" . __('Geo IP Library','geo-ip-library') . ":</b> " . __('No content found. Check for closing tag.', 'geo-ip-library') . "</i></p>";

            $client_country = GeoIPLibrary::get_client_country_code();
            $country_sanitized = strtoupper(trim($atts['country']));
            $countries = preg_split('/\s*,\s*/', $country_sanitized);
            
            if(in_array($client_country, $countries))
                return do_shortcode($content);

            return null;
        }

    }

?>