<?php
/**
 * Custom Price Calculation Fields with Radio Buttons
 * Description: Tilføjer brugerdefinerede prisfelter til WooCommerce produkter med mulighed for at vælge mellem forskellige beregningsmetoder.
 *   
 */

// Tilføj ekstra felter på produktsiden
add_action('woocommerce_before_add_to_cart_button', 'custom_price_fields_with_radio_buttons');
function custom_price_fields_with_radio_buttons() {
    $pladekategori_id = 31; // ID for metalpladeoverkategorien

    global $product;
    $flexible_amount = get_post_meta( $product->get_id(), 'flexible_amount', true );
	
    $attributes = $product->get_attributes();
    foreach ( $attributes as $attribute ) {
        if ( $attribute->get_name() === 'pa_laengde') {
            $terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
            $laengde = floatvalue($terms[0]);
        } else if ($attribute->get_name() === 'pa_hojde') {
            $terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
            $hojde = floatvalue($terms[0]);
        }
    }

	$hovedkategori = find_hovedkategori($product->get_id());
	$benevn = ($hovedkategori == $pladekategori_id) ? 'plade(r)' : 'lgd';
	?>

    <div class="custom-price-fields">
        <p><label><input type="radio" name="price_option" value="full_area_price" checked><input class="qty" type="number" id="full_area_price_pieces" name="full_area_price_pieces" value="1" min="1" step="1"/><?php echo $benevn; ?> á <span id="total_weight"></span> kg</label></p>
        <?php if ($flexible_amount == 'laengde-maal') { ?>
        <p><label><input type="radio" name="price_option" value="input_length"><input class="qty" type="number" id="input_length_pieces" name="input_length_pieces" value="1" min="1" step="1"/>lgd á<input type="number" id="input_length" name="input_length" value="50" min="50" max="<?php echo $laengde; ?>" step="1"/> mm<div class="input-error-tipbox kun-laengde"><p>Max længde: <?php echo $laengde; ?> mm</p><div class="triangle"></div></div></label></p>
        <?php } ?>
        <?php if ($flexible_amount == 'laengde-x-bredde') { ?>
        <p><label><input type="radio" name="price_option" value="input_length_height"><input class="qty" type="number" id="input_length_height_pieces" name="input_length_height_pieces" value="1" min="1" step="1"/>stk. á<input type="number" id="input_length" name="input_length" value="50" min="50" max="<?php echo $laengde; ?>" step="1"/> mm X <div class="input-error-tipbox hxl-laengde"><p>Max længde: <?php echo $laengde; ?> mm</p><div class="triangle"></div></div><input type="number" id="input_width" name="input_width" value="50" min="50" max="<?php echo $hojde; ?>" step="1"/> mm<div class="input-error-tipbox hxl-hojde"><p>Max højde: <?php echo $hojde; ?> mm</p><div class="triangle"></div></div></label></p>
        <?php } ?>
        <p id="custom-total-price">Samlet pris: <span id="total_price"> kr.</span></p>
        <input type="hidden" name="custom_price" id="custom_price" value="">
        <input type="hidden" name="custom_qty" id="custom_qty" value="">	
    </div>
<?php    
}

/**
 * Converts a string to a float value.
 * Replaces commas with dots and removes any trailing decimal points.
 * Useful for converting prices from strings to floats.
 *
 * @param string $val The value to convert.
 * @return float The converted value.
 */
function floatvalue($val){
    $val = str_replace(",",".",$val);
    $val = preg_replace('/\.(?=.*\.)/', '', $val);
    return floatval($val);
}

// Tilføj select box i WooCommerce produkteditoren i admin
add_action( 'woocommerce_product_options_general_product_data', 'custom_product_select_field' );
function custom_product_select_field() {
    woocommerce_wp_select( array(
        'id'          => 'flexible_amount', // Metadata ID
        'label'       => __( 'Fleksibel mængde', 'woocommerce' ), // Label der vises i admin
        'description' => __( 'Angiv om produktet kan skæres i en af følgende valgmuligheder', 'woocommerce' ), // Beskrivelse i admin
        'options'     => array(
            'kun-hele-stykker'  => __( 'Kun hele stykker', 'woocommerce' ),  // Valgmulighed "Kun hele stykker"
            'laengde-maal' => __( 'Længde', 'woocommerce' ),  // Valgmulighed "Længde"
            'laengde-x-bredde' => __( 'Længde x bredde', 'woocommerce' )  // Valgmulighed "Længde x bredde" ved plader
        ),
    ));
}

// Gem select boxens værdi, når produktet gemmes
add_action( 'woocommerce_process_product_meta', 'save_custom_product_select_field' );
function save_custom_product_select_field( $post_id ) {
    if ( isset( $_POST['flexible_amount'] ) ) {
        update_post_meta( $post_id, 'flexible_amount', sanitize_text_field( $_POST['flexible_amount'] ) );
    }
}


/**
 * Removes the "Additional Information" tab from the WooCommerce product page.
 *
 * This function unsets the 'additional_information' key from the tabs array, 
 * effectively removing the tab from the product page in WooCommerce.
 *
 * @param array $tabs The array of tabs on the WooCommerce product page.
 * @return array The modified array of product tabs without the additional information tab.
 */
function remove_product_additional_information_tab( $tabs ) {
    unset( $tabs['additional_information'] );  // Remove the additional information tab
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'remove_product_additional_information_tab', 98 );


/**
 * Displays the product attributes next to the product image on the single product page.
 *
 * This function loops through the product attributes and displays them in a list
 * next to the product image. It only displays the first option for each attribute.
 *
 * @since 1.0.0
 */
function display_product_attributes_next_to_image() {
    global $product;

    echo '<div class="single-product-attributes-and-categories">';

    // Get the product attributes
    $attributes = $product->get_attributes();

    if ( ! empty( $attributes ) ) {
        $price = floatvalue($product->get_price());
        echo '<div class="single-product-attributes" data-price="' . $price . '">';
        echo '<ul>';
        // Loop through each attribute
		foreach ( $attributes as $attribute ) {
			// Tjek om attributten er synlig på varesiden
			if ( $attribute->get_visible() ) {
				if ( taxonomy_exists( $attribute->get_name() ) ) {
					// For attributes that are taxonomy-based (like color, size, etc.)
					$terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
					$value = floatval( str_replace(',', '.', $terms[0] ) ); // Brug korrekt float-funktion
					echo '<li id="attr-' . esc_attr( $attribute->get_name() ) . '" data-' . esc_attr( $attribute->get_name() ) . '="' . esc_attr( $value ) . '"><strong>' . wc_attribute_label( $attribute->get_name() ) . ':</strong> ' . implode( ', ', array_map( 'esc_html', $terms ) ) . '</li>';
				} else {
					// For custom attributes
					$value = floatval( $attribute->get_options()[0] ); // Brug korrekt float-funktion
					echo '<li data-' . esc_attr( $attribute->get_name() ) . '="' . esc_attr( $value ) . '" class="custom-attribute"><strong>' . wc_attribute_label( $attribute->get_name() ) . ':</strong> ' . esc_html( $attribute->get_options()[0] ) . '</li>';
				}
			} else {
                if ( taxonomy_exists( $attribute->get_name() ) ) {
                    $terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
                    $value = floatval( str_replace(',', '.', $terms[0] ) );
					echo '<li class="hidden-attribute" id="attr-' . esc_attr( $attribute->get_name() ) . '" data-' . esc_attr( $attribute->get_name() ) . '="' . esc_attr( $value ) . '"></li>';
                }
            }
		}
        // Get the SKU
        $sku = $product->get_sku();

        // Only display SKU if it's not empty
        if ( $sku ) {
            echo '<li class="product-sku"><strong>Varenummer (SKU):</strong> ' . esc_html( $sku ) . '</li>';
        }

        // Get the product categories
        $categories = get_the_terms( $product->get_id(), 'product_cat' );

		$last_category = end( $categories ); // Få den sidste kategori
        echo '<li class="product-categories"><strong>Kategorier:</strong> ';
		foreach ( $categories as $category ) {
			echo '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
			if ( $category !== $last_category ) {
				echo ', '; // Tilføj komma, hvis det ikke er den sidste kategori
			}
		}
        echo '</li>';

        echo '</ul>';
        echo '</div>';
    }

    echo '</div>';
}
add_action( 'woocommerce_before_single_product_summary', 'display_product_attributes_next_to_image', 20 );

// Remove product meta (SKU, category, tags) from single product page
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Move short description to additional information
function move_short_description() {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
    add_action( 'woocommerce_product_additional_info', 'woocommerce_template_single_excerpt', 10 );
}
add_action( 'woocommerce_init', 'move_short_description' );


/**
 * 
 * HJælpefunktion til at finde Hovedkategorien 
 * 
 * 
 **/

function find_hovedkategori( $product_id ) {
    // Hent produktets kategorier
    $categories = get_the_terms( $product_id, 'product_cat' );

    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
        foreach ( $categories as $category ) {
            // Gå op i hierarkiet, indtil vi når den øverste kategori
            while ( $category->parent > 0 ) {
                $category = get_term( $category->parent, 'product_cat' );
            }
            return $category->term_id; // Returnér den øverste kategori
        }
    }

    return null; // Ingen kategorier fundet
}