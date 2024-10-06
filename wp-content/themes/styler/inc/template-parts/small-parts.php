<?php


/**
 * Custom template parts for this theme.
 *
 * preloader, backtotop, conten-none
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package styler
*/


/*************************************************
## START PRELOADER
*************************************************/

if ( ! function_exists( 'styler_preloader' ) ) {
    function styler_preloader()
    {
        $type = styler_settings('pre_type', 'default');

        if ( '0' != styler_settings('preloader_visibility', '1') ) {

            if ( 'default' == $type && '' != styler_settings( 'pre_img', '' ) ) {
                ?>
                <div class="preloader">
                    <img class="preloader__image" width="55" src="<?php echo esc_url( styler_settings( 'pre_img' )[ 'url' ] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
                </div>
                <?php
            } else {
                ?>
                <div id="nt-preloader" class="preloader">
                    <div class="loader<?php echo esc_attr( $type );?>"></div>
                </div>
                <?php
            }
        }
    }
}
add_action( 'styler_after_body_open', 'styler_preloader', 10 );
add_action( 'elementor/page_templates/canvas/before_content', 'styler_preloader', 10 );

/*************************************************
##  BACKTOP
*************************************************/

if ( ! function_exists( 'styler_backtop' ) ) {
    add_action( 'styler_before_wp_footer', 'styler_backtop', 10 );
    function styler_backtop() {
        if ( '1' == styler_settings('backtotop_visibility', '1') ) { ?>
            <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>
            <?php
        }
    }
}


/*************************************************
##  CONTENT NONE
*************************************************/

if ( ! function_exists( 'styler_content_none' ) ) {
    function styler_content_none() {
        ?>

        <div class="col-12">
            <div class="content-none-container">
                <h3 class="__title mb-20"><?php esc_html_e( 'Nothing Found', 'styler' ); ?></h3>
                <?php
                    if ( is_home() && current_user_can( 'publish_posts' ) ) :

                        printf( '<p>%s</p> <a class="thm-btn" href="%s">%s</a>',
                        esc_html__( 'Ready to publish your first post?', 'styler' ),
                        esc_url( admin_url( 'post-new.php' ) ),
                        esc_html__( 'Get started here', 'styler' )
                    );
                    elseif ( is_search() ) :
                    ?>
                    <p class="__nothing"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'styler' ); ?></p>

                    <?php printf( '<a href="%1$s" class="btn btn-fill-out styler-primary-background mt-30"><span>%2$s</span></a>',
                            esc_url( home_url('/') ),
                            esc_html__( 'Go to home page', 'styler' )
                        );
                    ?>

                <?php else : ?>
                    <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'styler' ); ?></p>


                <?php printf( '<a href="%1$s" class="btn btn-fill-out styler-primary-background"><span>%2$s</span></a>',
                        esc_url( home_url('/') ),
                        esc_html__( 'Go to home page', 'styler' )
                    );
                ?>

                <?php endif; ?>
            </div>
        </div>

        <?php
    }
}
