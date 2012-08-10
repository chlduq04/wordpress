<?php

/**
 * Get all posts or all posts from a category
 */
function spp_get_all_posts($category = NULL) {
  global $wpdb;
  $query =
    "SELECT ID, post_title, post_content, post_date, post_status, guid, term_id
     FROM {$wpdb->posts}
     LEFT JOIN {$wpdb->term_relationships}
     ON object_id = ID
     LEFT JOIN {$wpdb->term_taxonomy}
     ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
     WHERE post_status = 'publish'";
     if($category != NULL) {
       $query .= " AND {$wpdb->term_taxonomy}.term_id = " . $category;
     }
     $query .= " AND post_type = 'post'
     GROUP BY ID
     ORDER BY post_date
     ;";
  $data = $wpdb->get_results($query);
  return $data;
}
/*select post_title from wp_posts left join wp_term_relationships on object_id = ID left join wp_term_taxonomy on wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id where post_status='publish';*/
/*all activate post : select post_title from wp_posts where post_type = 'post' and post_status = 'publish';*/
/*least recently activate post : select post_title from wp_posts left join wp_term_relationships on object_id = ID where ID = (select max(ID) from wp_posts where post_type = 'post' and post_status = 'publish') limit 1;*/
/*all category : select name from wp_terms left join wp_term_taxonomy on wp_term_taxonomy.term_id = wp_terms.term_id where wp_term_taxonomy.taxonomy='category';*/
/*category in at least on post : select name from wp_terms left join wp_term_taxonomy on wp_term_taxonomy.term_id = wp_terms.term_id where wp_term_taxonomy.taxonomy='category'and wp_term_taxonomy.count>0*/
/**
 * Select a specific post or the latest post from a category
 */
function spp_get_post($type, $selector = NULL) {
  global $wpdb;
  if($selector == NULL) {
    $data = $wpdb->get_results(
      "SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid
       FROM {$wpdb->posts}
       LEFT JOIN {$wpdb->term_relationships}
       ON object_id = ID
       WHERE ID = (SELECT max(ID) FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish')
       LIMIT 1;"
    );
  } else {
    switch($type) {
      case 'category':
        $data = $wpdb->get_results(
          "SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid, term_id
           FROM {$wpdb->posts}
           LEFT JOIN {$wpdb->term_relationships}
           ON object_id = ID
           LEFT JOIN {$wpdb->term_taxonomy}
           ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
           WHERE term_id = $selector
           AND post_status = 'publish'
           ORDER BY post_date
           DESC LIMIT 1;"

        );
        break;

      case 'post':
        $data = $wpdb->get_results(
          "SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid
           FROM {$wpdb->posts}
           LEFT JOIN {$wpdb->term_relationships}
           ON object_id = ID
           WHERE ID = $selector
           AND post_status = 'publish'
           LIMIT 1;"
        );
        break;
    }
  }
  return $data;
}

/**
 * Get all categories
 */
function spp_get_categories() {
  global $wpdb;
  $categories = $wpdb->get_results(
    "SELECT {$wpdb->terms}.term_id, name FROM {$wpdb->terms}
     LEFT JOIN {$wpdb->term_taxonomy}
     ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
     WHERE {$wpdb->term_taxonomy}.taxonomy = 'category'
     AND {$wpdb->term_taxonomy}.count > 0;"
  );
  return $categories;
}

/**
 *
 */
function spp_get_dropdown() {
  $categories = spp_get_categories();
  $i = 0;
  foreach($categories as $category) {
    $posts = spp_get_all_posts($category->term_id);
    $select[$i]['category_name'] = $category->name;
    $select[$i]['category_id'] = $category->term_id;
    $j = 0;
    foreach($posts as $post) {
      $select[$i]['children'][$j]['post_name'] = $post->post_title;
      $select[$i]['children'][$j]['post_id'] = $post->ID;
      $j++;
    }
    $i++;
  }
  return $select;
}