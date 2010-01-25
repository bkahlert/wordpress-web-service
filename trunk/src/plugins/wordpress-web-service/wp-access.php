<?php

define("WPWS_WSDL_TEMPLATE", dirname(__FILE__) . "/wpws.template.wsdl");
define("WPWS_WSDL", dirname(__FILE__) . "/wpws.wsdl");
define("WPWS_BLOG_URL", "%{BLOG_URL}");
define("WPWS_INDEX", dirname(__FILE__) . "/wpws-index.php");

function wpws_WSDLcustomized() {
	if(file_exists(WPWS_WSDL)) {
		$wsdl = file_get_contents(WPWS_WSDL_TEMPLATE);
		return (strpos($wsdl, WPWS_BLOG_URL) !== false);
	}
}

function wpws_createWSDL() {
	$wsdl = wpws_getWSDLfromTemplate();
	
	// Should this operation fail, write permission aren't given.
	// In this case you need to create the customized WSDL on the base
	// of the template file on your own.
	@file_put_contents(WPWS_WSDL, $wsdl);
}

function wpws_getWSDLfromTemplate() {
	$wsdl = file_get_contents(WPWS_WSDL_TEMPLATE);
	return str_replace(WPWS_BLOG_URL, wpws_getBlogUrl(), $wsdl);
}

function wpws_getBlogUrl() {
	return (defined("WP_HOME")) ? WP_HOME : get_option("home", "");
}

function wpws_getWsdlUrl() {
	return wpws_getBlogUrl() . "/index.php?/wpws/?wsdl";
}

function wpws_getBaseDir() {
	$current_path = $_SERVER['SCRIPT_FILENAME'];
	while(true) {
		$slash_pos = strrpos($current_path, "/");
		if($slash_pos === false) return false;
		
		$current_path = substr($current_path, 0, $slash_pos);
		if(file_exists($current_path . "/wp-load.php")) {
			return $current_path;
		}
	}
}

function wpws_getPluginBaseDir() {
	return wpws_getBlogUrl() . "/wp-content/plugins/wordpress-web-service";
}

?>