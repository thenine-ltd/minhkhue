<div id="usbs-locations-tree-side">
    <?php foreach ($options as $id => $option) : ?>
        <?php $checked = $activeStores && in_array($option["id"], $activeStores) ? "checked='checked'" : ""; ?>
        <label class="selectit"><input value="<?php echo $option["id"]; ?>" type="checkbox" name="usbs-locations-stores[]" <?php echo $checked; ?>> <?php echo stripslashes($option["name"]); ?></label>
    <?php endforeach; ?>
    <hr />
    <?php $editLocationUrl = admin_url('/admin.php?page=barcode-scanner-settings&tab=locations-data'); ?>
    <?php echo __("You can edit locations here", "us-barcode-scanner"); ?> <a href="<?php echo $editLocationUrl; ?>"><?php echo __("here", "us-barcode-scanner"); ?></a>
</div>
<style>
    #usbs-locations-tree-side {
        padding: 10px 0;
    }

    #usbs-locations-tree-side label {
        display: block;
        margin-bottom: 10px;
    }
</style>