<?php

define("WPWS_WSDL_TEMPLATE", dirname(__FILE__) . "/wpws.template.wsdl");
define("WPWS_WSDL", dirname(__FILE__) . "/wpws.wsdl");

function wpws_createWSDL() {
	$wsdl = wpws_getWSDLfromTemplate();
	
	// Should this operation fail, write permission aren't given.
	// In this case you need to create the customized WSDL on the base
	// of the template file on your own.
	@file_put_contents(WPWS_WSDL, $wsdl);
}

function wpws_getWSDLfromTemplate() {
	$wsdl = file_get_contents(WPWS_WSDL_TEMPLATE);
	return str_replace("{RELATIVE_PATH}", wpws_getHome(), $wsdl);
}

function wpws_getHome() {
	return (defined("WP_HOME")) ? WP_HOME : get_option("home", "");
}

function wpws_getBasedir() {
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

?>