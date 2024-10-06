<?php

namespace UkrSolution\BarcodeScanner\API\routes;

use UkrSolution\BarcodeScanner\API\actions\PostActions;
use UkrSolution\BarcodeScanner\API\Routes;

class Post extends Routes
{
    public function __construct()
    {
        $actions = new PostActions();

        register_rest_route('scanner/v1', '/post/search/(?P<query>.+)/(?P<withVariation>.+)', array(
            'methods' => 'POST',
            'permission_callback' => array($this, 'permissionCallback'),
            'args' => array(
                'query' => $this->getArgSearchQuery(),
                'withVariation' => array(
                    'default' => 1,
                    'required' => false,
                    'sanitize_callback' => function ($param) {
                        return (int) $param;
                    },
                )
            ),
            'callback' => array($actions, 'postSearch'),
        ));

        register_rest_route('scanner/v1', '/post/check-custom-fields', array(
            'methods' => 'POST',
            'permission_callback' => array($this, 'permissionCallback'),
            'args' => array(),
            'callback' => array($actions, 'checkCustomFields'),
        ));
    }
}
