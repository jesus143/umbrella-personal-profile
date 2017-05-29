<?php 
/**
 * Template Name: Profile Page
 */
get_header(); ?>
<div id="page-content">
  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
  <h2>
    <?php the_title(); ?>
  </h2>
  
  <?php the_content(); 
    echo do_shortcode('[shortcode-profile]');
  ?>
  
  <div style="clear:both"></div>
  <?php endwhile; ?>
  <?php else : ?>
  <h2 class="center">Not Found</h2>
  <p class="center">Sorry, but you are looking for something that isn't here.</p>
  <?php get_search_form(); ?>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
