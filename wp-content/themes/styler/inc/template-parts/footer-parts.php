<?php

/**
* Custom template parts for this theme.
*
* Eventually, some of the functionality here could be replaced by core features.
*
* @package styler
*/


add_action( 'styler_footer_action', 'styler_footer', 10 );

if ( ! function_exists( 'styler_footer' ) ) {
    function styler_footer()
    {
        $footer_id = false;

        if ( class_exists( '\Elementor\Core\Settings\Manager' ) ) {

            $page_settings  = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' )->get_model( get_the_ID() );
            $page_footer_id = $page_settings->get_settings( 'styler_page_footer_template' );
            $footer_id      = isset( $page_footer_id ) !== '' ? $page_footer_id : $footer_id;
            $footer_id      = apply_filters('styler_elementor_footer_template', $footer_id );
        }
        
        if ( '0' != styler_settings( 'footer_visibility', '1' ) ) {

            if ( class_exists( '\Elementor\Frontend' ) && 'elementor' == styler_settings( 'footer_template', 'default' ) ) {

                if ( $footer_id ) {
                    $frontend = new \Elementor\Frontend;
                    printf( '<footer class="styler-elementor-footer footer-'.$footer_id.'">%1$s</footer>', $frontend->get_builder_content_for_display( $footer_id, true ) );

                } else {

                    echo styler_print_elementor_templates( 'footer_elementor_templates', 'styler-elementor-footer', true );
                }

            } else {

                styler_copyright();

            }
        }
    }
}

/*************************************************
##  FOOTER COPYRIGHT
*************************************************/

if ( ! function_exists( 'styler_copyright' ) ) {
    function styler_copyright()
    {
        ?>
        <footer id="nt-footer" class="styler-footer-area styler-default-copyright">
            <div class="container-xl styler-container-xl">
                <div class="row styler-align-center styler-justify-center">
                    <div class="col-12">
                        <div class="copyright-text">
                            <?php
                            if ( '' != styler_settings( 'footer_copyright' ) ) {

                                echo wp_kses( styler_settings( 'footer_copyright' ), styler_allowed_html() );

                            } else {
                                echo sprintf( '<p class="text-center">Copyright &copy; %1$s, <a class="theme" href="%2$s">%3$s</a> Theme. %4$s <a class="dev" href="https://ninetheme.com/contact/"> %5$s</a></p>',
                                    date_i18n( _x( 'Y', 'copyright date format', 'styler' ) ),
                                    esc_url( home_url( '/' ) ),
                                    get_bloginfo( 'name' ),
                                    esc_html__( 'Made with passion by', 'styler' ),
                                    esc_html__( 'Ninetheme.', 'styler' )
                                );
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <?php
    }
}
