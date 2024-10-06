<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="initial-scale=1.0" />
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
wp_enqueue_style( 'styler-checkout-popup' );
wp_enqueue_script( 'styler-checkout-popup' );
add_filter('show_admin_bar', '__return_false');

while ( have_posts() ) {
    the_post();
    the_content();
}

wp_footer();
?>
</body>
</html>
