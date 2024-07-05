<?php
/**
 * @package JinsPlugin
 */
namespace Inc\Base;

use Inc\Base\BaseController;
use Inc\Api\SettingsApi;
use Inc\Api\Callbacks\TestimonialCallbacks;

class TestimonialController extends BaseController
{
  public $meta_box_data;
  public $settings;
  public $callbacks;
  private $ajax_action;
  private $meta_box_info;
  public function register()
  {
    if (!$this->isActivated('testimonial_manager'))
      return;

    $this->settings = new SettingsApi();
    $this->callbacks = new TestimonialCallbacks();
    $this->meta_box_info = ['key' => '_jin_testimonial_details', 'title' => 'Testimonial Details'];

    add_action('init', array($this, 'testimonialCPT'));

    $this->setMetaBoxes();
    // Add custom meta box
    add_action('add_meta_boxes', [$this, 'addMetaBoxes']);

    // Save custom meta box
    add_action('save_post', [$this, 'saveMetaBox']);

    // Add custom columns to the testimonial post type table
    add_filter('manage_testimonial_posts_columns', [$this, 'setCustomColumns']);
    // Add custom column data
    add_action('manage_testimonial_posts_custom_column', [$this, 'setCustomColumnsData'], 10, 2);
    // Add sorting to the custom columns
    add_filter('manage_edit-testimonial_sortable_columns', [$this, 'setCustomColumnsSortable']);

    $this->setShortcodePage();
    // Add ajax action for the testimonial form
    $this->ajax_action = 'submit_testimonial';

    add_action("wp_ajax_$this->ajax_action", [$this, 'testimonialFormSubmission']);
    add_action("wp_ajax_nopriv_$this->ajax_action", [$this, 'testimonialFormSubmission']);

    // Add shortcode for the testimonial form
    add_shortcode('testimonial_form', [$this, 'testimonialForm']);
    // Add shortcode for the testimonial slider
    add_shortcode('testimonial_slider', [$this, 'testimonialSlider']);

  }
  public function setMetaBoxes()
  {
    $this->meta_box_data = [
      [
        'id' => 'author',
        'title' => 'Testimonial Author',
        'type' => 'text',
        'placeholder' => 'eg. John Doe'
      ],
      [
        'id' => 'author_email',
        'title' => 'Testimonial Author Email',
        'type' => 'text',
        'placeholder' => 'eg. johndoe@example.com'
      ],
      [
        'id' => 'approved',
        'title' => 'Approved',
        'type' => 'checkbox',
      ],
      [
        'id' => 'featured',
        'title' => 'Featured',
        'type' => 'checkbox',
      ],
    ];
  }
  public function testimonialCPT()
  {
    $labels = [
      'name' => 'Testimonials',
      'singular_name' => 'Testimonial',
      'plural_name' => 'Testimonials',
      'menu_name' => 'Testimonials',
      'add_new' => 'Add New Testimonial',
      'add_new_item' => 'Add New Testimonial',
    ];
    register_post_type('testimonial', [
      'public' => true,
      'has_archive' => false,
      'labels' => $labels,
      'menu_icon' => 'dashicons-testimonial',
      'exclude_from_search' => true,
      'publicly_queryable' => false,
      'supports' => ['title', 'editor', 'thumbnail'],
      'show_in_rest' => true,
    ]);
  }
  public function addMetaBoxes()
  {
    add_meta_box(
      $this->meta_box_info['key'],         // id of div wrapper
      $this->meta_box_info['title'],       // title
      [$this, 'renderMetabox'],       // callback to render the metabox
      'testimonial',                  // name of the post type
      'side',                         // where the metabox will appear
      'default',                      // the order in which the metabox will appear
    );
  }
  public function renderMetabox($post, $meta_box)
  {
    // check what metabox is being rendered is calling this function
    $meta_box_values = get_post_meta($post->ID, $this->meta_box_info['key'], true);
    // create nonce
    wp_nonce_field($this->meta_box_info['key'], "{$this->meta_box_info['key']}_nonce");
    // render the metaboxes
    ?>
    <section class="testimonial-details">
      <?php
      foreach ($this->meta_box_data as $smaller_meta_box):
        $field_id = "jin_testimonial_{$smaller_meta_box['id']}";
        $field_type = $smaller_meta_box['type'];
        if ($field_type !== 'checkbox')
          $field_value = $meta_box_values[$smaller_meta_box['id']] ?? '';
        else
          $field_value = $meta_box_values[$smaller_meta_box['id']] ?? false;
        ?>
        <div class="testimonial-details__meta-box">
          <label for="<?= esc_attr($field_id); ?>"><?= esc_html($smaller_meta_box['title']); ?></label>
          <?php if ($field_type === 'text'): ?>
            <input type="text" id="<?= esc_attr($field_id); ?>" name="<?= esc_attr($field_id); ?>"
              value="<?= esc_attr($field_value); ?>" placeholder="<?= esc_attr($smaller_meta_box['placeholder']); ?>">
          <?php elseif ($field_type === 'checkbox'): ?>
            <div class="ui-toggle">
              <input type="checkbox" id="<?= esc_attr($field_id); ?>" name="<?= esc_attr($field_id); ?>" <?= $field_value === true ? 'checked' : ''; ?>>
              <label for="<?= esc_attr($field_id); ?>"></label>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </section>
    <style>
      .testimonial-details__meta-box {
        padding-block: .5em;
        display: flex;
        flex-flow: row wrap;
        align-items: center;
        gap: .4em;
      }

      .testimonial-details__meta-box input[type="text"] {
        flex: 1;
      }

      .testimonial-details__meta-box:has(input[type="checkbox"]) {
        justify-content: space-between;
      }
    </style>
    <?php
  }
  public function saveMetaBox($post_id)
  {
    // check if the nonce is set
    if (!isset($_POST["{$this->meta_box_info['key']}_nonce"]))
      return $post_id;
    $nonce = $_POST["{$this->meta_box_info['key']}_nonce"];
    // verify the nonce
    if (!wp_verify_nonce($nonce, $this->meta_box_info['key']))
      return $post_id;
    // prevent the action for autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      return $post_id;
    // check if the user has permission to edit the post
    if (!current_user_can('edit_post', $post_id))
      return $post_id;
    // Get the meta box values
    $data = [];
    foreach ($this->meta_box_data as $meta_box) {
      if ($meta_box['type'] === 'checkbox') {
        $data[$meta_box['id']] = isset($_POST["jin_testimonial_{$meta_box['id']}"]) ? true : false;
      } else {
        $data[$meta_box['id']] = sanitize_text_field($_POST["jin_testimonial_{$meta_box['id']}"]);
      }
    }
    update_post_meta($post_id, $this->meta_box_info['key'], $data);
  }
  public function setCustomColumns($columns)
  {
    $title = $columns['title'];
    $date = $columns['date'];
    unset($columns['title'], $columns['date']);
    $columns['testimonial_author'] = 'Author Name';
    $columns['title'] = $title;
    $columns['testimonial_approved'] = 'Approved';
    $columns['testimonial_featured'] = 'Featured';
    $columns['date'] = $date;
    return $columns;
  }
  public function setCustomColumnsData($column, $post_id)
  {
    $data = get_post_meta($post_id, $this->meta_box_info['key'], true);
    switch ($column) {
      case 'testimonial_author':
        echo '<strong>' . $data['author'] . '</strong><br/><a href="' . $data['author_email'] . '">' . $data['author_email'] . '</a>';
        break;
      case 'testimonial_approved':
        echo isset($data['approved']) && $data['approved'] ? 'YES' : 'NO';
        break;
      case 'testimonial_featured':
        echo isset($data['featured']) && $data['featured'] ? 'YES' : 'NO';
        break;
    }
  }
  public function setCustomColumnsSortable($columns)
  {
    $columns['testimonial_author'] = 'testimonial_author';
    $columns['testimonial_approved'] = 'testimonial_approved';
    $columns['testimonial_featured'] = 'testimonial_featured';
    return $columns;
  }
  public function setShortcodePage()
  {
    $subpage = [
      [
        'parent_slug' => 'edit.php?post_type=testimonial',
        'page_title' => 'Shortcodes',
        'menu_title' => 'Shortcodes',
        'capability' => 'manage_options',
        'menu_slug' => 'jin_testimonial_shortcode',
        'callback' => [$this->callbacks, 'shortcodePage'],
      ]
    ];
    $this->settings->AddSubPages($subpage)->register();
  }
  public function testimonialForm()
  {
    $fields = [
      ['name' => 'name', 'placeholder' => 'Full Name', 'type' => 'text'],
      ['name' => 'email', 'placeholder' => 'Email Address', 'type' => 'email'],
      ['name' => 'message', 'placeholder' => 'Message', 'type' => 'textarea'],
    ];
    $form_class = 'testimonial-form';
    $form_action = $this->ajax_action;
    ob_start();
    require_once "$this->plugin_path/templates/contact-form.php";
    echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/css/jins-plugin-form.min.css\" media=\"all\" type=\"text/css\">";
    echo "<script src=\"$this->plugin_url/assets/js/jins-plugin-form.min.js\"></script>";
    return ob_get_clean();
  }
  public function testimonialSlider()
  {
    $meta_key = $this->meta_box_info['key'];
    // Get the Testimonial Slider data from the database
    $args = [
      'post_type' => 'testimonial',
      'post_status' => 'publish',
      'posts_per_page' => 4,
      'orderby' => 'date',
      'order' => 'ASC',
      'meta_query' => [
        [
          'key' => $this->meta_box_info['key'],
          'value' => 's:8:"approved";b:1;s:8:"featured";b:1;',
          'compare' => 'LIKE',
        ]
      ]
    ];
    $testimonials_query = new \WP_Query($args);
    $testimonials = $testimonials_query->posts;

    ob_start();
    require_once "$this->plugin_path/templates/testimonial-slider.php";
    echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/css/jins-plugin-slider.min.css\" media=\"all\" type=\"text/css\">";
    echo "<script src=\"$this->plugin_url/assets/js/jins-plugin-slider.min.js\"></script>";
    return ob_get_clean();
  }
  public function testimonialFormSubmission()
  {
    // a function to handle sending message to client
    $send_message = function ($type, $status, $message) {
      if ($type === 'error') {
        wp_send_json_error($message, $status);
      } else {
        wp_send_json_success($message, $status);
      }
      wp_die();
    };

    // check if the request is not ajax or nonce verification failed
    if (!DOING_AJAX || (check_ajax_referer("{$this->ajax_action}_nonce", 'nonce') && !wp_verify_nonce($_POST['nonce'], "{$this->ajax_action}_nonce"))) {
      wp_send_json_error('Unauthorized request', 401);
      wp_die();
    }

    // sanitize the data
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);

    // write a query to check if email already exists, metadata has key $this->meta_box_info['key'] and value = a:4:{s:18:"testimonial_author";s:8:"John Doe";s:24:"testimonial_author_email";s:12:"john@doe.com";s:20:"testimonial_approved";b:0;s:20:"testimonial_featured";b:0;}
    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id WHERE p.post_type = 'testimonial' AND pm.meta_key = %s AND pm.meta_value LIKE %s", $this->meta_box_info['key'], "%$email%");
    // Execute the query, get the number of rows
    $posts = $wpdb->get_results($query);

    if (!empty($posts)) {
      $send_message('error', 409, 'This email already used for another message, please try again with different email!');
    }

    // Create new post with testimonial post type
    $data = [
      'post_title' => 'Testimonial from ' . $name,
      'post_content' => $message,
      'post_author' => 1,
      'post_status' => 'publish',
      'post_type' => 'testimonial',
      'meta_input' => [
        $this->meta_box_info['key'] => [
          'author' => $name,
          'author_email' => $email,
          'approved' => false,
          'featured' => false,
        ]
      ]
    ];
    $post_id = wp_insert_post($data);
    if ($post_id) {
      $send_message('success', 200, 'Testimonial submitted successfully');
    } else {
      $send_message('error', 500, 'Failed to submit testimonial');
    }
  }
}