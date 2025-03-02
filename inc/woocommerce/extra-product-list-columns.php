<?php

// Add the "Dimensions" column to the product list in admin
add_filter( 'manage_edit-product_columns', 'add_custom_igmsteel_columns' );

function add_custom_igmsteel_columns( $columns ) {
    // Insert the "Dimensions" column after the product title column
    $columns['product_dimensions'] = __( 'Dimensioner', 'your-text-domain' );
    $columns['product_flexible_amount'] = __( 'Flexible Mængde', 'your-text-domain' );
    
    return $columns;
}

// Populate the "Dimensions" column with data
add_action( 'manage_product_posts_custom_column', 'custom_dimensions_column_content_attributes', 10, 2 );

function custom_dimensions_column_content_attributes( $column, $post_id ) {
    if ( $column === 'product_dimensions' ) {
        // Get the product object
        $product = wc_get_product( $post_id );
        
        // Fetch the dimensions and weight from attributes (change attribute names as needed)
        $length = $product->get_attribute( 'pa_laengde' );  // Replace 'pa_length' with the actual slug for length attribute
        $width = $product->get_attribute( 'pa_bredde' );    // Replace 'pa_width' with the actual slug for width attribute
        $height = $product->get_attribute( 'pa_hojde' );  // Replace 'pa_height' with the actual slug for height attribute
        $diameter = $product->get_attribute( 'pa_diameter' );
        $tykkelse = $product->get_attribute( 'pa_tykkelse' );
        $weight = $product->get_attribute( 'pa_vaegt' );  // Replace 'pa_weight' with the actual slug for weight attribute
        $allweight = $product->get_attribute( 'pa_samlet-vaegt' );
        
        // Format the dimensions
        $dimensions = array();
        if ( $length ) {
            $dimensions[] = 'Længde: ' . esc_html( $length ) . '';
        }
        if ( $width ) {
            $dimensions[] = 'Bredde: ' . esc_html( $width ) . '';
        }
        if ( $height ) {
            $dimensions[] = 'Højde: ' . esc_html( $height ) . '';
        }
        if ( $diameter ) {
            $dimensions[] = 'Diameter: ' . esc_html( $diameter ) . '';
        }
        if ( $tykkelse ) {
            $dimensions[] = 'Tykkelse: ' . esc_html( $tykkelse ) . '';
        }
        if ( $weight ) {
            $dimensions[] = '<br>Vægt: ' . esc_html( $weight ) . ' kg pr. meter';
        }
        if ( $allweight ) {
            $dimensions[] = '<br>Samlet vægt: ' . esc_html( $allweight ) . ' kg';
        }
        
        // Output the combined dimensions
        if ( ! empty( $dimensions ) ) {
            echo implode( '<br>', $dimensions );
        } else {
            _e( 'Ingen dimensioner', 'your-text-domain' );
        }
    }
}

// Populate the "Flexible Mængde" data to the product list in admin
add_action( 'manage_product_posts_custom_column', 'custom_flexible_column_content', 10, 2 );

function custom_flexible_column_content( $column, $post_id ) {
    if ( $column === 'product_flexible_amount' ) {
        $flexible_amount = get_post_meta( $post_id, 'flexible_amount', true );

        if ($flexible_amount == 'kun-hele-stykker') {
            echo 'Kun hele stykker';
        } elseif ($flexible_amount == 'laengde-maal') {
            echo 'Længde';
        } elseif ($flexible_amount == 'laengde-x-bredde') {
            echo 'Længde x Bredde';
        }
    }
}