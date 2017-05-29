<?php get_header('letterpage'); ?>
<div id="page-content">
  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
  <h2>
    <?php the_title(); ?>
  </h2>
  
  <?php

    // $id=$post->ID; 
    // $post = get_post($id); 
    // $content = apply_filters('the_content', $post->post_content); 

  ?>
  <?php the_content(); ?>
  
  <div style="clear:both"></div>
  <?php endwhile; ?>
  <?php else : ?>
  <h2 class="center">Not Found</h2>
  <p class="center">Sorry, but you are looking for something that isn't here.</p>
  <?php get_search_form(); ?>
  <?php endif; ?>
</div>
<?php get_footer('letterpage'); ?>
