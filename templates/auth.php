<?php
/**
 * @package JinsPlugin
 * Login/Register with Ajax
 */
$action = 'jins_auth';
?>
<section class="jins-auth">
  <button type="button" class="jins-auth__show-auth-form-btn">Login</button>
  <dialog class="jins-auth__dialog">
    <header class="jins-auth__dialog-header">
      <h2>Site login</h2>
      <button type="button" class="jins-auth__close-dialog-btn">X</button>
    </header>
    <form method="POST" class="jins-auth__form" data-url="<?= esc_attr(admin_url('admin-ajax.php')) ?>">
      <div class="jins-auth__form-message"></div>
      <input type="hidden" name="action" value="<?= esc_attr( $action ) ?>">
      <?php wp_nonce_field($action, "{$action}_nonce") ?>
      <div class="jins-auth__form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>
      </div>
      <div class="jins-auth__form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
      </div>
      <div class="jins-auth__form-group">
        <button type="submit" class="jins-auth__submit-btn">Login</button>
      </div>
      <div class="jins-auth__form-group">
        <span>Don't have an account?</span> 
        <a href="<?= esc_url( wp_registration_url(  ) ) ?>">Register</a>
      </div>
    </form>
  </dialog>
</section>
<?php
