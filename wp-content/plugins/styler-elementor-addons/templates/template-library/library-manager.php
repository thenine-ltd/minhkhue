<?php
namespace Styler_Addons\Elementor;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;

defined('ABSPATH') || die();

class Library_Manager {

    protected static $source = null;

    public static function init() {
        add_action( 'elementor/editor/footer', [ __CLASS__, 'print_template_views' ] );
        add_action( 'elementor/ajax/register_actions', [ __CLASS__, 'register_ajax_actions' ] );
        // Enqueue editor scripts
        add_action('elementor/editor/after_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
    }

    public static function print_template_views() {

        include_once( STYLER_PLUGIN_PATH . 'templates/template-library/templates.php' );

    }

    public static function enqueue_assets() {
        wp_enqueue_style(
            'styler-addons-templates-library',
            STYLER_PLUGIN_URL . 'templates/template-library/js-css/template-library.css',
            [
                'elementor-editor',
            ],
            STYLER_PLUGIN_VERSION
        );
        wp_enqueue_script(
            'styler-elementor-addons-editor',
            STYLER_PLUGIN_URL . 'templates/template-library/js-css/editor.min.js',
            ['elementor-editor', 'jquery'],
            STYLER_PLUGIN_VERSION,
            true
        );
        wp_enqueue_script(
            'styler-addons-templates-library',
            STYLER_PLUGIN_URL . 'templates/template-library/js-css/template-library.min.js',
            [
                'styler-elementor-addons-editor',
                'jquery-hover-intent',
            ],
            STYLER_PLUGIN_VERSION,
            true
        );
        $localize_data = [
            'placeholder_widgets' => [],
            //*'hasPro'                  => styler_has_pro(),
            'editor_nonce'            => wp_create_nonce('styler_editor_nonce'),
            //'dark_stylesheet_url'     => self::get_dark_stylesheet_url(),
            'i18n' => [
                'promotionDialogHeader'     => esc_html__('%s Widget', 'styler'),
                'promotionDialogMessage'    => esc_html__('Use %s widget with other exclusive pro widgets and 100% unique features to extend your toolbox and build sites faster and better.', 'styler'),
                'templatesEmptyTitle'       => esc_html__('No Templates Found', 'styler'),
                'templatesEmptyMessage'     => esc_html__('Try different category or sync for new templates.', 'styler'),
                'templatesNoResultsTitle'   => esc_html__('No Results Found', 'styler'),
                'templatesNoResultsMessage' => esc_html__('Please make sure your search is spelled correctly or try a different words.', 'styler'),
            ],
        ];
        wp_localize_script(
            'styler-elementor-addons-editor',
            'StylerAddonsEditor',
            $localize_data
        );
    }

    /**
    * Undocumented function
    *
    * @return Library_Source
    */
    public static function get_source() {
        if ( is_null( self::$source ) ) {
            self::$source = new Library_Source();
        }

        return self::$source;
    }

    public static function register_ajax_actions( Ajax $ajax ) {
        $ajax->register_ajax_action( 'get_styler_library_data', function( $data ) {
            if ( ! current_user_can( 'edit_posts' ) ) {
                throw new \Exception( 'Access Denied' );
            }

            if ( ! empty( $data['editor_post_id'] ) ) {
                $editor_post_id = absint( $data['editor_post_id'] );

                if ( ! get_post( $editor_post_id ) ) {
                    throw new \Exception( __( 'Post not found.', 'styler' ) );
                }

                \Elementor\Plugin::instance()->db->switch_to_post( $editor_post_id );
            }

            $result = self::get_library_data( $data );

            return $result;
        } );

        $ajax->register_ajax_action( 'get_styler_template_data', function( $data ) {
            if ( ! current_user_can( 'edit_posts' ) ) {
                throw new \Exception( 'Access Denied' );
            }

            if ( ! empty( $data['editor_post_id'] ) ) {
                $editor_post_id = absint( $data['editor_post_id'] );

                if ( ! get_post( $editor_post_id ) ) {
                    throw new \Exception( __( 'Post not found', 'styler' ) );
                }

                \Elementor\Plugin::instance()->db->switch_to_post( $editor_post_id );
            }

            if ( empty( $data['template_id'] ) ) {
                throw new \Exception( __( 'Template id missing', 'styler' ) );
            }

            $result = self::get_template_data( $data );

            return $result;
        } );
    }

    public static function get_template_data( array $args ) {
        $source = self::get_source();
        $data = $source->get_data( $args );
        return $data;
    }

    /**
    * Get library data from cache or remote
    *
    * type_tags has been added in version 2.15.0
    *
    * @param array $args
    *
    * @return array
    */
    public static function get_library_data( array $args ) {
        $source = self::get_source();

        if ( ! empty( $args['sync'] ) ) {
            Library_Source::get_library_data( true );
        }

        return [
            'templates' => $source->get_items(),
            'tags'      => $source->get_tags(),
            'type_tags' => $source->get_type_tags(),
        ];
    }
}

Library_Manager::init();
