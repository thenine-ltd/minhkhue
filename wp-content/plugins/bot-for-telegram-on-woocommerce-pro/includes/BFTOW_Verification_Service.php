<?php

class BFTOW_Verification_Service
{
    private $isVerified = false;

    private $serverUrl = 'https://analythicsthemes.com';

    public function verify()
    {
        $result = wp_remote_post($this->serverUrl . '/domain/verify?domain=' .site_url());

        if ($result instanceof WP_Error) {
            return;
        }

        $body = json_decode($result['body'], true);

        $this->isVerified = !empty($body['verified']);

        $this->isVerified ? $this->activate() : $this->deactivate();
    }

    public function isVerified()
    {
        return $this->isVerified;
    }

    public function deactivate()
    {
         set_transient('bftow_pro_status', BFTOW_Plugin_Statuses::NOT_ACTIVATED, DAY_IN_SECONDS);
    }

    public function activate()
    {
        set_transient('bftow_pro_status', BFTOW_Plugin_Statuses::ACTIVATED , DAY_IN_SECONDS);
    }
}
