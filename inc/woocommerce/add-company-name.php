<?php
add_filter('woocommerce_billing_fields', function($fields) {
    // Sæt firmanavnets prioritet til lige efter efternavn (standard er 30)
    $fields['billing_company']['priority'] = 25;
    $fields['billing_company']['required'] = false; // Gør det valgfrit (eller true hvis det skal være påkrævet)
    $fields['billing_company']['autocomplete'] = 'organization'; // Hjælper med autofill
    return $fields;
});

add_filter('woocommerce_get_country_locale', function($locale) {
    foreach ($locale as $country => $fields) {
        if (isset($locale[$country]['billing_company'])) {
            $locale[$country]['billing_company']['required'] = false; // Gør valgfrit (eller true for at gøre det påkrævet)
            $locale[$country]['billing_company']['hidden'] = false; // Sørg for, at det ikke er skjult
        }
    }
    return $locale;
});

add_filter('woocommerce_billing_fields', function($fields) {
    $fields['billing_cvr'] = [
        'type'        => 'text',
        'label'       => __('CVR-nummer', 'woocommerce'),
        'placeholder' => __('Indtast CVR-nummer', 'woocommerce'),
        'required'    => false, // Sæt til true, hvis det skal være påkrævet
        'class'       => ['form-row-wide'],
        'priority'    => 26, // Placeres lige efter firmanavn
    ];
    return $fields;
});

add_action('woocommerce_checkout_update_order_meta', function($order_id) {
    if (!empty($_POST['billing_cvr'])) {
        update_post_meta($order_id, '_billing_cvr', sanitize_text_field($_POST['billing_cvr']));
    }
});

add_action('woocommerce_admin_order_data_after_billing_address', function($order) {
    $cvr = get_post_meta($order->get_id(), '_billing_cvr', true);
    if (!empty($cvr)) {
        echo '<p><strong>' . __('CVR-nummer:', 'woocommerce') . '</strong> ' . esc_html($cvr) . '</p>';
    }
});