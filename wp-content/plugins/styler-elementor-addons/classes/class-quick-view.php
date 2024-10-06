<?php
/**
* Styler Quick View
*/
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
if ( ! class_exists( 'Styler_QuickView' ) ) {
    class Styler_QuickView
    {
        private static $instance = null;

        function __construct()
        {
            // frontend scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            // shortcode
            add_shortcode( 'styler_quickview', array( $this, 'quick_view_shortcode' ) );

            // frontend template
            add_action( 'wp_ajax_styler_quickview', array( $this, 'quick_view_template' ) );
            add_action( 'wp_ajax_nopriv_styler_quickview', array( $this, 'quick_view_template' ) );

        }

        public function enqueue_scripts()
        {
            wp_enqueue_script( 'styler-quickview', STYLER_PLUGIN_URL . 'assets/front/js/quickview/quickview.js', array( 'jquery' ), STYLER_PLUGIN_VERSION, true );
        }

        public function quick_view_shortcode( $atts )
        {
            $output = '';

            $atts = shortcode_atts( array(
                'id'    => null,
                'title' => '',
                'icon'  =>''
            ), $atts, 'styler_quickview' );

            if ( ! $atts['id'] ) {
                global $product;
                $atts['id'] = $product->get_id();
            }

            $title = $atts['title'] ? esc_html($atts['title']) : esc_html__('Quick View', 'styler');
            $icon = $atts['icon'] ? $atts['icon'] : styler_svg_lists( 'eye', 'styler-svg-icon' );
            $html = '<div class="styler-quickview-btn styler-product-button" data-id="'.esc_attr( $atts['id'] ).'" data-label="'.$title.'">'.$icon.'</div>';

            return apply_filters( 'styler_quickview_html', $html, $atts['id'] );
        }

        public function quick_view_template()
        {
            global $post, $product;
            $product_id   = absint( $_GET['product_id'] );
            $catalog_mode = styler_settings( 'woo_catalog_mode', '0' );
            $product      = wc_get_product( $product_id );

            if ( $product ) {
                $post = get_post( $product_id );
                setup_postdata( $post );

                $images = $product->get_gallery_image_ids();
                $size   = apply_filters( 'styler_quickview_product_thumb_size', 'large' );

                ?>
                <div id="product-<?php echo $product_id; ?>" <?php wc_product_class( 'styler-quickview-wrapper single-content zoom-anim-dialog', $product ); ?>>
                    <div class="container-full styler-container-full">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="styler-swiper-slider-wrapper">
                                    <div class="styler-quickview-main styler-swiper-main swiper">
                                        <?php styler_single_product_labels(); ?>
                                        <div class="swiper-wrapper">
                                            <?php
                                            styler_single_product_labels();
                                            echo '<div class="swiper-slide">'.get_the_post_thumbnail( $product->get_id(), $size ).'</div>';
                                            foreach( $images as $image ) {
                                                echo '<div class="swiper-slide"><img src="'.wp_get_attachment_image_url($image,'styler-square').'" alt="'.esc_html( $product->get_name() ).'"/></div>';
                                            }
                                            ?>
                                        </div>
                                        <?php if ( is_rtl() ) { ?>
                                            <div class="swiper-button-next styler-swiper-next"></div>
                                            <div class="swiper-button-prev styler-swiper-prev"></div>
                                        <?php } else { ?>
                                            <div class="swiper-button-prev styler-swiper-prev"></div>
                                            <div class="swiper-button-next styler-swiper-next"></div>
                                        <?php } ?>
                                    </div>

                                    <div class="styler-quickview-thumbnails styler-swiper-thumbnails swiper">
                                        <div class="swiper-wrapper">
                                            <?php
                                            echo '<div class="swiper-slide">'.get_the_post_thumbnail( $product->get_id(), 'thumbnail' ).'</div>';
                                            foreach( $images as $image ) {
                                                $img = '<img width="80" height="80" src="'.wp_get_attachment_image_url($image,'styler-panel').'" alt="'.esc_html( $product->get_name() ).'"/>';
                                                echo '<div class="swiper-slide">'.$img.'</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="styler-quickview-product-details styler-product-summary">
                                    <?php the_title( '<h4 class="styler-product-title">', '</h4>' ); ?>
                                    <?php
                                    if ( '1' != $catalog_mode ) {
                                        woocommerce_template_single_price();
                                    }
                                    ?>
                                    <?php if ( has_excerpt() ) { ?>
                                        <div class="styler-summary-item"><?php the_excerpt(); ?></div>
                                    <?php } ?>
                                    <?php if ( '1' != $catalog_mode ) { ?>
                                        <div class="styler-summary-item template-add-to-cart"><?php woocommerce_template_loop_add_to_cart($product); ?></div>
                                    <?php } ?>
                                    <div class="styler-summary-item">
                                        <?php echo $this->get_product_attributes( $product ); ?>
                                        <?php if ( $product->get_sku() ) { ?>
                                            <div class="styler-sku-wrapper">
                                                <span><?php esc_html_e('SKU:','styler' ); ?></span>
                                                <span class="styler-sku"><?php echo esc_html( $product->get_sku() ); ?></span>
                                            </div>
                                        <?php } ?>
                                        <?php woocommerce_template_single_meta(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                wp_reset_postdata();
            }
            die();
        }

        public function get_product_attributes( $product )
        {
            $product_attributes = array();

            // Display weight and dimensions before attribute list.
            $display_dimensions = apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() );

            if ( $display_dimensions && $product->has_weight() )
            {
                $product_attributes['weight'] = array(
                    'label' => __( 'Weight', 'styler' ),
                    'value' => wc_format_weight( $product->get_weight() ),
                );
            }

            if ( $display_dimensions && $product->has_dimensions() ) {
                $product_attributes['dimensions'] = array(
                    'label' => __( 'Dimensions', 'styler' ),
                    'value' => wc_format_dimensions( $product->get_dimensions( false ) ),
                );
            }

            // Add product attributes to list.
            $attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );
            if ( !empty( $attributes ) ) {
                echo '<ul class="styler-attr-list">';
                foreach ( $attributes as $attribute ) {
                    $values = array();

                    if ( $attribute->is_taxonomy() ) {
                        $attribute_taxonomy = $attribute->get_taxonomy_object();
                        $attribute_values   = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

                        foreach ( $attribute_values as $attribute_value ) {
                            $value_name = esc_html( $attribute_value->name );

                            if ( $attribute_taxonomy->attribute_public ) {
                                $values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attribute->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
                            } else {
                                $values[] = $value_name;
                            }
                        }
                    }

                    $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ] = array(
                        'label' => wc_attribute_label( $attribute->get_name() ),
                        'value' => apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values )
                    );
                    $label = $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ]['label'];
                    $value = $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ]['value'];
                    echo '<li class="styler-attr-item">';
                    echo !empty( $label ) ? '<span class="styler-attr-label">'.$label.': </span>' : '';
                    echo !empty( $value ) ? '<div class="styler-attr-value">'.$value.'</div>' : '';
                    echo '</li>';
                }
                echo '</ul>';
            }
        }

        public static function get_instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
    }
    Styler_QuickView::get_instance();
}
