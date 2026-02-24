<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Solace_Extra_Product_Breadcrumbs extends Widget_Base {

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
		 * Breadcrumbs
		 */
		$this->start_controls_section(
			'section_content_Breadcrumbs',
			[
				'label' => __( 'Breadcrumbs', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'breadcrumbs_align',
			[
				'label' 		=> __( 'Alignment', 'solace-extra' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'solace-extra' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'solace-extra' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'solace-extra' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' 		=> 'left',
				'toggle' 		=> true,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'breadcrump_indicator_show_hide',
			[
				'label'         => __( 'Use Custom Separator', 'solace-extra' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'solace-extra' ),
				'label_off'     => __( 'Hide', 'solace-extra' ),
				'return_value'  => 'yes',
				'default'       => 'no',
			]
		);

        $this->add_control(
			'breadcrump_indicator',
			[
				'label' => __( 'Select Separator Icon', 'solace-extra' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'eicon-angle-right',
					'library' => 'solid',
				],
				'condition' => [
                    'breadcrump_indicator_show_hide' => 'yes'
                ],
			]
		);

		$this->end_controls_section();

		/**
		 * Breadcrumbs
		 */
		$this->start_controls_section(
			'_breadcrumbs',
			[
				'label' 	=> __( 'Breadcrumbs Color', 'solace-extra' ),
				'tab'   	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb a,{{WRAPPER}} .woocommerce-breadcrumb' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'breadcrumbs_typographyrs',
				'label' 	=> __( 'Typography', 'solace-extra' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' 	=> '{{WRAPPER}} .woocommerce-breadcrumb',
				'fields_options' 	=> [
					'typography' 	=> [ 'default' => 'yes' ],
				    'font_family' 	=> [ 'default' => 'Montserrat' ],
				],
			]
		);

		$this->add_responsive_control(
			'breadcrumb_separator_horizontal_spacing',
			[
				'label' => __( 'Separator Spacing', 'solace-extra' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb i' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'breadcrumbs_padding',
			[
				'label' 		=> __( 'Padding', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}}.woocommerce-breadcrumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before',
			]
		);

		$this->add_responsive_control(
			'breadcrumbs_margin',
			[
				'label' 		=> __( 'Margin', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'default'		=> [
					'unit' => 'px', 
					'top' => 0, 
					'left' => 0, 
					'bottom' => 0, 
					'right' => 0, 
					'isLinked' => true, 
				],
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		
		$this->end_controls_section();
	}


	public function solace_breadcrumb_empty() {
		echo '<div class="sol-bc">
			<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">
				Home
				<span class="nv-breadcrumb-delimiter">\\</span>
				Products
				<span class="nv-breadcrumb-delimiter">\\</span>'
				. esc_html__( 'Sorry, no products are available', 'solace-extra' ) .
			'</nav>
		</div>';
	}


	protected function render() {

		$settings = $this->get_settings_for_display();

		$product = solace_get_preview_product();
		
		$checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			// echo '<div class="solace-container"><div class="solace-empty-product">' . esc_html( $checkempty ) . '</div></div>';
			$this->solace_breadcrumb_empty();
			return;
		}

		$product_id    = $product->get_id();
		$product_title = $product->get_title();

		if ( 'yes' === $settings['breadcrump_indicator_show_hide'] ) {
			$breadcrump_indicator = $settings['breadcrump_indicator'];

			add_filter( 'woocommerce_breadcrumb_defaults', function( $defaults ) use ( $breadcrump_indicator ) {
				$defaults['delimiter'] = ' <i class="' . esc_attr( $breadcrump_indicator['value'] ) . '"></i> ';
				return $defaults;
			} );
		}

		global $post;
		$original_post = $post;

		$post = get_post( $product_id );
		setup_postdata( $post );

		?>
		<div class="sol-bc">
			<?php 
			if ( function_exists( 'woocommerce_breadcrumb' ) ) {
				woocommerce_breadcrumb();
			}
			?>
		</div>
		<?php

		wp_reset_postdata();
		$post = $original_post;

		do_action( 'solace_extra_after_main_content', $this );
	}

}
