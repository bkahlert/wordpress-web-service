=== WordPress Web Service ===
Contributors: bkahlert
Tags: wpws, wordpress, wsdl, webservices, web, service, web service, webservice, soap, rpc, flex, flash, flex4, flex4beta, flash builder
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: trunk

WordPress Web Service is used to access WordPress resources via WSDL and SOAP.

== Description ==

WordPress Web Service (WPWS) is used to access WordPress resources via WSDL and SOAP.
It allows you to connect WSDL enabled software like Adobe Flex / Flash Builder,
Microsoft Visual Studio, PHP, J2EE, etc. to WordPress resources like posts
and pages.

WPWS gives you also the opportunity to program alternative (graphical) interfaces
for your WordPress installation.

Furthermore alternative interpretations of WordPress resources are possible.
For example you can interpret a post/page as a gallery; a datatype consisting of
all included images with the corresponding attributes.

After installation simply open http://yoursite.com/blog/index.php/wpws to test your plugin.
That is to add "/wpws" to the index.php part in your WordPress' url.

For more information visit:
[WordPress Web Service](http://code.google.com/p/wordpress-web-service/)

Developers willing to help development on WPWS are greatly welcomed.

== Installation ==

1. Upload `wordpress-web-service` directory to the `/wp-content/plugins/` folder
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Open http://yoursite.com/blog/index.php/wpws in your webbrowser to test the plugin.

== Screenshots ==

1. Page http://yoursite.com/blog/index.php/wpws opened in a webbrowser 
2. Data Connection Wizard with WSDL selected
3. Entering the site's WSDL url as shown on the first screenshot
4. Successfully parsed WSDL file with all currently supported methods and datatypes
5. Successfully generated proxy classes for easy access of WordPress resources

== Changelog ==

= 0.2.2 =
* Resolves potential security vulnerabilities in resize-image.php
* Resolves potential security vulnerabilities in the web service (wpws-soap.php)

= 0.2.1 =
* Change of namespace from *.wpws.bkahlert.com to *.wpws.develop.bkahlert.com
* Issues with pages/posts that contain html entities and are processed by getGallery/getImages resolved
* Issue with some WordPress installation that couldn't retrieve any SOAP response resolved (changed SOAP address from .../index.php/wpws to .../index.php?/wpws

= 0.2.0 =
* Incompatibility with Flash Builder resolved
* Compatibility with WordPress 3.x

= 0.1.4 =
* A writeable cache directory is no more needed in order to dynamically resize images and to return the correct content length to the caller.
* The `resizeableUrl` attribute of the `Image` datatype contains now 3 placeholders, namely `%{WIDTH}`, `%{HEIGHT}` and `%{QUALITY}`.
* The `Image` type now contains the property `parentId`
* Method `getImages` now allows to return not only images of one resource but also of all child resources

= 0.1.3 =
* Properties `width`, `height`, `maxResizeableWidth` and `maxResizeableHeight` added to Image type
* Minor bugs removed

= 0.1.2 =
* Improved instructions on http://yoursite.com/blog/index.php**/wpws**
* Cleaner directory structure

= 0.1.1 =
* Improved WSDL1.1 compability
* WSDL file size optimization

= 0.1.0 =
* Initial version