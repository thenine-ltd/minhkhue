<form class="bs-settings-input-conditions" id="bs-settings-orders-tab" method="POST" action="<?php echo $actualLink; ?>">
    <input type="hidden" name="tab" value="orders" />
    <input type="hidden" name="storage" value="table" />
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row" style="width: 240px;">
                    <?php echo __("Default order status", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("defaultOrderStatus");
                    $defaultValue = $defaultValue === null ? $settings->getField("general", "defaultOrderStatus", "wc-processing") : $defaultValue->value;
                    ?>
                    <select name="defaultOrderStatus">
                        <?php
                        foreach ($settings->getOrderStatuses() as $key => $value) {
                            $selected = "";
                            if ($defaultValue === $key) {
                                $selected = ' selected=selected ';
                            }
                        ?>
                            <option value="<?php esc_html_e($key, 'us-barcode-scanner'); ?>" <?php esc_html_e($selected, 'us-barcode-scanner'); ?>><?php esc_html_e($value, 'us-barcode-scanner'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row" style="width: 240px;">
                    <?php echo __("Default shipping method", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("defaultShippingMethod");
                    $defaultValue = $defaultValue === null ? $settings->getField("general", "defaultShippingMethod") : $defaultValue->value;
                    ?>
                    <select name="defaultShippingMethod" style="max-width: 175px;">
                        <option value=""><?php echo __('Not selected', 'us-barcode-scanner'); ?></option>
                        <?php
                        foreach ($settings->getShippingMethod() as $value) {
                            $selected = "";
                            if ($defaultValue === $value["id"] . ':' . $value["instance_id"]) {
                                $selected = ' selected=selected ';
                            }
                        ?>
                            <option value="<?php esc_html_e($value["id"] . ':' . $value["instance_id"], 'us-barcode-scanner'); ?>" <?php esc_html_e($selected, 'us-barcode-scanner'); ?>><?php esc_html_e($value["title"], 'us-barcode-scanner'); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row" style="width: 240px;">
                    <?php echo __("New order default user", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("nowOrderDefaultUser");
                    $defaultValue = $defaultValue === null ? "" : $defaultValue->value;
                    $userName = "";
                    if ($defaultValue) {
                        $user = get_user_by("ID", $defaultValue);
                        if ($user) {
                            $userName = $user->display_name . " (" . $user->user_login . ") - " . $user->user_email;
                        }
                    }
                    ?>
                    <span style="position: relative;">
                        <input type="text" value="<?php esc_html_e($userName); ?>" placeholder="<?php echo __("Find user", "us-barcode-scanner"); ?>" class="order-default-user-search-input" />
                        <input type="hidden" name="nowOrderDefaultUser" value="<?php esc_html_e($defaultValue); ?>" class="order-default-user-id-search-input" />
                        <span style="position: relative;">
                            <span style="position: absolute; top: -5px; left: 0; display: none;" id="order-default-user-search-preloader">
                                <span id="barcode-scanner-action-preloader">
                                    <span class="a4b-action-preloader-icon"></span>
                                </span>
                            </span>
                        </span>
                        <ul class="order-default-users-search-list"></ul>
                        <div>
                            <i><?php echo __("Link this user (by default) to all newly created orders via Barcode Scanner popup.", "us-barcode-scanner"); ?></i>
                        </div>
                    </span>
                </td>
            </tr>
            <?php  ?>
            <tr>
                <th scope="row" style="width: 240px;">
                    <b><?php echo __("Use price to create order", "us-barcode-scanner"); ?></b>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("defaultPriceField");
                    $defaultValue = $defaultValue === null ? $settings->getField("prices", "defaultPriceField") : $defaultValue->value;
                    ?>
                    <select name="defaultPriceField" style="max-width: 175px;">
                        <?php $selected = $defaultValue === "wc_default" || $settings->getField("prices", "defaultPriceField", "wc_default") ? 'selected="selected"' : ""; ?>
                        <option value="wc_default" <?php esc_html_e($selected, 'us-barcode-scanner'); ?>><?php esc_html_e("WooCommerce default", 'us-barcode-scanner'); ?></option>

                        <?php
                        ?>
                        <?php foreach ($interfaceData::getFields(true) as $field) : ?>
                            <?php if ($field["type"] == "price" && $field["status"] == 1) : ?>
                                <?php $selected = $defaultValue === $field["field_name"] ? 'selected="selected"' : ""; ?>
                                <option value="<?php echo $field["field_name"]; ?>" <?php esc_html_e($selected, 'us-barcode-scanner'); ?>><?php esc_html_e("Always use " . $field["field_label"], 'us-barcode-scanner'); ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php
                        ?>
                    </select>
                </td>
            </tr>

            <!-- Order fulfillment enabled by default - Disable by default -->
            <tr id="bs_order_fulfillment_enabled">
                <th scope="row">
                    <?php echo __("Order fulfillment enabled by default", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("orderFulfillmentEnabled");
                    $defaultValue = $defaultValue === null ? 'off' : $defaultValue->value;
                    ?>
                    <label>
                        <?php $checked = $defaultValue !== "off" ? ' checked=checked ' : ''; ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_order_fulfillment_enabled input[name='orderFulfillmentEnabled']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="orderFulfillmentEnabled" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label><br />
                    <i><?php echo __('If this option enabled then "fulfillment" mode will be active by default.', "us-barcode-scanner"); ?></i>
                </td>
            </tr>

            <!-- Send new order email to admin - Disable by default -->
            <tr id="bs_send_email_for_created_order">
                <th scope="row">
                    <?php echo __("Send new order email to admin", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("sendAdminEmailCreatedOrder");
                    $defaultValue = $defaultValue === null ? 'off' : $defaultValue->value;
                    ?>
                    <label>
                        <?php $checked = $defaultValue !== "off" ? ' checked=checked ' : ''; ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_send_email_for_created_order input[name='sendAdminEmailCreatedOrder']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="sendAdminEmailCreatedOrder" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
            <!-- Send new order email to client - Enable by default -->
            <tr id="bs_send_email_for_created_order">
                <th scope="row">
                    <?php echo __("Send new order email to client", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("sendClientEmailCreatedOrder");
                    $defaultValue = $defaultValue === null ? 'on' : $defaultValue->value;
                    ?>
                    <label>
                        <?php $checked = $defaultValue !== "off" ? ' checked=checked ' : ''; ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_send_email_for_created_order input[name='sendClientEmailCreatedOrder']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="sendClientEmailCreatedOrder" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
            <!-- Require to select user -->
            <tr id="bs_new_order_user_required">
                <th scope="row">
                    <?php echo __("Require to select user", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("newOrderUserRequired");
                    $defaultValue = $defaultValue === null ? 'off' : $defaultValue->value;
                    ?>
                    <label>
                        <?php $checked = $defaultValue !== "off" ? ' checked=checked ' : ''; ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_new_order_user_required input[name='newOrderUserRequired']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="newOrderUserRequired" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
            <!-- Shipping method is required -->
            <tr id="bs_shipping_required">
                <th scope="row">
                    <?php echo __("Shipping method is required", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("shippingRequired");
                    $defaultValue = $defaultValue === null ? 'off' : $defaultValue->value;
                    ?>
                    <label>
                        <?php $checked = $defaultValue !== "off" ? ' checked=checked ' : ''; ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_shipping_required input[name='shippingRequired']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="shippingRequired" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
            <!-- Open order after creation -->
            <tr id="bs_open_order_after_creation">
                <th scope="row">
                    <?php echo __("Open order after creation", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("openOrderAfterCreation");
                    $defaultValue = $defaultValue === null ? 'off' : $defaultValue->value;
                    ?>
                    <label>
                        <?php $checked = $defaultValue !== "off" ? ' checked=checked ' : ''; ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_open_order_after_creation input[name='openOrderAfterCreation']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="openOrderAfterCreation" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
            <!-- Require to select user -->
            <tr id="fulfillment_scan_item_qty">
                <th scope="row">
                    <?php echo __("Order fulfillment - take into account item's quantity", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $defaultValue = $settings->getSettings("fulfillmentScanItemQty");
                    $defaultValue = $defaultValue === null ? 'on' : $defaultValue->value;
                    ?>
                    <label>
                        <?php $checked = $defaultValue !== "off" ? ' checked=checked ' : ''; ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#fulfillment_scan_item_qty input[name='fulfillmentScanItemQty']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="fulfillmentScanItemQty" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label><br />
                    <i><?php echo __("In order fulfillment mode, this option will take into account amount of the purchased items (qty). So, order item will be  fulfilled (marked with green arrow) as soon as product is scanned in the same amount as was purchased. E.g. if 10 the same items were purchased - you will have to scan the barcode 10 times.", "us-barcode-scanner"); ?></i>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="submit">
        <input type="submit" class="button button-primary" value="<?php echo __("Save Changes", "us-barcode-scanner"); ?>">
    </div>
</form>
<style>
    .order-default-user-search-input {
        min-width: 250px;
    }
</style>