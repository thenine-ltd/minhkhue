<?php
/**
 * The template for displaying post content card style within loops
 *
 * This template can be editing
 *
 */

$size = styler_loop_post_thumbnail_size();
$bg   = get_the_post_thumbnail_url( get_the_ID(), $size );
?>

<div id="post-<?php echo get_the_ID() ?>" <?php post_class( 'styler-blog-posts-item style-card' ); ?>>
    <div class="styler-blog-post-item-inner" data-background="<?php echo esc_url( $bg ); ?>">

        <?php styler_loop_post_first_category(); ?>

        <div class="styler-blog-post-content">
            <div class="styler-blog-post-meta styler-inline-two-block">
                <?php echo styler_loop_post_author('<h6 class="styler-post-author">','</h6>', true); ?>
                <?php echo styler_loop_post_date('<h6 class="styler-post-date">','</h6>', true); ?>
            </div>
            <?php styler_loop_post_title(); ?>
            <?php styler_loop_post_excerpt(); ?>
        </div>

    </div>
</div>
