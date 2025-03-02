  <section class="container single posts">
    <div class="row">
      <div class="offset-by-one columns ten columns">
        <!-- <div class="breadcrumb"><?php //get_breadcrumb(); ?></div> -->
         <?php
        the_title( '<h1 class="overskrift">','</h1>' );
        if ( has_excerpt() ) {
          echo '<p class="vis-uddraget">'.get_the_excerpt().'</p>';
        } 
        echo '<hr>';
        ?>
      </div>
      <div class="offset-by-three columns six columns">        
        <!-- <p class="udgivet-den">Udgivet den <?php //the_date(); ?></p> -->
        <?php edit_post_link('<button class="edit-logged-in">Ret siden</button>','','');?>
        <div class="indholdet"><?php
          if ( has_post_format( 'gallery' )) { 
            get_template_part ('template-parts/content', 'gallery');
          }	else {
            the_content(); 
          } ?>
        </div>
        <?php get_template_part('template-parts/content', 'links'); ?>
      </div>
    </div>
    <?php get_template_part ('template-parts/content', 'relatednews'); ?>
  </section>
