<?php
/**
* The main template file
*
*/

if ( is_search() ) {
    $name           = 'search';
    $sidebar        = 'styler-search-sidebar';
    $default_layout = 'full-width';
} elseif ( is_archive() ) {
    $name           = 'archive';
    $sidebar        = 'styler-archive-sidebar';
    $default_layout = 'full-width';
} else {
    $name           = 'index';
    $sidebar        = 'sidebar-1';
    $default_layout = 'right-sidebar';
}

$styler_layout      = apply_filters('styler_index_layout', styler_settings( $name.'_layout', $default_layout ) );
$grid_column        = apply_filters('styler_blog_grid_column', styler_settings( 'grid_column', '1' ) );
$grid_mobile_column = apply_filters('styler_blog_grid_mobile_column', styler_settings( 'grid_mobile_column', '1' ) );

$masonry       = 'masonry' == apply_filters('styler_index_type', styler_settings( 'index_type', 'grid' ) ) ? ' styler-masonry-container' : '';
$has_sidebar   = ! empty( styler_settings( 'blog_sidebar_templates', null ) ) || is_active_sidebar( $sidebar ) ? true : false;
$layout_column = !$has_sidebar || 'full-width' == $styler_layout ? 'col-lg-12' : 'col-lg-9';
$row_reverse   = (! empty( styler_settings( 'blog_sidebar_templates', null ) ) || is_active_sidebar( $sidebar ) ) && 'left-sidebar' == $styler_layout ? ' flex-lg-row-reverse' : '';
$post_style    = apply_filters('styler_blog_post_style', styler_settings( 'post_style', 'classic' ) );

?>

<div class="nt-styler-inner-container blog-area section-padding styler-blog-<?php echo esc_attr( $post_style  ); ?>">
    <div class="container-xl styler-container-xl">
        <div class="row justify-content-lg-center<?php echo esc_attr( $row_reverse ); ?>">

            <!-- Sidebar column control -->
            <div class="<?php echo esc_attr( $layout_column ); ?>">
                <div class="row row-cols-sm-<?php echo esc_attr( $grid_mobile_column ); ?> row-cols-lg-<?php echo esc_attr( $grid_column.$masonry ); ?> styler-posts-row">
                    <?php
                    if ( have_posts() ) {

                        while ( have_posts() ) {
                            the_post();

                            get_template_part( 'blog/style/'.$post_style );

                        }

                    } else {

                        // if there are no posts, read content none function
                        styler_content_none();

                    }
                    ?>
                </div>
                <?php
                // this function working with wp reading settins + posts
                styler_index_loop_pagination(true);
                ?>
            </div>
            <!-- End content column -->

            <!-- right sidebar -->
            <?php
            if ( $has_sidebar && ( 'right-sidebar' == $styler_layout || 'left-sidebar' == $styler_layout ) ) {

                get_sidebar();
            }
            ?>
            <!-- End right sidebar -->

        </div><!--End row -->
    </div><!--End container -->
</div><!--End #blog -->
