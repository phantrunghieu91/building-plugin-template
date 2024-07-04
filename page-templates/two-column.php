<?php
/**
 * Template Name: Two Column Layout
 */
get_header();
?>

<div id="content">
  <h1>This is Two Column Layout Template</h1>
    <?php
    while (have_posts()):
      the_post();
      the_content();
    endwhile;
    ?>
</div>
<aside class="sidebar">
  <?php get_sidebar(); ?>
</aside>

<?php
get_footer();