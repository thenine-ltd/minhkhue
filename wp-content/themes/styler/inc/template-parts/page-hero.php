<?php

/*************************************************
## THEME DEFAULT HERO TEMPLATE
*************************************************/
if ( ! function_exists( 'styler_hero_section' ) ) {

    function styler_hero_section()
    {
        $h_t = get_the_title();
        $page_id = '';

        if ( is_404() ) {

            $name = 'error';
            $h_t = esc_html__( 'Page Not Found', 'styler' );

        } elseif ( is_archive() ) {

            $name = 'archive';
            $h_t = get_the_archive_title();

        } elseif ( is_search() ) {

            $name = 'search';
            $h_t = esc_html__( 'Search results for :', 'styler' );

        } elseif ( is_home() || is_front_page() ) {

            $name = 'blog';
            $h_t = esc_html__( 'Blog', 'styler' );

        } elseif ( is_single() ) {

            $name = 'single';
            $h_t = get_the_title();

        } elseif ( is_page() ) {

            $name = 'page';
            $h_t = get_the_title();
            $page_id = 'page-'.get_the_ID();

        }

        do_action( 'styler_before_page_hero' );

        if ( '0' != styler_settings( $name.'_hero_visibility', '1' ) ) {
            ?>
            <div class="styler-page-hero page-hero-mini <?php echo esc_attr( $page_id ); ?>">
                <div class="container-xl styler-container-xl">
                    <div class="row">
                        <div class="col-12">
                            <div class="styler-page-hero-content styler-flex styler-align-center styler-justify-center">
                                <?php

                                do_action( 'styler_before_page_title' );

                                if ( !is_single() ) {
                                    if ( $h_t ) {

                                        printf( '<h2 class="nt-hero-title page-title mb-30">%s %s</h2>',
                                            wp_kses( $h_t, styler_allowed_html() ),
                                            strlen( get_search_query() ) > 16 ? substr( get_search_query(), 0, 16 ).'...' : get_search_query()
                                        );

                                    } else {

                                        the_title('<h2 class="nt-hero-title page-title mb-10">', '</h2>');
                                    }
                                }

                                do_action( 'styler_after_page_title' );

                                echo styler_breadcrumbs();

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        do_action( 'styler_after_page_hero' );
    }
}
