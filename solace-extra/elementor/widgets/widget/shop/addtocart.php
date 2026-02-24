<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor WooCommerce Shop Widget.
 *
 * Elementor widget that displays the default WooCommerce Shop page with layout and styling options.
 *
 * @since 1.0.0
 */
class Solace_Extra_WooCommerce_AddToCart extends \Elementor\Widget_Base {

    public function get_name() {
        return 'solace-extra-woocommerce-addtocart';
    }

    public function get_title() {
        return esc_html__('Add To Cart', 'solace-extra');
    }

    public function get_icon() {
        return 'eicon-woocommerce solace-extra';
    }

    public function get_categories() {
        return ['solace-extra-single'];
    }

    public function get_keywords() {
        return ['solace', 'woocommerce', 'cart','shop', 'products'];
    }

	public function register_controls() {	

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__('Content', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
	
		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__('Button Text', 'solace-extra'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__('Add to Cart', 'solace-extra'),
				'description' => esc_html__('Customize the Add to Cart button text.', 'solace-extra'),
			]
		);
	
		$this->end_controls_section();
	
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__('Button', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
	
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .solace-extra-add-to-cart .button',
			]
		);
	
		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__('Padding', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default'    => [
					'top'    => 12,
					'right'  => 24,
					'bottom' => 12,
					'left'   => 24,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-add-to-cart .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	
		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => esc_html__('Margin', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-add-to-cart .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	
		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default'    => [
					'top'    => 8,
					'right'  => 8,
					'bottom' => 8,
					'left'   => 8,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-add-to-cart .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	

		$this->start_controls_tabs('button_style_tabs');
	
		$this->start_controls_tab(
			'button_normal',
			[
				'label' => esc_html__('Normal', 'solace-extra'),
			]
		);
	
		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__('Text Color', 'solace-extra'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-add-to-cart .button' => 'color: {{VALUE}};',
				],
			]
		);
	
		$this->add_control(
			'button_background_color',
			[
				'label'     => esc_html__('Background Color', 'solace-extra'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-add-to-cart .button' => 'background-color: {{VALUE}};',
				],
			]
		);
	
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .solace-extra-add-to-cart .button',
			]
		);
	
		$this->end_controls_tab();
	
		$this->start_controls_tab(
			'button_hover',
			[
				'label' => esc_html__('Hover', 'solace-extra'),
			]
		);
	
		$this->add_control(
			'button_text_color_hover',
			[
				'label'     => esc_html__('Text Color', 'solace-extra'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-add-to-cart .button:hover' => 'color: {{VALUE}};',
				],
			]
		);
	
		$this->add_control(
			'button_background_color_hover',
			[
				'label'     => esc_html__('Background Color', 'solace-extra'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-extra-add-to-cart .button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
	
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'button_border_hover',
				'selector' => '{{WRAPPER}} .solace-extra-add-to-cart .button:hover',
			]
		);
	
		$this->end_controls_tab();
		$this->end_controls_tabs();
	
		$this->end_controls_section();

		$this->start_controls_section(
			'section_quantity_style',
			[
				'label' => esc_html__('Quantity Input', 'solace-extra'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'quantity_typography',
				'selector' => '{{WRAPPER}} .solace-extra-add-to-cart .quantity',
			]
		);
		
		$this->add_responsive_control(
			'quantity_alignment',
			[
				'label'        => esc_html__('Alignment', 'solace-extra'),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => esc_html__('Left', 'solace-extra'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'solace-extra'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__('Right', 'solace-extra'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors'    => [
					'{{WRAPPER}} .solace-extra-add-to-cart .quantity' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'quantity_width',
			[
				'label'      => esc_html__('Width', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min' => 50,
						'max' => 300,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 66,
				],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-add-to-cart .quantity' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'quantity_border',
				'selector' => '{{WRAPPER}} .solace-extra-add-to-cart .quantity',
			]
		);
		
		$this->add_responsive_control(
			'quantity_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-add-to-cart .quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'quantity_margin',
			[
				'label'      => esc_html__('Margin', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-add-to-cart .quantity' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'quantity_padding',
			[
				'label'      => esc_html__('Padding', 'solace-extra'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .solace-extra-add-to-cart .quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
	}
	


	protected function render() {
		if (!class_exists('WooCommerce')) {
			echo esc_html__('WooCommerce is not activated.', 'solace-extra');
			return;
		}
	
		$settings = $this->get_settings_for_display();
		$product_id = !empty($settings['product_id']) ? $settings['product_id'] : 0;
		$button_text = !empty($settings['button_text']) ? $settings['button_text'] : esc_html__('Add to Cart', 'solace-extra');

		
		if (!$product_id && is_singular('product')) {
			$product_id = get_the_ID(); 
		}
	
		if (!$product_id) {
			$args = [
				'limit' => 1,
				'orderby' => 'rand', 
				'status' => 'publish',
			];
	
			$products = wc_get_products($args);
			if (!empty($products)) {
				$product_id = $products[0]->get_id();
			}
		}
	
	
		if (!$product_id) {
			echo esc_html__('No product available.', 'solace-extra');
			return;
		}
	
		echo '<div class="solace-extra-add-to-cart">';
		echo '<form class="cart" method="post" enctype="multipart/form-data">';
		echo '<input type="number" class="quantity" name="quantity" value="1" min="1">';
		echo '<button type="submit" data-product_id="' . esc_attr($product_id) . '" class="button add_to_cart_button ajax_add_to_cart">' . esc_html($button_text) . '</button>';
		$cart_page_id = get_option('woocommerce_cart_page_id'); 
		$cart_page_slug = get_post_field('post_name', $cart_page_id); 
		
		if ($cart_page_slug) {
			$cart_url = site_url('/' . $cart_page_slug); 
		} else {
			$cart_url = site_url('/cart'); 
		}
		
		echo '<a href="' . esc_url($cart_url) . '" class="button view_cart_button" style="display:none;">' . esc_html__('View Cart', 'solace-extra') . '</a>';
		
		echo '</form>';
		// echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="button view_cart_button" style="display:none;">' . esc_html__('View Cart', 'solace-extra') . '</a>';
		echo '</div>';
	}
	
}
