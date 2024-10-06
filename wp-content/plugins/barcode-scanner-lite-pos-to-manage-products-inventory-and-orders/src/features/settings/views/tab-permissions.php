<form id="bs-settings-permissions-tab" method="POST" action="<?php echo $actualLink; ?>">
    <input type="hidden" name="tab" value="permissions" />
    <table class="form-table">
        <tbody>
            <?php  ?>
            <tr>
                <th scope="row" colspan="2" style="padding-bottom: 0;">
                    <b><?php echo __("Tabs permissions:", "us-barcode-scanner"); ?></b>
                <th>
            </tr>
            <tr>
                <td colspan="2">
                    <!-- roles -->
                    <table class="bs-settings-roles-list">
                        <tr>
                            <td><?php echo __("Role", "us-barcode-scanner"); ?></td>
                            <td><?php echo __("Products tab", "us-barcode-scanner"); ?></td>
                            <td><?php echo __("New product", "us-barcode-scanner"); ?></td>
                            <td>
                                <div style="height: 100%; width: 1px; border-left: 1px solid;">&nbsp;</div>
                            </td>
                            <td><?php echo __("Orders tab", "us-barcode-scanner"); ?></td>
                            <td><?php echo __("New order", "us-barcode-scanner"); ?></td>
                            <td><?php echo __("Allow to link customer", "us-barcode-scanner"); ?></td>
                            <td>
                                <div style="height: 100%; width: 1px; border-left: 1px solid;">&nbsp;</div>
                            </td>
                            <td>
                                <?php echo __("Frontend popup", "us-barcode-scanner"); ?>
                                <span style="position: relative;">
                                    <span style="font-size: 16px; position: absolute; left: 5px;" class="dashicons dashicons-info-outline" title="<?php echo __("Allows to display search popup for users on frontend/website.", "us-barcode-scanner"); ?>"></span>
                                </span>
                            </td>
                        </tr>
                        <?php foreach ($settings->getRoles() as $key => $role) : ?>
                            <tr>
                                <!-- Role -->
                                <td><?php echo $role["name"]; ?></td>
                                <!-- Products tab -->
                                <?php $permissions = $settings->getRolePermissions($key); ?>
                                <td style="text-align: center;">
                                    <?php
                                    if ($permissions && isset($permissions["inventory"]) && $permissions["inventory"]) $checked = ' checked=checked ';
                                    else $checked = '';
                                    $parentProduct = $checked;
                                    ?>
                                    <input type="hidden" name="rolesPermissions[<?php echo $key; ?>][inventory]" value="0" />
                                    <input type="checkbox" name="rolesPermissions[<?php echo $key; ?>][inventory]" value="1" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> />
                                </td>
                                <!-- New product -->
                                <td style="text-align: center;">
                                    <?php
                                    if ($permissions && isset($permissions["newprod"]) && $permissions["newprod"]) $checked = ' checked=checked ';
                                    else $checked = '';
                                    ?>
                                    <input type="hidden" name="rolesPermissions[<?php echo $key; ?>][newprod]" value="0" />
                                    <input type="checkbox" name="rolesPermissions[<?php echo $key; ?>][newprod]" value="1" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> />
                                </td>
                                <td>&nbsp;</td>
                                <!-- Orders tab -->
                                <td style="text-align: center;">
                                    <?php
                                    if ($permissions && isset($permissions["orders"]) && $permissions["orders"]) $checked = ' checked=checked ';
                                    else $checked = '';
                                    ?>
                                    <input type="hidden" name="rolesPermissions[<?php echo $key; ?>][orders]" value="0" />
                                    <input type="checkbox" name="rolesPermissions[<?php echo $key; ?>][orders]" value="1" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> />
                                </td>
                                <!-- New order -->
                                <td style="text-align: center;">
                                    <?php
                                    if ($permissions && isset($permissions["cart"]) && $permissions["cart"]) $checked = ' checked=checked ';
                                    else $checked = '';
                                    $parentNewOrder = $checked;
                                    ?>
                                    <input type="hidden" name="rolesPermissions[<?php echo $key; ?>][cart]" value="0" />
                                    <input type="checkbox" name="rolesPermissions[<?php echo $key; ?>][cart]" value="1" parent="order" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> />
                                </td>
                                <!-- Allow to link customer -->
                                <td style="text-align: center;">
                                    <?php
                                    if ($permissions && isset($permissions["linkcustomer"]) && $permissions["linkcustomer"]) $checked = ' checked=checked ';
                                    else $checked = '';
                                    ?>
                                    <input type="hidden" name="rolesPermissions[<?php echo $key; ?>][linkcustomer]" value="0" />
                                    <input type="checkbox" name="rolesPermissions[<?php echo $key; ?>][linkcustomer]" value="1" group="order" <?php echo $parentNewOrder ? '' : 'disabled="disabled"'; ?> <?php esc_html_e($checked, 'us-barcode-scanner'); ?> />
                                </td>
                                <td>&nbsp;</td>
                                <!-- Frontend popup -->
                                <td style="text-align: center;">
                                    <?php
                                    if ($permissions && isset($permissions["frontend"]) && $permissions["frontend"]) $checked = ' checked=checked ';
                                    else $checked = '';
                                    ?>
                                    <input type="hidden" name="rolesPermissions[<?php echo $key; ?>][frontend]" value="0" />
                                    <input type="checkbox" name="rolesPermissions[<?php echo $key; ?>][frontend]" value="1" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> />
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="submit">
        <input type="submit" class="button button-primary" value="<?php echo __("Save Changes", "us-barcode-scanner"); ?>">
    </div>
</form>

<script>
    jQuery(document).ready(() => {
        jQuery(".bs-settings-roles-list tr input[type='checkbox']").change((e) => {
            const parent = jQuery(e.target).attr("parent");
            const group = jQuery(e.target).attr("group");
            const status = jQuery(e.target).is(":checked");

            if (parent && status) {
                jQuery(e.target).closest("tr").find("input[type='checkbox'][group='" + parent + "']").removeAttr("disabled");
            } else {
                jQuery(e.target).closest("tr").find("input[type='checkbox'][group='" + parent + "']").prop("checked", false);
                jQuery(e.target).closest("tr").find("input[type='checkbox'][group='" + parent + "']").attr("disabled", "disabled");
            }
        });
    });
</script>