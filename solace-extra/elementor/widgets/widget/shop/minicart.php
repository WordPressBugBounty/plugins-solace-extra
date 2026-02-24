<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Solace_Extra_WooCommerce_MiniCart extends Widget_Base {

    public function get_name() {
        return 'solace_extra_minicart';
    }

    public function get_title() {
        return __( 'Mini Cart', 'solace-extra' );
    }

    public function get_icon() {
        return 'eicon-cart-medium solace-extra';
    }

    public function get_categories() {
        return ['solace-extra-woocommerce'];
    }

    public function get_keywords() {
        return ['woocommerce', 'cart', 'minicart'];
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
		$selector = '{{WRAPPER}} .solace-minicart-dropdown .elementor-button';

		$selector_hover = '{{WRAPPER}} .solace-minicart-dropdown .elementor-button:hover'; 

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

        $this->add_control(
            'minicart_buttons_flex_direction',
            [
                'label' => __( 'Style', 'solace-extra' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => __( 'Horizontal', 'solace-extra' ),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                    'column' => [
                        'title' => __( 'Vertical', 'solace-extra' ),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                ],
                'default' => 'row',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-cart__footer-buttons' => 'flex-direction: {{VALUE}};',
                ],
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

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'cart_icon',
            [
                'label' => __( 'Cart Icon', 'solace-extra' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-shopping-cart',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'dropdown_visibility',
            [
                'label' => __( 'Dropdown Visibility', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'click' => [
                        'title' => __( 'On Click', 'solace-extra' ),
                        'icon'  => 'eicon-click',
                    ],
                    'hover' => [
                        'title' => __( 'On Hover', 'solace-extra' ),
                        'icon'  => 'eicon-drag-n-drop',
                    ],
                ],
                'default' => 'click',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'show_subtotal',
            [
                'label' => __( 'Show Subtotal', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'solace-extra' ),
                'label_off' => __( 'No', 'solace-extra' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'dropdown_position',
            [
                'label'   => __( 'Minicart Align', 'solace-extra' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'right',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'solace-extra' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'solace-extra' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'solace-extra' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Mini Cart', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'minicart_product_mini_tabs' );

        // === ICON Tab ===
        $this->start_controls_tab(
            'minicart_product_icon_tab',
            [
                'label' => __( 'Icon', 'solace-extra' ),
            ]
        );

        // Icon size
        $this->add_control(
            'cart_icon_size',
            [
                'label' => __( 'Size', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-minicart-toggle svg.solace-cart-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .solace-minicart-toggle i.solace-cart-icon'   => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Icon color
        $this->add_control(
            'cart_icon_color',
            [
                'label' => __( 'Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-minicart-toggle svg.solace-cart-icon' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .solace-minicart-toggle i.solace-cart-icon'   => 'color: {{VALUE}};',
                ],
            ]
        );

        // Icon background
        $this->add_control(
            'cart_icon_bg',
            [
                'label' => __( 'Background Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-minicart-toggle' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        // Padding
        $this->add_responsive_control(
            'cart_icon_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-minicart-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin
        $this->add_responsive_control(
            'cart_icon_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-minicart-toggle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'cart_icon_border',
                'selector' => '{{WRAPPER}} .solace-minicart-toggle',
            ]
        );

        // Border radius
        $this->add_responsive_control(
            'cart_icon_border_radius',
            [
                'label' => __( 'Border Radius', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-minicart-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // === Quantity Tab ===
        $this->start_controls_tab(
            'minicart_product_badge_tab',
            [
                'label' => __( 'Badge', 'solace-extra' ),
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'selector' => '{{WRAPPER}} .solace-cart-count',
            ]
        );

        // Font color
        $this->add_control(
            'count_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-cart-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Background color
        $this->add_control(
            'count_bg',
            [
                'label' => __( 'Background Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-cart-count' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'count_border',
                'selector' => '{{WRAPPER}} .solace-cart-count',
            ]
        );

        // Border radius
        $this->add_responsive_control(
            'count_border_radius',
            [
                'label' => __( 'Border Radius', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-cart-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'count_icon_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-cart-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin
        $this->add_responsive_control(
            'count_icon_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-cart-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // === Quantity Tab ===
        $this->start_controls_tab(
            'minicart_product_subtotal_tab',
            [
                'label' => __( 'Subtotal', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'subtotal_color',
            [
                'label' => __( 'Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-cart-subtotal' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .solace-cart-subtotal .woocommerce-Price-amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subtotal_typography',
                'selector' => '{{WRAPPER}} .solace-cart-subtotal',
            ]
        );

        $this->add_responsive_control(
            'subtotal_spacing',
            [
                'label' => __( 'Spacing from Icon', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-cart-subtotal' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        
        // ==== MINICART BOX ====
        $this->start_controls_section(
            'section_style_minicart',
            [
                'label' => __( 'Cart', 'solace-extra' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Background
        $this->add_control(
            'minicart_dropdown_background_color',
            [
                'label'     => __( 'Background Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-minicart-dropdown' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'minicart_dropdown_padding',
            [
                'label'      => __( 'Padding', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .solace-minicart-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'minicart_dropdown_border',
                'selector' => '{{WRAPPER}} .solace-minicart-dropdown',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'minicart_dropdown_border_radius',
            [
                'label'      => __( 'Border Radius', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .solace-minicart-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator_2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'minicart_button_heading_list',
            [
                'label' => __( 'Product List', 'solace-extra' ),
                'type'  => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->start_controls_tabs( 'minicart_product_text_tabs' );

            // === Product Title Tab ===
            $this->start_controls_tab(
                'minicart_product_title_tab',
                [
                    'label' => __( 'Title', 'solace-extra' ),
                ]
            );

            $this->add_control(
                'minicart_product_title_color',
                [
                    'label'     => __( 'Text Color', 'solace-extra' ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .solace-minicart-dropdown .product-name a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name'     => 'minicart_product_title_typography',
                    'selector' => '{{WRAPPER}} .solace-minicart-dropdown .product-name a',
                ]
            );

            $this->end_controls_tab();

            // === Quantity Tab ===
            $this->start_controls_tab(
                'minicart_product_qty_tab',
                [
                    'label' => __( 'Quantity', 'solace-extra' ),
                ]
            );

            $this->add_control(
                'minicart_product_qty_color',
                [
                    'label'     => __( 'Text Color', 'solace-extra' ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .solace-minicart-dropdown .product-price .product-quantity' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name'     => 'minicart_product_qty_typography',
                    'selector' => '{{WRAPPER}} .solace-minicart-dropdown .product-price .product-quantity',
                ]
            );

            $this->end_controls_tab();

            // === Price Tab ===
            $this->start_controls_tab(
                'minicart_product_price_tab',
                [
                    'label' => __( 'Price', 'solace-extra' ),
                ]
            );

            $this->add_control(
                'minicart_product_price_color',
                [
                    'label'     => __( 'Text Color', 'solace-extra' ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .solace-minicart-dropdown .product-price .woocommerce-Price-amount' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name'     => 'minicart_product_price_typography',
                    'selector' => '{{WRAPPER}} .solace-minicart-dropdown .product-price .woocommerce-Price-amount',
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'separator_3',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'minicart_button_heading_subtotal',
            [
                'label' => __( 'Subtotal', 'solace-extra' ),
                'type'  => \Elementor\Controls_Manager::HEADING,
            ]
        );

        // ==== SUBTOTAL ====
        $this->add_control(
            'minicart_subtotal_color',
            [
                'label'     => __( 'Text Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-minicart-dropdown .elementor-menu-cart__subtotal' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'minicart_subtotal_typography',
                'selector' => '{{WRAPPER}} .solace-minicart-dropdown .elementor-menu-cart__subtotal',
            ]
        );

        // Background Color
        $this->add_control(
            'minicart_subtotal_background',
            [
                'label'     => __( 'Background Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-minicart-dropdown .elementor-menu-cart__subtotal' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'minicart_subtotal_padding',
            [
                'label'      => __( 'Padding', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
					'top' => 5,
					'right' => 21,
					'bottom' => 5,
					'left' => 21,
				],
                'selectors'  => [
                    '{{WRAPPER}} .solace-minicart-dropdown .elementor-menu-cart__subtotal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin
        $this->add_responsive_control(
            'minicart_subtotal_margin',
            [
                'label'      => __( 'Margin', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .solace-minicart-dropdown .elementor-menu-cart__subtotal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'minicart_subtotal_border',
                'selector' => '{{WRAPPER}} .solace-minicart-dropdown .elementor-menu-cart__subtotal',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'minicart_subtotal_border_radius',
            [
                'label'      => __( 'Border Radius', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .solace-minicart-dropdown .elementor-menu-cart__subtotal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        // ==== BUTTON STYLE ====
        $this->start_controls_section(
            'minicart_section_button',
            [
                'label' => __( 'Buttons', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_button_style_controls();

        $this->end_controls_section();

    }

    protected function style(){
        echo '<style>
        .solace-minicart-wrap {
            position: relative;
            display: inline-block;
        }
        .solace-minicart-toggle {
            position: relative; 
            display: inline-block;
            cursor: pointer;
            padding: 6px 10px;
            background: #f5f5f5;
            border-radius: 6px;
            text-decoration: none;
            color: inherit;
            font-size: 20px; 
            display: flex;
            flex-direction: row-reverse;
            align-items: center;
        }
        .solace-minicart-toggle .solace-cart-icon {
            font-size: 22px;
            line-height: 1;
            width: 19px;
        }

        .solace-cart-count {
            position: absolute;
            top: -16px;
            right: -16px;
            background: red;
            color: #fff;
            font-size: 12px;
            font-weight: bold;

            width: 24px;   
            height: 24px;
            border-radius: 50%;

            display: flex;
            align-items: center;   
            justify-content: center; 
        }
        .solace-minicart-dropdown {
            display: block;
            position: absolute;
            right: 0;
            top: 100%;
            background: #fff;
            border: 1px solid #ddd;
            width: 280px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 9999;
            box-shadow: 0 6px 12px rgba(0,0,0,.1);
            border-radius: 8px;
            padding: 10px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
        }
        
        .solace-minicart-wrap.dropdown-open .solace-minicart-dropdown { 
            display: block; 
            display: block !important;
            opacity: 1;
            visibility: visible;
        }
        .solace-minicart-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .elementor-editor-active .solace-minicart-wrap.dropdown-open:not(.position-center) .solace-minicart-dropdown {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }
        .elementor-editor-active .solace-minicart-wrap[data-visibility="hover"]:hover .solace-minicart-dropdown {
            display: block !important;
            opacity: 1;
            visibility: visible;
        }

        .elementor-editor-active .solace-minicart-wrap[data-visibility="click"].dropdown-open .solace-minicart-dropdown {
            display: block !important;
            opacity: 1;
            visibility: visible;
        }

        .elementor-menu-cart__products {
            max-height: calc(100vh - 250px);
            overflow: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .elementor-menu-cart__product:last-child {
            border: none;
        }

        .elementor-menu-cart__product, .elementor-menu-cart__subtotal {
            padding-bottom: var(--product-divider-gap, 20px);
        }

        .elementor-menu-cart__product {
            border-bottom-color: var(--divider-color, #d5d8dc);
            border-bottom-style: var(--divider-style, solid);
            border-width: 0 0 var(--divider-width, 1px);
            display: grid;
            grid-template-columns: 28% auto;
            grid-template-rows: var(--price-quantity-position--grid-template-rows, auto auto);
            position: relative;
        }

        .elementor-menu-cart__product-image {
            grid-row-end: 3;
            grid-row-start: 1;
            width: 100%;
        }

        .solace-minicart-wrap .elementor-menu-cart__product {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .solace-minicart-wrap .elementor-menu-cart__product.removing {
            opacity: 0;
            margin: 0;
            padding: 0;
            height: 0;
        }

        .elementor-menu-cart__product-image a, .elementor-menu-cart__product-image img {
            display: block;
        }

        .elementor-menu-cart__product-name, .elementor-menu-cart__product-price {
            font-size: 14px;
            padding-left: 20px;
        }

        .elementor-menu-cart__product-name {
            grid-column-end: 3;
            grid-column-start: 2;
            margin: 0;
        }

        .elementor-menu-cart__product-name, .elementor-menu-cart__product-price {
            font-size: 14px;
            padding-left: 20px;
        }

        .elementor-menu-cart__subtotal {
            border-color: var(--subtotal-divider-color, #d5d8dc);
            border-style: var(--subtotal-divider-style, solid);
            border-width: var(--subtotal-divider-top-width, 1px) var(--subtotal-divider-right-width, 1px) var(--subtotal-divider-bottom-width, 1px) var(--subtotal-divider-left-width, 1px);
            color: var(--menu-cart-subtotal-color, inherit);
            font-size: 20px;
            font-weight: 600;
            text-align: var(--menu-cart-subtotal-text-align, center);
        }

        .elementor-menu-cart__product-price {
            align-self: var(--price-quantity-position--align-self, end);
            color: var(--product-price-color, #d5d8dc);
            font-weight: 300;
            grid-column-end: 3;
            grid-column-start: 2;
            position: relative;
        }

        .elementor-menu-cart__footer-buttons {
            display: var(--cart-footer-buttons-alignment-display, grid);
            font-size: 20px;
            grid-template-columns: var(--cart-footer-layout, 1fr 1fr);
            margin-top: var(--cart-buttons-position-margin, 0);
            text-align: var(--cart-footer-buttons-alignment-text-align, center);
            grid-column-gap: var(--space-between-buttons, 10px);
            grid-row-gap: var(--space-between-buttons, 10px);
        }

        .elementor-menu-cart__footer-buttons, .elementor-menu-cart__product:not(:first-of-type), .elementor-menu-cart__subtotal {
            padding-top: var(--product-divider-gap, 20px);
        }

        .solace-remove-icon {
            font-size: 10px;
            color: #69727d;
            transition: color .3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .solace-remove-icon:hover {
            color: #ff0000; /* warna hover */
        }

        .elementor-menu-cart__product-remove.product-remove {
            position: absolute;
            top: 0;
            right: 0;
        }
        .elementor-menu-cart__product-remove.product-remove .remove_from_cart_button {
            padding-left: 1px;
            padding-bottom: 2px;
        }
            
        .solace-minicart-dropdown {
            max-height: 400px;      
            overflow-y: auto;       
            scrollbar-width: thin;  
            scrollbar-color: #aaa transparent; 
        }

        
        .solace-minicart-dropdown::-webkit-scrollbar {
            width: 2px;  
        }

        .solace-minicart-dropdown::-webkit-scrollbar-thumb {
            background: #aaa; 
            border-radius: 2px;
        }

        .solace-minicart-dropdown::-webkit-scrollbar-thumb:hover {
            background: #777; 
        }

        .solace-minicart-dropdown::-webkit-scrollbar-track {
            background: transparent; 
        }

        .elementor-menu-cart__footer-buttons {
            display: flex;
            width: 100%;
            justify-content: space-between;
            flex-direction: row;
        }

        .solace-minicart-wrap.position-left .solace-minicart-dropdown {
            left: 0;
            right: auto;
        }
        .solace-minicart-wrap.position-center .solace-minicart-dropdown {
            left: 50%;
            transform: translateX(-50%);
        }
        .solace-minicart-wrap.position-right .solace-minicart-dropdown {
            right: 0;
            left: auto;
        }

        .solace-minicart-dropdown span {
            color: var(--e-global-color-solcolorbasefont);
            font-weight: 600;
        }

        body .solace-minicart-dropdown .elementor-menu-cart__product {
            padding-right: 0;
            padding-bottom: 12px;
            padding-top: 12px;
        }

        .solace-minicart-dropdown .elementor-menu-cart__product-name.product-name {
            font-weight: 600;
        }

        .solace-minicart-dropdown .elementor-button {
            padding: 12px;
        }


        </style>';
    }

    protected function script() {
        echo '<script>
        (function($){

            function initSolaceMiniCart(){
                const minicarts = document.querySelectorAll(".solace-minicart-wrap");

                minicarts.forEach(function(wrap){
                    const toggle = wrap.querySelector(".solace-minicart-toggle");
                    const dropdown = wrap.querySelector(".solace-minicart-dropdown");
                    const visibility = wrap.getAttribute("data-visibility") || "click";

                    if(!toggle || !dropdown) return;

                    // === CLICK MODE ===
                    if(visibility === "click"){
                        wrap.classList.add("click-mode");

                        $(toggle).off("click.solace").on("click.solace", function(e){
                            e.preventDefault();
                            $(wrap).toggleClass("dropdown-open");
                        });

                        $(document).off("click.solaceOutside").on("click.solaceOutside", function(e){
                            if (!$(e.target).closest(".solace-minicart-wrap").length) {
                                $(".solace-minicart-wrap.click-mode").removeClass("dropdown-open");
                            }
                        });
                    }

                    // === HOVER MODE ===
                    if(visibility === "hover"){
                        wrap.classList.add("hover-mode");
                        let hideTimeout;

                        wrap.addEventListener("mouseenter", () => {
                            clearTimeout(hideTimeout);
                            wrap.classList.add("dropdown-open");
                        });

                        wrap.addEventListener("mouseleave", () => {
                            hideTimeout = setTimeout(() => {
                                wrap.classList.remove("dropdown-open");
                            }, 500);
                        });
                    }
                });
            }

            // === INIT ===
            $(document).ready(function(){
                initSolaceMiniCart();
            });

            $(window).on("elementor/frontend/init", function(){
                initSolaceMiniCart();
            });

            function updateSubtotal() {
                var subtotal = 0;

                $(".solace-minicart-wrap .elementor-menu-cart__product").each(function(){
                    var priceText = $(this).find(".woocommerce-Price-amount").first().text();
                    if(priceText){
                        var number = priceText.replace(/[^0-9.,]/g, "").replace(",", "");
                        var val = parseFloat(number);
                        if(!isNaN(val)) subtotal += val;
                    }
                });

                if ($(".solace-minicart-wrap .elementor-menu-cart__subtotal").length) {
                    $(".solace-minicart-wrap .elementor-menu-cart__subtotal .woocommerce-Price-amount bdi")
                        .text(subtotal.toFixed(2));
                }

                var count = $(".solace-minicart-wrap .elementor-menu-cart__product").length;
                if(count === 0){
                    $(".solace-minicart-wrap .solace-minicart-content").html(
                        "<p class=\'woocommerce-mini-cart__empty-message\'>Cart is empty.</p>"
                    );
                }
            }

            // === REMOVE ITEM ===
            $(document).on("click", ".solace-minicart-wrap .remove_from_cart_button", function(e){
                var $btn = $(this);
                var $item = $btn.closest(".elementor-menu-cart__product");

                $item.addClass("removing"); 

                setTimeout(function(){
                    $item.remove();

                    var count = $(".solace-minicart-wrap .elementor-menu-cart__product").length;
                    $(".solace-minicart-wrap .solace-cart-count").text(count);

                    updateSubtotal();
                }, 300); 
            });


        })(jQuery);
        </script>';
    }

    protected function solace_render_elementor_style_minicart() {
        // self::render_minicart_static();
    }

    protected function solace_render_elementor_style_minicart2() {
        if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
            echo '<p class="woocommerce-mini-cart__empty-message">' . esc_html__( 'No products in the cart.', 'solace-extra' ) . '</p>';
            return;
        }

        $cart_items = WC()->cart->get_cart();

        if ( empty( $cart_items ) ) {
            echo '<p class="woocommerce-mini-cart__empty-message">' . esc_html__( 'No products in the cart.', 'solace-extra' ) . '</p>';
            return;
        }

        echo '<div class="elementor-menu-cart__products woocommerce-mini-cart cart woocommerce-cart-form__contents">';

        foreach ( $cart_items as $cart_item_key => $cart_item ) {
            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
                $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
                $thumbnail         = $_product->get_image( 'woocommerce_thumbnail' );
                $product_name      = $_product->get_name();
                $product_price     = WC()->cart->get_product_price( $_product );
                $remove_url        = wc_get_cart_remove_url( $cart_item_key );

                echo '<div class="elementor-menu-cart__product woocommerce-cart-form__cart-item cart_item">';

                echo '<div class="elementor-menu-cart__product-image product-thumbnail">';
                if ( $product_permalink ) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '<a href="' . esc_url( $product_permalink ) . '">' . $thumbnail . '</a>';
                } else {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo $thumbnail;
                }
                echo '</div>';

                echo '<div class="elementor-menu-cart__product-name product-name" data-title="' . esc_attr__( 'Product', 'solace-extra' ) . '">';
                if ( $product_permalink ) {
                    echo '<a href="' . esc_url( $product_permalink ) . '">' . esc_html( $product_name ) . '</a>';
                } else {
                    echo esc_html( $product_name );
                }
                echo '</div>';

                echo '<div class="elementor-menu-cart__product-price product-price" data-title="' . esc_attr__( 'Price', 'solace-extra' ) . '">';
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<span class="quantity"><span class="product-quantity">' . esc_html( $cart_item['quantity'] ) . ' ×</span> ' . $product_price . '</span>';

                $remove_style = 'display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;line-height: 20px;border:1px solid #d5d8dc;border-radius:20px;';

                echo '<div class="elementor-menu-cart__product-remove product-remove" style="' . esc_attr( $remove_style ) . '">';

                $icon_html = '';
                if ( ! empty( $settings['remove_icon']['value'] ) ) {
                    ob_start();
                    \Elementor\Icons_Manager::render_icon(
                        $settings['remove_icon'],
                        [ 'aria-hidden' => 'true', 'class' => 'solace-remove-icon' ]
                    );
                    $icon_html = ob_get_clean();
                } else {
                    // fallback default
                    $icon_html = '<i class="fas fa-times solace-remove-icon"></i>';
                }

                echo '<a href="' . esc_url( $remove_url ) . '" 
                        class="elementor_remove_from_cart_button remove_from_cart_button" 
                        aria-label="' . esc_attr__( 'Remove this item', 'solace-extra' ) . '" 
                        data-product_id="' . esc_attr( $product_id ) . '" 
                        data-cart_item_key="' . esc_attr( $cart_item_key ) . '" 
                        data-product_sku="' . esc_attr( $_product->get_sku() ) . '">' 
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        . $icon_html . 
                    '</a>';

                echo '</div>';


                echo '</div>';

                echo '</div>'; // end product row
            }
        }

        echo '</div>'; // end .elementor-menu-cart__products

        echo '<div class="elementor-menu-cart__subtotal">';
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<strong>' . esc_html__( 'Subtotal:', 'solace-extra' ) . '</strong> ' . WC()->cart->get_cart_subtotal();
        echo '</div>';

        $settings = $this->get_settings_for_display();

        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';

        echo '<div class="elementor-menu-cart__footer-buttons">';
        echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="solace-extra-button elementor-button elementor-button--view-cart ' . esc_attr( $hover_class ) . '">' . esc_html__( 'View Cart', 'solace-extra' ) . '</a>';
        echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="solace-extra-button elementor-button elementor-button--checkout ' . esc_attr( $hover_class ) . '">' . esc_html__( 'Checkout', 'solace-extra' ) . '</a>';
        echo '</div>';
    }

    public static function render_minicart_static() {
        echo '<pre>MINICART CALLED</pre>';

        if ( function_exists( 'wc_load_cart' ) && ! WC()->cart ) {
            wc_load_cart();
        }

        if ( ! WC()->cart ) {
            return;
        }

        $cart_items = WC()->cart->get_cart();

        if ( empty( $cart_items ) ) {
            echo '<p class="woocommerce-mini-cart__empty-message">No products in the cart.</p>';
            return;
        }

        if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
            echo '<p class="woocommerce-mini-cart__empty-message">No products in the cart.</p>';
            return;
        }

        $cart_items = WC()->cart->get_cart();

        if ( empty( $cart_items ) ) {
            echo '<p class="woocommerce-mini-cart__empty-message">No products in the cart.</p>';
            return;
        }

        echo '<div class="elementor-menu-cart__products woocommerce-mini-cart cart woocommerce-cart-form__contents">';

        foreach ( $cart_items as $cart_item_key => $cart_item ) {
            $_product = $cart_item['data'];

            if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) {
                continue;
            }

            $thumbnail     = $_product->get_image( 'woocommerce_thumbnail' );
            $product_name  = $_product->get_name();
            $product_price = WC()->cart->get_product_price( $_product );
            $remove_url    = wc_get_cart_remove_url( $cart_item_key );

            echo '<div class="elementor-menu-cart__product">';

            echo '<div class="elementor-menu-cart__product-image">' . wp_kses_post( $thumbnail ) . '</div>';

            echo '<div class="elementor-menu-cart__product-name">' .
                    esc_html( $product_name ) .
                '</div>';

            echo '<div class="elementor-menu-cart__product-price">
                    <span class="quantity">' .
                    esc_html( $cart_item['quantity'] ) .
                    ' × ' . wp_kses_post( $product_price ) .
                '</span>
            </div>';

            echo '<a href="' . esc_url( $remove_url ) . '" 
                class="remove remove_from_cart_button"
                data-cart_item_key="' . esc_attr( $cart_item_key ) . '">
                ×
            </a>';

            echo '</div>';
        }

        echo '</div>';

        echo '<div class="elementor-menu-cart__subtotal">';
        echo '<strong>' . esc_html__( 'Subtotal:', 'solace-extra' ) . '</strong> ' . wp_kses_post( WC()->cart->get_cart_subtotal() );
        echo '</div>';
    }


    protected function render() {
        $settings = $this->get_settings_for_display();
        $position = $settings['dropdown_position'];

        if ( function_exists( 'WC' ) && ! WC()->cart && function_exists( 'wc_load_cart' ) ) {
            wc_load_cart();
        }
        $this->style();
        $this->script();
        ?>
        <div class="solace-minicart-wrap position-<?php echo esc_attr( $position ); ?>"  data-visibility="<?php echo esc_attr( $settings['dropdown_visibility'] ); ?>">
            <a href="#" class="solace-minicart-toggle">
                <?php 
                if ( ! empty( $settings['cart_icon']['value'] ) ) {
                    \Elementor\Icons_Manager::render_icon( 
                        $settings['cart_icon'], 
                        [ 'aria-hidden' => 'true', 'class' => 'solace-cart-icon' ] 
                    );
                } else {
                    // fallback default icon
                    echo '<i class="fas fa-shopping-cart solace-cart-icon"></i>';
                }
                ?>
                <?php if ( $settings['show_subtotal'] === 'yes' ) : ?>
                    <span class="solace-cart-subtotal">
                        <?php 
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo WC()->cart->get_cart_subtotal();
                        ?>
                    </span>
                <?php endif; ?>
                <span class="solace-cart-count">
                    <?php 
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo WC()->cart->get_cart_contents_count();
                    ?>
                </span>
            </a>
            <div class="solace-minicart-dropdown">
                <div class="solace-minicart-content">
                    <?php //$this->solace_render_elementor_style_minicart(); ?>
                    <?php solace_render_minicart_html( $settings ); ?>
                </div>
            </div>
        </div>

        
        <?php
    }
}
