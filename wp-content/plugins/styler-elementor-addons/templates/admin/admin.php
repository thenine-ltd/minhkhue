<?php

/*
* Admin Assets
*/
function styler_enqueue_admin_assets( $hook ) {
    global $styler_admin_menu_page;
    if ( $hook != $styler_admin_menu_page ) {
        return;
    }
    wp_enqueue_style( 'styler-admin-bootstrap-css',  plugins_url( 'assets/admin/css/bootstrap.min.css', dirname(__DIR__) )  );
    wp_enqueue_style( 'styler-admin-styles', plugins_url( 'assets/admin/css/plugin-admin-styles.css', dirname(__DIR__) ) );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'styler-admin-popper-js', plugins_url( 'assets/admin/js/popper.min.js', dirname(__DIR__) ) );
    wp_enqueue_script( 'styler-admin-bootstrap-js', plugins_url( 'assets/admin/js/bootstrap.min.js', dirname(__DIR__) ) );
    wp_enqueue_script( 'styler-admin-script-js',  plugins_url( 'assets/admin/js/plugin-admin-scripts.js', dirname(__DIR__) ) );
}
add_action( 'admin_enqueue_scripts', 'styler_enqueue_admin_assets' );

add_action( 'admin_menu', 'styler_admin_menu_page', 200 );
function styler_admin_menu_page() {
    $parent_slug = apply_filters( 'ninetheme_parent_slug', 'elementor' );
    $page_title = esc_html__( 'Styler Addons', 'styler' );
    $menu_title = '<span class="dashicons dashicons-tagcloud"></span> ' .esc_html__( 'Styler Addons', 'styler' );
    $capability = 'manage_options';
    $menu_slug = 'styler';
    $function = 'styler_admin_menu_page_display';
    global $styler_admin_menu_page;
    $styler_admin_menu_page = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}

/*
* Admin Menu Page Output
*/
function styler_admin_menu_page_display() {
    if ( !current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized user' );
    }
    require_once( __DIR__ . '/admin-template.php' );
}
