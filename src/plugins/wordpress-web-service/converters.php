<?php

/**
 * This class defines methods that convert WordPress datatypes to
 * those needed for the creation of SOAP datatyped objects needed by the PHP SoapServer class.
 */
 
 /**
 * Converts a post retrieved trough the WordPress method get_post
 * to a wpws_Post object.
 * @return wpws_Post
 */
function wpws_convert_Post($post) {
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

/**
 * Converts a page retrieved trough the WordPress method get_page
 * to a wpws_Page object.
 * @return wpws_Page
 */
function wpws_convert_Page($page) {
	if(!$page) return null;
	else {
		$wpws_page = new wpws_Page(
				intval($page->ID),
				$page->post_author,
				$page->post_date,
				$page->post_date_gmt,
				$page->post_content,
				$page->post_title,
				$page->post_excerpt,
				$page->post_status,
				$page->comment_status,
				$page->ping_status,
				$page->post_password,
				$page->post_name,
				$page->to_ping,
				$page->pinged,
				$page->post_modified,
				$page->post_modified_gmt,
				$page->post_content_filtered,
				intval($page->post_parent),
				$page->guid,
				intval($page->menu_order),
				$page->post_type,
				$page->post_mime_type,
				$page->comment_count,
				$page->filter);
		return $wpws_page;
	}
}