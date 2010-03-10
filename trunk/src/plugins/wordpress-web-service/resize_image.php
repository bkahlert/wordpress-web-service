<?php

require_once(dirname(__FILE__) . "/includes/wpws-access.php");
require_once(dirname(__FILE__) . "/includes/wpws-imageutils.php");

/*
ini_set("display_errors", 1);
error_reporting(E_ALL);/**/


$src = $_REQUEST["src"];
$width = isset($_REQUEST["width"]) ? $_REQUEST["width"] : false;
$height = isset($_REQUEST["height"]) ? $_REQUEST["height"] : false;
$quality = isset($_REQUEST["quality"]) ? $_REQUEST["quality"] : 80;
$file = wpws_getBasedir() . WP_UPLOAD_DIR . $_REQUEST["src"];
$send_function_name = wpws_ImageUtils::get_send_function_name($file);

if(wpws_cacheIsFunctional()) {
	// Use the cache
	$mtime = filemtime($file);
	if(wpws_ImageUtils::cached_file_is_needed($src, $width, $height, $quality, $mtime)) {
		// Cache image
		wpws_ImageUtils::remove_cached_file($src, $width, $height, $quality);
		$resized_image = wpws_ImageUtils::generate_resized_image($file, $width, $height);
		$cache_filename = wpws_ImageUtils::generate_timestamped_cache_filename($src, $width, $height, $quality, $mtime);
		$send_function_name($resized_image, WPWS_CACHE_DIR . "/" . $cache_filename, $quality);
	}
	
	$cached_file = wpws_ImageUtils::get_cached_file($src, $width, $height, $quality);
	header("Content-Type: " . wpws_ImageUtils::get_mime_type($file));
	header("Content-Length: " . filesize($cached_file));
	readfile($cached_file);
} else {
	// Do not use the cache
	$resized_image = wpws_ImageUtils::generate_resized_image($file, $width, $height);
	
	ob_start();
	$send_function_name($resized_image, "", $quality);
	header("Content-Type: " . get_mime_type($file));
	header("Content-Length: " . ob_get_length());
	ob_end_flush();
}

// Delete temporary resources
if($resized_image) imagedestroy($resized_image);
exit;

?> 