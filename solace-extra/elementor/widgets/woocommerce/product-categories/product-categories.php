<?php

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use SolaceExtra\App\Controls\Group_Control_Gradient_Text;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

class Solace_Extra_Product_Categories extends Widget_Base {

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
			'_sectio_cat',
			[
				'label' 		=> __( 'Content', 'solace-extra' ),
				'tab' 			=> Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'cat_label',
            [
                'label' 		=> __( 'Label', 'solace-extra' ),
                'type' 			=> Controls_Manager::TEXT,
                'default' 		=> 'Category: ',                
				'label_block' 	=> true,
            ]
        );

        $this->end_controls_section();

        /**
		 * Product sku label Style
		 */
		$this->start_controls_section(
			'section_style_cat_lable',
			[
				'label' => __( 'Label', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cat_label_color',
			[
				'label' => __( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .solace-product-categories .product-cat-label' => 'color: {{VALUE}}',
				],
			]
		);

		// $this->add_group_control(
		// 	Group_Control_Typography::get_type(),
		// 	[
		// 		'name' 		=> 'cat_lable_typography',
		// 		'label' 	=> __( 'Typography', 'solace-extra' ),
		// 		'global' => [
		// 			'default' => Global_Typography::TYPOGRAPHY_TEXT,
		// 		],
		// 		'selector' 	=> '{{WRAPPER}} .solace-product-categories .product-cat-label',
		// 		'fields_options' 	=> [
		// 			'typography' 	=> [ 'default' => 'yes' ],
		// 		    'font_family' 	=> [ 'default' => 'Montserrat' ],
		// 		    'font_weight' 	=> [ 'default' => 400 ],
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'cat_lable_padding',
			[
				'label' 		=> __( 'Padding', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-categories .product-cat-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before',
			]
		);

		$this->add_responsive_control(
			'cat_lable_margin',
			[
				'label' 		=> __( 'Margin', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-categories .product-cat-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); 

		/**
		 * Product categories Style
		 */
		$this->start_controls_section(
			'section_style_cat',
			[
				'label' => __( 'Categories', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cat_color',
			[
				'label' => __( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .solace-product-categories a' => 'color: {{VALUE}}',
				],
			]
		);

		// $this->add_group_control(
		// 	Group_Control_Typography::get_type(),
		// 	[
		// 		'name' 		=> 'cat_typography',
		// 		'label' 	=> __( 'Typography', 'solace-extra' ),
		// 		'global' => [
		// 			'default' => Global_Typography::TYPOGRAPHY_TEXT,
		// 		],
		// 		'selector' 	=> '{{WRAPPER}} .solace-product-categories a',
		// 		'fields_options' 	=> [
		// 			'typography' 	=> [ 'default' => 'yes' ],
		// 		    'font_family' 	=> [ 'default' => 'Montserrat' ],
		// 		    'font_weight' 	=> [ 'default' => 400 ],
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'cat_padding',
			[
				'label' 		=> __( 'Padding', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-categories a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before',
			]
		);

		$this->add_responsive_control(
			'cat_margin',
			[
				'label' 		=> __( 'Margin', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-categories a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); 
	}

	protected function renderx() {

		$product = solace_get_preview_product();
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return;
		}

		$product_id    = $product->get_id();
		$product_title = $product->get_title(); 
		$settings = $this->get_settings_for_display();
        ?>

        <div class="solace-product-categories">

        	<?php do_action( 'solace_extra_product_cat_start' );

        	if( function_exists( 'wc_get_product' ) && ( $product_cat_type == 'current_product' || $product_cat_type == 'custom_product' ) ): 
    			if( $product_cat_type == 'current_product' ) {
    				$product = wc_get_product( get_the_ID() );
    				if ( solace_extra_is_edit_mode() || solace_extra_is_preview_mode() ) {
    					$product_id = solace_extra_get_product_id();
    					$product 	= wc_get_product( $product_id );
    				}
	    			else {
						// phpcs:ignore WordPress.Security.NonceVerification.Missing
	    				if ( isset( $_POST['product_id'] ) ) {
							// phpcs:ignore WordPress.Security.NonceVerification.Missing
							$product = wc_get_product(
								solace_extra_sanitize_number(
									// phpcs:ignore WordPress.Security.NonceVerification.Missing
									isset( $_POST['product_id'] ) ? wp_unslash( absint( $_POST['product_id'] ) ) : ''
								)
							);

						}
	    			}
    			}
    			elseif ( $product_cat_type == 'custom_product' ) {
    				$product_id = solace_extra_sanitize_number( $settings['product_id'] );
    				$product 	= $product_id != '' ? wc_get_product( $product_id ) : '';

    				if( $product_id == '' || !$product ) {
    					echo "Input valid Product ID"; return;
    				}
    			}
        		?>

	        	<span class="categories_wrapper">
		        	<?php 
		        	if ( $product ) {
		        		$before_label = sprintf( '<span %s>%s</span> ', $this->get_render_attribute_string( 'cat_label' ), esc_html( $settings['cat_label'] ) );
		        		$before = '<span class="posted_in">' . $before_label;
		        		$after  = '</span>';
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo wc_get_product_category_list( $product->get_id(), ', ', $before, $after ); 
		        	}
		        	?>
		        </span>

	        <?php elseif( $product_cat_type == 'custom_cat' ): ?>
	        	<span class="categories_wrapper">

	        		<?php 
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					printf( '<span %s>%s</span>',
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$this->get_render_attribute_string( 'cat_label' ),
						esc_html( $settings['cat_label'] )
					);
        			?>

	        		<span class="cat-items">
	        			<?php 
	        			$last_item = end( $settings['cat_list'] );
	        			foreach ($settings['cat_list'] as $key => $category) {
	        				$separator 	= isset( $category['_id'] ) && $category['_id'] != $last_item['_id'] ? ', ' : '';
	        				$target 	= isset( $category['is_external'] ) && $category['is_external'] ? ' target="_blank"' : '';
    						$nofollow 	= isset( $category['nofollow'] ) && $category['nofollow'] ? ' rel="nofollow"' : '';
	        				echo '<a href="'. esc_url( $category['cat_link']['url'] ) .'" '. esc_attr( $target ) . esc_attr( $nofollow ) .' class="cat-item">'.  esc_html( $category['cat_name'] ) . esc_html( $separator ) .'</a>';
	        			}
	        			 ?>
	        		</span>
	        	</span>

	        <?php endif;

	        do_action( 'solace_extra_product_cat_end', $this ); ?>

        </div>

        <?php
	}

	protected function render() {

		$product = solace_get_preview_product();
		$checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
			return;
		}

		$product_id    = $product->get_id();
		$product_title = $product->get_title(); 
		$settings = $this->get_settings_for_display();
		$label = isset( $settings['cat_label'] ) ? $settings['cat_label'] : '';

		?>

		<div class="solace-product-categories">
			<?php
			$terms = get_the_terms( $product_id, 'product_cat' );

			if ( $terms && ! is_wp_error( $terms ) ) :
				echo '<span class="product-cat-label">' . esc_html( $label ) . '</span> ';

				$links = [];

				foreach ( $terms as $term ) {
					$term_link = get_term_link( $term );
					if ( is_wp_error( $term_link ) ) {
						continue;
					}
					$links[] = '<a href="' . esc_url( $term_link ) . '">' . esc_html( $term->name ) . '</a>';
				}

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo implode( ' ', $links );

			else :
				echo '<span class="no-category">' . esc_html__( 'No categories found.', 'solace-extra' ) . '</span>';
			endif;
			?>
		</div>
		<style>
			.solace-product-categories {
				display: flex;
			}
		</style>

		<?php
	}


}

