<?php

function wpws_getSoapClientUrl() {
	return "http://www.soapclient.com/soapclient?template=/clientform.html&fn=soapform&SoapTemplate=none&SoapWSDL=" .
	urlencode(wpws_getWsdlUrl());
}

$soapClientUrl = wpws_getSoapClientUrl();
$formattedSoapClientUrl = "";
$numCharPerBreak = 30;
for($i=0; $i<strlen($soapClientUrl); $i+=$numCharPerBreak) $formattedSoapClientUrl .= substr($soapClientUrl, $i, $numCharPerBreak) . " ";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>WordPress Web Service</title>
<link rel="stylesheet" type="text/css" href="<?php echo wpws_getPluginBaseDir(); ?>/wpws-index.css"/>
</head>
<body>
<h1><a href="https://code.google.com/p/wordpress-web-service" target="_blank"><img src="<?php echo wpws_getPluginBaseDir(); ?>/wpws.png" alt="WordPress Web Service" width="265" height="73" border="0" /></a></h1>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><p>Welcome to your WordPress Web Service (WPWS) plugin!</p>
		<?php if(wpws_WSDLcustomized()) { ?>
			<p>You have successfully installed WPWS and you're ready to connect <br />
				your WSDL enabled application to your WordPress installation.<br />
			Your WSDL url is:<br />
<a href="<?php echo wpws_getWsdlUrl(); ?>" target="_blank"><?php echo wpws_getWsdlUrl(); ?></a>.</p>
			<p>On the right you can see the Generic SOAP Client which has already loaded your WSDL file.<br />
				You may want to check for proper operation of the plugin before using it.</p>
		<?php } else { ?>
			<p>You have nearly completed the installation of your WPWS plugin. <br />
			<strong>Unfortunately your WSDL file could not be created automatically because of missing write rights.</strong><br />
			But that's no problem! Follow the 5 steps to create one manually:</p>
			<ol>
				<li>Use a FTP client to connect to your WordPress installation</li>
				<li>Go to the directory <code>wp-content</code> then to <code>plugins</code> and finally open the folder <code>wpws</code></li>
				<li>Make a copy of <code>wpws.template.wsdl</code> and name it <code>wpws.wsdl</code></li>
				<li>Open the file <code>wpws.wsdl</code> in a text editor</li>
				<li>Scroll to the end of the file and replace <code>%{BLOG_PATH}</code> by <code><?php echo wpws_getBlogUrl(); ?></code></li>
			</ol>
			<p>Revisit this page after having created the customized <code>wpws.wsdl</code> file.</p>
		<?php } ?>
			<p>See <a href="https://code.google.com/p/wordpress-web-service" target="_blank">WordPress Web Service Plugin</a> for more information.</p></td>
		<td>
		<?php if(wpws_WSDLcustomized()) { ?>
			<h2><a href="http://www.soapclient.com/soaptest.html" target="_blank">Generic SOAP Client</a></h2>
			<iframe src="<?php echo $soapClientUrl; ?>" width="100%" height="400"></iframe>
			<p class="source"><a href="<?php echo $soapClientUrl; ?>" target="_blank"><?php echo $formattedSoapClientUrl; ?></a></p>
		<?php } else { ?>
			&nbsp;
		<?php } ?>
		</td>
	</tr>
</table>
</body>
</html>
