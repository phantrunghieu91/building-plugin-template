<?php
/** 
 * @package JinsPlugin
 */
namespace Inc\Api\Widgets;

use WP_Widget;

class MediaWidget extends WP_Widget
{
  public $ID;
  public $name;
  public $options = [];
  public $control_options = [];
  public function __construct()
  {
    $this->ID = 'jins_media_widget';
    $this->name = 'Jin\'s Media Widget';
    $this->options = [
      'classname' => 'jin-media-widget',
      'description' => 'This is a custom media widget.',
      'customize_selective_refresh' => true,
    ];
    $this->control_options = [
      'width' => 400,
      'height' => 350
    ];
  }
  public function register()
  {
    parent::__construct($this->ID, $this->name, $this->options, $this->control_options);

    add_action('widgets_init', [$this, 'widgetInit']);
  }

  public function widgetInit()
  {
    register_widget($this);
  }
  // Front-end display of widget, override WP_Widget form method
  public function form($instance)
  {
    $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Jin\'s Widget', 'jins_plugin');
    $image = !empty($instance['image']) ? $instance['image'] : '';
    $title_field_id = $this->get_field_id('title');
    $image_field_id = $this->get_field_id('image');
    ?>
    <p>
      <label for="<?php echo esc_attr($title_field_id) ?>"><?php _e('Title:'); ?></label>
      <input class="widefat" id="<?php echo esc_attr($title_field_id) ?>"
        name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr($image_field_id) ?>"><?php _e('Image:'); ?></label>
      <a href="javascript:void(0);" class="remove-selected-img<?= !$image ? ' hide' : '' ?>">Remove Image</a>
      <input class="widefat image-upload" id="<?php echo esc_attr($image_field_id) ?>"
        name="<?php echo $this->get_field_name('image'); ?>" type="hidden" value="<?php echo esc_url($image); ?>">
        <button class="button button-primary image-upload-btn">Upload Image</button>
        <?php if ($image): ?>
          <img src="<?php echo esc_url($image); ?>" class="image-preview" alt="Image preview">
        <?php endif; ?>
    </p>
    <?php
  }
  public function widget($args, $instance)
  {
    echo $args['before_widget'];

    if (!empty($instance['title'])) {
      echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
    }
    if (!empty($instance['image'])) {
      echo '<img src="' . esc_url($instance['image']) . '" alt="">';
    }

    echo $args['after_widget'];
  }
  public function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    $instance['title'] = sanitize_text_field($new_instance['title']);
    $instance['image'] = !empty($new_instance['image']) ? $new_instance['image'] : '';

    return $instance;
  }
}