<?php

use UkrSolution\BarcodeScanner\API\PluginsHelper;
?>
<form id="bs-settings-plugins-tab" method="POST" action="<?php echo $actualLink; ?>">
    <input type="hidden" name="tab" value="plugins" />
    <div style="padding: 20px 20px 0; font-size: 14px;">
        <?php echo __("To speed up search, Barcode Scanner avoids worpdress core and plugins.", "us-barcode-scanner"); ?><br />
        <?php echo __("Here you can select which plugins should not be ignored and allow them to interfere into \"Barcode Scanner\" plugin's work.", "us-barcode-scanner"); ?>
    </div>
    <table class="form-table">
        <tbody>
            <?php  ?>
            <tr>
                <td colspan="2">
                    <!-- roles -->
                    <table class="wp-list-table widefat plugins">
                        <tr>
                            <td><?php echo __("Status", "us-barcode-scanner"); ?></td>
                            <td><?php echo __("Plugin name", "us-barcode-scanner"); ?></td>
                        </tr>
                        <tbody class="the-list">
                            <?php foreach ($settings->getPlugins() as $slug => $data) : ?>
                                <?php $isEnabled = PluginsHelper::is_plugin_active($slug); ?>
                                <tr class="<?php if ($isEnabled) echo "active"; ?>">
                                    <!-- status -->
                                    <th class="check-column" style="<?php echo $isEnabled ? "padding-left: 0;" : "padding-left: 6px;"; ?>">
                                        <?php $checked = isset($data["bs_active"]) && $data["bs_active"] ? ' checked=checked ' : ''; ?>
                                        <input type="checkbox" name="plugins[]" value="<?php echo $slug; ?>" id="<?php echo $slug; ?>" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> />
                                    </th>
                                    <!-- name -->
                                    <td class="plugin-title">
                                        <label for="<?php echo $slug; ?>"><?php echo $data["Name"]; ?></label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="submit">
        <input type="submit" class="button button-primary" value="<?php echo __("Save Changes", "us-barcode-scanner"); ?>">
    </div>
</form>

<style>
    #bs-settings-plugins-tab tr.active {}
</style>