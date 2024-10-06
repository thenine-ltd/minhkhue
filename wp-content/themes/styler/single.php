<?php

/**
* The template for displaying all single posts
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
*
* @package WordPress
* @subpackage Styler
* @since 1.0.0
*/

if ( styler_check_is_elementor() ) {
    // on-off header footer function
    styler_page_header_footer_manager();
}

get_header();

// Elementor `single` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {

    // you can use this action to add any content before single page
    do_action( 'styler_before_post_single' );

    if ( styler_check_is_elementor() ) {

        while ( have_posts() ) {

            the_post();

            the_content();

        }

    } else {
        
        /**
        * Hook: styler_single.
        *
        * @hooked styler_single_layout
        */
        do_action( 'styler_single' );
    }

    // you can use this action to add any content after single page
    do_action( 'styler_after_post_single' );
}

get_footer();
?>
