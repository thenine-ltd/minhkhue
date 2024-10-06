<?php

namespace UkrSolution\BarcodeScanner\features\frontend;

use UkrSolution\BarcodeScanner\API\actions\UsersActions;
use UkrSolution\BarcodeScanner\API\classes\PostsList;
use UkrSolution\BarcodeScanner\API\classes\SearchFilter;
use UkrSolution\BarcodeScanner\API\classes\WPML;
use UkrSolution\BarcodeScanner\API\PluginsHelper;
use UkrSolution\BarcodeScanner\features\cart\Cart;
use UkrSolution\BarcodeScanner\features\interfaceData\InterfaceData;
use UkrSolution\BarcodeScanner\features\locations\Locations;
use UkrSolution\BarcodeScanner\features\locations\LocationsData;
use UkrSolution\BarcodeScanner\features\settings\Settings;
use UkrSolution\BarcodeScanner\features\settings\SettingsHelper;
use UkrSolution\BarcodeScanner\features\sounds\Sounds;

class FrontendRouter
{
    private $userPermissionKey = "barcode-scanner-permission";
    private $core = null;

    private $prefix = "";

    public function init($core)
    {
        $this->core = $core;
        add_action('setup_theme', array($this, 'hooks'));
    }

    public function hooks()
    {
        try {
            add_filter('template_include', array($this, 'includeTemplate'), 100);
        } catch (\Throwable $th) {
        }
    }

    private function getParamsFromPlainUrl()
    {
        $result = array("route" => "", "params" => array());

        try {
            if (isset($_SERVER["REQUEST_URI"])) {
                $key = $_SERVER["REQUEST_URI"];

                if (!$key) return $result;

                if (preg_match("/^\/?(barcode-scanner-front)(.*?)?$/", $key, $m)) {
                    if (count($m) >= 2) {
                        return array(
                            "route" => $m[1],
                            "params" => $_GET,
                        );
                    }
                }
            }
        } catch (\Throwable $th) {
            return $result;
        }

        return $result;
    }

    public function includeTemplate($template)
    {
        $result = $this->getParamsFromPlainUrl();

        if ($result["route"] != "barcode-scanner-front") {
            return $template;
        }

        header("HTTP/1.1 200 OK");
        header("Expires: on, 01 Jan 1970 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        add_filter('show_admin_bar', '__return_false');

        include_once(ABSPATH . 'wp-admin/includes/plugin.php');

        if (!is_user_logged_in()) {
            auth_redirect();
        }

        if (!$this->checkUserPermissions()) {
            require_once __DIR__ . '/FrontendRouterAccessDenied.php';
        }

        $path = plugin_dir_url(__FILE__);
        $path = str_replace('src/features/frontend/', '', $path);

        $settings = new Settings();
        $interfaceData = new InterfaceData();
        $jsData = $this->generateJsData($settings);

        $productCategories = get_terms('product_cat', array('orderby' => "name", 'order' => "ASC", 'hide_empty' => false));

        foreach ($productCategories as $value) {
            unset($value->slug);
            unset($value->term_group);
            unset($value->term_taxonomy_id);
            unset($value->taxonomy);
            unset($value->description);
            unset($value->count);
            unset($value->filter);
        }

        $customCss = $settings->getSettings("customCss");
        $customCss = array("css" => $customCss ? $customCss->value : "");

        $locationsTree = array("options" => LocationsData::getLocations());

        $usbsHistory = History::getByUser();

        require_once __DIR__ . '/FrontendRouterIndex.php';

        exit;

    }

    private function getLangs()
    {
        $languages = require USBS_PLUGIN_BASE_PATH . "src/Languages.php";


        return $languages;

    }

    public function generateJsData($settings)
    {
        global $wp_version;

        $path = plugin_dir_url(__FILE__);
        $path = str_replace('src/features/frontend/', '', $path);

                wp_enqueue_script("barcode_scanner_loader", $path."assets/js/index-business-1.5.1-1698401813780.js", array("jquery"), 1698401813780, true);

    $appJsPath = $path."assets/js/bundle-business-1.5.1-1698401813780.js";

    $vendorJsPath = $path."assets/js/chunk-business-1.5.1-1698401813780.js";


        $sounds = new Sounds();
        $cart = new Cart();
        $interfaceData = new InterfaceData();
        $location = new Locations();
        $usersActions = new UsersActions();

        $wpml = null;

        if(WPML::status()) {
            $wpml = array(
                "translations" => WPML::getTranslations()
            );
        }

        $currency = "$";
        $currencyLabel = "USD";
        $priceThousandSeparator = "";
        $priceDecimalSeparator = ".";
        $priceDecimals = 2;

        if(function_exists('get_woocommerce_currency_symbol') && function_exists('get_woocommerce_currency')) {
            $currency = html_entity_decode(get_woocommerce_currency_symbol());
            $currencyLabel = get_woocommerce_currency();
        }

        if(function_exists('wc_get_price_decimal_separator')) {
            $priceDecimalSeparator = wc_get_price_decimal_separator();
        }

        if(function_exists('wc_get_price_thousand_separator')) {
            $priceThousandSeparator = wc_get_price_thousand_separator();
        }

        if(function_exists('wc_get_price_decimals')) {
            $priceDecimals = wc_get_price_decimals();
        }

        $userSessions = $settings->getSettings("userSessions");
        if ($userSessions) {
            $userSessions = $userSessions->value;
        }

        $session = $settings->getSettings("session");
        $sessionStamp = $settings->getSettings("sessionStamp");

        $countries = array();
        if(PluginsHelper::is_plugin_active('woocommerce/woocommerce.php')) {
            try {
                $countries = WC()->countries->countries;
            } catch (\Throwable $th) {
            }
        }

        $productsList = PostsList::getList(get_current_user_id());

        $jsData = array(
            'appJsPath' => $appJsPath,
            'vendorJsPath' => $vendorJsPath,
            'websiteUrl' => get_bloginfo("url"),
            'adminUrl' => get_admin_url(),
            'pluginUrl' => USBS_PLUGIN_BASE_URL,
            'jsonUrl' => get_rest_url(),
            'pluginVersion' => '1.5.1',
            'isWoocommerceActive' => PluginsHelper::is_plugin_active('woocommerce/woocommerce.php'),
            'isStockLocations' => PluginsHelper::is_plugin_active('stock-locations-for-woocommerce/stock-locations-for-woocommerce.php'),
            'currencySymbol' => $currency,
            'currencyLabel' => $currencyLabel,
            'priceDecimalSeparator' => $priceDecimalSeparator,
            'priceThousandSeparator' => $priceThousandSeparator,
            'priceDecimals' => $priceDecimals,
            'rest_root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
            'ajaxUrl' => get_admin_url() . 'admin-ajax.php',
            'ajaxUrlUS' => USBS_PLUGIN_BASE_URL . 'request.php',
            'urlSettingsLocations' => admin_url('/admin.php?page=barcode-scanner-settings&tab=locations-data'),
            'wc_nonce' => wp_create_nonce('wc_store_api'),
            'uid' => get_current_user_id(),
            'settings' => $settings->getField("", "", "", false, true),
            'searchFilter' => SearchFilter::get(),
            'sounds' => $sounds->getList(),
            'wp_version' => $wp_version,
            'wc_version' => defined("WC_VERSION") ? WC_VERSION : 0,
            'wpml' => $wpml,
            'plugins' => PluginsHelper::checkExternalPlugins(),
            'tabsPermissions' => $settings->getUserRolePermissions(),
            'session' => $session ? $session->value : "",
            'sessionStamp' => $sessionStamp ? $sessionStamp->value : "",
            'customSearchFilters' => apply_filters("scanner_custom_search_filters", array()),
            "locations" => array(),
            "pp_locations" => array(),
            "prefix" => strlen($this->prefix) > 0 ? substr($this->prefix, 1) : "",
            "mode" => 'WEl5I+xhJLxE9d0ZGEOn2g==',
            "userSessions" => $userSessions,
            "shippingMethods" => array(),
            "paymentMethods" => $cart->getPaymentMethods(),
            "wcPricesInclTax" => function_exists("wc_prices_include_tax") ? \wc_prices_include_tax() : "",
            "orderStatuses" => $settings->getOrderStatuses(),
            "countries" => $countries,
            "searchHistory"=> array(),
            "productsListCount" => $productsList ? count($productsList) : 0,
        );

        return $jsData;

    }

    public function checkUserPermissions($userId = null)
    {
        try {
            if (!$userId) {
                $userId = \get_current_user_id();
            }

            $settings = new Settings();
            $rolePermissions = $settings->getUserRolePermissions($userId);
            $allowToUseOnFrontend = $settings->getField("general", "allowToUseOnFrontend", "");

            if ($allowToUseOnFrontend != "on") {
                return false;
            }

            if ($rolePermissions && isset($rolePermissions["frontend"]) && $rolePermissions["frontend"]) {
                return true;
            }

            $permission = \get_user_meta($userId, $this->userPermissionKey, true);

            if ($permission && (int)$permission) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
