<?php

namespace UkrSolution\BarcodeScanner\features\export;

use UkrSolution\BarcodeScanner\features\settings\Settings;

class Export
{
    function __construct()
    {
        add_filter('woocommerce_product_export_column_names', array($this, 'woocommerce_product_export_product_default_columns'));
        add_filter('woocommerce_product_export_product_default_columns', array($this, 'woocommerce_product_export_product_default_columns'));
        add_filter('woocommerce_product_export_product_column_usbs_barcode_field', array($this, 'woocommerce_product_export_product_column_usbs_barcode_field'), 10, 2);
    }

    function woocommerce_product_export_product_default_columns($columns)
    {
        $settings = new Settings();
        $label = $settings->getSettings("searchCFLabel");
        $label = $label ? $label->value : $settings->getField("general", "searchCFLabel", "Barcode");
        $columns['usbs_barcode_field'] = $label;

        return $columns;
    }

    function woocommerce_product_export_product_column_usbs_barcode_field($value, $product)
    {
        $value = get_post_meta($product->get_id(), 'usbs_barcode_field', true);
        return $value;
    }
}
