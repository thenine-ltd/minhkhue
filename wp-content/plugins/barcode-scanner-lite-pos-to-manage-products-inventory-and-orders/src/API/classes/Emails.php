<?php

namespace UkrSolution\BarcodeScanner\API\classes;

use UkrSolution\BarcodeScanner\features\settings\Settings;

class Emails
{
    static public function sendLowStock($productId, $qty, $productName, $thershold)
    {
        try {

            $settings = new Settings();
            $notifyUsersStock = $settings->getSettings("notifyUsersStock");
            $usersIds = $notifyUsersStock === null ? "" : $notifyUsersStock->value;
            $usersIds = $usersIds ? explode(",", $usersIds) : array();

            if ($usersIds) {
                $subject = __("Low stock inventory", "us-barcode-scanner") . "({$qty}) - {$productName}";

                $linkFront = '<a href="' . get_permalink($productId) . '">' . $productName . '</a>';
                $message = sprintf(__('Product "%s" has reached', "us-barcode-scanner"), $linkFront);

                $urlBack = get_edit_post_link($productId);
                $message .= ' <a href="' . $urlBack . '">' . sprintf(__('stock quantity %s', "us-barcode-scanner"), $qty) . '</a>';

                $message .= sprintf(__('<br/><i>The stock threshold for this product is %s</i>', "us-barcode-scanner"), $thershold);

                foreach ($usersIds as $userId) {
                    if ($userId) {
                        $user = get_userdata($userId);
                        if ($user) {

                            $headers = array('Content-Type: text/html; charset=UTF-8');
                            wp_mail($user->user_email,  $subject, $message, $headers);
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
        }
    }
}
