<div style="margin-right: 20px;">
    <b><?php echo __("Fields list:", "us-barcode-scanner"); ?></b>
    <button type="button" class="settings_field_add_new" data-position="product-middle-left">Add new</button>
    <div style="height: 5px;"></div>
    <table class="form-table wrapper" data-position="product-middle-left">
        <?php
        $isMobile = true;
        $orderField = "order_mobile";
        ?>
        <tbody class="usbs_fields_list_sortable_prices">
            <?php foreach ($interfaceData::getFields(false, "mobile") as $field) : ?>
                <?php if ($field["type"] == "price") {
                    require __DIR__ . "/field.php";
                } ?>
            <?php endforeach; ?>
        </tbody>
        <tbody>
            <tr>
                <td style="height: 10px;"></td>
            </tr>
        </tbody>
        <tbody class="usbs_fields_list_sortable">
            <?php foreach ($interfaceData::getFields(false, "mobile") as $field) : ?>
                <?php if ($field["type"] == "price") continue; ?>
                <?php if ($field["position"] == "product-middle-left" || $field["position"] == "product-middle-right" || $field["position"] == "product-left-sidebar") {
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