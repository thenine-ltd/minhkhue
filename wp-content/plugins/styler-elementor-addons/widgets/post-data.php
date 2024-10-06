<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Post_Data extends Widget_Base {
    public function get_name() {
        return 'styler-post-data';
    }
    public function get_title() {
        return 'Post Data (N)';
    }
    public function get_icon() {
        return 'eicon-shortcode';
    }
    public function get_categories() {
        return [ 'styler-post' ];
    }

    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'styler_post_data_settings',
            [
                'label' => esc_html__('Post Data', 'styler'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'data',
            [
                'label' => esc_html__( 'Data Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'title' => esc_html__( 'Title', 'styler' ),
                    'featured' => esc_html__( 'Featured Image', 'styler' ),
                    'author' => esc_html__( 'Author Name', 'styler' ),
                    'desc' => esc_html__( 'Author Description', 'styler' ),
                    'avatar' => esc_html__( 'Author Avatar', 'styler' ),
                    'authbox' => esc_html__( 'Author Box', 'styler' ),
                    'date' => esc_html__( 'Date', 'styler' ),
                    'cat' => esc_html__( 'Category', 'styler' ),
                    'tag' => esc_html__( 'Tags', 'styler' ),
                    'comment-number' => esc_html__( 'Comment Number', 'styler' ),
                    'comment-template' => esc_html__( 'Comment Template', 'styler' ),
                    'related' => esc_html__( 'Related Post', 'styler' ),
                    'nav' => esc_html__( 'Navigation', 'styler' ),
                    'prev' => esc_html__( 'Previous Post', 'styler' ),
                    'next' => esc_html__( 'Next Post', 'styler' )
                ],
                'default' => 'title'
            ]
        );
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Tag', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'p',
                'options' => [
                    'h1' => esc_html__( 'h1', 'styler' ),
                    'h2' => esc_html__( 'h2', 'styler' ),
                    'h3' => esc_html__( 'h3', 'styler' ),
                    'h4' => esc_html__( 'h4', 'styler' ),
                    'h5' => esc_html__( 'h5', 'styler' ),
                    'h6' => esc_html__( 'h6', 'styler' ),
                    'div' => esc_html__( 'div', 'styler' ),
                    'p' => esc_html__( 'p', 'styler' ),
                    'span' => esc_html__( 'span', 'styler' )
                ],
                'separator' => 'before',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [ 'name' => 'data','operator' => '==','value' => 'date' ],
                        [ 'name' => 'data','operator' => '==','value' => 'cat' ],
                        [ 'name' => 'data','operator' => '==','value' => 'comment-number' ],
                        [ 'name' => 'data','operator' => '==','value' => 'tag' ],
                        [ 'name' => 'data','operator' => '==','value' => 'title' ],
                        [ 'name' => 'data','operator' => '==','value' => 'author' ],
                        [ 'name' => 'data','operator' => '==','value' => 'desc' ]
                    ]
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [ 'name' => 'data','operator' => '==','value' => 'featured' ],
                        [ 'name' => 'data','operator' => '==','value' => 'related' ]
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'perpage',
            [
                'label' => esc_html__( 'Post Per Page', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 6,
                'condition' => [ 'data' => 'related' ]
            ]
        );
        $this->add_control( 'perview',
            [
                'label' => esc_html__( 'Per View', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 4,
                'condition' => [ 'data' => 'related' ],
                'separator' => 'before'
            ]
        );
        $this->add_control( 'mdperview',
            [
                'label' => esc_html__( 'Per View Tablet', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 2,
                'condition' => [ 'data' => 'related' ]
            ]
        );
        $this->add_control( 'smperview',
            [
                'label' => esc_html__( 'Per View Phone', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 1,
                'condition' => [ 'data' => 'related' ]
            ]
        );
        $this->add_control( 'speed',
            [
                'label' => esc_html__( 'Speed', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5000,
                'step' => 100,
                'default' => 1000,
                'separator' => 'before',
                'condition' => [ 'data' => 'related' ]
            ]
        );
        $this->add_control( 'loop',
            [
                'label' => esc_html__( 'Loop', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [ 'data' => 'related' ]
            ]
        );
        $this->add_control( 'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [ 'data' => 'related' ]
            ]
        );
        $this->add_control( 'mousewheel',
            [
                'label' => esc_html__( 'Mousewheel', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [ 'data' => 'related' ]
            ]
        );
        $this->add_control( 'gap',
            [
                'label' => esc_html__( 'Gap', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'default' => 30,
                'condition' => [ 'data' => 'related' ]
            ]
        );
        $this->end_controls_section();

        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('data_avatar_style_section',
            [
                'label'=> esc_html__( 'Avatar Style', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['data' => 'avatar']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .post--data.author--img img.avatar'
            ]
        );
        $this->add_responsive_control( 'widget_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .post--data.author--img img.avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'avatar_size',
            [
                'label' => esc_html__( 'Size', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 30,
                'max' => 500,
                'step' => 1,
                'default' => 167
            ]
        );
        $this->end_controls_section();
        
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('data_navigation_style_section',
            [
                'label'=> esc_html__( 'Navigation Style', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['data' => 'nav']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nav_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .post--data.nt-styler-content .pagination'
            ]
        );
        $this->add_responsive_control( 'nav_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .post--data.nt-styler-content .pagination' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'nav_post_data_margin',
            [
                'label' => esc_html__( 'Margin', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .post--data.nt-styler-content .pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control( 'nav_post_data_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .post--data.nt-styler-content .pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_control( 'nav_post_data_bgcolor',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .post--data.nt-styler-content .pagination' => 'color:{{VALUE}};' ],
                'separator' => 'before'
            ]
        );
        $this->add_control( 'nav_post_data_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .post--data.nt-styler-content .pagination a' => 'color:{{VALUE}};' ],
                'separator' => 'before'
            ]
        );
        $this->add_control( 'nav_post_data_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .post--data.nt-styler-content .pagination a:hover' => 'color:{{VALUE}};' ],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nav_post_data_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .post--data.nt-styler-content .pagination a'
            ]
        );
        $this->end_controls_section();

        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('data_style_section',
            [
                'label'=> esc_html__( 'Data Style', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [ 'name' => 'data','operator' => '==','value' => 'date' ],
                        [ 'name' => 'data','operator' => '==','value' => 'cat' ],
                        [ 'name' => 'data','operator' => '==','value' => 'comment-number' ],
                        [ 'name' => 'data','operator' => '==','value' => 'tag' ],
                        [ 'name' => 'data','operator' => '==','value' => 'title' ],
                        [ 'name' => 'data','operator' => '==','value' => 'author' ],
                        [ 'name' => 'data','operator' => '==','value' => 'desc' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'hide_icon',
            [
                'label' => esc_html__( 'Hide Icon', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [ 'name' => 'data','operator' => '==','value' => 'comment-number' ],
                        [ 'name' => 'data','operator' => '==','value' => 'author' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'post_data_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .post--data a i' => 'color:{{VALUE}};' ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [ 'name' => 'data','operator' => '==','value' => 'comment-number' ],
                        [ 'name' => 'data','operator' => '==','value' => 'author' ]
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'post_data_margin',
            [
                'label' => esc_html__( 'Margin', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .post--data' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control( 'post_data_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .post--data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_control( 'post_data_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .post--data, {{WRAPPER}} .post--data a' => 'color:{{VALUE}};' ],
                'separator' => 'before'
            ]
        );
        $this->add_control( 'post_data_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .post--data:hover,{{WRAPPER}} .post--data a:hover' => 'color:{{VALUE}};' ],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'post_data_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .post--data'
            ]
        );
        $this->add_responsive_control( 'hero_horizontal',
            [
                'label' => esc_html__( 'Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .post--data' => 'text-align: {{VALUE}};'],
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => ''
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    public function post_related() {
        $settings = $this->get_settings_for_display();
        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'full';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size = [ $sizew, $sizeh ];
        }

        $speed      = $settings['speed'] ? $settings['speed'] : 1000;
        $perview    = $settings['perview'] ? $settings['perview'] : 4;
        $mdperview  = $settings['mdperview'] ? $settings['mdperview'] : 3;
        $smperview  = $settings['smperview'] ? $settings['smperview'] : 2;
        $gap        = $settings['gap'] ? $settings['gap'] : 30;
        $loop       = 'yes' == $settings['loop'] ? 'true' : 'false';
        $autoplay   = 'yes' == $settings['autoplay'] ? 'true' : 'false';
        $mousewheel = 'yes' == $settings['mousewheel'] ? 'true' : 'false';

        global $post;
        $cats = get_the_category( $post->ID );
        $args = array(
            'post__not_in' => array( $post->ID ),
            'posts_per_page' => $settings['perpage']
        );

        $the_query = new \WP_Query( $args );

        if( $the_query->have_posts() ) {
            wp_enqueue_script( 'swiper' );
        ?>

            <div class="related-slider post--data">
                <div class="swiper-container styler-swiper-slider" data-swiper-options=<?php echo '\'{"speed": '.$speed.',"loop": '.$loop.',"autoplay": '.$autoplay.',"mousewheel": '.$mousewheel.',"slidesPerView": '.$perview.',"spaceBetween": '.$gap.',"breakpoints": {"320": {"slidesPerView": 1,"768": {"slidesPerView": '.$smperview.'},"1024": {"slidesPerView": '.$mdperview.'},"1200": {"slidesPerView": '.$perview.'}}}\''; ?>
                    <div class="swiper-wrapper">
                        <?php
                            while( $the_query->have_posts() ) {

                                $the_query->the_post();
                                if ( has_post_thumbnail() && function_exists( 'styler_post_style_one' ) ) {
                                    ?>
                                    <div class="swiper-slide">
                                        <?php styler_post_style_one(); ?>
                                    </div>
                                    <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            wp_reset_postdata();
        }
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        global $post;
        $tag = $settings['tag'];
        $post_type = get_post_type( $post->ID );
        if ( 'title' == $settings['data'] ) {
            $title = $post_type == 'elementor_library' ? 'This is a demo title' : get_the_title( $post->ID ); 
            echo '<'.$tag.' class="post--data post--title post--id-'.$post->ID.'">'.$title.'</'.$tag.'>';
        }
        if ( 'featured' == $settings['data'] ) {
            echo '<div class="post--data post--img post--id-'.$post->ID.'">' . get_the_post_thumbnail( get_the_ID(), $settings['thumbnail_size'], array( 'class' => 'post-img' ) ) . '</div>';
        }
        if ( 'cat' == $settings['data'] ) {
            if ( $post_type == 'elementor_library' ) {
                echo '<'.$tag.' class="post--data post--cat post--id-'.$post->ID.'"><a href="#0">Demo Category 1</a>, <a href="#0">Demo Category 2</a></'.$tag.'>';
            } else {
                if ( has_category() ) {
                    echo '<'.$tag.' class="post--data post--cat post--id-'.$post->ID.'">';
                        the_category(', ');
                    echo '</'.$tag.'>';
                }
            }
        }
        if ( 'tag' == $settings['data'] && has_tag() ) {
            echo '<'.$tag.' class="post--data post--tags post--id-'.$post->ID.'">';
                the_tags('', ', ', '');
            echo '</'.$tag.'>';
        }
        if ( 'date' == $settings['data'] ) {
            echo '<'.$tag.' class="post--data post--date post--id-'.$post->ID.'">';
            $archive_year  = get_the_time( 'Y' );
            $archive_month = get_the_time( 'm' );
            $archive_day   = get_the_time( 'd' );
            printf( '<a href="%s">%s</a>',
                esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
                get_the_time( get_option( 'date_format' ) )
            );
            echo '</'.$tag.'>';
        }
        if ( 'comment-number' == $settings['data'] ) {
            echo '<'.$tag.' class="post--data post--author post--id-'.$post->ID.'">';
                printf( '<a href="%s" title="%s">%s%s</a>',
                    esc_url( get_comments_link( $post->ID ) ),
                    get_the_title(),
                    'yes' == $settings['hide_icon'] ? '<i class="far fa-comments"></i> ' : '',
                    _nx( '1 Comment', '%1$s Comments', get_comments_number(), 'comments title', 'styler' )
                );
            echo '</'.$tag.'>';
        }
        if ( 'comment-template' == $settings['data'] && ( comments_open() || '0' != get_comments_number() ) ) {
            echo '<div class="nt-styler-content post--data post--id-'.$post->ID.'">';
                echo comments_template();
            echo'</div>';
        }
        if ( 'nav' == $settings['data'] && function_exists( 'styler_single_navigation' ) ) {
            echo '<div class="nt-styler-content post--data post--nav post--id-'.$post->ID.'">';
                styler_single_navigation();
            echo '</div>';
        }
        if ( 'related' == $settings['data'] ) {
            $this->post_related();
        }
        if ( 'author' == $settings['data'] && function_exists( 'styler_post_meta_author' ) ) {
            echo '<'.$tag.' class="post--data post--author post--id-'.$post->ID.'">';
            printf( '<a href="%s" title="%s">%s%s</a>',
                get_author_posts_url( $post->post_author ),
                get_the_author_meta( 'display_name', $post->post_author ),
                'yes' == $settings['hide_icon'] ? '<i class="far fa-user-circle"></i> ' : '',
                get_the_author_meta( 'display_name', $post->post_author )
            );
            echo '</'.$tag.'>';
        }
        if ( 'desc' == $settings['data'] && get_the_author_meta('user_description', $post->post_author) ) {
            $desc = get_the_author_meta( 'user_description', $post->post_author );
            echo '<'.$tag.' class="post--data post--author post--id-'.$post->ID.'">'.$desc.'</'.$tag.'>';
        }
        if ( 'avatar' == $settings['data'] ) {
            if ( function_exists( 'get_avatar' ) ) {
                $args = [ 'class' => 'a-img' ];
                $alt = get_the_author_meta( 'display_name', $post->post_author );
                echo '<div class="post--data author--img post--id-'.$post->ID.'">'.get_avatar( get_the_author_meta( 'email' ), $settings['avatar_size'],'',$alt, $args).'</div>';
            }
        }
        if ( 'authbox' == $settings['data'] && function_exists( 'styler_single_post_author_box' ) ) {
            echo '<div class="nt-styler-content post--data author--box post--id-'.$post->ID.'">';
                echo styler_single_post_author_box();
            echo'</div>';
        }

    }
}
