<?php $id = get_the_ID();?> 
<article class="six columns single-forloeb single-nyhed" data-article-id="<?php echo $id;?>">
  <a href="<?php the_permalink();?>">
    <div class="content-of-single-course">
      <div class="nyhed"><?php the_post_thumbnail('nyheder', ['class' => 'forside-nyheds-billede', 'title' => get_the_title()]);?></div>
      <div class="nyhedsoverskrift"><?php the_title(); ?></div>
      <div class="nyhedsuddrag" data-uddrag-id="<?php echo $id;?>"><?php the_excerpt(); ?></div>      
    </div>
  </a>
</article>
