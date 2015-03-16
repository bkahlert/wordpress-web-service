## WordPress Web Service (WPWS) is used to access [WordPress](http://wordpress.org/) resources via WSDL and SOAP. ##
It allows you to connect to WSDL enabled software like Adobe Flex / Flash Builder,<br>
Microsoft Visual Studio, PHP, J2EE, etc. to WordPress resources like posts and pages.<br>
<br>
<a href='http://s.wordpress.org/extend/plugins/wordpress-web-service/screenshot-4.png?r=198142'>http://s.wordpress.org/extend/plugins/wordpress-web-service/screenshot-4.png?r=198142</a>

<h2>Prerequisites</h2>
<ul><li>PHP5<br>
</li><li>PHP SOAP Support</li></ul>

<h2>Installation</h2>
<ol><li>Install plugin by ...<br>
<ol><li>... using your WordPress admin panel to search for the plugin <b>WordPress Web Service</b> and clicking <b>install</b>
</li><li>... or by uploading the manually downloaded and extracted <code>wordpress-web-service.zip</code> to your <code>/wp-content/plugins/</code> folder<br>
</li></ol></li><li>Activate the plugin through the "Plugins" menu in WordPress<br>
</li><li>Open <code>http://yoursite.com/blog/index.php</code><b><code>/wpws</code></b> in your webbrowser to test the plugin.</li></ol>

<a href='http://s.wordpress.org/extend/plugins/wordpress-web-service/screenshot-1.png?r=198142'>http://s.wordpress.org/extend/plugins/wordpress-web-service/screenshot-1.png?r=198142</a>

<h3>Limitations</h3>
<ul><li>No access control yet! All your WordPress' resources can be accessed from anyone who knows WSDL.<br>
</li><li>Until now only pages and posts can be accessed<br>BUT: the <code>get_posts</code>/<code>get_pages</code>' <code>args</code> argument is supported and can be used to easily get a lot of functionality<br>
</li><li><b>Developers willing to help development on WPWS are greatly welcomed!</b></li></ul>

<h3>Extras</h3>
<ul><li>In addition to posts and pages also galleries can be accessed. A gallery is a post/page treated as a gallery with an <code>images</code> attribute containing all images that are contained in the post/page<br>
</li><li>An image has the attributes <code>title</code>, <code>description</code>, <code>thumbUrl</code> and <code>url</code> where all information are extracted from the posts/pages html content<br>
</li><li>The url points to a php file that resizes dynamically a requested image so the user only gets the dimensions he needs.</li></ul>

<h3>Possible problems</h3>
Normally no customization is required in order to get the plugin running.<br>
<br>
There's only one exception:<br>
Because the SoapServer resides in the WordPress installation the caller<br>
needs to know the exact blogs url. This url must be set in WSDL file.<br>
<br>
The WordPress Web Service Plugin creates a customized copy of the file <code>wpws.template.wsdl</code> on its own.<br>
Should the script doesn't have the sufficient rights to create / write to the file <code>wpws.wsdl</code> in the plugin's folder<br>
the SoapServer can't access the customized WSDL.<br>
<br>
<b>Follow the 5 steps to create your <code>wpws.wsdl</code> manually:</b>
<ol><li>Use a FTP client to connect to your WordPress installation<br>
</li><li>Go to the directory <code>wp-content</code> then to <code>plugins</code> and finally open the folder <code>wordpress-web-service</code>
</li><li>Make a copy of <code>wpws.template.wsdl</code> and name it <code>wpws.wsdl</code>
</li><li>Open the file <code>wpws.wsdl</code> in a text editor<br>
</li><li>Scroll to the end of the file and replace <code>%{BLOG_PATH}</code> by your blog's url (e.g. <code>http://yoursite.com/blog</code>)