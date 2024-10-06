<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Gallery extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-gallery';
    }
    public function get_title() {
        return 'Gallery (N)';
    }
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    public function get_categories() {
        return [ 'styler' ];
    }
    public function get_script_depends() {
        return [ 'isotope' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'post_query_section',
            [
                'label' => esc_html__( 'Post Query', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        
        $this->styler_query_controls( 'post', false, true );
        
        $this->add_control( 'layoutmode',
            [
                'label' => esc_html__( 'Layout Mode', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => 'true',
                'default' => 'fitRows',
                'options' => [
                    'fitRows' => esc_html__( 'Fit Rows', 'styler' ),
                    'masonry' => esc_html__( 'masonry', 'styler' ),
                ]
            ]
        );
        $this->add_responsive_control( 'col',
            [
                'label' => esc_html__( 'Column', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 4,
                'selectors' => ['{{WRAPPER}} .post-wrapper,{{WRAPPER}} .grid-sizer' => '-ms-flex: 0 0 calc(100% / {{VALUE}} );flex: 0 0 calc(100% / {{VALUE}} );max-width: calc(100% / {{VALUE}} );']
            ]
        );
		$this->add_control('column_gap',
			[
				'label' => __( 'Columns Gap', 'styler' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100
					]
				],
				'selectors' => [ 
				    '{{WRAPPER}} .post-wrapper' => 'padding: 0 {{SIZE}}px;margin-bottom: {{SIZE}}px;',
				    '{{WRAPPER}} .gallery' => 'margin: 0 -{{SIZE}}px -{{SIZE}}px -{{SIZE}}px;'
				]
			]
		);
        $this->add_control( 'hide_filters',
            [
                'label' => esc_html__( 'Hide Filters', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'all_text',
            [
                'label' => esc_html__( 'All Text', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'All',
                'label_block' => true,
                'condition' => [ 'hide_filters!' => 'yes' ]
            ]
        );
        $this->add_control( 'hidetitle',
            [
                'label' => esc_html__( 'Hide Post Title', 'styler' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'hideexcerpt',
            [
                'label' => esc_html__( 'Hide Excerpt', 'styler' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'excerpt_limit',
            [
                'label' => esc_html__( 'Excerpt Word Limit', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'default' => 20,
                'condition' => [ 'hideexcerpt!' => 'yes' ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $settingsid = $this->get_id();

        $post_type = $settings['post_type'];

        $args['post_type']      = $settings['post_type'];
        $args['posts_per_page'] = $settings['posts_per_page'];
        $args['offset']         = $settings['offset'];
        $args['order']          = $settings['order'];
        $args['orderby']        = $settings['orderby'];
        $args[$settings['author_filter_type']] = $settings['author'];

        if ( ! empty( $settings[ $post_type . '_filter' ] ) ) {
            $args[ $settings[ $post_type . '_filter_type' ] ] = $settings[ $post_type . '_filter' ];
        }

        // Taxonomy Filter.
        $taxonomy = $this->get_post_taxonomies( $post_type );

        if ( ! empty( $taxonomy ) && ! is_wp_error( $taxonomy ) ) {

            foreach ( $taxonomy as $index => $tax ) {

                $tax_control_key = $index . '_' . $post_type;

                if ( $post_type == 'post' ) {
                    if ( $index == 'post_tag' ) {
                        $tax_control_key = 'tags';
                    } elseif ( $index == 'category' ) {
                        $tax_control_key = 'categories';
                    }
                }

                if ( ! empty( $settings[ $tax_control_key ] ) ) {

                    $operator = $settings[ $index . '_' . $post_type . '_filter_type' ];

                    $args['tax_query'][] = array(
                        'taxonomy' => $index,
                        'field'    => 'term_id',
                        'terms'    => $settings[ $tax_control_key ],
                        'operator' => $operator,
                    );
                }
            }
        }

        
        $the_query = new \WP_Query( $args );
        
        if ( $the_query->have_posts() ) {
            echo '<div class="portfolio" id="gallery-'.$settingsid.'">';

               $exclude = array();
                $taxonomy = '';
                $post_type = $this->styler_get_post_types();
                foreach ( $post_type as $slug => $label  ) {
                    if ( !empty($settings[ $slug.'_top_taxonomy' ]) ) {
                        
                        $taxonomy = $settings[ $slug.'_top_taxonomy' ];
                    }
                    if ( !empty($settings[ $slug.'_top_filter' ]) ) {
                        $exclude = $settings[ $slug.'_top_filter' ];
                    }
                }
                $cats = get_terms( array (
                    'taxonomy'   => $taxonomy,
                    'order'      => $settings['order'],
                    'orderby'    => $settings['orderby'],
                    'hide_empty' => true,
                    'parent'     => 0,
                    'exclude'    => $exclude
                ) );

                if ( 'yes' != $settings['hide_filters'] && $cats > 1 ) {
 
                    
                    echo '<div class="filtering">';
                        if ( $settings['all_text'] ) {
                            echo '<span data-filter=\'*\' class="active">'.$settings['all_text'].'</span>';
                        }
                        foreach ( $cats as $cat ) {
                            $filter = strtolower( str_replace(' ', '-', $cat->name ) );
                            echo '<span data-filter=\'.'.$filter.'\'>'.$cat->name.'</span>';
                        }
                    echo '</div>';
                }

                echo '<div class="gallery row" data-layout-mode="'.$settings['layoutmode'].'">';
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();

                        foreach ( $taxonomy as $index => $tax ) {
                            $terms = get_the_terms( get_the_ID(), $tax );
                            if ( ! empty( $terms ) ) {
                                foreach ( $terms as $term ) {
                                    if ( ! empty( $term->name ) ) {
                                        $links[] = strtolower( $term->name );
                                    }
                                }
                            }
                        }
                        $links = str_replace(' ', '-', $links);
                        $tax = join( " ", array_unique($links) );

                        echo '<div class="post-wrapper col '.$tax.'">';
                            echo '<a href="' . get_permalink() . '">';
                                echo get_the_post_thumbnail( get_the_ID(), 'full' );
                                echo '<div class="post-info">';
                                    if ( 'yes' != $settings['hidetitle'] ) {
                                        echo '<h5 class="post-title">' . get_the_title() . '</h5>';
                                    }
                                    if ( has_excerpt() && 'yes' != $settings['hideexcerpt'] ) {
                                        echo wpautop( wp_trim_words( get_the_excerpt(), $settings['excerpt_limit'] ) );
                                    }
                                echo '</div>';
                            echo '</a>';
                        echo '</div>';
                    }
                    wp_reset_postdata();
                echo '</div>';
            echo '</div>';
            
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
                <script>
                    var myGallery = jQuery( '#gallery-<?php echo esc_attr($settingsid); ?>' );
                    var myIsotope = myGallery.find(".gallery");
                    var myFilters = myGallery.find(".filtering");
                    if ( myGallery ) {
                        myGallery.isotope(
                            {
                                itemSelector: '.post-wrapper',
                                layoutMode: '<?php echo $settings['layoutmode']; ?>'
                            }
                        );
                        var $gallery = myIsotope.isotope();
                        myFilters.on('click', 'span', function () {
                            var filterValue = jQuery(this).attr('data-filter');
                            $gallery.isotope({
                                filter: filterValue
                            });
                        });
                        myFilters.on('click', 'span', function () {
                            jQuery(this).addClass('active').siblings().removeClass('active');
                        });
                    }
                </script>
                <?php
            }
        } else {
            echo '<p class="text">'.esc_html__('No post found!','styler').'</p>';
        }

    }
}
