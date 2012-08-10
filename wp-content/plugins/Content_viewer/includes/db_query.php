<?php
/**
 * Get limit 3 posts from a db
 * select post_title from wp_posts where post_type = 'post' and post_status = 'publish' order by id desc limit 3;
 */

function get_recently_posts($num,$category_id){
	global $wpdb;
	if($category_id==NULL)
	{$query = "SELECT id,post_title,post_content,guid FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 3;";}
	else{
		$query = "SELECT id,post_title,post_content,guid FROM {$wpdb->posts} p
		LEFT OUTER JOIN {$wpdb->term_relationships} r ON r.object_id = p.ID
		WHERE p.post_status = 'publish' AND p.post_type = 'post' AND r.term_taxonomy_id= ".$category_id."
		ORDER BY p.post_date DESC LIMIT ".$num .";";	
	}
	$result = $wpdb->get_results($query);

	return $result;
}

function get_first_img($id){
	global $wpdb;
	$query = "SELECT post_content FROM {$wpdb->posts} WHERE id = '".$id ."' AND post_status='publish' AND post_type = 'post';";
	$result = $wpdb->get_results($query);
	return $result;
}

function get_all_categorys(){
	global $wpdb;
	$query = "SELECT {$wpdb->terms}.term_id, name FROM {$wpdb->terms}
	LEFT JOIN {$wpdb->term_taxonomy}
	ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
	WHERE {$wpdb->term_taxonomy}.taxonomy = 'category'";
	$result = $wpdb->get_results($query);
	return $result;
}

function set_category_dropdown() {
	$categories = get_all_categorys();
	$i = 0;
	foreach($categories as $category) {
		$select[$i]['category_name'] = $category->name;
		$select[$i]['category_id'] = $category->term_id;

		$i++;
	}
	return $select;
}

/**
 * Get all
 */

?>