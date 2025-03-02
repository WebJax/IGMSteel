<?php

/**
 * Login Customization
 * 
 * - Add custom login logo
 * - Add custom login logo URL
 * - Add custom login logo title
 * 
 * @see https://developer.wordpress.org/reference/functions/add_theme_support/#custom-logo
 * 
 * @package IGMSteel - functions.php
 */

/**
 *
 * Personliggør login skærm
 */
function imgsteel_login_logo() { 
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );?>
      <style type="text/css">
        body.login.login-action-login.wp-core-ui.locale-da-dk {
          background-image: url(/wp-content/uploads/2024/09/professional-modern-plasma-cutter-metal-factory-igm-steel.webp);
          background-repeat: no-repeat;
          background-size: cover;
          background-blend-mode: overlay;
        }
        
        #login h1 a, .login h1 a {
          background-image: url(/wp-content/uploads/2024/09/logo-light.png);
          height: 160px;
          width: 160px;
          background-size: 160px 160px;
          background-repeat: no-repeat;
          padding-bottom: 0px;
        }
      </style>
  <?php }
  add_action( 'login_enqueue_scripts', 'imgsteel_login_logo' );
  
  function my_login_logo_url() {
      return home_url();
  }
  add_filter( 'login_headerurl', 'my_login_logo_url' );
  
  function my_login_logo_url_title() {
      return get_bloginfo('name') . ' - ' . get_bloginfo('description');
  }
  add_filter( 'login_headertext', 'my_login_logo_url_title' );