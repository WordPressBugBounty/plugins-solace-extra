<?php

use Elementor\Widget_Base;
use Elementor\Group_Control_Background;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Solace_Extra_WooCommerce_Checkout extends Widget_Base {

    public function get_name() {
        return 'solace-extra-woocommerce-checkout';
    }

    public function get_title() {
        return __('Checkout', 'solace-extra');
    }

    public function get_icon() {
        return 'eicon-checkout solace-extra';
    }

    public function get_categories() {
        return ['solace-extra-woocommerce'];
    }

    public function get_keywords() {
        return ['woocommerce', 'checkout', 'payment'];
    }

	public function get_style_depends(): array {
		return [ 'solace-checkout' ];
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
		$selector = '
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce .checkout_coupon button,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce form.woocommerce-checkout button.button,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce form.woocommerce-checkout a.button,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce form.woocommerce-checkout input.button,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce form.woocommerce-checkout #place_order,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce form.woocommerce-checkout .place-order button,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce .checkout_coupon button,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce .button:not(header.button):not(footer.button)';

		$selector_hover = '
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce .checkout_coupon button:hover,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce form.woocommerce-checkout button.button:hover,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce form.woocommerce-checkout a.button:hover,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce form.woocommerce-checkout input.button:hover,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce form.woocommerce-checkout #place_order:hover,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce form.woocommerce-checkout .place-order button:hover,
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce .button:not(header.button):not(footer.button):hover';

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

    protected function register_controls() {

        // === Content Tab ===
        
        // Layout Section
        $this->start_controls_section(
            'section_checkout_layout',
            [
                'label' => __('Layout', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Dropdown layout
        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'solace-extra'),
                'type' => Controls_Manager::SELECT,
                'default' => 'layout-1',
                'options' => [
                    'layout-1' => __('Layout 1', 'solace-extra'),
                    'layout-2' => __('Layout 2', 'solace-extra'),
                    'layout-3' => __('Layout 3', 'solace-extra'),
                ],
            ]
        );

        $this->end_controls_section();

        // Billing Details Section
        $this->start_controls_section(
            'section_billing_details',
            [
                'label' => __('Billing Details', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Billing Heading
        $this->add_control(
            'billing_heading',
            [
                'label'       => __('Billing', 'solace-extra'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Billing details', 'solace-extra'),
                'placeholder' => __('Enter billing title', 'solace-extra'),
            ]
        );

        // Ship to Different Address Text
        $this->add_control(
            'ship_different_address_text',
            [
                'label'       => __('Address', 'solace-extra'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Ship to a different address?', 'solace-extra'),
                'placeholder' => __('Enter ship to different address text', 'solace-extra'),
            ]
        );

        $this->end_controls_section();

        // Additional Information Section
        $this->start_controls_section(
            'section_additional_information',
            [
                'label' => __('Additional Information', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );        

        // Show Additional Information (toggle)
        $this->add_control(
            'show_additional_information',
            [
                'label' => __('Show Additional information', 'solace-extra'),
                'type'  => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'solace-extra'),
                'label_off' => __('Hide', 'solace-extra'),
                'return_value' => 'yes',
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .woocommerce-additional-fields, {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .box-shipping, {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 form.checkout .box-shipping' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'yes' => 'display: block;',
                    ''    => 'display: none;',
                ],
            ]
        );        

        // Layout 3: set padding-right: 0 on form when Additional Information is hidden (switcher OFF)
        $this->add_control(
            'layout3_padding_when_additional_hidden',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => '1',
                'condition' => [
                    'layout' => 'layout-3',
                    'show_additional_information' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 form.checkout' => 'padding-right: 0;',
                ],
            ]
        );

        // Additional Information Heading
        $this->add_control(
            'additional_information_heading',
            [
                'label'       => __('Additional Information', 'solace-extra'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Additional information', 'solace-extra'),
                'placeholder' => __('Enter additional information title', 'solace-extra'),
                'condition' => [ 'show_additional_information' => 'yes' ],
            ]
        );

        $this->end_controls_section();

        // Orders Section
        $this->start_controls_section(
            'section_order',
            [
                'label' => __('Orders', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Order Review Heading
        $this->add_control(
            'order_review_heading',
            [
                'label'       => __('Order Review Heading', 'solace-extra'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Your order', 'solace-extra'),
                'placeholder' => __('Enter order review title', 'solace-extra'),
            ]
        );

        // Button Placeholder
        $this->add_control(
            'button_placeholder',
            [
                'label'       => __('Button Placeholder', 'solace-extra'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Place order', 'solace-extra'),
                'placeholder' => __('Enter button placeholder text', 'solace-extra'),
            ]
        );        

        $this->end_controls_section();

        // Coupon Section
        $this->start_controls_section(
            'section_coupon',
            [
                'label' => __('Coupon', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Show Coupon Notice (toggle)
        $this->add_control(
            'show_coupon_notice',
            [
                'label' => __('Show Coupon', 'solace-extra'),
                'type'  => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'solace-extra'),
                'label_off' => __('Hide', 'solace-extra'),
                'return_value' => 'yes',
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce-info' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'yes' => 'display: flex;',
                    ''    => 'display: none;',
                ],
            ]
        );        

        // Coupon Notice Text
        $this->add_control(
            'coupon_notice_text',
            [
                'label'       => __('Coupon Notice', 'solace-extra'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Have a coupon?', 'solace-extra'),
                'placeholder' => __('Enter coupon notice text', 'solace-extra'),
                'condition' => [ 'show_coupon_notice' => 'yes' ],
            ]
        );

        // Coupon Link Text
        $this->add_control(
            'coupon_link_text',
            [
                'label'       => __('Coupon Link', 'solace-extra'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Click here to enter your code', 'solace-extra'),
                'placeholder' => __('Enter coupon link text', 'solace-extra'),
                'condition' => [ 'show_coupon_notice' => 'yes' ],
            ]
        );

        // Apply Coupon Text
        $this->add_control(
            'apply_coupon_button_text',
            [
                'label'       => __('Apply Coupon', 'solace-extra'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Apply coupon', 'solace-extra'),
                'placeholder' => __('Enter apply coupon button text', 'solace-extra'),
                'condition' => [ 'show_coupon_notice' => 'yes' ],
            ]
        );

        $this->end_controls_section();
    
        // === STYLE TAB ===
    
        // Title Style
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __('Title', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_general_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout h3#order_review_heading, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .box-form .woocommerce-billing-fields h3, {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce-additional-fields h3',
            ]
        );

        $this->add_control(
            'title_general_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout h3#order_review_heading, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .box-form .woocommerce-billing-fields h3, {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce-additional-fields h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_general_bg_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout h3#order_review_heading, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .box-form .woocommerce-billing-fields h3, {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce-additional-fields h3' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_general_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout h3#order_review_heading, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .box-form .woocommerce-billing-fields h3, {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce-additional-fields h3' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_general_padding',
            [
                'label' => __('Padding', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout h3#order_review_heading, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .box-form .woocommerce-billing-fields h3, {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce-additional-fields h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_general_spacing',
            [
                'label' => __('Spacing', 'solace-extra'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
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
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout h3#order_review_heading, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .box-form .woocommerce-billing-fields h3, {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce-additional-fields h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'title_general_border',
                'label' => __('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout h3#order_review_heading, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .box-form .woocommerce-billing-fields h3, {{WRAPPER}} .solace-extra-box-woocommerce-checkout .woocommerce-additional-fields h3',
            ]
        );

        $this->end_controls_section();
    
        // Labels Style
        $this->start_controls_section(
            'section_label_style',
            [
                'label' => __('Labels', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
    
        $this->add_control(
            'label_color',
            [
                'label'     => __('Color', 'solace-extra'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce form .form-row label' => 'color: {{VALUE}};',
                ],
            ]
        );
    
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .woocommerce form .form-row label',
            ]
        );
    
        $this->end_controls_section();
    
        // Billing Details Style
        $this->start_controls_section(
            'section_form_fields_style',
            [
                'label' => __('Billing Details', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Billing Details Background Color
        $this->add_control(
            'billing_details_bg_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-billing-fields' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce-billing-fields__field-wrapper' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce-billing-fields h3' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Billing Details Padding
        $this->add_responsive_control(
            'billing_details_padding',
            [
                'label' => __('Padding', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-billing-fields, {{WRAPPER}} .woocommerce-billing-fields h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .woocommerce-billing-fields__field-wrapper, {{WRAPPER}} .woocommerce-billing-fields h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Billing Details Margin
        $this->add_responsive_control(
            'billing_details_margin',
            [
                'label' => __('Margin', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-billing-fields, {{WRAPPER}} .woocommerce-billing-fields h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .woocommerce-billing-fields__field-wrapper, {{WRAPPER}} .woocommerce-billing-fields h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Billing Details Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'billing_details_border',
                'label' => __('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-billing-fields',
            ]
        );

        // Billing Details Border Radius
        $this->add_responsive_control(
            'billing_details_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-billing-fields' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .woocommerce-billing-fields__field-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .woocommerce-billing-fields h3' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Input Fields
        $this->start_controls_section(
            'section_input_fields_style',
            [
                'label' => __('Input Fields', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
    
        $this->add_control(
            'input_text_color',
            [
                'label'     => __('Text Color', 'solace-extra'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce form .form-row input, {{WRAPPER}} .woocommerce form .form-row textarea, {{WRAPPER}} .woocommerce form .form-row select, {{WRAPPER}} .woocommerce .select2-selection__rendered' => 'color: {{VALUE}};',
                ],
            ]
        );
    
        $this->add_control(
            'input_bg_color',
            [
                'label'     => __('Background Color', 'solace-extra'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce form .form-row input, {{WRAPPER}} .woocommerce form .form-row textarea, {{WRAPPER}} .woocommerce form .form-row select' => 'background-color: {{VALUE}};',
                ],
            ]
        );
    
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'input_border',
                'selector' => '{{WRAPPER}} .woocommerce form .form-row input, {{WRAPPER}} .woocommerce form .form-row textarea, {{WRAPPER}} .woocommerce form .form-row select',
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label'      => __('Border Radius', 'solace-extra'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce form .form-row input, {{WRAPPER}} .woocommerce form .form-row textarea, {{WRAPPER}} .woocommerce form .form-row select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
    
        $this->add_responsive_control(
            'input_padding',
            [
                'label'      => __('Padding', 'solace-extra'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce form .form-row input, {{WRAPPER}} .woocommerce form .form-row textarea, {{WRAPPER}} .woocommerce form .form-row select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_margin',
            [
                'label'      => __('Margin', 'solace-extra'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce form .form-row input, {{WRAPPER}} .woocommerce form .form-row textarea, {{WRAPPER}} .woocommerce form .form-row select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
    
        $this->end_controls_section();
    
        // Buttons
        $this->start_controls_section(
            'section_button_style',
            [
                'label' => __('Button', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_button_style_controls();

        $this->end_controls_section();

        // Coupon Style
        $this->start_controls_section(
            'section_notice_style',
            [
                'label' => __('Coupon', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Coupon Text Color
        $this->add_control(
            'notice_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info,
                    {{WRAPPER}} .woocommerce-message,
                    {{WRAPPER}} .woocommerce-error,
                    {{WRAPPER}} .woocommerce-notice' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Coupon Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'notice_typography',
                'label' => __('Typography', 'solace-extra'),
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selector' => '{{WRAPPER}} .woocommerce-info,
                {{WRAPPER}} .woocommerce-message,
                {{WRAPPER}} .woocommerce-error,
                {{WRAPPER}} .woocommerce-notice',
            ]
        );

        // Coupon Padding
        $this->add_responsive_control(
            'notice_padding',
            [
                'label' => __('Padding', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info,
                    {{WRAPPER}} .woocommerce-message,
                    {{WRAPPER}} .woocommerce-error,
                    {{WRAPPER}} .woocommerce-notice' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Coupon Margin
        $this->add_responsive_control(
            'notice_margin',
            [
                'label' => __('Margin', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info,
                    {{WRAPPER}} .woocommerce-message,
                    {{WRAPPER}} .woocommerce-error,
                    {{WRAPPER}} .woocommerce-notice' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Coupon Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'notice_border',
                'label' => __('Border', 'solace-extra'),
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selector' => '{{WRAPPER}} .woocommerce-info,
                {{WRAPPER}} .woocommerce-message,
                {{WRAPPER}} .woocommerce-error,
                {{WRAPPER}} .woocommerce-notice',
            ]
        );

        // Coupon Border Radius
        $this->add_responsive_control(
            'notice_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info,
                    {{WRAPPER}} .woocommerce-message,
                    {{WRAPPER}} .woocommerce-error,
                    {{WRAPPER}} .woocommerce-notice' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Coupon Background Color
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'notice_background_color',
                'types' => ['classic', 'gradient'],
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selector' => '{{WRAPPER}} .woocommerce-info,
                {{WRAPPER}} .woocommerce-message,
                {{WRAPPER}} .woocommerce-error,
                {{WRAPPER}} .woocommerce-notice',
            ]
        );        

        // Coupon Links
        $this->add_control(
            'notice_links_heading',
            [
                'label' => __('Coupon Links', 'solace-extra'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
            ]
        );

        // Coupon Link Color
        $this->add_control(
            'notice_link_color',
            [
                'label' => __('Link Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info a,
                    {{WRAPPER}} .woocommerce-message a,
                    {{WRAPPER}} .woocommerce-error a,
                    {{WRAPPER}} .woocommerce-notice a' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Coupon Link Background Color
        $this->add_control(
            'notice_link_bg_color',
            [
                'label' => __('Link Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info a,
                    {{WRAPPER}} .woocommerce-message a,
                    {{WRAPPER}} .woocommerce-error a,
                    {{WRAPPER}} .woocommerce-notice a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Coupon Link Hover Color
        $this->add_control(
            'notice_link_hover_color',
            [
                'label' => __('Link Hover Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info a:hover,
                    {{WRAPPER}} .woocommerce-message a:hover,
                    {{WRAPPER}} .woocommerce-error a:hover,
                    {{WRAPPER}} .woocommerce-notice a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Coupon Link Hover Background Color
        $this->add_control(
            'notice_link_hover_bg_color',
            [
                'label' => __('Link Hover Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info a:hover,
                    {{WRAPPER}} .woocommerce-message a:hover,
                    {{WRAPPER}} .woocommerce-error a:hover,
                    {{WRAPPER}} .woocommerce-notice a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Coupon Link Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'notice_link_typography',
                'label' => __('Link Typography', 'solace-extra'),
                'condition' => [
                    'show_coupon_notice' => 'yes',
                ],
                'selector' => '{{WRAPPER}} .woocommerce-info a,
                {{WRAPPER}} .woocommerce-message a,
                {{WRAPPER}} .woocommerce-error a,
                {{WRAPPER}} .woocommerce-notice a',
            ]
        );

        $this->end_controls_section();

        // Additional Information Style
        $this->start_controls_section(
            'section_additional_information_style',
            [
                'label' => __('Additional Information', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Additional Information Padding
        $this->add_responsive_control(
            'additional_information_padding',
            [
                'label' => __('Padding', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'condition' => [
                    'show_additional_information' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-1 .woocommerce .woocommerce-additional-fields,
                    {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .woocommerce .box-shipping,
                    {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 form.checkout .box-shipping' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Additional Information Margin
        $this->add_responsive_control(
            'additional_information_margin',
            [
                'label' => __('Margin', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'condition' => [
                    'show_additional_information' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-1 .woocommerce .woocommerce-additional-fields,
                    {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .woocommerce .box-shipping,
                    {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 form.checkout .box-shipping' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Additional Information Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'additional_information_border',
                'label' => __('Border', 'solace-extra'),
                'condition' => [
                    'show_additional_information' => 'yes',
                ],
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-1 .woocommerce .woocommerce-additional-fields,
                    {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .woocommerce .box-shipping,
                    {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 form.checkout .box-shipping',
            ]
        );

        // Additional Information Border Radius
        $this->add_responsive_control(
            'additional_information_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'condition' => [
                    'show_additional_information' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-1 .woocommerce .woocommerce-additional-fields,
                    {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .woocommerce .box-shipping,
                    {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 form.checkout .box-shipping' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Additional Information Background Color
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'additional_information_bg_color',
                'types' => ['classic', 'gradient'],
                'condition' => [
                    'show_additional_information' => 'yes',
                ],
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-1 .woocommerce .woocommerce-additional-fields,
                {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .woocommerce .box-shipping,
                {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 form.checkout .box-shipping',
            ]
        );         

        $this->end_controls_section();

        // Orders Style
        $this->start_controls_section(
            'section_orders_style',
            [
                'label' => __('Orders', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Orders Padding
        $this->add_responsive_control(
            'orders_padding',
            [
                'label' => __('Padding', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-1 form.checkout .box-order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .box-order-and-shipping' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 .box-order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Orders Margin
        $this->add_responsive_control(
            'orders_margin',
            [
                'label' => __('Margin', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-1 form.checkout .box-order' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .box-order-and-shipping' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 .box-order' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Orders Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'orders_border',
                'label' => __('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-1 form.checkout .box-order, {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .box-order-and-shipping, {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 .box-order',
            ]
        );

        // Orders Border Radius
        $this->add_responsive_control(
            'orders_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-1 form.checkout .box-order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .box-order-and-shipping' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 .box-order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Orders Background Color
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'orders_bg_color',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-1 form.checkout .box-order, {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-2 .box-order-and-shipping, {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 .box-order',
            ]
        );        

        // Order Heading Controls
        $this->add_control(
            'orders_table_heading_heading',
            [
                'label' => __('Order Heading', 'solace-extra'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'orders_table_heading_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table thead tr th, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.order-total th, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.order-total strong',
            ]
        );

        $this->add_control(
            'orders_table_heading_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table thead tr th, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.order-total th, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.order-total strong' => 'color: {{VALUE}};',
                ],
            ]
        );        

        // Order Table Elements Controls
        $this->add_control(
            'orders_table_elements_heading',
            [
                'label' => __('Orders', 'solace-extra'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'orders_table_elements_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody tr td.product-name, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody tr td.product-total, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.cart-subtotal td, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.shipping td, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.tax-rate td, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.cart-subtotal th, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.shipping th, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.tax-rate th',
            ]
        );

        $this->add_control(
            'orders_table_elements_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody tr td.product-name, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody tr td.product-total, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.cart-subtotal td, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.shipping td, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.tax-rate td, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.cart-subtotal th, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.shipping th, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot tr.tax-rate th' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Payment Controls
        $this->add_control(
            'orders_payment_heading',
            [
                'label' => __('Payment', 'solace-extra'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'orders_payment_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-checkout #payment li.wc_payment_method label, {{WRAPPER}} .solace-extra-box-woocommerce-checkout #payment li.wc_payment_method p',
            ]
        );

        $this->add_control(
            'orders_payment_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout #payment li.wc_payment_method label, {{WRAPPER}} .solace-extra-box-woocommerce-checkout #payment li.wc_payment_method p' => 'color: {{VALUE}};',
                ],
            ]
        );        

        // Privacy Policy Controls
        $this->add_control(
            'orders_privacy_policy_heading',
            [
                'label' => __('Privacy Policy', 'solace-extra'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'orders_privacy_policy_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .woocommerce-privacy-policy-text',
            ]
        );

        $this->add_control(
            'orders_privacy_policy_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .woocommerce-privacy-policy-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'orders_privacy_policy_link_color',
            [
                'label' => __('Link Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout .woocommerce-privacy-policy-text a' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Divider Controls
        $this->add_control(
            'checkout_orders_divider_heading',
            [
                'label' => __('Divider', 'solace-extra'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'checkout_orders_divider',
            [
                'label' => __('Divider', 'solace-extra'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'solace-extra'),
                'label_off' => __('Hide', 'solace-extra'),
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout .box-order h3,
                    {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table thead, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody, {{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot' => 'position: relative;',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table thead::after' => 'content: ""',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody::after' => 'content: ""',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot::after' => 'content: ""',
                ],
            ]
        );

        $this->add_control(
            'checkout_orders_divider_style',
            [
                'label' => __('Style', 'solace-extra'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'solid' => __('Solid', 'solace-extra'),
                    'double' => __('Double', 'solace-extra'),
                    'dotted' => __('Dotted', 'solace-extra'),
                    'dashed' => __('Dashed', 'solace-extra'),
                    'groove' => __('Groove', 'solace-extra'),
                    'ridge' => __('Ridge', 'solace-extra'),
                    'inset' => __('Inset', 'solace-extra'),
                    'outset' => __('Outset', 'solace-extra'),
                ],
                'default' => 'solid',
                'condition' => [
                    'checkout_orders_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table thead::after' => 'border-top-style: {{VALUE}}',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody::after' => 'border-top-style: {{VALUE}}',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot::after' => 'border-top-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'checkout_orders_divider_weight',
            [
                'label' => __('Weight', 'solace-extra'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'condition' => [
                    'checkout_orders_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table thead::after' => 'border-top-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody::after' => 'border-top-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot::after' => 'border-top-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'checkout_orders_divider_width',
            [
                'label' => __('Width', 'solace-extra'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'condition' => [
                    'checkout_orders_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table thead::after' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody::after' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot::after' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'checkout_orders_divider_color',
            [
                'label' => __('Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'default' => '#676767',
                'condition' => [
                    'checkout_orders_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table thead::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot::after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'checkout_orders_divider_alignment',
            [
                'label' => __('Alignment', 'solace-extra'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'solace-extra'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'solace-extra'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'solace-extra'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'condition' => [
                    'checkout_orders_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table thead::after' => '{{VALUE}}',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tbody::after' => '{{VALUE}}',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-checkout form.checkout table.shop_table tfoot::after' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'left' => 'position: absolute; left: 0; bottom: 0;',
                    'center' => 'position: absolute; left: 0; right: 0; bottom: 0; margin: 0 auto;',
                    'right' => 'position: absolute; right: 0; left: unset; bottom: 0;',
                ],
            ]
        );        

        $this->end_controls_section();

    }

    protected function renderx() {
        $settings = $this->get_settings_for_display();
        $layout_class = isset($settings['layout']) ? $settings['layout'] : 'layout-1';        
    
        // Filter heading billing
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Billing details' && !empty($settings['billing_heading'])) {
                    return $settings['billing_heading'];
                }
                if ($text === 'Your order' && !empty($settings['order_review_heading'])) {
                    return $settings['order_review_heading'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter address
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Ship to a different address?' && !empty($settings['ship_different_address_text'])) {
                    return $settings['ship_different_address_text'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter coupon notice text
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Have a coupon?' && !empty($settings['coupon_notice_text'])) {
                    return $settings['coupon_notice_text'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter coupon link text
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Click here to enter your code' && !empty($settings['coupon_link_text'])) {
                    return $settings['coupon_link_text'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter apply coupon button text
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Apply coupon' && !empty($settings['apply_coupon_button_text'])) {
                    return $settings['apply_coupon_button_text'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter additional information heading
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Additional information' && !empty($settings['additional_information_heading'])) {
                    return $settings['additional_information_heading'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter button text
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Place order' && !empty($settings['button_placeholder'])) {
                    return $settings['button_placeholder'];
                }
            }
            return $translated_text;
        }, 10, 3);       

        // Open a wrapper <div> before the order review heading section
        add_action( 'woocommerce_checkout_before_order_review_heading', function() {
            echo '<div class="box-order">';
        });

        // Close the wrapper <div> after the order review heading section
        add_action( 'woocommerce_checkout_after_order_review_heading', function() {
            echo '</div>';
        });

        // Open a wrapper <div> before the customer details section (billing & shipping forms)
        add_action( 'woocommerce_checkout_before_customer_details', function() {
            echo '<div class="box-form">';
        });

        // Close the wrapper <div> after the customer details section
        add_action( 'woocommerce_checkout_after_customer_details', function() {
            echo '</div>';
        });

        if ( 'layout-2' === $settings['layout'] ) {
        
            // Open wrapper for order and shipping section before order review heading
            add_action( 'woocommerce_checkout_before_order_review_heading', function() {
                echo '<div class="box-order-and-shipping">';
            });

            // Close wrapper for order and shipping section after order review
            add_action( 'woocommerce_checkout_after_order_review', function() {
                echo '</div>';
            });            
        
            // Remove the default shipping form from the checkout
            add_action( 'woocommerce_checkout_init', function( $checkout ) {
                remove_action( 'woocommerce_checkout_shipping', array( $checkout, 'checkout_form_shipping' ) );
            }, 20 );
        
            // Display the shipping form manually after the order review
            add_action( 'woocommerce_checkout_after_order_review', function() {
                if ( class_exists( 'WC_Checkout' ) ) {
                    $checkout = WC()->checkout();
        
                    // Ensure that the checkout instance has the shipping form method
                    if ( method_exists( $checkout, 'checkout_form_shipping' ) ) {
                        echo '<div class="box-shipping">';
                        $checkout->checkout_form_shipping();
                        echo '</div>';
                    }
                }
            });
        
        } else if ( 'layout-3' === $settings['layout'] ) {
            // Remove the default WooCommerce shipping form from its original location
            add_action( 'woocommerce_checkout_init', function( $checkout ) {
                remove_action( 'woocommerce_checkout_shipping', array( $checkout, 'checkout_form_shipping' ) );
            }, 20 );
        
            // Manually display the shipping form after the checkout form
            add_action( 'woocommerce_after_checkout_form', function() {
                if ( class_exists( 'WC_Checkout' ) ) {
                    $checkout = WC()->checkout();
        
                     // Ensure the shipping form method exists
                    if ( method_exists( $checkout, 'checkout_form_shipping' ) ) {
                        echo '<div class="box-shipping">';
                        $checkout->checkout_form_shipping();
                        echo '</div>';
                    }
                }
            });
       
        }
    
        // Add custom CSS for layout-3 padding adjustment when additional information is hidden
        if (isset($settings['layout']) && $settings['layout'] === 'layout-3' && 
            isset($settings['show_additional_information']) && $settings['show_additional_information'] !== 'yes') {
            echo '<style>
            {{WRAPPER}} .solace-extra-box-woocommerce-checkout.layout-3 form.checkout {
                padding-right: 0;
            }
            </style>';
        }
    
        echo '<div class="solace-extra-box-woocommerce-checkout ' . esc_attr($layout_class) . '">';
        echo do_shortcode('[woocommerce_checkout]');
        echo '</div>';
    }

    protected function layout_1($settings = []) {
        ob_start();

        if ( ! function_exists( 'WC' ) || ! WC()->checkout ) {
            echo '<p>' . esc_html__( 'WooCommerce not active or checkout not ready.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }



        $checkout = WC()->checkout;
        ?>
        <div class="solace-extra-box-woocommerce-checkout layout-1">
            <div class="woocommerce">

                <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

                <form name="checkout" method="post" class="checkout woocommerce-checkout"
                    action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

                    <div class="box-form">
                        <div class="col2-set" id="customer_details">
                            <div class="col-1">
                                <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            </div>

                            <div class="col-2">
                                <?php do_action( 'woocommerce_checkout_shipping' ); ?>

                                <div class="woocommerce-additional-fields">
                                    <?php do_action( 'woocommerce_checkout_before_order_notes', $checkout ); ?>
                                    <?php do_action( 'woocommerce_checkout_after_order_notes', $checkout ); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-order">
                        <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'solace-extra' ); ?></h3>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                        </div>
                    </div>

                </form>

                <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function layout_2($settings = []) {
        ob_start();

        if ( ! function_exists( 'WC' ) || ! WC()->checkout ) {
            echo '<p>' . esc_html__( 'WooCommerce not active or checkout not ready.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }



        $checkout = WC()->checkout;
        ?>
        <div class="solace-extra-box-woocommerce-checkout layout-2">
            <div class="woocommerce">
                <?php do_action( 'woocommerce_before_checkout_form', WC()->checkout() ); ?>

                <form name="checkout" method="post" class="checkout woocommerce-checkout"
                    action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
                    enctype="multipart/form-data">

                    <div class="box-form">
                        <div class="col2-set" id="customer_details">
                            <div class="col-1">
                                <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            </div>
                            <div class="col-2">
                               
                            </div>
                        </div>
                    </div>

                    <div class="box-order">
                        <div class="box-order-and-shipping">
                            <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'solace-extra' ); ?></h3>

                            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                            <div id="order_review" class="woocommerce-checkout-review-order">
                                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                            </div>

                            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                        </div>
                        <div class="box-shipping">
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                            
                        </div>
                        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                    </div>
                    
                </form>

                <?php do_action( 'woocommerce_after_checkout_form', WC()->checkout() ); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function layout_3($settings = []) {
        ob_start();

        if ( ! function_exists( 'WC' ) || ! WC()->checkout ) {
            echo '<p>' . esc_html__( 'WooCommerce not active or checkout not ready.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }



        $checkout = WC()->checkout;
        ?>
        <div class="solace-extra-box-woocommerce-checkout layout-3">
            <div class="woocommerce">
                <?php do_action( 'woocommerce_before_checkout_form', WC()->checkout() ); ?>

                <form name="checkout" method="post" class="checkout woocommerce-checkout"
                    action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
                    enctype="multipart/form-data">

                    <div class="box-form">
                        <div id="customer_details" class="col2-set">
                            <div class="col-1">
                                <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            </div>
                            <div class="col-2"></div>
                        </div>
                    </div>

                    <div class="box-order">
                        <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'solace-extra' ); ?></h3>

                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                        <div class="box-shipping">
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>
                    </div>
                </form>

                <?php do_action( 'woocommerce_after_checkout_form', WC()->checkout() ); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function add_render_hooks( $settings = []) {
        // Filter heading billing
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Billing details' && !empty($settings['billing_heading'])) {
                    return $settings['billing_heading'];
                }
                if ($text === 'Your order' && !empty($settings['order_review_heading'])) {
                    return $settings['order_review_heading'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter address
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Ship to a different address?' && !empty($settings['ship_different_address_text'])) {
                    return $settings['ship_different_address_text'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter coupon notice text
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Have a coupon?' && !empty($settings['coupon_notice_text'])) {
                    return $settings['coupon_notice_text'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter coupon link text
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Click here to enter your code' && !empty($settings['coupon_link_text'])) {
                    return $settings['coupon_link_text'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter apply coupon button text
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Apply coupon' && !empty($settings['apply_coupon_button_text'])) {
                    return $settings['apply_coupon_button_text'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter additional information heading
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Additional information' && !empty($settings['additional_information_heading'])) {
                    return $settings['additional_information_heading'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Filter button text
        add_filter('gettext', function($translated_text, $text, $domain) use ($settings) {
            if ($domain === 'woocommerce') {
                if ($text === 'Place order' && !empty($settings['button_placeholder'])) {
                    return $settings['button_placeholder'];
                }
            }
            return $translated_text;
        }, 10, 3);

        // Ensure Place Order button classes are rewritten
        add_filter('woocommerce_order_button_html', function($button_html) {
            // Only process if there is a class attribute
            if (strpos($button_html, 'class=') === false) {
                return $button_html;
            }
            $button_html = preg_replace_callback(
                '/class="([^"]*)"/i',
                function ($matches) {
                    $original = $matches[1];
                    $tokens = preg_split('/\s+/', trim($original));
                    if (!is_array($tokens)) {
                        $tokens = [$original];
                    }
                    $normalized = [];
                    foreach ($tokens as $cls) {
                        if ($cls === 'button') {
                            // Replace Woo button token with our class
                            $normalized[] = 'solace-extra-button';
                            continue;
                        }
                        $normalized[] = $cls;
                    }
                    if (!in_array('elementor-button', $normalized, true)) {
                        $normalized[] = 'elementor-button';
                    }
                    $normalized = array_values(array_unique($normalized));
                    return 'class="' . trim(implode(' ', $normalized)) . '"';
                },
                $button_html
            );
            return $button_html;
        }, 10);
    }

    private function get_checkout_field_selectorsOK( $widget_id, $is_focus = false ) {
        $prefix = '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-checkout';
        
        $base_selectors = [
            $prefix . ' form .form-row input.input-text',
            $prefix . ' select.country_select',
            $prefix . ' select.state_select',
            $prefix . ' form .form-row textarea.input-text',
            $prefix . ' .select2-selection',
            $prefix . ' .select2-selection__rendered',
        ];

        if ( $is_focus ) {
            $base_selectors = array_map( function( $selector ) {
                return $selector . ':focus';
            }, $base_selectors );
        }

        $final_selectors = implode( ',', $base_selectors );

        return $final_selectors;
    }

    protected function renderOK() {
        $settings = $this->get_settings_for_display();
        $kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();

		if ( ! $kit_id ) {
            return;
        }

        $kit_settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
        if ( ! is_array( $kit_settings ) ) {
            return;
        }

        $text_color       = $kit_settings['form_field_text_color'] ?? '';
        $bg_color         = $kit_settings['form_field_background_color'] ?? '';
        $text_color_focus = $kit_settings['form_field_focus_text_color'] ?? '';
        $bg_color_focus   = $kit_settings['form_field_focus_background_color'] ?? '';

        if ( ! $text_color && ! $bg_color && ! $text_color_focus && ! $bg_color_focus ) {
            return;
        }

        static $style_printed = [];
        $widget_id = $this->get_id();

        if ( isset( $style_printed[ $widget_id ] ) ) {
            return;
        }

        echo '<style>';

        if ( $text_color || $bg_color ) {
            $normal_selectors = $this->get_checkout_field_selectors( $widget_id, false );
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS selectors, safe
            echo $normal_selectors . ' {';
            if ( $text_color ) {
                echo 'color:' . esc_attr( $text_color ) . ';';
            }
            if ( $bg_color ) {
                echo 'background-color:' . esc_attr( $bg_color ) . ';';
            }
            echo '}';
        }

        if ( $text_color_focus || $bg_color_focus ) {
            $focus_selectors = $this->get_checkout_field_selectors( $widget_id, true );
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS selectors, safe
            echo $focus_selectors . ' {';
            if ( $text_color_focus ) {
                echo 'color:' . esc_attr( $text_color_focus ) . ';';
            }
            if ( $bg_color_focus ) {
                echo 'background-color:' . esc_attr( $bg_color_focus ) . ';';
            }
            echo '}';
        }

        echo '</style>';

        $style_printed[ $widget_id ] = true;





        $layout_class = isset($settings['layout']) ? $settings['layout'] : 'layout-1';        
        $this->add_render_hooks($settings);

        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';

        if ( function_exists( 'wc_load_cart' ) && is_null( WC()->cart ) ) {
            wc_load_cart();
        }
        if ( ! function_exists( 'WC' ) || ! WC()->checkout ) {
            echo '<p>' . esc_html__( 'WooCommerce is not active.', 'solace-extra' ) . '</p>';
            return;
        }

		echo '<div id="solace-checkout-' . esc_attr( $this->get_id() ) . '" class="solace-extra-box-woocommerce-checkout ' . esc_attr($layout_class) . '">';  
        
        $html = '';
        switch ($layout_class) {
            case 'layout-1':
                $html = $this->layout_1($settings);
                break;
            case 'layout-2':
                $html = $this->layout_2($settings);
                break;
            case 'layout-3':
                $html = $this->layout_3($settings);
                break;
        }

        // Post-process: adjust button classes for all buttons
        if ($html) {
            // Handle buttons with specific IDs/names (Place Order, Apply Coupon, etc.)
            $html = preg_replace_callback(
                '/<button\b(?=[^>]*\b(?:id="place_order"|name="woocommerce_checkout_place_order"|name="apply_coupon")\b)([^>]*)\bclass="([^"]*)"([^>]*)>/i',
                function ($matches) use ($hover_class) {
                    $before = $matches[1];
                    $class  = $matches[2];
                    $after  = $matches[3];
                    $tokens = preg_split('/\s+/', trim($class));
                    if (!is_array($tokens)) {
                        $tokens = [$class];
                    }
                    $normalized = [];
                    foreach ($tokens as $cls) {
                        if ($cls === 'button') {
                            $normalized[] = 'solace-extra-button' . $hover_class;
                            continue;
                        }
                        $normalized[] = $cls;
                    }
                    if (!in_array('elementor-button', $normalized, true)) {
                        $normalized[] = 'elementor-button';
                    }
                    $normalized = array_values(array_unique($normalized));
                    return '<button' . $before . 'class="' . implode(' ', $normalized) . '"' . $after . '>';
                },
                $html
            );
            
            // Handle all other buttons with class="button"
            $html = preg_replace_callback(
                '/<button\b([^>]*)\bclass="([^"]*button[^"]*)"([^>]*)>/i',
                function ($matches) use ($hover_class) {
                    $before = $matches[1];
                    $class  = $matches[2];
                    $after  = $matches[3];
                    $tokens = preg_split('/\s+/', trim($class));
                    if (!is_array($tokens)) {
                        $tokens = [$class];
                    }
                    $normalized = [];
                    foreach ($tokens as $cls) {
                        if ($cls === 'button') {
                            $normalized[] = 'solace-extra-button' . $hover_class;
                            continue;
                        }
                        $normalized[] = $cls;
                    }
                    if (!in_array('elementor-button', $normalized, true)) {
                        $normalized[] = 'elementor-button';
                    }
                    $normalized = array_values(array_unique($normalized));
                    return '<button' . $before . 'class="' . $hover_class . ' '. implode(' ', $normalized) . '"' . $after . '>';
                },
                $html
            );

            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $html;
        }
        echo '</div>';

        // Add JavaScript to ensure body class is added
        echo '<script>
        (function() {
            function addBodyClass() {
                var body = document.body;
                if (body && !body.classList.contains("has-solace-checkout-widget")) {
                    body.classList.add("has-solace-checkout-widget");
                }
            }
            
            // Add class immediately
            addBodyClass();
            
            // Also add on DOM ready
            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", addBodyClass);
            }
            
            // For Elementor compatibility
            if (typeof jQuery !== "undefined") {
                jQuery(document).ready(addBodyClass);
            }
        })();
        </script>';        
    }

    private function get_checkout_field_selectors( $widget_id, $is_focus = false ) {
        $prefix = '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-checkout';
        
        $selectors = [
            $prefix . ' form .form-row input.input-text',
            $prefix . ' select.country_select',
            $prefix . ' select.state_select',
            $prefix . ' form .form-row textarea.input-text',
            $prefix . ' .select2-selection',
            $prefix . ' .country_to_state',
            // $prefix . ' .select2-selection__rendered',
            $prefix . ' .select2-container--default .select2-selection--single',
        ];

        if ( $is_focus ) {
            $selectors = array_map( function( $selector ) {
                return $selector . ':focus';
            }, $selectors );
            
            $selectors[] = $prefix . ' .select2-selection';
            $selectors[] = $prefix . ' .country_to_state';
            $selectors[] = $prefix . ' .select2-container--focus .select2-selection';
            $selectors[] = $prefix . ' .select2-container--open .select2-selection';

            $selectors[] = $prefix . ' .select2-container--default.select2-container--focus .select2-selection--single';
            $selectors[] = $prefix . ' .select2-container--default.select2-container--open .select2-selection--single';
        }

        return implode( ',', $selectors );
    }

    private function generate_typography_style( $settings, $prefix ) {
        $css = '';
        
        // Font Family
        $family = $settings[ $prefix . 'typography_font_family' ] ?? '';
        if ( $family ) {
            $css .= "font-family: \"{$family}\", Sans-serif;";
        }

        // Font Size
        $size = $settings[ $prefix . 'typography_font_size' ] ?? '';
        if ( is_array( $size ) && ! empty( $size['size'] ) ) {
            $css .= "font-size: {$size['size']}{$size['unit']};";
        }

        // Font Weight
        $weight = $settings[ $prefix . 'typography_font_weight' ] ?? '';
        if ( $weight ) {
            $css .= "font-weight: {$weight};";
        }

        // Transform (Uppercase, Lowercase, dll)
        $transform = $settings[ $prefix . 'typography_text_transform' ] ?? '';
        if ( $transform ) {
            $css .= "text-transform: {$transform};";
        }

        // Font Style (Italic, dll)
        $font_style = $settings[ $prefix . 'typography_font_style' ] ?? '';
        if ( $font_style ) {
            $css .= "font-style: {$font_style};";
        }

        // Line Height
        $lh = $settings[ $prefix . 'typography_line_height' ] ?? '';
        if ( is_array( $lh ) && ! empty( $lh['size'] ) ) {
            $css .= "line-height: {$lh['size']}{$lh['unit']};";
        }

        // Letter Spacing
        $ls = $settings[ $prefix . 'typography_letter_spacing' ] ?? '';
        if ( is_array( $ls ) && ! empty( $ls['size'] ) ) {
            $css .= "letter-spacing: {$ls['size']}{$ls['unit']};";
        }

        return $css;
    }

    private function generate_field_style( $settings, $state = 'normal' ) {
        $css = '';
        $prefix = ( $state === 'focus' ) ? 'form_field_focus_' : 'form_field_';

        $text_color = $settings[ $prefix . 'text_color' ] ?? '';
        $bg_color   = $settings[ $prefix . 'background_color' ] ?? '';
        if ( $text_color ) $css .= "color: {$text_color};";
        if ( $bg_color )   $css .= "background-color: {$bg_color};";

        if ( $state === 'normal' ) {
            $css .= $this->generate_typography_style( $settings, 'form_field_' );
        }

        $border_type = $settings[ $prefix . 'border_border' ] ?? '';
        if ( $border_type ) {
            $css .= "border-style: {$border_type};";
            $border_width = $settings[ $prefix . 'border_width' ] ?? '';
            if ( is_array( $border_width ) && ! empty( $border_width['top'] ) ) {
                $unit = $border_width['unit'] ?? 'px';
                $css .= "border-width: {$border_width['top']}{$unit} {$border_width['right']}{$unit} {$border_width['bottom']}{$unit} {$border_width['left']}{$unit};";
            }
            $border_color = $settings[ $prefix . 'border_color' ] ?? '';
            if ( $border_color ) $css .= "border-color: {$border_color};";
        }

        $radius = $settings[ $prefix . 'border_radius' ] ?? '';
        if ( is_array( $radius ) && ! empty( $radius['top'] ) ) {
            $unit = $radius['unit'] ?? 'px';
            $css .= "border-radius: {$radius['top']}{$unit} {$radius['right']}{$unit} {$radius['bottom']}{$unit} {$radius['left']}{$unit};";
        }

        $padding = $settings[ $prefix . 'padding' ] ?? '';
        if ( is_array( $padding ) && ! empty( $padding['top'] ) ) {
            $unit = $padding['unit'] ?? 'px';
            $css .= "padding: {$padding['top']}{$unit} {$padding['right']}{$unit} {$padding['bottom']}{$unit} {$padding['left']}{$unit};";
        }

        $shadow = $settings[ $prefix . 'box_shadow_box_shadow' ] ?? '';
        if ( is_array( $shadow ) && ! empty( $shadow['color'] ) ) {
            $hor  = $shadow['horizontal'] ?? 0;
            $ver  = $shadow['vertical'] ?? 0;
            $blur = $shadow['blur'] ?? 0;
            $spr  = $shadow['spread'] ?? 0;
            $col  = $shadow['color'];
            $ins  = ( isset( $shadow['outline'] ) && $shadow['outline'] === 'inset' ) ? 'inset' : '';
            
            $css .= "box-shadow: {$hor}px {$ver}px {$blur}px {$spr}px {$col} {$ins};";
            $css .= "outline: none;"; 
        }

        if ( $state === 'focus' ) {
            $transition = $settings['form_field_focus_transition_duration'] ?? '';
            if ( is_array( $transition ) ) {
                $val  = $transition['size'] ?? '';
                $unit = $transition['unit'] ?? 's';
                if ( $val !== '' ) {
                    $css .= "transition: all {$val}{$unit} ease-in-out;";
                }
            } elseif ( $transition !== '' ) {
                $css .= "transition: all {$transition}s ease-in-out;";
            }
        }

        return $css;
    }

    private function generate_label_style( $settings ) {
        $css = '';
        
        $label_color = $settings['form_label_color'] ?? '';
        if ( $label_color ) {
            $css .= "color: {$label_color};";
        }

        $css .= $this->generate_typography_style( $settings, 'form_label_' );

        return $css;
    }

    private function get_label_selectors( $widget_id ) {
        $prefix = '.elementor-element-' . esc_attr( $widget_id );
        
        return "
            $prefix .solace-extra-box-woocommerce-checkout form .form-row label            
        ";
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();

		if ( ! $kit_id ) return;

        $kit_settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
        if ( ! is_array( $kit_settings ) ) return;

        static $style_printed = [];
        $widget_id = $this->get_id();
        if ( isset( $style_printed[ $widget_id ] ) ) return;

        $normal_styles = $this->generate_field_style( $kit_settings, 'normal' );
        $focus_styles  = $this->generate_field_style( $kit_settings, 'focus' );

        if ( empty( $normal_styles ) && empty( $focus_styles ) ) return;

        echo '<style>';

        if ( ! empty( $label_styles ) ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS output, safe
            echo $this->get_label_selectors( $widget_id ) . " { {$label_styles} }";
        }

        if ( ! empty( $normal_styles ) ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS output, safe
            echo $this->get_checkout_field_selectors( $widget_id, false ) . " { {$normal_styles} }";
        }

        if ( ! empty( $focus_styles ) ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS output, safe
            echo $this->get_checkout_field_selectors( $widget_id, true ) . " { {$focus_styles} }";
        }

        echo '</style>';

        $style_printed[ $widget_id ] = true;





        $layout_class = isset($settings['layout']) ? $settings['layout'] : 'layout-1';        
        $this->add_render_hooks($settings);

        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';

        if ( function_exists( 'wc_load_cart' ) && is_null( WC()->cart ) ) {
            wc_load_cart();
        }
        if ( ! function_exists( 'WC' ) || ! WC()->checkout ) {
            echo '<p>' . esc_html__( 'WooCommerce is not active.', 'solace-extra' ) . '</p>';
            return;
        }

		echo '<div id="solace-checkout-' . esc_attr( $this->get_id() ) . '" class="solace-extra-box-woocommerce-checkout ' . esc_attr($layout_class) . '">';  
        
        $html = '';
        switch ($layout_class) {
            case 'layout-1':
                $html = $this->layout_1($settings);
                break;
            case 'layout-2':
                $html = $this->layout_2($settings);
                break;
            case 'layout-3':
                $html = $this->layout_3($settings);
                break;
        }

        // Post-process: adjust button classes for all buttons
        if ($html) {
            // Handle buttons with specific IDs/names (Place Order, Apply Coupon, etc.)
            $html = preg_replace_callback(
                '/<button\b(?=[^>]*\b(?:id="place_order"|name="woocommerce_checkout_place_order"|name="apply_coupon")\b)([^>]*)\bclass="([^"]*)"([^>]*)>/i',
                function ($matches) use ($hover_class) {
                    $before = $matches[1];
                    $class  = $matches[2];
                    $after  = $matches[3];
                    $tokens = preg_split('/\s+/', trim($class));
                    if (!is_array($tokens)) {
                        $tokens = [$class];
                    }
                    $normalized = [];
                    foreach ($tokens as $cls) {
                        if ($cls === 'button') {
                            $normalized[] = 'solace-extra-button' . $hover_class;
                            continue;
                        }
                        $normalized[] = $cls;
                    }
                    if (!in_array('elementor-button', $normalized, true)) {
                        $normalized[] = 'elementor-button';
                    }
                    $normalized = array_values(array_unique($normalized));
                    return '<button' . $before . 'class="' . implode(' ', $normalized) . '"' . $after . '>';
                },
                $html
            );
            
            // Handle all other buttons with class="button"
            $html = preg_replace_callback(
                '/<button\b([^>]*)\bclass="([^"]*button[^"]*)"([^>]*)>/i',
                function ($matches) use ($hover_class) {
                    $before = $matches[1];
                    $class  = $matches[2];
                    $after  = $matches[3];
                    $tokens = preg_split('/\s+/', trim($class));
                    if (!is_array($tokens)) {
                        $tokens = [$class];
                    }
                    $normalized = [];
                    foreach ($tokens as $cls) {
                        if ($cls === 'button') {
                            $normalized[] = 'solace-extra-button' . $hover_class;
                            continue;
                        }
                        $normalized[] = $cls;
                    }
                    if (!in_array('elementor-button', $normalized, true)) {
                        $normalized[] = 'elementor-button';
                    }
                    $normalized = array_values(array_unique($normalized));
                    return '<button' . $before . 'class="' . $hover_class . ' '. implode(' ', $normalized) . '"' . $after . '>';
                },
                $html
            );

            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $html;
        }
        echo '</div>';

        // Add JavaScript to ensure body class is added
        echo '<script>
        (function() {
            function addBodyClass() {
                var body = document.body;
                if (body && !body.classList.contains("has-solace-checkout-widget")) {
                    body.classList.add("has-solace-checkout-widget");
                }
            }
            
            // Add class immediately
            addBodyClass();
            
            // Also add on DOM ready
            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", addBodyClass);
            }
            
            // For Elementor compatibility
            if (typeof jQuery !== "undefined") {
                jQuery(document).ready(addBodyClass);
            }
        })();
        </script>';        
    }

}
