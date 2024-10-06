<?php

namespace UkrSolution\BarcodeScanner\features\history;

use UkrSolution\BarcodeScanner\API\actions\HPOS;
use UkrSolution\BarcodeScanner\API\classes\Results;
use UkrSolution\BarcodeScanner\Database;
use UkrSolution\BarcodeScanner\features\Debug\Debug;

class History
{
    public static function add($postId)
    {
        global $wpdb;

        try {
            $uid = \get_current_user_id();

            if (!$uid) return;

            $table = $wpdb->prefix . Database::$history;

            $record = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE `user_id` = %d AND `post_id` = %d;", $uid, $postId));

            if ($record) {
                $wpdb->update($table, array('counter' => $record->counter + 1, 'updated' => date("Y-m-d H:i:s")), array('id' => $record->id));
            } else {
                $wpdb->insert($table, array('user_id' => $uid, 'post_id' => $postId, 'updated' => date("Y-m-d H:i:s")), array('%d', '%s', '%s'));
            }
        } catch (\Throwable $th) {
            Debug::addPoint($th->getMessage());
        }
    }

    public static function getByUser($userId = null)
    {
        global $wpdb;

        $list = array();

        try {
            $uid = $userId ? $userId : \get_current_user_id();

            if (!$uid) return array();

            $table = $wpdb->prefix . Database::$history;
            $products = $wpdb->get_results($wpdb->prepare(
                "SELECT H.* FROM {$table} AS H, {$wpdb->posts} AS P WHERE P.ID = H.post_id AND P.post_type IN('product','product_variation') AND H.user_id = %d ORDER BY H.updated DESC LIMIT 15;",
                $uid
            ));

            if (HPOS::getStatus()) {
                $orders = $wpdb->get_results($wpdb->prepare(
                    "SELECT H.* FROM {$table} AS H, {$wpdb->prefix}wc_orders AS O WHERE O.id = H.post_id AND H.user_id = %d ORDER BY H.updated DESC LIMIT 15;",
                    $uid
                ));
            } else {
                $orders = $wpdb->get_results($wpdb->prepare(
                    "SELECT H.* FROM {$table} AS H, {$wpdb->posts} AS P WHERE P.ID = H.post_id AND P.post_type = 'shop_order' AND H.user_id = %d ORDER BY H.updated DESC LIMIT 15;",
                    $uid
                ));
            }

            foreach ($products as $value) {
                if (!$value->post_id) continue;

                $post = \get_post($value->post_id);
                if (!$post) continue;

                if ($post->post_type == "product" || $post->post_type == "product_variation") {
                    $value = (new Results())->formatProduct($post, array(), false);
                    $list[] = array(
                        "ID" => $post->ID,
                        "post_type" => $post->post_type,
                        "post_title" => base64_encode($post->post_title),
                        "product_sku" => isset($value['product_sku']) ? $value['product_sku'] : "",
                        "translation" => array("language_code" =>  isset($value['translation']) && isset($value['translation']->language_code) ? $value['translation']->language_code : ""),
                        "product_thumbnail_url" => isset($value['product_thumbnail_url']) ? $value['product_thumbnail_url'] : "",
                        "_source" => "history"
                    );
                }
                $post = null;
            }
            unset($products);


            foreach ($orders as $value) {
                if (!$value->post_id) continue;

                if (HPOS::getStatus()) {
                    $order = new \WC_Order($value->post_id);
                    $record = $wpdb->get_row($wpdb->prepare("SELECT O.* FROM {$wpdb->prefix}wc_orders AS O WHERE O.id = %d", $value->post_id));
                    if ($order && $record) {
                        $orderData = HPOS::formatOrder($record);
                        $list[] = array(
                            "ID" => $order->get_id(),
                            "post_type" => $order->get_type(),
                            "post_title" => base64_encode($order->get_title()),
                            "date_format" => isset($orderData["date_format"]) ? $orderData["date_format"] : "",
                            "customer_name" => isset($orderData['customer_name']) ? $orderData['customer_name'] : "",
                            "customer_country" => isset($orderData['customer_country']) ? $orderData['customer_country'] : "",
                            "order_total_c" => isset($orderData['order_total_c']) ? $orderData['order_total_c'] : "",
                            "preview_date_format" => isset($orderData['preview_date_format']) ? $orderData['preview_date_format'] : "",
                            "user" => array("avatar" =>  isset($orderData['user']) && isset($orderData['user']->avatar) ? $orderData['user']->avatar : ""),
                            "_source" => "history"
                        );
                    }
                } else {
                    $post = \get_post($value->post_id);

                    if ($post && $post->post_type == "shop_order") {
                        $value = (new Results())->formatOrder($post);
                        $list[] = array(
                            "ID" => $post->ID,
                            "post_type" => $post->post_type,
                            "post_title" => base64_encode($post->post_title),
                            "date_format" => isset($value["date_format"]) ? $value["date_format"] : "",
                            "customer_name" => isset($value['customer_name']) ? $value['customer_name'] : "",
                            "customer_country" => isset($value['customer_country']) ? $value['customer_country'] : "",
                            "order_total_c" => isset($value['order_total_c']) ? $value['order_total_c'] : "",
                            "preview_date_format" => isset($value['preview_date_format']) ? $value['preview_date_format'] : "",
                            "user" => array("avatar" =>  isset($value['user']) && isset($value['user']->avatar) ? $value['user']->avatar : ""),
                            "_source" => "history"
                        );
                    }
                }

                $post = null;
            }
            unset($orders);
        } catch (\Throwable $th) {
            Debug::addPoint($th->getMessage());
        }

        return $list;
    }
}
