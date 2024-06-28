<?php
/**
 * Trigger the file when user uninstall the plugin
 * @package JinsPlugin
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit();
}

// Clear Database stored data
$books = get_posts(array('post_type' => 'book', 'numberposts' => -1));

foreach ($books as $book) {
  // delete all related post meta
  delete_post_meta($book->ID, 'publisher');
  
  // delete all related terms
  wp_delete_object_term_relationships($book->ID);

  // delete all book related data
  wp_delete_post($book->ID, true);
}