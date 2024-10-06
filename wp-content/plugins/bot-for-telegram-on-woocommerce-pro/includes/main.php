<?php
require_once BFTOW_PRO_DIR . '/includes/BFTOW_PRO_Notifications.php';
require_once BFTOW_PRO_DIR . '/includes/BFTOW_PRO_Account.php';
require_once BFTOW_PRO_DIR . '/includes/orders/orders.php';
require_once BFTOW_PRO_DIR . '/includes/hooks/BFTOW_PRO_Hooks.php';
require_once BFTOW_PRO_DIR . '/includes/BFTOW_PRO_Keyboard.php';
require_once BFTOW_PRO_DIR . '/includes/BFTOW_PRO_Location.php';
require_once BFTOW_PRO_DIR . '/includes/BFTOW_PRO_User_Settings.php';
if(bftow_get_option('bftow_enable_search', false) === true){
    require_once BFTOW_PRO_DIR . '/includes/BFTOW_PRO_Search.php';
}

require_once BFTOW_PRO_DIR . '/includes/alerts/connect.php';
