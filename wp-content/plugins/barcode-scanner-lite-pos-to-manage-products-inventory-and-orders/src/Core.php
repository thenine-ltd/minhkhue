<?php

namespace UkrSolution\BarcodeScanner;

use UkrSolution\BarcodeScanner\API\actions\UsersActions;
use UkrSolution\BarcodeScanner\API\AjaxRoutes;
use UkrSolution\BarcodeScanner\API\classes\Auth;
use UkrSolution\BarcodeScanner\API\classes\Checker;
use UkrSolution\BarcodeScanner\API\classes\Integrations;
use UkrSolution\BarcodeScanner\API\classes\PostsList;
use UkrSolution\BarcodeScanner\API\classes\Roles;
use UkrSolution\BarcodeScanner\API\classes\SearchFilter;
use UkrSolution\BarcodeScanner\API\classes\WPML;
use UkrSolution\BarcodeScanner\API\PluginsHelper;
use UkrSolution\BarcodeScanner\API\Routes;
use UkrSolution\BarcodeScanner\features\admin\Admin;
use UkrSolution\BarcodeScanner\features\cart\Cart;
use UkrSolution\BarcodeScanner\features\debug\Debug;
use UkrSolution\BarcodeScanner\features\export\Export;
use UkrSolution\BarcodeScanner\features\frontend\Frontend;
use UkrSolution\BarcodeScanner\features\frontend\FrontendRouter;
use UkrSolution\BarcodeScanner\features\history\History;
use UkrSolution\BarcodeScanner\features\import\Import;
use UkrSolution\BarcodeScanner\features\indexedData\IndexedData;
use UkrSolution\BarcodeScanner\features\interfaceData\InterfaceData;
use UkrSolution\BarcodeScanner\features\locations\Locations;
use UkrSolution\BarcodeScanner\features\locations\LocationsData;
use UkrSolution\BarcodeScanner\features\logs\Logs;
use UkrSolution\BarcodeScanner\features\mobile\MobileRouter;
use UkrSolution\BarcodeScanner\features\products\Products;
use UkrSolution\BarcodeScanner\features\settings\Settings;
use UkrSolution\BarcodeScanner\features\settings\SettingsHelper;
use UkrSolution\BarcodeScanner\features\sounds\Sounds;
use UkrSolution\BarcodeScanner\features\updater\Updater;

class Core
{
    protected $updater;
    private $appLinkProtocol = "scan-app://";
    private $prefix = "";

        public function __construct() 
    {
        Debug::init();

        $integrations = new Integrations($this);

        add_action('rest_api_init', function () {
            try {
                if(class_exists("Routes")) {
                    (new Routes())->registerRoutes();
                }
            } catch (\Throwable $th) {
            }
        });

        add_action('wp_enqueue_media', function () {
            Checker::setMediaLoader();
        });

        add_action('wp_ajax'. $this->prefix . '_barcodeScannerAction', array($this, "ajaxRequest"));
        add_action('wp_ajax_nopriv' . $this->prefix . '_barcodeScannerAction', array($this, "ajaxRequest"));

        add_action('wp_ajax_usbs_find_user', array($this, 'findUser'));

        add_action('admin_menu', array($this, 'createMenu'), 9);
        add_action('admin_menu', array($this, 'adminEnqueueScripts'), 9);

        $cartDecimalQuantity = false;

        try {
            $settings = new Settings();

            $field = $settings->getSettings("cartDecimalQuantity");
            $value = $field === null ? "off" : $field->value;
            $cartDecimalQuantity = $value === "on";
        } catch (\Throwable $th) {
        }

        add_action('shutdown', function() {
            foreach (PluginsHelper::$postsForUpdate as $postId) {
                Database::updatePost($postId, array(), null, null, "updated_post_meta_admin");
            }
        }, 999999);

        add_action('updated_post_meta', function($metaId, $postId, $metaKey, $metaValue) {
            if(\is_admin()) {
                if(!in_array($postId, PluginsHelper::$postsForUpdate)) {
                    PluginsHelper::$postsForUpdate[] = $postId;
                }
            } else if(true) {
                if(mt_rand(0, 100) === 100) {
                    Database::updatePost($postId, array(), null, null, "updated_post_meta");
                }
            }
        }, 1000, 4);
        add_action('woocommerce_save_product_variation', function($variationId){
            Database::updatePost($variationId, array(), null, null, "woocommerce_save_product_variation");
        }, 1000, 2);
        add_action('transition_post_status', function($newStatus, $oldStatus, $post){            
            if ($post->post_type !== "product") {
                return;
            }
            Database::updatePost($post->ID, array(), null, null, "transition_post_status");
        }, 9999, 3);
        add_action('wp_insert_post', function($orderId) {
            if(in_array(get_post_type($orderId), array("shop_order"))) {
                Database::updatePost($orderId, array(), null, null, "wp_insert_post");
            }
        });

                add_filter('woocommerce_order_item_get_formatted_meta_data', function ($formatted_meta) {
            if($formatted_meta) {
                foreach ($formatted_meta as $key => $value) {
                    if(in_array($value->key, array("usbs_check_product","usbs_check_product_scanned"))) {
                    unset($formatted_meta[$key]);
                    }
                }
            }
            return $formatted_meta;
        }, 10, 1 );

        if($cartDecimalQuantity && \is_admin()) {
            add_filter('woocommerce_quantity_input_min', function ($val) { 
                return 0.1;
            });

            add_filter('woocommerce_quantity_input_step', function ($val) {
                return 0.1;
            });

            add_filter('woocommerce_order_item_get_quantity', function ($quantity, $item) {
                if($item->get_type() == "line_item") {
                    $metaQty = \wc_get_order_item_meta($item->get_id(), "_qty");
                    if($metaQty && $quantity != $metaQty) {
                        $quantity = (float)$metaQty;
                    }
                }

                    return $quantity;
            }, 10, 3);
        }

        add_action('setup_theme', array(new MobileRouter, 'init'));
        (new FrontendRouter())->init($this);        

        $this->updater = new Updater();

        $auth = new Auth();

        $frontend = new Frontend($this);
        $admin = new Admin($frontend);
        $products = new Products();
        $locations = new Locations();
        $indexedData = new IndexedData();
        $roles = new Roles();
        $import = new Import();
        $export = new Export();

        add_action('wp_ajax_usbs_auth', array($auth, 'login'));
        add_action('wp_ajax_nopriv_usbs_auth', array($auth, 'login'));
        add_action('wp_ajax_usbs_auth_otp', array($auth, 'loginOtp'));
        add_action('wp_ajax_nopriv_usbs_auth_otp', array($auth, 'loginOtp'));
        add_action('wp_ajax_usbs_auth_link', array($auth, 'loginLink'));
        add_action('wp_ajax_nopriv_usbs_auth_link', array($auth, 'loginLink'));

        add_action('init', array($this, "parseAuthRequest"));



        add_action('admin_enqueue_scripts', function () {
            $action = isset($_GET["action"]) ? $_GET["action"] : "";
            $postId = isset($_GET["post"]) ? $_GET["post"] : "";

            if(Checker::getMediaLoader()) {
                return;
            }

            if ($action == 'edit' && $postId) {
            } else {
                wp_enqueue_media();
            }
        });

        add_action('init', function() use ($frontend) {
            $frontend->userMenuIntegration();
            $frontend->shordcodesIntegration();
        });
    }

    public function createMenu()
    {
        $icons = str_replace("src/", "", \plugin_dir_url(__FILE__)) . "assets/icons/";

        $suf = '';
        $mainRout = 'barcode-scanner';
        $icon = $icons . 'barcode-scanner-menu-logo.svg';

                add_menu_page(__('Barcode Scanner', 'barcode-scanner'), __('Barcode Scanner', 'barcode-scanner'), 'read', $mainRout, array($this, 'modalPage'), $icon);


        add_submenu_page($mainRout, __('Scan & Find item', 'barcode-scanner'), __('Scan & Find item', 'barcode-scanner'), 'read', $mainRout, array($this, 'modalPage'));

        add_submenu_page($mainRout, __('Settings', 'barcode-scanner'), __('Settings', 'barcode-scanner'), 'read', 'barcode-scanner-settings' . $suf, array($this, 'pageSettings'));
        add_submenu_page(null, __('Settings', 'barcode-scanner'), __('Settings', 'barcode-scanner'), 'read', 'barcode-scanner-settings-update' . $suf, array($this, 'pageSettingsUpdate'));

        add_submenu_page($mainRout, __('Logs', 'barcode-scanner'), __('Logs', 'barcode-scanner'), 'read', 'barcode-scanner-logs' . $suf, array($this, 'pageLogs'));

        add_submenu_page($mainRout, __('Indexed data', 'barcode-scanner'), __('Indexed data', 'barcode-scanner'), 'read', 'barcode-scanner-indexed-data', array($this, 'pageIndexedData'));

        add_submenu_page($mainRout, __('Support & Chat', 'barcode-scanner'), '<span class="barcode_scanner_support">' . __('Support & Chat', 'barcode-scanner') . '</span>', 'read', 'barcode-scanner-support', array($this, 'emptyPage'));

        add_submenu_page($mainRout, __('FAQ', 'barcode-scanner'), '<span class="barcode_scanner_faq">' . __('FAQ', 'barcode-scanner') . '</span>', 'read', 'barcode-scanner-faq', array($this, 'emptyPage'));

        add_submenu_page(null, __('Barcode Scanner', 'barcode-scanner'), __('Barcode Scanner', 'barcode-scanner'), 'read', 'bs-mobile-home', array($this, 'mobilePageHome'));

        add_submenu_page(null, __('Barcode Scanner', 'barcode-scanner'), __('Barcode Scanner', 'barcode-scanner'), 'read', 'bs-redirect', array($this, 'redirectPage'));
    }

    public function adminEnqueueScripts()
    {
        global $wp_version;

        $path = plugin_dir_url(__FILE__);
        $path = str_replace('src/', '', $path);

        wp_enqueue_script("barcode_scanner_loader", $path."assets/js/index-business-1.5.1-1698401813780.js", array("jquery"), 1698401813780, true);

    $appJsPath = $path."assets/js/bundle-business-1.5.1-1698401813780.js";

    $vendorJsPath = $path."assets/js/chunk-business-1.5.1-1698401813780.js";


        $userId = get_current_user_id();
        $platform = isset($_POST["platform"]) ? sanitize_key("platform") :"";

        $userLocale = $userId ? get_user_meta($userId, 'locale', true) : "";
        if ($userLocale && in_array($platform, array("android", "ios"))) switch_to_locale($userLocale);

        $settings = new Settings();
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

              $session = $settings->getSettings("session");
        $sessionStamp = $settings->getSettings("sessionStamp");
        $usbsInterfaceMobile = $interfaceData::getFields(true);
        wp_localize_script("barcode_scanner_loader", "usbsLangs", $this->getLangs());
        wp_localize_script("barcode_scanner_loader", "usbsInterface", apply_filters("scanner_product_fields_filter", $usbsInterfaceMobile));
        wp_localize_script("barcode_scanner_loader", "usbsCategories", $productCategories);

        $countries = array();
        if(PluginsHelper::is_plugin_active('woocommerce/woocommerce.php')) {
            try {
                $countries = WC()->countries->countries;
            } catch (\Throwable $th) {
            }
        }

        $productsList = PostsList::getList(get_current_user_id());

        $usbs = array(
            'appJsPath' => $appJsPath,
            'vendorJsPath' => $vendorJsPath,
            'websiteUrl' => get_bloginfo("url"),
            'adminUrl' => get_admin_url(),
            'pluginUrl' => plugin_dir_url(__DIR__),
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
            'ajaxUrlUS' => plugin_dir_url(__DIR__) . 'request.php',
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
            "userLocale" => $userLocale,
            "platform" => $platform,
        );

        $enableLocations = $settings->getSettings("enableLocations");
        if(($enableLocations && $enableLocations->value === "on") || !$enableLocations) {
            $usbs["locations"] = $location->get();
        }

        wp_localize_script("barcode_scanner_loader", "usbs", $usbs);

        $customCss = $settings->getSettings("customCss");
        wp_localize_script("barcode_scanner_loader", "usbsCustomCss", array("css" => $customCss ? $customCss->value : ""));

        wp_localize_script("barcode_scanner_loader", "usbsLocationsTree", array("options" => LocationsData::getLocations()));
        wp_localize_script("barcode_scanner_loader", "usbsHistory", History::getByUser());

                wp_enqueue_style('barcode_scanner_main', USBS_PLUGIN_BASE_URL . '/assets/css/style.css', array(), '1.5.1');
    }

    private function getLangs() {
        $languages = require USBS_PLUGIN_BASE_PATH . "src/Languages.php";


        return $languages;
    }

    public function pageSettings () {
        $settings = new Settings();
        $wpml = WPML::status();
        $locations = new Locations();
        $interfaceData = new InterfaceData();
        $settingsHelper = new SettingsHelper();


        $customTabs = array();
        $customTabs = apply_filters("scanner_settings_tabs", array());

        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('barcode_scanner_settings', USBS_PLUGIN_BASE_URL . '/src/features/settings/assets/js/index-business-1.5.1-1698401813780.js', array('jquery'), null, true);
        wp_enqueue_script('barcode_scanner_settings_chosen', USBS_PLUGIN_BASE_URL . '/src/features/settings/assets/js/chosen.jquery.min.js', array('jquery'), null, true);
        wp_enqueue_script('barcode_scanner_settings_nestable', USBS_PLUGIN_BASE_URL . '/src/features/settings/assets/js/jquery.nestable.js', array('jquery'), null, true);
        wp_enqueue_style('barcode_scanner_settings', USBS_PLUGIN_BASE_URL . '/src/features/settings/assets/css/index.css');
        wp_enqueue_style('barcode_scanner_settings_chosen', USBS_PLUGIN_BASE_URL . '/src/features/settings/assets/css/chosen.min.css');
        wp_enqueue_style('barcode_scanner_settings_nestable', USBS_PLUGIN_BASE_URL . '/src/features/settings/assets/css/jquery.nestable.css');

        require_once USBS_PLUGIN_BASE_PATH . "src/features/settings/index.php";
    }

    public function pageSettingsUpdate () {
        $settings = new Settings();
        $settings->formSubmitted();
        $tab = isset($_POST["tab"]) ? "&tab=" . sanitize_text_field($_POST["tab"]) : "";
        $subTab = isset($_POST["sub"]) ? "&sub=" . sanitize_text_field($_POST["sub"]) : "";
        wp_redirect(admin_url('/admin.php?page=barcode-scanner-settings' . $tab . $subTab));
        exit;
    }

    public function pageLogs () {
        $logs = new Logs();

        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('barcode_scanner_logs', USBS_PLUGIN_BASE_URL . '/src/features/logs/assets/js/index-business-1.5.1-1698401813780.js', array('jquery'), null, true);
        wp_enqueue_style('barcode_scanner_logs', USBS_PLUGIN_BASE_URL . '/src/features/logs/assets/css/index.css');
        wp_register_style('jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
        wp_enqueue_style('jquery-ui'); 

               require_once USBS_PLUGIN_BASE_PATH . "src/features/logs/index.php";
    }

    public function pageIndexedData () {
        $indexedData = new IndexedData();

        if(isset($_GET["index"]) && $_GET["index"]) {
            $pid = sanitize_text_field($_GET["index"]);
            Database::updatePost($pid, array(), null, null, "pageIndexedData");
        }

        if(isset($_GET["reCreateTable"]) && $_GET["reCreateTable"]) {
            Database::removeTableProducts();
            Database::setupTableProducts(true, true);      
            update_option("usbs_reCreateTable_msg", true);      
            wp_redirect(admin_url('/admin.php?page=barcode-scanner-indexed-data'));
            exit;
        }

                if(isset($_GET["triggers"])) {
            $itc = isset($_GET["index_triggers_counting"]) ? $_GET["index_triggers_counting"] : "";
            update_option("usbs_index_triggers_counting", $itc);
            update_option("usbs_iic_updated_post_meta_admin", 0);
            update_option("usbs_iic_updated_post_meta", 0);
            update_option("usbs_iic_woocommerce_save_product_variation", 0);
            update_option("usbs_iic_transition_post_status", 0);
            update_option("usbs_iic_wp_insert_post", 0);
            update_option("usbs_iic_pageIndexedData", 0);
            update_option("usbs_iic_updatePostsTable", 0);

            wp_redirect(admin_url('/admin.php?page=barcode-scanner-indexed-data'));
        }

        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('barcode_scanner_logs', USBS_PLUGIN_BASE_URL . '/src/features/indexedData/assets/js/index-business-1.5.1-1698401813780.js', array('jquery'), null, true);
        wp_enqueue_style('barcode_scanner_logs', USBS_PLUGIN_BASE_URL . '/src/features/indexedData/assets/css/index.css');
        wp_register_style('jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
        wp_enqueue_style('jquery-ui'); 

               require_once USBS_PLUGIN_BASE_PATH . "src/features/indexedData/index.php";
    }

    public function mobilePageHome () {
        echo "<hr/>[mobilePageHome]<hr/>";
    }

    public function redirectPage () {
        $referer = \wp_get_referer();

        if($referer) {
            \wp_redirect($referer);
        } else {
            \wp_redirect(\get_admin_url());
        }
    }

    public function emptyPage () {}

    public function modalPage () {
        echo '<a href="admin.php?page=barcode-scanner" class="usbs-auto-start-modal"></a>';
    }

    public function ajaxRequest() {
        $post = json_decode(file_get_contents("php://input"), true);

        if(!$post) $post = $_POST;
        $get = array();

        foreach ($_GET as $key => $value) {
            $get[$key] = sanitize_text_field($value);
        }


                new AjaxRoutes($post, $get);
    }

    public function parseAuthRequest()
    {
        if (preg_match('/\/.*?usbs-mobile\?u=(.*?)?$/', $_SERVER["REQUEST_URI"], $m)) {
            $siteUrl = get_site_url();
            $token = "";

            if(count($m) === 2) {
                $token = trim($m[1]);
            }

            $users = get_users(array('meta_key' => 'scanner-app-token', 'meta_value' => $token));

            if($users && count($users) > 0 && strlen($token) >= 14 && strlen($token) <= 18) {
                $user = $users[0];
                $fullName = trim($user->first_name . " " . $user->last_name);

                $link = $this->appLinkProtocol . "login/?u=" . $siteUrl . "?" . $token;

                $logoUrl = esc_url(wp_get_attachment_url(get_theme_mod('custom_logo')));
                $blogName = get_bloginfo("name");

                require_once USBS_PLUGIN_BASE_PATH . "src/features/settings/views/page-app-login.php";
            }else{
                echo "Wrong request";
            }

            exit;

        }
    }

    public function findUser(){
        global $wpdb;

        $currentIds = isset($_POST["currentIds"]) ? $_POST["currentIds"] : array();
        $query = isset($_POST["query"]) ? $_POST["query"] : "";
        $query = trim($query);
        $query = addslashes($query);

        $users = array();
        $where = "";

        if($currentIds) {
            $ids = implode(",",  $currentIds);
            $where = $ids ? " OR ID IN({$ids}) " : "";
        }

        if($query) {
            $records = $wpdb->get_results("SELECT * FROM {$wpdb->users} WHERE user_nicename LIKE '%{$query}%' {$where} ORDER BY ID LIMIT 100;");

            foreach ($records as $user) {
                $name = $user->display_name == $user->user_login ? $user->display_name : $user->display_name . " (" . $user->user_login . ")";
                $users[] = array(
                    "ID" => $user->ID,
                    "user_nicename" => $name,
                );
            }
        }

        echo json_encode(array("users" => $users));
        exit;
    }
}
