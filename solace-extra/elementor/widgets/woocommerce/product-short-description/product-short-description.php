<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use SolaceExtra\App\Controls\Group_Control_Gradient_Text;

class Solace_Extra_Product_Short_Description extends Widget_Base {

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
		 * Description style Section
		 */
		$this->start_controls_section(
			'pd_style',
			[
				'label'			=> __( 'Design', 'solace-extra' ),
				'tab'   		=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
            Solace_Extra_Group_Control_Gradient_Text::get_type(),
            [
                'name' => 'pd_title_gradient_color',
                'selector' => '{{WRAPPER}} .solace-product-description p',
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pd_typography',
				'label' => __( 'Typography', 'solace-extra' ),
				'selector' => '{{WRAPPER}} .solace-product-description p',
				'fields_options' 	=> [
					'typography' 	=> [ 'default' => 'yes' ],
					'font_size' 	=> [ 'default' => [ 'size' => 14 ] ],
				    'font_family' 	=> [ 'default' => 'Montserrat' ],
				    'font_weight' 	=> [ 'default' => 400 ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'pd_border',
				'label'         => __( 'Border', 'solace-extra' ),
				'selector'      => '{{WRAPPER}} .solace-product-description p',
				'separator'		=> 'before'
			]
		);

		$this->add_responsive_control(
			'pd_border_radius',
			[
				'label'         => __( 'Border Radius', 'solace-extra' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%', 'em' ],
				'selectors'     => [
					'{{WRAPPER}} .solace-product-description p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pd_box_shadow',
				'label' => __( 'Box Shadow', 'solace-extra' ),
				'selector' => '{{WRAPPER}} .solace-product-description p',
			]
		);

		$this->add_responsive_control(
			'pd_field_padding',
			[
				'label' 		=> __( 'Padding', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-description p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before',
			]
		);

		$this->add_responsive_control(
			'pd_margin',
			[
				'label' 		=> __( 'Margin', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-description p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'pd_product_description', 'basic' );

		$product = solace_get_preview_product();

		$checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
			return;
		}

		$the_excerpt = $product->get_short_description();
		?>

		<div class="solace-product-description">
				<?php echo wp_kses_post( wpautop( $the_excerpt ) ); ?>
		</div>

		<?php
	}



}