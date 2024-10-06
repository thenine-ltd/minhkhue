<form class="bs-settings-input-conditions" id="bs-settings-general-tab" method="POST" action="<?php echo $actualLink; ?>">
    <input type="hidden" name="tab" value="general" />
    <?php
    ?>
    <table class="form-table">
        <tbody>
            <?php
            ?>
            <?php
            $allowToUseOnFrontend = $settings->getField("general", "allowToUseOnFrontend", "");
            $frontendIntegration = $settings->getField("general", "frontendIntegration", "");
            $allowFrontendShortcodes = $settings->getField("general", "allowFrontendShortcodes", "");

            if ($allowToUseOnFrontend === "" && $frontendIntegration === "on") {
                $allowToUseOnFrontend = "on";
            }
            ?>
            <tr id="bs_allow_frontend_integration">
                <th scope="row">
                    <?php echo __("Allow to use scanner on website/front-end", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <label>
                        <?php
                        if ($allowToUseOnFrontend === "on") {
                            $checked = ' checked=checked ';
                        } else {
                            $checked = '';
                        }
                        ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_allow_frontend_integration input[name='allowToUseOnFrontend']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="allowToUseOnFrontend" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                        <i style="color: #8d0000;">
                            <?php $url = get_admin_url() . "admin.php?page=barcode-scanner-settings&tab=permissions"; ?>
                            <?php $text = __('(You also need to grant this access for the groups, do it in the <a href="%url%">Permissions</a> tab)', "us-barcode-scanner"); ?>
                            <?php echo str_replace("%url%", $url, $text); ?>
                        </i>
                    </label>
                </td>
            </tr>
            <tr id="bs_frontend_url" <?php echo $allowToUseOnFrontend === "on" ? "" : "style='display: none;'" ?>>
                <th scope="row" style="padding-left: 20px; padding-top: 5px;">
                    <?php echo __("Front-end link", "us-barcode-scanner"); ?>
                </th>
                <td style="padding-top: 5px;">
                    <?php $url = get_home_url() . "/barcode-scanner-front"; ?>
                    <a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a>
                </td>
            </tr>
            <tr id="bs_frontend_integration" <?php echo $allowToUseOnFrontend === "on" ? "" : "style='display: none;'" ?>>
                <th scope="row" style="padding-left: 20px; padding-top: 5px;">
                    <?php echo __("Show scanner in user's <br/>\"My-Account\" menu", "us-barcode-scanner"); ?>
                </th>
                <td style="padding-top: 5px;">
                    <label>
                        <?php
                        if ($frontendIntegration === "on") {
                            $checked = ' checked=checked ';
                        } else {
                            $checked = '';
                        }
                        ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_frontend_integration input[name='frontendIntegration']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="frontendIntegration" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
            <tr id="bs_frontend_shortcodes_integration" <?php echo $allowToUseOnFrontend === "on" ? "" : "style='display: none;'" ?>>
                <th scope="row" style="padding-left: 20px; padding-top: 5px;">
                    <?php echo __("Allow to embed scanner shortcode on the front-end", "us-barcode-scanner"); ?>
                </th>
                <td style="padding-top: 5px;">
                    <label>
                        <?php
                        if ($allowFrontendShortcodes === "on") {
                            $checked = ' checked=checked ';
                        } else {
                            $checked = '';
                        }
                        ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_frontend_shortcodes_integration input[name='allowFrontendShortcodes']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="allowFrontendShortcodes" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
            <tr id="bs_frontend_shortcodes_docs" <?php echo $allowToUseOnFrontend === "on" && $allowFrontendShortcodes === "on" ? "" : "style='display: none;'" ?>>
                <td scope="row" colspan="2" style="padding: 0 0 0 20px; position: relative; top: -10px;">
                    <i><?php echo __("Embed shortcode on any page: <b>[barcode-scanner-popup auto-show=true show-link=\"Show Scanner\"]</b>
                                        <br/><br/>Attributes:
                                        <br/><b>auto-show</b> - allows to display scanner popup right after page is loaded.
                                        <br/><b>show-link</b> - displays link by clicking on which scanner popup will be displayed.", "us-barcode-scanner"); ?></i>
                </td>
            </tr>
            <?php
            ?>

            <?php   ?>

        </tbody>
    </table>
    <?php
    ?>
    <div class="submit">
        <input type="submit" class="button button-primary" value="<?php echo __("Save Changes", "us-barcode-scanner"); ?>">
    </div>
    <?php
    ?>
</form>