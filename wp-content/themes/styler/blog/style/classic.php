<?php
/**
 * The template for displaying post content default style within loops
 *
 * This template can be editing
 *
 */

$post_style = styler_settings( 'post_style', 'classic' );
?>

<div id="post-<?php echo get_the_ID() ?>" <?php post_class( 'styler-blog-posts-item style-'.$post_style ); ?>>
    <div class="styler-blog-post-item-inner">

        <?php if ( is_sticky() ) { ?>
            <span class="blog-sticky"><?php esc_html_e( 'Featured', 'styler' ); ?></span>
        <?php } ?>

        <?php if ( has_post_thumbnail() ) { ?>
            <div class="styler-blog-thumb image-<?php echo apply_filters('styler_post_image_size_style', styler_settings( 'post_image_style', 'default' ) ); ?>">
                <?php styler_loop_post_thumbnail(); ?>
                <?php styler_loop_post_first_category(); ?>
            </div>
        <?php } ?>

        <div class="styler-blog-post-content">
            <div class="styler-blog-post-meta styler-inline-two-block">
                <?php echo styler_loop_post_author('<h6 class="styler-post-meta-title styler-block-left">','</h6>', true); ?>
                <?php echo styler_loop_post_date('<span class="styler-post-meta-date styler-block-right">','</span>', true); ?>
            </div>
            <?php
                styler_loop_post_title();
                styler_loop_post_excerpt();
            ?>
            <?php if ( ! get_the_title() ) { ?>
                <a class="blog-read-more-link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo get_the_title(); ?>"><?php esc_html_e( 'Read More', 'styler' ); ?></a>
            <?php } ?>
        </div>

    </div>
</div>
