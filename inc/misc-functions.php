<?php

/**
 * Misc Functions
 * 
 * - Add custom admin inline styles - disable theme editor message
 * - Filter expirationtime for preview post by 14 days
 * - Add iframe shortcode
 * - Add preload to css
 * 
 * 
 * @package IGMSteel - functions.php
 */




/**
 * Filter expirationtime for preview post by 14 days.
 *
 * @param
 * @return int modified expirationtime.
 */
add_filter( 'ppp_nonce_life', 'my_nonce_life' );
function my_nonce_life() {
    return 60 * 60 * 24 * 14; // 14 days
}

/* Add iframe shortcode */
add_shortcode('iframe', 'dianalund_iframe');
function dianalund_iframe($atts, $content) {
 if (!$atts['width']) { $atts['width'] = 630; }
 if (!$atts['height']) { $atts['height'] = 1500; }

 return '<iframe border="0" class="shortcode_iframe" style="border: 0;" src="' . $atts['src'] . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '"></iframe>';
}

//add async css load
function add_preload_to_css ($html, $handle, $href, $media) {
	return '<link id="'.$handle.'" rel="preload" href="'.$href.'" as="style" onload="this.rel=\'stylesheet\'"><noscript><link id="'.$handle.'" rel="stylesheet" href="'.$href.'"></noscript>';
}
add_filter( 'style_loader_tag', 'add_preload_to_css', 10 ,4 );