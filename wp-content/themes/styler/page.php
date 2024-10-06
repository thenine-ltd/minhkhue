<?php

/**
* default page template
*/

if ( styler_check_is_elementor() ) {
    // on-off header function
    styler_page_header_footer_manager();
}

if ( ( isset( $_GET['iframe_checkout'] ) && esc_html( $_GET['iframe_checkout'] ) == true ) || ( isset( $_GET['order_received'] ) && esc_html( $_GET['order_received'] ) == true ) ) {

    get_template_part( 'content', 'iframe' );

} else {

    if ( !styler_is_pjax() ) {
        get_header();
    }
    //get_header();

    // Elementor `single` location
    if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {

        if ( styler_check_is_elementor() ) {

            while ( have_posts() )
            {

                the_post();

                the_content();

            }

        } else {

            get_template_part( 'page', 'content' );

        }
    }
    if ( !styler_is_pjax() ) {
        get_footer();
    }

}
