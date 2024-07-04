<?php
/**
 * @package JinsPlugin
 * Custom Templates Controller
 */
namespace Inc\Base;
use \Inc\Base\BaseController;
class CustomTemplatesController extends BaseController {
  public $templates;
  public function register() {
    if ( ! $this->isActivated( 'templates_manager' ) ) return;

    $this->templates = [
      'page-templates/two-column.php' => 'Two Column Layout',
    ];
    // Add custom template to the page template dropdown in the admin panel
    add_filter( 'theme_page_templates', [ $this, 'customTemplate' ] );
    // Load custom template from the theme directory
    add_filter( 'template_include', [ $this, 'loadTemplate' ] );
  }
  public function customTemplate( $templates ) {
    $templates = array_merge( $templates, $this->templates );
    return $templates;
  }

  public function loadTemplate( $template ) {
    
    global $post;
    if ( ! $post ) return $template;

    $template_name = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( ! isset( $this->templates[$template_name] ) ) return $template;
    $file = $this->plugin_path . $template_name;

    if( file_exists( $file ) ) return $file;

    return $template;
  }
}