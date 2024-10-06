<?php
/**
* The template for displaying post content card style within loops
*
* This template can be editing
*
*/
$grid_column = apply_filters('styler_blog_grid_column', styler_settings( 'grid_column', '1' ) );
$size =  $grid_column == 1 ? styler_loop_post_thumbnail_size() : [250,250];
?>

<div id="post-<?php echo get_the_ID() ?>" <?php post_class( 'styler-blog-posts-item style-split' ); ?>>
    <div class="styler-blog-post-item-inner">

        <div class="styler-blog-post-thumb-wrapper">
            <div class="styler-blog-post-thumb">
                <?php echo get_the_post_thumbnail( get_the_ID(), styler_loop_post_thumbnail_size() ); ?>
                <a class="blog-thumb-link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo get_the_title(); ?>"></a>
            </div>
            <?php styler_loop_post_first_category(); ?>
        </div>

        <div class="styler-blog-post-content">
            <?php styler_loop_post_title(); ?>
            <?php styler_loop_post_excerpt(); ?>

            <div class="styler-blog-post-meta styler-inline-two-block">
                <?php echo styler_loop_post_author('<h6 class="styler-post-author">','</h6>', true); ?>
                <?php echo styler_loop_post_date('<h6 class="styler-post-date">','</span>', true); ?>
            </div>
        </div>

    </div>
</div>
