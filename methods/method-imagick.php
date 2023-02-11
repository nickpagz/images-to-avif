<?php

defined('ABSPATH') || exit;

class avif_converter{

	public function convertImage( $path, $quality ){
		ini_set( 'memory_limit', '1G' );
		set_time_limit( 120 );

		$output = $path . '.avif';

		try{
			$image = new Imagick( $path );
			$image->setImageFormat('avif');
			$image->stripImage();
			$image->setImageCompressionQuality( $quality );
			$blob = $image->getImageBlob();
			$success = file_put_contents( $output, $blob );
		}catch( \Throwable $e ){
			error_log( print_r( $e, 1 ) );
			return false;
		}

		return array(
			'path' => $output,
			'size' => array(
				'before' => filesize( $path ),
				'after' => filesize( $output )
			)
		);
	}

}