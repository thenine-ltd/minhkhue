<?php

/*************************************************
## Load More Button
*************************************************/
function styler_load_more_button(){
    $current_page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    $max_page     = wc_get_loop_prop( 'total_pages' );
    if ( $max_page > $current_page ) {
        echo '<div class="row row-more styler-more mt-30">
        <div class="col-12 nt-pagination styler-justify-center">
        <div class="button styler-load-more" data-title="'.esc_html__('Loading...','styler').'">'.esc_html__('Load More','styler').'</div>
        </div>
        </div>';
    }
}


/*************************************************
## Infinite Pagination
*************************************************/
function styler_infinite_scroll(){
    $current_page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    $max_page     = wc_get_loop_prop( 'total_pages' );
    if ( $max_page > $current_page ) {
        echo '<div class="row row-infinite styler-more mt-30">
        <div class="col-12 nt-pagination styler-justify-center">
        <div class="styler-load-more" data-title="'.esc_html__('Loading...','styler').'">'.esc_html__('Loading...','styler').'</div>
        </div>
        </div>';
    }
}


/*************************************************
## Load More CallBack
*************************************************/
add_action( 'wp_ajax_nopriv_styler_shop_load_more', 'styler_shop_load_more' );
add_action( 'wp_ajax_styler_shop_load_more', 'styler_shop_load_more' );
function styler_shop_load_more() {

    $args = array(
        's'              => $_POST['s'],
        'post_type'      => 'product',
        'posts_per_page' => $_POST['per_page'],
        'paged'          => $_POST['current_page'] + 1,
        'posts_status'   => 'publish'
    );

    // Price Slider
    if ( $_POST['min_price'] != null || $_POST['max_price'] != null ) {
        $args['meta_query'][] = wc_get_min_max_price_meta_query( array(
          'min_price' => $_POST['min_price'],
          'max_price' => $_POST['max_price']
        ));
    }

    // On Sale Products
    if ( isset( $_POST['on_sale'] ) && $_POST['on_sale'] == 'yes' ) {
        $args['post__in'] = wc_get_product_ids_on_sale();
    }

    // In Stock Products
    if ( isset( $_POST['in_stock'] ) && $_POST['in_stock'] == 'yes' ) {
        $args['meta_query'][] = array(
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '='
        );
    }
    // Orderby
    $orderby_value = isset( $_POST['orderby'] ) ? wc_clean( (string) wp_unslash( $_POST['orderby'] ) ) : wc_clean( get_query_var( 'orderby' ) );

    if ( ! $orderby_value ) {
        if ( $_POST['is_search'] == 'yes' ) {
            $orderby_value = 'relevance';
        } else {
            $orderby_value = apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
        }
    }

    switch ( $orderby_value ) {
        case 'menu_order':
        $args['orderby'] = 'menu_order title';
        $args['order']   = 'ASC';
        break;
        case 'relevance':
        $args['orderby'] = 'relevance';
        $args['order']   = 'DESC';
        break;
        case 'price':
        add_filter( 'posts_clauses', array( WC()->query, 'order_by_price_asc_post_clauses' ) );
        break;
        case 'price-desc':
        add_filter( 'posts_clauses', array( WC()->query, 'order_by_price_desc_post_clauses' ) );
        break;
        case 'popularity':
        $args['meta_key'] = 'total_sales';
        add_filter( 'posts_clauses', array( WC()->query, 'order_by_popularity_post_clauses' ) );
        break;
        case 'rating':
        $args['meta_key'] = '_wc_average_rating'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        $args['order']    = 'DESC';
        $args['orderby']  = 'meta_value_num';
        add_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );
        break;
    }

    $args['tax_query'] = array(
        'relation' => 'AND'
    );
    $args['tax_query'][] = array(
        'taxonomy'  => 'product_visibility',
        'terms'     => array( 'exclude-from-catalog' ),
        'field'     => 'name',
        'operator'  => 'NOT IN'
    );

    // Product Category Filter Widget on shop page
    if ( $_POST['filter_cat'] != null ) {
        if ( !empty( $_POST['filter_cat'] ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'id',
                'terms'    => explode( ',', $_POST['filter_cat'] )
            );
        }
    }

    // Product Category Page
    if ( $_POST['cat_id'] != null ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'id',
            'terms'    => $_POST['cat_id']
        );
    }

    // Product Tag Page
    if ( $_POST['tag_id'] != null ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_tag',
            'field'    => 'id',
            'terms'    => $_POST['tag_id']
        );
    }

    // Product Brands Filter Widget on shop page
    if ( $_POST['is_brand'] == 'yes' && $_POST['brand_id'] != null ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'styler_product_brands',
            'field'    => 'id',
            'terms'    => explode( ',', $_POST['filter_brand'] )
        );
    }

    // Product Brands Page
    if ( $_POST['is_brand'] == 'yes' && $_POST['brand_id'] != null ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'styler_product_brands',
            'field'    => 'id',
            'terms'    => $_POST['brand_id']
        );
    }

    // Product Filter By widget
    if ( isset( $_POST['layered_nav'] ) ) {
        $choosen_attributes = $_POST['layered_nav'];

        foreach ( $choosen_attributes as $taxonomy => $data ) {
            $args['tax_query'][] = array(
                'taxonomy'         => $taxonomy,
                'field'            => 'slug',
                'terms'            => $data['terms'],
                'operator'         => 'and' === $data['query_type'] ? 'AND' : 'IN',
                'include_children' => false
            );
        }
    }

    $type   = styler_settings( 'shop_product_type', 3 );
    $column = '';

    if ( isset( $_POST['product_style'] ) && $_POST['product_style'] ) {
        $type = $_POST['product_style'];
    }
    if ( isset( $_POST['column'] ) && $_POST['column'] ) {
        $column = $_POST['column'];
    }

    $animation  = apply_filters( 'styler_loop_product_animation', styler_settings( 'shop_product_animation_type', 'fadeInUp' ) );
    $css_class  = 'styler-product-type-'.$type;
    $css_class .= $column == '1' ? '' : ' animated '.$animation;

    //Loop
    $loop = new WP_Query( $args );
    if ( $loop->have_posts() ) {
        while ( $loop->have_posts() ) {
            $loop->the_post();
            global $product;

            // Ensure visibility.
            if ( !empty( $product ) && $product->is_visible() ) {
            ?>
            <div <?php wc_product_class( $css_class, $product ); ?> data-product-animation="<?php echo esc_attr( $animation ); ?>">

                <?php styler_loop_product_layout_manager($type,$column); ?>

            </div>
            <?php
            }
        }
    }
    wp_reset_postdata();
    wp_die();
}
