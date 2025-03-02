<?php

/**
 * Scripts and Styles
 * 
 * - Enqueue styles and scripts
 * 
 * 
 * @package IGMSteel - functions.php
 */



/**
 * Enqueue styles and scripts
 *
 * Adds fonts, theme styles, admin styles and jQuery from the `js` folder.
 *
 * @since 1.0.0
 */
function jaxgenerel_enqueue () {
	// fonts
	wp_enqueue_style( 'ioniconscss' , get_template_directory_uri() . '/css/ionicons.min.css');
    wp_enqueue_style( 'google-fonts-titillium-web', 'https://fonts.googleapis.com/css2?family=Titillium+Web:wght@400;600&display=swap', false );

	// theme styles
	wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '1.0.2' );
  
  	// jQuery
  	wp_enqueue_script( 'jsscripts', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0.3', true );
}
add_action( 'wp_enqueue_scripts', 'jaxgenerel_enqueue' );

function jaxgenerel_custom_admin_styles() {
	wp_enqueue_style( 'admin_css_foo', get_template_directory_uri() . '/css/admin-styles.css', false, '1.0.0' );
}
add_action('admin_enqueue_scripts', 'jaxgenerel_custom_admin_styles');


