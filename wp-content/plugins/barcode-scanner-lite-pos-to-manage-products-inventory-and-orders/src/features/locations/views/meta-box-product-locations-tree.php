<?php ?>
<div id="usbs_product_tab_locations_content" class="panel woocommerce_options_panel">
    <div id="usbs-locations-tree">
        <?php if ($options) : ?>
            <?php $index = 1; ?>
            <?php foreach ($options as $id => $option) : ?>
                <div class="usbs-location-tree" data-location-id="<?php echo $id; ?>" style="display: flex; flex-wrap: wrap; align-items: center; padding: 15px;">
                    <b><?php echo stripslashes($option["name"]); ?> &nbsp;</b>
                    <div class="usbs-location-tree-children"></div>
                </div>
                <?php
                if ($index < count($options)) echo '<hr />';
                $index++;
                ?>
            <?php endforeach; ?>
        <?php else : ?>
            <?php echo __("You need to choose a location to this product.", "us-barcode-scanner") ?>
        <?php endif; ?>
        <br />
        <span>
            <hr />
            <span style="padding-left: 15px;">
                <?php $editLocationUrl = admin_url('/admin.php?page=barcode-scanner-settings&tab=locations-data'); ?>
                <?php echo __("You can edit locations here", "us-barcode-scanner"); ?> <a href="<?php echo $editLocationUrl; ?>"><?php echo __("here", "us-barcode-scanner"); ?></a>
            </span>
        </span>
    </div>
    <style>
        #usbs-locations-tree {
            padding: 10px 0;
        }

        #usbs-locations-tree hr {
            margin: 15px 0;
        }
    </style>
    <script>
        const usbsltPostLocations = <?php echo json_encode($savedData); ?>;
        jQuery(document).ready(function() {
            let storesEl = jQuery("#usbs-locations-tree .usbs-location-tree[data-location-id]");

            const createList = (storeId, parentId, name) => {
                let isOptions = false;
                let list = jQuery("<select name='" + name + "' class='usbslt-list' data-store-id='" + storeId + "' data-parent-id='" + parentId + "'></select>");
                list.append('<option value="">Not selected</option>');

                Object.values(window.usbsLocationsTree.options).forEach(option => {
                    if (option.parent == parentId) {
                        list.append('<option value="' + option.id + '">' + option.name.replace(/\\(.)/mg, "$1") + '</option>');
                        isOptions = true;
                    }
                });

                return isOptions ? list : null;
            }

            const findOption = (optionId) => {
                let result = null;

                Object.values(window.usbsLocationsTree.options).forEach(option => {
                    if (option.id == optionId) result = option;
                });

                return result;
            }

            jQuery(document).on("change", "#usbs-locations-tree select.usbslt-list", (e) => {
                let selectedIndex = jQuery(e.target).index();
                let selectedId = jQuery(e.target).val();
                let storeId = jQuery(e.target).attr("data-store-id");
                let parentId = jQuery(e.target).attr("data-parent-id");
                let dropdownsEl = jQuery(e.target).closest(".usbs-location-tree[data-location-id='" + storeId + "']").find(".usbs-location-tree-children");

                dropdownsEl.find("select").each((index, element) => {
                    if (index > selectedIndex) jQuery(element).remove();
                });

                let list = createList(storeId, selectedId, "usbs-locations-tree[" + storeId + "][]");
                if (list) dropdownsEl.append(list);
            })

            storesEl.each((i, element) => {
                let id = jQuery(element).attr("data-location-id");
                let dropdownsEl = jQuery(element).find(".usbs-location-tree-children");
                let list = createList(id, id, "usbs-locations-tree[" + id + "][]");
                if (list) dropdownsEl.append(list);
            });

            if (Object.keys(usbsltPostLocations).length) {
                Object.keys(usbsltPostLocations).forEach(storeId => {
                    const optionId = usbsltPostLocations[storeId];
                    const option = findOption(optionId);
                    let parent = {
                        ...option
                    };
                    let parents = [];

                    while (parent) {
                        if (parent.parent) {
                            parent = findOption(parent.parent);
                            if (parent.parent) parents.push(parent);
                        } else {
                            parent = null;
                        }
                    }


                    let offset = 100;
                    parents = parents.reverse();
                    parents.push(option);

                    parents.forEach((value, index) => {
                        setTimeout(() => {
                            if (value && value.parent) {
                                jQuery("#usbs-locations-tree select.usbslt-list[data-parent-id='" + value.parent + "']").val(value.id);
                                jQuery("#usbs-locations-tree select.usbslt-list[data-parent-id='" + value.parent + "']").change();
                            }
                            offset = 100 + (100 * index);
                        }, offset);
                    });
                });
            }
        });
    </script>
</div>