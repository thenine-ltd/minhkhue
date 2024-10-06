<?php
/**
* The template for displaying archive pages
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/
*
* @package WordPress
* @subpackage Styler
* @since 1.0.0
*/

get_header();

// you can use this action for add any content before container element
do_action( 'styler_before_archive' );

// Elementor `archive` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {

    ?>
    <!-- archive page general div -->
    <div id="nt-archive" class="nt-archive" >

        <?php
        styler_hero_section();

        get_template_part( 'blog/layout/main' );
        ?>
    </div>
    <!-- End archive page general div-->
    <?php
}

do_action( 'styler_after_archive' );

get_footer();
?>
