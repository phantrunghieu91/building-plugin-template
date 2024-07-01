<?php
/**
 * @package JinsPlugin
 * 
 * This template is for front-end Form.
 */
// access query variables passed form where we call the shortcode
$nonce = wp_create_nonce("{$form_action}_nonce");
?>

<form class="jins-plugin-form <?= esc_attr($form_class)?>" method="POST" data-url="<?= esc_attr(admin_url('admin-ajax.php')) ?>">
  <input type="hidden" name="action" value="<?= esc_attr($form_action) ?>">
  <input type="hidden" name="nonce" value="<?= esc_attr($nonce) ?>">
  <div class="jins-plugin-form__form-message"></div>
  <?php 
    if( isset($fields) && !empty( $fields ) ) :
      foreach( $fields as $field ) :
      ?>
      <div class="jins-plugin-form__form-group">
        <?php if( $field['type'] === 'textarea' ) : ?>
          <textarea class="jins-plugin-form__field" name="<?= esc_attr($field['name']); ?>" id="<?= esc_attr($field['name']); ?>" placeholder="<?= esc_attr($field['placeholder']); ?>" rows="5"></textarea>
        <?php else: ?>
          <input class="jins-plugin-form__field" type="<?= esc_attr($field['type']); ?>" name="<?= esc_attr($field['name']); ?>" id="<?= esc_attr($field['name']); ?>" placeholder="<?= esc_attr($field['placeholder']); ?>">
        <?php endif; ?>
        <span class="jins-plugin-form__field-error-message"></span>
      </div>
  <?php endforeach; 
  else: ?>
    <p>Please check again, there is no fields to display.</p>
  <?php endif; ?>
  <div class="jins-plugin-form__button-group">
    <button type="submit" class="jins-plugin-form__submit-btn">Submit</button>
    <button type="reset" class="jins-plugin-form__reset-btn">Reset</button>
  </div>
</form>