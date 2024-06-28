<?php
/**
 * @package JinsPlugin
 * Enqueue Scripts and Styles
 */
namespace Inc\Base;
use Inc\Base\BaseController;
class Enqueue extends BaseController {
  public function register(){
    if ( is_admin() ) {
      add_action('admin_enqueue_scripts', array($this, 'enqueueAdminsScripts'));
    } else {
      add_action('wp_enqueue_scripts', array($this, 'enqueueFrontEndScripts'));
    }
  }

  public function enqueueAdminsScripts(){
    wp_enqueue_style('jins-plugin-style', $this->plugin_url . 'assets/css/jins-plugin.min.css', [], null, 'all');
    wp_enqueue_script('jins-plugins-cript', $this->plugin_url . 'assets/js/jins-plugin.min.js', ['jquery'], null, true);
  }
  
  public function enqueueFrontEndScripts() {

  }
}