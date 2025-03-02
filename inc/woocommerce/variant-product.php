<?php

// Når produktet tilføjes til kurven, gemmes den beregnede pris som en del af kurvens metadata
add_filter('woocommerce_add_cart_item_data', 'save_custom_price_in_cart_data', 10, 2);
function save_custom_price_in_cart_data($cart_item_data, $product_id) {
    if (isset($_POST['custom_price'])) {
        $cart_item_data['custom_price'] = floatval($_POST['custom_price']); // Gem den beregnede pris som metadata
    }
	if (isset($_POST['custom_qty'])) {
        $cart_item_data['quantity'] = absint($_POST['custom_qty']); // Gem antallet som metadata
    }
    if (isset($_POST['price_option'])) {
        if ($_POST['price_option'] == 'full_area_price') {
            $product = wc_get_product( $product_id );
            // find ud af om der er sat pa_length woocommerce egenskab og hvis der så tildel denne værdi til custom_length metadata
            if ($product->get_attribute( 'pa_laengde' ) > 0) {
                $cart_item_data['custom_length'] = floatval($product->get_attribute( 'pa_laengde' ));
            }
        } elseif ($_POST['price_option'] == 'input_length') {
            $cart_item_data['custom_length'] = floatval($_POST['input_length']);
        } elseif ($_POST['price_option'] == 'input_length_height') {
            $cart_item_data['custom_length'] = floatval($_POST['input_length']);
            $cart_item_data['custom_width'] = floatval($_POST['input_width']);
        }
    }

    return $cart_item_data;
}

// Prisen vises korrekt i kurven
add_filter('woocommerce_cart_item_price', 'set_price_from_cart_data', 10, 3);
function set_price_from_cart_data($price, $cart_item, $cart_item_key) {
    if (isset($cart_item['custom_price'])) {
        $new_price = wc_price($cart_item['custom_price']); // Formater prisen som WooCommerce-pris
        return $new_price;
    }
    return $price; // Returner original pris, hvis der ikke er custom_price
}

// Længde og Højde vises korrekt i kurven
add_filter('woocommerce_cart_item_quantity', function ($product_quantity, $cart_item_key, $cart_item) {
    if (isset($cart_item['custom_length']) && isset($cart_item['custom_width'])) {
        $extra_text = '<br><small>Dimensioner: ' . esc_html($cart_item['custom_length']) . ' mm x ' . esc_html($cart_item['custom_width']) . ' mm</small>';
    } elseif (isset($cart_item['custom_length'])) {
        $extra_text = '<br><small>Længde: ' . esc_html($cart_item['custom_length']) . ' mm</small>';
    } else {
        $extra_text = ''; // Ingen tekst hvis ingen værdier er sat
    }

    return $product_quantity . $extra_text;
}, 10, 3);

add_filter('woocommerce_checkout_cart_item_quantity', function ($quantity, $cart_item, $cart_item_key) {
    if (isset($cart_item['custom_length']) && isset($cart_item['custom_width'])) {
        $quantity .= '<br><small>Dimensioner: ' . esc_html($cart_item['custom_length']) . ' mm x ' . esc_html($cart_item['custom_width']) . ' mm</small>';
    } elseif (isset($cart_item['custom_length'])) {
        $quantity .= '<br><small>Længde: ' . esc_html($cart_item['custom_length']) . ' mm</small>';
    }
    return $quantity;
}, 10, 3);

add_filter('woocommerce_get_cart_item_from_session', function ($cart_item, $values, $cart_item_key) {
    if (isset($values['custom_length'])) {
        $cart_item['custom_length'] = $values['custom_length'];
    }
    if (isset($values['custom_width'])) {
        $cart_item['custom_width'] = $values['custom_width'];
    }
    return $cart_item;
}, 10, 3);

add_filter('woocommerce_cart_item_name', function ($name, $cart_item, $cart_item_key) {
    if (isset($cart_item['custom_length']) && isset($cart_item['custom_width'])) {
        $name .= '<br><small>Dimension: ' . esc_html($cart_item['custom_length']) . ' mm x ' . esc_html($cart_item['custom_width']) . ' mm</small>';
    } elseif (isset($cart_item['custom_length'])) {
        $name .= '<br><small>Længde: ' . esc_html($cart_item['custom_length']) . ' mm</small>';
    }
    return $name;
}, 10, 3);

add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (isset($values['custom_length'])) {
        $item->add_meta_data('Længde', esc_html($values['custom_length']) . ' mm', true);
    }
    if (isset($values['custom_width'])) {
        $item->add_meta_data('Bredde', esc_html($values['custom_width']) . ' mm', true);
    }
    if (isset($values['custom_price'])) {
        $item->add_meta_data('Tilpasset pris', wc_price($values['custom_price']), true);
    }
}, 10, 4);

add_filter('woocommerce_order_item_name', function ($item_name, $item, $is_visible) {
    $custom_length = $item->get_meta('Længde', true);
    $custom_width = $item->get_meta('Bredde', true);
    $custom_price = $item->get_meta('Tilpasset pris', true);

    if ($custom_length && $custom_width) {
        $item_name .= '<br><small>Dimensioner: ' . esc_html($custom_length) . ' x ' . esc_html($custom_width) . ' mm</small>';
    } elseif ($custom_length) {
        $item_name .= '<br><small>Dimensioner: ' . esc_html($custom_length) . ' mm</small>';
    }

    if ($custom_price) {
        $item_name .= '<br><small>Tilpasset pris: ' . esc_html($custom_price) . '</small>';
    }

    return $item_name;
}, 10, 3);

// Prisen anvendes under beregning af subtotaler
add_action('woocommerce_before_calculate_totals', 'apply_custom_price_to_cart', 10, 1);
function apply_custom_price_to_cart($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {		
		if (isset($cart_item['custom_price'])) {
            // Opdater kurvens pris korrekt
            $cart_item['data']->set_price($cart_item['custom_price']);
        }
    }
}
