<?php

namespace UkrSolution\BarcodeScanner\API\actions;

use UkrSolution\BarcodeScanner\API\classes\Post;
use UkrSolution\BarcodeScanner\API\classes\Results;
use UkrSolution\BarcodeScanner\API\classes\WPML;
use UkrSolution\BarcodeScanner\Database;
use UkrSolution\BarcodeScanner\features\Debug\Debug;
use UkrSolution\BarcodeScanner\features\interfaceData\InterfaceData;
use UkrSolution\BarcodeScanner\features\settings\Settings;

class HPOS
{
    private static $filter_search_query = "scanner_search_query";
    private static $filter_get_after = "barcode_scanner_%field_get_after";
    private static $limit = 20;
    private static $onlyById = false;
    private static $filterExcludes = array();

    public static function getStatus()
    {
        try {
            return \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() == true;
        } catch (\Throwable $th) {
            return false;
        }

        return false;
    }

    public static function findOrders($query, $filter, $onlyById, $autoFill, $filterExcludes)
    {
        global $wpdb;

        $settings = new Settings();

        $field = $settings->getSettings("searchResultsLimit");
        $searchResultsLimit = $field === null ? 20 : (int)$field->value;
        $searchResultsLimit = $searchResultsLimit ? $searchResultsLimit : 20;

        $query = apply_filters(self::$filter_search_query, trim($query));

        if ($searchResultsLimit != self::$limit && $searchResultsLimit > 0) {
            self::$limit = $searchResultsLimit;
        }

        self::$onlyById = $onlyById;
        self::$filterExcludes = $filterExcludes;

        $excludeStatuses = "";
        $orderStatusesField = $settings->getSettings("orderStatuses");
        $excludeStatuses = $orderStatusesField === null ? "wc-checkout-draft,trash" : $orderStatusesField->value;

        $filterWithoutTitle = $filter;
        $findByTitle = false;

        if (self::isUrl($query)) {
            $query = urldecode($query);
        }

        $query = trim($query);
        $query = str_replace("'", "\'", $query);

        $queryFromUrl = self::checkUrlInQuery($query, $filter);

        if ($query != $queryFromUrl && is_numeric($queryFromUrl)) {
            if (isset($filterWithoutTitle["products"]) && $filterWithoutTitle["products"]["prod_link"]) {
                $filterWithoutTitle["products"]["ID"] = 1;
            }

            $query = $queryFromUrl;
        }

        $sql = self::sqlBuilder($query, $filterWithoutTitle, $excludeStatuses);

        $orders = $wpdb->get_results($sql);

        Debug::addPoint("1. sql");
        Debug::addPoint($sql);
        Debug::addPoint("1. orders = " . count($orders));

        if (count($orders)) {

            return array(
                "posts" => $orders,
                "filter" => (new Post())->getFilterParams($filterWithoutTitle),
                "findByTitle" => $findByTitle,
                "query" => $query
            );
        }

        return array(
            "posts" => null,
            "findByTitle" => null,
            "query" => $query
        );
    }

    private static function isUrl($query)
    {
        try {
            return preg_match("/.*?post=([\d]+).*?/", $query, $m) || (function_exists("wp_http_validate_url") && \wp_http_validate_url($query)) ? true : false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    private static function checkUrlInQuery($query, $filter)
    {
        try {
            if (!$filter || !isset($filter["products"])) return $query;

            if (!isset($filter["products"]["prod_link"]) || $filter["products"]["prod_link"] != "1") return $query;

            $id = self::isUrl($query) ? url_to_postid($query) : "";

            if ($id) {
                return $id;
            } else if (preg_match("/.*?post=([\d]+).*?/", $query, $m)) {
                if (count($m) == 2 && is_numeric($m[1])) {
                    return $m[1];
                } else {
                    return $query;
                }
            } else {
                return $query;
            }
        } catch (\Throwable $th) {
            return $query;
        }
    }

    private static function sqlBuilder($query, $filter, $excludeStatuses)
    {
        global $wpdb;

        $queryLike = str_replace(" ", "%", $query);

        $posts = $wpdb->prefix . Database::$posts;
        $columns = $wpdb->prefix . Database::$columns;
        $metaFieldPrefix = Database::$postMetaFieldPrefix;

        $tableColumns = $wpdb->get_results("SELECT * FROM {$columns} AS C;");

        $statuses = explode(",", $excludeStatuses);
        if (!count($statuses)) $statuses[] = "trash";
        $statuses = implode("','", $statuses);

        $selectColumns = array();
        $params = (new Post())->getFilterParams($filter);
        $isNumeric = (preg_match('/^[0-9]{0,20}$/', trim($query), $m)) ? true : false;


        $sql = "SELECT O.* %SELECT_COLUMNS% FROM {$posts} AS P, {$wpdb->prefix}wc_orders AS O 
                WHERE O.id = P.post_id AND ( P.post_status NOT IN('{$statuses}') OR P.post_status IS NULL ) 
                    AND ( P.post_parent = 0 OR P.post_parent IS NULL OR P.post_parent_status IS NUll OR P.post_parent_status NOT IN('{$statuses}')  ) 
                    AND ( ";

        $filterSql = "";

        if ($params["postID"] && $isNumeric) {
            if ($params["post_types"]) {
                $types = "'" . implode("', '", $params["post_types"]) . "'";

                if ($params["postID"] == 2) {
                    $filterSql .= " (P.post_id LIKE '%{$query}%' AND P.post_type IN({$types})) ";
                } else {
                    $filterSql .= " (P.post_id = '{$query}' AND P.post_type IN({$types})) ";
                }
            } else {
                $filterSql .= " P.post_id = '{$query}' ";
            }
        }

        if ($params["orderCF"] && $params["statusOrderCF"] != 0) {
            $types = '"shop_order"';
            $field = trim($params["orderCF"]);

            foreach ($tableColumns as $value) {
                if ($value->name === $field) {
                    $field = $value->column;
                    $filterSql .= (strlen($filterSql)) ? " OR " : "";
                    $_sql = self::sqlComp($params["statusOrderCF"], $types, $query, "P.{$field}");

                    $filterSql .= $_sql;
                    $selectColumns[] = "{$_sql} AS '{$field}'";
                    break;
                }
            }

            if (key_exists($field, Database::$postsFields)) {
                $filterSql .= (strlen($filterSql)) ? " OR " : "";
                $_sql = Database::$postsFields[$field] === "like" || $params["statusProductCF"] == 2 ? " ( P.post_type IN( {$types} ) AND  P.`{$field}` LIKE '%{$query}%' ) " :  " ( P.post_type IN( {$types} ) AND  P.`{$field}` = '{$query}' ) ";

                $filterSql .= $_sql;
                $selectColumns[] = "{$_sql} AS '{$field}'";
            }
        }

        if ($params["_order_number"] != 0) {
            $filterSql .= (strlen($filterSql)) ? " OR " : "";
            $types = '"shop_order"';
            $_sql = self::sqlComp($params["_order_number"], $types, $query, "P.{$metaFieldPrefix}_order_number");
            $filterSql .= $_sql;
            $selectColumns[] = "{$_sql} AS '_order_number'";
        }

        if ($params["_billing_address_index"] != 0) {
            $filterSql .= (strlen($filterSql)) ? " OR " : "";
            $types = '"shop_order"';
            $_sql = self::sqlComp($params["_billing_address_index"], $types, $query, "P.{$metaFieldPrefix}_billing_address_index");
            $filterSql .= $_sql;
            $selectColumns[] = "{$_sql} AS '_billing_address_index'";
        }

        if ($params["_shipping_address_index"] != 0) {
            $filterSql .= (strlen($filterSql)) ? " OR " : "";
            $types = '"shop_order"';
            $_sql = self::sqlComp($params["_shipping_address_index"], $types, $query, "P.{$metaFieldPrefix}_shipping_address_index");
            $filterSql .= $_sql;
            $selectColumns[] = "{$_sql} AS '_shipping_address_index'";
        }

        if ($params["ywot_tracking_code"] != 0) {
            $filterSql .= (strlen($filterSql)) ? " OR " : "";
            $types = '"shop_order"';
            $_sql = self::sqlComp($params["ywot_tracking_code"], $types, $query, "P.{$metaFieldPrefix}ywot_tracking_code");
            $filterSql .= $_sql;
            $selectColumns[] = "{$_sql} AS 'ywot_tracking_code'";
        }
        if ($params["_wc_shipment_tracking_items"] != 0) {
            $filterSql .= (strlen($filterSql)) ? " OR " : "";
            $types = '"shop_order"';
            $_sql = self::sqlComp($params["_wc_shipment_tracking_items"], $types, $query, "P.{$metaFieldPrefix}_wc_shipment_tracking_items");
            $filterSql .= $_sql;
            $selectColumns[] = "{$_sql} AS '_wc_shipment_tracking_items'";
        }
        if ($params["_aftership_tracking_items"] != 0) {
            $filterSql .= (strlen($filterSql)) ? " OR " : "";
            $types = '"shop_order"';
            $_sql = self::sqlComp($params["_aftership_tracking_items"], $types, $query, "P.{$metaFieldPrefix}_aftership_tracking_items");
            $filterSql .= $_sql;
            $selectColumns[] = "{$_sql} AS '_aftership_tracking_items'";
        }

        $limit = self::$limit;
        $sql .= ($filterSql) ? $filterSql : " 1 != 1 ";
        $sql .= " ) GROUP BY P.ID LIMIT {$limit} ";

        if ($selectColumns) {
            $sql = str_replace("%SELECT_COLUMNS%", ", " . implode(", ", $selectColumns), $sql);
        } else {
            $sql = str_replace("%SELECT_COLUMNS%", " ", $sql);
        }

        return $sql;
    }

    private static function sqlComp($status, $types, $query, $field)
    {
        $like = str_replace("\'", "%", $query);

        return $status == 1
            ? " ( P.post_type IN( {$types} ) AND {$field} = '{$query}' ) "
            : " ( P.post_type IN( {$types} ) AND {$field} LIKE '%{$like}%' ) ";
    }

    public static function ordersPrepare($posts, $additionalFields = array(), $autoFill = false)
    {
        $orders = array();

        if (!$posts) return $orders;

        if (count($posts) > 1) {
            foreach ($posts as $post) {
                $order = self::formatOrder($post, $additionalFields);

                if ($order) $orders[] = $order;
            }
        } elseif (count($posts)) {
            $order = self::formatOrder($posts[0], $additionalFields);

            if ($order) $orders[] = $order;
        }

        return $orders;
    }

    public static function formatOrder($post, $additionalFields = array())
    {
        $order = new \WC_Order($post->id);

        if ($order) {
            $reflect = new \ReflectionClass($order);

            if ($reflect->getShortName() === "OrderRefund") {
                return null;
            }

            return self::assignOrderProps($post, $order, $additionalFields);
        }

        return null;
    }

    private static function assignOrderProps($post, $order, $additionalFields = array())
    {
        global $wpdb;

        $products = array();
        $items = $order->get_items("line_item");
        $currencySymbol = get_woocommerce_currency_symbol(get_woocommerce_currency());
        $isPricesIncludeTax = \wc_prices_include_tax();

        $order_subtotal_tax = 0;

        $shipping_class_names = \WC()->shipping->get_shipping_method_class_names();

        $order_shipping = 0;
        $order_shipping_tax = 0;
        $order_shipping_name = "";
        $order_shipping_title = "";

        foreach ($order->get_items("shipping") as $value) {
            $order_shipping += $value->get_total();
            $order_shipping_tax += $value->get_total_tax();
            $order_shipping_name = $value->get_name();
            $order_shipping_title = $value->get_method_title();
            $method_id = $value->get_method_id();
            $instance_id = $value->get_instance_id();


            try {
                if ($shipping_class_names && isset($shipping_class_names[$method_id])) {
                    $method_instance = new $shipping_class_names[$method_id]($instance_id);

                    $order_shipping_title = $method_instance->method_title;
                }
            } catch (\Throwable $th) {
            }
        }

        $order_payment = $order->get_payment_method();
        $order_payment_title = $order->get_payment_method_title();

        $additionalTaxes = array();

        foreach ($order->get_items("fee") as $value) {
            $additionalTaxes[] = array(
                "label" => $value->get_name(),
                "value" => $value->get_total(),
                "value_c" => strip_tags(wc_price($value->get_total())),
                "tax" => $value->get_total_tax(),
                "tax_c" => strip_tags(wc_price($value->get_total_tax())),
                "plugin" => "",
            );
        }

        foreach ($items as $item) {
            $variationId = $item->get_variation_id();
            $id = $variationId;

            if (!$id) {
                $id = $item->get_product_id();
            }
            $_post = get_post($id);

            if (!$_post) {
                $_post = (object)array("ID" => "", "post_parent" => "", "post_type" => "");
            }

            $product_thumbnail_url = get_the_post_thumbnail_url($_post->ID, 'medium');

            if (!$product_thumbnail_url && $_post->post_parent) {
                $product_thumbnail_url = get_the_post_thumbnail_url($_post->post_parent, 'medium');
            }

            $editId = $variationId && $_post->post_parent ? $_post->post_parent : $_post->ID;

            $args = array("currency" => " ", "thousand_separator" => "", "decimal_separator" => ".");

            $usbs_check_product_scanned = \wc_get_order_item_meta($item->get_id(), 'usbs_check_product_scanned', true);
            $usbs_check_product_scanned = $usbs_check_product_scanned == "" ? 0 : $usbs_check_product_scanned;

            $logRecord = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}barcode_scanner_logs AS L WHERE L.post_id = '{$item->get_id()}' AND L.field = 'usbs_check_product' AND L.action = 'update_order_item_meta' ORDER BY L.id DESC LIMIT 1");
            $fulfillment_user_name = "";
            $fulfillment_user_email = "";

            if ($logRecord && $logRecord->user_id) {
                $user = get_user_by("ID", $logRecord->user_id);

                if ($user) {
                    $fulfillment_user_name = $user->display_name ? $user->display_name : $user->user_nicename;
                    $fulfillment_user_email = $user->user_email;
                }
            }

            $quantity = \wc_get_order_item_meta($item->get_id(), '_qty', true);

            $_productData = array(
                "ID" => $_post->ID,
                "variation_id" => $variationId,
                "post_type" => $_post->post_type,
                "name" => strip_tags($item->get_name()),
                "quantity" => (float)$quantity,
                "price_c" => strip_tags(wc_price($item->get_total() / $quantity)),
                "subtotal" => self::clearPrice($item->get_subtotal(), $args),
                "subtotal_c" => strip_tags(wc_price($item->get_subtotal())),
                "total" => self::clearPrice($item->get_total(), $args),
                "total_c" => strip_tags(wc_price($item->get_total())),
                "total_tax" => self::clearPrice($item->get_total_tax(), $args),
                "total_tax_c" => strip_tags(wc_price($item->get_total_tax())),
                "taxes" => strip_tags(wc_price($item->get_taxes())),
                "product_thumbnail_url" => $product_thumbnail_url,
                "postEditUrl" => admin_url('post.php?post=' . $editId) . '&action=edit',
                "locations" => (new Results())->getLocations($_post->ID),
                "item_id" => $item->get_id(),
                "usbs_check_product" => \wc_get_order_item_meta($item->get_id(), 'usbs_check_product', true),
                "usbs_check_product_scanned" => $usbs_check_product_scanned,
                "fulfillment_user_name" => $fulfillment_user_name,
                "fulfillment_user_email" => $fulfillment_user_email,
            );

            foreach (InterfaceData::getFields(true) as $value) {
                if (!$value['field_name']) continue;
                $filterName = str_replace("%field", $value['field_name'], self::$filter_get_after);
                $defaultValue = \get_post_meta($_productData["ID"], $value['field_name'], true);
                $filteredValue = apply_filters($filterName, $defaultValue, $value['field_name'], $_productData["ID"]);
                $filteredValue = $filteredValue;
                $_productData[$value['field_name']] = $filteredValue;
            }

            $products[] = $_productData;

            $_taxes = $item->get_taxes();

            if ($_taxes && isset($_taxes["total"]) && is_array($_taxes["total"])) {
                foreach ($_taxes["total"] as $_tax) {
                    if ($_tax) {
                        $order_subtotal_tax += $_tax;
                    }
                }
            }
        }

        $customerId = $order->get_customer_id();
        $user = $order->get_user();

        if ($customerId && $user) {
            $user = $user->data;
            $user->phone = get_user_meta($user->ID, 'billing_phone', true);
            $user->avatar = @get_avatar_url($customerId);
        }

        $wpFormat = get_option("date_format", "F j, Y") . " " . get_option("time_format", "g:i a");
        $orderDate = new \DateTime($order->get_date_created());

        $customerName = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
        $customerCountry = $order->get_billing_country();
        $previewDateFormat = $orderDate->format("M j, Y");

        $logRecord = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}barcode_scanner_logs AS L WHERE L.post_id = '{$order->get_id()}' AND L.action = 'update_order_fulfillment' AND L.value = '1' ORDER BY L.id DESC LIMIT 1");
        $fulfillment_user_name = "";
        $fulfillment_user_email = "";

        if ($logRecord && $logRecord->user_id) {
            $user = get_user_by("ID", $logRecord->user_id);

            if ($user) {
                $fulfillment_user_name = $user->display_name ? $user->display_name : $user->user_nicename;
                $fulfillment_user_email = $user->user_email;
            }
        }

        $settings = new Settings();

        $fulfillmentField = $settings->getSettings("orderFulFillmentField");
        $fulfillmentField = $fulfillmentField === null ? "" : $fulfillmentField->value;

        $props = array(
            "ID" => $order->get_id(),
            "post_type" => $post->type ? $post->type : "shop_order",
            "post_author" => "",
            "data" => array(
                "billing" => array(
                    'first_name' => $order->get_billing_first_name(),
                    'last_name' => $order->get_billing_last_name(),
                    'company' => $order->get_billing_company(),
                    'email' => $order->get_billing_email(),
                    'phone' => $order->get_billing_phone(),
                    'address_1' => $order->get_billing_address_1(),
                    'address_2' => $order->get_billing_address_2(),
                    'postcode' => $order->get_billing_postcode(),
                    'city' => $order->get_billing_city(),
                    'state' => $order->get_billing_state(),
                    'country' => $order->get_billing_country(),
                ),
                "shipping" => array(
                    'first_name' => $order->get_shipping_first_name(),
                    'last_name' => $order->get_shipping_last_name(),
                    'company' => $order->get_shipping_company(),
                    'address_1' => $order->get_shipping_address_1(),
                    'address_2' => $order->get_shipping_address_2(),
                    'postcode' => $order->get_shipping_postcode(),
                    'city' => $order->get_shipping_city(),
                    'state' => $order->get_shipping_state(),
                    'country' => $order->get_shipping_country(),
                ),
                "customer_note" => $order->get_customer_note(),
                "total_tax" => $order->get_total_tax(),
                "status" => $order->get_status(),
            ),
            "order_date" => $order->get_date_created(),
            "date_format" => $orderDate->format($wpFormat),
            "preview_date_format" => $previewDateFormat,
            "user" => $user,
            "order_tax" => $order->get_total_tax(),
            "order_tax_c" => strip_tags(wc_price($order->get_total_tax())),
            "order_subtotal" => $order->get_subtotal(),
            "order_subtotal_c" => strip_tags(wc_price($order->get_subtotal())),
            "order_subtotal_tax" => $order_subtotal_tax,
            "order_subtotal_tax_c" => strip_tags(wc_price($order_subtotal_tax)),
            "order_shipping" => $order_shipping,
            "order_shipping_c" => strip_tags(wc_price($order_shipping)),
            "order_shipping_tax" => $order_shipping_tax,
            "order_shipping_tax_c" => strip_tags(wc_price($order_shipping_tax)),
            "order_shipping_name" => $order_shipping_name,
            "order_shipping_title" => $order_shipping_title,
            "order_payment" => $order_payment,
            "order_payment_title" => $order_payment_title,
            "additionalTaxes" => $additionalTaxes,
            "order_total" => $order->get_total(),
            "order_total_c" => strip_tags(wc_price($order->get_total())),
            "customer_id" => $customerId,
            "customer_name" => $customerName,
            "customer_country" => $customerCountry,
            "products" => $products,
            "currencySymbol" => $currencySymbol,
            "statuses" => wc_get_order_statuses(),
            "postEditUrl" => admin_url('post.php?post=' . $post->id) . '&action=edit',
            "postPayUrl" => $order->get_checkout_payment_url(),
            "updated" => time(),
            "foundCounter" => $order->get_meta("usbs_found_counter", true),
            "fulfillment_user_name" => $fulfillment_user_name,
            "fulfillment_user_email" => $fulfillment_user_email,
            "discount" => $order->get_discount_total() ?  strip_tags($order->get_discount_to_display()) : "",
            "coupons" => $order->get_coupon_codes(),
        );


        $props["_order_number"] = $order->get_meta("_order_number", true);
        $props["_billing_address_index"] = str_replace("<br/>", ", ", $order->get_formatted_billing_address());
        $props["_shipping_address_index"] = str_replace("<br/>", ", ", $order->get_formatted_shipping_address());
        $props["ywot_tracking_code"] = $order->get_meta("ywot_tracking_code", true);

        $wcShipmentTrackingItems = $order->get_meta("_wc_shipment_tracking_items", true);
        $_wc_shipment_tracking_items = "";
        if ($wcShipmentTrackingItems && is_array($wcShipmentTrackingItems)) {
            foreach ($wcShipmentTrackingItems as $value) {
                if (isset($value["tracking_number"])) $_wc_shipment_tracking_items .= " " . $value["tracking_number"];
            }
        }
        $props["_wc_shipment_tracking_items"] = trim($_wc_shipment_tracking_items);

        $aftershipTrackingItems = $order->get_meta("_aftership_tracking_items", true);
        $_aftership_tracking_items = "";
        if ($aftershipTrackingItems && is_array($aftershipTrackingItems)) {
            foreach ($aftershipTrackingItems as $value) {
                if (isset($value["tracking_number"])) $_aftership_tracking_items .= " " . $value["tracking_number"];
            }
        }
        $props["_aftership_tracking_items"] = trim($_aftership_tracking_items);

        foreach ($additionalFields as $key => $value) {
            $props[$key] = $value;
        }

        return $props;
    }

    private static function clearPrice($price, $args = array())
    {
        $price = trim(strip_tags(wc_price($price, $args)));
        $price = str_replace("&nbsp;", "", $price);

        return $price;
    }
}
