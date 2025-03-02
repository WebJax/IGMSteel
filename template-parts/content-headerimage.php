  <div class="flowing-content">
    <?php
    global $post;
    $post_slug=$post->post_name;
    if (is_home() or $post->post_slug == 'arrangementerne' or !has_post_thumbnail( $post->ID ) ) { 
      $args = array ('category_name' => 'forside', 'post_type' => 'attachment');
      $forside_billede = get_posts($args);
      foreach ($forside_billede as $billede) {
        echo '<div class="wp-header-image" style="background-image: url(\''.wp_get_attachment_url( $billede->ID ).'\')"></div>';
        //echo '<img src="'.wp_get_attachment_url( $billede->ID ).'" class="wp-header-image">';
      }
      wp_reset_postdata();
    } else {
        echo '<div class="wp-header-image" style="background-image: url(\''.get_the_post_thumbnail_url($post->ID, 'header-image').'\')"></div>';
        //echo '<img src="'.the_post_thumbnail_url('header-image').'" class="wp-header-image">';
     } ?>