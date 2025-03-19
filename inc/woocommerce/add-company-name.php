<?php
// Tilføj et felt til kurven, hvor brugeren kan indtaste et firmanavn
add_action('woocommerce_before_cart_table', function() {
    ?>
    <div class="custom-cart-company-field">
        <label for="cart_company_name"><?php _e('Firmanavn', 'woocommerce'); ?></label>
        <input type="text" name="cart_company_name" id="cart_company_name" value="<?php echo esc_attr(WC()->session->get('cart_company_name', '')); ?>">
    </div>
    <?php
});

// Gem værdien i sessionen, når kurven opdateres
add_action('woocommerce_cart_updated', function() {
    if (isset($_POST['cart_company_name'])) {
        WC()->session->set('cart_company_name', sanitize_text_field($_POST['cart_company_name']));
    }
});

// Tilføj firmanavnet til checkout
add_action('woocommerce_after_order_notes', function($checkout) {
    echo '<div id="custom_checkout_company_field">';
    woocommerce_form_field('company_name', [
        'type'        => 'text',
        'class'       => ['form-row-wide'],
        'label'       => __('Firmanavn', 'woocommerce'),
        'placeholder' => __('Indtast firmanavn her', 'woocommerce'),
    ], $checkout->get_value('company_name'));
    echo '</div>';
});

// Gem firmanavnet i ordren
add_action('woocommerce_checkout_update_order_meta', function($order_id) {
    if (!empty($_POST['company_name'])) {
        update_post_meta($order_id, 'company_name', sanitize_text_field($_POST['company_name']));
    }
});

// Vis firmanavnet i WooCommerce-ordredetaljerne i admin
add_action('woocommerce_admin_order_data_after_billing_address', function($order) {
    $company_name = get_post_meta($order->get_id(), '_company_name', true);
    if ($company_name) {
        echo '<p><strong>' . __('Firmanavn:', 'woocommerce') . '</strong> ' . esc_html($company_name) . '</p>';
    }
});

// Autofyld feltet fra kurven til checkout
add_filter('woocommerce_checkout_get_value', function($value, $input) {
    if ($input === 'company_name' && empty($value)) {
        return WC()->session->get('cart_company_name', '');
    }
    return $value;
}, 10, 2);