<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor WooCommerce Shop Widget.
 *
 * Elementor widget that displays the default WooCommerce Shop page with layout and styling options.
 *
 * @since 1.0.0
 */
class Solace_Extra_WooCommerce_Shop extends \Elementor\Widget_Base {

    public function get_name() {
        return 'solace-extra-woocommerce-shop';
    }

    public function get_title() {
        return esc_html__('Shop', 'solace-extra');
    }

    public function get_icon() {
        return 'eicon-woocommerce solace-extra';
    }

    public function get_categories() {
        return ['solace-extra-woocommerce'];
    }

    public function get_keywords() {
        return ['solace', 'woocommerce', 'shop', 'products'];
    }

	/**
	 * @param array $args {
	 *     An array of values for the button adjustments.
	 *
	 *     @type array  $section_condition  Set of conditions to hide the controls.
	 *     @type string $alignment_default  Default position for the button.
	 *     @type string $alignment_control_prefix_class  Prefix class name for the button position control.
	 *     @type string $content_alignment_default  Default alignment for the button content.
	 * }
	 */
	protected function register_button_style_controls( $args = [] ) {

		$selector = '{{WRAPPER}} .woocommerce ul.products li.sol-product .solace-extra-button, {{WRAPPER}} ul.products li.sol-product .solace-extra-button, {{WRAPPER}} .product-info .add_to_cart_button';

		$selector_hover = '{{WRAPPER}} .woocommerce ul.products li.sol-product .solace-extra-button:hover, {{WRAPPER}} ul.products li.sol-product .solace-extra-button:hover, {{WRAPPER}} .product-info .add_to_cart_button:hover'; 

		$default_args = [
			'section_condition' => [],
			'alignment_default' => '',
			'alignment_control_prefix_class' => 'elementor%s-align-',
			'content_alignment_default' => '',
		];

		$args = wp_parse_args( $args, $default_args );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => $selector,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style', [
			// 'condition' => $args['section_condition'],
		] );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'solace-extra' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude' => [ 'image' ],
				'selector' => $selector,
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => $selector,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'solace-extra' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [ $selector_hover => 'color: {{VALUE}};' ],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'types' => [ 'classic', 'gradient' ],
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude' => [ 'image' ],
				'selector' => $selector_hover,
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector_hover => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => $selector_hover,
			]
		);

		$this->add_control(
			'button_hover_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms', 'custom' ],
				'default' => [
					'unit' => 's',
				],
				'selectors' => [
					$selector_hover => 'transition-duration: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'solace-extra' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => $selector,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'solace-extra' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'solace-extra' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					$selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
	}	

    protected function register_controls() {
        // Tab Content - Layout Control
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('Layout', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
    		'shop_columns',
            [
                'label'   => esc_html__('Columns', 'solace-extra'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('1 Columns', 'solace-extra'),
                    '2' => esc_html__('2 Columns', 'solace-extra'),
                    '3' => esc_html__('3 Columns', 'solace-extra'),
                    '4' => esc_html__('4 Columns', 'solace-extra'),
                    '5' => esc_html__('5 Columns', 'solace-extra'),
                ],
                'default' => '3',
            ]
        );

		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Products per Page', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 100,
				'step' => 1,
				'default' => 4,
			]
		);

		// Add Layout Selection
		$this->add_control(
			'shop_layout',
			[
				'label'   => esc_html__('Layout', 'solace-extra'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'layout-1' => esc_html__('Layout 1 (Left)', 'solace-extra'),
					'layout-2' => esc_html__('Layout 2 (Centered)', 'solace-extra'),
					'layout-3' => esc_html__('Layout 3 (Right)', 'solace-extra'),
					'layout-4' => esc_html__('Layout 4 (Hover Details)', 'solace-extra'),
				],
				'default' => 'layout-1',
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => esc_html__( 'Pagination', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'solace-extra' ),
				'label_off' => esc_html__( 'Hide', 'solace-extra' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'pagination_label',
			[
				'label' => esc_html__( 'Element Order', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before', 
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'element',
			[
				'label' => __( 'Element', 'solace-extra' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'Title'      => __( 'Title', 'solace-extra' ),
					'Category'   => __( 'Category', 'solace-extra' ),
					'Price'      => __( 'Price', 'solace-extra' ),
					'Add To Cart'=> __( 'Add To Cart', 'solace-extra' ),
				],
				'default' => 'title',
			]
		);

		$this->add_control(
			'product_elements_order',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ element }}}',
				'default' => [
					[ 'element' => 'Title' ],
					[ 'element' => 'Category' ],
					[ 'element' => 'Price' ],
					[ 'element' => 'Add To Cart' ],
				],
			]
		);


        $this->end_controls_section();

		$this->start_controls_section(
			'query_section',
			[
				'label' => __('Query', 'solace-extra'),
			]
		);

		$this->add_control(
			'query_source',
			[
				'label'   => __('Source', 'solace-extra'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'latest',
				'options' => [
					'latest'   => __('Latest Products', 'solace-extra'),
					'sale'     => __('Sale', 'solace-extra'),
					'featured' => __('Featured', 'solace-extra'),
				],
			]
		);

		$this->start_controls_tabs( 'query_include_exclude_tabs' );

		$this->start_controls_tab(
			'tab_include',
			[
				'label' => __( 'Include', 'solace-extra' ),
			]
		);

		$this->add_control(
			'include_categories',
			[
				'label' => __( 'Product Categories', 'solace-extra' ),
				'type' =>  \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_product_categories(),
				'label_block' => true,
			]
		);

		$this->add_control(
			'include_tags',
			[
				'label' => __( 'Product Tags', 'solace-extra' ),
				'type' =>  \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_product_tags(),
				'label_block' => true,
			]
		);

		$this->add_control(
			'include_brands',
			[
				'label' => __( 'Product Brands', 'solace-extra' ),
				'type' =>  \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_product_brands(),
				'label_block' => true,
			]
		);

		$this->add_control(
			'include_author',
			[
				'label' => __( 'Author', 'solace-extra' ),
				'type' =>  \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_authors(),
				'label_block' => true,
			]
		);

		$this->end_controls_tab();


		$this->start_controls_tab(
			'tab_exclude',
			[
				'label' => __( 'Exclude', 'solace-extra' ),
			]
		);

		$this->add_control(
			'exclude_categories',
			[
				'label' => __( 'Product Categories', 'solace-extra' ),
				'type' =>  \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_product_categories(),
				'label_block' => true,
			]
		);

		$this->add_control(
			'exclude_tags',
			[
				'label' => __( 'Product Tags', 'solace-extra' ),
				'type' =>  \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_product_tags(),
				'label_block' => true,
			]
		);

		$this->add_control(
			'exclude_brands',
			[
				'label' => __( 'Product Brands', 'solace-extra' ),
				'type' =>  \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_product_brands(),
				'label_block' => true,
			]
		);

		$this->add_control(
			'exclude_author',
			[
				'label' => __( 'Author', 'solace-extra' ),
				'type' =>  \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_authors(),
				'label_block' => true,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_control(
			'query_orderby',
			[
				'label'   => __('Order By', 'solace-extra'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'  => __('Date', 'solace-extra'),
					'title' => __('Title', 'solace-extra'),
					'menu_order' => __('Menu Order', 'solace-extra'),
					'rand'  => __('Random', 'solace-extra'),
				],
			]
		);

		$this->add_control(
			'query_order',
			[
				'label'   => __('Order', 'solace-extra'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' => __('DESC', 'solace-extra'),
					'ASC'  => __('ASC', 'solace-extra'),
				],
			]
		);

		$this->end_controls_section();



        // Tab Style - Typography & Colors
        $this->start_controls_section(
            'layout_style',
            [
                'label' => esc_html__('Layout', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'layout_4_bg_color',
			[
				'label'     => esc_html__('Overlay Color', 'solace-extra'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.3)',
				'selectors' => [
					'{{WRAPPER}} .solace-extra-woocommerce-shop.layout-4 .product-info' => 'background-color: {{VALUE}}; transition: opacity 0.3s ease-in-out;',
				],
				'condition' => [
					'shop_layout' => 'layout-4',
				],
			]
		);

		$this->add_responsive_control(
			'shop_products_gap',
			[
				'label' => __( 'Products Gap', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ,'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-extra-woocommerce-shop ul.products' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'sol_product_background',
				'label'    => __( 'Background', 'solace-extra' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .solace-extra-woocommerce-shop .sol-product',
			]
		);

		/* Border Control */
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'sol_product_border',
				'label'    => __( 'Border', 'solace-extra' ),
				'selector' => '{{WRAPPER}} .solace-extra-woocommerce-shop .sol-product',
			]
		);

		/* Border Radius */
		$this->add_control(
			'sol_product_border_radius',
			[
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 200 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-woocommerce-shop .sol-product' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
		$this->add_responsive_control(
			'shop_padding',
			[
				'label' => __( 'Padding', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px', 
				],
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-woocommerce-shop .woocommerce ul.products li.sol-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
				// 'selectors' => [
				// 	'{{WRAPPER}} .solace-extra-woocommerce-shop .woocommerce ul.products li.sol-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				// 	'{{WRAPPER}} .solace-extra-woocommerce-shop .woocommerce ul.products li.sol-product' => 'width: 100%;',
				// ],
				// 'selectors' => [
				// 	'{{WRAPPER}} .solace-extra-woocommerce-shop .sol-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				// 	'{{WRAPPER}} .solace-extra-woocommerce-shop .sol-product' => 'width: 100%;',
				// ],
			]
		);

		$this->add_responsive_control(
			'shop_margin',
			[
				'label' => __( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-extra-woocommerce-shop .woocommerce ul.products li.sol-product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		



		$this->end_controls_section();

		// Tab Style - Typography & Colors
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Product Title', 'solace-extra'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'product_name_typography',
                'label'    => esc_html__('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-loop-product__title, {{WRAPPER}} ul.products li.sol-product .woocommerce-loop-product__title, {{WRAPPER}} ul.products li.sol-product .woocommerce-loop-product__title a',
				
            ]
        );

        $this->add_control(
            'product_name_color',
            [
                'label'     => esc_html__('Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__title, {{WRAPPER}} .woocommerce-loop-product__title a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ul.products li.sol-product .woocommerce-loop-product__title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ul.products li.sol-product .woocommerce-loop-product__title a' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'product_name_padding',
			[
				'label'      => esc_html__('Padding', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-loop-product__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'product_name_margin',
			[
				'label'      => esc_html__('Margin', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_categories',
			[
				'label' => esc_html__('Product Categories', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'product_categories_typography',
				'selector' => '{{WRAPPER}} .sol-product .product-info .product-categories a',
			]
		);

		// Font Color
		$this->add_control(
			'product_categories_color',
			[
				'label'     => esc_html__('Font Color', 'solace-extra'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sol-product .product-info .product-categories a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_categories_bg_color',
			[
				'label'     => esc_html__('Background Color', 'solace-extra'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sol-product .product-info .product-categories a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_categories_content_spacing',
			[
				'label' => __( 'Spacing', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%'],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'default' => [
					'size' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sol-product .product-info .product-categories' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'product_categories_padding',
			[
				'label'      => esc_html__('Padding', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sol-product .product-info .product-categories a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				// 'separator'  => 'before',
			]
		);

		// Margin
		$this->add_responsive_control(
			'product_categories_margin',
			[
				'label'      => esc_html__('Margin', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sol-product .product-info .product-categories a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'product_categories_border',
				'selector' => '{{WRAPPER}} .sol-product .product-info .product-categories a',
			]
		);

		// Border Radius
		$this->add_responsive_control(
			'product_categories_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default'    => [
					'top'    => 12,
					'right'  => 12,
					'bottom' => 12,
					'left'   => 12,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .sol-product .product-info .product-categories a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// ======= Section Price =======
		$this->start_controls_section(
			'section_price',
			[
				'label' => esc_html__('Price', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        // Price Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'label'    => esc_html__('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.sol-product .price, {{WRAPPER}} ul.products li.sol-product .price',
            ]
        );

        // Price Color
        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__('Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.sol-product .price' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ul.products li.sol-product .price' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ul.products li.sol-product .price span' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'price_padding',
			[
				'label'      => esc_html__('Padding', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce ul.products li.sol-product .price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		// ✅ Margin control
		$this->add_responsive_control(
			'price_margin',
			[
				'label'      => esc_html__('Margin', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce ul.products li.sol-product .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// ======= Section Badge =======
		$this->start_controls_section(
			'section_sale',
			[
				'label' => esc_html__('Badge', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'badge_typography',
                'label'    => esc_html__('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.sol-product .onsale',
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label'     => esc_html__('Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.sol-product .onsale' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label'     => esc_html__('Background Color', 'solace-extra'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.sol-product .onsale' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		// Padding (Responsive)
		$this->add_responsive_control(
			'badge_padding',
			[
				'label'      => esc_html__('Padding', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default'    => [
					'top'    => '10',
					'right'  => '20',
					'bottom' => '10',
					'left'   => '20',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce ul.products li.sol-product .onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin (Responsive)
		$this->add_responsive_control(
			'badge_margin',
			[
				'label'      => esc_html__('Margin', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default'    => [
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '0',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce ul.products li.sol-product .onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		

		// Border Type
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'badge_border',
				'label'    => esc_html__('Border', 'solace-extra'),
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.sol-product .onsale',
			]
		);

		// Border Radius
		$this->add_responsive_control(
			'badge_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default'    => [
					'top'    => 12,
					'right'  => 12,
					'bottom' => 12,
					'left'   => 12,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce ul.products li.sol-product .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box Shadow
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'badge_box_shadow',
				'label'    => esc_html__('Box Shadow', 'solace-extra'),
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.sol-product .onsale',
			]
		);

		$this->end_controls_section();

		// ======= Section Button =======
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__('Button', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->register_button_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			[
				'label' => esc_html__( 'Pagination', 'solace-extra' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'pagination_alignment',
			[
				'label' => esc_html__( 'Alignment', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'solace-extra' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'solace-extra' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'solace-extra' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} .solace-extra-pagination .page-numbers',
			]
		);

		$this->start_controls_tabs( 'pagination_tabs' );

		// --- TAB NORMAL ---
		$this->start_controls_tab(
			'pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'solace-extra' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'     => esc_html__( 'Text Color', 'solace-extra' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'solace-extra' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// --- TAB HOVER ---
		$this->start_controls_tab(
			'pagination_hover',
			[
				'label' => esc_html__( 'Hover', 'solace-extra' ),
			]
		);

		$this->add_control(
			'pagination_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'solace-extra' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'solace-extra' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'solace-extra' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// --- TAB ACTIVE (Current) ---
		$this->start_controls_tab(
			'pagination_active',
			[
				'label' => esc_html__( 'Active', 'solace-extra' ),
			]
		);

		$this->add_control(
			'pagination_color_active',
			[
				'label'     => esc_html__( 'Text Color', 'solace-extra' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers.current' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_active',
			[
				'label'     => esc_html__( 'Background Color', 'solace-extra' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_border_color_active',
			[
				'label'     => esc_html__( 'Border Color', 'solace-extra' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers.current' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'pagination_border',
				'selector'  => '{{WRAPPER}} .solace-extra-pagination .page-numbers',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label'      => esc_html__( 'Padding', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label'      => esc_html__( 'Spacing Between', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ], 
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'%'  => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination .page-numbers' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_margin_top',
			[
				'label'      => esc_html__( 'Margin Top', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ], 
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .solace-extra-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

    }

	protected function get_product_categories() {
		$terms = get_terms( [
			'taxonomy' => 'product_cat',
			'hide_empty' => false,
		] );

		$options = [];

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		return $options;
	}

	protected function get_product_tags() {
		$terms = get_terms( [
			'taxonomy' => 'product_tag',
			'hide_empty' => false,
		] );

		$options = [];

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		return $options;
	}

	protected function get_product_brands() {
		$terms = get_terms( [
			'taxonomy' => 'product_brand',
			'hide_empty' => false,
		] );

		$options = [];

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		return $options;
	}

	public function get_style(){
		echo '<style>
			.solace-product-gallery  .sol-product .onsale,
			.solace-extra-woocommerce-shop .sol-product .onsale {
				z-index: 2;
			}
			.sol-product .elementor-button.add_to_cart_button.loading {
				position: relative;
				pointer-events: none;
				color: transparent !important;
			}
			.sol-product .elementor-button.add_to_cart_button.loading::after {
				content: "";
				position: absolute;
				top: 50%;
				left: 50%;

				width: 18px;
				height: 18px;

				border-radius: 50%;
				border: 2px solid #fff;
				border-top-color: transparent;

				transform: translate(-50%, -50%);
				transform-origin: center;

				animation: spin 0.8s linear infinite;
			}



			@keyframes spin {
				from {
					transform: translate(-50%, -50%) rotate(0deg);
				}
				to {
					transform: translate(-50%, -50%) rotate(360deg);
				}
			}

			.solace-extra-woocommerce-shop.layout-4 .sol-product .elementor-button,
			.solace-extra-woocommerce-shop.layout-4 .sol-product a.added_to_cart,
			.solace-extra-woocommerce-shop.layout-4 .sol-product .product-info .product-categories {
				z-index: 2;
			}

			.solace-extra-woocommerce-shop .product-image {
				justify-content: center;
				display: flex;
				}
			.solace-extra-woocommerce-shop .product-image img {
				width: 100%;
			}
			.solace-extra-woocommerce-shop span.price {
				display: block;
			}
			.solace-extra-woocommerce-shop ul.products {
				display: grid;
				gap: 30px;
			}
			.solace-extra-woocommerce-shop .woocommerce ul.products li.sol-product .onsale {
				background-color: var(--sol-color-selection-high);
				color: var(--sol-color-selection-initial);
				left: 0;
			}
			
			.solace-extra-woocommerce-shop ul.products li.sol-product {
				list-style: none;
			}
			.solace-extra-woocommerce-shop ul.products::before {
				display: none;
			}
			
			.solace-extra-woocommerce-shop .woocommerce-loop-product__title,
			.solace-extra-woocommerce-shop .product-categories,
			.solace-extra-woocommerce-shop .price,
			.solace-extra-woocommerce-shop .woocommerce ul.products li.sol-product .add_to_cart_button,
			.solace-extra-woocommerce-shop .woocommerce ul.products li.sol-product .added_to_cart {
				margin-top: 12px;
			}

			.solace-extra-woocommerce-shop .woocommerce ul.products li.sol-product .added_to_cart {
				padding: 12px 24px;
				font-size: 14px;
				line-height: 21px;
			}

			.solace-extra-woocommerce-shop .woocommerce ul.products li.sol-product .add_to_cart_button:hover {
				background-color: var(--e-global-color-primary);
			}

			.solace-extra-woocommerce-shop .product-categories {
				display: flex;
				gap: 5px;
			}

			.solace-extra-woocommerce-shop li.sol-product .product-info .product-categories a {
				padding: 5px 10px;
				color: var(--sol-color-background);
    			background-color: var(--sol-color-page-title-background);
				border-radius: 5px;
			}

			/* Default Desktop */
			.solace-extra-woocommerce-shop.columns-desktop-2 ul.products {
			grid-template-columns: repeat(2, 1fr);
			}
			.solace-extra-woocommerce-shop.columns-desktop-3 ul.products {
			grid-template-columns: repeat(3, 1fr);
			}
			.solace-extra-woocommerce-shop.columns-desktop-4 ul.products {
			grid-template-columns: repeat(4, 1fr);
			}
			.solace-extra-woocommerce-shop.columns-desktop-5 ul.products {
			grid-template-columns: repeat(5, 1fr);
			}

			/* Tablet */
			@media (max-width: 1024px) {
				.solace-extra-woocommerce-shop.columns-tablet-1 ul.products {
					grid-template-columns: repeat(1, 1fr);
				}
				.solace-extra-woocommerce-shop.columns-tablet-2 ul.products {
					grid-template-columns: repeat(2, 1fr);
				}
				.solace-extra-woocommerce-shop.columns-tablet-3 ul.products {
					grid-template-columns: repeat(3, 1fr);
				}
				.solace-extra-woocommerce-shop.columns-tablet-4 ul.products {
					grid-template-columns: repeat(4, 1fr);
				}
				.solace-extra-woocommerce-shop.columns-tablet-5 ul.products {
					grid-template-columns: repeat(5, 1fr);
				}
			}

			/* Mobile */
			@media (max-width: 767px) {
				.solace-extra-woocommerce-shop.columns-mobile-1 ul.products {
					grid-template-columns: repeat(1, 1fr);
				}
				.solace-extra-woocommerce-shop.columns-mobile-2 ul.products {
					grid-template-columns: repeat(2, 1fr);
				}
				.solace-extra-woocommerce-shop.columns-mobile-3 ul.products {
					grid-template-columns: repeat(3, 1fr);
				}
				.solace-extra-woocommerce-shop.columns-mobile-4 ul.products {
					grid-template-columns: repeat(4, 1fr);
				}
				.solace-extra-woocommerce-shop.columns-mobile-5 ul.products {
					grid-template-columns: repeat(5, 1fr);
				}
			}

			.solace-extra-pagination {
				margin-top: 30px;
				text-align: center;
				clear: both;
			}

			.solace-extra-pagination .page-numbers {
				display: inline-block;
				padding: 8px 16px;
				margin: 0 4px;
				background: #f4f4f4;
				color: #333;
				text-decoration: none;
				border-radius: 4px;
			}

			.solace-extra-pagination .page-numbers.current {
				background: #000;
				color: #fff;
			}

			.solace-extra-pagination .page-numbers:hover:not(.current) {
				background: #e0e0e0;
			}


		</style>';
	}

	private function get_authors() {
		$users = get_users([
			// 'who' => 'authors',
			'capability' => 'edit_posts'
		]);

		$options = [];
		foreach ($users as $user) {
			$options[$user->ID] = $user->display_name;
		}
		
		return $options;
	}
	
	protected function render() {
		if (!class_exists('WooCommerce')) {
			echo esc_html__('WooCommerce is not activated.', 'solace-extra');
			return;
		}
		$widget_id = $this->get_id();
		$settings = $this->get_settings_for_display();

		$hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';

		$this->get_style();
		$columns_desktop = !empty($settings['shop_columns']) ? $settings['shop_columns'] : '3';
		$columns_tablet  = !empty($settings['shop_columns_tablet']) ? $settings['shop_columns_tablet'] : $columns_desktop;
		$columns_mobile  = !empty($settings['shop_columns_mobile']) ? $settings['shop_columns_mobile'] : $columns_tablet;

		$layout = !empty($settings['shop_layout']) ? $settings['shop_layout'] : 'layout-1';
		$query_source = !empty($settings['query_source']) ? $settings['query_source'] : 'latest';
		$query_orderby = !empty($settings['query_orderby']) ? $settings['query_orderby'] : 'date';
		$query_order = !empty($settings['query_order']) ? $settings['query_order'] : 'DESC';
		$query_author = !empty($settings['query_author']) ? $settings['query_author'] : '';

		// Include/Exclude Tabs
		$include_categories = !empty($settings['include_categories']) ? $settings['include_categories'] : [];
		$include_tags       = !empty($settings['include_tags']) ? $settings['include_tags'] : [];
		$include_brands     = !empty($settings['include_brands']) ? $settings['include_brands'] : [];

		$exclude_categories = !empty($settings['exclude_categories']) ? $settings['exclude_categories'] : [];
		$exclude_tags       = !empty($settings['exclude_tags']) ? $settings['exclude_tags'] : [];
		$exclude_brands     = !empty($settings['exclude_brands']) ? $settings['exclude_brands'] : [];

		$columns_classes = sprintf(
			'columns-desktop-%s columns-tablet-%s columns-mobile-%s',
			esc_attr($columns_desktop),
			esc_attr($columns_tablet),
			esc_attr($columns_mobile)
		);

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && empty( $include_categories ) ) {
			$template_id = get_the_ID();

			$preview_settings = get_post_meta( $template_id, '_elementor_preview_settings_category_shop', true );

			if ( isset( $preview_settings['preview_category'] ) && $preview_settings['preview_category'] ) {
				$include_categories = [ absint( $preview_settings['preview_category'] ) ];
			}
		}

		echo '<div class="solace-extra-woocommerce-shop ' . esc_attr($layout) . ' ' . esc_attr($columns_classes) . ' shop-widget-' . esc_attr($widget_id) . '">';

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );

		$posts_per_page = !empty($settings['posts_per_page']) ? intval($settings['posts_per_page']) : 6;

		$args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged'          => $paged, 
            'orderby'        => $query_orderby,
            'order'          => $query_order,
            'suppress_filters' => true,
        ];

		if ($query_author) {
			$args['author'] = intval($query_author);
		}

		// Tax Query for Include/Exclude Tabs
		$tax_query = [];

		if (!empty($include_categories)) {
			$tax_query[] = [
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $include_categories,
				'operator' => 'IN',
			];
		}

		if (!empty($include_tags)) {
			$tax_query[] = [
				'taxonomy' => 'product_tag',
				'field'    => 'term_id',
				'terms'    => $include_tags,
				'operator' => 'IN',
			];
		}

		if (!empty($include_brands)) {
			$tax_query[] = [
				'taxonomy' => 'product_brand',
				'field'    => 'term_id',
				'terms'    => $include_brands,
				'operator' => 'IN',
			];
		}

		if (!empty($exclude_categories)) {
			$tax_query[] = [
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $exclude_categories,
				'operator' => 'NOT IN',
			];
		}

		if (!empty($exclude_tags)) {
			$tax_query[] = [
				'taxonomy' => 'product_tag',
				'field'    => 'term_id',
				'terms'    => $exclude_tags,
				'operator' => 'NOT IN',
			];
		}

		if (!empty($exclude_brands)) {
			$tax_query[] = [
				'taxonomy' => 'product_brand',
				'field'    => 'term_id',
				'terms'    => $exclude_brands,
				'operator' => 'NOT IN',
			];
		}

		if (!empty($tax_query)) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			$args['tax_query'] = $tax_query;
		}

		// Modify args based on query_source
		switch ($query_source) {
			case 'sale':
				$args['meta_query'][] = [
					'key'     => '_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'NUMERIC',
				];
				break;

			case 'featured':
				$args['tax_query'][] = [
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
					'operator' => 'IN',
				];
				break;

			case 'related':
				global $product;
				if ($product) {
					$related_ids = wc_get_related_products($product->get_id(), 4);
					$args['post__in'] = $related_ids;
				}
				break;

			case 'upsells':
				global $product;
				if ($product) {
					$upsells = $product->get_upsell_ids();
					$args['post__in'] = $upsells;
				}
				break;

			case 'cross_sells':
				$cross_sells = [];
				if (WC()->cart) {
					foreach (WC()->cart->get_cart() as $cart_item) {
						$cart_product = wc_get_product($cart_item['product_id']);
						if ($cart_product) {
							$cross_sells = array_merge($cross_sells, $cart_product->get_cross_sell_ids());
						}
					}
				}
				$args['post__in'] = $cross_sells;
				break;

			default:
				break;
		}
		remove_all_filters( 'pre_get_posts', 10 );

		$query = new WP_Query($args);

		if ($query->have_posts()) {
			echo '<div class="woocommerce">';
			echo '<div class="data-post">';
			echo '<ul class="products">';

			while ($query->have_posts()) {
				$query->the_post();
				$solace_extra_product = wc_get_product(get_the_ID());

				if (!$solace_extra_product) continue;

				if ($layout === 'layout-1' || $layout === 'layout-2' || $layout === 'layout-3') {
					if ($layout === 'layout-2') {
						echo '<style>.elementor-element-' . esc_attr($widget_id) . ' .product-info {
							display: flex;
							text-align: center;
							flex-direction: column;
							justify-content: center;
							align-items: center;
						}</style>';
					}elseif ($layout === 'layout-3') {
						echo '<style>.elementor-element-' . esc_attr($widget_id) . ' .product-info {
							display: flex;
							text-align: right;
							flex-direction: column;
							justify-content: right;
							align-items: end;
						}</style>';
					}
					echo '<li class="sol-product">';
					echo '<div class="product-wrapper" style="position: relative;">';
					if ($layout === 'layout-4') {
						echo '<a href="' . esc_url(get_permalink()) . '" class="product-full-link" style="position: absolute; inset: 0; z-index: 1;"></a>';
					}else {
						echo '<a href="' . esc_url(get_permalink()) . '" class="product-full-link rico-lay-else" style="position: absolute; "></a>';
					}
					if ($solace_extra_product->is_on_sale()) {
						echo '<span class="onsale">' . esc_html__('Sale!', 'solace-extra') . '</span>';
					}
					echo '<div class="product-image">
						<a href="' . esc_url( get_permalink() ) . '">
							' . wp_kses_post( woocommerce_get_product_thumbnail() ) . '
						</a>
					</div>';
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<div class="product-info">';

					foreach ( $settings['product_elements_order'] as $item ) {

						switch ( $item['element'] ) {

							case 'Title':
								echo '<h4 class="woocommerce-loop-product__title">
									<a href="' . esc_url( get_permalink() ) . '">
										' . esc_html( get_the_title() ) . '
									</a>
								</h4>';
								break;

							case 'Category':
								$categories = wc_get_product_category_list(
									$solace_extra_product->get_id(),
									' ',
									'<span class="product-categories">',
									'</span>'
								);
								if ( $categories ) {
									echo wp_kses_post( $categories );
								}
								break;

							case 'Price':
								echo '<span class="price">' . wp_kses_post( $solace_extra_product->get_price_html() ) . '</span>';
								break;

						
							case 'Add To Cart':
								$button_url  = $solace_extra_product->add_to_cart_url();
								$button_text = $solace_extra_product->add_to_cart_text();
								$is_ajaxable = $solace_extra_product->is_purchasable() && $solace_extra_product->is_in_stock() && $solace_extra_product->supports( 'ajax_add_to_cart' );
								$ajax_class  = $is_ajaxable ? 'ajax_add_to_cart' : '';

								echo '<a 
									href="' . esc_url( $button_url ) . '" 
									data-product_id="' . esc_attr( $solace_extra_product->get_id() ) . '" 
									data-quantity="1" 
									class="elementor-button solace-extra-button add_to_cart_button ' . esc_attr( $ajax_class ) . ' ' . esc_attr( $hover_class ) . '" 
									aria-label="' . esc_attr( $solace_extra_product->add_to_cart_description() ) . '" 
									rel="nofollow">
										' . esc_html( $button_text ) . '
								</a>';
								break;
						}
					}

					echo '</div>';
					echo '</div></li>';

				} elseif ($layout === 'layout-4') {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<li class="sol-product">';
					echo '<div class="product-wrapper" style="position: relative;">';
					echo '<a href="' . esc_url(get_permalink()) . '" class="product-full-link" style="position: absolute; inset: 0; z-index: 1;"></a>';

					if ($solace_extra_product->is_on_sale()) {
						echo '<span class="onsale">' . esc_html__('Sale!', 'solace-extra') . '</span>';
					}

					echo '<div class="product-image">';
					echo wp_kses_post( woocommerce_get_product_thumbnail() );
					echo '</div>';

					// // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<div class="product-info">';

					foreach ( $settings['product_elements_order'] as $item ) {

						switch ( $item['element'] ) {

							case 'title':
								echo '<h4 class="woocommerce-loop-product__title">
									<a href="' . esc_url( get_permalink() ) . '">
										' . esc_html( get_the_title() ) . '
									</a>
								</h4>';
								break;

							case 'category':
								$categories = wc_get_product_category_list(
									$solace_extra_product->get_id(),
									' ',
									'<span class="product-categories">',
									'</span>'
								);
								if ( $categories ) {
									echo wp_kses_post( $categories );
								}
								break;

							case 'price':
								echo '<span class="price">' . wp_kses_post( $solace_extra_product->get_price_html() ) . '</span>';
								break;

							case 'add_to_cart':
								echo '<a 
									href="' . esc_url( $solace_extra_product->add_to_cart_url() ) . '" 
									data-product_id="' . esc_attr( $solace_extra_product->get_id() ) . '" 
									data-quantity="1" 
									class="elementor-button solace-extra-button add_to_cart_button ajax_add_to_cart ' . esc_attr( $hover_class ) . '" 
									aria-label="' . esc_attr( $solace_extra_product->add_to_cart_description() ) . '" 
									rel="nofollow">
										' . esc_html__( 'Add to cart', 'solace-extra' ) . '
								</a>';
								break;
						}
					}

					echo '</div>';

					echo '</div>'; // .product-wrapper
					echo '</li>';
				}
			}

			echo '</ul>';
			echo '</div>'; // .data-post
			
            $total_pages = $query->max_num_pages;

            if ( 'yes' === $settings['show_pagination'] && $total_pages > 1 ) {
                $current_page = max( 1, $paged );

                $align_class = ! empty( $settings['pagination_alignment'] ) ? ' align-' . $settings['pagination_alignment'] : '';

				echo '<div class="solace-extra-pagination' . esc_attr( $align_class ) . '">';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo paginate_links( array(
					'base'      => absint( $current_page ) > 1 ? str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ) : str_replace( '#', '%#%', esc_url( add_query_arg( 'paged', '#', get_pagenum_link( 1 ) ) ) ),
					'format'    => '?paged=%#%',
					'current'   => $current_page,
					'total'     => $total_pages,
					'prev_text' => '<i class="fas fa-chevron-left"></i>',
					'next_text' => '<i class="fas fa-chevron-right"></i>',
					'type'      => 'plain',
				) );
				echo '</div>';
            }

            echo '</div>'; // .woocommerce
			wp_reset_postdata();
		}

    	echo '</div>'; // .solace-extra-woocommerce-shop
		
	}

}
