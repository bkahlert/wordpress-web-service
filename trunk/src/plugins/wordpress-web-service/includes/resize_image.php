<?php

/*
ini_set('display_errors', 1);
error_reporting(E_ALL);/**/

require_once(dirname(__FILE__) . "/shared.php");
$file = wpws_getBasedir() . "/wp-content/uploads" . $_REQUEST["src"];

$finalImgInfo = array();
$finalImgInfo[0] = isset($_REQUEST['width']) ? $_REQUEST['width'] : false;
$finalImgInfo[1] = isset($_REQUEST['height']) ? $_REQUEST['height'] : false;

if(!($originalImgInfo = getimagesize($file))) { header("HTTP/1.0 404 Not Found"); exit; }

// Load Image
list(, $type) = split('/', $originalImgInfo['mime']);
$createFunctionName = 'imagecreatefrom' . $type;
$sendFunctionName = 'image' . $type;

// Make sure, the image becomes bigger than the original
if($finalImgInfo[0] > $originalImgInfo[0]) $finalImgInfo[0] = $originalImgInfo[0];
if($finalImgInfo[1] > $originalImgInfo[1]) $finalImgInfo[1] = $originalImgInfo[1];

if($finalImgInfo[0] && $finalImgInfo[1]) {
	// Final dimensions are already set
} else if($finalImgInfo[0]) {
	// Width set
	$finalImgInfo[1] = round($finalImgInfo[0]/($originalImgInfo[0]/$originalImgInfo[1]));
} else if($finalImgInfo[1]) {
	// Height set
	$finalImgInfo[0] = round($finalImgInfo[1]*($originalImgInfo[0]/$originalImgInfo[1]));
} else {
	// Nothing set
	$finalImgInfo[0] = $originalImgInfo[0];
	$finalImgInfo[1] = $originalImgInfo[1];
}

$originalImg = $createFunctionName($file);
$finalImg = imagecreatetruecolor($finalImgInfo[0], $finalImgInfo[1]);
$white = imagecolorallocate($finalImg, 255, 255, 255);
imagefill($finalImg, 0, 0, $white);


imagesavealpha($finalImg, true);
imagealphablending($finalImg, true);
imageantialias($finalImg, true);



// INSERT THE IMAGE
//imagecopyresampled
//imagecopyresized
imagecopyresampled(
	$finalImg,
	$originalImg,
	0,
	0,
	0,
	0,
	$finalImgInfo[0],
	$finalImgInfo[1],
	$originalImgInfo[0],
	$originalImgInfo[1]
);

header("Content-type: text/html");
header("Content-type: image/" . $type);
$sendFunctionName($finalImg);
imagedestroy($originalImg);
imagedestroy($finalImg);
exit;
?> 