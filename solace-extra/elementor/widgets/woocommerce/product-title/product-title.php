<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use SolaceExtra\App\Controls\Group_Control_Gradient_Text;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Solace_Extra_Product_Title extends Widget_Base {

	public $id;
	
	public $widget;

	public function __construct( $data = [], $args = null ) {
	    parent::__construct( $data, $args );

		$data = solace_extra_widgets();
		$this->id = strtolower( 'solace-extra-' . str_replace( ' ', '-', $data[ pathinfo( __FILE__, PATHINFO_FILENAME ) ][ 'title' ] ) );
	    $this->widget = $data[ pathinfo(__FILE__, PATHINFO_FILENAME) ];

	}

	public function get_script_depends() {
		return [ "solace-extra-{$this->id}", 'fancybox' ];
	}

	public function get_style_depends() {
		return [ "solace-extra-{$this->id}", 'fancybox' ];
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
			'_sectio_title',
			[
				'label' 		=> __( 'Product Title', 'solace-extra' ),
				'tab' 			=> Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'title_tag',
            [
                'label' 		=> __( 'Title HTML Tag', 'solace-extra' ),
                'type' 			=> Controls_Manager::CHOOSE,
                'options' 		=> [
                    'h1'  		=> [
                        'title' 	=> __( 'H1', 'solace-extra' ),
                        'icon' 		=> 'eicon-editor-h1'
                    ],
                    'h2'  		=> [
                        'title' 	=> __( 'H2', 'solace-extra' ),
                        'icon' 		=> 'eicon-editor-h2'
                    ],
                    'h3'  		=> [
                        'title' 	=> __( 'H3', 'solace-extra' ),
                        'icon' 		=> 'eicon-editor-h3'
                    ],
                    'h4'  		=> [
                        'title' 	=> __( 'H4', 'solace-extra' ),
                        'icon' 		=> 'eicon-editor-h4'
                    ],
                    'h5'  		=> [
                        'title' 	=> __( 'H5', 'solace-extra' ),
                        'icon' 		=> 'eicon-editor-h5'
                    ],
                    'h6'  		=> [
                        'title' 	=> __( 'H6', 'solace-extra' ),
                        'icon' 		=> 'eicon-editor-h6'
                    ]
                ],
                'default' 		=> 'h3',
                'toggle' 		=> false,
            ]
        );

        $this->end_controls_section();
		
        /**
		 * Product Title Style 
		 */
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Product Title', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'link_color',
			[
				'label' => __( 'Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-product-title .solace-heading-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'title_typography',
				'label' 	=> __( 'Typography', 'solace-extra' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' 	=> '{{WRAPPER}} .solace-product-title .solace-heading-title',
				'fields_options' 	=> [
					'typography' 	=> [ 'default' => 'yes' ],
				    'font_family' 	=> [ 'default' => 'Montserrat' ],
				    'font_weight' 	=> [ 'default' => 500 ],
                    // 'font_size'     => [ 'default' => [ 'size' => 24 ] ],
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' 		=> __( 'Padding', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before',
			]
		);
		
		$this->add_responsive_control(
			'title_margin',
			[
				'label' 		=> __( 'Margin', 'solace-extra' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .solace-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$product_title = $checkempty;
		} else {
			$product_title = $product->get_title();
		}

		$title_tag = $settings['title_tag'];

		?>
		<div class="solace-product-title">
			<<?php echo tag_escape( $title_tag ); ?> class="solace-heading-title">
				<?php echo esc_html( $product_title ); ?>
			</<?php echo tag_escape( $title_tag ); ?>>
		</div>
		<?php
	}

}

