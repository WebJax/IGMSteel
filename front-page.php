<?php
/*
Template Name: FrontPage
*/

get_header();

echo '<div class="flow-container frontpage">';

if ( have_posts() ) : while ( have_posts() ) : the_post();
  the_content();
endwhile; else :
	esc_html_e( '<p>Sorry, no posts matched your criteria.</p>' );
endif; 

echo '</div>';

get_footer();
