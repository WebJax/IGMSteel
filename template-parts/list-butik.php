<?php
  $catId = 0;
  $cat = get_the_category($post->ID);
  foreach ($cat as $category) {
    if ($category->category_parent == 27) {
      $catId = $category->cat_ID;
    }
  }
?>

<li class="butik-paa-liste" data-shopcat="<?php echo $catId; ?>">
  <div class="butik-info-venstre">
    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"  class="link-butikbillede">
  		<img src="<?php echo wp_get_attachment_url( get_post_meta( $post->ID, 'allround-cpt_logo_id', true ) ); ?>" class="wp-header-image"/>
    </a>
    <?php get_template_part('template-parts/vis', 'butikinfo'); ?>      
  </div>
  <div class="butik-info-hojre">
    <?php get_template_part('template-parts/vis', 'aabningstider'); ?>    
  </div>
</li>

