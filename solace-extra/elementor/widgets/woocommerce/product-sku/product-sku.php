<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use SolaceExtra\App\Controls\Group_Control_Gradient_Text;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Solace_Extra_Product_Sku extends Widget_Base {

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
			'_sectio_sku',
			[
				'label' 		=> __( 'Content', 'solace-extra' ),
				'tab' 			=> Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'sku_label',
            [
                'label' 		=> __( 'Label', 'solace-extra' ),
                'type' 			=> Controls_Manager::TEXT,
                'default' 		=> 'SKU: ',
				'label_block' 	=> true,
            ]
        );

        $this->end_controls_section();

        /**
		 * Product sku label Style
		 */
		$this->start_controls_section(
			'section_style_sku_lable',
			[
				'label' => __( 'Label', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'sku_label_background',
				'label' => __( 'Background', 'solace-extra' ),
				'types' => [ 'classic', 'gradient' ],
				'separator' => 'after',
				'selector' => '{{WRAPPER}} .solace-product-sku .sku-label',
			]
		);

        $this->add_control(
        	'sku_label_color',
        	[
        		'label' => __( 'Text Color', 'solace-extra' ),
        		'type' => Controls_Manager::COLOR,
        		'separator' => 'before',
        		'default' => '#000',
        		'selectors' => [
        			'{{WRAPPER}} .solace-product-sku .sku-label' => 'color: {{VALUE}}',
        		],
        	]
        );

		// $this->add_group_control(
		// 	Group_Control_Typography::get_type(),
		// 	[
		// 		'name' 		=> 'sku_lable_typography',
		// 		'label' 	=> __( 'Typography', 'solace-extra' ),
		// 		'global' => [
		// 			'default' => Global_Typography::TYPOGRAPHY_TEXT,
		// 		],
		// 		'selector' 	=> '{{WRAPPER}} .solace-product-sku .sku-label',
		// 		'fields_options' 	=> [
		// 			'typography' 	=> [ 'default' => 'yes' ],
		// 		    'font_family' 	=> [ 'default' => 'Montserrat' ],
		// 		    'font_weight' 	=> [ 'default' => 500 ],
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'sku_lable_padding',
			[
				'label' 		=> __( 'Padding', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-sku .sku-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before',
			]
		);

		$this->add_responsive_control(
			'sku_lable_margin',
			[
				'label' 		=> __( 'Margin', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-sku .sku-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); 

		/**
		 * Product sku Style
		 */
		$this->start_controls_section(
			'section_style_sku',
			[
				'label' => __( 'SKU', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'sku_background',
				'label' => __( 'Background', 'solace-extra' ),
				'types' => [ 'classic', 'gradient' ],
				'separator' => 'after',
				'selector' => '{{WRAPPER}} .solace-product-sku .sku',
			]
		);

        $this->add_control(
        	'sku_color',
        	[
        		'label' => __( 'Text Color', 'solace-extra' ),
        		'type' => Controls_Manager::COLOR,
        		'separator' => 'before',
        		'default' => '#551FE8',
        		'selectors' => [
        			'{{WRAPPER}} .solace-product-sku .sku' => 'color: {{VALUE}}',
        		],
        	]
        );

		// $this->add_group_control(
		// 	Group_Control_Typography::get_type(),
		// 	[
		// 		'name' 		=> 'sku_typography',
		// 		'label' 	=> __( 'Typography', 'solace-extra' ),
		// 		'global' => [
		// 			'default' => Global_Typography::TYPOGRAPHY_TEXT,
		// 		],
		// 		'selector' 	=> '{{WRAPPER}} .solace-product-sku .sku',
		// 		'fields_options' 	=> [
		// 			'typography' 	=> [ 'default' => 'yes' ],
		// 		    'font_family' 	=> [ 'default' => 'Montserrat' ],
		// 		    'font_weight' 	=> [ 'default' => 400 ],
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'sku_padding',
			[
				'label' 		=> __( 'Padding', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-sku .sku' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before',
			]
		);

		$this->add_responsive_control(
			'sku_margin',
			[
				'label' 		=> __( 'Margin', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-sku .sku' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$product = solace_get_preview_product();

		$checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
			return;
		}

		$product_id = $product->get_id();
		$sku        = $product->get_sku(); 

		$settings = $this->get_settings_for_display();
		?>

		<div class="solace-product-sku">
			<?php if ( ! empty( $sku ) ) : ?>
				<span class="sku-label"><?php esc_html_e( 'SKU:', 'solace-extra' ); ?></span>
				<span class="sku-value sku"><?php echo esc_html( $sku ); ?></span>
			<?php else : ?>
				<span class="sku-missing"><?php esc_html_e( 'No SKU available', 'solace-extra' ); ?></span>
			<?php endif; ?>
		</div>

		<?php
	}


}

