<?php

use UkrSolution\BarcodeScanner\API\PluginsHelper;
?>
<form class="bs-settings-input-conditions" id="bs-settings-products-tab" method="POST" action="<?php echo $actualLink; ?>">
    <input type="hidden" name="tab" value="products" />
    <input type="hidden" name="storage" value="table" />
    <table class="form-table">
        <tbody>
            <!-- allowNegativeStock -->
            <?php
            $allowNegativeStock = $settings->getSettings("allowNegativeStock");
            ?>
            <tr id="bs_allow_negative_stock">
                <th scope="row">
                    <?php echo __("Allow negative stock quantity", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <label>
                        <?php
                        if ($allowNegativeStock && $allowNegativeStock->value === "on") {
                            $checked = ' checked=checked ';
                        } else {
                            $checked = '';
                        }
                        ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_allow_negative_stock input[name='allowNegativeStock']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="allowNegativeStock" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
            <!-- crete search field -->
            <tr id="bs_search_cf">
                <th scope="row">
                    <?php echo __("Create search field", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <label>
                        <?php
                        $searchCF = $settings->getSettings("searchCF");
                        $searchCF = $searchCF ? $searchCF->value : $settings->getField("general", "searchCF", "on");

                        if ($searchCF === "on") {
                            $checked = ' checked=checked ';
                        } else {
                            $checked = '';
                        }
                        ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> data-main="searchCF" onchange="WebbsSettingsCheckboxChange(`#bs_search_cf input[name='searchCF']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="searchCF" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Create new custom field for products and variations which can be used for product search", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
            <tr id="bs_search_indexation" data-parent="searchCF">
                <th scope="row" style="padding-left: 20px;">
                    <?php echo __("Display name", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $searchCFLabel = $settings->getSettings("searchCFLabel");
                    $searchCFLabel = $searchCFLabel ? $searchCFLabel->value : $settings->getField("general", "searchCFLabel", "Barcode");
                    ?>
                    <input type="text" name="searchCFLabel" value=" <?php esc_html_e($searchCFLabel, 'us-barcode-scanner'); ?>" />
                    <br /><i><?php echo __("Custom field name, which will be displayed on the product page", "us-barcode-scanner"); ?></i>
                </td>
            </tr>
            <!-- Notify users on low product stock (after QTY change) -->
            <tr>
                <th scope="row">
                    <?php echo __("Notify users on low product stock (after QTY change)", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $field = $settings->getSettings("notifyUsersStock");
                    $productStatusesValue = $field === null ? "" : $field->value;
                    $productStatusesValueArr = $productStatusesValue ? explode(",", $productStatusesValue) : array();
                    ?>
                    <input type="hidden" name="notifyUsersStock[]" value="" />
                    <select name='notifyUsersStock[]' multiple data-placeholder='Choose a user...' multiple class='chosen-select-users-products' style="width:300px;">
                        <?php if ($productStatusesValueArr) : ?>
                            <?php foreach (get_users(array("orderby" => "ID", "include" => $productStatusesValueArr)) as $user) : ?>
                                <?php if (!in_array($user->ID, $productStatusesValueArr)) continue; ?>
                                <?php $name = $user->display_name == $user->user_login ? $user->display_name : $user->display_name . " (" . $user->user_login . ")"; ?>
                                <option value="<?php echo $user->ID; ?>"><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div>
                        <i>
                            <?php echo __("If stock QTY comes to a defined WooCommerce threshold - send en email to specific users about it.", "us-barcode-scanner"); ?>
                        </i>
                    </div>
                </td>
            </tr>

            <?php
            ?>
            <tr id="field_for_new_product">
                <th scope="row">
                    <?php echo __("New product code field", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $field = $settings->getSettings("fieldForNewProduct");
                    $fieldValue = $field === null ? $settings->getField("general", "fieldForNewProduct", "_sku") : $field->value;

                    $fields = array(
                        array("key" => "_sku", "value" => __("SKU Field", "us-barcode-scanner")),
                    );

                    $plugins = PluginsHelper::customPluginFields();
                    ?>
                    <select name="fieldForNewProduct" data-main="fieldForNewProduct">
                        <!-- default fields -->
                        <?php foreach ($fields as $value) : ?>
                            <option value="<?php echo $value["key"]; ?>" <?php if ($value["key"] == $fieldValue) echo 'selected="selected"'; ?>><?php echo $value["value"]; ?></option>
                        <?php endforeach; ?>
                        <!-- plugins fields -->
                        <?php foreach ($plugins as $key => $value) : ?>
                            <?php if (!$value["status"]) continue; ?>
                            <option value="<?php echo $key; ?>" <?php if ($key == $fieldValue) echo 'selected="selected"'; ?>><?php echo $value["label"]; ?></option>
                        <?php endforeach; ?>
                        <!-- custom field -->
                        <option value="custom_field" <?php if ("custom_field" == $fieldValue) echo 'selected="selected"'; ?>><?php echo __("Custom field (meta name)", "us-barcode-scanner"); ?></option>
                    </select>
                    <br /><i><?php echo __("Use this field for newly created products", "us-barcode-scanner"); ?></i>
                </td>
            </tr>
            <tr id="cf_for_new_product" data-parent="fieldForNewProduct">
                <th scope="row" style="padding-left: 20px;">
                    <?php echo __("Custom field", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $cfForNewProduct = $settings->getSettings("cfForNewProduct");
                    $cfForNewProduct = $cfForNewProduct ? $cfForNewProduct->value : "_sku";
                    ?>
                    <input type="text" name="cfForNewProduct" value=" <?php esc_html_e($cfForNewProduct, 'us-barcode-scanner'); ?>" />
                    <br /><i><?php echo __("Meta name", "us-barcode-scanner"); ?></i>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <?php echo __('Default "Status" for new products', "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $field = $settings->getSettings("newProductStatus");
                    $value = $field ? $field->value : "draft";
                    ?>
                    <select name="newProductStatus" style="width: 178px;">
                        <?php foreach (get_post_statuses() as $key => $label) : ?>
                            <?php $selected = $value === $key ? 'selected="selected"' : ""; ?>
                            <option value="<?php esc_html_e($key, 'us-barcode-scanner'); ?>" <?php esc_html_e($selected, 'us-barcode-scanner'); ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <?php echo __('Default "Quantity" for new products', "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $field = $settings->getSettings("newProductQty");
                    $value = $field === null ? $settings->getField("general", "newProductQty", "") : $field->value;
                    ?>
                    <input type="number" name="newProductQty" min="0" value="<?php esc_html_e($value, 'us-barcode-scanner'); ?>" placeholder="<?php esc_html_e("Quantity", 'us-barcode-scanner'); ?>" />
                </td>
            </tr>
            <?php
            ?>
            <!-- decimal_quantity -->
            <tr id="decimal_quantity">
                <th scope="row">
                    <?php echo __("Allow decimal quantity", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <label>
                        <?php
                        $field = $settings->getSettings("cartDecimalQuantity");
                        $value = $field === null ? "off" : $field->value;
                        ?>
                        <?php
                        if ($value === "on") {
                            $checked = ' checked=checked ';
                        } else {
                            $checked = '';
                        }
                        ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> data-main="cartDecimalQuantity" onchange="WebbsSettingsCheckboxChange(`#decimal_quantity input[name='cartDecimalQuantity']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="cartDecimalQuantity" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="submit">
        <input type="submit" class="button button-primary" value="<?php echo __("Save Changes", "us-barcode-scanner"); ?>">
    </div>
</form>
<script>
</script>


<?php
$stockLocations = get_terms(array(
    'taxonomy' => 'location',
    'hide_empty' => false,
));
?>

<style>
    table.scanner-tab-stock-locations th,
    table.scanner-tab-stock-locations td {
        padding: 5px 25px 10px 10px;
    }
</style>
<script>
    jQuery(document).ready(() => {
        const chosenOption = {
            width: "300px",
            no_results_text: "Loading:"
        };
        jQuery(".chosen-select-users-products").chosen(chosenOption);

        let request = null;
        jQuery('.chosen-select-users-products').on('chosen:no_results', function(evt, params) {
            const query = jQuery(params.chosen.search_field).val();
            const currentIds = jQuery(evt.target).val();
            if (request) request.abort();
            request = jQuery.post(window.usbs.ajaxUrl, {
                action: "usbs_find_user",
                query,
                currentIds
            }, function(data) {
                const selectElem = jQuery(evt.target);
                const selectedIds = selectElem.val();
                selectElem.empty();

                try {
                    if (data && JSON.parse(data)) data = JSON.parse(data);
                } catch (error) {

                }

                if (data.users) {
                    jQuery.each(data.users, function(idx, obj) {
                        const selected = currentIds.includes(obj.ID) ? "selected='selected'" : "";
                        selectElem.append('<option value="' + obj.ID + '" ' + selected + '>' + obj.user_nicename + '</option>');
                    });
                }

                jQuery(evt.target).trigger('chosen:updated');
                jQuery(".chosen-select-users-products").val(selectedIds).trigger("chosen:updated");
            });
        });

        var defaultValue = '<?php echo $productStatusesValue; ?>'.split(',');
        jQuery(".chosen-select-users-products").val(defaultValue).trigger("chosen:updated");
    });
</script>