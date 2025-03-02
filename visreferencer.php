<?php
/*
Template Name: Referencer
*/
get_header();

get_template_part('template-parts/content', 'headerimage');

if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <section class="container sideindhold traeningsforloeb">
    <div class="row">
      <div class="offset-by-two columns eight columns">
        <?php the_title( '<h1 class="overskrift">','</h1>' );?>
				<?php edit_post_link('<button class="edit-logged-in">Ret siden</button>','','');?>
        <div class="indholdet">
          <?php the_content(); ?>
        </div>
        <div class="liste-over-referencer">
          <?php get_template_part('template-parts/content', 'visreferencer'); ?>        
        </div>
        <div class="social-share-links">
  				<?php //get_template_part('template-parts/content', 'links'); ?>        
        </div>
      </div>
    </div>
  </section>    
<?php
endwhile;
else :
    _e( 'Sorry, no posts matched your criteria.', 'textdomain' );
endif;

get_footer();
echo '</div>';
?>