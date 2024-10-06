<?php

/**
* The template for displaying 404 pages (not found)
*
* @link https://codex.wordpress.org/Creating_an_Error_404_Page
*
* @package WordPress
* @subpackage Styler
* @since 1.0.0
*/

if ( '0' == styler_settings( 'error_header_visibility', '1' ) ) {
    remove_action( 'styler_header_action', 'styler_main_header', 10 );
}
if ( '0' == styler_settings( 'error_footer_visibility', '1' ) ) {
    remove_action( 'styler_footer_action', 'styler_footer', 10 );
}

get_header();

// you can use this action for add any content before container element
do_action( 'styler_before_404' );

if ( 'elementor' == styler_settings( 'error_page_type', 'default' ) && !empty( styler_settings( 'error_page_elementor_templates' ) ) ) {

    echo styler_print_elementor_templates( 'error_page_elementor_templates', false );

} else {
    $btn_title  = '' != styler_settings( 'error_content_btn_title' ) ? styler_settings( 'error_content_btn_title' ) : esc_html__( 'Go to home page', 'styler' );
    $error_desc = '' != styler_settings( 'error_content_desc' ) ? styler_settings( 'error_content_desc' ) : esc_html__( 'Sorry, but the page you are looking for does not exist or has been removed!', 'styler' );
    ?>
    <div id="nt-404" class="nt-404 error">

        <?php styler_hero_section(); ?>

        <div class="nt-styler-inner-container styler-error-area pt-80 pb-100">
            <div class="container-xl styler-container-xl">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-10">
                        <div class="styler-error-content text-center">

                            <div class="styler-error-txt"><?php esc_html_e( '404','styler' ); ?></div>

                            <?php
                            if ( '0' != styler_settings('error_content_desc_visibility', '1' ) ) {
                                printf( '<h5 class="content-text">%s</h5>', esc_html( $error_desc ) );
                            }

                            if ( '0' != styler_settings( 'error_content_form_visibility', '0' ) ) {
                                echo styler_search_form();
                            }

                            if ( '0' != styler_settings('error_content_btn_visibility', '1' ) ) {
                                printf( '<a href="%1$s" class="styler-btn-medium styler-btn styler-bg-black mt-30"><span>%2$s</span></a>',
                                    esc_url( home_url('/') ),
                                    esc_html( $btn_title )
                                );
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

// use this action to add any content after 404 page container element
do_action( 'styler_after_404' );

get_footer();

?>
