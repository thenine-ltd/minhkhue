<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

    <!-- Meta UTF8 charset -->
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="initial-scale=1.0" />
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui" />
    <?php wp_head(); ?>

</head>

<!-- BODY START -->
<body <?php body_class(); ?>>
    <?php
    if ( function_exists( 'wp_body_open' ) ) {
        wp_body_open();
    }
    /**
    * Hook: styler_after_body_open
    *
    * @hooked styler_preloader - 10
    */
    do_action( 'styler_after_body_open' );
    ?>
    <div id="wrapper" class="page-wrapper">
        <div class="page-wrapper-inner">
            <div class="styler-header-overlay"></div>
        <?php
        // Elementor `header` location
        if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
            do_action( 'styler_header_action' );
        }
        ?>
        <div role="main" class="site-content">
            <div class="header-spacer"></div>
