<?php
get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <div class="overskrift-baggrund">
	  <?php the_title( '<h1 class="overskrift"><span>','</span></h1>' );?>
  </div>
  <section class="container sideindhold wctemplate">
    <div class="row">
      <div class="offset-by-two column eight columns">
		<?php edit_post_link('<button class="edit-logged-in">Ret page</button>','','');?>
		<?php custom_breadcrumb(); ?>
        <div class="indholdet">
        	<?php the_content();?>
        </div>
		<?php get_template_part('template-parts/content', 'links'); ?>
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
