<?php
new BFTOW_PRO_Keyboard;

class BFTOW_PRO_Keyboard
{
    public function __construct()
    {
        add_filter('bftow_default_keyboard', array($this, 'add_buttons'), 100);
        add_action('bftow_get_tg_data', array($this, 'show_answer'));
    }

    function add_buttons($keyboard)
    {
        $bftow_settings = get_option('bftow_settings', array());
        if (!empty($bftow_settings['keyboard'])) {
            foreach ($bftow_settings['keyboard'] as $button) {
                if (empty($button['button_text'])) continue;
                $tg_button = [
                    'text' => $button['button_text']
                ];
                if(!empty($button['web_app_url'])){
                    $tg_button['web_app'] = [
                        'url' => $button['web_app_url']
                    ];
                }
                $keyboard[] = [
                    $tg_button
                ];
            }
        }
        return $keyboard;
    }

    function show_answer($tg_data)
    {
        $bftow_settings = get_option('bftow_settings', array());
        if (!empty($bftow_settings['keyboard'])) {
            foreach ($bftow_settings['keyboard'] as $button) {
                if (empty($button['button_text'])) continue;
                $button['button_text'] = trim($button['button_text']);
                if (!empty($tg_data['message']['text']) && ($tg_data['message']['text'] === $button['button_text'] || $tg_data['message']['text'] === $button['command']) && !empty($tg_data['message']['chat']['id'])) {
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
                    $message = preg_replace('/<!--(.|s)*?-->/', '', wp_kses($button['message_text'], $allowed_html));
                    if (!empty($button['message_image'])) {
                        $image_url = wp_get_attachment_image_url($button['message_image'], 'full');
                        if (!empty($image_url)) {
                            $send_data = [
                                'chat_id' => $tg_data['message']['chat']['id'],
                                'photo' => $image_url,
                                'caption' => substr($message, 0, 960),
                                'parse_mode' => 'html',
                            ];

                            BFTOW_Api::getInstance()->send_photo($send_data);
                        }
                    } else if (!empty($button['message_text'])) {
                        $send_data = [
                            'chat_id' => $tg_data['message']['chat']['id'],
                            'text' => $message,
                            'parse_mode' => 'html',
                        ];

                        BFTOW_Api::getInstance()->send_message('sendMessage', $send_data);
                    }
                }
            }
        }
    }
}
