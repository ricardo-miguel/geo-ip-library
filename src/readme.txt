=== Geo IP Library ===
Contributors: ricardomiguel
Tags: geo location, geo ip, library, custom content, standalone
Requires at least: 4.3
Tested up to: 4.8.2
Stable tag: 0.9.7
Requires PHP: 5.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0-standalone.html

Provides simple geo ip country location features for Wordpress.


== Description ==

Geo IP Library enables sort of simple but powerful functions in order to provide geo ip country features on WordPress. Main features so far until now:

*	Static PHP functions to be used in plugins and themes. 
*	Shortcodes to display different contents by country or a bunch of countries.
*	Update management of local library from its source.


== About library ==

This plugin uses a third-party PHP single library file which does not have external dependencies, so it comes within the plugin and works locally. It is intended for anyone who don't want to use (or depend of) web services or either implement [native PHP GeoIP extensions](http://php.net/manual/es/book.geoip.php).

The library was made by [Chirag Mehta](http://chir.ag/projects/geoiploc/).


== Library update ==
Since working with a local library can be a time-saver, its data may get deprecated sometime in the future. This plugin allow update your local library from its remote source. To do that, go to admin dashboard (specifically under _Tools_ menu), where the library can be updated without any risk.

= Updating restriction =
The library can be updated every 72 hours. It is not really necessary a minor interval.


== How to use ==

= Shortcode =
Display different content for each country (or countries) within posts and pages by using **[geo-ip]** or **[geo]** tags. To do magic, see the following syntaxes:

	/**
	* INCLUDE PROPERTY
	* Display content to a specific country or many countries
	*/

	// [geo-ip] tag
	[geo-ip include="{2-digits country code [, other countries]}"]{plain text, HTML and/or shortcodes}[/geo-ip]

	// [geo] tag
	[geo include="{2-digits country code [, other countries]}"]{plain text, HTML and/or shortcodes}[/geo]

	/**
	* EXCLUDE PROPERTY
	* Display content to all countries but...
	*/

	// [geo-ip] tag
	[geo-ip exclude="{2-digits country code [, other countries]}"]{plain text, HTML and/or shortcodes}[/geo-ip]

	// [geo] tag
	[geo exclude="{2-digits country code [, other countries]}"]{plain text, HTML and/or shortcodes}[/geo]


*	[geo] tag is only available if no other plugin is using it (since it's a pretty common word).
*	*include* and *exclude* cannot be used together due to their purpose.


= Coding =

The following static functions can be used anywhere along GeoIPLibrary class:

	/** Returns the current client's IP address as STRING. 
		* It bypasses proxies and/or forwarding. 
		* Returns FALSE if it fails. */
	GeoIPLibrary::get_client_address()


	/** Returns the current client's ISO 3166-1 alpha-2 country code 
		* or the specified at $ip parameter as STRING. 
		* Returns FALSE if it fails. */
	GeoIPLibrary::get_client_country_code(string $ip = '')


	/** Returns the current client's country name 
		* or the specified at $ip parameter as STRING. 
		* Returns FALSE if it fails. */
	GeoIPLibrary::get_client_country_name(string $ip = '')


== Contributions ==
Geo IP Library is still in development, but currently stable. You can contribute with new ideas, coding improves/issues and even grammar/spelling check (english is not my mother language). Feel free to [make a pull request](https://github.com/ricardo-miguel/geo-ip-library/pulls) or [open an issue](https://github.com/ricardo-miguel/geo-ip-library/issues).