<?php

defined('ABSPATH') || exit;

if( version_compare( PHP_VERSION, '5.6.12', '<' ) ){
	deactivate_plugins( __DIR__ );
	wp_die( __( 'Please update your PHP to version 5.6.12 or higher, then try activate <strong>Images to avif</strong> again.', 'images-to-avif' ) );
}

if( ! extension_loaded('gd') && ! extension_loaded('imagick') ){
	deactivate_plugins( __DIR__ );
	wp_die( __( 'Please install GD or Imagick on your server, then try activate <strong>Images to avif</strong> again.', 'images-to-avif' ) );
}

$methods = array();

if( extension_loaded('imagick') ){
	if( class_exists('Imagick') ){
		$image = new Imagick();
		if( in_array( 'avif', $image->queryFormats() ) ){
			$methods['imagick'] = __( 'Imagick', 'images-to-avif' );
		}
	}
}

if(
	function_exists('imagecreatefromjpeg') &&
	function_exists('imagecreatefrompng') &&
	function_exists('imagecreatefromgif') &&
	function_exists('imageistruecolor') &&
	function_exists('imagepalettetotruecolor') &&
	function_exists('imageavif')
){
	$methods['gd'] = __( 'GD', 'images-to-avif' );
}

if( count( $methods ) === 0 ){
	deactivate_plugins( __DIR__ );
	wp_die( __( 'Please enable avif in GD or Imagick on your server, then try activate <strong>Images to avif</strong> again.', 'images-to-avif' ) );
}

update_site_option( 'images_to_avif_methods', $methods );