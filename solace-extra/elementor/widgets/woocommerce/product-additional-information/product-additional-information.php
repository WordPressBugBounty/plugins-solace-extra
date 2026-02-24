<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

use Elementor\Group_Control_Typography;

class Solace_Extra_Product_Additional_Information extends Widget_Base {

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

	protected function register_controls() {

		// === Section: Heading Display ===
		$this->start_controls_section(
			'payment_section_title',
			[
				'label' => __( 'Heading', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_heading',
			[
				'label'        => __( 'Show Heading', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'solace-extra' ),
				'label_off'    => __( 'Hide', 'solace-extra' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		// === Section: Heading Style ===
		$this->start_controls_section(
			'section_additional_info_style',
			[
				'label' => __( 'Heading', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_heading' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => __( 'Color', 'solace-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-product-additional-information h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_heading' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'heading_typography',
				'label'          => __( 'Heading Typography', 'solace-extra' ),
				'selector'       => '{{WRAPPER}} .solace-product-additional-information h2',
				'fields_options' => [
					'typography'   => [ 'default' => 'yes' ],
					'font_family'  => [ 'default' => 'Montserrat' ],
					'font_weight'  => [ 'default' => 500 ],
				],
				'condition' => [
					'show_heading' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_margin',
			[
				'label' => __( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-product-additional-information .woocommerce-product-details__title' => 
						'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_padding',
			[
				'label' => __( 'Padding', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .solace-product-additional-information .woocommerce-product-details__title' => 
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// === Section: Content Style ===
		$this->start_controls_section(
			'section_additional_info_content',
			[
				'label' => __( 'Content', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Color', 'solace-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-product-additional-information .shop_attributes' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'content_typography',
				'label'          => __( 'Content Typography', 'solace-extra' ),
				'selector'       => '{{WRAPPER}} .solace-product-additional-information .shop_attributes tr th,
									{{WRAPPER}} .solace-product-additional-information .shop_attributes tr td',
				'fields_options' => [
					'typography'   => [ 'default' => 'yes' ],
					'font_family'  => [ 'default' => 'Montserrat' ],
					'font_weight'  => [ 'default' => 500 ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} .solace-product-additional-information td, {{WRAPPER}} .solace-product-additional-information th',
			]
		);

		$this->add_control(
			'content_margin',
			[
				'label' => __( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .solace-product-additional-information td, {{WRAPPER}} .solace-product-additional-information th' => 
						'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-product-additional-information td, {{WRAPPER}} .solace-product-additional-information th' => 
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$product = solace_get_preview_product();
		$checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			// echo '<div class="solace-container"><div class="solace-empty-product">' . esc_html( $checkempty ) . '</div></div>';
			echo '<div class="solace-product-additional-information"><h2 class="woocommerce-product-details__title">Additional Information</h2><table class="shop_attributes"><tbody><tr><th>'.esc_html( $checkempty ). '</th></tr></tbody></table></div>';
			return;
		}


		$attributes = $product->get_attributes();

		if ( empty( $attributes ) ) {
			return;
		}

		$show_heading = isset( $settings['show_heading'] ) && $settings['show_heading'] === 'yes';

		echo '<div class="solace-product-additional-information">';

		if ( $show_heading ) {
			echo '<h2 class="woocommerce-product-details__title">' . esc_html__( 'Additional Information', 'solace-extra' ) . '</h2>';
		}

		echo '<table class="shop_attributes">';

		foreach ( $attributes as $attribute ) {
			// Skip if not visible
			if ( ! $attribute->get_visible() ) {
				continue;
			}

			$label = wc_attribute_label( $attribute->get_name() );

			echo '<tr>';
			echo '<th>' . esc_html( $label ) . '</th>';
			echo '<td>';

			if ( $attribute->is_taxonomy() ) {
				$values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), [
					'fields' => 'names',
				] );
				echo esc_html( implode( ', ', $values ) );
			} else {
				echo esc_html( $attribute->get_options() ? implode( ', ', $attribute->get_options() ) : '' );
			}

			echo '</td>';
			echo '</tr>';
		}

		echo '</table>';
		echo '</div>';

		?>
		<style>
			.solace-product-additional-information td {
				border:none;
			}
		</style>
		<?php
	}


}

