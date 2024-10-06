<div id="nt-page-container" class="nt-page-layout">

    <?php

    styler_hero_section();

    $col = class_exists( 'WooCommerce' ) && ( is_cart() || is_account_page() || is_checkout() ) ? 'col-12' : 'col-7';

    ?>

    <div id="nt-page" class="nt-styler-inner-container pt-100 pb-100">
        <div class="container-xl styler-container-xl">
            <div class="row justify-content-center">

                <div class="<?php echo esc_attr( $col ); ?>">

                    <?php while ( have_posts() ) : the_post(); ?>

                        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <div class="nt-styler-content nt-clearfix content-container">
                                <?php

                                /* translators: %s: Name of current post */
                                the_content( sprintf(
                                    esc_html__( 'Continue reading %s', 'styler' ),
                                    the_title( '<span class="screen-reader-text">', '</span>', false )
                                ) );

                                /* theme page link pagination */
                                styler_wp_link_pages();

                                ?>
                            </div>
                        </div>
                        <?php

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) {
                            comments_template();
                        }

                    // End the loop.
                    endwhile;
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>
