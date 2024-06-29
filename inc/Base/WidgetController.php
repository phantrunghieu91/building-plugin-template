<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Base;

use Inc\Base\BaseController;
use Inc\Api\Widgets\MediaWidget;
class WidgetController extends BaseController
{
  public $settings;
  public $media_widget;
  public function register()
  {
    if( !$this->isActivated('media_widget') ) return;

    $this->media_widget = new MediaWidget();

    $this->media_widget->register();
  }
}