<?php
new BFTOW_PRO_Hooks;
class BFTOW_PRO_Hooks {
    function __construct()
    {
        add_filter('bftow_get_categories_args', [$this, 'get_categories']);
        add_filter('bftow_categories_keyboard', [$this, 'categories_keyboard']);
    }

    function categories_keyboard($keyboard)
    {
        $categories = bftow_get_option('bftow_product_category_buttons', []);
        if(!empty($categories)) {
            $custom_categories_buttons = [];
            foreach ($categories as $category) {
                if(!empty($category['category_row'])) {
                    $category_row = [];
                    foreach ($category['category_row'] as $row) {
                        if(!empty($row['category'])) {
                            $category_data = explode(';', $row['category']);
                            $slug = !empty($category_data[0]) ? $category_data[0] : '';
                            $text = !empty($category_data[1]) ? $category_data[1] : '';
                            $category_row[] = [
                                'text' => $text,
                                'switch_inline_query_current_chat' => urldecode($slug),
                            ];
                        }
                    }
                    $custom_categories_buttons[] = $category_row;
                }
            }
            if(!empty($custom_categories_buttons)) {
                return $custom_categories_buttons;
            }
        }

        return $keyboard;
    }

    function get_categories($args)
    {
        $categories = bftow_get_option('bftow_product_categories', []);

        if(!empty($categories)){
            if(isset($args['parent'])) {
                unset($args['parent']);
            }
            $args['include'] = $categories;
        }

        return $args;
    }
}