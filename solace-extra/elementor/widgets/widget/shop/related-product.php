<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) exit;

class Solace_Extra_WooCommerce_Related_Products extends Widget_Base {

    public function get_name() {
        return 'solace-extra-woocommerce-related-products';
    }

    public function get_title() {
        return __('Related Products', 'solace-extra');
    }

    public function get_icon() {
        return 'eicon-products solace-extra';
    }

    public function get_categories() {
        return [ 'solace-extra-single' ]; 
    }

    public function get_keywords() {
        return ['woocommerce', 'related', 'products', 'shop'];
    }

	/**
	 * @param array $args {
	 *     An array of values for the button adjustments.
	 *
	 *     @type array  $section_condition  Set of conditions to hide the controls.
	 *     @type string $alignment_default  Default position for the button.
	 *     @type string $alignment_control_prefix_class  Prefix class name for the button position control.
	 *     @type string $content_alignment_default  Default alignment for the button content.
	 * }
	 */
	protected function register_button_style_controls( $args = [] ) {
		$selector = '{{WRAPPER}}.elementor-widget-solace-extra-woocommerce-related-products section.woocommerce li.product .add_to_cart_button';

		$selector_hover = '{{WRAPPER}}.elementor-widget-solace-extra-woocommerce-related-products section.woocommerce li.product .add_to_cart_button:hover'; 

		$default_args = [
			'section_condition' => [],
			'alignment_default' => '',
			'alignment_control_prefix_class' => 'elementor%s-align-',
			'content_alignment_default' => '',
		];

		$args = wp_parse_args( $args, $default_args );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => $selector,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style', [
			// 'condition' => $args['section_condition'],
		] );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'solace-extra' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude' => [ 'image' ],
				'selector' => $selector,
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => $selector,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'solace-extra' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [ $selector_hover => 'color: {{VALUE}};' ],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'types' => [ 'classic', 'gradient' ],
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude' => [ 'image' ],
				'selector' => $selector_hover,
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector_hover => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => $selector_hover,
			]
		);

		$this->add_control(
			'button_hover_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms', 'custom' ],
				'default' => [
					'unit' => 's',
				],
				'selectors' => [
					$selector_hover => 'transition-duration: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'solace-extra' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => $selector,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'solace-extra' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'solace-extra' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					$selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
	}    

	/**
	 * @param array $args {
	 *     An array of values for the button adjustments.
	 *
	 *     @type array  $section_condition  Set of conditions to hide the controls.
	 *     @type string $alignment_default  Default position for the button.
	 *     @type string $alignment_control_prefix_class  Prefix class name for the button position control.
	 *     @type string $content_alignment_default  Default alignment for the button content.
	 * }
	 */
	protected function register_button_category_style_controls( $args = [] ) {
		$selector = '{{WRAPPER}} .related.products .product-categories a';
        
		$selector_hover = '{{WRAPPER}} .related.products .product-categories a:hover'; 

		$default_args = [
			'section_condition' => [],
			'alignment_default' => '',
			'alignment_control_prefix_class' => 'elementor%s-align-',
			'content_alignment_default' => '',
		];

		$args = wp_parse_args( $args, $default_args );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'category_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => $selector,
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => 15,
							'unit' => 'px',
						],
					],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_category_style', [
			// 'condition' => $args['section_condition'],
		] );

		$this->start_controls_tab(
			'tab_category_normal',
			[
				'label' => esc_html__( 'Normal', 'solace-extra' ),
			]
		);

		$this->add_control(
			'category_text_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'category_background',
				'types' => [ 'classic', 'gradient' ],
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude' => [ 'image' ],
				'selector' => $selector,
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'category_spacing',
			[
				'label' => __( 'Spacing', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%'],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'selectors' => [
					$selector => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);		

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'category_box_shadow',
				'selector' => $selector,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_category_hover',
			[
				'label' => esc_html__( 'Hover', 'solace-extra' ),
			]
		);

		$this->add_control(
			'category_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [ $selector_hover => 'color: {{VALUE}};' ],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'category_background_hover',
				'types' => [ 'classic', 'gradient' ],
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude' => [ 'image' ],
				'selector' => $selector_hover,
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'category_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector_hover => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'category_hover_box_shadow',
				'selector' => $selector_hover,
			]
		);

		$this->add_control(
			'category_hover_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms', 'custom' ],
				'selectors' => [
					$selector_hover => 'transition-duration: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'category_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'solace-extra' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'category_border',
				'selector' => $selector,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'category_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'solace-extra' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'category_padding',
			[
				'label' => esc_html__( 'Padding', 'solace-extra' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					$selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
	}     

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Layout', 'solace-extra'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading Text', 'solace-extra'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Related Products', 'solace-extra'),
            ]
        );

        $this->add_control(
            'related_heading_tag',
            [
                'label' => __('Heading Tag', 'solace-extra'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'DIV',
                    'span' => 'SPAN',
                ],
            ]
        );

        $this->add_control(
            'products_per_page',
            [
                'label' => __('Products to Show', 'solace-extra'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 4,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'solace-extra'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                    '5' => '5 Columns',
                    '6' => '6 Columns',
                ],
            ]
        );

        


        $this->end_controls_section();

        $this->start_controls_section(
            'layout_related_style',
            [
                'label' => __( 'Layout', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'related_products_item_alignment',
            [
                'label'   => __( 'Alignment', 'solace-extra' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'default'   => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Left', 'solace-extra' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => __( 'Center', 'solace-extra' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => __( 'End', 'solace-extra' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .related.products ul.products li.product, {{WRAPPER}} .related.products ul.products li.product .woocommerce-loop-product__title' => 'align-items: {{VALUE}};justify-content: {{VALUE}};',
                ],
                'toggle'   => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'heading_style',
            [
                'label' => __( 'Heading', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'related_heading_alignment',
            [
                'label' => __('Alignment', 'solace-extra'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'solace-extra'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'solace-extra'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'solace-extra'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .related.products .solace-related-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'related_heading_typography',
                'selector' => '{{WRAPPER}} .related.products .solace-related-heading',
            ]
        );

        // Font Color
        $this->add_control(
            'related_heading_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related.products .solace-related-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Padding (with px & em)
        $this->add_responsive_control(
            'related_heading_padding',
            [
                'label' => __('Padding', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .related.products .solace-related-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin (with px & em)
        $this->add_responsive_control(
            'related_heading_margin',
            [
                'label' => __('Margin', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .related.products .solace-related-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Product Title', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'product_name_typography',
                'label'    => esc_html__('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .related.products .woocommerce-loop-product__title',
				
            ]
        );

        $this->add_control(
            'product_name_color',
            [
                'label'     => esc_html__('Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related.products .woocommerce-loop-product__title' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'product_name_padding',
			[
				'label'      => esc_html__('Padding', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .related.products .woocommerce-loop-product__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'product_name_margin',
			[
				'label'      => esc_html__('Margin', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .related.products .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        // ======= Section Price =======
		$this->start_controls_section(
			'section_price',
			[
				'label' => esc_html__('Price', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        // Price Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'label'    => esc_html__('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .related.products .price, {{WRAPPER}} .related.products .price span',
            ]
        );

        // Price Color
        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__('Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .related.products .price, {{WRAPPER}} .related.products .price span' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'price_padding',
			[
				'label'      => esc_html__('Padding', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .related.products .price, {{WRAPPER}} .related.products .price span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'price_margin',
			[
				'label'      => esc_html__('Margin', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .related.products .price, {{WRAPPER}} .related.products .price span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        // ======= Section Badge =======
		$this->start_controls_section(
			'section_sale',
			[
				'label' => esc_html__('Badge', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'badge_typography',
                'label'    => esc_html__('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale',
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label'     => esc_html__('Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .onsale' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label'     => esc_html__('Background Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .onsale' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		// Padding (Responsive)
		$this->add_responsive_control(
			'badge_padding',
			[
				'label'      => esc_html__('Padding', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default'    => [
					'top'    => '10',
					'right'  => '20',
					'bottom' => '10',
					'left'   => '20',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce ul.products li.product .onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin (Responsive)
		$this->add_responsive_control(
			'badge_margin',
			[
				'label'      => esc_html__('Margin', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default'    => [
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce ul.products li.product .onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		

		// Border Type
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'badge_border',
				'label'    => esc_html__('Border', 'solace-extra'),
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale',
			]
		);

		// Border Radius
		$this->add_responsive_control(
			'badge_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default'    => [
					'top'    => 12,
					'right'  => 12,
					'bottom' => 12,
					'left'   => 12,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce ul.products li.product .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box Shadow
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'badge_box_shadow',
				'label'    => esc_html__('Box Shadow', 'solace-extra'),
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale',
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'section_product_categories',
			[
				'label' => esc_html__('Product Categories', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->register_button_category_style_controls();

		$this->end_controls_section();        

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __( 'Button', 'solace-extra' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_button_style_controls();

        $this->end_controls_section();
    }


    public function solace_related_products_empty() {
        echo '<section class="related products woocommerce">
            <h2>Related Productsx</h2>
            <ul class="products columns-4">
                <li class="product type-product post-5914 status-publish first instock">
                    <div class="nv-card-content-wrapper">
                        <div class="sp-product-image">
                            <div class="img-wrap">
                                <img width="300" height="300" src="https://placehold.co/600x400?text=No Products" alt="Placeholder" style="background:#555; border-radius:8px;">
                            </div>
                        </div>
                        <h5 class="woocommerce-loop-product__title">No Products</h5>
                        <span class="price">
                            <span class="woocommerce-Price-amount amount">
                                <bdi><span class="woocommerce-Price-currencySymbol">$</span>299.99</bdi>
                            </span>
                        </span>
                        <a href="#" class="button product_type_simple add_to_cart_button ajax_add_to_cart elementor-button" data-product_id="5914">No Products</a>
                    </div>
                </li>
                <li class="product type-product post-5898 status-publish instock">
                    <div class="nv-card-content-wrapper">
                        <div class="sp-product-image">
                            <div class="img-wrap">
                                <img width="300" height="300" src="https://placehold.co/600x400?text=No Products" alt="Placeholder" style="background:#555; border-radius:8px;">
                            </div>
                        </div>
                        <h5 class="woocommerce-loop-product__title">No Products</h5>
                        <span class="price">
                            <del><span class="woocommerce-Price-amount amount">
                                <bdi><span class="woocommerce-Price-currencySymbol">$</span>266.00</bdi>
                            </span></del>
                            <ins><span class="woocommerce-Price-amount amount">
                                <bdi><span class="woocommerce-Price-currencySymbol">$</span>217.00</bdi>
                            </span></ins>
                        </span>
                        <a href="#" class="button product_type_simple add_to_cart_button ajax_add_to_cart elementor-button" data-product_id="5898">No Products</a>
                    </div>
                </li>
                <li class="product type-product post-5900 status-publish instock">
                    <div class="nv-card-content-wrapper">
                        <div class="sp-product-image">
                            <div class="img-wrap">
                                <img width="300" height="300" src="https://placehold.co/600x400?text=No Productse" alt="Placeholder" style="background:#555; border-radius:8px;">
                            </div>
                        </div>
                        <h5 class="woocommerce-loop-product__title">No Products</h5>
                        <span class="price">
                            <del><span class="woocommerce-Price-amount amount">
                                <bdi><span class="woocommerce-Price-currencySymbol">$</span>379.00</bdi>
                            </span></del>
                            <ins><span class="woocommerce-Price-amount amount">
                                <bdi><span class="woocommerce-Price-currencySymbol">$</span>300.00</bdi>
                            </span></ins>
                        </span>
                        <a href="#" class="button product_type_simple add_to_cart_button ajax_add_to_cart elementor-button" data-product_id="5900">No Products</a>
                    </div>
                </li>
                <li class="product type-product post-5904 status-publish last instock">
                    <div class="nv-card-content-wrapper">
                        <div class="sp-product-image">
                            <div class="img-wrap">
                                <img width="300" height="300" src="https://placehold.co/600x400?text=No Products" alt="Placeholder" style="background:#555; border-radius:8px;">
                            </div>
                        </div>
                        <h5 class="woocommerce-loop-product__title">No Products</h5>
                        <span class="price">
                            <del><span class="woocommerce-Price-amount amount">
                                <bdi><span class="woocommerce-Price-currencySymbol">$</span>338.00</bdi>
                            </span></del>
                            <ins><span class="woocommerce-Price-amount amount">
                                <bdi><span class="woocommerce-Price-currencySymbol">$</span>299.00</bdi>
                            </span></ins>
                        </span>
                        <a href="#" class="button product_type_simple add_to_cart_button ajax_add_to_cart elementor-button" data-product_id="5904">No Products</a>
                    </div>
                </li>
            </ul>
        </section>';
    }

    public function get_style(){
		echo '<style>
            .related.products.woocommerce ul.products li.product {
                display: flex;
                flex-direction: column;
            }
            .related.products.woocommerce ul.products li.product .woocommerce-loop-product__title {
                display: flex;
                justify-content: end;
            }
            .related.products.woocommerce ul.products .price {
                display: block;
                margin: 16px 0;
            }
            body.theme-solace .woocommerce ul.products li.product .onsale {
				background-color: var(--sol-color-selection-high);
				color: var(--sol-color-selection-initial);
			}
		</style>';
	}

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->get_style();
        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $category_hover_animation = ! empty( $settings['category_hover_animation'] ) ? $settings['category_hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';
        $category_hover_class     = $category_hover_animation ? ' elementor-animation-' . $category_hover_animation : '';

        // Get current product context
        $product = solace_get_preview_product();

        if (! $product) {
            $this->solace_related_products_empty();
            return;
        }

        $checkempty = solace_check_empty_product( $product );
        if ( $checkempty ) {
            $this->solace_related_products_empty();
            return;
        }

        // Enqueue WooCommerce default styles
        if (function_exists('WC')) {
            wp_enqueue_style('woocommerce-general');
            wp_enqueue_style('woocommerce-layout');
            wp_enqueue_style('woocommerce-smallscreen');
        }

        $product_id = $product->get_id();
        $heading = $settings['heading'];
        $limit = intval($settings['products_per_page']);
        $columns = intval($settings['columns']);
        $columns = ($columns >= 2 && $columns <= 6) ? $columns : 4;

        $related_ids = wc_get_related_products($product_id, $limit);
        if (empty($related_ids)) {
            return;
        }

        $args = apply_filters('woocommerce_related_products_args', [
            'post_type'           => 'product',
            'ignore_sticky_posts' => 1,
            'no_found_rows'       => 1,
            'posts_per_page'      => $limit,
            'post__in'            => $related_ids,
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in	
            'post__not_in'        => [$product_id],
        ]);

        $query = new WP_Query($args);

        if ($query->have_posts()) {

            add_filter('loop_shop_columns', function() use ($columns) {
                return $columns;
            });

            echo '<section class="related products woocommerce">';

            $tag = ! empty( $settings['related_heading_tag'] ) ? $settings['related_heading_tag'] : 'h2';
            if ( ! empty( $heading ) ) {
                echo sprintf(
                    '<%1$s class="solace-related-heading">%2$s</%1$s>',
                    esc_attr( $tag ),
                    esc_html( $heading )
                );
            }

            woocommerce_product_loop_start(); 

            while ($query->have_posts()) {
                $query->the_post();
                $product = wc_get_product(get_the_ID());

                if (! $product) continue;

                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<li class="product ' . implode(' ', wc_get_product_class('', $product)) . '">';
                
                echo '<a href="' . esc_url(get_permalink()) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                
                // Thumbnail
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo woocommerce_get_product_thumbnail();
                
                // Sale flash
                if ($product->is_on_sale()) {
                    echo '<span class="onsale">' . esc_html__('Sale!', 'solace-extra') . '</span>';
                }

                // Title
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<h4 class="woocommerce-loop-product__title">' . get_the_title() . '</h4>';
                echo '</a>'; 
				$categories = wc_get_product_category_list( $product->get_id(), ' ' );
				if ( $categories ) {
					echo '<span class="product-categories' . esc_attr( $category_hover_class ) . '">' . wp_kses_post( $categories ) . '</span>';
				}
                
                // Price
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<span class="price">' . $product->get_price_html() . '</span>';

                

                // Add to Cart Button
                echo '<a href="' . esc_url($product->add_to_cart_url()) . '" '
                . 'data-quantity="1" '
                . 'class="elementor-button solace-extra-button product_type_' . esc_attr($product->get_type()) . ' add_to_cart_button ajax_add_to_cart' . esc_attr($hover_class) . '" '
                . 'data-product_id="' . esc_attr($product->get_id()) . '" '
                /* translators: %s: product name */                
                . 'aria-label="' . esc_attr(sprintf(__('Add to cart: %s', 'solace-extra'), $product->get_name())) . '" '
                . 'rel="nofollow">'
                . esc_html__('Add to cart', 'solace-extra') .
                '</a>';

                echo '</li>';
            }

            woocommerce_product_loop_end(); 
            echo '</section>';

            remove_all_filters('loop_shop_columns');
        }

        wp_reset_postdata();
    }



}
