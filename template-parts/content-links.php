<div class="deldette fancy">
  <p class="del-dette-tekst">
    Del dette
  </p>
  <div class="dellogo" id="facebook" data-href="<?php the_permalink(); ?>" data-mobile-iframe="true">
    <a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink();?>%2F&amp;src=sdkpreparse">
      <i class="ion-social-facebook delopslag"></i>
    </a>
  </div>
  <div class="dellogo" id="twitter">
    <a class="" href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title();?>">
      <i class="ion-social-twitter delopslag"></i>
    </a>	
  </div>
  <div class="dellogo" id="linkedin">
    <a class="linkedin-button" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&source=dianalund-centret.dk">
      <i class="ion-social-linkedin delopslag"></i>
    </a>	
  </div>
  <div class="dellogo" id="maildeling">
    <a href="mailto:?subject=<?php the_title(); ?> - dianalund-centret.dk&amp;body=Klik herunder og læse mere<br/><?php the_permalink(); ?>">
      <i class="ion-ios-email delopslag"></i>
    </a>
  </div>          
</div>