<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Solace_Extra_WooCommerce_Cart extends Widget_Base {

    public function get_name() {
        return 'solace-extra-woocommerce-cart';
    }

    public function get_title() {
        return __('Cart', 'solace-extra');
    }

    public function get_icon() {
        return 'eicon-cart-medium solace-extra';
    }

    public function get_categories() {
        return ['solace-extra-woocommerce'];
    }

    public function get_keywords() {
        return ['woocommerce', 'cart', 'checkout'];
    }

	public function get_style_depends(): array {
		return [ 'solace-cart' ];
	}

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        
        // Add class via Elementor hooks for better compatibility
        add_action('elementor/frontend/after_render', function($element) {
            if ($element->get_name() === 'solace-extra-woocommerce-cart') {
                add_filter('body_class', function($classes) {
                    if (!in_array('has-solace-cart-widget', $classes)) {
                        $classes[] = 'has-solace-cart-widget';
                    }
                    return $classes;
                });
            }
        });
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
            {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form table.shop_table .button,
            {{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce button,
            {{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce .button,
            {{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce .solace-extra-button,
            {{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce .button:not(header.button):not(footer.button),
            {{WRAPPER}} .solace-extra-box-woocommerce-cart ul.products li.product .button,
            {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .button,
            {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce a.button,
            {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cross-sells a.button,
            {{WRAPPER}} .woocommerce ul.products li.product .button,
            {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cross-sells a.button,
            {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form table.shop_table .button,
            {{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce button,

            body.woocommerce-cart {{WRAPPER}} .woocommerce ul.products li.product .button,      
            body.woocommerce-cart {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .button,
            body.woocommerce-cart {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce a.button,
            body.woocommerce-cart {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cross-sells a.button,
			body.woocommerce-cart {{WRAPPER}} .woocommerce ul.products li.product .button';

		$selector_hover = 'body {{WRAPPER}} div.solace-extra-box-woocommerce-cart div.woocommerce form.woocommerce-cart-form table.shop_table .button:hover,
            body {{WRAPPER}} div.solace-extra-box-woocommerce-cart div.woocommerce button:hover,
            body {{WRAPPER}} div.solace-extra-box-woocommerce-cart div.woocommerce .button:hover,
            body {{WRAPPER}} div.solace-extra-box-woocommerce-cart div.woocommerce .solace-extra-button:hover,
            body {{WRAPPER}} div.solace-extra-box-woocommerce-cart div.woocommerce .button:not(header.button):not(footer.button):hover,
            body {{WRAPPER}} div.solace-extra-box-woocommerce-cart ul.products li.product .button:hover,
            {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .button:hover,
            {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce a.button:hover,
            {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cross-sells a.button:hover,
            {{WRAPPER}} .woocommerce ul.products li.product .button:hover,
            body.woocommerce-cart {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .button:hover,
            body.woocommerce-cart {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce a.button:hover,
            body.woocommerce-cart {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cross-sells a.button:hover,
			body.woocommerce-cart {{WRAPPER}} .woocommerce ul.products li.product .button:hover'; 

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

        /**
         * SECTION: Content
         */
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Layout
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

        /**
         * SECTION: Cart Totals
         */
        $this->start_controls_section(
            'section_cart_totals',
            [
                'label' => __('Cart Totals', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'cart_totals_title',
            [
                'label'       => __('Section Title', 'solace-extra'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __('Cart totals', 'solace-extra'),
                'placeholder' => __('Enter Cart Totals title', 'solace-extra'),
            ]
        );

        $this->end_controls_section();          

        /**
         * SECTION: Cross Sell
         */
        $this->start_controls_section(
            'section_cross_sell_content',
            [
                'label' => __('Cross Sell', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'cross_sell_title',
            [
                'label'       => __('Section Title', 'solace-extra'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __('You may be interested in…', 'solace-extra'),
                'placeholder' => __('Enter your title', 'solace-extra'),
            ]
        );        
    
        $this->add_responsive_control(
            'cross_sell_columns',
            [
                'label'   => __('Columns', 'solace-extra'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'min'     => 1,
                'max'     => 5,
                'tablet_default' => 3,
                'mobile_default' => 2,
            ]
        );

        $this->add_responsive_control(
            'cross_sell_limit',
            [
                'label'   => __('Number of Products', 'solace-extra'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 8,
                'min'     => 1,
                'max'     => 10,
                'tablet_default' => 6,
                'mobile_default' => 4,
                'description' => __('Number of cross-sell products to display', 'solace-extra'),
            ]
        );        
    
        $this->end_controls_section();        

        /**
         * SECTION: Coupon Code
         */
        $this->start_controls_section(
            'section_cart_coupon_code',
            [
                'label' => __('Coupon Code', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_coupon_code',
            [
                'label' => __('Show Coupon Code', 'solace-extra'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'solace-extra'),
                'label_off' => __('Hide', 'solace-extra'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );        

        $this->add_control(
            'cart_coupon_code',
            [
                'label'       => __('Section Title', 'solace-extra'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __('Coupon Code', 'solace-extra'),
                'placeholder' => __('Coupon Code', 'solace-extra'),
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );

        $this->end_controls_section();        

        /**
         * SECTION: Table
         */
        $this->start_controls_section(
            'section_table',
            [
                'label' => __('Table', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Padding
        $this->add_responsive_control(
            'table_padding',
            [
                'label' => esc_html__( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .table-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        // Margin
        $this->add_responsive_control(
            'table_margin',
            [
                'label' => esc_html__( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .table-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'table_border',
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-cart .table-box',
            ]
        );
        
        // Border Radius
        $this->add_responsive_control(
            'table_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .table-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'table_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-cart .table-box',
            ]
        );         

        $this->end_controls_section();

        /**
         * SECTION: Table Heading
         */
        $this->start_controls_section(
            'section_column_heading',
            [
                'label' => __('Table Heading', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-cart-form__contents thead th,
                {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table thead th',
            ]
        );

        // Colors
        $this->add_control(
            'heading_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents thead th,
                    {{WRAPPER}} .woocommerce-cart-form__contents thead th span,
                    {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table thead th,
                    {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table thead th span' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'heading_background',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents thead tr,
                    {{WRAPPER}} .woocommerce-cart-form__contents thead th,
                    {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table thead tr,
                    {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table thead th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Spacing
        $this->add_responsive_control(
            'heading_padding',
            [
                'label' => __('Padding', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents thead th,
                    {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * SECTION: Table Content
         */
        $this->start_controls_section(
            'section_column_content',
            [
                'label' => __('Table Content', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Define common selectors for all table elements
        $table_selectors = [
            'all_cells' => '{{WRAPPER}} .woocommerce-cart-form__contents td:not(.product-remove):not(.actions), {{WRAPPER}} .woocommerce-cart-form__contents td:not(.product-remove):not(.actions) *, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table td:not(.product-remove):not(.actions), {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table td:not(.product-remove):not(.actions) *',
            'all_links' => '{{WRAPPER}} .woocommerce-cart-form__contents a:not(.button), {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table a:not(.button)',
            'all_links_hover' => '{{WRAPPER}} .woocommerce-cart-form__contents a:not(.button):hover, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table a:not(.button):hover',
            'all_inputs' => '{{WRAPPER}} .woocommerce-cart-form__contents input, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table input'
        ];

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'table_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => $table_selectors['all_cells'] . ', ' . $table_selectors['all_links'],
            ]
        );

        // Colors
        $this->add_control(
            'table_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    $table_selectors['all_cells'] . ', ' . $table_selectors['all_links'] => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'table_text_hover_color',
            [
                'label' => __('Text Hover Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    $table_selectors['all_cells'] . ':hover, ' . $table_selectors['all_links_hover'] . ', {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table td .woocommerce-Price-amount.amount:hover .woocommerce-Price-currencySymbol' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        // Background
        $this->add_control(
            'content_background',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table.cart.woocommerce-cart-form__contents tbody tr' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Input Fields
        $this->add_control(
            'table_input_heading',
            [
                'label' => __('Input Fields', 'solace-extra'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'table_input_background',
            [
                'label' => __('Input Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    $table_selectors['all_inputs'] => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'table_input_border',
                'label' => __('Input Border', 'solace-extra'),
                'selector' => $table_selectors['all_inputs'] . ', {{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce-cart-form table.shop_table.cart tr .quantity input',
            ]
        );

        $this->add_responsive_control(
            'table_input_border_radius',
            [
                'label' => __('Input Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    $table_selectors['all_inputs'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Divider Controls
        $this->add_control(
            'cart_item_divider_heading',
            [
                'label' => __('Divider', 'solace-extra'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cart_item_divider',
            [
                'label' => __('Divider', 'solace-extra'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'solace-extra'),
                'label_off' => __('Hide', 'solace-extra'),
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce-cart-form table.shop_table.cart tr:not(:last-child):after' => 'content: ""',
                ],
            ]
        );

        $this->add_control(
            'cart_item_divider_style',
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
                    'cart_item_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce-cart-form table.shop_table.cart tr:not(:last-child):after' => 'border-top-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'cart_item_divider_weight',
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
                    'cart_item_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce-cart-form table.shop_table.cart tr:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'cart_item_divider_width',
            [
                'label' => __('Width', 'solace-extra'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'condition' => [
                    'cart_item_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce-cart-form table.shop_table.cart tr:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'cart_item_divider_color',
            [
                'label' => __('Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'default' => '#676767',
                'condition' => [
                    'cart_item_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce-cart-form table.shop_table.cart tr:not(:last-child):after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'cart_item_divider_alignment',
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
                    'cart_item_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .woocommerce-cart-form table.shop_table.cart tr:not(:last-child):after' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'left' => 'position: absolute; left: 0; bottom: 0;',
                    'center' => 'position: absolute; left: 0; right: 0; bottom: 0; margin: 0 auto;',
                    'right' => 'position: absolute; bottom: 0; right: 0; left: unset;',
                ],
            ]
        );        

        $this->end_controls_section();



        $this->start_controls_section(
            'remove_icon_style',
            [
                'label' => esc_html__( 'Remove Icon', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        /* Typography */
        $this->add_responsive_control(
            'remove_icon_font_size',
            [
                'label' => esc_html__( 'Icon Size', 'solace-extra' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce table.shop_table.cart .remove' =>
                        'font-size: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );


        $this->start_controls_tabs( 'remove_icon_tabs' );

        /* ================= NORMAL ================= */
        $this->start_controls_tab(
            'remove_icon_normal',
            [
                'label' => esc_html__( 'Normal', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'remove_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'solace-extra' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' =>'--wc-red: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce table.shop_table.cart .remove' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'remove_icon_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'solace-extra' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce table.shop_table.cart .remove' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        /* ================= HOVER ================= */
        $this->start_controls_tab(
            'remove_icon_hover',
            [
                'label' => esc_html__( 'Hover', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'remove_icon_hover_color',
            [
                'label' => esc_html__( 'Icon Color', 'solace-extra' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce table.shop_table.cart .remove:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'remove_icon_bg_hover_color',
            [
                'label' => esc_html__( 'Background Color', 'solace-extra' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce table.shop_table.cart .remove:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();


        /**
         * SECTION: Product Images
         */
        $this->start_controls_section(
            'section_product_images',
            [
                'label' => __('Product Images', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'product_image_width',
            [
                'label' => esc_html__( 'Width', 'solace-extra' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 120,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 300,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 200,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    '%' => [ 'min' => 1, 'max' => 100 ],
                    'px' => [ 'min' => 1, 'max' => 1000 ],
                    'vw' => [ 'min' => 1, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'product_image_max_width',
            [
                'label' => esc_html__( 'Max Width', 'solace-extra' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [ 'unit' => '%' ],
                'tablet_default' => [ 'unit' => '%' ],
                'mobile_default' => [ 'unit' => '%' ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    '%' => [ 'min' => 1, 'max' => 100 ],
                    'px' => [ 'min' => 1, 'max' => 200 ],
                    'vw' => [ 'min' => 1, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'product_image_height',
            [
                'label' => esc_html__( 'Height', 'solace-extra' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 200 ],
                    'vh' => [ 'min' => 1, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'product_image_object_fit',
            [
                'label' => esc_html__( 'Object Fit', 'solace-extra' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'product_image_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'solace-extra' ),
                    'fill' => esc_html__( 'Fill', 'solace-extra' ),
                    'cover' => esc_html__( 'Cover', 'solace-extra' ),
                    'contain' => esc_html__( 'Contain', 'solace-extra' ),
                    'scale-down' => esc_html__( 'Scale Down', 'solace-extra' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'product_image_object_position',
            [
                'label' => esc_html__( 'Object Position', 'solace-extra' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'solace-extra' ),
                    'center left' => esc_html__( 'Center Left', 'solace-extra' ),
                    'center right' => esc_html__( 'Center Right', 'solace-extra' ),
                    'top center' => esc_html__( 'Top Center', 'solace-extra' ),
                    'top left' => esc_html__( 'Top Left', 'solace-extra' ),
                    'top right' => esc_html__( 'Top Right', 'solace-extra' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'solace-extra' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'solace-extra' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'solace-extra' ),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'product_image_height[size]!' => '',
                    'product_image_object_fit' => [ 'cover', 'contain', 'scale-down' ],
                ],
            ]
        );

        $this->add_control(
            'product_image_separator_panel_style',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->start_controls_tabs( 'product_image_effects' );

        $this->start_controls_tab( 'product_image_normal',
            [
                'label' => esc_html__( 'Normal', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'product_image_opacity',
            [
                'label' => esc_html__( 'Opacity', 'solace-extra' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'max' => 1, 'min' => 0.10, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'product_image_css_filters',
                'selector' => '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'product_image_hover',
            [
                'label' => esc_html__( 'Hover', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'product_image_opacity_hover',
            [
                'label' => esc_html__( 'Opacity', 'solace-extra' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'max' => 1, 'min' => 0.10, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img:hover, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img:hover, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img:hover, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img:hover' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'product_image_css_filters_hover',
                'selector' => '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img:hover, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img:hover, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img:hover, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img:hover',
            ]
        );

        $this->add_control(
            'product_image_transition',
            [
                'label' => esc_html__( 'Transition Duration', 'solace-extra' ) . ' (s)',
                'type' => Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 3, 'step' => 0.1 ] ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'product_image_border',
                'selector' => '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'product_image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'product_image_box_shadow',
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
                'exclude' => [ 'box_shadow_position' ],
                'selector' => '{{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail img, {{WRAPPER}} .woocommerce-cart-form__contents .product-thumbnail a img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail img, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce form.woocommerce-cart-form .shop_table .product-thumbnail a img',
            ]
        );

        $this->end_controls_section();

        /**
         * SECTION: Buttons
         */
        $this->start_controls_section(
            'section_buttons',
            [
                'label' => __('Buttons', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_button_style_controls();

        $this->end_controls_section();

        /**
         * SECTION: Cart Totals
         */
        $this->start_controls_section(
            'section_totals',
            [
                'label' => __('Cart Totals', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Padding
        $this->add_responsive_control(
            'cart_totals_box_padding',
            [
                'label' => esc_html__( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .cart_totals,
                    {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        // Margin
        $this->add_responsive_control(
            'cart_totals_box_margin',
            [
                'label' => esc_html__( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .cart_totals,
                    {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'cart_totals_box_border',
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .cart_totals, {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals',
            ]
        );
        
        // Border Radius
        $this->add_responsive_control(
            'cart_totals_box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .cart_totals, {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'totals_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cart_totals',
            ]
        );        

        // Section Title
        $this->add_control(
            'title_totals',
            [
                'label' => esc_html__( 'Section Title', 'solace-extra' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_totals_typography',
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals h2',
            ]
        );

        // Colors
        $this->add_control(
            'title_totals_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart h2.title, {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Background
        $this->add_control(
            'title_totals_background',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals h2' => 'background-color: {{VALUE}};',
                ],
            ]
        );        

        // Spacing
        $this->add_responsive_control(
            'title_totals_padding',
            [
                'label' => esc_html__( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'title_totals_margin',
            [
                'label' => esc_html__( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_totals_border_spacing',
            [
                'label' => __('Spacing', 'solace-extra'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals h2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'title_totals_border',
                'label' => __('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals h2',
            ]
        );
        
        // Content
        $this->add_control(
            'content_totals',
            [
                'label' => esc_html__( 'Content', 'solace-extra' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'totals_typography',
                'selector' => '{{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cart-collaterals .cart_totals th, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cart-collaterals .cart_totals td, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cart-collaterals .cart_totals .woocommerce-Price-amount',
            ]
        );

        // Colors
        $this->add_control(
            'totals_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart_totals, 
                    {{WRAPPER}} .cart_totals a,
                    {{WRAPPER}} .cart-collaterals .cart_totals th,
                    {{WRAPPER}} .cart-collaterals .cart_totals td,
                    {{WRAPPER}} .cart-collaterals .cart_totals .woocommerce-Price-amount.amount,
                    {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .woocommerce-cart-form .cart_totals th,
                    {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .woocommerce-cart-form .cart_totals td,
                    {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .woocommerce-cart-form .cart_totals .woocommerce-Price-amount.amount' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        // Divider Controls
        $this->add_control(
            'cart_totals_divider_heading',
            [
                'label' => __('Divider', 'solace-extra'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cart_totals_divider',
            [
                'label' => __('Divider', 'solace-extra'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'solace-extra'),
                'label_off' => __('Hide', 'solace-extra'),
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals tr:after' => 'content: ""',
                ],
            ]
        );

        $this->add_control(
            'cart_totals_divider_style',
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
                    'cart_totals_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals tr:after' => 'border-top-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'cart_totals_divider_weight',
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
                    'cart_totals_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals tr:after' => 'border-top-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'cart_totals_divider_width',
            [
                'label' => __('Width', 'solace-extra'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'condition' => [
                    'cart_totals_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals tr:after' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'cart_totals_divider_color',
            [
                'label' => __('Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'default' => '#676767',
                'condition' => [
                    'cart_totals_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals tr:after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'cart_totals_divider_alignment',
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
                    'cart_totals_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart_totals tr:after' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'left' => 'position: absolute; left: 0; bottom: 0;',
                    'center' => 'position: absolute; left: 0; right: 0; bottom: 0; margin: 0 auto;',
                    'right' => 'position: absolute; bottom: 0; right: 0; left: unset;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * SECTION: Coupon Code
         */
        $this->start_controls_section(
            'section_coupon',
            [
                'label' => __('Coupon Code', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Padding
        $this->add_responsive_control(
            'coupon_box_padding',
            [
                'label' => esc_html__( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );
        
        // Margin
        $this->add_responsive_control(
            'coupon_box_margin',
            [
                'label' => esc_html__( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .coupon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );
        
        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'coupon_box_border',
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .coupon, {{WRAPPER}} .solace-extra-box-woocommerce-cart.layout-2 .box-coupon, {{WRAPPER}} .solace-extra-box-woocommerce-cart.layout-3 .box-coupon',
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );
        
        // Border Radius
        $this->add_responsive_control(
            'coupon_box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .coupon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'coupon_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-cart .box-coupon, {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .coupon',
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );        

        // Section Title
        $this->add_control(
            'title_coupon',
            [
                'label' => esc_html__( 'Section Title', 'solace-extra' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography_coupon',
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-cart .solace-cart-flexbox h2.title-coupon, {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .coupon h2',
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );

        // Colors
        $this->add_control(
            'title_color_coupon',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .solace-cart-flexbox h2.title-coupon, {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .coupon h2' => 'color: {{VALUE}};',
                ],
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );

        // Background
        $this->add_control(
            'title_coupon_background',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .solace-cart-flexbox h2.title-coupon, {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .coupon h2' => 'background-color: {{VALUE}};',
                ],
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );           

        // Spacing
        $this->add_responsive_control(
            'title_coupon_padding',
            [
                'label' => esc_html__( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .solace-cart-flexbox h2.title-coupon, {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .coupon h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );
        
        $this->add_responsive_control(
            'title_coupon_margin',
            [
                'label' => esc_html__( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-cart .solace-cart-flexbox h2.title-coupon, {{WRAPPER}} .solace-extra-box-woocommerce-cart .cart-collaterals .coupon h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );

        $this->add_responsive_control(
            'coupon_code_border_spacing',
            [
                'label' => __('Spacing', 'solace-extra'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .solace-cart-flexbox .coupon' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} div.solace-extra-box-woocommerce-cart.layout-1 .woocommerce .solace-cart-flexbox .coupon h2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'coupon_code_border',
                'label' => __('Border', 'solace-extra'),
                'selector' => '
                {{WRAPPER}} .solace-extra-box-woocommerce-cart .coupon h2, 
                {{WRAPPER}} .solace-extra-box-woocommerce-cart .solace-cart-flexbox h2.title-coupon',
                'condition' => [ 'show_coupon_code' => 'yes' ],
            ]
        );

        $this->add_control(
            'coupon_input_heading',
            [
                'label' => __('Input Fields', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_coupon_code' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'coupon_input_typography',
                'label'    => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .coupon .input-text',
                'condition' => [
                    'show_coupon_code' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs(
            'coupon_input_tabs',
            [
                'condition' => [
                    'show_coupon_code' => 'yes',
                ],
            ]
        );

        $this->start_controls_tab(
            'coupon_input_normal_tab',
            [
                'label' => __('Normal', 'solace-extra'),
            ]
        );

        $this->add_control(
            'coupon_input_color',
            [
                'label'     => __('Text Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .coupon .input-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_input_bg_color',
            [
                'label'     => __('Background Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .coupon .input-text' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'coupon_input_focus_tab',
            [
                'label' => __('Focus', 'solace-extra'),
            ]
        );

        $this->add_control(
            'coupon_input_color_focus',
            [
                'label'     => __('Text Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .coupon .input-text:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_input_bg_color_focus',
            [
                'label'     => __('Background Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .coupon .input-text:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'coupon_input_border_radius',
            [
                'label'      => __('Border Radius', 'solace-extra'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .coupon .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_coupon_code' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'coupon_input_padding',
            [
                'label'      => __('Padding', 'solace-extra'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .coupon .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_coupon_code' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'coupon_input_margin',
            [
                'label'      => __('Margin', 'solace-extra'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .coupon .input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_coupon_code' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();        

        /**
         * SECTION: Cross Sell
         */
        $this->start_controls_section(
            'section_cross_sell',
            [
                'label' => __('Cross Sell', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Padding
        $this->add_responsive_control(
            'cross_sell_padding',
            [
                'label' => __('Padding', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cross-sells' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin
        $this->add_responsive_control(
            'cross_sell_margin',
            [
                'label' => __('Margin', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cross-sells' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cross_sell_border',
                'label' => __('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .cross-sells',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'cross_sell_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cross-sells' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'cross_sell_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cross-sells',
            ]
        );        

        // Section Title
        $this->add_control(
            'cross_sell_title_heading',
            [
                'label' => __('Section Title', 'solace-extra'),
                'type'  => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .cross-sells h2',
            ]
        );

        // Colors
        $this->add_control(
            'title_color',
            [
                'label'     => __('Text Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cross-sells h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Product Title
        $this->add_control(
            'cross_sell_product_title_heading',
            [
                'label' => __('Product Title', 'solace-extra'),
                'type'  => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'product_title_typography',
                'label'    => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} div.solace-extra-box-woocommerce-cart .cross-sells ul.products li.product a.woocommerce-loop-product__link>h5.woocommerce-loop-product__title'
            ]
        );

        // Colors
        $this->add_control(
            'product_title_color',
            [
                'label'     => __('Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cross-sells .woocommerce-loop-product__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Price
        $this->add_control(
            'cross_sell_price_heading',
            [
                'label' => __('Price', 'solace-extra'),
                'type'  => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'cross_sell_price_typography',
                'label'    => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cross-sells .price bdi',
            ]
        );

        // Colors
        $this->add_control(
            'cross_sell_price_color',
            [
                'label'     => __('Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cross-sells .price bdi, {{WRAPPER}} div.solace-extra-box-woocommerce-cart .woocommerce .cross-sells .price del bdi' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }


    protected function solace_extra_render_cart_html() {
        ob_start();
        ?>
        <div class="woocommerce solace-extra-cart-mock">
            <div class="woocommerce-notices-wrapper"></div>

            <form class="woocommerce-cart-form" action="" method="post">
                <table class="shop_table shop_table_responsive cart" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="product-remove">&nbsp;</th>
                            <th class="product-thumbnail">&nbsp;</th>
                            <th class="product-name"><?php echo esc_html__( 'Product', 'solace-extra' ); ?></th>
                            <th class="product-price"><?php echo esc_html__( 'Price', 'solace-extra' ); ?></th>
                            <th class="product-quantity"><?php echo esc_html__( 'Quantity', 'solace-extra' ); ?></th>
                            <th class="product-subtotal"><?php echo esc_html__( 'Subtotal', 'solace-extra' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="woocommerce-cart-form__cart-item cart_item">
                            <td class="product-remove">
                                <a href="#" class="remove" aria-label="<?php echo esc_attr__( 'Remove this item', 'solace-extra' ); ?>">&times;</a>
                            </td>

                            <td class="product-thumbnail">
                                <img src="#" alt="Sample Product" />
                            </td>

                            <td class="product-name" data-title="<?php echo esc_attr__( 'Product', 'solace-extra' ); ?>">
                                <?php echo esc_html__( 'Sample Product', 'solace-extra' ); ?>
                            </td>

                            <td class="product-price" data-title="<?php echo esc_attr__( 'Price', 'solace-extra' ); ?>">
                                <span class="amount">Rp 150.000</span>
                            </td>

                            <td class="product-quantity" data-title="<?php echo esc_attr__( 'Quantity', 'solace-extra' ); ?>">
                                <input type="number" class="input-text qty text" step="1" min="0" value="1" />
                            </td>

                            <td class="product-subtotal" data-title="<?php echo esc_attr__( 'Subtotal', 'solace-extra' ); ?>">
                                <span class="amount">Rp 150.000</span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="6" class="actions">
                                <div class="coupon">
                                    <label for="coupon_code"><?php echo esc_html__( 'Coupon:', 'solace-extra' ); ?></label>
                                    <input type="text" name="coupon_code" id="coupon_code" value="" placeholder="<?php echo esc_attr__( 'Coupon code', 'solace-extra' ); ?>" />
                                    <button type="submit" class="button" name="apply_coupon"><?php echo esc_html__( 'Apply coupon', 'solace-extra' ); ?></button>
                                </div>

                                <button type="submit" class="button" name="update_cart"><?php echo esc_html__( 'Update cart', 'solace-extra' ); ?></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>

            <div class="cart-collaterals" style="margin-top:20px;">
                <div class="cart_totals">
                    <h2><?php echo esc_html__( 'Cart totals', 'solace-extra' ); ?></h2>

                    <table cellspacing="0" class="shop_table shop_table_responsive">
                        <tr class="cart-subtotal">
                            <th><?php echo esc_html__( 'Subtotal', 'solace-extra' ); ?></th>
                            <td><span class="amount">Rp 150.000</span></td>
                        </tr>

                        <tr class="order-total">
                            <th><?php echo esc_html__( 'Total', 'solace-extra' ); ?></th>
                            <td><strong><span class="amount">Rp 150.000</span></strong></td>
                        </tr>
                    </table>

                    <div class="wc-proceed-to-checkout" style="margin-top:12px;">
                        <a href="#" class="checkout-button button alt elementor-button">
                             <?php echo esc_html__( 'Proceed to checkout', 'solace-extra' ); ?>
                         </a>
                     </div>
                </div>
            </div>

        </div>

        <style>
            .solace-extra-cart-mock table.shop_table {
                width:100%;
                border-collapse:collapse;
                margin-bottom:20px;
            }
            .solace-extra-cart-mock table.shop_table th,
            .solace-extra-cart-mock table.shop_table td {
                border:1px solid #efefef;
                padding:8px;
                text-align:left;
            }
            .solace-extra-cart-mock .button {
                background:#2b8aef;
                color:#fff;
                border:none;
                padding:8px 14px;
                border-radius:4px;
                cursor:pointer;
            }
            .solace-extra-cart-mock input[type="text"],
            .solace-extra-cart-mock input[type="number"] {
                border:1px solid #ddd;
                border-radius:4px;
                padding:6px;
            }
        </style>
        <?php
        return ob_get_clean();
    }


    protected function render() {

        add_filter( 'body_class', function( $classes ) {
            if ( ! in_array( 'has-solace-cart-widget', $classes, true ) ) {
                $classes[] = 'has-solace-cart-widget';
            }
            return $classes;
        });

        $settings = $this->get_settings_for_display();

        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';

        if ( ! function_exists( 'WC' ) ) {
            echo '<p>' . esc_html__( 'WooCommerce not active.', 'solace-extra' ) . '</p>';
            // return ob_get_clean();
            return;
        }


        if ( is_null( WC()->cart ) ) {
            wc_load_cart();
        }

        $cart = WC()->cart;

        echo '<div id="solace-cart-container" class="solace-cart-widget-content">';

        $is_empty = ( ! $cart || $cart->is_empty() );
        $empty_style = $is_empty ? '' : 'style="display:none;"';
        
        

        // Get responsive control values
        $cross_sell_columns = isset($settings['cross_sell_columns']) ? $settings['cross_sell_columns'] : 4;
        $cross_sell_columns_tablet = isset($settings['cross_sell_columns_tablet']) ? $settings['cross_sell_columns_tablet'] : 3;
        $cross_sell_columns_mobile = isset($settings['cross_sell_columns_mobile']) ? $settings['cross_sell_columns_mobile'] : 2;

        $cross_sell_limit = isset($settings['cross_sell_limit']) ? $settings['cross_sell_limit'] : 4;
        $cross_sell_limit_tablet = isset($settings['cross_sell_limit_tablet']) ? $settings['cross_sell_limit_tablet'] : 6;
        $cross_sell_limit_mobile = isset($settings['cross_sell_limit_mobile']) ? $settings['cross_sell_limit_mobile'] : 6;

        $layout_class = isset($settings['layout']) ? $settings['layout'] : 'layout-1';
        
        // Add responsive cross-sell columns classes
        $layout_class .= ' cross-sell-desktop-' . $cross_sell_columns;
        $layout_class .= ' cross-sell-tablet-' . $cross_sell_columns_tablet;
        $layout_class .= ' cross-sell-mobile-' . $cross_sell_columns_mobile;
        
        // Add responsive cross-sell limit classes
        $layout_class .= ' cross-sell-limit-desktop-' . $cross_sell_limit;
        $layout_class .= ' cross-sell-limit-tablet-' . $cross_sell_limit_tablet;
        $layout_class .= ' cross-sell-limit-mobile-' . $cross_sell_limit_mobile;
        
        // Add show product image class if disabled
        if (isset($settings['show_product_image']) && $settings['show_product_image'] !== 'yes') {
            $layout_class .= ' hide-product-image';
        }

        // Replace "Cart totals" heading in WooCommerce cart using gettext filter
        add_filter( 'gettext', function( $translated_text, $text, $domain ) use ( $settings ) {
            if ( 'woocommerce' === $domain && 'Cart totals' === $text ) {
                return ! empty( $settings['cart_totals_title'] )
                    ? $settings['cart_totals_title']
                    : $text;
            }
            return $translated_text;
        }, 10, 3 );         

        // Change the Cross-Sell heading using Elementor control value
        add_filter( 'woocommerce_product_cross_sells_products_heading', function() use ( $settings ) {
            return ! empty( $settings['cross_sell_title'] ) 
                ? $settings['cross_sell_title'] 
                : __( 'You may be interested in&hellip;', 'solace-extra' );
        });        

        // Dynamically set the number of columns for Cross-Sells based on Elementor control
        add_filter('woocommerce_cross_sells_columns', function($columns) use ($cross_sell_columns) {
            return $cross_sell_columns;
        });

        // Dynamically set the number of cross-sell products to display based on Elementor control
        add_filter('woocommerce_cross_sells_total', function($total) {
            return 10;
        });

        // Add table box wrapper for all layouts
        add_action('woocommerce_before_cart_table', function() {
            echo '<div class="table-box">';
        });

        add_action('woocommerce_after_cart_table', function() {
            echo '</div>';
        });

        if ( 'layout-1' === $settings['layout'] ) {
            add_action('woocommerce_before_cart', function() {
                echo '<div class="solace-cart-flexbox">';
            });

            add_action('woocommerce_after_cart', function() {
                echo '</div>';
            });
       
            add_action( 'woocommerce_after_cart_table', 'woocommerce_cross_sell_display' );

            remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

            add_action('woocommerce_cart_collaterals', function() {
                $settings = $this->get_settings_for_display();
                if ( wc_coupons_enabled() && isset($settings['show_coupon_code']) && $settings['show_coupon_code'] === 'yes') { ?>
                    <div class="coupon">
                        <h2 class="title title-coupon">
                            <?php 
                            $settings = $this->get_settings_for_display();
                            $cross_sell_columns = $settings['cart_coupon_code'];
                            echo esc_html( $cross_sell_columns );
                            ?>
                        </h2>
                        <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'solace-extra' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'solace-extra' ); ?>" /> <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'solace-extra' ); ?>"><?php esc_html_e( 'Apply coupon', 'solace-extra' ); ?></button>
                        <?php do_action( 'woocommerce_cart_coupon' ); ?>
                    </div>
                <?php } 
            }, 5);
        } else if ( 'layout-2' === $settings['layout'] ) {
            add_action('woocommerce_before_cart', function() {
                echo '<div class="solace-cart-flexbox">';
            });

            add_action('woocommerce_after_cart', function() {
                echo '</div>';
            });

            add_action('woocommerce_after_cart_table', function() {
                $settings = $this->get_settings_for_display();
                if ( wc_coupons_enabled() && isset($settings['show_coupon_code']) && $settings['show_coupon_code'] === 'yes') { ?>
                    <div class="solace-cart-flexbox box-coupon">
                        <h2 class="title-coupon">
                            <?php 
                            $settings = $this->get_settings_for_display();
                            $cross_sell_columns = $settings['cart_coupon_code'];
                            echo esc_html( $cross_sell_columns );
                            ?>
                        </h2>
                        <div class="coupon">
                            <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'solace-extra' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'solace-extra' ); ?>" /> <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'solace-extra' ); ?>"><?php esc_html_e( 'Apply coupon', 'solace-extra' ); ?></button>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                        <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'solace-extra' ); ?>"><?php esc_html_e( 'Update cart', 'solace-extra' ); ?></button>
                    </div>
                <?php } 
            }, 5);            
       
            add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

            remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

        } else if ( 'layout-3' === $settings['layout'] ) {

            add_action('woocommerce_after_cart_table', function() {
        ?>
                    <div class="solace-cart-flexbox" style="align-items: flex-start;">
                        <?php
                        $settings = $this->get_settings_for_display();
                        if ( wc_coupons_enabled() && isset($settings['show_coupon_code']) && $settings['show_coupon_code'] === 'yes') {
                        ?>
                        <div class="solace-cart-flexbox box-coupon">
                            <h2 class="title-coupon">
                                <?php 
                                $settings = $this->get_settings_for_display();
                                $cross_sell_columns = $settings['cart_coupon_code'];
                                echo esc_html( $cross_sell_columns );
                                ?>
                            </h2>
                            <div class="coupon">
                                <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'solace-extra' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'solace-extra' ); ?>" /> <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'solace-extra' ); ?>"><?php esc_html_e( 'Apply coupon', 'solace-extra' ); ?></button>
                                <?php do_action( 'woocommerce_cart_coupon' ); ?>
                            </div>
                            <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'solace-extra' ); ?>"><?php esc_html_e( 'Update cart', 'solace-extra' ); ?></button>
                        </div>
                        <?php } ?>
                        <?php woocommerce_cart_totals(); ?>
                    </div> 
                    <?php
            }, 5);            

            remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

            add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

            remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

        }

		echo '<div id="solace-cart-' . esc_attr( $this->get_id() ) . '" class="solace-extra-box-woocommerce-cart ' . esc_attr($layout_class) . '">';        
        
        // Add custom CSS for responsive cross-sell columns and limits using flexbox
        echo '<style>
        {{WRAPPER}} .solace-extra-box-woocommerce-cart.hide-product-image .woocommerce .woocommerce-cart-form__contents .product-thumbnail {
            display: none !important;
        }
        
        /* Cross-Sell Container Flexbox Setup */
        {{WRAPPER}} .cross-sells ul.products {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 20px !important;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
        }
        
        {{WRAPPER}} .cross-sells ul.products li.product {
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Desktop Cross-Sell Columns */
        {{WRAPPER}} .cross-sell-desktop-1 .cross-sells ul.products li.product { flex: 0 0 100% !important; }
        {{WRAPPER}} .cross-sell-desktop-2 .cross-sells ul.products li.product { flex: 0 0 calc(50% - 20px) !important; }
        {{WRAPPER}} .cross-sell-desktop-3 .cross-sells ul.products li.product { flex: 0 0 calc(33.333% - 20px) !important; }
        {{WRAPPER}} .cross-sell-desktop-4 .cross-sells ul.products li.product { flex: 0 0 calc(25% - 20px) !important; }
        {{WRAPPER}} .cross-sell-desktop-5 .cross-sells ul.products li.product { flex: 0 0 calc(20% - 20px) !important; }
        
        /* Desktop Cross-Sell Limits - Hide products beyond the limit */
        {{WRAPPER}} .cross-sell-limit-desktop-1 .cross-sells ul.products li.product:nth-child(n+2) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-2 .cross-sells ul.products li.product:nth-child(n+3) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-3 .cross-sells ul.products li.product:nth-child(n+4) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-4 .cross-sells ul.products li.product:nth-child(n+5) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-5 .cross-sells ul.products li.product:nth-child(n+6) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-6 .cross-sells ul.products li.product:nth-child(n+7) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-7 .cross-sells ul.products li.product:nth-child(n+8) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-8 .cross-sells ul.products li.product:nth-child(n+9) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-9 .cross-sells ul.products li.product:nth-child(n+10) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-10 .cross-sells ul.products li.product:nth-child(n+11) { display: none !important; }
        
        /* Tablet Cross-Sell Columns */
        @media (max-width: 1024px) {
            {{WRAPPER}} .cross-sell-tablet-1 .cross-sells ul.products li.product { flex: 0 0 100% !important; }
            {{WRAPPER}} .cross-sell-tablet-2 .cross-sells ul.products li.product { flex: 0 0 calc(50% - 20px) !important; }
            {{WRAPPER}} .cross-sell-tablet-3 .cross-sells ul.products li.product { flex: 0 0 calc(33.333% - 20px) !important; }
            {{WRAPPER}} .cross-sell-tablet-4 .cross-sells ul.products li.product { flex: 0 0 calc(25% - 20px) !important; }
            {{WRAPPER}} .cross-sell-tablet-5 .cross-sells ul.products li.product { flex: 0 0 calc(20% - 20px) !important; }
            
            /* Tablet Cross-Sell Limits - Hide products beyond the limit */
            {{WRAPPER}} .cross-sell-limit-tablet-1 .cross-sells ul.products li.product:nth-child(n+2) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-2 .cross-sells ul.products li.product:nth-child(n+3) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-3 .cross-sells ul.products li.product:nth-child(n+4) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-4 .cross-sells ul.products li.product:nth-child(n+5) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-5 .cross-sells ul.products li.product:nth-child(n+6) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-6 .cross-sells ul.products li.product:nth-child(n+7) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-7 .cross-sells ul.products li.product:nth-child(n+8) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-8 .cross-sells ul.products li.product:nth-child(n+9) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-9 .cross-sells ul.products li.product:nth-child(n+10) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-10 .cross-sells ul.products li.product:nth-child(n+11) { display: none !important; }
        }
        
        /* Mobile Cross-Sell Columns */
        @media (max-width: 767px) {
            {{WRAPPER}} .cross-sells ul.products {
                gap: 15px !important;
            }
            
            {{WRAPPER}} .cross-sell-mobile-1 .cross-sells ul.products li.product { flex: 0 0 100% !important; }
            {{WRAPPER}} .cross-sell-mobile-2 .cross-sells ul.products li.product { flex: 0 0 calc(47.2% - 7.20px) !important; }
            {{WRAPPER}} .cross-sell-mobile-3 .cross-sells ul.products li.product { flex: 0 0 calc(33.333% - 20px) !important; }
            {{WRAPPER}} .cross-sell-mobile-4 .cross-sells ul.products li.product { flex: 0 0 calc(25% - 20px) !important; width: 18% !important;}
            {{WRAPPER}} .cross-sell-mobile-5 .cross-sells ul.products li.product { flex: 0 0 calc(20% - 20px) !important; width: 13% !important; }
            
            /* Mobile Cross-Sell Limits - Hide products beyond the limit */
            {{WRAPPER}} .cross-sell-limit-mobile-1 .cross-sells ul.products li.product:nth-child(n+2) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-2 .cross-sells ul.products li.product:nth-child(n+3) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-3 .cross-sells ul.products li.product:nth-child(n+4) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-4 .cross-sells ul.products li.product:nth-child(n+5) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-5 .cross-sells ul.products li.product:nth-child(n+6) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-6 .cross-sells ul.products li.product:nth-child(n+7) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-7 .cross-sells ul.products li.product:nth-child(n+8) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-8 .cross-sells ul.products li.product:nth-child(n+9) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-9 .cross-sells ul.products li.product:nth-child(n+10) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-10 .cross-sells ul.products li.product:nth-child(n+11) { display: none !important; }
        }
        </style>';
         
        $template_woocommerce_cart = do_shortcode('[woocommerce_cart]');
        // Safely add elementor-button class to checkout and coupon buttons
        $processed_html = $template_woocommerce_cart;
        if (class_exists('DOMDocument')) {
            $dom = new \DOMDocument();
            $prev = libxml_use_internal_errors(true);
            $wrapper = '<div id="__solace_cart_wrap__">' . $processed_html . '</div>';
            $dom->loadHTML('<?xml encoding="UTF-8"?>' . $wrapper);
            $xpath = new \DOMXPath($dom);
            // .checkout-button
            foreach ($xpath->query('//*[@id="__solace_cart_wrap__"]//*[contains(concat(" ", normalize-space(@class), " "), " checkout-button ")]') as $node) {
                $class = trim($node->getAttribute('class'));
                $tokens = preg_split('/\s+/', $class, -1, PREG_SPLIT_NO_EMPTY);
                $updatedTokens = [];
                foreach ($tokens as $t) {
                    if ($t === 'checkout-button' || $t === 'button') {
                        $updatedTokens[] = 'solace-extra-' . $t;
                    } else {
                        $updatedTokens[] = $t;
                    }
                }
                if (!in_array('elementor-button', $updatedTokens, true)) {
                    $updatedTokens[] = 'elementor-button';
                }
                $node->setAttribute('class', implode(' ', array_unique($updatedTokens)));
            }
            // name="apply_coupon"
            foreach ($xpath->query('//*[@id="__solace_cart_wrap__"]//*[@name="apply_coupon"]') as $node) {
                $class = trim($node->getAttribute('class'));
                $tokens = $class !== '' ? preg_split('/\s+/', $class, -1, PREG_SPLIT_NO_EMPTY) : [];
                $updatedTokens = [];
                foreach ($tokens as $t) {
                    if ($t === 'button') {
                        $updatedTokens[] = 'solace-extra-button';
                    } else {
                        $updatedTokens[] = $t;
                    }
                }
                if (!in_array('elementor-button', $updatedTokens, true)) {
                    $updatedTokens[] = 'elementor-button';
                }
                $node->setAttribute('class', implode(' ', array_unique($updatedTokens)));
            }
            // name="update_cart"
            foreach ($xpath->query('//*[@id="__solace_cart_wrap__"]//*[@name="update_cart"]') as $node) {
                $class = trim($node->getAttribute('class'));
                $tokens = $class !== '' ? preg_split('/\s+/', $class, -1, PREG_SPLIT_NO_EMPTY) : [];
                $updatedTokens = [];
                foreach ($tokens as $t) {
                    if ($t === 'button') {
                        $updatedTokens[] = 'solace-extra-button';
                    } else {
                        $updatedTokens[] = $t;
                    }
                }
                if (!in_array('elementor-button', $updatedTokens, true)) {
                    $updatedTokens[] = 'elementor-button';
                }
                $node->setAttribute('class', implode(' ', array_unique($updatedTokens)));
            }
            // Extract inner HTML of wrapper
            $container = $dom->getElementById('__solace_cart_wrap__');
            $result = '';
            if ($container) {
                foreach ($container->childNodes as $child) {
                    $result .= $dom->saveHTML($child);
                }
                $processed_html = $result;
            }
            libxml_clear_errors();
            libxml_use_internal_errors($prev);
        } else {
            // Fallback: simple, safe string replacements
            $processed_html = preg_replace('/class="checkout-button\b/i', 'class="checkout-button elementor-button', $processed_html);
            // If coupon button already has class attribute
            $processed_html = preg_replace_callback('/(<(?:a|button|input)\b[^>]*\bname="apply_coupon"[^>]*\bclass=")([^"]*)(")/i', function($m){
                return $m[1] . (strpos(' ' . $m[2] . ' ', ' elementor-button ') === false ? trim($m[2] . ' elementor-button') : $m[2]) . $m[3];
            }, $processed_html);
            // If coupon button has no class attribute, insert it
            $processed_html = preg_replace('/(<(?:a|button|input)\b[^>]*\bname="apply_coupon"(?![^>]*\bclass=)[^>]*)(>)/i', '$1 class="elementor-button"$2', $processed_html);
            // Update update_cart button class token
            $processed_html = preg_replace_callback('/(<(?:button|input)\b[^>]*\bname="update_cart"[^>]*\bclass=")([^"]*)(")/i', function($m){
                $tokens = preg_split('/\s+/', $m[2], -1, PREG_SPLIT_NO_EMPTY);
                $updated = [];
                foreach ($tokens as $t) {
                    $updated[] = ($t === 'button') ? 'solace-extra-button' : $t;
                }
                if (!in_array('elementor-button', $updated, true)) {
                    $updated[] = 'elementor-button';
                }
                return $m[1] . implode(' ', array_unique($updated)) . $m[3];
            }, $processed_html);
            // If update_cart has no class attribute, insert it
            $processed_html = preg_replace('/(<(?:button|input)\b[^>]*\bname="update_cart"(?![^>]*\bclass=)[^>]*)(>)/i', '$1 class="solace-extra-button elementor-button"$2', $processed_html);
        }
        $template_woocommerce_cart = $processed_html;
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $template_woocommerce_cart;

        echo '</div>';
        
        // Add JavaScript to ensure body class is added
        echo '<script>
        (function() {
            function addBodyClass() {
                var body = document.body;
                if (body && !body.classList.contains("has-solace-cart-widget")) {
                    body.classList.add("has-solace-cart-widget");
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

			// Apply Elementor hover animation class to WooCommerce cart buttons inside this widget
			var widgetRoot = document.getElementById("solace-cart-' . esc_js( $this->get_id() ) . '");
			var animationClass = "' . esc_js( $hover_animation ? ( 'elementor-animation-' . $settings['hover_animation'] ) : '' ) . '";
			function applyHoverAnimation() {
				if (!widgetRoot || !animationClass) return;
				var buttons = widgetRoot.querySelectorAll(".solace-extra-box-woocommerce-cart button.button, .solace-extra-box-woocommerce-cart a.button, .solace-extra-box-woocommerce-cart a.checkout-button, .solace-extra-box-woocommerce-cart .wc-proceed-to-checkout .button, .solace-extra-box-woocommerce-cart .wc-proceed-to-checkout a.button");
				buttons.forEach(function(btn){
					if (!btn.classList.contains(animationClass)) {
						btn.classList.add(animationClass);
					}
				});
			}
			applyHoverAnimation();
			if (typeof jQuery !== "undefined") {
				jQuery(document).on("updated_wc_div wc_fragments_loaded wc_fragments_refreshed", applyHoverAnimation);
			}            
        })();
        </script>';
    }

    protected function solace_extra_render_cart_layout_1_html( $settings = [] ) {
        ob_start();

        $cart = WC()->cart;

        if ( ! function_exists( 'WC' ) ) {
            echo '<p>' . esc_html__( 'WooCommerce is not active.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }

        if ( is_null( WC()->cart ) && did_action( 'woocommerce_loaded' ) ) {
            wc_load_cart();
        }

        $cart = WC()->cart;

        if ( ! $cart || ! $cart instanceof WC_Cart ) {
            echo '<p>' . esc_html__( 'The cart has not been initialized yet.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }

        if ( $cart->is_empty() ) {
            echo '<p>' . esc_html__( 'Your cart is currently empty.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }


        ?>
        <!-- <div class="woocommerce-cart-form-wrapper solace-cart-flexbox"> -->
        <div class="woocommerce">
            <?php do_action( 'woocommerce_before_cart' ); ?>

            <div class="solace-cart-flexbox">
                <!-- <div class="cart-collaterals two-columns"> -->

                    <!-- LEFT COLUMN -->
                    <!-- <div class="cart-collaterals-left"> -->
                    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                        <?php do_action( 'woocommerce_before_cart_table' ); ?>
                        <div class="table-box">
                            <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp;</th>
                                        <th class="product-thumbnail">&nbsp;</th>
                                        <th class="product-name"><?php esc_html_e( 'Product', 'solace-extra' ); ?></th>
                                        <th class="product-price"><?php esc_html_e( 'Price', 'solace-extra' ); ?></th>
                                        <th class="product-quantity"><?php esc_html_e( 'Quantity', 'solace-extra' ); ?></th>
                                        <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'solace-extra' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) : ?>
                                            <tr class="woocommerce-cart-form__cart-item">
                                                <td class="product-remove">
                                                    <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="remove">&times;</a>
                                                </td>
                                                <td class="product-thumbnail"><?php 
                                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                echo $_product->get_image(); ?></td>
                                                <td class="product-name"><?php 
                                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                echo $_product->get_name(); ?></td>
                                                <td class="product-price"><?php 
                                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                echo WC()->cart->get_product_price( $_product ); ?></td>
                                                <td class="product-quantity">
                                                    <?php
                                                    woocommerce_quantity_input(
                                                        [
                                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                                            'input_value'  => $cart_item['quantity'],
                                                            'max_value'    => $_product->get_max_purchase_quantity(),
                                                            'min_value'    => 0,
                                                            'product_name' => $_product->get_name(),
                                                        ],
                                                        $_product,
                                                        false
                                                    );
                                                    ?>
                                                </td>
                                                <td class="product-subtotal"><?php 
                                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?></td>
                                            </tr>
                                        <?php endif;
                                    endforeach; ?>

                                    <?php do_action( 'woocommerce_cart_contents' ); ?>

                                    <tr>
                                        <td colspan="6" class="actions">
                                            <button type="submit" class="button" name="update_cart"><?php esc_html_e( 'Update cart', 'solace-extra' ); ?></button>
                                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                                        </td>
                                    </tr>

                                    <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                                </tbody>
                            </table>
                        </div> <!--table-box-->
                        <?php do_action( 'woocommerce_after_cart_table' ); ?>
                        <?php woocommerce_cross_sell_display(); ?>
                    </form>
                    <!-- Cross sells -->
                    <!-- </div> -->

                    <!-- RIGHT COLUMN -->
                    <!-- <div class="cart-collaterals-right"> -->
                    <div class="cart-collaterals">
                        <?php if ( wc_coupons_enabled() ) : ?>
                            <div class="coupon">
                                <h2 class="title title-coupon">
                                    <?php echo ! empty( $settings['cart_coupon_code'] ) ? esc_html( $settings['cart_coupon_code'] ) : esc_html__( 'Coupon', 'solace-extra' ); ?>
                                </h2>
                                <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'solace-extra' ); ?></label>
                                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'solace-extra' ); ?>" />
                                <button type="submit" class="button" name="apply_coupon"><?php esc_html_e( 'Apply coupon', 'solace-extra' ); ?></button>
                                <?php do_action( 'woocommerce_cart_coupon' ); ?>
                            </div>
                        <?php endif; ?>

                        <?php wc_get_template( 'cart/cart-totals.php' ); ?>
                    </div>
                <!-- </div> -->
            </div>

            <?php do_action( 'woocommerce_after_cart' ); ?>
        </div>
        <?php

        return ob_get_clean();
    }

    private function render_empty_cart_html() {
        echo '<div class="solace-extra-cart-empty">';
        echo '<p class="woocommerce-mini-cart__empty-message">' . esc_html__( 'Your cart is currently empty.', 'solace-extra' ) . '</p>';
        echo '<p class="return-to-shop">
                <a class="button wc-backward" href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '">
                    ' . esc_html__( 'Return to shop', 'solace-extra' ) . '
                </a>
            </p>';
        echo '</div>';
    }

    protected function solace_extra_render_cart_layout_2_html( $settings = [] ) {
        ob_start();

        $cart = WC()->cart;

        if ( ! function_exists( 'WC' ) ) {
            echo '<p>' . esc_html__( 'WooCommerce is not active.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }

        if ( is_null( WC()->cart ) && did_action( 'woocommerce_loaded' ) ) {
            wc_load_cart();
        }

        $cart = WC()->cart;

        if ( ! $cart || ! $cart instanceof WC_Cart ) {
            echo '<p>' . esc_html__( 'The cart has not been initialized yet.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }

        if ( $cart->is_empty() ) {
            echo '<p>' . esc_html__( 'Your cart is currently empty.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }

        ?>
        <div class="woocommerce">
            <?php do_action( 'woocommerce_before_cart' ); ?>
            <div class="solace-cart-flexbox">
                <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                    <?php do_action( 'woocommerce_before_cart_table' ); ?>
                    <div class="table-box">
                        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name"><?php esc_html_e( 'Product', 'solace-extra' ); ?></th>
                                    <th class="product-price"><?php esc_html_e( 'Price', 'solace-extra' ); ?></th>
                                    <th class="product-quantity"><?php esc_html_e( 'Quantity', 'solace-extra' ); ?></th>
                                    <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'solace-extra' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) : ?>
                                        <tr class="woocommerce-cart-form__cart-item">
                                            <td class="product-remove">
                                                <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="remove">&times;</a>
                                            </td>
                                            <td class="product-thumbnail"><?php 
                                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            echo $_product->get_image(); ?></td>
                                            <td class="product-name"><?php 
                                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            echo $_product->get_name(); ?></td>
                                            <td class="product-price"><?php 
                                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            echo WC()->cart->get_product_price( $_product ); ?></td>
                                            <td class="product-quantity">
                                                <?php
                                                woocommerce_quantity_input(
                                                    [
                                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                                        'input_value'  => $cart_item['quantity'],
                                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                                        'min_value'    => 0,
                                                        'product_name' => $_product->get_name(),
                                                    ],
                                                    $_product,
                                                    false
                                                );
                                                ?>
                                            </td>
                                            <td class="product-subtotal"><?php 
                                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?></td>
                                        </tr>
                                    <?php endif;
                                endforeach; ?>

                                <?php do_action( 'woocommerce_cart_contents' ); ?>

                                <tr>
                                    <td colspan="6" class="actions">
                                        <button type="submit" class="button" name="update_cart"><?php esc_html_e( 'Update cart', 'solace-extra' ); ?></button>
                                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                                    </td>
                                </tr>

                                <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                            </tbody>
                        </table>
                        <?php if ( wc_coupons_enabled() ) : ?>
                            <div class="solace-cart-flexbox box-coupon">
                                <h2 class="title title-coupon">
                                    <?php echo ! empty( $settings['cart_coupon_code'] ) ? esc_html( $settings['cart_coupon_code'] ) : esc_html__( 'Coupon', 'solace-extra' ); ?>
                                </h2>
                                <div class="coupon">
                                    <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'solace-extra' ); ?></label>
                                    <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'solace-extra' ); ?>" />
                                    <button type="submit" class="button" name="apply_coupon"><?php esc_html_e( 'Apply coupon', 'solace-extra' ); ?></button>
                                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div> <!--table-box-->
                    <?php do_action( 'woocommerce_after_cart_table' ); ?>
                </form>
                <div class="cart-collaterals">
                    <?php wc_get_template( 'cart/cart-totals.php' ); ?>
                </div>
            </div>
            <?php woocommerce_cross_sell_display(); ?>
            <?php do_action( 'woocommerce_after_cart' ); ?>
        </div>
        <?php

        return ob_get_clean();
    }
    
    protected function solace_extra_render_cart_layout_3_html( $settings = [] ) {
        ob_start();

        $cart = WC()->cart;

        if ( ! function_exists( 'WC' ) ) {
            echo '<p>' . esc_html__( 'WooCommerce is not active.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }

        if ( is_null( WC()->cart ) && did_action( 'woocommerce_loaded' ) ) {
            wc_load_cart();
        }

        $cart = WC()->cart;

        if ( ! $cart || ! $cart instanceof WC_Cart ) {
            echo '<p>' . esc_html__( 'The cart has not been initialized yet.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }

        if ( $cart->is_empty() ) {
            echo '<p>' . esc_html__( 'Your cart is currently empty.', 'solace-extra' ) . '</p>';
            return ob_get_clean();
        }

        ?>
        <div class="woocommerce">
            <?php do_action( 'woocommerce_before_cart' ); ?>
            
            <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                <?php do_action( 'woocommerce_before_cart_table' ); ?>
                <div class="table-box">
                    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="product-remove">&nbsp;</th>
                                <th class="product-thumbnail">&nbsp;</th>
                                <th class="product-name"><?php esc_html_e( 'Product', 'solace-extra' ); ?></th>
                                <th class="product-price"><?php esc_html_e( 'Price', 'solace-extra' ); ?></th>
                                <th class="product-quantity"><?php esc_html_e( 'Quantity', 'solace-extra' ); ?></th>
                                <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'solace-extra' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                            <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) : ?>
                                    <tr class="woocommerce-cart-form__cart-item">
                                        <td class="product-remove">
                                            <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="remove">&times;</a>
                                        </td>
                                        <td class="product-thumbnail"><?php 
                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        echo $_product->get_image(); ?></td>
                                        <td class="product-name"><?php 
                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        echo $_product->get_name(); ?></td>
                                        <td class="product-price"><?php 
                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        echo WC()->cart->get_product_price( $_product ); ?></td>
                                        <td class="product-quantity">
                                            <?php
                                            woocommerce_quantity_input(
                                                [
                                                    'input_name'   => "cart[{$cart_item_key}][qty]",
                                                    'input_value'  => $cart_item['quantity'],
                                                    'max_value'    => $_product->get_max_purchase_quantity(),
                                                    'min_value'    => 0,
                                                    'product_name' => $_product->get_name(),
                                                ],
                                                $_product,
                                                false
                                            );
                                            ?>
                                        </td>
                                        <td class="product-subtotal"><?php 
                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?></td>
                                    </tr>
                                <?php endif;
                            endforeach; ?>

                            <?php do_action( 'woocommerce_cart_contents' ); ?>

                            <tr>
                                <td colspan="6" class="actions">
                                    <button type="submit" class="button" name="update_cart"><?php esc_html_e( 'Update cart', 'solace-extra' ); ?></button>
                                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                                </td>
                            </tr>

                            <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                        </tbody>
                    </table>
                    <div class="solace-cart-flexbox" style="align-items: flex-start;">
                        <?php if ( wc_coupons_enabled() ) : ?>
                        <div class="solace-cart-flexbox box-coupon">
                            <h2 class="title title-coupon">
                                <?php echo ! empty( $settings['cart_coupon_code'] ) ? esc_html( $settings['cart_coupon_code'] ) : esc_html__( 'Coupon', 'solace-extra' ); ?>
                            </h2>
                            <div class="coupon">
                                <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'solace-extra' ); ?></label>
                                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'solace-extra' ); ?>" />
                                <button type="submit" class="button" name="apply_coupon"><?php esc_html_e( 'Apply coupon', 'solace-extra' ); ?></button>
                                <?php do_action( 'woocommerce_cart_coupon' ); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php wc_get_template( 'cart/cart-totals.php' ); ?>
                </div> <!--table-box-->
                <?php do_action( 'woocommerce_after_cart_table' ); ?>
            </form>
            <?php woocommerce_cross_sell_display(); ?>
            <?php do_action( 'woocommerce_after_cart' ); ?>
        </div>
        <?php

        return ob_get_clean();
    }

    protected function style_all_layout(){
        echo '<style>
        {{WRAPPER}} .solace-extra-box-woocommerce-cart.hide-product-image .woocommerce .woocommerce-cart-form__contents .product-thumbnail {
            display: none !important;
        }
        
        /* Cross-Sell Container Flexbox Setup */
        {{WRAPPER}} .cross-sells ul.products {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 20px !important;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
        }
        
        {{WRAPPER}} .cross-sells ul.products li.product {
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Desktop Cross-Sell Columns */
        {{WRAPPER}} .cross-sell-desktop-1 .cross-sells ul.products li.product { flex: 0 0 100% !important; }
        {{WRAPPER}} .cross-sell-desktop-2 .cross-sells ul.products li.product { flex: 0 0 calc(50% - 20px) !important; }
        {{WRAPPER}} .cross-sell-desktop-3 .cross-sells ul.products li.product { flex: 0 0 calc(33.333% - 20px) !important; }
        {{WRAPPER}} .cross-sell-desktop-4 .cross-sells ul.products li.product { flex: 0 0 calc(25% - 20px) !important; }
        {{WRAPPER}} .cross-sell-desktop-5 .cross-sells ul.products li.product { flex: 0 0 calc(20% - 20px) !important; }
        
        /* Desktop Cross-Sell Limits - Hide products beyond the limit */
        {{WRAPPER}} .cross-sell-limit-desktop-1 .cross-sells ul.products li.product:nth-child(n+2) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-2 .cross-sells ul.products li.product:nth-child(n+3) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-3 .cross-sells ul.products li.product:nth-child(n+4) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-4 .cross-sells ul.products li.product:nth-child(n+5) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-5 .cross-sells ul.products li.product:nth-child(n+6) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-6 .cross-sells ul.products li.product:nth-child(n+7) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-7 .cross-sells ul.products li.product:nth-child(n+8) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-8 .cross-sells ul.products li.product:nth-child(n+9) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-9 .cross-sells ul.products li.product:nth-child(n+10) { display: none !important; }
        {{WRAPPER}} .cross-sell-limit-desktop-10 .cross-sells ul.products li.product:nth-child(n+11) { display: none !important; }
        
        /* Tablet Cross-Sell Columns */
        @media (max-width: 1024px) {
            {{WRAPPER}} .cross-sell-tablet-1 .cross-sells ul.products li.product { flex: 0 0 100% !important; }
            {{WRAPPER}} .cross-sell-tablet-2 .cross-sells ul.products li.product { flex: 0 0 calc(50% - 20px) !important; }
            {{WRAPPER}} .cross-sell-tablet-3 .cross-sells ul.products li.product { flex: 0 0 calc(33.333% - 20px) !important; }
            {{WRAPPER}} .cross-sell-tablet-4 .cross-sells ul.products li.product { flex: 0 0 calc(25% - 20px) !important; }
            {{WRAPPER}} .cross-sell-tablet-5 .cross-sells ul.products li.product { flex: 0 0 calc(20% - 20px) !important; }
            
            /* Tablet Cross-Sell Limits - Hide products beyond the limit */
            {{WRAPPER}} .cross-sell-limit-tablet-1 .cross-sells ul.products li.product:nth-child(n+2) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-2 .cross-sells ul.products li.product:nth-child(n+3) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-3 .cross-sells ul.products li.product:nth-child(n+4) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-4 .cross-sells ul.products li.product:nth-child(n+5) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-5 .cross-sells ul.products li.product:nth-child(n+6) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-6 .cross-sells ul.products li.product:nth-child(n+7) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-7 .cross-sells ul.products li.product:nth-child(n+8) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-8 .cross-sells ul.products li.product:nth-child(n+9) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-9 .cross-sells ul.products li.product:nth-child(n+10) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-tablet-10 .cross-sells ul.products li.product:nth-child(n+11) { display: none !important; }
        }
        
        /* Mobile Cross-Sell Columns */
        @media (max-width: 767px) {
            {{WRAPPER}} .cross-sells ul.products {
                gap: 15px !important;
            }
            
            {{WRAPPER}} .cross-sell-mobile-1 .cross-sells ul.products li.product { flex: 0 0 100% !important; }
            {{WRAPPER}} .cross-sell-mobile-2 .cross-sells ul.products li.product { flex: 0 0 calc(47.2% - 7.20px) !important; }
            {{WRAPPER}} .cross-sell-mobile-3 .cross-sells ul.products li.product { flex: 0 0 calc(33.333% - 20px) !important; }
            {{WRAPPER}} .cross-sell-mobile-4 .cross-sells ul.products li.product { flex: 0 0 calc(25% - 20px) !important; width: 18% !important;}
            {{WRAPPER}} .cross-sell-mobile-5 .cross-sells ul.products li.product { flex: 0 0 calc(20% - 20px) !important; width: 13% !important; }
            
            /* Mobile Cross-Sell Limits - Hide products beyond the limit */
            {{WRAPPER}} .cross-sell-limit-mobile-1 .cross-sells ul.products li.product:nth-child(n+2) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-2 .cross-sells ul.products li.product:nth-child(n+3) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-3 .cross-sells ul.products li.product:nth-child(n+4) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-4 .cross-sells ul.products li.product:nth-child(n+5) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-5 .cross-sells ul.products li.product:nth-child(n+6) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-6 .cross-sells ul.products li.product:nth-child(n+7) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-7 .cross-sells ul.products li.product:nth-child(n+8) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-8 .cross-sells ul.products li.product:nth-child(n+9) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-9 .cross-sells ul.products li.product:nth-child(n+10) { display: none !important; }
            {{WRAPPER}} .cross-sell-limit-mobile-10 .cross-sells ul.products li.product:nth-child(n+11) { display: none !important; }
        }
        </style>';

    }

    protected function cross_sell_settings( $settings = [] ) {

        $layout_class = isset($settings['layout']) ? $settings['layout'] : 'layout-1';
        // Get responsive control values
        $cross_sell_columns = isset($settings['cross_sell_columns']) ? $settings['cross_sell_columns'] : 4;
        $cross_sell_columns_tablet = isset($settings['cross_sell_columns_tablet']) ? $settings['cross_sell_columns_tablet'] : 3;
        $cross_sell_columns_mobile = isset($settings['cross_sell_columns_mobile']) ? $settings['cross_sell_columns_mobile'] : 2;

        $cross_sell_limit = isset($settings['cross_sell_limit']) ? $settings['cross_sell_limit'] : 4;
        $cross_sell_limit_tablet = isset($settings['cross_sell_limit_tablet']) ? $settings['cross_sell_limit_tablet'] : 6;
        $cross_sell_limit_mobile = isset($settings['cross_sell_limit_mobile']) ? $settings['cross_sell_limit_mobile'] : 6;

        // Layout base
        $layout_class = isset($settings['layout']) ? $settings['layout'] : 'layout-1';

        // Add responsive cross-sell columns classes
        $layout_class .= ' cross-sell-desktop-' . $cross_sell_columns;
        $layout_class .= ' cross-sell-tablet-' . $cross_sell_columns_tablet;
        $layout_class .= ' cross-sell-mobile-' . $cross_sell_columns_mobile;

        // Add responsive cross-sell limit classes
        $layout_class .= ' cross-sell-limit-desktop-' . $cross_sell_limit;
        $layout_class .= ' cross-sell-limit-tablet-' . $cross_sell_limit_tablet;
        $layout_class .= ' cross-sell-limit-mobile-' . $cross_sell_limit_mobile;

        // Change the Cross-Sell heading using Elementor control value
        add_filter( 'woocommerce_product_cross_sells_products_heading', function() use ( $settings ) {
            return ! empty( $settings['cross_sell_title'] ) 
                ? $settings['cross_sell_title'] 
                : __( 'You may be interested in&hellip;', 'solace-extra' );
        });        

        // Dynamically set the number of columns for Cross-Sells based on Elementor control
        add_filter('woocommerce_cross_sells_columns', function($columns) use ($cross_sell_columns) {
            return $cross_sell_columns;
        });

        // Dynamically set the number of cross-sell products to display based on Elementor control
        add_filter('woocommerce_cross_sells_total', function($total) {
            return 10;
        });

         // Add show product image class if disabled
        if (isset($settings['show_product_image']) && $settings['show_product_image'] !== 'yes') {
            $layout_class .= ' hide-product-image';
        }

        return $layout_class;
    }

    protected function renderx() {
        $settings = $this->get_settings_for_display();        
        $this->cross_sell_settings($settings);
        $layout_class = isset($settings['layout']) ? $settings['layout'] : 'layout-1';

        add_filter( 'gettext', function( $translated_text, $text, $domain ) use ( $settings ) {
            if ( 'woocommerce' === $domain && 'Cart totals' === $text ) {
                return ! empty( $settings['cart_totals_title'] )
                    ? $settings['cart_totals_title']
                    : $text;
            }
            return $translated_text;
        }, 10, 3 );         

        echo '<div class="solace-extra-box-woocommerce-cart ' . esc_attr($layout_class) . '">';
        $this->style_all_layout();
        switch ($layout_class) {
            case 'layout-1':
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $this->solace_extra_render_cart_layout_1_html($settings);
                break;
            case 'layout-2':
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $this->solace_extra_render_cart_layout_2_html($settings);
                break;
            case 'layout-3':
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $this->solace_extra_render_cart_layout_3_html($settings);
                break;
        }
        echo '</div>';
    }

}

