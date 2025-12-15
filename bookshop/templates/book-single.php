<?php get_header() ?>


<div class="container">
  <div class="single-book">
    <h1><?php the_title(); ?></h1>
    <?php the_post_thumbnail("medium"); ?>
    <p><?php echo get_post_meta(get_the_ID(), "author", true) ?></p>
    <?php the_content(); ?>
  </div>
</div>

<?php get_footer() ?>