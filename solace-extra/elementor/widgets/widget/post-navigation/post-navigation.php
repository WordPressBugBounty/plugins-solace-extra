<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Solace_Extra_Post_Navigation extends \Elementor\Widget_Base {

    public function get_name() {
        return 'solace_extra_post_navigation';
    }

    public function get_title() {
        return __( 'Post Navigation', 'solace-extra' );
    }

    public function get_icon() {
        return 'eicon-post-navigation solace-extra';
    }

    public function get_categories() {
		return [ 'solace-extra-single-post' ];
    }
    protected function register_controls() {

        /**
         * === Section: Layout ===
         */
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __( 'Layout', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_label',
            [
                'label' => __( 'Show Label', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'solace-extra' ),
                'label_off' => __( 'Hide', 'solace-extra' ),
                'return_value' => 'yes',
                'default' => 'yes',
                ]
        );
            
        $this->add_control(
            'show_thumbnail',
            [
                'label' => __( 'Show Thumbnail', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'solace-extra' ),
                'label_off' => __( 'Hide', 'solace-extra' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => __( 'Alignment', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'space-between' => __( 'Space Between', 'solace-extra' ),
                    'flex-start'    => __( 'Left', 'solace-extra' ),
                    'center'        => __( 'Center', 'solace-extra' ),
                    'flex-end'      => __( 'Right', 'solace-extra' ),
                ],
                'default' => 'space-between',
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation' => 'display: flex; justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * === Section: Thumbnail ===
         */
        $this->start_controls_section(
            'thumbnail_section',
            [
                'label' => __( 'Thumbnail', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'next_label',
            [
                'label' => __( 'Next Label Text', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Next Post:', 'solace-extra' ),
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'previous_label',
            [
                'label' => __( 'Previous Label Text', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Previous Post:', 'solace-extra' ),
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        

        $this->add_responsive_control(
            'thumbnail_border_radius',
            [
                'label' => __( 'Border Radius', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation .thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_thumbnail' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnail_height',
            [
                'label' => __( 'Height', 'solace-extra' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ,'em'],
                'default' => [
                    'size' => 50,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation .left .thumbnail, {{WRAPPER}} .solace-posts-navigation .left .thumbnail img'  => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .solace-posts-navigation .right .thumbnail, {{WRAPPER}} .solace-posts-navigation .right .thumbnail img' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_thumbnail' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnail_width',
            [
                'label' => __( 'Width', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 58,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation .thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'thumbnail_object_fit',
            [
                'label' => __( 'Object Fit', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'fill' => __( 'Fill', 'solace-extra' ),
                    'contain' => __( 'Contain', 'solace-extra' ),
                    'cover' => __( 'Cover', 'solace-extra' ),
                    'none' => __( 'None', 'solace-extra' ),
                    'scale-down' => __( 'Scale Down', 'solace-extra' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation .thumbnail img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnail_spacing',
            [
                'label' => __( 'Spacing', 'solace-extra' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ,'em'],
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation .left .thumbnail'  => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .solace-posts-navigation .right .thumbnail' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_thumbnail' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * === Section: Text ===
         */
        $this->start_controls_section(
            'text_section',
            [
                'label' => __( 'Navigation', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'layout_spacing',
            [
                'label' => __( 'Spacing between Navigation', 'solace-extra' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px','em'],
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation'  => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_thumbnail' => 'yes',
                    'alignment' => [ 'flex-start', 'center', 'flex-end' ],
                ],
            ]
        );

        // Typography Group
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Post Title ', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .solace-posts-navigation .title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __( 'Label Next/Prev', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .solace-posts-navigation .previous, {{WRAPPER}} .solace-posts-navigation .next',
            ]
        );

        // === Tabs for Normal & Hover ===
        $this->start_controls_tabs( 'text_color_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            'text_tab_normal',
            [
                'label' => __( 'Normal', 'solace-extra' ),
            ]
        );


        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'label_color',
            [
                'label' => __( 'Label Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation .previous, {{WRAPPER}} .solace-posts-navigation .next' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'text_tab_hover',
            [
                'label' => __( 'Hover', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Title Hover Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation a:hover .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_hover_color',
            [
                'label' => __( 'Label Hover Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-posts-navigation a:hover .previous, {{WRAPPER}} .solace-posts-navigation a:hover .next' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function solace_render_post_navigation_empty() {
        echo '<style>.right a {
                flex-direction: row-reverse;
            }</style>
            <div class="solace-posts-navigation">
            <div class="left">
                <a href="#" title="Power of Mindful Moveme...">
                    <div class="thumbnail">
                        <img width="58" height="58" src="https://placehold.co/300x300?text=Sorry,%20no%20post%20are%20available">
                    </div>
                    <div class="text">
                        <span class="previous">Previous Post:</span>
                        <span class="title">Sorry, no posts are available...</span>
                    </div>
                </a>
            </div>
            <div class="right">
                <a href="#" title="Choosing the Right Runn...">
                    <div class="thumbnail">
                        <img width="58" height="58" src="https://placehold.co/300x300?text=Sorry,%20no%20post%20are%20available">
                    </div>
                    <div class="text">
                        <span class="next">Next Post:</span>
                        <span class="title">Sorry, no posts are available...</span>
                    </div>
                </a>
            </div>
        </div>';
    }

    public function solace_post_navigation_style(){
        echo '<style>
            .solace-posts-navigation .right > a,
            .solace-posts-navigation .left > a {
                display: flex;
            }
            .text {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .solace-posts-navigation .right .next {
                text-align: right;
            }
        </style>';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $post_id = function_exists( 'solace_get_preview_post' ) ? solace_get_preview_post() : get_the_ID();
        $post_object = get_post( $post_id );
        $show_label = $settings['show_label'] === 'yes';
        if ( ! $post_object ) {
            return;
        }

        global $post;
        $original_post = $post;

        $post = $post_object;
        setup_postdata( $post );

        $prev_post = get_previous_post();
        $next_post = get_next_post();

        $this->solace_post_navigation_style();
        wp_reset_postdata();
        $post = $original_post;

        if ( ! $prev_post && ! $next_post ) {
            $this->solace_render_post_navigation_empty();
            return;
        }

        echo '<div class="solace-posts-navigation">';

        if ( $prev_post ) {
            $prev_url = get_permalink( $prev_post->ID );
            $prev_title = get_the_title( $prev_post->ID );
            $prev_thumb = get_the_post_thumbnail_url( $prev_post->ID, array( 58, 58 ) );

            echo '<div class="left">';
            echo '<a href="' . esc_url( $prev_url ) . '" title="' . esc_attr( $prev_title ) . '">';
            if ( $this->get_settings( 'show_thumbnail' ) === 'yes' && $prev_thumb ) {
                echo '<div class="thumbnail"><img width="58" height="58" src="' . esc_url( $prev_thumb ) . '" alt="' . esc_attr( $prev_title ) . '" /></div>';
            } else if ( $this->get_settings( 'show_thumbnail' ) === 'yes' && !$prev_thumb ) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<div class="thumbnail"><img width="58" height="58" src="data:image/svg+xml;base64,' . base64_encode( '
                    <svg xmlns="http://www.w3.org/2000/svg" width="58" height="58">
                        <rect width="58" height="58" fill="#cccccc"/>
                    </svg>' ) . '" alt="' . esc_attr__( 'No Thumbnail', 'solace-extra' ) . '" /></div>';
            }
            echo '<div class="text">';
            // echo '<span class="previous">Previous Post:</span>';
            if ( $show_label ) : ?>
                <span class="previous"><?php echo esc_html( $settings['previous_label'] ); ?></span>
            <?php 
            endif;
            echo '<span class="title">' . esc_html( $prev_title ) . '</span>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
        }

        if ( $next_post ) {
            $next_url = get_permalink( $next_post->ID );
            $next_title = get_the_title( $next_post->ID );
            $next_thumb = get_the_post_thumbnail_url( $next_post->ID, array( 58, 58 ) );

            echo '<div class="right">';
            echo '<a href="' . esc_url( $next_url ) . '" title="' . esc_attr( $next_title ) . '">';
            
            echo '<div class="text">';
            // echo '<span class="next">Next Post:</span>';
            if ( $show_label ) : ?>
                <span class="next"><?php echo esc_html( $settings['next_label'] ); ?></span>
            <?php 
            endif;
            echo '<span class="title">' . esc_html( $next_title ) . '</span>';
            echo '</div>';
            if ( $this->get_settings( 'show_thumbnail' ) === 'yes' && $next_thumb ) {
                echo '<div class="thumbnail"><img width="58" height="58" src="' . esc_url( $next_thumb ) . '" alt="' . esc_attr( $next_title ) . '" /></div>';
            }else if ( $this->get_settings( 'show_thumbnail' ) === 'yes' && !$next_thumb ) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<div class="thumbnail"><img width="58" height="58" src="data:image/svg+xml;base64,' . base64_encode( '
                    <svg xmlns="http://www.w3.org/2000/svg" width="58" height="58">
                        <rect width="58" height="58" fill="#cccccc"/>
                    </svg>' ) . '" alt="' . esc_attr__( 'No Thumbnail', 'solace-extra' ) . '" /></div>';
            }
            echo '</a>';
            echo '</div>';
        }

        echo '</div>';
        ?>

        
    <?php
    }


    
}
