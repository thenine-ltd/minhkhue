<?php
/**
* The basic template file
*/

$layout_column = is_active_sidebar( 'sidebar-1' ) && !is_search() && !is_archive() ? 'col-lg-9 pe-lg-5' : 'col-lg-12';
$layout_row    = !is_active_sidebar( 'sidebar-1' ) || is_search() || is_archive() ? ' row-cols-1 row-cols-sm-2 row-cols-lg-3 styler-masonry-container' : '';

if ( !is_active_sidebar( 'sidebar-1' ) || is_search() || is_archive() ) {
    wp_enqueue_script( 'imagesloaded' );
    wp_enqueue_script( 'masonry' );
}
?>
<div class="nt-styler-inner-container blog-area styler-blog-basic section-padding">
    <div class="container-xl styler-container-xl">
        <div class="row">

            <!-- Sidebar column control -->
            <div class="<?php echo esc_attr( $layout_column ); ?>">
                <div class="row<?php echo esc_attr( $layout_row ); ?>">
                <?php
                    if ( have_posts() ) {

                        while ( have_posts() ) {
                            the_post();

                            get_template_part( 'blog/style/default' );

                        }

                    } else {

                        // if there are no posts, read content none function
                        styler_content_none();

                    }
                ?>
                </div>
                <?php styler_index_loop_pagination(true); ?>
            </div>
            <!-- End content column -->

            <!-- right sidebar -->
            <?php
            if ( is_active_sidebar( 'sidebar-1' ) && !is_search() && !is_archive() ) {
                get_sidebar();
            }
            ?>
            <!-- End right sidebar -->

        </div><!--End row -->
    </div><!--End container -->
</div><!--End #blog -->
