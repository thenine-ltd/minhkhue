<?php

    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if (! class_exists('Redux' )) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $styler_pre = "styler";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $styler_theme = wp_get_theme(); // For use with some settings. Not necessary.

    $styler_options_args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name' => $styler_pre,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name' => $styler_theme->get('Name' ),
        // Name that appears at the top of your panel
        'display_version' => $styler_theme->get('Version' ),
        // Version that appears at the top of your panel
        'menu_type' => 'submenu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu' => false,
        // Show the sections below the admin menu item or not
        'menu_title' => esc_html__( 'Theme Options', 'styler' ),
        'page_title' => esc_html__( 'Theme Options', 'styler' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key' => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography' => false,
        // Use a asynchronous font on the front end or font string
        'admin_bar' => false,
        // Show the panel pages on the admin bar
        'admin_bar_icon' => 'dashicons-admin-generic',
        // Choose an icon for the admin bar menu
        'admin_bar_priority' => 50,
        // Choose an priority for the admin bar menu
        'global_variable' => 'styler',
        // Set a different name for your global variable other than the styler_pre
        'dev_mode' => false,
        // Show the time the page took to load, etc
        'update_notice' => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer' => true,
        // Enable basic customizer support

        // OPTIONAL -> Give you extra features
        'page_priority' => 99,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent' => apply_filters( 'ninetheme_parent_slug', 'themes.php' ),
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions' => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon' => '',
        // Specify a custom URL to an icon
        'last_tab' => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon' => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug' => '',
        // Page slug used to denote the panel, will be based off page title then menu title then styler_pre if not provided
        'save_defaults' => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show' => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark' => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export' => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time' => 60 * MINUTE_IN_SECONDS,
        'output' => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag' => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database' => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn' => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints' => array(
            'icon' => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color' => 'lightgray',
            'icon_size' => 'normal',
            'tip_style' => array(
                'color' => 'dark',
                'shadow' => true,
                'rounded' => false,
                'style' => '',
            ),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'effect' => 'slide',
                    'duration' => '500',
                    'event' => 'mouseover',
                ),
                'hide' => array(
                    'effect' => 'slide',
                    'duration' => '500',
                    'event' => 'click mouseleave',
                ),
            ),
        )
    );

    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $styler_options_args['admin_bar_links'][] = array(
        'id' => 'ninetheme-styler-docs',
        'href' => 'https://ninetheme.com/docs/styler-documentation/',
        'title' => esc_html__( 'styler Documentation', 'styler' ),
    );
    $styler_options_args['admin_bar_links'][] = array(
        'id' => 'ninetheme-support',
        'href' => 'https://9theme.ticksy.com/',
        'title' => esc_html__( 'Support', 'styler' ),
    );
    $styler_options_args['admin_bar_links'][] = array(
        'id' => 'ninetheme-portfolio',
        'href' => 'https://themeforest.net/user/ninetheme/portfolio',
        'title' => esc_html__( 'NineTheme Portfolio', 'styler' ),
    );

    // Add content after the form.
    $styler_options_args['footer_text'] = esc_html__( 'If you need help please read docs and open a ticket on our support center.', 'styler' );

    Redux::setArgs($styler_pre, $styler_options_args);

    /* END ARGUMENTS */

    /* START SECTIONS */

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

    $activekit = get_option( 'elementor_active_kit' );

    $wpcf7_args = array(
        'post_type'      => 'wpcf7_contact_form',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );

    /*************************************************
    ## MAIN SETTING SECTION
    *************************************************/
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Main Setting', 'styler' ),
        'id' => 'basic',
        'desc' => esc_html__( 'These are main settings for general theme!', 'styler' ),
        'icon' => 'el el-cog',
    ));
    //BREADCRUMBS SETTINGS SUBSECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Typograhy and Color', 'styler' ),
        'id' => 'themecolorsubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'id' =>'edit_typograhy_settings',
                'type' => 'info',
                'desc' => sprintf( '<b>%s</b> <a class="thm-btn" href="%s" target="_blank">%s</a>',
                    esc_html__( 'This theme uses Elementor Site Settings', 'styler' ),
                    admin_url('post.php?post='.$activekit.'&action=elementor'),
                    esc_html__( 'Site Settings', 'styler' )
                )
            ),
            array(
                'title' => esc_html__( 'Theme Base Color', 'styler' ),
                'subtitle' => esc_html__( 'Add theme root base color.', 'styler' ),
                'customizer' => true,
                'id' => 'theme_clr1',
                'type' => 'color',
                'default' => ''
            ),
            array(
                'title' => esc_html__( 'Theme Primary Color', 'styler' ),
                'subtitle' => esc_html__( 'Add theme root primary color.', 'styler' ),
                'customizer' => true,
                'id' => 'theme_clr2',
                'type' => 'color',
                'default' => ''
            ),
            array(
                'title' => esc_html__( 'Theme Black Color', 'styler' ),
                'subtitle' => esc_html__( 'Add theme root black color.', 'styler' ),
                'customizer' => true,
                'id' => 'theme_clr3',
                'type' => 'color',
                'default' => ''
            ),
            array(
                'title' => esc_html__( 'Theme Black Color 2', 'styler' ),
                'subtitle' => esc_html__( 'Add theme root black color.', 'styler' ),
                'customizer' => true,
                'id' => 'theme_clr4',
                'type' => 'color',
                'default' => ''
            ),
            array(
                'title' => esc_html__( 'Theme Content Width', 'styler' ),
                'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'styler' ),
                'customizer' => true,
                'id' => 'content_width',
                'type' => 'slider',
                'default' => 1230,
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'display_value' => 'text'
            ),
            array(
                'title' => esc_html__( 'Theme Content Width', 'styler' ),
                'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'styler' ),
                'customizer' => true,
                'id' => 'content_width',
                'type' => 'slider',
                'default' => 1450,
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'display_value' => 'text'
            ),
            array(
                'title' => esc_html__( 'Theme Content Width Responsive ( min-width 1200px )', 'styler' ),
                'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'styler' ),
                'customizer' => true,
                'id' => 'content_width_md',
                'type' => 'slider',
                'default' => 1140,
                'min' => 0,
                'step' => 1,
                'max' => 1200,
                'display_value' => 'text'
            ),
        )
    ));
    //BREADCRUMBS SETTINGS SUBSECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Breadcrumbs', 'styler' ),
        'id' => 'themebreadsubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'title' => esc_html__( 'Breadcrumbs', 'styler' ),
                'subtitle' => esc_html__( 'If enabled, adds breadcrumbs navigation to bottom of page title.', 'styler' ),
                'customizer' => true,
                'id' => 'breadcrumbs_visibility',
                'type' => 'switch',
                'default' => true
            ),
            array(
                'title' => esc_html__( 'Breadcrumbs Current Color', 'styler' ),
                'customizer' => true,
                'id' => 'breadcrumbs_current',
                'type' => 'color',
                'default' => '',
                'output' => array( '.styler-breadcrumb li.breadcrumb_active' ),
                'required' => array( 'breadcrumbs_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Breadcrumbs Separator Color', 'styler' ),
                'customizer' => true,
                'id' => 'breadcrumbs_icon',
                'type' => 'color',
                'default' => '',
                'output' => array( '.styler-breadcrumb .breadcrumb_link_seperator' ),
                'required' => array( 'breadcrumbs_visibility', '=', '1' )
            )
        )
    ));
    //PRELOADER SETTINGS SUBSECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Preloader', 'styler' ),
        'id' => 'themepreloadersubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'title' => esc_html__( 'Preloader', 'styler' ),
                'subtitle' => esc_html__( 'If enabled, adds preloader.', 'styler' ),
                'customizer' => true,
                'id' => 'preloader_visibility',
                'type' => 'switch',
                'default' => true
            ),
            array(
                'title' => esc_html__( 'Preloader Type', 'styler' ),
                'subtitle' => esc_html__( 'Select your preloader type.', 'styler' ),
                'customizer' => true,
                'id' => 'pre_type',
                'type' => 'select',
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Default', 'styler' ),
                    '01' => esc_html__( 'Type 1', 'styler' ),
                    '02' => esc_html__( 'Type 2', 'styler' ),
                    '03' => esc_html__( 'Type 3', 'styler' ),
                    '04' => esc_html__( 'Type 4', 'styler' ),
                    '05' => esc_html__( 'Type 5', 'styler' ),
                    '06' => esc_html__( 'Type 6', 'styler' ),
                    '07' => esc_html__( 'Type 7', 'styler' ),
                    '08' => esc_html__( 'Type 8', 'styler' ),
                    '09' => esc_html__( 'Type 9', 'styler' ),
                    '10' => esc_html__( 'Type 10', 'styler' ),
                    '11' => esc_html__( 'Type 11', 'styler' ),
                    '12' => esc_html__( 'Type 12', 'styler' )
                ),
                'default' => '12'
            ),
            array(
                'title' => esc_html__( 'Preloader Image', 'styler' ),
                'subtitle' => esc_html__( 'Upload your Logo. If left blank theme will use site default preloader.', 'styler' ),
                'customizer' => true,
                'id' => 'pre_img',
                'type' => 'media',
                'url' => true,
                'customizer' => true,
                'required' => array(
                    array( 'preloader_visibility', '=', '1' ),
                    array( 'pre_type', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Background Color', 'styler' ),
                'subtitle' => esc_html__( 'Add preloader background color.', 'styler' ),
                'customizer' => true,
                'id' => 'pre_bg',
                'type' => 'color',
                'default' => '',
                'required' => array( 'preloader_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Spin Color', 'styler' ),
                'subtitle' => esc_html__( 'Add preloader spin color.', 'styler' ),
                'customizer' => true,
                'id' => 'pre_spin',
                'type' => 'color',
                'default' => '',
                'required' => array( 'preloader_visibility', '=', '1' )
            )
    )));
    //NEWSLETTER SETTINGS SUBSECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Popup Newsletter', 'styler' ),
        'id' => 'themenewslettersubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'title' => esc_html__( 'Newsletter Popup', 'styler' ),
                'subtitle' => esc_html__( 'If enabled, adds preloader.', 'styler' ),
                'customizer' => true,
                'id' => 'popup_newsletter_visibility',
                'type' => 'switch',
                'default' => false
            ),
            array(
                'title' => esc_html__( 'Template Type', 'styler' ),
                'subtitle' => esc_html__( 'Select your preloader type.', 'styler' ),
                'customizer' => true,
                'id' => 'popup_newsletter_type',
                'type' => 'select',
                'customizer' => true,
                'options' => array(
                    'elementor' => esc_html__( 'Elementor', 'styler' ),
                    'shortcode' => esc_html__( 'Shortcode', 'styler' )
                ),
                'default' => 'elementor',
                'required' => array( 'popup_newsletter_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'styler' ),
                'customizer' => true,
                'id' => 'popup_newsletter_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'popup_newsletter_visibility', '=', '1' ),
                    array( 'popup_newsletter_type', '=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Shortcode', 'styler' ),
                'subtitle' => esc_html__( 'Add your shortcode here', 'styler' ),
                'customizer' => true,
                'id' => 'popup_newsletter_shortcode',
                'type' => 'text',
                'validate' => 'number',
                'customizer' => true,
                'required' => array(
                    array( 'popup_newsletter_visibility', '=', '1' ),
                    array( 'popup_newsletter_type', '=', 'shortcode' )
                )
            ),
            array(
                'title' => esc_html__( 'Expire Date', 'styler' ),
                'subtitle' => esc_html__( 'Add your expire date here', 'styler' ),
                'customizer' => true,
                'id' => 'popup_newsletter_expire_date',
                'type' => 'text',
                'validate' => 'number',
                'default' => 15,
                'customizer' => true,
                'required' => array( 'popup_newsletter_visibility', '=', '1' )
            )
    )));
    $is_right = is_rtl() ? 'right' : 'left';
    $is_left = is_rtl() ? 'left' : 'right';
    //BACKTOTOP BUTTON SUBSECTION
    Redux::setSection($styler_pre, array(
    'title' => esc_html__( 'Back-to-top Button', 'styler' ),
    'id' => 'backtotop',
    'icon' => 'el el-brush',
    'subsection' => true,
    'fields' => array(
        array(
            'title' => esc_html__( 'Back-to-top', 'styler' ),
            'subtitle' => esc_html__( 'Switch On-off', 'styler' ),
            'desc' => esc_html__( 'If enabled, adds back to top.', 'styler' ),
            'customizer' => true,
            'id' => 'backtotop_visibility',
            'type' => 'switch',
            'default' => true
        ),
        array(
            'title' => esc_html__( 'Bottom Offset', 'styler' ),
            'subtitle' => esc_html__( 'Set custom bottom offset for the back-to-top button', 'styler' ),
            'customizer' => true,
            'id' => 'backtotop_top_offset',
            'type' => 'spacing',
            'output' => array('.scroll-to-top'),
            'mode' => 'absolute',
            'units' => array('px'),
            'all' => false,
            'top' => false,
            $is_left => true,
            'bottom' => true,
            $is_right => false,
            'default' => array(
                $is_left => '30',
                'bottom' => '30',
                'units' => 'px'
            ),
            'required' => array( 'backtotop_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Background Color', 'styler' ),
            'customizer' => true,
            'id' => 'backtotop_bg',
            'type' => 'color',
            'mode' => 'background-color',
            'validate' => 'color',
            'output' => array('.scroll-to-top'),
            'required' => array( 'backtotop_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Hover Background Color', 'styler' ),
            'customizer' => true,
            'id' => 'backtotop_hvrbg',
            'type' => 'color',
            'mode' => 'background-color',
            'validate' => 'color',
            'output' => array('.scroll-to-top:hover'),
            'required' => array( 'backtotop_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Arrow Color', 'styler' ),
            'customizer' => true,
            'id' => 'backtotop_icon',
            'type' => 'color',
            'default' =>  '',
            'validate' => 'color',
            'output' => array('.scroll-to-top'),
            'required' => array( 'backtotop_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Hover Arrow Color', 'styler' ),
            'customizer' => true,
            'id' => 'backtotop_hvricon',
            'type' => 'color',
            'default' =>  '',
            'validate' => 'color',
            'output' => array('.scroll-to-top:hover'),
            'required' => array( 'backtotop_visibility', '=', '1' )
        ),
    )));

    // THEME PAGINATION SUBSECTION
    Redux::setSection($styler_pre, array(
    'title' => esc_html__( 'Pagination', 'styler' ),
    'desc' => esc_html__( 'These are main settings for general theme!', 'styler' ),
    'id' => 'pagination',
    'subsection' => true,
    'icon' => 'el el-link',
    'fields' => array(
        array(
            'title' => esc_html__( 'Pagination', 'styler' ),
            'subtitle' => esc_html__( 'Switch On-off', 'styler' ),
            'desc' => esc_html__( 'If enabled, adds pagination.', 'styler' ),
            'customizer' => true,
            'id' => 'pagination_visibility',
            'type' => 'switch',
            'default' => true
        ),
        array(
            'title' => esc_html__( 'Alignment', 'styler' ),
            'customizer' => true,
            'id' => 'pagination_alignment',
            'type' => 'button_set',
            'customizer' => true,
            'options' => array(
                'flex-start' => esc_html__( 'Left', 'styler' ),
                'center' => esc_html__( 'Center', 'styler' ),
                'flex-end' => esc_html__( 'Right', 'styler' )
            ),
            'default' => 'center',
            'required' => array( 'pagination_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Size', 'styler' ),
            'customizer' => true,
            'id' => 'pagination_size',
            'type' => 'dimensions',
            'output' => array('.nt-pagination .nt-pagination-item .nt-pagination-link,.styler-woocommerce-pagination ul li a, .styler-woocommerce-pagination ul li span' ),
            'required' => array( 'pagination_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Border', 'styler' ),
            'customizer' => true,
            'id' => 'pagination_border',
            'type' => 'border',
            'output' => array('.nt-pagination .nt-pagination-item .nt-pagination-link,.styler-woocommerce-pagination ul li a, .styler-woocommerce-pagination ul li span' ),
            'required' => array( 'pagination_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Border ( Hover/Active )', 'styler' ),
            'customizer' => true,
            'id' => 'pagination_hvrborder',
            'type' => 'border',
            'output' => array('.nt-pagination .nt-pagination-item.active .nt-pagination-link,.nt-pagination .nt-pagination-item .nt-pagination-link:hover,.styler-woocommerce-pagination ul li a:focus, .styler-woocommerce-pagination ul li a:hover, .styler-woocommerce-pagination ul li span.current' ),
            'required' => array( 'pagination_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Border Radius ( px )', 'styler' ),
            'customizer' => true,
            'id' => 'pagination_border_radius',
            'type' => 'slider',
            'max' => 300,
            'required' => array( 'pagination_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Background Color', 'styler' ),
            'customizer' => true,
            'id' => 'pagination_bgclr',
            'type' => 'color_rgba',
            'mode' => 'background-color',
            'validate' => 'color',
            'output' => array('.nt-pagination .nt-pagination-item .nt-pagination-link,.styler-woocommerce-pagination ul li a, .styler-woocommerce-pagination ul li span' ),
            'required' => array( 'pagination_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Background Color ( Hover/Active )', 'styler' ),
            'customizer' => true,
            'id' => 'pagination_hvrbgclr',
            'type' => 'color_rgba',
            'mode' => 'background-color',
            'output' => array('.nt-pagination .nt-pagination-item.active .nt-pagination-link,.nt-pagination .nt-pagination-item .nt-pagination-link:hover,.styler-woocommerce-pagination ul li a:focus, .styler-woocommerce-pagination ul li a:hover, .styler-woocommerce-pagination ul li span.current' ),
            'required' => array( 'pagination_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Number Color', 'styler' ),
            'customizer' => true,
            'id' => 'pagination_clr',
            'type' => 'color',
            'validate' => 'color',
            'output' => array('.nt-pagination .nt-pagination-item .nt-pagination-link,.styler-woocommerce-pagination ul li a, .styler-woocommerce-pagination ul li span' ),
            'required' => array( 'pagination_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Number Color ( Hover/Active )', 'styler' ),
            'customizer' => true,
            'id' => 'pagination_hvrclr',
            'type' => 'color',
            'validate' => 'color',
            'output' => array('.nt-pagination .nt-pagination-item.active .nt-pagination-link,.nt-pagination .nt-pagination-item .nt-pagination-link:hover,.styler-woocommerce-pagination ul li a:focus, .styler-woocommerce-pagination ul li a:hover, .styler-woocommerce-pagination ul li span.current' ),
            'required' => array( 'pagination_visibility', '=', '1' )
        )
    )));

    // THEME LIGHTBOX POPUP SUBSECTION
    Redux::setSection($styler_pre, array(
    'title' => esc_html__( 'Lightbox', 'styler' ),
    'desc' => esc_html__( 'These are main settings for general theme!', 'styler' ),
    'id' => 'themelightbox',
    'subsection' => true,
    'icon' => 'el el-brush',
    'fields' => array(
        array(
            'title' => esc_html__( 'Overlay Color', 'styler' ),
            'customizer' => true,
            'id' => 'lightbox_overlay_bgclr',
            'type' => 'color_rgba',
            'mode' => 'background-color',
            'validate' => 'color',
            'output' => array('.mfp-bg' ),
            'required' => array( 'pagination_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Content Background Color', 'styler' ),
            'customizer' => true,
            'id' => 'lightbox_content_bgclr',
            'type' => 'color_rgba',
            'mode' => 'background-color',
            'validate' => 'color',
            'output' => array('.styler-product360-wrapper, .styler-single-product-delivery, .styler-single-product-question, .styler-quickview-wrapper' )
        ),
        array(
            'title' => esc_html__( 'Content Max-width', 'styler' ),
            'customizer' => true,
            'id' => 'lightbox_maxwidth',
            'type' => 'dimensions',
            'output' => array('.nt-pagination .nt-pagination-item .nt-pagination-link,.styler-woocommerce-pagination ul li a, .styler-woocommerce-pagination ul li span' ),
            'required' => array( 'pagination_visibility', '=', '1' )
        ),
        array(
            'title' => esc_html__( 'Close Background Color', 'styler' ),
            'customizer' => true,
            'id' => 'lightbox_close_bgclr',
            'type' => 'color_rgba',
            'mode' => 'background-color',
            'validate' => 'color',
            'output' => array('.styler-mfp-close' )
        ),
        array(
            'title' => esc_html__( 'Close Color', 'styler' ),
            'customizer' => true,
            'id' => 'lightbox_close_clr',
            'type' => 'color_rgba',
            'mode' => 'background-color',
            'validate' => 'color',
            'output' => array('.mfp-close-btn-in .mfp-close' )
        )
    )));

    // THEME OPTIMIZATION
    Redux::setSection($styler_pre, array(
    'title' => esc_html__( 'Optimization', 'styler' ),
    'desc' => esc_html__( 'These are main settings for general theme!', 'styler' ),
    'id' => 'themeoptimization',
    'subsection' => true,
    'icon' => 'el el-brush',
    'fields' => array(
        array(
            'title' => esc_html__( 'LazyLoad', 'styler' ),
            'subtitle' => esc_html__( 'You can use this option to disable or enable lazy loading of image files.', 'styler' ),
            'customizer' => true,
            'id' => 'theme_lazyload_images',
            'type' => 'switch',
            'default' => true
        ),
        array(
            'title' => esc_html__( 'Disable Gutenberg', 'styler' ),
            'subtitle' => esc_html__( 'This theme does not support gutenberg so some css files are filtered, if you want to use gutenberg you can use this option', 'styler' ),
            'customizer' => true,
            'id' => 'theme_blocks_styles',
            'type' => 'switch',
            'default' => false
        )
    )));

    /*************************************************
    ## LOGO SECTION
    *************************************************/
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Logo', 'styler' ),
        'desc' => esc_html__( 'These are main settings for general theme!', 'styler' ),
        'id' => 'logosection',
        'icon' => 'el el-star-empty',
        'fields' => array(
            array(
                'title' => esc_html__( 'Logo Switch', 'styler' ),
                'subtitle' => esc_html__( 'You can select logo on or off.', 'styler' ),
                'customizer' => true,
                'id' => 'logo_visibility',
                'type' => 'switch',
                'default' => true
            ),
            array(
                'title' => esc_html__( 'Logo Type', 'styler' ),
                'subtitle' => esc_html__( 'Select your logo type.', 'styler' ),
                'customizer' => true,
                'id' => 'logo_type',
                'type' => 'select',
                'customizer' => true,
                'options' => array(
                    'img' => esc_html__( 'Image Logo', 'styler' ),
                    'sitename' => esc_html__( 'Site Name', 'styler' ),
                    'customtext' => esc_html__( 'Custom HTML', 'styler' )
                ),
                'default' => 'sitename',
                'required' => array( 'logo_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Custom Text for Logo', 'styler' ),
                'desc' => esc_html__( 'Text entered here will be used as logo', 'styler' ),
                'customizer' => true,
                'id' => 'text_logo',
                'type' => 'editor',
                'args' => array(
                    'teeny' => false,
                    'textarea_rows' => 10
                ),
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'customtext' )
                ),
            ),
            array(
                'title' => esc_html__( 'Custom Text Logo Typography', 'styler' ),
                'id' => 'text_logo_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.logo .header-text-logo' ),
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'customtext' )
                ),
            ),
            array(
                'title' => esc_html__( 'Hover Logo Color', 'styler' ),
                'desc' => esc_html__( 'Set your own hover color for the text logo.', 'styler' ),
                'customizer' => true,
                'id' => 'text_logo_hvr',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.nt-logo .header-text-logo:hover' ),
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '!=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Logo Image', 'styler' ),
                'subtitle' => esc_html__( 'Upload your Logo. If left blank theme will use site default logo.', 'styler' ),
                'customizer' => true,
                'id' => 'img_logo',
                'type' => 'media',
                'url' => true,
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Logo Size', 'styler' ),
                'subtitle' => esc_html__( 'Set the logo max-width of the image.', 'styler' ),
                'customizer' => true,
                'id' => 'logo_size',
                'type' => 'slider',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 400,
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' ),
                    array( 'logo_type', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Logo', 'styler' ),
                'subtitle' => esc_html__( 'Upload your Logo. If left blank theme will use site default logo.', 'styler' ),
                'customizer' => true,
                'id' => 'sticky_logo',
                'type' => 'media',
                'url' => true,
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Logo Size', 'styler' ),
                'subtitle' => esc_html__( 'Set the logo max-width of the image.', 'styler' ),
                'customizer' => true,
                'id' => 'sticky_logo_size',
                'type' => 'slider',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 400,
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Mobile Menu Logo', 'styler' ),
                'subtitle' => esc_html__( 'Upload your Logo. If left blank theme will use site default logo.', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_logo',
                'type' => 'media',
                'url' => true,
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Mobile Logo Size', 'styler' ),
                'subtitle' => esc_html__( 'Set the logo max-width of the image.', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_logo_size',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 400,
                'type' => 'slider',
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' ),
                    array( 'logo_type', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Logo Size', 'styler' ),
                'subtitle' => esc_html__( 'Set the logo max-width of the image.', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_logo_size',
                'default' => 80,
                'min' => 0,
                'step' => 1,
                'max' => 400,
                'type' => 'slider',
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' ),
                    array( 'logo_type', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Logo Left Offset', 'styler' ),
                'subtitle' => esc_html__( 'Set the logo max-width of the image.', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_logo_left_offset',
                'default' => '',
                'min' => -200,
                'step' => 1,
                'max' => 400,
                'type' => 'slider',
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' ),
                    array( 'logo_type', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Logo Padding', 'styler' ),
                'customizer' => true,
                'id' => 'text_logo_pad',
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'units' => array( 'em', 'px', '%' ),
                'units_extended' => 'true',
                'output' => array( '.nt-logo' ),
                'required' => array( 'logo_visibility', '=', '1' )
            )
    )));

    /*************************************************
    ## HEADER & NAV SECTION
    *************************************************/
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Header', 'styler' ),
        'id' => 'headersection',
        'icon' => 'fa fa-bars',
    ));
    //HEADER MENU
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'General', 'styler' ),
        'id' => 'headernavgeneralsection',
        'subsection' => true,
        'icon' => 'fa fa-cog',
        'fields' => array(
            array(
                'title' => esc_html__( 'Header Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site navigation.', 'styler' ),
                'customizer' => true,
                'id' => 'header_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'id' =>'header_template',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Template', 'styler' ),
                'subtitle' => esc_html__( 'Select your header template.', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Deafult Site Header', 'styler' ),
                    'elementor' => esc_html__( 'Elementor Templates', 'styler' ),
                    'sidebar' => esc_html__( 'Sidebar Header', 'styler' ),
                ),
                'default' => 'default',
                'required' => array( 'header_visibility', '=', '1' )
            ),
            array(
                'id' =>'sidebar_header_color',
                'type' => 'button_set',
                'title' => esc_html__( 'Sidebar Header Color Type', 'styler' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'light' => esc_html__( 'Light', 'styler' ),
                    'dark' => esc_html__( 'Dark', 'styler' ),
                ),
                'default' => 'light',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'sidebar' )
                )
            ),
            array(
                'id' =>'sidebar_header_position',
                'type' => 'button_set',
                'title' => esc_html__( 'Sidebar Header Position', 'styler' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'left' => esc_html__( 'Left', 'styler' ),
                    'right' => esc_html__( 'Right', 'styler' ),
                ),
                'default' => 'left',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'sidebar' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Header Display', 'styler' ),
                'customizer' => true,
                'id' => 'header_sticky_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'styler' ),
                'customizer' => true,
                'id' => 'header_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'elementor' )
                )
            ),
            array(
                'id' =>'edit_header_elementor_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'elementor' ),
                    array( 'header_elementor_templates', '!=', '' )
                )
            ),
            array(
                'id' => 'header_top_start',
                'type' => 'section',
                'title' => esc_html__('Header Main Options', 'styler'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'id' =>'header_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Header Layout Manager', 'styler' ),
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
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Toggle Bar Menu Title', 'styler' ),
                'customizer' => true,
                'id' => 'menu_title_visibility',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Title', 'styler' ),
                'customizer' => true,
                'id' => 'menu_title',
                'type' => 'text',
                'default' => 'Menu',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' ),
                    array( 'menu_title_visibility', '=', '1' ),
                )
            ),
            array(
                'title' => esc_html__( 'Phone Number Custom HTML', 'styler' ),
                'subtitle' => esc_html__( 'Add your custom html here.', 'styler' ),
                'customizer' => true,
                'id' => 'header_custom_html',
                'type' => 'textarea',
                'default' => '<a href="tel:280 900 3434"><i aria-hidden="true" class="styler-icons flaticon-24-hours-support"></i><span>280 900 3434<span class="phone-text">Call Anytime</span></span></a>',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Height', 'styler' ),
                'subtitle' => esc_html__( 'You can use this option to control the header height.', 'styler' ),
                'customizer' => true,
                'id' => 'header_height',
                'type' => 'slider',
                'default' => 80,
                'min' => 0,
                'step' => 1,
                'max' => 500,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'header_width',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Container Width', 'styler' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Deafult', 'styler' ),
                    'stretch' => esc_html__( 'Stretch', 'styler' )
                ),
                'default' => 'default',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Left Items Spacing', 'styler' ),
                'subtitle' => esc_html__( 'You can use this option to adjust the spacing between header left items.', 'styler' ),
                'customizer' => true,
                'id' => 'header_left_item_spacing',
                'type' => 'slider',
                'default' => 20,
                'min' => 0,
                'step' => 1,
                'max' => 50,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Right Items Spacing', 'styler' ),
                'subtitle' => esc_html__( 'You can use this option to adjust the spacing between header right items.', 'styler' ),
                'customizer' => true,
                'id' => 'header_right_item_spacing',
                'type' => 'slider',
                'default' => 15,
                'min' => 0,
                'step' => 1,
                'max' => 50,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'header_buttons_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Header Buttons Manager', 'styler' ),
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
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Header Search Display', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_header_search_visibility',
                'type' => 'switch',
                'default' => 1,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'sidebar' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Buttons Spacing', 'styler' ),
                'subtitle' => esc_html__( 'You can use this option to adjust the spacing between header buttons.', 'styler' ),
                'customizer' => true,
                'id' => 'header_buttons_spacing',
                'type' => 'slider',
                'default' => 15,
                'min' => 0,
                'step' => 1,
                'max' => 50,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' => 'header_top_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            // DEFAULT HEADER OPTIONS
            array(
                'id' => 'header_menu_items_customize_start',
                'type' => 'section',
                'title' => esc_html__('Header Customize Options', 'styler'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'id' =>'header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( General )', 'styler' ),
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
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'archive_cat_header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( Archive Category Page )', 'styler' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'general' => esc_html__( 'General', 'styler' ),
                    'default' => esc_html__( 'Deafult', 'styler' ),
                    'dark' => esc_html__( 'Dark', 'styler' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'styler' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'styler' )
                ),
                'default' => 'general',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'archive_tag_header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( Archive Tag Page )', 'styler' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'general' => esc_html__( 'General', 'styler' ),
                    'default' => esc_html__( 'Deafult', 'styler' ),
                    'dark' => esc_html__( 'Dark', 'styler' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'styler' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'styler' )
                ),
                'default' => 'general',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'single_post_header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( Single Post Page )', 'styler' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'general' => esc_html__( 'General', 'styler' ),
                    'default' => esc_html__( 'Deafult', 'styler' ),
                    'dark' => esc_html__( 'Dark', 'styler' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'styler' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'styler' )
                ),
                'default' => 'general',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'header_bg',
                'type' => 'color_rgba',
                'mode' => 'background-color',
                'output' => array( 'header.styler-header-default, .has-header-sidebar .styler-main-sidebar-header, .has-header-sidebar .styler-main-sidebar-header.styler-active' ),
            ),
            array(
                'title' => esc_html__( 'Sidebar Header Border Right Color', 'styler' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'styler' ),
                'customizer' => true,
                'id' => 'nav_submenu_bg',
                'type' => 'color',
                'mode' => 'border-right-color',
                'validate' => 'color',
                'output' => array( '.has-header-sidebar .styler-main-sidebar-header, .has-header-sidebar .styler-main-sidebar-header.styler-active' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'sidebar' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Typography', 'styler' ),
                'id' => 'header_menuitem_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.styler-header-top-menu-area>ul>li.menu-item>a,.has-header-sidebar .header-text-logo, .has-header-sidebar .styler-main-sidebar-header .primary-menu > li > a' ),
                'default' => array(
                    'color' => '',
                    'font-style' => '',
                    'font-family' => '',
                    'google' => true,
                    'font-size' => '',
                    'line-height' => ''
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color', 'styler' ),
                'desc' => esc_html__( 'Set your own color for the navigation menu item.', 'styler' ),
                'customizer' => true,
                'id' => 'nav_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.styler-header-top-menu-area>ul>li.menu-item>a,.has-header-sidebar .header-text-logo, .has-header-sidebar .styler-main-sidebar-header .primary-menu > li > a, .has-header-sidebar .styler-main-sidebar-header .submenu > li > a, .has-header-sidebar .sliding-menu .sliding-menu-inner li a, .has-header-sidebar .sliding-menu li .sliding-menu__nav, .has-header-sidebar .styler-main-sidebar-header .styler-svg-icon' ),
            ),
            array(
                'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'styler' ),
                'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'styler' ),
                'customizer' => true,
                'id' => 'nav_hvr_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.current-menu-parent>a, .current-menu-item>a, .styler-header-top-menu-area>ul>li.menu-item>a:hover, .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.styler-header-top-menu-area>ul>li.menu-item.active>a, .styler-header-top-menu-area ul li .submenu>li.menu-item.active>a,.has-header-sidebar .header-text-logo:hover, .has-header-sidebar .styler-main-sidebar-header .primary-menu > li > a:hover, .has-header-sidebar .styler-main-sidebar-header .primary-menu > li.styler-active > a, .has-header-sidebar .styler-main-sidebar-header .submenu > li > a:hover, .has-header-sidebar .styler-main-sidebar-header .submenu > li.styler-active > a, .has-header-sidebar .sliding-menu .sliding-menu-inner li a:hover, .has-header-sidebar .sliding-menu li .sliding-menu__nav:hover' ),
            ),
            array(
                'title' => esc_html__( 'Sticky Header Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'nav_top_sticky_bg',
                'type' => 'color_rgba',
                'mode' => 'background-color',
                'output' => array( '.has-sticky-header.scroll-start header.styler-header-default' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Typography', 'styler' ),
                'id' => 'header_sticky_menuitem_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.has-sticky-header.scroll-start header.styler-header-default .styler-header-top-menu-area>ul>li.menu-item>a' ),
                'default' => array(
                    'color' => '',
                    'font-style' => '',
                    'font-family' => '',
                    'google' => true,
                    'font-size' => '',
                    'line-height' => ''
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Menu Item Color', 'styler' ),
                'desc' => esc_html__( 'Set your own color for the sticky navigation menu item.', 'styler' ),
                'customizer' => true,
                'id' => 'sticky_nav_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.has-sticky-header.scroll-start .styler-header-top-menu-area>ul>li.menu-item>a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Menu Item Color ( Hover and Active )', 'styler' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'styler' ),
                'customizer' => true,
                'id' => 'sticky_nav_hvr_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.has-sticky-header.scroll-start .current-menu-parent>a, .has-sticky-header.scroll-start .current-menu-item>a, .has-sticky-header.scroll-start .styler-header-top-menu-area>ul>li.menu-item>a:hover, .has-sticky-header.scroll-start .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.has-sticky-header.scroll-start .styler-header-top-menu-area>ul>li.menu-item.active>a, .has-sticky-header.scroll-start .styler-header-top-menu-area ul li .submenu>li.menu-item.active>a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' => 'header_menu_items_style_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'id' => 'header_submenumenu_items_style_end',
                'type' => 'section',
                'title' => esc_html__('Header Sub Menu Customize Options', 'styler'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Sub Menu Background Color', 'styler' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'styler' ),
                'customizer' => true,
                'id' => 'nav_submenu_bg',
                'type' => 'color',
                'mode' => 'background-color',
                'validate' => 'color',
                'output' => array( '.styler-header-top-menu-area ul li .submenu' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Typography', 'styler' ),
                'id' => 'header_submenu_item_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.styler-header-top-menu-area ul li .submenu>li.menu-item>a,.has-header-sidebar .styler-main-sidebar-header .submenu > li > a' ),
                'default' => array(
                    'color' => '',
                    'font-style' => '',
                    'font-family' => '',
                    'google' => true,
                    'font-size' => '',
                    'line-height' => ''
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color', 'styler' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'styler' ),
                'customizer' => true,
                'id' => 'nav_submenu_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.styler-header-top-menu-area ul li .submenu>li.menu-item>a,.has-header-sidebar .styler-main-sidebar-header .submenu > li > a' ),
            ),
            array(
                'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'styler' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'styler' ),
                'customizer' => true,
                'id' => 'nav_submenu_hvr_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.styler-header-top-menu-area ul li .submenu>li.menu-item.active>a,.has-header-sidebar .styler-main-sidebar-header .submenu > li > a,.has-header-sidebar .styler-main-sidebar-header .submenu > li.active > a' ),
            ),
            array(
                'id' => 'header_submenu_items_style_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'id' => 'header_svgbuttons_items_style_start',
                'type' => 'section',
                'title' => esc_html__('Header SVG Buttons Customize Options', 'styler'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'SVG Icon Color', 'styler' ),
                'desc' => esc_html__( 'Cart, Wishlist, Compare, Account, Search, Sidemenu bar', 'styler' ),
                'customizer' => true,
                'id' => 'header_buttons_svg_color',
                'type' => 'color',
                'mode' => 'fill',
                'validate' => 'color',
                'output' => array( '.styler-header-default .top-action-btn .styler-svg-icon,.has-header-sidebar .styler-main-sidebar-header .styler-svg-icon' ),
            ),
            array(
                'title' => esc_html__( 'Button Counter Background Color', 'styler' ),
                'desc' => esc_html__( 'Cart, Wishlist, Compare', 'styler' ),
                'customizer' => true,
                'id' => 'header_buttons_counter_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'validate' => 'color',
                'output' => array( '.styler-header-default .styler-wc-count, .has-header-sidebar .styler-main-sidebar-header .styler-wc-count' ),
            ),
            array(
                'title' => esc_html__( 'Button Counter Color', 'styler' ),
                'desc' => esc_html__( 'Cart, Wishlist, Compare', 'styler' ),
                'customizer' => true,
                'id' => 'header_buttons_counter_color',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.styler-header-default .styler-wc-count, .has-header-sidebar .styler-main-sidebar-header .styler-wc-count' ),
            ),
            array(
                'id' => 'header_svgbuttons_items_style_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            //information on-off
            array(
                'id' =>'info_nav0',
                'type' => 'info',
                'style' => 'success',
                'title' => esc_html__( 'Success!', 'styler' ),
                'icon' => 'el el-info-circle',
                'customizer' => true,
                'desc' => sprintf(esc_html__( '%s is disabled on the site. Please activate to view options.', 'styler' ), '<b>Header</b>' ),
                'required' => array( 'header_visibility', '=', '0' )
            )
        )
    ));
    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Mobile Top Header', 'styler' ),
        'id' => 'headermobilesection',
        'subsection' => true,
        'icon' => 'fa fa-cog',
        'fields' => array(
            array(
                'id' => 'mobile_header_start',
                'type' => 'section',
                'title' => esc_html__('Mobile Top Header Options', 'styler'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Mobile Header Breakpoint ( px )', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_header_breakpoint',
                'type' => 'slider',
                'default' => 1280,
                'min' => 768,
                'step' => 1,
                'max' => 2000,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'id' =>'mobile_header_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Mobile Top Header Layouts Manager', 'styler' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme mobile header', 'styler' ),
                'options' => array(
                    'show'  => array(
                        'toggle' => esc_html__( 'Toggle Button', 'styler' ),
                        'logo' => esc_html__( 'Logo', 'styler' ),
                        'buttons' => esc_html__( 'Buttons', 'styler' )
                    ),
                    'hide'  => array(
                    )
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'mobile_header_buttons_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Mobile Header Buttons Layouts Manager', 'styler' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme mobile header', 'styler' ),
                'options' => array(
                    'show'  => array(
                        'cart' => esc_html__( 'Cart', 'styler' ),
                    ),
                    'hide'  => array(
                        'account' => esc_html__( 'Account', 'styler' ),
                        'search' => esc_html__( 'Search Form', 'styler' )
                    )
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_header_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'validate' => 'color',
                'output' => array( '.styler-header-mobile-top' ),
            ),
            array(
                'title' => esc_html__( 'Mobile Menu Trigger Bar Color', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_header_trigger_textlogo_color',
                'type' => 'color',
                'mode' => 'color',
                'validate' => 'color',
                'output' => array( '.styler-header-mobile-top .nav-logo .header-text-logo' ),
            ),
            array(
                'title' => esc_html__( 'Mobile Menu Trigger Bar Color', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_header_trigger_menubar_color',
                'type' => 'color',
                'mode' => 'color',
                'validate' => 'color',
                'output' => array( '.styler-header-mobile-top .mobile-toggle' ),
            ),
            array(
                'title' => esc_html__( 'Button Icon Color', 'styler' ),
                'desc' => esc_html__( 'Cart, Account, Search', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_header_buttons_color',
                'type' => 'color',
                'mode' => 'fill',
                'validate' => 'color',
                'output' => array( '.styler-header-mobile-top .styler-svg-icon' ),
            ),
            array(
                'title' => esc_html__( 'Button Counter Background Color', 'styler' ),
                'desc' => esc_html__( 'Cart', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_header_buttons_counter_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'validate' => 'color',
                'output' => array( '.styler-header-mobile-top .top-action-btn .styler-wc-count' ),
            ),
            array(
                'title' => esc_html__( 'Button Counter Number Color', 'styler' ),
                'desc' => esc_html__( 'Cart', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_header_buttons_counter_color',
                'type' => 'color',
                'mode' => 'color',
                'validate' => 'color',
                'output' => array( '.styler-header-mobile-top .top-action-btn .styler-wc-count' ),
            ),
            array(
                'id' => 'mobile_header_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            )
    )));
    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Sidebar Panel', 'styler' ),
        'id' => 'headermobilesidebarsection',
        'subsection' => true,
        'icon' => 'fa fa-cog',
        'fields' => array(
            array(
                'id' => 'sidebar_menu_start',
                'type' => 'section',
                'title' => esc_html__('Sidebar Panel Options', 'styler'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'sidebar_menu_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Minibar Layouts Manager', 'styler' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme mobile header sidebar', 'styler' ),
                'options' => array(
                    'show'  => array(
                        'buttons' => esc_html__( 'Buttons', 'styler' ),
                        'logo' => esc_html__( 'Logo', 'styler' ),
                        'socials' => esc_html__( 'Socials', 'styler' )
                    ),
                    'hide'  => array(
                    )
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'sidebar_menu_buttons_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Minibar Buttons Layouts Manager', 'styler' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme mobile header', 'styler' ),
                'options' => array(
                    'show'  => array(
                        'cart' => esc_html__( 'Cart', 'styler' ),
                        'wishlist' => esc_html__( 'Wishlist', 'styler' ),
                        'compare' => esc_html__( 'Compare', 'styler' ),
                        'search' => esc_html__( 'Search Category', 'styler' ),
                        'contact' => esc_html__( 'Contact Form', 'styler' ),
                        'account' => esc_html__( 'Account', 'styler' ),
                        'socials' => esc_html__( 'Socials', 'styler' )
                    ),
                    'hide'  => array(
                    )
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Show Menu Bar Button', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_header_menubar_visibility',
                'type' => 'switch',
                'default' => 0,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Menu Social Icons', 'styler' ),
                'subtitle' => esc_html__( 'Add your social links here.', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_socials',
                'type' => 'textarea',
                'default' => '<a href="#0" title="facebook"><i class="fab fa-facebook"></i></a>
<a href="#0" title="twitter"><i class="fab fa-twitter"></i></a>
<a href="#0" title="instagram"><i class="fab fa-instagram"></i></a>
<a href="#0" title="youtube"><i class="fab fa-youtube"></i></a>',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Menu Container Max Width ( px )', 'styler' ),
                'subtitle' => esc_html__( 'You can control sidebar menu content width.', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_content_width',
                'type' => 'slider',
                'default' => 530,
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_bg',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( 'body .styler-header-mobile, .styler-header-mobile .action-content' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' => 'sidebar_menu_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
    )));

    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Sidebar Panel Minibar', 'styler' ),
        'id' => 'headermobilesidebarminibarsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Sidebar Menu Minibar Min Width ( px )', 'styler' ),
                'subtitle' => esc_html__( 'You can control sidebar menu bar width.', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_width',
                'type' => 'slider',
                'default' => 80,
                'min' => 0,
                'step' => 1,
                'max' => 300,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_bg',
                'type' => 'color_rgba',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-sidebar' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Close Icon Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_close_icon_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile .styler-panel-close-button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Close Icon Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_close_icon_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile .styler-panel-close-button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar SVG Icon Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_svg_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array( '.styler-header-mobile-sidebar .styler-svg-icon' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Active SVG Icon Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_active_svg_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array( '.styler-header-mobile-sidebar .sidebar-top-action .top-action-btn.active .styler-svg-icon' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Active SVG Icon Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_active_svg_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-sidebar .sidebar-top-action .top-action-btn.active .styler-svg-icon' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Counter Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_counter_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-sidebar .sidebar-top-action .top-action-btn .styler-wc-count' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Counter Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_counter_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-sidebar .sidebar-top-action .top-action-btn .styler-wc-count' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Text Logo Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_textlogo_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-sidebar .sidebar-top-action .top-action-btn.active .styler-svg-icon' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Social Icon Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_social_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-sidebar .styler-header-mobile-sidebar-bottom a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Social Icon Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_social_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-sidebar .styler-header-mobile-sidebar-bottom a:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            )
    )));

    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Sidebar Panel Search', 'styler' ),
        'id' => 'headermobilesidebarsearchsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Search Placeholder Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_placeholder_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-slide-menu .search-area-top input::-webkit-input-placeholder' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-slide-menu .search-area-top input' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-slide-menu .search-area-top input' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Icon Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_icon_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array( '.styler-header-slide-menu .search-area-top svg' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Border Bottom Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_brdcolor',
                'type' => 'color',
                'mode' => 'border-bottom-color',
                'output' => array( '.styler-header-slide-menu .search-area-top' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Result Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_result_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-slide-menu .styler-asform-container .autocomplete-suggestions' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Result Item Price Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_result_item_price_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-slide-menu .styler-asform-container .autocomplete-suggestion .woocommerce-variation-price .price, .styler-header-slide-menu .styler-asform-container .autocomplete-suggestion .styler-price' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            )
    )));

    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Sidebar Panel Sliding Menu', 'styler' ),
        'id' => 'headermobilesidebarslidingmenusubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Sliding Menu Item Typography', 'styler' ),
                'id' => 'sidebar_panel_menuitem_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.sliding-menu .sliding-menu-inner li a, .sliding-menu li .sliding-menu__nav,.sliding-menu .sliding-menu__nav:before' ),
                'default' => array(
                    'color' => '',
                    'font-style' => '',
                    'font-family' => '',
                    'google' => true,
                    'font-size' => '',
                    'line-height' => ''
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Sliding Menu Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menuitem_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .sliding-menu' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Menu Item Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menuitem_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.sliding-menu .sliding-menu-inner li a, .sliding-menu li .sliding-menu__nav,.sliding-menu .sliding-menu__nav:before' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Menu Item Color ( Hover/Active )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menuitem_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.sliding-menu li.current-menu-parent>.sliding-menu__nav, .sliding-menu li.current-menu-item>.sliding-menu__nav, .sliding-menu li.current-menu-item>a, .sliding-menu li a:hover, .sliding-menu li.active a, .sliding-menu li .sliding-menu__nav:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Submenu Back Title Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_submenu_back_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.sliding-menu li .sliding-menu__nav.sliding-menu__back' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Submenu Back Title Border Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_submenu_back_title_brdcolor',
                'type' => 'color',
                'mode' => 'border-bottom-color',
                'output' => array( '.sliding-menu .sliding-menu__back:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Submenu Item Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_submenuitem_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.sliding-menu .sliding-menu-inner ul li a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Submenu Item Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_submenuitem_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.sliding-menu .sliding-menu-inner ul li a:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Text', 'styler' ),
                'subtitle' => esc_html__( 'Add your site copyright here.', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_copyright',
                'type' => 'textarea',
                'default' => sprintf( '<p>&copy; %1$s, <a class="theme" href="%2$s">%3$s</a> Theme. %4$s <a class="dev" href="https://ninetheme.com/contact/">%5$s</a></p>',
                    date( 'Y' ),
                    esc_url( home_url( '/' ) ),
                    get_bloginfo( 'name' ),
                    esc_html__( 'Made with passion by', 'styler' ),
                    esc_html__( 'Ninetheme.', 'styler' )
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Text Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menu_copy_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-sidemenu-copyright,.styler-sidemenu-copyright p' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Link Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menu_copy_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-sidemenu-copyright a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Link Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menu_copy_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-sidemenu-copyright a:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Menu Language Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the sidebar language switcher if you have.', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_lang_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
    )));

    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Sidebar Panel WooCommerce', 'styler' ),
        'desc' => esc_html__( 'Cart,Wishlist,Compare,Categories', 'styler' ),
        'id' => 'headersidebarpanelcartsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Cart Panel Title', 'styler' ),
                'subtitle' => esc_html__( 'You can change cart panel title if you want', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_custom_title',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Wishlist Panel Title', 'styler' ),
                'subtitle' => esc_html__( 'You can change wishlist panel title if you want', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_wishlist_custom_title',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Compare Panel Title', 'styler' ),
                'subtitle' => esc_html__( 'You can change compare panel title if you want', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_compare_custom_title',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Categories Panel Title', 'styler' ),
                'subtitle' => esc_html__( 'You can change categories panel title if you want', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_categories_custom_title',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .panel-top-title, .styler-side-panel .panel-top-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Border Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_title_brdcolor',
                'type' => 'color',
                'mode' => 'border-bottom-color',
                'output' => array( '.styler-header-mobile-content .panel-top-title:after, .styler-side-panel .panel-top-title:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Item Title Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .cart-name, .styler-side-panel .cart-name' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Item Price Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_price_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .styler-price, .styler-side-panel .styler-price' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Item Quantity Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_qty_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .quantity, .styler-side-panel .quantity' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Item Quantity Plus Minus Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_qty_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .quantity-button.plus,.styler-header-mobile-content .quantity-button.minus,.styler-header-mobile-content .input-text::-webkit-input-placeholder,.styler-header-mobile-content .input-text,.styler-side-panel .quantity-button.plus,.styler-side-panel .quantity-button.minus,.styler-side-panel .input-text::-webkit-input-placeholder,.styler-side-panel .input-text'),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Item Quantity Plus Minus Backgroud Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_qty_hvrbgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .quantity-button.plus:hover,.styler-header-mobile-content .quantity-button.minus:hover,.styler-side-panel .quantity-button.plus:hover,.styler-side-panel .quantity-button.minus:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Wishlist,Compare Add to Cart Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_addtocart_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .styler-content-info .add_to_cart_button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Wishlist,Compare Add to Cart Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_addtocart_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .styler-content-info .add_to_cart_button:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Wishlist,Compare Stock Status Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_addtocart_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .styler-content-info .product-stock' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Subtotal Border Top Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_subtotal_brdcolor',
                'type' => 'color',
                'mode' => 'border-top-color',
                'output' => array( '.styler-header-mobile-content .cart-total-price,.styler-side-panel .cart-total-price' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Subtotal Title Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_subtotal_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .cart-total-price,.styler-side-panel .cart-total-price' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Subtotal Price Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_subtotal_price_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .cart-total-price .cart-total-price-right,.styler-side-panel .cart-total-price .cart-total-price-right' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Delete Icon Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_delete_icon_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array( '.styler-header-mobile-content .del-icon a svg,.styler-side-panel .del-icon a svg' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Free Shipping Text Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_extra_text_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .minicart-extra-text,.styler-side-panel .minicart-extra-text' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Buttons Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_buttons_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .cart-bottom-btn .styler-btn,.styler-side-panel .cart-bottom-btn .styler-btn' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Buttons Background Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_buttons_hvrbgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .cart-bottom-btn .styler-btn:hover,.styler-side-panel .cart-bottom-btn .styler-btn:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Buttons Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_buttons_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .cart-bottom-btn .styler-btn,.styler-side-panel .cart-bottom-btn .styler-btn' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Buttons Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_buttons_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .cart-bottom-btn .styler-btn:hover,.styler-side-panel .cart-bottom-btn .styler-btn:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Empty Cart Icon Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_empty_svg_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array( '.styler-header-mobile-content svg,.styler-side-panel .panel-content svg' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Empty Cart Text Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_empty_text_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .cart-empty-content .styler-small-title,.styler-side-panel  .styler-small-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
    )));
    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Sidebar Panel Contact Form', 'styler' ),
        'id' => 'headersidebarpanelcontactsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Contact Form 7', 'styler' ),
                'subtitle' => esc_html__( 'Select a form from the list.', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_cf7',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $wpcf7_args,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Contact Form Shotcode', 'styler' ),
                'subtitle' => esc_html__( 'Add your shortcode here, if you want to use different contact form instead of Contact Form 7.', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_menu_custom_form',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Panel Title', 'styler' ),
                'subtitle' => esc_html__( 'You can change contact form panel title if you want', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_custom_title',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .contact-area .panel-top-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Border Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_title_brdcolor',
                'type' => 'color',
                'mode' => 'border-bottom-color',
                'output' => array( '.styler-header-mobile-content .contact-area .panel-top-title:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Placeholder Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_placeholder_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array('.styler-header-mobile-content .contact-area .wpcf7-form-control::-webkit-input-placeholder'),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_input_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .contact-area input:not([type="submit"]),.styler-header-mobile-content .contact-area textarea,.styler-header-mobile-content .contact-area select' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_input_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .contact-area input:not([type="submit"]),.styler-header-mobile-content .contact-area textarea,.styler-header-mobile-content .contact-area select' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Background Color ( Focus )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_input_focus_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .contact-area input:not([type="submit"]):focus,.styler-header-mobile-content .contact-area textarea:focus,.styler-header-mobile-content .contact-area select:focus' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Border', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_input_brdcolor',
                'type' => 'border',
                'output' => array( '.styler-header-mobile-content .contact-area input:not([type="submit"]),.styler-header-mobile-content .contact-area textarea,.styler-header-mobile-content .contact-area select' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Border ( Focus )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_input_focus_brdcolor',
                'type' => 'border',
                'output' => array( '.styler-header-mobile-content .contact-area input:not([type="submit"]):focus,.styler-header-mobile-content .contact-area textarea:focus,.styler-header-mobile-content .contact-area select:focus' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .contact-area input[type="submit"],.styler-header-mobile-content .contact-area .wpcf7-submit' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Background Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_hvrbgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .contact-area input[type="submit"]:hover,.styler-header-mobile-content .contact-area .wpcf7-submit:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .contact-area input[type="submit"],.styler-header-mobile-content .contact-area .wpcf7-submit' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .contact-area input[type="submit"]:hover,.styler-header-mobile-content .contact-area .wpcf7-submit:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Border', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_brdcolor',
                'type' => 'border',
                'output' => array( '.styler-header-mobile-content .contact-area input[type="submit"],.styler-header-mobile-content .contact-area .wpcf7-submit' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Border ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_hvrbrdcolor',
                'type' => 'border',
                'output' => array( '.styler-header-mobile-content .contact-area input[type="submit"]:hover,.styler-header-mobile-content .contact-area .wpcf7-submit:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Element Label Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_label_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .contact-area label,.styler-header-mobile-content .contact-area .label' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Contact Details Heading Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_h_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .contact-area h2,.styler-header-mobile-content .contact-area h3,.styler-header-mobile-content .contact-area h4,.styler-header-mobile-content .contact-area h5,.styler-header-mobile-content .contact-area h6, .styler-header-mobile-content .contact-area .styler-meta-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Contact Details Text Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_p_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .contact-area div,.styler-header-mobile-content .contact-area p' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
    )));
    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Sidebar Panel Account', 'styler' ),
        'id' => 'headersidebarpanelaccountsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'id' => 'sidebar_account_logout_start',
                'title' => esc_html__( 'Log Out Options', 'styler' ),
                'type' => 'section',
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .account-area .panel-top-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Icon Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_title_icon_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .account-area .form-action-btn svg' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Border Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_title_brdcolor',
                'type' => 'color',
                'mode' => 'border-bottom-color',
                'output' => array( '.styler-header-mobile-content .account-area .panel-top-title:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Placeholder Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_placeholder_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array('.woocommerce .styler-header-mobile-content .account-area form .form-row input.input-text::-webkit-input-placeholder'),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_input_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.woocommerce .styler-header-mobile-content .account-area form .form-row input.input-text' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_input_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.woocommerce .styler-header-mobile-content .account-area form .form-row input.input-text' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Background Color ( Focus )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_input_focus_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.woocommerce .styler-header-mobile-content .account-area form .form-row input.input-text:focus' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Border', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_input_brdcolor',
                'type' => 'border',
                'output' => array( '.woocommerce .styler-header-mobile-content .account-area form .form-row input.input-text' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Border ( Focus )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_input_focus_brdcolor',
                'type' => 'border',
                'output' => array( '.woocommerce .styler-header-mobile-content .account-area form .form-row input.input-text:focus' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Checkbox Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_checkbox_bgcolor',
                'type' => 'border',
                'output' => array( '.styler-header-mobile-content .account-area input[type="checkbox"]:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Checkbox Background Color ( Checked )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_checkbox_actbgcolor',
                'type' => 'border',
                'output' => array( '.styler-header-mobile-content .account-area input[type="checkbox"]:checked:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .account-area button[type="submit"],.woocommerce-page .styler-header-mobile-content .account-area button.button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Background Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_hvrbgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .account-area button[type="submit"]:hover,.woocommerce-page .styler-header-mobile-content .account-area button.button:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .account-area button[type="submit"],.woocommerce-page .styler-header-mobile-content .account-area button.button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .account-area button[type="submit"]:hover,.woocommerce-page .styler-header-mobile-content .account-area button.button:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Border', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_brdcolor',
                'type' => 'border',
                'output' => array( '.styler-header-mobile-content .account-area button[type="submit"],.woocommerce-page .styler-header-mobile-content .account-area button.button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Border ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_hvrbrdcolor',
                'type' => 'border',
                'output' => array( '.styler-header-mobile-content .account-area button[type="submit"]:hover,.woocommerce-page .styler-header-mobile-content .account-area button.button:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Element Label Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_label_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.woocommerce .styler-header-mobile-content .account-area form .form-row label' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Lost Password Text Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_p_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .account-area .lost_password a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' => 'sidebar_account_logout_end',
                'title' => esc_html__( 'Log In Options', 'styler' ),
                'type' => 'section',
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_menu_item_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .account-area li.menu-item a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_menu_item_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .account-area li.menu-item a:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Border Bootm Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_menu_item_brdcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .account-area li.menu-item a:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' => 'sidebar_account_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            )
    )));
    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Panel Categories', 'styler' ),
        'id' => 'headerleftpanelcategoriessubsection',
        'subsection' => true,
        'icon' => 'el el-cog',
        'fields' => array(
            array(
                'title' => esc_html__('Show Only Parent Categories', 'styler'),
                'subtitle' => esc_html__('Enable this option if you want to show only parent categories.', 'styler'),
                'customizer' => true,
                'id' => 'header_panel_only_cats_parents',
                'type' => 'switch',
                'default' => 0,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__('Hide Empty Categories', 'styler'),
                'customizer' => true,
                'id' => 'header_panel_cats_hide_empty',
                'type' => 'switch',
                'default' => 1,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__('Category Image Display', 'styler'),
                'customizer' => true,
                'id' => 'header_panel_cats_img_visibility',
                'type' => 'switch',
                'default' => 1,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'header_panel_cats_imgsize',
                'type' => 'button_set',
                'title' => esc_html__('Image Size', 'styler'),
                'options' => array(
                    'styler-panel' => esc_html__('Default (80x80) Cropped', 'styler'),
                    'thumbnail' => esc_html__('thumbnail', 'styler'),
                    'medium' => esc_html__('medium', 'styler'),
                    'large' => esc_html__('large', 'styler'),
                    'full' => esc_html__('full', 'styler')
                ),
                'default' => 'thumbnail',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' ),
                    array( 'header_panel_cats_img_visibility', '=', '1' ),
                )
            ),
            array(
                'title' => esc_html__('Category Count Display', 'styler'),
                'customizer' => true,
                'id' => 'header_panel_cats_count_visibility',
                'type' => 'switch',
                'default' => 1,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'header_panel_cats_column',
                'type' => 'button_set',
                'title' => esc_html__('Column', 'styler'),
                'options' => array(
                    '1' => esc_html__('1', 'styler'),
                    '2' => esc_html__('2', 'styler'),
                    '3' => esc_html__('3', 'styler'),
                    '4' => esc_html__('4', 'styler'),
                    '5' => esc_html__('5', 'styler')
                ),
                'default' => '3',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Category Item Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_categories_item_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .category-area .product-category' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Category Item Title Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_categories_item_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .category-area .category-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' ),
                    array( 'header_panel_cats_count_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Category Item Counter Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_categories_item_counter_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-header-mobile-content .category-area .cat-count' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' ),
                    array( 'header_panel_cats_count_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Category Item Counter Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'sidebar_panel_categories_item_counter_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-header-mobile-content .category-area .cat-count' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' ),
                    array( 'header_panel_cats_count_visibility', '=', '1' )
                )
            )
        )
    ));

    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Panel Checkout Status', 'styler' ),
        'id' => 'headerrightpanelcheckoutsubsection',
        'subsection' => true,
        'icon' => 'fa fa-cog',
        'fields' => array(
            array(
                'id' =>'panels_checkout_button_status',
                'type' => 'button_set',
                'title' => esc_html__( 'Left/Right Panel Checkout Button Click Action', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'pagelink' => esc_html__( 'Checkout page', 'styler' ),
                    'multisteps' => esc_html__( 'Multi-steps', 'styler' )
                ),
                'default' => 'pagelink'
            ),
            array(
                'title' => esc_html__( 'Step Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_step_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Background Color ( Success )', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_success_step_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step.success' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Background Color ( Error )', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_error_step_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step.has-error' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Text Color', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_step_text_color',
                'type' => 'color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Text Color ( Success )', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_success_step_text_color',
                'type' => 'color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step.success' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Text Color ( Error )', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_error_step_text_color',
                'type' => 'color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step.has-error' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Number Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_step_number_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step .number' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Number Background Color ( Success )', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_success_step_number_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step.success .number' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Number Background Color ( Error )', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_error_step_number_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step.has-error .number' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Number Color', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_step_number_color',
                'type' => 'color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step .number' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Number Color ( Success )', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_success_step_number_color',
                'type' => 'color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step.success .number' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            ),
            array(
                'title' => esc_html__( 'Step Number Color ( Error )', 'styler' ),
                'customizer' => true,
                'id' => 'checkout_iframe_error_step_number_color',
                'type' => 'color',
                'output' => array( '.styler-ajax-checkout-wrapper .steps .step.has-error .number' ),
                'required' => array( 'panels_checkout_button_status', '=', 'multisteps' )
            )
        )
    ));
    //HEADER MOBILE TOP
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Header Extra', 'styler' ),
        'id' => 'headerbottomsection',
        'subsection' => true,
        'icon' => 'fa fa-cog',
        'fields' => array(
            array(
                'title' => esc_html__( 'Before Header ( Elementor Templates )', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates for before header.', 'styler' ),
                'customizer' => true,
                'id' => 'before_header_template',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'edit_before_header_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'before_header_template', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'After Header ( Elementor Templates )', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates for after header.', 'styler' ),
                'customizer' => true,
                'id' => 'after_header_template',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'edit_after_header_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'after_header_template', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Bottom Bar Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site header bottom area.', 'styler' ),
                'customizer' => true,
                'id' => 'header_bottom_area_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array( 'header_visibility', '=', '1' )
            ),
            array(
                'id' =>'header_bottom_area_display_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Bottom Bar Display Type', 'styler' ),
                'subtitle' => esc_html__( 'Select your header template.', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'show-always' => esc_html__( 'Show Always', 'styler' ),
                    'show-on-scroll' => esc_html__( 'Show on Scroll', 'styler' )
                ),
                'default' => 'show-on-scroll',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_bottom_area_visibility', '=', '1' )
                )
            ),
            array(
                'id' =>'header_bottom_area_template_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Bottom Bar Template Type', 'styler' ),
                'subtitle' => esc_html__( 'Select your template type.', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'filters' => esc_html__( 'Shop Filters', 'styler' ),
                    'elementor' => esc_html__( 'Elementor Template', 'styler' )
                ),
                'default' => 'filters',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_bottom_area_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Bottom Bar Elementor Templates', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates for before header bottom area.', 'styler' ),
                'customizer' => true,
                'id' => 'header_bottom_bar_template',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_bottom_area_visibility', '=', '1' ),
                    array( 'header_bottom_area_template_type', '=', 'elementor' )
                )
            ),
            array(
                'id' =>'edit_header_bottom_bar_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_bottom_area_visibility', '=', '1' ),
                    array( 'header_bottom_area_template_type', '=', 'elementor' ),
                    array( 'header_bottom_bar_template', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Cart Extra Text', 'styler' ),
                'subtitle' => esc_html__( 'Add your custom text to header cart before buttons.', 'styler' ),
                'customizer' => true,
                'id' => 'header_cart_before_buttons',
                'type' => 'text',
                'default' => ''
            ),
            array(
                'id' =>'header_myaccount_action_type',
                'type' => 'select',
                'title' => esc_html__( 'Header My Account Click Action', 'styler' ),
                'customizer' => true,
                'options' => array(
                    'page' => esc_html__( 'Redirect to Account Page', 'styler' ),
                    'panel' => esc_html__( 'Open in Left Panel', 'styler' ),
                    'popup' => esc_html__( 'Open in Popup', 'styler' )
                ),
                'default' => 'panel',
                'required' => array( 'header_visibility', '=', '1' )
            )
        )
    ));
    //FOOTER SECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Mobile Bottom Menu Bar', 'styler' ),
        'desc' => esc_html__( 'These are main settings for general theme!', 'styler' ),
        'id' => 'mobilebottommenusubsection',
        'subsection' => true,
        'icon' => 'el el-photo',
        'fields' => array(
            array(
                'title' => esc_html__( 'Mobile Bottom Menu Bar Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site mobile bottom menu bar.', 'styler' ),
                'customizer' => true,
                'id' => 'bottom_mobile_nav_visibility',
                'type' => 'switch',
                'default' => 1
            ),
            array(
                'title' => esc_html__( 'Mobile Bottom Menu Type', 'styler' ),
                'subtitle' => esc_html__( 'Select your mobile bottom menu popup search type.', 'styler' ),
                'customizer' => true,
                'id' => 'bottom_mobile_menu_type',
                'type' => 'button_set',
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Default', 'styler' ),
                    'wp-menu' => esc_html__( 'WP Menu', 'styler' ),
                    'elementor' => esc_html__( 'Elementor Template', 'styler' ),
                ),
                'default' => 'default',
                'required' => array( 'bottom_mobile_nav_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Mobile Bottom Menu Display Type', 'styler' ),
                'subtitle' => esc_html__( 'Select your mobile bottom menu popup search type.', 'styler' ),
                'customizer' => true,
                'id' => 'bottom_mobile_menu_display_type',
                'type' => 'button_set',
                'customizer' => true,
                'options' => array(
                    'show-allways' => esc_html__( 'Always show', 'styler' ),
                    'show-onscroll' => esc_html__( 'Show on scroll', 'styler' ),
                ),
                'default' => 'show-allways',
                'required' => array( 'bottom_mobile_nav_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'elementor' )
                )
            ),
            array(
                'id' =>'mobile_bottom_menu_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Layout Manager', 'styler' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme bottom mobile menu bar', 'styler' ),
                'options' => array(
                    'show' => array(
                        'home' => esc_html__( 'Home', 'styler' ),
                        'shop' => esc_html__( 'Shop', 'styler' ),
                        'cart' => esc_html__( 'Cart', 'styler' ),
                        'account' => esc_html__( 'Account', 'styler' ),
                        'search' => esc_html__( 'Search', 'styler' ),
                        'cats' => esc_html__( 'Categories', 'styler' ),
                    ),
                    'hide'  => array(
                    )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Link Type', 'styler' ),
                'customizer' => true,
                'id' => 'bottom_mobile_menu_cart_link_type',
                'type' => 'button_set',
                'customizer' => true,
                'options' => array(
                    'page' => esc_html__( 'Cart Page', 'styler' ),
                    'popup' => esc_html__( 'Popup', 'styler' ),
                ),
                'default' => 'page',
                'required' => array( 'bottom_mobile_nav_visibility', '=', '1' )
            ),
            array(
                'desc' => sprintf( '%s <b>"%s"</b> <a class="button" href="'.admin_url('nav-menus.php?action=edit&menu=0').'" target="_blank">%s</a>',
                    esc_html__( 'Please create new menu and assign it as', 'styler' ),
                    esc_html__( 'Mobile Bottom Menu', 'styler' ),
                    esc_html__( 'Create New Menu', 'styler' )
                ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_menu_info',
                'type' => 'info',
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'wp-menu' )
                )
            ),
            array(
                'title' => esc_html__( 'Change Default Menu Item HTML', 'styler' ),
                'subtitle' => esc_html__( 'You can change the site mobile bottom menu item html.', 'styler' ),
                'customizer' => true,
                'id' => 'bottom_mobile_nav_item_customize',
                'type' => 'switch',
                'default' => 0,
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Home HTML ( optional )', 'styler' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_home_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="%s">%s<span>Home</span></a></li>',
                    esc_url( home_url( '/' ) ),
                    styler_svg_lists( 'arrow-left', 'styler-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Shop HTML ( optional )', 'styler' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_shop_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="%s">%s<span>Shop</span></a></li>',
                    function_exists('wc_get_page_permalink') ? esc_url( wc_get_page_permalink( 'shop' ) ) : '#0',
                    styler_svg_lists( 'store', 'styler-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Cart HTML ( optional )', 'styler' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_cart_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="%s">%s<span class="styler-cart-count styler-wc-count"></span><span>Cart</span></a></li>',
                    function_exists('wc_get_page_permalink') ? esc_url( wc_get_page_permalink( 'cart' ) ) : '#0',
                    styler_svg_lists( 'bag', 'styler-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Account HTML ( optional )', 'styler' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_account_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="%s">%s<span>Account</span></a></li>',
                    function_exists('wc_get_page_permalink') ? esc_url( wc_get_page_permalink( 'myaccount' ) ) : '#0',
                    styler_svg_lists( 'user-1', 'styler-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Search HTML ( optional )', 'styler' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_search_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="#0" data-name="search-popup">%s<span>Search</span></a></li>',
                    styler_svg_lists( 'search', 'styler-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Categories HTML ( optional )', 'styler' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_cats_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="#0" data-name="search-cats">%s<span>Categories</span></a></li>',
                    styler_svg_lists( 'paper-search', 'styler-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Backgroud Color', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_bg_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-bottom-mobile-nav' ),
                'required' => array( 'bottom_mobile_nav_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-bottom-mobile-nav .menu-item a,.styler-bottom-mobile-nav .menu-item a span' ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-bottom-mobile-nav .menu-item a:hover,.styler-bottom-mobile-nav .menu-item a:hover span'),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'SVG Icon Color', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_icon_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array('.styler-bottom-mobile-nav .menu-item svg,.styler-bottom-mobile-nav .styler-svg-icon'),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'SVG Icon Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_icon_hvrcolor',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array('.styler-bottom-mobile-nav .menu-item a:hover svg,.styler-bottom-mobile-nav a:hover .styler-svg-icon'),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Font Icon Color', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_icon2_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array('.styler-bottom-mobile-nav a i,.styler-bottom-mobile-nav a span' ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Font Icon Color ( Hover )', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_icon2_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array('.styler-bottom-mobile-nav a:hover i,.styler-bottom-mobile-nav a:hover span' ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Count Background Color', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_cart_count_bg_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.styler-bottom-mobile-nav .menu-item a span.styler-wc-count, .styler-bottom-mobile-nav .styler-wc-count' ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Count Number Color', 'styler' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_cart_count_number_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.styler-bottom-mobile-nav .menu-item a span.styler-wc-count, .styler-bottom-mobile-nav .styler-wc-count' ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
    )));
    //FOOTER SECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Footer', 'styler' ),
        'desc' => esc_html__( 'These are main settings for general theme!', 'styler' ),
        'id' => 'footersection',
        'icon' => 'el el-photo',
        'fields' => array(
            array(
                'title' => esc_html__( 'Footer Section Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site footer copyright and footer widget area on the site with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'footer_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Footer Type', 'styler' ),
                'subtitle' => esc_html__( 'Select your footer type.', 'styler' ),
                'customizer' => true,
                'id' => 'footer_template',
                'type' => 'select',
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Deafult Site Footer', 'styler' ),
                    'elementor' => esc_html__( 'Elementor Templates', 'styler' )
                ),
                'default' => 'default',
                'required' => array( 'footer_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'styler' ),
                'customizer' => true,
                'id' => 'footer_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'elementor' )
                )
            ),
            array(
                'id' =>'edit_footer_template',
                'type' => 'info',
                'desc' => 'Edit template',
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'elementor' ),
                    array( 'footer_elementor_templates', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Text', 'styler' ),
                'subtitle' => esc_html__( 'HTML allowed (wp_kses)', 'styler' ),
                'desc' => esc_html__( 'Enter your site copyright text here.', 'styler' ),
                'customizer' => true,
                'id' => 'footer_copyright',
                'type' => 'textarea',
                'validate' => 'html',
                'default' => sprintf( '<p>&copy; %1$s, <a class="theme" href="%2$s">%3$s</a> Theme. %4$s <a class="dev" href="https://ninetheme.com/contact/">%5$s</a></p>',
                    date( 'Y' ),
                    esc_url( home_url( '/' ) ),
                    get_bloginfo( 'name' ),
                    esc_html__( 'Made with passion by', 'styler' ),
                    esc_html__( 'Ninetheme.', 'styler' )
                ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            //information on-off
            array(
                'id' =>'info_f0',
                'type' => 'info',
                'style' => 'success',
                'title' => esc_html__( 'Success!', 'styler' ),
                'icon' => 'el el-info-circle',
                'customizer' => true,
                'desc' => sprintf(esc_html__( '%s section is disabled on the site.Please activate to view subsection options.', 'styler' ), '<b>Site Main Footer</b>' ),
                'required' => array( 'footer_visibility', '=', '0' )
            )
    )));
    //FOOTER SECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Footer Style', 'styler' ),
        'desc' => esc_html__( 'These are main settings for general theme!', 'styler' ),
        'id' => 'footerstylesubsection',
        'icon' => 'el el-photo',
        'subsection' => true,
        'fields' => array(
            array(
                'id' =>'footer_color_customize',
                'type' => 'info',
                'icon' => 'el el-brush',
                'customizer' => false,
                'desc' => sprintf(esc_html__( '%s', 'styler' ), '<h2>Footer Color Customize</h2>' ),
                'customizer' => true,
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Footer Padding', 'styler' ),
                'subtitle' => esc_html__( 'You can set the top spacing of the site main footer.', 'styler' ),
                'customizer' => true,
                'id' => 'footer_pad',
                'type' => 'spacing',
                'output' => array('#nt-footer' ),
                'mode' => 'padding',
                'units' => array('em', 'px' ),
                'units_extended' => 'false',
                'default' => array(
                    'padding-top' => '',
                    'padding-right' => '',
                    'padding-bottom' => '',
                    'padding-left' => '',
                    'units' => 'px'
                ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Footer Background Color', 'styler' ),
                'desc' => esc_html__( 'Set your own colors for the footer.', 'styler' ),
                'customizer' => true,
                'id' => 'footer_bg_clr',
                'type' => 'color',
                'validate' => 'color',
                'mode' => 'background-color',
                'output' => array( '#nt-footer' ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Text Color', 'styler' ),
                'desc' => esc_html__( 'Set your own colors for the copyright.', 'styler' ),
                'customizer' => true,
                'id' => 'footer_copy_clr',
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'output' => array( '#nt-footer, #nt-footer p' ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Link Color', 'styler' ),
                'desc' => esc_html__( 'Set your own colors for the copyright.', 'styler' ),
                'customizer' => true,
                'id' => 'footer_link_clr',
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'output' => array( '#nt-footer a' ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Link Color ( Hover )', 'styler' ),
                'desc' => esc_html__( 'Set your own colors for the copyright.', 'styler' ),
                'customizer' => true,
                'id' => 'footer_link_hvr_clr',
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'output' => array( '#nt-footer a:hover' ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            //information on-off
            array(
                'id' =>'info_fc0',
                'type' => 'info',
                'style' => 'success',
                'title' => esc_html__( 'Success!', 'styler' ),
                'icon' => 'el el-info-circle',
                'customizer' => true,
                'desc' => sprintf(esc_html__( '%s section is disabled on the site.Please activate to view subsection options.', 'styler' ), '<b>Site Main Footer</b>' ),
                'required' => array( 'footer_visibility', '=', '0' )
            )
    )));
    /*************************************************
    ## DEFAULT PAGE SECTION
    *************************************************/
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Default Page', 'styler' ),
        'id' => 'defaultpagesection',
        'icon' => 'el el-home',
    ));
    // BLOG HERO SUBSECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Default Page Hero', 'styler' ),
        'desc' => esc_html__( 'These are default page hero settings!', 'styler' ),
        'id' => 'pageherosubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Page Hero Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site default page hero section with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'page_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Page Hero Background', 'styler' ),
                'customizer' => true,
                'id' => 'page_hero_bg',
                'type' => 'background',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-page-container .breadcrumb-bg' ),
                'required' => array( 'blog_hero_visibility', '=', '1' )
            )
    )));
    /*************************************************
    ## BLOG PAGE SECTION
    *************************************************/
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Blog Posts Page', 'styler' ),
        'id' => 'blogsection',
        'icon' => 'el el-home',
    ));
    // BLOG HERO SUBSECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Blog Hero', 'styler' ),
        'desc' => esc_html__( 'These are blog index page hero text settings!', 'styler' ),
        'id' => 'blogherosubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Blog Hero Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site blog index page hero section with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'blog_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Elementor Templates', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates instead of default template.', 'styler' ),
                'customizer' => true,
                'id' => 'blog_hero_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args
            ),
            array(
                'id' =>'edit_blog_hero_elementor_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Background', 'styler' ),
                'customizer' => true,
                'id' => 'blog_hero_bg',
                'type' => 'background',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-index .breadcrumb-bg' ),
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Title', 'styler' ),
                'subtitle' => esc_html__( 'Add your blog index page title here.', 'styler' ),
                'customizer' => true,
                'id' => 'blog_title',
                'type' => 'text',
                'default' => '',
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
    )));
    // BLOG LAYOUT AND POST COLUMN STYLE
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Blog Layout', 'styler' ),
        'id' => 'bloglayoutsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Blog Page Layout', 'styler' ),
                'subtitle' => esc_html__( 'Choose the blog index page layout.', 'styler' ),
                'customizer' => true,
                'id' => 'index_layout',
                'type' => 'image_select',
                'options' => array(
                    'left-sidebar' => array(
                        'alt' => 'Left Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cl.png'
                    ),
                    'full-width' => array(
                        'alt' => 'Full Width',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/1col.png'
                    ),
                    'right-sidebar' => array(
                        'alt' => 'Right Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cr.png'
                    )
                ),
                'default' => 'right-sidebar'
            ),
            array(
                'title' => esc_html__( 'Blog Sidebar Elementor Templates', 'styler' ),
                'subtitle' => esc_html__( 'You can use elementor templates instead of default sidebar if you want.Select a template from elementor templates.', 'styler' ),
                'customizer' => true,
                'id' => 'blog_sidebar_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array( 'index_layout', '!=', 'full-width' )
            ),
            array(
                'id' =>'edit_sidebar_elementor_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'index_layout', '!=', 'full-width' ),
                    array( 'blog_sidebar_templates', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Container Width', 'styler' ),
                'subtitle' => esc_html__( 'Select blog page container width type.', 'styler' ),
                'customizer' => true,
                'id' => 'index_container_type',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'styler' ),
                    'container' => esc_html__( 'Default Boxed', 'styler' ),
                    'container-fluid' => esc_html__( 'Fluid', 'styler' )
                ),
                'default' => 'container'
            )
    )));
    // BLOG LAYOUT AND POST COLUMN STYLE
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Blog Post', 'styler' ),
        'id' => 'blogpostsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Layout Type', 'styler' ),
                'subtitle' => esc_html__( 'Select blog page layout type.', 'styler' ),
                'customizer' => true,
                'id' => 'index_type',
                'type' => 'select',
                'options' => array(
                    'grid' => esc_html__( 'grid', 'styler' ),
                    'masonry' => esc_html__( 'masonry', 'styler' )
                ),
                'default' => 'grid',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Post Style', 'styler' ),
                'subtitle' => esc_html__( 'Select blog page post style type.', 'styler' ),
                'customizer' => true,
                'id' => 'post_style',
                'type' => 'select',
                'options' => array(
                    'classic' => esc_html__( 'Classic', 'styler' ),
                    'card' => esc_html__( 'Card', 'styler' ),
                    'split' => esc_html__( 'Split', 'styler' )
                ),
                'default' => 'classic',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Post Image Size Style', 'styler' ),
                'subtitle' => esc_html__( 'Select blog page post image size style type.', 'styler' ),
                'customizer' => true,
                'id' => 'post_image_style',
                'type' => 'select',
                'options' => array(
                    'default' => esc_html__( 'Default', 'styler' ),
                    'fit' => esc_html__( 'Fit', 'styler' ),
                    'split' => esc_html__( 'Split', 'styler' )
                ),
                'default' => 'default',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Post Overlay Color', 'styler' ),
                'customizer' => true,
                'id' => 'post_card_style_overlay_color',
                'type' => 'color_rgba',
                'mode' => 'background-color',
                'output' => array( '.styler-blog-posts-item.style-card .styler-blog-post-item-inner:before' ),
                'required' => array( 'post_style', '=', 'card' )
            ),
            array(
                'title' => esc_html__( 'Post Min Height', 'styler' ),
                'subtitle' => esc_html__( 'Set the logo width and height of the image.', 'styler' ),
                'customizer' => true,
                'id' => 'post_card_style_height',
                'type' => 'dimensions',
                'width' => false,
                'output' => array('.styler-blog-posts-item.style-card .styler-blog-post-item-inner' ),
                'required' => array( 'post_style', '=', 'card' )
            ),
            array(
                'title' => esc_html__( 'Column Width', 'styler' ),
                'subtitle' => esc_html__( 'Select a column width.', 'styler' ),
                'customizer' => true,
                'id' => 'grid_column',
                'type' => 'select',
                'options' => array(
                    '1' => esc_html__( '1 column', 'styler' ),
                    '2' => esc_html__( '2 column', 'styler' ),
                    '3' => esc_html__( '3 column', 'styler' ),
                    '4' => esc_html__( '4 column', 'styler' )
                ),
                'default' => '1',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Mobile Column Width', 'styler' ),
                'subtitle' => esc_html__( 'Select a column width for mobile device.', 'styler' ),
                'customizer' => true,
                'id' => 'grid_mobile_column',
                'type' => 'select',
                'options' => array(
                    '1' => esc_html__( '1 column', 'styler' ),
                    '2' => esc_html__( '2 column', 'styler' ),
                    '3' => esc_html__( '3 column', 'styler' )
                ),
                'default' => '1',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Post Image Size', 'styler' ),
                'customizer' => true,
                'id' => 'post_imgsize',
                'type' => 'select',
                'data' => 'image_sizes'
            ),
            array(
                'title' => esc_html__( 'Custom Post Image Size', 'styler' ),
                'customizer' => true,
                'id' => 'post_custom_imgsize',
                'unit' => false,
                'type' => 'dimensions'
            ),
            array(
                'title' => esc_html__( 'Post Title Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site blog index page post title with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'post_title_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Excerpt Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site blog index page post meta with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'post_excerpt_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Excerpt Size (max word count)', 'styler' ),
                'subtitle' => esc_html__( 'You can control blog post excerpt size with this option.', 'styler' ),
                'customizer' => true,
                'id' => 'post_excerpt_limit',
                'type' => 'slider',
                'default' => 30,
                'min' => 0,
                'step' => 1,
                'max' => 100,
                'display_value' => 'text',
                'required' => array( 'post_excerpt_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Button Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site blog index page post read more button wityh switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'post_button_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Read More Button Title', 'styler' ),
                'subtitle' => esc_html__( 'Add your blog post read more button title here.', 'styler' ),
                'customizer' => true,
                'id' => 'post_button_title',
                'type' => 'text',
                'default' => '',
                'required' => array( 'post_button_visibility', '=', '1' )
            )
    )));

    /*************************************************
    ## SINGLE PAGE SECTION
    *************************************************/
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Single Post Page', 'styler' ),
        'id' => 'singlesection',
        'icon' => 'el el-home-alt',
    ));
    // SINGLE HERO SUBSECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Single Hero', 'styler' ),
        'desc' => esc_html__( 'These are single page hero section settings!', 'styler' ),
        'id' => 'singleherosubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Single Hero Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page hero section with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'single_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates instead of default template.', 'styler' ),
                'customizer' => true,
                'id' => 'single_hero_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array( 'single_hero_visibility', '=', '1' )
            ),
            array(
                'id' =>'edit_single_hero_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'single_hero_visibility', '=', '1' ),
                    array( 'single_hero_elementor_templates', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Single Hero Background', 'styler' ),
                'customizer' => true,
                'id' => 'single_hero_bg',
                'type' => 'background',
                'output' => array( '#nt-single .breadcrumb-bg' ),
                'required' => array(
                    array( 'single_hero_visibility', '=', '1' ),
                    array( 'single_hero_elementor_templates', '=', '' )
                )
            )
    )));
    // SINGLE CONTENT SUBSECTION
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Single Content', 'styler' ),
        'id' => 'singlecontentsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Post Page Layout', 'styler' ),
                'subtitle' => esc_html__( 'Choose the single post page layout.', 'styler' ),
                'customizer' => true,
                'id' => 'single_layout',
                'type' => 'image_select',
                'options' => array(
                    'left-sidebar' => array(
                        'alt' => 'Left Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cl.png'
                    ),
                    'full-width' => array(
                        'alt' => 'Full Width',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/1col.png'
                    ),
                    'right-sidebar' => array(
                        'alt' => 'Right Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cr.png'
                    )
                ),
                'default' => 'full-width'
            ),
            array(
                'title' => esc_html__( 'Author Name Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post date with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'single_postmeta_author_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Date Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post date number with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'single_postmeta_date_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Categories Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post meta tags with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'single_postmeta_category_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Tags Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post meta tags with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'single_postmeta_tags_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Authorbox Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post authorbox with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'single_post_author_box_visibility',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Post Pagination Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post next and prev pagination with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'single_navigation_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            )
    )));
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Single Related Posts', 'styler' ),
        'id' => 'singlerelatedsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Related Post Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page related post with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'single_related_visibility',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates instead of default related post template.', 'styler' ),
                'customizer' => true,
                'id' => 'single_related_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array( 'single_related_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Post Style', 'styler' ),
                'subtitle' => esc_html__( 'Select single page related post style type.', 'styler' ),
                'customizer' => true,
                'id' => 'related_post_style',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select style', 'styler' ),
                    'classic' => esc_html__( 'Classic', 'styler' ),
                    'card' => esc_html__( 'Card', 'styler' ),
                    'split' => esc_html__( 'Split', 'styler' )
                ),
                'default' => 'classic',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'id' => 'related_section_heading_start',
                'type' => 'section',
                'title' => esc_html__('Related Section Heading', 'styler'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Related Section Subtitle', 'styler' ),
                'subtitle' => esc_html__( 'Add your single page related post section subtitle here.', 'styler' ),
                'customizer' => true,
                'id' => 'related_subtitle',
                'type' => 'text',
                'default' => '',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Subtitle Tag', 'styler' ),
                'customizer' => true,
                'id' => 'related_subtitle_tag',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'styler' ),
                    'h1' => esc_html__( 'H1', 'styler' ),
                    'h2' => esc_html__( 'H2', 'styler' ),
                    'h3' => esc_html__( 'H3', 'styler' ),
                    'h4' => esc_html__( 'H4', 'styler' ),
                    'h5' => esc_html__( 'H5', 'styler' ),
                    'h6' => esc_html__( 'H6', 'styler' ),
                    'p' => esc_html__( 'p', 'styler' ),
                    'div' => esc_html__( 'div', 'styler' ),
                    'span' => esc_html__( 'span', 'styler' )
                ),
                'default' => 'p',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' ),
                    array( 'related_subtitle', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Related Section Title', 'styler' ),
                'subtitle' => esc_html__( 'Add your single page related post section title here.', 'styler' ),
                'customizer' => true,
                'id' => 'related_title',
                'type' => 'text',
                'default' => esc_html__( 'Related Post', 'styler' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Title Tag', 'styler' ),
                'customizer' => true,
                'id' => 'related_title_tag',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'styler' ),
                    'h1' => esc_html__( 'H1', 'styler' ),
                    'h2' => esc_html__( 'H2', 'styler' ),
                    'h3' => esc_html__( 'H3', 'styler' ),
                    'h4' => esc_html__( 'H4', 'styler' ),
                    'h5' => esc_html__( 'H5', 'styler' ),
                    'h6' => esc_html__( 'H6', 'styler' ),
                    'p' => esc_html__( 'p', 'styler' ),
                    'div' => esc_html__( 'div', 'styler' ),
                    'span' => esc_html__( 'span', 'styler' )
                ),
                'default' => 'h3',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' ),
                    array( 'related_title', '!=', '' )
                )
            ),
            array(
                'id' => 'related_section_heading_end',
                'customizer' => true,
                'type' => 'section',
                'indent' => false,
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'id' => 'related_section_posts_start',
                'type' => 'section',
                'title' => esc_html__('Related Post Options', 'styler'),
                'customizer' => true,
                'indent' => true
            ),
            array(
                'title' => esc_html__( 'Posts Perpage', 'styler' ),
                'subtitle' => esc_html__( 'You can control related post count with this option.', 'styler' ),
                'customizer' => true,
                'id' => 'related_perpage',
                'type' => 'slider',
                'default' => 3,
                'min' => 1,
                'step' => 1,
                'max' => 24,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Post Image Size', 'styler' ),
                'customizer' => true,
                'id' => 'related_imgsize',
                'type' => 'select',
                'data' => 'image_sizes',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Post Excerpt Display', 'styler' ),
                'id' => 'related_excerpt_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Post Excerpt Limit', 'styler' ),
                'subtitle' => esc_html__( 'You can control related post excerpt word limit.', 'styler' ),
                'customizer' => true,
                'id' => 'related_excerpt_limit',
                'type' => 'slider',
                'default' => 30,
                'min' => 0,
                'step' => 1,
                'max' => 100,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' ),
                    array( 'related_excerpt_visibility', '=', '1' )
                )
            ),
            array(
                'id' => 'related_section_posts_end',
                'customizer' => true,
                'type' => 'section',
                'indent' => false,
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'id' => 'related_section_slider_start',
                'type' => 'section',
                'title' => esc_html__('Related Slider Options', 'styler'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Perview ( Min 1200px )', 'styler' ),
                'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'styler' ),
                'customizer' => true,
                'id' => 'related_perview',
                'type' => 'slider',
                'default' => 5,
                'min' => 1,
                'step' => 1,
                'max' => 10,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Slider Perview ( Min 992px )', 'styler' ),
                'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'styler' ),
                'customizer' => true,
                'id' => 'related_mdperview',
                'type' => 'slider',
                'default' => 3,
                'min' => 1,
                'step' => 1,
                'max' => 10,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Perview ( Min 768px )', 'styler' ),
                'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'styler' ),
                'customizer' => true,
                'id' => 'related_smperview',
                'type' => 'slider',
                'default' => 3,
                'min' => 1,
                'step' => 1,
                'max' => 10,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Perview ( Min 480px )', 'styler' ),
                'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'styler' ),
                'customizer' => true,
                'id' => 'related_xsperview',
                'type' => 'slider',
                'default' => 2,
                'min' => 1,
                'step' => 1,
                'max' => 10,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Speed', 'styler' ),
                'subtitle' => esc_html__( 'You can control related post slider item gap.', 'styler' ),
                'customizer' => true,
                'id' => 'related_speed',
                'type' => 'slider',
                'default' => 1000,
                'min' => 100,
                'step' => 1,
                'max' => 10000,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Gap', 'styler' ),
                'subtitle' => esc_html__( 'You can control related post slider item gap.', 'styler' ),
                'customizer' => true,
                'id' => 'related_gap',
                'type' => 'slider',
                'default' => 30,
                'min' => 0,
                'step' => 1,
                'max' => 100,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Centered', 'styler' ),
                'customizer' => true,
                'id' => 'related_centered',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Autoplay', 'styler' ),
                'customizer' => true,
                'id' => 'related_autoplay',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Loop', 'styler' ),
                'customizer' => true,
                'id' => 'related_loop',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Mousewheel', 'styler' ),
                'customizer' => true,
                'id' => 'related_mousewheel',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'id' => 'related_section_slider_end',
                'customizer' => true,
                'type' => 'section',
                'indent' => false,
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            )
    )));
    /*************************************************
    ## ARCHIVE PAGE SECTION
    *************************************************/
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Archive Page', 'styler' ),
        'id' => 'archivesection',
        'icon' => 'el el-folder-open',
        'fields' => array(
            array(
                'title' => esc_html__( 'Archive Page Layout', 'styler' ),
                'subtitle' => esc_html__( 'Choose the archive page layout.', 'styler' ),
                'customizer' => true,
                'id' => 'archive_layout',
                'type' => 'image_select',
                'options' => array(
                    'left-sidebar' => array(
                        'alt' => 'Left Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cl.png'
                    ),
                    'full-width' => array(
                        'alt' => 'Full Width',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/1col.png'
                    ),
                    'right-sidebar' => array(
                        'alt' => 'Right Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cr.png'
                    )
                ),
                'default' => 'full-width'
            ),
            array(
                'title' => esc_html__( 'Archive Hero Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site archive page hero section with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'archive_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Archive Hero Background', 'styler' ),
                'customizer' => true,
                'id' => 'archive_hero_bg',
                'type' => 'background',
                'output' => array( '#nt-archive .breadcrumb-bg' ),
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Custom Archive Title', 'styler' ),
                'subtitle' => esc_html__( 'Add your custom archive page title here.', 'styler' ),
                'customizer' => true,
                'id' => 'archive_title',
                'type' => 'text',
                'default' =>'',
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            )
    )));
    /*************************************************
    ## SEARCH PAGE SECTION
    *************************************************/
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( 'Search Page', 'styler' ),
        'id' => 'searchsection',
        'icon' => 'el el-search',
        'fields' => array(
            array(
                'title' => esc_html__( 'Search Page Layout', 'styler' ),
                'subtitle' => esc_html__( 'Choose the search page layout.', 'styler' ),
                'customizer' => true,
                'id' => 'search_layout',
                'type' => 'image_select',
                'options' => array(
                    'left-sidebar' => array(
                        'alt' => 'Left Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cl.png'
                    ),
                    'full-width' => array(
                        'alt' => 'Full Width',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/1col.png'
                    ),
                    'right-sidebar' => array(
                        'alt' => 'Right Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cr.png'
                    )
                ),
                'default' => 'full-width'
            ),
            array(
                'title' => esc_html__( 'Search Hero Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site search page hero section with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'search_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' )
            ),
            array(
                'title' => esc_html__( 'Search Hero Background', 'styler' ),
                'customizer' => true,
                'id' =>'search_hero_bg',
                'type' => 'background',
                'output' => array( '#nt-search .breadcrumb-bg' ),
                'required' => array( 'search_hero_visibility', '=', '1' )
            )
    )));
    /*************************************************
    ## 404 PAGE SECTION
    *************************************************/
    Redux::setSection($styler_pre, array(
        'title' => esc_html__( '404 Page', 'styler' ),
        'id' => 'errorsection',
        'icon' => 'el el-error',
        'fields' => array(
            array(
                'title' => esc_html__( '404 Type', 'styler' ),
                'subtitle' => esc_html__( 'Select your 404 page type.', 'styler' ),
                'customizer' => true,
                'id' => 'error_page_type',
                'type' => 'select',
                'options' => array(
                    'default' => esc_html__( 'Deafult', 'styler' ),
                    'elementor' => esc_html__( 'Elementor Templates', 'styler' )
                ),
                'default' => 'default'
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'styler' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'styler' ),
                'customizer' => true,
                'id' => 'error_page_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array( 'error_page_type', '=', 'elementor' )
            ),
            array(
                'id' =>'edit_error_page_template',
                'type' => 'info',
                'desc' => 'Edit template',
                'required' => array(
                    array( 'error_page_type', '=', 'elementor' ),
                    array( 'error_page_elementor_templates', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( '404 Header Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page header with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'error_header_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array( 'error_page_type', '=', 'elementor' )
            ),
            array(
                'title' => esc_html__( '404 Footer Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page footer with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'error_footer_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array( 'error_page_type', '=', 'elementor' )
            ),
            array(
                'title' => esc_html__( '404 Hero Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page hero section with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'error_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array( 'error_page_type', '=', 'default' )
            ),
            array(
                'title' => esc_html__( '404 Hero Background', 'styler' ),
                'customizer' => true,
                'id' => 'error_hero_bg',
                'type' => 'background',
                'output' => array( '#nt-archive .breadcrumb-bg' ),
                'required' => array(
                    array( 'error_page_type', '=', 'default' ),
                    array( 'error_hero_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom 404 Title', 'styler' ),
                'subtitle' => esc_html__( 'Add your custom 404 page title here.', 'styler' ),
                'customizer' => true,
                'id' => 'error_title',
                'type' => 'text',
                'default' =>'',
                'required' => array(
                    array( 'error_page_type', '=', 'default' ),
                    array( 'error_hero_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Content Description Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page content description with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'error_content_desc_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array( 'error_page_type', '=', 'default' )
            ),
            array(
                'title' => esc_html__( 'Content Description', 'styler' ),
                'subtitle' => esc_html__( 'Add your 404 page content description here.', 'styler' ),
                'customizer' => true,
                'id' => 'error_content_desc',
                'type' => 'textarea',
                'default' => '',
                'required' => array(
                    array( 'error_page_type', '=', 'default' ),
                    array( 'error_content_desc_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Button Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page content back to home button with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'error_content_btn_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array( 'error_page_type', '=', 'default' )
            ),
            array(
                'title' => esc_html__( 'Button Title', 'styler' ),
                'subtitle' => esc_html__( 'Add your 404 page content back to home button title here.', 'styler' ),
                'customizer' => true,
                'id' => 'error_content_btn_title',
                'type' => 'text',
                'default' => '',
                'required' => array(
                    array( 'error_page_type', '=', 'default' ),
                    array( 'error_content_btn_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Form Display', 'styler' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page content search form with switch option.', 'styler' ),
                'customizer' => true,
                'id' => 'error_content_form_visibility',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'styler' ),
                'off' => esc_html__( 'Off', 'styler' ),
                'required' => array( 'error_page_type', '=', 'default' )
            )
    )));
    if ( !class_exists( 'WooCommerce' ) ) {
        Redux::setSection($styler_pre, array(
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
        )));
        Redux::setSection($styler_pre, array(
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
                <a target="_blank" class="button" href="https://ninetheme.com/docs/styler/">'.esc_html__( 'READ MORE', 'styler' ).'</a>
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
                <a target="_blank" class="button" href="https://ninetheme.com/contact/">'.esc_html__( 'GET SUPPORT', 'styler' ).'</a>
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
                <a target="_blank" class="button" href="https://ninetheme.com/themes/">'.esc_html__( 'SEE MORE', 'styler' ).'</a>
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
        )));
    }
    /*
     * <--- END SECTIONS
     */


    /** Action hook examples **/

    function styler_remove_demo()
    {
        // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
        if (class_exists('ReduxFrameworkPlugin' )) {
            // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
            remove_action('admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ));
        }
    }
    //include get_template_directory() . '/inc/core/theme-options/redux-extensions/loader.php';
    function styler_newIconFont() {
        // Uncomment this to remove elusive icon from the panel completely
        // wp_deregister_style( 'redux-elusive-icon' );
        // wp_deregister_style( 'redux-elusive-icon-ie7' );
        wp_register_style(
            'redux-font-awesome',
            '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
            array(),
            time(),
            'all'
        );
        wp_enqueue_style( 'redux-font-awesome' );
    }
    add_action( 'redux/page/styler/enqueue', 'styler_newIconFont' );
