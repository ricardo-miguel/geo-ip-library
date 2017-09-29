<?php

   /* * * * * * * * * * * * * * * * * * * * * * * *
    *            < GeoIPLibraryAdmin >            *
    *                                             *
    * This is the admin core. All needed actions  *
    * filters for management are settled here.    *
    *                                             *
    * * * * * * * * * * * * * * * * * * * * * * * *
    * This is a built-in script, please do not    *
    * modify if is not really necessary.          *
    * * * * * * * * * * * * * * * * * * * * * * * */

    class GeoIPLibraryAdmin {

        /**
         * Initializes administration related actions
         * 
         * @since   0.0.2
         * @return  void
         */
        function init() {
            add_action('current_screen', array($this, 'assets'));
            add_action('admin_menu', array($this, 'menu'));            
        }

        /**
         * Load front-end assets (like stylesheets, scripts, etc.)
         * 
         * @since   0.0.2
         * @return  void
         */
        function assets() {
            $current_screen = get_current_screen();
            if($current_screen->id == "tools_page_geo-ip-library-settings") {
                wp_enqueue_style('geo-ip-library-admin-book-style', GEO_IP_LIBRARY_URL . 'assets/css/book.min.css');
                wp_enqueue_style('geo-ip-library-admin-style', GEO_IP_LIBRARY_URL . 'assets/css/admin.min.css');
                wp_enqueue_script('geo-ip-library-timeago-script', GEO_IP_LIBRARY_URL . 'assets/js/jquery.timeago.js', 'jquery');
                wp_enqueue_script('geo-ip-library-admin-script', GEO_IP_LIBRARY_URL . 'assets/js/admin.min.js', 'jquery');

                $this->l10n_assets();
            }
        }

        /**
         * Set and load localized strings to front-end
         *
         * @since   0.8
         * @return  void
         */
        function l10n_assets() {
            $l10n_geo_ip = array(
                "NEVER"                => __('Never', 'geo-ip-library'),
                "UPDATE_AVAILABLE_IN"  => __('UPDATE AVAILABLE IN %d %s', 'geo-ip-library'),
                "HOUR"                 => __('HOUR', 'geo-ip-library'),
                "HOURS"                => __('HOURS', 'geo-ip-library'),
                "EX_FILE_DOWNLOAD"     => __('Something went wrong while attempting to download from source. Try again later.', 'geo-ip-library'),
                "EX_FILE_SIZE"         => __('It seems that source library is updating itself, give it a try later.', 'geo-ip-library'),
                "EX_FILE_EXTRACTION"   => __('There was something weird while trying to unzip the source file. Try again in a few minutes.', 'geo-ip-library'),
                "EX_FILE_NOT_FOUND"    => __('Huh! The library source was suppose to be found, but is not! Check for writing and reading permissions and try it again.', 'geo-ip-library')
            );

            wp_localize_script( 'geo-ip-library-admin-script', 'l10n_geo_ip', $l10n_geo_ip);
        }

        /**
         * Defines how admin is shown at dashboard
         * 
         * @return  void
         */
        function menu() {
            add_submenu_page(
                'tools.php',
                'Geo IP Library',
                'Geo IP Library',
                'manage_options',
                'geo-ip-library-settings',
                array($this, 'settings')
            );
        }

        /**
         * Settings page (mostly info)
         * 
         * @return  void
         */
        function settings() {
            require(GEO_IP_LIBRARY_PATH . 'includes/htmlizer.php');

            $latest_update          = (get_option('geo_ip_library_latest_update')) ? get_option('geo_ip_library_latest_update') : null;
            $string_latest_update   = (is_null($latest_update)) ? __('Never', 'geo-ip-library') : date('l, jS F H:i:s (eP)', strtotime($latest_update));
            $diff_latest_update     = date_diff(new DateTime($latest_update), new DateTime());
            $diff_total_hours       = ($diff_latest_update->d * 24) + $diff_latest_update->h;
            $file_size              = round(filesize(GEO_IP_LIBRARY_PATH . 'lib/geoiploc.php') / 1000) . ' KB';

            $vars = array(
                "GEO_IP_LIBRARY"                 => __('Geo IP Library', 'geo-ip-library'),
                "SOURCE_LIBRARY_BY"              => sprintf(__('source library by %s', 'geo-ip-library'), '<a href="//chir.ag/projects/geoiploc/">Chirag Mehta</a>'),
                "SCRAPING_BY"                    => sprintf(__('scraping by %s', 'geo-ip-library'), '<a href="//ricardomiguel.cl">Ricardo Miguel</a>'),
                "SOURCE"                         => __('Source', 'geo-ip-library'),
                "LATEST_UPDATE"                  => __('Latest update', 'geo-ip-library'),
                "LATEST_UPDATE_DATE"             => $latest_update,
                "LATEST_UPDATE_STRING"           => $string_latest_update,
                "DIFF_UPDATE"                    => $diff_total_hours,
                "UPDATE_NOW"                     => __('UPDATE NOW', 'geo-ip-library'),
                "SIZE"                           => __('Library size', 'geo-ip-library'),
                "FILE_SIZE"                      => $file_size,
                "PHP_VERSION"                    => __('PHP Version', 'geo-ip-library'),
                "PHP_VERSION_FUNC"               => phpversion(),
                "SHORTCODE"                      => __('Shortcode', 'geo-ip-library'),
                "SHORTCODE_DESCRIPTION"          => sprintf(__('Display different contents for each country (or countries) within posts and pages by using %s or %s tags. To do magic, use the following syntax:', 'geo-ip-library'), '<strong>[geo-ip]</strong>', '<strong>[geo]</strong>'),
                "SYNTAX"                         => __('SYNTAX', 'geo-ip-library'),
                "SYNTAX_COUNTRY"                 => __('{2-digits country code [, other countries]}', 'geo-ip-library'),
                "SYNTAX_CONTENT"                 => __('{plain text, HTML and/or shortcodes}', 'geo-ip-library'),
                "USAGE_EXAMPLES"                 => __('A few usage examples', 'geo-ip-library'),
                "SINGLE_COUNTRY"                 => __('SINGLE COUNTRY', 'geo-ip-library'),
                "SINGLE_COUNTRY_DESCRIPTION"     => sprintf(__('This will display %s to all visitors from USA. Others will not see anything at all.', 'geo-ip-library'), '<i>Hello world!</i>'),
                "MULTIPLE_COUNTRIES"             => __('MULTIPLE COUNTRIES', 'geo-ip-library'),
                "MULTIPLE_COUNTRIES_DESCRIPTION" => sprintf(__('This will display %s to all visitors from Chile, Spain and Mexico. Others will not see anything at all.', 'geo-ip-location'), '<i>¡Hola mundo!</i>'),
                "OTHER_SHORTCODE"                => __('You can also call other shortcodes inside:', 'geo-ip-library'),
                "OTHER_SHORTCODE_SYNTAX"         => __('We\'re ready to go! See details below:', 'geo-ip-library'),
                "OTHER_SHORTCODE_TAG"            => __('[my-other-shortcode]', 'geo-ip-library'),
                "OTHER_SHORTCODE_DESCRIPTION"    => sprintf(__('This will display the content to all visitors from Canada and will process %s shortcode. Others will not see anything at all.', 'geo-ip-library'), '<i> ' . __('[my-other-shortcode]', 'geo-ip-library') . '</i>'),
                "SHORTCODE_CEPTION"              => __('SHORTCODE-CEPTION', 'geo-ip-library'),
                "CODING"                         => __('Coding', 'geo-ip-library'),
                "CODING_DESCRIPTION"             => sprintf(__('The following static functions can be used anywhere along %s class:', 'geo-ip-library'), 'GeoIPLibrary'),
                "GET_CLIENT_ADDRESS"             => __('Returns the current client\'s IP address. It bypasses proxies and/or forwarding.', 'geo-ip-library'),
                "GET_CLIENT_COUNTRY_CODE"        => sprintf(__('Returns the current client\'s %s country code or the specified at %s parameter.', 'geo-ip-library'), '<a href="https://wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a>', '<span style="color: #369">$ip</span>'),
                "GET_CLIENT_COUNTRY_NAME"        => sprintf(__('Returns the current client\'s country name or the specified at %s parameter.', 'geo-ip-library'), '<span style="color: #369">$ip</span>'),
                "DOWNLOADING"                    => __('Downloading library package', 'geo-ip-library'),
                "DECOMPRESSING"                  => __('Decompressing library file', 'geo-ip-library'),
                "DONE"                           => __('Everything works like a charm!', 'geo-ip-library'),
                "OK_BUTTON"                      => __('OK, you\'re the best!', 'geo-ip-library'),
                "ERROR_BUTTON"                   => __('For sure I will', 'geo-ip-library')
            );

            $HTMLizer = new HTMLizer();
            echo $HTMLizer->build_from_file(GEO_IP_LIBRARY_PATH . 'includes/templates/admin.min.html', $vars);
        }

    }

?>