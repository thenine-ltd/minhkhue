<?php
/**
* Taxonomy: Styler Brands.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
if ( ! class_exists( 'Styler_Popup_Builder' ) ) {
    class Styler_Popup_Builder {
        private static $instance = null;
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
        public function __construct() {
            if ( ! get_option( 'disable_styler_popups' ) == 1 ) {
                add_action( 'init', array( $this, 'styler_register_popups' ) );

                $cpt_support = get_option( 'elementor_cpt_support' );
                if ( is_array( $cpt_support ) && ! in_array( 'styler_popups', $cpt_support ) ) {
                    $cpt_support[] = 'styler_popups';
                    update_option( 'elementor_cpt_support', $cpt_support );
                }
                // Add the custom columns to the book post type:
                add_filter( 'manage_styler_popups_posts_columns', array( $this, 'set_custom_edit_styler_popups_columns' ) );
                // Add the data to the custom columns for the book post type:
                add_action( 'manage_styler_popups_posts_custom_column' , array( $this, 'custom_styler_popups_column' ), 10, 2 );
            }
        }
        public function styler_register_popups() {

            /**
            * Post Type: Styler Popups.
            */

            $labels = [
                "name" => __( "Popups Builder", "styler" ),
                "singular_name" => __( "Popup Builder", "styler" ),
                "menu_name" => __( "Popups Builder", "styler" ),
                "all_items" => __( "Popups Builder", "styler" ),
                "add_new" => __( "Add Popup", "styler" ),
                "add_new_item" => __( "Add new Popup", "styler" ),
                "edit_item" => __( "Edit Popup", "styler" ),
                "new_item" => __( "New Popup", "styler" ),
                "view_item" => __( "View Popup", "styler" ),
                "view_items" => __( "View Popups", "styler" ),
                "search_items" => __( "Search Popups", "styler" ),
                "not_found" => __( "No Popups found", "styler" ),
                "not_found_in_trash" => __( "No Popups found in trash", "styler" ),
                "archives" => __( "Popup archives", "styler" ),
            ];

            $args = [
                "label" => __( "Styler Popups", "styler" ),
                "labels" => $labels,
                "description" => "",
                "public" => true,
                "publicly_queryable" => true,
                "show_ui" => true,
                "show_in_rest" => true,
                "rest_base" => "",
                "rest_controller_class" => "WP_REST_Posts_Controller",
                "has_archive" => false,
                "show_in_menu" => "ninetheme_theme_manage",
                "show_in_nav_menus" => true,
                "delete_with_user" => false,
                "exclude_from_search" => true,
                "capability_type" => "post",
                "map_meta_cap" => true,
                "hierarchical" => false,
                "rewrite" => [ "slug" => "styler_popups", "with_front" => true ],
                "query_var" => true,
                "supports" => [ "title", "editor", "author" ],
                "show_in_graphql" => false,
            ];

            register_post_type( "styler_popups", $args );
        }

        public function set_custom_edit_styler_popups_columns($columns) {
            $columns[ 'shortcode' ] = __( "Popups ID", "styler" );
        
            return $columns;
        }
        
        public function custom_styler_popups_column( $column, $post_id ) {
            
            if ( 'shortcode' === $column ) {
        
                /** %s = shortcode tag, %d = post_id */
                $shortcode = esc_attr(
                    sprintf(
                        '#%s%d',
                        'styler-popup-',
                        $post_id
                    )
                );
                printf(
                    '<input class="styler-popup-input widefat" type="text" readonly onfocus="this.select()" value="%s" />',
                    $shortcode
                );
            } 
        }
    }
    Styler_Popup_Builder::get_instance();
}
