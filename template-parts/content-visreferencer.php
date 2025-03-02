<?php
/* Hent referencer */
  $args = array(
      'tax_query' => array (
        array (
          'taxonomy'  => 'category',
          'field'     => 'slug',
          'terms'     => 'reference'
        ),
      ),
  );
  $nyheder = new WP_Query( $args );
  while ( $nyheder->have_posts() ) : $nyheder->the_post();
    get_template_part('template-parts/list', 'referencer');
  endwhile;
