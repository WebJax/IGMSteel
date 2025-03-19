<?php
add_filter('woocommerce_billing_fields', function($fields) {
    // Sæt firmanavnets prioritet til lige efter efternavn (standard er 30)
    $fields['billing_company']['priority'] = 25;
    $fields['billing_company']['required'] = false; // Gør det valgfrit (eller true hvis det skal være påkrævet)
    $fields['billing_company']['autocomplete'] = 'organization'; // Hjælper med autofill
    return $fields;
});