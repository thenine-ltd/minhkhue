<?php
/**
* Styler_Swatches
*/
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
if ( ! class_exists( 'Styler_Swatches' ) && class_exists( 'WC_Product' ) ) {
    class Styler_Swatches {
        function __construct()
        {
            add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'backend_scripts' ) );

            add_shortcode( 'styler_swatches', array( $this, 'archive_shortcode' ) );
            // add field for attributes
            add_filter( 'product_attributes_type_selector', array( $this, 'type_selector' ) );

            $attrs = wc_get_attribute_taxonomies();

            foreach ( $attrs as $attr ) {
                $name = $attr->attribute_name;
                add_action( 'pa_' . $name . '_add_form_fields', array($this,'show_field') );
                add_action( 'pa_' . $name . '_edit_form_fields', array( $this,'show_field') );
                add_action( 'create_pa_' . $name, array($this,'save_field') );
                add_action( 'edited_pa_' . $name, array( $this, 'save_field' ) );
                add_filter( "manage_edit-pa_{$name}_columns", array($this,'custom_columns') );
                add_filter( "manage_pa_{$name}_custom_column", array($this,'custom_columns_content'), 10, 3 );
            }

            add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array($this,'variation_attribute_options_html'), 199, 2 );

            // ajax add to cart
            add_action( 'wp_ajax_styler_swatches_add_to_cart', array( $this, 'add_to_cart' ) );
            add_action( 'wp_ajax_nopriv_styler_swatches_add_to_cart', array( $this, 'add_to_cart' ) );

        }

        public function frontend_scripts()
        {
            wp_enqueue_script( 'wc-add-to-cart-variation' );
            wp_enqueue_style( 'styler-swatches', STYLER_PLUGIN_URL . 'assets/front/js/swatches/swatches.css', STYLER_PLUGIN_VERSION );
            wp_enqueue_script( 'styler-swatches', STYLER_PLUGIN_URL . 'assets/front/js/swatches/swatches.js', array( 'jquery' ), STYLER_PLUGIN_VERSION, true );
        }

        public function backend_scripts()
        {
            wp_enqueue_script( 'styler-backend', STYLER_PLUGIN_URL . 'assets/front/js/swatches/backend.js', array('jquery','wp-color-picker'), STYLER_PLUGIN_VERSION, true );
            wp_localize_script( 'styler-backend', 'styler_swatches_vars', array('placeholder_img' => wc_placeholder_img_src()) );
        }

        public function archive_shortcode( $atts )
        {
            $atts = shortcode_atts( array(
                'id' => null,
            ), $atts, 'styler_swatches' );

            ob_start();
            $this->archive_swatches( $atts['id'] );
            return ob_get_clean();
        }

        public function add_to_cart()
        {
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'styler-special-string' ) ) {
                die( esc_html__( 'Permissions check failed', 'styler' ) );
            }

            $product_id   = (int) $_POST['product_id'];
            $variation_id = (int) $_POST['variation_id'];
            $quantity     = (float) $_POST['quantity'];
            $variation    = (array) json_decode( stripslashes( $_POST['attributes'] ) );

            if ( $product_id && $variation_id ) {
                $item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );

                if ( ! empty( $item_key ) ) {
                    echo true;
                }
            }
            die();
        }

        public function type_selector( $types )
        {
            global $pagenow;

            if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( $pagenow === 'post-new.php' ) || ( $pagenow === 'post.php' )  ) {
                return $types;
            } else {
                $types['color']  = esc_html__( 'Color', 'styler' );
                $types['image']  = esc_html__( 'Image', 'styler' );
                $types['button']  = esc_html__( 'Button', 'styler' );

                return $types;
            }
        }

        public function show_field( $term_or_tax )
        {
            if ( is_object( $term_or_tax ) ) {
                // is term
                $term_id    = $term_or_tax->term_id;
                $attr_id    = wc_attribute_taxonomy_id_by_name( $term_or_tax->taxonomy );
                $attr_info  = wc_get_attribute( $attr_id );
                $html_start = '<tr class="form-field"><th><label>';
                $html_mid   = '</label></th><td>';
                $html_end   = '</td></tr>';
            } else {
                // is taxonomy
                $term_id    = 0;
                $attr_id    = wc_attribute_taxonomy_id_by_name( $term_or_tax );
                $attr_info  = wc_get_attribute( $attr_id );
                $html_start = '<div class="form-field"><label>';
                $html_mid   = '</label>';
                $html_end   = '</div>';
            }
            $type = $attr_info->type;

            $tooltip = get_term_meta( $term_id, 'styler_swatches_tooltip', true );
            $value   = get_term_meta( $term_id, 'styler_'.$type, true );

            if ( $type == 'color' ) {
                echo $html_start . esc_html__( 'Color', 'styler' ) . $html_mid . '<input class="styler_swatches_color" id="styler_swatches_color" name="styler_swatches_color" value="' . esc_attr( $value ) . '" type="text"/>' . $html_end;
                echo $html_start . esc_html__( 'Tooltip', 'styler' ) . $html_mid . '<input id="styler_swatches_tooltip" name="styler_swatches_tooltip" value="' . esc_attr( $tooltip ) . '" type="text"/>' . $html_end;
            }
            if ( $type == 'image' ) {
                wp_enqueue_media();
                $image = $value ? wp_get_attachment_thumb_url( $value ) : wc_placeholder_img_src();

                echo $html_start . 'Image' . $html_mid; ?>
                    <div id="styler_swatches_image_thumbnail">
                        <img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px"/>
                    </div>
                    <div id="styler_swatches_image_wrapper">
                        <input type="hidden" id="styler_swatches_image" name="styler_swatches_image" value="<?php echo esc_attr( $value ); ?>"/>
                        <button id="styler_swatches_upload_image" type="button" class="styler_swatches_upload_image button"><?php esc_html_e( 'Upload/Add image', 'styler' ); ?></button>
                        <button id="styler_swatches_remove_image" type="button" class="styler_swatches_remove_image button"><?php esc_html_e( 'Remove image', 'styler' ); ?></button>
                    </div>
                <?php
                echo $html_end;
                echo $html_start . 'Tooltip' . $html_mid . '<input id="styler_swatches_tooltip" name="styler_swatches_tooltip" value="' . esc_attr( $tooltip ) . '" type="text"/>' . $html_end;
            }

            if ( $type == 'button' ) {
                echo $html_start . esc_html__( 'Label', 'styler' ) . $html_mid . '<input id="styler_swatches_button" name="styler_swatches_button" value="' . esc_attr( $value ) . '" type="text"/>' . $html_end;
                echo $html_start . esc_html__( 'Tooltip', 'styler' ) . $html_mid . '<input id="styler_swatches_tooltip" name="styler_swatches_tooltip" value="' . esc_attr( $tooltip ) . '" type="text"/>' . $html_end;
            }
        }

        public function save_field( $term_id )
        {
            $terms = [
                'styler_swatches_color',
                'styler_swatches_image',
                'styler_swatches_button',
                'styler_swatches_tooltip'
            ];
            foreach ( $terms as $term ) {
                if ( isset( $_POST[$term] ) ) {
                    update_term_meta( $term_id, $term, sanitize_text_field( $_POST[$term] ) );
                }
            }
        }

        public function variation_attribute_options_html( $html, $args )
        {
            $term_html = '';
            $shape     = styler_settings('variations_terms_shape');
            $hints     = styler_settings('variations_terms_hints_visibility', '0');
            $color_brd = styler_settings('product_attr_type_bordered', '0');
            $hide_icon = '1' == styler_settings('variations_terms_checked_closed_icon_visibility', '0') ? ' hide-icon' : '';
            $attr_id   = wc_attribute_taxonomy_id_by_name( $args['attribute'] );

            $options   = $args['options'];
            $product   = $args['product'];
            $attribute = $args['attribute'];

            if ( $attr_id ) {
                $attr_info    = wc_get_attribute( $attr_id );
                $curr['type'] = isset( $attr_info->type ) ? $attr_info->type : '';
                $curr['slug'] = isset( $attr_info->slug ) ? $attr_info->slug : '';
                $curr['name'] = isset( $attr_info->name ) ? $attr_info->name : '';

                if ( $curr['type'] == 'color' || $curr['type'] == 'image' || $curr['type'] == 'button' ) {
                    $term_html .= '<div class="styler-terms styler-type-'.esc_attr( $curr['type'] ).' terms-shape-'.$shape.' outline-'.$color_brd.$hide_icon.'" data-attribute="'.esc_attr( $args['attribute'] ).'">';
                    if ( $product && taxonomy_exists( $attribute ) ) {
                        $terms = wc_get_product_terms(
                            $product->get_id(),
                            $attribute,
                            array(
                                'fields' => 'all'
                            )
                        );
                        foreach ( $terms as $term ) {
                            if ( in_array( $term->slug, $options, true ) ) {
                                $tooltip = get_term_meta( $term->term_id, 'styler_swatches_tooltip', true ) ? : $term->name;
                                $color   = get_term_meta( $term->term_id, 'styler_swatches_color', true ) ? : '';
                                $image   = get_term_meta( $term->term_id, 'styler_swatches_image', true ) ? : '';
                                $img     = $image ? wp_get_attachment_thumb_url( $image ) : wc_placeholder_img_src();
                                $style   = ! empty( $color ) ? ' style="background-color:' . esc_attr( $color ) . '"' : '';
                                $hint   = '1' == $hints ? '<span class="term-hint">'.esc_html( $tooltip ).'</span>' : '';

                                if ( $curr['type'] == 'color' ) {
                                    if ( '1' == $color_brd ) {
                                        $term_html .= '<span class="styler-term" data-title="'.esc_attr( $tooltip ).'" data-term="'.esc_attr( $term->slug ).'"><span'.$style.'></span>'.$hint.'</span>';
                                    } else {
                                        $term_html .= '<span class="styler-term" data-title="'.esc_attr( $tooltip ).'" data-term="'.esc_attr( $term->slug ).'"'.$style.'>'.$hint.'</span>';
                                    }
                                }
                                if ( $curr['type'] == 'button' ) {
                                    $term_html .= '<span class="styler-term" data-title="'.esc_attr( $tooltip ).'" data-term="'.esc_attr( $term->slug ).'">'.esc_html( $term->name ).$hint.'</span>';
                                }
                                if ( $curr['type'] == 'image' ) {
                                    $term_html .= '<span class="styler-term" data-title="'.esc_attr( $tooltip ).'" data-term="'.esc_attr( $term->slug ).'"><img src="'.esc_url( $img ).'" alt="'.esc_attr( $term->name ).'"/>'.$hint.'</span>';
                                }
                            }
                        }
                    }
                    $term_html .= '</div>';
                }
            }
            return $term_html . $html;
        }

        public function custom_columns( $columns )
        {
            $columns['styler_swatches_value']   = esc_html__( 'Value', 'styler' );
            $columns['styler_swatches_tooltip'] = esc_html__( 'Tooltip', 'styler' );

            return $columns;
        }

        public function custom_columns_content( $columns, $column, $term_id )
        {
            if ( 'styler_swatches_value' === $column ) {
                $term = get_term( $term_id );
                $id   = wc_attribute_taxonomy_id_by_name( $term->taxonomy );
                $attr = wc_get_attribute( $id );

                switch ( $attr->type ) {
                    case 'image':
                    $val = get_term_meta( $term_id, 'styler_swatches_image', true );
                    echo '<img class="styler-column-item" src="' . esc_url( wp_get_attachment_thumb_url( $val ) ) . '"/>';
                    break;

                    case 'color':
                    $val = get_term_meta( $term_id, 'styler_swatches_color', true );
                    echo '<span class="styler-column-item" style="background-color: ' . esc_attr( $val ) . ';"></span>';
                    break;

                    case 'button':
                    $val = get_term_meta( $term_id, 'styler_swatches_button', true );
                    echo '<span class="styler-column-item">' . esc_attr( $val ) . '</span>';
                    break;
                }
            }

            if ( $column === 'styler_swatches_tooltip' ) {
                echo get_term_meta( $term_id, 'styler_swatches_tooltip', true );
            }
        }

        public function archive_swatches( $product_id = null )
        {
            if ( $product_id ) {
                $product = wc_get_product( $product_id );
            } else {
                global $product;
            }

            if ( ! $product || ! $product->is_type( 'variable' ) ) {
                return;
            }

            $attributes = $product->get_variation_attributes();
            $var_av     = $product->get_available_variations();
            $shape      = styler_settings('variations_terms_shape');
            $var_json   = wp_json_encode( $var_av );
            $var_attr   = function_exists( 'wc_esc_json' ) ? wc_esc_json( $var_json ) : _wp_specialchars( $var_json, ENT_QUOTES, 'UTF-8', true );

            if ( is_array( $attributes ) && ( count( $attributes ) > 0 ) ) {
                echo '<div class="variations_form styler-loop-swatches terms-shape-'.$shape.'" data-product_id="' . absint( $product->get_id() ) . '" data-product_variations="' . $var_attr . '">';
                    echo '<div class="styler-variations variations">';

                    foreach ( $attributes as $name => $opts ) { ?>
                        <div class="styler-variations-items styler-flex styler-align-center variation attr-<?php echo sanitize_title( $name ); ?>">
                            <div class="styler-small-title label"><?php echo wc_attribute_label( $name ); ?></div>
                            <div class="select">
                                <?php
                                $attr     = 'attribute_' . sanitize_title( $name );
                                $selected = isset( $_REQUEST[ $attr ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ $attr ] ) ) ) : $product->get_variation_default_attribute( $name );
                                wc_dropdown_variation_attribute_options( array(
                                    'options'          => $opts,
                                    'attribute'        => $name,
                                    'product'          => $product,
                                    'selected'         => $selected,
                                    'show_option_none' => esc_html__( 'Choose', 'styler' ) . ' ' . wc_attribute_label( $name )
                                ) );
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    echo '<div class="styler-reset-variations">' . apply_filters( 'woocommerce_reset_variations_link', '<a class="styler-btn-reset reset_variations" href="#">' . esc_html__( 'Clear', 'styler' ) . '</a>' ) . '</div>';
                    echo '</div>';
                echo '</div>';
            }
        }
    }
    new Styler_Swatches();
}
