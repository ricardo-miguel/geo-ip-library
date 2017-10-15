<?php
/**
 * Geo IP Library - < GeoIPLibrary class >
 * This is a built-in script, please do not
 * modify if is not really necessary.
 *
 * @package geo-ip-library
 */

/**
 * Public static functions collection class
 */
class GeoIPLibrary {

	/**
	 * Return current client IP address. Bypassing proxies, forwarding and network masks.
	 *
	 * @since   0.1
	 * @return  string
	 */
	public static function get_client_address() {
		$client_address;
		if ( getenv( 'HTTP_CLIENT_IP' ) ) {
			$client_address = getenv( 'HTTP_CLIENT_IP' );
		} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
			$client_address = getenv( 'HTTP_X_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
			$client_address = getenv( 'HTTP_X_FORWARDED' );
		} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
			$client_address = getenv( 'HTTP_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
			$client_address = getenv( 'HTTP_FORWARDED' );
		} elseif ( getenv( 'REMOTE_ADDR' ) ) {
			$client_address = getenv( 'REMOTE_ADDR' );
		}
		return $client_address;
	}

	/**
	 * Returns current or specified client country ISO 3166-1 alpha-2 code
	 *
	 * @since   0.2
	 * @param   string $ip IP address.
	 * @return  string
	 */
	public static function get_client_country_code( $ip = '' ) {
		if ( empty( $ip ) ) {
			return getCountryFromIP( GeoIPLibrary::get_client_address() );
		} else {
			return getCountryFromIP( $ip );
		}
	}

	/**
	 * Returns current or specified client country name
	 *
	 * @since   0.2
	 * @param   string $ip IP address.
	 * @return  string
	 */
	public static function get_client_country_name( $ip = '' ) {
		if ( empty( $ip ) ) {
			return getCountryFromIP( GeoIPLibrary::get_client_address(), 'name' );
		} else {
			return getCountryFromIP( $ip, 'name' );
		}
	}

}
