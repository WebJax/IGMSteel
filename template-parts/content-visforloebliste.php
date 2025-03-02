<?php
/* Hent træningsforløb */
  $args = array(
      'tax_query' => array (
          array(
          'taxonomy'  => 'category',
          'field'     => 'slug',
          'terms'     => 'believe-traeningsforloeb',
          ),
      ),
  );
  $nyheder = new WP_Query( $args );
  echo '<ul>';
  while ( $nyheder->have_posts() ) : $nyheder->the_post();
    get_template_part('template-parts/list', 'forloeb');
  endwhile;
  echo '</ul>';
