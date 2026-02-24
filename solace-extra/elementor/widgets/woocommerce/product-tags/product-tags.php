<?php

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Codexpert\CoDesigner\App\Controls\Group_Control_Gradient_Text;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Solace_Extra_Product_Tags extends Widget_Base {

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
			'_sectio_tag',
			[
				'label' 		=> __( 'Content', 'solace-extra' ),
				'tab' 			=> Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'tag_label',
            [
                'label' 		=> __( 'Label', 'solace-extra' ),
                'type' 			=> Controls_Manager::TEXT,
                'default' 		=> 'Tag: ',                
				'label_block' 	=> true,
            ]
        );

        $this->end_controls_section();

        /**
		 * Product sku label Style
		 */
		$this->start_controls_section(
			'section_style_tag_lable',
			[
				'label' => __( 'Label', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tag_label_color',
			[
				'label' => __( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .solace-product-tags .product-tag-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'tag_lable_padding',
			[
				'label' 		=> __( 'Padding', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-tags .product-tag-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before',
			]
		);

		$this->add_responsive_control(
			'tag_lable_margin',
			[
				'label' 		=> __( 'Margin', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-tags .product-tag-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); 

		/**
		 * Product categories Style
		 */
		$this->start_controls_section(
			'section_style_tag',
			[
				'label' => __( 'Tags', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tag_color',
			[
				'label' => __( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'default' => '#E9345F',
				'selectors' => [
					'{{WRAPPER}} .solace-product-tags a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'tag_padding',
			[
				'label' 		=> __( 'Padding', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-tags a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before',
			]
		);

		$this->add_responsive_control(
			'tag_margin',
			[
				'label' 		=> __( 'Margin', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-tags a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
			return;
		}

		$product_id    = $product->get_id();
		$product_title = $product->get_title(); 
		$label = isset( $settings['tag_label'] ) ? $settings['tag_label'] : 'Tags: ';
		?>

		<div class="solace-product-tags">
			<?php
			$tags = get_the_terms( $product_id, 'product_tag' );

			if ( $tags && ! is_wp_error( $tags ) ) :
				echo '<span class="product-tag-label">' . esc_html( $label ) . '</span>';

				$tag_links = [];

				foreach ( $tags as $tag ) {
					$tag_link = get_term_link( $tag );
					if ( is_wp_error( $tag_link ) ) {
						continue;
					}
					$tag_links[] = '<a href="' . esc_url( $tag_link ) . '">' . esc_html( $tag->name ) . '</a>';
				}

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo implode( ', ', $tag_links );

			else :
				echo '<span class="no-tags">' . esc_html__( 'No tags found.', 'solace-extra' ) . '</span>';
			endif;
			?>
		</div>

		<?php
	}



}

