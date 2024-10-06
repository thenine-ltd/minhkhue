<?php
new BFTOW_PRO_Search;
class BFTOW_PRO_Search {
    private $search_button_text;
    private $text_after_search;
    private $not_found_text;
    private $search_result_button_text;
    private $query;
    private $show_out_of_stock;

    function __construct()
    {
        $this->search_button_text = trim(bftow_get_option('bftow_search_button_text', esc_html__('Search', 'bot-for-telegram-on-woocommerce-pro')));
        $this->text_after_search = bftow_get_option('bftow_text_after_search', esc_html__('Enter a search word', 'bot-for-telegram-on-woocommerce-pro'));
        $this->not_found_text = bftow_get_option('bftow_not_found_text', esc_html__('Products not found', 'bot-for-telegram-on-woocommerce-pro'));
        $this->search_result_button_text = trim(bftow_get_option('bftow_search_result_button_text', esc_html__('Show results', 'bot-for-telegram-on-woocommerce-pro')));
        $this->show_out_of_stock = bftow_get_option('bftow_show_out_of_stock', false);
        add_action('bftow_get_tg_data', array($this, 'show_search_result'));
        add_filter('bftow_default_keyboard', array($this, 'add_button'));
    }

    function show_search_result($tg_data)
    {
        if(!empty($tg_data['message']['chat']['id'])) {
            $chat_id = $tg_data['message']['chat']['id'];
            if(!empty($tg_data['message']['text']) && $tg_data['message']['text'] === $this->search_button_text){
                BFTOW_Api::getInstance()->delete_message(
                    array(
                        'chat_id' => $chat_id,
                        'message_id' => $tg_data['message']['message_id']
                    )
                );
                $send_data = array(
                    'text' => $this->text_after_search,
                    'chat_id' => $chat_id,
                );
                BFTOW_Api::getInstance()->send_message('sendMessage', $send_data);
            }
            if(!empty(get_transient('bftow_get_last_user_action_' . $chat_id))){

                $previous_user_action = get_transient('bftow_get_last_user_action_' . $chat_id);

                if(!empty($previous_user_action['message']['text']) && $previous_user_action['message']['text'] === $this->search_button_text && !empty($tg_data['message']['text'])) {
                    $this->query = $tg_data['message']['text'];

                    $posts_count = $this->get_searched_products_count($this->query);
                    $keyboard = [
                        [
                            [
                                'text' => esc_html__('Show Results', 'bot-for-telegram-on-woocommerce-pro'),
                                'switch_inline_query_current_chat' => 'bftow_search_' . $this->query
                            ]
                        ]
                    ];
                    if(!empty($posts_count)){
                        $message = sprintf(esc_html__('Found %s products', 'bot-for-telegram-on-woocommerce-pro'), $posts_count);
                    }
                    else {
                        $message = $this->not_found_text;
                        $keyboard = [];
                    }

                    $send_data = array(
                        'text' => $message,
                        'chat_id' => $chat_id,
                        'reply_markup' => json_encode([
                            'resize_keyboard' => true,
                            'inline_keyboard' => $keyboard,
                        ])
                    );
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
    }

    function get_searched_products_count($search) {
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            's' => $search,
            'meta_query' => [
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'bftow_hide_product',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key' => 'bftow_hide_product',
                        'value' => 'on',
                        'compare' => '!='
                    )
                ),
            ]
        ];

        if(empty($this->show_out_of_stock)) {
            $args['meta_query'][] = array(
                'key' => '_stock_status',
                'value' => 'instock'
            );
        }

        $q = new WP_Query($args);
        return $q->found_posts;
    }

    function add_button($keyboard)
    {
        $keyboard[] = [
            [
                'text' => $this->search_button_text,
            ],
        ];
        return $keyboard;
    }
}