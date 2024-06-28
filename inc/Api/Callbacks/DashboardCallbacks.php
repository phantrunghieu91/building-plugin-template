<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Api\Callbacks;
use Inc\Base\BaseController;
class DashboardCallbacks extends BaseController {
  public function pluginDashboard(){
    return require_once( "$this->plugin_path/templates/dashboard.php" );
  }
  public function customPostType(){
    return require_once( "$this->plugin_path/templates/custom-post-type.php" );
  }
  public function customTaxonomies(){
    return require_once( "$this->plugin_path/templates/taxonomies.php" );
  }
  public function widgets(){
    return require_once( "$this->plugin_path/templates/widgets.php" );
  }
}