<?php

namespace UkrSolution\BarcodeScanner\features\settings;

use UkrSolution\BarcodeScanner\Database;
use UkrSolution\BarcodeScanner\features\cart\Cart;
use UkrSolution\BarcodeScanner\features\interfaceData\InterfaceData;
use UkrSolution\BarcodeScanner\features\locations\Locations;
use UkrSolution\BarcodeScanner\features\locations\LocationsData;
use UkrSolution\BarcodeScanner\features\mobile\MobileRouter;
use WP_REST_Request;

class Settings
{
    private $post = array();
    private $frontendPermissions = array();
    private $dbOptionSettingsKey = "barcode-scanner-settings-options";
    private $dbOptionRolesPermissionsKey = "barcode-scanner-roles-permissions";
    private $rolesPermissions = array();
    private $userPermissionKey = "barcode-scanner-permission";
    private $dbOptionPluginsKey = "barcode-scanner-plugins";
    private $plugins = array();
    public $userAppPermissionKey = "scanner-app-token";
    public $activeTab = "";

    public function __construct()
    {
    }

    public function formSubmitted()
    {
        $this->formListener();
    }

    public function updateSettings($key, $value, $type = "json")
    {
        global $wpdb;

        $table = $wpdb->prefix . Database::$settings;
        $row = $wpdb->get_row(
            $wpdb->prepare("SELECT S.id FROM {$table} AS S WHERE S.key = '%s' LIMIT 1;", $key)
        );

        if ($row) {
            $wpdb->update($table, array("value" => $value, "type" => $type), array("id" => $row->id));
        } else {
            $wpdb->insert($table, array("key" => $key, "value" => $value, "type" => $type), array('%s', '%s', '%s'));
        }

        $row = null;
    }

    public function getSettings($key = "", $asArray = false, $reSelectData = false)
    {
        return SettingsHelper::getSettingsField($this, $key, $asArray, $reSelectData);
    }

    public function formListener()
    {
        try {
            if (isset($_POST) && !empty($_POST)) {
                $keys = array(
                    'tab', 'key', 'm_key', 'defaultOrderStatus', 'defaultShippingMethod', 'fieldForNewProduct', 'cfForNewProduct', 'wpmlUpdateProductsTree', 'orderCreateEmail',
                    'allowToUseOnFrontend', 'frontendIntegration', 'allowFrontendShortcodes', 'allowNegativeStock', 'indexationStep', 'searchResultsLimit',
                    'debugInfo', 'searchCF', 'searchCFLabel', 'newProductQty', 'newProductStatus', 'directDbUpdate', 'directDbSearch', 'customCss',
                    'show_price_1', 'show_price_2', 'show_price_3', 'price_1_label', 'price_2_label', 'price_3_label', 'cartDecimalQuantity',
                    'defaultPriceField', 'sendAdminEmailCreatedOrder', 'sendClientEmailCreatedOrder', 'price_1_field', 'price_2_field', 'price_3_field', 'productStatuses', 'orderStatuses',
                    'addAppUsersPermissions', 'removeAppUsersPermissions', 'storage', 'locations', 'enableLocations', 'fields', 'newOrderUserRequired', 'fulfillmentScanItemQty',
                    'notifyUsersStock', 'nowOrderDefaultUser', 'displaySearchCounter', 'openOrderAfterCreation', 'shippingRequired', 'orderFulfillmentEnabled',
                    'orderFulFillmentField'
                );
                foreach ($keys as $key) {
                    if (isset($_POST[$key])) {
                        $this->post[$key] = $this->getRequestData($_POST[$key], $key);

                        if (in_array($key, array("productStatuses", "orderStatuses", "notifyUsersStock")) && is_array($this->post[$key])) {
                            $this->post[$key] = implode(",", $this->post[$key]);
                        }
                    }
                }

                $this->post = SettingsHelper::stripslashesDeep($this->post, false);

                $isIndexed = $this->getField("indexing", "indexed", false);
                if (!$isIndexed) $this->updateField("indexing", "indexed", false);

                $this->formSubmitSounds();

                if (isset($this->post["storage"]) && $this->post["storage"] === "table") {
                    $this->formSubmitStorageTable();
                    return;
                }

                if (isset($_POST["tab"]) && $_POST["tab"] === "permissions") {
                    if (isset($_POST["rolesPermissions"]) && is_array($_POST["rolesPermissions"])) {
                        $this->rolesPermissions = $_POST["rolesPermissions"];
                    }
                    $this->formSubmitRolePermissions();
                }

                if (isset($_POST["tab"]) && $_POST["tab"] === "plugins") {
                    if (isset($_POST["plugins"]) && is_array($_POST["plugins"])) {
                        $this->plugins = $_POST["plugins"];
                    }
                    $this->formSubmitPlugins();
                }

                if (isset($_POST["addAppUsersPermissions"])) {
                    $this->addAppUsersPermissions($_POST["addAppUsersPermissions"]);
                }

                if (isset($_POST["removeAppUsersPermissions"])) {
                    $this->removeAppUsersPermissions($_POST["removeAppUsersPermissions"]);
                }

                if (isset($this->post["key"]) || isset($this->post["m_key"])) {
                    $prefix = "";
                    @delete_transient($prefix . 'ukrsolution_upgrade_scanner_1.5.1');
                    $user_id = get_current_user_id();
                    update_option($user_id . '_' . basename(USBS_PLUGIN_BASE_PATH) . '_notice_dismissed', '', true);
                }
            }

            if (isset($this->post["tab"])) {
                $this->activeTab = $this->post["tab"];
            }

            $this->formSubmit();
        } catch (\Throwable $th) {
        }
    }

    private function getRequestData($value, $key = "")
    {

        if (is_array($value)) {
            $data = array();

            foreach ($value as $key => $_value) {
                if (is_array($_value)) {
                    $data[$key] = $this->getRequestData($_value);
                } else {
                    if (in_array($key, array("customCss"))) {
                        $data[$key] = $_value;
                    } else {
                        $data[$key] = sanitize_text_field($_value);
                    }
                }
            }

            return $data;
        } else {
            if (in_array($key, array("customCss"))) {
                return $value;
            } else {
                return sanitize_text_field($value);
            }
        }
    }

    public function getField($tab = "", $field = "", $defaultValue = "", $isEncode = false, $reSelectData = false)
    {
        try {
            $settings = get_option($this->dbOptionSettingsKey, array());

            if (!$tab) {
                $settingsTable = $this->getSettings("", false, $reSelectData);

                $settings["prices"] = (object)array();
                $settings["modalShowLocations"] = 0;
                $settings["directDbSearch"] = "on";

                foreach ($settingsTable as $key => $setting) {
                    if ($setting->key === "settings_prices") {
                        if ($setting->value) {
                            $settings["prices"] = (array)$setting->value;
                        }
                    } else if (in_array($setting->key, array(
                        "modalShowLocations", "directDbUpdate", "directDbSearch", "cartDecimalQuantity",
                        "newOrderUserRequired", "fulfillmentScanItemQty", "nowOrderDefaultUser", 'displaySearchCounter',
                        'openOrderAfterCreation', 'shippingRequired', "fieldForNewProduct", 'orderFulfillmentEnabled', 'orderFulFillmentField'
                    ))) {
                        $settings[$setting->key] = $setting->value;
                    } else if (in_array($setting->key, ["defaultOrderStatus", "defaultShippingMethod", "defaultPriceField", "allowNegativeStock"])) {
                        $settings[$setting->key] = $setting->value;
                    }
                }

                return $settings;
            }

            if ($tab === "prices") {
                $settingsTable = $this->getSettings("settings_prices");

                if (!$settingsTable) return $defaultValue;

                if (!$field) return $settingsTable;

                if (!isset($settingsTable->value) || !isset($settingsTable->value->$field)) return $defaultValue;

                return $settingsTable->value->$field;
            } else {
                if (!isset($settings[$tab])) return $defaultValue;

                if (!$field) return $settings[$tab];

                if (!isset($settings[$tab][$field])) return $defaultValue;

                if (!$settings[$tab][$field] && $defaultValue) return $defaultValue;

                return $settings[$tab][$field];
            }
        } catch (\Throwable $th) {
            return "";
        }
    }

    public function getOrderStatuses()
    {
        if (!function_exists("wc_get_order_statuses")) {
            return array();
        }

        $statuses = \wc_get_order_statuses();

        try {
            if (!$statuses) {
                $statuses = array();
            } else {
                foreach ($statuses as $key => &$value) {
                    $value = trim($value);
                    $value = strip_tags($value);
                }
            }
        } catch (\Throwable $th) {
        }

        return $statuses;
    }

    public function getShippingMethod()
    {
        $cart = new Cart();
        $methods = $cart->getShippingMethods();

        if (!$methods) {
            $methods = array();
        }

        return $methods;
    }

    public function getUsers()
    {
        $users = array();
        $result = array();

        if (!$users) {
            $users = array();
        }

        foreach ($users as $user) {
            $result[] = array(
                "ID" => $user->ID,
                "display_name" => $user->display_name,
                "permission" => get_user_meta($user->ID, $this->userPermissionKey, true)
            );
        }

        return $result;
    }

    public function getTotalIndexedRecords()
    {
        global $wpdb;

        $tablePosts = $wpdb->prefix . Database::$posts;
        $posts = $wpdb->get_row("SELECT COUNT(P.id) as 'total' FROM {$tablePosts} AS P WHERE P.successful_update = '1' AND P.updated != '0000-00-00 00:00:00';");

        if ($posts && $posts->total) {
            return $posts->total;
        }

        return 0;
    }

    public function getTotalCantIndexedRecords()
    {
        global $wpdb;

        $tablePosts = $wpdb->prefix . Database::$posts;
        $posts = $wpdb->get_row("SELECT COUNT(P.id) as 'total' FROM {$tablePosts} AS P WHERE P.successful_update = '0';");

        if ($posts && $posts->total) {
            return $posts->total;
        }

        return 0;
    }

    public function getTotalPosts()
    {
        $result = Database::updatePostsTable(0, 1, false, true);
        $total = 0;

        if ($result && isset($result["total"])) {
            $total = $result["total"];
        }


        return $total;
    }

    public function updateField($tab, $field, $value)
    {
        try {
            if (!$tab || !$field) {
                return;
            }

            $settings = get_option($this->dbOptionSettingsKey, array());

            if (!isset($settings[$tab])) {
                $settings[$tab] = array();
            }

            $settings[$tab][$field] = $value;

            update_option($this->dbOptionSettingsKey, $settings);
        } catch (\Throwable $th) {
        }
    }
    private function formSubmit()
    {
        try {
            if (!$this->post) {
                return;
            }

            if (!isset($this->post["tab"])) {
                return;
            }

            if (isset($this->post["storage"]) && $this->post["storage"] === "table") {
                return;
            }

            if ($this->post["tab"] === "prices") {
                $this->updateSettings("settings_prices", json_encode($this->post));
            } else {
                $settings = get_option($this->dbOptionSettingsKey, array());

                $settings[$this->post["tab"]] = $this->post;

                update_option($this->dbOptionSettingsKey, $settings);
            }
        } catch (\Throwable $th) {
        }
    }

    private function formSubmitFrontendPermissions()
    {
        try {
            foreach ($this->getUsers() as $user) {
                update_user_meta($user["ID"], $this->userPermissionKey, "0");
            }

            foreach ($this->frontendPermissions as $id) {
                update_user_meta($id, $this->userPermissionKey, "1");
            }
        } catch (\Throwable $th) {
        }
    }

    private function formSubmitRolePermissions()
    {
        try {
            update_option($this->dbOptionRolesPermissionsKey, $this->rolesPermissions);
        } catch (\Throwable $th) {
        }
    }

    private function formSubmitPlugins()
    {
        try {
            update_option($this->dbOptionPluginsKey, $this->plugins);
        } catch (\Throwable $th) {
        }
    }

    private function formSubmitStorageTable()
    {
        try {
            if (isset($this->post['locations'])) {
                $location = new Locations();
                $location->update($this->post['locations']);
            }

            if (isset($this->post["tab"]) && $this->post["tab"] == "fields") {
                InterfaceData::saveFields($this->post["fields"]);

                if (isset($this->post["defaultPriceField"])) {
                    $this->updateSettings("settings_prices", json_encode(array(
                        "defaultPriceField" => $this->post["defaultPriceField"]
                    )));
                }
                return;
            }

            if (isset($this->post["tab"]) && $this->post["tab"] == "locations-data") {
                $locationData = isset($_POST["locationData"]) ? $_POST["locationData"] : array();
                LocationsData::saveLocations($locationData);
                return;
            }

            foreach ($this->post as $key => $value) {
                if (!in_array($key, array("tab", "storage", "locations"))) {
                    if (in_array($key, array("customCss"))) {
                        $this->updateSettings($key, stripslashes($value), "text");
                    } else {
                        $this->updateSettings($key, $value, "text");
                    }
                }
            }
        } catch (\Throwable $th) {
        }
    }

    private function formSubmitSounds()
    {
        try {
            if (isset($_FILES["increaseFile"]) && isset($_FILES["increaseFile"]["name"]) && $_FILES["increaseFile"]["name"]) {
                $url = $this->uploadFile($_FILES["increaseFile"]);
                $this->updateSettings("sound_increase", $url, "text");
            }

            if (isset($_FILES["decreaseFile"]) && isset($_FILES["decreaseFile"]["name"]) && $_FILES["decreaseFile"]["name"]) {
                $url = $this->uploadFile($_FILES["decreaseFile"]);
                $this->updateSettings("sound_decrease", $url, "text");
            }

            if (isset($_FILES["failFile"]) && isset($_FILES["failFile"]["name"]) && $_FILES["failFile"]["name"]) {
                $url = $this->uploadFile($_FILES["failFile"]);
                $this->updateSettings("sound_fail", $url, "text");
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function uploadFile($file)
    {
        try {
            $wp_upload_dir = wp_upload_dir();
            $upload_dir = $wp_upload_dir['basedir'] . '/barcode-scanner/';
            $upload_dir_url = $wp_upload_dir['baseurl'] . '/barcode-scanner/';

            if (!file_exists($upload_dir)) {
                wp_mkdir_p($upload_dir);
            }
            if (!file_exists($upload_dir . 'sounds')) {
                wp_mkdir_p($upload_dir . 'sounds');
            }

            $dt = new \DateTime("now");
            $fileName = $dt->format("dmYhis") . "-" . $file["name"];

            if (move_uploaded_file($file["tmp_name"], $upload_dir . "sounds/" . $fileName)) {
                return str_replace(home_url(), '', $upload_dir_url . "sounds/" . $fileName);
            }

            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getRoles()
    {
        global $wp_roles;

        $allRoles = $wp_roles->roles;

        return $allRoles;
    }

    public function getPlugins()
    {
        $plugins = get_plugins();

        $userSettings = get_option($this->dbOptionPluginsKey, array());

        $default = array(
            "barcode-scanner.php",
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

        foreach ($plugins as $slug => &$value) {
            if (empty($userSettings)) {
                $value["bs_active"] = in_array($slug, $default) ? 1 : 0;
            } else {
                $value["bs_active"] = in_array($slug, $userSettings) ? 1 : 0;
            }
        }

        return $plugins;
    }

    public function getRolePermissions($role)
    {
        $roles = get_option($this->dbOptionRolesPermissionsKey, null);

        if ($roles === null) {
            $roles = array();

            foreach ($this->getRoles() as $key => $value) {
                $roles[$key] = array("inventory" => 1, "newprod" => 1, "orders" => 1, "cart" => 1, "linkcustomer" => 1, "frontend" => in_array($key, array("administrator", "barcode_scanner_front_end")) ? 1 : 0);
            }
        }

        if (!isset($roles["barcode_scanner_front_end"])) {
            $roles["barcode_scanner_front_end"] = array("inventory" => 1, "newprod" => 1, "orders" => 1, "cart" => 1, "linkcustomer" => 1,  "frontend" => in_array($role, array("administrator", "barcode_scanner_front_end")) ? 1 : 0);
        }


        if (isset($roles[$role])) {
            $data = $roles[$role];
            if (!isset($data["newprod"]) && isset($data["inventory"])) $data["newprod"] = $data["inventory"];
            if (!isset($data["linkcustomer"]) && isset($data["cart"])) $data["linkcustomer"] = $data["cart"];

            return $data;
        }

        return array();
    }

    public function getUserRolePermissions($userId = null)
    {
        $result = array("inventory" => 0, "newprod" => 0, "orders" => 0, "cart" => 0, "linkcustomer" => 0, "frontend" => 0);

        if (!$userId) {
            $userId = get_current_user_id();
        }

        if (!$userId) {
            return $result;
        }

        $userMeta = get_userdata($userId);
        $userRoles = $userMeta ? $userMeta->roles : null;

        if ($userRoles) {
            foreach ($userRoles as $roleKey) {
                $permissions = $this->getRolePermissions($roleKey);

                if (is_array($permissions)) {
                    $result = array_merge($result, $permissions);
                }
            }
        }

        return $result;
    }

    public function getAppUsersPermissions()
    {
        return array();
    }

    private function addAppUsersPermissions($userId)
    {
        update_user_meta($userId, $this->userAppPermissionKey, $this->generateRandomString(16));
    }

    private function removeAppUsersPermissions($userId)
    {
        update_user_meta($userId, $this->userAppPermissionKey, "");
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function updateSettingsArray(WP_REST_Request $request)
    {
        $data = $request->get_param("data");

        if (!$data) {
            return;
        }

        if (!is_array($data)) {
            return;
        }

        foreach ($data as $key => $value) {
            $this->updateSettings($key, $value, "text");
        }

        return rest_ensure_response($data);
    }

    public function loadSettingsArray(WP_REST_Request $request)
    {
        $platform = $request->get_param("platform");
        $result = array();

        if ($platform !== "web") {
            $MobileRouter = new MobileRouter();
            $result["usbs"] = $MobileRouter->generateJsData($platform, false, $request);
        }

        return rest_ensure_response($result);
    }
}
