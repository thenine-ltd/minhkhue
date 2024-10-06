<?php

namespace UkrSolution\BarcodeScanner\features\import;

use UkrSolution\BarcodeScanner\features\settings\Settings;

class Import
{
    function __construct()
    {
        add_filter('woocommerce_csv_product_import_mapping_options', array($this, 'woocommerce_csv_product_import_mapping_options'));
        add_filter('woocommerce_csv_product_import_mapping_default_columns', array($this, 'woocommerce_csv_product_import_mapping_default_columns'));
        add_filter('woocommerce_product_import_inserted_product_object', array($this, 'woocommerce_product_import_inserted_product_object'), 10, 2);
    }

    function woocommerce_csv_product_import_mapping_options($columns)
    {
        $settings = new Settings();
        $label = $settings->getSettings("searchCFLabel");
        $label = $label ? $label->value : $settings->getField("general", "searchCFLabel", "Barcode");
        $columns['usbs_barcode_field'] = $label;

        return $columns;
    }

    function woocommerce_csv_product_import_mapping_default_columns($columns)
    {
        $settings = new Settings();
        $label = $settings->getSettings("searchCFLabel");
        $label = $label ? $label->value : $settings->getField("general", "searchCFLabel", "Barcode");
        $columns[$label] = 'usbs_barcode_field';

        return $columns;
    }

    function woocommerce_product_import_inserted_product_object($object, $data)
    {
        if ($data && isset($data["usbs_barcode_field"])) {
            update_post_meta($object->get_id(), "usbs_barcode_field", $data["usbs_barcode_field"]);
        }
    }
}
