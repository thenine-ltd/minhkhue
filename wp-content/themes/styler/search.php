<?php
/**
* The template for displaying search results pages
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
*
* @package WordPress
* @subpackage Styler
* @since 1.0.0
*/

get_header();

// you can use this action for add any content before container element
do_action( 'styler_before_search' );

// Elementor `archive` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
    ?>
    <!-- search page general div -->
    <div id="nt-search" class="nt-search">

        <?php
        styler_hero_section();

        get_template_part( 'blog/layout/main' );
        ?>
    </div>
    <!--End search page general div -->
    <?php
}

// you can use this action to add any content after search page
do_action( 'styler_after_search' );

get_footer();
?>
