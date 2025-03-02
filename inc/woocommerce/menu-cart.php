<?php
/**
 * Add WooCommerce Cart Menu Item with Item Count and Dropdown
 * This code adds a shopping cart icon to your WordPress menu with a counter showing the number of items
 * and a dropdown displaying cart contents on hover/click
 */

// Don't run if WooCommerce isn't active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return;
}

/**
 * Add cart icon with item count and dropdown to menu
 */
function add_cart_dropdown_to_menu($items, $args) {
    // Only add to the primary menu - change 'primary' to your theme's menu location ID if needed
    if ($args->theme_location == 'primary') {
        
        // Get cart count
        $cart_count = WC()->cart->get_cart_contents_count();
        
        // Start building the cart menu item
        $cart_item = '<li class="menu-item cart-menu-item">';
        $cart_item .= '<a href="' . wc_get_cart_url() . '" class="cart-contents" title="' . __('View your shopping cart', 'text-domain') . '">';
        $cart_item .= '<span class="ion-ios-cart"></span>';
        
        // Show count only if there are items in cart
        if ($cart_count > 0) {
            $cart_item .= '<span class="cart-contents-count">' . esc_html($cart_count) . '</span>';
        }
        
        $cart_item .= '</a>';
        
        // Add dropdown with cart content
        $cart_item .= '<ul class="cart-dropdown">';
        
        if ($cart_count > 0) {
			$cart_item .= '<ul class="cart-dropdown-items-list">';
            // Loop through cart items
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item_values) {
                $_product = wc_get_product($cart_item_values['data']->get_id());
                
                $cart_item .= '<li class="cart-dropdown-item">';
                
                // Product thumbnail
                $thumbnail = $_product->get_image(array(50, 50));
                $cart_item .= '<div class="cart-item-image">' . $thumbnail . '</div>';

                // Product name
                if ($_product->is_type('variation')) {
                    $parent_id = $_product->get_parent_id();
                    $parent_product = wc_get_product($parent_id);
                    $parent_title = $parent_product->get_title();
                    $product_name = $parent_title;
                } else {
                    $product_name = $_product->get_name();
                }
    
                // Custom dimensions
                if (isset($cart_item_values['custom_length']) && isset($cart_item_values['custom_width'])) {
                    $product_name .= '<small>Dimension: ' . esc_html($cart_item_values['custom_length']) . ' mm x ' . esc_html($cart_item_values['custom_width']) . ' mm</small>';
                } elseif (isset($cart_item_values['custom_length'])) {
                    $product_name .= '<small>Længde: ' . esc_html($cart_item_values['custom_length']) . ' mm</small>';
                }

                // Product details
                $cart_item .= '<div class="cart-item-details">';
                $cart_item .= '<span class="cart-item-title">' . $product_name . '</span>';
                $cart_item .= '<span class="cart-item-quantity">' . $cart_item_values['quantity'] . ' x ' . 
                              wc_price($cart_item_values['custom_price']) . '</span>';
                $cart_item .= '</div>';
                
                // Remove button
                $cart_item .= '<a href="' . wc_get_cart_remove_url($cart_item_key) . '" class="cart-item-remove" 
                              title="' . __('Fjern dette produkt', 'text-domain') . '">×</a>';                
                $cart_item .= '</li>';
            }
			$cart_item .= '</ul>';
            
            // Subtotal
            $cart_item .= '<li class="cart-dropdown-subtotal">';
            $cart_item .= '<span>' . __('Subtotal', 'text-domain') . ':</span>';
            $cart_item .= '<span>' . WC()->cart->get_cart_subtotal() . '</span>';
            $cart_item .= '</li>';
            
            // Buttons
            $cart_item .= '<li class="cart-dropdown-buttons">';
            $cart_item .= '<a href="' . wc_get_cart_url() . '" class="wp-block-button__link">' . __('Vis kurv', 'text-domain') . '</a>';
            $cart_item .= '<a href="' . wc_get_checkout_url() . '" class="wp-block-button__link">' . __('Kassen', 'text-domain') . '</a>';
            $cart_item .= '</li>';
        } else {
            // Empty cart message
            $cart_item .= '<li class="cart-dropdown-empty">';
            $cart_item .= __('Din vogn er tom.', 'text-domain');
            $cart_item .= '</li>';
            
            // Shop button
            $cart_item .= '<li class="cart-dropdown-buttons">';
            $cart_item .= '<a href="' . get_permalink(wc_get_page_id('shop')) . '" class="wp-block-button__link">' . __('Produkter', 'text-domain') . '</a>';
            $cart_item .= '</li>';
        }
        
        $cart_item .= '</ul>';
        $cart_item .= '</li>';
        
        // Add cart item to the end of the menu
        $items .= $cart_item;
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'add_cart_dropdown_to_menu', 10, 2);

/**
 * Ensure cart contents update when products are added to the cart via AJAX
 */
function cart_dropdown_fragments($fragments) {
    ob_start();
    ?>
    <ul class="cart-dropdown">
        <?php 
        $cart_count = WC()->cart->get_cart_contents_count();

        if ($cart_count > 0) { ?>
			<ul class="cart-dropdown-items-list">
            <?php // Loop through cart items
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item_values) {
                $_product = wc_get_product($cart_item_values['data']->get_id());
                ?>
                <li class="cart-dropdown-item">
                    <div class="cart-item-image">
                        <?php echo $_product->get_image(array(50, 50)); ?>
                    </div>

                    <?php
                    // Product name
                    if ($_product->is_type('variation')) {
                        $parent_id = $_product->get_parent_id();
                        $parent_product = wc_get_product($parent_id);
                        $parent_title = $parent_product->get_title();
                        $product_name = $parent_title;
                    } else {
                        $product_name = $_product->get_name();
                    }
        
                    // Custom dimensions
                    if (isset($cart_item_values['custom_length']) && isset($cart_item_values['custom_width'])) {
                        $product_name .= '<small>Dimension: ' . esc_html($cart_item_values['custom_length']) . ' mm x ' . esc_html($cart_item_values['custom_width']) . ' mm</small>';
                    } elseif (isset($cart_item_values['custom_length'])) {
                        $product_name .= '<small>Længde: ' . esc_html($cart_item_values['custom_length']) . ' mm</small>';
                    }
                    ?>

                    <div class="cart-item-details">
                        <span class="cart-item-title"><?php echo $product_name; ?></span>
                        <span class="cart-item-quantity">
                            <?php echo $cart_item_values['quantity']; ?> x 
                            <?php echo wc_price($cart_item_values['custom_price']); ?>
                        </span>
                    </div>
                    <a href="<?php echo wc_get_cart_remove_url($cart_item_key); ?>" class="cart-item-remove" 
                       title="<?php esc_attr_e('Fjern dette produkt', 'text-domain'); ?>">×</a>
                </li>
                <?php
            }
            
            // Subtotal
            ?>
			</ul>
            <li class="cart-dropdown-subtotal">
                <span><?php _e('Subtotal', 'text-domain'); ?>:</span>
                <span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
            </li>
            
            <!-- Buttons -->
            <li class="cart-dropdown-buttons">
                <a href="<?php echo wc_get_cart_url(); ?>" class="wp-block-button__link">
                    <?php _e('Se kurv', 'text-domain'); ?>
                </a>
                <a href="<?php echo wc_get_checkout_url(); ?>" class="wp-block-button__link">
                    <?php _e('Kassen', 'text-domain'); ?>
                </a>
            </li>
            <?php
        } else {
            // Empty cart message
            ?>
            <li class="cart-dropdown-empty">
                <?php _e('Din kurv er tom.', 'text-domain'); ?>
            </li>
            
            <!-- Shop button -->
            <li class="cart-dropdown-buttons">
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="button shop-button">
                    <?php _e('Se produkter', 'text-domain'); ?>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php
    $fragments['ul.cart-dropdown'] = ob_get_clean();
    
    // Also update the count on the cart icon
    ob_start();
    ?>
    <a href="<?php echo wc_get_cart_url(); ?>" class="cart-contents" title="<?php _e('Se din kurv', 'text-domain'); ?>">
        <span class="ion-ios-cart-outline"></span>
        <?php if ($cart_count > 0) : ?>
            <span class="cart-contents-count"><?php echo esc_html($cart_count); ?></span>
        <?php endif; ?>
    </a>
    <?php
    $fragments['a.cart-contents'] = ob_get_clean();
    
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'cart_dropdown_fragments');

/**
 * Add jQuery script to handle dropdown behavior
 */
function cart_dropdown_scripts() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Toggle dropdown on hover (or click for mobile)
        $( '.cart-menu-item' ).hover(
            function() {
                $(this).find('.cart-dropdown').stop(true, true).fadeIn(200);
            },
            function() {
                $(this).find('.cart-dropdown').stop(true, true).fadeOut(200);
            }
        );
        
        // For touch devices, toggle dropdown on click
        $( document ).on('click', 'a.cart-contents', function(e) {
            if (window.innerWidth < 550) { // Adjust breakpoint as needed
                e.preventDefault();
                $('.cart-dropdown').slideToggle(200);
            }
        });
		
		// Move cart according to screen size
		var currentPosition = 'desktop';

		function moveCartDropdown() {
			var windowWidth = $(window).width();

			if (windowWidth < 550 && currentPosition !== 'mobile') {
				$('.cart-contents').insertBefore('#nav-icon4');
				$('.cart-dropdown').insertBefore('#nav-icon4');
				currentPosition = 'mobile';
			} else if (windowWidth >= 550 && currentPosition !== 'desktop') {
				$('.cart-contents').appendTo('.menu-item.cart-menu-item');
				$('.cart-dropdown').insertAfter('.cart-contents');
				currentPosition = 'desktop';
			}
		}

		moveCartDropdown();
		$(window).resize(moveCartDropdown);
		
    });
    </script>
    <?php
}
add_action('wp_footer', 'cart_dropdown_scripts');

/**
 * Add the necessary CSS for styling the cart icon, count and dropdown
 */
function cart_dropdown_styles() {
    ?>
    <style>
        /* Cart Icon Styles */
        .cart-menu-item {
            position: relative;
        }
        
        .cart-contents {
            display: flex;
            align-items: center;
			position: relative;
        }
        
        .ion-ios-cart {
            font-size: 28px;
            position: relative;
            top: -8px;
        }

        .cart-contents:hover {
            border-bottom-color: transparent !important;
        }
        
        .cart-contents-count {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 11px;
            background-color: #ff6b6b;
            color: #fff;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }
        
        /* Dropdown Styles */
        .cart-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            top: 20px;
            min-width: 300px;
            background: #fff;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            z-index: 999;
            padding: 10px;
            display: none;
        }
		
		/* Cart Items Container */
		ul.cart-dropdown-items-list {
		    max-height: 250px;
		    overflow: auto;
		}
        
        /* Cart Items in Dropdown */
        .cart-dropdown-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            position: relative;
        }
        
        .cart-item-image {
            width: 50px;
            margin-right: 10px;
        }
        
        .cart-item-details {
            flex-grow: 1;
			display: flex;
		    flex-direction: column;
        }
        
        .cart-item-title small {
            font-size: 1.4rem;
            font-weight: 400;
            margin-top: -10px;
        }

        .cart-item-title {
            display: flex;
            font-weight: bold;
            margin-bottom: 0;
            flex-direction: column;
			font-size: 1.5rem;
        }
		
        .cart-item-quantity {
            font-size: 1.4rem;
            color: #555;
        }
        
		.cart-item-quantity span.woocommerce-Price-amount.amount {
 		   font-size: 1.6rem !important;
		}
		
        .cart-item-remove {
            font-size: 18px;
            line-height: 1;
            color: #999;
            text-decoration: none;
            margin-left: 10px;
        }
        
        .cart-item-remove:hover {
            color: #f00;
        }
        
        /* Subtotal */
        .cart-dropdown-subtotal {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-weight: bold;
        }
        
        /* Action Buttons */
        .cart-dropdown-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        
        .cart-dropdown-buttons .button {
            padding: 8px 15px;
            text-align: center;
            text-decoration: none;
            border-radius: 3px;
            font-weight: bold;
            font-size: 0.9em;
        }
		
		#menu-hovedmenu.menu li a.wp-block-button__link {
    		color: var(--color-light);
		}
        
        .cart-button {
            background-color: #f0f0f0;
            color: #333;
        }
        
        .checkout-button, .shop-button {
            background-color: #7dc855;
            color: #fff;
        }
        
        /* Empty Cart Message */
        .cart-dropdown-empty {
            padding: 15px 0;
            text-align: center;
            color: #777;
        }
        		
		.mobile-menu.cart-menu-item {
			display: none;
			position: fixed;
			top: 4px;
			right: 60px;
		}
        
        /* Responsive adjustments */
        @media (max-width: 550px) {
			.cart-contents {
				position: fixed;
				top: 4px;
				right: 60px;
			}
			.cart-dropdown {
				top: 40px;
				right: 10px;
			}
			.cart-dropdown span.woocommerce-Price-amount.amount {
				font-size: 2rem;
			}
        }
    </style>
    <?php
}
add_action('wp_head', 'cart_dropdown_styles');