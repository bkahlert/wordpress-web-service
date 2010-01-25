<?php

require_once(dirname(__FILE__) . "/valueObjects.php");
require_once(dirname(__FILE__) . "/converters.php");

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
		$posts = get_posts($args);
		$wpws_posts = array();
		foreach($posts as $post) {
			$wpws_posts[] = wpws_convert_Post($post);
		}
		return $wpws_posts;
	 }
	 
	 /**
	 * Returns a post
	 * @return wpws_Post
	 */
	function getPost($postId) {
		$post = get_post($postId, OBJECT);
		return wpws_convert_Post($post);
	 }
	 
	 
	 
	/*** PAGES ***/
	 
	/**
	 * Returns all pages
	 * @param args is the paramater as used by the WordPress get_pages method 
	 * @return wpws_Page[]
	 */
	function getPages($args = null) {
		$pages = get_pages($args);
		$wpws_pages = array();
		foreach($pages as $page) {
			$wpws_pages[] = wpws_convert_Page($page);
		}
		return $wpws_pages;
	 }
	 
	 /**
	 * Returns a page
	 * @return wpws_Page
	 */
	function getPage($pageId) {
		$page = get_page($pageId, OBJECT);
		return wpws_convert_Page($page);
	 }
	 
	 
	 
	/*** GALLERIES ***/
	
	
	function getGalleryHierarchy($args = null) {
		// Note that the user provided $args is the 2nd operator which enforced
		// the parameters defined in the array
		$r = wp_parse_args(array("hierarchical" => 1, "sort_column" => "menu_order"), $args);
		
		$wpws_pages = wp_WebService::getPages($r);
		
		$wpws_galleries = array();
		$breadcrumb = array();
		foreach($wpws_pages as $wpws_page) {
			$mainImage = wp_WebService::getImages($wpws_page->id, 1, 1);
			$mainImage = (count($mainImage) < 1) ? null : $mainImage[0];
			
			$wpws_gallery = new wpws_Gallery(
								$wpws_page->id,
								$wpws_page->parentId,
								$wpws_page->title,
								"",
								$mainImage,
								null,
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
		// Note that the user provided $args is the 2nd operator which enforced
		// the parameters defined in the array
		$wpws_pages = wp_WebService::getPages($args);
		
		$wpws_galleries = array();
		foreach($wpws_pages as $wpws_page) {
			$mainImage = wp_WebService::getImages($wpws_page->id, 1, 1);
			$mainImage = (count($mainImage) < 1) ? null : $mainImage[0];
			
			$wpws_galleries[] = new wpws_Gallery(
								$wpws_page->id,
								$wpws_page->parentId,
								$wpws_page->title,
								"",
								$mainImage,
								null,
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
		$wpws_page = wp_WebService::getPage($galleryId);
		$wpws_images = wp_WebService::getImages($galleryId);
		
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
	  * @param int index of the first image to return
	  * @param int index of the the last image to return
	  * @return wpws_Image[]
	  */
	function getImages($galleryId, $start = 1, $end = null) {
		$wpws_page = wp_WebService::getPage($galleryId);
		$html = $wpws_page->content;
		$xml = new SimpleXMLElement("<articial_root>" . $html . "</articial_root>");
		
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
			$url = explode("wp-content/uploads", strval($xml_image["src"])); // http://abc.de/blog/, 2010/xyz/img-120x120.jpg
			$url = preg_replace("~-\d+x\d+~", "", $url[1], 1); // 2010/xyz/img.jpg
			$resizeUrl = wpws_getHome() . "/wp-content/plugins/wpws/resizeImage.php?src=" . $url . "&width=800";

			$wpws_images[] = new wpws_Image(
								$resizeUrl,
								strval($xml_image["src"]),
								strval($xml_image["title"]),
								strval($xml_image["alt"]));
		}
		return $wpws_images;
	}
}

?>