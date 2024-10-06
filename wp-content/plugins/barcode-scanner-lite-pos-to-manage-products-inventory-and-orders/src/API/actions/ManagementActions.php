<?php

namespace UkrSolution\BarcodeScanner\API\actions;

use DateInterval;
use DateTime;
use UkrSolution\BarcodeScanner\API\classes\Emails;
use UkrSolution\BarcodeScanner\API\classes\Post;
use UkrSolution\BarcodeScanner\API\classes\PostsList;
use UkrSolution\BarcodeScanner\API\classes\Results;
use UkrSolution\BarcodeScanner\API\classes\Request;
use UkrSolution\BarcodeScanner\API\classes\SearchFilter;
use UkrSolution\BarcodeScanner\API\classes\Users;
use UkrSolution\BarcodeScanner\API\classes\WPML;
use UkrSolution\BarcodeScanner\API\RequestHelper;
use UkrSolution\BarcodeScanner\Database;
use UkrSolution\BarcodeScanner\features\debug\Debug;
use UkrSolution\BarcodeScanner\features\history\History;
use UkrSolution\BarcodeScanner\features\logs\LogActions;
use UkrSolution\BarcodeScanner\features\mobile\MobileRouter;
use UkrSolution\BarcodeScanner\features\settings\Settings;
use WP_REST_Request;

class ManagementActions
{
    private $postAutoAction = array(
        "AUTO_INCREASING" => "AUTO_INCREASING",
        "AUTO_DECREASING" => "AUTO_DECREASING"
    );
    private $orderAutoAction = array(
        "ORDER_STATUS" => "ORDER_STATUS"
    );
    private $qtyBeforeUpdate = array();
    private $tableColumns = null;

    public $filter_search_result = 'scanner_search_result';
    public $filter_quantity_plus = 'scanner_quantity_plus';
    public $filter_quantity_minus = 'scanner_quantity_minus';
    public $filter_quantity_update = 'scanner_quantity_update';
    public $filter_set_after = "barcode_scanner_%field_set_after";
    public $filter_auto_action_step = 'scanner_auto_action_step';

    public function productSearch(WP_REST_Request $request, $actions = true, $findById = false, $actionError = "")
    {
        $autoFill = $request->get_param("autoFill");
        $filter = SearchFilter::get();

        $customFilter = $request->get_param("customFilter");
        $filterExcludes = $request->get_param("filterExcludes");
        $query = RequestHelper::getQuery($request, "product");
        $postAutoAction = $request->get_param("postAutoAction");
        $platform = $request->get_param("platform");
        $limit = null;

        $postAutoField = $request->get_param("postAutoField");
        $postAutoField = $postAutoField ? $postAutoField : "_stock";

        $byId = $request->get_param("byId");
        $onlyById = $byId || $findById ? true : false;

        $isAddToList = $request->get_param("isAddToList");
        $modifyAction = $request->get_param("modifyAction");
        $fulfillmentOrderId = $request->get_param("fulfillmentOrderId");
        $filterResult = $request->get_param("filterResult");

        if ($findById === true && isset($filter["products"])) {
            $filter["products"]["ID"] = 1;
        }

        $result = array(
            "products" => array(),
            "findByTitle" => null,
            "qtyBeforeUpdate" => $this->qtyBeforeUpdate,
            "actionError" => $actionError,
            "fulfillment" => $fulfillmentOrderId ? 1 : 0,
        );

        if ($platform !== "web") {
        }

        Debug::addPoint("start Post()->find");
        $data = (new Post())->find($query, $filter, $onlyById, $autoFill, $limit, "product", $filterExcludes);
        Debug::addPoint("end Post()->find");

        if ($filterResult && $data && isset($filterResult["postId"]) && is_array($data["posts"]) && count($data["posts"]) > 1) {
            $data["posts"] = array_values(array_filter(
                $data["posts"],
                function ($_post) use ($filterResult) {
                    return $_post->ID == $filterResult["postId"];
                }
            ));
        }

        Debug::addPoint("start Results()->productsPrepare");
        $postCounter = $data["posts"] ? count($data["posts"]) : 0;
        $products = (new Results())->productsPrepare(
            $data["posts"],
            array(
                "useAction" => $postAutoAction && $postAutoAction != "empty" ? $postAutoField : false,
                "isAddToList" => $isAddToList,
                "modifyAction" => $modifyAction,
                "isAutoFill" => $autoFill,
            ),
            $autoFill || $postCounter > 1
        );
        Debug::addPoint("end Results()->productsPrepare");

        if ($findById === true) {
            foreach ($products as $product) {
                if ($product["ID"] === $query) {
                    $products = array($product);
                    break;
                }
            }
        }

        $this->itemsLevenshtein($products, $query, $data);

        if ($products) {
            $customFilter["searchQuery"] = $query;
            $products = apply_filters($this->filter_search_result, $products, $customFilter);

            $userId = Users::getUserId($request);

            if ($isAddToList && !$autoFill) {
                $actions = false;
                $product = count($products)  == 1 ? $products[0] : null;
                PostsList::addToList($userId, $product, 1, $modifyAction);

                $result['productsList'] = PostsList::getList($userId);

                if (isset($result['productsList']) && count($result['productsList']) > 0 && $product) {
                    $_id = $product["variation_id"] ? $product["variation_id"] : $product["ID"];

                    foreach ($result['productsList'] as &$value) {
                        if ($value->post_id == $_id) {
                            $value->isUpdated = true;
                        }
                    }
                }
            } else {
                $result['productsList'] = PostsList::getList($userId);
            }

            if ($fulfillmentOrderId && count($products) == 1 && !$autoFill) {
                $fulfillmentResult = $this->applyFulfillment($request, $fulfillmentOrderId, $products[0]);

                if ($fulfillmentResult) {
                    return $fulfillmentResult;
                }
            }

            if ($actions) {
                $actionResult = $this->checkPostAutoAction($request, $postAutoAction, $products, $data["findByTitle"]);

                if ($actionResult !== false) {
                    return $actionResult;
                }
            }

            $result['products'] = $products;
            $result['findByTitle'] = count($products) > 1 ? true : $data["findByTitle"];
            $result['findByWords'] = explode(" ", $query);
        } else {
            $requestName = $request->get_param("request");

            if ($requestName === "post-search") {
                return $this->orderSearch($request);
            }
        }

        if (isset($data["query"])) {
            $result["foundBy"] = $data["query"];
        }

        if (Debug::$status) {
            $result['debug'] = Debug::getResult();
        }

        if (count($result["products"]) === 1 && !$autoFill && !$findById && !$fulfillmentOrderId) {
            LogActions::add($result["products"][0]["ID"], LogActions::$actions["open_product"], "", "", "", "product", $request);
        }

        return rest_ensure_response($result);
    }

    private function applyFulfillment(WP_REST_Request $request, $orderId, $product)
    {
        $order = new \WC_Order($orderId);

        if (!$order) {
            return false;
        }

        foreach ($order->get_items() as $itemId => $value) {
            $pid = $value->get_variation_id() ? $value->get_variation_id() : $value->get_product_id();

            if ($pid == $product["ID"] && ($value->get_variation_id() == $product["variation_id"] || $value->get_variation_id() == 0)) {
                return $this->orderUpdateItemMeta($request, $orderId, $itemId, array(
                    array("key" => "usbs_check_product", "value" => time())
                ), $product);
            }
        }

        return false;
    }

    private function itemsLevenshtein(&$items, $query, $data)
    {
        try {
            if (count($items) >= 1) {
                $postsSearchData = array();
                if (isset($data["postsSearchData"])) {
                    foreach ($data["postsSearchData"] as $key => &$value) {
                        $postsSearchData[$value->ID] = $value;
                    }
                }

                $_q = trim($query);
                $_q = str_replace(array(":", "/"), "", $_q);

                usort($items, function ($a, $b) use ($_q, $postsSearchData) {
                    $valueA = $a["post_title"];
                    $valueB = $a["post_title"];

                    if (key_exists($a["ID"], $postsSearchData)) {
                        $valueA = $this->findValueByField($postsSearchData, $a);
                    }

                    if (key_exists($b["ID"], $postsSearchData)) {
                        $valueB = $this->findValueByField($postsSearchData, $b);
                    }

                    return levenshtein($_q, $valueA) - levenshtein($_q, $valueB);
                });

                if (count($items) > 1 && false) {
                    foreach ($items as $key => $value) {
                        if (!key_exists($value["ID"], $postsSearchData)) {
                            continue;
                        }

                        $field = array_search("1", (array)$postsSearchData[$value["ID"]]);
                        $field = $this->getColumnName($field);
                        $items[$key]["found_by_field"] = $field;

                        if (!$field) {
                            continue;
                        }

                        $str = isset($value[$field]) ? $value[$field] : \get_post_meta($value["ID"], $field, true);
                        $filterStatus = 1;

                        if (isset($data["filter"]) && isset($data["filter"]["productCF"]) && $data["filter"]["productCF"] == $field) {
                            $filterStatus = $data["filter"]["statusProductCF"];
                        } else if (isset($data["filter"]) && isset($data["filter"][$field])) {
                            $filterStatus = $data["filter"][$field];
                        }

                        if ($filterStatus == 2 && $str) {
                            foreach (explode(" ", $_q) as $word) {
                                if (!preg_match('/(?<=[\s,.:;"\']|^)' . $word . '(?=[\s,.:;"\']|$)/', strtolower($str))) {
                                    unset($items[$key]);
                                    continue 2;
                                }
                            }
                        }
                    }
                } else {
                    foreach ($items as $key => $value) {
                        if (!key_exists($value["ID"], $postsSearchData)) {
                            continue;
                        }

                        $field = array_search("1", (array)$postsSearchData[$value["ID"]]);
                        $field = $this->getColumnName($field);
                        $items[$key]["found_by_field"] = $field;
                    }
                }

                foreach ($items as $key => $value) {
                    $str = $value["post_title"];

                    if (key_exists($value["ID"], $postsSearchData)) {
                        $str = $this->findValueByField($postsSearchData, $value);
                    }

                    if (strtolower($str) == strtolower($_q)) {
                        $items[$key]["rs"] = 1000;
                    } else {
                        foreach (explode(" ", $_q) as $word) {
                            if (!preg_match('/(?<=[\s,.:;"\']|^)' . $word . '(?=[\s,.:;"\']|$)/', strtolower($str))) {
                                if (isset($items[$key]["rs"])) $items[$key]["rs"]--;
                                else $items[$key]["rs"] = -1;
                            } else {
                                if (isset($items[$key]["rs"])) $items[$key]["rs"]++;
                                else $items[$key]["rs"] = 0;
                            }
                        }
                    }
                }

                array_multisort(array_column($items, 'rs'), SORT_DESC, $items);
            }
        } catch (\Throwable $th) {
        }
    }

    private function findValueByField($postsSearchData, $item)
    {
        $field = array_search("1", (array)$postsSearchData[$item["ID"]]);
        $field = $this->getColumnName($field);

        if ($field) {
            return ($field && isset($item[$field])) ? $item[$field] : \get_post_meta($item["ID"], $field, true);
        } else {
            return "";
        }
    }

    private function getColumnName($name)
    {
        global $wpdb;

        if ($this->tableColumns == null) {
            $tableColumns = $wpdb->prefix . Database::$columns;
            $this->tableColumns = $wpdb->get_results("SELECT * FROM {$tableColumns}");
        }

        if (preg_match("/^column_.*?/", $name)) {
            foreach ($this->tableColumns as $value) {
                if ($value->column == $name) {
                    return $value->name;
                }
            }
        }

        return $name;
    }

    private function checkPostAutoAction($request, $action, &$products, $foundBy)
    {
        if (!$action || $foundBy || count($products) !== 1) {
            return false;
        }

        $product = &$products[0];

        if (!in_array($product["post_type"], array("product", "product_variation"))) {
            return false;
        }

        if (in_array($product["product_type"], array("external"))) {
            return false;
        }

        $actionError = false;

        $field = $request->get_param("postAutoField");
        $fieldName = $field ? $field : "_stock";

        switch ($action) {
            case $this->postAutoAction["AUTO_INCREASING"]:
                if ($fieldName == "_stock") {
                    if (isset($product["product_manage_stock"]) && isset($product["post_parent"]) && $product["product_manage_stock"] === "parent" && $product["post_parent"]) {
                        $ms = get_post_meta($product["post_parent"], "_manage_stock", true);
                        if ($ms == "yes") $this->productUpdateQuantityPlus($request, $product["post_parent"]);
                        else $actionError = $this->postAutoAction["AUTO_INCREASING"];
                    } else {
                        $ms = get_post_meta($product["ID"], "_manage_stock", true);
                        if ($ms == "yes") $this->productUpdateQuantityPlus($request, $product["ID"]);
                        else $actionError = $this->postAutoAction["AUTO_INCREASING"];
                    }
                }
                else {
                    $value = get_post_meta($product["ID"], $fieldName, true);
                    $value = $value && is_numeric($value) ? $value : 0;
                    update_post_meta($product["ID"], $fieldName, $value + 1);
                    LogActions::add($product["ID"], LogActions::$actions["quantity_plus"], $fieldName, $value + 1, $value, "product", $request);
                }

                return $this->productSearch($request, false, false, $actionError);
            case $this->postAutoAction["AUTO_DECREASING"]:
                if ($fieldName == "_stock") {
                    if (isset($product["product_manage_stock"]) && isset($product["post_parent"]) && $product["product_manage_stock"] === "parent" && $product["post_parent"]) {
                        $ms = get_post_meta($product["post_parent"], "_manage_stock", true);
                        if ($ms == "yes") $this->productUpdateQuantityMinus($request, $product["post_parent"]);
                        else $actionError = $this->postAutoAction["AUTO_DECREASING"];
                    } else {
                        $ms = get_post_meta($product["ID"], "_manage_stock", true);
                        if ($ms == "yes") $this->productUpdateQuantityMinus($request, $product["ID"]);
                        else $actionError = $this->postAutoAction["AUTO_DECREASING"];
                    }
                }
                else {
                    $settings = new Settings();
                    $allowNegativeStock = $settings->getSettings("allowNegativeStock");
                    $allowNegativeStock = $allowNegativeStock ? $allowNegativeStock->value : "";

                    $value = get_post_meta($product["ID"], $fieldName, true);
                    $value = $value && is_numeric($value) ? $value : 0;

                    if ($value > 0 || $allowNegativeStock == "on") {
                        update_post_meta($product["ID"], $fieldName, $value - 1);
                        LogActions::add($product["ID"], LogActions::$actions["quantity_minus"], $fieldName, $value - 1, $value, "product", $request);
                    }
                }

                return $this->productSearch($request, false, false, $actionError);
        }

        return false;
    }

    private function checkOrderAutoAction($request, $orderAutoAction, $orderAutoStatus, &$orders, $foundBy)
    {
        if (!$orderAutoAction || $foundBy || count($orders) !== 1) {
            return false;
        }

        $order = &$orders[0];

        if (!in_array($order["post_type"], array("shop_order"))) {
            return false;
        }

        if (!$orderAutoStatus) {
            return false;
        }

        $orderId = $order["ID"];

        switch ($orderAutoAction) {
            case $this->orderAutoAction["ORDER_STATUS"]: {
                    $order = new \WC_Order($orderId);

                    if ($order) {
                        $oldValue = $order->get_status();
                        $order->update_status($orderAutoStatus);
                        $this->productIndexation($orderId, "orderChangeStatus");
                        LogActions::add($orderId, LogActions::$actions["update_order_status"], "post_status", $orderAutoStatus, $oldValue, "order", $request);
                    }

                    return $this->orderSearch($request, false, true);
                    break;
                }
        }

        return false;
    }

    public function productEnableManageStock(WP_REST_Request $request)
    {
        $query = RequestHelper::getQuery($request, "product");
        $productId = $query;

        $settings = new Settings();
        $wpmlRow = $settings->getSettings("wpmlUpdateProductsTree");
        $isUpdateAllProds = $wpmlRow === null ? $settings->getField("general", "wpmlUpdateProductsTree", "") : $wpmlRow->value;

        $result = true;

        if ($isUpdateAllProds === "on") {
            try {
                $productsIds = (array)$request->get_param("products");

                if (count($productsIds) === 0) {
                    $productsIds = array($productId);
                }

                if (count($productsIds) > 0) {
                    foreach ($productsIds as $id) {
                        $result = $this->setManageStock($id);
                        LogActions::add($id, LogActions::$actions["enable_stock"], "_stock_status", "on", "", "product", $request);
                    }
                }
            } catch (\Throwable $th) {
            }
        } else {
            $result = $this->setManageStock($productId);
            LogActions::add($productId, LogActions::$actions["enable_stock"], "_stock_status", "on", "", "product", $request);
        }

        if ($result === true) {
            return $this->productSearch($request, true, true);
        } else {
            return $result;
        }
    }

    private function setManageStock($productId)
    {
        $product = \wc_get_product($productId);

        if ($product) {
            $product->set_manage_stock(true);
            $product->save();

            return true;
        } else {
            return array(
                "errors" => array("Product not found")
            );
        }
    }

    public function productUpdateQuantity(WP_REST_Request $request, $postId = null, $quantity = null)
    {
        $customFilter = $request->get_param("customFilter");
        $query = RequestHelper::getQuery($request, "product");
        $productId = $postId ? $postId : $query;

        if (!$quantity && (int)$quantity != 0) {
            $quantity = $request->get_param("quantity");
        }

        $filteredData = 1;
        $filteredData = apply_filters($this->filter_quantity_update, $productId, $quantity, $customFilter);

        $settings = new Settings();
        $wpmlRow = $settings->getSettings("wpmlUpdateProductsTree");
        $isUpdateAllProds = $wpmlRow === null ? $settings->getField("general", "wpmlUpdateProductsTree", "") : $wpmlRow->value;

        $result = true;

        if ($isUpdateAllProds === "on" && $filteredData !== null) {
            try {
                $productsIds = (array)$request->get_param("products");

                if (count($productsIds) === 0) {
                    $productsIds = array($productId);
                }

                if (count($productsIds) > 0) {
                    foreach ($productsIds as $id) {
                        $oldValue = get_post_meta($id, '_stock', true);
                        $result = $this->setQuantity($id, $quantity, null, true);
                        LogActions::add($id, LogActions::$actions["update_qty"], "_stock", $quantity, $oldValue, "product", $request);
                    }
                }
            } catch (\Throwable $th) {
            }
        } else if ($filteredData !== null) {
            $oldValue = get_post_meta($productId, '_stock', true);
            $result = $this->setQuantity($productId, $quantity, null, true);
            LogActions::add($productId, LogActions::$actions["update_qty"], "_stock", $quantity, $oldValue, "product", $request);
        }

        if ($result === true) {
            return $this->productSearch($request, true, true);
        } else {
            return $result;
        }
    }

    public function setQuantity($productId, $quantity, $product = null, $checkHershold = false)
    {
        if ($quantity != "") $this->setManageStock($productId);

        if (!$product) {
            $product = \wc_get_product($productId);
        }

        if ($product) {
            Debug::addPoint("setQuantity productId: {$productId}, quantity: {$quantity}");

            $product->set_stock_quantity($quantity);
            $product->save();

            if ($quantity != "") {
                update_post_meta($product->get_id(), "_stock", $quantity);

                if ($quantity > 0) {
                    update_post_meta($product->get_id(), "_stock_status", "instock");
                }
            }

            if ($checkHershold) {
                $manageStock = get_post_meta($productId, "_manage_stock", true);
                $lowStockHershold = \wc_get_low_stock_amount($product);

                if (
                    $lowStockHershold != null
                    && $lowStockHershold != ""
                    && is_numeric($quantity)
                    && $quantity <= $lowStockHershold
                    && $manageStock == "yes"
                    && $product->get_manage_stock()
                ) {
                    Emails::sendLowStock($productId, $quantity, $product->get_name(), $lowStockHershold);
                }
            }

            $this->productIndexation($productId, "setQuantity");
            return true;
        } else {
            return rest_ensure_response(array(
                "errors" => array("Product not found")
            ));
        }
    }

    public function productUpdateQuantityPlus(WP_REST_Request $request, $productId = null)
    {
        $valid = (new Request())->validate($request);

        if ($valid !== true) {
            return $valid;
        }

        $customFilter = $request->get_param("customFilter");
        $query = RequestHelper::getQuery($request, "product");
        $productId = ($productId) ? $productId : $query;

        $filteredData = 1;
        $filteredData = apply_filters($this->filter_quantity_plus, $productId, $customFilter);

        $settings = new Settings();
        $wpmlRow = $settings->getSettings("wpmlUpdateProductsTree");
        $isUpdateAllProds = $wpmlRow === null ? $settings->getField("general", "wpmlUpdateProductsTree", "") : $wpmlRow->value;

        $result = true;

        if ($isUpdateAllProds === "on" && $filteredData !== null) {

            try {
                $productsIds = (array)$request->get_param("products");

                if (count($productsIds) === 0) {
                    $translations = WPML::getProductTranslations($productId);

                    if ($translations) {
                        $productsIds = array_column($translations["translations"], 'element_id');
                    }
                }

                if (count($productsIds) > 0) {
                    $curQuantity = null;
                    $step = null;

                    foreach ($productsIds as $id) {

                        if ($curQuantity === null) {
                            $product = \wc_get_product($id);

                            $step = apply_filters($this->filter_auto_action_step, 1, $id);

                            if ($step == 1) {
                                $curQuantity = (float)$product->get_stock_quantity();
                            } else {
                                $curQuantity = (float)get_post_meta($productId, "_stock", true);
                            }
                        }

                        $this->qtyBeforeUpdate[$productId] = $curQuantity;

                        $result = $this->setQuantityPlus($request, $id, $curQuantity, $step);
                    }
                } else if ($productId) {
                    $result = $this->setQuantityPlus($request, $productId);
                }
            } catch (\Throwable $th) {
            }
        } else if ($filteredData !== null) {
            $result = $this->setQuantityPlus($request, $productId);
        }

        if ($result === true) {
            return $this->productSearch($request, false, true);
        } else {
            return $result;
        }
    }

    public function setQuantityPlus($request, $productId = null, $curQty = null, $step = null)
    {
        $this->setManageStock($productId);

        $product = \wc_get_product($productId);

        if ($product) {
            if ($curQty === null && $step === null) {
                $step = apply_filters($this->filter_auto_action_step, 1, $productId);

                if ($step == 1) {
                    $qty = (float)$product->get_stock_quantity();
                } else {
                    $qty = (float)get_post_meta($productId, "_stock", true);
                }
            } else {
                $step = 1;
                $qty = $curQty;
            }

            if ($qty == 0) {
                $this->setQuantity($productId, $step, null, true);
                LogActions::add($productId, LogActions::$actions["quantity_plus"], "_stock", $step, 0, "product", $request);
            } else {
                $this->setQuantity($productId, $qty + $step, null, true);
                LogActions::add($productId, LogActions::$actions["quantity_plus"], "_stock", $qty + $step, $qty, "product", $request);
            }

            return true;
        } else {
            return rest_ensure_response(array(
                "errors" => array("Product not found")
            ));
        }
    }

    public function productUpdateQuantityMinus(WP_REST_Request $request, $productId = null)
    {
        global $wpdb;

        $valid = (new Request())->validate($request);

        if ($valid !== true) {
            return $valid;
        }

        $customFilter = $request->get_param("customFilter");
        $query = RequestHelper::getQuery($request, "product");
        $productId = ($productId) ? $productId : $query;

        $filteredData = 1;
        $filteredData = apply_filters($this->filter_quantity_minus, $productId, $customFilter);

        $settings = new Settings();
        $wpmlRow = $settings->getSettings("wpmlUpdateProductsTree");
        $isUpdateAllProds = $wpmlRow === null ? $settings->getField("general", "wpmlUpdateProductsTree", "") : $wpmlRow->value;

        $result = true;

        Debug::addPoint("isUpdateAllProds: {$isUpdateAllProds}");

        if ($isUpdateAllProds === "on" && $filteredData !== null) {
            try {
                $productsIds = (array)$request->get_param("products");

                if (count($productsIds) === 0) {
                    $translations = WPML::getProductTranslations($productId);

                    Debug::addPoint("productUpdateQuantityMinus translations: " . json_encode($translations));

                    if ($translations) {
                        $productsIds = array_column($translations["translations"], 'element_id');
                    }
                }

                if (count($productsIds) > 0) {
                    $curQuantity = null;
                    $step = null;

                    foreach ($productsIds as $id) {

                        if ($curQuantity === null) {
                            $product = \wc_get_product($id);

                            $step = apply_filters($this->filter_auto_action_step, 1, $id);

                            if ($step == 1) {
                                $curQuantity = (float)$product->get_stock_quantity();
                            } else {
                                $curQuantity = (float)get_post_meta($productId, "_stock", true);
                            }
                        }

                        $this->qtyBeforeUpdate[$productId] = $curQuantity;

                        $result = $this->setQuantityMinus($request, $id, $curQuantity, $step);
                    }
                } else if ($productId) {
                    $result = $this->setQuantityMinus($request, $productId);
                }
            } catch (\Throwable $th) {
            }
        } else if ($filteredData !== null) {
            $result = $this->setQuantityMinus($request, $productId);
        }

        if ($result === true) {
            return $this->productSearch($request, false, true);
        } else {
            return $result;
        }
    }

    private function setQuantityMinus($request, $productId = null, $curQty = null, $step = null)
    {
        $this->setManageStock($productId);

        $settings = new Settings();
        $allowNegativeStock = $settings->getSettings("allowNegativeStock");
        $allowNegativeStock = $allowNegativeStock ? $allowNegativeStock->value : "";
        $product = \wc_get_product($productId);

        if ($product) {
            if ($curQty === null && $step === null) {
                $step = apply_filters($this->filter_auto_action_step, 1, $productId);

                if ($step == 1) {
                    $qty = (float)$product->get_stock_quantity();
                } else {
                    $qty = (float)get_post_meta($productId, "_stock", true);
                }

                $this->qtyBeforeUpdate[$productId] = $qty;
            } else {
                $step = 1;
                $qty = $curQty;
            }

            if ($qty > 0 || $allowNegativeStock === "on") {
                $this->setQuantity($productId, $qty - $step, null, true);
                LogActions::add($productId, LogActions::$actions["quantity_minus"], "_stock", $qty - $step, $qty, "product", $request);
            }

            return true;
        } else {
            return rest_ensure_response(array(
                "errors" => array("Product not found")
            ));
        }
    }

    public function productUpdateRegularPrice(WP_REST_Request $request, $postId = null, $regularPrice = null)
    {
        $query = RequestHelper::getQuery($request, "product");
        $price = $request->get_param("price");
        $productId = $postId ? $postId : $query;
        $price = $regularPrice ? $regularPrice : $price;

        if ($price == 0) {
            $price = "";
        }

        $settings = new Settings();
        $wpmlRow = $settings->getSettings("wpmlUpdateProductsTree");
        $isUpdateAllProds = $wpmlRow === null ? $settings->getField("general", "wpmlUpdateProductsTree", "") : $wpmlRow->value;

        $result = true;

        if ($isUpdateAllProds === "on") {
            try {
                $productsIds = (array)$request->get_param("products");

                if (count($productsIds) === 0) {
                    $productsIds = array($productId);
                }

                if (count($productsIds) > 0) {
                    foreach ($productsIds as $id) {
                        $result = $this->setRegularPrice($request, $id, $price);
                    }
                }
            } catch (\Throwable $th) {
            }
        } else {
            $result = $this->setRegularPrice($request, $productId, $price);
        }

        if ($result === true) {
            return $this->productSearch($request, false, true);
        } else {
            return $result;
        }
    }

    private function setRegularPrice($request, $productId, $price)
    {
        $product = \wc_get_product($productId);

        if ($product) {
            $oldValue = $product->get_regular_price();
            $product->set_regular_price($price);
            $product->save();

            @wc_delete_product_transients($productId);

            $this->productIndexation($productId, "setRegularPrice");
            LogActions::add($productId, LogActions::$actions["update_regular_price"], "_regular_price", $price, $oldValue, "product", $request);

            return true;
        } else {
            return rest_ensure_response(array(
                "errors" => array("Product not found")
            ));
        }
    }

    public function productUpdateSalePrice(WP_REST_Request $request, $postId = null, $salePrice = null)
    {
        $query = RequestHelper::getQuery($request, "product");
        $price = $request->get_param("price");
        $productId = $postId ? $postId : $query;
        $price = $salePrice ? $salePrice : $price;

        if ($price == 0) {
            $price = "";
        }

        $settings = new Settings();
        $wpmlRow = $settings->getSettings("wpmlUpdateProductsTree");
        $isUpdateAllProds = $wpmlRow === null ? $settings->getField("general", "wpmlUpdateProductsTree", "") : $wpmlRow->value;

        $result = true;

        if ($isUpdateAllProds === "on") {
            try {
                $productsIds = (array)$request->get_param("products");

                if (count($productsIds) === 0) {
                    $productsIds = array($productId);
                }

                if (count($productsIds) > 0) {
                    foreach ($productsIds as $id) {
                        $result = $this->setSalePrice($request, $id, $price);
                    }
                }
            } catch (\Throwable $th) {
            }
        } else {
            $result = $this->setSalePrice($request, $productId, $price);
        }

        if ($result === true) {
            return $this->productSearch($request, false, true);
        } else {
            return $result;
        }
    }

    public function updateProductCustomPrice(WP_REST_Request $request, $postId = null, $salePrice = null)
    {
        $query = RequestHelper::getQuery($request, "product");
        $price = $request->get_param("price");
        $field = $request->get_param("field");
        $productId = $postId ? $postId : $query;
        $price = $salePrice ? $salePrice : $price;

        if ($price == 0) {
            $price = "";
        }

        $settings = new Settings();
        $wpmlRow = $settings->getSettings("wpmlUpdateProductsTree");
        $isUpdateAllProds = $wpmlRow === null ? $settings->getField("general", "wpmlUpdateProductsTree", "") : $wpmlRow->value;

        $result = true;

        if ($isUpdateAllProds === "on") {
            try {
                $productsIds = (array)$request->get_param("products");

                if (count($productsIds) === 0) {
                    $productsIds = array($productId);
                }

                if (count($productsIds) > 0) {
                    foreach ($productsIds as $id) {
                        $filterName = str_replace("%field", $field, $this->filter_set_after);
                        $filteredValue = apply_filters($filterName, $price, $field, $id);

                        if ($field === "_regular_price") {
                            $this->setRegularPrice($request, $id, $filteredValue);
                        } else if ($field === "_sale_price") {
                            $this->setSalePrice($request, $id, $filteredValue);
                        } else {
                            $oldValue = \get_post_meta($id, $field, true);
                            $result = $this->updateCustomField($id, $field, $filteredValue);
                            $customACtion = $this->getPriceFieldLabel($field);
                            LogActions::add($id, LogActions::$actions["update_custom_field"], $field, $filteredValue, $oldValue, "product", $request, $customACtion);
                        }

                        @wc_delete_product_transients($id);
                    }
                }
            } catch (\Throwable $th) {
            }
        } else {
            $filterName = str_replace("%field", $field, $this->filter_set_after);
            $filteredValue = apply_filters($filterName, $price, $field, $productId);

            if ($field === "_regular_price") {
                $this->setRegularPrice($request, $productId, $filteredValue);
            } else if ($field === "_sale_price") {
                $this->setSalePrice($request, $productId, $filteredValue);
            } else {
                $oldValue = \get_post_meta($productId, $field, true);
                $result = $this->updateCustomField($productId, $field, $filteredValue);
                $customACtion = $this->getPriceFieldLabel($field);
                LogActions::add($productId, LogActions::$actions["update_custom_field"], $field, $filteredValue, $oldValue, "product", $request, $customACtion);
            }

            @wc_delete_product_transients($productId);
        }

        if ($result === true) {
            return $this->productSearch($request, false, true);
        } else {
            return $result;
        }
    }

    public function productUpdateMeta(WP_REST_Request $request, $productId = null, $key = null, $value = null)
    {
        $customAction = $request->get_param("customAction");

        if ($key == null || ($value == null && $value != "") || $productId == null) {
            $key = $request->get_param("key");
            $value = $request->get_param("value");
            $productId = RequestHelper::getQuery($request, "product");
        }

        $settings = new Settings();
        $wpmlRow = $settings->getSettings("wpmlUpdateProductsTree");
        $isUpdateAllProds = $wpmlRow === null ? $settings->getField("general", "wpmlUpdateProductsTree", "") : $wpmlRow->value;

        $result = true;

        if ($isUpdateAllProds === "on") {
            try {
                $productsIds = (array)$request->get_param("products");

                if (count($productsIds) === 0) {
                    $productsIds = array($productId);
                }

                if (count($productsIds) > 0) {
                    foreach ($productsIds as $id) {
                        $filterName = str_replace("%field", $key, $this->filter_set_after);
                        $filteredValue = apply_filters($filterName, $value, $key, $id);

                        if ($key === "_sku") {
                            $oldValue = \get_post_meta($id, "_sku", true);
                            $result = $this->setSKU($id, $filteredValue);
                            LogActions::add($id, LogActions::$actions["sku"], "_sku", $filteredValue, $oldValue, "product", $request);
                        } else if ($key === "_stock") {
                            if ($filteredValue === "") {
                                $this->productUpdateMeta($request, $productId, "_manage_stock", "no");
                            }
                            $result = $this->productUpdateQuantity($request, $id, $filteredValue);
                        } else if ($key === "usbs_product_status") {
                            $result =  $this->productUpdateStatus($request, $productId, $filteredValue);
                        } else if ($key === "_shipping_class") {
                            $result =  $this->productUpdateShippingClass($request, $productId, $filteredValue);
                        } else {
                            $oldValue = \get_post_meta($id, $key, true);
                            update_post_meta($id, $key, $filteredValue);
                            LogActions::add($id, LogActions::$actions["update_meta_field"], $key, $filteredValue, $oldValue, "product", $request, $customAction);
                        }

                        $this->productIndexation($id, "productUpdateMeta");
                    }
                }
            } catch (\Throwable $th) {
            }
        } else {
            $filterName = str_replace("%field", $key, $this->filter_set_after);
            $filteredValue = apply_filters($filterName, $value, $key, $productId);

            if ($key === "_sku") {
                $oldValue = \get_post_meta($productId, "_sku", true);
                $result = $this->setSKU($productId, $filteredValue);
                LogActions::add($productId, LogActions::$actions["sku"], "_sku", $filteredValue, $oldValue, "product", $request);
            } else if ($key === "_stock") {
                if ($filteredValue === "") {
                    $this->productUpdateMeta($request, $productId, "_manage_stock", "no");
                }
                $result = $this->productUpdateQuantity($request, $productId, $filteredValue);
            } else if ($key === "usbs_product_status") {
                $result =  $this->productUpdateStatus($request, $productId, $filteredValue);
            } else if ($key === "_shipping_class") {
                $result =  $this->productUpdateShippingClass($request, $productId, $filteredValue);
            } else {
                $oldValue = \get_post_meta($productId, $key, true);
                update_post_meta($productId, $key, $filteredValue);
                LogActions::add($productId, LogActions::$actions["update_meta_field"], $key, $filteredValue, $oldValue, "product", $request, $customAction);
            }

            $this->productIndexation($productId, "productUpdateMeta");
        }

        if ($result === true) {
            return $this->productSearch($request, false, true);
        } else {
            return $result;
        }
    }

    private function setSKU($productId, $sku)
    {
        try {
            $product = \wc_get_product($productId);

            if ($product) {
                $product->set_sku($sku);
                $product->save();

                $this->productIndexation($productId, "setSKU");

                return true;
            } else {
                return rest_ensure_response(array(
                    "errors" => array("Product not found")
                ));
            }
        } catch (\Throwable $th) {
            return rest_ensure_response(array(
                "errors" => array($th->getMessage())
            ));
        }
    }

    public function productUpdateStatus(WP_REST_Request $request, $postId = null, $status = null)
    {
        try {
            $postId = $postId ? $postId : $request->get_param("query");
            $status = $status ? $status : $request->get_param("status");

            if (!$postId || !$status) {
                return rest_ensure_response(array("errors" => array("Something wrong")));
            }
            $oldValue = get_post_status($postId);
            wp_update_post(array('ID' => $postId, 'post_status' => $status));
            $this->productIndexation($postId, "setSalePrice");

            LogActions::add($postId, LogActions::$actions["update_product_status"], "post_status", $status, $oldValue, "product", $request);

            return $this->productSearch($request, false, true);
        } catch (\Throwable $th) {
            return rest_ensure_response(array("errors" => array($th->getMessage())));
        }
    }

    public function productUpdateShippingClass(WP_REST_Request $request, $postId = null, $value = null)
    {
        try {
            wp_set_object_terms($postId, $value, 'product_shipping_class');
            LogActions::add($postId, LogActions::$actions["update_product_shipping"], "shipping class", $value, "", "product", $request);

            return $this->productSearch($request, false, true);
        } catch (\Throwable $th) {
            return rest_ensure_response(array("errors" => array($th->getMessage())));
        }
    }

    private function setSalePrice($request, $productId, $price)
    {
        $product = \wc_get_product($productId);

        if ($product) {
            $oldValue = $product->get_sale_price();
            $product->set_sale_price($price);
            $product->save();

            @wc_delete_product_transients($productId);

            $this->productIndexation($productId, "setSalePrice");
            LogActions::add($productId, LogActions::$actions["update_sale_price"], "_sale_price", $price, $oldValue, "product", $request);

            return true;
        } else {
            return rest_ensure_response(array(
                "errors" => array("Product not found")
            ));
        }
    }

    private function updateCustomField($postId, $field, $value)
    {
        update_post_meta($postId, $field, $value);

        $this->productIndexation($postId, "updateCustomField");

        return true;
    }

    public function orderSearch(WP_REST_Request $request, $actions = true, $findById = false)
    {
        $autoFill = $request->get_param("autoFill");
        $query = RequestHelper::getQuery($request, "order");
        $filter = SearchFilter::get();
        $customFilter = $request->get_param("customFilter");
        $filterExcludes = $request->get_param("filterExcludes");
        $platform = $request->get_param("platform");
        $orderAutoAction = $request->get_param("orderAutoAction");
        $orderAutoStatus = $request->get_param("orderAutoStatus");
        $postAutoField = $request->get_param("postAutoField");
        $postAutoField = $postAutoField ? $postAutoField : "_stock";
        $isNew = $request->get_param("isNew");
        $filterResult = $request->get_param("filterResult");

        $byId = $request->get_param("byId");
        $onlyById = $byId || $findById ? true : false;

        $result = array(
            "orders" => null,
            "findByTitle" => null,
            "isNew" => $isNew ? 1 : 0
        );

        if ($platform !== "web") {
        }

        if (HPOS::getStatus()) {
            Debug::addPoint("start HPOS()->findOrders");
            $data = HPOS::findOrders($query, $filter, $onlyById, $autoFill, $filterExcludes);
            Debug::addPoint("end HPOS()->findOrders");

            if ($filterResult && $data && isset($filterResult["postId"]) && is_array($data["posts"]) && count($data["posts"]) > 1) {
                $data["posts"] = array_values(array_filter(
                    $data["posts"],
                    function ($_post) use ($filterResult) {
                        return $_post->id == $filterResult["postId"];
                    }
                ));
            }

            Debug::addPoint("start HPOS()->ordersPrepare");
            $postCounter = $data["posts"] ? count($data["posts"]) : 0;
            $orders = HPOS::ordersPrepare($data["posts"], array(
                "useAction" => $orderAutoAction && $orderAutoAction != "empty" ? $postAutoField : false,
                "autoStatus" => $orderAutoStatus,
                "isNew" => $isNew ? 1 : 0,
                "isAutoFill" => $autoFill
            ), $autoFill || $postCounter > 1);
            Debug::addPoint("end HPOS()->ordersPrepare");
        }
        else {
            Debug::addPoint("start Post()->find");
            $data = (new Post())->find($query, $filter, $onlyById, $autoFill, null, "order", $filterExcludes);
            Debug::addPoint("end Post()->find");

            if ($filterResult && $data && isset($filterResult["postId"]) && is_array($data["posts"]) && count($data["posts"]) > 1) {
                $data["posts"] = array_values(array_filter(
                    $data["posts"],
                    function ($_post) use ($filterResult) {
                        return $_post->ID == $filterResult["postId"];
                    }
                ));
            }

            Debug::addPoint("start Results()->ordersPrepare");
            $postCounter = $data["posts"] ? count($data["posts"]) : 0;
            $orders = (new Results())->ordersPrepare($data["posts"], array(
                "useAction" => $orderAutoAction && $orderAutoAction != "empty" ? $postAutoField : false,
                "autoStatus" => $orderAutoStatus,
                "isNew" => $isNew ? 1 : 0,
                "isAutoFill" => $autoFill
            ), $autoFill || $postCounter > 1);
            Debug::addPoint("end Results()->ordersPrepare");
        }

        if ($orders) {
            if ($actions) {
                $actionResult = $this->checkOrderAutoAction($request, $orderAutoAction, $orderAutoStatus, $orders, $data["findByTitle"]);

                if ($actionResult !== false) {
                    return $actionResult;
                }
            }

            $orders = apply_filters($this->filter_search_result, $orders, $customFilter);
            $result['orders'] = $orders;
            $result['findByTitle'] = $data["findByTitle"];
        } else {
            $requestName = $request->get_param("request");

            if ($requestName === "order-search") {
                return $this->productSearch($request, false);
            }
        }

        if (isset($data["query"])) {
            $result["foundBy"] = $data["query"];
        }

        if (isset($result["orders"]) && count($result["orders"]) === 1 && !$autoFill && !$findById) {
            LogActions::add($result["orders"][0]["ID"], LogActions::$actions["open_order"], "", "", "", "order", $request);
        }

        return rest_ensure_response($result);
    }

    public function orderChangeStatus(WP_REST_Request $request)
    {
        $orderId = $request->get_param("orderId");
        $status = $request->get_param("status");
        $customFilter = $request->get_param("customFilter");
        $filterExcludes = $request->get_param("filterExcludes");

        $result = array(
            "orders" => null,
            "findByTitle" => null,
        );

        $order = new \WC_Order($orderId);

        if ($order) {
            $oldValue = $order->get_status();
            $order->update_status($status);
            $this->productIndexation($orderId, "orderChangeStatus");

            LogActions::add($orderId, LogActions::$actions["update_order_status"], "post_status", $status, $oldValue, "order", $request);
        }

        $data = (new Post())->find($orderId, array(), false, false, null, "order", $filterExcludes);
        $orders = (new Results())->ordersPrepare($data["posts"]);

        if ($orders) {
            $orders = apply_filters($this->filter_search_result, $orders, $customFilter);
            $result['orders'] = $orders;
        }

        if (isset($data["query"])) {
            $result["foundBy"] = $data["query"];
        }

        return rest_ensure_response($result);
    }

    public function orderChangeCustomer(WP_REST_Request $request)
    {
        global $wpdb;

        $orderId = $request->get_param("orderId");
        $customerId = $request->get_param("customerId");
        $customFilter = $request->get_param("customFilter");
        $filterExcludes = $request->get_param("filterExcludes");

        $result = array(
            "orders" => null,
            "findByTitle" => null,
        );

        $order = new \WC_Order($orderId);

        if ($order) {
            $oldValue = $order->get_customer_id();

            $order->set_customer_id($customerId);

            $address = array(
                'first_name' => get_user_meta($customerId, "billing_first_name", true),
                'last_name'  => get_user_meta($customerId, "billing_last_name", true),
                'company'    => get_user_meta($customerId, "billing_company", true),
                'email'      => get_user_meta($customerId, "billing_email", true),
                'phone'      => get_user_meta($customerId, "billing_phone", true),
                'address_1'  => get_user_meta($customerId, "billing_address_1", true),
                'address_2'  => get_user_meta($customerId, "billing_address_2", true),
                'city'       => get_user_meta($customerId, "billing_city", true),
                'state'      => get_user_meta($customerId, "billing_state", true),
                'postcode'   => get_user_meta($customerId, "billing_postcode", true),
                'country'    => get_user_meta($customerId, "billing_country", true),
            );
            $order->set_address($address, 'billing');

            $prefix = true ? "billing" : "shipping";
            $address = array(
                'first_name' => get_user_meta($customerId, $prefix . "_first_name", true),
                'last_name'  => get_user_meta($customerId, $prefix . "_last_name", true),
                'company'    => get_user_meta($customerId, $prefix . "_company", true),
                'phone'      => get_user_meta($customerId, $prefix . "_phone", true),
                'address_1'  => get_user_meta($customerId, $prefix . "_address_1", true),
                'address_2'  => get_user_meta($customerId, $prefix . "_address_2", true),
                'city'       => get_user_meta($customerId, $prefix . "_city", true),
                'state'      => get_user_meta($customerId, $prefix . "_state", true),
                'postcode'   => get_user_meta($customerId, $prefix . "_postcode", true),
                'country'    => get_user_meta($customerId, $prefix . "_country", true),
            );
            $order->set_address($address, 'shipping');


            $order->save();

            LogActions::add($orderId, LogActions::$actions["update_order_customer"], "_customer_user", $customerId, $oldValue, "order", $request);

            $this->productIndexation($orderId, "orderChangeCustomer");
        }

        $data = (new Post())->find($orderId, array(), false, false, null, "order", $filterExcludes);
        $orders = (new Results())->ordersPrepare($data["posts"]);

        if ($orders) {
            $orders = apply_filters($this->filter_search_result, $orders, $customFilter);
            $result['orders'] = $orders;
        }

        if (isset($data["query"])) {
            $result["foundBy"] = $data["query"];
        }

        return rest_ensure_response($result);
    }

    public function orderUpdateItemsMeta(WP_REST_Request $request)
    {
        $orderId = $request->get_param("orderId");
        $fields = $request->get_param("fields");
        $customFilter = $request->get_param("customFilter");

        if (!$orderId || !$fields) {
            return rest_ensure_response(array("error" => "Incorrect data."));
        }

        $order = wc_get_order($orderId);

        if (!$order) {
            return rest_ensure_response(array("error" => "Order not found."));
        }

        $items = $order->get_items();
        $isOrderFulfillmentReset = false;

        foreach ($items as $key => $item) {
            foreach ($fields as $field) {
                \wc_update_order_item_meta($key, $field["key"], $field["value"]);

                if ($field["key"] == "usbs_check_product") {
                    if ($field["value"] == "") {
                        if (!$isOrderFulfillmentReset) {
                            LogActions::add($orderId, LogActions::$actions["update_order_fulfillment"], "", 0, "", "order", $request);
                            $isOrderFulfillmentReset = true;
                        }
                    }
                }
            }
        }

        $post = get_post($orderId);
        $orders = (new Results())->ordersPrepare(array($post));

        if ($orders) {
            $orders = apply_filters($this->filter_search_result, $orders, $customFilter);
        }

        $result = array("success" => true, "orders" => $orders);

        return rest_ensure_response($result);
    }

    public function orderUpdateItemMeta(WP_REST_Request $request, $orderId = null, $itemId = null, $fields = null, $product = null)
    {
        $orderId = $orderId ? $orderId : $request->get_param("orderId");
        $itemId = $itemId ? $itemId : $request->get_param("itemId");
        $fields = $fields ? $fields :  $request->get_param("fields");
        $customFilter = $request->get_param("customFilter");

        if (!$orderId || !$itemId || !$fields) {
            return rest_ensure_response(array("error" => "Incorrect data.", "fulfillment" => 1));
        }

        $order = wc_get_order($orderId);

        if (!$order) {
            return rest_ensure_response(array("error" => "Order not found.", "fulfillment" => 1));
        }

        $settings = new Settings();
        $fulfillmentScanItemQty = $settings->getSettings("fulfillmentScanItemQty");
        $fulfillmentScanItemQty = $fulfillmentScanItemQty ? $fulfillmentScanItemQty->value == "on" : true;

        $items = $order->get_items();
        $isUpdated = false;
        $isFulfillmentChanged = false;
        $isFulfillmentAlready = false;

        $isCheckOrderFulfillment = false;
        $orderItemsFulfillmentSuccess = 0;
        $isOrderFulfillmentReset = false;

        foreach ($items as $item) {
            if ($item->get_id() == $itemId) {
                $step = 1;

                $pid = $item->get_product_id();

                $usbs_qty_step = ($product && isset($product["number_field_step"])) ? $product["number_field_step"] : 0;

                if ($usbs_qty_step && is_numeric($usbs_qty_step)) {
                    $step = (float)$usbs_qty_step;
                }

                foreach ($fields as $key => $field) {
                    if ($field["key"] == "usbs_check_product") {
                        if ($field["value"] == "") {
                            if (!$isOrderFulfillmentReset) {
                                \wc_update_order_item_meta($itemId, "usbs_check_product_scanned", "");
                                \wc_update_order_item_meta($itemId, "usbs_check_product", "");
                                $isFulfillmentChanged = true;
                                LogActions::add($orderId, LogActions::$actions["update_order_fulfillment"], "", 0, "", "order", $request);
                                $isOrderFulfillmentReset = true;
                            }
                        }
                        else if ($fulfillmentScanItemQty) {
                            $isCheckOrderFulfillment = true;

                            $qty = (float)\wc_get_order_item_meta($itemId, '_qty', true);
                            $scanned = (float)\wc_get_order_item_meta($itemId, 'usbs_check_product_scanned', true);

                            if ($qty && $scanned + $step < $qty) {
                                \wc_update_order_item_meta($itemId, "usbs_check_product_scanned", $scanned + $step);
                                $isFulfillmentChanged = true;
                            }
                            else if ($qty && $scanned < $qty) {
                                \wc_update_order_item_meta($itemId, "usbs_check_product_scanned", $scanned + $step);
                                $logId = LogActions::add($pid, LogActions::$actions["update_order_item_meta"], $field["key"], $field["value"] ? $step : 0, "", "order_item", $request, "", $orderId);
                                \wc_update_order_item_meta($itemId, $field["key"], $field["value"] ? $logId : "");
                                $isFulfillmentChanged = true;
                            }
                            else {
                                $scanned = \wc_get_order_item_meta($itemId, 'usbs_check_product', true);
                                if (!$scanned) {
                                    \wc_update_order_item_meta($itemId, $field["key"], time());
                                }
                                $isFulfillmentAlready = true;
                            }
                        }
                        else {
                            $isCheckOrderFulfillment = true;

                            $scanned = \wc_get_order_item_meta($itemId, 'usbs_check_product', true);

                            if (!$scanned) {
                                $logId = LogActions::add($pid, LogActions::$actions["update_order_item_meta"], $field["key"], $field["value"] ? 1 : 0, "", "order_item", $request, "", $orderId);
                                \wc_update_order_item_meta($itemId, $field["key"], $field["value"] ? $logId : "");
                                $isFulfillmentChanged = true;
                            }
                        }
                    } else {
                        \wc_update_order_item_meta($itemId, $field["key"], $field["value"]);
                    }
                }

                $isUpdated = true;
            }

            $usbs_check_product = \wc_get_order_item_meta($item->get_id(), 'usbs_check_product', true);

            if ($usbs_check_product && $usbs_check_product != "") {
                $orderItemsFulfillmentSuccess += 1;
            }
        }

        if ($isCheckOrderFulfillment && $isFulfillmentChanged && count($items) == $orderItemsFulfillmentSuccess) {
            $logId = LogActions::add($orderId, LogActions::$actions["update_order_fulfillment"], "", 1, "", "order", $request);
        }

        $post = get_post($orderId);
        $orders = (new Results())->ordersPrepare(array($post));

        if ($orders) {
            $orders = apply_filters($this->filter_search_result, $orders, $customFilter);

            if ($isFulfillmentChanged || $isFulfillmentAlready) {
                if ($orders[0] && $orders[0]["products"]) {
                    foreach ($orders[0]["products"] as $key => &$value) {
                        if (isset($value["item_id"]) && $value["item_id"] == $itemId) {
                            if ($isFulfillmentChanged) {
                                $orders[0]["products"][$key]["updatedAction"] = "checked" . time();
                            } else if ($isFulfillmentAlready) {
                                $orders[0]["products"][$key]["updatedAction"] = "already" . time();
                            }
                        }
                    }
                }
            }
        }


        $result = array("success" => true, "isUpdated" => $isUpdated, "orders" => $orders, "fulfillment" => 1);

        return rest_ensure_response($result);
    }

    public function productUpdateTitle(WP_REST_Request $request)
    {
        $query = RequestHelper::getQuery($request, "product");
        $title = $request->get_param("title");
        $customFilter = $request->get_param("customFilter");
        $filterExcludes = $request->get_param("filterExcludes");
        $productId = $query;
        $title = trim($title);

        if ($productId && $title) {
            $oldValue = get_the_title($productId);
            $my_post = array(
                'ID' => $productId,
                'post_title' => $title,
            );

            wp_update_post($my_post);

            LogActions::add($productId, LogActions::$actions["update_title"], "post_title", $title, $oldValue, "product", $request);
        }

        $result = array(
            "products" => null,
            "findByTitle" => null,
        );

        $data = (new Post())->find($query, array("products" => array("ID" => true)), true, false, null, "product", $filterExcludes);
        $products = (new Results())->productsPrepare($data["posts"], array("useAction" => false));
        WPML::addTranslations($products);
        $products = apply_filters($this->filter_search_result, $products, $customFilter);
        $result["products"] = $products;

        if (isset($data["query"])) {
            $result["foundBy"] = $data["query"];
        }

        return rest_ensure_response($result);
    }

    public function productSetImage(WP_REST_Request $request)
    {
        $postId = $request->get_param("postId");
        $attachmentId = $request->get_param("attachmentId");
        $customFilter = $request->get_param("customFilter");
        $filterExcludes = $request->get_param("filterExcludes");

        $oldValue = "";

        set_post_thumbnail($postId, $attachmentId);

        LogActions::add($postId, LogActions::$actions["set_product_image"], "", $attachmentId, $oldValue, "product", $request);

        $result = array(
            "products" => null,
            "findByTitle" => null,
        );

        $data = (new Post())->find($postId, array("products" => array("ID" => true)), true, false, null, "product", $filterExcludes);
        $products = (new Results())->productsPrepare($data["posts"], array("useAction" => false));
        WPML::addTranslations($products);
        $products = apply_filters($this->filter_search_result, $products, $customFilter);
        $result["products"] = $products;

        if (isset($data["query"])) {
            $result["foundBy"] = $data["query"];
        }

        return rest_ensure_response($result);
    }

    public function productCreateNew(WP_REST_Request $request, $query = "", $meta = array())
    {
        $query = $query ? $query : RequestHelper::getQuery($request, "product");
        $filterExcludes = $request->get_param("filterExcludes");
        $status = trim($request->get_param("status"));
        $query = trim($query);
        $productId = null;

        $settings = new Settings();

        if (!$status) {
            $field = $settings->getSettings("newProductStatus");
            $status = $field ? $field->value : "";
        }

        $post_id = wp_insert_post(array(
            'post_title' => 'Product name',
            'post_type' => 'product',
            'post_status' => $status ? $status : 'draft',
            'post_content' => '',
        ));
        $product = \wc_get_product($post_id);
        $product->save();

        if ($product->id) {
            $productId = $product->id;

            $field = $settings->getSettings("fieldForNewProduct");
            $fieldNameValue = $field === null ? $settings->getField("general", "fieldForNewProduct", "_sku") : $field->value;

            if ($fieldNameValue == "custom_field") {
                $field = $settings->getSettings("cfForNewProduct");
                $fieldNameValue = $field === null ? "_sku" : $field->value;
            }

            if ($query) {
                $this->productUpdateMeta($request, $productId, $fieldNameValue, $query);
            }

            $field = $settings->getSettings("newProductQty");
            $qty = $field === null ? $settings->getField("general", "newProductQty", "") : $field->value;

            if ($qty !== "" && (int)$qty) {
                $this->setQuantity($product->id, $qty, null, true);
            }

            if ($meta && isset($meta["_manage_stock"])) {
                $this->setManageStock($product->id);
            }

            $this->productIndexation($product->id, "productCreateNew");

            LogActions::add($product->id, LogActions::$actions["create_product"], "", "", "", "product", $request);
        }

        $result = array(
            "products" => null,
            "findByTitle" => null,
        );

        if ($productId) {
            $data = (new Post())->find($productId, array("products" => array("ID" => true)), true, false, null, "product", $filterExcludes);
            $products = (new Results())->productsPrepare($data["posts"], array("useAction" => false));

            if (count($products)) {
                $products[0]["newProduct"] = true;
            }

            WPML::addTranslations($products);
            $result["products"] = $products;

            if (isset($data["query"])) {
                $result["foundBy"] = $data["query"];
            }
        }

        return rest_ensure_response($result);
    }


    public function reloadNewProduct(WP_REST_Request $request)
    {
        $postId = $request->get_param("postId");
        $filterExcludes = $request->get_param("filterExcludes");

        $result = array(
            "products" => null,
            "findByTitle" => null,
        );

        if ($postId) {
            $data = (new Post())->find($postId, array("products" => array("ID" => true)), true, false, null, "product", $filterExcludes);
            $products = (new Results())->productsPrepare($data["posts"], array("useAction" => false));

            WPML::addTranslations($products);

            $result["products"] = $products;

            if (isset($data["query"])) {
                $result["foundBy"] = $data["query"];
            }
        }

        return rest_ensure_response($result);
    }

    public function productUpdateFields(WP_REST_Request $request)
    {
        $customFilter = $request->get_param("customFilter");
        $filterExcludes = $request->get_param("filterExcludes");
        $fields = $request->get_param("fields");

        if ($fields) {
            $fields = json_decode(stripslashes($fields), true);
        }

        if (!isset($fields["postId"]) || !$fields["postId"]) {
            return rest_ensure_response(array(
                "errors" => array("Product ID is required.")
            ));
        }

        $postId = $fields["postId"];
        $errors = array();

        $this->uploadPick($request, $postId);

        if ($fields && is_array($fields)) {
            foreach ($fields as $key => $value) {
                switch ($key) {
                    case 'postId':
                        break;
                    case '_regular_price':
                    case 'regularPrice':
                        $this->productUpdateRegularPrice($request, $postId, $value);
                        break;
                    case '_sale_price':
                    case 'salePrice':
                        $this->productUpdateSalePrice($request, $postId, $value);
                        break;
                    case '_stock':
                    case 'quantity':
                        $filterName = str_replace("%field", $key, $this->filter_set_after);
                        $filteredValue = apply_filters($filterName, $value, $key, $postId);

                        if (!$filteredValue) {
                            $this->productUpdateMeta($request, $postId, "_manage_stock", "on");
                        }
                        $this->productUpdateQuantity($request, $postId, $value);
                        break;
                    case 'post_title':
                    case 'postTitle':
                        $post = array('ID' => $postId, 'post_title' => $value,);
                        wp_update_post($post);
                        break;
                    default:
                        $response = $this->productUpdateMeta($request, $postId, $key, $value);

                        if ($response && isset($response->data) && isset($response->data["errors"]) && $response->data["errors"]) {
                            $errors = $response->data["errors"];
                        }
                        break;
                }
            }
        }

        $result = array(
            "products" => null,
            "findByTitle" => null,
            "errors" => $errors,
        );

        $data = (new Post())->find($fields["postId"], array("products" => array("ID" => true)), true, false, null, "product", $filterExcludes);
        $products = (new Results())->productsPrepare($data["posts"], array("useAction" => false));
        WPML::addTranslations($products);
        $products = apply_filters($this->filter_search_result, $products, $customFilter);
        $result["products"] = $products;

        $this->productIndexation($fields["postId"], "productUpdateFields");

        if (isset($data["query"])) {
            $result["foundBy"] = $data["query"];
        }

        $result["productsList"] = PostsList::getList(Users::getUserId($request));

        return rest_ensure_response($result);
    }

    private function getPriceFieldLabel($field)
    {
        $settings = new Settings();

        if ($settings->getField("prices", "price_1_field", "_regular_price") === $field) {
            return $settings->getField("prices", "price_1_label", "Regular price");
        } else if ($settings->getField("prices", "price_2_field", "_sale_price") === $field) {
            return $settings->getField("prices", "price_2_label", "Sale price");
        } else if ($settings->getField("prices", "price_3_field", "_purchase_price") === $field) {
            return $settings->getField("prices", "price_3_label", "Purchase price");
        }
    }

    public function updateFoundCounter(WP_REST_Request $request)
    {
        try {
            $postId = $request->get_param("postId");
            $result = array();

            if ($postId) {
                $newCount = 1;
                $isOrder = false;

                if (HPOS::getStatus()) {
                    try {
                        $order = new \WC_Order($postId);
                    } catch (\Throwable $th) {
                    }

                    if ($order && $order->get_id()) {
                        $count = $order->get_meta("usbs_found_counter", true);
                        $newCount = $count ? (int)$count + 1 : 1;
                        $order->update_meta_data("usbs_found_counter", $newCount);
                        $order->save();
                        $isOrder = true;
                    }
                }

                if (!$isOrder) {
                    $count = \get_post_meta($postId, "usbs_found_counter", true);
                    $newCount = $count ? (int)$count + 1 : 1;
                    \update_post_meta($postId, "usbs_found_counter", $newCount);
                }

                $result["success"] = 1;
                $result["newCount"] = $newCount;

                History::add($postId);

                $result["settings"] = array("searchHistory" => History::getByUser());
            }

            return rest_ensure_response($result);
        } catch (\Throwable $th) {
            return rest_ensure_response(array("error" => $th->getMessage()));
        }
    }

    public function saveLog(WP_REST_Request $request)
    {
        try {
            $postId = $request->get_param("postId");
            $slug = $request->get_param("slug");
            $result = array();

            if ($postId && $slug && isset(LogActions::$actions[$slug])) {
                LogActions::add($postId, LogActions::$actions[$slug], "", "", "", "url", $request);
                $result["success"] = 1;
            }

            return rest_ensure_response($result);
        } catch (\Throwable $th) {
            return rest_ensure_response(array("error" => $th->getMessage()));
        }
    }

    public function uploadPick(WP_REST_Request $request, $postId = null)
    {
        $filterExcludes = $request->get_param("filterExcludes");

        if ($postId) {
            $id = $postId;
        } else {
            $id = $request->get_param("postId");
        }

        if (!$id) return;
        if (!isset($_FILES["image"])) return;

        $attachmentId = media_handle_upload('image', $id);

        if (is_wp_error($attachmentId)) {
        } else {
            set_post_thumbnail($id, $attachmentId);
        }

        if ($postId) {
            $result = array(
                "products" => null,
                "findByTitle" => null,
            );

            $data = (new Post())->find($id, array("products" => array("ID" => true)), true, false, null, "product", $filterExcludes);
            $products = (new Results())->productsPrepare($data["posts"], array("useAction" => false));
            $result["products"] = $products;

            if (isset($data["query"])) {
                $result["foundBy"] = $data["query"];
            }

            return rest_ensure_response($result);
        } else {
            return true;
        }
    }

    public function updateCategories(WP_REST_Request $request, $postId = null)
    {
        $postId = $request->get_param("postId");
        $categories = $request->get_param("categories");


        $settings = new Settings();
        $wpmlRow = $settings->getSettings("wpmlUpdateProductsTree");
        $isUpdateAllProds = $wpmlRow === null ? $settings->getField("general", "wpmlUpdateProductsTree", "") : $wpmlRow->value;

        $result = true;

        if ($isUpdateAllProds === "on") {
            try {
                $productsIds = (array)$request->get_param("products");

                if (count($productsIds) === 0) {
                    $productsIds = array($postId);
                }

                if (count($productsIds) > 0) {
                    foreach ($productsIds as $id) {
                        $product = \wc_get_product($id);

                        if (!$product) {
                            return rest_ensure_response(array("error" => "Product not found"));
                        }

                        $product->set_category_ids($categories);
                        $product->save();
                    }
                }
            } catch (\Throwable $th) {
            }
        }
        else {
            $product = \wc_get_product($postId);

            if (!$product) {
                return rest_ensure_response(array("error" => "Product not found"));
            }

            $product->set_category_ids($categories);
            $product->save();
        }

        $result = array(
            "products" => null,
            "findByTitle" => null,
        );

        $data = (new Post())->find($postId, array("products" => array("ID" => true)), true, false, null, "product", array());
        $products = (new Results())->productsPrepare($data["posts"], array("useAction" => false));
        $result["products"] = $products;

        if (isset($data["query"])) {
            $result["foundBy"] = $data["query"];
        }

        return rest_ensure_response($result);
    }


    public function importCodes(WP_REST_Request $request, $postId = null)
    {
        $isCheck = $request->get_param("isCheck");
        $codes = $request->get_param("codes");
        $importData = $request->get_param("lines");
        $autoActionData = $request->get_param("autoAction");

        if (!$codes || !is_array($codes)) {
            return rest_ensure_response(array("error" => "Empty list"));
        }

        $autoAction = isset($autoActionData["action"]) ? trim($autoActionData["action"]) : "";
        $autoActionField = isset($autoActionData["field"]) ? trim($autoActionData["field"]) : "";
        $filter = SearchFilter::get();
        $lines = array();

        if ($isCheck) {
            foreach ($codes as $key => $value) {
                $query = trim($value);

                if ($query) {
                    $data = (new Post())->find($query, $filter, false, true, 2, "product", array());
                    $products = (new Results())->productsPrepare($data["posts"], array("useAction" => false));

                    if ($products && count($products) == 1) {
                        $product = $products[0];
                        $pid = $product["ID"];

                        $ms = get_post_meta($pid, "_manage_stock", true);

                        if ($ms == "yes" || $autoActionField != "_stock") {
                            $lines[$key] = array("code" => $query, "id" => $pid, "name" => $data["posts"][0]->post_title, "success" => 1, "isImport" => 1);
                        } else {
                            $lines[$key] = array("code" => $query, "id" => $pid, "error" => __('"Manage stock" option is disabled for this product. Enable checkbox to enable stock and increase ' . $autoActionField, "us-barcode-scanner"));
                        }
                    }
                    else if ($data && $data["posts"] && count($data["posts"]) > 1) {
                        $ids = array_column($data["posts"], 'ID');
                        $lines[$key] = array("code" => $query, "id" => $ids, "error" => __("Found more than 1 product", "us-barcode-scanner"));
                    }
                    else {
                        $lines[$key] = array("code" => $query, "error" => __("Product not found, enable checkbox to create such product with this code.", "us-barcode-scanner"));
                    }
                }
                else {
                    $lines[$key] = array("code" => $query, "error" => __("Empty line", "us-barcode-scanner"));
                }
            }
        }
        else if (!$importData || !is_array($importData)) {
            return rest_ensure_response(array("error" => "Wrong import data"));
        }
        else {
            if (!$autoAction || !$autoActionField) {
                return rest_ensure_response(array("error" => "Wrong auto action data"));
            }


            $updatedProducts = 0;
            $createdProducts = 0;
            $createdCodes = array();

            foreach ($importData as $key => $value) {
                if (!isset($value["isImport"]) || $value["isImport"] != 1) continue;

                $id = isset($value["id"]) ? $value["id"] : null;
                $code = isset($value["code"]) ? $value["code"] : null;

                if (is_array($id)) {
                    foreach ($id as $pid) {
                        $this->importCodesAction($request, $pid, $autoAction, $autoActionField);
                        $updatedProducts++;
                    }
                }
                else if ($id) {
                    $this->importCodesAction($request, $id, $autoAction, $autoActionField);
                    $updatedProducts++;
                }
                else if ($code) {
                    $meta = $autoActionField == "_stock" ? array("_manage_stock" => "on") : array();

                    if (isset($createdCodes[$code])) {
                        $pid = $createdCodes[$code];
                        $updatedProducts++;
                    }
                    else {
                        $create = $this->productCreateNew($request, $code, $meta);
                        $pid = $create->data["products"][0]["ID"];
                        $createdCodes[$code] = $pid;
                        $createdProducts++;
                    }

                    if ($create && $create->data && isset($create->data["products"])) {
                        @$this->importCodesAction($request, $pid, $autoAction, $autoActionField);
                    }
                }
                else {
                    continue;
                }
            }

            return rest_ensure_response(array(
                "success" => 1,
                "lines" => $lines,
                "info" => array("updatedProducts" => $updatedProducts, "createdProducts" => $createdProducts)
            ));
        }

        return rest_ensure_response(array("lines" => $lines));

    }

    public function updateItemsFromList(WP_REST_Request $request)
    {
        $items = $request->get_param("items");

        if (!$items || !is_array($items)) {
            return rest_ensure_response(array("error" => "Incorrect data"));
        }

        $cartDecimalQuantity = false;

        try {
            $settings = new Settings();
            $field = $settings->getSettings("cartDecimalQuantity");
            $value = $field === null ? "off" : $field->value;
            $cartDecimalQuantity = $value === "on";
        } catch (\Throwable $th) {
        }

        foreach ($items as $value) {
            if ($value["post_id"]) {
                if ($cartDecimalQuantity) {
                    $quantity = (float)$value["quantity"];
                    $increase = isset($value["_stock"]) ? (float)$value["_stock"] : 0;
                }
                else {
                    $quantity = (int)$value["quantity"];
                    $increase = isset($value["_stock"]) ? (int)$value["_stock"] : 0;
                }

                $this->setManageStock($value["post_id"]);

                if ($cartDecimalQuantity) {
                    $currentQty = (float)get_post_meta($value["post_id"], '_stock', true);
                }
                else {
                    $currentQty = (int)get_post_meta($value["post_id"], '_stock', true);
                }
                $qty = $currentQty;

                if (is_numeric($quantity) && $qty != $quantity) {
                    $qty = $quantity;
                }
                else if ($qty != $quantity) {
                    $qty = 0;
                }

                if ($increase != 0) {
                    $this->productUpdateQuantity($request, $value["post_id"], $qty + $increase);
                }
                else if ($qty != $currentQty) {
                    $this->productUpdateQuantity($request, $value["post_id"], $qty);
                }
            }
        }

        $userId = Users::getUserId($request);

        PostsList::resetCounter($userId);

        return rest_ensure_response(array("success" => 1, "productsList" => PostsList::getList($userId), "uid" => $userId));
    }

    public function removeItemsListRecord(WP_REST_Request $request)
    {
        $recordId = (int)$request->get_param("recordId");

        if (!$recordId) {
            return rest_ensure_response(array("error" => "Incorrect data"));
        }

        $userId = Users::getUserId($request);

        PostsList::removeRecord($recordId);

        return rest_ensure_response(array("success" => 1, "productsList" => PostsList::getList($userId), "uid" => $userId));
    }

    public function clearItemsList(WP_REST_Request $request)
    {
        $userId = Users::getUserId($request);

        if ($userId) {
            PostsList::clear($userId);
        }

        return rest_ensure_response(array("success" => 1, "productsList" => PostsList::getList($userId), "uid" => $userId));
    }

    public function getOrdersList(WP_REST_Request $request)
    {
        $userId = Users::getUserId($request);

        $filter = $request->get_param("filter");

        $type = isset($filter["type"]) ? $filter["type"] : "";
        $status = isset($filter["status"]) ? $filter["status"] : "";
        $from = isset($filter["from"]) ? $filter["from"] : "";
        $to = isset($filter["to"]) ? $filter["to"] : "";
        $page = isset($filter["page"]) ? (int)$filter["page"] : "";

        $orders = array();

        $paged = $page ? $page : 1;
        $perPage = 10;
        $args = array('post_type' => 'shop_order', 'post_status' => $status ? $status : 'any', 'numberposts' => $perPage, 'paged' => $paged, 'orderby' => 'date', 'order' => 'DESC');

        if ($type == "my" && $userId) {
            $args["author"] = $userId;
        }

        if ($from) {
            $args['date_query'] = array('after' => $from, 'inclusive' => true);

            if ($to) {
                $args['date_query']["before"] = $to;
            }
        } else if ($to) {
            $args['date_query'] = array('before' => $to, 'inclusive' => true);
        }

        $orders = get_posts($args);

        if (count($orders)) {
            if (HPOS::getStatus()) {
                $orders = HPOS::ordersPrepare($orders);
            } else {
                $orders = (new Results())->ordersPrepare($orders);
            }
        }

        $groups = array();

        foreach ($orders as $order) {
            $date = $order["order_date"]->format("Y-m-d");

            if (!isset($groups[$date])) $groups[$date] = array();

            if (!in_array($order["ID"], $groups[$date])) $groups[$date][] = $order["ID"];
        }








        return rest_ensure_response(array(
            "orders" => $orders,
            "groups" => $groups,
        ));
    }

    private function importCodesAction($request, $productId, $autoAction, $autoActionField)
    {
        $data = (new Post())->find($productId, array("products" => array("ID" => true)), true, false, null, "product", array());
        $products = (new Results())->productsPrepare($data["posts"], array("useAction" => false));

        if (!$products || count($products) > 1) return;

        $product = $products[0];

        switch ($autoAction) {
            case $this->postAutoAction["AUTO_INCREASING"]: { 
                    if ($autoActionField == "_stock") {
                        if (isset($product["product_manage_stock"]) && isset($product["post_parent"]) && $product["product_manage_stock"] === "parent" && $product["post_parent"]) {
                            if ($autoActionField == "_stock") {
                                $this->setManageStock($product["post_parent"]);
                            }
                            $this->productUpdateQuantityPlus($request, $product["post_parent"]);
                        } else {
                            if ($autoActionField == "_stock") {
                                $this->setManageStock($product["ID"]);
                            }
                            $this->productUpdateQuantityPlus($request, $product["ID"]);
                        }
                    }
                    else {
                        $value = get_post_meta($product["ID"], $autoActionField, true);
                        $value = $value && is_numeric($value) ? $value : 0;
                        update_post_meta($product["ID"], $autoActionField, $value + 1);
                        LogActions::add($product["ID"], LogActions::$actions["quantity_plus"], $autoActionField, $value + 1, $value, "product", $request);
                    }
                    break;
                }
            case $this->postAutoAction["AUTO_DECREASING"]: { 
                    if ($autoActionField == "_stock") {
                        if (isset($product["product_manage_stock"]) && isset($product["post_parent"]) && $product["product_manage_stock"] === "parent" && $product["post_parent"]) {
                            if ($autoActionField == "_stock") {
                                $this->setManageStock($product["post_parent"]);
                            }
                            $this->productUpdateQuantityMinus($request, $product["post_parent"]);
                        } else {
                            if ($autoActionField == "_stock") {
                                $this->setManageStock($product["ID"]);
                            }
                            $this->productUpdateQuantityMinus($request, $product["ID"]);
                        }
                    }
                    else {
                        $settings = new Settings();
                        $allowNegativeStock = $settings->getSettings("allowNegativeStock");
                        $allowNegativeStock = $allowNegativeStock ? $allowNegativeStock->value : "";

                        $value = get_post_meta($product["ID"], $autoActionField, true);
                        $value = $value && is_numeric($value) ? $value : 0;

                        if ($value > 0 || $allowNegativeStock == "on") {
                            update_post_meta($product["ID"], $autoActionField, $value - 1);
                            LogActions::add($product["ID"], LogActions::$actions["quantity_minus"], $autoActionField, $value - 1, $value, "product", $request);
                        }
                    }
                    break;
                }
        }
    }

    public function productIndexation($id, $trigger)
    {
        try {
            Database::updatePost($id, array(), null, null, $trigger);
        } catch (\Throwable $th) {
        }
    }

    public function updateOrderMeta(WP_REST_Request $request)
    {
        $id = $request->get_param("id");
        $key = $request->get_param("key");
        $value = $request->get_param("value");

        if ($id && $key) {
            update_post_meta($id, $key, $value);
        }

        $orderRequest = new WP_REST_Request("", "");
        $orderRequest->set_param("query", $id);

        return $this->orderSearch($orderRequest, false, true);
    }
}
