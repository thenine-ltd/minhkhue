<?php
/**
 * Plugin Name: Bot for Telegram on WooCommerce PRO
 * Description: Bot for Telegram on WooCommerce PRO is a plugin that allows you to create a telegram online store based on your website with WooCommerce.
 * Plugin URI:  https://wp-guruteam.com/woocommerce-telegram/
 * Version:     1.1.2
 * Author:      guru-team
 * Author URI:  https://wp-guruteam.com/
 * Text Domain: bot-for-telegram-on-woocommerce-pro
 */
if (!defined('ABSPATH')) exit;

define('BFTOW_PRO_PLUGIN_VERSION', '1.0.6');
define('BFTOW_PRO_DIR', dirname(__FILE__));
define('BFTOW_PRO_URL', plugins_url('/', __FILE__));
define('BFTOW_DB_V', 1.9);

require_once BFTOW_PRO_DIR . '/activation.php';
require_once BFTOW_PRO_DIR . '/rest-api.php';

if (!is_textdomain_loaded('bot-for-telegram-on-woocommerce-pro')) {
    load_plugin_textdomain(
        'bot-for-telegram-on-woocommerce-pro',
        false,
        'bot-for-telegram-on-woocommerce-pro/languages'
    );
}

add_action('plugins_loaded', function() use ($status) {
    if ($status->isNotActivated()) {
        return;
    }

    if (defined('BFTOW_DIR')) {
        require_once BFTOW_PRO_DIR . '/includes/main.php';
        return;
    }

    add_action('admin_notices', function () {
        require_once BFTOW_PRO_DIR . '/includes/notices/install_free.php';
    });
});

