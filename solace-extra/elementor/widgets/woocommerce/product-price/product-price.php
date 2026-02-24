<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class Solace_Extra_Product_Price extends Widget_Base {

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

		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __( 'Price', 'solace-extra' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => __( 'Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-product-price' => 'color: {{VALUE}}',
				],
                'default' => 'color: var(--wl-gray);'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				// 'selector' => '{{WRAPPER}} .solace-product-price',
				// 'selector' => '{{WRAPPER}} .solace-product-price .woocommerce-Price-amount.amount, {{WRAPPER}} .solace-product-price .amount',
				'selector' => '{{WRAPPER}} .solace-product-price .solace-price-amount',

				// 'selector' => '{{WRAPPER}} .solace-product-price, {{WRAPPER}} .solace-product-price *', 

				'fields_options' 	=> [
					'typography' 	=> [ 'default' => 'yes' ],
					'font_size' 	=> [ 'default' => [ 'size' => 16 ] ],
				    'font_family' 	=> [ 'default' => 'Montserrat' ],
				    'font_weight' 	=> [ 'default' => 500 ],
				],
			]
		);

		$this->add_control(
			'sale_heading',
			[
				'label' => __( 'Sale Price', 'solace-extra' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sale_price_color',
			[
				'label' => __( 'Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-product-price ins' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sale_price_typography',
				'selector' => '{{WRAPPER}} .solace-product-price ins .amount',
				'fields_options' 	=> [
					'typography' 	=> [ 'default' => 'yes' ],
					'font_size' 	=> [ 'default' => [ 'size' => 16 ] ],
				    'font_family' 	=> [ 'default' => 'Montserrat' ],
				    'font_weight' 	=> [ 'default' => 500 ],
				],
			]
		);

		$this->add_responsive_control(
			'sale_price_spacing',
			[
				'label' => __( 'Spacing', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}}:not(.solace-extra-product-price-block-yes) del' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}}:not(.solace-extra-product-price-block-yes) del' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.solace-extra-product-price-block-yes del' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- WooCommerce template convention
		global $product;
		
		if ( ! function_exists( 'wc_get_product' ) ) return;

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$product = wc_get_product();

		$settings = $this->get_settings_for_display();

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- WooCommerce template convention
		$product = solace_get_preview_product();

		$checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
			return;
		}

		$product_id    = $product->get_id();
    
    	// Get the product price HTML
		$checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			$product_price_html = $checkempty;
			// $product_price_html = $product->get_price_html();
		} else {
			// $product_title = $product->get_title();
			$product_price_html = $product->get_price_html();
		}

		
		?>

		<div class="solace-product-price">
			<div class="solace-price-amount">
				<?php 
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $product_price_html; ?>
			</div>
		</div>
    <?php
	}
}

