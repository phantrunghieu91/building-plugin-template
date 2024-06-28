<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Base;

use Inc\Base\BaseController;
class WidgetController extends BaseController
{
  public $settings;
  public function register()
  {
    if( !$this->isActivated('media_widget') ) return;
  }
}