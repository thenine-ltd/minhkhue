<?php

use UkrSolution\BarcodeScanner\features\settings\Settings;

$settings = new Settings();

wp_head();
?>
<title>Barcode Scanner</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<div class="message">
    <span><?php echo __("You don't have access to this page.", "us-barcode-scanner"); ?></span>
</div>
<style>
    .message {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .message span {
        background: #eaeaea;
        border-radius: 5px;
        padding: 10px 25px;
        font-size: 18px;
    }
</style>
<?php
wp_footer();
