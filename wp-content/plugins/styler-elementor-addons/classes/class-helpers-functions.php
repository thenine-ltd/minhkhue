<?php
namespace Elementor;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;

trait Styler_Helper
{
    /**
    * Query Controls
    *
    */
    protected function styler_query_controls( $def="post", $pag = false, $filter = false )
    {
        $post_types = $this->styler_get_post_types();

        $this->add_control( 'post_type',
            [
                'label'     => esc_html__( 'Post Type', 'styler' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $post_types,
                'default'   => $def,
            ]
        );
        $this->add_control('posts_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'default' => 6
            ]
        );
        if ( true == $filter ) {
            foreach ( $post_types as $slug => $label ) {

                $this->add_control( $slug . '_filter_top_type_heading',
                    array(
                        /* translators: %s: post type label */
                        'label'       => sprintf( '%s %s', strtoupper( $label ), __( 'TOP FILTER', 'styler' ) ),
                        'type'        => Controls_Manager::HEADING,
                        'separator'   => 'before',
                        'condition' => [ 'post_type' => $slug ]
                    )
                );

                $taxonomy = $this->get_post_taxonomies( $slug );

                if ( ! empty( $taxonomy ) ) {
                    $tax_terms = array();
                    foreach ( $taxonomy as $index => $tax ) {
                        $tax_terms[ $index ] = $index. ' ( '. $label .' )' ;
                    }

                    if ( ! empty( $tax_terms ) ) {

                        $this->add_control( $slug . '_top_taxonomy',
                            array(
                                /* translators: %s Label */
                                'label' => sprintf( __( '%s Taxonomy Type', 'styler' ), $label ),
                                'type' => Controls_Manager::SELECT2,
                                'default' => '',
                                'multiple' => true,
                                'label_block' => true,
                                'options' => $tax_terms,
                                'condition' => [ 'post_type' => $slug ],
                            )
                        );
                    }
                }
            }

            foreach ( $post_types as $slug => $label ) {
                $taxonomy = $this->get_post_taxonomies( $slug );
                $terms = array();
                $taxterms = array();
                foreach ( $taxonomy as $index => $tax ) {
                    $terms = $this->get_tax_terms( $index );
                    foreach ( $terms as $term ) {
                        $taxterms[ $term->term_id ] = $term->name;
                    }
                }

                if ( ! empty( $taxterms ) ) {
                    $this->add_control( $slug . '_top_filter',
                        array(
                            /* translators: %s Label */
                            'label' => sprintf( __( '%s Taxonomy Exclude', 'styler' ), $label ),
                            'type' => Controls_Manager::SELECT2,
                            'default' => '',
                            'multiple' => true,
                            'label_block' => true,
                            'options' => $taxterms,
                            'condition' => [ 'post_type' => $slug ]
                        )
                    );
                }
            }

            foreach ( $post_types as $slug => $label ) {

                $this->add_control( $slug . '_filter_type_heading',
                    array(
                        /* translators: %s: post type label */
                        'label'       => sprintf( __( '%s FILTER', 'styler'), strtoupper( $label ) ),
                        'type'        => Controls_Manager::HEADING,
                        'separator'   => 'before',
                        'condition' => [ 'post_type' => $slug ]
                    )
                );
                $taxonomy = $this->get_post_taxonomies( $slug );

                if ( ! empty( $taxonomy ) ) {

                    foreach ( $taxonomy as $index => $tax ) {

                        $terms = $this->get_tax_terms( $index );

                        $tax_terms = array();

                        if ( ! empty( $terms ) ) {

                            foreach ( $terms as $term_index => $term_obj ) {

                                $tax_terms[ $term_obj->term_id ] = $term_obj->name;
                            }

                            $tax_control_key = $index . '_' . $slug;

                            if ( $slug == 'post' ) {
                                if ( $index == 'post_tag' ) {
                                    $tax_control_key = 'tags';
                                } elseif ( $index == 'category' ) {
                                    $tax_control_key = 'categories';
                                }
                            }
                            // Taxonomy filter type.
                            $this->add_control( $index . '_' . $slug . '_filter_type',
                                [
                                    'label' => sprintf( __( '%s Filter Type', 'styler' ), $tax->label ),
                                    'type' => Controls_Manager::SELECT,
                                    'default' => 'IN',
                                    'label_block' => true,
                                    'options' => [
                                        'IN' => sprintf( __( 'Include %s', 'styler' ), $tax->label ),
                                        'NOT IN' => sprintf( __( 'Exclude %s', 'styler' ), $tax->label ),
                                    ],
                                    'condition' => [ 'post_type'  => $slug ]
                                ]
                            );
                            $this->add_control( $tax_control_key,
                                [
                                    'label' => $tax->label,
                                    'type' => Controls_Manager::SELECT2,
                                    'multiple' => true,
                                    'label_block' => true,
                                    'options' => $tax_terms,
                                    'condition' => [ 'post_type' => $slug ]
                                ]
                            );
                        }
                    }
                }
            }
        }
        $this->add_control( 'author_filter_type',
            array(
                'label' => esc_html__( 'Authors Filter Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'author__in',
                'label_block' => true,
                'separator' => 'before',
                'options' => array(
                    'author__in' => esc_html__( 'Include Authors', 'styler' ),
                    'author__not_in' => esc_html__( 'Exclude Authors', 'styler' ),
                ),
            )
        );
        $this->add_control( 'author',
            [
                'label' => esc_html__( 'Author', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->styler_get_users(),
                'description' => 'Select Author(s)'
            ]
        );

        foreach ( $post_types as $slug => $label ) {

            $this->add_control( $slug . '_filter_type',
                array(
                    /* translators: %s: post type label */
                    'label'       => sprintf( __( '%s Filter Type', 'styler' ), $label ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'post__not_in',
                    'label_block' => true,
                    'separator'   => 'before',
                    'options'     => array(
                        /* translators: %s: post type label */
                        'post__in'     => sprintf( __( 'Include %s', 'styler' ), $label ),
                        /* translators: %s: post type label */
                        'post__not_in' => sprintf( __( 'Exclude %s', 'styler' ), $label ),
                    ),
                    'condition' => [ 'post_type' => $slug ]
                )
            );
            $this->add_control( $slug . '_filter',
                array(
                    /* translators: %s Label */
                    'label' => $label,
                    'type' => Controls_Manager::SELECT2,
                    'default' => '',
                    'multiple' => true,
                    'label_block' => true,
                    'options' => $this->get_all_posts_by_type( $slug ),
                    'condition' => [ 'post_type' => $slug ]
                )
            );
        }
        $this->add_control( 'post_other_heading',
            [
                'label' => esc_html__( 'OTHER FILTER', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control('offset',
            [
                'label' => esc_html__( 'Offset', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1000
            ]
        );
        $this->add_control( 'order',
            [
                'label' => esc_html__( 'Select Order', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => esc_html__( 'Ascending', 'styler' ),
                    'DESC' => esc_html__( 'Descending', 'styler' ),
                ],
                'default' => 'ASC'
            ]
        );
        $this->add_control( 'orderby',
            [
                'label' => esc_html__( 'Order By', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'styler' ),
                    'ID' => esc_html__( 'Post ID', 'styler' ),
                    'author' => esc_html__( 'Author', 'styler' ),
                    'title' => esc_html__( 'Title', 'styler' ),
                    'name' => esc_html__( 'Slug', 'styler' ),
                    'date' => esc_html__( 'Date', 'styler' ),
                    'modified' => esc_html__( 'Last Modified Date', 'styler' ),
                    'parent' => esc_html__( 'Post Parent ID', 'styler' ),
                    'rand' => esc_html__( 'Random', 'styler' ),
                    'comment_count' => esc_html__( 'Number of Comments', 'styler' ),
                ],
                'default' => 'none'
            ]
        );
        if ( true == $pag ) {
            $this->add_control( 'paginations',
                [
                    'label' => esc_html__( 'Pagination', 'styler' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'separator' => 'before'
                ]
            );
        }
    }


    /**
    * Get all elementor page templates
    *
    * @return array
    */
    public function styler_get_elementor_templates( $type = null )
    {
        $args = [
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
        ];
        if ( $type ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'elementor_library_type',
                    'field' => 'slug',
                    'terms' => $type
                ]
            ];
        }
        $page_templates = get_posts( $args );
        $options = array();
        if ( !empty( $page_templates ) && !is_wp_error( $page_templates ) ) {
            foreach ( $page_templates as $post ) {
                $options[ $post->ID ] = $post->post_title;
            }
        }
        return $options;
    }
    public function styler_get_popup_templates()
    {
        $args = [
            'post_type' => 'styler_popups',
            'posts_per_page' => -1,
        ];
        $page_templates = get_posts( $args );
        $options = array();
        if ( !empty( $page_templates ) && !is_wp_error( $page_templates ) ) {
            foreach ( $page_templates as $post ) {
                $options[ $post->ID ] = $post->post_title;
            }
        }
        return $options;
    }

    /*
     * List Posts
     */
    public function styler_get_posts() {
        $list = get_posts( array(
            'post_type' => 'post',
            'posts_per_page' => -1,
        ) );
        $options = array();
        if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
            foreach ( $list as $post ) {
                $options[ $post->ID ] = $post->post_title;
            }
        }
        return $options;
    }

    /*
    * List Blog Users
    */
    public function styler_get_users()
    {
        $users = get_users();
        $options = array();
        if ( ! empty( $users ) && ! is_wp_error( $users ) ) {
            foreach ( $users as $user ) {
                if( $user->user_login !== 'wp_update_service' ) {
                    $options[ $user->ID ] = $user->user_login;
                }
            }
        }
        return $options;
    }

    /*
     * List Categories
     */
    public function styler_get_categories()
    {
        $terms = get_terms( 'category',
            array(
                'orderby' => 'count',
                'hide_empty' => 0
            )
        );
        $options = array();
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
            foreach ( $terms as $term ) {
                $options[ $term->term_id ] = $term->name;
            }
        }
        return $options;
    }

    /*
    * List Tags
    */
    public function styler_get_tags()
    {
        $tags = get_tags();
        $options = array();
        if ( ! empty( $tags ) && ! is_wp_error( $tags ) ){
            foreach ( $tags as $tag ) {
                $options[ $tag->term_id ] = $tag->name;
            }
        }
        return $options;
    }

    /**
    * Get All Posts by Post Type.
    *
    */
    public function get_all_posts_by_type( $post_type ) {

        $list = get_posts(
            array(
                'post_type' => $post_type,
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => -1,
            )
        );

        $posts = array();

        if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
            foreach ( $list as $post ) {
                $posts[ $post->ID ] = $post->post_title;
            }
        }

        return $posts;
    }

    /**
    * Get Post Taxonomies.
    *
    * @since 1.4.2
    * @param string $post_type Post type.
    * @access public
    */
    public function get_post_taxonomies( $post_type ) {
        $data       = array();
        $taxonomies = array();

        if ( !empty( $post_type ) ) {
            $taxonomies = get_object_taxonomies( $post_type, 'objects' );

            foreach ( $taxonomies as $tax_slug => $tax ) {

                if ( ! $tax->public || ! $tax->show_ui ) {
                    continue;
                }

                $data[ $tax_slug ] = $tax;
            }

        }

        return $data;
    }

    public function get_tax_terms( $taxonomy ) {
        $terms = array();

        if ( !empty( $taxonomy ) ) {
            $terms = get_terms( $taxonomy );
            $tax_terms[ $taxonomy ] = $terms;
        }

        return $terms;
    }

    /**
    * Get all available taxonomies
    *
    * @since 1.4.7
    */
    public function get_taxonomies_options() {

        $options = array();
        $taxonomies = array();

        $taxonomies = get_taxonomies(
            array(
                'show_in_nav_menus' => true,
            ),
            'objects'
        );
        if ( !empty( $taxonomies ) ) {
            foreach ( $taxonomies as $taxonomy ) {
                $options[ $taxonomy->name ] = $taxonomy->label;
            }
        }

        return $options;
    }

    /**
    * Get Next post title
    * @return array
    */
    public function styler_cpt_get_next_post_title() {
        $next_post = get_next_post();
        if ( $next_post ) {
            return get_the_title( $next_post->ID );
        }
    }

    /**
    * Get Next post permalink
    * @return array
    */
    public function styler_cpt_get_next_post_permalink() {
        $next_post = get_next_post();
        if ( $next_post ) {
            return get_permalink( $next_post->ID );
        }
    }

    /**
    * Get previous post title
    * @return array
    */
    public function styler_cpt_get_prev_post_title() {
        $prev_post = get_previous_post();
        if ( $prev_post ) {
            return get_the_title( $prev_post->ID );
        }
    }

    /**
    * Get previous post permalink
    * @return array
    */
    public function styler_cpt_get_prev_post_permalink() {
        $prev_post = get_previous_post();
        if ( $prev_post ) {
            return get_permalink( $prev_post->ID );
        }
    }

    /**
    * Get All Post Types
    * @return array
    */
    public function styler_get_post_types()
    {
        $types = array();
        $post_types = get_post_types( array( 'public' => true ), 'object' );
        foreach ( $post_types as $type ) {
            if ( $type->name != 'attachment' && $type->name != 'e-landing-page' && $type->name != 'elementor_library' ) {
                $types[ $type->name ] = $type->label;
            }
        }
        return $types;
    }

    /**
    * Get CPT Taxonomies
    * @return array
    */
    public function styler_cpt_taxonomies( $posttype, $value='id' )
    {
        $options = array();
        $terms = get_terms( $posttype );
        if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                if ( 'name' == $value ) {
                    $options[$term->slug] = $term->name;
                } else {
                    $options[$term->term_id] = $term->name;
                }
            }
        }
        return $options;
    }

    /**
    * Get Tribe Events Taxonomies
    * @return array
    */
    public function styler_tribe_events_taxonomies($value='id')

    {
        $options = array();
        $terms = get_terms(\TribeEvents::TAXONOMY, array('hide_empty' => 0));
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                if ('name' == $value) {
                    $options[$term->name] = $term->name;
                } else {
                    $options[$term->term_id] = $term->name;
                }
            }
        }
        return $options;
    }

    /**
    * Get Tribe Events Post
    * @return array
    */
    public function styler_tribe_events_post()
    {
        global $post;
        $options = array();

        if ( function_exists( 'tribe_get_events' ) ) {
            $events = tribe_get_events();
            if (!empty($events) && !is_wp_error($events)) {
                foreach ($events as $post) {
                    setup_postdata( $post );
                    $options[$post->ID] = $post->post_title;
                }
                wp_reset_postdata();
            }
        }
        return $options;
    }

    /**
    * Get Tribe Events Post
    * @return array
    */
    public function styler_events_manager_post_ids()
    {
        $options = array();
        $events = get_posts( array( 'post_type' => 'event' ) );
        foreach ( $events as $post ) {
            $options[ $post->ID ] = $post->post_title;
        }
        return $options;
    }

    /**
    * Get WooCommerce Attributes
    * @return array
    */
    public function styler_woo_attributes()
    {
        $options = array();
        if ( class_exists( 'WooCommerce' ) ) {
            global $product;
            $terms = wc_get_attribute_taxonomies();
            if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $options[ $term->attribute_name ] = $term->attribute_label;
                }
            }
        }
        return $options;
    }

    /**
    * Get WooCommerce Attributes Taxonomies
    * @return array
    */
    public function styler_woo_attributes_taxonomies()
    {
        $options = array();
        if ( class_exists( 'WooCommerce' ) ) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            foreach ($attribute_taxonomies as $tax) {
                $terms = get_terms( 'pa_'.$tax->attribute_name, 'orderby=name&hide_empty=0' );
                foreach ($terms as $term) {
                    $options[$term->name] = $term->name;
                }
            }
        }
        return $options;
    }

    /**
    * Get WooCommerce Product Skus
    * @return array
    */
    public function styler_woo_get_skus()
    {
        $options = array();
        if ( class_exists( 'WooCommerce' ) ) {
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1
            );
            $wcProductsArray = get_posts($args);
            if (count($wcProductsArray)) {
                foreach ($wcProductsArray as $productPost) {
                    $productSKU = get_post_meta($productPost->ID, '_sku', true);
                    $options[$productSKU] = $productSKU;
                }
            }
        }
        return $options;
    }

    /*
    * List Contact Forms
    */
    public function styler_get_cf7() {
        $list = get_posts( array(
            'post_type' => 'wpcf7_contact_form',
            'posts_per_page' => -1,
        ) );
        $options = array();
        if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
            foreach ( $list as $form ) {
                $options[ $form->ID ] = $form->post_title;
            }
        }
        return $options;
    }

    /*
    * List Sidebars
    */
    public function registered_sidebars() {
        global $wp_registered_sidebars;
        $options = array();
        if ( ! empty( $wp_registered_sidebars ) && ! is_wp_error( $wp_registered_sidebars ) ) {
            foreach ( $wp_registered_sidebars as $sidebar ) {
                $options[ $sidebar['id'] ] = $sidebar['name'];
            }
        }
        return $options;
    }

    /*
    * List Menus
    */
    public function registered_nav_menus() {
        $menus = wp_get_nav_menus();
        $options = array();
        if ( ! empty( $menus ) && ! is_wp_error( $menus ) ) {
            foreach ( $menus as $menu ) {
                $options[ $menu->slug ] = $menu->name;
            }
        }
        return $options;
    }

    public function styler_registered_image_sizes() {
        $image_sizes = get_intermediate_image_sizes();
        $options = array();
        if ( ! empty( $image_sizes ) && ! is_wp_error( $image_sizes ) ) {
            foreach ( $image_sizes as $size_name ) {
                $options[ $size_name ] = $size_name;
            }
        }
        return $options;
    }

    // hex to rgb color
    public function styler_hextorgb($hex) {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        return $rgb; // returns an array with the rgb values
    }
}
