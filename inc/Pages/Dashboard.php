<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Pages;

use \Inc\Base\BaseController;
use Inc\Api\SettingsApi;
use Inc\Api\Callbacks\DashboardCallbacks;
use Inc\Api\Callbacks\FieldsManagerCallbacks;

class Dashboard extends BaseController
{
  public $settings;
  public $pages;
  // public $subPages;
  public $dashboardCallbacks;
  public $fieldsManagerCallbacks;
  public function register()
  {
    $this->settings = new SettingsApi();
    $this->dashboardCallbacks = new DashboardCallbacks();
    $this->fieldsManagerCallbacks = new FieldsManagerCallbacks();

    // Add pages
    $this->setPages();
    // $this->setSubPages();

    // Add custom fields
    $this->setSettings();
    $this->setSections();
    $this->setFields();

    $this->settings->AddPages($this->pages)->withSubPage('Dashboard')->register();
  }

  public function setPages()
  {
    $this->pages = [
      [
        'page_title' => 'Jin\'s Plugin Dashboard',
        'menu_title' => 'Jin\'s Plugin Dashboard',
        'capability' => 'manage_options',
        'menu_slug' => 'jins_plugin',
        'callback' => [$this->dashboardCallbacks, 'pluginDashboard'],
        'icon_url' => 'dashicons-store',
        'position' => 110
      ]
    ];
  }
  public function setSettings()
  {
    // Save all the settings in options table at once
    $args = [
      [
        'option_group' => 'jins_option_settings',
        'option_name' => 'jins_plugin',
        'callback' => [$this->fieldsManagerCallbacks, 'checkboxSanitize'],
      ]
    ];
    // Save the settings in options table one by one
    // $args = [];
    // foreach($this->managers as $key => $value) {
    //   $args[] = [
    //     'option_group' => 'jins_option_settings',
    //     'option_name' => $key,
    //     'callback' => [$this->fieldsManagerCallbacks, 'checkboxSanitize'],
    //   ];
    // }
    $this->settings->setSettings($args);
  }

  public function setSections()
  {
    $args = [
      [
        'id' => 'jins_admin_index',
        'title' => 'Settings',
        'callback' => [$this->fieldsManagerCallbacks, 'jinsAdminSection'],
        'page' => 'jins_plugin'
      ]
    ];
    $this->settings->setSections($args);
  }
  public function setFields()
  {
    $args = [];
    foreach($this->managers as $key => $value) {
      $args[] = [
        'id' => $key,
        'title' => $value,
        'callback' => [$this->fieldsManagerCallbacks, 'renderCheckboxField'],
        'page' => 'jins_plugin',
        'section' => 'jins_admin_index',
        'args' => [
          'option_name' => 'jins_plugin',
          'label_for' => $key,
          'class' => 'ui-toggle'
        ],
      ];
    }
    $this->settings->setFields($args);
  }
}