<?php

use UkrSolution\BarcodeScanner\features\settings\SettingsHelper;
?>
<?php
$subTabUrl = get_admin_url() . "admin.php?page=barcode-scanner-settings&tab=fields";
$subActive = isset($_GET["sub"]) ? $_GET["sub"] : "desktop";

?>
<div class="usbs-subtubs">
    <?php
    ?>
    <div><a data-tab="desktop" href="<?php echo $subTabUrl; ?>&sub=desktop" class="<?php echo $subActive == "desktop" ? "active" : "" ?>"><?php echo __('Desktop-full view ', "us-barcode-scanner"); ?></a></div>
    <div><span class="separator">|</span></div>
    <div><a data-tab="mobile" href="<?php echo $subTabUrl; ?>&sub=mobile" class="<?php echo $subActive == "mobile" ? "active" : "" ?>"><?php echo __('Mobile view', "us-barcode-scanner"); ?></a></div>
    <?php
    ?>
</div>

<form id="bs-settings-fields-tab" method="POST" action="<?php echo $actualLink; ?>">
    <input type="hidden" name="tab" value="fields" />
    <input type="hidden" name="sub" value="<?php echo $subActive; ?>" />
    <input type="hidden" name="storage" value="table" />
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; max-width: 1190px;">
        <div style="padding: 0px 0 0 10px;"><?php echo __('Here you can add/edit/remove fields which displays in the "Inventory" tab (in popup).', "us-barcode-scanner"); ?></div>
        <?php  ?>
    </div>
    <div style="display: flex; padding: 25px 0 0 10px; flex-flow: row wrap;">
        <?php
        if ($subActive == "mobile") {
            require_once __DIR__ . "/prices/_mobile.php";
        } else {
            require_once __DIR__ . "/prices/_web.php";
        }
        ?>
    </div>

    <div class="submit">
        <input type="submit" class="button button-primary" value="<?php echo __("Save Changes", "us-barcode-scanner"); ?>">
    </div>
</form>