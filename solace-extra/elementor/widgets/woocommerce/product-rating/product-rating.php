<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Solace_Extra_Product_Rating extends Widget_Base {

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
		return ['solace-extra-product-rating'];
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
			'section_product_rating_style',
			[
				'label' => __( 'Style', 'solace-extra' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'star_color',
			[
				'label' => __( 'Star Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-star-rating .star-rating span::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'empty_star_color',
			[
				'label' => __( 'Empty Star Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-star-rating .star-rating::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Label Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-star-rating .review-count' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'product_rating_typography',
				'label' 	=> __( 'Typography', 'solace-extra' ),

				'selector' 	=> '.wl {{WRAPPER}} .wl-product-title,{{WRAPPER}} .custom-star-rating .review-count, {{WRAPPER}} .solace-product-rating .review-count',
				'fields_options' 	=> [
					'typography' 	=> [ 'default' => 'yes' ],
				    'font_family' 	=> [ 'default' => 'Montserrat' ],
				    'font_weight' 	=> [ 'default' => 500 ],
				],
			]
		);

		$this->add_control(
			'star_size',
			[
				'label' => __( 'Star Size', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ,'em'],
				'default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .custom-star-rating .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'px',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .custom-star-rating .review-count' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	public function solace_product_rating_empty() {
		echo '<div class="solace-product-rating">
			<div class="woocommerce-product-rating custom-star-rating">
				<div class="star-rating" role="img" aria-label="' . esc_attr__( 'Rated 0 out of 5', 'solace-extra' ) . '">
					<span style="width:0%"></span>
				</div>
				<span class="review-count">(' . esc_html__( '0 customer reviews', 'solace-extra' ) . ')</span>
			</div>
		</div>';
	}


	public function solace_product_rating_style(){
		echo '<style>
				.woocommerce-product-rating.custom-star-rating {
					display: flex;
					align-items: center;
				}
				.woocommerce .custom-star-rating .star-rating,
				.custom-star-rating .star-rating {
					overflow: hidden;
					position: relative;
					height: 1em;
					line-height: 1;
					font-size: 1em;
					width: 5.4em;
					display: inline-block;
					color:red;
					margin: 0;
				}
				.custom-star-rating .star-rating::before {
					content: "★★★★★";
					position: absolute;
					top: -3px;
					left: 0;
					color: #ffc107;
				}
				.custom-star-rating .star-rating span {
					display: block;
					position: absolute;
					top: 0;
					left: 0;
					overflow: hidden;
					white-space: nowrap;
					width: 100%;
					height: 100%;
				}
				.custom-star-rating .star-rating span::before {
					content: "★★★★★";
					position: absolute;
					top: 0;
					left: 0;
					color: #ffc107;
				}
				.custom-star-rating  .review-count {
					margin-left: 10px;
					font-size: 0.9em;
					color: #666;
				}
			</style>';
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$this->solace_product_rating_style();
		$product = solace_get_preview_product();
		$checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			// echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
			$this->solace_product_rating_empty();
			return;
		}

		$product_id    = $product->get_id();
	
		if ( $product ) {
			$rating            = $product->get_average_rating();
			$rating_percentage = ( $rating / 5 ) * 100;
			$review_count      = $product->get_review_count();
		} else {
			$rating            = 3.2;
			$rating_percentage = ( $rating / 5 ) * 100;
			$review_count      = 2;
		}
		$font_family = "";
		
		// Force enqueue Google Font on frontend
		if ( ! empty( $settings['product_rating_typography_typography'] ) &&'custom' !== $settings['product_rating_typography_font_family'] ) {
			$font_family = $settings['product_rating_typography_font_family'];
			$font_weight = ! empty( $settings['product_rating_typography']['font_weight'] ) ? $settings['product_rating_typography']['font_weight'] : '400';

			$family_param = str_replace( ' ', '+', $font_family );
			$google_fonts_url = 'https://fonts.googleapis.com/css2?family=' . $family_param . ':wght@' . $font_weight . '&display=swap';

			wp_enqueue_style( 'product-rating-elementor-font-' . sanitize_title( $font_family ), $google_fonts_url, [], SOLACE_EXTRA_VERSION );
			
		}
	
		echo '<div class="solace-product-rating">';
		echo '<div class="woocommerce-product-rating custom-star-rating">';
		echo '<div class="star-rating" role="img" aria-label="Rated ' . esc_attr( $rating ) . ' out of 5">';
		echo '<span style="width:' . esc_attr( $rating_percentage ) . '%"></span>';
		echo '</div>';
		echo '<span class="review-count">(' . esc_html( $review_count ) . ' customer review' . ( $review_count === 1 ? '' : 's' ) . ')</span>';
		echo '</div>';
		echo '</div>';

		
		// }
	}
	
}
