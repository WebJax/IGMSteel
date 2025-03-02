<!DOCTYPE html>
<html lang="da">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if (is_home()) { ?>
 		<meta name="description" content="JaxWeb - Hjemmesider der er til at gå til" />
	<?php }
	wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<header class="page-header">
    <div class="topmenu-container container">
      <div class="two columns logo-title">
        <div class="center-logo">
          <?php if ( function_exists( 'the_custom_logo' ) ) { the_custom_logo(); } ?>
        </div>
		<div class="site-title">
		  <?php echo get_bloginfo( 'name' ); ?>	
		</div>
      </div>
      <div class="ten columns">
        <div id="nav-icon4" class="menu">
          <span></span>
          <span></span>
          <span></span>
        </div>
        <?php wp_nav_menu( array( 'theme_location' => 'top' ) ); ?>
        <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
      </div>
    </div>
  </header>
