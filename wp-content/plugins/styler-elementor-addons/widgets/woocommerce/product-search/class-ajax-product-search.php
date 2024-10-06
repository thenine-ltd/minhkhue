<?php
/*
Plugin Name: Woocommerce AJAX product search
Plugin URI: http://www.enovathemes.com
Description: Ajax product search for WooCommerce
Author: Enovathemes
Version: 1.0
Author URI: http://enovathemes.com
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if ( ! class_exists( 'Styler_Wc_Ajax_Search' ) ) {
    /**
    * Styler WooCommerce Ajax Search
    *
    * @since 1.0.0
    */
    class Styler_Wc_Ajax_Search {

        private static $instance = null;

        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
        /**
        * Constructor
        *
        * @return Styler_Wc_Ajax_Search
        * @since 1.0.0
        */
        public function __construct() {

            add_action( 'wp_enqueue_scripts', array( $this, 'search_plugin_scripts_styles' ) );

            add_action( 'create_term', [ $this, 'edit_product_term' ], 99, 3 );
            add_action( 'edit_term', [ $this, 'edit_product_term' ], 99, 3 );
            add_action( 'delete_term', [ $this, 'delete_product_term' ], 99, 4 );

            //add_action( 'save_post', [ $this,'save_post_action' ], 99, 3 );

            add_action( 'wp_ajax_styler_ajax_search_product', [ $this, 'search_product' ] );
            add_action( 'wp_ajax_nopriv_styler_ajax_search_product', [ $this, 'search_product' ] );

            // register shortcode.
            add_shortcode( 'styler_wc_ajax_product_search', [ $this, 'add_wc_ajax_search_shortcode' ] );
        }

        public function search_plugin_scripts_styles()
        {
            if (class_exists("Woocommerce")) {
                $rtl = is_rtl() ? '-rtl' : '';
                wp_enqueue_style( 'styler-ajax-product-search', STYLER_PLUGIN_URL. 'widgets/woocommerce/product-search/css-js/style'.$rtl.'.css' );
                wp_register_script( 'styler-ajax-product-search', STYLER_PLUGIN_URL . 'widgets/woocommerce/product-search/css-js/main.js', array( 'jquery' ), STYLER_PLUGIN_VERSION, true );
            }
        }

        /*  Get taxonomy hierarchy
        /*-------------------*/

        public function get_taxonomy_hierarchy( $taxonomy, $parent = 0, $exclude = 0) {
            $taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;
            $terms = get_terms( $taxonomy, array( 'parent' => $parent, 'hide_empty' => false, 'exclude' => $exclude) );

            $children = array();
            foreach ( $terms as $term ){
                $term->children = $this->get_taxonomy_hierarchy( $taxonomy, $term->term_id, $exclude);
                $children[ $term->term_id ] = $term;
            }
            return $children;
        }

        /*  List taxonomy hierarchy
        /*-------------------*/

        public function list_taxonomy_hierarchy_no_instance( $taxonomies) {

            foreach ( $taxonomies as $taxonomy ) {
                $children = $taxonomy->children;
                echo '<option value="'.$taxonomy->term_id.'">'.$taxonomy->name.'</option>';
                if ( is_array( $children ) && !empty( $children ) ) {
                    echo '<optgroup>';
                    $this->list_taxonomy_hierarchy_no_instance( $children );
                    echo '</optgroup>';
                }
            }
        }

        /*  Product categories transient
        /*-------------------*/

        public function get_product_categories_hierarchy() {

            if ( false === ( $categories = get_transient( 'styler-product-categories-hierarchy' ) ) ) {

                $categories = $this->get_taxonomy_hierarchy( 'product_cat', 0, 0);

                // do not set an empty transient - should help catch private or empty accounts.
                if ( ! empty( $categories ) ) {
                    $categories = base64_encode( serialize( $categories ) );
                    set_transient( 'styler-product-categories-hierarchy', $categories, apply_filters( 'null_categories_cache_time', 0 ) );
                }
            }

            if ( ! empty( $categories ) ) {

                return unserialize( base64_decode( $categories ) );

            } else {

                return new WP_Error( 'no_categories', esc_html__( 'No categories.', 'textdomain' ) );

            }
        }

        /*  Delete product categories transient
        /*-------------------*/

        public function edit_product_term($term_id, $tt_id, $taxonomy) {
            $term = get_term($term_id,$taxonomy);
            if (!is_wp_error($term) && is_object($term)) {
                $taxonomy = $term->taxonomy;
                if ( $taxonomy == "product_cat" ) {
                    delete_transient( 'styler-product-categories-hierarchy' );
                }
            }
        }

        public function delete_product_term($term_id, $tt_id, $taxonomy, $deleted_term) {
            if (!is_wp_error($deleted_term) && is_object($deleted_term)) {
                $taxonomy = $deleted_term->taxonomy;
                if ( $taxonomy == "product_cat" ) {
                    delete_transient( 'styler-product-categories-hierarchy' );
                }
            }
        }

        public function save_post_action( $post_id ){

            if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
            if (!current_user_can( 'edit_page', $post_id ) ) return;

            $post_info = get_post($post_id);

            if (!is_wp_error($post_info) && is_object($post_info)) {
                $content   = $post_info->post_content;
                $post_type = $post_info->post_type;

                if ( $post_type == "product" ) {
                    delete_transient( 'styler-product-categories' );
                }
            }
        }

        /*  Search action
        /*-------------------*/

        public function search_product() {

            global $wpdb, $woocommerce;

            if ( isset($_GET['keyword'] ) && !empty( $_GET['keyword'] ) ) {

                $keyword = $_GET['keyword'];

                if (isset($_GET['category']) && !empty($_GET['category'])) {

                    $category = $_GET['category'];

                    $querystr = "SELECT DISTINCT * FROM $wpdb->posts AS p
                    LEFT JOIN $wpdb->term_relationships AS r ON (p.ID = r.object_id)
                    INNER JOIN $wpdb->term_taxonomy AS x ON (r.term_taxonomy_id = x.term_taxonomy_id)
                    INNER JOIN $wpdb->terms AS t ON (r.term_taxonomy_id = t.term_id)
                    WHERE p.post_type IN ('product')
                    AND p.post_status = 'publish'
                    AND x.taxonomy = 'product_cat'
                    AND (
                        (x.term_id = {$category})
                    OR
                        (x.parent = {$category})
                    )
                    AND (
                        (p.ID IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%{$keyword}%'))
                    OR
                        (p.post_content LIKE '%{$keyword}%')
                    OR
                        (p.post_title LIKE '%{$keyword}%')
                    )
                    ORDER BY t.name ASC, p.post_date DESC;";

                } else {
                    $querystr = "SELECT DISTINCT $wpdb->posts.*
                    FROM $wpdb->posts, $wpdb->postmeta
                    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
                    AND (
                        ($wpdb->postmeta.meta_key = '_sku' AND $wpdb->postmeta.meta_value LIKE '%{$keyword}%')
                        OR
                            ($wpdb->posts.post_content LIKE '%{$keyword}%')
                        OR
                            ($wpdb->posts.post_title LIKE '%{$keyword}%')
                    )
                    AND $wpdb->posts.post_status = 'publish'
                    AND $wpdb->posts.post_type = 'product'
                    ORDER BY $wpdb->posts.post_date DESC";
                }

                $query_results = $wpdb->get_results($querystr);

                if (!empty($query_results)) {

                    $output = '';

                    foreach ($query_results as $result) {

                        $_product = wc_get_product( $result->ID );
                        $price    = $_product->get_price_html();
                        $stock    = get_post_meta($result->ID,'_stock_status');

                        $categories = wp_get_post_terms($result->ID, 'product_cat');

                        $output .= '<li>';
                            $output .= '<a class="styler-ajax-product-link" href="'.get_post_permalink($result->ID).'">';
                                $output .= '<div class="styler-ajax-product-image">';
                                    $output .= '<img src="'.esc_url(get_the_post_thumbnail_url($result->ID,'styler-panel')).'">';
                                $output .= '</div>';
                                $output .= '<div class="styler-ajax-product-data">';
                                    $output .= '<h5 class="styler-ajax-product-title">'.$result->post_title.'</h5>';
                                    if ( !empty( $price ) && '1' != styler_settings( 'woo_catalog_mode', '0' ) ) {
                                        $output .= '<div class="styler-ajax-product-price">';
                                            $output .= $price;
                                        $output .= '</div>';
                                    }

                                    if ( !empty( $stock ) && '1' != styler_settings( 'woo_catalog_mode', '0' ) ) {
                                        $output .= '<div class="styler-ajax-product-stock">'.$stock[0].'</div>';
                                    }

                                $output .= '</div>';
                            $output .= '</a>';
                        $output .= '</li>';
                    }

                    if ( !empty( $output ) ) {
                        echo $output;
                    }
                }
            }
            die();
        }

        public function add_wc_ajax_search_shortcode($attr='') {
            $args = shortcode_atts(array(
                'class' => '',
                'cats' => '',
                'select_text' => '',
                'search_text' => ''
            ), $attr );

            $attr        = $args['class'] != '' ? ' '.$args['class'] : '';
            $quick_text  = styler_settings('ajax_search_bottom_cats_title', '') ? styler_settings('ajax_search_bottom_cats_title', '') : esc_html__( 'Quick Links:', 'styler' );
            $select_text = styler_settings('ajax_search_cats_select_title', '') ? styler_settings('ajax_search_cats_select_title', '') : esc_html__( 'Select a category', 'styler' );
            $search_text = styler_settings('ajax_search_placeholder_title', '') ? styler_settings('ajax_search_placeholder_title', '') : esc_html__( 'Search for product...', 'styler' );

            wp_enqueue_script('styler-ajax-product-search');
            wp_enqueue_script( 'jquery-nice-select' );

            $product_categories = get_terms( 'product_cat', array(
                'orderby'    => 'name',
                'order'      => 'asc',
                'hide_empty' => '1' == styler_settings('ajax_cats_hide_empty', '') ? true : false
            ));

            ob_start();
            ?>
            <div class="styler-ajax-product-search<?php echo esc_attr( $attr ); ?>">
                <form role="search" name="styler-ajax-product-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php if ( $product_categories ) : ?>

                        <select name="category" class="styler-ajax-category">
                            <option class="styler-ajax-default" value=""><?php echo esc_html( $select_text ); ?></option>
                            <?php
                                echo wp_list_categories(array(
                                    'echo'       => true,
                                    'taxonomy'   => 'product_cat',
                                    'depth'      => 5,
                                    'hide_empty' => '1' == styler_settings('ajax_cats_hide_empty', '') ? true : false,
                                    'title_li'   => '',
                                    'walker'     => new Styler_WooCommerce_Categories_Select_Walker2()
                                ));
                            ?>
                        </select>

                    <?php endif ?>

                    <div class="styler-ajax-search-wrapper">
                        <input type="search" name="s" class="styler-ajax-search-input hide-clear" placeholder="<?php echo esc_attr( $search_text ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
                        <div class="styler-ajax-close-search-results"></div>
                        <button class="styler-ajax-search-submit" type="submit"><?php echo styler_svg_lists( 'search', 'styler-svg-icon' ); ?></button>
                        <input type="hidden" name="post_type" value="product">
                        <?php do_action( 'wpml_add_language_form_field' ); ?>
                    </div>
                </form>

                <div class="styler-ajax-search-results styler-scrollbar"><span class="loading-wrapper"><span class="ajax-loading"></span></span></div>
            </div>

            <?php if ( !empty( $product_categories ) && 'hide' != $args['cats'] && '0' != styler_settings('ajax_search_bottom_cats', '1') ) { ?>
                <div class="styler-product-categories category-area">

                    <h5 class="styler-ajax-product-title"><?php echo esc_html( $quick_text ); ?></h5>
                    <div class="styler-product-categories-inner styler-scrollbar">
                        <ul class="styler-wc-category-list">
                            <?php
                                echo wp_list_categories(array(
                                    'echo'       => true,
                                    'taxonomy'   => 'product_cat',
                                    'depth'      => 5,
                                    'hide_empty' => '1' == styler_settings('ajax_cats_hide_empty', '') ? true : false,
                                    'title_li'   => '',
                                    'exclude'    => [],
                                    'walker'     => new Styler_WooCommerce_Categories_Walker()
                                ));
                            ?>
                        </ul>
                    </div>
                </div>
            <?php } ?>
            <?php
            return ob_get_clean();
        }
    }
    Styler_Wc_Ajax_Search::get_instance();
}

if ( !class_exists('Styler_WooCommerce_Categories_Select_Walker2') ) {
    class Styler_WooCommerce_Categories_Select_Walker2 extends Walker {
        /**
        * What the class handles.
        *
        * @var string
        */
        public $tree_type = 'product_cat';

        /**
        * DB fields to use.
        *
        * @var array
        */
        public $db_fields = array(
            'parent' => 'parent',
            'id'     => 'term_id',
            'slug'   => 'slug',
        );

        /**
        * Start the element output.
        *
        * @see Walker::start_el()
        * @since 2.1.0
        *
        * @param string  $output            Passed by reference. Used to append additional content.
        * @param object  $cat               Category.
        * @param int     $depth             Depth of category in reference to parents.
        * @param array   $args              Arguments.
        * @param integer $current_object_id Current object ID.
        */
        public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {
            $cat_id = intval( $cat->term_id );
            $space  = $depth > 0 ? '&nbsp;&nbsp;&nbsp;' : '';

            $output .= '<option class="cat-item cat-item-'.$cat_id.' cat-depth-'.$depth;

            if ( $args['current_category'] === $cat_id ) {
                $output .= ' current-cat';
            }

            if ( $args['has_children'] && $args['hierarchical'] && ( empty( $args['max_depth'] ) || $args['max_depth'] > $depth + 1 ) ) {
                $output .= ' cat-parent';
            }

            if ( isset($args['current_category_ancestors']) && $args['current_category'] && in_array( $cat_id, $args['current_category_ancestors'], true ) ) {
                $output .= ' current-cat-parent';
            }

            $output .= '" value="'.$cat->term_id.'">'.$space.$cat->name.'</option>';
        }

        /**
        * Ends the element output, if needed.
        *
        * @see Walker::end_el()
        * @since 2.1.0
        *
        * @param string $output Passed by reference. Used to append additional content.
        * @param object $cat    Category.
        * @param int    $depth  Depth of category. Not used.
        * @param array  $args   Only uses 'list' for whether should append to output.
        */
        public function end_el( &$output, $cat, $depth = 0, $args = array() ) {
            //$output .= "</option>\n";
        }

        /**
        * Traverse elements to create list from elements.
        *
        * Display one element if the element doesn't have any children otherwise,
        * display the element and its children. Will only traverse up to the max.
        * depth and no ignore elements under that depth. It is possible to set the.
        * max depth to include all depths, see walk() method.
        *
        * This method shouldn't be called directly, use the walk() method instead.
        *
        * @since 2.5.0
        *
        * @param object $element           Data object.
        * @param array  $children_elements List of elements to continue traversing.
        * @param int    $max_depth         Max depth to traverse.
        * @param int    $depth             Depth of current element.
        * @param array  $args              Arguments.
        * @param string $output            Passed by reference. Used to append additional content.
        * @return null Null on failure with no changes to parameters.
        */
        public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
            if ( ! $element || ( 0 === $element->count && ! empty( $args[0]['hide_empty'] ) ) ) {
                return;
            }
            parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
        }
    }
}

if ( !class_exists('Styler_WooCommerce_Categories_Walker') ) {
    class Styler_WooCommerce_Categories_Walker extends Walker
    {
        /**
        * DB fields to use.
        *
        * @var array
        */
        public $db_fields = array(
            'parent' => 'parent',
            'id'     => 'term_id',
            'slug'   => 'slug',
        );
        /**
        * Starts the list before the elements are added.
        *
        * @see Walker::start_lvl()
        * @since 2.1.0
        *
        * @param string $output Passed by reference. Used to append additional content.
        * @param int    $depth Depth of category. Used for tab indentation.
        * @param array  $args Will only append content if style argument value is 'list'.
        */
        public function start_lvl( &$output, $depth = 0, $args = array() ) {
            if ( 'list' !== $args['style'] ) {
                return;
            }
            $rtl = is_rtl() ? 'left' : 'right';
            $indent  = str_repeat( "\t", $depth );
            $output .= "$indent<span class='dropdown-btn fas fa-angle-$rtl'></span><ul class='styler-wc-cats-children submenu depth-$depth'>\n";
        }

        /**
        * Ends the list of after the elements are added.
        *
        * @see Walker::end_lvl()
        * @since 2.1.0
        *
        * @param string $output Passed by reference. Used to append additional content.
        * @param int    $depth Depth of category. Used for tab indentation.
        * @param array  $args Will only append content if style argument value is 'list'.
        */
        public function end_lvl( &$output, $depth = 0, $args = array() ) {
            if ( 'list' !== $args['style'] ) {
                return;
            }

            $indent  = str_repeat( "\t", $depth );
            $output .= "$indent</ul>\n";
        }

        /**
        * Start the element output.
        *
        * @see Walker::start_el()
        * @since 2.1.0
        *
        * @param string  $output            Passed by reference. Used to append additional content.
        * @param object  $cat               Category.
        * @param int     $depth             Depth of category in reference to parents.
        * @param array   $args              Arguments.
        * @param integer $current_object_id Current object ID.
        */
        public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {
            $cat_id       = intval( $cat->term_id );
            $thumbnail_id = get_term_meta( $cat_id, 'thumbnail_id', true );
            $imgurl       = wp_get_attachment_image_url($thumbnail_id,[30,30]);
            $imgsrc       = $imgurl ? $imgurl : '';
            $is_widget    =  isset( $args['is_widget'] ) ? '1' : '0';
            $thumb_display = $is_widget ? $args['cat_img'] : styler_settings( 'header_woo_category_thumb_visibility', 1 );

            $thumb = '1' == $thumb_display && $imgsrc ? '<img width="30" height="30" src="'.esc_url( $imgsrc ).'" alt="'.esc_attr( $cat->name ).'"/>' : '';

            $output .= '<li class="cat-item cat-item-' . $cat_id;

            if ( $args['current_category'] === $cat_id ) {
                $output .= ' current-cat';
            }

            if ( $args['has_children'] && $args['hierarchical'] && ( empty( $args['max_depth'] ) || $args['max_depth'] > $depth + 1 ) ) {
                $output .= ' cat-parent';
            }

            if ( isset($args['current_category_ancestors']) && $args['current_category'] && in_array( $cat_id, $args['current_category_ancestors'], true ) ) {
                $output .= ' current-cat-parent';
            }

            if ( isset( $_GET['filter_cat'] ) ) {
                if ( in_array( $cat_id, explode( ',', $_GET['filter_cat'] ) ) ) {
                    $checkbox = 'checked';
                }
            }

            $output .= '">';
            $output .= '<a class="product_cat" href="'.esc_url( get_term_link( $cat_id ) ).'">';
            $output .= $thumb;
            $output .= '<span class="category-title">'.$cat->name.'</span>';
            if ( isset( $args['cat_count'] ) ) {
                $output .= '<span class="category-count">'.$cat->count.'</span>';
            }
            $output .= '</a>';
        }

        /**
        * Ends the element output, if needed.
        *
        * @see Walker::end_el()
        * @since 2.1.0
        *
        * @param string $output Passed by reference. Used to append additional content.
        * @param object $cat    Category.
        * @param int    $depth  Depth of category. Not used.
        * @param array  $args   Only uses 'list' for whether should append to output.
        */
        public function end_el( &$output, $cat, $depth = 0, $args = array() ) {
            $output .= "</li>\n";
        }

        /**
        * Traverse elements to create list from elements.
        *
        * Display one element if the element doesn't have any children otherwise,
        * display the element and its children. Will only traverse up to the max.
        * depth and no ignore elements under that depth. It is possible to set the.
        * max depth to include all depths, see walk() method.
        *
        * This method shouldn't be called directly, use the walk() method instead.
        *
        * @since 2.5.0
        *
        * @param object $element           Data object.
        * @param array  $children_elements List of elements to continue traversing.
        * @param int    $max_depth         Max depth to traverse.
        * @param int    $depth             Depth of current element.
        * @param array  $args              Arguments.
        * @param string $output            Passed by reference. Used to append additional content.
        * @return null Null on failure with no changes to parameters.
        */
        public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
            if ( ! $element || ( 0 === $element->count && ! empty( $args[0]['hide_empty'] ) ) ) {
                return;
            }
            parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
        }
    }
}
