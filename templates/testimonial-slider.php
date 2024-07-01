<?php
/** 
 * @package JinsPlugin
 * 
 * This template is for front-end Testimonial Slider.
 */
?>
<div class="jins-plugin-testimonial-slider">
  <h1>Testimonial Slider</h1>
  <?php
  if (isset($testimonials) && !empty($testimonials)): ?>
    <div class="jins-plugin-testimonial-slider__slider-wrapper">
      <?php foreach ($testimonials as $testimonial):
        $testimonial_meta = get_post_meta($testimonial->ID, $meta_key, true);
        ?>
        <div class="jins-plugin-testimonial-slider__testimonial">
          <div class="jins-plugin-testimonial-slider__testimonial-content">
            <p><?= esc_html($testimonial->post_content); ?></p>
          </div>
          <div class="jins-plugin-testimonial-slider__testimonial-author">
            <p><?= esc_html($testimonial_meta['author']); ?></p>
            <a
              href="mailto:<?= esc_attr($testimonial_meta['author_email']) ?>"><?= esc_html($testimonial_meta['author_email']) ?></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <a class="jins-plugin-testimonial-slider__button-prev" href="javascript:void(0);">
      <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="blue" stroke="blue" stroke-linecap="round" stroke-linejoin="round" >
      <path d="M50 10 L20 50 L50 90 L70 90 L40 50 L70 10 Z" stroke="inherit" fill="inherit"/>
      </svg>
    </a>
    <a class="jins-plugin-testimonial-slider__button-next" href="javascript:void(0);">
      <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="blue" stroke="blue" stroke-linecap="round" stroke-linejoin="round" >
        <path d="M50 10 L80 50 L50 90 L30 90 L60 50 L30 10 Z" stroke="inherit" fill="inherit"/>
      </svg>
    </a>
  <?php else: ?>
    <p>Please check again, there is no testimonials to display.</p>
  <?php endif; ?>
</div>