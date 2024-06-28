<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Base;

class Activate
{
  public static function activate(){
    flush_rewrite_rules();
    
    // Checks if the 'jins_plugin' option is set and initializes it with a default value if not.
    $default = array();
    if ( ! get_option( 'jins_plugin' ) ) {
      update_option( 'jins_plugin', $default );
    }

    if ( !get_option( 'jins_plugin_cpt' ) ) {
      update_option( 'jins_plugin_cpt', $default );
    }
  }
}