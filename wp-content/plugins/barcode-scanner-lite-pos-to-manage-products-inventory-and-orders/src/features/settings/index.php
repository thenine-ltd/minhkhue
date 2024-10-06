<!-- default tab -->
<?php
$tab = "search";
if (isset($_POST["tab"])) {
    $tab = sanitize_text_field($_POST["tab"]);
} else if (isset($_GET["tab"])) {
    $tab = sanitize_text_field($_GET["tab"]);
} else if ($settings->activeTab) {
    $tab = $settings->activeTab;
}
$url = $_SERVER['REQUEST_URI'];
$url = preg_replace('/(\&tab=.*)/', "", $url);
$actualLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$url-update";
?>
<a href="#barcode-scanner-settings"></a>
<div id="bs-settings-page">
    <h2><?php echo __("Barcode Scanner settings", "us-barcode-scanner"); ?></h2>
    <div>
        <nav class="nav-tab-wrapper">
            <a href="#search" class="nav-tab <?php echo ($tab === "search") ? "nav-tab-active" : "" ?>" data-tab="search"><?php echo __("Search", "us-barcode-scanner"); ?></a>
            <a href="#fields" class="nav-tab <?php echo ($tab === "fields") ? "nav-tab-active" : "" ?>" data-tab="fields"><?php echo __("Product fields", "us-barcode-scanner"); ?></a>

            <?php
            ?>
            <a href="#app" class="nav-tab <?php echo ($tab === "app") ? "nav-tab-active" : "" ?>" data-tab="app"><?php echo __("Mobile App", "us-barcode-scanner"); ?></a>
            <?php
            ?>

            <a href="#products" class="nav-tab <?php echo ($tab === "products") ? "nav-tab-active" : "" ?>" data-tab="products"><?php echo __("Products", "us-barcode-scanner"); ?></a>
            <?php
            ?>

            <?php
            ?>
            <a href="#orders" class="nav-tab <?php echo ($tab === "orders") ? "nav-tab-active" : "" ?>" data-tab="orders"><?php echo __("Orders", "us-barcode-scanner"); ?></a>
            <?php
            ?>

            <?php
            ?>
            <a href="#permissions" class="nav-tab <?php echo ($tab === "permissions") ? "nav-tab-active" : "" ?>" data-tab="permissions"><?php echo __("Permissions", "us-barcode-scanner"); ?></a>
            <?php
            ?>

            <a href="#general" class="nav-tab <?php echo ($tab === "general") ? "nav-tab-active" : "" ?>" data-tab="general"><?php echo __("Front-end popup", "us-barcode-scanner"); ?></a>

            <?php
            ?>

            <a href="#plugins" class="nav-tab <?php echo ($tab === "plugins") ? "nav-tab-active" : "" ?>" data-tab="plugins"><?php echo __("Direct DB requests", "us-barcode-scanner"); ?></a>

            <?php
            ?>
            <a href="#css" class="nav-tab <?php echo ($tab === "css") ? "nav-tab-active" : "" ?>" data-tab="css"><?php echo __("Other", "us-barcode-scanner"); ?></a>
            <?php
            ?>

            <a href="#license" class="nav-tab <?php echo ($tab === "license") ? "nav-tab-active" : "" ?>" data-tab="license"><?php echo __("License", "us-barcode-scanner"); ?></a>
            <?php
            ?>

            <!-- custom tabs -->
            <?php foreach ($customTabs as $index => $customTab) : ?>
                <?php $slug = isset($customTab["slug"]) && $customTab["slug"] ? $customTab["slug"] : "tab-slug-" . $index; ?>
                <?php $name = isset($customTab["name"]) && $customTab["name"] ? $customTab["name"] : "Tab name"; ?>
                <a href="#license" class="nav-tab <?php echo ($tab === $slug) ? "nav-tab-active" : "" ?>" data-tab="<?php echo $slug; ?>"><?php echo $name; ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="tabs">

            <!-- general -->
            <div class="settings-tab general-tab" <?php echo ($tab !== "general") ? 'style="display: none;"' : "" ?>>
                <?php require_once(__DIR__ . "/views/tab-general.php"); ?>
            </div>
            <!-- locations -->
            <?php
            ?>
            <!-- search -->
            <div class="settings-tab search-tab" <?php echo ($tab !== "search") ? 'style="display: none;"' : "" ?>>
                <?php require_once(__DIR__ . "/views/tab-search.php"); ?>
            </div>
            <!-- products -->
            <div class="settings-tab products-tab" <?php echo ($tab !== "products") ? 'style="display: none;"' : "" ?>>
                <?php require_once(__DIR__ . "/views/tab-products.php"); ?>
            </div>
            <?php
            ?>
            <!-- orders -->
            <div class="settings-tab orders-tab" <?php echo ($tab !== "orders") ? 'style="display: none;"' : "" ?>>
                <?php require_once(__DIR__ . "/views/tab-orders.php"); ?>
            </div>
            <?php
            ?>
            <!-- fields -->
            <div class="settings-tab fields-tab" <?php echo ($tab !== "fields") ? 'style="display: none;"' : "" ?>>
                <?php require_once(__DIR__ . "/views/tab-fields.php"); ?>
            </div>
            <?php
            ?>
            <!-- permissions -->
            <div class="settings-tab permissions-tab" <?php echo ($tab !== "permissions") ? 'style="display: none;"' : "" ?>>
                <?php require_once(__DIR__ . "/views/tab-permissions.php"); ?>
            </div>
            <?php
            ?>
            <!-- plugins -->
            <div class="settings-tab plugins-tab" <?php echo ($tab !== "plugins") ? 'style="display: none;"' : "" ?>>
                <?php require_once(__DIR__ . "/views/tab-plugins.php"); ?>
            </div>
            <!-- license -->
            <div class="settings-tab license-tab" <?php echo ($tab !== "license") ? 'style="display: none;"' : "" ?>>
                <?php require_once(__DIR__ . "/views/tab-license.php"); ?>
            </div>
            <?php
            ?>
            <!-- app -->
            <div class="settings-tab app-tab" <?php echo ($tab !== "app") ? 'style="display: none;"' : "" ?>>
                <?php require_once(__DIR__ . "/views/tab-app.php"); ?>
            </div>
            <?php
            ?>

            <?php
            ?>
            <!-- CSS -->
            <div class="settings-tab css-tab" <?php echo ($tab !== "css") ? 'style="display: none;"' : "" ?>>
                <?php require_once(__DIR__ . "/views/tab-other.php"); ?>
            </div>
            <?php
            ?>

            <!-- custom tabs -->
            <?php foreach ($customTabs as $index => $customTab) : ?>
                <?php $slug = isset($customTab["slug"]) && $customTab["slug"] ? $customTab["slug"] : "tab-slug-" . $index; ?>
                <div class="settings-tab <?php echo $slug; ?>-tab" <?php echo ($tab !== $slug) ? 'style="display: none;"' : "" ?>>
                    <?php if (file_exists($customTab["viewPath"])) {
                        require($customTab["viewPath"]);
                    } else {
                        echo '"viewPath" ' . __("is incorrect", "us-barcode-scanner");
                    } ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div id="barcode-scanner-preloader">
    <span class="a4b-preloader-icon"></span>
    <style>
        #barcode-scanner-preloader {
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100vw;
            height: 100vh;
            z-index: 9000;
            font-size: 14px;
            background: rgba(0, 0, 0, 0.3);
            transition: opacity 0.3s ease 0s;
            transform: translate3d(0px, 0px, 0px);
        }

        #barcode-scanner-preloader .a4b-preloader-icon {
            position: relative;
            top: 50%;
            left: 50%;
            color: #fff;
            border-radius: 50%;
            opacity: 1;
            width: 30px;
            height: 30px;
            border: 2px solid #f3f3f3;
            border-top: 3px solid #3498db;
            display: inline-block;
            animation: a4b-spin 1s linear infinite;
        }

        @keyframes a4b-spin {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>
</div>