<?php

use UkrSolution\BarcodeScanner\features\settings\Settings;

$settings = new Settings();

?>
<title>Barcode Scanner</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<a href="#?p=barcode-scanner-frontend" data-plugin="barcode-scanner-frontend" id="barcode-scanner-auto-show" style="display:none;">Barcode scanner</a>
<link href="<?php echo USBS_PLUGIN_BASE_URL; ?>/assets/css/style.css">
</link>
<script>
    window.BarcodeScannerAutoShow = true;
    window.BarcodeScannerDisableClose = true;
</script>
<script>
    window.usbsLangs = <?php echo json_encode($this->getLangs()); ?>;
    window.usbsInterface = <?php echo json_encode(apply_filters("scanner_product_fields_filter", $interfaceData::getFields(true))); ?>;
    window.usbsCategories = <?php echo json_encode($productCategories); ?>;
    window.usbs = <?php echo json_encode($jsData); ?>;
    window.usbsCustomCss = <?php echo json_encode($customCss); ?>;
    window.usbsLocationsTree = <?php echo json_encode($locationsTree); ?>;
    window.usbsHistory = <?php echo json_encode($usbsHistory); ?>;
</script>
<script src="<?php echo home_url(); ?>/wp-includes/js/jquery/jquery.min.js"></script>
<script src="<?php echo home_url(); ?>/wp-includes/js/jquery/jquery-migrate.min.js"></script>

<script src="<?php echo $path; ?>assets/js/index-business-1.5.1-1698401813780.js"></script>

<?php
