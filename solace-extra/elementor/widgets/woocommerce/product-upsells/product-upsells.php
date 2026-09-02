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

class Solace_Extra_Product_Upsells extends Widget_Base {

	public $id;
	
	public $widget;

	public function __construct( $data = [], $args = null ) {
	    parent::__construct( $data, $args );

		$data = solace_extra_widgets();
		$this->id = strtolower( 'solace-extra-' . str_replace( ' ', '-', $data[ pathinfo( __FILE__, PATHINFO_FILENAME ) ][ 'title' ] ) );
	    $this->widget = $data[ pathinfo(__FILE__, PATHINFO_FILENAME) ];

	}

	public function get_script_depends() {
		return [];
	}

	public function get_style_depends() {
		return [];
	}

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return $this->widget['title'];
	}

	public function get_icon() {
		return $this->widget['icon'];
	}

	public function get_categories() {
		return $this->widget['categories'];
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
		$selector = '{{WRAPPER}}.elementor-widget-solace-extra-product-upsells section.woocommerce li.product .add_to_cart_button';

		$selector_hover = '{{WRAPPER}}.elementor-widget-solace-extra-product-upsells section.woocommerce li.product .add_to_cart_button:hover'; 

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
	 *     An array of values for the category adjustments.
	 *
	 *     @type array  $section_condition  Set of conditions to hide the controls.
	 *     @type string $alignment_default  Default position for the category.
	 *     @type string $alignment_control_prefix_class  Prefix class name for the category position control.
	 *     @type string $content_alignment_default  Default alignment for the category content.
	 * }
	 */
	protected function register_button_category_style_controls( $args = [] ) {
		$selector = '{{WRAPPER}} .upsells.products .product-categories a';
        
		$selector_hover = '{{WRAPPER}} .upsells.products .product-categories a:hover'; 

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
                'label' => __('Settings', 'solace-extra'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading Text', 'solace-extra'),
                'type' => Controls_Manager::TEXT,
                'default' => __('You may also like…', 'solace-extra'),
            ]
        );

        $this->add_control(
            'upsells_heading_tag',
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

        // Tab Style - Layout
        $this->start_controls_section(
            'layout_upsells_style',
            [
                'label' => __( 'Layout', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'upsells_products_item_alignment',
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
                    '{{WRAPPER}} .upsells.products ul.products li.product, {{WRAPPER}} .upsells.products ul.products li.product .woocommerce-loop-product__title' => 'align-items: {{VALUE}};justify-content: {{VALUE}};',
                ],
                'toggle'   => true,
            ]
        );

        $this->end_controls_section();

        // Tab Style - Heading
        $this->start_controls_section(
            'heading_style',
            [
                'label' => __( 'Heading', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'upsells_heading_alignment',
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
                    '{{WRAPPER}} .upsells.products .solace-upsells-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'upsells_heading_typography',
                'selector' => '{{WRAPPER}} .upsells.products .solace-upsells-heading',
            ]
        );

        // Font Color
        $this->add_control(
            'upsells_heading_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .upsells.products .solace-upsells-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Padding (with px & em)
        $this->add_responsive_control(
            'upsells_heading_padding',
            [
                'label' => __('Padding', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .upsells.products .solace-upsells-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin (with px & em)
        $this->add_responsive_control(
            'upsells_heading_margin',
            [
                'label' => __('Margin', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .upsells.products .solace-upsells-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Tab Style - Product Title
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
                'selector' => '{{WRAPPER}} .upsells.products .woocommerce-loop-product__title',
				
            ]
        );

        $this->add_control(
            'product_name_color',
            [
                'label'     => esc_html__('Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .upsells.products .woocommerce-loop-product__title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .upsells.products .woocommerce-loop-product__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .upsells.products .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        // Tab Style - Price
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
                'selector' => '{{WRAPPER}} .upsells.products .price, {{WRAPPER}} .upsells.products .price span',
            ]
        );

        // Price Color
        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__('Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .upsells.products .price, {{WRAPPER}} .upsells.products .price span' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .upsells.products .price, {{WRAPPER}} .upsells.products .price span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .upsells.products .price, {{WRAPPER}} .upsells.products .price span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        // Tab Style - Badge
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

        // Tab Style - Product Categories
        $this->start_controls_section(
			'section_product_categories',
			[
				'label' => esc_html__('Product Categories', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->register_button_category_style_controls();

		$this->end_controls_section();        

        // Tab Style - Button
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

    public function get_style(){
		echo '<style>
            .upsells.products.woocommerce ul.products li.product {
                display: flex;
                flex-direction: column;
            }
            .upsells.products.woocommerce ul.products li.product .woocommerce-loop-product__title {
                display: flex;
                justify-content: end;
            }
            .upsells.products.woocommerce ul.products .price {
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
        $is_cart = function_exists( 'is_cart' ) && is_cart();
        $upsell_ids = [];
        $product_id = 0;

        if ( $product && is_a( $product, 'WC_Product' ) ) {
            // Single product context
            $product_id = $product->get_id();
            $upsell_ids = $product->get_upsell_ids();
        } elseif ( $is_cart && function_exists( 'WC' ) && WC()->cart ) {
            // Cart page context — aggregate upsells from all cart items
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $cart_product = wc_get_product( $cart_item['product_id'] );
                if ( $cart_product ) {
                    $item_upsells = $cart_product->get_upsell_ids();
                    $upsell_ids = array_merge( $upsell_ids, $item_upsells );
                }
            }
            $upsell_ids = array_unique( $upsell_ids );
        }

        // No upsells found — show nothing. On a fresh store with no products
        // yet, render lorem-ipsum placeholder cards so the section isn't blank
        // in the Site Builder editor.
        if ( empty( $upsell_ids ) ) {
            if ( $product && is_a( $product, 'WC_Product' ) && function_exists( 'solace_extra_is_placeholder_product' ) && solace_extra_is_placeholder_product( $product ) ) {
                $heading = $settings['heading'];
                $limit   = intval( $settings['products_per_page'] );
                $tag     = ! empty( $settings['upsells_heading_tag'] ) ? $settings['upsells_heading_tag'] : 'h2';
                $columns = intval( $settings['columns'] );
                $columns = ( $columns >= 2 && $columns <= 6 ) ? $columns : 4;

                if ( function_exists( 'WC' ) ) {
                    wp_enqueue_style( 'woocommerce-general' );
                    wp_enqueue_style( 'woocommerce-layout' );
                    wp_enqueue_style( 'woocommerce-smallscreen' );
                }

                echo '<section class="upsells products woocommerce">';
                if ( ! empty( $heading ) ) {
                    echo sprintf(
                        '<%1$s class="solace-upsells-heading">%2$s</%1$s>',
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

        $checkempty = solace_check_empty_product( $product );
        // Only show empty notice in single-product (non-cart) context when product is invalid
        if ( ! $is_cart && $checkempty ) {
            echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
            return;
        }
    
        // Enqueue WooCommerce default styles
        if (function_exists('WC')) {
            wp_enqueue_style('woocommerce-general');
            wp_enqueue_style('woocommerce-layout');
            wp_enqueue_style('woocommerce-smallscreen');
        }
    
        $heading = $settings['heading'];
        $limit = intval($settings['products_per_page']);
        $columns = intval($settings['columns']);
        $columns = ($columns >= 2 && $columns <= 6) ? $columns : 4;
    
        $args = apply_filters('woocommerce_upsell_display_args', [
            'post_type'           => 'product',
            'ignore_sticky_posts' => 1,
            'no_found_rows'       => 1,
            'posts_per_page'      => $limit,
            'post__in'            => $upsell_ids,
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
            'post__not_in'        => $product_id ? [ $product_id ] : [],
        ]);
    
        $query = new WP_Query($args);
    
        if ($query->have_posts()) {
    
            // Apply WooCommerce column filter for ul.products
            add_filter('loop_shop_columns', function() use ($columns) {
                return $columns;
            });
    
            echo '<section class="upsells products woocommerce">';
    
            $tag = ! empty( $settings['upsells_heading_tag'] ) ? $settings['upsells_heading_tag'] : 'h2';
            if ( ! empty( $heading ) ) {
                echo sprintf(
                    '<%1$s class="solace-upsells-heading">%2$s</%1$s>',
                    esc_attr( $tag ),
                    esc_html( $heading )
                );
            }
    
            woocommerce_product_loop_start();
    
            while ($query->have_posts()) {
                $query->the_post();
                $loop_product = wc_get_product(get_the_ID());

                if (! $loop_product) continue;

                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<li class="product ' . implode(' ', wc_get_product_class('', $loop_product)) . '">';
                
                echo '<a href="' . esc_url(get_permalink()) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                
                // Thumbnail
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo woocommerce_get_product_thumbnail();
                
                // Sale flash
                if ($loop_product->is_on_sale()) {
                    echo '<span class="onsale">' . esc_html__('Sale!', 'solace-extra') . '</span>';
                }

                // Title
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<h4 class="woocommerce-loop-product__title">' . get_the_title() . '</h4>';
                echo '</a>'; 
                $categories = wc_get_product_category_list( $loop_product->get_id(), ' ' );
                if ( $categories ) {
                    echo '<span class="product-categories' . esc_attr( $category_hover_class ) . '">' . wp_kses_post( $categories ) . '</span>';
                }
                
                // Price
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<span class="price">' . $loop_product->get_price_html() . '</span>';

                

                // Add to Cart Button
                echo '<a href="' . esc_url($loop_product->add_to_cart_url()) . '" '
                . 'data-quantity="1" '
                . 'class="elementor-button solace-extra-button product_type_' . esc_attr($loop_product->get_type()) . ' add_to_cart_button ajax_add_to_cart' . esc_attr($hover_class) . '" '
                . 'data-product_id="' . esc_attr($loop_product->get_id()) . '" '
                /* translators: %s: product name */                
                . 'aria-label="' . esc_attr(sprintf(__('Add to cart: %s', 'solace-extra'), $loop_product->get_name())) . '" '
                . 'rel="nofollow">'
                . esc_html__('Add to cart', 'solace-extra') .
                '</a>';

                echo '</li>';
            }
    
            woocommerce_product_loop_end(); 
    
            echo '</section>';
    
            // Clean up filter after rendering
            remove_all_filters('loop_shop_columns');
        }
    
        wp_reset_postdata();
    }

}
