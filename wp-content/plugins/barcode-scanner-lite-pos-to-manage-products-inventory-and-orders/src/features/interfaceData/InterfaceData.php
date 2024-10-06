<?php

namespace UkrSolution\BarcodeScanner\features\interfaceData;

use UkrSolution\BarcodeScanner\Database;

class InterfaceData
{
    public static $filter_dropdown_options = 'scanner_dropdown_%field_options';

    private static $allFields = array();
    private static $plugin = "";

    public static function getFields($addTranslations = false, $plugin = "", $isReload = false)
    {
        global $wpdb;

        $orderKey = $plugin == "mobile" ? "order_mobile" : "order";

        if (self::$allFields && self::$plugin == $plugin && !$isReload) {
            return self::$allFields;
        }

        $table = $wpdb->prefix . Database::$interface;

        if ($plugin == "mobile") {
            $orderField = "order_mobile";
        } else {
            $orderField = "order";
        }

        $fields = $wpdb->get_results("SELECT * FROM {$table} ORDER BY `" . $orderField . "` DESC", ARRAY_A);


        foreach ($fields as &$value) {
            if ($addTranslations && isset($value["field_label"])) {
                $value["field_label"] = __($value["field_label"], 'us-barcode-scanner');
            }

            if ($value["type"] != "select") {
                continue;
            }

            $options = $value["options"] ? @json_decode($value["options"], false) : array();

            $filterName = str_replace("%field", $value["field_name"], self::$filter_dropdown_options);
            $filteredOptions = apply_filters($filterName, (array)$options, $value["field_name"]);
            $value["options"] = json_encode($filteredOptions);
        }

        self::$allFields = $fields ? $fields : array();
        self::$plugin = $plugin;

        usort(self::$allFields, function ($a, $b) use ($orderKey) {
            return $a[$orderKey] && $b[$orderKey] && $a[$orderKey] < $b[$orderKey] ? 1 : 0;
        });

        return self::$allFields;
    }

    public static function getField($fieldName)
    {
        global $wpdb;

        $table = $wpdb->prefix . Database::$interface;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE `field_name` = '%s';", $fieldName), ARRAY_A);
    }

    public static function saveFields($fields)
    {
        global $wpdb;

        $table = $wpdb->prefix . Database::$interface;
        $dt = new \DateTime("now");
        $created = $dt->format("Y-m-d H:i:s");

        foreach ($fields as $id => $value) {
            $data = array(
                "status" => $value["status"],
                "field_label" => $value["field_label"],
                "field_name" => $value["field_name"],
                "type" => $value["type"],
                "label_position" => $value["label_position"],
                "field_height" => $value["field_height"],
                "label_width" => $value["label_width"],
                "show_in_create_order" => $value["show_in_create_order"],
                "show_in_products_list" => $value["show_in_products_list"],
                "updated" => $created,
            );

            $options = array();

            if (isset($value["options"]) && is_array($value["options"])) {
                foreach ($value["options"] as $option) {
                    $options[$option["key"]] = $option["value"];
                }

                $data["options"] = json_encode($options);
            }


            if (isset($value["order_mobile"])) {
                $data["order_mobile"] = $value["order_mobile"];
            } else {
                $data["position"] = $value["position"];
                $data["order"] = $value["order"];
            }

            if ($value["remove"] == 1) {
                $wpdb->delete($table, array("id" => $id));
            } else if (preg_match("/^[0-9]+$/", $id, $m)) {
                $wpdb->update($table, $data, array("id" => $id));
            } else {
                if (!isset($data["position"])) {
                    $data["position"] = "product-middle-left";
                }

                $wpdb->insert($table, $data);
            }
        }

        self::generateFieldsTranslationsFile($fields);
    }

    public static function getFieldForAutoAction()
    {
        global $wpdb;

        $table = $wpdb->prefix . Database::$interface;
        return $wpdb->get_row("SELECT * FROM {$table} WHERE `use_for_auto_action` = '1';");
    }

    public static function generateFieldsTranslationsFile($fields)
    {
        try {
            $filePath = USBS_PLUGIN_BASE_PATH . 'texts-for-translator-plugins.php';

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $file = fopen($filePath, 'a');

            if (file_exists($filePath)) { 
                fwrite($file, "<?php \n\n");

                foreach ($fields as $value) {
                    $str = (isset($value["field_label"]) && $value["field_label"]) ? $value["field_label"] : "";

                    if (isset($value["field_label"]) && $value["field_label"]) {
                        $label = addslashes($value["field_label"]);
                        fwrite($file, "__('{$label}', 'us-barcode-scanner');\n");
                    }
                }

                fclose($file);
            }
        } catch (\Throwable $th) {
        }
    }
}
