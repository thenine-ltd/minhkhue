<?php

use UkrSolution\BarcodeScanner\features\settings\Settings;

$settings = new Settings();

?>
<title>Barcode Scanner mobile</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<a href="#barcode-scanner-mobile"></a>
<div id="ukrsolution-barcode-scanner"></div>
<div id="ukrsolution-barcode-scanner-mobile"></div>

<div id="barcode-scanner-mobile-preloader">
    <div style="user-select: none;">Loading...</div>
</div>

<script>
    window.usbsLangsMobile = <?php echo json_encode($this->getLangs()); ?>;
    window.usbsInterfaceMobile = <?php echo json_encode(apply_filters("scanner_product_fields_filter", $interfaceData::getFields(true))); ?>;
    window.usbsCategoriesMobile = <?php echo json_encode($productCategories); ?>;
    window.usbsMobile = <?php echo json_encode($jsData); ?>;
    window.usbsHistory = <?php echo json_encode($usbsHistory); ?>;
</script>