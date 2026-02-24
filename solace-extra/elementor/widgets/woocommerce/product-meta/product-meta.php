<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;

class Solace_Extra_Product_Meta extends Widget_Base {

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

		$this->start_controls_section(
			'section_product_meta_style',
			[
				'label' => __( 'Style', 'solace-extra' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'solace-extra' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					// 'table' => __( 'Table', 'solace-extra' ),
					'stacked' => __( 'Stacked', 'solace-extra' ),
					'inline' => __( 'Inline', 'solace-extra' ),
				],
				'prefix_class' => 'elementor-woo-meta--view-',
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ,'em'],
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .solace_extra_product_meta .detail-container:not(:last-child):after' => 'margin-top: calc({{SIZE}}{{UNIT}})',
					'{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .solace_extra_product_meta' => 'gap: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .solace_extra_product_meta .detail-container:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.elementor-woo-meta--view-inline .solace_extra_product_meta .detail-container' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.elementor-woo-meta--view-inline .solace_extra_product_meta' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body:not(.rtl) {{WRAPPER}}.elementor-woo-meta--view-inline .detail-container:after' => 'right: calc( (-{{SIZE}}{{UNIT}}/2) + (-{{divider_weight.SIZE}}px/2) )',
					'body:not.rtl {{WRAPPER}}.elementor-woo-meta--view-inline .detail-container:after' => 'left: calc( (-{{SIZE}}{{UNIT}}/2) - ({{divider_weight.SIZE}}px/2) )',
					'{{WRAPPER}} .solace_extra_product_meta .detail-container:not(:last-child):after' => 'right: calc( (-{{SIZE}}{{UNIT}}/2) - ({{divider_weight.SIZE}}px/2) ); position: relative;',
					'.elementor-woo-meta--view-stacked.elementor-widget-solace-extra-product-meta .solace_extra_product_meta span.detail-container:not(:last-child)::after' => 'bottom: calc( -{{SIZE}}{{UNIT}}/2 )',
				],
			]
		);

		$this->add_control(
			'label_margin_right',
			[
				'label' => esc_html__( 'Distance', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} span.detail-label' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'divider',
			[
				'label' => __( 'Divider', 'solace-extra' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'solace-extra' ),
				'label_on' => __( 'On', 'solace-extra' ),
				'selectors' => [
					'{{WRAPPER}} .solace_extra_product_meta .detail-container:not(:last-child):after' => 'content: ""',
				],
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'solace-extra' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'solace-extra' ),
					'double' => __( 'Double', 'solace-extra' ),
					'dotted' => __( 'Dotted', 'solace-extra' ),
					'dashed' => __( 'Dashed', 'solace-extra' ),
				],
				'default' => 'solid',
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .solace_extra_product_meta .detail-container:not(:last-child):after' => 'border-top-style: {{VALUE}}',
					'{{WRAPPER}}.elementor-woo-meta--view-inline .solace_extra_product_meta .detail-container:not(:last-child):after' => 'border-left-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ,'em'],
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .solace_extra_product_meta .detail-container:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}; margin-bottom: calc(-{{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.elementor-woo-meta--view-inline .solace_extra_product_meta .detail-container:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_width',
			[
				'label' => __( 'Width', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],				
				'condition' => [
					'divider' => 'yes',
					'view!' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .solace_extra_product_meta .detail-container:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label' => __( 'Height', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ,'em'],
				'default' => [
					'size' => 24,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .solace_extra_product_meta .detail-container:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .solace_extra_product_meta .detail-container:not(:last-child):after' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_text_style',
			[
				'label' => __( 'Text', 'solace-extra' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}}',
				'fields_options' 	=> [
					'typography' 	=> [ 'default' => 'yes' ],
				    'font_family' 	=> [ 'default' => 'Montserrat' ],
				    'font_weight' 	=> [ 'default' => 400 ],
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_link_style',
			[
				'label' => __( 'Link', 'solace-extra' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_typography',
				'selector' => '{{WRAPPER}} a',
				'fields_options' 	=> [
					'typography' 	=> [ 'default' => 'yes' ],
				    'font_family' 	=> [ 'default' => 'Montserrat' ],
				    'font_weight' 	=> [ 'default' => 400 ],
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_meta_captions',
			[
				'label' => __( 'Captions', 'solace-extra' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_category_caption',
			[
				'label' => __( 'Category', 'solace-extra' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'category_caption_single',
			[
				'label' => __( 'Singular', 'solace-extra' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Category', 'solace-extra' ),
			]
		);

		$this->add_control(
			'category_caption_plural',
			[
				'label' => __( 'Plural', 'solace-extra' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Categories', 'solace-extra' ),
			]
		);

		$this->add_control(
			'heading_tag_caption',
			[
				'label' => __( 'Tag', 'solace-extra' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tag_caption_single',
			[
				'label' => __( 'Singular', 'solace-extra' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Tag', 'solace-extra' ),
			]
		);

		$this->add_control(
			'tag_caption_plural',
			[
				'label' => __( 'Plural', 'solace-extra' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Tags', 'solace-extra' ),
			]
		);

		$this->add_control(
			'heading_sku_caption',
			[
				'label' => __( 'SKU', 'solace-extra' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sku_caption',
			[
				'label' => __( 'SKU', 'solace-extra' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'SKU', 'solace-extra' ),
			]
		);

		$this->add_control(
			'sku_missing_caption',
			[
				'label' => __( 'Missing', 'solace-extra' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'N/A', 'solace-extra' ),
			]
		);

		$this->end_controls_section();
	}

	private function get_plural_or_single( $single, $plural, $count ) {
		return 1 === $count ? $single : $plural;
	}

	protected function render() {
		if ( ! is_woocommerce_activated() ) {
			return;
		}

		$product = solace_get_preview_product();
		$checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
			return;
		}

		$sku = $product->get_sku();

		$settings = $this->get_settings_for_display();
		$sku_caption = ! empty( $settings['sku_caption'] ) ? sanitize_text_field( $settings['sku_caption'] ) : __( 'SKU', 'solace-extra' );
		$sku_missing = ! empty( $settings['sku_missing_caption'] ) ? sanitize_text_field( $settings['sku_missing_caption'] ) : __( 'N/A', 'solace-extra' );
		$category_caption_single = ! empty( $settings['category_caption_single'] ) ? sanitize_text_field( $settings['category_caption_single'] ) : __( 'Category', 'solace-extra' );
		$category_caption_plural = ! empty( $settings['category_caption_plural'] ) ? sanitize_text_field( $settings['category_caption_plural'] ) : __( 'Categories', 'solace-extra' );
		$tag_caption_single = ! empty( $settings['tag_caption_single'] ) ? sanitize_text_field( $settings['tag_caption_single'] ) : __( 'Tag', 'solace-extra' );
		$tag_caption_plural = ! empty( $settings['tag_caption_plural'] ) ? sanitize_text_field( $settings['tag_caption_plural'] ) : __( 'Tags', 'solace-extra' );
		?>
		<div class="solace_extra_product_meta">

			<?php do_action( 'woocommerce_product_meta_start' ); ?>

			<?php if ( wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
				<span class="sku_wrapper detail-container"><span class="detail-label"><?php echo esc_html( $sku_caption ); ?></span> <span class="sku"><?php echo esc_html( $sku  ? $sku : $sku_missing ); ?></span></span>
			<?php endif; ?>

			<?php if ( count( $product->get_category_ids() ) ) : ?>
				<span class="posted_in detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $category_caption_single, $category_caption_plural, count( $product->get_category_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_cat', '', ', ' ); ?></span></span>
			<?php endif; ?>

			<?php if ( count( $product->get_tag_ids() ) ) : ?>
				<span class="tagged_as detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $tag_caption_single, $tag_caption_plural, count( $product->get_tag_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_tag', '', ', ' ); ?></span></span>
			<?php endif; ?>

			<?php do_action( 'woocommerce_product_meta_end', $this ); ?>

		</div>
		<?php
	}

}

