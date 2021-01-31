<?php
include 'vendor/autoload.php';

use Spatie\ImageOptimizer\OptimizerChainFactory;

if ( ! isset( $argv[1] ) ) {
	print "Images Path Required! \n";
	exit;
}

$path = $argv[1];

if ( ! is_dir( $path ) ) {
	print "Please provide a valid DIR \n";
	exit;
}

function is_image_file( $file ): bool {
	if ( @is_array( getimagesize( $file ) ) ) {
		$image = true;
	} else {
		$image = false;
	}

	return $image;
}

function scanAllDir( $dir ) {
	$result = [];
	foreach ( scandir( $dir ) as $filename ) {
		if ( $filename[0] === '.' ) {
			continue;
		}
		$filePath = $dir . '/' . $filename;
		if ( is_dir( $filePath ) ) {
			foreach ( scanAllDir( $filePath ) as $childFilename ) {
				$result[] = $filename . '/' . $childFilename;
			}
		} else {
			$result[] = $filename;
		}
	}

	return $result;
}

$dires = scanAllDir( $path );
foreach ( $dires as $file ) {
	if ( is_image_file( $path . '/' . $file ) ) {
		$optimizerChain = OptimizerChainFactory::create();
		$optimizerChain->optimize( $path . '/' . $file );
		print 'Optimized ' . $path . '/' . $file . "\n";
	}
}


//print_r(scanAllDir('uploads'));
//print_r( $argv[1] );