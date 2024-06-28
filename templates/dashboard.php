<?php
/**
 * @package JinsPlugin
 * This is the template for the dashboard page
 */
?>
<div class="wrap">
  <h1>Jin's Plugin Dashboard</h1>
  <?php settings_errors(); ?>

  <section class="tabs">
    <nav class="nav tab__nav">
      <a data-target="#settings" class="nav__item nav__item--active">Manager Settings</a>
      <a data-target="#update" class="nav__item">Update</a>
      <a data-target="#about" class="nav__item">About</a>
    </nav>
    <div class="tab-content">
      <div id="settings" class="tab-pane tab-pane--active">
        <h3>Manager Settings</h3>
        <form method="post" action="options.php">
          <?php
          settings_fields('jins_option_settings');
          do_settings_sections('jins_plugin');
          submit_button();
          ?>
        </form>
      </div>
      <div id="update" class="tab-pane">
        <h3>Update</h3>
      </div>
      <div id="about" class="tab-pane">
        <h3>About</h3>
      </div>
  </section>


</div>