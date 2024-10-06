<?php

new BFTOW_PRO_ALERTS_DB;

class BFTOW_PRO_ALERTS_DB
{

    private static $transient_name = 'bftow_alerts_db_v';

    public function __construct()
    {
        add_action('admin_init', array($this, 'check_db'));
    }

    static function get_transient()
    {
        return get_transient(self::$transient_name);
    }

    static function set_transient()
    {
        set_transient(self::$transient_name, BFTOW_DB_V);
    }

    function check_db()
    {

        $create_db = false;

        /*We dont have any db for now*/
        if (false === ($current_version = self::get_transient())) {
            $create_db = true;
        }

        /*Or we have current version lower than we need to*/
        if (version_compare(BFTOW_DB_V, $current_version, '>')) {
            $create_db = true;
        }

        /*We should create db then and update its version*/
        if ($create_db) {
            self::create_db();
            self::set_transient();
        }

    }

    static private function table_name()
    {
        global $wpdb;
        return $wpdb->prefix . 'bftow_alerts';
    }

    static private function create_db()
    {

        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();

        //* Create the teams table
        $table_name = self::table_name();
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time bigint(20) NOT NULL DEFAULT '0',
            text longtext NOT NULL,
            image mediumint(9) DEFAULT '0' NOT NULL,
            total_users bigint(20) NOT NULL DEFAULT '0',
            current_users bigint(20) NOT NULL DEFAULT '0',
            PRIMARY KEY  (id)
         ) $charset_collate;";
        dbDelta($sql);

    }

    static function create($message, $image_id, $total = 0)
    {
        global $wpdb;
        $create = $wpdb->insert(self::table_name(), array(
            "time" => time(),
            "text" => $message,
            "image" => $image_id,
            "total_users" => $total
        ));

        return $create;
    }

    static function get_records($fields = '*')
    {
        global $wpdb;
        $table_name = self::table_name();
        $results = $wpdb->get_results("SELECT {$fields} FROM {$table_name} ORDER BY time DESC LIMIT 50 ", ARRAY_A);
        return (array)self::prepare_results($results);
    }

    static function get_record($record_id, $fields = '*')
    {
        global $wpdb;
        $table_name = self::table_name();
        $results = $wpdb->get_results("SELECT {$fields} FROM {$table_name} WHERE id = {$record_id} ORDER BY time DESC LIMIT 50 ", ARRAY_A);
        return (array)self::prepare_results($results);
    }

    static function prepare_results($results)
    {


        $d = array();

        foreach ($results as $key => $result) {


            $single = array(
                'id' => $result['id'],
                'message' => $result['text'],
                'time' => date_i18n('F j, Y, g:i a', $result['time']),
                'total_users' => $result['total_users'],
                'current_users' => $result['current_users'],
            );

            $image = array(
                'id' => 0,
                'url' => ''
            );

            if (!empty($result['image'])) {
                $url = wp_get_attachment_image_src($result['image'], 'full');
                $image = array(
                    'id' => $result['image'],
                    'url' => $url['0']
                );
            }

            $single['image'] = $image;

            $d[] = $single;

        }

        return $d;
    }

    static function update($record_id, $data)
    {
        global $wpdb;

        $upd = $wpdb->update(
            self::table_name(),
            $data,
            array(
                'id' => $record_id
            )
        );


    }

}