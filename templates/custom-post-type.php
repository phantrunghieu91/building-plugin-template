<?php
/**
 * @package JinsPlugin
 * Template for custom post type submenu
 */
$options = get_option('jins_plugin_cpt') ?? [];
?>
<div class="wrap">
  <h1>Custom Post Types Manager</h1>
  <?php settings_errors(); ?>
  <section class="tabs">
    <nav class="nav tab__nav">
      <a data-target="#display-cpt" class="nav__item nav__item--active">All your custom post type</a>
      <a data-target="#modify-cpt" class="nav__item">Create new custom post type</a>
      <a data-target="#export" class="nav__item">Export</a>
    </nav>

    <div class="tab-content">
      <div id="display-cpt" class="tab-pane tab-pane--active">
        <table class="cpt-display">
          <caption>Display all exist custom post types.</caption>
          <thead>
            <tr>
              <th>Slug</th>
              <th>Singular Name</th>
              <th>Plural Name</th>
              <th>Public</th>
              <th>Archive</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!empty($options)) {
              foreach ($options as $option) {
                $true_false_icons = [
                  'true' => '<span class="dashicons dashicons-yes-alt"></span>',
                  'false' => '<span class="dashicons dashicons-no-alt"></span>'
                ];
                $public = $option['public'] ? $true_false_icons['true'] : $true_false_icons['false'];
                $has_archive = ($option['has_archive'] ?? false) ? $true_false_icons['true'] : $true_false_icons['false'];
                ?>
                <tr>
                  <td><?php echo $option['post_type']; ?></td>
                  <td><?php echo $option['singular_name']; ?></td>
                  <td><?php echo $option['plural_name']; ?></td>
                  <td><?php echo $public; ?></td>
                  <td><?php echo $has_archive; ?></td>
                  <td>
                    <a class="edit-cpt button button-primary button-small" 
                    data-post-type="<?php echo esc_attr($option['post_type']); ?>"
                    data-singular-name="<?= esc_attr($option['singular_name']) ?>"
                    data-plural-name="<?= esc_attr($option['plural_name']) ?>"
                    data-public="<?= (isset($option['public']) ? $option['public'] : '0') ?>"
                    data-has-archive="<?= (isset($option['has_archive']) ? $option['has_archive'] : '0') ?>"
                    >Edit</a>
                    <form class="cpt-display__delete" method="post" action="options.php">
                      <?php
                      settings_fields('jins_plugin_cpt_settings');
                      ?><input type="hidden" name="remove" value="<?= esc_attr($option['post_type']) ?>"><?php
                        submit_button('Delete', 'delete small', 'submit', false, [
                          'onclick' => 'return confirm("Are you sure you want to delete this custom post type? The data associated with it will not be deleted.")'
                        ]);
                        ?>
                    </form>
                  </td>
                </tr>
                <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>
      <div id="modify-cpt" class="tab-pane">
        <form class="cpt-form" method="post" action="options.php">
          <?php
          settings_fields('jins_plugin_cpt_settings');
          do_settings_sections('jins_cpt');
          submit_button();
          ?>
        </form>
      </div>
      <div id="export" class="tab-pane">
        <h3>Export</h3>
      </div>

  </section>
</div>