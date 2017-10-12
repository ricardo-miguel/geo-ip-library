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
            $attributes = shortcode_atts(array(
                "show"      => "*",
                "exclude"   => ""
            ), $atts);

            if($attributes->show == '*' && !is_null($attributes->exclude) && empty($attributes->exclude))
                return "<p><i><b>" . __('Geo IP Library','geo-ip-library') . ":</b> " . __('Exclude property was declared, but it is empty.', 'geo-ip-library') . "</i></p>";

            if($attributes->show !== '*' && !empty($attributes['exclude']))
                return "<p><i><b>" . __('Geo IP Library','geo-ip-library') . ":</b> " . __('Exclude property can no be declared <u>along</u> show property.', 'geo-ip-library') . "</i></p>";

            if(empty($content))
                return "<p><i><b>" . __('Geo IP Library','geo-ip-library') . ":</b> " . __('No content found. Check for closing tag.', 'geo-ip-library') . "</i></p>";

            $client_country     = GeoIPLibrary::get_client_country_code();

            $show_sanitized     = strtoupper(trim($attributes['show']));
            $show               = preg_split('/\s*,\s*/', $show_sanitized);

            if(!is_null($attributes->exclude)) {

            }
            $exclude_sanitized  = strtoupper(trim($attributes['exclude']));
            $exclude            = preg_split('/\s*,\s*/', $exclude_sanitized);
            
            if(($attributes['show'] == '*' || in_array($client_country, $show)) && !in_array($client_country, $exclude))
                return do_shortcode($content);

            return null;
        }

    }

?>