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

		$this->start_controls_section(
			'section_quantity_style',
			[
				'label' => __( 'Quantity', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'quantity_text_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-add-to-cart .quantity input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'quantity_background',
				'label' => esc_html__( 'Background', 'solace-extra' ),
				'types' => [ 'classic', 'gradient' ],
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .solace-add-to-cart .quantity input',
			]
		);		

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

	


	protected function render() {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wc-add-to-cart-variation' );
		$this->solace_product_addtocart_style();

		$settings = $this->get_settings_for_display();
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
