<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

/**
 * Product Featured Image widget.
 *
 * Renders the WooCommerce product's featured image with an optional sale badge.
 * Replaces the need to use Elementor's image widget with dynamic content, which
 * is no longer available in the free version.
 */
class Solace_Extra_Product_Featured_Image extends Widget_Base {

	public $id;

	public $widget;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$widgets = solace_extra_widgets();
		$key     = pathinfo( __FILE__, PATHINFO_FILENAME );

		$this->id     = strtolower( 'solace-extra-' . str_replace( ' ', '-', $widgets[ $key ]['title'] ) );
		$this->widget = $widgets[ $key ];
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

		// ===== Content =====
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Featured Image', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'image',
				'default' => 'full',
			]
		);

		$this->add_control(
			'link_to',
			[
				'label'   => __( 'Link', 'solace-extra' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'    => __( 'None', 'solace-extra' ),
					'product' => __( 'Product Page', 'solace-extra' ),
					'custom'  => __( 'Custom URL', 'solace-extra' ),
				],
			]
		);

		$this->add_control(
			'custom_link',
			[
				'label'       => __( 'Custom Link', 'solace-extra' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'solace-extra' ),
				'condition'   => [
					'link_to' => 'custom',
				],
			]
		);

		$this->add_control(
			'fallback_image',
			[
				'label'       => __( 'Fallback Image', 'solace-extra' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => __( 'Used when the product has no featured image.', 'solace-extra' ),
			]
		);

		$this->add_control(
			'sale_flash',
			[
				'label'        => __( 'Sale Flash', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'solace-extra' ),
				'label_off'    => __( 'Hide', 'solace-extra' ),
				'render_type'  => 'template',
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		// ===== Style: Product Image =====
		$this->start_controls_section(
			'section_product_image_style',
			[
				'label' => __( 'Product Image', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'     => __( 'Alignment', 'solace-extra' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'solace-extra' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'solace-extra' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'solace-extra' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .solace-product-featured-image' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => __( 'Width', 'solace-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 2000 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} .solace-product-featured-image__main' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} .solace-product-featured-image__main img',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .solace-product-featured-image__main img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .solace-product-featured-image__main img',
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label'      => __( 'Margin', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .solace-product-featured-image__main' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label'      => __( 'Padding', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .solace-product-featured-image__main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// ===== Style: Sale Badge =====
		$this->start_controls_section(
			'section_sale_style',
			[
				'label' => __( 'Sale Badge', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sale_color',
			[
				'label'     => __( 'Text Color', 'solace-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .onsale' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sale_background',
			[
				'label'     => __( 'Background Color', 'solace-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .onsale' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_border_radius',
			[
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'sale_border',
				'selector' => '{{WRAPPER}} .onsale',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_typography',
				'selector' => '{{WRAPPER}} .onsale',
			]
		);

		$this->add_responsive_control(
			'sale_padding',
			[
				'label'      => __( 'Padding', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			echo '<div class="solace-product-featured-image">' . esc_html( $checkempty ) . '</div>';
			return;
		}

		$image_id   = $product->get_image_id();
		$image_size = ! empty( $settings['image_size'] ) ? $settings['image_size'] : 'full';

		if ( $image_id ) {
			$image_html = wp_get_attachment_image( $image_id, $image_size, false, [ 'class' => 'solace-product-featured-image__img' ] );
		} elseif ( ! empty( $settings['fallback_image']['url'] ) ) {
			$image_html = '<img class="solace-product-featured-image__img" src="' . esc_url( $settings['fallback_image']['url'] ) . '" alt="' . esc_attr( $product->get_name() ) . '">';
		} elseif ( function_exists( 'wc_placeholder_img' ) ) {
			$image_html = wc_placeholder_img( $image_size );
		} else {
			$image_html = '<img src="' . esc_url( \Elementor\Utils::get_placeholder_image_src() ) . '" alt="' . esc_attr( $product->get_name() ) . '">';
		}

		$link_open  = '';
		$link_close = '';

		if ( 'product' === $settings['link_to'] ) {
			$url        = get_permalink( $product->get_id() );
			$link_open  = '<a href="' . esc_url( $url ) . '">';
			$link_close = '</a>';
		} elseif ( 'custom' === $settings['link_to'] && ! empty( $settings['custom_link']['url'] ) ) {
			$this->add_link_attributes( 'custom_link', $settings['custom_link'] );
			$link_open  = '<a ' . $this->get_render_attribute_string( 'custom_link' ) . '>';
			$link_close = '</a>';
		}

		$show_sale = ( 'yes' === $settings['sale_flash'] ) && $product->is_on_sale();

		?>
		<div class="solace-product-featured-image">
			<div class="solace-product-featured-image__main" style="position: relative; display: inline-block;">
				<?php if ( $show_sale ) : ?>
					<span class="onsale"><?php esc_html_e( 'Sale!', 'solace-extra' ); ?></span>
				<?php endif; ?>
				<?php echo $link_open; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo $link_close; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<style>
			.solace-product-featured-image .solace-product-featured-image__main img {
				display: block;
				width: 100%;
			}
			.solace-product-featured-image .onsale {
				position: absolute;
				top: 10px;
				left: 10px;
				z-index: 2;
			}
		</style>
		<?php
	}
}
