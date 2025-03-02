  <div class="relatedposts row">
    <h5>Se flere arrangementer</h5>
    <?php $events = tribe_get_events( array( 'posts_per_page' => 3, 'start_date' => current_time( 'Y-m-d' ) ) ); ?>
    <div class="row">
      <?php foreach ($events as $post) {
        get_template_part ('template-parts/list', 'events');
      } ?>
    </div>
    <div class="row">
      <div class="arrangementer-laes-mere">
        <a href="/arrangementerne"><button class="dc-button">Se flere arrangementer</button></a>
      </div>
    </div>
  </div>