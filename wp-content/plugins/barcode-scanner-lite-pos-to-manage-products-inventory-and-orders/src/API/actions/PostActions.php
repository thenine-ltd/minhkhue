<?php

namespace UkrSolution\BarcodeScanner\API\actions;

use UkrSolution\BarcodeScanner\API\classes\Post;
use UkrSolution\BarcodeScanner\API\classes\Results;
use UkrSolution\BarcodeScanner\API\classes\SearchFilter;
use UkrSolution\BarcodeScanner\API\RequestHelper;
use UkrSolution\BarcodeScanner\Database;
use UkrSolution\BarcodeScanner\features\debug\Debug;
use UkrSolution\BarcodeScanner\features\settings\Settings;
use WP_REST_Request;

class PostActions
{
    public function postSearch(WP_REST_Request $request)
    {
        $query = RequestHelper::getQuery($request, "post_search");
        $withVariation = $request->get_param("withVariation");
        $filterExcludes = $request->get_param("filterExcludes");
        $filter = SearchFilter::get();

        $result = array(
            "posts" => null,
            "findByTitle" => null,
        );

        $data = (new Post())->find($query, $filter, false, false, null, "product", $filterExcludes);
        $posts = (new Results())->postsPrepare($data["posts"], $withVariation);

        if ($posts) {
            $result['posts'] = $posts;
            $result['findByTitle'] = $data["findByTitle"];
        }

        if (Debug::$status) {
            $result['debug'] = Debug::getResult();
        }

        return rest_ensure_response($result);
    }

    public function checkCustomFields(WP_REST_Request $request)
    {
        global $wpdb;
        global $wp_version;

        $fields = $request->get_param("fields");
        $filter = $request->get_param("filter");

        $settings = new Settings();
        $key = $settings->getField("license", "key", "");
        $url = "https://www.ukrsolution.com/ExtensionsSupport/Support?extension=24&version=1.5.1&pversion=" . $wp_version . "&d=" . base64_encode($key); // 1.5.1

        if (!$fields) {
            return rest_ensure_response(array("error" => "Field is empty"));
        }

        foreach ($fields as $value) {
            $name = trim($value["field"]);

            if (!$name) {
                continue;
            }

            if ($value["type"] === "product" && isset($filter["products"]) && isset($filter["products"]["customStatus"]) && $filter["products"]["customStatus"]) {
                $productCustomField = $wpdb->get_row(
                    $wpdb->prepare("SELECT P.ID FROM {$wpdb->posts} AS P, {$wpdb->postmeta} AS PM WHERE PM.post_id = P.ID AND P.post_type IN('product','product_variation') AND PM.meta_key = %s LIMIT 1;", $name)
                );

                if (!$productCustomField && !key_exists($name, Database::$postsFields)) {
                    return rest_ensure_response(array("error" => __("Product's custom field \"{$name}\" not found. Please make sure you entered a correct database value or <a href='{$url}' target='_blank'>contact us</a> for help.", "us-barcode-scanner")));
                }
            } else if ($value["type"] === "order" && isset($filter["orders"]) && isset($filter["orders"]["customStatus"]) && $filter["orders"]["customStatus"]) {
                $orderCustomField = $wpdb->get_row(
                    $wpdb->prepare("SELECT P.ID FROM {$wpdb->posts} AS P, {$wpdb->postmeta} AS PM WHERE PM.post_id = P.ID AND P.post_type IN('shop_order') AND PM.meta_key = %s LIMIT 1;", $name)
                );

                if (!$orderCustomField && !key_exists($name, Database::$postsFields)) {
                    return rest_ensure_response(array("error" => __("Order's custom field \"{$name}\" not found. Please make sure you entered a correct database value or <a href='{$url}' target='_blank'>contact us</a> for help.", "us-barcode-scanner")));
                }
            }
        }

        return rest_ensure_response(array("success" => 1));
    }

    public function checkOtherPrices(WP_REST_Request $request)
    {
        global $wpdb;
        global $wp_version;

        $inputs = $request->get_param("inputs");

        $field = "";

        if (isset($inputs["price_1_field"])) {
            $field = trim($inputs["price_1_field"]);
        }

        if (isset($inputs["price_2_field"])) {
            $field = trim($inputs["price_2_field"]);
        }

        if (isset($inputs["price_3_field"])) {
            $field = trim($inputs["price_3_field"]);
        }

        if (!$field) {
            return rest_ensure_response(array("error" => "Field is empty"));
        }

        $productsCustomField = $wpdb->get_row(
            $wpdb->prepare("SELECT COUNT(P.ID) AS total FROM {$wpdb->posts} AS P, {$wpdb->postmeta} AS PM WHERE PM.post_id = P.ID AND P.post_type IN('product','product_variation') AND PM.meta_key = %s;", $field)
        );
        $total = $productsCustomField->total;

        if ($total) {
            return rest_ensure_response(array("success" => sprintf("Custom field found for %s product%s.", $total, $total > 1 ? "s" : "")));
        } else {
            return rest_ensure_response(array("error" => "Field not found"));
        }
    }

    public function checkFieldName(WP_REST_Request $request)
    {
        global $wpdb;
        global $wp_version;

        $inputs = $request->get_param("inputs");

        $field = "";

        if ($inputs) {
            foreach ($inputs as $value) {
                if (is_array($value) && isset($value["field_name"])) {
                    $field = trim($value["field_name"]);
                }
            }
        }

        if (!$field) {
            return rest_ensure_response(array("error" => "Field is empty"));
        }

        $productsCustomField = $wpdb->get_row(
            $wpdb->prepare("SELECT COUNT(P.ID) AS total FROM {$wpdb->posts} AS P, {$wpdb->postmeta} AS PM WHERE PM.post_id = P.ID AND P.post_type IN('product','product_variation') AND PM.meta_key = %s;", $field)
        );
        $total = $productsCustomField->total;

        if ($total) {
            return rest_ensure_response(array("success" => sprintf("Custom field found for %s product%s.", $total, $total > 1 ? "s" : "")));
        } else {
            return rest_ensure_response(array("error" => "Field not found"));
        }
    }
}
