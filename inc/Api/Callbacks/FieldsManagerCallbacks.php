<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Api\Callbacks;
use Inc\Base\BaseController;
class FieldsManagerCallbacks extends BaseController {
  public function jinsOptionsGroup( $input ) {
    return $input;
  }
  public function jinsAdminSection() {
    echo 'Manage the Sections and Fields of your Plugin by activating checkbox below:';
  }
  public function checkboxSanitize( $input ) {
    $output = [];
    foreach($this->managers as $manager_key => $manager_value) {
      $output[$manager_key] = isset($input[$manager_key]);
    }
    return $output;
  }

  public function renderCheckboxField( $args ) {
    $id = $args['label_for'];
    $classes = $args['class'];
    $option_name = $args['option_name'];
    $option_value = get_option( $option_name );
    $checkbox_value = $option_value[$id] ?? false;

    echo '<div class="'. esc_attr($classes) .'">
    <input type="checkbox" id="'. esc_attr($id) .'" name="'. $option_name .'[' . $id . ']" '. ($checkbox_value ? 'checked' : '') .'>
    <label for="'. esc_attr($id) .'"></label>
    </div>';
  }
}