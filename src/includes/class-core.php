<?php
/**
 * Geo IP Library - < Core class >
 * This is a built-in script, please do not
 * modify if is not really necessary.
 *
 * @package geo-ip-library
 */

namespace GeoIPLibrary;

/**
 * Load all required classes
 */
require( GEO_IP_LIBRARY_PATH . 'includes/class-geoiplibrary.php' );
require( GEO_IP_LIBRARY_PATH . 'includes/class-scraper.php' );
require( GEO_IP_LIBRARY_PATH . 'includes/class-shortcode.php' );
require( GEO_IP_LIBRARY_PATH . 'includes/class-admin.php' );

if ( ! function_exists( 'getCountryFromIP' ) ) {
	require( GEO_IP_LIBRARY_PATH . 'lib/geoiploc.php' );
}

/**
 * Core class
 * All needed actions and filters are settled here.
 */
class Core {

	/**
	 * Loads all needed plugin classes and/or components
	 *
	 * @since   0.1
	 * @return  void
	 */
	function init() {
		$scraper = new Scraper();
		$scraper->init();

		$shortcode = new Shortcode();
		$shortcode->init();

		$admin = new Admin();
		$admin->init();

		$this->i18n();
	}

	/**
	 * Load text domain for i18n
	 *
	 * @since   0.8
	 * @return  void
	 */
	function i18n() {
		load_plugin_textdomain( 'geo-ip-library', false, GEO_IP_LIBRARY_DIR . '/languages/' );
	}

}
