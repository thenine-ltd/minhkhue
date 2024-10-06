<?php

add_action('rest_api_init', 'plugin_de_activation_rest_api_route');

function plugin_de_activation_rest_api_route() {
    register_rest_route(
        'license/v1',
        'de-activate',
        array(
            'methods' => 'post',
            'permission_callback' => '__return_true',
            'callback' => 'deactivate_plugin' // this can any function name,
        )
    );
}

function deactivate_plugin(){
    $verifyService = new BFTOW_Verification_Service();

    $verifyService->deactivate();

    return rest_ensure_response([
        'message' => 'deactivated'
    ]);
}
