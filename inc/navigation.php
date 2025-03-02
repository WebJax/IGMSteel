<?php

/**
 * Navigation
 * 
 * - Remove the <div> surrounding the dynamic navigation to cleanup markup
 * - Remove Injected classes, ID's and Page ID's from Navigation <li> items
 * - Setup menus
 * - Enable shortcodes for menu navigation - Search + miniCart
 * 
 * 
 * @package IGMSteel - functions.php
 */


// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}
// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's

//setup menus
function register_jaxgenerel_menus() {
    register_nav_menus(
      array(
        'primary' => __( 'Primary' ),
        'top' => __ ('Top'),
      )
    );
  }
  add_action( 'init', 'register_jaxgenerel_menus' );
  
  /**
   * Enable shortcodes for menu navigation.
   */
  
  function jaxgeneral_shortcode_inside_top_bar ( $items, $args ) {
      if ($args->theme_location == 'primary') {
          $items .= '<li class="menu-soeg">' . do_shortcode( "[smart_search id=1]" ) . '</li>';
      }
      return $items;
  }
  add_filter( 'wp_nav_menu_items', 'jaxgeneral_shortcode_inside_top_bar', 10, 2 );