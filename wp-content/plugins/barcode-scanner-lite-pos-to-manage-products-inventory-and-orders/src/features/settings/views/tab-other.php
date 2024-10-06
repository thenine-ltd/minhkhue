<?php

use UkrSolution\BarcodeScanner\features\sounds\Sounds;
?>
<form id="bs-settings-css-tab" method="POST" action="<?php echo $actualLink; ?>" enctype="multipart/form-data">
    <input type="hidden" name="tab" value="css" />
    <input type="hidden" name="storage" value="table" />
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row" style="width: 240px;">
                    <?php echo __("Custom CSS", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php $customCss = $settings->getSettings("customCss"); ?>
                    <textarea name="customCss" rows="6" cols="60"><?php echo $customCss ? stripslashes($customCss->value) : ""; ?></textarea>
                </td>
            </tr>

            <?php
            ?>
            <tr id="bs_debug">
                <th scope="row">
                    <?php echo __("Sound effects", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $sounds = new Sounds();
                    $list = $sounds->getList();
                    ?>
                    <!-- Increase -->
                    <div class="sound-block">
                        <!-- preview -->
                        <div style="display: flex; align-items: center;">
                            <b><?php echo __("Increase value", "us-barcode-scanner"); ?></b>
                            <audio controls>
                                <source src="<?php echo $list["increase"]; ?>" type="audio/mpeg">
                                <?php echo __("Your browser does not support the audio element.", "us-barcode-scanner"); ?>
                            </audio>
                        </div>
                        <!-- upload -->
                        <div style="display: flex; align-items: center;">
                            <b><?php echo __("Upload new", "us-barcode-scanner"); ?></b> &nbsp;
                            <input type="file" accept=".mp3" name="increaseFile" />
                        </div>
                    </div><br />

                    <!-- Decrease -->
                    <div class="sound-block">
                        <!-- preview -->
                        <div style="display: flex; align-items: center;">
                            <b><?php echo __("Decrease value", "us-barcode-scanner"); ?></b>
                            <audio controls>
                                <source src="<?php echo $list["decrease"]; ?>" type="audio/mpeg">
                                <?php echo __("Your browser does not support the audio element.", "us-barcode-scanner"); ?>
                            </audio>
                        </div>
                        <!-- upload -->
                        <div style="display: flex; align-items: center;">
                            <b><?php echo __("Upload new", "us-barcode-scanner"); ?></b> &nbsp;
                            <input type="file" accept=".mp3" name="decreaseFile" />
                        </div>
                    </div><br />

                    <!-- Fail -->
                    <div class="sound-block">
                        <!-- preview -->
                        <div style="display: flex; align-items: center;">
                            <b><?php echo __("Fail", "us-barcode-scanner"); ?></b>
                            <audio controls>
                                <source src="<?php echo $list["fail"]; ?>" type="audio/mpeg">
                                <?php echo __("Your browser does not support the audio element.", "us-barcode-scanner"); ?>
                            </audio>
                        </div>
                        <!-- upload -->
                        <div style="display: flex; align-items: center;">
                            <b><?php echo __("Upload new", "us-barcode-scanner"); ?></b> &nbsp;
                            <input type="file" accept=".mp3" name="failFile" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr id="bs_debug">
                <th scope="row">
                    <?php echo __("Debug information", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <label>
                        <?php
                        $field = $settings->getSettings("debugInfo");
                        $value = $field === null ? $settings->getField("general", "debugInfo", "") : $field->value;

                        if ($value === "on") {
                            $checked = ' checked=checked ';
                        } else {
                            $checked = '';
                        }
                        ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_debug input[name='debugInfo']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="debugInfo" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?>
                    </label>
                </td>
            </tr>
            <?php
            ?>
            <?php if ($wpml) : ?>
                <tr id="bs_wpml">
                    <th scope="row">
                        <?php echo __("wpml languages", "us-barcode-scanner"); ?>
                    </th>
                    <td>
                        <label>
                            <?php
                            $field = $settings->getSettings("wpmlUpdateProductsTree");
                            $value = $field === null ? $settings->getField("general", "wpmlUpdateProductsTree", "") : $field->value;

                            if ($value === "on") {
                                $checked = ' checked=checked ';
                            } else {
                                $checked = '';
                            }
                            ?>
                            <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> onchange="WebbsSettingsCheckboxChange(`#bs_wpml input[name='wpmlUpdateProductsTree']`,this.checked ? 'on' : 'off')" />
                            <input type="hidden" name="wpmlUpdateProductsTree" value="<?php echo $checked ? "on" : "off"; ?>" />
                            <?php echo __("Update all products", "us-barcode-scanner"); ?>
                        </label><br />
                        <i><?php echo __("If your wpml already configured to sync data between product translations,<br/>then you DON'T need to enable this option as it may cause double increase of qty.", "us-barcode-scanner"); ?></i>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="submit">
        <input type="submit" class="button button-primary" value="<?php echo __("Save Changes", "us-barcode-scanner"); ?>">
    </div>
</form>