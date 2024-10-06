<?php

namespace UkrSolution\BarcodeScanner\features\mobile;

use UkrSolution\BarcodeScanner\API\actions\UsersActions;
use UkrSolution\BarcodeScanner\API\classes\PostsList;
use UkrSolution\BarcodeScanner\API\classes\Users;
use UkrSolution\BarcodeScanner\features\settings\Settings;
use UkrSolution\BarcodeScanner\features\sounds\Sounds;
use UkrSolution\BarcodeScanner\API\classes\WPML;
use UkrSolution\BarcodeScanner\features\cart\Cart;
use UkrSolution\BarcodeScanner\features\history\History;
use UkrSolution\BarcodeScanner\features\interfaceData\InterfaceData;
use UkrSolution\BarcodeScanner\features\locations\Locations;
use UkrSolution\BarcodeScanner\features\settings\SettingsHelper;
use WP_REST_Request;

class MobileRouter
{
    private $jsParams = array();
    private $prefix = "";

    public function init()
    {
        try {
            add_filter('wp_loaded', array($this, 'includeTemplate'));

                    } catch (\Throwable $th) {
        }

        add_filter('template_include', array($this, 'includeTemplate'), 100);
        add_filter('init', array($this, 'rewriteRules'));
        add_action('wp_head', function () {
        }, 0);

    }

    private function getParamsFromPlainUrl() {
        try {
            if($_GET && count($_GET) === 1 || isset($_SERVER["REQUEST_URI"])) {
                $key = $_SERVER["REQUEST_URI"];

                                    if (!$key) return null;

                if (preg_match("/^\/?mobile-barcode-scanner\/(plugin|android|ios)\/(checker|display)\/?(.*?)?$/", $key, $m)) {
                    if (count($m) >= 3) {
                        return array(
                            "route" => str_replace("/", "", $m[1]),
                            "params" => str_replace("/", "", $m[2]),
                        );
                    }
                }
            }
        } catch (\Throwable $th) {
        }
    }

    public function includeTemplate($template = "")
    {
        $route = get_query_var('route');
        $params = get_query_var('params');
        $tn = "script";

        if(!$route && !$params) {
            $result = $this->getParamsFromPlainUrl();

            if($result && isset($result["route"]) && isset($result["params"])) {
                $route = $result["route"];
                $params = $result["params"];
            }
        }

        if ($route === "plugin" && $params === "checker") {
            header("Expires: on, 01 Jan 1970 00:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            require __DIR__ . '/checker.php';
            exit();
        } else if (in_array($route, array("android", "ios")) && $params === "display") {
            header("Expires: on, 01 Jan 1970 00:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            include_once(ABSPATH . 'wp-admin/includes/plugin.php');


                        $token = isset($_GET["token"]) ? $_GET["token"] : "";
            $jsData = $this->generateJsData($route, true);
            $interfaceData = new InterfaceData();
            $productCategories = array();

            if($token) {
                $request = new WP_REST_Request("", "");
                $request->set_param("token", $token);
                $userId = $request ? Users::getUserId($request) : null;
                $usbsHistory = History::getByUser($userId);
            }

            try {
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
            } catch (\Throwable $th) {
            }

            echo "\n<";
            esc_html_e($tn, 'us-barcode-scanner');
            echo " src='" . home_url() . "/wp-includes/js/jquery/jquery.js' ";
            echo ">";
            echo "<";
            echo "/";
            esc_html_e($tn, 'us-barcode-scanner');
            echo ">";

            echo "<";
            esc_html_e($tn, 'us-barcode-scanner');
            echo " src='" . home_url() . "/wp-includes/js/jquery/jquery-migrate.min.js' ";
            echo ">";
            echo "<";
            echo "/";
            esc_html_e($tn, 'us-barcode-scanner');
            echo ">";

            echo "<";
            esc_html_e($tn, 'us-barcode-scanner');
            echo " src='" . USBS_PLUGIN_BASE_URL . "src/features/mobile/assets/js/index.js?v=1.5.1&t=1698401813780' ";
            echo ">";
            echo "<";
            echo "/";
            esc_html_e($tn, 'us-barcode-scanner');
            echo ">";

            echo "<";
            esc_html_e($tn, 'us-barcode-scanner');
            echo " src='" . USBS_PLUGIN_BASE_URL . "src/features/mobile/assets/js/loader.js?v=1.5.1&t=1698401813780' ";
            echo ">";
            echo "<";
            echo "/";
            esc_html_e($tn, 'us-barcode-scanner');
            echo ">";


                        require __DIR__ . '/index.php';
            exit();
        }

                return $template;

    }

    private function getLangs()
    {
        $languages = require USBS_PLUGIN_BASE_PATH . "src/Languages.php";


        return $languages;

    }

    public function generateJsData($route, $reSelectSettings = false, $request = null)
    {
        global $wp_version;

                
  $appJsPath = plugin_dir_url(__FILE__)."../../../assets/js/bundle-business-1.5.1-1698401813780.js";

  $vendorJsPath = plugin_dir_url(__FILE__)."../../../assets/js/chunk-business-1.5.1-1698401813780.js";

  

        if(!$request && isset($_GET["token"])) {
            $request = new WP_REST_Request("", "");
            $request->set_param("token", $_GET["token"]);
        }

        $userId = $request ? Users::getUserId($request) : null;

        $userLocale = "";
        if ($userId) { $userLocale = get_user_meta($userId, 'locale', true); }
        if ($userLocale) { switch_to_locale($userLocale); }

        $wpml = null;

        if (WPML::status()) {
            $wpml = array(
                "translations" => WPML::getTranslations()
            );
        }

        $currency = "$";
        $currencyLabel = "USD";
        $priceThousandSeparator = "";
        $priceDecimalSeparator = ".";
        $priceDecimals = 2;

        if (function_exists('get_woocommerce_currency_symbol') && function_exists('get_woocommerce_currency')) {
            $currency = html_entity_decode(get_woocommerce_currency_symbol());
            $currencyLabel = get_woocommerce_currency();
        }

        if (function_exists('wc_get_price_decimal_separator')) {
            $priceDecimalSeparator = wc_get_price_decimal_separator();
        }

        if (function_exists('wc_get_price_thousand_separator')) {
            $priceThousandSeparator = wc_get_price_thousand_separator();
        }

        if (function_exists('wc_get_price_decimals')) {
            $priceDecimals = wc_get_price_decimals();
        }

        $settings = new Settings();
        $sounds = new Sounds();
        $cart = new Cart();
        $location = new Locations();
        $usersActions = new UsersActions();

        $userSessions = $settings->getSettings("userSessions", false, $reSelectSettings);
        if($userSessions) {
            $userSessions = $userSessions->value;
        }

        $countries = array();
        if(SettingsHelper::is_plugin_active('woocommerce/woocommerce.php')) {
            try {
                $countries = @WC()->countries->countries;
            } catch (\Throwable $th) {
            }
        }

        $productsList = PostsList::getList($userId);

        $jsData = array(
            'appJsPath' => $appJsPath,
            'vendorJsPath' => $vendorJsPath,
            'websiteUrl' => get_bloginfo("url"),
            'adminUrl' => get_admin_url(),
            'pluginUrl' => plugin_dir_url(__DIR__),
            'jsonUrl' => get_rest_url(),
            'pluginVersion' => '1.5.1',
            'isWoocommerceActive' => SettingsHelper::is_plugin_active('woocommerce/woocommerce.php'),
            'currencySymbol' => $currency,
            'priceDecimalSeparator' => $priceDecimalSeparator,
            'priceThousandSeparator' => $priceThousandSeparator,
            'priceDecimals' => $priceDecimals,
            'rest_root' => \esc_url_raw(\rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
            'ajaxUrl' => get_admin_url() . 'admin-ajax.php',
            'ajaxUrlUS' => plugin_dir_url(__DIR__) . '../../request.php',
            'urlSettingsLocations' => admin_url('/admin.php?page=barcode-scanner-settings&tab=locations-data'),
            'wc_nonce' => wp_create_nonce('wc_store_api'),
            'uid' => get_current_user_id(),
            'settings' => $settings->getField(),
            'searchFilter' => (array)$settings->getSettings("search_filter", true),
            'platform' => $route,
            'sounds' => $sounds->getList(),
            'wp_version' => $wp_version,
            'wc_version' => defined("WC_VERSION") ? WC_VERSION : 0, 
            'wpml' => $wpml,
            'plugins' => $this->checkExternalPlugins(),
            'tabsPermissions' => $settings->getUserRolePermissions($userId),            
            "prefix" => strlen($this->prefix) > 0 ? substr($this->prefix, 1) : "",
            "mode" => 'WEl5I+xhJLxE9d0ZGEOn2g==',
            "userSessions" => $userSessions,
            "orderStatuses" => $settings->getOrderStatuses(),
            "shippingMethods" => $cart->getShippingMethods(),
            "paymentMethods" => $cart->getPaymentMethods(),
            "wcPricesInclTax" => function_exists("wc_prices_include_tax") ? \wc_prices_include_tax() : "",
            "locations" => array(),
            "pp_locations" => array(),
            "countries" => $countries,
            'm_otp_status' => $usersActions->getUsersOtpStatus(),
            'm_otp_expired' => $usersActions->getUsersOtpExpired(),
            "productsListCount" => $productsList ? count($productsList) : 0,
            "userId" => $userId,
            "userLocale" => $userLocale,
        );

        $enableLocations = $settings->getSettings("enableLocations");
        if(($enableLocations && $enableLocations->value === "on") || !$enableLocations) {
            $jsData["locations"] = $location->get(true);
        }

        return $jsData;

    }

    private function checkExternalPlugins()
    {
        return array(
            array('key' => '_alg_ean', 'status' => function_exists('alg_wc_ean'), 'label' => 'EAN for WooCommerce'),
            array('key' => '_wpm_gtin_code', 'status' => function_exists('wpm_product_gtin_wc'), 'label' => 'Product GTIN (EAN, UPC, ISBN) for WooCommerce'),
            array('key' => 'hwp_product_gtin', 'status' => class_exists('Woo_GTIN'), 'label' => 'WooCommerce UPC, EAN, and ISBN'),
            array('key' => '_wepos_barcode', 'status' => SettingsHelper::is_plugin_active('wepos/wepos.php'), 'label' => 'WePOS'),
            array('key' => '_ts_gtin', 'status' => SettingsHelper::is_plugin_active('woocommerce-germanized/woocommerce-germanized.php'), 'label' => 'GTIN - Germanized for WooCommerce'),
            array('key' => '_ts_mpn', 'status' => SettingsHelper::is_plugin_active('woocommerce-germanized/woocommerce-germanized.php'), 'label' => 'MPN - Germanized for WooCommerce'),
            array('key' => '_order_number', 'status' => defined('WT_SEQUENCIAL_ORDNUMBER_VERSION') || SettingsHelper::is_plugin_active("woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers-pro.php"), 'label' => 'Sequential Order Number for WooCommerce'),
        );

    }

    public function flushRules()
    {
        $this->rewriteRules();

        flush_rewrite_rules();

    }

    public function rewriteRules()
    {
        add_rewrite_rule('mobile-barcode-scanner/(plugin|android|ios)/(checker|display)?$', 'index.php?route=$matches[1]&params=$matches[2]', 'top');
        add_rewrite_tag('%route%', '([^&]+)');
        add_rewrite_tag('%params%', '([^&]+)');

    }
}
