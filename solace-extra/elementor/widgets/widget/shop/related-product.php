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

        $this->add_responsive_control(
            'products_per_page',
            [
                'label' => __('Products to Show', 'solace-extra'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 4,
                'desktop_default' => 4,
                'tablet_default' => 4,
                'mobile_default' => 4,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'solace-extra'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'tablet_default' => '3',
                'mobile_default' => '2',
                'options' => [
                    '1' => '1 Column',
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
        $settings = $this->get_settings_for_display();

        $this->get_style();

        $heading = $settings['heading'];
        $tag     = ! empty( $settings['related_heading_tag'] ) ? $settings['related_heading_tag'] : 'h2';
        $limits  = $this->get_products_per_page_limits();
        $limit   = $limits['max'];
        $columns = isset( $settings['columns'] ) ? intval( $settings['columns'] ) : 4;
        $columns = ( $columns >= 1 && $columns <= 6 ) ? $columns : 4;
        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';

        if ( function_exists( 'WC' ) ) {
            wp_enqueue_style( 'woocommerce-general' );
            wp_enqueue_style( 'woocommerce-layout' );
            wp_enqueue_style( 'woocommerce-smallscreen' );
        }

        echo '<section class="related products woocommerce">';
        if ( ! empty( $heading ) ) {
            echo sprintf(
                '<%1$s class="solace-related-heading">%2$s</%1$s>',
                esc_attr( $tag ),
                esc_html( $heading )
            );
        }

        add_filter( 'loop_shop_columns', function () use ( $columns ) {
            return $columns;
        } );

        woocommerce_product_loop_start();

        $placeholder_count   = max( 1, min( $limit, 8 ) );
        $placeholder_product = function_exists( 'solace_extra_placeholder_product' ) ? solace_extra_placeholder_product() : false;
        for ( $i = 0; $i < $placeholder_count; $i++ ) {
            $card_title = ( $placeholder_product && 0 === $i ) ? $placeholder_product->get_name() : sprintf( 'Lorem Ipsum Dolor Sit Amet %d', $i + 1 );
            if ( function_exists( 'solace_extra_placeholder_product_card' ) ) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo solace_extra_placeholder_product_card( $card_title, $hover_class );
            }
        }

    }

    public function get_style(){
		$settings  = $this->get_settings_for_display();

		$columns_desktop = ! empty( $settings['columns'] ) ? absint( $settings['columns'] ) : 4;
		$columns_tablet  = ! empty( $settings['columns_tablet'] ) ? absint( $settings['columns_tablet'] ) : $columns_desktop;
		$columns_mobile  = ! empty( $settings['columns_mobile'] ) ? absint( $settings['columns_mobile'] ) : $columns_tablet;

		$limits = $this->get_products_per_page_limits();
		$show_desktop = max( 1, $limits['desktop'] );
		$show_tablet  = max( 1, $limits['tablet'] );
		$show_mobile  = max( 1, $limits['mobile'] );

		echo '<style>
            .related.products.woocommerce ul.products li.product {
                display: flex;
                flex-direction: column;
                width: 100% !important;
                max-width: none !important;
                min-width: 0;
                margin: 0 !important;
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

			/* Responsive columns (grid) */
			.related.products.woocommerce ul.products {
				display: grid;
				grid-template-columns: repeat(' . absint( $columns_desktop ) . ', 1fr);
				gap: 20px;
				list-style: none;
				padding: 0;
				margin: 0 0 1em;
			}
			/* WC float-clearing pseudo-elements become grid items — disable them */
			.related.products.woocommerce ul.products::before,
			.related.products.woocommerce ul.products::after {
				content: none;
				display: none;
			}
			@media (max-width: 1024px) {
				.related.products.woocommerce ul.products {
					grid-template-columns: repeat(' . absint( $columns_tablet ) . ', 1fr);
				}
			}
			@media (max-width: 767px) {
				.related.products.woocommerce ul.products {
					grid-template-columns: repeat(' . absint( $columns_mobile ) . ', 1fr);
				}
			}

			/* Products to show per breakpoint */
			@media (min-width: 1025px) {
				.related.products.woocommerce ul.products li.product:nth-child(n+' . ( absint( $show_desktop ) + 1 ) . ') {
					display: none;
				}
			}
			@media (max-width: 1024px) {
				.related.products.woocommerce ul.products li.product {
					display: flex;
				}
				.related.products.woocommerce ul.products li.product:nth-child(n+' . ( absint( $show_tablet ) + 1 ) . ') {
					display: none;
				}
			}
			@media (max-width: 767px) {
				.related.products.woocommerce ul.products li.product {
					display: flex;
				}
				/* Re-show the items the tablet limit hid: same specificity, later
				   in the cascade, so mobile can exceed the tablet count. */
				.related.products.woocommerce ul.products li.product:nth-child(-n+' . absint( $show_mobile ) . ') {
					display: flex;
				}
				.related.products.woocommerce ul.products li.product:nth-child(n+' . ( absint( $show_mobile ) + 1 ) . ') {
					display: none;
				}
			}
		</style>';
	}

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Related products render only inside a product context:
        //  - frontend single product page
        //  - frontend product archive (category/tag/brand)
        //  - Site Builder "Single Product" template (editor)
        //  - Site Builder "Shop" / product archive template (editor)
        // Everywhere else render nothing to avoid a misleading preview.
        // Editor context: the Elementor admin editor AND its preview iframe both
        // count (the preview iframe runs the front-end render where
        // editor->is_edit_mode() is false and only preview->is_preview_mode()
        // is true).
        $is_elementor_editor = false;
        if ( class_exists( '\Elementor\Plugin' ) ) {
            $elementor_instance = \Elementor\Plugin::$instance;
            $is_elementor_editor = (
                ( isset( $elementor_instance->editor ) && method_exists( $elementor_instance->editor, 'is_edit_mode' ) && $elementor_instance->editor->is_edit_mode() )
                || ( isset( $elementor_instance->preview ) && method_exists( $elementor_instance->preview, 'is_preview_mode' ) && $elementor_instance->preview->is_preview_mode() )
            );
        }

        $is_single_product_template = false;
        $is_shop_archive_template   = false;
        if ( $is_elementor_editor ) {
            $editing_id = get_the_ID();
            $is_single_product_template = (
                $editing_id
                && 'solace-sitebuilder' === get_post_type( $editing_id )
                && metadata_exists( 'post', $editing_id, '_solace_singleproduct_status' )
            );
            $is_shop_archive_template = (
                $editing_id
                && 'solace-sitebuilder' === get_post_type( $editing_id )
                && metadata_exists( 'post', $editing_id, '_solace_shopproduct_status' )
            );
        }

        // Context: either a specific product (single) or a term list (archive).
        // $tax_args = false means "no archive term"; an array is a tax_query.
        $product  = false;
        $tax_args = false;

        if ( function_exists( 'is_product' ) && is_product() ) {
            // Frontend single product page.
            $product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : false;
        } elseif ( function_exists( 'is_product_category' ) && ( is_product_category() || is_product_tag() || is_tax( 'product_brand' ) ) ) {
            // Frontend product archive: mirror the current term, the same way
            // the Solace Shop widget does — "related" = products sharing the
            // archived category/tag/brand.
            $queried_object = get_queried_object();
            if ( $queried_object && isset( $queried_object->term_id ) && isset( $queried_object->taxonomy ) ) {
                $tax_args = [
                    [
                        'taxonomy' => sanitize_key( $queried_object->taxonomy ),
                        'field'    => 'term_id',
                        'terms'    => [ absint( $queried_object->term_id ) ],
                        'operator' => 'IN',
                    ],
                ];
            }
        } elseif ( $is_shop_archive_template ) {
            // Site Builder Shop (product archive) template editor/preview.
            $tax_args = $this->solace_related_shop_archive_tax_args( get_the_ID() );
        } elseif ( $is_elementor_editor ) {
            // Elementor editor/preview — Site Builder "Single Product" template
            // or any other post/page: mirror the upsells widget and resolve the
            // preview product (lorem-ipsum placeholder when the store has no
            // products), so the section isn't a blank grey box while designing.
            $product = function_exists( 'solace_get_preview_product' ) ? solace_get_preview_product() : false;
        } else {
            return;
        }

        // Single-product context always needs a valid product; archive context
        // needs at least one resolvable term.
        if ( ! $tax_args && ( ! $product || ! is_a( $product, 'WC_Product' ) ) ) {
            if ( $is_single_product_template || $is_shop_archive_template ) {
                $this->solace_related_products_empty();
            }
            return;
        }

        $this->get_style();
        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $category_hover_animation = ! empty( $settings['category_hover_animation'] ) ? $settings['category_hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';
        $category_hover_class     = $category_hover_animation ? ' elementor-animation-' . $category_hover_animation : '';

        // Enqueue WooCommerce default styles
        if (function_exists('WC')) {
            wp_enqueue_style('woocommerce-general');
            wp_enqueue_style('woocommerce-layout');
            wp_enqueue_style('woocommerce-smallscreen');
        }


        $heading = $settings['heading'];
        // Responsive controls: query the max of desktop/tablet/mobile so every
        // breakpoint has enough items; get_style() hides the surplus per
        // breakpoint via nth-child rules.
        $limits  = $this->get_products_per_page_limits();
        $limit   = $limits['max'];
        $columns = isset( $settings['columns'] ) ? intval( $settings['columns'] ) : 4;
        $columns = ( $columns >= 1 && $columns <= 6 ) ? $columns : 4;

        if ( $tax_args ) {
            // Archive context: products sharing the current/preview term.
            $args = [
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'no_found_rows'       => 1,
                'posts_per_page'      => $limit,
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
                'tax_query'           => $tax_args,
            ];
        } else {
            // Single product context: WooCommerce-style related products.
            $product_id  = $product->get_id();
            $related_ids = wc_get_related_products( $product_id, $limit );

            // No related products. On a fresh store with no products yet,
            // render lorem-ipsum placeholder cards so the section isn't blank
            // in the Site Builder editor.
            if ( empty( $related_ids ) ) {
                if ( $product && is_a( $product, 'WC_Product' ) && function_exists( 'solace_extra_is_placeholder_product' ) && solace_extra_is_placeholder_product( $product ) ) {
                    $heading = $settings['heading'];
                    $tag     = ! empty( $settings['related_heading_tag'] ) ? $settings['related_heading_tag'] : 'h2';

                    if ( function_exists( 'WC' ) ) {
                        wp_enqueue_style( 'woocommerce-general' );
                        wp_enqueue_style( 'woocommerce-layout' );
                        wp_enqueue_style( 'woocommerce-smallscreen' );
                    }

                    echo '<section class="related products woocommerce">';
                    if ( ! empty( $heading ) ) {
                        echo sprintf(
                            '<%1$s class="solace-related-heading">%2$s</%1$s>',
                            esc_attr( $tag ),
                            esc_html( $heading )
                        );
                    }
                    add_filter( 'loop_shop_columns', function () use ( $columns ) {
                        return $columns;
                    } );
                    woocommerce_product_loop_start();
                    $placeholder_count = max( 1, min( $limit, 8 ) );
                    for ( $i = 0; $i < $placeholder_count; $i++ ) {
                        $card_title = 0 === $i ? $product->get_name() : sprintf( 'Lorem Ipsum Dolor Sit Amet %d', $i + 1 );
                        if ( function_exists( 'solace_extra_placeholder_product_card' ) ) {
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo solace_extra_placeholder_product_card( $card_title, $hover_class );
                        }
                    }
                    woocommerce_product_loop_end();
                    echo '</section>';
                }
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
        }

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

    /**
     * Resolve the tax_query for the Site Builder "Shop" (product archive)
     * template editor/preview: the saved preview category, falling back to the
     * product category with the most products.
     *
     * @param int $template_id Site Builder part ID being edited.
     * @return array|false Array of tax_query clauses, or false when no category
     *                     can be resolved.
     */
    private function solace_related_shop_archive_tax_args( $template_id ) {
        $preview_settings = get_post_meta( $template_id, '_elementor_preview_settings_category_shop', true );
        $category_id      = 0;

        if ( ! empty( $preview_settings['preview_category'] ) ) {
            $category_id = absint( $preview_settings['preview_category'] );
        }

        if ( ! $category_id ) {
            $category_id = $this->solace_related_first_product_category_id();
        }

        if ( ! $category_id ) {
            return false;
        }

        return [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => [ $category_id ],
                'operator' => 'IN',
            ],
        ];
    }

    /**
     * ID of the product category with the most products, or 0 if none exists.
     *
     * @return int
     */
    private function solace_related_first_product_category_id() {
        $terms = get_terms( [
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'number'     => 1,
            'orderby'    => 'count',
            'order'      => 'DESC',
        ] );

        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            return 0;
        }

        return absint( $terms[0]->term_id );
    }

    /**
     * Per-breakpoint "Products to Show" limits from the responsive control.
     *
     * Tablet/mobile fall back to the desktop value when left empty. `max` is
     * the largest of the three — the server query renders that many items and
     * get_style() hides the surplus per breakpoint.
     *
     * @return array{desktop:int,tablet:int,mobile:int,max:int}
     */
    private function get_products_per_page_limits() {
        $settings = $this->get_settings_for_display();

        $desktop = isset( $settings['products_per_page'] ) ? intval( $settings['products_per_page'] ) : 4;
        $tablet  = isset( $settings['products_per_page_tablet'] ) && '' !== $settings['products_per_page_tablet'] ? intval( $settings['products_per_page_tablet'] ) : $desktop;
        $mobile  = isset( $settings['products_per_page_mobile'] ) && '' !== $settings['products_per_page_mobile'] ? intval( $settings['products_per_page_mobile'] ) : $tablet;

        $desktop = max( 1, $desktop );
        $tablet  = max( 1, $tablet );
        $mobile  = max( 1, $mobile );

        return [
            'desktop' => $desktop,
            'tablet'  => $tablet,
            'mobile'  => $mobile,
            'max'     => max( $desktop, $tablet, $mobile ),
        ];
    }
}
