<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;

class Solace_Extra_Post_RelatedPost extends \Elementor\Widget_Base {

	public function get_name() {
		return 'related_posts_widget';
	}

	public function get_title() {
		return __( 'Related Posts', 'solace-extra' );
	}

	public function get_icon() {
		return 'eicon-post-list solace-extra';
	}

	public function get_categories() {
		return [ 'solace-extra-single-post' ];
	}

	protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default' => '3',
            ]
        );

        $this->add_responsive_control(
            'posts_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'min' => 1,
            ]
        );

        $this->add_control(
            'show_label',
            [
                'label' => esc_html__('Show Label', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'solace-extra'),
                'label_off' => esc_html__('Hide', 'solace-extra'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_category',
            [
                'label' => esc_html__('Show Category', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'solace-extra'),
                'label_off' => esc_html__('Hide', 'solace-extra'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_post_title',
            [
                'label' => esc_html__('Show Post Title', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'solace-extra'),
                'label_off' => esc_html__('Hide', 'solace-extra'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => esc_html__('Show Meta', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'solace-extra'),
                'label_off' => esc_html__('Hide', 'solace-extra'),
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('Layout', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => esc_html__('Grid Gap', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ,'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .image .thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__('Container Padding', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label' => esc_html__('Container Margin', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_label',
            [
                'label' => esc_html__('Label', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Color', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related-posts h3' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .related-posts h3',
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'content_alignment',
            [
                'label' => esc_html__('Alignment', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'solace-extra'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'solace-extra'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'solace-extra'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts h3' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_featured_image',
            [
                'label' => esc_html__('Featured Image', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_overlay',
            [
                'label' => esc_html__('Overlay Gradient', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'description' => esc_html__('Linear gradient from bottom', 'solace-extra'),
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .image .thumbnail::before' => '--overlay-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'image_overlay_hover',
            [
                'label' => esc_html__('Overlay Gradient (Hover)', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li:hover .image .thumbnail::before' => '--overlay-color-hover: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .related-posts ul li .image .thumbnail',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_category',
            [
                'label' => esc_html__('Category', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'category_text_color',
            [
                'label' => esc_html__('Text Color', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .the-categories a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_bg_color',
            [
                'label' => esc_html__('Background Color', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .the-categories' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'category_typography',
                'selector' => '{{WRAPPER}} .related-posts ul li .the-categories',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'category_border',
                'label' => esc_html__('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .related-posts .the-categories',
            ]
        );

        $this->add_control(
            'category_border_radius',
            [
                'label' => esc_html__('Border Radius', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts .the-categories' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_padding',
            [
                'label' => esc_html__('Padding', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .the-categories' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_margin',
            [
                'label' => esc_html__('Margin', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .the-categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        // === Post Title ===
        $this->start_controls_section(
            'section_post_title',
            [
                'label' => esc_html__('Post Title', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label' => esc_html__('Text Color', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .related-posts ul li .title a',
            ]
        );

        $this->add_responsive_control(
            'title_line_clamp',
            [
                'label' => esc_html__( 'Max Lines for Title', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'default' => 2,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .title' => '-webkit-line-clamp: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        // === Meta ===
        $this->start_controls_section(
            'section_meta',
            [
                'label' => esc_html__('Meta', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_icon',
            [
                'label' => esc_html__('Meta Icon', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::ICONS,
            ]
        );

        $this->add_responsive_control(
			'meta_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .related-posts ul li .date time svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
				],
			]
		);

        $this->add_responsive_control(
			'meta_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .related-posts ul li .date time svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'meta_icon_color',
            [
                'label' => esc_html__('Icon Color', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .date time svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_text_color',
            [
                'label' => esc_html__('Text Color', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .date time span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .related-posts ul li .date .time',
            ]
        );

        $this->add_responsive_control(
            'meta_padding',
            [
                'label' => esc_html__('Padding', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_margin',
            [
                'label' => esc_html__('Margin', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .related-posts ul li .date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function solace_post_relatedpost_script(){
        echo "<script>
            jQuery(document).ready(function($){
                function adjustPosts(){
                    var container = $('.related-posts .data-post');
                    var articles = container.find('li');
                    var w = window.innerWidth;
                    var max = container.data('posts-desktop');

                    if(w <= 768) {
                        max = container.data('posts-tablet');
                    }
                    if(w <= 480) {
                        max = container.data('posts-mobile');
                    }

                    articles.show();

                    articles.slice(max).hide();
                }

                adjustPosts();

                var resizeTimer;
                $(window).on('resize', function(){
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function(){
                        adjustPosts();
                    }, 200);
                });
            });
        </script>";
    }
    public function solace_post_relatedpost_style(){
        echo '<style>
            
            .related-posts h3 {
                margin-top: 32px;
                margin-bottom: 27px;
                text-align: center;
                font-size: 25px;
                font-style: normal;
                font-weight: 700;
                line-height: normal;
            }
            .related-posts ul {
                display: flex;
                flex-wrap: wrap;
                margin-left: 0;
                padding-left: 0;
                max-width: unset;
                margin-top: 0;
            }
            .related-posts ul li {
                margin-top: 0;
                position: relative;
                list-style: none;
                overflow: hidden;
            }
            .related-posts ul li .image {
                height: 277px;
                position: relative;
            }
            /* .related-posts .date svg {
                width: 10px;
            } */
            .the-categories {
                /* display: flex;
                gap: 5px; */
                width: fit-content;
            }
            .the-categories a {
                /* display: block; */
            }
            .related-posts ul li .content {
                position: absolute;
                top: calc(56% - 18px);
                left: 50%;
                transform: translate(-96px, 50%);
                left: 0;
                transform: translate(18px, 50%);
                transform: none;
                padding: 0 20px;
                height: auto;
                z-index: 2;
                text-align: center;
                width: 100%;
            }

            .related-posts ul li .image .thumbnail {
                height: 100%;
                width: 100%;
                background-size: cover;
                background-position: 50%;
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                border-radius: 10px;
                overflow: hidden;
            }

            .related-posts ul li .image .thumbnail::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, var(--overlay-color, rgba(0, 0, 0, 0.5)) 0%, transparent 100%);
                transition: background 0.4s ease-in-out; 
                border-radius: inherit;
                z-index: 1;
            }
            .related-posts ul li:hover .image .thumbnail::before {
                background: linear-gradient(to top, var(--overlay-color-hover, rgba(0, 0, 0, 0.3)) 0%, transparent 100%);
                transition: background 0.4s ease-in-out; 
            }
            .related-posts ul li .content .the-categories {
                text-align: left;
            }

            .related-posts ul li .title {
                display: -webkit-box;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .related-posts ul li .content .title {
                line-height: 1.2;
                margin: 8px 0;
                text-align: left;
                /* white-space: nowrap; */
            }
            .related-posts ul li .content .title a {
                display: block;
                color: #fff;
                font-size: 16px;
                font-style: normal;
                font-weight: 700;
                line-height: normal;
            }
            .related-posts ul li .content .date time {
                display: flex
            ;
                flex-wrap: wrap;
                align-items: center;
            }
            .related-posts ul li .content .date time svg {
                font-size: 12px;
                width: 12px;
                height: 12px;
                fill: #fff;
                margin-right: 3px;
            }
            .related-posts ul li .content .date time span {
                color: #fff;
                font-size: 9px;
                font-size: 13px;
                font-style: normal;
                font-weight: 500;
                line-height: normal;
            }

        </style>';
    }

    public function solace_render_related_posts_empty() {
        echo '
        <div class="related-posts">
            <h3>Related Posts</h3>
            <ul>
                <li>
                    <div class="image has-post-thumbnail">
                        <div class="overlay"></div>
                        <div class="thumbnail" style="background-image: url(https://ricoboy.djavaweb.com/wp-content/uploads/2023/10/young-athlete-man-tying-lace-on-running-shoes-duri-2022-12-03-22-10-14-utc-250x277.jpg);"></div>
                    </div>
                    <div class="content">
                        <div class="the-categories">
                            <a href="#">Sport</a><a href="#">Uncategorized</a>
                        </div>
                        <h4 class="title">
                            <a href="#">
                                Strength Trainin...
                            </a>
                        </h4>
                        <div class="date">
                            <time class="time-with-icon" datetime="July 10, 2025">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"></path>
                                </svg>
                                <span class="time">July 10, 2025</span>
                            </time>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="image has-post-thumbnail">
                        <div class="overlay"></div>
                        <div class="thumbnail" style="background-image: url(https://ricoboy.djavaweb.com/wp-content/uploads/2023/10/fit-swimmer-training-in-the-swimming-pool-2022-02-02-05-05-55-utc-250x277.jpg);"></div>
                    </div>
                    <div class="content">
                        <div class="the-categories">
                            <a href="#">Sport</a><a href="#">Uncategorized</a>
                        </div>
                        <h4 class="title">
                            <a href="#">
                                Power of Mindful...
                            </a>
                        </h4>
                        <div class="date">
                            <time class="time-with-icon" datetime="July 10, 2025">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"></path>
                                </svg>
                                <span class="time">July 10, 2025</span>
                            </time>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="image has-post-thumbnail">
                        <div class="overlay"></div>
                        <div class="thumbnail" style="background-image: url(https://ricoboy.djavaweb.com/wp-content/uploads/2023/10/athlete-s-sports-shoes-on-a-dirt-track-2021-08-26-19-58-08-utc-250x277.jpg);"></div>
                    </div>
                    <div class="content">
                        <div class="the-categories">
                            <a href="#">Sport</a><a href="#">Uncategorized</a>
                        </div>
                        <h4 class="title">
                            <a href="#">
                                Healthy Habits, ...
                            </a>
                        </h4>
                        <div class="date">
                            <time class="time-with-icon" datetime="July 10, 2025">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"></path>
                                </svg>
                                <span class="time">July 10, 2025</span>
                            </time>
                        </div>
                    </div>
                </li>
            </ul>
        </div>';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->solace_post_relatedpost_style();
        $this->solace_post_relatedpost_script();

        $columns = ! empty( $settings['columns'] ) ? intval( $settings['columns'] ) : 3;
        $gap = ! empty( $settings['grid_gap']['size'] ) ? intval( $settings['grid_gap']['size'] ) : 20;
        $gap_unit = ! empty( $settings['grid_gap']['unit'] ) ? $settings['grid_gap']['unit'] : 'px';

        $border_radius = ! empty( $settings['item_border_radius']['size'] ) ? intval( $settings['item_border_radius']['size'] ) : 10;
        $border_radius_unit = ! empty( $settings['item_border_radius']['unit'] ) ? $settings['item_border_radius']['unit'] : 'px';

        $column_width = 'calc((100% - ' . ($columns - 1) * $gap . $gap_unit . ') / ' . $columns . ')';

        $columns_tablet = !empty($settings['columns_tablet']) ? $settings['columns_tablet'] : $columns;
        $columns_mobile = !empty($settings['columns_mobile']) ? $settings['columns_mobile'] : $columns;

        $gap_tablet = !empty($settings['grid_gap_tablet']['size']) ? $settings['grid_gap_tablet']['size'] : $gap;
        $gap_mobile = !empty($settings['grid_gap_mobile']['size']) ? $settings['grid_gap_mobile']['size'] : $gap;

        $column_width_tablet = 'calc((100% - ' . ($columns_tablet - 1) * $gap_tablet . $gap_unit . ') / ' . $columns_tablet . ')';
        $column_width_mobile = 'calc((100% - ' . ($columns_mobile - 1) * $gap_mobile . $gap_unit . ') / ' . $columns_mobile . ')';


        $posts_per_page = !empty( $settings['posts_per_page'] ) ? $settings['posts_per_page'] : 5;
        $posts_per_page_tablet = !empty( $settings['posts_per_page_tablet'] ) ? $settings['posts_per_page_tablet'] : $posts_per_page;
        $posts_per_page_mobile = !empty( $settings['posts_per_page_mobile'] ) ? $settings['posts_per_page_mobile'] : $posts_per_page;


        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<style>
        .related-posts ul li { width: '.esc_attr($column_width).'; }
        @media(max-width: 1024px) {
        .related-posts ul li { width: '.esc_attr($column_width_tablet).'; }
        }
        @media(max-width: 767px) {
        .related-posts ul li { width: '.esc_attr($column_width_mobile).'; }
        }
        </style>';

        global $post;
        $categories = wp_get_post_categories( $post->ID );

        $args = [
            'category__in'   => $categories,
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
            'post__not_in'   => [ $post->ID ],
            'posts_per_page' => $posts_per_page,
            'ignore_sticky_posts' => 1
        ];

        $related_posts = new \WP_Query( $args );
        if ( $related_posts->have_posts() ) : ?>
            <div class="related-posts">
                <div class="data-post" 
                data-posts-desktop="<?php echo esc_attr( $posts_per_page ); ?>" 
                data-posts-tablet="<?php echo esc_attr( $posts_per_page_tablet ); ?>" 
                data-posts-mobile="<?php echo esc_attr( $posts_per_page_mobile ); ?>">
                <?php if ( ! empty( $settings['show_label'] ) && $settings['show_label'] === 'yes' ) : ?>
                    <h3><?php echo esc_html__('Related Posts', 'solace-extra'); ?></h3>
                <?php endif; ?>
                <ul>
                    <?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
                        <li>
                            <?php solace_the_thumbnail_related_posts('250x277', true); ?>
                            <div class="content">
                                <?php if ( ! empty( $settings['show_category'] ) && $settings['show_category'] === 'yes' ) : ?>
                                    <div class="the-categories"><?php echo wp_kses_post( get_the_category_list(', ') ); ?></div>
                                <?php endif; ?>
                                <?php if ( ! empty( $settings['show_post_title'] ) && $settings['show_post_title'] === 'yes' ) : ?>
                                    <h4 class="title">
                                        <a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_title() ); ?></a>
                                    </h4>
                                    <?php endif; ?>
                                <?php if ( ! empty( $settings['show_meta'] ) && $settings['show_meta'] === 'yes' ) : ?>
                                    <div class="date">
                                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                            <?php
                                            if ( ! empty( $settings['meta_icon']['value'] ) ) {
                                                \Elementor\Icons_Manager::render_icon( $settings['meta_icon'], [ 'aria-hidden' => 'true' ] );
                                            }
                                            ?>
                                            <span class="time"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
                                        </time>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </div>
        </div>
        <?php endif;?>

    <?php
    }


}
