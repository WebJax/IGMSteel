<?php

/**
 * Theme Support
 * 
 * - Register widgets
 * - Register Theme Features
 * - Add featured image size
 * - Add tags and categories to pages
 * - Add custom logo
 * - Add align-wide
 * - Add header image
 * - Add custom CSS
 * - Add custom editor style
 * - Add Translation
 * - Add widgets
 * - Filter the excerpt length to 20 characters
 * - Filter the "read more" excerpt string link to the post
 * - Allow shortcodes in the description field
 * 
 * @see https://developer.wordpress.org/reference/functions/add_theme_support
 * 
 * @package IGMSteel - functions.php
 */

/**
 * Set featured image size (1920x480) adding theme support for post thumbnails and custom logo
 * 
 * @return void
 * @see https://developer.wordpress.org/reference/hooks/add_theme_support/#custom-logo
 */
add_theme_support( 'custom-logo' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'align-wide' );
add_image_size("header-image", 1920, 480, true );

/**
 * Modifies the list of image sizes in the Add Media modal to include our custom "Header Image" size.
 *
 * @param array $sizes The list of image sizes. The key is the image size name, the value is the translated human-readable name.
 * @return array The modified list of image sizes.
 */
function wpshout_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'header-image' => __( 'Header Image' ),
    ) );
}
add_filter( 'image_size_names_choose', 'wpshout_custom_sizes' );

// Adding tags and categories to pages
function add_taxonomy_to_pages () {
	register_taxonomy_for_object_type('post_tag', 'page');
	register_taxonomy_for_object_type('category', 'page');
	
	// Add to the init hook of your theme functions.php file
	add_filter('request', 'my_expanded_request');  
 
	function my_expanded_request($q) {
    if (isset($q['tag']) || isset($q['category_name'])) 
                $q['post_type'] = array('post', 'page');
    return $q;
	}
}

add_action ('init', 'add_taxonomy_to_pages');

// Register Theme Features
function custom_theme_features()  {

	// Add theme support for Post Formats
	add_theme_support( 'post-formats', array( 'gallery' ) );

	// Add theme support for document Title tag
	add_theme_support( 'title-tag' );

	// Add theme support for custom CSS in the TinyMCE visual editor
	add_editor_style( array('custom-editor-style.css','https://fonts.googleapis.com/css?family=Open+Sans|Roboto') );

	// Add theme support for Translation
	load_theme_textdomain( 'text_domain', get_template_directory() . '/language' );
}
add_action( 'after_setup_theme', 'custom_theme_features' );

/**
 * Register widgets
 *
 */

 function register_widgets_jaxgenerel () {
	register_sidebar( array(
		'name' => 'Footer left',
		'id' => 'footer-sidebar-1',
		'description' => 'Appears in the footer area',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => 'Footer middle',
		'id' => 'footer-sidebar-2',
		'description' => 'Appears in the footer area',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => 'Footer right',
		'id' => 'footer-sidebar-3',
		'description' => 'Appears in the footer area',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => 'Footer bottom full width',
		'id' => 'footer-sidebar-4',
		'description' => 'Appears in the footer area for copyrights',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
}

add_action ( 'widgets_init', 'register_widgets_jaxgenerel' );

/**
 * Filter the excerpt length to 20 characters.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function wpdocs_custom_excerpt_length( $length ) {
    return 15;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );


/**
 * Filter the "read more" excerpt string link to the post.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function wpdocs_excerpt_more( $more ) {
    return '[Læs mere]';
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );


// Stops WordPress from applying the wp_filter_kses filter to the excerpt
remove_filter( 'pre_term_description', 'wp_filter_kses' ); 

// Allow shortcodes in the description field
add_filter('term_description', 'shortcode_unautop');
add_filter('term_description', 'do_shortcode');