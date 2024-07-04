<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Base;
/**
 * Represents the base controller for the plugin.
 *
 * This class provides common properties and methods used by other controllers in the plugin.
 */
class BaseController {
    public $plugin_path;
    public $plugin_url;
    public $plugin_name;
    public $managers = [];
    public function __construct() {
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
        $this->plugin_name = plugin_basename( dirname( __FILE__, 3 ) ) . '/jins-plugin.php';
        $this->managers = [
            'cpt_manager' => 'Activate CPT Manager',
            'taxonomy_manager' => 'Activate Taxonomy Manager',
            'media_widget' => 'Activate Media Widget',
            'gallery_manager' => 'Activate Gallery Manager',
            'testimonial_manager' => 'Activate Testimonial Manager',
            'templates_manager' => 'Activate Custom Templates',
            'login_manager' => 'Activate Ajax Login/Signup',
            'membership_manager' => 'Activate Membership Manager',
            'chat_manager' => 'Activate Chat Manager',
        ];
    }
    /**
     * Checks if a specific feature of plugin is activated.
     *
     * @param string $key The key of the feature to check.
     * @return bool Returns true if the feature is activated, false otherwise.
     */
    public function isActivated($key) {
        return get_option('jins_plugin')[$key] ?? false;
    }
}