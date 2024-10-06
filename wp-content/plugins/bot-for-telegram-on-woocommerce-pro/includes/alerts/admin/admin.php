<?php

new BFTOW_PRO_ALERTS_ADMIN;

class BFTOW_PRO_ALERTS_ADMIN
{

    public function __construct()
    {

        add_action('admin_menu', array($this, 'add_page'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

        add_action('wp_ajax_bftow_pro_create_new_record', array($this, 'create_record'));
        add_action('wp_ajax_bftow_pro_send_single_bulk_message', array($this, 'send_message'));
    }

    function add_page()
    {
        add_menu_page(
            esc_html__('Bulk bot mailing', 'bftow-pro'),
            'Bot mailing',
            'manage_options',
            'bftow_bulk_alerts',
            array($this, 'page_view'),
            BFTOW_PRO_URL . 'assets/images/icon.png',
            100
        );
    }

    function admin_scripts($hook)
    {
        if ($hook === 'toplevel_page_bftow_bulk_alerts') {
            wp_enqueue_media();
            wp_enqueue_style(
                'milligram',
                BFTOW_PRO_URL . '/assets/vendors/milligram.min.css',
                false,
                '1.0.0'
            );
            wp_enqueue_style(
                'bftow_bulk_alerts',
                BFTOW_URL . '/assets/css/bulk_alerts.css',
                false,
                '1.0.0'
            );
            wp_enqueue_script(
                'vue2.js',
                BFTOW_PRO_URL . '/assets/vendors/vue.min.js',
                null,
                '1.0.0'
            );
            wp_enqueue_script(
                'vue-resource.js',
                BFTOW_PRO_URL . '/assets/vendors/vue-resource.js',
                null,
                '1.0.0'
            );
            wp_enqueue_script(
                'bftow_bulk_alerts',
                BFTOW_PRO_URL . '/assets/js/bulk_alerts.js',
                array('vue2.js', 'vue-resource.js'),
                '1.0.3'
            );
            wp_localize_script('bftow_bulk_alerts', 'bftow_pro_bulk_alerts', array(
                'translations' => array(
                    'creating' => esc_html__('Creating new record', 'bftow'),
                    'sending' => esc_html__('Sending message to user... Do not reload the page.', 'bftow'),
                ),
                'ajax_url' => esc_url(admin_url('admin-ajax.php')),
                'records' => BFTOW_PRO_ALERTS_DB::get_records()
            ));
        }
        wp_enqueue_style(
            'bftow_pro_admin_style',
            BFTOW_PRO_URL . '/assets/admin/admin.css',
            false,
            '1.0.1'
        );
    }

    function page_view()
    {
        self::include_template('main');
    }

    static function include_template($view)
    {
        require_once BFTOW_PRO_DIR . "/includes/alerts/admin/views/{$view}.php";
    }

    function create_record()
    {
        $r = array(
            'error' => false,
            'message' => esc_html__('Record created', 'bftow')
        );
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['image_id'])) $image = 0;
        $image = intval($data['image_id']);
        $allowed_html = array(
            'a' => array(
                'href' => true,
                'title' => true,
            ),
            'b' => array(),
            'strong' => array(),
            'em' => array(),
            'i' => array(),
            's' => array(),
            'strike' => array(),
            'del' => array(),
            'pre' => array(
                'language' => true
            )
        );
        $title = preg_replace('/<!--(.|s)*?-->/', '', wp_kses($data['message'], $allowed_html));

        $users = new WP_User_Query(array(
            'meta_key' => 'bftow_user_system_id',
            'meta_compare' => 'EXISTS'
        ));

        $total = $users->get_total();

        $created = BFTOW_PRO_ALERTS_DB::create($title, $image, $total);

        if (!$created) {
            $r['message'] = esc_html__('Cant create record');
            $r['error'] = false;
        }

        $r['records'] = BFTOW_PRO_ALERTS_DB::get_records();

        wp_send_json($r);
    }

    function send_message($user_id_native = '')
    {

        $record_id = intval($_GET['record_id']);
        $r = array(
            'next' => false,
            'message' => esc_html__('All users notified', 'bftow-pro'),
            'records' => BFTOW_PRO_ALERTS_DB::get_records()
        );

        $users = new WP_User_Query(array(
            'number' => 1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'bftow_user_system_id',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => "bftow_bulk_send_{$record_id}",
                    'compare' => 'NOT EXISTS'
                ),
            )
        ));

        $total = $users->get_total();

        if (!$total) wp_send_json($r);

        $users = $users->get_results();

        $user_id = $users[0]->ID;

//        $user_id = 125;

        //if(!empty($user_id_native)) $user_id = $user_id_native;

        $record = BFTOW_PRO_ALERTS_DB::get_record($record_id);
        $record = $record[0];
        $current_sent = (int)$record['current_users'];

        $send_message = BFTOW_Api::getInstance()->bftow_send_message_to_user(
            $user_id,
            $record['message'],
            $record['image']['id'],
            'full'
        );

        if (!$send_message) {
            wp_send_json(array(
                'next' => true,
                'message' => esc_html__('Coudn\'t send message. Skipping...', 'bftow-pro'),
                'records' => BFTOW_PRO_ALERTS_DB::get_records(),
            ));
        }

        BFTOW_PRO_ALERTS_DB::update($record_id, array(
            'current_users' => $current_sent + 1
        ));

        update_user_meta($user_id, "bftow_bulk_send_{$record_id}", 1);

        wp_send_json(array(
            'next' => true,
            'total_left' => $total,
            'message' => esc_html__('Message sent. Sending next one.', 'bftow-pro'),
            'records' => BFTOW_PRO_ALERTS_DB::get_records()
        ));

    }

}
