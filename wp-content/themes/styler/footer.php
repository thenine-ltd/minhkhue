<?php
/**
* The template for displaying the footer.
*
* Contains the closing of the #content div and all content after
*
* @package styler
*/
            // Elementor `footer` location
            if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
                /**
                * Hook: styler_footer_action.
                *
                * @hooked styler_footer
                */
                do_action( 'styler_footer_action' );
            }
            ?>
            </div>
        </div>
    </div>

    <?php do_action( 'styler_before_wp_footer' ); ?>

    <?php wp_footer(); ?>

    </body>
</html>
