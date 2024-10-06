<?php

use UkrSolution\BarcodeScanner\Core;

if (!defined('WP_USE_THEMES')) {
    define('WP_USE_THEMES', false);
}

if (!defined('DISABLE_WP_CRON')) {
    define('DISABLE_WP_CRON', true);
}

global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;

$root = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : "../../..";

require($root . "/wp-includes/plugin.php");

function usbs_plugin_filter($plugins)
{
    global $wpdb;

    $defaultPlugins = array(
        "woocommerce/woocommerce.php",
        "atum-stock-manager-for-woocommerce.php",
        "ean-for-woocommerce/ean-for-woocommerce.php",
        "ean-for-woocommerce-pro/ean-for-woocommerce-pro.php",
        "woo-add-gtin/woocommerce-gtin.php",
        "product-gtin-ean-upc-isbn-for-woocommerce/product-gtin-ean-upc-isbn-for-woocommerce.php",
        "aftership-woocommerce-tracking/aftership-woocommerce-tracking.php",
        "woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php",
        "yith-woocommerce-order-tracking/init.php",
        "wt-woocommerce-sequential-order-numbers/wt-advanced-order-number.php",
        "woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers-pro.php",
        "stock-locations-for-woocommerce/stock-locations-for-woocommerce.php",
        "woocommerce-wholesale-pricing/woocommerce-wholesale-pricing.php",
        "zettle-pos-integration/zettle-pos-integration.php",
        "dokan-lite/dokan.php",
        "custom-order-statuses-for-woocommerce/custom-order-statuses-for-woocommerce.php",
        "checkout-fees-for-woocommerce/checkout-fees-for-woocommerce.php",
        "sitepress-multilingual-cms/sitepress.php",
        "bp-custom-order-status-for-woocommerce/main.php",
        "polylang/polylang.php",
        "polylang-pro/polylang.php",
        "woocommerce-order-status-manager/woocommerce-order-status-manager.php"
    );

    $option = $wpdb->get_row("SELECT * FROM {$wpdb->options} WHERE option_name = 'barcode-scanner-plugins';");
    $availablePlugins = $option && $option->option_value ? unserialize($option->option_value) : array();
    $availablePlugins = array_merge($defaultPlugins, $availablePlugins);

    if (!in_array("barcode-scanner.php", $availablePlugins)) {
        $availablePlugins[] = "barcode-scanner.php";
    }

    $newList =  array();

    foreach ($plugins as $plugin) {
        foreach ($availablePlugins as $value) {
            if ($plugin == $value || strpos($plugin, $value)) {
                $newList[] = $plugin;
            }
        }
    }

    return $newList;
}

add_filter("option_active_plugins", "usbs_plugin_filter", 1, 1);
add_filter("site_option_active_plugins", "usbs_plugin_filter", 1, 1);
add_filter("active_plugins", "usbs_plugin_filter", 1, 1);

require($root . "/wp-load.php");

$core = new Core();
$core->ajaxRequest();
