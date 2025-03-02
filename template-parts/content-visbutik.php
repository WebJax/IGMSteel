  <section class="container sideindhold posts">
    <?php
    if ('' == get_the_content()) { ?>
      <div class="row">
        <div class="offset-by-four four columns">
          <div class="breadcrumb"><?php get_breadcrumb(); ?></div>
          <?php
          the_title('<h1 class="butiksnavn">','</h1>');
          ?>
        </div>
      </div>
      <div class="row">
        <div class="offset-by-four four columns">
          <?php
          get_template_part('template-parts/vis', 'butikinfo'); 
          get_template_part('template-parts/vis', 'aabningstider');
          ?>
        </div>
      </div>
    <?php } else { ?>
    <div class="row">
      <div class="three columns ">
        <p></p>
      </div>
      <div class="six columns">
        <div class="breadcrumb"><?php get_breadcrumb(); ?></div>
        <?php
        the_title('<h1 class="butiksnavn">','</h1>'); ?>
      </div>
      <div class="three columns">
        <p></p>
      </div>    
    </div>                
    <div class="row">
      <div class="three columns">
        <?php
          $image_id = get_post_meta( $post->ID, 'allround-cpt_logo_id', true );
          if (!empty($image_id)) {
            echo '<img id="allround-cpt-logo" src="'.wp_get_attachment_url( $image_id ).'" style="max-width:100%;" />';
          } 
          get_template_part('template-parts/vis', 'butikinfo');
        ?>
      </div>
      <div class="six columns indholdet">
        <?php the_content(); ?>
      </div>
      <div class="three columns">
      <?php
        get_template_part('template-parts/vis', 'aabningstider');
      ?>
      </div>
    </div>
    <?php } ?>
  </section>