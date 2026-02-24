<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Codexpert\CoDesigner\Helper;

class Solace_Extra_Product_Stock extends Widget_Base {

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

		/**
		 * Product Title
		 */
		$this->start_controls_section(
			'section_product_stock_content',
			[
				'label' 		=> __( 'Content', 'solace-extra' ),
				'tab' 			=> Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'outofstock_text',
			[
				'label'         => esc_html__( 'Outofstock Text', 'solace-extra' ),
				'type'          => Controls_Manager::TEXT,
				'default'       => esc_html__( 'Out Of Stock', 'solace-extra' ),
			]
        );

		$this->add_control(
			'backorder_text',
			[
				'label'         => esc_html__( 'Backorder Text', 'solace-extra' ),
				'type'          => Controls_Manager::TEXT,
				'default'       => esc_html__( 'Expected availability: %%available_date%%', 'solace-extra' ),
			]
        );

        $this->end_controls_section();

		$this->start_controls_section(
			'section_product_stock_style',
			[
				'label' => __( 'Style', 'solace-extra' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
			);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-product-stock .stock' => 'color: {{VALUE}}',
					'{{WRAPPER}} .solace-product-stock' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- WooCommerce template convention
		global $product;
		$settings  = $this->get_settings_for_display();
		if ( ! is_woocommerce_activated() ) return;

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$product = wc_get_product();
		// $product = solace_get_preview_product();

		if ( ! empty( get_the_ID() ) ) {
			$product_id = solace_extra_sanitize_number( get_the_ID() );
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- WooCommerce template convention
			$product 	= wc_get_product( $product_id );
		}
		

		if ( empty( $product ) && ( solace_extra_is_edit_mode() || solace_extra_is_preview_mode() ) ) {
			$product_id 	= solace_extra_get_product_id();
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- WooCommerce template convention
			$product 		= wc_get_product( $product_id );
			$stock_status 	= $product->get_stock_status();
			$stock_qty 		= $product->get_stock_quantity();
			// $stock_status = $product && $product->get_stock_status() ? $product->get_stock_status() : 'no_product';
			// $stock_qty    = $product && $product->get_stock_quantity() ? $product->get_stock_quantity() : 0;


			if ( $stock_status == 'instock' && is_null( $stock_qty ) ) {
				echo "<div class='solace-product-stock'><span class='stock' >100 in stock</span></div>";
			}
		}

		if ( empty( $product ) ) {
			return;
		}
		$backorder_text 	= $settings['backorder_text'];
		$outofstock_text 	= $settings['outofstock_text'];
		$stock_status 		= $product->get_stock_status();
		if ( $stock_status == 'onbackorder' && $backorder_text ) {
			$available_date = get_post_meta( $product->get_id(), 'cd_backorder_time', true );
			$text =  str_replace(  '%%available_date%%', $available_date, $backorder_text );
		}
		elseif( $stock_status == 'outofstock' && $outofstock_text ){
			$text = $outofstock_text;
		}
		else{
			$text =  wc_get_stock_html( $product );
		}
		echo "<div class='solace-product-stock'>";
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $text;
		echo "</div>";

		do_action( 'solace_extra_after_main_content', $this );
	}

	protected function renderXX() {
		// global $product;
		$settings  = $this->get_settings_for_display();
		if ( ! is_woocommerce_activated() ) return;

		// error_log( '=== RENDER START ===' );

		// $product = wc_get_product();
		$product = solace_get_preview_product();
		// error_log( 'solace_get_preview_product(): ' . print_r( $product, true ) );

		if ( ! empty( get_the_ID() ) ) {
			$product_id = solace_extra_sanitize_number( get_the_ID() );
			// error_log( 'get_the_ID(): ' . get_the_ID() );
			// error_log( 'sanitized product_id: ' . $product_id );
			$product 	= wc_get_product( $product_id );
			// error_log( 'wc_get_product(): ' . print_r( $product, true ) );
		}

		$checkempty = solace_check_empty_product( $product );
		// error_log( 'solace_check_empty_product: ' . print_r( $checkempty, true ) );
		if ( $checkempty ) {
			// error_log( 'Product is empty. Rendering sorry message.' );
			echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
			return;
		}

		if ( empty( $product ) && ( solace_extra_is_edit_mode() || solace_extra_is_preview_mode() ) ) {
			// error_log( 'Product empty, edit/preview mode active.' );
			$product_id 	= solace_extra_get_product_id();
			$product 		= wc_get_product( $product_id );
			// error_log( 'Product in preview mode: ' . print_r( $product, true ) );

			$stock_status = $product && $product->get_stock_status() ? $product->get_stock_status() : 'no_product';
			$stock_qty    = $product && $product->get_stock_quantity() ? $product->get_stock_quantity() : 0;

			// error_log( 'stock_status: ' . $stock_status );
			// error_log( 'stock_qty: ' . $stock_qty );

			if ( $stock_status == 'instock' && is_null( $stock_qty ) ) {
				echo "<div class='solace-product-stock'><span class='stock' >100 in stock</span></div>";
			}
		}

		if ( empty( $product ) ) {
			// error_log( 'Product still empty after checks. RETURNING.' );
			return;
		}

		$backorder_text 	= $settings['backorder_text'];
		$outofstock_text 	= $settings['outofstock_text'];
		$stock_status 		= $product->get_stock_status();

		// error_log( 'Final stock_status: ' . $stock_status );
		// error_log( 'backorder_text: ' . $backorder_text );
		// error_log( 'outofstock_text: ' . $outofstock_text );

		if ( $stock_status == 'onbackorder' && $backorder_text ) {
			$available_date = get_post_meta( $product->get_id(), 'cd_backorder_time', true );
			$text =  str_replace(  '%%available_date%%', $available_date, $backorder_text );
			// error_log( 'Render backorder text: ' . $text );
		}
		elseif( $stock_status == 'outofstock' && $outofstock_text ){
			$text = $outofstock_text;
			// error_log( 'Render outofstock text: ' . $text );
		}
		else{
			$text =  wc_get_stock_html( $product );
			// error_log( 'Render wc_get_stock_html: ' . $text );
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo "<div class='solace-product-stock'>";
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $text;
		echo "</div>";

		do_action( 'solace_extra_after_main_content', $this );

		// error_log( '=== RENDER END ===' );
	}


}