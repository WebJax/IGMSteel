<?php

add_filter('woocommerce_json_search_found_attributes', 'improve_attribute_search', 10, 1);
add_filter('woocommerce_json_search_attribute_terms_args', 'modify_attribute_search_args', 10, 1);

/**
 * Modify the number of results displayed for attribute search.
 *
 * This function hooks into 'woocommerce_json_search_found_attributes' to
 * modify the results of the attribute search. It takes the found attributes
 * and returns an array with the first 50 results.
 *
 * @param array $found_attributes The found attributes.
 *
 * @return array The first 50 found attributes.
 */
function improve_attribute_search($found_attributes) {
    // Øg antallet af resultater der vises
    if (!empty($found_attributes)) {
        return array_slice($found_attributes, 0, 50, true);
    }
    return $found_attributes;
}

/**
 * Modify the arguments for attribute search.
 *
 * This function hooks into 'woocommerce_json_search_attribute_terms_args' to
 * modify the arguments used for attribute search. It takes the arguments and
 * modifies them to:
 * - Show also empty attributes
 * - Sort by name
 * - Sort ascending
 * - Use fuzzy search by modifying the search string
 * - Increase the number of results per page
 * - Add caching
 *
 * @param array $args The arguments for attribute search.
 *
 * @return array The modified arguments.
 */
function modify_attribute_search_args($args) {
    // Modificer søgeargumenterne
    $args['hide_empty'] = false; // Vis også tomme attributter
    $args['orderby'] = 'name';
    $args['order'] = 'ASC';
    
    // Tilføj fuzzy søgning ved at modificere søgestrengen
    if (!empty($args['search'])) {
        $args['search'] = '*' . $args['search'] . '*';
    }
    
    // Øg antallet af resultater per side
    $args['number'] = 50;
    
    // Tilføj caching
    $args['cache_results'] = true;
    $args['update_post_meta_cache'] = true;
    $args['update_post_term_cache'] = true;
    
    return $args;
}

// Tilføj custom caching til attribute terms
add_action('init', 'setup_attribute_terms_cache');

/**
 * Sets up a cache for WooCommerce attribute terms.
 *
 * This function initializes a cache to store WooCommerce attribute terms
 * for faster retrieval. It checks if the terms are already cached using
 * the specified cache key. If not, it retrieves all attribute taxonomies,
 * fetches their terms, and stores them in the cache for one hour.
 *
 * Caching helps in reducing the database queries needed to fetch attribute
 * terms repeatedly, thus improving performance.
 */

function setup_attribute_terms_cache() {
    $cache_key = 'wc_attribute_terms_cache';
    $cached_terms = wp_cache_get($cache_key);
    
    if (false === $cached_terms) {
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $terms = array();
        
        foreach ($attribute_taxonomies as $tax) {
            $taxonomy = wc_attribute_taxonomy_name($tax->attribute_name);
            $terms[$taxonomy] = get_terms(array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false
            ));
        }
        
        wp_cache_set($cache_key, $terms, '', HOUR_IN_SECONDS);
    }
}

// Tilføj custom styles for at forbedre visningen
add_action('admin_head', 'custom_attribute_search_styles');

/**
 * Adds custom styles to the select2 widget used for attribute search.
 *
 * This function hooks into 'admin_head' and adds custom styles to the select2
 * widget used for attribute search in the admin. It modifies the styles of the
 * select2 options and search field to make them more readable and visually
 * appealing.
 */
function custom_attribute_search_styles() {
    ?>
    <style>
        .select2-container .select2-results__option {
            padding: 8px;
            margin: 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .select2-container .select2-search__field {
            min-height: 30px;
            padding: 5px !important;
        }
    </style>
    <?php
}