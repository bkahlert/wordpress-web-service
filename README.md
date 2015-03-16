# WordPress Web Service (WPWS) is used to access [WordPress](http://wordpress.org/) resources via WSDL and SOAP.

It allows you to connect to WSDL enabled software like Adobe Flex / Flash Builder,
Microsoft Visual Studio, PHP, J2EE, etc. to WordPress resources like posts and pages.

<img src="http://s.wordpress.org/extend/plugins/wordpress-web-service/screenshot-4.png?r=198142"/>

## Prerequisites
- PHP5
- PHP SOAP Support

## Installation
1. Install plugin by ...
  1. ... using your WordPress admin panel to search for the plugin WordPress Web Service and clicking install
  2. ... or by uploading the manually downloaded and extracted wordpress-web-service.zip to your /wp-content/plugins/ folder. The plugin can also be downloaded at https://wordpress.org/plugins/wordpress-web-service/.
2. Activate the plugin through the "Plugins" menu in WordPress
3. Open http://yoursite.com/blog/index.php/wpws in your webbrowser to test the plugin.

<img src="http://s.wordpress.org/extend/plugins/wordpress-web-service/screenshot-1.png?r=198142"/>

## Limitations
- No access control yet! All your WordPress' resources can be accessed from anyone who knows WSDL.
- Until now only pages and posts can be accessed. BUT: the "get_posts" resp. "get_pages" args argument is supported and can be used to easily get a lot of functionality
- **Developers willing to help development on WPWS are greatly appreciated!**

## Extras
- In addition to posts and pages also galleries can be accessed. A gallery is a post/page treated as a gallery with an images attribute containing all images that are contained in the post/page
- An image has the attributes title, description, thumbUrl and url where all information are extracted from the posts/pages html content
- The url points to a php file that resizes dynamically a requested image so the user only gets the dimensions he needs.

## Possible problems
Normally no customization is required in order to get the plugin running.

There's only one exception:
Because the SoapServer resides in the WordPress installation the caller
needs to know the exact blogs url. This url must be set in WSDL file.

The WordPress Web Service Plugin creates a customized copy of the file "wpws.template.wsdl" on its own.
Should the script doesn't have the sufficient rights to create / write to the file "wpws.wsdl" in the plugin's folder
the SoapServer can't access the customized WSDL.

### Follow the 5 steps to create your "wpws.wsdl" manually:
1. Use a FTP client to connect to your WordPress installation
2. Go to the directory "wp-content" then to plugins and finally open the folder "wordpress-web-service"
3. Make a copy of "wpws.template.wsdl" and name it wpws.wsdl
4. Open the file "wpws.wsdl" in a text editor
5. Scroll to the end of the file and replace "%{BLOG_PATH}" by your blog's url (e.g. "http://yoursite.com/blog")
