<?php
/**
 * Return current plugin version
 *
 * @package geo-ip-library
 */

$plugin_file       = __DIR__ . '/src/geo-ip-library.php';
$plugin_file_lines = file( $plugin_file );

$version = trim( explode( ':', $plugin_file_lines[5] )[1] );
echo $version;
