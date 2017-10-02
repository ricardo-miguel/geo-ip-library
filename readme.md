# Geo IP Library
[![License](https://img.shields.io/badge/license-GPLv3-b62b6e.svg?style=flat-square)](https://www.gnu.org/licenses/gpl-3.0-standalone.html)
![Plugin version](https://img.shields.io/badge/version-0.9.1-8ba753.svg?style=flat-square)
![PHP minimum](https://img.shields.io/badge/php-%3E%3D%205.3-8892be.svg?style=flat-square)
![Wordpress minimum](https://img.shields.io/badge/wordpress-%3E%3D%204.4-21759b.svg?style=flat-square)
![Wordpress tested](https://img.shields.io/badge/tested%20to-4.8.1-green.svg?style=flat-square)

Provides simple country location features for Wordpress.


## Description

This plugin enables global static PHP functions in order to provide ip geolocation features for plugins and themes. Also, provides a shortcode to display contents by country.

<br />

## Source library

This plugin uses a third-party standalone PHP library to make everything work. It is intended for anyone who don't want to use (or depend of) external services or just cannot implement [native PHP GeoIP functionality](http://php.net/manual/es/book.geoip.php).

The library was made by [Chirag Mehta](http://chir.ag/projects/geoiploc/) and it updates automatically everyday.


### Updating library
From admin dashboard (specifically under _Tools_ menu), the library can be updated without any risk.

<br />

## Usage

### Shortcode

Display different content for each country (or countries) within posts and pages by using [geo-ip] or [geo] tags. To do magic, use the following syntax:

```php
[geo-ip country="{2-digits country code [, other countries]}"]{plain text, HTML and/or shortcodes}[/geo-ip]

/* OR */

[geo country="{2-digits country code [, other countries]}"]{plain text, HTML and/or shortcodes}[/geo]
```

### Coding

The following static functions can be used anywhere along GeoIPLibrary class:

```php
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
```

# Contributions
Geo IP Library is still in development, but currently stable. You can contribute with new ideas, coding improves/issues and even grammar/spelling check (english is not my mother language).