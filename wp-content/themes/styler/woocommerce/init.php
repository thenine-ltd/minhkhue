<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( class_exists( 'Redux' ) ) {

    if ( ! function_exists( 'styler_dynamic_section' ) ) {
        function styler_dynamic_section($sections)
        {
            global $styler_pre;

            $el_args = array(
                'post_type'      => 'elementor_library',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'elementor_library_type',
                        'field'    => 'slug',
                        'terms'    => 'section'
                    )
                )
            );

            // create sections in the theme options
            $sections[] = array(
                'title' => esc_html__('WOOCOMMERCE', 'styler'),
                'id' => 'woogeneralsection',
                'icon' => 'el el-shopping-cart-sign',
            );
            // SHOP PAGE SECTION
            $sections[] = array(
                'title' => esc_html__( 'Ajax', 'styler' ),
                'id' => 'wooajaxsubsection',
                'subsection'=> true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Ajax Shop', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_ajax_filter',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Ajax Login/Register', 'styler'),
                        'customizer' => true,
                        'id' => 'wc_ajax_login_register',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Product Page Ajax Add To Cart', 'styler'),
                        'customizer' => true,
                        'id' => 'product_ajax_addtocart',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Ajax Add To Cart ( for Simle Products )', 'styler'),
                        'customizer' => true,
                        'id' => 'ajax_addtocart',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Ajax Add To Cart ( for Variable and Grouped Products )', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_shop_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Popup Overlay Color', 'styler'),
                        'subtitle' => esc_html__('Change quick view overlay color.', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_shop_overlaycolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.mfp-bg.mfp-styler-quickshop'),
                        'required' => array( 'quick_shop_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Content Background Color', 'styler'),
                        'subtitle' => esc_html__('Change quick view background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_shop_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-quickshop-wrapper'),
                        'required' => array( 'quick_shop_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Content Border', 'styler'),
                        'subtitle' => esc_html__('Set your custom border styles for the posts.', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_shop_brd',
                        'type' => 'border',
                        'all' => false,
                        'output' => array('.styler-quickshop-wrapper'),
                        'required' => array( 'quick_shop_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Content Padding', 'styler'),
                        'subtitle' => esc_html__('You can set the spacing of the site shop page post.', 'styler'),
                        'customizer' => true,
                        'id' =>'quick_shop_pad',
                        'type' => 'spacing',
                        'output' => array('.styler-quickshop-wrapper'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false',
                        'default' => array(
                            'units' => 'px'
                        ),
                        'required' => array( 'quick_shop_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Max Width', 'styler' ),
                        'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'styler' ),
                        'customizer' => true,
                        'id' => 'quick_shop_width',
                        'type' => 'slider',
                        'default' => '',
                        'min' => 0,
                        'step' => 1,
                        'max' => 4000,
                        'display_value' => 'text',
                        'required' => array( 'quick_shop_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Max Width Responsive ( min-width 768px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'styler' ),
                        'customizer' => true,
                        'id' => 'quick_shop_width_sm',
                        'type' => 'slider',
                        'default' => '',
                        'min' => 0,
                        'step' => 1,
                        'max' => 1200,
                        'display_value' => 'text',
                        'required' => array( 'quick_shop_visibility', '=', '1' )
                    )
                )
            );
            //NEWSLETTER SETTINGS SUBSECTION
            $sections[] = array(
                'title' => esc_html__( 'Ajax Live Search', 'styler' ),
                'id' => 'themepopupsearchsubsection',
                'icon' => 'el el-cog',
                'subsection' => true,
                'fields' => array(
                    array(
                        'title' => esc_html__( 'Search Display', 'styler' ),
                        'customizer' => true,
                        'id' => 'ajax_search_visibility',
                        'type' => 'switch',
                        'default' => true
                    ),
                    array(
                        'title' => esc_html__( 'Search Type', 'styler' ),
                        'customizer' => true,
                        'id' => 'ajax_search_type',
                        'type' => 'select',
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'Default Search Form', 'styler' ),
                            'cats' => esc_html__( 'Category Search Form', 'styler' ),
                            'custom' => esc_html__( 'Custom ( Shortcodes provided by the plugins )', 'styler' ),
                        ),
                        'default' => 'cats',
                        'required' => array( 'ajax_search_visibility', '=', '1' )
                    ),
                    array(
                        'id' =>'ajax_search_shortcode',
                        'type' => 'text',
                        'title' => esc_html__('Shortcode', 'styler'),
                        'subtitle' => esc_html__('Maximum number of strings required to start the search', 'styler'),
                        'customizer' => true,
                        'default' => '',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'custom' )
                        )
                    ),
                    array(
                        'id' =>'ajax_search_menu_depth',
                        'type' => 'switch',
                        'title' => esc_html__('Sub-category', 'styler'),
                        'customizer' => true,
                        'default' => '1',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'id' =>'ajax_search_sku',
                        'type' => 'switch',
                        'title' => esc_html__('Search String by Product SKU', 'styler'),
                        'customizer' => true,
                        'default' => '1',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'id' =>'ajax_search_content',
                        'type' => 'switch',
                        'title' => esc_html__('Search String by Product Content', 'styler'),
                        'customizer' => true,
                        'default' => '1',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Max Strings', 'styler'),
                        'subtitle' => esc_html__('Maximum number of strings required to start the search', 'styler'),
                        'customizer' => true,
                        'id' => 'ajax_search_max_char',
                        'type' => 'slider',
                        'default' => 3,
                        'min' => 1,
                        'step' => 1,
                        'max' => 10,
                        'display_value' => 'text',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Time Out', 'styler'),
                        'subtitle' => esc_html__('Time to wait to start searching', 'styler'),
                        'customizer' => true,
                        'id' => 'ajax_search_time_out',
                        'type' => 'slider',
                        'default' => 1500,
                        'min' => 50,
                        'step' => 1,
                        'max' => 100000,
                        'display_value' => 'text',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'id' =>'ajax_search_result_img',
                        'type' => 'switch',
                        'title' => esc_html__('Search Result Image Display', 'styler'),
                        'customizer' => true,
                        'default' => '1',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Search Result Custom Image', 'styler' ),
                        'customizer' => true,
                        'id' => 'ajax_search_custom_img',
                        'type' => 'media',
                        'url' => true,
                        'customizer' => true,
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' ),
                            array( 'ajax_search_result_img', '=', '1' )
                        )
                    ),
                    array(
                        'id' =>'ajax_search_result_prc',
                        'type' => 'switch',
                        'title' => esc_html__('Search Result Price Display', 'styler'),
                        'customizer' => true,
                        'default' => '1',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'id' =>'ajax_search_result_stock',
                        'type' => 'switch',
                        'title' => esc_html__('Search Result Stock Display', 'styler'),
                        'customizer' => true,
                        'default' => '1',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'id' =>'ajax_search_result_btn',
                        'type' => 'switch',
                        'title' => esc_html__('Search Result Add To Cart Display', 'styler'),
                        'customizer' => true,
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Hide Empty Category', 'styler' ),
                        'customizer' => true,
                        'id' => 'ajax_cats_hide_empty',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Quick Links Display', 'styler' ),
                        'customizer' => true,
                        'id' => 'ajax_search_bottom_cats',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Quick Links Text', 'styler' ),
                        'subtitle' => sprintf('%s <code>%s</code>', esc_html__( 'Default text:', 'styler' ), 'Quick Links'),
                        'customizer' => true,
                        'id' => 'ajax_search_bottom_cats_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Category Select First Option Text', 'styler' ),
                        'subtitle' => sprintf('%s <code>%s</code>', esc_html__( 'Default text:', 'styler' ), 'Select a category'),
                        'customizer' => true,
                        'id' => 'ajax_search_cats_select_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Search Form Placeholder Text', 'styler' ),
                        'subtitle' => sprintf('%s <code>%s</code>', esc_html__( 'Default text:', 'styler' ), 'Search for product...'),
                        'customizer' => true,
                        'id' => 'ajax_search_placeholder_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'ajax_search_visibility', '=', '1' ),
                            array( 'ajax_search_type', '=', 'cats' )
                        )
                    )
                )
            );
            $sections[] = array(
                'title' => esc_html__('Shop Pages', 'styler'),
                'id' => 'shoppagessubsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'id' =>'myaccount_page_type',
                        'type' => 'button_set',
                        'title' => esc_html__('My Account Page Type', 'styler'),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop account page.', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'Default', 'styler' ),
                            'multisteps' => esc_html__( 'Multi Steps', 'styler' ),
                        ),
                        'default' => 'default'
                    ),
                    array(
                        'id' =>'checkout_enable_multistep',
                        'type' => 'button_set',
                        'title' => esc_html__('Checkout Page Type', 'styler'),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop checkout page.', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'Default', 'styler' ),
                            'multisteps' => esc_html__( 'Multi Steps', 'styler' )
                        ),
                        'default' => 'default'
                    )
                )
            );
            // SHOP PAGE SECTION
            $sections[] = array(
                'title' => esc_html__('Cross-Sells Products', 'styler'),
                'id' => 'singleshopcrosssells',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Cross-Sells Title', 'styler'),
                        'subtitle' => esc_html__('Add your cart page cross-sells section title here.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_title',
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'id' =>'shop_cross_sells_type',
                        'type' => 'button_set',
                        'title' => esc_html__('Cross-Sells Layout Type', 'styler'),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page cross-sells.', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'slider' => esc_html__( 'Slider', 'styler' ),
                            'grid' => esc_html__( 'Grid', 'styler' )
                        ),
                        'default' => 'slider'
                    ),
                    array(
                        'title' => esc_html__('Column', 'styler'),
                        'subtitle' => esc_html__('You can control cross-sells post column with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_colxl',
                        'type' => 'slider',
                        'default' => 3,
                        'min' => 1,
                        'step' => 1,
                        'max' => 5,
                        'display_value' => 'text',
                        'required' => array( 'shop_cross_sells_type', '=', 'grid' )
                    ),
                    array(
                        'title' => esc_html__('Column ( Desktop/Tablet )', 'styler'),
                        'subtitle' => esc_html__('You can control cross-sells post column for tablet device with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_collg',
                        'type' => 'slider',
                        'default' => 3,
                        'min' => 1,
                        'step' => 1,
                        'max' => 4,
                        'display_value' => 'text',
                        'required' => array( 'shop_cross_sells_type', '=', 'grid' )
                    ),
                    array(
                        'title' => esc_html__('Column ( Tablet )', 'styler'),
                        'subtitle' => esc_html__('You can control cross-sells post column for phone device with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_colsm',
                        'type' => 'slider',
                        'default' => 2,
                        'min' => 1,
                        'step' => 1,
                        'max' => 3,
                        'display_value' => 'text',
                        'required' => array( 'shop_cross_sells_type', '=', 'grid' )
                    ),
                    array(
                        'title' => esc_html__('Column ( Phone )', 'styler'),
                        'subtitle' => esc_html__('You can control cross-sells post column for phone device with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_colxs',
                        'type' => 'slider',
                        'default' => 2,
                        'min' => 1,
                        'step' => 1,
                        'max' => 2,
                        'display_value' => 'text',
                        'required' => array( 'shop_cross_sells_type', '=', 'grid' )
                    ),
                    array(
                        'id' => 'shop_cross_sells_section_slider_start',
                        'type' => 'section',
                        'title' => esc_html__('Cross-Sells Slider Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Perview ( Min 1024px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can control cross-sells post slider item count for big device with this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_perview',
                        'type' => 'slider',
                        'default' => 5,
                        'min' => 1,
                        'step' => 1,
                        'max' => 10,
                        'display_value' => 'text',
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Perview ( Min 768px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can control cross-sells post slider item count for big device with this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_mdperview',
                        'type' => 'slider',
                        'default' => 3,
                        'min' => 1,
                        'step' => 1,
                        'max' => 10,
                        'display_value' => 'text',
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Perview ( Min 480px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can control cross-sells post slider item count for big device with this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_smperview',
                        'type' => 'slider',
                        'default' => 2,
                        'min' => 1,
                        'step' => 1,
                        'max' => 10,
                        'display_value' => 'text',
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Speed', 'styler' ),
                        'subtitle' => esc_html__( 'You can control cross-sells post slider item gap.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_speed',
                        'type' => 'slider',
                        'default' => 1000,
                        'min' => 100,
                        'step' => 1,
                        'max' => 10000,
                        'display_value' => 'text',
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Gap', 'styler' ),
                        'subtitle' => esc_html__( 'You can control cross-sells post slider item gap.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_gap',
                        'type' => 'slider',
                        'default' => 30,
                        'min' => 0,
                        'step' => 1,
                        'max' => 100,
                        'display_value' => 'text',
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Autoplay', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_autoplay',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Loop', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_loop',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Mousewheel', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_mousewheel',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Free Mode', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cross_sells_freemode',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    ),
                    array(
                        'id' => 'shop_cross_sells_section_slider_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                    )
                )
            );
            // Quick View SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Quick View', 'styler'),
                'id' => 'shopquickviewsubsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Quick View Display', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Overlay Color', 'styler'),
                        'subtitle' => esc_html__('Change quick view overlay color.', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_overlaycolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.mfp-bg.mfp-styler-quickview'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Content Background Color', 'styler'),
                        'subtitle' => esc_html__('Change quick view background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Content Border', 'styler'),
                        'subtitle' => esc_html__('Set your custom border styles for the posts.', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_brd',
                        'type' => 'border',
                        'all' => false,
                        'output' => array('.styler-quickview-wrapper'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Content Padding', 'styler'),
                        'subtitle' => esc_html__('You can set the spacing of the site shop page post.', 'styler'),
                        'customizer' => true,
                        'id' =>'quick_view_pad',
                        'type' => 'spacing',
                        'output' => array('.styler-quickview-wrapper'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false',
                        'default' => array(
                            'units' => 'px'
                        ),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Content Width', 'styler' ),
                        'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'styler' ),
                        'customizer' => true,
                        'id' => 'quick_view_width',
                        'type' => 'slider',
                        'default' => '',
                        'min' => 0,
                        'step' => 1,
                        'max' => 4000,
                        'display_value' => 'text',
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Content Width Responsive ( min-width 768px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'styler' ),
                        'customizer' => true,
                        'id' => 'quick_view_width_sm',
                        'type' => 'slider',
                        'default' => '',
                        'min' => 0,
                        'step' => 1,
                        'max' => 1200,
                        'display_value' => 'text',
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Close Button Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_close_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.mfp-styler-quickview .styler-panel-close-button'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Close Button Color', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_close_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper .mfp-close.styler-panel-close-button:before,.styler-quickview-wrapper .mfp-close.styler-panel-close-button:after'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Product Title Color', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_title_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper .styler-product-title'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Product Price Color', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_price_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper .styler-price'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Product Description Color', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_desc_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper .styler-product-summary .styler-summary-item p'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Add to Cart Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_btn_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper .styler-btn-small'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Add to Cart Background Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_btn_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper .styler-btn-small:hover'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Add to Cart Color', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_btn_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper .styler-btn-small'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Add to Cart Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_btn_hvrcolor',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper .styler-btn-small:hover'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Meta Label Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_meta_label_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper .styler-attr-label'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Meta Label Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'quick_view_meta_value_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-quickview-wrapper .product_meta>span a, .styler-quickview-wrapper .styler-attr-value, .styler-quickview-wrapper .styler-attr-value a'),
                        'required' => array( 'quick_view_visibility', '=', '1' )
                    )
                )
            );
            // Popup Notices SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Popup Notices', 'styler'),
                'id' => 'shopquickshopsubsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Ajax Add to Cart Notices Display', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_notices_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__( 'Notices Duration ( ms )', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_notices_duration',
                        'type' => 'slider',
                        'default' => 3500,
                        'min' => 0,
                        'step' => 100,
                        'max' => 20000,
                        'display_value' => 'text',
                        'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Success Message Background Color', 'styler'),
                        'subtitle' => esc_html__('Change popup notices background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_notices_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-shop-popup-notices .woocommerce-messages'),
                        'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Success Message Text Color', 'styler'),
                        'subtitle' => esc_html__('Change popup notices text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_notices_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-shop-popup-notices .woocommerce-messages,.styler-shop-popup-notices .woocommerce-messages strong'),
                        'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Success Message Border', 'styler'),
                        'subtitle' => esc_html__('Change popup notices border.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_notices_brd',
                        'type' => 'border',
                        'output' => array('.styler-shop-popup-notices .woocommerce-messages'),
                        'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Error Message Background Color', 'styler'),
                        'subtitle' => esc_html__('Change popup notices background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_error_notices_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-shop-popup-notices .woocommerce-error'),
                        'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Success Message Text Color', 'styler'),
                        'subtitle' => esc_html__('Change popup notices text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_error_notices_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-shop-popup-notices .woocommerce-error,.styler-shop-popup-notices .woocommerce-error strong'),
                        'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Success Message Border', 'styler'),
                        'subtitle' => esc_html__('Change popup notices border.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_error_notices_brd',
                        'type' => 'border',
                        'output' => array('.styler-shop-popup-notices .woocommerce-error'),
                        'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Update Message Background Color', 'styler'),
                        'subtitle' => esc_html__('Change popup notices background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_error_notices_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-shop-popup-notices .woocommerce-message.update-message'),
                        'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Update Message Text Color', 'styler'),
                        'subtitle' => esc_html__('Change popup notices text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_error_notices_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-shop-popup-notices .woocommerce-message,.styler-shop-popup-notices .woocommerce-message.update-message strong'),
                        'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Update Message Border', 'styler'),
                        'subtitle' => esc_html__('Change popup notices border.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_cart_popup_error_notices_brd',
                        'type' => 'border',
                        'output' => array('.styler-shop-popup-notices .woocommerce-message.update-message'),
                        'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                    ),
                )
            );
            // Popup Notices SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Sticky Fly Cart', 'styler'),
                'id' => 'shopflycartsubsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Sticky Fly Cart Display', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__( 'Animation Duration ( ms )', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_duration',
                        'type' => 'slider',
                        'default' => 1500,
                        'min' => 0,
                        'step' => 100,
                        'max' => 5000,
                        'display_value' => 'text',
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'styler'),
                        'subtitle' => esc_html__('Change Fly Cart background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-sticky-cart-toggle'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color ( Active )', 'styler'),
                        'subtitle' => esc_html__('Change Fly Cart background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_actbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-sticky-cart-toggle.active'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Icon Color', 'styler'),
                        'subtitle' => esc_html__('Change fly cart icon color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_svgcolor',
                        'type' => 'color',
                        'mode' => 'fill',
                        'default' => '',
                        'output' => array('.styler-sticky-cart-toggle svg'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Icon Color', 'styler'),
                        'subtitle' => esc_html__('Change fly cart icon color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_actsvgcolor',
                        'type' => 'color',
                        'mode' => 'fill',
                        'default' => '',
                        'output' => array('.styler-sticky-cart-toggle.active svg'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Counter Background Color', 'styler'),
                        'subtitle' => esc_html__('Change fly cart background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_counter_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-sticky-cart-toggle .styler-wc-count'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Counter Number Color', 'styler'),
                        'subtitle' => esc_html__('Change fly cart icon color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_counter_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.styler-sticky-cart-toggle .styler-wc-count'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    )
                )
            );
            // Popup Notices SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Wishlist', 'styler'),
                'id' => 'compare_wishlist_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Wishlist Display', 'styler'),
                        'customizer' => true,
                        'id' => 'wishlist_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'id' =>'wishlist_shortcoe_info',
                        'type' => 'info',
                        'desc' =>  sprintf( esc_html__( 'Create new Wishlist page and use this shortcode %s to display the wishlist on a page.', 'styler' ),'<code>[styler_wishlist]</code>'),
                        'required' => array( 'wishlist_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Wishlist Page', 'styler' ),
                        'subtitle' => esc_html__( 'Select page from the list.', 'styler' ),
                        'customizer' => true,
                        'id' => 'wishlist_page_id',
                        'type' => 'select',
                        'data' => 'page',
                        'multi' => false,
                        'required' => array( 'wishlist_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Wishlist Page Copy', 'styler'),
                        'customizer' => true,
                        'id' => 'wishlist_page_copy',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'wishlist_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Wishlist My Account Page', 'styler'),
                        'customizer' => true,
                        'id' => 'wishlist_page_myaccount',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'wishlist_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Sidebar Panel Clear Button', 'styler'),
                        'customizer' => true,
                        'id' => 'sidebar_panel_wishlist_clear_btn',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'wishlist_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Disable the wishlist for unauthenticated users', 'styler'),
                        'customizer' => true,
                        'id' => 'wishlist_disable_unauthenticated',
                        'type' => 'switch',
                        'on' => esc_html__( 'Yes', 'styler' ),
                        'off' => esc_html__( 'No', 'styler' ),
                        'default' => 0,
                        'required' => array( 'wishlist_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Maximum wishlists per user', 'styler' ),
                        'desc' => esc_html__( 'Please leave this field blank for unlimited additions', 'styler' ),
                        'customizer' => true,
                        'id' => 'wishlist_max_count',
                        'type' => 'text',
                        'default' => '',
                        'validate' => array( 'numeric' ),
                        'required' => array( 'wishlist_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Wishlist Button Action', 'styler' ),
                        'id' =>'wishlist_btn_action',
                        'type' => 'select',
                        'mutiple' => false,
                        'options' => array(
                            'panel' => esc_html__( 'Open Sidebar Panel', 'styler' ),
                            'message' => esc_html__( 'Show Message', 'styler' ),
                        ),
                        'default' => 'panel',
                        'required' => array( 'wishlist_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Header Wishlist Button Action', 'styler' ),
                        'id' =>'header_wishlist_btn_action',
                        'type' => 'select',
                        'mutiple' => false,
                        'options' => array(
                            'panel' => esc_html__( 'Open Sidebar Panel', 'styler' ),
                            'page' => esc_html__( 'Open Wishlist Page', 'styler' ),
                        ),
                        'default' => 'panel',
                        'required' => array( 'wishlist_visibility', '=', '1' )
                    ),
                )
            );
            // Popup Notices SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Compare', 'styler'),
                'id' => 'compare_compare_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Compare Display', 'styler'),
                        'customizer' => true,
                        'id' => 'compare_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Use Most Popular Plugins', 'styler'),
                        'customizer' => true,
                        'id' => 'use_compare_plugins',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'compare_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Select Available Plugins', 'styler' ),
                        'id' =>'compare_plugin',
                        'type' => 'select',
                        'mutiple' => false,
                        'options' => array(
                            'wpc' => esc_html__( 'WPC Smart Compare', 'styler' ),
                            'yith' => esc_html__( 'Yith Compare', 'styler' ),
                        ),
                        'default' => 'wpc',
                        'required' => array(
                            array( 'compare_visibility', '=', '1' ),
                            array( 'use_compare_plugins', '=', '1' ),
                        )
                    ),
                    array(
                        'id' =>'compare_shortcoe_info',
                        'type' => 'info',
                        'desc' =>  sprintf( esc_html__( 'Create new Compare page and use this shortcode %s to display the compare on a page.', 'styler' ),'<code>[styler_compare]</code>'),
                        'required' => array(
                            array( 'compare_visibility', '=', '1' ),
                            array( 'use_compare_plugins', '=', '0' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Compare Page', 'styler' ),
                        'subtitle' => esc_html__( 'Select page from the list.', 'styler' ),
                        'customizer' => true,
                        'id' => 'compare_page_id',
                        'type' => 'select',
                        'data' => 'page',
                        'multi' => false,
                        'required' => array( 'compare_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Maximum compare per user', 'styler' ),
                        'desc' => esc_html__( 'Please leave this field blank for unlimited additions', 'styler' ),
                        'customizer' => true,
                        'id' => 'compare_max_count',
                        'type' => 'text',
                        'default' => '100',
                        'validate' => array( 'numeric' ),
                        'required' => array(
                            array( 'compare_visibility', '=', '1' ),
                            array( 'use_compare_plugins', '=', '0' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Product Compare Button Action', 'styler' ),
                        'id' =>'compare_btn_action',
                        'type' => 'select',
                        'mutiple' => false,
                        'options' => array(
                            'panel' => esc_html__( 'Open Sidebar Panel', 'styler' ),
                            'popup' => esc_html__( 'Open Compare Popup', 'styler' ),
                            'message' => esc_html__( 'Show Message', 'styler' ),
                        ),
                        'default' => 'panel',
                        'required' => array(
                            array( 'compare_visibility', '=', '1' ),
                            array( 'use_compare_plugins', '=', '0' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Header Compare Button Action', 'styler' ),
                        'id' =>'header_compare_btn_action',
                        'type' => 'select',
                        'mutiple' => false,
                        'options' => array(
                            'panel' => esc_html__( 'Open Sidebar Panel', 'styler' ),
                            'popup' => esc_html__( 'Open Compare Popup', 'styler' ),
                            'page' => esc_html__( 'Open Compare Page', 'styler' ),
                        ),
                        'default' => 'panel',
                        'required' => array( 'compare_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Compare table', 'styler' ),
                        'id' =>'compare_table',
                        'type' => 'checkbox',
                        'mutiple' => false,
                        'options' => array(
                            'image' => esc_html__( 'Image', 'styler' ),
                            'price' => esc_html__( 'Price', 'styler' ),
                            'sku' => esc_html__( 'SKU', 'styler' ),
                            'stock' => esc_html__( 'Stock', 'styler' ),
                            'rating' => esc_html__( 'Rating', 'styler' ),
                            'desc' => esc_html__( 'Description', 'styler' ),
                            'content' => esc_html__( 'Content', 'styler' ),
                            'weight' => esc_html__( 'Weight', 'styler' ),
                            'dimensions' => esc_html__( 'Dimensions', 'styler' ),
                            'additional' => esc_html__( 'Additional information', 'styler' ),
                            'availability' => esc_html__( 'Availability', 'styler' ),
                            'cart' => esc_html__( 'Add to cart', 'styler' ),
                        ),
                        'default' => array(
                            'image' => 1,
                            'price' => 1,
                            'sku' => 1,
                            'stock' => 1,
                            'rating' => 0,
                            'desc' => 1,
                            'content' => 0,
                            'weight' => 1,
                            'dimensions' => 1,
                            'additional' => 1,
                            'availability' => 1,
                            'cart' => 1,
                        ),
                        'required' => array(
                            array( 'compare_visibility', '=', '1' ),
                            array( 'use_compare_plugins', '=', '0' )
                        )
                    )
                )
            );
            // Minicart SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Minicart', 'styler'),
                'id' => 'compare_minicart_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Minicart Total Display', 'styler'),
                        'customizer' => true,
                        'id' => 'sidebar_panel_cart_total_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__( 'Disable Panel Auto-Open', 'styler' ),
                        'subtitle' => esc_html__( 'You can disable automatic opening of the right panel( mini cart ) when a product is added to the cart', 'styler' ),
                        'customizer' => true,
                        'id' => 'disable_right_panel_auto',
                        'on' => esc_html__( 'Yes', 'styler' ),
                        'off' => esc_html__( 'No', 'styler' ),
                        'type' => 'switch',
                        'customizer' => true,
                        'default' => 0,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Header Border Bottom Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_header_brdcolor',
                        'type' => 'color',
                        'mode' => 'border-bottom-color',
                        'output' => array( '.styler-side-panel .panel-header' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Header Close Icon Background Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_close_icon_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-side-panel .styler-panel-close-button' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Header Close Icon Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_close_icon_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .styler-panel-close-button' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Header SVG Icon Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_svg_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-side-panel .panel-header .styler-svg-icon' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Header Active SVG Icon Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_active_svg_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-side-panel .panel-header .panel-header-btn.active .styler-svg-icon' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Header Active SVG Icon Background Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_active_svg_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-side-panel .panel-header .panel-header-btn.active .styler-svg-icon' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Header Counter Background Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_counter_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-side-panel .panel-header .panel-header-btn .styler-wc-count' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Header Counter Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_counter_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .panel-header .panel-header-btn  .styler-wc-count' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Header Cart Total Text Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_total_text_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .panel-header .panel-header-btn span.styler-cart-total' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Title Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_title_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .panel-top-title' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Panel Title Border Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_title_brdcolor',
                        'type' => 'color',
                        'mode' => 'border-bottom-color',
                        'output' => array( '.styler-side-panel .panel-top-title:after' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Cart Item Title Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_title_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .cart-name' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Cart Item Price Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_price_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .styler-price' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Cart Item Quantity Background Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_qty_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-side-panel .quantity' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Cart Item Quantity Plus Minus Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_qty_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .quantity-button.plus,.styler-side-panel .quantity-button.minus,.styler-side-panel .input-text::-webkit-input-placeholder,.styler-side-panel .input-text'),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Cart Item Quantity Plus Minus Backgroud Color ( Hover )', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_qty_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-side-panel .quantity-button.plus:hover,.styler-side-panel .quantity-button.minus:hover' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Wishlist,Compare Add to Cart Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_addtocart_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .styler-content-info .add_to_cart_button' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Wishlist,Compare Add to Cart Color ( Hover )', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_addtocart_hvrcolor',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .styler-content-info .add_to_cart_button:hover' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Wishlist,Compare Stock Status Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_addtocart_hvrcolor',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .styler-content-info .product-stock' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Subtotal Border Top Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_subtotal_brdcolor',
                        'type' => 'color',
                        'mode' => 'border-top-color',
                        'output' => array( '.styler-side-panel .cart-total-price' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Subtotal Title Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_subtotal_color',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-side-panel .cart-total-price' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Subtotal Price Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_subtotal_price_color',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-side-panel .cart-total-price .cart-total-price-right' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Delete Icon Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_delete_icon_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-side-panel .del-icon a svg' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Free Shipping Text Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_extra_text_color',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-side-panel .minicart-extra-text' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Buttons Background Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_buttons_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-side-panel .cart-bottom-btn .styler-btn, .styler-side-panel .checkout-area .styler-bg-black' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Buttons Background Color ( Hover )', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_buttons_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-side-panel .cart-bottom-btn .styler-btn:hover, .styler-side-panel .checkout-area .styler-bg-black:hover' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Buttons Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_buttons_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .cart-bottom-btn .styler-btn, .styler-side-panel .checkout-area .styler-bg-black' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Buttons Color ( Hover )', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_buttons_hvrcolor',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .cart-bottom-btn .styler-btn:hover, .styler-side-panel .checkout-area .styler-bg-black:hover' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Empty Cart Icon Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_empty_svg_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-side-panel .panel-content svg' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Empty Cart Text Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'sidebar_right_panel_cart_item_empty_text_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'output' => array( '.styler-side-panel .styler-small-title' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '=', 'default' )
                        )
                    )
                )
            );
            // Buy Now Button SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Buy Now Button', 'styler'),
                'id' => 'shop_buynow_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Buy Now Button Display', 'styler'),
                        'customizer' => true,
                        'id' => 'buy_now_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__( 'Button Text', 'styler' ),
                        'subtitle' => esc_html__('Leave blank to use the default text or its equivalent translation in multiple languages.', 'styler'),
                        'customizer' => true,
                        'id' => 'buy_now_btn_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Reset Cart', 'styler'),
                        'subtitle' => esc_html__('Reset the cart before doing buy now.', 'styler'),
                        'on' => esc_html__('Yes', 'styler'),
                        'off' => esc_html__('No', 'styler'),
                        'customizer' => true,
                        'id' => 'buy_now_reset_cart',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Parameter', 'styler' ),
                        'customizer' => true,
                        'id' => 'buy_now_param',
                        'type' => 'text',
                        'default' => 'styler-buy-now',
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'id' =>'buy_now_redirect',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Redirect to', 'styler' ),
                        'options' => array(
                            'checkout' => esc_html__( 'Checkout page', 'styler' ),
                            'cart' => esc_html__( 'Cart page', 'styler' ),
                            'custom' => esc_html__( 'Custom', 'styler' ),
                        ),
                        'default' => 'checkout',
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Custom Page', 'styler' ),
                        'customizer' => true,
                        'id' => 'buy_now_redirect_custom',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'buy_now_visibility', '=', '1' ),
                            array( 'buy_now_redirect', '=', 'custom' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'styler'),
                        'subtitle' => esc_html__('Change button background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'buy_now_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-btn.styler-btn-buynow'),
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color ( Hover )', 'styler'),
                        'subtitle' => esc_html__('Change button hover background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'buy_now_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-btn.styler-btn-buynow:hover'),
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'styler'),
                        'subtitle' => esc_html__('Change button text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'buy_now_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-btn.styler-btn-buynow'),
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color ( Hover )', 'styler'),
                        'subtitle' => esc_html__('Change button hover text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'buy_now_hvrcolor',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-btn.styler-btn-buynow:hover'),
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    )
                )
            );
            // Whatsapp Button SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Whatsapp Button', 'styler'),
                'id' => 'shop_whatsapp_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Whatsapp Display', 'styler'),
                        'customizer' => true,
                        'id' => 'product_whatsapp_button_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__( 'Button Text', 'styler' ),
                        'subtitle' => esc_html__('Leave blank to use the default text or its equivalent translation in multiple languages.', 'styler'),
                        'customizer' => true,
                        'id' => 'whatsapp_btn_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array( 'product_whatsapp_button_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Custom URL', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_whatsapp_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array( 'product_whatsapp_button_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Mobile Device Custom URL', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_whatsapp_mobile_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array( 'product_whatsapp_button_visibility', '=', '1' )
                    ),
                    array(
                        'id' =>'product_whatsapp_target',
                        'type' => 'select',
                        'title' => esc_html__( 'Target', 'styler' ),
                        'options' => array(
                            '' => esc_html__( 'Select an option', 'styler' ),
                            '_blank' => esc_html__( 'Open in a new window', 'styler' ),
                            '_self' => esc_html__( 'Open in the same frame', 'styler' ),
                            '_parent' => esc_html__( 'Open in the parent frame', 'styler' ),
                            '_top' => esc_html__( 'Open in the full body of the window', 'styler' )
                        ),
                        'required' => array( 'product_whatsapp_button_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'styler'),
                        'subtitle' => esc_html__('Change button background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_whatsapp_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-summary-item .styler-btn.social-whatsapp'),
                        'required' => array( 'product_whatsapp_button_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color ( Hover )', 'styler'),
                        'subtitle' => esc_html__('Change button hover background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_whatsapp_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-summary-item .styler-btn.social-whatsapp:hover'),
                        'required' => array( 'product_whatsapp_button_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'styler'),
                        'subtitle' => esc_html__('Change button text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_whatsapp_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.styler-summary-item .styler-btn.social-whatsapp'),
                        'required' => array( 'product_whatsapp_button_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color ( Hover )', 'styler'),
                        'subtitle' => esc_html__('Change button hover text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_whatsapp_hvrcolor',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.styler-summary-item .styler-btn.social-whatsapp:hover'),
                        'required' => array( 'product_whatsapp_button_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'subtitle' => esc_html__('Change button border.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_whatsapp_border',
                        'type' => 'border',
                        'output' => array('.styler-summary-item .styler-btn.social-whatsapp:hover'),
                        'required' => array( 'product_whatsapp_button_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border ( Hover )', 'styler'),
                        'subtitle' => esc_html__('Change button hover border color.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_whatsapp_hvrborder',
                        'type' => 'border',
                        'output' => array('.styler-summary-item .styler-btn.social-whatsapp:hover'),
                        'required' => array( 'product_whatsapp_button_visibility', '=', '1' )
                    )
                )
            );
            // Product Custom Button SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Product Custom Button', 'styler'),
                'id' => 'shop_product_custom_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Product Page Custom Button Display', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__( 'Button Text', 'styler' ),
                        'subtitle' => esc_html__('Leave blank to use the default text or its equivalent translation in multiple languages.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_title',
                        'type' => 'text',
                        'default' => 'Request Information',
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'id' =>'product_custom_btn_action',
                        'type' => 'select',
                        'title' => esc_html__( 'Action', 'styler' ),
                        'options' => array(
                            'link' => esc_html__( 'Custom Link', 'styler' ),
                            'form' => esc_html__( 'Open Popup Form', 'styler' ),
                            'whatsapp' => esc_html__( 'Open Whatsapp', 'styler' ),
                        ),
                        'default' => 'link',
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Custom Link', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_custom_btn_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'product_custom_btn_visibility', '=', '1' ),
                            array( 'product_custom_btn_action', '=', 'link' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Form Shortcode or Custom HTML', 'styler' ),
                        'subtitle' => esc_html__('Add your form shortcode here.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_form_shortcode',
                        'type' => 'textarea',
                        'default' => '',
                        'required' => array(
                            array( 'product_custom_btn_visibility', '=', '1' ),
                            array( 'product_custom_btn_action', '=', 'form' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Whatsapp Desktop Link', 'styler' ),
                        'subtitle' => esc_html__('Add your whatsapp link here.Deafult: https://api.whatsapp.com/send?text=', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_whatsapp_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'product_custom_btn_visibility', '=', '1' ),
                            array( 'product_custom_btn_action', '=', 'whatsapp' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Whatsapp Mobile Link', 'styler' ),
                        'subtitle' => esc_html__('Add your whatsapp link here.Deafult: whatsapp://send?text=', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_whatsapp_mobile_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'product_custom_btn_visibility', '=', '1' ),
                            array( 'product_custom_btn_action', '=', 'whatsapp' )
                        )
                    ),
                    array(
                        'id' =>'product_custom_btn_target',
                        'type' => 'select',
                        'title' => esc_html__( 'Target', 'styler' ),
                        'options' => array(
                            '' => esc_html__( 'Select an option', 'styler' ),
                            '_blank' => esc_html__( 'Open in a new window', 'styler' ),
                            '_self' => esc_html__( 'Open in the same frame', 'styler' ),
                            '_parent' => esc_html__( 'Open in the parent frame', 'styler' ),
                            '_top' => esc_html__( 'Open in the full body of the window', 'styler' )
                        ),
                        'required' => array(
                            array( 'product_custom_btn_visibility', '=', '1' ),
                            array( 'product_custom_btn_action', '!=', 'form' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'styler'),
                        'subtitle' => esc_html__('Change button background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-product-action-button .styler-btn:not(.type-widget)'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color ( Hover )', 'styler'),
                        'subtitle' => esc_html__('Change button hover background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-product-action-button .styler-btn:not(.type-widget):hover'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'styler'),
                        'subtitle' => esc_html__('Change button text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-product-action-button .styler-btn:not(.type-widget)'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color ( Hover )', 'styler'),
                        'subtitle' => esc_html__('Change button hover text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_hvrcolor',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.styler-product-action-button .styler-btn:not(.type-widget):hover'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'subtitle' => esc_html__('Change button border.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_brdcolor',
                        'type' => 'border',
                        'output' => array('.styler-product-action-button .styler-btn:not(.type-widget)'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border ( Hover )', 'styler'),
                        'subtitle' => esc_html__('Change button hover border.', 'styler'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_hvrbrdcolor',
                        'type' => 'border',
                        'output' => array('.styler-product-action-button .styler-btn:not(.type-widget):hover'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    )
                )
            );
            // Free Shipping Progressbar
            $sections[] = array(
                'title' => esc_html__( 'Free Shipping Progressbar', 'styler' ),
                'id' => 'shopshippingprogressbarsubsection',
                'subsection'=> true,
                'icon' => 'fa fa-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Progressbar Display', 'styler'),
                        'subtitle' => esc_html__('You can enable or disable the site shop free shipping progressbar with switch option.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progressbar_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__('Minicart Panel Progressbar Display', 'styler'),
                        'subtitle' => esc_html__('You can enable or disable the site shop free shipping progressbar with switch option.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progressbar_minicart_visibility',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Cart Page Progressbar Display', 'styler'),
                        'subtitle' => esc_html__('You can enable or disable the site shop free shipping progressbar with switch option.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progressbar_cartpage_visibility',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Targeted Amount', 'styler'),
                        'subtitle' => esc_html__('Please enter the targeted amount without currency for free shipping in this field.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progressbar_amount',
                        'validate' => array( 'numeric', 'not_empty' ),
                        'type' => 'text',
                        'default' => 500,
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Initial Message', 'styler'),
                        'subtitle' => sprintf('%s <code>[remainder]</code> %s',
                        	esc_html__('Please enter the initial message with', 'styler'),
                        	esc_html__('for free shipping in this field.', 'styler')
                        ),
                        'customizer' => true,
                        'id' => 'free_shipping_progressbar_message_initial',
                        'type' => 'textarea',
                        'default' => 'Buy [remainder] more to enjoy FREE Shipping',
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Success Message', 'styler'),
                        'subtitle' => esc_html__('Please enter the success message with for free shipping in this field.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progressbar_message_success',
                        'type' => 'textarea',
                        'default' => 'Congrats! You are eligible for more to enjoy FREE Shipping',
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Progressbar Background Color', 'styler'),
                        'subtitle' => esc_html__('Change progress background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-free-shipping-progress .styler-progress-bar-wrap:before'),
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Progress Color', 'styler'),
                        'subtitle' => esc_html__('Change progress background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_color',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-free-shipping-progress .styler-progress-bar:before'),
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Progress Color ( Success )', 'styler'),
                        'subtitle' => esc_html__('Change progress background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_success_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.free-shipping-success .styler-progress-bar:before'),
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Text Typography', 'styler' ),
                        'id' => 'free_shipping_progress_text_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.styler-cart-goal-wrapper .styler-cart-goal-text' ),
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'styler'),
                        'subtitle' => esc_html__('Change button text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_text_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.styler-cart-goal-wrapper .styler-cart-goal-text'),
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color ( Success )', 'styler'),
                        'subtitle' => esc_html__('Change button hover text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_success_text_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.styler-cart-goal-wrapper.free-shipping-success .styler-cart-goal-text'),
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('SVG Icon Color', 'styler'),
                        'subtitle' => esc_html__('Change button text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_icon_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'default' => '',
                        'output' => array('.styler-free-shipping-progress .styler-progress-value svg *'),
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('SVG Color ( Success )', 'styler'),
                        'subtitle' => esc_html__('Change button hover text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_success_icon_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'default' => '',
                        'output' => array('.free-shipping-success .styler-progress-value svg *'),
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Amount Color', 'styler'),
                        'subtitle' => esc_html__('Change amount text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_amount_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.styler-cart-goal-text .woocommerce-Price-amount.amount'),
                        'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Cart Page Container Border', 'styler'),
                        'subtitle' => esc_html__('Change border.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_container_brdcolor',
                        'type' => 'border',
                        'output' => array('.styler-before-cart-table .styler-cart-goal-wrapper'),
                        'required' => array(
                            array( 'free_shipping_progressbar_visibility', '=', '1' ),
                            array( 'free_shipping_progressbar_cartpage_visibility', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Cart Page Container Border  ( Success )', 'styler'),
                        'subtitle' => esc_html__('Change border.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_container_success_brdcolor',
                        'type' => 'border',
                        'output' => array('.styler-before-cart-table .styler-cart-goal-wrapper.free-shipping-success'),
                        'required' => array(
                            array( 'free_shipping_progressbar_visibility', '=', '1' ),
                            array( 'free_shipping_progressbar_cartpage_visibility', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Cart Page Container Background Color', 'styler'),
                        'subtitle' => esc_html__('Change background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_container_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-before-cart-table .styler-cart-goal-wrapper'),
                        'required' => array(
                            array( 'free_shipping_progressbar_visibility', '=', '1' ),
                            array( 'free_shipping_progressbar_cartpage_visibility', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Cart Page Container Background Color ( Success )', 'styler'),
                        'subtitle' => esc_html__('Change background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'free_shipping_progress_container_success_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.styler-before-cart-table .styler-cart-goal-wrapper.free-shipping-success'),
                        'required' => array(
                            array( 'free_shipping_progressbar_visibility', '=', '1' ),
                            array( 'free_shipping_progressbar_cartpage_visibility', '=', '1' ),
                        )
                    )
                )
            );
            // Extra
            $sections[] = array(
                'title' => esc_html__( 'Catalog Mode', 'styler' ),
                'id' => 'shop_catalog_mode_subsection',
                'subsection'=> true,
                'icon' => 'fa fa-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Catalog Mode', 'styler'),
                        'subtitle' => esc_html__('Use this option to hide all the "Add to Cart" buttons in the shop.', 'styler'),
                        'customizer' => true,
                        'id' => 'woo_catalog_mode',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__('Disable Product Page Add to Cart', 'styler'),
                        'subtitle' => esc_html__('Use this option to hide all the "Add to Cart" buttons in the product page.', 'styler'),
                        'customizer' => true,
                        'id' => 'woo_disable_product_addtocart',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'woo_catalog_mode', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Disable Cart and Checkout Page', 'styler'),
                        'subtitle' => esc_html__('Use this option to hide the "Cart" page, "Checkout" page in the shop.', 'styler'),
                        'customizer' => true,
                        'id' => 'woo_disable_cart_checkout',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'woo_catalog_mode', '=', '1' )
                    )
                )
            );
            // create sections in the theme options
            $sections[] = array(
                'title' => esc_html__('SHOP PAGE', 'styler'),
                'id' => 'shopsection',
                'icon' => 'el el-shopping-cart-sign'
            );
            // SHOP PAGE SECTION
            $sections[] = array(
                'title' => esc_html__( 'Shop Page Layout', 'styler' ),
                'id' => 'shoplayoutsection',
                'subsection'=> true,
                'icon' => 'el el-website',
                'fields' => array(
                    array(
                        'id' =>'shop_layout',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Shop Layouts', 'styler' ),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop page sidebar area.', 'styler' ),
                        'options' => array(
                            'top-sidebar' => esc_html__( 'Top Hidden Sidebar', 'styler' ),
                            'fixed-sidebar' => esc_html__( 'Left Fixed Sidebar', 'styler' ),
                            'left-sidebar' => esc_html__( 'Left Sidebar', 'styler' ),
                            'right-sidebar' => esc_html__( 'Right Sidebar', 'styler' ),
                            'no-sidebar' => esc_html__( 'No Sidebar', 'styler' )
                        ),
                        'default' => 'fixed-sidebar',
                    ),
                    array(
                        'title' => esc_html__('Choosen Filters', 'styler'),
                        'subtitle' => esc_html__('You can enable or disable the filters selected before the loop.', 'styler'),
                        'customizer' => true,
                        'id' => 'choosen_filters_before_loop',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'shop_layout', '!=', 'top-sidebar' ),
                            array( 'shop_layout', '!=', 'top-sidebar' ),
                            array( 'shop_layout', '!=', 'no-sidebar' )
                        )
                    ),
                    array(
                        'id' =>'shop_grid_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Shop Grid Type', 'styler' ),
                        'options' => array(
                            'grid' => esc_html__( 'Default Grid', 'styler' ),
                            'masonry' => esc_html__( 'Masonry', 'styler' )
                        ),
                        'default' => 'grid',
                        'required' => array(
                            array( 'shop_layout', '!=', 'left-sidebar' ),
                            array( 'shop_layout', '!=', 'right-sidebar' )
                        )
                    ),
                    array(
                        'id' =>'shop_masonry_column',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Shop Masonry Column Width', 'styler' ),
                        'subtitle' => esc_html__( 'Select your shop masonry type column width', 'styler' ),
                        'options' => array(
                            '3' => esc_html__( '3 Column', 'styler' ),
                            '4' => esc_html__( '4 Column', 'styler' ),
                            '5' => esc_html__( '5 Column', 'styler' ),
                            '6' => esc_html__( '6 Column', 'styler' ),
                        ),
                        'default' => '4',
                        'required' => array(
                            array( 'shop_layout', '!=', 'left-sidebar' ),
                            array( 'shop_layout', '!=', 'right-sidebar' ),
                            array( 'shop_grid_type', '=', 'masonry' )
                        )
                    ),
                    array(
                        'id' =>'shop_hidden_sidebar_column',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Hidden Sidebar Widget Column Width', 'styler' ),
                        'subtitle' => esc_html__( 'Select your shop sidebar widget column width', 'styler' ),
                        'options' => array(
                            '1' => esc_html__( '1 Column', 'styler' ),
                            '2' => esc_html__( '2 Column', 'styler' ),
                            '3' => esc_html__( '3 Column', 'styler' ),
                            '4' => esc_html__( '4 Column', 'styler' ),
                            '5' => esc_html__( '5 Column', 'styler' ),
                        ),
                        'default' => '3',
                        'required' => array( 'shop_layout', '=', 'top-sidebar' )
                    ),
                    array(
                        'id' =>'shop_loop_filters_layouts',
                        'type' => 'sorter',
                        'title' => esc_html__( 'Shop Filter Area Layouts Manager', 'styler' ),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop page filter area.', 'styler' ),
                        'options' => array(
                            'left' => array(
                                'breadcrumbs' => esc_html__( 'Breadcrumbs', 'styler' ),
                            ),
                            'right' => array(
                                'sidebar-filter' => esc_html__( 'Sidebar Toggle', 'styler' ),
                                'per-page' => esc_html__( 'Perpage Selection', 'styler' ),
                                'column-select' => esc_html__( 'Column Selection', 'styler' ),
                                'ordering' => esc_html__( 'Ordering', 'styler' )
                            ),
                            'hide' => array(
                                'result-count' => esc_html__( 'Result Count', 'styler' ),
                                'search' => esc_html__( 'Search Popup', 'styler' ),
                            )
                        )
                    ),
                    array(
                        'title' => esc_html__('Per Page Select Options', 'styler'),
                        'subtitle' => esc_html__('Separate each number with a comma.For example: 12,24,36', 'styler'),
                        'customizer' => true,
                        'id' => 'per_page_select_options',
                        'type' => 'text',
                        'default' => '9,12,18,24'
                    ),
                    array(
                        'id' =>'shop_paginate_type',
                        'type' => 'button_set',
                        'title' => esc_html__('Pagination Type', 'styler'),
                        'subtitle' => esc_html__('Select your pagination type.', 'styler'),
                        'options' => array(
                            'pagination' => esc_html__('Default Pagination', 'styler'),
                            'ajax-pagination' => esc_html__('Ajax Pagination', 'styler'),
                            'loadmore' => esc_html__('Ajax Load More', 'styler'),
                            'infinite' => esc_html__('Ajax Infinite Scroll', 'styler')
                        ),
                        'default' => 'ajax-pagination',
                        'required' => array( 'shop_ajax_filter', '=', '1' )
                    ),
                    array(
                        'id' =>'shop_container_width',
                        'type' => 'select',
                        'title' => esc_html__( 'Container Width', 'styler' ),
                        'subtitle' => esc_html__( 'Select your header background type.', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'Deafult ( theme Content Width from Main settings )', 'styler' ),
                            'stretch' => esc_html__( 'Stretch', 'styler' ),
                        ),
                        'default' => 'default'
                    )
                )
            );
            // SINGLE HERO SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Shop Page Header', 'styler'),
                'desc' => esc_html__('These are shop page header section settings', 'styler'),
                'id' => 'shopheadersubsection',
                'subsection' => true,
                'icon' => 'el el-brush',
                'fields' => array(
                    array(
                        'title' => esc_html__('Use Different Header Layouts', 'styler'),
                        'subtitle' => esc_html__('You can use different header layouts type on shop pages.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_different_header_layouts',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'id' =>'shop_header_layouts',
                        'type' => 'sorter',
                        'title' => esc_html__( 'Shop Header Layout Manager', 'styler' ),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme header', 'styler' ),
                        'options' => array(
                            'left' => array(
                                'sidemenu' => esc_html__( 'Sidemenu Toggle', 'styler' ),
                                'logo' => esc_html__( 'Logo', 'styler' )
                            ),
                            'center'=> array(
                                'menu' => esc_html__( 'Main Menu', 'styler' )
                            ),
                            'right'=> array(
                                'search' => esc_html__( 'Search', 'styler' ),
                                'buttons' => esc_html__( 'Buttons', 'styler' )
                            ),
                            'hide'  => array(
                                'center-logo' => esc_html__( 'Menu Logo Menu', 'styler' ),
                                'mini-menu' => esc_html__( 'Mini Menu', 'styler' ),
                                'double-menu' => esc_html__( 'Double Menu', 'styler' ),
                                'custom-html' => esc_html__( 'Phone Number', 'styler' )
                            )
                        ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_layouts', '=', '1' )
                        )
                    ),
                    array(
                        'id' =>'shop_header_buttons_layouts',
                        'type' => 'sorter',
                        'title' => esc_html__( 'Shop Header Buttons Manager', 'styler' ),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme header for buttons', 'styler' ),
                        'options' => array(
                            'show'  => array(
                                'cart' => esc_html__( 'Cart', 'styler' ),
                                'wishlist' => esc_html__( 'Wishlist', 'styler' ),
                                'compare' => esc_html__( 'Compare', 'styler' ),
                                'account' => esc_html__( 'Account', 'styler' )
                            ),
                            'hide'  => array(
                                'search' => esc_html__( 'Search', 'styler' )
                            )
                        ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_layouts', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Use Different Header Background Type', 'styler'),
                        'subtitle' => esc_html__('You can use different header background type on product page.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_different_header_bg_type',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'id' => 'shop_header_menu_items_customize_start',
                        'type' => 'section',
                        'title' => esc_html__('Header Customize Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' =>'shop_header_bg_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Header Background Type', 'styler' ),
                        'subtitle' => esc_html__( 'Select your header background type.', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'Deafult', 'styler' ),
                            'dark' => esc_html__( 'Dark', 'styler' ),
                            'trans-light' => esc_html__( 'Transparent Light', 'styler' ),
                            'trans-dark' => esc_html__( 'Transparent Dark', 'styler' )
                        ),
                        'default' => 'default',
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Header Background Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_header_bg',
                        'type' => 'color_rgba',
                        'mode' => 'background-color',
                        'output' => array( '.archive.post-type-archive-product header.styler-header-default,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Menu Item Color', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_nav_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product .styler-header-top-menu-area>ul>li.menu-item>a,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .styler-header-top-menu-area>ul>li.menu-item>a' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_nav_hvr_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product .current-menu-parent>a,.archive.post-type-archive-product .current-menu-item>a,.archive.post-type-archive-product .styler-header-top-menu-area>ul>li.menu-item>a:hover,.archive.post-type-archive-product .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .current-menu-parent>a,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .current-menu-item>a,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .styler-header-top-menu-area>ul>li.menu-item>a:hover,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover'),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Header Background Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_nav_top_sticky_bg',
                        'type' => 'color_rgba',
                        'mode' => 'background-color',
                        'output' => array( '.archive.post-type-archive-product.has-sticky-header.scroll-start header.styler-header-default' ),
                        'required' => array( 'header_sticky_visibility', '=', '1' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Menu Item Color', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_sticky_nav_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product.has-sticky-header.scroll-start .styler-header-top-menu-area>ul>li.menu-item>a' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Menu Item Color ( Hover and Active )', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_sticky_nav_hvr_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product.has-sticky-header.scroll-start .current-menu-parent>a, .archive.post-type-archive-product.has-sticky-header.scroll-start .current-menu-item>a, .archive.post-type-archive-product.has-sticky-header.scroll-start .styler-header-top-menu-area>ul>li.menu-item>a:hover, .archive.post-type-archive-product.has-sticky-header.scroll-start .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'shop_header_menu_items_style_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'shop_header_submenu_items_style_start',
                        'type' => 'section',
                        'title' => esc_html__('Header Sub Menu Customize Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sub Menu Background Color', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_nav_submenu_bg',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product .styler-header-top-menu-area ul li .submenu' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Menu Item Color', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_nav_submenu_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product .styler-header-top-menu-area ul li .submenu>li.menu-item>a' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_nav_submenu_hvr_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.archive.post-type-archive-product .styler-header-top-menu-area ul li .submenu>li.menu-item.active>a' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'shop_header_submenu_items_style_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'shop_header_svgbuttons_items_style_start',
                        'type' => 'section',
                        'title' => esc_html__('Header SVG Buttons Customize Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'SVG Icon Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare, Account, Search, Sidemenu bar', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_header_buttons_svg_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product .styler-header-default .header-top-buttons .top-action-btn,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .header-top-buttons .top-action-btn' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Button Counter Background Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_header_buttons_counter_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product .styler-header-default .styler-wc-count,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .styler-wc-count' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Button Counter Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_header_buttons_counter_color',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product .styler-header-default .styler-wc-count,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .styler-wc-count' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Header SVG Icon Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare, Account, Search, Sidemenu bar', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_sticky_header_buttons_svg_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product.scroll-start .styler-header-default .header-top-buttons .top-action-btn,.archive.post-type-archive-product.has-default-header-type-trans.scroll-start header.styler-header-default .header-top-buttons .top-action-btn' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Header Button Counter Background Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_sticky_header_buttons_counter_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product.scroll-start .styler-header-default .styler-wc-count,.archive.post-type-archive-product.has-default-header-type-trans.scroll-start header.styler-header-default .styler-wc-count' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Header Button Counter Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_sticky_header_buttons_counter_color',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.archive.post-type-archive-product.scroll-start .styler-header-default .styler-wc-count,.archive.post-type-archive-product.has-default-header-type-trans.scroll-start header.styler-header-default .styler-wc-count' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'shop_header_svgbuttons_items_style_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'shop_different_header_bg_type', '=', '1' )
                        )
                    )
                )
            );
            // SINGLE HERO SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Shop Page Hero', 'styler'),
                'desc' => esc_html__('These are shop page hero section settings', 'styler'),
                'id' => 'shopherosubsection',
                'subsection' => true,
                'icon' => 'el el-brush',
                'fields' => array(
                    array(
                        'title' => esc_html__('Hero display', 'styler'),
                        'subtitle' => esc_html__('You can enable or disable the site shop page hero section with switch option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_hero_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'id' =>'shop_hero_type',
                        'type' => 'button_set',
                        'title' => esc_html__('Shop Hero Type', 'styler'),
                        'subtitle' => esc_html__('Select your pagination type.', 'styler'),
                        'options' => array(
                            'default' => esc_html__('Default Hero', 'styler'),
                            'elementor' => esc_html__('Elementor Templates', 'styler'),
                        ),
                        'default' => 'default',
                        'required' => array( 'shop_hero_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Custom Page Title', 'styler'),
                        'subtitle' => esc_html__('Add your shop page custom title here.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' )
                        )
                    ),
                    array(
                        'id' =>'shop_page_title_tag',
                        'type' => 'button_set',
                        'title' => esc_html__('Shop Page Heading Tag', 'styler'),
                        'subtitle' => esc_html__('Select heading tag.', 'styler'),
                        'options' => array(
                            'h1' => esc_html__('H1', 'styler'),
                            'h2' => esc_html__('H2', 'styler'),
                            'h3' => esc_html__('H3', 'styler'),
                            'h4' => esc_html__('H4', 'styler'),
                            'h5' => esc_html__('H5', 'styler'),
                            'h6' => esc_html__('H6', 'styler'),
                            'div' => esc_html__('Div', 'styler'),
                            'p' => esc_html__('P', 'styler'),
                        ),
                        'default' => 'h2',
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates.If you want to show the theme default hero template please leave a blank.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_hero_elementor_templates',
                        'type' => 'select',
                        'customizer' => true,
                        'data' => 'posts',
                        'args' => $el_args,
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'elementor' ),
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Category Pages Hero Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates.If you want to show the theme default hero template please leave a blank.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_cats_hero_elementor_templates',
                        'type' => 'select',
                        'customizer' => true,
                        'data' => 'posts',
                        'args' => $el_args,
                        'required' => array( 'shop_hero_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Tags Pages Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates.If you want to show the theme default hero template please leave a blank.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_tax_hero_elementor_templates',
                        'type' => 'select',
                        'customizer' => true,
                        'data' => 'posts',
                        'args' => $el_args,
                        'required' => array( 'shop_hero_visibility', '=', '1' )
                    ),
                    array(
                        'id' =>'shop_hero_layout_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Default Hero Layouts', 'styler' ),
                        'subtitle' => esc_html__( 'Select how you want the layout to appear on the theme shop page hero area.', 'styler' ),
                        'options' => array(
                            'mini' => esc_html__( 'Title + Breadcrumbs', 'styler' ),
                            'small' => esc_html__( 'Title Center', 'styler' ),
                            'big' => esc_html__( 'Title + Categories', 'styler' ),
                            'cat-slider' => esc_html__( 'Title + Categories Slider', 'styler' ),
                        ),
                        'default' => 'mini',
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Show Only Parent Categories', 'styler'),
                        'subtitle' => esc_html__('Enable this option if you want to show only parent categories.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_hero_only_cats_parents',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_layout_type', '!=', 'mini' ),
                            array( 'shop_hero_layout_type', '!=', 'small' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Hero Customize Options', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_hero_customize_section_start',
                        'type' => 'section',
                        'indent' => true,
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Hero Background', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_hero_bg',
                        'type' => 'background',
                        'output' => array( '#nt-shop-page .styler-page-hero' ),
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Hero height', 'styler' ),
                        'subtitle' => esc_html__( 'Set the logo width and height of the image.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_hero_height',
                        'type' => 'dimensions',
                        'width' => false,
                        'output' => array( '#nt-shop-page .styler-page-hero' ),
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Hero Background Image for Tablet', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_hero_tablet_bg',
                        'type' => 'media',
                        'url' => true,
                        'customizer' => true,
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Hero height ( Tablet )', 'styler' ),
                        'subtitle' => esc_html__( 'Set the hero height for mobile device.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_hero_tablet_height',
                        'type' => 'dimensions',
                        'width' => false,
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Hero Background Image for Phone', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_hero_mobile_bg',
                        'type' => 'media',
                        'url' => true,
                        'customizer' => true,
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Hero height ( Phone )', 'styler' ),
                        'subtitle' => esc_html__( 'Set the hero height for mobile device.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_hero_mobile_height',
                        'type' => 'dimensions',
                        'width' => false,
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' )
                        )
                    ),
                    array(
                        'customizer' => true,
                        'id' => 'shop_hero_customize_section_end',
                        'type' => 'section',
                        'indent' => false,
                        'required' => array(
                            array( 'shop_hero_visibility', '=', '1' ),
                            array( 'shop_hero_type', '=', 'default' )
                        )
                    )
                )
            );
            // SINGLE CONTENT SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Shop Page Content', 'styler'),
                'id' => 'shopcontentsubsection',
                'subsection' => true,
                'icon' => 'el el-brush',
                'fields' => array(
                    array(
                        'title' => esc_html__( 'Product Box Pre-layouts', 'styler' ),
                        'subtitle' => esc_html__( 'Choose the your product box type.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_product_type',
                        'type' => 'image_select',
                        'width' => '175',
                        'options' => array(
                            '1' => array(
                                'title' => 'Type 1',
                                'img' => get_template_directory_uri() . '/inc/core/theme-options/img/style-1.png',
                            ),
                            '2' => array(
                                'title' => 'Type 2',
                                'img' => get_template_directory_uri() . '/inc/core/theme-options/img/style-2.png',
                            ),
                            '3' => array(
                                'title' => 'Type 3',
                                'img' => get_template_directory_uri() . '/inc/core/theme-options/img/style-3.png',
                            ),
                            '4' => array(
                                'title' => 'Type 4',
                                'img' => get_template_directory_uri() . '/inc/core/theme-options/img/style-4.png',
                            ),
                            '5' => array(
                                'title' => 'Type 5',
                                'img' => get_template_directory_uri() . '/inc/core/theme-options/img/style-5.png',
                            ),
                            '6' => array(
                                'title' => 'Type 6',
                                'img' => get_template_directory_uri() . '/inc/core/theme-options/img/style-6.png',
                            ),
                            'custom' => array(
                                'title' => 'Custom',
                                'img' => get_template_directory_uri() . '/inc/core/theme-options/img/product1.png',
                            ),
                        ),
                        'default' => '1'
                    ),
                    array(
                        'id' =>'shop_loop_product_layouts',
                        'type' => 'sorter',
                        'title' => esc_html__( 'Custom Product Layouts', 'styler' ),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the shop loop product item.', 'styler' ),
                        'options' => array(
                            'show'  => array(
                                'thumb' => esc_html__( 'Image', 'styler' ),
                                'price-rating' => esc_html__( 'Price + Rating', 'styler' ),
                                'title-cart-hover' => esc_html__( 'Title + Add to Cart Hover', 'styler' ),
                                'buttons-hover' => esc_html__( 'Buttons Hover', 'styler' ),
                                'sale-discount' => esc_html__( 'Sale + Discount ( Top )', 'styler' ),
                                'stock' => esc_html__( 'Stock Status', 'styler' ),
                            ),
                            'hide'  => array(
                                'thumb-overlay' => esc_html__( 'Image + Image overlay', 'styler' ),
                                'gallery' => esc_html__( 'Slider Images', 'styler' ),
                                'title' => esc_html__( 'Title', 'styler' ),
                                'title-price' => esc_html__( 'Title + Price', 'styler' ),
                                'title-rating' => esc_html__( 'Title + Rating', 'styler' ),
                                'title-buttons-hover' => esc_html__( 'Title + Buttons Hover', 'styler' ),
                                'title-buttons-static' => esc_html__( 'Title + Buttons Static', 'styler' ),
                                'title-stock' => esc_html__( 'Title + Stock Status', 'styler' ),
                                'title-discount' => esc_html__( 'Title + Discount', 'styler' ),
                                'price' => esc_html__( 'Price', 'styler' ),
                                'price-text' => esc_html__( 'Price + Text', 'styler' ),
                                'price-stock' => esc_html__( 'Price + Stock', 'styler' ),
                                'price-buttons' => esc_html__( 'Price + Buttons', 'styler' ),
                                'price-cart-hover' => esc_html__( 'Price + Add to Cart Hover', 'styler' ),
                                'rating' => esc_html__( 'Rating Static', 'styler' ),
                                'rating-top' => esc_html__( 'Rating Top', 'styler' ),
                                'buttons-static' => esc_html__( 'Buttons Static', 'styler' ),
                                'cart' => esc_html__( 'Add to Cart Static', 'styler' ),
                                'cart-buttons' => esc_html__( 'Add to Cart + Buttons', 'styler' ),
                                'swatches' => esc_html__( 'Swatches Static', 'styler' ),
                                'swatches-hover' => esc_html__( 'Swatches Hover', 'styler' ),
                                'discount' => esc_html__( 'Discount', 'styler' ),
                                'sale' => esc_html__( 'Sale Label', 'styler' ),
                                'text' => esc_html__( 'Custom text', 'styler' ),
                            )
                        ),
                        'required' => array('shop_product_type', '=', 'custom' )
                    ),
                    array(
                        'id' =>'shop_loop_product_buttons_layouts',
                        'type' => 'sorter',
                        'title' => esc_html__( 'Custom Product Buttons Layouts', 'styler' ),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the shop loop product item buttons.', 'styler' ),
                        'options' => array(
                            'show'  => array(
                                'quickview' => esc_html__( 'Quick View', 'styler' ),
                                'compare' => esc_html__( 'Compare', 'styler' ),
                                'wishlist' => esc_html__( 'Wishlist', 'styler' ),
                            ),
                            'hide'  => array()
                        ),
                        'required' => array('shop_product_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Column', 'styler'),
                        'subtitle' => esc_html__('You can control post column with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_colxl',
                        'type' => 'slider',
                        'default' => 5,
                        'min' => 1,
                        'step' => 1,
                        'max' => 6,
                        'display_value' => 'text'
                    ),
                    array(
                        'title' => esc_html__('Column 992px ( Responsive: Desktop, Tablet )', 'styler'),
                        'subtitle' => esc_html__('You can control post column on max-device width 992px with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_collg',
                        'type' => 'slider',
                        'default' =>3,
                        'min' => 1,
                        'step' => 1,
                        'max' => 4,
                        'display_value' => 'text'
                    ),
                    array(
                        'title' => esc_html__('Column 768px ( Responsive: Tablet, Phone )', 'styler'),
                        'subtitle' => esc_html__('You can control post column on max-device-width 768px with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_colsm',
                        'type' => 'slider',
                        'default' =>2,
                        'min' => 1,
                        'step' => 1,
                        'max' => 3,
                        'display_value' => 'text'
                    ),
                    array(
                        'title' => esc_html__('Column 480px ( Responsive: Phone )', 'styler'),
                        'subtitle' => esc_html__('You can control post column on max-device-width 768px with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_colxs',
                        'type' => 'slider',
                        'default' =>2,
                        'min' => 1,
                        'step' => 1,
                        'max' => 2,
                        'display_value' => 'text'
                    ),
                    array(
                        'title' => esc_html__('Product Count for Per Page', 'styler'),
                        'subtitle' => esc_html__('You can control show post count with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_perpage',
                        'type' => 'slider',
                        'default' => 10,
                        'min' => 1,
                        'step' => 1,
                        'max' => 100,
                        'display_value' => 'text'
                    ),
                    array(
                        'title' => esc_html__('Use WooCommerce Product Images Customize Settings', 'styler'),
                        'subtitle' => esc_html__('if you want to set the product image size by WooCommerce Customize settings you can enable this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'use_wc_image_sizes',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__( 'Product Image Size', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_imgsize',
                        'type' => 'select',
                        'data' => 'image_sizes',
                        'required' => array( 'use_wc_image_sizes', '!=', '1' ),
                    ),
                    array(
                        'title' => esc_html__( 'Mobile Product Image Size', 'styler' ),
                        'subtitle' => esc_html__('Ipad,Iphone,Android etc', 'styler'),
                        'customizer' => true,
                        'id' => 'mobile_product_imgsize',
                        'type' => 'select',
                        'data' => 'image_sizes',
                        'required' => array( 'use_wc_image_sizes', '!=', '1' ),
                    ),
                    array(
                        'title' => esc_html__('Custom Product Image Size', 'styler'),
                        'subtitle' => esc_html__('if you want to set the product image custom size you can enable this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_loop_custom_image_size',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'use_wc_image_sizes', '!=', '1' ),
                    ),
                    array(
                        'title' => esc_html__('Dimensions', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_loop_custom_image_dimensions',
                        'type' => 'dimensions',
                        'units' => false,
                        'required' => array(
                            array( 'use_wc_image_sizes', '!=', '1' ),
                            array( 'shop_loop_custom_image_size', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Always keep this size', 'styler'),
                        'subtitle' => esc_html__('Preserves your image size while filtering your products by the number of columns on your store page', 'styler'),
                        'customizer' => true,
                        'id' => 'keep_image_size',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'use_wc_image_sizes', '!=', '1' ),
                    ),
                    array(
                        'title' => esc_html__( 'Page Content Padding', 'styler' ),
                        'subtitle' => esc_html__( 'You can set the top spacing of the site shop page content.', 'styler' ),
                        'customizer' => true,
                        'id' =>'shop_content_pad',
                        'type' => 'spacing',
                        'output' => array('#nt-shop-page .nt-styler-inner-container'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false'
                    ),
                    array(
                        'title' => esc_html__( 'Excerpt Size (for Shop page list type)', 'styler' ),
                        'subtitle' => esc_html__( 'You can control shop product excerpt size with this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_loop_excerpt_limit',
                        'type' => 'slider',
                        'default' => 17,
                        'min' => 0,
                        'step' => 1,
                        'max' => 300,
                        'display_value' => 'text'
                    ),
                    array(
                        'title' => esc_html__('Shop List Type Column for Per Row', 'styler'),
                        'subtitle' => esc_html__('You can control product column with this option for shop list type.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_list_type_colxl',
                        'type' => 'slider',
                        'default' => 2,
                        'min' => 1,
                        'step' => 1,
                        'max' => 3,
                        'display_value' => 'text'
                    )
                )
            );
            // SINGLE CONTENT SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Shop Elementor Template', 'styler'),
                'id' => 'shopaftercontentsubsection',
                'subsection' => true,
                'icon' => 'el el-brush',
                'fields' => array(
                    array(
                        'title' => esc_html__( 'Before Shop Content Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after hero section.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_before_content_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args
                    ),
                    array(
                        'title' => esc_html__( 'After Shop Content Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_after_content_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args
                    ),
                    array(
                        'title' => esc_html__( 'Before Loop Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content before products loop.Note:This option is only compatible with shop left sidebar and right sidebar layouts.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_before_loop_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args
                    ),
                    array(
                        'title' => esc_html__( 'After Loop Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products loop.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_after_loop_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args,
                    ),
                    array(
                        'title' => esc_html__( 'Category Pages Before Loop Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content before products loop.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_category_pages_before_loop_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args
                    ),
                    array(
                        'title' => esc_html__( 'Category Pages After Loop Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products loop.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_category_pages_after_loop_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args,
                    ),
                    array(
                        'title' => esc_html__( 'Tag Pages Before Loop Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content before products loop.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_tag_pages_before_loop_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args
                    ),
                    array(
                        'title' => esc_html__( 'Tag Pages After Loop Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products loop.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_tag_pages_after_loop_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args,
                    ),
                    array(
                        'title' => esc_html__( 'Category Pages Before Content Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content before products loop.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_category_pages_before_content_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args
                    ),
                    array(
                        'title' => esc_html__( 'Category Pages After Content Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products loop.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_category_pages_after_content_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args,
                    ),
                    array(
                        'title' => esc_html__( 'Tag Pages Before Content Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content before products loop.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_tag_pages_before_content_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args
                    ),
                    array(
                        'title' => esc_html__( 'Tag Pages After Content Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products loop.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_tag_pages_after_content_templates',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args,
                    )
                )
            );
            $sections[] = array(
                'title' => esc_html__('Shop Page Product Style', 'styler'),
                'id' => 'shoppoststylesubsection',
                'subsection' => true,
                'icon' => 'el el-brush',
                'fields' => array(
                    array(
                        'title' => esc_html__('Background Color', 'styler'),
                        'subtitle' => esc_html__('Change post background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_post_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.woocommerce.styler-product')
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'subtitle' => esc_html__('Set your custom border styles for the posts.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_post_brd',
                        'type' => 'border',
                        'all' => false,
                        'output' => array('.woocommerce.styler-product')
                    ),
                    array(
                        'title' => esc_html__('Padding', 'styler'),
                        'subtitle' => esc_html__('You can set the spacing of the site shop page post.', 'styler'),
                        'customizer' => true,
                        'id' =>'shop_post_pad',
                        'type' => 'spacing',
                        'output' => array('.woocommerce.styler-product'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false',
                        'default' => array(
                            'units' => 'px'
                        )
                    ),
                    // post button ( Add to cart )
                    array(
                        'title' => esc_html__('Product title', 'styler'),
                        'subtitle' => esc_html__('Change theme main color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_loop_post_title_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.styler-product .styler-product-name')
                    ),
                    array(
                        'title' => esc_html__('Price', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_loop_post_price_reg_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.woocommerce .styler-price')
                    ),
                    array(
                        'title' => esc_html__('Price Regular', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_loop_post_price_reg_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.woocommerce .styler-price del')
                    ),
                    array(
                        'title' => esc_html__('Price Sale', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_loop_post_price_sale_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.woocommerce .styler-price ins')
                    ),
                    array(
                        'title' => esc_html__('Discount Background', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_loop_post_discount_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.woocommerce .styler-label')
                    ),
                    // post button ( Add to cart )
                    array(
                        'title' => esc_html__('Button Background ( Add to cart )', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_addtocartbtn_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.woocommerce .styler-btn')
                    ),
                    array(
                        'title' => esc_html__('Hover Button Background ( Add to cart )', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_addtocartbtn_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.woocommerce .styler-btn:hover')
                    ),
                    array(
                        'title' => esc_html__('Button Title ( Add to cart )', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_addtocartbtn_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.woocommerce .styler-btn')
                    ),
                    array(
                        'title' => esc_html__('Hover Button Title ( Add to cart )', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_addtocartbtn_hvrcolor',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.woocommerce .styler-btn:hover')
                    ),
                    // post button ( view cart )
                    array(
                        'title' => esc_html__('Button Background ( View cart )', 'styler'),
                        'subtitle' => esc_html__('Change button background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_viewcartbtn_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.woocommerce.styler-product a.added_to_cart')
                    ),
                    array(
                        'title' => esc_html__('Hover Button Background ( View cart )', 'styler'),
                        'subtitle' => esc_html__('Change button hover background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_viewcartbtn_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.woocommerce.styler-product a.added_to_cart:hover')
                    ),
                    array(
                        'title' => esc_html__('Button Title ( View cart )', 'styler'),
                        'subtitle' => esc_html__('Change button title color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_viewcartbtn_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.woocommerce.styler-product a.added_to_cart')
                    ),
                    array(
                        'title' => esc_html__('Hover Button Title ( View cart )', 'styler'),
                        'subtitle' => esc_html__('Change button hover title color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_viewcartbtn_hvrcolor',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.woocommerce.styler-product a.added_to_cart')
                    ),
                    array(
                        'title' => esc_html__('Button Border ( View cart )', 'styler'),
                        'subtitle' => esc_html__('Change hover button border style.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_viewcartbtn_brd',
                        'type' => 'border',
                        'output' => array('.woocommerce.styler-product a.added_to_cart')
                    ),
                    array(
                        'title' => esc_html__('Hover Button Border ( View cart )', 'styler'),
                        'subtitle' => esc_html__('Change hover button border style.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_viewcartbtn_hvrbrd',
                        'type' => 'border',
                        'output' => array('.woocommerce.styler-product a.added_to_cart:hover')
                    ),
                    array(
                        'title' => esc_html__('Pagination Background Color', 'styler'),
                        'subtitle' => esc_html__('Change shop page pagination background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_pagination_bgcolor',
                        'type' => 'color',
                        'mode' => 'background',
                        'default' => '',
                        'output' => array('.woocommerce nav.styler-woocommerce-pagination ul li a, .woocommerce nav.styler-woocommerce-pagination ul li span')
                    ),
                    array(
                        'title' => esc_html__('Active Pagination Background Color', 'styler'),
                        'subtitle' => esc_html__('Change shop page pagination hover and active item background color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_pagination_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background',
                        'default' => '',
                        'output' => array('.woocommerce nav.styler-woocommerce-pagination ul li a:focus, .woocommerce nav.styler-woocommerce-pagination ul li a:hover, .woocommerce nav.styler-woocommerce-pagination ul li span.current')
                    ),
                    array(
                        'title' => esc_html__('Pagination Text Color', 'styler'),
                        'subtitle' => esc_html__('Change shop page pagination text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_pagination_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.woocommerce nav.styler-woocommerce-pagination ul li a, .woocommerce nav.styler-woocommerce-pagination ul li span')
                    ),
                    array(
                        'title' => esc_html__('Active Pagination Text Color', 'styler'),
                        'subtitle' => esc_html__('Change shop page pagination hover and active item text color.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_pagination_hvrcolor',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.woocommerce nav.styler-woocommerce-pagination ul li a:focus, .woocommerce nav.styler-woocommerce-pagination ul li a:hover, .woocommerce nav.styler-woocommerce-pagination ul li span.current')
                    )
                )
            );
            /*************************************************
            ## SINGLE PAGE SECTION
            *************************************************/
            // create sections in the theme options
            $sections[] = array(
                'title' => esc_html__('PRODUCT PAGE', 'styler'),
                'id' => 'singleshopsection',
                'icon' => 'el el-shopping-cart-sign'
            );
            $sections[] = array(
                'title' => esc_html__('Layout', 'styler'),
                'id' => 'singleshopgeneral',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'id' =>'single_shop_layout',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Page Sidebar Layouts', 'styler' ),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop page sidebar area.', 'styler' ),
                        'options' => array(
                            'left-sidebar' => esc_html__( 'Left Sidebar', 'styler' ),
                            'right-sidebar' => esc_html__( 'Right Sidebar', 'styler' ),
                            'full-width' => esc_html__( 'Full width ( no-sidebar )', 'styler' ),
                            'stretch' => esc_html__( 'Full width ( stretch )', 'styler' ),
                            'showcase' => esc_html__( 'Showcase Style', 'styler' )
                        ),
                        'default' => 'full-width'
                    ),
                    array(
                        'title' => esc_html__('Gallery Image Width', 'styler'),
                        'customizer' => true,
                        'id' => 'product_gallery_image_width',
                        'type' => 'dimensions',
                        'height' => false,
                        'unit' => false
                    ),
                    array(
                        'title' => esc_html__('Scroll to Top Behavior', 'styler'),
                        'subtitle' => esc_html__( 'You can use this option if you do not want the scroll to top when the variation is selected on the product page.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_scrolltop',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__( 'After Tabs Elementor Templates', 'styler' ),
                        'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after product page tabs section.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_stretch_elementor_template',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args,
                        'required' => array( 'single_shop_layout', '=', 'stretch' )
                    ),
                    array(
                        'id' =>'product_thumbs_layout',
                        'type' => 'button_set',
                        'title' => esc_html__('Gallery Layout Type', 'styler'),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page tumbs.', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'grid' => esc_html__( 'Grid', 'styler' ),
                            'slider' => esc_html__( 'Slider', 'styler' ),
                            'wc' => esc_html__( 'WooCommerce Default', 'styler' ),
                        ),
                        'default' => 'slider',
                        'required' => array( 'single_shop_layout', '!=', 'showcase' )
                    ),
                    array(
                        'title' => esc_html__('Gallery Thumbs Arrow', 'styler'),
                        'customizer' => true,
                        'id' => 'single_gallery_thumbs_arrow',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'product_thumbs_layout', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__('Carousel Slider', 'styler'),
                        'subtitle' => esc_html__( 'If you have problems with 3rd party plugins, disable this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_gallery_owl_carousel',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'product_thumbs_layout', '=', 'wc' )
                    ),
                    array(
                        'id' =>'product_gallery_slider_effect',
                        'type' => 'button_set',
                        'title' => esc_html__('Slider Effect', 'styler'),
                        'customizer' => true,
                        'options' => array(
                            'creative' => esc_html__( 'Creative', 'styler' ),
                            'slide' => esc_html__( 'Slide', 'styler' ),
                        ),
                        'default' => 'slide',
                        'required' => array( 'product_thumbs_layout', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__('Gallery Column Width', 'styler'),
                        'customizer' => true,
                        'id' => 'product_thumbs_column_width',
                        'type' => 'spinner',
                        'default' => '7',
                        'min' => '1',
                        'step' => '1',
                        'max' => '12'
                    ),
                    array(
                        'id' =>'styler_product_gallery_grid_column',
                        'type' => 'button_set',
                        'title' => esc_html__('Grid Column', 'styler'),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page tumbs.', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            '1' => esc_html__( '1 Column', 'styler' ),
                            '2' => esc_html__( '2 Column', 'styler' ),
                            '3' => esc_html__( '3 Column', 'styler' ),
                            '4' => esc_html__( '4 Column', 'styler' ),
                        ),
                        'default' => '2',
                        'required' => array(
                            array( 'single_shop_layout', '!=', 'showcase' ),
                            array( 'product_thumbs_layout', '=', 'grid' )
                        )
                    ),
                    array(
                        'id' =>'product_gallery_slider_layout',
                        'type' => 'button_set',
                        'title' => esc_html__('Slider Thumbs Type', 'styler'),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page tumbs.', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'top' => esc_html__( 'Horizontal Top', 'styler' ),
                            'bottom' => esc_html__( 'Horizontal Bottom', 'styler' ),
                            'left' => esc_html__( 'Vertical Left', 'styler' ),
                            'right' => esc_html__( 'Vertical Right', 'styler' ),
                        ),
                        'default' => 'bottom',
                        'required' => array(
                            array( 'single_shop_layout', '!=', 'showcase' ),
                            array( 'product_thumbs_layout', '=', 'slider' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Slider Thumbs Count', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_gallery_thumbs_count',
                        'type' => 'slider',
                        'default' => 8,
                        'min' => 2,
                        'step' => 1,
                        'max' => 10,
                        'required' => array(
                            array( 'single_shop_layout', '!=', 'showcase' ),
                            array( 'product_thumbs_layout', '=', 'slider' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Mobile Slider Thumbs Count', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_gallery_thumbs_mobile_count',
                        'type' => 'slider',
                        'default' => 6,
                        'min' => 2,
                        'step' => 1,
                        'max' => 10,
                        'required' => array(
                            array( 'single_shop_layout', '!=', 'showcase' ),
                            array( 'product_thumbs_layout', '=', 'slider' )
                        )
                    ),
                    array(
                        'id' =>'single_shop_showcase_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Showcase Slider Type', 'styler' ),
                        'subtitle' => esc_html__( 'Select theme shop page showcase type.', 'styler' ),
                        'options' => array(
                            'carousel' => esc_html__( 'Carousel', 'styler' ),
                            'full' => esc_html__( 'Full', 'styler' ),
                        ),
                        'default' => 'carousel',
                        'required' => array( 'single_shop_layout', '=', 'showcase' )
                    ),
                    array(
                        'id' =>'single_shop_showcase_full_effect_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Showcase Full Effect Type', 'styler' ),
                        'subtitle' => esc_html__( 'Select theme shop page showcase full type effect.', 'styler' ),
                        'options' => array(
                            'slide' => esc_html__( 'Slide', 'styler' ),
                            'flip' => esc_html__( 'flip', 'styler' ),
                            'fade' => esc_html__( 'fade', 'styler' ),
                            'creative' => esc_html__( 'creative', 'styler' ),
                        ),
                        'default' => 'slide',
                        'required' => array(
                            array( 'single_shop_layout', '=', 'showcase' ),
                            array( 'single_shop_showcase_type', '=', 'full' ),
                        )
                    ),
                    array(
                        'id' =>'single_shop_showcase_carousel_effect_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Showcase Carousel Effect Type', 'styler' ),
                        'subtitle' => esc_html__( 'Select theme shop page showcase carousel type effect.', 'styler' ),
                        'options' => array(
                            '' => esc_html__( 'Carousel', 'styler' ),
                            'coverflow' => esc_html__( 'Coverflow', 'styler' ),
                        ),
                        'default' => 'slide',
                        'required' => array(
                            array( 'single_shop_layout', '=', 'showcase' ),
                            array( 'single_shop_showcase_type', '=', 'carousel' ),
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Coverflow Rotate', 'styler' ),
                        'subtitle' => esc_html__( 'Set the rotate of the coverflow effect.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_showcase_carousel_coverflow_rotate',
                        'type' => 'slider',
                        'default' => 30,
                        'min' => -90,
                        'step' => 1,
                        'max' => 90,
                        'required' => array(
                            array( 'single_shop_layout', '=', 'showcase' ),
                            array( 'single_shop_showcase_type', '=', 'carousel' ),
                            array( 'single_shop_showcase_carousel_effect_type', '=', 'coverflow' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Slider Loop', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_showcase_carousel_loop',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_layout', '=', 'showcase' ),
                            array( 'single_shop_showcase_type', '=', 'carousel' ),
                        )
                    ),
                    array(
                        'id' =>'single_shop_showcase_bg_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Showcase BG Style', 'styler' ),
                        'subtitle' => esc_html__( 'Select theme shop page showcase background style.', 'styler' ),
                        'options' => array(
                            'light' => esc_html__( 'Light', 'styler' ),
                            'dark' => esc_html__( 'Dark', 'styler' ),
                            'custom' => esc_html__( 'Custom Color', 'styler' ),
                        ),
                        'default' => 'light',
                        'required' => array( 'single_shop_layout', '=', 'showcase' )
                    ),
                    array(
                        'title' => esc_html__('Showcase Background', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_showcase_custom_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-showcase.styler-bg-custom' ),
                        'required' => array(
                            array( 'single_shop_layout', '=', 'showcase' ),
                            array( 'single_shop_showcase_bg_type', '=', 'custom' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Showcase Text Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_showcase_custom_textcolor',
                        'type' => 'color',
                        'output' => array( '.styler-product-showcase.styler-bg-custom' ),
                        'required' => array(
                            array( 'single_shop_layout', '=', 'showcase' ),
                            array( 'single_shop_showcase_bg_type', '=', 'custom' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Showcase Content Padding', 'styler'),
                        'subtitle' => esc_html__('You can set the spacing of the site single page showcase content.', 'styler'),
                        'customizer' => true,
                        'id' =>'single_shop_showcase_content_pad',
                        'type' => 'spacing',
                        'output' => array('.product .styler-product-showcase.styler-bg-custom'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false',
                        'required' => array( 'single_shop_layout', '=', 'showcase' )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Sidebar', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_sticky_sidebar',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array(
                            array( 'single_shop_layout', '!=', 'full-width' ),
                            array( 'single_shop_layout', '!=', 'showcase' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Gallery Zoom Effect', 'styler'),
                        'subtitle' => esc_html__('You can enable or disable the site product image zoom option.', 'styler'),
                        'customizer' => true,
                        'id' => 'styler_product_zoom',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Reviews Section', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_review_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                )
            );
            // SINGLE HERO SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Product Header', 'styler'),
                'desc' => esc_html__('These are shop product page header section settings', 'styler'),
                'id' => 'singleshopheadersubsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Use Different Header Layouts', 'styler'),
                        'subtitle' => esc_html__('You can use different header layouts type on shop product pages.', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_different_header_layouts',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'id' =>'single_shop_header_layouts',
                        'type' => 'sorter',
                        'title' => esc_html__( 'Product Header Layout Manager', 'styler' ),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme header', 'styler' ),
                        'options' => array(
                            'left' => array(
                                'sidemenu' => esc_html__( 'Sidemenu Toggle', 'styler' ),
                                'logo' => esc_html__( 'Logo', 'styler' )
                            ),
                            'center'=> array(
                                'menu' => esc_html__( 'Main Menu', 'styler' )
                            ),
                            'right'=> array(
                                'search' => esc_html__( 'Search', 'styler' ),
                                'buttons' => esc_html__( 'Buttons', 'styler' )
                            ),
                            'hide'  => array(
                                'center-logo' => esc_html__( 'Menu Logo Menu', 'styler' ),
                                'mini-menu' => esc_html__( 'Mini Menu', 'styler' ),
                                'double-menu' => esc_html__( 'Double Menu', 'styler' ),
                                'custom-html' => esc_html__( 'Phone Number', 'styler' )
                            )
                        ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_layouts', '=', '1' )
                        )
                    ),
                    array(
                        'id' =>'single_shop_header_buttons_layouts',
                        'type' => 'sorter',
                        'title' => esc_html__( 'Product Header Buttons Manager', 'styler' ),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme header for buttons', 'styler' ),
                        'options' => array(
                            'show'  => array(
                                'cart' => esc_html__( 'Cart', 'styler' ),
                                'wishlist' => esc_html__( 'Wishlist', 'styler' ),
                                'compare' => esc_html__( 'Compare', 'styler' ),
                                'account' => esc_html__( 'Account', 'styler' )
                            ),
                            'hide'  => array(
                                'search' => esc_html__( 'Search', 'styler' )
                            )
                        ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_layouts', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Use Different Header Background Type', 'styler'),
                        'subtitle' => esc_html__('You can use different header background type on product page.', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_different_header_bg_type',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'id' => 'single_shop_header_menu_items_customize_start',
                        'type' => 'section',
                        'title' => esc_html__('Header Customize Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' =>'single_shop_header_bg_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Header Background Type', 'styler' ),
                        'subtitle' => esc_html__( 'Select your header background type.', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'Deafult', 'styler' ),
                            'dark' => esc_html__( 'Dark', 'styler' ),
                            'trans-light' => esc_html__( 'Transparent Light', 'styler' ),
                            'trans-dark' => esc_html__( 'Transparent Dark', 'styler' )
                        ),
                        'default' => 'default',
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Header Background Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_header_bg',
                        'type' => 'color_rgba',
                        'mode' => 'background-color',
                        'output' => array( '.single.single-product header.styler-header-default,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Menu Item Color', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_nav_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product .styler-header-top-menu-area>ul>li.menu-item>a,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .styler-header-top-menu-area>ul>li.menu-item>a' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_nav_hvr_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product .current-menu-parent>a,.single.single-product .current-menu-item>a,.single.single-product .styler-header-top-menu-area>ul>li.menu-item>a:hover,.single.single-product .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .current-menu-parent>a,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .current-menu-item>a,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .styler-header-top-menu-area>ul>li.menu-item>a:hover,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover'),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Header Background Color', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_nav_top_sticky_bg',
                        'type' => 'color_rgba',
                        'mode' => 'background-color',
                        'output' => array( '.single.single-product.has-sticky-header.scroll-start header.styler-header-default' ),
                        'required' => array( 'header_sticky_visibility', '=', '1' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Menu Item Color', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_sticky_nav_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product.has-sticky-header.scroll-start .styler-header-top-menu-area>ul>li.menu-item>a' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Menu Item Color ( Hover and Active )', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_sticky_nav_hvr_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product.has-sticky-header.scroll-start .current-menu-parent>a, .single.single-product.has-sticky-header.scroll-start .current-menu-item>a, .single.single-product.has-sticky-header.scroll-start .styler-header-top-menu-area>ul>li.menu-item>a:hover, .single.single-product.has-sticky-header.scroll-start .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'single_shop_header_menu_items_style_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'single_shop_header_submenu_items_style_start',
                        'type' => 'section',
                        'title' => esc_html__('Header Sub Menu Customize Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sub Menu Background Color', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_nav_submenu_bg',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product .styler-header-top-menu-area ul li .submenu' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Menu Item Color', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_nav_submenu_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product .styler-header-top-menu-area ul li .submenu>li.menu-item>a' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'styler' ),
                        'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_nav_submenu_hvr_a',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.single.single-product .styler-header-top-menu-area ul li .submenu>li.menu-item.active>a' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'single_shop_header_submenu_items_style_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'single_shop_header_svgbuttons_items_style_start',
                        'type' => 'section',
                        'title' => esc_html__('Header SVG Buttons Customize Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'SVG Icon Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare, Account, Search, Sidemenu bar', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_header_buttons_svg_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'validate' => 'color',
                        'output' => array( '.single.single-product .styler-header-default .header-top-buttons .top-action-btn,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .header-top-buttons .top-action-btn' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Button Counter Background Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_header_buttons_counter_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product .styler-header-default .styler-wc-count,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .styler-wc-count' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Button Counter Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_header_buttons_counter_color',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product .styler-header-default .styler-wc-count,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.styler-header-default .styler-wc-count' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Header SVG Icon Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare, Account, Search, Sidemenu bar', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_sticky_header_buttons_svg_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'validate' => 'color',
                        'output' => array( '.single.single-product.scroll-start .styler-header-default .header-top-buttons .top-action-btn,.single.single-product.has-default-header-type-trans.scroll-start header.styler-header-default .header-top-buttons .top-action-btn' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Header Button Counter Background Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_sticky_header_buttons_counter_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product.scroll-start .styler-header-default .styler-wc-count,.single.single-product.has-default-header-type-trans.scroll-start header.styler-header-default .styler-wc-count' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Header Button Counter Color', 'styler' ),
                        'desc' => esc_html__( 'Cart, Wishlist, Compare', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_sticky_header_buttons_counter_color',
                        'type' => 'color',
                        'validate' => 'color',
                        'output' => array( '.single.single-product.scroll-start .styler-header-default .styler-wc-count,.single.single-product.has-default-header-type-trans.scroll-start header.styler-header-default .styler-wc-count' ),
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'single_shop_header_svgbuttons_items_style_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array(
                            array( 'header_visibility', '=', '1' ),
                            array( 'header_template', '!=', 'elementor' ),
                            array( 'single_shop_different_header_bg_type', '=', '1' )
                        )
                    )
                )
            );
            // SHOP PAGE SECTION
            $sections[] = array(
                'title' => esc_html__('Products Navigation', 'styler'),
                'id' => 'singleshophero',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Product Hero Next/Prev', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_nav_visibility',
                        'type' => 'switch',
                        'default' => 1
                    )
                )
            );
            // SHOP PAGE SECTION
            $sections[] = array(
                'title' => esc_html__('Variable Products Terms (Swatches)', 'styler'),
                'id' => 'singleshopvariations',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Theme Swatches', 'styler'),
                        'customizer' => true,
                        'id' => 'swatches_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'id' => 'styler_product_variations_attr_start',
                        'type' => 'section',
                        'title' => esc_html__('Product Attribute Terms Customize Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'id' =>'variations_terms_shape',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Terms Box Type', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'default', 'styler' ),
                            'circle' => esc_html__( 'circle', 'styler' ),
                            'square' => esc_html__( 'square', 'styler' ),
                            'radius' => esc_html__( 'radius', 'styler' )
                        ),
                        'default' => 'default'
                    ),
                    array(
                        'title' => esc_html__('Outline', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_bordered',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__( 'Term Size', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_attr_term_size',
                        'type' => 'dimensions',
                        'output' => array('.styler-terms .styler-term'),
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_term_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-terms .styler-term')
                    ),
                    array(
                        'title' => esc_html__('Active Term Border Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_term_active_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-terms .styler-enabled.styler-selected')
                    ),
                    array(
                        'title' => esc_html__('Disabled Term Border Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_term_inactive_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-terms .styler-disabled')
                    ),
                    array(
                        'title' => esc_html__('Disabled Terms Opacity', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_disabled_terms_opacity',
                        'type' => 'slider',
                        'default' => '',
                        'min' => 0,
                        'step' => 0.01,
                        'max' => 1,
                        'resolution' => 0.01,
                        'display_value' => 'text'
                    ),
                    array(
                        'title' => esc_html__('Hide Checked / Closed Icon', 'styler'),
                        'customizer' => true,
                        'id' => 'variations_terms_checked_closed_icon_visibility',
                        'type' => 'switch',
                        'default' => 0,
                        'on' => esc_html__( 'Yes', 'styler' ),
                        'off' => esc_html__( 'No', 'styler' )
                    ),
                    array(
                        'title' => esc_html__( 'Attribute Customize ( for type Color )', 'styler' ),
                        'indent' => false,
                        'id' => 'product_attr_type_color_term_divide',
                        'type' => 'info'
                    ),
                    array(
                        'title' => esc_html__( 'Term Size', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_attr_type_color_term_size',
                        'type' => 'dimensions',
                        'output' => array('.styler-terms.styler-type-color:not(.outline-1) .styler-term,.styler-terms.styler-type-color.outline-1 .styler-term > span'),
                    ),
                    array(
                        'title' => esc_html__('Padding', 'styler'),
                        'customizer' => true,
                        'id' =>'product_attr_type_color_term_pad',
                        'type' => 'spacing',
                        'output' => array('.styler-terms.styler-type-color .styler-term'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false',
                        'default' => array('units' => 'px')
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_color_term_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-terms.styler-type-color .styler-term')
                    ),
                    array(
                        'title' => esc_html__('Active Term Border Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_color_term_active_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-terms.styler-type-color .styler-enabled.styler-selected')
                    ),
                    array(
                        'title' => esc_html__( 'Attribute Customize ( for type Button )', 'styler' ),
                        'indent' => false,
                        'id' => 'product_attr_type_button_term_divide',
                        'type' => 'info'
                    ),
                    array(
                        'title' => esc_html__( 'Term Size', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_attr_type_button_term_size',
                        'type' => 'dimensions',
                        'output' => array('.styler-terms.styler-type-button .styler-term'),
                    ),
                    array(
                        'title' => esc_html__('Padding', 'styler'),
                        'customizer' => true,
                        'id' =>'product_attr_type_button_term_pad',
                        'type' => 'spacing',
                        'output' => array('.styler-terms.styler-type-button .styler-term'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false',
                        'default' => array('units' => 'px')
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_button_term_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-terms.styler-type-button .styler-term')
                    ),
                    array(
                        'title' => esc_html__('Active Term Border Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_button_term_active_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-terms.styler-type-button .styler-enabled.styler-selected')
                    ),
                    array(
                        'title' => esc_html__('Term Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_button_term_color',
                        'type' => 'color',
                        'output' => array( '.styler-terms.styler-type-button .styler-term' ),
                    ),
                    array(
                        'title' => esc_html__('Active Term Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_button_term_active_color',
                        'type' => 'color',
                        'output' => array( '.styler-terms.styler-type-button .styler-enabled.styler-selected' ),
                    ),
                    array(
                        'title' => esc_html__('Term Backgorund Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_button_term_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-terms.styler-type-button .styler-term' ),
                    ),
                    array(
                        'title' => esc_html__('Active Term Backgorund Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_button_term_active_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-terms.styler-type-button .styler-enabled.styler-selected' ),
                    ),
                    array(
                        'title' => esc_html__( 'Attribute Customize ( for type Image )', 'styler' ),
                        'indent' => false,
                        'id' => 'product_attr_type_image_term_divide',
                        'type' => 'info'
                    ),
                    array(
                        'title' => esc_html__( 'Term Size', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_attr_type_image_term_size',
                        'type' => 'dimensions',
                        'output' => array('.styler-terms.styler-type-image .styler-term'),
                    ),
                    array(
                        'title' => esc_html__('Padding', 'styler'),
                        'customizer' => true,
                        'id' =>'product_attr_type_image_term_pad',
                        'type' => 'spacing',
                        'output' => array('.styler-terms.styler-type-image .styler-term'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false',
                        'default' => array('units' => 'px')
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_image_term_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-terms.styler-type-image .styler-term')
                    ),
                    array(
                        'title' => esc_html__('Active Term Border Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_attr_type_image_term_active_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-terms.styler-type-image .styler-enabled.styler-selected')
                    ),
                    array(
                        'id' => 'styler_product_variations_attr_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    ),
                    array(
                        'id' => 'product_attr_title_typo_start',
                        'type' => 'section',
                        'title' => esc_html__('Attribute Title Customize Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__( 'Attribute Title Typography', 'styler' ),
                        'id' => 'product_attr_title_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.styler-variations-items .styler-small-title' )
                    ),
                    array(
                        'id' => 'product_attr_title_typo_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    ),
                    //
                    array(
                        'id' => 'styler_product_variations_terms_start',
                        'type' => 'section',
                        'title' => esc_html__('Attribute Hints Customize Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Show Hints', 'styler'),
                        'customizer' => true,
                        'id' => 'variations_terms_hints_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__('Hints Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_hints_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.show-hints .styler-terms .styler-term .term-hint' ),
                        'required' => array( 'variations_terms_hints_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Hints Arrow Color', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_hints_bgcolor2',
                        'type' => 'color',
                        'mode' => 'border-top-color',
                        'output' => array( '.show-hints .styler-terms .styler-term .term-hint:before' ),
                        'required' => array( 'variations_terms_hints_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Hints Text Color', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_hints_titlecolor',
                        'type' => 'color',
                        'output' => array( '.show-hints .styler-terms .styler-term .term-hint' ),
                        'required' => array( 'variations_terms_hints_visibility', '=', '1' )
                    ),
                    array(
                        'id' => 'styler_product_variations_terms_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    ),
                    //
                    array(
                        'id' => 'styler_product_selected_variations_terms_start',
                        'type' => 'section',
                        'title' => esc_html__('Product Selected Variations Customize Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Show Selected Variations', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_terms_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Select Terms Title', 'styler'),
                        'customizer' => true,
                        'id' => 'select_variations_terms_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array( 'selected_variations_terms_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Selected Terms Title', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_terms_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array( 'selected_variations_terms_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Selected Terms Title Typography', 'styler' ),
                        'id' => 'selected_variations_terms_title_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.styler-selected-variations-terms-wrapper .styler-selected-variations-terms-title, .styler-select-variations-terms-title' ),
                        'required' => array( 'selected_variations_terms_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Title Color', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_terms_titlecolor',
                        'type' => 'color',
                        'output' => array( '.styler-selected-variations-terms-wrapper .styler-selected-variations-terms-title, .styler-select-variations-terms-title' ),
                        'required' => array( 'selected_variations_terms_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_terms_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-selected-variations-terms-wrapper .styler-selected-variations-terms' ),
                        'required' => array( 'selected_variations_terms_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_terms_brd',
                        'type' => 'border',
                        'all' => false,
                        'output' => array('.styler-selected-variations-terms-wrapper .styler-selected-variations-terms'),
                        'required' => array( 'selected_variations_terms_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border Radius', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_terms_brd_radius',
                        'type' => 'slider',
                        'default' => 4,
                        'min' => 0,
                        'step' => 1,
                        'max' => 100,
                        'display_value' => 'text',
                        'required' => array( 'selected_variations_terms_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Padding', 'styler'),
                        'customizer' => true,
                        'id' =>'selected_variations_terms_pad',
                        'type' => 'spacing',
                        'output' => array('.styler-selected-variations-terms-wrapper .styler-selected-variations-terms'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false',
                        'default' => array(
                            'units' => 'px'
                        ),
                        'required' => array( 'selected_variations_terms_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Terms Color', 'styler'),
                        'customizer' => true,
                        'id' => 'selected_variations_terms_value_color',
                        'type' => 'color',
                        'output' => array( '.styler-selected-variations-terms-wrapper .selected-features' ),
                        'required' => array( 'selected_variations_terms_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Selected Terms Typography', 'styler' ),
                        'id' => 'selected_variations_terms_value_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.styler-selected-variations-terms-wrapper .selected-features' ),
                        'required' => array( 'selected_variations_terms_visibility', '=', '1' )
                    ),
                    array(
                        'id' => 'styler_product_selected_variations_terms__end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    ),
                )
            );
            // SHOP PAGE SECTION
            $sections[] = array(
                'title' => esc_html__('Summary', 'styler'),
                'id' => 'singleshoptumbssection',
                'subsection' => true,
                'icon' => 'fa fa-cog',
                'fields' => array(
                    array(
                        'id' => 'styler_product_summary_start',
                        'type' => 'section',
                        'title' => esc_html__('Summary Elements Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'id' =>'single_shop_summary_layout_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Product Summary Layouts', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'Default', 'styler' ),
                            'custom' => esc_html__( 'Custom Layout', 'styler' )
                        ),
                        'default' => 'default'
                    ),
                    array(
                        'id' =>'single_shop_summary_layouts',
                        'type' => 'sorter',
                        'title' => esc_html__( 'Product Summary Layouts Manager', 'styler' ),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme product page summary area.', 'styler' ),
                        'options' => array(
                            'show' => array(
                                'bread' => esc_html__( 'Breadcrumbs', 'styler' ),
                                'title' => esc_html__( 'Title', 'styler' ),
                                'rating' => esc_html__( 'Rating', 'styler' ),
                                'price' => esc_html__( 'Price', 'styler' ),
                                'excerpt' => esc_html__( 'Excerpt', 'styler' ),
                                'cart' => esc_html__( 'Cart', 'styler' ),
                                'meta' => esc_html__( 'Meta', 'styler' ),
                            ),
                            'hide' => array()
                        ),
                        'required' => array( 'single_shop_summary_layout_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__( 'Sticky Summary', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_sticky_summary',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__('Products All Labels', 'styler'),
                        'subtitle' => esc_html__('Sale, Stock, Discount', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_top_labels_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'id' =>'single_button_actions_position',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Wishlist/Compare Position', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'Default', 'styler' ),
                            'after-cart' => esc_html__( 'Bottom Add to Cart', 'styler' )
                        ),
                        'default' => 'default'
                    ),
                    array(
                        'title' => esc_html__('Product Brand Image Visibility', 'styler'),
                        'customizer' => true,
                        'id' => 'product_page_brand_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'id' => 'styler_product_summary_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    ),
                    array(
                        'id' => 'product_delivery_template_start',
                        'type' => 'section',
                        'title' => esc_html__('Delivery & Return Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__( 'Delivery & Return Template', 'styler' ),
                        'subtitle' => esc_html__( 'Select an elementor template from list', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_delivery_template',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args
                    ),
                    array(
                        'title' => esc_html__( 'Category(s) Exclude ( Delivery & Return )', 'styler' ),
                        'subtitle' => esc_html__( 'Select category(s) from the list.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_delivery_template_category_exclude',
                        'type' => 'select',
                        'data' => 'terms',
                        'multi' => true,
                        'args'  => [
                            'taxonomies' => array( 'product_cat' ),
                        ],
                        'required' => array( 'single_shop_delivery_template', '!=', '' )
                    ),
                    array(
                        'title' => esc_html__('Title for Delivery & Return', 'styler'),
                        'subtitle' => esc_html__('Default: Delivery & Return', 'styler'),
                        'customizer' => true,
                        'id' => 'product_delivery_return_title',
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'title' => esc_html__('Title Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_delivery_return_title_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-product-popup-details .styler-product-delivery-btn .styler-open-popup' ),
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Icon for Delivery & Return', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_delivery_icon',
                        'type' => 'textarea',
                        'default' => ''
                    ),
                    array(
                        'title' => esc_html__('Icon Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_delivery_return_icon_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-product-summary .styler-product-popup-details .styler-product-delivery-btn .styler-svg-icon' ),
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'id' => 'product_delivery_template_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    ),
                    array(
                        'id' => 'product_question_form_template_start',
                        'type' => 'section',
                        'title' => esc_html__('Size Guide Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__( 'Size Guide Template', 'styler' ),
                        'subtitle' => esc_html__( 'Select an elementor template from list', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_question_form_template',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => $el_args
                    ),
                    array(
                        'title' => esc_html__( 'Category(s) Exclude ( Size Guide )', 'styler' ),
                        'subtitle' => esc_html__( 'Select category(s) from the list.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_question_template_category_exclude',
                        'type' => 'select',
                        'data' => 'terms',
                        'multi' => true,
                        'args'  => [
                            'taxonomies' => array( 'product_cat' ),
                        ],
                        'required' => array( 'single_shop_question_form_template', '!=', '' )
                    ),
                    array(
                        'title' => esc_html__('Title for Size Guide', 'styler'),
                        'subtitle' => esc_html__('Default: Size Guide', 'styler'),
                        'customizer' => true,
                        'id' => 'product_shop_question_title',
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'title' => esc_html__('Title Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_shop_question_title_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-product-popup-details .styler-product-question-btn .styler-open-popup' ),
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Icon for Size Guide', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_question_icon',
                        'type' => 'textarea',
                        'default' => ''
                    ),
                    array(
                        'title' => esc_html__('Icon Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_shop_question_icon_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-product-summary .styler-product-popup-details .styler-product-question-btn .styler-svg-icon' ),
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'id' => 'product_question_form_template_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    ),
                    array(
                        'id' => 'product_meta_start',
                        'type' => 'section',
                        'title' => esc_html__('Meta Options', 'styler'),
                        'subtitle' => esc_html__('SKU, Categories, Tags', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Meta', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_meta_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'id' => 'product_meta_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    ),
                    array(
                        'id' => 'product_estimated_delivery_start',
                        'type' => 'section',
                        'title' => esc_html__('Estimated Delivery Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Estimated Delivery', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_estimated_delivery_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__('Title for Estimated Delivery', 'styler'),
                        'subtitle' => esc_html__('Default: Estimated Delivery', 'styler'),
                        'customizer' => true,
                        'id' => 'product_estimated_delivery_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Title Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_estimated_delivery_title_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-product-popup-details .styler-estimated-delivery span' ),
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Date Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_estimated_delivery_date_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-product-popup-details .styler-estimated-delivery' ),
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Icon for Estimated Delivery', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_estimated_delivery_icon',
                        'type' => 'textarea',
                        'default' => '',
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Icon Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_estimated_delivery_icon_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-product-summary .styler-product-popup-details .styler-estimated-delivery .styler-svg-icon' ),
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Estimated Delivery ( Min )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_min_estimated_delivery',
                        'type' => 'spinner',
                        'default' => '3',
                        'min' => '1',
                        'step' => '1',
                        'max' => '31',
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Estimated Delivery ( Max )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_max_estimated_delivery',
                        'type' => 'spinner',
                        'default' => '7',
                        'min' => '1',
                        'step' => '1',
                        'max' => '31',
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'id' => 'product_estimated_delivery_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    ),
                    array(
                        'id' => 'product_visitor_count_start',
                        'type' => 'section',
                        'title' => esc_html__('Fake Visitor Count Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Fake Visitor Count', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_visit_count_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_visit_count_title_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-product-popup-details .styler-product-view span' ),
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color 2', 'styler'),
                        'customizer' => true,
                        'id' => 'product_visit_count_title_color2',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-product-popup-details .styler-product-view span' ),
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Icon for Visitor Count', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_visit_count_icon',
                        'type' => 'textarea',
                        'default' => '',
                        'required' => array( 'single_shop_visit_count_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Icon Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_visit_count_icon_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-product-summary .styler-product-popup-details .styler-product-view .styler-svg-icon' ),
                        'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Fake Visitor Count ( Min )', 'styler'),
                        'customizer' => true,
                        'id' => 'visit_count_min',
                        'type' => 'spinner',
                        'default' => '10',
                        'min' => '1',
                        'step' => '1',
                        'max' => '100',
                        'required' => array( 'single_shop_visit_count_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Fake Visitor Count ( Max )', 'styler'),
                        'customizer' => true,
                        'id' => 'visit_count_max',
                        'type' => 'spinner',
                        'default' => '50',
                        'min' => '1',
                        'step' => '1',
                        'max' => '100',
                        'required' => array( 'single_shop_visit_count_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Fake Visitor Count ( Delay )', 'styler'),
                        'customizer' => true,
                        'id' => 'visit_count_delay',
                        'type' => 'spinner',
                        'default' => '30000',
                        'min' => '1000',
                        'step' => '100',
                        'max' => '100000',
                        'required' => array( 'single_shop_visit_count_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Fake Visitor Count ( Change )', 'styler'),
                        'customizer' => true,
                        'id' => 'visit_count_change',
                        'type' => 'spinner',
                        'default' => '5',
                        'min' => '1',
                        'step' => '1',
                        'max' => '50',
                        'required' => array( 'single_shop_visit_count_visibility', '=', '1' )
                    ),
                    array(
                        'id' => 'product_visitor_count_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    ),
                    array(
                        'id' => 'product_extra_start',
                        'type' => 'section',
                        'title' => esc_html__('Popup Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Title Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_extra_title_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-summary-item.styler-product-popup-details .styler-open-popup,.styler-product-summary .styler-summary-item.styler-product-popup-details span' )
                    ),
                    array(
                        'title' => esc_html__('Icon Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_extra_icon_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-product-summary .styler-summary-item.styler-product-popup-details .styler-svg-icon' )
                    ),
                    array(
                        'id' => 'product_extra_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false
                    )
                )
            );
            $sections[] = array(
                'title' => esc_html__('Countdown', 'styler'),
                'id' => 'product_countdown_subsection',
                'subsection' => true,
                'icon' => 'fa fa-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Countdown', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_countdown_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('General Date', 'styler'),
                        'subtitle' => esc_html__('If you want to use different time for each product, you can set or change time in product settings', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_date',
                        'type' => 'date',
                        'placeholder' => 'Click to enter a date',
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Update Countdown When Expires', 'styler'),
                        'subtitle' => esc_html__('When the time is expired, update the date every next X days', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_update',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Update Per Next Day', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_update_next',
                        'type' => 'text',
                        'default' => '7',
                        'validate' => array('numeric'),
                        'required' => array(
                            array( 'single_shop_countdown_visibility', '=', '1' ),
                            array( 'product_countdown_update', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Expired Text', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_expired_text',
                        'type' => 'textarea',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_countdown_visibility', '=', '1' ),
                            array( 'product_countdown_update', '=', '0' ),
                        )
                    ),
                    array(
                        'id' =>'product_countdown_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Type', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'Default', 'styler' ),
                            '1' => esc_html__( 'Type 1', 'styler' ),
                            '2' => esc_html__( 'Type 2', 'styler' ),
                            '3' => esc_html__( 'Type 3', 'styler' ),
                            '4' => esc_html__( 'Type 4', 'styler' )
                        ),
                        'default' => 'default',
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'id' =>'product_countdown_icon',
                        'type' => 'textarea',
                        'title' => esc_html__( 'Icon ( SVG or HTML )', 'styler' ),
                        'customizer' => true,
                        'default' => '<svg class="svgFlash svg-icon" fill="currentColor" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g id="a"><g><path d="m452.51 194.38c3.59-3.25 21.83-19.17 24.92-21.99 5 5.57 9.58 10.59 10.24 11.13 2.28 2.05 6.34 2.46 9.4.71 1.83-1.06 4.24-2.64 7.18-5.21 2.93-2.58 4.81-4.76 6.11-6.45 2.15-2.81 2.32-6.93.62-9.5-.96-1.62-20.53-25.25-22.82-27.75-2.13-2.64-22.54-25.53-24-26.72-2.28-2.06-6.34-2.46-9.4-.71-1.83 1.06-4.24 2.64-7.18 5.21-2.93 2.58-4.81 4.76-6.11 6.44-2.15 2.81-2.32 6.93-.62 9.5.44.74 4.72 6.02 9.47 11.8-2.85 2.39-20.75 17.81-24.02 20.59l26.21 32.94z" fill="#454565"></path><path d="m356.57 126.14c.5-4.1 5.2-25.34 5.62-28.97 11.36-.21 21.68-.47 22.98-.67 4.69-.51 9.73-4.21 11.42-8.77 1-2.74 2.14-6.49 2.87-11.63.71-5.14.63-8.89.4-11.63-.41-4.55-4.41-8.25-8.95-8.77-2.74-.44-49.07-1.17-54.22-1.03-5.11-.14-51.64.59-54.5 1.03-4.69.51-9.73 4.22-11.42 8.77-1 2.74-2.14 6.49-2.87 11.63-.71 5.13-.63 8.89-.4 11.63.41 4.55 4.41 8.25 8.95 8.77 1.25.2 11.5.46 22.79.67-.59 3.63-5.47 24.87-6.12 28.97h63.44z" fill="#454565"></path><rect fill="#f04760" height="37.83" rx="18.91" width="37.83" x="15.97" y="225.7"></rect><path d="m327.25 121.9c-34.31 0-67.66 10.31-96.71 27.99l-67.56-.03h-.13l-.06-.02-.04.02h-116.87c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86l92.75.05c9.78.7 17.49 8.85 17.49 18.81v.19c0 10.42-8.45 18.86-18.86 18.86h-51.97c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86h20.4c10.42 0 18.86 8.45 18.86 18.86v.19c0 10.42-8.45 18.86-18.86 18.86h-86.71c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86h101.67c10.42 0 18.86 8.44 18.86 18.86v.19c0 10.42-8.45 18.86-18.86 18.86h-49.4c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86h103.7c25.91 26.16 62.55 42.06 105.15 42.06 92.63 0 178.27-75.09 191.29-167.72s-51.52-167.72-144.15-167.72z" fill="#e03757"></path><path d="m135.64 369.91c131.56-6.76 238.81-105.43 258.84-233.05-19.78-9.61-42.51-14.96-67.24-14.96-34.31 0-67.66 10.31-96.71 27.99l-67.56-.03h-.13l-.06-.02-.04.02h-116.86c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86l92.75.05c9.78.7 17.49 8.85 17.49 18.81v.19c0 10.42-8.45 18.86-18.86 18.86h-51.97c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86h20.4c10.42 0 18.86 8.45 18.86 18.86v.19c0 10.42-8.45 18.86-18.86 18.86h-86.71c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86h101.67c10.42 0 18.86 8.44 18.86 18.86v.19c0 4.29-1.45 8.24-3.87 11.41z" fill="#f04760"></path><path d="m389.77 272.6-79.02 121.93c-1.82 2.8-4.93 4.49-8.27 4.49h-6.19c-6.38 0-11.08-5.97-9.57-12.17l19.47-80.36h-47.47c-5.69 0-9.88-5.32-8.54-10.85l26.34-108.72c.95-3.94 4.48-6.72 8.54-6.72h54.62c5.69 0 9.88 5.32 8.54 10.85l-16.07 66.33h49.35c7.81 0 12.51 8.65 8.27 15.21z"></path></g></g></svg>',
                        'required' => array(
                            array( 'single_shop_countdown_visibility', '=', '1' ),
                            array( 'product_countdown_type', '=', '4' )
                        )
                    ),
                    array(
                        'id' =>'product_countdown_separator_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Separator Type', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'none' => esc_html__( 'None', 'styler' ),
                            '1' => esc_html__( 'Type ( : )', 'styler' ),
                            '2' => esc_html__( 'Type ( / )', 'styler' ),
                            '3' => esc_html__( 'Type ( - )', 'styler' ),
                            '4' => esc_html__( 'Type ( | )', 'styler' )
                        ),
                        'default' => '1',
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Before Countdown Text', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_before_text',
                        'type' => 'textarea',
                        'default' => '',
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('After Countdown Text', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_after_text',
                        'type' => 'textarea',
                        'default' => '',
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'id' =>'product_countdown_separator_width',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Width', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'default' => esc_html__( 'Default', 'styler' ),
                            'full' => esc_html__( 'Fullwidth', 'styler' ),
                        ),
                        'default' => '1',
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-default .styler-coming-time,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-1,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-3' ),
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-default .styler-coming-time,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-1,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-2,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-3'),
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Padding', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_padding',
                        'type' => 'spacing',
                        'mode' => 'padding',
                        'all' => true,
                        'output' => array('.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-default .styler-coming-time,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-1,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-2,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-3'),
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Before Countdown Text Typography', 'styler' ),
                        'id' => 'product_countdown_before_text_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.styler-product-summary .styler-viewed-offer-time .offer-time-text' ),
                    ),
                    array(
                        'title' => esc_html__( 'After Countdown Text Typography', 'styler' ),
                        'id' => 'product_countdown_after_text_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.styler-product-summary .styler-viewed-offer-time .offer-time-text-after' ),
                    ),
                    array(
                        'title' => esc_html__( 'Number Typography', 'styler' ),
                        'id' => 'product_countdown_number_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.styler-product-summary .styler-summary-item .styler-coming-time .time-count' ),
                    ),
                    array(
                        'title' => esc_html__('Number Border', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_number_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-default .styler-coming-time .time-count,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-1 .styler-coming-time .time-count span,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-2 .styler-coming-time .time-count span,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-3 .styler-coming-time .time-count span'),
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Number Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_number_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array('.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-default .styler-coming-time .time-count,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-1 .styler-coming-time .time-count span,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-2 .styler-coming-time .time-count span,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-3 .styler-coming-time .time-count span'),
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Number Padding', 'styler'),
                        'customizer' => true,
                        'id' => 'product_countdown_number_padding',
                        'type' => 'spacing',
                        'mode' => 'padding',
                        'all' => true,
                        'output' => array('.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-default .styler-coming-time .time-count,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-1 .styler-coming-time .time-count span,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-2 .styler-coming-time .time-count span,.styler-product-summary .styler-summary-item.styler-viewed-offer-time.type-3 .styler-coming-time .time-count span'),
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Shortcode', 'styler'),
                        'subtitle' => sprintf(esc_html__('if you need a shortcode you can use %s anywhere in the theme. %s %s %s %s %s %s', 'styler'),
                        '<code>[styler_countdown]</code>',
                        '<br><br><b>Parameters:</b> <br><br><code>date=""</code>format: m/d/Y -- ( ex: date="05/21/2026" )',
                        '<br><br><code>type=""</code>type ( ex: type="2" )',
                        '<br><br><code>expired_text=""</code>message to be displayed when time is expired -- ( ex: expired_text="time is expired" )',
                        '<br><br><code>before_text=""</code>text before countdown -- ( ex: before_text="time is start" )',
                        '<br><br><code>after_text=""</code>text after countdown -- ( ex: after_text="time is end" )',
                        '<br><br><code>update=""</code> When the time is expired, update the date every next X days -- (ex: update="13" )'),
                        'customizer' => true,
                        'id' => 'product_countdown_shortcode_info',
                        'type' => 'info',
                        'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                    )
                )
            );
            $sections[] = array(
                'title' => esc_html__('Progressbar', 'styler'),
                'id' => 'single_shopprogressbar_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Stock Progressbar', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_progressbar_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Container Padding', 'styler'),
                        'customizer' => true,
                        'id' =>'product_attr_type_image_term_pad',
                        'type' => 'spacing',
                        'output' => array('.styler-summary-item.styler-single-product-stock'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false',
                        'default' => array('units' => 'px'),
                        'required' => array( 'single_shop_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Progressbar Typography', 'styler' ),
                        'id' => 'single_shop_product_progressbar_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.nt-woo-single .styler-single-product-stock .stock-sold, .nt-woo-single .styler-single-product-stock .current-stock' ),
                        'required' => array( 'single_shop_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_progressbar_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-single-product-stock .styler-product-stock-progress' ),
                        'required' => array( 'single_shop_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Color 2', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_progressbar_bgcolor2',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-single-product-stock .styler-product-stock-progressbar' ),
                        'required' => array( 'single_shop_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Height', 'styler' ),
                        'customizer' => true,
                        'width' => false,
                        'id' => 'product_progressbar_height',
                        'type' => 'dimensions',
                        'output' => array('.styler-single-product-stock .styler-product-stock-progressbar'),
                        'required' => array( 'single_shop_progressbar_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'customizer' => true,
                        'id' => 'product_progressbar_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-single-product-stock .styler-product-stock-progressbar'),
                        'required' => array( 'single_shop_progressbar_visibility', '=', '1' )
                    ),
                )
            );
            $sections[] = array(
                'title' => esc_html__('Summary Customize', 'styler'),
                'id' => 'singleshopsummarycolors',
                'subsection' => true,
                'icon' => 'el el-brush',
                'fields' => array(
                    array(
                        'title' => esc_html__( 'Title Typography', 'styler' ),
                        'id' => 'single_shop_product_title_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.nt-woo-single .styler-summary-item.styler-product-title' ),
                    ),
                    array(
                        'id' =>'single_shop_product_title_tag',
                        'type' => 'select',
                        'multiple' => false,
                        'title' => esc_html__( 'Product Title Tag', 'styler' ),
                        'options' => array(
                            'h1' => esc_html__( 'H1', 'styler' ),
                            'h2' => esc_html__( 'H2', 'styler' ),
                            'h3' => esc_html__( 'H3', 'styler' ),
                            'h4' => esc_html__( 'H4', 'styler' ),
                            'h5' => esc_html__( 'H5', 'styler' ),
                            'h6' => esc_html__( 'H6', 'styler' ),
                            'div' => esc_html__( 'Div', 'styler' )
                        ),
                        'default' => 'h2'
                    ),
                    array(
                        'title' => esc_html__( 'Price Typography', 'styler' ),
                        'id' => 'single_shop_product_price_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.nt-woo-single .styler-product-summary .styler-summary-item.styler-price.price span' ),
                    ),
                    array(
                        'title' => esc_html__( 'Excerpt Typography', 'styler' ),
                        'id' => 'single_shop_product_excerpt_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.nt-woo-single .woocommerce-product-details__short-description' ),
                    ),
                    array(
                        'title' => esc_html__( 'Meta && Extra Typography', 'styler' ),
                        'id' => 'single_shop_product_meta_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.styler-product-view span, .styler-estimated-delivery span, a.styler-open-popup, .styler-product-summary .styler-product-meta .styler-brands, .styler-product-summary .styler-product-meta .posted_in, .styler-product-summary .styler-product-meta .tagged_as, .styler-product-summary .styler-product-meta .styler-sku-wrapper' ),
                    ),
                    array(
                        'title' => esc_html__( 'Stock Status Typography', 'styler' ),
                        'id' => 'single_shop_product_stock_status_typo',
                        'type' => 'typography',
                        'font-backup' => false,
                        'letter-spacing' => true,
                        'text-transform' => true,
                        'all_styles' => true,
                        'output' => array( '.nt-woo-single .styler-summary-item.styler-price p.stock.styler-stock-status' )
                    ),
                    array(
                        'title' => esc_html__('Stock Status Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_stock_status_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.nt-woo-single .styler-summary-item.styler-price p.stock.styler-stock-status' )
                    ),
                    array(
                        'title' => esc_html__('Stock Status Text Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_stock_status_color',
                        'type' => 'color',
                        'output' => array( '.nt-woo-single .styler-summary-item.styler-price p.stock.styler-stock-status' )
                    ),
                    array(
                        'title' => esc_html__('Out of Stock Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_outofstock_status_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.nt-woo-single .styler-summary-item.styler-price p.stock.styler-stock-status.out-of-stock' )
                    ),
                    array(
                        'title' => esc_html__('Stock Status Text Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_outofstock_status_color',
                        'type' => 'color',
                        'output' => array( '.nt-woo-single .styler-summary-item.styler-price p.stock.styler-stock-status.out-of-stock' )
                    ),
                    array(
                        'id' => 'styler_product_addtocart_start',
                        'type' => 'section',
                        'title' => esc_html__('Add to Cart Color Options', 'styler'),
                        'customizer' => true,
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Variable Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_addtocart_container_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.nt-woo-single .product .single-product-add-to-cart-type-black .woocommerce-variation-add-to-cart, .nt-woo-single .product .single-product-add-to-cart-type-gray .woocommerce-variation-add-to-cart' )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_addtocart_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-summary .styler-btn' )
                    ),
                    array(
                        'title' => esc_html__('Background Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_addtocart_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-summary .styler-btn:hover' )
                    ),
                    array(
                        'title' => esc_html__('Title Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_addtocart_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-btn' )
                    ),
                    array(
                        'title' => esc_html__('Title Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_addtocart_hvrcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-summary .styler-btn:hover' )
                    ),
                    array(
                        'title' => esc_html__('Wishlist && Compare Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_buttons_svg_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-summary .styler-product-button' )
                    ),
                    array(
                        'title' => esc_html__('Wishlist && Compare Background Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_buttons_svg_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-summary .styler-product-button:hover' )
                    ),
                    array(
                        'title' => esc_html__('Wishlist && Compare Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_buttons_svg_color',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-product-summary .styler-product-button svg' )
                    ),
                    array(
                        'title' => esc_html__('Wishlist && Compare Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_buttons_svg_hvrcolor',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.styler-product-summary .styler-product-button:hover svg' )
                    ),
                    array(
                        'id' => 'styler_product_addtocart_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array( 'product_tabs_type', '=', 'tabs' )
                    )
                )
            );
            $sections[] = array(
                'title' => esc_html__('Tabs', 'styler'),
                'id' => 'singleshoptabs',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__( 'Tabs Display', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_tabs_visibility',
                        'type' => 'switch',
                        'default' => 1,
                        'on' => esc_html__( 'On', 'styler' ),
                        'off' => esc_html__( 'Off', 'styler' )
                    ),
                    array(
                        'id' =>'product_tabs_type',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Tabs Type', 'styler' ),
                        'options' => array(
                            'tabs' => esc_html__( 'Default Tabs', 'styler' ),
                            'accordion' => esc_html__( 'Accordion In Summary', 'styler' ),
                            'accordion-2' => esc_html__( 'Accordion After Summary', 'styler' )
                        ),
                        'default' => 'tabs',
                        'required' => array( 'product_tabs_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Hide Description Tab', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_hide_description_tab',
                        'type' => 'switch',
                        'default' => 0,
                        'on' => esc_html__( 'Yes', 'styler' ),
                        'off' => esc_html__( 'No', 'styler' ),
                        'required' => array( 'product_tabs_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Hide Reviews Tab', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_hide_reviews_tab',
                        'type' => 'switch',
                        'default' => 0,
                        'on' => esc_html__( 'Yes', 'styler' ),
                        'off' => esc_html__( 'No', 'styler' ),
                        'required' => array( 'product_tabs_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Hide Additional Information Tab', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_hide_additional_tab',
                        'type' => 'switch',
                        'default' => 0,
                        'on' => esc_html__( 'Yes', 'styler' ),
                        'off' => esc_html__( 'No', 'styler' ),
                        'required' => array( 'product_tabs_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Hide Q & A Tab', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_hide_crqna_tab',
                        'type' => 'switch',
                        'default' => 0,
                        'on' => esc_html__( 'Yes', 'styler' ),
                        'off' => esc_html__( 'No', 'styler' ),
                        'required' => array( 'product_tabs_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Global Extra Tab Title', 'styler' ),
                        'desc' => esc_html__( '!Important note: One title per line.', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_extra_tab_title',
                        'type' => 'textarea',
                        'required' => array( 'product_tabs_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Global Extra Tab Content', 'styler' ),
                        'desc' => esc_html__( '!Important note: One content per line.Iframe,shortcode,HTML content allowed.', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_extra_tab_content',
                        'type' => 'textarea',
                        'required' => array( 'product_tabs_visibility', '=', '1' )
                    ),
                    array(
                        'id' =>'product_tabs_active_tab',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Active Tab', 'styler' ),
                        'options' => array(
                            '' => esc_html__( 'None', 'styler' ),
                            'all' => esc_html__( 'All Tabs', 'styler' ),
                            ':first-child' => esc_html__( '1. Tab', 'styler' ),
                            ':nth-child(2)' => esc_html__( '2. Tab', 'styler' ),
                            ':nth-child(3)' => esc_html__( '3. Tab', 'styler' ),
                            ':nth-child(4)' => esc_html__( '4. Tab', 'styler' ),
                            ':nth-child(5)' => esc_html__( '5. Tab', 'styler' ),
                            ':nth-child(6)' => esc_html__( '6. Tab', 'styler' )
                        ),
                        'default' => '',
                        'required' => array( 'product_tabs_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Description Tab Content Title', 'styler'),
                        'customizer' => true,
                        'id' => 'product_description_tab_title_visibility',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'product_tabs_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Description Tab Content Title', 'styler'),
                        'customizer' => true,
                        'id' => 'product_description_tab_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_description_tab_title_visibility', '=', '1' )
                        )
                    ),
                    array(
                        'id' => 'styler_product_accordion_start',
                        'type' => 'section',
                        'title' => esc_html__('Accordion Color Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '!=', 'tabs' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Title Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_accordion_titlecolor',
                        'type' => 'color',
                        'output' => array( '.styler-accordion-header' ),
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '!=', 'tabs' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Title Color ( Active )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_accordion_active_titlecolor',
                        'type' => 'color',
                        'output' => array( '.styler-accordion-item.active .styler-accordion-header' ),
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '!=', 'tabs' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Title Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_accordion_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-accordion-item' ),
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '!=', 'tabs' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Title Background Color ( Active )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_accordion_active_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-accordion-item.active .styler-accordion-header' ),
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '!=', 'tabs' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Content Text Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_accordion_textcolor',
                        'type' => 'color',
                        'output' => array( '.styler-accordion-body,.styler-product-showcase.styler-bg-custom .product-desc-content h4,.styler-product-showcase.styler-bg-custom .product-desc-content .title' ),
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '!=', 'tabs' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Border Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_accordion_bordercolor',
                        'type' => 'color',
                        'mode' => 'border-color',
                        'output' => array( '.styler-accordion-item' ),
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '!=', 'tabs' )
                        )
                    ),
                    array(
                        'id' => 'styler_product_accordion_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '!=', 'tabs' )
                        )
                    ),
                    array(
                        'id' => 'styler_product_tabs_start',
                        'type' => 'section',
                        'title' => esc_html__('Tabs Color Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '=', 'tabs' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Title Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_tabs_titlecolor',
                        'type' => 'color',
                        'output' => array( '.styler-product-tab-title-item' ),
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '=', 'tabs' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Title Color ( Active )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_tabs_active_titlecolor',
                        'type' => 'color',
                        'output' => array( '.styler-product-tab-title-item.active' ),
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '=', 'tabs' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Title Border Color ( Active )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_tabs_active_bordercolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-tab-title-item::after' ),
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '=', 'tabs' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Tabs Border Bottom Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_tabs_bordercolor',
                        'type' => 'color',
                        'mode' => 'border-bottom-color',
                        'output' => array( '.styler-product-tab-title' ),
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '=', 'tabs' )
                        )
                    ),
                    array(
                        'id' => 'styler_product_tabs_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array(
                            array( 'product_tabs_visibility', '=', '1' ),
                            array( 'product_tabs_type', '=', 'tabs' )
                        )
                    )
                )
            );
            // SHOP PAGE SECTION
            $sections[] = array(
                'title' => esc_html__('Related Posts', 'styler'),
                'id' => 'singleshoprelated',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Related Section', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_ralated_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'id' =>'product_ralated_same_category',
                        'type' => 'switch',
                        'title' => esc_html__('Show Related in Same Category', 'styler'),
                        'customizer' => true,
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__('Related Title', 'styler'),
                        'subtitle' => esc_html__('Add your single shop page related section title here.', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_related_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Product Count ( Per Page )', 'styler'),
                        'subtitle' => esc_html__('You can control show related post count with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_related_count',
                        'type' => 'slider',
                        'default' => 10,
                        'min' => 1,
                        'step' => 1,
                        'max' => 24,
                        'display_value' => 'text',
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'id' => 'shop_related_section_slider_start',
                        'type' => 'section',
                        'title' => esc_html__('Related Slider Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Perview ( Min 1024px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_related_perview',
                        'type' => 'slider',
                        'default' => 4,
                        'min' => 1,
                        'step' => 1,
                        'max' => 10,
                        'display_value' => 'text',
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Perview ( Min 768px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_related_mdperview',
                        'type' => 'slider',
                        'default' => 3,
                        'min' => 1,
                        'step' => 1,
                        'max' => 10,
                        'display_value' => 'text',
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Perview ( Min 480px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_related_smperview',
                        'type' => 'slider',
                        'default' => 2,
                        'min' => 1,
                        'step' => 1,
                        'max' => 10,
                        'display_value' => 'text',
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Speed', 'styler' ),
                        'subtitle' => esc_html__( 'You can control related post slider item gap.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_related_speed',
                        'type' => 'slider',
                        'default' => 1000,
                        'min' => 100,
                        'step' => 1,
                        'max' => 10000,
                        'display_value' => 'text',
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Gap', 'styler' ),
                        'subtitle' => esc_html__( 'You can control related post slider item gap.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_related_gap',
                        'type' => 'slider',
                        'default' => 30,
                        'min' => 0,
                        'step' => 1,
                        'max' => 100,
                        'display_value' => 'text',
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Autoplay', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_related_autoplay',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Loop', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_related_loop',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Mousewheel', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_related_mousewheel',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Free Mode', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_related_freemode',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    ),
                    array(
                        'id' => 'shop_related_section_slider_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                    )
                )
            );
            // SHOP PAGE SECTION
            $sections[] = array(
                'title' => esc_html__('Upsells Posts', 'styler'),
                'id' => 'singleshopupsells',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Upsells Title', 'styler'),
                        'subtitle' => esc_html__('Add your single shop page upsells section title here.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_upsells_title',
                        'type' => 'text',
                        'default' => ''
                    ),
                    array(
                        'id' =>'shop_upsells_type',
                        'type' => 'button_set',
                        'title' => esc_html__('Upsells Layout Type', 'styler'),
                        'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page upsells.', 'styler' ),
                        'customizer' => true,
                        'options' => array(
                            'slider' => esc_html__( 'Slider', 'styler' ),
                            'grid' => esc_html__( 'Grid', 'styler' )
                        ),
                        'default' => 'slider'
                    ),
                    array(
                        'title' => esc_html__('Column', 'styler'),
                        'subtitle' => esc_html__('You can control upsells post column with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_upsells_colxl',
                        'type' => 'slider',
                        'default' => 4,
                        'min' => 1,
                        'step' => 1,
                        'max' => 6,
                        'display_value' => 'text',
                        'required' => array( 'shop_upsells_type', '=', 'grid' )
                    ),
                    array(
                        'title' => esc_html__('Column ( Desktop/Tablet )', 'styler'),
                        'subtitle' => esc_html__('You can control upsells post column for tablet device with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_upsells_collg',
                        'type' => 'slider',
                        'default' => 3,
                        'min' => 1,
                        'step' => 1,
                        'max' => 4,
                        'display_value' => 'text',
                        'required' => array( 'shop_upsells_type', '=', 'grid' )
                    ),
                    array(
                        'title' => esc_html__('Column ( Tablet )', 'styler'),
                        'subtitle' => esc_html__('You can control upsells post column for phone device with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_upsells_colsm',
                        'type' => 'slider',
                        'default' => 1,
                        'min' => 1,
                        'step' => 1,
                        'max' => 3,
                        'display_value' => 'text',
                        'required' => array( 'shop_upsells_type', '=', 'grid' )
                    ),
                    array(
                        'title' => esc_html__('Column ( Phone )', 'styler'),
                        'subtitle' => esc_html__('You can control upsells post column for phone device with this option.', 'styler'),
                        'customizer' => true,
                        'id' => 'shop_upsells_colxs',
                        'type' => 'slider',
                        'default' => 1,
                        'min' => 1,
                        'step' => 1,
                        'max' => 3,
                        'display_value' => 'text',
                        'required' => array( 'shop_upsells_type', '=', 'grid' )
                    ),
                    array(
                        'id' => 'shop_upsells_section_slider_start',
                        'type' => 'section',
                        'title' => esc_html__('Related Slider Options', 'styler'),
                        'customizer' => true,
                        'indent' => true,
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Perview ( Min 1024px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_upsells_perview',
                        'type' => 'slider',
                        'default' => 4,
                        'min' => 1,
                        'step' => 1,
                        'max' => 10,
                        'display_value' => 'text',
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Perview ( Min 768px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_upsells_mdperview',
                        'type' => 'slider',
                        'default' => 3,
                        'min' => 1,
                        'step' => 1,
                        'max' => 10,
                        'display_value' => 'text',
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Perview ( Min 480px )', 'styler' ),
                        'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_upsells_smperview',
                        'type' => 'slider',
                        'default' => 2,
                        'min' => 1,
                        'step' => 1,
                        'max' => 10,
                        'display_value' => 'text',
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Speed', 'styler' ),
                        'subtitle' => esc_html__( 'You can control related post slider item gap.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_upsells_speed',
                        'type' => 'slider',
                        'default' => 1000,
                        'min' => 100,
                        'step' => 1,
                        'max' => 10000,
                        'display_value' => 'text',
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Gap', 'styler' ),
                        'subtitle' => esc_html__( 'You can control related post slider item gap.', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_upsells_gap',
                        'type' => 'slider',
                        'default' => 30,
                        'min' => 0,
                        'step' => 1,
                        'max' => 100,
                        'display_value' => 'text',
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Autoplay', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_upsells_autoplay',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Loop', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_upsells_loop',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Mousewheel', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_upsells_mousewheel',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    ),
                    array(
                        'title' => esc_html__( 'Free Mode', 'styler' ),
                        'customizer' => true,
                        'id' => 'shop_upsells_freemode',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    ),
                    array(
                        'id' => 'shop_upsells_section_slider_end',
                        'type' => 'section',
                        'customizer' => true,
                        'indent' => false,
                        'required' => array( 'shop_upsells_type', '=', 'slider' )
                    )
                )
            );
            // SINGLE CONTENT SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Single Content', 'styler'),
                'id' => 'singleshopcontentsubsection',
                'subsection' => true,
                'icon' => 'el el-brush',
                'fields' => array(
                    array(
                        'title' => esc_html__('Single Content Padding', 'styler'),
                        'subtitle' => esc_html__('You can set the top spacing of the site single page content.', 'styler'),
                        'customizer' => true,
                        'id' =>'single_shop_content_pad',
                        'type' => 'spacing',
                        'output' => array('#nt-woo-single .nt-styler-inner-container'),
                        'mode' => 'padding',
                        'units' => array('em', 'px'),
                        'units_extended' => 'false'
                    )
                )
            );
            // SINGLE CONTENT SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Bottom Popup Cart', 'styler'),
                'id' => 'single_bottom_popup_cart_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Bottom Popup Cart on Scroll', 'styler'),
                        'customizer' => true,
                        'id' => 'styler_product_bottom_popup_cart',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__( 'Max Width ( px )', 'styler' ),
                        'customizer' => true,
                        'id' => 'product_bottom_popup_cart_maxwidth',
                        'type' => 'dimensions',
                        'default' => '',
                        'height' => false,
                        'required' => array( 'styler_product_bottom_popup_cart', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_bottom_popup_cart_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-bottom-popup-cart' ),
                        'required' => array( 'styler_product_bottom_popup_cart', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Product Title Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_bottom_popup_cart_title_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-bottom-popup-cart .styler-product-bottom-title' ),
                        'required' => array( 'styler_product_bottom_popup_cart', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Product Price Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_bottom_popup_cart_price_color',
                        'type' => 'color',
                        'output' => array( '.woocommerce .styler-product-bottom-popup-cart .styler-product-bottom-title div.product span.price' ),
                        'required' => array( 'styler_product_bottom_popup_cart', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Add to cart Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_bottom_popup_cart_addtocart_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-bottom-popup-cart .styler-product-to-top .styler-btn' ),
                        'required' => array( 'styler_product_bottom_popup_cart', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Add to cart Background Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'product_bottom_popup_cart_addtocart_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-bottom-popup-cart .styler-product-to-top .styler-btn:hover' ),
                        'required' => array( 'styler_product_bottom_popup_cart', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Add to cart Text Color', 'styler'),
                        'customizer' => true,
                        'id' => 'product_bottom_popup_cart_addtocart_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-bottom-popup-cart .styler-product-to-top .styler-btn' ),
                        'required' => array( 'styler_product_bottom_popup_cart', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Add to cart Text Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'product_bottom_popup_cart_addtocart_hvrcolor',
                        'type' => 'color',
                        'output' => array( '.styler-product-bottom-popup-cart .styler-product-to-top .styler-btn:hover' ),
                        'required' => array( 'styler_product_bottom_popup_cart', '=', '1' )
                    ),
                )
            );
            // SINGLE CONTENT SUBSECTION
            $sections[] = array(
                'title' => esc_html__('Share Buttons', 'styler'),
                'id' => 'singleshopsharesubsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Products share', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__( 'Share type', 'styler' ),
                        'subtitle' => esc_html__( 'Select your product share type.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_share_type',
                        'type' => 'select',
                        'multiple' => false,
                        'options' => array(
                            'share' => esc_html__( 'Share', 'styler' ),
                            'follow' => esc_html__( 'follow', 'styler' )
                        ),
                        'default' => 'share',
                        'required' => array( 'single_shop_share_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Color type', 'styler' ),
                        'subtitle' => esc_html__( 'Select your product share type.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_share_color_type',
                        'type' => 'select',
                        'multiple' => false,
                        'options' => array(
                            'official' => esc_html__( 'Official', 'styler' ),
                            'custom' => esc_html__( 'Custom', 'styler' )
                        ),
                        'default' => 'official',
                        'required' => array( 'single_shop_share_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Shape type', 'styler' ),
                        'subtitle' => esc_html__( 'Select your product share type.', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_share_shape_type',
                        'type' => 'select',
                        'multiple' => false,
                        'options' => array(
                            'square' => esc_html__( 'Square', 'styler' ),
                            'circle' => esc_html__( 'Circle', 'styler' ),
                            'round' => esc_html__( 'Round', 'styler' ),
                        ),
                        'default' => 'circle',
                        'required' => array( 'single_shop_share_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Share Label Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_label_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-product-share .share-title' ),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__( 'Size', 'styler' ),
                        'customizer' => true,
                        'id' => 'single_shop_share_size',
                        'type' => 'dimensions',
                        'output' => array('.styler-product-summary .styler-product-share a'),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-summary .styler-product-share a' ),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Background Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-summary .styler-product-share a' ),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-product-share a' ),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Color ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_hvrcolor',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-product-share a:hover' ),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Border', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_brd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-product-summary .styler-product-share a'),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Border ( Hover )', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_hvrbrd',
                        'type' => 'border',
                        'all' => true,
                        'output' => array('.styler-product-summary .styler-product-share a:hover'),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Hint Background Color', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_hint_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.styler-product-summary .styler-social-icons a:after' ),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Hint Arrow Color ', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_hint_arrow_color',
                        'type' => 'color',
                        'mode' => 'border-top-color',
                        'output' => array( '.styler-product-summary .styler-social-icons a:before' ),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Hint Text Color ', 'styler'),
                        'customizer' => true,
                        'id' => 'single_shop_share_hint_text_color',
                        'type' => 'color',
                        'output' => array( '.styler-product-summary .styler-social-icons a:after' ),
                        'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                    ),
                    array(
                        'title' => esc_html__('Facebook', 'styler'),
                        'customizer' => true,
                        'id' => 'share_facebook',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'single_shop_share_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Facebook link', 'styler'),
                        'customizer' => true,
                        'id' => 'facebook_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'share_facebook', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Twitter', 'styler'),
                        'customizer' => true,
                        'id' => 'share_twitter',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'single_shop_share_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Twitter link', 'styler'),
                        'customizer' => true,
                        'id' => 'twitter_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'share_twitter', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Instagram', 'styler'),
                        'customizer' => true,
                        'id' => 'share_instagram',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Instagram link', 'styler'),
                        'customizer' => true,
                        'id' => 'instagram_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_instagram', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Youtube', 'styler'),
                        'customizer' => true,
                        'id' => 'share_youtube',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Youtube link', 'styler'),
                        'customizer' => true,
                        'id' => 'youtube_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_youtube', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Vimeo', 'styler'),
                        'customizer' => true,
                        'id' => 'share_vimeo',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Vimeo link', 'styler'),
                        'customizer' => true,
                        'id' => 'vimeo_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_vimeo', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Pinterest', 'styler'),
                        'customizer' => true,
                        'id' => 'share_pinterest',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'single_shop_share_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Pinterest link', 'styler'),
                        'customizer' => true,
                        'id' => 'pinterest_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'share_pinterest', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Linkedin', 'styler'),
                        'customizer' => true,
                        'id' => 'share_linkedin',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'single_shop_share_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Linkedin link', 'styler'),
                        'customizer' => true,
                        'id' => 'linkedin_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'share_linkedin', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Tumblr', 'styler'),
                        'customizer' => true,
                        'id' => 'share_tumblr',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Tumblr link', 'styler'),
                        'customizer' => true,
                        'id' => 'tumblr_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_tumblr', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Flickr', 'styler'),
                        'customizer' => true,
                        'id' => 'share_flickr',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Flickr link', 'styler'),
                        'customizer' => true,
                        'id' => 'flickr_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_flickr', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Github', 'styler'),
                        'customizer' => true,
                        'id' => 'share_github',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Github link', 'styler'),
                        'customizer' => true,
                        'id' => 'github_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_github', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Behance', 'styler'),
                        'customizer' => true,
                        'id' => 'share_behance',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Behance link', 'styler'),
                        'customizer' => true,
                        'id' => 'behance_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_behance', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Dribbble', 'styler'),
                        'customizer' => true,
                        'id' => 'share_dribbble',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Dribbble link', 'styler'),
                        'customizer' => true,
                        'id' => 'dribbble_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_dribbble', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Soundcloud', 'styler'),
                        'customizer' => true,
                        'id' => 'share_soundcloud',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Soundcloud link', 'styler'),
                        'customizer' => true,
                        'id' => 'soundcloud_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_soundcloud', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Spotify', 'styler'),
                        'customizer' => true,
                        'id' => 'share_spotify',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Spotify link', 'styler'),
                        'customizer' => true,
                        'id' => 'spotify_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_spotify', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Ok', 'styler'),
                        'customizer' => true,
                        'id' => 'share_ok',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'single_shop_share_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Ok link', 'styler'),
                        'customizer' => true,
                        'id' => 'ok_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'share_ok', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Whatsapp', 'styler'),
                        'customizer' => true,
                        'id' => 'share_whatsapp',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'single_shop_share_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Whatsapp link', 'styler'),
                        'customizer' => true,
                        'id' => 'whatsapp_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'share_whatsapp', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Telegram', 'styler'),
                        'customizer' => true,
                        'id' => 'share_telegram',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'single_shop_share_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Telegram link', 'styler'),
                        'customizer' => true,
                        'id' => 'telegram_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'share_telegram', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Viber', 'styler'),
                        'customizer' => true,
                        'id' => 'share_viber',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'share' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Viber link', 'styler'),
                        'customizer' => true,
                        'id' => 'viber_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'share' ),
                            array( 'share_viber', '=', '1' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Tiktok', 'styler'),
                        'customizer' => true,
                        'id' => 'share_tiktok',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Tiktok link', 'styler'),
                        'customizer' => true,
                        'id' => 'tiktok_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_tiktok', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Snapchat', 'styler'),
                        'customizer' => true,
                        'id' => 'share_snapchat',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Snapchat link', 'styler'),
                        'customizer' => true,
                        'id' => 'snapchat_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'single_shop_share_type', '=', 'follow' ),
                            array( 'share_snapchat', '=', '1' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Vk', 'styler'),
                        'customizer' => true,
                        'id' => 'share_vk',
                        'type' => 'switch',
                        'default' => 1,
                        'required' => array( 'single_shop_share_visibility', '=', '1' ),
                    ),
                    array(
                        'title' => esc_html__('Vk link', 'styler'),
                        'customizer' => true,
                        'id' => 'vk_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'single_shop_share_visibility', '=', '1' ),
                            array( 'share_vk', '=', '1' ),
                        )
                    ),
                )
            );
            if ( class_exists( 'WooCommerce' ) ) {
                $sections[] = array(
                    'id' => 'inportexport_settings',
                    'title' => esc_html__( 'Import / Export', 'styler' ),
                    'desc' => esc_html__( 'Import and Export your Theme Options from text or URL.', 'styler' ),
                    'icon' => 'fa fa-download',
                    'fields' => array(
                        array(
                            'id' => 'opt-import-export',
                            'type' => 'import_export',
                            'title' => '',
                            'customizer' => false,
                            'subtitle' => '',
                            'full_width' => true
                        )
                    )
                );
                $sections[] = array(
                    'id' => 'nt_support_settings',
                    'title' => esc_html__( 'Support', 'styler' ),
                    'icon' => 'el el-idea',
                    'fields' => array(
                        array(
                            'id' => 'doc',
                            'type' => 'raw',
                            'markdown' => true,
                            'class' => 'theme_support',
                            'content' => '<div class="support-section">
                            <h5>'.esc_html__( 'WE RECOMMEND YOU READ IT BEFORE YOU START', 'styler' ).'</h5>
                            <h2><i class="el el-website"></i> '.esc_html__( 'DOCUMENTATION', 'styler' ).'</h2>
                            <a target="_blank" class="button" href="https://styler.com/docs/styler/">'.esc_html__( 'READ MORE', 'styler' ).'</a>
                            </div>'
                        ),
                        array(
                            'id' => 'support',
                            'type' => 'raw',
                            'markdown' => true,
                            'class' => 'theme_support',
                            'content' => '<div class="support-section">
                            <h5>'.esc_html__( 'DO YOU NEED HELP?', 'styler' ).'</h5>
                            <h2><i class="el el-adult"></i> '.esc_html__( 'SUPPORT CENTER', 'styler' ).'</h2>
                            <a target="_blank" class="button" href="https://styler.com/contact/">'.esc_html__( 'GET SUPPORT', 'styler' ).'</a>
                            </div>'
                        ),
                        array(
                            'id' => 'portfolio',
                            'type' => 'raw',
                            'markdown' => true,
                            'class' => 'theme_support',
                            'content' => '<div class="support-section">
                            <h5>'.esc_html__( 'SEE MORE THE NINETHEME WORDPRESS THEMES', 'styler' ).'</h5>
                            <h2><i class="el el-picture"></i> '.esc_html__( 'NINETHEME PORTFOLIO', 'styler' ).'</h2>
                            <a target="_blank" class="button" href="https://styler.com/themes/">'.esc_html__( 'SEE MORE', 'styler' ).'</a>
                            </div>'
                        ),
                        array(
                            'id' => 'like',
                            'type' => 'raw',
                            'markdown' => true,
                            'class' => 'theme_support',
                            'content' => '<div class="support-section">
                            <h5>'.esc_html__( 'WOULD YOU LIKE TO REWARD OUR EFFORT?', 'styler' ).'</h5>
                            <h2><i class="el el-thumbs-up"></i> '.esc_html__( 'PLEASE RATE US!', 'styler' ).'</h2>
                            <a target="_blank" class="button" href="https://themeforest.net/downloads/">'.esc_html__( 'GET STARS', 'styler' ).'</a>
                            </div>'
                        )
                    )
                );
            }
            return $sections;
        }
        add_filter('redux/options/'.$styler_pre.'/sections', 'styler_dynamic_section');
    }
}


/*************************************************
## ADD THEME SUPPORT FOR WOOCOMMERCE
*************************************************/
if ( ! function_exists( 'styler_wc_shop_per_page' ) ) {
    add_action( 'after_setup_theme', 'styler_wc_theme_setup' );
    function styler_wc_theme_setup()
    {
        $single_size = styler_settings( 'product_gallery_image_width', 980 );
        $single_size = isset($single_size['width']) ? $single_size['width'] : 980;
        add_theme_support( 'woocommerce', array(
            'thumbnail_image_width' => 450,
            'single_image_width'    => $single_size
        ));

        if ( '1' == styler_settings('styler_product_zoom', '1') ) {
            add_theme_support( 'wc-product-gallery-zoom' );
        }

        $thumbs_layout = apply_filters( 'styler_product_thumbs_layout', styler_settings( 'product_thumbs_layout', 'slider' ) );
        if ( $thumbs_layout == 'wc' ) {
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }
    }
}


// Remove each style one by one
if ( ! function_exists( 'styler_dequeue_wc_styles' ) ) {
    add_filter( 'woocommerce_enqueue_styles', 'styler_dequeue_wc_styles' );
    function styler_dequeue_wc_styles( $styles ) {
        unset( $styles['woocommerce-general'] ); // Remove the gloss
        unset( $styles['woocommerce-layout'] ); // Remove the layout
        unset( $styles['woocommerce-smallscreen'] ); // Remove the smallscreen optimisation
        return $styles;
    }
}


/*************************************************
## THEME CUSTOM CSS AND JS FOR WOOCOMMERCE
*************************************************/
if ( ! function_exists( 'ajax_login_init' ) ) {
    function ajax_login_init()
    {
        if ( '1' != styler_settings( 'wc_ajax_login_register', '1' ) ) {
            return;
        }

        wp_enqueue_script( 'styler-login-register-ajax', get_template_directory_uri() . '/woocommerce/assets/js/ajax-login-register-script.js', array( 'jquery' ), false, '1.0' );

        add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
        add_action( 'wp_ajax_nopriv_ajaxregister', 'ajax_register' );
        add_action( "woocommerce_register_form_end", 'styler_register_message' );
    }
}

// Execute the action only if the user isn't logged in
if ( !is_user_logged_in() ) {
    add_action('init', 'ajax_login_init');
}

//ajax login function
function ajax_login(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'woocommerce-login', 'woocommerce-login-nonce' );

    if ( ! empty( $_POST['username'] ) && ! empty( $_POST['password'] ) ) {
        // Nonce is checked, get the POST data and sign user on
        $info = array();
        $info['user_login']    = wc_clean($_POST['username']);
        $info['user_password'] = $_POST['password'];
        $info['remember']      = false;
        if ( isset( $_POST['rememberme'] ) ) {
            $info['remember'] = true;
        }

        $user_signon = wp_signon( $info, false );
        if ( is_wp_error($user_signon) ) {

            if ( isset( $user_signon->errors[ 'invalid_username' ] ) ) {
                $username_error = true;
            } else{
                $username_error = false;
            }
            if ( isset( $user_signon->errors[ 'incorrect_password' ] ) ) {
                $password_error = true;
            } else {
                $password_error = false;
            }
            $error_string = $user_signon->get_error_message();

            echo json_encode( array(
                'loggedin'           => false,
                'message'            => $error_string,
                'invalid_username'   => $username_error,
                'incorrect_password' => $password_error,
            ));

        } else {
            // hook after successfull login
            do_action( "styler_after_login", $user_signon );
            $args = array(
                'loggedin' => true,
                'message'  => esc_html__( 'Login successful, redirecting...', 'styler' ),
                'redirect' => apply_filters( "styler_login_redirect", false )
            );

            echo json_encode( $args );
        }
        die();
    } else {
        echo json_encode( array('loggedin'=>false, 'message'=>esc_html__('Please fill all required fields.','styler') ) );
        die();
    }
}

/*
* Ajax register function
*/
function ajax_register(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'woocommerce-register', 'woocommerce-register-nonce' );

    $generate_password = get_option( 'woocommerce_registration_generate_password' );

    if ( ! empty( $_POST['email'] ) && ! empty( $_POST['password'] ) ) {
        $username = 'no' === get_option( 'woocommerce_registration_generate_username' ) ? $_POST['username'] : '';
        $password = 'no' === get_option( 'woocommerce_registration_generate_password' ) ? $_POST['password'] : '';
        $email    = $_POST['email'];

        $validation_error = new WP_Error();
        $validation_error = apply_filters( 'woocommerce_process_registration_errors', $validation_error, $username, $password, $email );

        if ( $validation_error->get_error_code() ) {

            $error_array = array(
                'code'    => $validation_error->get_error_code(),
                'message' => $validation_error->get_error_message()
            );
        } else {
            $new_customer = wc_create_new_customer( sanitize_email( $email ), wc_clean( $username ), $password );
            if ( is_wp_error( $new_customer ) ) {
                $error_array = array(
                    'code'    => $new_customer->get_error_code(),
                    'message' => $new_customer->get_error_message()
                );
            } else {
                if ( apply_filters( 'woocommerce_registration_auth_new_customer', true, $new_customer ) ) {
                    wc_set_customer_auth_cookie( $new_customer );
                }

                $args = array(
                    'code'     => 200,
                    'message'  =>esc_html__( 'Account created successfully. redirecting...', 'styler' ),
                    'redirect' => apply_filters( "styler_register_redirect", false )
                );
                apply_filters( "styler_register_user_successful", false );
                echo json_encode( $args );
                die();
            }
        }
    }
    elseif ( $generate_password == 'yes' ) {
        if ( empty( $_POST['email'] ) ) {
            $error_array = array(
                'code'    => 'error',
                'message' => esc_html__('Please fill all required fields.','styler')
            );
        } else {
            $username         = 'no' === get_option( 'woocommerce_registration_generate_username' ) ? $_POST['username'] : '';
            $email            = $_POST['email'];
            $validation_error = new WP_Error();
            $validation_error = apply_filters( 'woocommerce_process_registration_errors', $validation_error, $username, $password, $email );

            if ( $validation_error->get_error_code() ) {
                $error_array = array(
                    'code'    => $validation_error->get_error_code(),
                    'message' => $validation_error->get_error_message()
                );
            } else {
                $new_customer = wc_create_new_customer( sanitize_email( $email ), wc_clean( $username ));
                if ( is_wp_error( $new_customer ) ) {
                    $error_array = array(
                        'code'    => $new_customer->get_error_code(),
                        'message' => $new_customer->get_error_message()
                    );
                } else {
                    if ( apply_filters( 'woocommerce_registration_auth_new_customer', true, $new_customer ) ) {
                        wc_set_customer_auth_cookie( $new_customer );
                    }

                    $args = array(
                        'code'     => 200,
                        'message'  => esc_html__( 'Account created successfully. redirecting...', 'styler' ),
                        'redirect' => apply_filters( "styler_register_redirect", false )
                    );
                    echo json_encode( $args );
                    die();
                }
            }
        }
    }
    else {
        $error_array = array(
            'code' => 'error',
            'message' => esc_html__('Please fill all required fields.','styler')
        );
    }
    echo json_encode($error_array);
    die();
}

function styler_register_message(){
    global $woocommerce;
    ?>
    <input type="hidden" name="action" value="ajaxregister">
    <?php
}

/*************************************************
## REGISTER SIDEBAR FOR WOOCOMMERCE
*************************************************/

if ( ! function_exists( 'styler_wc_widgets_init' ) ) {
    add_action( 'widgets_init', 'styler_wc_widgets_init' );
    function styler_wc_widgets_init()
    {
        //Shop page sidebar
        register_sidebar( array(
            'id' => 'shop-page-sidebar',
            'name' => esc_html__( 'Shop Page Sidebar', 'styler' ),
            'description' => esc_html__( 'These widgets for the Shop page.','styler' ),
            'before_widget' => '<div class="nt-sidebar-inner-widget shop-widget styler-widget-show mb-40 %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="nt-sidebar-inner-widget-title shop-widget-title"><span class="nt-sidebar-widget-title">',
            'after_title' => '</span><span class="nt-sidebar-widget-toggle"></span></h5>'
        ) );
        //Single product sidebar
        register_sidebar( array(
            'id' => 'shop-single-sidebar',
            'name' => esc_html__( 'Shop Single Page Sidebar', 'styler' ),
            'description' => esc_html__( 'These widgets for the Shop Single page.','styler' ),
            'before_widget' => '<div class="nt-sidebar-inner-widget shop-widget styler-widget-show mb-40 %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="nt-sidebar-inner-widget-title shop-widget-title"><span class="styler-sidebar-widget-title">',
            'after_title' => '</span><span class="styler-sidebar-widget-toggle"></span></h5>'
        ) );
    }
}


/*************************************************
## WOOCOMMERCE PAGE TITLE FUNCTION
*************************************************/

if ( ! function_exists( 'styler_shop_page_title' ) ) {
    add_filter( 'woocommerce_page_title', 'styler_shop_page_title');
    function styler_shop_page_title( $page_title )
    {
        $tag = styler_settings( 'shop_page_title_tag', 'h2' );
        if ( 'Shop' == $page_title && styler_settings( 'shop_title' ) ) {
            return '<'.$tag.' class="nt-hero-title page-title mb-10">'.styler_settings( 'shop_title' ).'</'.$tag.'>';
        } else {
            return '<'.$tag.' class="nt-hero-title page-title mb-10">'.$page_title.'</'.$tag.'>';
        }
    }
}


/*************************************************
## WOOCOMMERCE HERO FUNCTION
*************************************************/

if ( ! function_exists( 'styler_wc_hero_section' ) ) {
    add_action( 'styler_before_shop_content', 'styler_wc_hero_section', 10 );
    function styler_wc_hero_section()
    {
        $name = is_product() ? 'single_shop' : 'shop';

        $hero_type        = styler_settings( $name.'_hero_layout_type', 'mini' );
        $template_id      = apply_filters( 'styler_shop_hero_template_id', intval( styler_settings( 'shop_hero_elementor_templates' ) ) );
        $cats_template_id = apply_filters( 'styler_shop_category_hero_template_id', intval( styler_settings( 'shop_cats_hero_elementor_templates' ) ) );
        $tax_template_id  = apply_filters( 'styler_shop_tags_hero_template_id', intval( styler_settings( 'shop_tax_hero_elementor_templates' ) ) );
        $is_elementor     = class_exists( '\Elementor\Frontend' ) ? true : false;
        $frontend         = $is_elementor ? new \Elementor\Frontend : false;

        if ( '0' != styler_settings($name.'_hero_visibility', '1') ) {

            if ( is_product_category() ) {

                styler_wc_archive_category_page_hero_section();

            } elseif ( is_product_tag() && $is_elementor && $tax_template_id  ) {

                printf( '<div class="styler-shop-hero-tag">%1$s</div>', $frontend->get_builder_content_for_display( $tax_template_id, false ) );

            } elseif ( ( is_shop() || is_product() ) && $is_elementor && $template_id  ) {

                printf( '<div class="styler-shop-custom-hero">%1$s</div>', $frontend->get_builder_content_for_display( $template_id, false ) );

            } else {
                ?>
                <div class="styler-shop-hero-wrapper">
                    <div class="styler-shop-hero styler-page-hero page-hero-<?php echo esc_attr( $hero_type ); ?>">
                        <div class="container-xl styler-container-xl">
                            <div class="row">
                                <div class="col-12">
                                    <div class="styler-page-hero-content styler-flex styler-align-center styler-justify-center">

                                        <?php
                                        if ( is_product() ) {
                                            the_title('<h2 class="styler-page-title styler-product-title">','</h2>');
                                        } else {
                                            woocommerce_page_title();
                                        }
                                        if ( $hero_type == 'big' ) {
                                            echo styler_wc_category_list();
                                        }
                                        if ( $hero_type == 'mini' && '0' != styler_settings( 'breadcrumbs_visibility', '1' ) ) {
                                            echo woocommerce_breadcrumb();
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    if ( $hero_type == 'cat-slider' ) {
                                        styler_wc_hero_category_slider();
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
}

if ( ! function_exists( 'styler_wc_default_hero_section' ) ) {

    function styler_wc_default_hero_section()
    {
        ?>
        <div class="styler-shop-hero-wrapper">
            <div class="styler-shop-hero styler-page-hero page-hero-mini">
                <div class="container-xl styler-container-xl">
                    <div class="row">
                        <div class="col-12">
                            <div class="styler-page-hero-content styler-flex styler-align-center styler-justify-center">
                                <?php
                                    woocommerce_page_title();
                                    if ( '0' != styler_settings( 'breadcrumbs_visibility', '1' ) ) {
                                        echo woocommerce_breadcrumb();
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

if ( ! function_exists( 'styler_wc_archive_category_page_hero_section' ) ) {

    function styler_wc_archive_category_page_hero_section()
    {
        $cats_template_id = apply_filters( 'styler_shop_category_hero_template_id', intval( styler_settings( 'shop_cats_hero_elementor_templates' ) ) );
        $is_elementor     = class_exists( '\Elementor\Frontend' ) ? true : false;
        $frontend         = $is_elementor ? new \Elementor\Frontend : false;
        $term_bg_id       = get_term_meta( get_queried_object_id(), 'styler_product_cat_hero_bgimage_id', true );
        $term_bg_url      = wp_get_attachment_image_url( $term_bg_id, 'large' );

        if ( $term_bg_url ) {
            ?>
            <div class="styler-shop-hero-wrapper">
                <div class="styler-shop-hero styler-page-hero page-hero-big has-bg-image" data-bg="<?php echo esc_url( $term_bg_url ); ?>">
                    <div class="container-xl styler-container-xl">
                        <div class="row justify-content-center">
                            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                                <div class="styler-page-hero-content">
                                    <?php woocommerce_page_title(); ?>
                                    <?php do_action( 'woocommerce_archive_description' ); ?>
                                    <?php
                                    if ( '0' != styler_settings( 'breadcrumbs_visibility', '1' ) ) {
                                        echo woocommerce_breadcrumb();
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            if ( $is_elementor && $cats_template_id ) {
                printf( '<div class="styler-shop-hero-cats">%1$s</div>', $frontend->get_builder_content_for_display( $cats_template_id, false ) );
            } else {
                styler_wc_default_hero_section();
            }
        }
    }
}



/*************************************************
## WOOCOMMERCE HERO CATEGORY SLIDER FUNCTION
*************************************************/

if ( ! function_exists( 'styler_wc_hero_category_slider' ) ) {
    function styler_wc_hero_category_slider()
    {
        $categories = get_terms( 'product_cat', array(
            'orderby'    => 'name',
            'order'      => 'asc',
            'hide_empty' => true,
            'parent'     => '1' == styler_settings( 'shop_hero_only_cats_parents' ) ? 0 : ''
        ));
        $options = '"spaceBetween":1,
        "centeredSlides":true,
        "centeredSlidesBounds":true,
        "loop":false,
        "autoplay":true,
        "spaceBetween": 0,
        "speed":2000,
        "slidesPerView":1,
        "pagination": false,
        "breakpoints": { "320": {"slidesPerView": 3},"768": {"slidesPerView": 3},"992": {"slidesPerView": 5},"1200": {"slidesPerView": 7}}';
        wp_enqueue_script( 'swiper' );
        if ( !empty( $categories ) ) {
            ?>
            <div class="styler-category-slider styler-swiper-slider swiper-container" data-swiper-options='{<?php echo esc_attr( $options ); ?>}'>
                <div class="swiper-wrapper">
                    <?php
                    foreach ( $categories as $cat ) {
                        $id    = intval( $cat->term_id );
                        $thumb = get_term_meta( $id, 'thumbnail_id', true );
                        $name  = $cat->name;
                        $count = $cat->count;
                        $link  = is_shop() ? styler_get_cat_url( $id ) : get_term_link($cat);
                        ?>
                        <div class="styler-category-slide-item swiper-slide">
                            <a href="<?php echo esc_url( $link ); ?>" rel="nofollow">
                                <?php echo wp_get_attachment_image( $thumb, array(100,100), true ); ?>
                                <span class="cat-count"><?php echo esc_html( $count ); ?></span>
                                <span class="category-title"><?php echo esc_html( $name ); ?></span>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    }
}

/*************************************************
## WOOCOMMERCE HERO CATEGORY LIST FUNCTION
*************************************************/

if ( ! function_exists( 'styler_wc_category_list' ) ) {
    function styler_wc_category_list()
    {
        $categories = get_terms( 'product_cat', array(
            'orderby'    => 'name',
            'order'      => 'asc',
            'hide_empty' => true,
            'parent'     => '1' == styler_settings( 'shop_hero_only_cats_parents' ) ? 0 : ''
        ));

        if ( !empty( $categories ) ) {
            ?>
            <ul class="styler-wc-category-list">
                <?php
                foreach ( $categories as $key => $cat ) {
                    $id   = intval( $cat->term_id );
                    $name = $cat->name;
                    $link = is_shop() ? styler_get_cat_url( $id ) : get_term_link($cat);
                    ?>
                    <li>
                        <a href="<?php echo esc_url( $link ); ?>" rel="nofollow">
                            <span class="category-title"><?php echo esc_html( $name ); ?></span>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php
        }
    }
}


if ( ! function_exists( 'styler_before_shop_elementor_templates' ) ) {
    add_action( 'styler_before_shop_content', 'styler_before_shop_elementor_templates', 15 );
    function styler_before_shop_elementor_templates()
    {
        $cat_template = styler_settings('shop_category_pages_before_content_templates', null );
        $tag_template = styler_settings('shop_tag_pages_before_content_templates', null );
        if ( ( $cat_template || $tag_template ) && ( is_product_category() || is_product_tag() ) ) {
            if ( $cat_template && is_product_category() ) {
                echo styler_print_elementor_templates( 'shop_category_pages_before_content_templates', 'shop-before-content-template-wrapper', true );
            } elseif ( $tag_template && is_product_tag() ) {
                echo styler_print_elementor_templates( 'shop_tag_pages_before_content_templates', 'shop-before-content-template-wrapper', true );
            }
        } else {
            echo styler_print_elementor_templates( 'shop_before_content_templates', '', true );
        }
    }
}

if ( ! function_exists( 'styler_after_shop_loop_elementor_templates' ) ) {
    add_action( 'styler_after_shop_loop', 'styler_after_shop_loop_elementor_templates', 10 );
    function styler_after_shop_loop_elementor_templates()
    {
        $cat_template = styler_settings('shop_category_pages_after_loop_templates', null );
        $tag_template = styler_settings('shop_tag_pages_after_loop_templates', null );
        if ( ( $cat_template || $tag_template ) && ( is_product_category() || is_product_tag() ) ) {
            if ( $cat_template && is_product_category() ) {
                echo styler_print_elementor_templates( 'shop_category_pages_after_loop_templates', 'shop-after-loop-template-wrapper', true );
            } elseif ( $tag_template && is_product_tag() ) {
                echo styler_print_elementor_templates( 'shop_tag_pages_after_loop_templates', 'shop-after-loop-template-wrapper', true );
            }
        } else {
            echo styler_print_elementor_templates( 'shop_after_loop_templates', '', true );
        }
    }
}

if ( ! function_exists( 'styler_after_shop_page_elementor_templates' ) ) {
    add_action( 'styler_after_shop_page', 'styler_after_shop_page_elementor_templates', 10 );
    function styler_after_shop_page_elementor_templates()
    {
        $cat_template = styler_settings('shop_category_pages_after_content_templates', null );
        $tag_template = styler_settings('shop_tag_pages_after_content_templates', null );
        if ( ( $cat_template || $tag_template ) && ( is_product_category() || is_product_tag() ) ) {
            if ( $cat_template && is_product_category() ) {
                echo styler_print_elementor_templates( 'shop_category_pages_after_content_templates', 'shop-after-content-template-wrapper', true );
            } elseif ( $tag_template && is_product_tag() ) {
                echo styler_print_elementor_templates( 'shop_tag_pages_after_content_templates', 'shop-after-content-template-wrapper', true );
            }
        } else {
            echo styler_print_elementor_templates( 'shop_after_content_templates', '' );
        }
    }
}


/*************************************************
## Get Columns options
*************************************************/
if ( ! function_exists( 'styler_get_shop_column' ) ) {
    function styler_get_shop_column()
    {
        $column = isset( $_GET['column'] ) ? $_GET['column'] : '';
        return esc_html($column);
    }
}


if ( ! function_exists( 'styler_shop_pagination' ) ) {
    add_action( 'styler_shop_pagination', 'styler_shop_pagination', 15 );
    function styler_shop_pagination()
    {
        $pagination   = apply_filters('styler_shop_pagination_type', styler_settings('shop_paginate_type') );
        $current_page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
        $max_page     = wc_get_loop_prop( 'total_pages' );
        if ( $pagination == 'loadmore' && ( $current_page != $max_page ) ) {

            styler_load_more_button();

        } elseif ( $pagination == 'infinite' && ( $current_page != $max_page ) ) {

            styler_infinite_scroll();

        } else  {

            woocommerce_pagination();
        }
    }
}


if ( ! function_exists( 'styler_wc_filters_for_ajax' ) ) {
    function styler_wc_filters_for_ajax()
    {
        if ( '1' == styler_get_shop_column() ) {
            $type = 7;
        } else {
            $type = isset( $_GET['product_style'] ) && $_GET['product_style'] ? esc_html ( $_GET['product_style'] ) : styler_settings( 'shop_product_type', '3' );
            $type = apply_filters( 'styler_loop_product_type', $type );
        }
        return json_encode(
            array(
                'ajaxurl'        => esc_url( admin_url( 'admin-ajax.php' ) ),
                'current_page'   => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
                'max_page'       => wc_get_loop_prop( 'total_pages' ),
                'per_page'       => isset( $_GET['per_page'] ) ? $_GET['per_page'] : wc_get_loop_prop( 'per_page' ),
                'layered_nav'    => WC_Query::get_layered_nav_chosen_attributes(),
                'cat_id'         => is_product_category() && isset( get_queried_object()->term_id ) ? get_queried_object()->term_id : '',
                'tag_id'         => is_product_tag() && isset( get_queried_object()->term_id ) ? get_queried_object()->term_id : '',
                'brand_id'       => is_tax( 'styler_product_brands' ) && isset( $_GET['brand_id'] ) ? $_GET['brand_id'] : '',
                'filter_cat'     => isset( $_GET['filter_cat'] ) ? $_GET['filter_cat'] : '',
                'filter_brand'   => isset( $_GET['brand_id'] ) ? $_GET['brand_id'] : '',
                'on_sale'        => isset( $_GET['on_sale'] ) ? 'yes' : 'no',
                'in_stock'       => isset( $_GET['stock_status'] ) && $_GET['stock_status'] == 'instock' ? 'yes' : 'no',
                'orderby'        => isset( $_GET['orderby'] ) ? $_GET['orderby'] : '',
                'min_price'      => isset( $_GET['min_price'] ) ? $_GET['min_price'] : '',
                'max_price'      => isset( $_GET['max_price'] ) ? $_GET['max_price'] : '',
                'product_style'  => $type,
                'column'         => styler_get_shop_column(),
                'no_more'        => esc_html__( 'All Products Loaded', 'styler' ),
                'is_search'      => is_search() ? 'yes' : '',
                'is_shop'        => is_shop() ? 'yes' : '',
                'is_brand'       => is_tax( 'styler_product_brands' ) ? 'yes' : '',
                'is_cat'         => is_tax( 'product_cat' ) ? 'yes' : '',
                'is_tag'         => is_tax( 'product_tag' ) ? 'yes' : '',
                's'              => isset($_GET['s']) ? $_GET['s'] : '',
            )
        );
    }
}


if ( ! function_exists( 'styler_get_cat_url' ) ) {
    function styler_get_cat_url( $termid )
    {
        global $wp;
        if ( '' === get_option( 'permalink_structure' ) ) {
            $link = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        } else {
            $link = preg_replace( '%\/page/[0-9]+%', '', add_query_arg( null, null ) );
        }

        if ( isset( $_GET['filter_cat'] ) ) {
            $explode_old = explode( ',', $_GET['filter_cat'] );
            $explode_termid = explode( ',', $termid );

            if ( in_array( $termid, $explode_old ) ) {
                $data = array_diff( $explode_old, $explode_termid );
                $checkbox = 'checked';
            } else {
                $data = array_merge( $explode_termid , $explode_old );
            }
        } else {
            $data = array( $termid );
        }

        $dataimplode = implode( ',', $data );

        if ( empty( $dataimplode ) ) {
            $link = remove_query_arg( 'filter_cat', $link );
        } else {
            $link = add_query_arg( 'filter_cat', implode( ',', $data ), $link );
        }

        return $link;
    }
}


if ( ! function_exists( 'styler_get_brand_url' ) ) {
    function styler_get_brand_url( $termid )
    {
        global $wp;
        if ( '' === get_option( 'permalink_structure' ) ) {
            $link = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        } else {
            $link = preg_replace( '%\/page/[0-9]+%', '', add_query_arg( null, null ) );
        }

        if ( isset( $_GET['brand_id'] ) ) {
            $explode_old = explode( ',', $_GET['brand_id'] );
            $explode_termid = explode( ',', $termid );

            if ( in_array( $termid, $explode_old ) ) {
                $data = array_diff( $explode_old, $explode_termid );
                $checkbox = 'checked';
            } else {
                $data = array_merge( $explode_termid , $explode_old );
            }
        } else {
            $data = array( $termid );
        }

        $dataimplode = implode( ',', $data );

        if ( empty( $dataimplode ) ) {
            $link = remove_query_arg( 'brand_id', $link );
        } else {
            $link = add_query_arg( 'brand_id', implode( ',', $data ), $link );
        }

        return $link;
    }
}

/*************************************************
## MINICART AND QUICK-VIEW
*************************************************/

include get_template_directory() . '/woocommerce/minicart/actions.php';
include get_template_directory() . '/woocommerce/load-more/load-more.php';


/**
* Change number of products that are displayed per page (shop page)
*/
if ( ! function_exists( 'styler_wc_shop_per_page' ) ) {
    add_filter( 'loop_shop_per_page', 'styler_wc_shop_per_page', 20 );
    add_filter( 'dokan_store_products_per_page', 'styler_wc_shop_per_page', 20 );
    function styler_wc_shop_per_page( $cols )
    {
        if ( isset( $_GET['per_page'] ) && $_GET['per_page'] ) {
            return $_GET['per_page'];
        }

        $cols = apply_filters( 'styler_wc_shop_per_page', styler_settings( 'shop_perpage', '8' ) );

        if ( class_exists('WeDevs_Dokan') && dokan_is_store_page() ) {
            $store_user  = dokan()->vendor->get( get_query_var( 'author' ) );
            $store_info  = dokan_get_store_info( $store_user->get_id() );
            $cols        = dokan_get_option( 'store_products_per_page', 'dokan_general', 12 );

            return $cols;
        }

        return $cols;
    }
}


/**
* Change product column
*/
if ( ! function_exists( 'styler_wc_product_column' ) ) {

    function styler_wc_product_column()
    {
        if ( '1' == styler_get_shop_column() ) {
            $listcol = styler_settings('shop_list_type_colxl', '2');
            return apply_filters( 'styler_product_column', 'row-cols-2 row-cols-md-3 row-cols-xl-'.$listcol.' styler-product-list' );
        }
        if ( '2' == styler_get_shop_column() ) {
            return apply_filters( 'styler_product_column', 'row-cols-2 row-cols-sm-3 row-cols-lg-2' );
        }
        if ( '3' == styler_get_shop_column() ) {
            return apply_filters( 'styler_product_column', 'row-cols-2 row-cols-sm-3 row-cols-lg-3' );
        }
        if ( '4' == styler_get_shop_column() ) {
            return apply_filters( 'styler_product_column', 'row-cols-2 row-cols-sm-3 row-cols-lg-4' );
        }
        if ( '5' == styler_get_shop_column() ) {
            return apply_filters( 'styler_product_column', 'row-cols-2 row-cols-sm-3 row-cols-lg-3 row-cols-xl-5' );
        }
        if ( '6' == styler_get_shop_column() ) {
            return apply_filters( 'styler_product_column', 'row-cols-2 row-cols-sm-3 row-cols-lg-3 row-cols-xl-6' );
        }

        $col[] = 'row-cols-' . styler_settings('shop_colxs', '2');
        $col[] = 'row-cols-sm-' . styler_settings('shop_colsm', '2');
        $col[] = 'row-cols-lg-' . styler_settings('shop_collg', '3');
        $col[] = 'row-cols-xl-' . styler_settings('shop_colxl', '4');
        $col = implode( ' ', $col );

        return apply_filters( 'styler_product_column', $col );
    }
}


/**
* Change number of upsells products column
*/
if ( ! function_exists( 'styler_wc_sells_product_column' ) ) {

    function styler_wc_sells_product_column()
    {
        $sells = is_cart() ? 'cross_sells' : 'upsells';
        $col[] = 'cart row-cols-' . styler_settings('shop_'.$sells.'_colxs', '2');
        $col[] = 'row-cols-sm-' . styler_settings('shop_'.$sells.'_colsm', '2');
        $col[] = 'row-cols-lg-' . styler_settings('shop_'.$sells.'_collg', '3');
        $col[] = 'row-cols-xl-' . styler_settings('shop_'.$sells.'_colxl', '4');
        $col   = implode( ' ', $col );
        return apply_filters( 'styler_wc_sells_column', $col );
    }
}


/**
* Change number of related products output
*/
if ( ! function_exists( 'styler_wc_related_products_limit' ) ) {

    add_filter( 'woocommerce_output_related_products_args', 'styler_wc_related_products_limit', 20 );
    function styler_wc_related_products_limit( $args )
    {
        $args['posts_per_page'] = apply_filters( 'styler_wc_related_products_limit', styler_settings('single_shop_related_count', '6') ); // 4 related products
        return $args;
    }
}


/**
* Theme custom filter and actions for woocommerce
*/

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );


// add actions

add_action( 'woocommerce_single_product_summary', 'styler_product_countdown', 25 );
add_action( 'woocommerce_single_product_summary', 'styler_product_stock_progress_bar', 26 );
add_action( 'woocommerce_product_meta_end', 'styler_product_brands', 10 );

add_action( 'woocommerce_widget_shopping_cart_before_buttons', 'styler_minicart_before_buttons', 10 );


/**
* Clear Filters
*/
if ( ! function_exists( 'styler_clear_filters' ) ) {
    function styler_clear_filters() {

        $url = wc_get_page_permalink( 'shop' );
        $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();

        $min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
        $max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';

        if ( 0 < count( $_chosen_attributes ) || $min_price || $max_price ) {
            $reset_url = strtok( $url, '?' );
            if ( isset( $_GET['post_type'] ) ) {
                $reset_url = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $reset_url );
            }
            ?>
            <div class="styler-clear-filters">
                <a href="<?php echo esc_url( $reset_url ); ?>"><?php echo esc_html__( 'Clear filters', 'styler' ); ?></a>
            </div>
            <?php
        }
    }
    add_action( 'styler_before_choosen_filters', 'styler_clear_filters' );
}



/**
* Product thumbnail
*/
if ( ! function_exists( 'styler_minicart_before_buttons' ) ) {
    function styler_minicart_before_buttons()
    {
        if ( styler_settings('header_cart_before_buttons', '' ) ) {
            ?>
            <div class="minicart-extra-text">
                <?php echo styler_settings('header_cart_before_buttons', '' ); ?>
            </div>
            <?php
        }
    }
}

/**
* wp_get_attachment_image_attributes
*/
if ( ! function_exists( 'styler_wp_get_attachment_image_attributes' ) ) {
    add_filter( 'wp_get_attachment_image_attributes', 'styler_wp_get_attachment_image_attributes');
    function styler_wp_get_attachment_image_attributes($attr)
    {
        if ( '1' == styler_settings('theme_lazyload_images', '1' ) ){
            $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
            $blank_img = get_template_directory().'/images/blank.gif';
            $blank_img = file_exists( $blank_img ) ? get_template_directory_uri().'/images/blank.gif' : $placeholder;
            $attr['data-src'] = $attr['src'];

            $attr['src'] = is_admin() || isset($attr['loading']) != 'lazy' ? $attr['src'] : $blank_img;

            $attr['data-srcset'] = isset( $attr['srcset'] ) ? $attr['srcset'] : '';

            unset( $attr['srcset'] );

            $attr['data-sizes']  = isset( $attr['sizes'] ) ? $attr['sizes'] : '';

            unset( $attr['sizes'] );
        }
        return $attr;
    }
}

/**
* Product thumbnail
*/
if ( ! function_exists( 'shop_related_thumb_size' ) ) {
    function shop_related_thumb_size()
    {
        return apply_filters( 'shop_related_thumb_size', [370,370] );
    }
}

/**
* Product thumbnail
*/
if ( ! function_exists( 'styler_loop_product_thumb' ) ) {
    function styler_loop_product_thumb($column='')
    {
        global $product;
        $column = isset( $_GET['column'] ) ? esc_html( $_GET['column'] ) : $column;
        $size   = styler_settings('product_imgsize','woocommerce_thumbnail');
        $mobile_size   = styler_settings('mobile_product_imgsize','woocommerce_thumbnail');
        $size   = wp_is_mobile() && $mobile_size ? $mobile_size : $size;
        if ( '1' != styler_settings('keep_image_size', '0') ) {
            switch ( $column ) {
                case '6':
                    $size = 'styler-mini';
                    break;
                case '5':
                    $size = 'styler-mini';
                    break;
                case '4':
                    $size = 'styler-medium';
                    break;
                case '3':
                    $size = 'styler-square';
                    break;
                case '2':
                    $size = 'styler-grid';
                    break;
                case '1':
                    $size = 'styler-medium';
                    break;
                default:
                    $size = styler_settings('product_imgsize','woocommerce_thumbnail');
                    $size = wp_is_mobile() && $mobile_size ? $mobile_size : $size;
                    break;
            }
        }
        $id          = $product->get_id();
        $size        = isset( $_POST['img_size'] ) != null ? $_POST['img_size'] : $size;
        $size        = apply_filters( 'styler_product_thumb_size', $size );
        $attr        = !empty( $gallery ) ? 'product-thumb attachment-woocommerce_thumbnail size-'.$size : 'attachment-woocommerce_thumbnail size-'.$size;
        $show_video  = get_post_meta( $id, 'styler_product_video_on_shop', true );
        $iframe_id   = get_post_meta( $id, 'styler_product_iframe_video', true );
        $video_type  = get_post_meta( $id, 'styler_product_video_source_type', true );
        $popup_video = get_post_meta( $id, 'styler_product_popup_video', true );

        if ( '1' == styler_settings( 'shop_loop_custom_image_size', '0' ) ) {
            $dimensions = styler_settings( 'shop_loop_custom_image_dimensions' );
            $width  = !empty( $dimensions['width'] ) ? $dimensions['width'] : '';
            $height = !empty( $dimensions['height'] ) ? $dimensions['height'] : '';
            $crop   = '1' == styler_settings( 'shop_loop_custom_image_crop', '0' ) ? true : false;
            $size   = [$width,$height];
        }

        if ( $show_video == 'yes' ) {
            if ( $video_type == 'youtube' && $iframe_id ) {
                $iframe_html = '<iframe class="lazy" loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_id.'?playlist='.$iframe_id.'&modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
                echo '<div class="styler-loop-product-iframe-wrapper"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'"></a>'.$iframe_html.'</div>';
            } elseif ( 'vimeo' == $video_type && $iframe_id ) {
                echo '<div class="styler-loop-product-iframe-wrapper"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'"></a><iframe class="lazy vimeo-video" loading="lazy" data-src="https://player.vimeo.com/video/'.$iframe_id.'?autoplay=1&loop=1&title=0&byline=0&portrait=0&muted=1" frameborder="0" allow=fullscreen allow=autoplay></iframe></div>';
            } elseif ( 'hosted' == $video_type ) {
                echo '<div class="styler-loop-product-video-wrapper"><video class="lazy hosted-video" width="320" height="240" autoplay muted loop><source data-src="'.$popup_video.'" type="video/mp4"></video></div>';
            }
        } else {
            ?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="product-link">
                <?php
                if ( '1' == styler_settings('use_wc_image_sizes', '0') ) {
                    woocommerce_template_loop_product_thumbnail();
                } else {
                    echo has_post_thumbnail() ? get_the_post_thumbnail( $id, $size, array( 'class' => $attr ) ) : wc_placeholder_img( $size );
                }
                ?>
            </a>
            <?php
        }
    }
}

/**
* Product thumbnail
*/
if ( ! function_exists( 'styler_loop_product_thumb_overlay' ) ) {
    function styler_loop_product_thumb_overlay($column='')
    {
        global $product;
        $column = isset( $_GET['column'] ) ? esc_html( $_GET['column'] ) : $column;
        $size   = styler_settings('product_imgsize','woocommerce_thumbnail');
        $mobile_size = styler_settings('mobile_product_imgsize','woocommerce_thumbnail');
        $size   = wp_is_mobile() && $mobile_size ? $mobile_size : $size;
        $use_wc_image_sizes   = styler_settings('use_wc_image_sizes', '0');

        if ( '1' != styler_settings('keep_image_size', '0') ) {
            //custom image size by column filter
            switch ( $column ) {
                case '6':
                    $size = 'styler-mini';
                    break;
                case '5':
                    $size = 'styler-mini';
                    break;
                case '4':
                    $size = 'styler-medium';
                    break;
                case '3':
                    $size = 'styler-square';
                    break;
                case '2':
                    $size = 'styler-grid';
                    break;
                case '1':
                    $size = 'styler-medium';
                    break;
                default:
                    $size = styler_settings('product_imgsize','woocommerce_thumbnail');
                    $size   = wp_is_mobile() && $mobile_size ? $mobile_size : $size;
                    break;
            }
        }

        $id           = $product->get_id();
        $size         = isset( $_POST['img_size'] ) != null ? $_POST['img_size'] : $size;
        $size         = apply_filters( 'styler_product_thumb_size', $size );
        $gallery      = $product->get_gallery_image_ids();
        $has_images   = !empty( $gallery ) && !wp_is_mobile() ? 'product-link has-images' : 'product-link';
        $attr         = !empty( $gallery ) ? 'product-thumb attachment-woocommerce_thumbnail size-'.$size : 'attachment-woocommerce_thumbnail size-'.$size;
        $show_video   = get_post_meta( $id, 'styler_product_video_on_shop', true );
        $iframe_video = get_post_meta( $id, 'styler_product_iframe_video', true );
        $show_gallery = get_post_meta( $id, 'styler_loop_product_slider', true );
        $video_type   = get_post_meta( $id, 'styler_product_video_source_type', true );
        $popup_video  = get_post_meta( $id, 'styler_product_popup_video', true );
        $isshop       = is_shop() ? ' is-shop' : '';

        if ( '1' == styler_settings( 'shop_loop_custom_image_size', '0' ) ) {
            $dimensions = styler_settings( 'shop_loop_custom_image_dimensions' );
            $width  = !empty( $dimensions['width'] ) ? $dimensions['width'] : '';
            $height = !empty( $dimensions['height'] ) ? $dimensions['height'] : '';
            $size   = $width && $height ? [$width,$height] : 'woocommerce_thumbnail';
        }

        if ( $show_video == 'yes' ) {
            if ( $video_type == 'hosted' ) {
                echo '<div class="styler-loop-video-wrapper"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'"></a><video class="lazy hosted-video" width="320" height="240" autoplay muted loop><source data-src="'.$popup_video.'" type="video/mp4"></video></div>';
            } elseif ( 'vimeo' == $video_type && $iframe_video ) {
                echo '<div class="styler-loop-product-iframe-wrapper"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'"></a><iframe class="lazy vimeo-video" loading="lazy" data-src="https://player.vimeo.com/video/'.$iframe_video.'?autoplay=1&loop=1&title=0&byline=0&portrait=0&autopause=0&muted=1" frameborder="0" allow=fullscreen allow=autoplay></iframe></div>';
            } else {
                $iframe_html = '<iframe class="lazy" loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_video.'?playlist='.$iframe_video.'&modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
                echo '<div class="styler-loop-product-iframe-wrapper"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'"></a>'.$iframe_html.'</div>';
            }
        } elseif ( !empty( $gallery ) && $show_gallery == 'yes' ) {
            styler_loop_product_gallery();
        } else {
            ?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="<?php echo esc_attr( $has_images ); ?>">
                <?php
                if ( '1' == styler_settings('use_wc_image_sizes', '0') ) {
                    woocommerce_template_loop_product_thumbnail();

                    if ( !empty( $gallery ) && !wp_is_mobile() ) {
                        $wcsize = wc_get_image_size('thumbnail');
                        $width  = isset($wcsize['width']) ? $wcsize['width'] : '';
                        $height = isset($wcsize['height']) ? $wcsize['height'] : '';
                        $crop   = 1 == $wcsize['crop'] ? true : false;
                        $wc_size = $width && $height ? [$width,$height,$crop] : 'medium_large';
                        echo wp_get_attachment_image( $gallery[0], $wc_size, "", array( "class" => "overlay-thumb ".$isshop ) );
                    }
                } else {
                    echo has_post_thumbnail() ? get_the_post_thumbnail( $id, $size, array( 'class' => $attr ) ) : wc_placeholder_img( $size );
                    if ( !empty( $gallery ) && !wp_is_mobile() ) {
                        echo wp_get_attachment_image( $gallery[0], $size, "", array( "class" => "overlay-thumb ".$isshop ) );
                    }
                }
                ?>
            </a>
            <?php
        }
    }
}

/**
* loop product gallery
*/
if ( ! function_exists( 'styler_loop_product_gallery' ) ) {
    function styler_loop_product_gallery($column='')
    {
        global $product;

        $column = isset( $_GET['column'] ) ? esc_html( $_GET['column'] ) : $column;
        $size   = styler_settings('product_imgsize','woocommerce_thumbnail');
        $mobile_size = styler_settings('mobile_product_imgsize','woocommerce_thumbnail');
        $size   = wp_is_mobile() && $mobile_size ? $mobile_size : $size;
        if ( '1' != styler_settings('keep_image_size', '0') ) {
            switch ( $column ) {
                case '6':
                    $size  = 'styler-mini';
                    break;
                case '5':
                    $size  = 'styler-mini';
                    break;
                case '4':
                    $size  = 'styler-medium';
                    break;
                case '3':
                    $size  = 'styler-square';
                    break;
                case '2':
                    $size  = 'styler-grid';
                    break;
                case '1':
                    $size  = 'styler-medium';
                    break;
                default:
                    $size  = styler_settings('product_imgsize','woocommerce_thumbnail');
                    $size  = wp_is_mobile() && $mobile_size ? $mobile_size : $size;
                    break;
            }
        }
        $id           = $product->get_id();
        $data         = array();
        $show_gallery = get_post_meta( $id, 'styler_loop_product_slider', true );
        $autoplay     = get_post_meta( $id, 'styler_loop_product_slider_autoplay', true );
        $speed        = get_post_meta( $id, 'styler_loop_product_slider_speed', true );
        $gallery      = $product->get_gallery_image_ids();
        $size         = isset( $_POST['img_size'] ) != null ? $_POST['img_size'] : $size;
        $size         = apply_filters( 'styler_product_thumb_size', $size );
        $attr         = !empty( $gallery ) ? 'product-thumb attachment-woocommerce_thumbnail size-'.$size : 'attachment-woocommerce_thumbnail size-'.$size;
        $thumburl     = get_the_post_thumbnail_url( $id, $size, array( 'class' => $attr ) );
        $data[]       = 'yes' == $autoplay ? '"autoplay":true' : '"autoplay":false';
        $data[]       = is_numeric($speed) ? '"speed":'.round($speed) : '"speed":500';
        $data[]       = '"slidesPerView":1';
        $data[]       = '"pagination":{"el": ".swiper-pagination","type": "bullets","clickable":true}';
        $data         = apply_filters('styler_loop_product_slider_options', $data);

        if ( '1' == styler_settings( 'shop_loop_custom_image_size', '0' ) ) {
            $dimensions = styler_settings( 'shop_loop_custom_image_dimensions' );
            $width  = !empty( $dimensions['width'] ) ? $dimensions['width'] : '';
            $height = !empty( $dimensions['height'] ) ? $dimensions['height'] : '';
            $crop   = '1' == styler_settings( 'shop_loop_custom_image_crop', '0' ) ? true : false;
            $size   = $width && $height ? [$width,$height] : 'woocommerce_thumbnail';
        }

        wp_enqueue_script( 'swiper' );

        if ( !empty( $gallery ) && 'yes' == $show_gallery ) {
            ?>
            <div class="styler-loop-slider styler-swiper-slider swiper-container" data-swiper-options='{<?php echo implode(',', $data ); ?>}'>
                <div class="swiper-wrapper">
                    <div class="styler-loop-slider-item swiper-slide">
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="product-link" data-img="<?php echo esc_url( $thumburl ); ?>">
                            <?php
                            if ( '1' == styler_settings('use_wc_image_sizes', '0') ) {
                                woocommerce_template_loop_product_thumbnail();
                            } else {
                                echo has_post_thumbnail() ? get_the_post_thumbnail( $id, $size, ['class'=>$attr] ) : wc_placeholder_img( $size );
                            }
                            ?>
                        </a>
                    </div>
                    <?php
                    foreach ( $gallery as $img ) {
                        $imgurl = wp_get_attachment_image_url( $img, $size );
                        ?>
                        <div class="styler-loop-slider-item swiper-slide">
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="product-link" data-img="<?php echo esc_url( $imgurl ); ?>">
                                <?php
                                if ( '1' == styler_settings('use_wc_image_sizes', '0') ) {
                                    $wcsize  = wc_get_image_size('thumbnail');
                                    $width   = isset($wcsize['width']) ? $wcsize['width'] : '';
                                    $height  = isset($wcsize['height']) ? $wcsize['height'] : '';
                                    $crop    = 1 == $wcsize['crop'] ? true : false;
                                    $wc_size = $width && $height ? [$width,$height,$crop] : 'medium_large';
                                    echo wp_get_attachment_image( $img, $wc_size );
                                } else {
                                    echo wp_get_attachment_image( $img, $size );
                                }
                                ?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <?php
        } else {
            styler_loop_product_thumb($column);
        }
    }
}


/**
* Add stock to loop
*/
if ( ! function_exists( 'styler_loop_product_nostock' ) ) {
    function styler_loop_product_nostock()
    {
        global $product;
        $stock = get_post_meta( $product->get_id(), 'styler_product_hide_stock', true );

        if ( 'yes' == $stock ) {
            return;
        }

        if ( !$product->is_in_stock() ) {
            echo '<span class="styler-small-title styler-stock-status styler-out-of-stock">'.esc_html__('Out of stock', 'styler').'</span>';
        }
    }
}


/**
* Product buttons
*/
if ( ! function_exists( 'styler_add_to_cart' ) ) {
    function styler_add_to_cart()
    {
        do_action( 'woocommerce_after_shop_loop_item' );
    }
}


/**
* Cart Button with Quantity Box
*/
if ( !function_exists( 'styler_cart_with_quantity' ) ) {
    function styler_cart_with_quantity()
    {
        ?>
        <div class="product-cart-with-quantity">
            <div class="quantity ajax-quantity">
                <div class="quantity-button minus">-</div>
                <input type="text" class="input-text qty text" name="quantity" value="1" title="Menge" size="4" inputmode="numeric">
                <div class="quantity-button plus">+</div>
            </div>
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
        <?php
    }
}


/**
* Product wishlist button
*/
if ( ! function_exists( 'styler_single_product_buttons' ) ) {
    add_action( 'woocommerce_after_add_to_cart_button', 'styler_single_product_buttons', 10 );
    function styler_single_product_buttons()
    {
        if ( wp_doing_ajax() ) {
            return;
        }
        if ( '1' == styler_settings( 'buy_now_visibility', '0' ) ) {
            echo styler_add_buy_now_button_single();
            if ( 'default' == styler_settings( 'single_button_actions_position', 'default' ) ) {
                echo styler_wishlist_button();
                echo styler_compare_button();
            }
        } else {
            if ( 'default' == styler_settings( 'single_button_actions_position', 'default' ) ) {
                echo styler_wishlist_button();
                echo styler_compare_button();
            }
        }
    }
}

/**
* Product wishlist button
*/
if ( ! function_exists( 'styler_single_product_buttons_after_cart' ) ) {
    add_action( 'woocommerce_after_add_to_cart_form', 'styler_single_product_buttons_after_cart', 10 );
    function styler_single_product_buttons_after_cart()
    {
        if ( 'after-cart' == styler_settings( 'single_button_actions_position', 'default' ) ) {
            echo '<div class="styler-summary-item styler-product-after-cart">';
                echo styler_wishlist_button();
                echo styler_compare_button();
            echo '</div>';
        }
    }
}


/**
* Product wishlist button
*/
if ( ! function_exists( 'styler_wishlist_button' ) ) {
    function styler_wishlist_button()
    {
        if ( ! class_exists( 'Styler_Wishlist' ) || '0' == styler_settings( 'wishlist_visibility', '1' ) ) {
            return;
        }
        global $product;
        $id   = $product->get_id();
        $text = esc_html__( 'Add to Wishlist', 'styler' );
        $icon = styler_svg_lists( 'love', 'styler-svg-icon' );
        $html = '<div class="styler-wishlist-btn styler-product-button" data-id="' . esc_attr( $id ) . '" data-label="'.$text.'">'.$icon.'</div>';

        return apply_filters( 'styler-wishlist-btn', $html );
    }
}


/**
* Product compare button
*/
if ( ! function_exists( 'styler_compare_button' ) ) {
    function styler_compare_button()
    {
        global $product;
        $id = $product->get_id();
        $title = esc_html( $product->get_name() );
        $text  = esc_html__( 'Compare', 'styler' );
        $icon  = styler_svg_lists( 'compare', 'styler-svg-icon' );
        if ( '1' == styler_settings( 'use_compare_plugins', '0' ) ) {
            if ( class_exists( 'WPCleverWoosc' ) && 'wpc' == styler_settings( 'compare_plugin', 'wpc' ) ) {
                return do_shortcode( '[woosc id="'.$id.'" type="link"]' );
            } elseif ( defined( 'YITH_WOOCOMPARE' ) && 'yith' == styler_settings( 'compare_plugin', 'wpc' ) ) {
                return '<div class="styler-yith-compare-btn styler-product-button">'.$icon.do_shortcode( '[yith_compare_button id="'.$id.'" type="link" container="no"]' ).'</div>';
            }
        } else {
            if ( class_exists( 'Styler_Compare' ) ) {
                $html  = '<div class="styler-compare-btn styler-product-button" data-id="'.$id.'" data-label="'.$text.'" data-title="'.$title.'">'.$icon.'</div>';

                return apply_filters( 'styler-compare-btn', $html );
            }
        }
    }
}
/**
* Product compare button
*/
if ( ! function_exists( 'styler_woosc_button' ) ) {
    add_filter( 'woosc_button_html', 'styler_woosc_button' );
    function styler_woosc_button()
    {
        if ( '1' == styler_settings( 'use_compare_plugins', '0' ) && 'wpc' == styler_settings( 'compare_plugin', 'wpc' ) ) {
            global $product;
            $id     = $product->get_id();
            $name   = $product->get_name();
            $img_id = $product->get_image_id();
            $image  = wp_get_attachment_image_url( $img_id );
            $icon   = styler_svg_lists( 'compare', 'styler-svg-icon' );

            return '<div class="styler-compare-btn styler-product-button woosc-btn woosc-btn-has-icon" data-id="'.esc_attr( $id ).'" data-product_name="'.esc_attr( $name ).'" data-product_image="'.esc_attr( $image ).'">'.$icon.'</div>';
        }
    }
}


/**
* Product add to cart icon button
*/
if ( ! function_exists( 'styler_add_to_cart_button' ) ) {
    function styler_add_to_cart_button($args = array())
    {
        global $product;

        if ( $product ) {
            $id   = $product->get_id();
            $text = esc_html( $product->add_to_cart_text() );
            $icon = $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'bag' : 'arrow-right';
            $icon = styler_svg_lists( $icon, 'styler-svg-icon' );
            $type = $product->get_type();

            $defaults = array();

            $args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

            if ( isset( $args['attributes']['aria-label'] ) ) {
                $args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
            }

            $btn = sprintf(
                '<a href="%s" data-quantity="%s" class="%s" %s></a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                esc_attr( isset( $args['class'] ) ? $args['class'] : 'styler-add-to-cart-icon-link' ),
                isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : ''
            );

            return '<div class="styler-add-to-cart-btn styler-product-button type-'.$type.'" data-id="'.$id.'" data-label="'.$text.'">'.$btn.$icon.'</div>';
        }
    }
}

if ( !function_exists( 'styler_wc_loop_add_to_cart_args' ) ) {
    add_filter( 'woocommerce_loop_add_to_cart_args', 'styler_wc_loop_add_to_cart_args', 10, 2 );
    function styler_wc_loop_add_to_cart_args() {
        global $product;
        $type = $product->get_type();
        $defaults = array(
            'quantity' => 1,
            'class'    => implode(
                ' ',
                array_filter(
                    array(
                        'styler-btn-small',
                        'product_type_' . $product->get_type(),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $type == 'variable' || $type == 'grouped' ? 'styler-quick-shop-btn' : '',
                        $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'styler_ajax_add_to_cart' : '',
                    )
                )
            ),
            'attributes' => array(
                'data-product_id'  => $product->get_id(),
                'data-product_sku' => $product->get_sku(),
                'aria-label'       => $product->add_to_cart_description(),
                'rel'              => 'nofollow'
            )
        );
        return $defaults;
    }
}


/**
* Product quickview button
*/
if ( ! function_exists( 'styler_quickview_button' ) ) {
    function styler_quickview_button()
    {
        if ( class_exists( 'Styler_QuickView' ) && '1' == styler_settings('quick_view_visibility', '1') ) {
            echo do_shortcode( '[styler_quickview]' );
        }
    }
}


/**
* Product discount label
*/
if ( ! function_exists( 'styler_product_discount' ) ) {
    function styler_product_discount($echo=true)
    {
        if ( '1' == styler_settings( 'woo_catalog_mode', '0' ) ) {
            return;
        }
        global $product;
        $discount = get_post_meta( $product->get_id(), 'styler_product_discount', true );
        if ( 'yes' != $discount && $product->is_on_sale() && ! $product->is_type('variable') ) {
            $regular = (float) $product->get_regular_price();
            $sale = (float) $product->get_sale_price();
            $precision = 1; // Max number of decimals
            $saving = $sale && $regular ? round( 100 - ( $sale / $regular * 100 ), 0 ) . '%' : '';
            if ( $echo == true ) {
                echo !empty( $saving ) ? '<span class="styler-label styler-discount">'.$saving.'</span>' : '';
            } else {
                return !empty( $saving ) ? '<span class="styler-label styler-discount">'.$saving.'</span>' : '';
            }
        }
    }
}


/**
* Get all product categories
*/
if ( ! function_exists( 'styler_product_all_categories' ) ) {
    function styler_product_all_categories()
    {
        $cats = get_terms( 'product_cat' );
        $categories = array();

        if ( empty( $cats ) ) {
            return;
        }

        foreach ( $cats as $cat ) {
            $categories[] = '<a href="'.esc_url( get_term_link( $cat ) ) .'" >'. esc_html( $cat->name ) .'</a>';
        }
        return implode( ', ', $categories );
    }
}


/**
* Get all product tags
*/
if ( ! function_exists( 'styler_product_tags' ) ) {
    function styler_product_tags()
    {
        $tags = get_terms( 'product_tag' );
        $alltags = array();
        if ( empty( $tags ) ) {
            return;
        }
        foreach ( $tags as $tag ) {
            $alltags[] = '<a href="'.esc_url( get_term_link( $tag ) ) .'" >'. esc_html( $tag->name ) .'</a>';
        }
        return implode( ', ', $alltags );
    }
}


if ( ! function_exists( 'styler_product_terms' ) ) {

    /**
    * Function to return list of the terms.
    *
    * @param string 'taxonomy'
    *
    * @return html Returns the list of elements.
    */

    function styler_product_terms( $taxonomy, $label ) {

        $terms = get_the_terms( get_the_ID(), $taxonomy );

        if ( $terms && ! is_wp_error( $terms ) ) {

            $term_links = array();
            echo '<div class="styler-meta-wrapper">';
                foreach ( $terms as $term ) {
                    $term_links[] = '<a href="' . esc_url( get_term_link( $term->slug, $taxonomy ) ) . '">' . $term->name . '</a>';
                }
                $all_terms = join( ', ', $term_links );

                echo !empty( $label ) ? '<span class="styler-terms-label styler-small-title">' . $label . '</span>' : '';
                echo '<span class="styler-small-title terms-' . esc_attr( $term->slug ) . '">' . $all_terms . '</span>';
            echo '</div>';
        }
    }
}


/**
* Add product attribute name
*/
if ( ! function_exists( 'styler_product_attr_label' ) ) {
    function styler_product_attr_label()
    {
        global $product;

        $attributes = $product->get_attributes();
        foreach ( $attributes as $attribute ) {
            $values = array();
            $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ] = array(
                'label' => wc_attribute_label( $attribute->get_name() ),
                'value' => apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ),
            );
            $label = $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ]['label'];
            $value = $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ]['value'];
            echo !empty( $label ) ? '<span class="product-attr_label">'.$label.'</span>' : '';
        }
    }
}



/**
* product page gallery
*/
if ( ! function_exists( 'styler_product_gallery_slider' ) ) {
    function styler_product_gallery_slider()
    {
        global $product;
        $images = $product->get_gallery_image_ids();
        $size   = apply_filters( 'styler_product_thumb_size', 'woocommerce_single' );
        $id     = $product->get_id();
        $effect = styler_settings( 'product_gallery_slider_effect', 'slide' );

        // gallery top first thumbnail
        $img    = get_the_post_thumbnail( $id, $size );
        $full   = get_the_post_thumbnail_url( $id, 'full' );
        $url    = get_the_post_thumbnail_url( $id, $size );

        $type       = apply_filters( 'styler_product_gallery_slider_layout', styler_settings( 'product_gallery_slider_layout', 'bottom' ) );
        $direction  = 'left' == $type || 'right' == $type ? 'vertical' : 'horizontal';

        $iframe_video   = get_post_meta( get_the_ID(), 'styler_product_iframe_video', true );
        $video_img      = get_post_meta( get_the_ID(), 'styler_product_video_preview', true );
        $popup_video    = get_post_meta( get_the_ID(), 'styler_product_popup_video', true );
        $video_type     = apply_filters( 'styler_product_video_type', get_post_meta( get_the_ID(), 'styler_product_video_type', true ) );
        $video_src_type = get_post_meta( get_the_ID(), 'styler_product_video_source_type', true );

        $col1_order = '';
        $col2_order = '';

        switch ( $type ) {
            case 'bottom':
                $col1_order = ' order-2';
                $col2_order = ' order-1';
                break;
            case 'left':
                $col1_order = ' col-md-2 order-1';
                $col2_order = ' col-md-10 order-md-2';
                break;
            case 'right':
                $col1_order = ' col-md-2 order-2';
                $col2_order = ' col-md-10 order-1';
                break;
            default:
                $col1_order = '';
                $col2_order = '';
                break;
        }
        $slider_options = json_encode( apply_filters('styler_product_gallery_thumbs_js_options',
            array(
                "direction"  => $direction,
                "effect"     => $effect,
                "perview"    => styler_settings( 'product_gallery_thumbs_count', 8 ),
                "mobperview" => styler_settings( 'product_gallery_thumbs_mobile_count', 6 ),
            )
        ));

        wp_enqueue_style( 'fancybox' );
        wp_enqueue_script( 'fancybox' );

        $fullscreen  = '<span class="styler-product-popup"><i class="fas fa-expand"></i></span>';
        $arrow       = styler_settings( 'single_gallery_thumbs_arrow', 0 );
        $col1_order .= '1' == $arrow ? ' has-arrow' : '';
        ?>
        <div class="styler-swiper-slider-wrapper styler-slider-thumbs-<?php echo esc_attr( $type ); ?>">
            <div class="row">

                <div class="col-12<?php echo esc_attr( $col1_order ); ?>">
                    <div class="styler-product-thumbnails styler-swiper-thumbnails swiper-container">
                        <div class="swiper-wrapper"></div>
                        <?php if ( '1' == $arrow && 'left' != $type && 'right' != $type ) { ?>
                            <div class="swiper-button-prev styler-swiper-prev"></div>
                            <div class="swiper-button-next styler-swiper-next"></div>
                        <?php } ?>
                    </div>
                    <?php if ( '1' == $arrow && ( 'left' == $type || 'right' == $type ) ) { ?>
                        <div class="styler-thums-arrows">
                            <div class="swiper-button-prev styler-swiper-prev"></div>
                            <div class="swiper-button-next styler-swiper-next"></div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-12<?php echo esc_attr( $col2_order ); ?>">
                    <div class="styler-product-gallery-main-slider styler-swiper-main swiper-container" data-swiper-options="<?php echo esc_attr( $slider_options ); ?>">
                        <?php
                        if ( $popup_video && 'popup' == $video_type ) {
                            echo '<a href="'.$popup_video.'" class="styler-product-video-button" data-fancybox="images" data-src="'.$popup_video.'"><i class="fa fa-play"></i></a>';
                        }
                        styler_single_product_labels();
                        ?>
                        <div class="swiper-wrapper styler-gallery-items">
                            <?php
                            echo '<div class="swiper-slide styler-swiper-slide-first" data-src="'.$full.'" data-thumb="'.$url.'" data-fancybox="gallery">'.$fullscreen.$img.'</div>';
                            foreach ( $images as $image ) {
                                $gimg = wp_get_attachment_image( $image, $size );
                                $turl = wp_get_attachment_image_url( $image, $size );
                                $gurl = wp_get_attachment_image_url( $image, 'full' );
                                echo '<div class="swiper-slide" data-src="'.$gurl.'" data-thumb="'.$turl.'" data-fancybox="images">'.$fullscreen.$gimg.'</div>';
                            }
                            if ( $iframe_video && 'gallery' == $video_type ) {
                                if ( 'vimeo' == $video_src_type ) {
                                    $iframe_html = '<iframe class="lazy vimeo-video" loading="lazy" data-src="https://player.vimeo.com/video/'.$iframe_video.'?autoplay=1&loop=1&title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                                } elseif ( 'hosted' == $video_src_type ) {
                                    $iframe_html = '<video class="lazy hosted-video" width="320" height="240" autoplay muted loop><source data-src="'.$popup_video.'" type="video/mp4"></video>';
                                } else {
                                    $iframe_html = '<iframe class="lazy youtube-video" loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_video.'?modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
                                }
                                echo '<div class="swiper-slide swiper-slide-video-item iframe-video video-src-'.$video_src_type.'" data-fancybox="images" data-type="iframe" data-src="'.$popup_video.'" data-preview="'.$video_img.'" data-background="'.$video_img.'"><div class="styler-slide-iframe-wrapper">'.$fullscreen.$iframe_html.'</div></div>';
                            }
                            ?>
                        </div>

                        <div class="swiper-button-prev styler-swiper-prev"></div>
                        <div class="swiper-button-next styler-swiper-next"></div>

                    </div>
                </div>

            </div>
        </div>
        <?php
    }
}

/**
* product page gallery strecth
*/
if ( ! function_exists( 'styler_product_gallery_slider_stretch' ) ) {
    function styler_product_gallery_slider_stretch()
    {
        global $product;
        $images = $product->get_gallery_image_ids();
        $size   = apply_filters( 'styler_product_thumb_size', 'woocommerce_single' );
        $id     = $product->get_id();

        // gallery top first thumbnail
        $img    = get_the_post_thumbnail( $id, $size );
        $full   = get_the_post_thumbnail_url( $id, 'full' );

        // gallery bottom first thumbnail
        $tsize = [70,70];
        $timg  = get_the_post_thumbnail( $id, $tsize );

        $type       = 'left';
        $direction  = 'vertical';

        $iframe_video = get_post_meta( get_the_ID(), 'styler_product_iframe_video', true );
        $popup_video  = get_post_meta( get_the_ID(), 'styler_product_popup_video', true );
        $video_type   = apply_filters( 'styler_product_video_type', get_post_meta( get_the_ID(), 'styler_product_video_type', true ) );
        $video_src_type   = get_post_meta( get_the_ID(), 'styler_product_video_source_type', true );
        $video_img    = get_template_directory_uri() . '/images/video-play.png';

        $col1_order = '';
        $col2_order = '';

        switch ( $type ) {
            case 'bottom':
                $col1_order = ' order-2';
                $col2_order = ' order-1';
                break;
            case 'left':
                $col1_order = ' col-md-2 order-1';
                $col2_order = ' col-md-10 order-md-2';
                break;
            case 'right':
                $col1_order = ' col-md-2 order-2';
                $col2_order = ' col-md-10 order-1';
                break;
            default:
                $col1_order = '';
                $col2_order = '';
                break;
        }
        $main_slider_options = json_encode( apply_filters('styler_product_gallery_js_options',
            array(
                "speed"                => 800,
                "spaceBetween"         => 0,
                "slidesPerView"        => 1,
                "direction"            => "horizontal",
                "autoPlay"             => false,
                "rewind"               => true,
                "observer"             => true,
                "observeParents"       => true,
                "observeSlideChildren" => true,
                "navigation"           => [
                    "nextEl" => ".styler-product-gallery-main-slider .swiper-button-next",
                    "prevEl" => ".styler-product-gallery-main-slider .swiper-button-prev"
                ],
                "effect"               => "slide",
                "creativeEffect"       => [
                    "prev" => [ "translate" => ['0%', 0, -400] ],
                    "next" => [ "translate" => ['100%', 0, 0] ]
                ]
            )
        ));
        $thumbs_slider_options = json_encode( apply_filters('styler_product_gallery_thumbs_js_options',
            array(
                "spaceBetween"          => 10,
                "slidesPerView"         => 10,
                "direction"             => "horizontal",
                "breakpoints"   => [
                    "992" => [
                        "direction"     => "vertical",
                        "slidesPerView" => 5
                    ]
                ]
            )
        ));
        remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

        $fullscreen = '<span class="styler-product-popup"><i class="fas fa-expand"></i></span>';

        wp_enqueue_style( 'fancybox' );
        wp_enqueue_script( 'fancybox' );
        ?>
        <div class="styler-swiper-slider-wrapper styler-slider-thumbs-vertical-left">

            <div class="styler-product-gallery-main-slider styler-swiper-main swiper-container" data-swiper-options="<?php echo esc_attr( $main_slider_options ); ?>">
                <?php
                if ( $popup_video && 'popup' == $video_type ) {
                    echo '<a href="'.$popup_video.'" class="styler-product-video-button" data-fancybox="images" data-src="'.$popup_video.'"><i class="fa fa-play"></i></a>';
                }
                styler_single_product_labels();
                do_action( 'woocommerce_product_thumbnails' );
                ?>
                <div class="swiper-wrapper styler-gallery-items">
                    <?php
                    echo '<div class="swiper-slide styler-swiper-slide-first" data-fancybox="images" data-src="'.$full.'">'.$fullscreen.$img.'</div>';
                    $countt = 2;
                    foreach ( $images as $image ) {
                        $gimg = wp_get_attachment_image( $image, $size );
                        $gurl = wp_get_attachment_image_url( $image, 'full' );
                        echo '<div class="swiper-slide" data-fancybox="images" data-src="'.$gurl.'">'.$fullscreen.$gimg.'</div>';
                        $countt++;
                    }
                    if ( $iframe_video && 'gallery' == $video_type ) {
                        if ( 'vimeo' == $video_src_type ) {
                            $iframe_html = '<iframe class="lazy vimeo-video" loading="lazy" data-src="https://player.vimeo.com/video/'.$iframe_video.'?h=e1515b84ac&autoplay=1&loop=1&title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe><script src="https://player.vimeo.com/api/player.js"></script>';
                        } elseif ( 'hosted' == $video_src_type ) {
                            $iframe_html = '<video class="lazy hosted-video" width="320" height="240" autoplay muted loop><source data-src="'.$popup_video.'" type="video/mp4"></video>';
                        } else {
                            $iframe_html = '<iframe class="lazy youtube-video" loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_video.'?modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
                        }
                        echo '<div class="swiper-slide swiper-slide-video-item iframe-video" data-fancybox="images" data-src="'.$popup_video.'"><div class="styler-slide-iframe-wrapper">'.$fullscreen.$iframe_html.'</div></div>';
                    }
                    ?>
                </div>
                <?php if ( is_rtl() ) { ?>
                    <div class="swiper-button-next styler-swiper-next"></div>
                    <div class="swiper-button-prev styler-swiper-prev"></div>
                <?php } else { ?>
                    <div class="swiper-button-prev styler-swiper-prev"></div>
                    <div class="swiper-button-next styler-swiper-next"></div>
                <?php } ?>
            </div>

            <div class="styler-product-thumbnails-vertical">
                <div class="styler-product-thumbnails styler-swiper-thumbnails swiper-container" data-swiper-options="<?php echo esc_attr( $thumbs_slider_options ); ?>">
                    <div class="swiper-wrapper">
                        <?php
                        echo '<div class="swiper-slide styler-swiper-slide-first">'.$timg.'</div>';
                        foreach ( $images as $image ) {
                            echo '<div class="swiper-slide">'.wp_get_attachment_image( $image, $tsize ).'</div>';
                        }
                        if ( $iframe_video && 'gallery' == $video_type ) {
                            echo '<div class="swiper-slide swiper-slide-video-item"><img width="70" height="70" src="'.esc_url($video_img).'" /></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <?php
    }
}

/**
* product page gallery
*/
if ( ! function_exists( 'styler_product_gallery_slider_full' ) ) {
    function styler_product_gallery_slider_full()
    {
        global $product;
        $images = $product->get_gallery_image_ids();
        $size   = apply_filters( 'styler_product_thumb_size', 'woocommerce_single' );
        $id     = $product->get_id();

        // gallery top first thumbnail
        $img   = get_the_post_thumbnail( $id, $size );
        $url   = get_the_post_thumbnail_url( $id, 'full' );
        $str   = json_encode( $img );

        // gallery bottom first thumbnail
        $tsize = [90,90];
        $timg  = get_the_post_thumbnail( $id, $tsize );
        $tstr  = json_encode( $timg );

        $iframe_video = get_post_meta( get_the_ID(), 'styler_product_iframe_video', true );
        $popup_video  = get_post_meta( get_the_ID(), 'styler_product_popup_video', true );
        $video_type   = apply_filters( 'styler_product_video_type', get_post_meta( get_the_ID(), 'styler_product_video_type', true ) );
        $video_src_type   = get_post_meta( get_the_ID(), 'styler_product_video_source_type', true );

        $main_slider_options = json_encode( apply_filters('styler_product_gallery_showcase_js_options',
            array(
                "loop"                 => false,
                "speed"                => 800,
                "spaceBetween"         => 0,
                "slidesPerView"        => 1,
                "freeMode"             => false,
                "direction"            => "horizontal",
                "autoHeight"           => false,
                "observer"             => true,
                "observeParents"       => true,
                "observeSlideChildren" => true,
                "updateOnWindowResize" => true,
                "preventClicks"        => true,
                "preventClicksPropagation"        => true,
                "navigation"           => [
                    "nextEl" => ".styler-product-gallery-main-slider .swiper-button-next",
                    "prevEl" => ".styler-product-gallery-main-slider .swiper-button-prev"
                ],
                "effect"               => styler_settings('single_shop_showcase_full_effect_type', 'creative'),
                "creativeEffect"       => [
                    "prev" => [ "translate" => [0, 0, -400] ],
                    "next" => [ "translate" => ['100%', 0, 0] ]
                ]
            )
        ));
        $thumbs_slider_options = json_encode( apply_filters('styler_product_gallery_thumbs_js_options',
            array(
                "loop"          => false,
                "speed"         => 1000,
                "spaceBetween"  => 10,
                "slidesPerView" => 6,
                "freeMode"      => false,
                "direction"     => "horizontal",
                "autoHeight"    => false,
                "preventClicks" => true,
                "navigation"    => [
                    "nextEl" => ".styler-product-gallery-main-slider .swiper-button-next",
                    "prevEl" => ".styler-product-gallery-main-slider .swiper-button-prev"
                ],
                "breakpoints"   => [
                    "768" => [
                        "slidesPerView" => 8
                    ]
                ]
            )
        ));

        remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

        $fullscreen = ' <span class="styler-product-popup"><i class="fas fa-expand"></i></span>';
        wp_enqueue_style( 'fancybox' );
        wp_enqueue_script( 'fancybox' );
        ?>
        <div class="styler-swiper-slider-wrapper styler-slider-thumbs-bottom container-xl styler-container-xl">

            <div class="styler-product-gallery-main-slider styler-swiper-main swiper-container col-12 col-lg-12" data-swiper-options="<?php echo esc_attr( $main_slider_options ); ?>">
                <?php
                if ( $popup_video && 'popup' == $video_type ) {
                    echo '<a href="'.$popup_video.'" class="styler-product-video-button" data-fancybox="images" data-src="'.$popup_video.'"><i class="fa fa-play"></i></a>';
                }
                styler_single_product_labels();
                do_action( 'woocommerce_product_thumbnails' );
                ?>
                <div class="swiper-wrapper styler-gallery-items">
                    <?php
                    echo '<div class="swiper-slide styler-swiper-slide-first" data-fancybox="images" data-src="'.$url.'">'.$fullscreen.$img.'</div>';
                    foreach ( $images as $image ) {
                        $gimg = wp_get_attachment_image( $image, $size );
                        $gurl = wp_get_attachment_image_url( $image, $size );
                        echo '<div class="swiper-slide" data-fancybox="images" data-src="'.$gurl.'">'.$fullscreen.$gimg.'</div>';
                    }
                    if ( $iframe_video && 'gallery' == $video_type ) {
                        if ( 'vimeo' == $video_src_type ) {
                            $iframe_html = '<iframe class="lazy vimeo-video" loading="lazy" data-src="https://player.vimeo.com/video/'.$iframe_video.'?h=e1515b84ac&autoplay=1&loop=1&title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe><script src="https://player.vimeo.com/api/player.js"></script>';
                        } elseif ( 'hosted' == $video_src_type ) {
                            $iframe_html = '<video class="lazy hosted-video" width="320" height="240" autoplay muted loop><source data-src="'.$popup_video.'" type="video/mp4"></video>';
                        } else {
                            $iframe_html = '<iframe class="lazy youtube-video" loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_video.'?modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
                        }
                        echo '<div class="swiper-slide swiper-slide-video-item iframe-video" data-fancybox="images" data-src="'.$popup_video.'"><div class="styler-slide-iframe-wrapper">'.$fullscreen.$iframe_html.'</div></div>';
                    }
                    ?>
                </div>
                <?php if ( is_rtl() ) { ?>
                    <div class="swiper-button-next styler-swiper-next"></div>
                    <div class="swiper-button-prev styler-swiper-prev"></div>
                <?php } else { ?>
                    <div class="swiper-button-prev styler-swiper-prev"></div>
                    <div class="swiper-button-next styler-swiper-next"></div>
                <?php } ?>
            </div>

            <div class="styler-product-thumbnails styler-swiper-thumbnails styler-slider-thumbnails-full swiper-container col-12 col-lg-12" data-swiper-options="<?php echo esc_attr( $thumbs_slider_options ); ?>">
                <div class="swiper-wrapper">
                    <?php
                    echo '<div class="swiper-slide styler-swiper-slide-first">'.$timg.'</div>';
                    foreach ( $images as $image ) {
                        echo '<div class="swiper-slide">'.wp_get_attachment_image( $image, $tsize ).'</div>';
                    }
                    if ( $iframe_video && 'gallery' == $video_type ) {
                        echo '<div class="swiper-slide swiper-slide-video-item"><div class="styler-slide-video-item-icon" data-height="90"><i class="fa fa-play"></i></div></div>';
                    }
                    ?>
                </div>
            </div>

        </div>
        <?php
    }
}

/**
* product page gallery
*/
if ( ! function_exists( 'styler_product_gallery_slider_carousel' ) ) {
    function styler_product_gallery_slider_carousel()
    {
        global $product;
        $images = $product->get_gallery_image_ids();
        $size   = apply_filters( 'styler_product_carousel_lightbox_images_size', 'woocommerce_single' );
        $csize  = apply_filters( 'styler_product_carousel_images_size', [500,500] );
        $id     = $product->get_id();

        // gallery top first thumbnail
        $img = get_the_post_thumbnail( $id, $csize );
        $url = get_the_post_thumbnail_url( $id, 'full' );
        $str = json_encode( $img );

        $iframe_video = get_post_meta( get_the_ID(), 'styler_product_iframe_video', true );
        $popup_video  = get_post_meta( get_the_ID(), 'styler_product_popup_video', true );
        $video_type   = apply_filters( 'styler_product_video_type', get_post_meta( get_the_ID(), 'styler_product_video_type', true ) );
        $video_src_type   = get_post_meta( get_the_ID(), 'styler_product_video_source_type', true );
        $loader       = '<div class="loading-wrapper"><span class="ajax-loading"></span></div>';

        $main_slider_options = json_encode( apply_filters('styler_product_gallery_showcase_js_options',
            array(
                "loop"                 => '1' == styler_settings('single_shop_showcase_carousel_loop', '1') ? true : false,
                "loopedSlides"         => '1' == styler_settings('single_shop_showcase_carousel_loop', '1') ? 1 : false,
                "roundLengths"         => true,
                "speed"                => 800,
                "spaceBetween"         => 0,
                "slidesPerView"        => 1,
                "freeMode"             => false,
                "direction"            => "horizontal",
                "centeredSlides"       => true,
                "slideToClickedSlide"  => true,
                "grabCursor"           => true,
                "autoHeight"           => false,
                "preventClicks"        => false,
                "observer"             => true,
                "observeParents"       => true,
                "observeSlideChildren" => true,
                "navigation"           => [
                    "nextEl" => ".styler-product-gallery-main-slider-carousel .swiper-button-next",
                    "prevEl" => ".styler-product-gallery-main-slider-carousel .swiper-button-prev"
                ],
                "pagination"           => [
                    "el"        => ".styler-product-gallery-main-slider-carousel .swiper-pagination",
                    "type"      => "bullets",
                    "clickable" => true
                ],
                "effect"               => styler_settings('single_shop_showcase_carousel_effect_type', ''),
                "coverflowEffect"      => [
                    "rotate"       => styler_settings('single_shop_showcase_carousel_coverflow_rotate', ''),
                    "slideShadows" => false
                ],
                "breakpoints"          => [
                    "768" => [
                        "slidesPerView" => 3
                    ],
                    "1024" => [
                        "slidesPerView" => 4
                    ]
                ]
            )
        ));
        remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

        $fullscreen = '<span class="styler-product-popup"><i class="fas fa-expand"></i></span>';
        wp_enqueue_style( 'fancybox' );
        wp_enqueue_script( 'fancybox' );
        ?>
        <div class="styler-swiper-slider-wrapper">

            <div class="styler-product-gallery-main-slider-carousel styler-swiper-main swiper-container" data-swiper-options="<?php echo esc_attr( $main_slider_options ); ?>">
                <?php
                do_action( 'woocommerce_product_thumbnails' );
                ?>
                <div class="swiper-wrapper styler-gallery-items">
                    <?php
                    if ( $iframe_video && 'gallery' == $video_type ) {
                        if ( 'vimeo' == $video_src_type ) {
                            $iframe_html = '<iframe class="lazy vimeo-video" loading="lazy" data-src="https://player.vimeo.com/video/'.$iframe_video.'?h=e1515b84ac&autoplay=1&loop=1&title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe><script src="https://player.vimeo.com/api/player.js"></script>';
                        } elseif ( 'hosted' == $video_src_type ) {
                            $iframe_html = '<video class="lazy hosted-video" width="320" height="240" autoplay muted loop><source data-src="'.$popup_video.'" type="video/mp4"></video>';
                        } else {
                            $iframe_html = '<iframe class="lazy youtube-video" loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_video.'?modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
                        }
                        echo '<div class="swiper-slide swiper-slide-video-item iframe-video" data-fancybox="images" data-src="'.$popup_video.'"><div class="styler-slide-iframe-wrapper">'.$fullscreen.$iframe_html.'</div></div>';
                    }
                    echo '<div class="swiper-slide styler-swiper-slide-first" data-fancybox="images" data-src="'.$url.'">'.$fullscreen.$img.'</div>';
                    foreach ( $images as $image ) {
                        $gimg = wp_get_attachment_image( $image, $csize );
                        $gurl = wp_get_attachment_image_url( $image, 'full' );
                        echo '<div class="swiper-slide" data-fancybox="images" data-src="'.$gurl.'">'.$fullscreen.$gimg.'</div>';
                    }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
                <?php if ( is_rtl() ) { ?>
                    <div class="swiper-button-next styler-swiper-next"></div>
                    <div class="swiper-button-prev styler-swiper-prev"></div>
                <?php } else { ?>
                    <div class="swiper-button-prev styler-swiper-prev"></div>
                    <div class="swiper-button-next styler-swiper-next"></div>
                <?php } ?>
            </div>

        </div>
        <?php
    }
}

/**
* product page gallery
*/
if ( ! function_exists( 'styler_product_gallery_grid' ) ) {
    function styler_product_gallery_grid()
    {
        global $product;
        $column = apply_filters( 'styler_product_gallery_type_column', styler_settings( 'styler_product_gallery_grid_column', '2' ) );
        $images = $product->get_gallery_image_ids();
        $size   = apply_filters( 'styler_product_thumb_size', 'woocommerce_single' );
        $id     = $product->get_id();

        // gallery top first thumbnail
        $img   = get_the_post_thumbnail( $product->get_id(), $size );
        $url   = get_the_post_thumbnail_url( $product->get_id(), $size );
        $str   = json_encode($img);

        $iframe_video = get_post_meta( get_the_ID(), 'styler_product_iframe_video', true );
        $popup_video  = get_post_meta( get_the_ID(), 'styler_product_popup_video', true );
        $video_type   = apply_filters( 'styler_product_video_type', get_post_meta( get_the_ID(), 'styler_product_video_type', true ) );
        $video_src_type   = get_post_meta( get_the_ID(), 'styler_product_video_source_type', true );

        switch ( $column ) {
            case '1':
                $tsize = 'woocommerce_single';
                break;
            case '2':
                $tsize = [500,500];
                break;
            case '3':
                $tsize = [300,300];
                break;
            case '4':
                $tsize = [200,200];
                break;
            default:
                $tsize = [400,400];
                break;
        }
        remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

        $fullscreen = '<span class="styler-product-popup"><i class="fas fa-expand"></i></span>';
        wp_enqueue_style( 'fancybox' );
        wp_enqueue_script( 'fancybox' );
        ?>
        <div class="styler-product-main-gallery-grid grid-column-<?php echo esc_attr( $column ); ?>">
            <?php
            if ( $iframe_video || $popup_video ) {
                if ( 'gallery' == $video_type ) {

                    if ( $iframe_video ) {
                        if ( 'vimeo' == $video_src_type ) {
                            $iframe_html = '<iframe class="lazy vimeo-video" loading="lazy" data-src="https://player.vimeo.com/video/'.$iframe_video.'?h=e1515b84ac&autoplay=1&loop=1&title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe><script src="https://player.vimeo.com/api/player.js"></script>';
                        } elseif ( 'hosted' == $video_src_type ) {
                            $iframe_html = '<video class="lazy hosted-video" width="320" height="240" autoplay muted loop><source data-src="'.$popup_video.'" type="video/mp4"></video>';
                        } else {
                            $iframe_html = '<iframe class="lazy youtube-video" loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_video.'?modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
                        }
                        echo '<div class="swiper-slide swiper-slide-video-item iframe-video" data-fancybox="images" data-src="'.$popup_video.'"><div class="styler-slide-iframe-wrapper">'.$fullscreen.$iframe_html.'</div></div>';
                    }
                } else {
                    if ( $popup_video ) {
                        echo '<a href="'.$popup_video.'" class="styler-product-video-button mfp-iframe"><i class="fa fa-play"></i></a>';
                    }
                }
            }
            styler_single_product_labels();
            do_action( 'woocommerce_product_thumbnails' );
            echo '<div class="styler-gallery-grid-item styler-gallery-grid-item-first" data-fancybox="images" data-src="'.esc_url( $url ).'">'.$fullscreen.$img.'</div>';
            if ( !empty( $images ) ) {
                echo '<div class="row row-cols-1 row-cols-sm-'.esc_attr( $column ).'">';
                foreach ( $images as $image ) {
                    $gimg = wp_get_attachment_image( $image, $tsize );
                    $gurl = wp_get_attachment_image_url( $image, $size );
                    echo '<div class="col styler-gallery-grid-item mt-30" data-fancybox="images" data-src="'.esc_url( $gurl ).'">'.$fullscreen.$gimg.'</div>';
                }
                echo '</div>';
            }
            ?>
        </div>
        <?php
    }
}


if ( ! function_exists( 'styler_single_product_nav_two' ) ) {
    function styler_single_product_nav_two() {

        if ( '0' == styler_settings('single_shop_nav_visibility', '1') ) {
            return;
        }
        $prev    = get_previous_post();
        $prevID  = $prev ? $prev->ID : '';
        $next    = get_next_post();
        $nextID  = $next ? $next->ID : '';
        $imgSize = array(40,40,true);
        ?>
        <div class="styler-product-nav styler-flex styler-align-center">
            <?php if ( $prevID ) : ?>
                <a class="product-nav-link styler-nav-prev" href="<?php echo esc_url( get_permalink( $prevID ) ); ?>">
                    <span class="styler-nav-arrow styler-nav-prev-arrow"></span>
                    <span class="product-nav-content">
                        <?php echo apply_filters( 'styler_products_nav_image', get_the_post_thumbnail( $prevID, $imgSize ) ); ?>
                        <span class="product-nav-title"><?php echo get_the_title( $prevID ); ?></span>
                    </span>
                </a>
            <?php else : ?>
                <a class="product-nav-link styler-nav-prev disabled" href="#0">
                    <span class="styler-nav-arrow styler-nav-prev-arrow"></span>
                </a>
            <?php endif ?>

            <a href="<?php echo apply_filters( 'styler_single_product_back_btn_url', get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="product-nav-link styler-nav-shop">
                <span class="styler-shop-link-inner">
                    <span class="styler-shop-link-icon"></span>
                    <span class="styler-shop-link-icon"></span>
                </span>
            </a>

            <?php if ( $nextID ) : ?>
                <a class="product-nav-link styler-nav-next" href="<?php echo esc_url( get_permalink( $nextID ) ); ?>">
                    <span class="styler-nav-arrow styler-nav-next-arrow"></span>
                    <span class="product-nav-content">
                        <?php echo apply_filters( 'styler_products_nav_image', get_the_post_thumbnail( $nextID, $imgSize ) ); ?>
                        <span class="product-nav-title"><?php echo get_the_title( $nextID ); ?></span>
                    </span>
                </a>
            <?php else : ?>
                <a class="product-nav-link styler-nav-next disabled" href="#0">
                    <span class="styler-nav-arrow styler-nav-next-arrow"></span>
                </a>
            <?php endif ?>
        </div>
        <?php
    }
}


/**
* Add stock progressbar
*/
if ( ! function_exists( 'styler_product_stock_progress_bar' ) ) {
    function styler_product_stock_progress_bar() {
        global $post,$product;
        $product_id   = $post->ID;
        $progressbar  = styler_settings( 'single_shop_progressbar_visibility', '0' );
        $manage_stock = get_post_meta( $product_id, '_manage_stock', true );

        if ( $manage_stock != 'yes' || '0' == $progressbar ) {
            return;
        }

        $current_stock = get_post_meta( $product_id, '_stock', true );
        $total_sold    = $product->get_total_sales();
        $percentage    = $total_sold > 0 && $current_stock > 0 ? round( $total_sold / $current_stock * 100 ) : 0;

        if ( $current_stock > 0 ) {
            ?>
            <div class="styler-summary-item styler-single-product-stock">
                <div class="stock-details">
                    <div class="stock-sold"><?php esc_html_e( 'Ordered:', 'styler' ); ?><span> <?php echo esc_html( $total_sold ); ?></span></div>
                    <div class="current-stock"><?php esc_html_e( 'Items available:', 'styler' ); ?><span> <?php echo esc_html( wc_trim_zeros( $current_stock ) ); ?></span></div>
                </div>
                <div class="styler-product-stock-progress">
                    <div class="styler-product-stock-progressbar" data-stock-percent="<?php echo esc_attr( $percentage ); ?>%"></div>
                </div>
            </div>
            <?php
        }
    }
}

/**
* Add size guide popup
*/
if ( ! function_exists( 'styler_product_popup_details' ) ) {
    add_action( 'woocommerce_single_product_summary', 'styler_product_popup_details', 35 );
    function styler_product_popup_details()
    {
        $product_id = get_the_ID();
        $guide      = get_post_meta( $product_id, 'styler_product_size_guide', true );
        $question   = styler_settings( 'single_shop_question_form_template', null );
        $delivery   = styler_settings( 'single_shop_delivery_template', null );
        $estimated  = '0' == styler_settings('single_shop_estimated_delivery_visibility', '0' );
        $visitor    = '0' == styler_settings('single_shop_visit_count_visibility', '0' );
        if ( $guide || $question || $delivery || '1' == $estimated || '1' == $visitor ) {
            ?>
            <div class="styler-summary-item styler-product-popup-details">
                <?php
                styler_product_size_guide();
                styler_product_delivery_return();
                styler_product_question_form();
                styler_product_estimated_delivery();
                styler_product_views();
                ?>
            </div>
            <?php
        }
    }
}
/**
* Add size guide popup
*/
if ( ! function_exists( 'styler_product_size_guide' ) ) {
    function styler_product_size_guide()
    {
        $product_id = get_the_ID();
        $guide_id   = get_post_meta( $product_id, 'styler_product_size_guide', true );

        if ( '0' == styler_settings( 'single_shop_size_guide_visibility', '0' ) && '' == $guide_id ) {
            return;
        }
        $title = styler_settings( 'product_shop_question_title', '' );
        $title = $title ? $title : esc_html__( 'Size Guide', 'styler' );
        ?>
        <div class="styler-size-guide-btn has-svg-icon styler-flex styler-align-center">
            <?php echo styler_svg_lists('ruler', 'styler-svg-icon'); ?>&nbsp;
            <a href="#styler_size_guide_<?php echo esc_attr( $product_id ); ?>" class="styler-open-popup"><?php echo esc_html( $title ); ?></a>
        </div>
        <div class="styler-single-product-size-guide styler-popup-content-big zoom-anim-dialog mfp-hide" id="styler_size_guide_<?php echo esc_attr( $product_id ); ?>">
            <?php styler_print_elTemplates_by_category( $guide_id, '', true ); ?>
        </div>
        <?php
    }
}

/**
* Add question form popup
*/
if ( ! function_exists( 'styler_product_question_form' ) ) {
    function styler_product_question_form()
    {
        global $product;
        $template_id = styler_settings( 'single_shop_question_form_template', null );

        if ( null == $template_id || '' == $template_id ) {
            return;
        }
        $terms = wc_get_product_term_ids( $product->get_id(), 'product_cat' );
        $category_exclude = styler_settings( 'single_shop_question_template_category_exclude', null );
        $icon = styler_settings( 'single_shop_question_icon', '' );
        $icon = trim($icon) ? $icon : styler_svg_lists('question', 'styler-svg-icon');
        if ( !empty($terms) ) {
            foreach ($terms as $term ) {
                if ( !empty($category_exclude) ) {
                    foreach ($category_exclude as $val ) {
                        if ( $term == $val ) {
                            return;
                        }
                    }
                }
            }
        }
        $title = styler_settings( 'product_shop_question_title', '' );
        $title = $title ? $title : esc_html__( 'Size Guide', 'styler' );
        ?>
        <div class="styler-product-question-btn has-svg-icon styler-flex styler-align-center">
            <?php printf('%s',$icon); ?>&nbsp;
            <a href="#styler_product_question_<?php echo esc_attr( $template_id ); ?>" class="styler-open-popup"><?php echo esc_html( $title ); ?></a>
        </div>
        <div class="styler-single-product-question styler-popup-content-big zoom-anim-dialog mfp-hide" id="styler_product_question_<?php echo esc_attr( $template_id ); ?>">
            <?php echo styler_print_elementor_templates( 'single_shop_question_form_template', '' ); ?>
        </div>
        <?php
    }
}


/**
* Add delivery and return popup
*/
if ( ! function_exists( 'styler_product_delivery_return' ) ) {
    function styler_product_delivery_return()
    {
        global $product;
        $template_id = styler_settings( 'single_shop_delivery_template', null );
        $terms = wc_get_product_term_ids( $product->get_id(), 'product_cat' );
        $category_exclude = styler_settings( 'single_shop_delivery_template_category_exclude', null );
        $icon = styler_settings( 'single_shop_delivery_icon', '' );
        $icon = trim($icon) ? $icon : styler_svg_lists('delivery-return', 'styler-svg-icon');
        if ( !empty($terms) ) {
            foreach ($terms as $term ) {
                if ( !empty($category_exclude) ) {
                    foreach ($category_exclude as $val ) {
                        if ( $term == $val ) {
                            return;
                        }
                    }
                }
            }
        }

        if ( null == $template_id || '' == $template_id ) {
            return;
        }
        $title = styler_settings( 'product_delivery_return_title', '' );
        $title = $title ? $title : esc_html__( 'Delivery & Return', 'styler' );
        ?>
        <div class="styler-product-delivery-btn has-svg-icon styler-flex styler-align-center">
            <?php printf('%s',$icon); ?>&nbsp;
            <a href="#styler_product_delivery_<?php echo esc_attr( $template_id ); ?>" class="styler-open-popup"><?php echo esc_html( $title ); ?></a>
        </div>
        <div class="styler-single-product-delivery styler-popup-content-big zoom-anim-dialog mfp-hide" id="styler_product_delivery_<?php echo esc_attr( $template_id ); ?>">
            <?php echo styler_print_elementor_templates( 'single_shop_delivery_template', '' ); ?>
        </div>
        <?php
    }
}

if ( ! function_exists( 'styler_product_views' ) ) {
    function styler_product_views()
    {
        if ( '0' == styler_settings('single_shop_visit_count_visibility', '0' ) ) {
            return;
        }
        wp_enqueue_script( 'jquery-cookie');
        global $product;

        $data[] = styler_settings( 'visit_count_min' ) ? '"min":' . styler_settings( 'visit_count_min' ) : '"min":10';
        $data[] = styler_settings( 'visit_count_max' ) ? '"max":' . styler_settings( 'visit_count_max' ) : '"max":50';
        $data[] = styler_settings( 'visit_count_delay' ) ? '"delay":' . styler_settings( 'visit_count_delay' ) : '"delay":30000';
        $data[] = styler_settings( 'visit_count_change' ) ? '"change":' . styler_settings( 'visit_count_change' ) : '"change":5';
        $data[] = '"id":' . $product->get_id();
        $icon = styler_settings( 'single_shop_visit_count_icon', '' );
        $icon = trim($icon) ? $icon : styler_svg_lists('smile', 'styler-svg-icon');
        ?>
        <div class="styler-product-view" data-product-view='{<?php echo implode(',', $data ); ?>}'>
            <?php printf('%s',$icon); ?>&nbsp;
            <span><span class="styler-view-count">&nbsp;</span> <?php esc_html_e( 'people', 'styler' ); ?></span>&nbsp;
            <?php esc_html_e( 'are viewing this right now', 'styler' ); ?>
        </div>
        <?php
    }
}

if ( ! function_exists( 'styler_product_estimated_delivery' ) ) {
    function styler_product_estimated_delivery() {

        if ( '0' == styler_settings('single_shop_estimated_delivery_visibility', '0' ) ) {
            return;
        }
        $title = styler_settings( 'product_estimated_delivery_title',  );
        $title = $title ? $title : esc_html__( 'Estimated Delivery', 'styler' );
        $icon = styler_settings( 'single_shop_estimated_delivery_icon', '' );
        $icon = trim($icon) ? $icon : styler_svg_lists('shipping', 'styler-svg-icon');

        $min_ed = styler_settings('single_shop_min_estimated_delivery');
        $max_ed = styler_settings('single_shop_max_estimated_delivery');

        $min = $min_ed ? $min_ed : 3;
        $from = '+' . $min;
        $from .= ' ' . ( $min = 1 ? 'day' : 'days' );

        $max = $max_ed ? (int) $max_ed : 7;
        $to = '+' . $max;
        $to .= ' ' . ( $max = 1 ? 'day' : 'days' );

        $now = get_date_from_gmt( date('Y-m-d H:i:s'), 'Y-m-d' );
        $est_days = array();

        $format = esc_html__( 'M d', 'styler' );
        $est_days[] = date_i18n( $format, strtotime( $now . $from ), true );
        $est_days[] = date_i18n( $format, strtotime( $now . $to ), true );

        if ( !empty( $est_days ) ) {
            ?>
            <div class="styler-estimated-delivery">
                <?php printf('%s',$icon); ?>&nbsp;
                <span><?php echo esc_html( $title ); ?>&nbsp;</span>
                <?php echo implode( ' ', $est_days ); ?>
            </div>
            <?php
        }
    }
}


/**
* Add product excerpt
*/
if ( ! function_exists( 'styler_product_excerpt' ) ) {
    function styler_product_excerpt()
    {
        global $product;
        if ( $product->get_short_description() ) {
            $limit = styler_settings('shop_loop_excerpt_limit');
            ?>
            <p class="styler-product-excerpt"><?php echo wp_trim_words( $product->get_short_description(), apply_filters( 'styler_loop_excerpt_limit', $limit ) ); ?></p>
            <?php
        }
    }
}

/**
* Add product rating
*/
if ( ! function_exists( 'styler_product_rating' ) ) {

    function styler_product_rating()
    {
        global $product;
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average      = $product->get_average_rating();
        if ( $product->get_average_rating() ) {
            ?>
            <div class="styler-rating star-rating">
                <span data-width="<?php echo esc_attr( ( $average / 5 ) * 100  ); ?>"></span>
                <?php if ( comments_open() ) { ?>
                    <a href="#reviews" class="styler-review-link styler-small-title" rel="nofollow"><?php printf( _n( '%s review', '%s reviews', $review_count, 'styler' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?></a>
                <?php } ?>
            </div>
            <?php
        }
    }
}

/**
* Add product rating
*/
if ( ! function_exists( 'styler_product_meta' ) ) {

    function styler_product_meta()
    {
        global $product;
        ?>
        <div class="styler-summary-item styler-product-meta">
            <?php do_action( 'woocommerce_product_meta_start' ); ?>
            <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="styler-small-title posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'styler' ) . ' ', '</span>' ); ?>
            <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="styler-small-title tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'styler' ) . ' ', '</span>' ); ?>
            <?php do_action( 'woocommerce_product_meta_end' ); ?>
        </div>
        <?php
    }
}


/**
* Get product sku
*/
if ( ! function_exists( 'styler_product_sku' ) ) {
    function styler_product_sku()
    {
        global $product;
        if ( $product->get_sku() ) {
        echo '<div class="styler-meta-wrapper styler-small-title"><span class="styler-terms-label">'.esc_html__('SKU:', 'styler') .'</span><span class="styler-sku">'.esc_html( $product->get_sku() ).'</span></div>';
        }
    }
}


if ( ! function_exists( 'styler_product_badge' ) ) {
    function styler_product_badge($echo=true)
    {
        if ( '1' == styler_settings( 'woo_catalog_mode', '0' ) ) {
            return;
        }
        global $product;
        $title  = get_post_meta( $product->get_id(), 'styler_custom_badge', true );
        $ishtml = get_post_meta( $product->get_id(), 'styler_custom_html_badge', true );
        $color  = get_post_meta( $product->get_id(), 'styler_badge_color', true );
        $color  = $color ? $color : '';

        if ( true == $echo ) {
            if ( '' != $title ) {
                if ( 'yes' == $ishtml ) {
                echo '<span class="styler-label styler-badge styler-custom-html-badge">'.$title.'</span>';
                } else {
                    echo '<span class="styler-label styler-badge badge-'.esc_attr( $title ).'" data-label-color="'.$color.'">'.esc_html( $title ).'</span>';
                }
            } else {
                if ( $product->is_on_sale() ) {
                    echo '<span class="styler-label styler-badge badge-def" data-label-color="'.esc_attr( $color ).'">'.esc_html__( 'Sale!', 'styler' ).'</span>';
                }
            }
        } else {
            if ( '' != $title ) {
                if ( 'yes' == $ishtml ) {
                    return '<span class="styler-label styler-badge styler-custom-html-badge">'.$title.'</span>';
                    } else {
                        return '<span class="styler-label styler-badge badge-'.esc_attr( $title ).'" data-label-color="'.$color.'">'.esc_html( $title ).'</span>';
                    }
            } else {
                if ( $product->is_on_sale() ) {
                    return '<span class="styler-label styler-badge badge-def" data-label-color="'.esc_attr( $color ).'">'.esc_html__( 'Sale!', 'styler' ).'</span>';
                }
            }
        }
    }
}

/**
* Single product labels
*/
if ( ! function_exists( 'styler_single_product_labels' ) ) {
    function styler_single_product_labels()
    {
        if ( '0' == styler_settings('single_shop_top_labels_visibility', '1' ) || '1' == styler_settings( 'woo_catalog_mode', '0' ) ) {
            return;
        }
        echo '<div class="styler-product-labels">';
            styler_product_badge();
            styler_product_discount();
        echo '</div>';
    }
}


if ( ! function_exists( 'styler_loop_category_title' ) ) {

    /**
    * Show the subcategory title in the product loop.
    *
    * @param object $category Category object.
    */
    function styler_loop_category_title( $category ) {
        ?>
        <h4 class="styler-loop-category-title">
            <?php
            echo esc_html( $category->name );

            if ( $category->count > 0 ) {
                echo '<span class="cat-count">' . esc_html( $category->count ) . '</span>';
            }
            ?>
        </h4>
        <?php
    }
}


/**
* product brand
*/
if ( ! function_exists( 'styler_product_brands' ) ) {
    function styler_product_brands()
    {
        global $product;
        $brands = '';
        $metaid = defined( 'YITH_WCBR' ) ? 'yith_product_brand' : 'styler_product_brands';
        $terms = get_the_terms( $product->get_id(), $metaid );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $brands = array();
            foreach ( $terms as $term ) {
                if ( $term->parent == 0 ) {
                    $brands[] = sprintf( '<a class="styler-brands" href="%s" itemprop="brand" title="%s">%s</a>',
                        get_term_link( $term ),
                        $term->slug,
                        $term->name
                    );
                }
            }
        }
        echo !empty( $brands ) ? '<span class="styler-brands">'.esc_html__('Brands: ', 'styler' ) . implode( ', ', $brands ) .'</span>' : '';
    }
}



/**
*  add custom color field to for product badge
*/
if ( ! function_exists( 'styler_wc_product_meta_color' ) ) {

    function styler_wc_product_meta_color( $field )
    {
        global $thepostid, $post;

        $thepostid      = empty( $thepostid ) ? $post->ID : $thepostid;
        $field['class'] = isset( $field['class'] ) ? $field['class'] : 'styler-color-field';
        $field['value'] = isset( $field['value'] ) ? $field['value'] : get_post_meta( $thepostid, $field['id'], true );

        echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>
        <input type="text" class="styler-color-field" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" /></p>';
    }
}


/**
*  countdown for product
*/
if ( ! function_exists( 'styler_product_countdown' ) ) {
    function styler_product_countdown()
    {
        if ( '0' != styler_settings('single_shop_countdown_visibility','1') ) {
            global $product;
            $ot_time = styler_settings('product_countdown_date');
            $class   = ' type-'.styler_settings('product_countdown_type');
            $class  .= ' width-'.styler_settings('product_countdown_separator_width');
            $sep     = ' separator-'.styler_settings('product_countdown_separator_type');
            $mb_time = get_post_meta( $product->get_id(), 'styler_countdown_date', true);
            $time    = $mb_time ? $mb_time : $ot_time;
            $ot_text = styler_settings('product_countdown_before_text');
            $text    = get_post_meta( $product->get_id(), 'styler_countdown_text', true);
            $text    = $text ? $text : $ot_text;
            $text2   = styler_settings('product_countdown_after_text');
            $class  .= $text2 ? ' has-after-text' : '';

            if ( $time ) {
                wp_enqueue_script( 'jquery-countdown' );
                wp_enqueue_script( 'styler-countdown' );

                $current_time = date( 'm/d/Y' );
                $data[]       = '"date":"'.$time.'"';

                if ( ( $current_time == $time || $time < $current_time ) && '1' == styler_settings('product_countdown_update', '0') ) {
                    $next_time = styler_settings('product_countdown_update_next', 7 );
                    $time      = date('m/d/Y', strtotime($time. ' + '.$next_time.' days'));
                    $data[]    = '"date":"'.$time.'"';
                }

                $data[] = '"day":"'.esc_html__('day', 'styler').'"';
                $data[] = '"hr":"'.esc_html__('hour', 'styler').'"';
                $data[] = '"min":"'.esc_html__('min', 'styler').'"';
                $data[] = '"sec":"'.esc_html__('sec', 'styler').'"';
                $data[] = '"expired":"'.esc_html( styler_settings('product_countdown_expired_text', 7 ) ).'"';

                echo '<div class="styler-summary-item styler-viewed-offer-time'.$class.'">';
                    if ( '4' == styler_settings('product_countdown_type') ) {
                        echo '<div class="styler-coming-time-icon">'.styler_settings('product_countdown_icon').'</div>';
                        echo '<div class="styler-coming-time-details">';
                    }
                    if ( $text ) {
                        echo '<p class="offer-time-text">'.$text.'</p>';
                    }
                    echo '<div class="styler-coming-time '.$sep.'" data-countdown=\'{'.implode(', ', $data ).'}\'></div>';
                    if ( $text2 ) {
                        echo '<p class="offer-time-text-after">'.$text2.'</p>';
                    }
                    if ( '4' == styler_settings('product_countdown_type') ) {
                        echo '</div>';
                    }
                echo '</div>';
            }
        }
    }
}


/**
*  custom extra tabs for product page
*/
if ( ! function_exists( 'styler_wc_extra_tabs_array' ) ) {
    function styler_wc_extra_tabs_array()
    {
        global $product;
        $tabs          = array();
        $ottab_title   = styler_settings('product_extra_tab_title');
        $ottab_content = styler_settings('product_extra_tab_content');
        $tab_title     = get_post_meta( $product->get_id(), 'styler_tabs_title', true);
        $tab_content   = get_post_meta( $product->get_id(), 'styler_tabs_content', true);
        $tab_title     = $tab_title ? $tab_title : $ottab_title;
        $tab_content   = $tab_content ? $tab_content : $ottab_content;
        $tabtitle      = preg_split("/\\r\\n|\\r|\\n/", $tab_title );
        $tabcontent    = preg_split("/\\r\\n|\\r|\\n/", $tab_content );

        $count    = 30;
        foreach( styler_combine_arr($tabtitle, $tabcontent) as $title => $details ) {
            if ( !empty( $title ) && !empty( $details ) ) {
                $replaced_title = preg_replace('/\s+/', '_', strtolower(trim($title)));
                $tabs[$replaced_title] = array(
                    'title' => $title,
                    'priority' => $count,
                    'content' => $details
                );
            }
            $count = $count + 10;
        }
        return $tabs;
    }
}


/*
* Tab
*/
if ( ! function_exists( 'styler_product_settings_tabs' ) ) {
    add_filter('woocommerce_product_data_tabs', 'styler_product_settings_tabs' );
    function styler_product_settings_tabs( $tabs ){
        $tabs['styler_general'] = array(
            'label'    => esc_html__('Styler General', 'styler'),
            'target'   => 'styler_product_general_data',
            'priority' => 100,
        );
        $tabs['styler_product_page'] = array(
            'label'    => esc_html__('Styler Product Page', 'styler'),
            'target'   => 'styler_product_page_data',
            'priority' => 101,
        );
        return $tabs;
    }
}
/*
* Tab content
*/
if ( ! function_exists( 'styler_product_panels' ) ) {
    add_action( 'woocommerce_product_data_panels', 'styler_product_panels' );
    function styler_product_panels(){

        echo '<div id="styler_product_general_data" class="panel woocommerce_options_panel hidden">';
            echo '<h3 class="styler-panel-heading">'.esc_html__('Styler Product General Settings', 'styler').'</h3>';
            woocommerce_wp_checkbox(
                array(
                    'id' => 'styler_loop_product_slider',
                    'label' => esc_html__( 'Show Slider Thumbnails on Archive page?', 'styler' ),
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_checkbox(
                array(
                    'id' => 'styler_loop_product_slider_autoplay',
                    'label' => esc_html__( 'Slider Autoplay?', 'styler' ),
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => 'styler_loop_product_slider_speed',
                    'label' => esc_html__( 'Slider Speed ( ms )', 'styler' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Duration of transition between slides (in ms).Use simple number', 'styler' ),
                )
            );
            echo '<div class="styler-panel-divider"></div>';
            woocommerce_wp_checkbox(
                array(
                    'id' => 'styler_product_discount',
                    'label' => esc_html__( 'Hide Product Discount?', 'styler' ),
                    'wrapper_class' => 'hide_if_variable',
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_checkbox(
                array(
                    'id' => 'styler_product_hide_stock',
                    'label' => esc_html__( 'Hide Product Stock Label?', 'styler' ),
                    'wrapper_class' => 'hide_if_variable',
                    'desc_tip' => false,
                )
            );
            echo '<div class="styler-panel-divider"></div>';
            echo '<h4 class="styler-panel-subheading">'.esc_html__('Badge Settings', 'styler').'</h4>';
            woocommerce_wp_text_input(
                array(
                    'id' => 'styler_custom_badge',
                    'label' => esc_html__( 'Badge Label', 'styler' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Add your custom badge label here', 'styler' ),
                )
            );
            woocommerce_wp_checkbox(
                array(
                    'id' => 'styler_custom_html_badge',
                    'label' => esc_html__( 'Use Custom HTML?', 'styler' ),
                    'description' => esc_html__( 'Add your custom html label here.', 'styler' ),
                    'desc_tip' => true,
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_badge_color',
                    'label' => esc_html__( 'Badge Color', 'styler' ),
                )
            );

            echo '<div class="styler-panel-divider"></div>';
            echo '<h4 class="styler-panel-subheading">'.esc_html__('Countdown Settings', 'styler').'</h4>';
            woocommerce_wp_text_input(
                array(
                    'id' => 'styler_countdown_date',
                    'label' => esc_html__( 'Date for Countdown', 'styler' ),
                    'desc_tip' => true,
                    'description' => sprintf('%s <br/> %s%s',
                        esc_html__( 'Usage : month/day/year', 'styler' ),
                        esc_html__( 'Example : ', 'styler' ),
                        date('m/d/Y', strtotime('+1 month'))
                    )
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => 'styler_countdown_text',
                    'label' => esc_html__( 'Countdown Text', 'styler' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Add your custom text here', 'styler' ),
                )
            );
        echo '</div>';

        echo '<div id="styler_product_page_data" class="panel woocommerce_options_panel hidden">';
            echo '<h3 class="styler-panel-heading">'.esc_html__('Styler Product Page Settings', 'styler').'</h3>';
            echo '<h4 class="styler-panel-subheading">'.esc_html__('Product Header Type Settings', 'styler').'</h4>';
            woocommerce_wp_select(
                array(
                    'id' => 'styler_product_header_type',
                    'label' => esc_html__( 'Header Type?', 'styler' ),
                    'options' => array(
                        '' => 'Select a type',
                        'default' => esc_html__( 'Theme options settings', 'styler' ),
                        'dark' => esc_html__( 'Dark', 'styler' ),
                        'trans-light' => esc_html__( 'Transparent Light', 'styler' ),
                        'trans-dark' => esc_html__( 'Transparent Dark', 'styler' ),
                        'custom' => esc_html__( 'Custom Color', 'styler' ),
                    ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'You can use this option to use a different header for this product', 'styler' )
                )
            );
            echo '<h4 class="styler-panel-subheading menu-customize">'.esc_html__('Header Custom Color Settings', 'styler').'</h4>';
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_header_bgcolor',
                    'label' => esc_html__( 'Header Background Color', 'styler' )
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_header_menuitem_color',
                    'label' => esc_html__( 'Menu Item Color', 'styler' )
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_header_menuitem_hvrcolor',
                    'label' => esc_html__( 'Menu Item Hover/Active Color', 'styler' )
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_header_svgicon_color',
                    'label' => esc_html__( 'Header SVG Buttons Color', 'styler' )
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_header_counter_bgcolor',
                    'label' => esc_html__( 'Header SVG Buttons Counter Background Color', 'styler' )
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_header_counter_color',
                    'label' => esc_html__( 'Header SVG Buttons Counter Color', 'styler' )
                )
            );
            echo '<h4 class="styler-panel-subheading menu-customize">'.esc_html__('Sticky Header Custom Color Settings', 'styler').'</h4>';
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_sticky_header_bgcolor',
                    'label' => esc_html__( 'Sticky Header Background Color', 'styler' )
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_sticky_header_menuitem_color',
                    'label' => esc_html__( 'Menu Item Color', 'styler' )
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_sticky_header_menuitem_hvrcolor',
                    'label' => esc_html__( 'Menu Item Hover/Active Color', 'styler' )
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_sticky_header_svgicon_color',
                    'label' => esc_html__( 'Header SVG Buttons Color', 'styler' )
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_sticky_header_counter_bgcolor',
                    'label' => esc_html__( 'Header SVG Buttons Counter Background Color', 'styler' )
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_product_sticky_header_counter_color',
                    'label' => esc_html__( 'Header SVG Buttons Counter Color', 'styler' )
                )
            );
            echo '<div class="styler-panel-divider"></div>';
            echo '<h4 class="styler-panel-subheading">'.esc_html__('Product Showcase Type Settings', 'styler').'</h4>';
            woocommerce_wp_select(
                array(
                    'id' => 'styler_showcase_type',
                    'label' => esc_html__( 'Showcase Type?', 'styler' ),
                    'options' => array(
                        '' => 'Select a type',
                        'full' => esc_html__( 'Full', 'styler' ),
                        'carousel' => esc_html__( 'Carousel', 'styler' ),
                    ),
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_select(
                array(
                    'id' => 'styler_showcase_bg_type',
                    'label' => esc_html__( 'Background Color Type?', 'styler' ),
                    'options' => array(
                        '' => 'Select a type',
                        'dark' => esc_html__( 'Dark', 'styler' ),
                        'light' => esc_html__( 'Light', 'styler' ),
                        'custom' => esc_html__( 'Custom Color', 'styler' ),
                    ),
                    'desc_tip' => false,
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_showcase_custom_bgcolor',
                    'label' => esc_html__( 'Custom Background Color', 'styler' ),
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_showcase_custom_textcolor',
                    'label' => esc_html__( 'Custom Text Color', 'styler' ),
                )
            );
            echo '<div class="styler-panel-divider"></div>';
            echo '<h4 class="styler-panel-subheading">'.esc_html__('Product Showcase Type Settings', 'styler').'</h4>';
            woocommerce_wp_select(
                array(
                    'id' => 'styler_showcase_type',
                    'label' => esc_html__( 'Showcase Type?', 'styler' ),
                    'options' => array(
                        '' => 'Select a type',
                        'full' => esc_html__( 'Full', 'styler' ),
                        'carousel' => esc_html__( 'Carousel', 'styler' ),
                    ),
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_select(
                array(
                    'id' => 'styler_showcase_bg_type',
                    'label' => esc_html__( 'Background Color Type?', 'styler' ),
                    'options' => array(
                        '' => 'Select a type',
                        'dark' => esc_html__( 'Dark', 'styler' ),
                        'light' => esc_html__( 'Light', 'styler' ),
                        'custom' => esc_html__( 'Custom Color', 'styler' ),
                    ),
                    'desc_tip' => false,
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_showcase_custom_bgcolor',
                    'label' => esc_html__( 'Custom Background Color', 'styler' ),
                )
            );
            styler_wc_product_meta_color(
                array(
                    'id' => 'styler_showcase_custom_textcolor',
                    'label' => esc_html__( 'Custom Text Color', 'styler' ),
                )
            );
            echo '<div class="styler-panel-divider"></div>';
            echo '<h4 class="styler-panel-subheading">'.esc_html__('Product Summary Settings', 'styler').'</h4>';
            woocommerce_wp_select(
                array(
                    'id' => 'styler_product_size_guide',
                    'label' => esc_html__( 'Size Guide of product', 'styler' ),
                    'options' => styler_get_elementorCategories(),
                    'desc_tip' => true,
                    'description' => sprintf('%s <a href="'.esc_url(admin_url('edit.php?post_type=elementor_library')).'"><b>%s</b></a> %s <a href="'.esc_url(admin_url('edit-tags.php?taxonomy=elementor_library_category&post_type=elementor_library')).'"><b>%s</b></a> %s',
                        esc_html__( 'Please create your size guide with', 'styler' ),
                        esc_html__( 'elementor template', 'styler' ),
                        esc_html__( 'and assign it to a', 'styler' ),
                        esc_html__( 'category', 'styler' ),
                        esc_html__( 'the categories you create will be listed here.', 'styler' )
                    )
                )
            );
            echo '<div class="styler-panel-divider"></div>';
            echo '<h4 class="styler-panel-subheading">'.esc_html__('Product Video Settings', 'styler').'</h4>';
            woocommerce_wp_select(
                array(
                    'id' => 'styler_product_video_type',
                    'label' => esc_html__( 'Product Video Type?', 'styler' ),
                    'options' => array(
                        '' => 'Select a type',
                        'popup' => esc_html__( 'Popup', 'styler' ),
                        'gallery' => esc_html__( 'Gallery Item', 'styler' ),
                    ),
                    'desc_tip' => false
                )
            );
            woocommerce_wp_select(
                array(
                    'id' => 'styler_product_video_source_type',
                    'label' => esc_html__( 'Product Video Source Type?', 'styler' ),
                    'options' => array(
                        '' => 'Select a type',
                        'youtube' => esc_html__( 'Youtube', 'styler' ),
                        'vimeo' => esc_html__( 'Vimeo', 'styler' ),
                        'hosted' => esc_html__( 'Hosted', 'styler' ),
                    ),
                    'desc_tip' => false
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => 'styler_product_popup_video',
                    'label' => esc_html__( 'Popup / Hosted Video URL', 'styler' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Add your youtube,vimeo,hosted video URL here', 'styler' )
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => 'styler_product_iframe_video',
                    'label' => esc_html__( 'Youtube / Vimeo Video ID', 'styler' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Add your youtube video ID here for background autoplay video.', 'styler' ),
                    'rows' => 4
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => 'styler_product_video_preview',
                    'label' => esc_html__( 'Gallery Slider Video Preview Image URL', 'styler' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Add preview image for gallery slider.', 'styler' ),
                    'rows' => 4
                )
            );
            woocommerce_wp_select(
                array(
                    'id' => 'styler_product_video_on_shop',
                    'label' => esc_html__( 'Show this iframe video on shop archive?', 'styler' ),
                    'options' => array(
                        '' => 'Select an option',
                        'no' => esc_html__( 'No', 'styler' ),
                        'yes' => esc_html__( 'Yes', 'styler' ),
                    ),
                    'desc_tip' => false
                )
            );
            echo '<div class="styler-panel-divider"></div>';
            echo '<h4 class="styler-panel-subheading">'.esc_html__('Extra Tabs Settings', 'styler').'</h4>';
            woocommerce_wp_textarea_input(
                array(
                    'id' => 'styler_tabs_title',
                    'label' => esc_html__( 'Extra Tabs Title', 'styler' ),
                    'desc_tip' => true,
                    'description' => esc_html__( '!Important note: One title per line.', 'styler' ),
                    'rows' => 3
                )
            );
            woocommerce_wp_textarea_input(
                array(
                    'id' => 'styler_tabs_content',
                    'label' => esc_html__( 'Extra Tabs Content', 'styler' ),
                    'desc_tip' => true,
                    'description' => esc_html__( '!Important note: One content per line.Iframe,shortcode,HTML content allowed.', 'styler' ),
                    'rows' => 4
                )
            );
        echo '</div>';
    }
}

/**
*  Save Custom Field
*/
if ( ! function_exists( 'styler_save_product_custom_field' ) ) {
    add_action( 'woocommerce_process_product_meta', 'styler_save_product_custom_field' );
    function styler_save_product_custom_field( $_post_id )
    {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
            return;
        }
        $options = array(
            'styler_loop_product_slider',
            'styler_loop_product_slider_autoplay',
            'styler_loop_product_slider_speed',
            'styler_showcase_type',
            'styler_showcase_bg_type',
            'styler_showcase_custom_bgcolor',
            'styler_showcase_custom_textcolor',
            'styler_badge_color',
            'styler_custom_badge',
            'styler_custom_html_badge',
            'styler_product_discount',
            'styler_product_size_guide',
            'styler_product_hide_stock',
            'styler_countdown_date',
            'styler_countdown_text',
            'styler_product_video_type',
            'styler_product_video_source_type',
            'styler_product_popup_video',
            'styler_product_iframe_video',
            'styler_product_video_preview',
            'styler_product_video_on_shop',
            'styler_tabs_title',
            'styler_tabs_content',
            'styler_product_header_type',
            'styler_product_header_bgcolor',
            'styler_product_header_menuitem_color',
            'styler_product_header_menuitem_hvrcolor',
            'styler_product_header_svgicon_color',
            'styler_product_header_counter_bgcolor',
            'styler_product_header_counter_color',
            'styler_product_sticky_header_bgcolor',
            'styler_product_sticky_header_menuitem_color',
            'styler_product_sticky_header_menuitem_hvrcolor',
            'styler_product_sticky_header_svgicon_color',
            'styler_product_sticky_header_counter_bgcolor',
            'styler_product_sticky_header_counter_color'
        );
        foreach ( $options as $option ) {
            if ( isset( $_POST[$option] ) ) {
                update_post_meta( $_post_id, $option, $_POST[$option] );
            } else {
                delete_post_meta( $_post_id, $option );
            }
        }
    }
}


/**
* Remove Reviews tab from tabs
*/
if ( ! function_exists( 'styler_wc_remove_product_tabs' ) ) {
    add_filter( 'woocommerce_product_tabs', 'styler_wc_remove_product_tabs', 98 );
    function styler_wc_remove_product_tabs( $tabs )
    {
        $tabs_type = apply_filters( 'styler_product_tabs_type', styler_settings( 'product_tabs_type', 'tabs' ) );
        if ( 'accordion' == $tabs_type || '0' == styler_settings('single_shop_review_visibility', '1' ) ) {
            unset($tabs['reviews']);
        }
        if ( '1' == styler_settings('product_hide_reviews_tab', '0' ) ) {
            unset($tabs['reviews']);
        }

        $tabs['description']['callback'] = 'styler_wc_custom_description_tab_content'; // Custom description callback

        if ( '1' == styler_settings('product_hide_description_tab', '0' ) ) {
            unset($tabs['description']);
        }
        if ( '1' == styler_settings('product_hide_additional_tab', '0' ) ) {
            unset($tabs['additional_information']);
        }
        if ( '1' == styler_settings('product_hide_crqna_tab', '0' ) ) {
            unset($tabs['cr_qna']);
        }

        return $tabs;
    }
}


/**
 * Customize product data tabs
 */
if ( ! function_exists( 'styler_wc_custom_description_tab_content' ) ) {
    function styler_wc_custom_description_tab_content()
    {
        $desc_tab_title = '' != styler_settings( 'product_description_tab_title', '' ) ? styler_settings( 'product_description_tab_title', '' ) : esc_html__( 'Product Details', 'styler' );
        ?>
        <div class="product-desc-content">
            <?php if ( '0' != styler_settings( 'product_description_tab_title_visibility', '1' ) ) { ?>
                <h4 class="title"><?php echo apply_filters( 'styler_description_tab_title', $desc_tab_title ); ?></h4>
            <?php } ?>
            <?php the_content(); ?>
        </div>
        <?php
    }
}


/**
 * Move Reviews tab after product related
 */
if ( ! function_exists( 'styler_wc_move_product_reviews' ) ) {
    function styler_wc_move_product_reviews()
    {
        comments_template();
    }
}


/**
 * woocommerce_layered_nav_term_html WIDGET
 */
if ( !function_exists( 'styler_add_span_wc_layered_nav_term_html' ) ) {
    function styler_add_span_wc_layered_nav_term_html( $links )
    {
        $links = str_replace( '</a> (', '</a> <span class="widget-list-span">', $links );
        $links = str_replace( '</a> <span class="count">(', '</a> <span class="widget-list-span">', $links );
        $links = str_replace( ')', '</span>', $links );

        return $links;
    }
    add_filter( 'woocommerce_layered_nav_term_html', 'styler_add_span_wc_layered_nav_term_html' );
}


/**
* Add to cart handler.
*/
if ( !function_exists( 'styler_ajax_add_to_cart_handler' ) ) {
    if ( 'yes' == get_option('woocommerce_enable_ajax_add_to_cart') ) {
        function styler_ajax_add_to_cart_handler()
        {
            styler_cart_fragments();
        }
        add_action( 'wc_ajax_styler_ajax_add_to_cart', 'styler_ajax_add_to_cart_handler' );
        add_action( 'wc_ajax_nopriv_styler_ajax_add_to_cart', 'styler_ajax_add_to_cart_handler' );
    }

    function styler_remove_from_cart_handler()
    {
        $cart_item_key = wc_clean( isset( $_POST['cart_item_key'] ) ? wp_unslash( $_POST['cart_item_key'] ) : '' );

        if ( $cart_item_key && false !== WC()->cart->remove_cart_item( $cart_item_key ) ) {
            styler_cart_fragments('remove');
        } else {
            wp_send_json_error();
        }
    }
    add_action( 'wc_ajax_styler_remove_from_cart', 'styler_remove_from_cart_handler' );
    add_action( 'wc_ajax_nopriv_styler_remove_from_cart', 'styler_remove_from_cart_handler' );
}


/**
* ajax quick shop handler.
*/
if ( !function_exists( 'styler_ajax_quick_shop' ) ) {

    add_action( 'wp_ajax_styler_ajax_quick_shop', 'styler_ajax_quick_shop' );
    add_action( 'wp_ajax_nopriv_styler_ajax_quick_shop', 'styler_ajax_quick_shop' );

    function styler_ajax_quick_shop()
    {
        global $post, $product;
        $product_id = absint( $_GET['product_id'] );
        $product    = wc_get_product( $product_id );
		$checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );

        if ( !$product ) {
            return;
        }

        $post = get_post( $product_id );
        setup_postdata( $post );

        if ( post_password_required($post) ) {
            ?>
            <div class="styler-quickshop-wrapper single-content zoom-anim-dialog product-protected">
                <p><?php echo esc_html_e('This content is password protected.', 'styler'); ?></p>
                <a class="styler-btn-medium styler-btn styler-bg-black" href="<?php echo esc_url( get_permalink( $product_id ) ) ?>"><?php echo esc_html_e('Check Product', 'styler'); ?></a>
            </div>
            <?php
        } else {
            ?>
            <div id="product-<?php echo esc_attr( $product_id ); ?>" <?php wc_product_class( 'styler-quickshop-wrapper single-content zoom-anim-dialog', $product ); ?>>

                <?php if ( styler_settings('header_cart_before_buttons', '' ) ) { ?>
                    <div class="minicart-extra-text">
                        <?php echo styler_settings('header_cart_before_buttons', '' ); ?>
                    </div>
                <?php } ?>

                <div class="styler-quickshop-form-wrapper">
                    <h4 class="styler-product-title"><a class="product-link" href="<?php echo esc_url( get_permalink( $product_id ) ) ?>"><?php the_title();?></a></h4>
                    <?php woocommerce_template_single_add_to_cart( $product ); ?>
                    <div class="styler-quickshop-notices-wrapper"></div>
                </div>

                <div class="styler-quickshop-buttons-wrapper">
                    <div class="styler-flex">
                        <div class="styler-btn-medium styler-btn styler-bg-black open-cart-panel"><?php echo esc_html_e( 'View Cart', 'styler' ); ?></div>
                        <div class="styler-btn-medium styler-btn styler-bg-black open-checkout-panel">
                            <a href="<?php echo esc_url( $checkout_url ); ?>"><?php echo esc_html_e( 'Checkout', 'styler' ); ?></a>
                        </div>
                    </div>
                </div>

            </div>
            <?php
        }
        wp_reset_postdata();
        die();
    }
}

/**
* ajax quick shop handler.
*/
if ( ! function_exists( 'styler_quick_shop_ajax_add_to_cart' ) ) {

    add_action( 'wp_ajax_styler_quick_shop_ajax_add_to_cart', 'styler_quick_shop_ajax_add_to_cart' );
    add_action( 'wp_ajax_nopriv_styler_quick_shop_ajax_add_to_cart', 'styler_quick_shop_ajax_add_to_cart' );

    function styler_quick_shop_ajax_add_to_cart() {
        styler_cart_fragments();
        die();
    }
}


if ( ! function_exists( 'styler_quantity_button' ) ) {
    function styler_quantity_button() {
        if ( ( isset( $_GET['id'] ) && $_GET['id'] ) && ( isset( $_GET['qty'] ) ) ) {

            if ( $_GET['qty'] ) {
                WC()->cart->set_quantity( $_GET['id'], $_GET['qty'] );
            } else {
                WC()->cart->remove_cart_item( $_GET['id'] );
            }

            if ( esc_html( WC()->cart->get_cart_contents_count() ) == 0 ) {
                $fragments = array(
                    'msg' => esc_html__('Your order has been reset!','styler')
                );
            } else {
                $fragments = array(
                    'msg' => $_GET['qty']
                );
            }

            if ( $_GET['is_cart'] == 'yes' ) {
                ob_start();
                get_template_part('woocommerce/cart/cart');
                $cart = ob_get_clean();
                $fragments['cart'] = $cart;
                styler_cart_fragments('update',$fragments);
            } else {
                styler_cart_fragments('update',$fragments);
            }
        }
    }

    add_action( 'wp_ajax_styler_quantity_button', 'styler_quantity_button' );
    add_action( 'wp_ajax_nopriv_styler_quantity_button', 'styler_quantity_button' );
}

function styler_cart_fragments( $name = '',$fragments = null )
{
    ob_start();
    get_template_part('woocommerce/minicart/minicart');
    $minicart = ob_get_clean();

    $notices = wc_print_notices(true);
    $total   = WC()->cart->get_cart_subtotal();
    $count   = esc_html( WC()->cart->get_cart_contents_count() );
    $shipping = '';
    if ( '1' == styler_settings( 'free_shipping_progressbar_visibility', '0' ) ) {
        $shipping = styler_free_shipping_goal_content();
    }
    $data = array(
        'fragments' => array(
            'notices'  => $notices,
            'minicart' => $minicart,
            'total'    => $total,
            'count'    => $count,
            'shipping' => $shipping
        ),
        'cart_hash' => WC()->cart->get_cart_hash()
    );

    if ( $name == 'clear' && !empty( $fragments ) ) {
        $data['fragments']['clear'] = $fragments;
    }

    if ( $name == 'update' && !empty( $fragments ) ) {
        $data['fragments']['update'] = $fragments;
    }

    wp_send_json( $data );
}

if ( ! function_exists( 'styler_free_shipping_goal_content' ) ) {
    function styler_free_shipping_goal_content()
    {
        $amount = intval(styler_settings( 'free_shipping_progressbar_amount', 500 ));
        $amount = round( $amount, wc_get_price_decimals() );

        if ( !( $amount > 0 ) || '1' != styler_settings( 'free_shipping_progressbar_visibility', 1 ) ) {
            return;
        }

        $message_initial = styler_settings( 'free_shipping_progressbar_message_initial' );
        $message_success = styler_settings( 'free_shipping_progressbar_message_success' );

        $total     = WC()->cart->get_displayed_subtotal();
        $remainder = ( $amount - $total );
        $value     = $total <= $amount ? ( $total / $amount ) * 100 : 100;

        if ( $total == 0 ) {
            $value = 0;
        }

        if ( $total >= $amount ) {
            if ( $message_success ) {
                $message = sprintf('%s', $message_success );
            } else {
                $message = sprintf('%s <strong>%s</strong>',
                esc_html__('Congrats! You are eligible for', 'styler'),
                esc_html__('more to enjoy FREE Shipping', 'styler'));
            }
        } else {
            if ( $message_initial ) {
                $message = sprintf('%s', str_replace( '[remainder]', wc_price( $remainder ), $message_initial ) );
            } else {
                $message = sprintf('%s %s <strong>%s</strong>',
                esc_html__('Buy', 'styler'),
                wc_price( $remainder ),
                esc_html__('more to enjoy FREE Shipping', 'styler'));
            }
        }

        $shipping = array(
            'value'   => $value,
            'message' => $message
        );

        return $shipping;
    }
}


/**
* Add category banner if shortcode exists
*/
if ( !function_exists( 'styler_print_category_banner' ) ) {
    add_action( 'styler_shop_before_loop', 'styler_print_category_banner', 10 );
    function styler_print_category_banner()
    {
        $cat_template = styler_settings('shop_category_pages_before_loop_templates', null );
        $tag_template = styler_settings('shop_tag_pages_before_loop_templates', null );
        $banner       = get_term_meta( get_queried_object_id(), 'styler_wc_cat_banner', true );
        $layouts      = isset( $_GET['shop_layouts'] ) && ( 'left-sidebar' == $_GET['shop_layouts'] || 'right-sidebar' == $_GET['shop_layouts'] ) ? true : false;

        if ( ( $cat_template || $tag_template || $banner ) && ( is_product_category() || is_product_tag() ) ) {
            if ( $banner && is_product_category() ) {
                printf( '<div class="shop-cat-banner styler-before-loop">%s</div>', do_shortcode( $banner ) );
            } elseif ( $cat_template && is_product_category() ) {
                echo styler_print_elementor_templates( 'shop_category_pages_before_loop_templates', 'shop-before-loop-template-wrapper', true );
            } elseif ( $tag_template && is_product_tag() ) {
                echo styler_print_elementor_templates( 'shop_tag_pages_before_loop_templates', 'shop-before-loop-template-wrapper', true );
            } else {
                printf( '<div class="shop-cat-banner styler-before-loop">%s</div>', do_shortcode( $banner ) );
            }

        } else {

            if ( 'left-sidebar' == styler_settings('shop_layout') || 'right-sidebar' == styler_settings('shop_layout') || $layouts ) {

                echo styler_print_elementor_templates( 'shop_before_loop_templates', 'shop-cat-banner-template-wrapper', true );
            }
        }
    }
}


add_action('product_cat_add_form_fields', 'styler_wc_taxonomy_add_new_meta_field', 15, 1);
//Product Cat Create page
function styler_wc_taxonomy_add_new_meta_field() {
    woocommerce_wp_textarea_input(
        array(
            'id' => 'styler_wc_cat_banner',
            'label' => esc_html__( 'Styler Category Banner ', 'styler' ),
            'description' => esc_html__( 'If you want to show a different banner on the archive category page for this category, use this field.Iframe,shortcode,HTML content allowed.', 'styler' ),
            'rows' => 4
        )
    );
    ?>
	<div class="form-field styler_term-hero-bgimage-wrap">
		<label><?php esc_html_e( 'Styler Shop Category Page Hero Background Image', 'styler' ); ?></label>
		<div id="styler_product_cat_hero_bgimage" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
		<div style="line-height: 60px;">
			<input type="hidden" id="styler_product_cat_hero_bgimage_id" name="styler_product_cat_hero_bgimage_id" />
			<button type="button" class="styler_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'styler' ); ?></button>
			<button type="button" class="styler_remove_image_button button"><?php esc_html_e( 'Remove image', 'styler' ); ?></button>
		</div>
		<div class="clear"></div>
		<span class="description"><?php esc_html_e( 'If you want to show a different background image on the shop archive category page for this category, upload your image from here.', 'styler'); ?></span>
		<script type="text/javascript">

			// Only show the "remove image" button when needed
			if ( ! jQuery( '#styler_product_cat_hero_bgimage_id' ).val() ) {
				jQuery( '.styler_term-hero-bgimage-wrap .styler_remove_image_button' ).hide();
			}

			// Uploading files
			var styler_cat_hero_file_frame;

			jQuery( document ).on( 'click', '.styler_term-hero-bgimage-wrap .styler_upload_image_button', function( event ) {

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( styler_cat_hero_file_frame ) {
					styler_cat_hero_file_frame.open();
					return;
				}

				// Create the media frame.
				styler_cat_hero_file_frame = wp.media.frames.downloadable_file = wp.media({
					title: '<?php esc_html_e( 'Choose an image', 'styler' ); ?>',
					button: {
						text: '<?php esc_html_e( 'Use image', 'styler' ); ?>'
					},
					multiple: false
				});

				// When an image is selected, run a callback.
				styler_cat_hero_file_frame.on( 'select', function() {
					var attachment           = styler_cat_hero_file_frame.state().get( 'selection' ).first().toJSON();
					var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

					jQuery( '#styler_product_cat_hero_bgimage_id' ).val( attachment.id );
					jQuery( '#styler_product_cat_hero_bgimage' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
					jQuery( '.styler_term-hero-bgimage-wrap .styler_remove_image_button' ).show();
				});

				// Finally, open the modal.
				styler_cat_hero_file_frame.open();
			});

			jQuery( document ).on( 'click', '.styler_term-hero-bgimage-wrap .styler_remove_image_button', function() {
				jQuery( '#styler_product_cat_hero_bgimage' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
				jQuery( '#styler_product_cat_hero_bgimage_id' ).val( '' );
				jQuery( '.styler_term-hero-bgimage-wrap .styler_remove_image_button' ).hide();
				return false;
			});

			jQuery( document ).ajaxComplete( function( event, request, options ) {
				if ( request && 4 === request.readyState && 200 === request.status
					&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

					var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
					if ( ! res || res.errors ) {
						return;
					}
					// Clear Thumbnail fields on submit
					jQuery( '#styler_product_cat_hero_bgimage' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
					jQuery( '#styler_product_cat_hero_bgimage_id' ).val( '' );
					jQuery( '.styler_term-hero-bgimage-wrap .styler_remove_image_button' ).hide();
					return;
				}
			} );

		</script>
	</div>
	<div class="clear"></div>
	<?php
}

add_action('product_cat_edit_form_fields', 'styler_wc_taxonomy_edit_meta_field', 15, 1);
//Product Cat Edit page
function styler_wc_taxonomy_edit_meta_field($term) {

    //getting term ID
    $term_id = $term->term_id;

    // retrieve the existing value(s) for this meta field.
    $styler_wc_cat_banner = get_term_meta($term_id, 'styler_wc_cat_banner', true);
	$thumbnail_id = absint( get_term_meta( $term_id, 'styler_product_cat_hero_bgimage_id', true ) );

	if ( $thumbnail_id ) {
		$image = wp_get_attachment_thumb_url( $thumbnail_id );
	} else {
		$image = wc_placeholder_img_src();
	}
    ?>
    <tr class="form-field term-styler-banner-wrap">
        <th scope="row" valign="top"><label for="styler_wc_cat_banner"><?php esc_html_e('Styler Banner', 'styler'); ?></label></th>
        <td>
            <textarea name="styler_wc_cat_banner" id="styler_wc_cat_banner" rows="5" cols="50" class="large-text"><?php echo esc_html($styler_wc_cat_banner) ? $styler_wc_cat_banner : ''; ?></textarea>
            <p class="description"><?php esc_html_e('If you want to show a different banner on the archive category page for this category, use this field.Iframe,shortcode,HTML content allowed.', 'styler'); ?></p>
        </td>
    </tr>

	<tr class="form-field styler_term-hero_bgimage-wrap">
		<th scope="row" valign="top"><label><?php esc_html_e( 'Styler Shop Category Page Hero Background Image', 'styler' ); ?></label></th>
		<td>
			<div id="styler_product_cat_hero_bgimage" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
			<div style="line-height: 60px;">
				<input type="hidden" id="styler_product_cat_hero_bgimage_id" name="styler_product_cat_hero_bgimage_id" value="<?php echo esc_attr( $thumbnail_id ); ?>" />
				<button type="button" class="styler_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'styler' ); ?></button>
				<button type="button" class="styler_remove_image_button button"><?php esc_html_e( 'Remove image', 'styler' ); ?></button>
			</div>
			<div class="clear"></div>
			<span class="description"><?php esc_html_e( 'If you want to show a different background image on the shop archive category page for this category, upload your image from here.', 'styler'); ?></span>
			<script type="text/javascript">

				// Only show the "remove image" button when needed
				if ( '0' === jQuery( '#styler_product_cat_hero_bgimage_id' ).val() ) {
					jQuery( '.styler_term-hero_bgimage-wrap .styler_remove_image_button' ).hide();
				}

				// Uploading files
				var styler_cat_hero_file_frame;

				jQuery( document ).on( 'click', '.styler_term-hero_bgimage-wrap .styler_upload_image_button', function( event ) {

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( styler_cat_hero_file_frame ) {
						styler_cat_hero_file_frame.open();
						return;
					}

					// Create the media frame.
					styler_cat_hero_file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php esc_html_e( 'Choose an image', 'styler' ); ?>',
						button: {
							text: '<?php esc_html_e( 'Use image', 'styler' ); ?>'
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					styler_cat_hero_file_frame.on( 'select', function() {
						var attachment           = styler_cat_hero_file_frame.state().get( 'selection' ).first().toJSON();
						var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

						jQuery( '#styler_product_cat_hero_bgimage_id' ).val( attachment.id );
						jQuery( '#styler_product_cat_hero_bgimage' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
						jQuery( '.styler_term-hero_bgimage-wrap .styler_remove_image_button' ).show();
					});

					// Finally, open the modal.
					styler_cat_hero_file_frame.open();
				});

				jQuery( document ).on( 'click', '.styler_term-hero_bgimage-wrap .styler_remove_image_button', function() {
					jQuery( '#styler_product_cat_hero_bgimage' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
					jQuery( '#styler_product_cat_hero_bgimage_id' ).val( '' );
					jQuery( '.styler_term-hero_bgimage-wrap .styler_remove_image_button' ).hide();
					return false;
				});

			</script>
			<div class="clear"></div>
		</td>
	</tr>
    <?php
}

add_action('edited_product_cat', 'styler_wc_save_taxonomy_custom_meta', 15, 1);
add_action('create_product_cat', 'styler_wc_save_taxonomy_custom_meta', 15, 1);
// Save extra taxonomy fields callback function.
function styler_wc_save_taxonomy_custom_meta( $term_id ) {

    $styler_wc_cat_banner = filter_input(INPUT_POST, 'styler_wc_cat_banner');
    $styler_product_cat_hero_bgimage_id = filter_input(INPUT_POST, 'styler_product_cat_hero_bgimage_id');
    update_term_meta($term_id, 'styler_wc_cat_banner', $styler_wc_cat_banner);
    update_term_meta($term_id, 'styler_product_cat_hero_bgimage_id', $styler_product_cat_hero_bgimage_id);

}

//Displaying Additional Columns
add_filter( 'manage_edit-product_cat_columns', 'styler_wc_customFieldsListTitle' ); //Register Function

function styler_wc_customFieldsListTitle( $columns ) {
    $columns['styler_cat_banner'] = esc_html__( 'Banner', 'styler' );
    return $columns;
}

add_action( 'manage_product_cat_custom_column', 'styler_wc_customFieldsListDisplay' , 10, 3); //Populating the Columns
function styler_wc_customFieldsListDisplay( $columns, $column, $id ) {
    if ( 'styler_cat_banner' == $column ) {
        $columns = get_term_meta($id, 'styler_wc_cat_banner', true);
        $columns = $columns ? '<span class="wc-banner"></span>' : '';
    }
    return $columns;
}

if ( ! function_exists( 'styler_wc_per_page_select' ) ) {
    function styler_wc_per_page_select()
    {
        if ( ! wc_get_loop_prop( 'is_paginated' ) ) {
            return;
        }

        $numbers = styler_settings( 'per_page_select_options' );
        $per_page_opt = ( ! empty( $numbers ) ) ? explode( ',', $numbers ) : array( 9, 12, 24, 36 );

        ?>
        <div class="styler-filter-per-page styler-shop-filter-item">
            <ul class="styler-filter-action">
                <li class="styler-per-page-title"><?php esc_html_e( 'Show', 'styler' ); ?></li>
                <?php foreach ( $per_page_opt as $key => $value ) {

                    $link = add_query_arg( 'per_page', $value );

                    $classes = isset( $_GET['per_page'] ) && $_GET['per_page'] === $value ? ' active' : '';
                    $val = $value == -1 ? esc_html__( 'All', 'styler' ) : $value;
                    ?>
                    <li class="styler-per-page-item<?php echo esc_attr( $classes ); ?>">
                        <a rel="nofollow noopener" href="<?php echo esc_url( $link ); ?>"><?php esc_html( printf( '%s', $val ) ); ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <?php
    }
}

if ( ! function_exists( 'styler_wc_column_select' ) ) {
    function styler_wc_column_select()
    {
        if ( ! wc_get_loop_prop( 'is_paginated' ) ) {
            return;
        }
        if ( !styler_get_shop_column() && 'list' == styler_settings( 'shop_product_type', '1' ) ) {
            $col = 1;
        } elseif ( intval(styler_get_shop_column()) > 1 ) {
            $col = intval(styler_get_shop_column());
        } else {
            $col = isset( $_GET['column'] ) && $_GET['column'] ? intval( $_GET['column'] ) : intval( styler_settings( 'shop_colxl' ) );
        }

        $active = $hide = '';
        $cols = array( 1, 2, 3, 4, 5 );

        ?>
        <div class="styler-filter-column-select styler-shop-filter-item">
            <ul class="styler-filter-action styler-filter-columns styler-mini-icon">
                <?php
                foreach ( $cols as $key => $value ) {

                    if ( ( $col < 6 ) && ( $col === $value ) ) {
                        $active = ' active';
                    }
                    if ( $value === 3 ) {
                        $hide = ' d-none d-sm-flex';
                    }
                    if ( $value === 4 ) {
                        $hide = ' d-none d-lg-flex';
                    }
                    if ( $value === 5 ) {
                        $hide = ' d-none d-xl-flex';
                    }
                    ?>
                    <li class="<?php echo esc_attr( 'val-'.$value.$active.$hide ); ?>">
                        <a href="<?php echo esc_url( add_query_arg( 'column', $value ) ); ?>" rel="nofollow noopener"><?php echo styler_svg_lists('column-'.$value, 'styler-svg-icon');?></a>
                    </li>
                    <?php
                    $active = '';
                }
                ?>
            </ul>
        </div>
        <?php
    }
}


if ( !function_exists( 'styler_wc_category_search_form' ) ) {
    function styler_wc_category_search_form()
    {
        $terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => 0 ) );
        ?>
        <div class="header-search-wrap">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/'  ) ) ?>">
                <input  type="text" name="s"
                value="<?php get_search_query() ?>"
                placeholder="<?php echo esc_attr_e( 'Search for your item\'s type.....', 'styler' ) ?>">
                <input type="hidden" name="post_type" value="product" />
                <select class="custom-select" name="product_cat">
                    <option value="" selected><?php echo esc_html_e( 'All Category', 'styler' ) ?></option>
                    <?php
                    foreach ( $terms as $term ) {
                        if ( $term->count >= 1 ) {
                            ?>
                            <option value="<?php echo esc_attr( $term->slug ) ?>"><?php echo esc_html( $term->name ) ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <button class="btn-submit" type="submit"><?php echo styler_svg_lists( 'search' ); ?></button>
                <?php do_action( 'wpml_add_language_form_field' ); ?>
            </form>
        </div>
        <?php
    }
}

if ( !function_exists( 'styler_wc_format_sale_price' ) ) {
    /**
     * Format a sale price for display.
     *
     * @since  3.0.0
     * @param  string $regular_price Regular price.
     * @param  string $sale_price    Sale price.
     * @return string
     */
    add_filter( 'woocommerce_format_sale_price', 'styler_wc_format_sale_price', 10, 3 );
    function styler_wc_format_sale_price( $price, $regular_price, $sale_price ) {
        $price = '<span class="styler-primary-color del"><span>' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</span> – </span> <span class="styler-primary-color ins">' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . '</span>';
        return $price;
    }
}

if ( !function_exists( 'styler_shop_main_loop' ) ) {
    add_action('styler_shop_main_loop','styler_shop_main_loop', 10 );
    function styler_shop_main_loop()
    {
        $pagination = apply_filters('styler_shop_pagination_type', styler_settings('shop_paginate_type') );
        $loop       = woocommerce_product_loop();
        echo '<div class="styler-products-wrapper">';
            do_action( 'styler_shop_choosen_filters' );

            if ( $pagination == 'loadmore' || $pagination == 'infinite' ) {
                echo '<div class="shop-data-filters" data-shop-filters=\''.styler_wc_filters_for_ajax().'\'></div>';
            }

            woocommerce_product_loop_start();

            if ( $loop && wc_get_loop_prop( 'total' ) ) {
                while ( have_posts() ) {
                    the_post();

                    /**
                    * Hook: woocommerce_shop_loop.
                    */
                    do_action( 'woocommerce_shop_loop' );

                    wc_get_template_part( 'content', 'product' );
                }
            }

            woocommerce_product_loop_end();

            if ( $loop ) {
                /**
                * Hook: styler_shop_pagination.
                *
                * @hooked styler_shop_pagination
                */
                do_action( 'styler_shop_pagination' );
            } else {
                /**
                * Hook: woocommerce_no_products_found.
                *
                * @hooked wc_no_products_found - 10
                */
                do_action( 'woocommerce_no_products_found' );
            }
        echo '</div>';
    }
}

if ( !function_exists( 'styler_shop_choosen_filters_row' ) ) {
    add_action('styler_shop_before_loop','styler_shop_choosen_filters_row', 20 );
    function styler_shop_choosen_filters_row()
    {
        $layout  = apply_filters('styler_shop_layout', styler_settings( 'shop_layout', 'left-sidebar' ) );
        $filters = styler_settings( 'choosen_filters_before_loop', '1' );

        if ( ('left-sidebar' == $layout || 'right-sidebar' == $layout ) && is_active_sidebar( 'shop-page-sidebar' ) && '1' == $filters ) {
            ?>
            <div class="styler-choosen-filters-row row styler-hidden-on-mobile">
                <div class="col-12"><?php do_action( 'styler_choosen_filters' );?></div>
            </div>
            <?php
        }
    }
}

if ( !function_exists( 'styler_shop_sidebar' ) ) {
    add_action('styler_shop_sidebar','styler_shop_sidebar', 10 );
    function styler_shop_sidebar()
    {
        $layout = apply_filters('styler_shop_layout', styler_settings( 'shop_layout', 'left-sidebar' ) );
        if ( ( 'left-sidebar' == $layout || 'right-sidebar' == $layout ) && is_active_sidebar( 'shop-page-sidebar' ) ) {
            ?>
            <div id="nt-sidebar" class="nt-sidebar default-sidebar col-lg-3">
                <div class="styler-panel-close-button styler-close-sidebar"></div>
                <div class="nt-sidebar-inner-wrapper">
                    <?php do_action( 'styler_choosen_filters' );?>
                    <div class="nt-sidebar-inner styler-scrollbar">
                        <?php dynamic_sidebar( 'shop-page-sidebar' ); ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if ( !function_exists( 'styler_shop_top_hidden_sidebar' ) ) {
    add_action('styler_shop_before_loop','styler_shop_top_hidden_sidebar', 20 );
    function styler_shop_top_hidden_sidebar()
    {
        $layout = apply_filters('styler_shop_layout', styler_settings( 'shop_layout', 'left-sidebar' ) );
        $column = styler_settings( 'shop_hidden_sidebar_column', '3' );
        if ( 'top-sidebar' == $layout && is_active_sidebar( 'shop-page-sidebar' ) ) {
            ?>
            <div id="nt-sidebar" class="nt-sidebar styler-shop-hidden-top-sidebar d-none" data-column="row row-cols-<?php echo esc_attr( $column ); ?>">
                <div class="styler-panel-close-button styler-close-sidebar"></div>
                <div class="nt-sidebar-inner-wrapper">
                    <?php do_action( 'styler_choosen_filters' );?>
                    <div class="nt-sidebar-inner row row-cols-<?php echo esc_attr( $column ); ?>">
                        <?php dynamic_sidebar( 'shop-page-sidebar' ); ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if ( !function_exists( 'styler_shop_sidebar_fixed' ) ) {
    add_action('styler_after_shop_page','styler_shop_sidebar_fixed', 20 );
    function styler_shop_sidebar_fixed()
    {
        $layout = apply_filters('styler_shop_layout', styler_settings( 'shop_layout', 'left-sidebar' ) );
        if ( 'fixed-sidebar' == $layout && is_active_sidebar( 'shop-page-sidebar' ) ) {
            ?>
            <div id="nt-sidebar" class="nt-sidebar styler-shop-fixed-sidebar">
                <div class="styler-panel-close-button styler-close-sidebar"></div>
                <div class="nt-sidebar-inner-wrapper">
                    <?php do_action( 'styler_choosen_filters' );?>
                    <div class="nt-sidebar-inner styler-scrollbar">
                        <?php dynamic_sidebar( 'shop-page-sidebar' ); ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if ( !function_exists( 'styler_shop_loop_notices' ) ) {
    add_action('styler_before_wp_footer','styler_shop_loop_notices', 15 );
    function styler_shop_loop_notices()
    {
        if ( '1' == styler_settings( 'shop_cart_popup_notices_visibility', '1' ) ) {
            ?>
            <div class="styler-shop-popup-notices"></div>
            <?php
        }
    }
}

if ( !function_exists( 'shop_loop_filters_layouts' ) ) {
    add_action('styler_shop_before_loop','shop_loop_filters_layouts', 15 );
    function shop_loop_filters_layouts()
    {
        $defaults = [
            'left'=> [
                'result-count' => ''
            ],
            'right'=> [
                'sidebar-filter' => '',
                'per-page' => '',
                'ordering' => '',
                'column-select' => ''
            ]
        ];
        $layouts = apply_filters( 'styler_get_filters_layouts', styler_settings( 'shop_loop_filters_layouts', $defaults ) );
        $page_layout = apply_filters('styler_shop_layout', styler_settings( 'shop_layout', 'left-sidebar' ) );
        if ( $layouts ) {

            unset( $layouts['left']['placebo'] );
            unset( $layouts['right']['placebo'] );

            echo '<div class="styler-inline-two-block styler-before-loop styler-shop-filter-top-area">';

                if ( !empty( $layouts['left'] ) ) {
                    echo '<div class="styler-block-left">';
                        foreach ( $layouts['left'] as $key => $value ) {
                            switch ( $key ) {
                                case 'sidebar-filter':
                                if ( $page_layout == 'top-sidebar' && is_active_sidebar( 'shop-page-sidebar' ) ) {
                                    echo '<div class="styler-toggle-hidden-sidebar"><span>'.esc_html__( 'Filter', 'styler' ).'</span> '.styler_svg_lists( 'filter', 'styler-svg-icon' ).'<div class="styler-filter-close"></div></div>';
                                }
                                if ( $page_layout != 'no-sidebar' && is_active_sidebar( 'shop-page-sidebar' ) ) {
                                    echo '<div class="styler-open-fixed-sidebar"><span>'.esc_html__( 'Filter', 'styler' ).'</span> '.styler_svg_lists( 'filter', 'styler-svg-icon' ).'</div>';
                                }
                                break;

                                case 'search':
                                echo '<div class="top-action-btn" data-name="search-popup">'.styler_svg_lists( 'search', 'styler-svg-icon' ).'</div>';
                                break;

                                case 'result-count':
                                echo '<div class="styler-woo-result-count">';woocommerce_result_count();echo '</div>';
                                break;

                                case 'breadcrumbs':
                                if ( '0' != styler_settings( 'breadcrumbs_visibility', '1' ) ) {
                                    echo '<div class="styler-woo-breadcrumb">'.woocommerce_breadcrumb().'</div>';
                                }
                                break;

                                case 'per-page':
                                echo '<div class="styler-shop-filter-area styler-filter-per-page-area">';
                                    styler_wc_per_page_select();
                                echo '</div>';
                                break;

                                case 'column-select':
                                echo '<div class="styler-shop-filter-area styler-filter-column-select-area">';
                                    styler_wc_column_select();
                                echo '</div>';
                                break;

                                case 'ordering':
                                if ( woocommerce_product_loop() ) {
                                    echo '<div class="styler-shop-filter-area styler-filter-ordering-area">';
                                        woocommerce_catalog_ordering();
                                    echo '</div>';
                                }
                                break;
                            }
                        }
                    echo '</div>';
                }

                if ( !empty( $layouts['right'] ) ) {
                    echo '<div class="styler-block-right">';
                        foreach ( $layouts['right'] as $key => $value ) {
                            switch ( $key ) {

                                case 'sidebar-filter':
                                if ( $page_layout == 'top-sidebar' && is_active_sidebar( 'shop-page-sidebar' ) ) {
                                    echo '<div class="styler-toggle-hidden-sidebar"><span>'.esc_html__( 'Filter', 'styler' ).'</span> '.styler_svg_lists( 'filter', 'styler-svg-icon' ).'<div class="styler-filter-close"></div></div>';
                                }
                                if ( $page_layout != 'no-sidebar' && is_active_sidebar( 'shop-page-sidebar' ) ) {
                                    echo '<div class="styler-open-fixed-sidebar"><span>'.esc_html__( 'Filter', 'styler' ).'</span> '.styler_svg_lists( 'filter', 'styler-svg-icon' ).'</div>';
                                }
                                break;

                                case 'search':
                                echo '<div class="top-action-btn" data-name="search-popup">'.styler_svg_lists( 'search', 'styler-svg-icon' ).'</div>';
                                break;

                                case 'result-count':
                                echo '<div class="styler-woo-result-count">';woocommerce_result_count();echo '</div>';
                                break;

                                case 'breadcrumbs':
                                if ( '0' != styler_settings( 'breadcrumbs_visibility', '1' ) ) {
                                    echo '<div class="styler-woo-breadcrumb">'.woocommerce_breadcrumb().'</div>';
                                }
                                break;

                                case 'per-page':
                                echo '<div class="styler-shop-filter-area styler-filter-per-page-area">';
                                    styler_wc_per_page_select();
                                echo '</div>';
                                break;

                                case 'column-select':
                                echo '<div class="styler-shop-filter-area styler-filter-column-select-area">';
                                    styler_wc_column_select();
                                echo '</div>';
                                break;

                                case 'ordering':
                                if ( woocommerce_product_loop() ) {
                                    echo '<div class="styler-shop-filter-area styler-filter-ordering-area">';
                                        woocommerce_catalog_ordering();
                                    echo '</div>';
                                }
                                break;
                            }
                        }
                    echo '</div>';
                }
            echo '</div>';
        }
    }
}

if ( !function_exists( 'styler_loop_product_buttons_layouts' ) ) {
    function styler_loop_product_buttons_layouts()
    {
        if ( '1' == styler_settings( 'woo_catalog_mode', '0' ) ) {
            echo styler_quickview_button();
        } else {
            $defaults = [
                'show'=> [
                    'quickview'=>'',
                    'compare'=>'',
                    'wishlist'=>''
                ]
            ];
            $type    = apply_filters( 'styler_loop_product_type', styler_settings( 'shop_product_type', '3' ) );
            $layouts = 'custom' == $type ? styler_settings( 'shop_loop_product_buttons_layouts' ) : $defaults;
            $layouts = apply_filters( 'styler_loop_product_buttons_layouts', $layouts );
            echo styler_add_to_cart_button();
            if ( $layouts ) {
                unset( $layouts['show']['placebo'] );
                foreach ( $layouts['show'] as $key => $value ) {

                    switch ( $key ) {

                        case 'quickview':
                        echo styler_quickview_button();
                        break;

                        case 'compare':
                        echo styler_compare_button();
                        break;

                        case 'wishlist':
                        echo styler_wishlist_button();
                        break;
                    }
                }
            } else {

                if ( '1' == styler_get_shop_column() ) {
                    echo styler_add_to_cart_button();
                    echo styler_quickview_button();
                    echo styler_compare_button();
                    echo styler_wishlist_button();
                }
            }
        }
    }
}


if ( !function_exists( 'styler_loop_product_prelayouts' ) ) {
    function styler_loop_product_prelayouts($types='')
    {
        $type = $types ? $types : apply_filters( 'styler_loop_product_type', styler_settings( 'shop_product_type', '3' ) );
        $type = '1' == styler_get_shop_column() ? 7 : $type;

        switch ( $type ) {
            case '1':
            $layouts = [
                'show'=> [
                    'thumb'=>'',
                    'price-rating'=>'',
                    'title-cart-hover'=>'',
                    'buttons-hover'=>'',
                    'sale-discount'=>'',
                    'stock'=>''
                ]
            ];
            break;
            case '2':
            $layouts = [
                'show'=> [
                    'thumb'=>'',
                    'title-cart-hover'=>'',
                    'price-buttons'=>'',
                    //'buttons-hover'=>'',
                    'sale-discount'=>'',
                    'stock'=>''
                ]
            ];
            break;
            case '3':
            $layouts = [
                'show'=> [
                    'thumb-overlay'=>'',
                    'title-cart-hover'=>'',
                    'price-rating'=>'',
                    'buttons-hover'=>'',
                    'sale-discount'=>'',
                    'stock'=>''
                ]
            ];
            break;
            case '4':
            $layouts = [
                'show'=> [
                    'thumb-overlay'=>'',
                    'title-buttons-hover'=>'',
                    'price-rating'=>'',
                    'sale-discount'=>'',
                    'stock'=>''
                ]
            ];
            break;
            case '5':
            $layouts = [
                'show'=> [
                    'gallery'=>'',
                    'title-cart-hover'=>'',
                    'price-buttons'=>'',
                    'sale-discount'=>'',
                    'stock'=>''
                ]
            ];
            break;
            case '6':
            $layouts = [
                'show'=> [
                    'thumb-overlay'=>'',
                    'price-rating'=>'',
                    'title-buttons-hover'=>'',
                    'sale-discount'=>'',
                    'stock'=>''
                ]
            ];
            break;
            case '7':
            $layouts = [
                'show'=> [
                    'thumb-overlay'=>'',
                    'buttons-hover'=>'',
                    'title'=>'',
                    'rating'=>'',
                    'desc'=>'',
                    //'swatches'=>'',
                    'price-cart'=>'',
                    'sale-discount'=>'',
                    'stock'=>''
                ]
            ];
            break;
        }
        return $layouts;
    }
}

if ( !function_exists( 'styler_loop_product_layout_manager' ) ) {
    function styler_loop_product_layout_manager($types='',$column='')
    {
        global $product;
        $type          = $types ? $types : styler_settings( 'shop_product_type', '3' );
        $type          = apply_filters( 'styler_loop_product_type', $type );
        $layouts       = $type != 'custom' ? styler_loop_product_prelayouts($types) : apply_filters( 'styler_shop_loop_product_layouts', styler_settings( 'shop_loop_product_layouts' ) );
        $list_type     = '1' == styler_get_shop_column() || '1' == $column ? ' row row-cols-1 row-cols-sm-1 row-cols-xl-2' : '';
        $list_type_col = '1' == styler_get_shop_column() || '1' == $column ? ' col' : '';
        $show_video    = get_post_meta( $product->get_id(), 'styler_product_video_on_shop', true );
        $show_video    = 'yes' ==$show_video ? ' has-iframe-video' : '';
        $catalog_mode  = styler_settings( 'woo_catalog_mode', '0' );
        $is_catalog_mode  = '1' == $catalog_mode ? ' woo-catalog-mode-enabled' : '';

        if ( $layouts ) {
            unset( $layouts['show']['placebo'] );
            if ( '1' == styler_get_shop_column() || '1' == $column ) {
                echo '<div class="styler-loop-item-list-wrapper">';
            }
            echo '<div class="woocommerce styler-product'.$list_type.$is_catalog_mode.'" data-id="'.$product->get_id().'">';
                echo '<span class="loading-wrapper"><span class="ajax-loading"></span></span>';
                foreach ( $layouts['show'] as $key => $value ) {

                    switch ( $key ) {

                        case 'thumb':
                        echo '<div class="styler-thumb-wrapper'.$list_type_col.$show_video.'">';
                            styler_loop_product_thumb($column);
                            if ( array_key_exists( 'stock', $layouts['show'] ) ) {
                                styler_loop_product_nostock();
                            }
                            if ( array_key_exists( 'sale-discount', $layouts['show'] ) ) {
                                echo '<div class="styler-product-labels">';
                                    styler_product_badge();
                                    styler_product_discount();
                                echo '</div>';
                            }
                            if ( array_key_exists( 'buttons-hover', $layouts['show'] ) ) {
                                echo '<div class="styler-loop-product-buttons-hover">';
                                    styler_loop_product_buttons_layouts();
                                echo '</div>';
                            }
                            if ( array_key_exists( 'swatches-hover', $layouts['show'] ) ) {
                                echo '<div class="styler-swatches-hover">'.do_shortcode( '[styler_swatches]' ).'</div>';
                            }
                        echo '</div>';
                        if ( array_key_exists( 'buttons-hover', $layouts['show'] ) ) {
                            echo '<div class="styler-loop-product-buttons-mobile styler-mini-icon">';
                                styler_loop_product_buttons_layouts();
                            echo '</div>';
                        }
                        if ( '1' == styler_get_shop_column() || '1' == $column ) {
                            echo '<div class="styler-loop-item-content-wrapper'.$list_type_col.'">';
                        }
                        break;

                        case 'thumb-overlay':
                        echo '<div class="styler-thumb-wrapper'.$list_type_col.$show_video.'">';
                            styler_loop_product_thumb_overlay($column);
                            if ( array_key_exists( 'stock', $layouts['show'] ) ) {
                                styler_loop_product_nostock();
                            }
                            if ( array_key_exists( 'sale-discount', $layouts['show'] ) ) {
                                echo '<div class="styler-product-labels">';
                                    styler_product_badge();
                                    styler_product_discount();
                                echo '</div>';
                            }
                            if ( array_key_exists( 'buttons-hover', $layouts['show'] ) ) {
                                echo '<div class="styler-loop-product-buttons-hover">';
                                    styler_loop_product_buttons_layouts();
                                echo '</div>';
                            }
                            if ( array_key_exists( 'swatches-hover', $layouts['show'] ) ) {
                                echo '<div class="styler-swatches-hover">'.do_shortcode( '[styler_swatches]' ).'</div>';
                            }
                        echo '</div>';
                        if ( array_key_exists( 'buttons-hover', $layouts['show'] ) || array_key_exists( 'title-buttons-hover', $layouts['show'] ) ) {
                            echo '<div class="styler-loop-product-buttons-mobile styler-mini-icon">';
                                styler_loop_product_buttons_layouts();
                            echo '</div>';
                        }
                        if ( '1' == styler_get_shop_column() || '1' == $column ) {
                            echo '<div class="styler-loop-item-content-wrapper'.$list_type_col.'">';
                        }
                        break;

                        case 'gallery':
                        $has_swatches = array_key_exists( 'swatches-hover', $layouts['show'] ) ? ' has-swatches' : '';
                        echo '<div class="styler-thumb-wrapper styler-thumb-slider'.$has_swatches.$list_type_col.$show_video.'">';
                            styler_loop_product_gallery();
                            if ( array_key_exists( 'stock', $layouts['show'] ) ) {
                                styler_loop_product_nostock();
                            }
                            if ( array_key_exists( 'sale-discount', $layouts['show'] ) ) {
                                echo '<div class="styler-product-labels">';
                                    styler_product_badge();
                                    styler_product_discount();
                                echo '</div>';
                            }
                            if ( array_key_exists( 'buttons-hover', $layouts['show'] ) ) {
                                echo '<div class="styler-loop-product-buttons-hover">';
                                    styler_loop_product_buttons_layouts();
                                echo '</div>';
                            }
                            if ( array_key_exists( 'swatches-hover', $layouts['show'] ) ) {
                                echo '<div class="styler-swatches-hover">'.do_shortcode( '[styler_swatches]' ).'</div>';
                            }
                        echo '</div>';
                        if ( array_key_exists( 'buttons-hover', $layouts['show'] ) || array_key_exists( 'title-buttons-hover', $layouts['show'] ) ) {
                            echo '<div class="styler-loop-product-buttons-mobile styler-mini-icon">';
                                styler_loop_product_buttons_layouts();
                            echo '</div>';
                        }
                        if ( '1' == styler_get_shop_column() || '1' == $column ) {
                            echo '<div class="styler-loop-item-content-wrapper'.$list_type_col.'">';
                        }
                        break;

                        case 'title':
                        echo '<h6 class="styler-product-name"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h6>';
                        break;

                        case 'title-stock':
                        echo '<div class="styler-title-stock styler-title-block styler-inline-two-block">';
                            echo '<div class="styler-block-left">';
                                echo '<h6 class="styler-product-name"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h6>';
                            echo '</div>';
                            echo '<div class="styler-block-right">';
                                styler_loop_product_nostock();
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'title-price':
                        echo '<div class="styler-title-price styler-title-block styler-inline-two-block">';
                            echo '<div class="styler-block-left">';
                                echo '<h6 class="styler-product-name"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h6>';
                            echo '</div>';
                                echo '<div class="styler-block-right">';
                                    woocommerce_template_loop_price();
                                echo '</div>';
                        echo '</div>';
                        break;

                        case 'title-rating':
                        echo '<div class="styler-title-rating styler-title-block styler-inline-two-block">';
                            echo '<div class="styler-block-left">';
                                echo '<h6 class="styler-product-name"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h6>';
                            echo '</div>';
                            if ( $product->get_average_rating() ) {
                                echo '<div class="styler-block-right">';
                                    woocommerce_template_loop_rating();
                                echo '</div>';
                            }
                        echo '</div>';
                        break;

                        case 'title-cart-hover':
                        echo '<div class="styler-title-cart-hover styler-title-block styler-has-hidden-cart">';
                            echo '<h6 class="styler-product-name"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h6>';
                            echo '<div class="styler-cart-hidden">';
                                styler_add_to_cart();
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'title-buttons-hover':
                        echo '<div class="styler-title-buttons-hover styler-title-block styler-has-hidden-cart">';
                            echo '<h6 class="styler-product-name"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h6>';
                            echo '<div class="styler-inline-two-block styler-sm-flex-left styler-mini-icon styler-cart-hidden">';
                                echo '<div class="styler-block-left">';
                                    styler_add_to_cart();
                                echo '</div>';
                                echo '<div class="styler-block-right">';
                                    styler_loop_product_buttons_layouts();
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'title-buttons-static':
                        echo '<div class="styler-title-buttons-static styler-title-block">';
                            echo '<h6 class="styler-product-name"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h6>';
                            echo '<div class="styler-block-right styler-mini-icon">';
                                styler_loop_product_buttons_layouts();
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'title-discount':
                        echo '<div class="styler-title-discount styler-title-block styler-inline-two-block">';
                            echo '<div class="styler-block-left">';
                                echo '<h6 class="styler-product-name"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h6>';
                            echo '</div>';
                            echo '<div class="styler-block-right">';
                                styler_product_discount();
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'price':
                            woocommerce_template_loop_price();
                        break;

                        case 'price-stock':
                        echo '<div class="styler-price-stock styler-inline-two-block styler-sm-flex-column">';
                                echo '<div class="styler-block-left">';
                                    woocommerce_template_loop_price();
                                echo '</div>';
                            echo '<div class="styler-block-right styler-sm-flex-left">';
                                styler_loop_product_nostock();
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'price-rating':
                        echo '<div class="styler-price-rating styler-inline-two-block styler-sm-flex-column">';
                                echo '<div class="styler-block-left">';
                                    woocommerce_template_loop_price();
                                echo '</div>';
                            echo '<div class="styler-block-right">';
                                woocommerce_template_loop_rating();
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'price-cart-hover':
                        echo '<div class="styler-price-cart-hover styler-title-block styler-has-hidden-cart">';
                                woocommerce_template_loop_price();
                            echo '<div class="styler-cart-hidden">';
                                styler_add_to_cart();
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'price-buttons':
                        echo '<div class="styler-price-buttons styler-inline-two-block">';
                                echo '<div class="styler-block-left">';
                                    woocommerce_template_loop_price();
                                echo '</div>';
                            echo '<div class="styler-block-right styler-mini-icon">';
                                styler_loop_product_buttons_layouts();
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'price-cart':
                        echo '<div class="styler-price-cart styler-inline-two-block">';
                                echo '<div class="styler-block-left">';
                                    woocommerce_template_loop_price();
                                echo '</div>';
                            echo '<div class="styler-block-right">';
                                styler_add_to_cart();
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'rating':
                            woocommerce_template_loop_rating();
                        break;

                        case 'rating-top':
                        echo '<div class="styler-rating styler-absolute-top">';
                            if ( $product->get_average_rating() ) {
                                woocommerce_template_loop_rating();
                            }
                        echo '</div>';
                        break;

                        case 'buttons-static':
                        echo '<div class="styler-loop-product-buttons-static">';
                            styler_loop_product_buttons_layouts();
                        echo '</div>';
                        break;

                        case 'cart':
                        echo '<div class="styler-cart-static">';
                            styler_add_to_cart();
                        echo '</div>';
                        break;

                        case 'cart-buttons':
                        echo '<div class="styler-cart-buttons styler-inline-two-block">';
                            echo '<div class="styler-block-left">';
                                styler_add_to_cart();
                            echo '</div>';
                            echo '<div class="styler-block-left styler-mini-icon">';
                                styler_loop_product_buttons_layouts();
                            echo '</div>';
                        echo '</div>';
                        break;

                        case 'swatches':
                        echo '<div class="styler-swatches-static">'.do_shortcode( '[styler_swatches]' ).'</div>';
                        break;

                        case 'sale':
                        echo '<div class="styler-product-labels">';
                            styler_product_badge();
                        echo '</div>';
                        break;

                        case 'discount':
                        echo '<div class="styler-product-labels">';
                            styler_product_discount();
                        echo '</div>';
                        break;

                        case 'desc':
                            styler_product_excerpt();
                        break;
                    }
                }
                if ( '1' == styler_get_shop_column() || '1' == $column ) {
                    echo '</div>';
                }
            echo '</div>';
            if ( '1' == styler_get_shop_column() || '1' == $column ) {
                echo '</div>';
            }
        }
    }
}


/**
* Single Bottom Popup Product Add To Cart
*/
if ( ! function_exists( 'styler_product_bottom_popup_cart' ) ) {
    add_action( 'styler_before_wp_footer', 'styler_product_bottom_popup_cart', 10 );
    function styler_product_bottom_popup_cart()
    {
        global $product;

        if ( !is_product() || $product->is_type( 'grouped' ) || '0' == styler_settings( 'styler_product_bottom_popup_cart', '0' ) || '1' == styler_settings( 'woo_catalog_mode', '0' ) ) {
            return;
        }
        ?>
        <div id="product-bottom-<?php the_ID(); ?>" <?php wc_product_class( 'styler-product-bottom-popup-cart', $product ); ?>>
            <div class="container-xl styler-container-xl">
                <div class="row">
                    <div class="col-12 col-md-6 d-none d-md-flex">
                        <div class="styler-product-bottom-details">
                            <?php echo get_the_post_thumbnail( $product->get_id(), 'thumbnail' ); ?>
                            <div class="styler-product-bottom-title">
                                <?php echo get_the_title( $product->get_id() ); ?>
                                <?php woocommerce_template_loop_price(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="styler-product-bottom-title mobile-title">
                            <?php echo get_the_title( $product->get_id() ); ?>
                        </div>
                        <?php
                        if ( $product->is_type( 'simple' ) ) {
                            woocommerce_template_single_add_to_cart();
                        } else {
                            $btn_title = esc_html__( 'Add to cart', 'styler' );
                            echo '<div class="styler-product-to-top"><a href="#product-'.$product->get_id().'" class="styler-btn styler-btn-medium styler-bg-black">'.$btn_title.'</a></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

if ( ! function_exists( 'styler_get_swatches_colors' ) ) {
    function styler_get_swatches_colors()
    {
        $colors = array();
        $attributes = wc_get_attribute_taxonomies();
        foreach ( $attributes as $attribute ) {
            if ( taxonomy_exists( wc_attribute_taxonomy_name( $attribute->attribute_name ) ) ) {
                $attr_id   = wc_attribute_taxonomy_id_by_name( $attribute->attribute_name );
                $attr_info = wc_get_attribute( $attr_id );

                if ( $attr_info->type == 'color' ) {
                    $terms = get_terms(wc_attribute_taxonomy_name($attribute->attribute_name), 'orderby=name&hide_empty=0');
                    foreach ( $terms as $term ) {
                        if ( !empty( $term->term_id ) ) {
                            $val = get_term_meta( $term->term_id, 'styler_swatches_color', true );
                            $colors[$term->name] = $val;
                        }
                    }
                }
            }
        }
        return !empty( $colors ) ? $colors : false;
    }
}

if ( !function_exists( 'shop_product_summary_layouts_manager' ) ) {
    function shop_product_summary_layouts_manager()
    {
        if ( 'default' == styler_settings( 'single_shop_summary_layout_type', 'default' ) ) {
            return;
        }
        $defaults = [
            'show'=> [
                'bread' => '',
                'title' => '',
                'rating' => '',
                'price' => '',
                'excerpt' => '',
                'cart' => '',
                'meta' => ''
            ]
        ];

        $layouts = styler_settings( 'single_shop_summary_layouts', $defaults );

        if ( $layouts ) {

            unset( $layouts['show']['placebo'] );

            foreach ( $layouts['show'] as $key => $value ) {
                switch ( $key ) {
                    case 'bread':
                    if ( '0' != styler_settings( 'breadcrumbs_visibility', '1' ) ) {
                        echo woocommerce_breadcrumb();
                    }
                    break;
                    case 'title':
                        woocommerce_template_single_title();
                    break;
                    case 'rating':
                        woocommerce_template_single_rating();
                    break;
                    case 'price':
                        woocommerce_template_single_price();
                    break;
                    case 'cart':
                    if ( '1' != styler_settings( 'woo_catalog_mode', '0' ) ) {
                        woocommerce_template_single_add_to_cart();
                    }
                    break;
                    case 'excerpt':
                         woocommerce_template_single_excerpt();
                    break;
                    case 'meta':
                         woocommerce_template_single_meta();
                    break;
                }
            }
        }
    }
}

if ( !function_exists( 'styler_fly_cart' ) ) {
    add_action( 'styler_before_wp_footer', 'styler_fly_cart' );
    function styler_fly_cart()
    {
        if ( '1' != styler_settings( 'shop_fly_cart_visibility', '0' ) ) {
            return;
        }
        ?>
        <div id="styler-sticky-cart-toggle" class="styler-sticky-cart-toggle" data-duration="<?php echo styler_settings( 'shop_fly_cart_duration', 1500 ); ?>">
            <span class="styler-cart-count styler-wc-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
            <?php echo styler_svg_lists( 'bag', 'styler-svg-icon' ); ?>
        </div>
        <?php
    }
}


if ( !function_exists( 'styler_ajax_checkout_popup' ) ) {
    function styler_ajax_checkout_popup()
    {
        $labels = apply_filters( 'styler_checkout_multisteps_strings', array(
            'billing' => _x( 'Billing & Shipping', 'Checkout: user multisteps', 'styler' ),
            'order'   => _x( 'Order & Payment', 'Checkout: user multisteps', 'styler' )
        ));

        $checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );
        $checkout     = new WC_Checkout();

        // If checkout registration is disabled and not logged in, the user cannot checkout.
        if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
            echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'styler' ) ) );
            return;
        }
        ?>
        <div class="styler-ajax-checkout-wrapper">

            <div class="steps">
                <div class="step step-billing active" data-step="1">
                    <span class="number">1</span>
                    <span class="label"><?php echo esc_html( $labels['billing'] ); ?></span>
                </div>
                <div class="step step-order" data-step="2">
                    <span class="number">2</span>
                    <span class="label"><?php echo esc_html( $labels['order'] ); ?></span>
                </div>
            </div>

            <div class="styler-checkout-form-wrapper styler-scrollbar">
                <div id="checkout_coupon" class="styler-woocommerce-checkout-coupon">
                    <?php woocommerce_checkout_coupon_form(); ?>
                </div>
                <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $checkout_url ); ?>" enctype="multipart/form-data" novalidate="novalidate">
                    <div class="slide-container">
                        <div class="slide-wrapper">
                            <?php if ( $checkout->get_checkout_fields() ) : ?>
                                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                                <div class="styler-customer-billing-details slide-item <?php echo is_user_logged_in() ? 'logged-in' : 'not-logged-in'; ?>" id="styler-customer-billing-details" data-step="1">
                                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                                    <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                                </div>

                                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                            <?php endif; ?>

                            <div class="styler-order-review slide-item" id="order_review" data-step="2">
                                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                                <h4 class="styler-form-title"><?php esc_html_e( 'Your order', 'styler' ); ?></h4>
                                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        </div>
        <?php
    }
}

if ( ! function_exists( 'styler_product_page_custom_btn' ) ) {
    add_action( 'woocommerce_after_add_to_cart_form', 'styler_product_page_after_form_buttons', 10 );
    function styler_product_page_after_form_buttons()
    {
        $custom_btn   = styler_settings('product_custom_btn_visibility', '0' );
        $whatsapp_btn = styler_settings('product_whatsapp_button_visibility', '0' );
        if ( ('1' == $custom_btn || '1' == $whatsapp_btn ) && !wp_doing_ajax() ) {
            $two_buttons = '1' == $custom_btn && '1' == $whatsapp_btn  ? ' has-two-buttons' : '';
            echo '<div class="styler-summary-item styler-extra-buttons'.$two_buttons.'">';
                if ( '1' == $custom_btn ) {
                    styler_product_page_custom_btn();
                }
                if ( '1' == $whatsapp_btn  ) {
                    styler_product_whatsapp_button();
                }
            echo '</div>';
        }
    }
}
if ( ! function_exists( 'styler_product_page_custom_btn' ) ) {
    function styler_product_page_custom_btn()
    {
        $page_link = get_the_permalink();
        $page_id   = get_the_ID();
        $action    = styler_settings( 'product_custom_btn_action', '' );
        $title     = styler_settings( 'product_custom_btn_title', '' );
        $link      = styler_settings( 'product_custom_btn_link', '' );
        $target    = styler_settings( 'product_custom_btn_target' );
        $shortcode = styler_settings( 'product_custom_btn_form_shortcode', '' );
        $wlink     = styler_settings( 'product_custom_btn_whatsapp_link' );
        $wlink     = $wlink ? $wlink : 'https://api.whatsapp.com/send?text=' . urlencode( $page_link );
        $wm_link   = styler_settings( 'product_custom_btn_whatsapp_mobile_link' );
        $wm_link   = $wm_link ? $wm_link : 'whatsapp://send?text=' . urlencode( $page_link );
        $w_link    = wp_is_mobile() ? $wm_link : $wlink;

        if ( 'link' == $action ) {

            echo '<a class="styler-btn styler-btn-medium styler-bg-black" href="'.$link.'" target="'.$target.'">'.$title.'</a>';

        } elseif ( 'form' == $action ) {

            wp_enqueue_style( 'fancybox' );
            wp_enqueue_script( 'fancybox' );

            echo '<a class="styler-btn styler-btn-medium styler-bg-black" data-fancybox="dialog" data-src="#dialog-content-'.$page_id.'" href="#dialog-content-'.$page_id.'">'.$title.'</a>';
            echo '<div id="dialog-content-'.$page_id.'" style="display:none;max-width:500px;">'.do_shortcode( $shortcode ).'</div>';

        } elseif ( 'whatsapp' == $action ) {

            echo '<a rel="noopener noreferrer nofollow" href="'.$w_link.'" target="'.esc_html( $target ).'" class="styler-btn styler-btn-medium styler-whatsapp"><i class="fab fa-whatsapp"></i><span class="whatsapp-text">'.$title.'</span></a>';
        }
    }
}

/*************************************************
## Whatsapp Button For Single Product
*************************************************/
if ( ! function_exists( 'styler_product_whatsapp_button' ) ) {
    function styler_product_whatsapp_button()
    {
        $page_link     = get_the_permalink();
        $link          = styler_settings( 'product_whatsapp_link' );
        $link          = $link ? $link : 'https://api.whatsapp.com/send?text=' . urlencode( $page_link );
        $mobile_link   = styler_settings( 'product_whatsapp_mobile_link' );
        $mobile_link   = $link ? $link : 'whatsapp://send?text=' . urlencode( $page_link );
        $whatsapp_link = wp_is_mobile() ? $mobile_link : $link;
        $target        = styler_settings( 'product_whatsapp_target' );
        $btn_title     = styler_settings( 'whatsapp_btn_title', '' ) ? styler_settings( 'whatsapp_btn_title' ) : esc_html__( 'Whatsapp', 'styler' );

        echo '<a rel="noopener noreferrer nofollow" href="'.$whatsapp_link.'" target="'.esc_attr( $target ).'" class="styler-btn styler-btn-medium styler-whatsapp"><i class="fab fa-whatsapp"></i><span class="whatsapp-text">'.esc_html( $btn_title ).'</span></a>';
    }
}

if ( ! function_exists( 'styler_cart_goal_progressbar' ) ) {
    add_action( 'styler_before_cart_table', 'styler_cart_goal_progressbar', 10 );
    add_action( 'styler_side_panel_after_header', 'styler_cart_goal_progressbar', 10 );
    function styler_cart_goal_progressbar()
    {
        $amount = intval(styler_settings( 'free_shipping_progressbar_amount', 500 ));
        $amount = round( $amount, wc_get_price_decimals() );
        if ( !( $amount > 0 ) || '1' != styler_settings( 'free_shipping_progressbar_visibility', '0' ) ) {
            return;
        }

        $message_initial = styler_settings( 'free_shipping_progressbar_message_initial' );
        $message_success = styler_settings( 'free_shipping_progressbar_message_success' );

        $total     = WC()->cart->get_displayed_subtotal();
        $remainder = ( $amount - $total );
        $success   = $total >= $amount ? ' free-shipping-success shakeY' : '';
        $value     = $total <= $amount ? ( $total / $amount ) * 100 : 0;

        if ( is_cart() ) {
            $success .= ' cart-page-goal';
        } elseif ( is_checkout() ) {
            $success .= ' checkout-page-goal';
        }
        wp_enqueue_style( 'free-shipping-progressbar');
        ?>
        <div class="styler-cart-goal-wrapper<?php echo esc_attr( $success ); ?>">
            <div class="styler-cart-goal-text">
                <?php
                if ( $total >= $amount ) {
                    if ( $message_success ) {
                        echo sprintf('%s', $message_success );
                    } else {
                        echo sprintf('%s <strong>%s</strong>',
                        esc_html__('Congrats! You are eligible for', 'styler'),
                        esc_html__('more to enjoy FREE Shipping', 'styler'));
                    }
                } else {
                    if ( $message_initial ) {
                        echo sprintf('%s', str_replace( '[remainder]', wc_price( $remainder ), $message_initial ) );
                    } else {
                        echo sprintf('%s %s <strong>%s</strong>',
                        esc_html__('Buy', 'styler'),
                        wc_price( $remainder ),
                        esc_html__('more to enjoy FREE Shipping', 'styler'));
                    }
                }
                ?>
                <div data-percent="<?php echo esc_attr( $value ); ?>" class="styler-cart-goal-percent"></div>
            </div>
            <div class="styler-free-shipping-progress">
                <div class="styler-progress-bar-wrap">
                    <div class="styler-progress-bar" style="width:<?php echo esc_attr( $value ); ?>%;">
                        <div class="styler-progress-value">
                            <?php echo styler_svg_lists( 'delivery-return', 'styler-svg-icon' ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}


/*************************************************
## Buy Now Button For Single Product
*************************************************/
if ( ! function_exists( 'styler_add_buy_now_button_single' ) ) {
    function styler_add_buy_now_button_single()
    {
        global $product;
        $param     = apply_filters( 'styler_buy_now_param', styler_settings( 'buy_now_param', 'styler-buy-now' ) );
        $btn_title = styler_settings( 'buy_now_btn_title', '' ) ? styler_settings( 'buy_now_btn_title' ) : esc_html__( 'Buy Now', 'styler' );
        if ( $product->is_type( 'simple' ) || $product->is_type( 'variable' ) ) {
            return '<button id="buynow" type="submit" name="'.$param.'" value="'.$product->get_ID().'" class="styler-btn-buynow styler-btn styler-btn-medium styler-bg-black">'.$btn_title.'</button>';
        }
    }
}


/*************************************************
## Handle for click on buy now
*************************************************/
if ( ! function_exists( 'styler_handle_buy_now' ) ) {
    function styler_handle_buy_now()
    {
        $param = apply_filters( 'styler_buy_now_param', styler_settings( 'buy_now_param', 'styler-buy-now' ) );

        if ( ! isset( $_REQUEST[ $param ] ) || '0' == styler_settings( 'buy_now_visibility', '0' ) ) {
            return false;
        }

        $quantity     = floatval( $_REQUEST['quantity'] ?: 1 );
        $product_id   = absint( $_REQUEST[ $param ] ?: 0 );
        $variation_id = absint( $_REQUEST['variation_id'] ?: 0 );
        $variation    = [];

        foreach ( $_REQUEST as $name => $value ) {
            if ( substr( $name, 0, 10 ) === 'attribute_' ) {
                $variation[ $name ] = $value;
            }
        }

        if ( $product_id ) {
            if ( '1' == styler_settings( 'buy_now_reset_cart', '0' ) ) {
                WC()->cart->empty_cart();
            }

            if ( $variation_id ) {
                WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
            } else {
                WC()->cart->add_to_cart( $product_id, $quantity );
            }

            switch ( apply_filters( 'styler_buy_now_redirect', styler_settings( 'buy_now_redirect', 'checkout' ) ) ) {
                case 'checkout':
                $redirect = wc_get_checkout_url();
                break;
                case 'cart':
                $redirect = wc_get_cart_url();
                break;
                default:
                $redirect = styler_settings( 'buy_now_redirect_custom', '/' );
            }

            $redirect = esc_url( apply_filters( 'buy_now_redirect_url', $redirect ) );

            if ( empty( $redirect ) ) {
                $redirect = '/';
            }

            wp_safe_redirect( $redirect );

            exit;
        }

    }
    add_action( 'template_redirect', 'styler_handle_buy_now' );
}


/* SHOP CATALOG MODE */
$styler_options = get_option('styler');

if ( '1' == $styler_options['woo_catalog_mode'] && '1' == $styler_options['woo_disable_cart_checkout'] ) {
    add_filter( 'get_pages','styler_hide_cart_checkout_pages' );
    add_filter( 'wp_get_nav_menu_items', 'styler_hide_cart_checkout_pages' );
    add_filter( 'wp_nav_menu_objects', 'styler_hide_cart_checkout_pages' );
    add_action( 'wp', 'styler_check_pages_redirect' );
}

function styler_hide_cart_checkout_pages( $pages )
{
    $excluded_pages = array(
        wc_get_page_id( 'cart' ),
        wc_get_page_id( 'checkout' )
    );

    foreach ( $pages as $key => $page ) {

        if ( in_array( current_filter(), array( 'wp_get_nav_menu_items', 'wp_nav_menu_objects' ), true ) ) {
            $page_id = $page->object_id;
            if ( 'page' !== $page->obect_id ) {
                continue;
            }
        } else {
            $page_id = $page->ID;
        }

        if ( in_array( (int) $page_id, $excluded_pages, true ) ) {
            unset( $pages[ $key ] );
        }
    }

    return $pages;
}

function styler_check_pages_redirect()
{
    $cart     = is_page( wc_get_page_id( 'cart' ) );
    $checkout = is_page( wc_get_page_id( 'checkout' ) );

    wp_reset_postdata();

    if ( $cart || $checkout ) {
        wp_safe_redirect( home_url() );
        exit;
    }
}

if ( !function_exists( 'styler_custom_stock_status_filter' ) ) {
    add_action( 'woocommerce_product_query', 'styler_custom_stock_status_filter', 10, 2 );
    function styler_custom_stock_status_filter($query)
    {
        if ( isset( $_GET['stock_status'] ) && $_GET['stock_status'] === 'instock' ) {
            $query->set('meta_query', array(
                array(
                    'key'     => '_stock_status',
                    'value'   => 'instock',
                    'compare' => '='
                )
            ));
        }

        if ( isset( $_GET['on_sale'] ) && $_GET['on_sale'] == 'onsale' ) {
            $query->set ( 'post__in', wc_get_product_ids_on_sale() );
        }
    }
}
// Products add brand logo
if ( !function_exists( 'styler_add_product_brand' ) ) {
    function styler_add_product_brand()
    {
        if ( '1' == styler_settings( 'product_page_brand_visibility', '0' ) ) {
            $terms = wp_get_post_terms(get_the_ID(), 'styler_product_brands');
            if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
                $termid    = $terms[0]->term_id;
                $term_meta = get_option( "taxonomy_$termid" );
                $image_id  = !empty($term_meta['brand_thumbnail_id']) ? absint( $term_meta['brand_thumbnail_id'] ) : '';
                if ( $image_id ) {
                    echo '<div class="styler-summary-item styler-product-brand">';
                        echo '<a href="'.esc_url( get_term_link( $terms[0] ) ).'" title="'.$terms[0]->name.'">';
                            echo wp_get_attachment_image( $image_id, 'thumbnail' );
                        echo '</a>';
                    echo '</div>';
                }
            }
        }
    }
}
add_action( 'woocommerce_single_product_summary', 'styler_add_product_brand', 5);
