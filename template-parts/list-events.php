
  <div class="four columns">
    <a href="<?php echo tribe_get_event_link($post->ID);?>">
      <article class="single-nyhed">
        <div class="nyhed">
        <?php the_post_thumbnail('nyheder', ['class' => 'forside-nyheds-billede', 'title' => get_the_title()]);?>
        </div>
        <div class="overskrift"><?php echo $post->post_title;?></div>
        <?php setup_postdata( $post ); ?>		
        <div class="dato"><?php echo tribe_events_event_schedule_details();?></div>
        <div class="nyhedsuddrag">
          <?php echo $post->post_excerpt ?>
        </div>
      </article>
    </a>        
  </div>
