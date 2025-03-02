    <div class="relatedposts row">
      <h5>Andre træningsforløb</h5>
      <?php
      $related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 6, 'post__not_in' => array($post->ID) ) );?>
      <ul class="related-news"><?php
      if( $related ) foreach( $related as $post ) {
        get_template_part ('template-parts/list', 'forloeb');
      }
      wp_reset_postdata(); ?>
      </ul>   
    </div>
