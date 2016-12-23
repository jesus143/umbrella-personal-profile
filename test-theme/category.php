<?php get_header(); ?>
<div id="page-content">
  <?php /*Begin Content area Query*/ ?>
  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
  <h2 class="CatTitle">
    <?php the_title(); ?>
  </h2>
  <div class="cat-excerpt-content">
    <?php the_excerpt(); ?>
  </div>
  <?php endwhile; ?>
  <div class="navigation">
    <div class="alignleft">
      <?php next_posts_link('&laquo; Older Entries') ?>
    </div>
    <div class="alignright">
      <?php previous_posts_link('Newer Entries &raquo;') ?>
    </div>
  </div>
  <?php else : ?>
  <h2 class="center">Not Found</h2>
  <p class="center">Sorry, but you are looking for something that isn't here.</p>
  <?php get_search_form(); ?>
  <?php endif; wp_reset_query(); ?>
  <?php /*End Content area Query*/ ?>
</div>
<?php get_footer(); ?>
