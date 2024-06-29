<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Base;

use Inc\Base\BaseController;
use Inc\Api\Widgets\MediaWidget;

/**
 * The WidgetController class handles the registration of widgets for the JinsPlugin.
 */
class WidgetController extends BaseController
{
  /**
   * The settings for the widget controller.
   *
   * @var array
   */
  public $settings;

  /**
   * The media widget instance.
   *
   * @var MediaWidget
   */
  public $media_widget;

  /**
   * Registers the widget controller.
   */
  public function register()
  {
    if( !$this->isActivated('media_widget') ) return;

    $this->media_widget = new MediaWidget();

    $this->media_widget->register();
  }
}