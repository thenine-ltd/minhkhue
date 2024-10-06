<?php

namespace UkrSolution\BarcodeScanner\API\classes;

class Users
{
    public static function getUserId($request)
    {
        $userId = get_current_user_id();
        $token = $request->get_param("token");

        if (!$userId && $token) {
            try {
                if (preg_match("/^([0-9]+)/", @base64_decode($token), $m)) {
                    if ($m && count($m) > 0 && is_numeric($m[0])) {
                        $userId = $m[0];
                    }
                } else {
                    $users = get_users(array('meta_key' => 'barcode_scanner_app_otp', 'meta_value' => $token));
                    $userId = count($users) > 0 ? $users[0]->ID : $userId;
                }
            } catch (\Throwable $th) {
            }
        }

        return $userId;
    }
}
