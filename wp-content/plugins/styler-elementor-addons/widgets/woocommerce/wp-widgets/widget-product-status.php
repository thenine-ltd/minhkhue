<?php

class Styler_Widget_Product_Status extends WP_Widget {

    // Widget Settings
    function __construct() {
        $widget_ops = array('description' => esc_html__('For Product Archive Page.','styler') );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'product_status' );
        parent::__construct( 'product_status', esc_html__('Styler Product Status','styler'), $widget_ops, $control_ops );

        add_action( 'woocommerce_product_query', [ $this, 'product_query' ], 10, 2 );
        add_action( 'styler_choosen_filters', [ $this, 'choosen_filters' ], 10 );
        add_filter( 'woocommerce_widget_get_current_page_url', [ $this, 'current_page_url' ], 10, 2 );
    }

    /*************************************************
    ## Styler Current Page URL
    *************************************************/

    function current_page_url( $link, $that ){
        if ( isset( $_GET['filter_cat'] ) ) {
            $link = add_query_arg( 'filter_cat', wc_clean( wp_unslash( $_GET['filter_cat'] ) ), $link );
        }

        if ( isset( $_GET['brand_id'] ) ) {
            $link = add_query_arg( 'brand_id', wc_clean( wp_unslash( $_GET['brand_id'] ) ), $link );
        }
        if ( isset( $_GET['shop_view'] ) ) {
            $link = add_query_arg( 'shop_view', wc_clean( wp_unslash( $_GET['shop_view'] ) ), $link );
        }

        if ( isset( $_GET['on_sale'] ) ) {
            $link = add_query_arg( 'on_sale', wc_clean( wp_unslash( $_GET['on_sale'] ) ), $link );
        }

        if ( isset( $_GET['stock_status'] ) ) {
            $link = add_query_arg( 'stock_status', wc_clean( wp_unslash( $_GET['stock_status'] ) ), $link );
        }

        if ( isset( $_GET['ft'] ) ) {
            $link = add_query_arg( 'ft', wc_clean( wp_unslash( $_GET['ft'] ) ), $link );
        }

        return $link;
    }
    /*************************************************
    ## Styler Remove Filter
    *************************************************/
    function choosen_filters()
    {
        $output = '';

        $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
        $min_price = isset( $_GET['min_price'] ) ? wc_clean( $_GET['min_price'] ) : 0;
        $max_price = isset( $_GET['max_price'] ) ? wc_clean( $_GET['max_price'] ) : 0;

        if ( ! empty( $_chosen_attributes ) || isset( $_GET['filter_cat'] ) || isset( $_GET['brand_id'] ) && !empty( $_GET['brand_id'] ) || 0 < $min_price || 0 < $max_price || $this->get_stock_status() == 'instock' || $this->get_on_sale() == 'onsale' ) {

            global $wp;

            if ( '' === get_option( 'permalink_structure' ) ) {
                $baselink = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
            } else {
                $baselink = preg_replace( '%\/page/[0-9]+%', '', home_url( add_query_arg( null, null ) ) );
            }

            $output .= '<div class="styler-choosen-filters"><ul class="styler-remove-filter">';

            $output .= '<li class="clear-li"><a href="'.esc_url( remove_query_arg( array_keys( $_GET ) ) ).'" class="styler-remove-filter-element clear-all"><span class="remove-filter"></span>'.esc_html__( 'Clear filters', 'styler' ).'</a></li>';

            if ( ! empty( $_chosen_attributes ) ) {
                foreach ( $_chosen_attributes as $taxonomy => $data ) {
                    foreach ( $data['terms'] as $term_slug ) {
                        $term = get_term_by( 'slug', $term_slug, $taxonomy );

                        $filter_name = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
                        $explode_old = explode(',',$_GET[$filter_name]);
                        $explode_termid = explode(',',$term->slug);
                        $stylerdata = array_diff( $explode_old, $explode_termid);
                        $stylerdataimplode = implode(',',$stylerdata);

                        if ( empty( $stylerdataimplode ) ) {
                            $link = remove_query_arg( $filter_name );
                        } else {
                            $link = add_query_arg( $filter_name, implode(',', $stylerdata ), $baselink );
                        }
                        $output .= '<li><a href="'.esc_url( $link ).'" class="styler-remove-filter-element attributes"><span class="remove-filter"></span>'.esc_html( $term->name ).'</a></li>';
                    }
                }
            }

            if ( $this->get_stock_status() == 'instock' ) {
                $output .= '<li><a href="'.esc_url(remove_query_arg('stock_status')).'" class="styler-remove-filter-element stock_status"><span class="remove-filter"></span>'.esc_html__('In Stock','styler').'</a></li>';
            }

            if ( $this->get_on_sale() == 'onsale' ) {
                $output .= '<li><a href="'.esc_url(remove_query_arg('on_sale')).'" class="styler-remove-filter-element on_sale"><span class="remove-filter"></span>'.esc_html__('On Sale','styler').'</a></li>';
            }

            if ( $min_price ) {
                $output .= '<li><a href="'.esc_url(remove_query_arg('min_price')).'" class="styler-remove-filter-element min_price"><span class="remove-filter"></span>' . sprintf( __( 'Min %s', 'styler' ), wc_price( $min_price ) ) . '</a></li>';
            }

            if ( $max_price ) {
                $output .= '<li><a href="'.esc_url(remove_query_arg('max_price')).'" class="styler-remove-filter-element max_price"><span class="remove-filter"></span>' . sprintf( __( 'Max %s', 'styler' ), wc_price( $max_price ) ) . '</a></li>';
            }

            if ( isset( $_GET['filter_cat'] ) ) {
                $terms = get_terms( array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false,
                    'parent' => 0,
                    'include' => explode(',',$_GET['filter_cat']),
                ) );

                foreach ( $terms as $term ) {
                    $term_children = get_term_children( $term->term_id, 'product_cat' );
                    $output .= '<li><a href="'.esc_url( styler_get_cat_url($term->term_id) ).'" class="styler-remove-filter-element product_cat" id="'.esc_attr( $term->term_id ).'"><span class="remove-filter"></span>'.esc_html( $term->name ).'</a></li>';
                    if( $term_children ) {
                        foreach( $term_children as $child ) {
                            $childterm = get_term_by( 'id', $child, 'product_cat' );
                            if ( in_array( $childterm->term_id, explode(',', $_GET['filter_cat'] ) ) ) {
                                $output .= '<li><a href="'.esc_url( styler_get_cat_url( $childterm->term_id ) ).'" class="styler-remove-filter-element product_cat" id="'.esc_attr( $childterm->term_id ).'"><span class="remove-filter"></span>'.esc_html( $childterm->name ).'</a></li>';
                            }
                        }
                    }
                }
            }
            if ( isset( $_GET['brand_id'] ) ) {
                $terms = get_terms( array(
                    'taxonomy'   => 'styler_product_brands',
                    'hide_empty' => false,
                    'parent'     => 0,
                    'include'    => explode( ',',$_GET['brand_id'] )
                ));

                foreach ( $terms as $term ) {
                    $output .= '<li><a href="'.esc_url( styler_get_brand_url( $term->term_id ) ).'" class="styler-remove-filter-element styler_product_brands" id="'.esc_attr( $term->term_id ).'"><span class="remove-filter"></span>'.esc_html( $term->name ).'</a></li>';
                }
            }
            $output .= '</ul></div>';
        }
        echo $output;

    }

    function get_stock_status()
    {
        $stock_status = isset( $_GET['stock_status'] ) ? $_GET['stock_status'] : '';

        if ( $stock_status == 'instock' ) {
            return $stock_status;
        }
    }

    function get_on_sale()
    {
        $on_sale = isset( $_GET['on_sale'] ) ? $_GET['on_sale'] : '';

        if ( $on_sale == 'onsale' ) {
            return $on_sale;
        }
    }

    function product_query( $q )
    {
        if ( $this->get_stock_status() == 'instock' ) {
            $q->set( 'meta_query', array (
                array(
                    'meta_key' => '_stock_status',
                    'value' => 'instock',
                )
            ));
        }

        if ( $this->get_on_sale() == 'onsale' ) {
            $q->set ( 'post__in', wc_get_product_ids_on_sale() );
        }
    }

    // Widget Output
    function widget( $args, $instance )
    {
        extract( $args );
        $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );

        echo $before_widget;

        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        if ( $this->get_stock_status() == 'instock' ) {
            $checkbox = 'checked';
            $stocklink = remove_query_arg('stock_status');
        } else {
            $checkbox = '';
            $stocklink = add_query_arg('stock_status','instock');
        }

        if ( $this->get_on_sale() == 'onsale' ) {
            $onsalecheckbox = 'checked';
            $salelink = remove_query_arg('on_sale');
        } else {
            $onsalecheckbox = '';
            $salelink = add_query_arg('on_sale','onsale');
        }

        echo '<div class="widget-body site-checkbox-lists styler-product-status">';
        echo '<div class="site-scroll">';
        echo '<ul>';

        echo '<li>';
        echo '<a href="'.esc_url($stocklink).'">';
        echo '<input name="stockonsale" value="instock" id="instock" type="checkbox" '.esc_attr($checkbox).'>';
        echo '<label><span></span>'.esc_html__('In Stock','styler').'</label>';
        echo '</a>';
        echo '</li>';

        echo '<li>';
        echo '<a href="'.esc_url($salelink).'">';
        echo '<input name="stockonsale" value="onsale" id="onsale" type="checkbox" '.esc_attr($onsalecheckbox).'>';
        echo '<label><span></span>'.esc_html__('On Sale','styler').'</label>';
        echo '</a>';
        echo '</li>';

        echo '</ul>';
        echo '</div>';
        echo '</div>';

        echo $after_widget;
    }

    // Update
    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    // Backend Form
    function form( $instance )
    {
        $defaults = array('title' => 'Product Status');
        $instance = wp_parse_args((array) $instance, $defaults); ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Title:', 'styler' ); ?></label>
            <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>
        <?php
    }
}

// Add Widget
function widget_product_status_init() {
    register_widget( 'Styler_Widget_Product_Status' );
}
add_action('widgets_init', 'widget_product_status_init');

?>
