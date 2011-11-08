<?php

require_once(dirname(__FILE__) . "/wpws-valueobjects.php");

/**
 * This class is the web service itself.
 * A PHP SoapServer instance instanciates a wp_WebService object and calls
 * the methods as specified in the WSDL file and requested as in the client SOAP request
 * on that wp_WebService object.
 */
class wp_WebService {
	/*** POSTS ***/
	
	/**
	 * Returns all posts
	 * @param args is the paramater as used by the WordPress get_posts method 
	 * @return wpws_Post[]
	 */
	function getPosts($args = null) {
		if($args != null) $args = mysql_real_escape_string($args);
		
		$posts = get_posts($args);
		$wpws_posts = array();
		foreach($posts as $post) {
			$wpws_posts[] = wpws_Post::convert($post);
		}
		return $wpws_posts;
	 }
	 
	 /**
	 * Returns a post
	 * @return wpws_Post
	 */
	function getPost($postId) {
		$postId = is_numeric($postId) ? intval($postId) : 0;
		
		$post = get_post($postId, OBJECT);
		return wpws_Post::convert($post);
	 }
	 
	 
	 
	/*** PAGES ***/
	 
	/**
	 * Returns all pages
	 * @param args is the paramater as used by the WordPress get_pages method 
	 * @return wpws_Page[]
	 */
	function getPages($args = null) {
		if($args != null) $args = mysql_real_escape_string($args);
		
		$pages = get_pages($args);
		$wpws_pages = array();
		foreach($pages as $page) {
			$wpws_pages[] = wpws_Page::convert($page);
		}
		return $wpws_pages;
	 }
	 
	 /**
	 * Returns a page
	 * @return wpws_Page
	 */
	function getPage($pageId) {
		$pageId = is_numeric($pageId) ? intval($pageId) : 0;
		
		$page = get_page($pageId, OBJECT);
		return wpws_Page::convert($page);
	 }
	 
	 
	 
	/*** GALLERIES ***/
	
	/**
	 * Returns all galleries (= pages treated as galleries).
	 * Each gallery has the attribute subGalleries in which an array of all child galleries is stored.
	 * Note that to minimize network traffic only the meta data and the main image are provided while
	 * the images array is always empty.
	 * @return wpws_Gallery[]
	 */
	function getGalleryHierarchy($args = null) {
		if($args != null) $args = mysql_real_escape_string($args);
		
		// Note that the user provided $args is the 2nd operator which enforced
		// the parameters defined in the array
		$r = wp_parse_args($args, array("hierarchical" => 1, "sort_column" => "menu_order"));
		
		$wpws_pages = wp_WebService::getPages($r);
		
		$wpws_galleries = array();
		$breadcrumb = array();
		foreach($wpws_pages as $wpws_page) {
			$wpws_images = wp_WebService::getImages($wpws_page->id, false);
			$mainImage = (count($wpws_images) > 0) ? $wpws_images[0] : null;
			
			$wpws_gallery = new wpws_Gallery(
								$wpws_page->id,
								$wpws_page->parentId,
								$wpws_page->title,
								"",
								$mainImage,
								$wpws_images,
								array());
			
			$last_gallery = array_pop($breadcrumb);
			if($last_gallery == null && $wpws_gallery->parentId == 0) {
				// we should be in the top level and the gallery's parent id also represents the top level
				$wpws_galleries[] = $wpws_gallery;
				$breadcrumb[] = $wpws_gallery;
			} else if($last_gallery->id == $wpws_gallery->parentId) {
				// the last generated gallery is the parent gallery of the current gallery
				// this means we need to add the current gallery to the subGalleries attribute of it's parent
				// we also need to put both parent- and subGallery on the $breadcrumb
				$last_gallery->subGalleries[] = $wpws_gallery;
				$breadcrumb[] = $last_gallery;
				$breadcrumb[] = $wpws_gallery;
			} else if($last_gallery->parentId == $wpws_gallery->parentId) {
				// the last generated gallery is on the same level as the current gallery
				// this means we need to add the current gallery to the last' parent gallery
				// the current gallery will be placed on the $breadcrumb instead of the last gallery
				$parent_gallery = array_pop($breadcrumb);
				if($parent_gallery == null) {
					$wpws_galleries[] = $wpws_gallery;
					$breadcrumb[] = $wpws_gallery;
				} else {
					$parent_gallery->subGalleries[] = $wpws_gallery;
					$breadcrumb[] = $parent_gallery;
					$breadcrumb[] = $wpws_gallery;
				}
			} else {
				// the last gallery was also the last child of it's parent gallery
				// we need to remove galleries from $breadcrumb till we find the current gallery's parent
				$parent_gallery = null;
				while(($parent_gallery = array_pop($breadcrumb)) != null) {
					if($parent_gallery->id == $wpws_gallery->parentId) {
						$parent_gallery->subGalleries[] = $wpws_gallery;
						$breadcrumb[] = $parent_gallery;
						$breadcrumb[] = $wpws_gallery;
						break;
					}
				}
				
				// in this case the current gallery has no parent gallery
				if($parent_gallery == null) {
					$wpws_galleries[] = $wpws_gallery;
					$breadcrumb[] = $wpws_gallery;
				}
			}
		}
		return $wpws_galleries;
	}
	
	/**
	 * Returns all galleries (= pages treated as galleries).
	 * Each gallery has the attribute subGalleries which is an empty array
	 * since the hierarchy is not constructed.
	 * Note that to minimize network traffic only the meta data and the main image are provided while
	 * the images array is always empty.
	 * @return wpws_Gallery[]
	 */
	function getGalleries($args = null) {
		if($args != null) $args = mysql_real_escape_string($args);
		
		// Note that the user provided $args is the 2nd operator which enforced
		// the parameters defined in the array
		$wpws_pages = wp_WebService::getPages($args);
		
		$wpws_galleries = array();
		foreach($wpws_pages as $wpws_page) {
			$wpws_images = wp_WebService::getImages($wpws_page->id, false);
			$mainImage = (count($wpws_images) > 0) ? $wpws_images[0] : null;
			
			$wpws_galleries[] = new wpws_Gallery(
								$wpws_page->id,
								$wpws_page->parentId,
								$wpws_page->title,
								"",
								$mainImage,
								$wpws_images,
								null);
		}
		return $wpws_galleries;
	}
	
	/**
	 * Returns a Gallery
	 * @param int id of the page to be returned as a Gallery
	 * @return wpws_Gallery
	 */
	function getGallery($galleryId) {
		$galleryId = is_numeric($galleryId) ? intval($galleryId) : 0;
		
		$wpws_page = wp_WebService::getPage($galleryId);
		$wpws_images = wp_WebService::getImages($galleryId, false);
		
		// Take the first image as the main image
		$mainImage = (count($wpws_images) > 0) ? $wpws_images[0] : null;
		
		$wpws_gallery = new wpws_Gallery(
						$wpws_page->id,
						$wpws_page->parentId,
						$wpws_page->title,
						"",
						$mainImage,
						$wpws_images,
						null);
		return $wpws_gallery;
	}
	
	/**
	  * Extracts all <img>-Tage contained in a page determined by the $galleryId
	  * Useful for paging are the parameters $start and $end. Both are optional and
	  * if not supplied will return all Images.
	  *
	  * @param int id of the page to be returned as a Gallery
	  * @param bool true if images of sub galleries should also be returned
	  * @param int index of the first image to return
	  * @param int index of the the last image to return
	  * @return wpws_Image[]
	  */
	function getImages($galleryId, $includeSubGalleries, $start = 1, $end = null) {
		$galleryId = is_numeric($galleryId) ? intval($galleryId) : 0;
		$includeSubGalleries = $includeSubGalleries ? true : false;
		$start = is_numeric($start) ? intval($start) : null;
		$end = is_numeric($end) ? intval($end) : null;
		
		$wpws_page = wp_WebService::getPage($galleryId);
		$html = $wpws_page->content;
		$xml = new SimpleXMLElement('<xml>' . utf8_encode(html_entity_decode($html)) . '</xml>');
		
		// Construct XPath query
		$q = "/descendant-or-self::img";
		$predicate = array();
		if($start != null) $predicate[] = "position() >= $start";
		if($end != null) $predicate[] = "position() <= $end";	
		if(count($predicate) > 0) $q .= "[" . implode(" and ", $predicate) . "]";
		$xml_images = $xml->xpath($q);
		
		$wpws_images = array();
		foreach($xml_images as $xml_image) {
			// Build original / full size image url
			// and pass the url to the resize script
			list(, $uri) = explode(WP_UPLOAD_DIR, strval($xml_image["src"]));	// http://abc.de/blog/, 2010/xyz/img-120x120.jpg
			$originalUri = preg_replace("~-\d+x\d+~", "", $uri, 1); 			// 						2010/xyz/img.jpg
			$resizeableUrl = wpws_getPluginUrl() . "/resize_image.php?src=" . $originalUri . "&width=%{WIDTH}&height=%{HEIGHT}&quality=%{QUALITY}";
			
			$file = wpws_getBasedir() . WP_UPLOAD_DIR . $uri;
			list($width, $height) = getimagesize($file);
			
			$originalFile = wpws_getBasedir() . WP_UPLOAD_DIR . $originalUri;
			list($maxResizeableWidth, $maxResizeableHeight) = getimagesize($originalFile);

			$wpws_images[] = new wpws_Image(
								$galleryId,
								strval($xml_image["src"]),
								$width,
								$height,
								$resizeableUrl,
								$maxResizeableWidth,
								$maxResizeableHeight,
								strval($xml_image["title"]),
								strval($xml_image["alt"]));
		}
		
		if($includeSubGalleries) {
			$wpws_subPages = wp_WebService::getPages("child_of=" . $galleryId);
			foreach($wpws_subPages as $wpws_subPage) {
				$wpws_subImages = wp_WebService::getImages($wpws_subPage->id);
				$wpws_images = array_merge($wpws_images, $wpws_subImages);
			}
		}
		
		return $wpws_images;
	}
}

?>