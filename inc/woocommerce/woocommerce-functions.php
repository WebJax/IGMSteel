<?php

/**
 * WooCommerce functions
 * 
 * - Modifies the product gallery template to use a custom template
 * - Brugerdefineret breadcrumb funktion til WooCommerce
 * - Vis hovedkategorier med billeder og de første 5 underkategorier på WooCommerce shop-side
 * - Only show products in the front-end search results
 * - Custom Price Calculation Fields with Radio Buttons
 * 
 * @package IGMSteel - functions.php
 */

// Inkluder WooCommerce Tilpassede pris felter 
require get_template_directory() . '/inc/woocommerce/woo-custom-price-fields.php';

// Inkluder ekstra kolonner i produktlisten i admin
require get_template_directory() . '/inc/woocommerce/extra-product-list-columns.php';

// Inkluder forbedret søgning for WooCommerce attributter
require get_template_directory() . '/inc/woocommerce/forbedret-woocommerce-attributsogning.php';

// Inkluder produktet i kurven
require get_template_directory() . '/inc/woocommerce/variant-product.php';

// Inkluder produktet i kurven
require get_template_directory() . '/inc/woocommerce/menu-cart.php';

// Inkluder firmanavn i ordrer
require get_template_directory() . '/inc/woocommerce/add-company-name.php';

/**
 * Modifies the product gallery template to use a custom template.
 *
 * This function is a callback for the `wc_get_template` filter.
 * It checks if the requested template is the product image template,
 * and if so, returns the path to a custom template in the theme's
 * assets/inc directory.
 *
 * @param string $located       The located template.
 * @param string $template_name The name of the template.
 * @param array  $args          The arguments passed to the wc_get_template function.
 * @param string $template_path The path to the template.
 * @param string $default_path  The default path to the template.
 *
 * @return string The modified template path.
 */
function modify_product_gallery_template( $located, $template_name, $args, $template_path, $default_path ) {

	if ( 'single-product/product-image.php' == $template_name ) {
		$located = get_template_directory().'/inc/woocommerce/single-product-gallery.php';
	}
	return $located;
}
add_filter( 'wc_get_template', 'modify_product_gallery_template', 10, 5 );


// Brugerdefineret breadcrumb funktion til WooCommerce
function custom_breadcrumb() {

    // TODO: Hvis ikke der er nogen produkter, skriv tilbage til produktoversigt side



    // Start HTML for breadcrumbs
    $breadcrumb = '<nav class="custom-breadcrumb" aria-label="breadcrumb"><a href="' . home_url() . '" class="home-link igm-breadcrumb">Hjem</a> <span class="breadcrumb-separator">&raquo;</span> ';

    if (is_shop()) {
        // WooCommerce shop side
        $breadcrumb .= 'Produkter';
    } elseif (is_product_category() || is_product_tag()) {
        // Produkt kategori eller tag side
        $breadcrumb .= '<a href="' . get_permalink(wc_get_page_id('shop')) . '" class="igm-breadcrumb">Produkter</a> <span class="breadcrumb-separator">&raquo;</span> ';

        // Get the current category
        $current_category = get_queried_object();

        // Get the parent categories
        $parent_categories = get_ancestors($current_category->term_id, 'product_cat');

        // Reverse the array to show the parent categories in the correct order
        $parent_categories = array_reverse($parent_categories);

        // Loop through the parent categories and add them to the breadcrumb
        foreach ($parent_categories as $parent_category_id) {
            $parent_category = get_term($parent_category_id, 'product_cat');
            $breadcrumb .= '<a href="' . get_term_link($parent_category) . '">' . $parent_category->name . '</a> <span class="breadcrumb-separator">&raquo;</span> ';
        }

        $breadcrumb .= single_term_title('', false);

    } elseif (is_product()) {
        // Enkel produkt side
        $breadcrumb .= '<a href="' . get_permalink(wc_get_page_id('shop')) . '">Produkter</a> <span class="breadcrumb-separator">&raquo;</span> ';
        $categories = get_the_terms(get_the_ID(), 'product_cat');
        if ($categories && !is_wp_error($categories)) {
            $category = current($categories);

            // Get the current product category
            $current_category = $category;

            // Get the parent categories
            $parent_categories = get_ancestors($current_category->term_id, 'product_cat');

            // Reverse the array to show the parent categories in the correct order
            $parent_categories = array_reverse($parent_categories);

            // Loop through the parent categories and add them to the breadcrumb
            foreach ($parent_categories as $parent_category_id) {
                $parent_category = get_term($parent_category_id, 'product_cat');
                $breadcrumb .= '<a href="' . get_term_link($parent_category) . '">' . $parent_category->name . '</a> <span class="breadcrumb-separator">&raquo;</span> ';
            }
            $breadcrumb .= '<a href="' . get_term_link($category) . '">' . $category->name . '</a> <span class="breadcrumb-separator">&raquo;</span> ';
        }
        $breadcrumb .= '<span class="igm-breadcrumb current">' . get_the_title() . '</span>';
    } /* elseif (is_cart()) {
        // Indkøbskurv side
        $breadcrumb .= 'Kurv';
    } elseif (is_checkout()) {
        // Checkout side
        $breadcrumb .= 'Kasse';
    } elseif (is_account_page()) {
        // Min konto side
        $breadcrumb .= 'Min konto';
    } */ else {
        // Standard fallback
        $breadcrumb .= '<span class="igm-breadcrumb fallback-title">' . get_the_title() . '</span>';
    }

    $breadcrumb .= '</nav>';

    echo $breadcrumb;
}
add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Vis hovedkategorier med billeder og de første 5 underkategorier på WooCommerce shop-side
function display_top_categories_with_subcategories_and_images() {
    // Tjek om vi er på WooCommerce shop-siden og om produkt loopet findes
    if (is_shop() && woocommerce_product_loop()) {
        // Hent alle topniveau kategorier (forældre kategorier)
        $top_categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
            'parent'     => 0, // Kun topniveau kategorier
        ));

        if (!empty($top_categories) && !is_wp_error($top_categories)) {
            echo '<ul class="products columns-3 top-categories-list">';

            foreach ($top_categories as $category) {
                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                $image_html = wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail', array('alt' => $category->name), true);

                echo '<li class="product-category product top-category">';
                // Vis kategori billede
                echo '<a href="' . get_term_link($category) . '">' . $image_html . '</a>';

                // Vis kategori navn
                echo '<div class="category-' . $category->name . ' category-title-subtitle"><h2><a class="woocommerce-loop-category__title" href="' . get_term_link($category) . '">' . $category->name . '</a></h2>';

                // Hent de første 5 underkategorier til denne topkategori
                $subcategories = get_terms(array(
                    'taxonomy'   => 'product_cat',
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'hide_empty' => true,
                    'parent'     => $category->term_id,
                    'number'     => 5, // Begræns til 5 underkategorier
                ));

                if (!empty($subcategories) && !is_wp_error($subcategories)) {
                    echo '<ul class="subcategory-list">';
                    foreach ($subcategories as $subcategory) {
                        echo '<li><a href="' . get_term_link($subcategory) . '">' . $subcategory->name . '</a></li>';
                    }
                    echo '</ul>';
                }

                echo '</div></li>';
            }

            echo '</ul>';
        }
    }
}
add_action('woocommerce_before_shop_loop', 'display_top_categories_with_subcategories_and_images', 15);

// Vis subkategorier før produktloopen på kategorisider
function display_subcategories_only() {
    if ( is_product_category() ) {
        $category_id = get_queried_object_id();

        // Hent underkategorier baseret på den overordnede kategori
        $args = array(
            'parent'       => $category_id,
            'taxonomy'     => 'product_cat',
            'hide_empty'   => false, // Vælg om du vil skjule tomme kategorier
        );

        $subcategories = get_terms( $args );

        // Hvis der er underkategorier, vis dem
        if ( ! empty( $subcategories ) ) {
            echo '<ul class="products columns-3 subcategories-list">';
            foreach ( $subcategories as $subcategory ) {
                $subcategory_link = get_term_link( $subcategory );

                echo '<li class="product-category product subcategory">';
                    echo '<a href="' . esc_url( $subcategory_link ) . '">';
                
                // Viser billede, hvis der er et tilknyttet underkategorien
                if ( function_exists( 'woocommerce_subcategory_thumbnail' ) ) {
                    woocommerce_subcategory_thumbnail( $subcategory );
                }
                    echo '</a>';
                    echo '<div class="category-' . $subcategory->name . ' category-title-subtitle"><h2><a class="woocommerce-loop-category__title" href="' . esc_url( $subcategory_link ) . '">' . $subcategory->name . '</a></h2></div>';
                    
                echo '</li>';
            }
            echo '</ul>';
        }
    }
}
add_action( 'woocommerce_before_shop_loop', 'display_subcategories_only', 20 );

// Tilføj klassen "has-subcategories" til kategorisider
function add_custom_woocommerce_body_class( $classes ) {
    if ( is_product_category() ) {
        global $wp_query;

        // Check if the current category has child categories
        $current_cat = $wp_query->get_queried_object();
        $subcategories = get_terms(array(
            'taxonomy' => 'product_cat',
            'parent' => $current_cat->term_id,
            'hide_empty' => true,
        ));

        // Hvis der er underkategorier, tilføj klassen "has-subcategories"
        if ( ! empty( $subcategories ) ) {
            $classes[] = 'has-subcategories';
        } else {
            $classes[] = 'product-list-without-subcategories';
        }
    }
    return $classes;
}
add_filter( 'body_class', 'add_custom_woocommerce_body_class' );

// Only show products in the front-end search results - Frontend search only
function lw_search_filter_pages($query) {

   if ( ! is_admin() && $query->is_search() ) {
       $query->set('post_type', 'product');
       $query->set( 'wc_query', 'product_query' );
   }
   return $query;
}
add_filter('pre_get_posts','lw_search_filter_pages');

function custom_display_all_product_attributes() {
    global $product;

    // Hent alle produktets attributter
    $attributes = $product->get_attributes();

    // Tjek om der er nogen attributter og vis dem
    if (!empty($attributes)) {
        echo '<div class="product-attributes">';
        foreach ($attributes as $attribute_name => $attribute) {
            $attribute_label = wc_attribute_label($attribute_name);
            $attribute_value = $product->get_attribute($attribute_name);

            if (!empty($attribute_value)) {
                echo '<p><strong>' . esc_html($attribute_label) . ':</strong> ' . esc_html($attribute_value) . '</p>';
            }
        }
        echo '</div>';
    }
}
add_action('woocommerce_after_shop_loop_item_title', 'custom_display_all_product_attributes', 5);

/**
 * Adds custom filters to the shop loop page on product subcategory pages.
 *
 * Displays a toggleable div with filter widgets for each attribute that has values
 * in the current subcategory. The filter widgets are generated using the WC_Widget_Layered_Nav
 * class and are displayed with the 'or' query type. The price filter widget is also displayed
 * optionally.
 *
 * This function is hooked into the woocommerce_before_shop_loop action hook.
 *
 * @since 1.0.0
 */
function custom_add_filters_before_shop_loop() {
    // Check if on a product subcategory page
    if (is_product_category() && !is_shop()) {
        echo '<div class="custom-filters-toggle">Filtre</div>';
        echo '<div class="custom-filters">';
        echo '<div class="woocommerce-product-filters">';
        echo '<h2 class="filter-title">Filtre</h2>';
        // Retrieve products in the current category
        global $wp_query;
        $current_cat = $wp_query->get_queried_object();
        $products_in_category = wc_get_products(array(
            'limit' => -1, // No limit to retrieve all products
            'category' => array($current_cat->slug)
        ));
        $attributes_values = [];
        // Loop through products and collect attribute values
        foreach ($products_in_category as $product) {
            $product_attributes = $product->get_attributes();
            foreach ($product_attributes as $attribute_name => $attribute) {
                if ($attribute->is_taxonomy()) {
                    // Retrieve terms (attribute values) for the taxonomy attribute
                    $terms = wc_get_product_terms($product->get_id(), $attribute_name, array('fields' => 'slugs'));

                    if (!isset($attributes_values[$attribute_name])) {
                        $attributes_values[$attribute_name] = [];
                    }
                    // Store unique values for each attribute
                    $attributes_values[$attribute_name] = array_unique(array_merge($attributes_values[$attribute_name], $terms));
                }
            }
        }
        // Display filter widgets with specific values for each attribute
        foreach ($attributes_values as $attribute_name => $values) {
            $attribute_taxonomy = str_replace('pa_', '', $attribute_name);
            $attribute_title = wc_attribute_label($attribute_name);

            // Only show the widget if there are values to display
            if (!empty($values) && $attribute_name != 'pa_vaegt' && $attribute_name != 'pa_samlet-vaegt') {
                the_widget('WC_Widget_Layered_Nav', array(
                    'title' => $attribute_title,
                    'attribute' => $attribute_taxonomy,
                    'query_type' => 'or'
                ));
            }
        }
        // Optionally, add the price filter widget
        the_widget('WC_Widget_Price_Filter');

        echo '</div> <!-- woocommerce-product-filters -->';
        echo '</div> <!-- custom-filters -->';
    }
}
add_action('woocommerce_before_shop_loop', 'custom_add_filters_before_shop_loop', 30);