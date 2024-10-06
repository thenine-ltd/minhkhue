<?php

use UkrSolution\BarcodeScanner\features\locations\LocationsData;

$isPrintingActive = class_exists('UkrSolution\ProductLabelsPrinting\Helpers\Variables');
?>

<table class="form-table">
    <tbody>
        <tr>
            <td>
                <button type="button" class="usbs-locations-add-option button button-default"><?php echo __("Create first level", "us-barcode-scanner"); ?></button>
                <?php if ($isPrintingActive) : ?>
                    &nbsp; &nbsp;
                    <button type="button" class="button button-default" href="#usbs-locations-print" disabled><?php echo __("Create label", "us-barcode-scanner"); ?></button>
                <?php endif; ?>
            </td>
        </tr>
    </tbody>
</table>

<form id="bs-settings-locations-tab" method="POST" action="<?php echo $actualLink; ?>">
    <input type="hidden" name="tab" value="locations-data" />
    <input type="hidden" name="storage" value="table" />

    <div class="dd" id="usbs-locations-place">
        <?php
        $locations = LocationsData::getLocations();

        $options = array_filter($locations, function ($value) {
            return $value["parent"] ? 0 : 1;
        });

        LocationsData::displaySettingsAdminList($locations, $options, __DIR__ . "/tab-locations-options-list.php");
        ?>
    </div>

    <div class="submit">
        <input type="submit" class="button button-primary" value="<?php echo __("Save Changes", "us-barcode-scanner"); ?>">
    </div>
</form>

<script>
    jQuery(document).ready(function() {
        const createOptionModal = (e) => {
            e.preventDefault();
            jQuery("#barcode-scanner-modal").remove();
            let parentId = jQuery(e.target).attr("data-id");
            let css = `
            #barcode-scanner-modal { position: fixed;top: 0px;left: 0px;width: 100vw;height: 100vh;z-index: 9000;font-size: 14px;background: rgba(0, 0, 0, 0.3);transition: opacity 0.3s ease 0s;transform: translate3d(0px, 0px, 0px); }
            #barcode-scanner-modal .bsm-body {box-sizing: border-box; background:#fff; padding:25px; position: relative; width: 450px; top: calc(50% - 225px); left: calc(50% - 225px); color: #fff; border-radius: 8px; border: 1px solid #f3f3f3; }
            #barcode-scanner-modal .bsm-title {color: #333; font-size: 21px; margin-bottom: 15px;}
            #barcode-scanner-modal .bsm-text {color: #333; font-size: 14px;}
            #barcode-scanner-modal .bsm-close {    position: absolute; top: 0; right: 0; width: 28px; cursor: pointer; padding: 6px; box-sizing: border-box; opacity: 0.6;}
            #barcode-scanner-modal .bsm-close img {width: 100%}
            #barcode-scanner-modal .bsm-description { padding-top: 15px; }
            @keyframes a4b-spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
            `;
            let modal = jQuery('<div id="barcode-scanner-modal"></div>');
            modal.attr("data-parent-id", parentId);
            let body = jQuery('<div class="bsm-body"></div>');
            body.append('<div class="bsm-close"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAjdJREFUWEftljtPVUEUhT/gFxAUeRRqADVBAz0VIrEksRVjqYm/R4UGW2JDQsuzJxYEhRAVgyT4oJAeNWYl+5h7J3se54LBgtOc5JyZvb5Ze2bvaeOcn7Zz1ucCIOZAB/AcGAMeAO9bTNUtYAFYB54Bv8M4HkA7MAc8tsHfgAngXU2Im8Aq0Gfz5oFp4FdjHA/gJfA0EPtiEDuFEMPACnAlGP/CnPj72QP4AAw4QqVO3AGWgW4nhlJ5I+eA8ibrep0AR+bEVsSJEWAJuOz8/25z3+YA9F8QsrDKX+OcH8Ak8CYQkbhWfqmOe6k6EG6iEOI+sGEfR23lnvhXW/m251quEClfSke/M/kYEMSJiXc5YyR+F4hu3hyAYqYglA49nY74ATAOfEydnBIAzb8GrNm75CR+tpUnxRWoFEBjrxrE9QyBxLXyvRLSOgCKJ3E5IRjv+WTi+yXidR2o9oMAvOOp/zrr2nTFZbuOA9qMKfFq0aqYgnCPXehMKUCpeG2IEoBUQdJq9YRNR98OzYnd0xzDIbPdK0RVbVchUmq83pFNR8qBEvGqscilFISOpVsNYwApcXVEbbKmrgakIKIl2QMYtCuUZ3uuHadauQvhAajX33Y2Tmzl4VDNVSv3LiSqD02xPYAZ4EkQNbfyUojZ8LrnAehG/Ap4ZFFjF5BctVU6tDF7bOBr4CHws3Fi6lou2nvAFLCZU4v81/1w0VIiV5tuxK30ghY54tNKKuGZi5ak4J+K/lcAfwBZG3QhFnA8UwAAAABJRU5ErkJggg=="/></div>');
            if (parentId) body.append('<div class="bsm-title"><?php echo __("Add child property", "us-barcode-scanner"); ?></div>');
            else body.append('<div class="bsm-title"><?php echo __("Create first level", "us-barcode-scanner"); ?></div>');
            let form = jQuery(`
                <div class="bsm-text">
                    <form id="usbs-locations-add-new-option-form">
                        <input type="text" name="name" placeholder="<?php echo __("Name", "us-barcode-scanner"); ?>"/>
                        <button type="submit"><?php echo __("Add", "us-barcode-scanner"); ?></button>
                    </form>
                </div>
            `);
            const desc = parentId ?
                `<?php echo __("For warehouse it can be a name of the room, for the rack it can be a number of shelf, etc.", "us-barcode-scanner"); ?>` :
                `<?php echo __("The first level can be a few things - it actually depends on your warehouse structure. It can be a warehouse name (if you have a few warehouses) or name of rooms in your warehouse.", "us-barcode-scanner"); ?>`;
            form.append(jQuery('<div class="bsm-description">' + desc + '</div>'));
            body.append(form);
            modal.append(body);
            modal.append(`<style>${css}</style>`);
            jQuery("#wpbody-content").append(modal);
            jQuery("#barcode-scanner-modal .bsm-close").click(() => {
                jQuery("#barcode-scanner-modal").remove();
            });
        }
        jQuery(".usbs-locations-add-option").click(createOptionModal);

        const setParent = (option, parent = "") => {
            jQuery('input[name="locationData[' + option.id + '][parent]"]').val(parent);
            if (option.children) {
                option.children.forEach(childOption => setParent(childOption, option.id));
            }
        };

        const updateOutput = () => {
            let data = jQuery('#usbs-locations-place').nestable('serialize');
            if (data) data.forEach(option => setParent(option, ""));
        };

        jQuery('#usbs-locations-place').nestable({
            maxLevels: 0,
            maxDepth: 10
        }).on('change', updateOutput);

        updateOutput();

        jQuery(document).on('submit', '#usbs-locations-add-new-option-form', (e) => {
            e.preventDefault();

            let id = Date.now();
            let label = jQuery("#usbs-locations-add-new-option-form > [name='name']").val();
            let parentId = jQuery(e.target).closest("#barcode-scanner-modal").attr("data-parent-id");
            let isPrintingActive = <?php echo $isPrintingActive ? 1 : 0; ?>;

            if (label == "") return;
            let html = '<li class="dd-item dd3-item" data-id="' + id + '" data-label="' + label + '">' +
                '<span class="dd-handle dashicons dashicons-move" title="Move"></span>' +
                '<div class="dd3-content"><span>' + label + '</span>';

            if (isPrintingActive == 1) {
                html += '<div class="usbs-locations-item-print"></div>';
            }

            html += '<div class="usbs-locations-item-edit">Edit</div>' +
                '<div class="usbs-locations-item-add-option" data-id="' + id + '">+ Add child property</div>' +
                '</div>' +
                '<div class="usbs-locations-item-settings d-none">' +
                '<p><label for=""><?php echo __("Label", "us-barcode-scanner"); ?><br><input type="text" class="name" name="locationData[' + id + '][name]" value="" /></label></p>' +
                '<input type="hidden" class="parent" name="locationData[' + id + '][parent]" value="" />' +
                '<p><a class="usbs-locations-item-delete" href="javascript:;"><?php echo __("Remove", "us-barcode-scanner"); ?></a> | ' +
                '<a class="usbs-locations-item-close" href="javascript:;"><?php echo __("Close", "us-barcode-scanner"); ?></a></p>' +
                '</div>' +
                '<ol class="dd-list"> </ol>' +
                '</li>';
            let item = jQuery(html);
            item.find("input.name").val(label);

            if (parentId) {
                jQuery("#usbs-locations-place .dd-item[data-id='" + parentId + "'] > .dd-list").append(item);
            }
            else {
                jQuery("#usbs-locations-place > .dd-list").append(item);
            }
            jQuery("#usbs-locations-place").find('.dd-empty').remove();
            jQuery("#usbs-locations-add-new-option-form > [name='name']").val('');

            updateOutput();

            jQuery("#barcode-scanner-modal").remove();
        });
        jQuery("body").delegate(".usbs-locations-item-delete", "click", function(e) {
            if (confirm(`<?php echo __("Are you sure delete?", "us-barcode-scanner"); ?>`)) {
                jQuery(this).closest(".dd-item").remove();
                updateOutput();
            }
        });
        jQuery("body").delegate(".usbs-locations-item-edit, .usbs-locations-item-close", "click", function(e) {
            const item_setting = jQuery(this).closest(".dd-item").find(">.usbs-locations-item-settings");
            if (item_setting.hasClass("d-none")) {
                item_setting.removeClass("d-none");
            } else {
                item_setting.addClass("d-none");
            }
        });
        jQuery("body").delegate(".usbs-locations-item-add-option", "click", createOptionModal);
        jQuery("body").delegate("input.name", "change paste keyup", function(e) {
            jQuery(this).closest(".dd-item").data("label", jQuery(this).val());
            jQuery(this).closest(".dd-item").find(">.dd3-content span").text(jQuery(this).val());
        });
        jQuery(document).on("change", "#usbs-locations-place input[type='checkbox']", function() {
            const checked = jQuery("#usbs-locations-place input[type='checkbox']:checked");
            if (checked.length) jQuery('button[href="#usbs-locations-print"]').removeAttr("disabled");
            else jQuery('button[href="#usbs-locations-print"]').attr("disabled", "disabled");
        });

    });
</script>
<style>
    .dd3-content {
        display: block;
        height: 45px;
        padding: 5px 10px 5px 54px;
        color: #333;
        text-decoration: none;
        font-weight: bold;
        line-height: 32px;
        border: 1px solid #ccc;
        background: #fafafa;
        background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
        background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
        background: linear-gradient(top, #fafafa 0%, #eee 100%);
        -webkit-border-radius: 3px;
        border-radius: 0;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .dd3-content:hover {
        background: #fff;
    }

    .dd-dragel>.dd3-item>.dd3-content {
        margin: 0;
    }


    .dd3-item {
        margin: 10px 0;
    }

    .dd3-item>button {
        margin-left: 30px;
    }

    .dd-handle.dashicons-move {
        position: absolute;
        left: 0;
        top: 10px;
        color: #cfcfcf;
        background: transparent;
        border: none;
        height: initial;
        width: 27px;
        margin: 0;
        padding: 4px;
        text-decoration: none;
        font-weight: bold;
        box-sizing: border-box;
    }

    .dd-expand,
    .dd-collapse {
        display: none;
    }

    .dd-handle.dashicons-move:hover {
        color: #999;
        background: transparent;
        border: none;
    }

    .usbs-locations-item-add-option,
    .usbs-locations-item-edit {
        font-size: 13px;
        float: right;
        cursor: pointer;
    }

    .usbs-locations-item-add-option {
        margin-right: 15px;
        padding-right: 15px;
        position: relative;
    }

    .usbs-locations-item-add-option::after {
        content: " ";
        display: block;
        width: 2px;
        background: #434343;
        height: 15px;
        position: absolute;
        right: -1px;
        top: 9px;
    }

    .usbs-locations-item-add-option:hover,
    .usbs-locations-item-edit:hover {
        text-decoration: underline;
    }

    .usbs-locations-item-print {
        float: right;
        margin: 0 0 0 10px;
        min-width: 20px;
        min-height: 5px;
    }

    .usbs-locations-item-settings.d-none {
        display: none !important;
    }

    .usbs-locations-item-settings {
        display: block;
        padding: 10px;
        position: relative;
        z-index: 10;
        border: 1px solid #e5e5e5;
        background: #fff;
        border-top: none;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
    }

    .usbs-locations-item-settings p {
        margin-top: 0;
    }

    .usbs-locations-item-settings p label {
        font-size: 13px;
        color: #666;
        line-height: 1.5;
    }

    .usbs-locations-item-settings p label input {
        border: 1px solid #ddd;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        background-color: #fff;
        color: #32373c;
        outline: 0;
        border-spacing: 0;
        width: -webkit-fill-available;
        clear: both;
        margin: 0;
        font-size: 14px;
        padding: 5px;
        border-radius: 0;
    }

    .usbs-locations-item-settings .usbs-locations-item-delete {
        color: #a00;
    }
</style>