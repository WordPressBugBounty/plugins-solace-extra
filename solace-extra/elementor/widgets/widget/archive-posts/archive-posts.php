<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;


class Solace_Extra_Post_Archive extends \Elementor\Widget_Base {

    public function get_name() {
        return 'solace-extra-post-archive';
    }

    public function get_title() {
        return esc_html__( 'Blog', 'solace-extra' );
    }

    public function get_icon() {
        return 'eicon-post-list solace-extra';
    }

    public function get_categories() {
        return [ 'solace-extra-archive' ];
    }

    public function get_keywords() {
        return [ 'solace', 'extra', 'post', 'archive' ];
    }

	public function get_style_depends() {
		return array( 'solace-extra-block-archive' );
	}    

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'solace-extra' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'   => esc_html__('Columns', 'solace-extra'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('1 Column', 'solace-extra'),
                    '2' => esc_html__('2 Columns', 'solace-extra'),
                    '3' => esc_html__('3 Columns', 'solace-extra'),
                    '4' => esc_html__('4 Columns', 'solace-extra'),
                    '5' => esc_html__('5 Columns', 'solace-extra'),
                ],
                'default' => '3',
                'selectors' => [
                    '{{WRAPPER}} .data-post' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );


        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => esc_html__( 'Grid Gap', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 25,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-archive .data-post' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'posts_per_page',
            [
                'label' => esc_html__( 'Post Per Page', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'tablet_default' => 3,
                'mobile_default' => 2,
            ]
        );

        $this->add_control(
            'display_image',
            [
                'label' => esc_html__( 'Featured Image', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'solace-extra' ),
                'label_off' => esc_html__( 'Hide', 'solace-extra' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'display_categories',
            [
                'label' => esc_html__( 'Categories', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'solace-extra' ),
                'label_off' => esc_html__( 'Hide', 'solace-extra' ),
                'default' => 'yes',
                'condition' => [
					'display_image' => 'yes',
				],
            ]
        );

        $this->add_control(
            'display_gravatar',
            [
                'label' => esc_html__( 'Author Image', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'solace-extra' ),
                'label_off' => esc_html__( 'Hide', 'solace-extra' ),
                'default' => 'yes',
                'condition' => [
					'display_image' => 'yes',
				],
            ]
        );

        $this->add_control(
            'display_excerpt',
            [
                'label' => esc_html__( 'Excerpt', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'solace-extra' ),
                'label_off' => esc_html__( 'Hide', 'solace-extra' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'display_readmore',
            [
                'label' => esc_html__( 'Read More', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'solace-extra' ),
                'label_off' => esc_html__( 'Hide', 'solace-extra' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'display_meta',
            [
                'label' => esc_html__( 'Meta', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'solace-extra' ),
                'label_off' => esc_html__( 'Hide', 'solace-extra' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__('Pagination', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'solace-extra'),
                'label_off' => esc_html__('Hide', 'solace-extra'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // TAB STYLE
        $this->start_controls_section(
            'section_style_article',
            [
                'label' => __( 'Article Box', 'solace-extra' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Background
        $this->add_control(
            'article_background',
            [
                'label' => esc_html__( 'Background Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-archive .article' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'article_border',
                'label' => __('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-post-archive .article',
            ]
        );

        $this->add_responsive_control(
            'article_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-archive .article' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'article_padding',
            [
                'label'      => __( 'Padding', 'solace-extra' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .solace-post-archive .article' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin
        // $this->add_responsive_control(
        //     'article_margin',
        //     [
        //         'label'      => __( 'Margin', 'solace-extra' ),
        //         'type'       => Controls_Manager::DIMENSIONS,
        //         'size_units' => [ 'px', '%', 'em' ],
        //         'selectors'  => [
        //             '{{WRAPPER}} .solace-post-archive .article' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        //         ],
        //     ]
        // );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Featured Image', 'solace-extra' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_image' => 'yes',
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
                    'size' => 200,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 150,
                        'max' => 900,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .featured .sol-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'display_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'thumbnail_background_size',
            [
                'label' => __( 'Image Size', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => __( 'Cover', 'solace-extra' ),
                    'contain' => __( 'Contain', 'solace-extra' ),
                    'auto' => __( 'Auto', 'solace-extra' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .featured .sol-image' => 'background-size: {{VALUE}};',
                    '{{WRAPPER}} .thumbnail-background' => 'background-size: {{VALUE}};',
                ],
                'condition' => [
                    'display_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'thumbnail_background_position',
            [
                'label' => __( 'Position', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    'left top' => __( 'Left Top', 'solace-extra' ),
                    'left center' => __( 'Left Center', 'solace-extra' ),
                    'left bottom' => __( 'Left Bottom', 'solace-extra' ),
                    'center top' => __( 'Center Top', 'solace-extra' ),
                    'center center' => __( 'Center Center', 'solace-extra' ),
                    'center bottom' => __( 'Center Bottom', 'solace-extra' ),
                    'right top' => __( 'Right Top', 'solace-extra' ),
                    'right center' => __( 'Right Center', 'solace-extra' ),
                    'right bottom' => __( 'Right Bottom', 'solace-extra' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .featured .sol-image' => 'background-position: {{VALUE}};',
                    '{{WRAPPER}} .thumbnail-background' => 'background-position: {{VALUE}};',
                ],
                'condition' => [
                    'display_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'thumbnail_background_repeat',
            [
                'label' => __( 'Repeat', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'no-repeat',
                'options' => [
                    'no-repeat' => __( 'No Repeat', 'solace-extra' ),
                    'repeat' => __( 'Repeat', 'solace-extra' ),
                    'repeat-x' => __( 'Repeat X', 'solace-extra' ),
                    'repeat-y' => __( 'Repeat Y', 'solace-extra' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .featured .sol-image' => 'background-repeat: {{VALUE}};',
                    '{{WRAPPER}} .thumbnail-background' => 'background-repeat: {{VALUE}};',
                ],
                'condition' => [
                    'display_image' => 'yes',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'category_style',
            [
                'label' => esc_html__( 'Categories', 'solace-extra' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'category_color',
            [
                'label' => esc_html__( 'Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sol-category a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sol-category' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'category_typography',
                'selector' => '{{WRAPPER}} .sol-category a',
            ]
        );

        $this->add_responsive_control(
            'category_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .sol-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .sol-category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__( 'Title', 'solace-extra' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'title_html_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'DIV',
					'span' => 'SPAN',
					'p' => 'P',
				],
				'default' => 'h3',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .sol-title a',
            ]
        );

        // Tabs: Normal & Hover
        $this->start_controls_tabs( 'tabs_title_style' );

        /**
         * TAB NORMAL
         */
        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label' => __( 'Normal', 'solace-extra' ),
            ]
        );

        // Font Color
        $this->add_control(
            'title_color',
            [
                'label'     => __( 'Font Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-archive .sol-title a,{{WRAPPER}} .solace-post-archive .sol-title a:visited' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Background Color
        $this->add_control(
            'title_background',
            [
                'label'     => __( 'Background Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-archive .sol-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'title_border',
                'selector' => '{{WRAPPER}} .solace-post-archive .sol-title',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'title_border_radius',
            [
                'label'      => __( 'Border Radius', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .solace-post-archive .sol-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        /**
         * TAB HOVER
         */
        $this->start_controls_tab(
            'tab_title_hover',
            [
                'label' => __( 'Hover', 'solace-extra' ),
            ]
        );

        // Font Color Hover
        $this->add_control(
            'title_color_hover',
            [
                'label'     => __( 'Font Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-archive .sol-title:hover a' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Background Color Hover
        $this->add_control(
            'title_background_hover',
            [
                'label'     => __( 'Background Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-archive .sol-title:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Border Hover
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'title_border_hover',
                'selector' => '{{WRAPPER}} .solace-post-archive .sol-title:hover',
            ]
        );

        // Border Radius Hover
        $this->add_responsive_control(
            'title_border_radius_hover',
            [
                'label'      => __( 'Border Radius (Hover)', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .solace-post-archive .sol-title:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    // '{{WRAPPER}} .sol-title h1,{{WRAPPER}} .sol-title h2,{{WRAPPER}} .sol-title h3,{{WRAPPER}} .sol-title h4,{{WRAPPER}} .sol-title h5,{{WRAPPER}} .sol-title h6,{{WRAPPER}} .sol-title p,{{WRAPPER}} .sol-title div, {{WRAPPER}} .sol-title span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .sol-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
					'top' => 30,
					'right' => 30,
					'bottom' => 30,
					'left' => 30,
				],
                'selectors' => [
                    '{{WRAPPER}} .sol-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'excerpt_section',
            [
                'label' => esc_html__( 'Excerpt', 'solace-extra' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'excerpt_length',
            [
                'label' => esc_html__( 'Length (Characters)', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 150,
                'tablet_default' => 100,
                'mobile_default' => 80,
                'min' => 10,
                'step' => 1,
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__( ' Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sol-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .sol-excerpt',
            ]
        );

        $this->add_responsive_control(
            'excerpt_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .sol-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'excerpt_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
					'top' => 0,
					'right' => 30,
					'bottom' => 0,
					'left' => 30,
				],
                'selectors' => [
                    '{{WRAPPER}} .sol-excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'readmore_section',
            [
                'label' => esc_html__( 'Read More', 'solace-extra' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'readmore_typography',
                'selector' => '{{WRAPPER}} .sol-readmore a',
            ]
        );

        $this->start_controls_tabs( 'readmore_color_tabs' );

        // === TAB NORMAL ===
        $this->start_controls_tab(
            'readmore_color_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'readmore_color',
            [
                'label'     => esc_html__( 'Text Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sol-readmore a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'readmore_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sol-readmore a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // === TAB HOVER ===
        $this->start_controls_tab(
            'readmore_color_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'readmore_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sol-readmore a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'readmore_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sol-readmore a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Akhiri TABS
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'readmore_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .sol-readmore a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'readmore_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
					'top' => 0,
					'right' => 30,
					'bottom' => 0,
					'left' => 30,
				],
                'selectors' => [
                    '{{WRAPPER}} .sol-readmore a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        

        $this->end_controls_section();

        $this->start_controls_section(
            'meta_section',
            [
                'label' => esc_html__( 'Meta', 'solace-extra' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => esc_html__( ' Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sol-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .sol-meta',
            ]
        );

        // Border Top Width
        $this->add_responsive_control(
            'meta_border_top_width',
            [
                'label'      => __( 'Border Width', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .solace-post-archive .sol-meta' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Border Top Color
        $this->add_control(
            'meta_border_top_color',
            [
                'label'     => __( 'Border Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-archive .sol-meta' => 'border-top-color: {{VALUE}};',
                ],
                'condition' => [
                    'meta_border_top_width[size]!' => '',
                ],
            ]
        );

        // Border Top Style
        $this->add_control(
            'meta_border_top_style',
            [
                'label'   => __( 'Border Style', 'solace-extra' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'solid'  => __( 'Solid', 'solace-extra' ),
                    'dashed' => __( 'Dashed', 'solace-extra' ),
                    'dotted' => __( 'Dotted', 'solace-extra' ),
                    'double' => __( 'Double', 'solace-extra' ),
                    'none'   => __( 'None', 'solace-extra' ),
                ],
                'default'   => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .solace-post-archive .sol-meta' => 'border-top-style: {{VALUE}};',
                ],
            ]
        );



        $this->add_responsive_control(
            'meta_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .sol-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
					'top' => 30,
					'right' => 30,
					'bottom' => 30,
					'left' => 30,
				],
                'selectors' => [
                    '{{WRAPPER}} .sol-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pagination_section',
            [
                'label' => esc_html__( 'Pagination', 'solace-extra' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Pagination Alignment
        $this->add_responsive_control(
            'pagination_align',
            [
                'label' => esc_html__('Alignment', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'solace-extra'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'solace-extra'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__('Right', 'solace-extra'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    // '{{WRAPPER}} .solace-pagination' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .solace-pagination' => 'justify-content: {{VALUE}};',
                ],
                'default' => 'center',
                'condition' => [
                    'show_pagination' => 'yes',
                ],
            ]
        );

        // Pagination Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'selector' => '{{WRAPPER}} .solace-pagination',
                'condition' => [
                    'show_pagination' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-pagination .page-numbers' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
					'top' => 5,
					'right' => 20,
					'bottom' => 5,
					'left' => 20,
				],
                'selectors' => [
                    '{{WRAPPER}} .solace-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
			'pagination_border_radius',
			[
				'label' => __( 'Border Radius', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 200 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'default' => [
					'size' => 50,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-pagination .page-numbers' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs( 'pagination_color_tabs' );

        // === TAB NORMAL ===
        $this->start_controls_tab(
            'pagination_color_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label'     => esc_html__( 'Text Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-pagination .page-numbers' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-pagination .page-numbers' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // === TAB HOVER ===
        $this->start_controls_tab(
            'pagination_color_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'pagination_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-pagination .page-numbers:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-pagination .page-numbers:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // === TAB active ===
        $this->start_controls_tab(
            'pagination_color_tab_active',
            [
                'label' => esc_html__( 'Active', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'pagination_color_active',
            [
                'label'     => esc_html__( 'Text Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-pagination .page-numbers.current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color_active',
            [
                'label'     => esc_html__( 'Background Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();        


        // Akhiri TABS
        $this->end_controls_tabs();

        


        $this->end_controls_section();
    }
    protected function style(){
        echo '<style>
            .featured .sol-image {
                position: relative;
                overflow: hidden;
            }

            .featured .sol-image .thumbnail-link-grids {
                display: block;
                width: 100%;
                height: 100%;
                }

            .featured .sol-image .thumbnail-background {
                width: 100%;
                height: 100%;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat; 
            }

            .solace-post-archive .data-post {
                display: grid;
                gap: 2rem;
            }

            /* Desktop default */
            .solace-post-archive.layout-column-desktop-1 .data-post { grid-template-columns: 1fr; }
            .solace-post-archive.layout-column-desktop-2 .data-post { grid-template-columns: repeat(2, 1fr); }
            .solace-post-archive.layout-column-desktop-3 .data-post { grid-template-columns: repeat(3, 1fr); }
            .solace-post-archive.layout-column-desktop-4 .data-post { grid-template-columns: repeat(4, 1fr); }
            .solace-post-archive.layout-column-desktop-5 .data-post { grid-template-columns: repeat(5, 1fr); }

            /* Tablet */
            @media (max-width: 768px) {
            .solace-post-archive.layout-column-tablet-1 .data-post { grid-template-columns: 1fr; }
            .solace-post-archive.layout-column-tablet-2 .data-post { grid-template-columns: repeat(2, 1fr); }
            .solace-post-archive.layout-column-tablet-3 .data-post { grid-template-columns: repeat(3, 1fr); }
            .solace-post-archive.layout-column-tablet-4 .data-post { grid-template-columns: repeat(4, 1fr); }
            .solace-post-archive.layout-column-tablet-5 .data-post { grid-template-columns: repeat(5, 1fr); }
            }

            /* Mobile */
            @media (max-width: 480px) {
            .solace-post-archive.layout-column-mobile-1 .data-post { grid-template-columns: 1fr; }
            .solace-post-archive.layout-column-mobile-2 .data-post { grid-template-columns: repeat(2, 1fr); }
            .solace-post-archive.layout-column-mobile-3 .data-post { grid-template-columns: repeat(3, 1fr); }
            .solace-post-archive.layout-column-mobile-4 .data-post { grid-template-columns: repeat(4, 1fr); }
            .solace-post-archive.layout-column-mobile-5 .data-post { grid-template-columns: repeat(5, 1fr); }
            }


            /* Optional styling artikel */
            .solace-post-archive .article {
                background: #fff;
                border: 1px solid #ddd;
                height: fit-content;
            }

            .featured {
                position: relative;
                margin-bottom: 35px;
            }
            .sol-title h1,.sol-title h2,.sol-title h3,.sol-title h4,.sol-title h5,.sol-title h6,.sol-title div,.sol-title span,.sol-title p {
                margin-bottom: 0;
            }
            .sol-title a,
            .sol-title a:visited {
                color: var(--single-link-color);
            }
            .sol-title a:hover {
                color: var(--single-link-hover);
            }
            /* .sol-content {
                padding: 0 30px;
            } */
            .sol-readmore {
                margin-top: 25px;
                margin-bottom: 25px;
            }
            .sol-author {
                position: relative;
                margin-right: 22px;
            }
            .sol-author::after {
                    content: "•";
                content: "";
                position: absolute;
                right: -14px;
                width: 3px;
                height: 3px;
                background: var(--sol-color-base-font);
                border-radius: 50%;
                top: 50%;
                transform: translateY(-50%);
            }
            .sol-meta {
                display: flex;
                flex-wrap: wrap;
                border-top: 1px solid var(--sol-color-border);
                /* padding: 15px 30px; */
                color: var(--sol-color-base-font);
                font-size: 0.8125rem;
                text-transform: capitalize;
            }
            .sol-category {
                text-decoration: none;
                display: block;
                border-radius: 3px;
                margin: 30px;
                position: absolute;
                top: 0;
                left: 0;
                padding: 0.6em 1.2em;
                background: var(--sol-color-link-button-initial);
                
            }
            .solace-post-archive .sol-category,
            .solace-post-archive .sol-category a {
                color: #fff;
            }
            .solace-post-archive .sol-author-image {
                position: absolute;
                bottom: -33px;
                left: 30px;
            }
            .solace-post-archive .sol-author-image img {
                border-radius: 100px;
            }

            .solace-pagination {
                margin-top: 2rem;
                display: flex;
            }

            .solace-pagination .page-numbers {
                padding: 0.5rem 1rem;
                display: inline-block;
                margin: 0 0.2rem;
                text-decoration: none;
            }
            
            .solace-pagination .current {
                font-weight: bold;
                border-color: #333;
                padding: 0.5rem 1rem;
                margin: 0 0.2rem;
            }


        </style>';
    }

    protected function script() {
        $widget_id = $this->get_id();
        ?>

        <script>
        jQuery(document).ready(function($){

            // Scope khusus widget ini
            var widget = $('#solace-post-archive-<?php echo esc_html( $widget_id ); ?>');
            var container = widget.find('.data-post');

            function adjustPosts(){
                var articles = container.find('article');
                var w = window.innerWidth;

                var max = container.data('posts-desktop');
                if(w <= 768) max = container.data('posts-tablet');
                if(w <= 480) max = container.data('posts-mobile');

                articles.show();
                articles.slice(max).hide();
            }

            function adjustExcerpt(){
                widget.find('.sol-excerpt').each(function(){
                    var $this = $(this);
                    var fullText = $this.data('fulltext');

                    if(!fullText) {
                        fullText = $this.text().replace(/\.\.\.$/, '');
                        $this.data('fulltext', fullText);
                    }

                    var w = window.innerWidth;
                    var len = $this.data('excerpt-desktop');

                    if(w <= 768) len = $this.data('excerpt-tablet');
                    if(w <= 480) len = $this.data('excerpt-mobile');

                    var trimmed = fullText.substring(0, len);
                    if(fullText.length > len) trimmed += '...';

                    $this.text(trimmed);
                });
            }

            // Run initial
            adjustPosts();
            adjustExcerpt();

            // On Resize
            var resizeTimer;
            $(window).on('resize', function(){
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function(){
                    adjustPosts();
                    adjustExcerpt();
                }, 200);
            });

        });
        </script>

        <?php
    }


    protected function render() {
        $this->style();
        $this->script();
        $widget_id = $this->get_id();
        $paged_key = 'page_' . $widget_id;
        $settings = $this->get_settings_for_display();
        $columns = !empty( $settings['columns'] ) ? $settings['columns'] : [];
        $columns_desktop = isset( $columns['desktop'] ) ? $columns['desktop'] : 3;
        $columns_tablet  = isset( $columns['tablet'] ) ? $columns['tablet'] : $columns_desktop;
        $columns_mobile  = isset( $columns['mobile'] ) ? $columns['mobile'] : $columns_tablet;

        $posts_per_page = !empty( $settings['posts_per_page'] ) ? $settings['posts_per_page'] : 5;
        $posts_per_page_tablet = !empty( $settings['posts_per_page_tablet'] ) ? $settings['posts_per_page_tablet'] : $posts_per_page;
        $posts_per_page_mobile = !empty( $settings['posts_per_page_mobile'] ) ? $settings['posts_per_page_mobile'] : $posts_per_page;

        $excerpt_length = !empty( $settings['excerpt_length'] ) ? $settings['excerpt_length'] : 150;
        $excerpt_length_tablet = !empty( $settings['excerpt_length_tablet'] ) ? $settings['excerpt_length_tablet'] : $excerpt_length;
        $excerpt_length_mobile = !empty( $settings['excerpt_length_mobile'] ) ? $settings['excerpt_length_mobile'] : $excerpt_length;

        

        ?>
       <div id="solace-post-archive-<?php echo esc_attr( $this->get_id() ); ?>" class="solace-post-archive 
            layout-column-desktop-<?php echo esc_attr( $columns_desktop ); ?> 
            layout-column-tablet-<?php echo esc_attr( $columns_tablet ); ?> 
            layout-column-mobile-<?php echo esc_attr( $columns_mobile ); ?>">
            <div class="data-post" 
                data-posts-desktop="<?php echo esc_attr( $posts_per_page ); ?>" 
                data-posts-tablet="<?php echo esc_attr( $posts_per_page_tablet ); ?>" 
                data-posts-mobile="<?php echo esc_attr( $posts_per_page_mobile ); ?>">
                <?php
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Pagination from URL, value sanitized with intval().
                $paged = isset($_GET['page_' . $this->get_id()]) ? intval($_GET['page_' . $this->get_id()]) : 1;

                $query_args = [
                    'post_type' => 'post',
                    'posts_per_page' => $posts_per_page,
                    'paged' => $paged,
                    'suppress_filters' => true,
                ];

                if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                    $template_id = get_the_ID(); 


                    $preview_settings = get_post_meta( $template_id, '_elementor_preview_settings_category', true );

                    if ( isset( $preview_settings['preview_category'] ) && $preview_settings['preview_category'] ) {
                        $query_args['cat'] = absint( $preview_settings['preview_category'] );
                    }
                }
                $custom_query = new WP_Query( $query_args );                
                    if ( $custom_query->have_posts() ) :
                        $total_pages = $custom_query->max_num_pages;                            
                        /* Start the Loop */
                        while ( $custom_query->have_posts() ) :
                            $custom_query->the_post();
                            $excerpt = get_the_excerpt();
                            if ( empty( $excerpt ) ) {
                                $excerpt = wp_strip_all_tags( get_the_content(), true );
                            }

                            $excerpt_trimmed = mb_substr( $excerpt, 0, $excerpt_length );

                            if ( mb_strlen( $excerpt ) > $excerpt_length ) {
                                $excerpt_trimmed .= '...';
                            }
                            ?>
                            <article class="article">
                                <?php if ( has_post_thumbnail() ) :
                                    if ( $settings['display_image'] === 'yes' ) : ?>
                                        <div class="featured">
                                            <div class="sol-image">
                                                <?php
                                                if ( ! post_password_required() && ! is_attachment() ) :
                                                    $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'solace-default-blog' );
                                                    if ( $thumbnail_url ) : ?>
                                                        <a class="thumbnail-link-grids" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                                            <div class="thumbnail-background" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');"></div>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>

                                            <?php if ( $settings['display_categories'] === 'yes' ) : ?>
                                                <div class="sol-category">
                                                    <?php the_category(', '); ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ( $settings['display_gravatar'] === 'yes' ) : ?>
                                                <div class="sol-author-image">
                                                    <?php echo get_avatar( get_the_author_meta('ID'), 60 ); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div class="sol-content">
                                <?php
                                $html_tag = $settings['title_html_tag'] ?? 'h3';?>
                                    <div class="sol-title">
                                        <?php echo '<' . esc_attr( $html_tag ) . '>';?>
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        <?php echo '</' . esc_attr( $html_tag ) . '>';?>
                                    </div>
                                    <?php if ( $settings['display_excerpt'] ) : ?>
                                        <div class="sol-excerpt" <?php echo '
                                            data-excerpt-desktop="' . esc_attr( $excerpt_length ) . '" 
                                            data-excerpt-tablet="' . esc_attr( $excerpt_length_tablet ) . '" 
                                            data-excerpt-mobile="' . esc_attr( $excerpt_length_mobile ) . '">'
                                            . esc_html( $excerpt_trimmed );?> >
                                        </div>
                                    <?php endif; ?>
                                    <?php if ( $settings['display_readmore'] ) : ?>
                                        <div class="sol-readmore">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php esc_html_e('Read More', 'solace-extra'); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ( $settings['display_meta'] ) : ?>
                                    <div class="sol-meta">
                                        <?php
                                        $author = get_the_author();
                                        $date   = get_the_time('F j, Y');

                                        if ( ! empty( trim( $author ) ) ) {
                                            echo '<span class="sol-author">' . esc_html( $author ) . '</span>';
                                            // echo '<span class="sol-dot"> · </span>';
                                        }

                                        echo '<span class="sol-date">' . esc_html( $date ) . '</span>';
                                        ?>
                                    </div>
                                <?php endif; ?>

                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                        
                        if ( $settings['show_pagination'] === 'yes' && $custom_query->max_num_pages > 1 ) :
                            ?>
                            <div class="solace-pagination">
                                <?php

                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                echo paginate_links([
                                    'total'   => $custom_query->max_num_pages,
                                    'current' => $paged,
                                    'format'  => '?' . $paged_key . '=%#%',
                                    'prev_text' => '&laquo; Prev',
                                    'next_text' => 'Next &raquo;',
                                ]);
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php 
                    else :
                        ?>
                        <section class="no-results not-found">
                            <header class="page-header">
                                <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'solace-extra' ); ?></h1>
                            </header><!-- .page-header -->

                            <div class="page-content">
                                <?php
                                if ( is_home() && current_user_can( 'publish_posts' ) ) :

                                    printf(
                                        '<p>' . wp_kses(
                                            /* translators: 1: link to WP admin new post page. */
                                            esc_html__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'solace-extra' ),
                                            array(
                                                'a' => array(
                                                    'href' => array(),
                                                ),
                                            )
                                        ) . '</p>',
                                        esc_url( admin_url( 'post-new.php' ) )
                                    );

                                elseif ( is_search() ) :
                                    ?>

                                    <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'solace-extra' ); ?></p>
                                    <?php
                                    get_search_form();

                                else :
                                    ?>

                                    <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'solace-extra' ); ?></p>
                                    <?php
                                    get_search_form();

                                endif;
                                ?>
                            </div><!-- .page-content -->
                        </section><!-- .no-results -->
                        <?php
                    endif;?>
            </div>
        </div><!-- .solace-post-archive -->
        
        

        <?php
    }
}
