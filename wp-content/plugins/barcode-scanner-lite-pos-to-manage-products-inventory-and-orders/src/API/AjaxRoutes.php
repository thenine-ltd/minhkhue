<?php

namespace UkrSolution\BarcodeScanner\API;

use UkrSolution\BarcodeScanner\API\actions\CartScannerActions;
use UkrSolution\BarcodeScanner\API\actions\DbActions;
use UkrSolution\BarcodeScanner\API\actions\ManagementActions;
use UkrSolution\BarcodeScanner\API\actions\PostActions;
use UkrSolution\BarcodeScanner\API\actions\UsersActions;
use UkrSolution\BarcodeScanner\API\classes\PostsList;
use UkrSolution\BarcodeScanner\API\classes\Users;
use UkrSolution\BarcodeScanner\features\Debug\Debug;
use UkrSolution\BarcodeScanner\features\history\History;
use UkrSolution\BarcodeScanner\features\logs\Logs;
use UkrSolution\BarcodeScanner\features\mobile\MobileRouter;
use UkrSolution\BarcodeScanner\features\settings\Settings;
use WP_REST_Request;


class AjaxRoutes
{
    public function __construct($post, $get)
    {
        Debug::addPoint("AjaxRoutes->start");

        if (isset($post["rout"]) && $post["rout"]) {
            $rout = $post["rout"];

            add_filter('scanner_filter_cart_item_price', function ($productId, $price, $customFilter) {
                return $price;
            }, 1, 3);


            $settings = new Settings();
            $postActions = new PostActions();
            $managementActions = new ManagementActions();
            $usersActions = new UsersActions();
            $dbActions = new DbActions();
            $cartActions = new CartScannerActions();
            $request = new WP_REST_Request("", "");

            $routes = new Routes();
            $request->set_param("token", $this->getParam($get, "token", ""));


            $tokenUserId = $routes->getUserId($request);
            $request->set_param("platform", $this->getParam($get, "platform", ""));
            $checker = $routes->permissionCallback($request);
            $request->set_param("token_user_id", $tokenUserId);

            if ($tokenUserId) {
                $userLocale = get_user_meta($tokenUserId, 'locale', true);
                if ($userLocale) switch_to_locale($userLocale);
            }

            if (!key_exists('woocommerce/woocommerce.php', get_plugins())) {
                $MobileRouter = new MobileRouter();
                $platform = $this->getParam($get, "platform", "");
                echo json_encode(array(
                    "errors" => array("WooCommerce is not activated"),
                    "cartErrors" => array(
                        array("notice" => "WooCommerce is not activated")
                    ),
                ));
                wp_die();
            }

            if (!$checker && !in_array($rout, array(
                "recalculate", "backgroundIndexing", "checkOtherPrices", "checkFieldName",
                "exportLog", "saveLog", "indexingClearTable", "getHistory", "getItemsList"
            ))) {
                $MobileRouter = new MobileRouter();
                $platform = $this->getParam($get, "platform", "");
                echo json_encode(array(
                    "redirect" => 0, "data" => array("rout" => $rout), "f" => 1,
                    "usbs" => $MobileRouter->generateJsData($platform, true, $request)
                ));
                exit;
            }

            $keysString = array(
                "id", "orderId", "userId", "itemId", "recordId", "query", "withVariation", "productId", "quantity", "price", "title", "postId", "attachmentId",
                "field", "request", "postAutoAction", "postAutoField", "status", "autoFill", "byId", "setQty", "orderCustomPrice", "orderCustomSubPrice", "orderCustomTax",
                "orderStatus", "shippingMethod", "paymentMethod", "key", "value", "session", "sessionStamp", "orderUserId", "productQty",
                "tab", "param", "str", "orderAutoAction", "orderAutoStatus", "autoEnableIndexation", "slug", "country", "customAction", "userAction",
                "bsInstanceFrontendStatus", "isCheck", "isAddToList", "modifyAction", "isNew", "fulfillmentOrderId", "confirmed", "isUpdateShipping",
                "coupon", "customerId", "isPay"
            );
            $keysArray = array(
                "filter", "customFilter", "filterExcludes", "products", "fields", "progress", "userData", "currentItems", "itemsCustomPrices", "cartItem", "extraData",
                "inputs", "data", "categories", "locations", "codes", "lines", "autoAction", "options", "items", "filterResult"
            );
            $response = array();

            foreach ($keysString as $key) {
                $request->set_param($key, $this->getParam($post, $key, ""));
            }

            foreach ($keysArray as $key) {
                $request->set_param($key, $this->getParam($post, $key, array()));
            }

            $_POST["bsInstanceFrontendStatus"] = $request->get_param("bsInstanceFrontendStatus");

            switch ($rout) {
                case 'getPost':
                    $response = $postActions->postSearch($request);
                    break;
                case 'checkCustomFields':
                    $response = $postActions->checkCustomFields($request);
                    break;
                case 'getProduct':
                    $response = $managementActions->productSearch($request);
                    break;
                case 'getOrder':
                    $response = $managementActions->orderSearch($request);
                    break;
                case 'productEnableManageStock':
                    $response = $managementActions->productEnableManageStock($request);
                    break;
                case 'updateProductQuantity':
                    $response = $managementActions->productUpdateQuantity($request);
                    break;
                case 'updateProductQuantityPlus':
                    $response = $managementActions->productUpdateQuantityPlus($request);
                    break;
                case 'updateProductQuantityMinus':
                    $response = $managementActions->productUpdateQuantityMinus($request);
                    break;
                case 'updateProductRegularPrice':
                    $response = $managementActions->productUpdateRegularPrice($request);
                    break;
                case 'updateProductSalePrice':
                    $response = $managementActions->productUpdateSalePrice($request);
                    break;
                case 'updateProductCustomPrice':
                    $response = $managementActions->updateProductCustomPrice($request);
                    break;
                case 'updateProductMeta':
                    $response = $managementActions->productUpdateMeta($request);
                    break;
                case 'updatePostStatus':
                    $response = $managementActions->productUpdateStatus($request);
                    break;
                case 'updateTitle':
                    $response = $managementActions->productUpdateTitle($request);
                    break;
                case 'setImage':
                    $response = $managementActions->productSetImage($request);
                    break;
                case 'createNew':
                    $response = $managementActions->productCreateNew($request);
                    break;
                case 'reloadNewProduct':
                    $response = $managementActions->reloadNewProduct($request);
                    break;
                case 'update':
                    $response = $managementActions->productUpdateFields($request);
                    break;
                case 'changeStatus':
                    $response = $managementActions->orderChangeStatus($request);
                    break;
                case 'changeCustomer':
                    $response = $managementActions->orderChangeCustomer($request);
                    break;
                case 'updateOrderMeta':
                    $response = $managementActions->updateOrderMeta($request);
                    break;
                case 'orderUpdateItemsMeta':
                    $response = $managementActions->orderUpdateItemsMeta($request);
                    break;
                case 'orderUpdateItemMeta':
                    $response = $managementActions->orderUpdateItemMeta($request);
                    break;
                case 'updateFoundCounter':
                    $response = $managementActions->updateFoundCounter($request);
                    break;
                case 'saveLog':
                    $response = $managementActions->saveLog($request);
                    break;
                case 'uploadPick':
                    $response = $managementActions->uploadPick($request);
                    break;
                case 'updateCategories':
                    $response = $managementActions->updateCategories($request);
                    break;
                case 'importCodes':
                    $response = $managementActions->importCodes($request);
                    break;
                case 'getItemsList':
                    $response = rest_ensure_response(array("productsList" => PostsList::getList(Users::getUserId($request))));
                    break;
                case 'updateItemsFromList':
                    $response = $managementActions->updateItemsFromList($request);
                    break;
                case 'removeItemsListRecord':
                    $response = $managementActions->removeItemsListRecord($request);
                    break;
                case 'clearItemsList':
                    $response = $managementActions->clearItemsList($request);
                    break;
                case 'getOrdersList':
                    $response = $managementActions->getOrdersList($request);
                    break;
                case 'usersFind':
                    $response = $usersActions->find($request);
                    break;
                case 'userCreate':
                    $response = $usersActions->createUser($request);
                    break;
                case 'getStates':
                    $response = $usersActions->getStates($request);
                    break;
                case 'addItem':
                    $response = $cartActions->addItem($request);
                    break;
                case 'removeItem':
                    $response = $cartActions->removeItem($request);
                    break;
                case 'updateQuantity':
                    $response = $cartActions->updateQuantity($request);
                    break;
                case 'updateAttributes':
                    $response = $cartActions->updateAttributes($request);
                    break;
                case 'clear':
                    $response = $cartActions->cartClear($request);
                    break;
                case 'orderCreate':
                    $response = $cartActions->orderCreate($request);
                    break;
                case 'getStatuses':
                    $response = $cartActions->getStatuses($request);
                    break;
                case 'recalculate':
                    $response = $cartActions->cartRecalculate($request);
                    break;
                case 'createColumn':
                    $response = $dbActions->createColumn($request);
                    break;
                case 'saveSession':
                    $response = $dbActions->saveSession($request);
                    break;
                case 'saveSettings':
                    $response = $dbActions->saveSettings($request);
                    break;
                case 'backgroundIndexing':
                    $response = $dbActions->backgroundIndexing($request);
                    break;
                case 'indexingClearTable':
                    $response = $dbActions->indexingClearTable($request);
                    break;
                case 'checkOtherPrices':
                    $response = $postActions->checkOtherPrices($request);
                    break;
                case 'checkFieldName':
                    $response = $postActions->checkFieldName($request);
                    break;
                case 'updateSettings':
                    $response = $settings->updateSettingsArray($request);
                    break;
                case 'loadSettings':
                    $response = $settings->loadSettingsArray($request);
                    break;
                case 'appUsersUpdate':
                    $MobileRouter = new MobileRouter();
                    $userSessions = $this->getParam($post, "str", "");
                    $platform = $this->getParam($get, "platform", "");
                    $settings->updateSettings("userSessions", $userSessions, "text");
                    $uid = $this->getParam($post, "userId", "");
                    $userAction = $this->getParam($post, "userAction", "");
                    $password = "";
                    if ($uid && $userAction == "add") {
                        $password = $usersActions->usersGenerateOtp();
                        update_user_meta($uid, "barcode_scanner_app_otp", md5($password));
                        update_user_meta($uid, "barcode_scanner_app_otp_expired_dt", time());
                    }
                    if ($uid && $userAction == "remove") {
                        update_user_meta($uid, "barcode_scanner_app_otp", "");
                        update_user_meta($uid, "barcode_scanner_app_otp_expired_dt", "");
                    }
                    $response = rest_ensure_response(array("usbs" => $MobileRouter->generateJsData($platform, true, $request), "password" => $password));
                    break;
                case 'appUserUpdatePassword':
                    $response = $usersActions->updatePassword($request);
                    break;
                case 'exportLog':
                    $logs = new Logs();
                    $response = $logs->export($request);
                    break;
                case 'getHistory':
                    $response = rest_ensure_response(array("history" => History::getByUser()));
                    break;
                default:
                    break;
            }

            $filter = $this->getParam($post, "filter", array());
            if ($filter && in_array($rout, array("checkCustomFields", "createColumn"))) {
                $settings = new Settings();
                $settings->updateSettings('search_filter', json_encode($filter));
            }


            if ($response && $response->data) {
                echo json_encode($response->data);
            } else {
                echo json_encode("error");
            }
            exit;
        }
    }

    private function getParam($post, $key, $default = null)
    {
        if (isset($post[$key])) {
            return $post[$key];
        } else {
            return $default;
        }
    }
}
