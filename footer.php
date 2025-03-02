    <footer>
      <div class="container">
        <div class="row">
          <div class="four columns left-widget">
            <?php
            if(is_active_sidebar('footer-sidebar-1')){
            dynamic_sidebar('footer-sidebar-1');
            }
            ?>
          </div>
          <div class="four columns middle-widget">
            <?php
            if(is_active_sidebar('footer-sidebar-2')){
            dynamic_sidebar('footer-sidebar-2');
            }
            ?>
          </div>
          <div class="four columns right-widget">
            <?php
            if(is_active_sidebar('footer-sidebar-3')){
            dynamic_sidebar('footer-sidebar-3');
            }
            ?>
          </div>
        </div>
      </div>
    </footer>
    <div class="container">
	  <div class="twelve columns bottom-footer-widget">
            <?php
            if(is_active_sidebar('footer-sidebar-4')){
            dynamic_sidebar('footer-sidebar-4');
            }
            ?>
      </div>
    </div>	 
  </div> <!-- end of flowing content -->
	  <?php wp_footer(); ?>
</body>

</html>
