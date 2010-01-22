<?php

/*
Plugin Name: WordPress Web Service
Plugin URI: http://code.google.com/p/wordpress-web-service/
Description: WordPress Web Service is used to access WordPress ressources via WSDL and SOAP. The Service Port is available on http://yoursite.com/blog/index.php/wpws whereas the Web Service Descriptor can be found by adding ?wsdl (http://yoursite.com/blog/index.php/wpws?wsdl). If you type http://yoursite.com/blog/index.php/wpws directly in your browser you get some useful information (plugin must be turned on in any case).
Version: 0.1.0
Author: Bj&ouml;rn Kahlert
Author URI: http://bkahlert.com/
*/

require_once(dirname(__FILE__) . "/wp-access.php");

/**
 * Catches index.php/wpws requests, stops further execution by WordPress
 * and handles the request depending on the request type.
 *
 * The are 3 types of treatment:
 * 1) User hasn't requested a WSDL file nor a SOAP operation => HTML output of general information
 * 2) User has requested requested WSDL file => deliveration by SoapServer instance
 * 3) User has submited a SOAP operation request => treatment by SoapServer instance
 *
 * Because the caller needs to know where he can access the service
 * the correct blog address needs to be specified in the service port of the WSDL.
 * For that reason only a template WSDL file exists. Should the script detect that
 * a customized WSDL with the correct address doesn't exist it creates it
 * by making a copy of the template WSDL and by replacing the address placeholder.
 * Should you ever need to reallocate the Blog simply delete the wpws.wsdl but provoce it's recreation.
 */
function wpws_handle_request($wp) {	
	// Look for the magic /wpws string in the $_SERVER variable
	$wpws_found = false;
	$wsdl_requested = false;
	foreach($_SERVER as $val) {
		if(strlen($val) >= 5 && substr($val, 0, 5) == "/wpws") {
			$wpws_found = true;
			if(isset($_SERVER["QUERY_STRING"]) && strpos($_SERVER["QUERY_STRING"], "?wsdl") !== false) $wsdl_requested = true;
			break;
		}
	}
	
	if($wpws_found) {		
		// make sure the QUERY_STRING is correctly set to ?wsdl so the SoapServer instance delivers the wsdl file
		if($wsdl_requested) {
			header("Content-type: text/xml");
			echo wpws_getWSDLfromTemplate();
			exit;
		} else if(!isset($_SERVER["HTTP_SOAPACTION"])) {
			// client hasn't requested a SOAP operation
			// return HTML page
			include(dirname(__FILE__) . "/WPWS.html");
		} else {
			// Create a customized WSDL file on disk
			// so the SoapServer can take this copy to load from.
			wpws_createWSDL();
			 
			// SoapServer handles both: deliveration of the requested WSDL file
			// and execution of SOAP operations
			header("Content-type: text/xml");
			require_once(dirname(__FILE__) . "/wp-soap.php");
			$server = new SoapServer(WPWS_WSDL, array("cache_wsdl" => WSDL_CACHE_NONE));
			$server->setClass("wp_WebService");
			$server->handle();
		}
		exit;
	}
	// no wpws-request, go on with WordPress execution
}

// creates a customized WSDL on plugin activation
register_activation_hook(__FILE__, 'wpws_createWSDL');

// checks whether the request should be handled by WPWS
add_action("parse_request", "wpws_handle_request");

?>
