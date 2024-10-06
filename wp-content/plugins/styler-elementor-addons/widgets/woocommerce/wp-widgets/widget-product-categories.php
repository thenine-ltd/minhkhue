<?php
class Styler_Widget_Product_Categories_Walker extends Walker {
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

        $indent  = str_repeat( "\t", $depth );
        $plus    = styler_svg_lists('plus');
        $output .= "$indent<span class='subDropdown plus'>$plus</span><ul class='children'>\n";
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
        $cat_id = intval( $cat->term_id );

        $output .= '<li class="cat-item cat-item-' . $cat_id;

        if ( $args['current_category'] === $cat_id ) {
            $output .= ' current-cat';
        }

        if ( $args['has_children'] && $args['hierarchical'] && ( empty( $args['max_depth'] ) || $args['max_depth'] > $depth + 1 ) ) {
            $output .= ' cat-parent';
        }

        if ( isset( $args['current_category_ancestors'] ) && $args['current_category'] && in_array( $cat_id, $args['current_category_ancestors'], true ) ) {
            $output .= ' current-cat-parent';
        }
        $checkbox = '';
        if ( isset( $_GET['filter_cat'] ) ) {
            if ( in_array( $cat_id, explode( ',', $_GET['filter_cat'] ) ) ) {
                $checkbox = 'checked';
                $output .= ' checked';
            }
        }
        $output .= '"><a class="product_cat'.esc_attr( $checkbox ).'" href="'.esc_url( styler_get_cat_url( $cat_id ) ).'">';
        $output .= '<input name="product_cat[]" value="'.esc_attr( $cat_id ).'" id="'.esc_attr( $cat->name ).'" type="checkbox" '.esc_attr( $checkbox ).'>';
        $output .= '<label >'.esc_html( $cat->name ).'</label>';
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

class Styler_Widget_Product_Categories extends WP_Widget {

    // Widget Settings
    function __construct() {
        $widget_ops = array('description' => esc_html__('For Main Shop Page.','styler') );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'styler_product_categories' );
        parent::__construct( 'styler_product_categories', esc_html__('Styler Product Categories','styler'), $widget_ops, $control_ops );
        add_filter( 'woocommerce_product_query_tax_query', [ $this, 'product_query_tax_query' ], 10, 2 );
    }

    function product_query_tax_query( $tax_query, $instance )
    {
        if ( isset( $_GET['filter_cat'] ) && !empty( $_GET['filter_cat'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => explode( ',', $_GET['filter_cat'] )
            );
        }
        return $tax_query;
    }

    // Widget Output
    function widget( $args, $instance )
    {
        if ( is_shop() && !is_product_category() )
        {
            extract( $args );
            $title = apply_filters( 'widget_title', empty($instance['title'] ) ? '' : $instance['title'], $instance );
            $exclude = $instance['exclude'];

            echo $before_widget;

            if ( $title ) {
                echo $before_title . $title . $after_title;
            }

            wp_enqueue_script( 'styler-widget-product-categories' );
            wp_enqueue_style( 'styler-widget-product-categories' );

            echo '<div class="widget-body site-checkbox-lists styler-widget-product-categories">';
            echo '<div class="site-scroll">';
            echo '<ul class="ninetheme-product-categories-primary">';
            if ( $exclude == 'All' || $exclude == 'all' || $exclude == '' ) {
                echo wp_list_categories(array(
                    'echo'       => true,
                    'taxonomy'   => 'product_cat',
                    'depth'      => 5,
                    'hide_empty' => 0,
                    'title_li'   => '',
                    'walker'     => new Styler_Widget_Product_Categories_Walker()
                ));
            } else {
                echo wp_list_categories(array(
                    'echo'       => true,
                    'taxonomy'   => 'product_cat',
                    'depth'      => 5,
                    'hide_empty' => 0,
                    'title_li'   => '',
                    'exclude'    => explode(',', $exclude),
                    'walker'     => new Styler_Widget_Product_Categories_Walker()
                ));
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';

            echo $after_widget;
        }
    }

    // Update
    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['exclude'] = strip_tags($new_instance['exclude']);

        return $instance;
    }

    // Backend Form
    function form( $instance )
    {
        $defaults = array('title' => 'Product Categories', 'exclude' => 'All');
        $instance = wp_parse_args(( array ) $instance, $defaults ); ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','styler'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('exclude'); ?>"><?php esc_html_e( 'Exclude id:','styler' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" value="<?php echo $instance['exclude']; ?>" />
        </p>

        <?php
    }
}

// Add Widget
function styler_widget_product_categories_init() {
    register_widget('Styler_Widget_Product_Categories');
}
add_action('widgets_init', 'styler_widget_product_categories_init');

?>
