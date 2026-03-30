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

class Solace_Extra_WooCommerce_ProductAddToCart extends Widget_Base {

	public function get_name() {
		return 'product-addtocart';
	}

	public function get_title() {
		return __( 'Product Add to Cart', 'solace-extra' );
	}

	public function get_icon() {
		return 'eicon-cart solace-extra';
	}

	public function get_categories() {
        return ['solace-extra-single'];
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
		$selector = '{{WRAPPER}} .solace-add-to-cart button';

		$selector_hover = '{{WRAPPER}} .solace-add-to-cart button:hover'; 

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

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'solace-extra' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => __( 'Button Text', 'solace-extra' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Add to Cart', 'solace-extra' ),
				'placeholder' => __( 'Add to Cart', 'solace-extra' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_variant_label_style',
			[
				'label' => __( 'Variant', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'variant_label_color',
			[
				'label'     => __( 'Text Color', 'solace-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .variations td.label label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variant_label_typography',
				'label'    => __( 'Typography', 'solace-extra' ),
				'selector' => '{{WRAPPER}} .variations td.label label',
			]
		);

		$this->end_controls_section();

		$field_selector = '{{WRAPPER}} .solace-add-to-cart input,{{WRAPPER}} .cart input';
		$field_selector_focus = '{{WRAPPER}} .solace-add-to-cart input:focus, {{WRAPPER}} .cart input:focus';
        

		$this->start_controls_section(
			'section_quantity_style',
			[
				'label' => __( 'Quantity', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'quantity_typography',
				'selector' => $field_selector,
			]
		);

		// $this->add_control(
		// 	'quantity_text_color',
		// 	[
		// 		'label' => esc_html__( 'Text Color', 'solace-extra' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .solace-add-to-cart .quantity input' => 'color: {{VALUE}};',
		// 		],
		// 	]
		// );

		// $this->add_group_control(
		// 	Group_Control_Background::get_type(),
		// 	[
		// 		'name' => 'quantity_background',
		// 		'label' => esc_html__( 'Background', 'solace-extra' ),
		// 		'types' => [ 'classic', 'gradient' ],
		// 		// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
		// 		'exclude' => [ 'image' ],
		// 		'selector' => '{{WRAPPER}} .solace-add-to-cart .quantity input',
		// 	]
		// );		

		$this->add_responsive_control(
			'quantity_width',
			[
				'label' => esc_html__( 'Input Width', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-add-to-cart .quantity input' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .cart .quantity input' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'gap_between_elements',
			[
				'label' => esc_html__( 'Spacing', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'default' => [
					'size' => 5,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-add-to-cart.simple form' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .solace-add-to-cart .woocommerce-variation-add-to-cart' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .cart' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'solace_fields_tabs' );

        $this->start_controls_tab(
            'solace_field_tab',
            [
                'label' => __( 'Normal', 'solace-extra' ),
            ]
        );

		

        // $this->add_group_control(
        //     \Elementor\Group_Control_Typography::get_type(),
        //     [
        //         'name' => 'solace_field_typography',
        //         'selector' => $field_selector,
        //     ]
        // );

        $this->add_control(
            'solace_field_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $field_selector => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'solace_field_bg_color',
            [
                'label' => __( 'Background Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $field_selector => 'background-color: {{VALUE}}',
                ],
            ]
        );



        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'solace_field_border',
                'label'    => __( 'Border', 'solace-extra' ),
                'selector' => $field_selector,
            ]
        );

        $this->add_control(
            'solace_field_border_radius',
            [
                'label'      => __( 'Border Radius', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                   $field_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'solace_field_box_shadow',
                'selector' => $field_selector,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'solace_field_focus_tab',
            [
                'label' => __( 'Focus', 'solace-extra' ),
            ]
        );
        

        $this->add_control(
            'solace_field_focus_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $field_selector_focus => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'solace_field_focus_bg_color',
            [
                'label' => __( 'Background Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $field_selector_focus => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'solace_field_focus_border',
                'label'    => __( 'Border', 'solace-extra' ),
                'selector' => $field_selector_focus,
            ]
        );

        $this->add_control(
            'solace_field_focus_border_radius',
            [
                'label'      => __( 'Border Radius', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    $field_selector_focus => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'solace_field_focus_box_shadow',
                'selector' => $field_selector_focus,
            ]
        );

        $this->add_control(
            'solace_field_transition',
            [
                'label' => __( 'Transition Duration (ms)', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                        'step' => 100,
                    ],
                ],
                'selectors' => [
                   $field_selector_focus => 'transition: all {{SIZE}}ms ease-in-out;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->add_control(
            'field_separator',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

		$this->add_responsive_control(
			'field_padding',
			[
				'label' => __( 'Padding', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					$field_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		

		$this->add_responsive_control(
			'field_margin',
			[
				'label' => __( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false, 
                ],
				'selectors' => [
					$field_selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Button', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->register_button_style_controls();

		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Width', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 400 ],
				],
				'default' => [
					'size' => 140,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-add-to-cart button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function solace_product_addtocart_empty() {
		echo '<form class="cart empty">
			<div class="quantity">
				<label class="screen-reader-text" for="quantity_68777c377f613">Quantity</label>
				<input type="number" id="quantity_68777c377f613" class="input-text qty text" name="quantity" value="1" aria-label="Product quantity" min="1" max="" step="1" placeholder="" inputmode="numeric" autocomplete="off">
			</div>
			<button type="submit" name="add-to-cart" value="0" class="single_add_to_cart_button alt elementor-button solace-extra-button" disabled>'
				. esc_html__( 'Sorry, there are no products available.', 'solace-extra' ) .
			'</button>
		</form>';
	}

	public function solace_product_addtocart_style() {
		echo '<style>		
			.cart.empty {
    			display: flex;
			}
			.cart.empty input {
    			width: 50px;
			}
			.woocommerce-variation-add-to-cart {
				display: flex;
			}
			.woocommerce.single .woocommerce-variation-add-to-cart .single_variation_wrap .woocommerce-variation-add-to-cart {
				display: flex;
				flex-wrap: nowrap;
			}
			.woocommerce.single .woocommerce-variation-add-to-cart {
				display: flex;
				flex-wrap: nowrap;
			}
			.single_variation_wrap {
				width: 100%;
			}
			.solace-add-to-cart.simple form {
				display: flex;
			}

			.solace-add-to-cart .quantity input {
				height: 48px;
			}

			.shop-container .woocommerce-notices-wrapper {
				width: 100%;
			}
			.solace-add-to-cart table, 
			.solace-add-to-cart td {
				border: none;
			}
			.solace-add-to-cart select {
				appearance: none;
				-webkit-appearance: none;
				-moz-appearance: none;
				background: url("data:image/svg+xml,%3Csvg fill=\'black\' height=\'12\' viewBox=\'0 0 24 24\' width=\'12\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M7 10l5 5 5-5z\'/%3E%3C/svg%3E") no-repeat right 1em center;
				background-color: #fff;
				background-size: 12px;
				padding-right: 2.5em;
				border: 1px solid #ccc;
				border-radius: 4px;
				min-height: 40px;
				font-size: 14px;
			}
			.solace-add-to-cart .woocommerce-variation-price {
				margin-bottom: 20px;
				font-size: 18px;
				font-weight: 700;
				margin-top: 20px;
			}
			.solace-add-to-cart.simple form.simple_add_to_cart_form {
				display: flex;
				
			}
			.solace-add-to-cart.simple form.simple_add_to_cart_form {
				background-color: var(--sol-color-button-hover);
			}
			
			.solace-add-to-cart button {
				width: 100%;
			}
			
			.solace-add-to-cart.simple form {
				display: flex;
				align-items: center;
			}
			
			.solace-add-to-cart .woocommerce-variation-add-to-cart {
				display: flex;
				align-items: center;
			}
			.solace-add-to-cart .woocommerce-error {
				display: none;
			}
		</style>';
	}

	private function get_field_selectors( $widget_id, $is_focus = false ) {
        // error_log('masuk: get_field_selectors');
		$prefix = '.elementor-element-' . esc_attr( $widget_id ) . ' .cart';        

        $selectors = [
			$prefix . ' input',
			$prefix . ' .quantity input', 
		];

        if ( $is_focus ) {
            $selectors = array_map( function( $selector ) {
                return $selector . ':focus';
            }, $selectors );
        }

        return implode( ',', $selectors );
    }

    private function generate_typography_style( $settings, $prefix ) {
        $css = '';
        $family = $settings[ $prefix . 'typography_font_family' ] ?? '';
        if ( $family ) $css .= "font-family: \"{$family}\", Sans-serif;";

        $size = $settings[ $prefix . 'typography_font_size' ] ?? '';
        if ( is_array( $size ) && ! empty( $size['size'] ) ) $css .= "font-size: {$size['size']}{$size['unit']};";

        $weight = $settings[ $prefix . 'typography_font_weight' ] ?? '';
        if ( $weight ) $css .= "font-weight: {$weight};";

        $lh = $settings[ $prefix . 'typography_line_height' ] ?? '';
        if ( is_array( $lh ) && ! empty( $lh['size'] ) ) $css .= "line-height: {$lh['size']}{$lh['unit']};";

        return $css;
    }

    private function generate_field_style( $settings, $state = 'normal' ) {
        $css = '';
        $prefix = ( $state === 'focus' ) ? 'form_field_focus_' : 'form_field_';

        // Color
        $text_color = $settings[ $prefix . 'text_color' ] ?? '';
        $bg_color   = $settings[ $prefix . 'background_color' ] ?? '';
        if ( $text_color ) $css .= "color: {$text_color};";
        if ( $bg_color )   $css .= "background-color: {$bg_color};";

        if ( $state === 'normal' ) $css .= $this->generate_typography_style( $settings, 'form_field_' );

        // Border
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

        // Radius & Padding
        foreach ( ['border_radius' => 'border-radius', 'padding' => 'padding'] as $key => $prop ) {
            $val = $settings[ $prefix . $key ] ?? '';
            if ( is_array( $val ) && ! empty( $val['top'] ) ) {
                $unit = $val['unit'] ?? 'px';
                $css .= "{$prop}: {$val['top']}{$unit} {$val['right']}{$unit} {$val['bottom']}{$unit} {$val['left']}{$unit};";
            }
        }

        // Shadow
        $shadow = $settings[ $prefix . 'box_shadow_box_shadow' ] ?? '';
        if ( is_array( $shadow ) && ! empty( $shadow['color'] ) ) {
            $ins = ( isset( $shadow['outline'] ) && $shadow['outline'] === 'inset' ) ? 'inset' : '';
            $css .= "box-shadow: {$shadow['horizontal']}px {$shadow['vertical']}px {$shadow['blur']}px {$shadow['spread']}px {$shadow['color']} {$ins};";
        }

        // Transition (Focus)
        if ( $state === 'focus' ) {
            $transition = $settings['form_field_focus_transition_duration'] ?? '';
            if ( is_array( $transition ) && ! empty( $transition['size'] ) ) {
                $css .= "transition: all {$transition['size']}{$transition['unit']} ease-in-out;";
            }
        }

        return $css;
    }


	protected function render() {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wc-add-to-cart-variation' );
		$this->solace_product_addtocart_style();

		$settings = $this->get_settings_for_display();

		$kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();

		if ( ! $kit_id ) {
            // error_log('DEBUG: Kit ID tidak ditemukan');
            // return;
        }

        if ( $kit_id ) {
            $kit_settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
        }

        $kit_settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
        if ( ! is_array( $kit_settings ) ) {
            // error_log('DEBUG: Kit Settings bukan array atau kosong');
            // return;
        }

        static $style_printed = [];
        $widget_id = $this->get_id();
        if ( isset( $style_printed[ $widget_id ] ) ) {
            // error_log('DEBUG: Style untuk widget ' . $widget_id . ' sudah pernah diprint sebelumnya');
            // return;
        }

        // error_log('DEBUG: Mengecek normal_styles...');
        $normal_styles = $this->generate_field_style( $kit_settings, 'normal' );
        
        // error_log('DEBUG: Mengecek focus_styles...');
        $focus_styles  = $this->generate_field_style( $kit_settings, 'focus' );
        
        // error_log('DEBUG: Hasil Normal Style: ' . $normal_styles);

        if ( empty( $normal_styles ) && empty( $focus_styles ) && empty( $label_styles ) ) {
            // error_log('DEBUG: Semua styles kosong, membatalkan render <style>');
            // return;
        }

        // error_log('DEBUG: Mencetak <style> ke frontend untuk widget: ' . $widget_id);

        echo '<style>';

        if ( ! empty( $normal_styles ) ) {
            $field_selectors = $this->get_field_selectors( $widget_id, false );
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $field_selectors . ' { ' . $normal_styles . ' }';
            // error_log('DEBUG: Field selectors printed: ' . $field_selectors);
        }
        if ( ! empty( $focus_styles ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $this->get_field_selectors( $widget_id, true ) . ' { ' . $focus_styles . ' }';
        }
        echo '</style>';


        $style_printed[ $widget_id ] = true;


		$product  = function_exists( 'solace_get_preview_product' ) ? solace_get_preview_product() : wc_get_product();
		// Set global $product to avoid undefined variable warnings in WooCommerce functions (WooCommerce template convention).
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		global $product;
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$product = $product ?: (function_exists( 'solace_get_preview_product' ) ? solace_get_preview_product() : wc_get_product());
		
		$hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';
		$checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			// echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
			$this->solace_product_addtocart_empty();
			return;
		}

		$product_id   = $product->get_id();
		$product_type = $product->get_type();

		$button_text = esc_html( $settings['button_text'] ?? __( 'Add to cart', 'solace-extra' ) );
		?>

		<div class="solace-add-to-cart <?php echo esc_attr( $product_type ); ?>">
			<?php if ( $product instanceof WC_Product_Variable ) : ?>
				<?php
					$available_variations = $product->get_available_variations();
					$attributes            = $product->get_variation_attributes();
					$default_attributes    = $product->get_default_attributes();
					$attribute_keys        = array_keys( $attributes );
				?>

				<div class="solace-add-to-cart variable">
					<form class="variations_form cart" method="post" enctype="multipart/form-data"
						action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
						data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
						data-product_variations="<?php echo esc_attr( wp_json_encode( $available_variations ) ); ?>">

						<?php do_action( 'woocommerce_before_variations_form' ); ?>

						<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
							<p class="stock out-of-stock"><?php esc_html_e( 'This product is currently out of stock and unavailable.', 'solace-extra' ); ?></p>
						<?php else : ?>

							<table class="variations" cellspacing="0">
								<tbody>
									<?php foreach ( $attributes as $attribute_name => $options ) : ?>
										<tr>
											<td class="label">
												<label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>">
													<?php 
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													echo wc_attribute_label( $attribute_name ); 
													?>
												</label>
											</td>
											<td class="value">
												<?php
												$attribute_key = 'attribute_' . sanitize_title( $attribute_name );

												// phpcs:ignore WordPress.Security.NonceVerification.Recommended
												if ( isset( $_REQUEST[ $attribute_key ] ) ) {
													// phpcs:ignore WordPress.Security.NonceVerification.Recommended
													$raw_value = isset( $_REQUEST[ $attribute_key ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $attribute_key ] ) )
													: '';
													$selected  = wc_clean( $raw_value );
												} else {
													$selected = $product->get_variation_default_attribute( $attribute_name );
												}

												wc_dropdown_variation_attribute_options( array(
													'options'   => $options,
													'attribute' => $attribute_name,
													'product'   => $product,
													'selected'  => $selected,
												) );
												?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>

							<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

							<div class="woocommerce-variation single_variation"></div>
							<div class="woocommerce-variation-add-to-cart variations_button">
								<?php woocommerce_quantity_input( array( 'input_value' => 1 ) ); ?>
								<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
								<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
								<input type="hidden" name="variation_id" class="variation_id" value="0" />
								<button type="submit" class="elementor-button solace-extra-button single_add_to_cart_button button alt <?php echo esc_attr( $hover_class );?>">
									<?php echo esc_html( $button_text ); ?>
								</button>
							</div>

							<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
						<?php endif; ?>

						<?php do_action( 'woocommerce_after_variations_form' ); ?>
					</form>
				</div>


			<?php else : ?>
				<!-- Simple product -->
				<div class="solace-add-to-cart simple">
					<form class="cart" method="post" enctype="multipart/form-data">
						<?php
						woocommerce_quantity_input( [ 'input_value' => 1 ] );
						echo sprintf(
							'<input type="hidden" name="add-to-cart" value="%d" />',
							esc_attr( $product->get_id() )
						);
						?>
						<button type="submit" class="elementor-button solace-extra-button single_add_to_cart_button alt <?php echo esc_attr( $hover_class );?>">
							<?php echo esc_html( $button_text ); ?>
						</button>
					</form>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

}
