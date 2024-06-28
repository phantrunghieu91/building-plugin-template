<?php
/**
 * @package JinsPlugin
 */
/**
 * Plugin Name: Jin's Plugin
 * Plugin URI:
 * Description: This is a plugin for the WordPress
 * Version: 1.0
 * Author: Jin
 * Author URI: https://fb.com/kimraejin91
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: jins-plugin
 */

defined('ABSPATH') or die('Hey, you can\t access this file!');

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
  require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Activation
function activate_jins_plugin() {
  Inc\Base\Activate::activate();
}
register_activation_hook(__FILE__, 'activate_jins_plugin');

// Deactivation
function deactivate_jins_plugin() {
  Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_jins_plugin');

// Initialize all the core classes of the plugin
if( class_exists('Inc\\Init')) {
  Inc\Init::register_services();
}