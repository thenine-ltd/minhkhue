<?php

new BFTOW_Orders_PRO();

class BFTOW_Orders_PRO
{

    private $token;
    private $user_id;
    static $orders_per_page = 10;
    public $offset = 0;
    public $no_orders_found;
    public $order_button_text;
    public $your_orders;

    public function __construct()
    {
        $this->no_orders_found = bftow_get_option('bftow_no_orders_found', esc_html__('No orders found', 'bot-for-telegram-on-woocommerce-pro'));
        $this->order_button_text = bftow_get_option('bftow_orders_button_text', esc_html__('My orders', 'bot-for-telegram-on-woocommerce-pro'));
        $this->your_orders = bftow_get_option('bftow_your_orders', esc_html__('Your Orders', 'bot-for-telegram-on-woocommerce-pro'));
        add_action('rest_api_init', array($this, 'register_route'));
        add_action('bftow_get_tg_data', array($this, 'show_orders'));
        add_action('bftow_get_tg_data', array($this, 'checkout'));
    }

    function register_route()
    {
        register_rest_route('woo-telegram/v1', '/orders/', array(
            'methods' => ['POST'],
            'callback' => array($this, 'get_orders'),
            'permission_callback' => '__return_true',
        ));

        register_rest_route('woo-telegram/v1', '/create-order/', array(
            'methods' => ['POST'],
            'callback' => array($this, 'create_order'),
            'permission_callback' => '__return_true',
        ));
    }

    function get_orders($request)
    {

        if (!empty($_POST['page'])) {
            $page = intval($_POST['page']);

            $this->offset = ($page * self::$orders_per_page) - self::$orders_per_page;
        }

        if (empty($_POST['bftow_token'])) self::error('empty_token', 'Token not found', 404);

        $this->token = sanitize_text_field($_POST['bftow_token']);

        $user = BFTOW_WooCommerce::find_user_by_token($this->token);

        if (empty($user)) self::error('go_away', 'Unauthorized', 403);

        $this->user_id = $user[0]->ID;

        $args = array(
            'customer_id' => $this->user_id,
            'limit' => self::$orders_per_page,
            'offset' => $this->offset
        );

        $orders_result = wc_get_orders($args);

        $orders = array();

        if (!empty($orders_result)) {
            foreach ($orders_result as $order) {

                $id = $order->get_ID();

                $orders[] = array(
                    'date' => $order->get_date_created(),
                    'status' => $order->get_status(),
                    'payment_title' => $order->get_payment_method_title(),
                    'total' => $order->get_total(),
                    'id' => $id,
                );
            }
        }

        return $orders;
    }

    static function error($code, $message, $status)
    {
        wp_send_json(new WP_Error($code, $message, array('status' => $status)));
    }

    function checkout($tg_data)
    {
        if (!empty($tg_data['callback_query'])) {
            $callback_query = $tg_data['callback_query'];
            $chat_id = $callback_query['message']['chat']['id'];
            if (!empty($callback_query['data'])) {
                $user = new BFTOW_User();
                $data = json_decode($callback_query['data'], true);
                if (!empty($data['action']) && $data['action'] === 'create_order') {
                    $product_id = $data['prd_id'] ? intval($data['prd_id']) : '';
                    $quantity = $data['qnt'] ? intval($data['qnt']) : 1;
                    $orders_data = wp_remote_post(get_site_url() . '/wp-json/woo-telegram/v1/create-order',
                        array(
                            'timeout' => 45,
                            'method' => 'POST',
                            'redirection' => 5,
                            'httpversion' => '1.1',
                            'headers' => array(),
                            'body' => array(
                                'bftow_token' => $user->bftow_get_user_token($chat_id),
                                'bftow_product_id' => $product_id,
                                'bftow_quantity' => $quantity,
                            )
                        )
                    );
                    $body = wp_remote_retrieve_body($orders_data);
                    if($body) {
                        BFTOW_Orders::delete_transient($chat_id);
                    }
                }
            }
        }
    }

    function show_orders($tg_data)
    {
        if (!empty($tg_data['message']['text']) && $tg_data['message']['text'] === $this->order_button_text && !empty($tg_data['message']['chat']['id'])) {
            $chat_id = $tg_data['message']['chat']['id'];
            $user = new BFTOW_User();
            $orders_data = wp_remote_post(get_site_url() . '/wp-json/woo-telegram/v1/orders',
                array(
                    'timeout' => 45,
                    'method' => 'POST',
                    'redirection' => 5,
                    'httpversion' => '1.1',
                    'headers' => array(),
                    'body' => array(
                        'bftow_token' => $user->bftow_get_user_token($chat_id)
                    )
                )
            );
            $body = wp_remote_retrieve_body($orders_data);
            $orders = json_decode($body);
            $r = array();
            if (empty($orders->errors)) {
                foreach ($orders as $order) {
                    $r[] = array(
                        'id' => $order->id,
                        'total' => BFTOW_Products::bftow_get_price_format($order->total),
                        'date' => date('Y/m/d H:i', strtotime($order->date->date))
                    );
                }
            }
            if (!empty($r)) {
                $keyboard = [];
                foreach ($r as $order) {
                    $keyboard[] = [
                        [
                            'text' => '#' . $order['id'] . ' ' . $order['date'] . ' ' . $order['total'],
                            'callback_data' => json_encode([
                                'action' => 'show_order',
                                'order_id' => $order['id'],
                            ]),
                        ]
                    ];
                }
                $send_data = [
                    'text' => $this->your_orders,
                    'chat_id' => $chat_id,
                    'reply_markup' => json_encode([
                        'resize_keyboard' => true,
                        'inline_keyboard' => $keyboard,
                    ])
                ];
            } else {
                $send_data = [
                    'text' => $this->no_orders_found,
                    'chat_id' => $chat_id,
                ];
            }

            BFTOW_Api::getInstance()->delete_message(
                array(
                    'chat_id' => $chat_id,
                    'message_id' => $tg_data['message']['message_id']
                )
            );

            BFTOW_Api::getInstance()->send_message('sendMessage', $send_data);
        } else if (!empty($tg_data['callback_query']['data']) && !empty($tg_data['callback_query']['message']['chat']['id'])) {
            $data = json_decode($tg_data['callback_query']['data'], true);
            if (!empty($data['action']) && $data['action'] === 'show_order' && !empty($data['order_id'])) {
                $order_id = intval($data['order_id']);
                $chat_id = $tg_data['callback_query']['message']['chat']['id'];
                $message = BFTOW_Orders::get_order_data($order_id);
                if (!empty($message)) {
                    $send_data = [
                        'text' => $message,
                        'chat_id' => $chat_id,
                        'parse_mode' => 'html',
                        'reply_markup' => BFTOW_PRO_Account::getInstance()->account_keyboard()
                    ];
                    BFTOW_Api::getInstance()->send_message('sendMessage', $send_data);
                    BFTOW_Api::getInstance()->send_message('answerCallbackQuery', array(
                        'callback_query_id' => $tg_data['callback_query']['id']
                    ));
                }
            }
        }
    }

    function create_order($request)
    {
        if (empty($_POST['bftow_token'])) self::error('empty_token', 'Token not found', 404);

        $this->token = sanitize_text_field($_POST['bftow_token']);

        $user = BFTOW_WooCommerce::find_user_by_token($this->token);

        if (empty($user)) self::error('go_away', 'Unauthorized', 403);

        $user_id = $user[0]->ID;

        $order = wc_create_order(array('customer_id' => $user_id));

        $chat_id = BFTOW_User::bftow_get_user_tg_chat_id($user_id);

        if(!empty($_POST['bftow_product_id'])) {
            $quantity = !empty($_POST['bftow_quantity']) ? intval($_POST['bftow_quantity']) : 1;
            $order->add_product(wc_get_product(intval($_POST['bftow_product_id'])), $quantity);
        }
        else {
            $buyer_cart = BFTOW_Orders::bftow_get_cart_transient($chat_id);

            if (empty($buyer_cart)) return false;

            foreach ($buyer_cart as $id => $product) {
                if (empty($product['product_id']) || !is_int($product['product_id'])) continue;
                if (!empty($product['variations'])) {
                    foreach ($product['variations'] as $varId => $varData) {
                        $order->add_product(wc_get_product($varData['product_id']), $varData['quantity']);
                    }
                } else {
                    $order->add_product(wc_get_product($product['product_id']), $product['quantity']);
                }
            }
        }

        $fields = [
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_country',
            'billing_state',
            'billing_postcode'
        ];
        $address = [];
        foreach ($fields as $field){
            $value = get_user_meta($user_id, $field, true);
            if(!empty($value)){
                $address[str_replace('billing_', '', $field)] = $value;
            }
        }
        if(!empty($address)){
            $order->set_billing_address($address);
            $order->set_shipping($address);
        }
        $order->calculate_totals();
        $order->update_status('processing');

        return true;
    }
}
