<form class="bs-settings-input-conditions" id="bs-settings-search-tab" method="POST" action="<?php echo $actualLink; ?>">
    <input type="hidden" name="tab" value="search" />
    <input type="hidden" name="storage" value="table" />
    <table class="form-table">
        <tbody>
            <?php
            ?>
            <!--  -->
            <tr>
                <th scope="row">
                    <?php echo __("Exclude product statuses from search", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $field = $settings->getSettings("productStatuses");
                    $productStatusesValue = $field === null ? "trash" : $field->value;
                    ?>
                    <select name="productStatuses[]" class="usbs_product_statuses" multiple="true" style="width:300px;">
                        <option value="publish"><?php echo __("Publish", "us-barcode-scanner"); ?></option>
                        <option value="future"><?php echo __("Future", "us-barcode-scanner"); ?></option>
                        <option value="draft"><?php echo __("Draft", "us-barcode-scanner"); ?></option>
                        <option value="pending"><?php echo __("Pending", "us-barcode-scanner"); ?></option>
                        <option value="private"><?php echo __("Private", "us-barcode-scanner"); ?></option>
                        <option value="auto-draft"><?php echo __("Auto-Draft", "us-barcode-scanner"); ?></option>
                        <option value="inherit"><?php echo __("Inherit", "us-barcode-scanner"); ?></option>
                        <option value="trash"><?php echo __("Trash", "us-barcode-scanner"); ?></option>
                    </select>
                </td>
            </tr>
            <!--  -->
            <tr>
                <th scope="row">
                    <?php echo __("Exclude order statuses from search", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <?php
                    $field = $settings->getSettings("orderStatuses");
                    $orderStatusesValue = $field === null ? "wc-checkout-draft,trash" : $field->value;
                    ?>
                    <select name="orderStatuses[]" class="usbs_order_statuses" multiple="true" style="width:300px;">
                        <?php foreach ($settings->getOrderStatuses() as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                        <option value="trash"><?php echo __("Trash", "us-barcode-scanner"); ?></option>
                    </select>
                </td>
            </tr>
            <tr id="bs_display_search_counter">
                <th scope="row">
                    <?php echo __("Display search counter", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <label>
                        <?php
                        $field = $settings->getSettings("displaySearchCounter");
                        $value = $field === null ? $settings->getField("general", "displaySearchCounter", "") : $field->value;
                        $checked = $value === "on" ? ' checked=checked ' : '';
                        ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> data-main="displaySearchCounter" onchange="WebbsSettingsCheckboxChange(`#bs_display_search_counter input[name='displaySearchCounter']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="displaySearchCounter" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?> <span class="usbs-option-notice"></span>
                    </label><br />
                    <i>
                        <?php echo __("Displays the counter of how much times the product/order has been opened using barcode scanner."); ?>
                    </i>
                </td>
            </tr>
            <tr id="bs_search_indexation">
                <th scope="row">
                    <?php echo __("Start indexation", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <div>
                        <?php if (!$settings->getField("indexing", "indexed", false)) : ?>
                            <div id="bs_search_indexation_notice">
                                <div class="notice-error notice" style="margin: 0 0 10px; display: inline-block;">
                                    <p>Please start indexation to speed up search</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php
                        $link = '#barcode-scanner-products-indexation';
                        ?>
                        <a href="<?php echo $link; ?>"><?php echo __("Start indexation", "us-barcode-scanner"); ?></a>
                        <i style="padding-left: 10px;"><?php echo __("Re-create index tables and make full indexation of products and orders.", "us-barcode-scanner"); ?></i>
                    </div>
                    <div style="padding-top: 5px;">
                        <?php
                        $indexed = $settings->getTotalIndexedRecords();
                        $total = $settings->getTotalPosts();
                        $cannotIndexed = $settings->getTotalCantIndexedRecords();
                        ?>
                        <span id="barcode-scanner-products-total-indexed" style="color: #008b00;"><?php echo $indexed < $total ? $indexed : $total; ?></span>
                        <?php echo __("successfully indexed of", "us-barcode-scanner"); ?> <span id="barcode-scanner-products-total"><?php echo $total; ?></span>
                        <?php if ($cannotIndexed) : ?>
                            <?php echo __("Can't index", "us-barcode-scanner"); ?> <span style="color: #ff0000;" id="barcode-scanner-products-fail-indexed"><?php echo $cannotIndexed; ?></span> <?php echo __("items", "us-barcode-scanner"); ?>.
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php 
            ?>
            <!-- Indexation step (items per request) -->
            <tr>
                <th scope="row">
                    <?php echo __("Indexation step (items per request)", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <label>
                        <?php
                        $field = $settings->getSettings("indexationStep");
                        $value = $field === null ? 50 : (int)$field->value;
                        $value = $value ? $value : 50;
                        ?>
                        <input type="number" name="indexationStep" value="<?php echo $value; ?>" placeholder="50" min="1" max="1000" />
                    </label>
                </td>
            </tr>
            <!-- Search results max limit -->
            <tr>
                <th scope="row">
                    <?php echo __("Search results max limit", "us-barcode-scanner"); ?>
                </th>
                <td>
                    <label>
                        <?php
                        $field = $settings->getSettings("searchResultsLimit");
                        $value = $field === null ? 20 : (int)$field->value;
                        $value = $value ? $value : 20;
                        ?>
                        <input type="number" name="searchResultsLimit" value="<?php echo $value; ?>" placeholder="20" min="1" max="200" />
                    </label>
                    <br />
                    <i><?php echo __("Specify how much maximum search result you would like to see in search suggestion dropdown.", "us-barcode-scanner"); ?></i>

                </td>
            </tr>
            <tr id="bs_direct_db_search">
                <th scope="row">
                    <?php echo __("Enable direct DB requests (avoiding WP/Woo core)", "us-barcode-scanner"); ?>
                    <div style="font-weight: 400; padding-top: 5px;">
                        <a href="<?php echo admin_url('/admin.php?page=barcode-scanner-settings&tab=plugins'); ?>"><?php echo __("Allow plugins", "us-barcode-scanner"); ?></a>
                        <?php echo __("which should interfere into \"Barcode Scanner\" plugin's work", "us-barcode-scanner"); ?>
                    </div>
                </th>
                <td>
                    <label>
                        <?php
                        $field = $settings->getSettings("directDbSearch");
                        $value = $field === null ? $settings->getField("general", "directDbSearch", "on") : $field->value;
                        ?>
                        <?php
                        if ($value === "on") {
                            $checked = ' checked=checked ';
                        } else {
                            $checked = '';
                        }
                        ?>
                        <input type="checkbox" <?php esc_html_e($checked, 'us-barcode-scanner'); ?> data-main="directDbSearch" onchange="WebbsSettingsCheckboxChange(`#bs_direct_db_search input[name='directDbSearch']`,this.checked ? 'on' : 'off')" />
                        <input type="hidden" name="directDbSearch" value="<?php echo $checked ? "on" : "off"; ?>" />
                        <?php echo __("Enable", "us-barcode-scanner"); ?> <span class="usbs-option-notice"></span>
                    </label><br />
                    <i>
                        <?php echo __("This option may speed up plugin's work dramatically as it starts to work with the database directly (avoiding WP/Woo core)."); ?><br />
                        <?php echo __("However, third party plugins won't be able to hook/interact with this plugin (won't be able to catch events triggered by this plugin)."); ?>
                    </i>
                </td>
            </tr>
            <?php  ?>
        </tbody>
    </table>
    <div class="submit">
        <input type="submit" class="button button-primary" value="<?php echo __("Save Changes", "us-barcode-scanner"); ?>">
    </div>
</form>
<script>
    jQuery(document).ready(function() {
        jQuery(".usbs_product_statuses").chosen({
            search_contains: true,
            no_results_text: "<?php echo __("Nothing found for:", "us-barcode-scanner"); ?> ",
            width: "300px"
        });
        var defaultValue = '<?php echo $productStatusesValue; ?>'.split(',');
        jQuery(".usbs_product_statuses").val(defaultValue).trigger("chosen:updated");

        jQuery(".usbs_order_statuses").chosen({
            search_contains: true,
            no_results_text: "<?php echo __("Nothing found for:", "us-barcode-scanner"); ?> ",
            width: "300px"
        });
        var defaultValue = '<?php echo $orderStatusesValue; ?>'.split(',');
        jQuery(".usbs_order_statuses").val(defaultValue).trigger("chosen:updated");
    });
</script>