<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Base;

use Inc\Base\BaseController;

class SettingsLinks extends BaseController {
  public function register(){
    add_filter("plugin_action_links_$this->plugin_name", [$this, 'settingLinks']);
  }
  public function settingLinks($links){
    $settings_link = '<a href="admin.php?page=jins_plugin">Settings</a>';
    array_push($links, $settings_link);
    return $links;
  }
}