<?php
$isMobile = false;
$orderField = "order";
?>
<!-- left sidebar -->
<div style="margin-right: 20px; width: 380px; margin-bottom: 25px;">
    <b><?php echo __("Left sidebar fields:", "us-barcode-scanner"); ?></b>
    <button type="button" class="settings_field_add_new" data-position="product-left-sidebar">Add new</button>
    <div style="height: 5px;"></div>
    <table class="form-table wrapper" data-position="product-left-sidebar">
        <tbody class="usbs_fields_list_sortable">
            <?php foreach ($interfaceData::getFields(false, "", true) as $field) : ?>
                <?php if ($field["position"] == "product-left-sidebar") {
                    require __DIR__ . "/field.php";
                } ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- end left sidebar -->
<!-- <div style="width: 100%;"></div> -->
<!-- middle left -->
<div style="margin-right: 20px; width: 380px;">
    <b><?php echo __("Middle left fields:", "us-barcode-scanner"); ?></b>
    <button type="button" class="settings_field_add_new" data-position="product-middle-left">Add new</button>
    <div style="height: 5px;"></div>
    <table class="form-table wrapper" data-position="product-middle-left">
        <tbody class="usbs_fields_list_sortable">
            <?php foreach ($interfaceData::getFields(false) as $field) : ?>
                <?php if ($field["position"] == "product-middle-left") {
                    require __DIR__ . "/field.php";
                } ?>
            <?php endforeach; ?>
            <!-- template -->
            <?php
            $field = array(
                "id" => 0,
                "field_name" => "",
                "field_label" => "New field",
                "label_position" => "top",
                "field_height" => "",
                "label_width" => "",
                "position" => "",
                "type" => "text",
                "order" => "",
                "order_mobile" => "",
                "status" => "0",
                "show_in_create_order" => "0",
                "show_in_products_list" => "0",
                "use_for_auto_action" => "0",
            );
            $rootClass = "new_field_template";
            require __DIR__ . "/field.php";
            $rootClass = "";
            ?>
            <!-- end template -->
        </tbody>
    </table>
</div>
<!-- end middle left -->
<!-- middle right -->
<div style="width: 380px;">
    <b><?php echo __("Middle right fields:", "us-barcode-scanner"); ?></b>
    <button type="button" class="settings_field_add_new" data-position="product-middle-right">Add new</button>
    <div style="height: 5px;"></div>
    <table class="form-table wrapper" data-position="product-middle-right">
        <tbody class="usbs_fields_list_sortable">
            <?php foreach ($interfaceData::getFields(false) as $field) : ?>
                <?php if ($field["position"] == "product-middle-right") {
                    require __DIR__ . "/field.php";
                } ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- end middle right -->