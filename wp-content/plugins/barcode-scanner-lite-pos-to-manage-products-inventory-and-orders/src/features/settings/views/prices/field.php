<tr class="settings_field_section <?php echo (isset($rootClass) && $rootClass) ? $rootClass : "" ?>">
    <td style="padding: 0;">
        <div style="padding: 14px 10px 10px; background: #fff; margin-bottom: 10px; position: relative; width: 360px; box-shadow: 0 0 8px 1px #c7c7c7; border-radius: 4px;">
            <input type="hidden" class="usbs_field_order" name="fields[<?php echo $field["id"]; ?>][<?php echo $orderField; ?>]" value="<?php echo $field[$orderField]; ?>" />
            <input type="hidden" class="usbs_field_position" name="fields[<?php echo $field["id"]; ?>][position]" value="<?php echo $field["position"]; ?>" />
            <input type="hidden" class="usbs_field_remove" name="fields[<?php echo $field["id"]; ?>][remove]" value="0" />

            <span class="dashicons dashicons-move" title="<?php echo __("Move", "us-barcode-scanner"); ?>"></span>

            <div class="settings_field_block_label" data-fid="<?php echo $field["id"]; ?>">
                <span class="dashicons dashicons-arrow-up-alt2"></span>
                <span class="dashicons dashicons-arrow-down-alt2 active"></span>
                <?php 
                ?> <?php echo $field["field_label"]; ?>
                <?php if ($field["status"] == 0) : ?>
                    <span style="color: #bbb; position: relative; top: -4px;}"><?php echo __("(disabled)", "us-barcode-scanner"); ?></span>
                <?php endif; ?>
            </div>
            <!-- settings -->
            <div id="settings_field" class="settings_field_body" data-fid="<?php echo $field["id"]; ?>">
                <div colspan="2" style="padding: 0;">
                    <table>
                        <tr class="usbs_field_status">
                            <td scope="row" style="text-align:left; padding: 0 10px 0 10px; width: 110px; box-sizing: border-box;">
                                <label onclick="WebbsFieldsChToggle(this, 'usbs_field_status')" data-fid="<?php echo $field["id"]; ?>">
                                    <?php echo __("Enable", "us-barcode-scanner"); ?>
                                </label>
                            </td>
                            <td style="padding: 0 0 5px;">
                                <!-- checkbox -->
                                <?php $checked = $field["status"] == 1 ? ' checked=checked ' : ''; ?>
                                <input type="checkbox" class="usbs_field_status" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> data-fid="<?php echo $field["id"]; ?>" onchange="WebbsSettingsCheckboxChange(`#bs-settings-fields-tab .usbs_field_status input[data-fid='<?php echo $field['id']; ?>']`, this.checked ? '1' : '0')" />
                                <input type="hidden" name="fields[<?php echo $field["id"]; ?>][status]" value="<?php echo $checked ? "1" : "0"; ?>" data-fid="<?php echo $field["id"]; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td scope="row" style="text-align:left; padding: 0 10px 0 10px; width: 110px; box-sizing: border-box;">
                                <?php echo __("Field type", "us-barcode-scanner"); ?>
                            </td>
                            <td style="padding: 0;">
                                <select class="usbs_field_type" name="fields[<?php echo $field["id"]; ?>][type]" style="width: 177px;">
                                    <option value="text" <?php echo $field["type"] == "text" ? "selected='selected'" : ""; ?>><?php echo  __("Text", "us-barcode-scanner"); ?></option>
                                    <option value="price" <?php echo $field["type"] == "price" ? "selected='selected'" : ""; ?>><?php echo  __("Price", "us-barcode-scanner"); ?></option>
                                    <option value="number_plus_minus" <?php echo $field["type"] == "number_plus_minus" ? "selected='selected'" : ""; ?>><?php echo  __("Number (plus/minus)", "us-barcode-scanner"); ?></option>
                                    <option value="select" <?php echo $field["type"] == "select" ? "selected='selected'" : ""; ?>><?php echo  __("Dropdown", "us-barcode-scanner"); ?></option>
                                    <?php if ($settingsHelper::is_plugin_active('product-expiry-for-woocommerce/product-expiry-for-woocommerce.php')) : ?>
                                        <option value="ExpiryDate" <?php echo $field["type"] == "ExpiryDate" ? "selected='selected'" : ""; ?>><?php echo  __("ExpiryDate", "us-barcode-scanner"); ?></option>
                                    <?php endif; ?>
                                    <option value="white_space" <?php echo $field["type"] == "white_space" ? "selected='selected'" : ""; ?>><?php echo  __("White space", "us-barcode-scanner"); ?></option>
                                    <option value="categories" <?php echo $field["type"] == "categories" ? "selected='selected'" : ""; ?>><?php echo  __("Categories", "us-barcode-scanner"); ?></option>
                                    <option value="locations" <?php echo $field["type"] == "locations" ? "selected='selected'" : ""; ?>><?php echo  __("Locations", "us-barcode-scanner"); ?></option>
                                    <option value="usbs_date" <?php echo $field["type"] == "usbs_date" ? "selected='selected'" : ""; ?>><?php echo  __("Date", "us-barcode-scanner"); ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php if ($isMobile == false || true) : ?>
                            <tr class="show_in_create_order">
                                <td scope="row" style="text-align:left; padding: 0 10px 0 10px; width: 110px; box-sizing: border-box;">
                                    <label onclick="WebbsFieldsChToggle(this, 'usbs_field_show_in_create_order')" data-fid="<?php echo $field["id"]; ?>">
                                        <?php echo __("Show in order", "us-barcode-scanner"); ?>
                                    </label>
                                </td>
                                <td style="padding: 0 0 5px;">
                                    <!-- checkbox -->
                                    <?php $checked = $field["show_in_create_order"] == 1 ? ' checked=checked ' : ''; ?>
                                    <input type="checkbox" class="usbs_field_show_in_create_order" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> data-fid="<?php echo $field["id"]; ?>" onchange="WebbsSettingsCheckboxChange(`#bs-settings-fields-tab .show_in_create_order input[data-fid='<?php echo $field['id']; ?>']`, this.checked ? '1' : '0')" />
                                    <input type="hidden" name="fields[<?php echo $field["id"]; ?>][show_in_create_order]" value="<?php echo $checked ? "1" : "0"; ?>" data-fid="<?php echo $field["id"]; ?>" />
                                </td>
                            </tr>
                        <?php endif; ?>

                        <tr class="show_in_products_list" style="<?php echo !$isMobile ? "display: none;" : ""; ?>">
                            <td scope="row" style="text-align:left; padding: 0 10px 0 10px; width: 110px; box-sizing: border-box;">
                                <label onclick="WebbsFieldsChToggle(this, 'usbs_field_show_in_products_list')" data-fid="<?php echo $field["id"]; ?>">
                                    <?php echo __("Show in mobile list", "us-barcode-scanner"); ?>
                                </label>
                            </td>
                            <td style="padding: 0 0 5px;">
                                <!-- checkbox -->
                                <?php $checked = $field["show_in_products_list"] == 1 ? ' checked=checked ' : ''; ?>
                                <input type="checkbox" class="usbs_field_show_in_products_list" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> data-fid="<?php echo $field["id"]; ?>" onchange="WebbsSettingsCheckboxChange(`#bs-settings-fields-tab .show_in_products_list input[data-fid='<?php echo $field['id']; ?>']`, this.checked ? '1' : '0')" />
                                <input type="hidden" name="fields[<?php echo $field["id"]; ?>][show_in_products_list]" value="<?php echo $checked ? "1" : "0"; ?>" data-fid="<?php echo $field["id"]; ?>" />
                            </td>
                        </tr>

                        <tr>
                            <td scope="row" style="text-align:left; padding: 0 10px 0 10px; width: 110px; box-sizing: border-box;">
                                <?php echo __("Field label", "us-barcode-scanner"); ?>
                            </td>
                            <td style="padding: 0;">
                                <input type="text" class="usbs_field_label" name="fields[<?php echo $field["id"]; ?>][field_label]" value="<?php echo $field["field_label"]; ?>" style="width: 177px;" />
                            </td>
                        </tr>
                        <tr>
                            <td scope="row" style="text-align:left; padding: 0 10px 0 10px; width: 110px; box-sizing: border-box;">
                                <?php echo __("Meta name", "us-barcode-scanner"); ?>
                            </td>
                            <td style="padding: 0;">
                                <input type="text" class="usbs_field_name" name="fields[<?php echo $field["id"]; ?>][field_name]" value="<?php echo $field["field_name"]; ?>" style="width: 177px;" />
                                <button type="button" class="cf_check_name">Check</button>
                                <div style="display: inline-block; position: relative; width: 1px;">
                                    <span class="cf_check_name_result"></span>
                                </div>
                            </td>
                        </tr>
                        <tr class="type_select">
                            <td scope="row" style="text-align:left; padding: 0 10px 0 10px; width: 110px; box-sizing: border-box;">
                                <?php echo __("Options", "us-barcode-scanner"); ?>
                            </td>
                            <td style="padding: 5px 0;">
                                <div class="type_select_options">
                                    <?php $options = isset($field["options"]) && $field["options"] ? @json_decode($field["options"], false) : null; ?>
                                    <?php if ($options) : ?>
                                        <?php $optionIndex = 0; ?>
                                        <?php foreach ($options as $key => $value) : ?>
                                            <div class="type_select_option">
                                                <input type="text" name="fields[<?php echo $field["id"]; ?>][options][<?php echo $optionIndex; ?>][key]" value="<?php echo $key; ?>" />
                                                <input type="text" name="fields[<?php echo $field["id"]; ?>][options][<?php echo $optionIndex; ?>][value]" value="<?php echo $value; ?>" />
                                                <span class="type_select_option_remove">✖</span>
                                            </div>
                                            <?php $optionIndex++; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <span class="type_select_option_add">+ <?php echo __("Add new", "us-barcode-scanner"); ?></span>
                            </td>
                        </tr>
                        <?php  ?>
                        <tr style="<?php echo $isMobile ? "display: none;" : ""; ?>">
                            <td scope="row" style="text-align:left; padding: 0 10px 0 10px; width: 110px; box-sizing: border-box;">
                                <?php echo __("Label position", "us-barcode-scanner"); ?>
                            </td>
                            <td style="padding: 0;">
                                <select class="usbs_field_label_position" name="fields[<?php echo $field["id"]; ?>][label_position]" style="width: 177px;">
                                    <option value="left" <?php echo $field["label_position"] == "left" ? "selected='selected'" : ""; ?>><?php echo  __("Left", "us-barcode-scanner"); ?></option>
                                    <option value="right" <?php echo $field["label_position"] == "right" ? "selected='selected'" : ""; ?>><?php echo  __("Right", "us-barcode-scanner"); ?></option>
                                    <option value="top" <?php echo $field["label_position"] == "top" ? "selected='selected'" : ""; ?>><?php echo  __("Top", "us-barcode-scanner"); ?></option>
                                    <option value="bottom" <?php echo $field["label_position"] == "bottom" ? "selected='selected'" : ""; ?>><?php echo  __("Bottom", "us-barcode-scanner"); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row" style="text-align:left; padding: 0 10px 0 10px; width: 110px; box-sizing: border-box;">
                                <?php echo __("Height", "us-barcode-scanner"); ?>
                            </td>
                            <td style="padding: 0;">
                                <input type="text" class="usbs_field_height" name="fields[<?php echo $field["id"]; ?>][field_height]" value="<?php echo $field["field_height"]; ?>" style="width: 177px;" />
                            </td>
                        </tr>
                        <tr>
                            <td scope="row" style="text-align:left; padding: 0 10px 0 10px; width: 110px; box-sizing: border-box;">
                                <?php echo __("Label width", "us-barcode-scanner"); ?>
                            </td>
                            <td style="padding: 0;">
                                <input type="number" class="usbs_label_width" name="fields[<?php echo $field["id"]; ?>][label_width]" value="<?php echo $field["label_width"]; ?>" style="width: 100px" /> %
                            </td>
                        </tr>
                    </table>

                    <span class="dashicons dashicons-trash settings_field_remove" title="<?php echo  __("Remove field", "us-barcode-scanner"); ?>" data-fid="<?php echo $field["id"]; ?>"></span>
                </div>
            </div>
        </div>
    </td>
</tr>