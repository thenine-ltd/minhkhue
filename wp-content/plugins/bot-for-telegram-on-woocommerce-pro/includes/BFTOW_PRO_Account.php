<?php
new BFTOW_PRO_Account;

class BFTOW_PRO_Account
{
    public static $account_button;
    public static $order_button;
    public static $update_phone_button;
    public static $update_location_button;
    public static $main_menu_button;
    public static $account_message;
    public static $info_updated;
    public static $hide_account_button;
    private static $instance = [];

    public function __construct()
    {
        self::$account_button = bftow_get_option('bftow_account_button_text', esc_html__('My account', 'bot-for-telegram-on-woocommerce-pro'));
        self::$order_button = bftow_get_option('bftow_orders_button_text', esc_html__('My orders', 'bot-for-telegram-on-woocommerce-pro'));
        self::$update_phone_button = bftow_get_option('bftow_update_phone_button_text', esc_html__('Update phone', 'bot-for-telegram-on-woocommerce-pro'));
        self::$update_location_button = bftow_get_option('bftow_update_location_button_text', esc_html__('Update location', 'bot-for-telegram-on-woocommerce-pro'));
        self::$main_menu_button = bftow_get_option('bftow_main_menu_button_text', esc_html__('Main menu', 'bot-for-telegram-on-woocommerce-pro'));
        self::$account_message = bftow_get_option('bftow_account_message', esc_html__('My account:', 'bot-for-telegram-on-woocommerce-pro'));
        self::$info_updated = bftow_get_option('bftow_info_updated', esc_html__('Information updated', 'bot-for-telegram-on-woocommerce-pro'));
        self::$hide_account_button = bftow_get_option('bftow_hide_account_button', false);
        add_filter('bftow_default_keyboard', array($this, 'add_button'));
        add_action('bftow_get_tg_data', array($this, 'show_account'));
    }

    static public function getInstance()
    {
        self::$instance = new self();

        return self::$instance;
    }

    public function account_keyboard()
    {
        $keyboard = [
            [
                [
                    'text' => self::$order_button,
                ],
                [
                    'text' => self::$update_phone_button,
                    'request_contact' => true,
                ]
            ],
            [
                [
                    'text' => self::$update_location_button,
                    'request_location' => true,
                ],
                [
                    'text' => self::$main_menu_button,
                ]
            ],
        ];

        return apply_filters('bftow_pro_account_keyboard', $keyboard);
    }

    function add_button($keyboard)
    {
        if(empty(self::$hide_account_button)) {
            $keyboard[] = [
                [
                    'text' => self::$account_button,
                ],
            ];
        }
        return $keyboard;
    }

    function show_account($tg_data)
    {
        if(!empty($tg_data['message']['text']) && $tg_data['message']['text'] === self::$account_button && !empty($tg_data['message']['chat']['id'])){
            $chat_id = $tg_data['message']['chat']['id'];
            $send_data = [
                'text' => self::$account_message,
                'chat_id' => $chat_id,
                'reply_markup' => json_encode([
                    'resize_keyboard' => true,
                    'keyboard' => $this->account_keyboard(),
                ])
            ];
            BFTOW_Api::getInstance()->delete_message(
                array(
                    'chat_id' => $chat_id,
                    'message_id' => $tg_data['message']['message_id']
                )
            );
            BFTOW_Api::getInstance()->send_message('sendMessage', $send_data);
        }
        else if(!empty($tg_data['message']['text']) && $tg_data['message']['text'] === self::$main_menu_button){
            $chat_id = $tg_data['message']['chat']['id'];
            $telegram = new BFTOW_Telegram;
            $send_data = [
                'text' => self::$main_menu_button,
                'chat_id' => $chat_id,
                'reply_markup' => json_encode([
                    'resize_keyboard' => true,
                    'keyboard' => $telegram->bftow_get_default_keyboard(),
                ])
            ];
            BFTOW_Api::getInstance()->delete_message(
                array(
                    'chat_id' => $chat_id,
                    'message_id' => $tg_data['message']['message_id']
                )
            );
            BFTOW_Api::getInstance()->send_message('sendMessage', $send_data);
        }
    }
}
