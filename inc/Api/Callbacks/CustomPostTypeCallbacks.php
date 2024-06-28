<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Api\Callbacks;

class CustomPostTypeCallbacks
{
  public function cptSection()
  {
    echo 'Manage all your Custom Post Type';
  }
  public function cptSanitize($input)
  {

    $options = get_option('jins_plugin_cpt') ?? [];

    // Handle delete custom post type
    if (isset($_POST['remove'])) {
      unset($options[$_POST['remove']]);
      return $options;
    }

    if (count($options) == 0) {
      return $options[$input['post_type']] = $input;
    }

    // Handle add that exist post type or edit custom post type
    if (!empty($input)) {
      // foreach ($options as $option) {
        $input['public'] = isset($input['public']);
        $input['has_archive'] = isset($input['has_archive']);
        $options[$input['post_type']] = $input;
      // }
    }

    return $options;
  }
  public function textField($args)
  {
    $id = $args['label_for'];
    $option_name = $args['option_name'];
    $option_value = get_option($option_name);
    $field_value = $option_value[$id] ?? '';
    $placeholder = $args['placeholder'] ?? '';
    $field_name = esc_attr($option_name . '[' . $id . ']');
    $required = $args['required'] ?? false;

    echo '<input type="text" class="regular-text" id="' . esc_attr($id) . '" name="' . $field_name . '" placeholder="' . esc_attr($placeholder) . '" value="' . esc_attr($field_value) . '"' . ($required ? 'required' : '') . '>';
  }
  public function checkboxField($args)
  {
    $id = $args['label_for'];
    $classes = $args['class'];
    $option_name = $args['option_name'];
    $option_value = get_option($option_name);
    $checkbox_value = $option_value[$id] ?? false;
    $field_name = $option_name . '[' . $id . ']';

    echo '<div class="' . esc_attr($classes) . '">
    <input type="checkbox" id="' . esc_attr($id) . '" name="' . esc_attr($field_name) . '" ' . ($checkbox_value ? 'checked' : '') . '>
    <label for="' . esc_attr($id) . '"></label>
    </div>';
  }
}
