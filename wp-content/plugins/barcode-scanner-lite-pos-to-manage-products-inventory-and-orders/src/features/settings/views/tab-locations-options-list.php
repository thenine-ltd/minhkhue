<?php

use UkrSolution\BarcodeScanner\features\locations\LocationsData;

$isPrintingActive = class_exists('UkrSolution\ProductLabelsPrinting\Helpers\Variables');
?>
<ol class="dd-list">
    <?php foreach ($options as $id => $option) : ?>
        <?php $name = stripslashes($option["name"]); ?>
        <li class="dd-item dd3-item" data-id="<?php echo $id; ?>" data-label="<?php echo $name; ?>"><span class="dd-handle dashicons dashicons-move" title="Move"></span>
            <div class="dd3-content"><span><?php echo $name; ?></span>
                <?php if ($isPrintingActive) : ?>
                    <div class="usbs-locations-item-print">
                        <input type="checkbox" name="print-locations[]" value="<?php echo $id; ?>" title="<?php echo __("Check to create label for this location", "us-barcode-scanner"); ?>" />
                    </div>
                <?php endif; ?>
                <div class="usbs-locations-item-edit"><?php echo __("Edit", "us-barcode-scanner"); ?></div>
                <div class="usbs-locations-item-add-option" data-id="<?php echo $id; ?>"><?php echo __("+ Add child property", "us-barcode-scanner"); ?></div>
            </div>
            <div class="usbs-locations-item-settings d-none">
                <p><label for=""><?php echo __("Label", "us-barcode-scanner"); ?><br><input type="text" class="name" name="locationData[<?php echo $id; ?>][name]" value="<?php echo htmlentities($name); ?>"></label></p>
                <input type="hidden" class="parent" name="locationData[<?php echo $id; ?>][parent]" value="<?php echo $option["parent"]; ?>">
                <p><a class="usbs-locations-item-delete" href="javascript:;"><?php echo __("Remove", "us-barcode-scanner"); ?></a> | <a class="usbs-locations-item-close" href="javascript:;"><?php echo __("Close", "us-barcode-scanner"); ?></a></p>
            </div>

            <!-- check child -->
            <?php
            $children = array_filter($locations, function ($value) use ($id) {
                return $value["parent"] == $id ? 1 : 0;
            });

            LocationsData::displaySettingsAdminList($locations, $children, __DIR__ . "/tab-locations-options-list.php");
            ?>
        </li>
    <?php endforeach; ?>
</ol>