<?php

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
}

// identically to wpws_Page
class wpws_Page extends wpws_Post { }

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
		$this->mainImage = $mainImage;
		$this->description = $description;
		$this->images = $images;
		$this->subGalleries = $subGalleries;
	}
}

class wpws_Image {
	public $url;
	public $thumbUrl;
	public $title;
	public $description;
	
	function __construct($url, $thumbUrl, $title, $description) {
		$this->url = $url;
		$this->thumbUrl = $thumbUrl;
		$this->title = $title;
		$this->description = $description;
	}
}

?>