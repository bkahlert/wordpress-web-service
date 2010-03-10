<?php

/* SOAP compatible Post definition */
class wpws_Post {
	public $id;
	public $author;
	public $date;
	public $dateGmt;
	public $content;
	public $title;
	public $excerpt;
	public $status;
	public $commentStatus;
	public $pingStatus;
	public $password;
	public $name;
	public $toPing;
	public $pinged;
	public $modified;
	public $modifiedGmt;
	public $contentFiltered;
	public $parentId;
	public $guid;
	public $menuOrder;
	public $type;
	public $mimeType;
	public $commentCount;
	public $filter;
 
	function __construct($id, $author, $date, $dateGmt,
						 $content, $title, $excerpt,
						 $status, $commentStatus, $pingStatus,
						 $password, $name, $toPing, $pinged,
						 $modified, $modifiedGmt, $contentFiltered,
						 $parentId, $guid, $menuOrder, $type, $mimeType,
						 $commentCount, $filter) {
		$this->id = $id;
		$this->author = $author;
		$this->date = $date;
		$this->dateGmt = $dateGmt;
		$this->content = $content;
		$this->title = $title;
		$this->excerpt = $excerpt;
		$this->status = $status;
		$this->commentStatus = $commentStatus;
		$this->pingStatus = $pingStatus;
		$this->password = $password;
		$this->name = $name;
		$this->toPing = $toPing;
		$this->pinged = $pinged;
		$this->modified = $modified;
		$this->modifiedGmt = $modifiedGmt;
		$this->contentFiltered = $contentFiltered;
		$this->parentId = $parentId;
		$this->guid = $guid;
		$this->menuOrder = $menuOrder;
		$this->type = $type;
		$this->mimeType = $mimeType;
		$this->commentCount = $commentCount;
		$this->filter = $filter;
	}
	
	/**
	 * Converts a post retrieved trough the WordPress method get_post
	 * to a wpws_Post object.
	 * @return wpws_Post
	 */
	function convert($post) {
		if(!$post) return null;
		else {
			$wpws_post = new wpws_Post(
					intval($post->ID),
					$post->post_author,
					$post->post_date,
					$post->post_date_gmt,
					$post->post_content,
					$post->post_title,
					$post->post_excerpt,
					$post->post_status,
					$post->comment_status,
					$post->ping_status,
					$post->post_password,
					$post->post_name,
					$post->to_ping,
					$post->pinged,
					$post->post_modified,
					$post->post_modified_gmt,
					$post->post_content_filtered,
					intval($post->post_parent),
					$post->guid,
					intval($post->menu_order),
					$post->post_type,
					$post->post_mime_type,
					$post->comment_count,
					$post->filter);
			return $wpws_post;
		}
	}
}

/* SOAP compatible Page definition */
class wpws_Page extends wpws_Post {
	/**
	 * Converts a page retrieved trough the WordPress method get_page
	 * to a wpws_Page object.
	 * @return wpws_Page
	 */
	function convert($page) {
		return parent::convert($page);
	}
}

/* SOAP compatible Gallery definition */
class wpws_Gallery {
	public $id;
	public $parentId;
	public $title;
	public $description;
	public $mainImage;
	public $images;
	public $subGalleries;
	
	function __construct($id, $parentId, $title, $description, $mainImage, $images, $subGalleries) {
		$this->id = $id;
		$this->parentId = $parentId;
		$this->title = $title;
		$this->description = $description;
		$this->mainImage = $mainImage;		
		$this->images = $images;
		$this->subGalleries = $subGalleries;
	}
}

/* SOAP compatible Image definition */
class wpws_Image {
	public $parentId;
	public $url;
	public $width;
	public $height;
	public $resizeableUrl;
	public $maxResizeableWidth;
	public $maxResizeableHeight;
	public $title;
	public $description;
	
	function __construct($parentId, $url, $width, $height, $resizeableUrl, $maxResizeableWidth, $maxResizeableHeight, $title, $description) {
		$this->parentId = $parentId;
		$this->url = $url;
		$this->width = $width;
		$this->height = $height;
		$this->resizeableUrl = $resizeableUrl;
		$this->maxResizeableWidth = $maxResizeableWidth;
		$this->maxResizeableHeight = $maxResizeableHeight;
		$this->title = $title;
		$this->description = $description;
	}
}

?>