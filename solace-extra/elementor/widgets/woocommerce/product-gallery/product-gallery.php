<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

class Solace_Extra_Product_Gallery extends Widget_Base {

	public $id;
	
	public $widget;

	public function __construct( $data = [], $args = null ) {
	    parent::__construct( $data, $args );

		$data = solace_extra_widgets();
		$this->id = strtolower( 'solace-extra-' . str_replace( ' ', '-', $data[ pathinfo( __FILE__, PATHINFO_FILENAME ) ][ 'title' ] ) );
	    $this->widget = $data[ pathinfo(__FILE__, PATHINFO_FILENAME) ];

	}	

	public function get_script_depends() {
		return array( "solace-extra-{$this->id}", 'fancybox', 'wc-single-product' );
	}

	public function get_style_depends() {
		return array( "solace-extra-{$this->id}", 'fancybox' );
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
			'payment_section_title',
			array(
				'label' => __( 'Sale', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sale_flash',
			array(
				'label'        => __( 'Sale Flash', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'solace-extra' ),
				'label_off'    => __( 'Hide', 'solace-extra' ),
				'render_type'  => 'template',
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => '',
			)
		);


		$this->add_control(
			'show_thumbnails',
			[
				'label'        => __( 'Product Gallery', 'solace-extra' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'solace-extra' ),
				'label_off'    => __( 'Hide', 'solace-extra' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_gallery_image_style',
			array(
				'label' => __( 'Product Image', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'gallery_layout',
			[
				'label'   => __( 'Layout', 'solace-extra' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'column',
				'options' => [
					'column'    => __( 'Top', 'solace-extra' ),
					'column-reverse' => __( 'Bottom', 'solace-extra' ),
					'row'   => __( 'Left', 'solace-extra' ),
					'row-reverse'  => __( 'Right', 'solace-extra' ),
				],
				'selectors' => [
					'{{WRAPPER}} .solace-product-gallery' => 'display:flex; flex-direction: {{VALUE}}; align-items: flex-start;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .solace-product-gallery-main',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .solace-product-gallery-main' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					
				),
			)
		);

		$this->add_control(
			'margin_padding',
			[
				'label' => __( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .solace-product-gallery-main' => 
						'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'main_padding',
			[
				'label' => __( 'Padding', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .solace-product-gallery-main' => 
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_gallery_style',
			array(
				'label' => __( 'Product Gallery', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_thumbnails' => 'yes',
				],
			)
		);

		$this->add_responsive_control(
			'thumbnail_columns',
			[
				'label' => __( 'Columns', 'solace-extra' ),
				'type' => Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'1' => __( '1 Columns', 'solace-extra' ),
					'2' => __( '2 Columns', 'solace-extra' ),
					'3' => __( '3 Columns', 'solace-extra' ),
					'4' => __( '4 Columns', 'solace-extra' ),
					'5' => __( '5 Columns', 'solace-extra' ),
					'6' => __( '6 Columns', 'solace-extra' ),
				],
				'selectors' => [
					'{{WRAPPER}} .solace-product-gallery-thumbnails' => 'display: grid; grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'condition' => [ 'show_thumbnails' => 'yes' ],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'thumbnail_border',
				'label'    => __( 'Border', 'solace-extra' ),
				'selector' => '{{WRAPPER}} .solace-product-gallery-thumbnails .thumbnail img',
				'condition' => [ 'show_thumbnails' => 'yes' ],
			]
		);

		// Border Radius
		$this->add_responsive_control(
			'thumbnail_border_radius',
			[
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .solace-product-gallery-thumbnails .thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [ 'show_thumbnails' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'thumbnail_item_width',
			[
				'label' => __( 'Thumbnail Width (%)', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-product-gallery .flex-control-nav.flex-control-thumbs li' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .solace-product-gallery-thumbnails .thumbnail' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();

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
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'sale_border',
				'selector' => '{{WRAPPER}} .onsale',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
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

	protected function renderxx() {
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		$settings = $this->get_settings_for_display();

		$product = solace_get_preview_product();
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( function_exists( 'wc_get_product' ) && isset( $_GET['post'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$product = wc_get_product( intval( $_GET['post'] ) );
			}
			if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
				return;
			}
		}

		$product_id         = $product->get_id();
		$product_title      = $product->get_title();
		$featured_image_id  = $product->get_image_id();
		$gallery_image_ids  = $product->get_gallery_image_ids();

		if ( empty( $featured_image_id ) && empty( $gallery_image_ids ) ) {
			return;
		}

		$all_image_ids = array_filter( array_merge( [ $featured_image_id ], $gallery_image_ids ) );
		$gallery_layout = isset( $settings['gallery_layout'] ) ? $settings['gallery_layout'] : 'column';
		?>
		<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images layout-<?php echo esc_attr( $gallery_layout ); ?>" data-columns="4">
			<figure class="woocommerce-product-gallery__wrapper">
				<?php foreach ( $all_image_ids as $index => $image_id ) :
					$full_url    = wp_get_attachment_image_url( $image_id, 'full' );
					$thumb_url   = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
					$single_url  = wp_get_attachment_image_url( $image_id, 'woocommerce_single' );
					$image_meta  = wp_get_attachment_metadata( $image_id );
					$img_width   = isset( $image_meta['width'] ) ? $image_meta['width'] : '';
					$img_height  = isset( $image_meta['height'] ) ? $image_meta['height'] : '';
					$is_first    = ( $index === 0 );
				?>
					<div 
						data-thumb="<?php echo esc_url( $thumb_url ); ?>" 
						class="woocommerce-product-gallery__image<?php echo $is_first ? ' active' : ''; ?>"
					>
						<a href="<?php echo esc_url( $full_url ); ?>">
							<img
								src="<?php echo esc_url( $single_url ); ?>"
								alt="<?php echo esc_attr( $product_title ); ?>"
								class="wp-post-image"
								data-caption=""
								data-src="<?php echo esc_url( $single_url ); ?>"
								data-large_image="<?php echo esc_url( $full_url ); ?>"
								data-large_image_width="<?php echo esc_attr( $img_width ); ?>"
								data-large_image_height="<?php echo esc_attr( $img_height ); ?>"
							/>
						</a>
					</div>
				<?php endforeach; ?>
			</figure>

			<!-- Icon trigger lightbox -->
			<a href="#" role="button" class="woocommerce-product-gallery__trigger"
			aria-haspopup="dialog"
			aria-label="<?php esc_attr_e( 'View full-screen image gallery', 'solace-extra' ); ?>">
			<span aria-hidden="true">
				<img class="emoji" alt="🔍" src="#">
			</span>
			</a>
		</div>

		<?php if ( 'yes' === $settings['show_thumbnails'] && count( $all_image_ids ) > 1 ) : ?>
		<div class="solace-product-gallery-thumbnails">
			<?php foreach ( $all_image_ids as $image_id ) :
				$thumb_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
				$full_url  = wp_get_attachment_image_url( $image_id, 'full' );
			?>
				<div class="thumbnail" data-full="<?php echo esc_url( $full_url ); ?>">
					<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $product_title ); ?>">
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<!-- <script>
		document.addEventListener("DOMContentLoaded", function () {
			jQuery(function($) {
				if ($.fn.wc_product_gallery) {
					$('.woocommerce-product-gallery').each(function () {
						$(this).wc_product_gallery();
					});
				}
			});

			document.querySelectorAll('.solace-product-gallery-thumbnails .thumbnail').forEach(function(thumb) {
				thumb.addEventListener('click', function() {
					const target = this.getAttribute('data-full');
					$('.woocommerce-product-gallery .woocommerce-product-gallery__image.active img').attr('src', target).data('large_image', target);
					jQuery('.woocommerce-product-gallery').wc_product_gallery();
				});
			});
		});
		</script> -->

		<script>
			(function($) { 

				function initializeProductGallery($scope) {
					if ($.fn.wc_product_gallery) {
						$scope.find('.woocommerce-product-gallery').each(function () {
							$(this).wc_product_gallery();
						});
					}

					$scope.find('.solace-product-gallery-thumbnails .thumbnail').off('click').on('click', function() {
						const target = $(this).data('full'); 
						const $mainImage = $scope.find('.woocommerce-product-gallery .woocommerce-product-gallery__image.active img');

						$mainImage.attr('src', target).data('large_image', target);

						$scope.find('.woocommerce-product-gallery').wc_product_gallery();
					});
				}
				if (typeof elementorFrontend !== 'undefined') {

					elementorFrontend.hooks.addAction(
						'frontend/element_ready/product-gallery.default', 
						function($scope) {
							initializeProductGallery($scope);
						}
					);
				} else {

					$(document).ready(function() {
						initializeProductGallery($(document));
					});
				}

			})(jQuery);
			</script>

		<style>
			.woocommerce-product-gallery ol.flex-control-nav.flex-control-thumbs {
				display: flex;
				flex-wrap: wrap;
				margin: 0;
				padding: 0;
				justify-content: space-between;
			}
			.woocommerce-product-gallery ol.flex-control-nav.flex-control-thumbs li::marker {
				content: "";
			}
			.solace-product-gallery-thumbnails {
				display: flex;
				gap: 10px;
				justify-content: center;
				margin: 15px 0;
			}
			.solace-product-gallery-thumbnails .thumbnail img {
				width: 60px;
				height: auto;
				cursor: pointer;
			}
		</style>
		<?php
	}

	protected function renderxxx() {
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		$settings = $this->get_settings_for_display();

		$product = solace_get_preview_product();
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( function_exists( 'wc_get_product' ) && isset( $_GET['post'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$product = wc_get_product( intval( $_GET['post'] ) );
			}

			if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
				return;
			}
		}

		$product_id       = $product->get_id();
		$product_title    = $product->get_title();
		$featured_image_id = $product->get_image_id();
		$gallery_image_ids = $product->get_gallery_image_ids();

		if ( empty( $featured_image_id ) && empty( $gallery_image_ids ) ) {
			return;
		}

		$all_image_ids = array_filter( array_merge( [$featured_image_id], $gallery_image_ids ) );
		$gallery_layout = isset( $settings['gallery_layout'] ) ? $settings['gallery_layout'] : 'column';
		?>
		<div class="solace-product-gallery layout-<?php echo esc_attr($gallery_layout); ?>">
			<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images layout-<?php echo esc_attr( $gallery_layout ); ?>" data-columns="4">
				<div class="solace-product-gallery-main">
					<?php if ( $product->is_on_sale() ) : ?>
						<span class="onsale"><?php esc_html_e( 'Sale!', 'solace-extra' ); ?></span>
					<?php endif; ?>

					<figure id="solace-main-image" class="woocommerce-product-gallery__wrapper">
						<?php foreach ( $all_image_ids as $index => $image_id ) :
							$full_url    = wp_get_attachment_image_url( $image_id, 'full' );
							$thumb_url   = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
							$single_url  = wp_get_attachment_image_url( $image_id, 'woocommerce_single' );
							$image_meta  = wp_get_attachment_metadata( $image_id );
							$img_width   = isset( $image_meta['width'] ) ? $image_meta['width'] : '';
							$img_height  = isset( $image_meta['height'] ) ? $image_meta['height'] : '';
							$is_first    = ( $index === 0 );?>
							<div data-thumb="<?php echo esc_url( $thumb_url ); ?>" class="woocommerce-product-gallery__image<?php echo $is_first ? ' active' : ''; ?>">
								<a href="<?php echo esc_url( $full_url ); ?>">
									<img
										src="<?php echo esc_url( $single_url ); ?>"
										alt="<?php echo esc_attr( $product_title ); ?>"
										class="wp-post-image"
										data-caption=""
										data-src="<?php echo esc_url( $full_url ); ?>"
										data-large_image="<?php echo esc_url( $full_url ); ?>"
										data-large_image_width="auto"
										data-large_image_height="auto"
									/>
								</a>
							</div>
						<?php endforeach; ?>
					</figure>
				</div>
			</div>
			<?php if ( 'yes' === $settings['show_thumbnails'] ) : ?>
				<?php if ( ! empty( $gallery_image_ids ) ) : ?>
					<div class="solace-product-gallery-thumbnails">
						<?php foreach ( $all_image_ids as $image_id ) :
							$image_url = wp_get_attachment_image_url( $image_id, 'large' );
							if ( ! $image_url ) continue;
							?>
							<div class="thumbnail" data-full="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>">
								<img src="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'thumbnail' ) ); ?>" alt="<?php echo esc_attr( $product_title ); ?>">
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<script>
			document.addEventListener("DOMContentLoaded", function () {
				jQuery(function($) {
					if ($.fn.wc_product_gallery) {
						$('.woocommerce-product-gallery').each(function () {
							$(this).wc_product_gallery();
						});
					}
				});

				document.querySelectorAll('.solace-product-gallery-thumbnails .thumbnail').forEach(function(thumb) {
					thumb.addEventListener('click', function() {
						const target = this.getAttribute('data-full');
						$('.woocommerce-product-gallery .woocommerce-product-gallery__image.active img').attr('src', target).data('large_image', target);
						jQuery('.woocommerce-product-gallery').wc_product_gallery();
					});
				});
			});
			
			document.addEventListener("DOMContentLoaded", function () {
				const image = document.getElementById("solace-main-image");

				if (!image) return;

				image.addEventListener("click", function () {
					const fullSrc = this.dataset.full || this.src;

					const overlay = document.createElement("div");
					overlay.className = "solace-lightbox-overlay";

					overlay.innerHTML = `
						<div class="solace-lightbox-content">
							<img src="${fullSrc}" alt="Full Image" />
						</div>
					`;

					document.body.appendChild(overlay);
					document.body.style.overflow = 'hidden';

					// Force reflow before adding class to trigger transition
					void overlay.offsetWidth;
					overlay.classList.add('visible');

					overlay.addEventListener("click", () => {
						overlay.classList.remove('visible');
						setTimeout(() => {
							overlay.remove();
							document.body.style.overflow = '';
						}, 300);
					});
				});
			});

			document.addEventListener("DOMContentLoaded", function () {
				const mainImage = document.getElementById("solace-main-image");
				const thumbnails = document.querySelectorAll(".solace-product-gallery-thumbnails .thumbnail");

				thumbnails.forEach(thumbnail => {
					thumbnail.addEventListener("click", function () {
						const newSrc = this.dataset.full;
						if (!newSrc || mainImage.src === newSrc) return;

						mainImage.classList.remove("fade-in");
						mainImage.classList.add("solace-image-fade");

						setTimeout(() => {
							mainImage.src = newSrc;
							mainImage.setAttribute("data-full", newSrc);
							mainImage.classList.remove("solace-image-fade");
							mainImage.classList.add("fade-in");
						}, 150); 
					});
				});
			});


		</script>
		<style>
			.solace-product-gallery-thumbnails {
				gap: 10px; 
				justify-items: center;
				width: 100%;
			}

			.solace-product-gallery-thumbnails .thumbnail {
				width: 100%;
			}

			.solace-product-gallery-thumbnails .thumbnail img {
				width: 100%;
				height: auto;
				cursor: pointer;
			}

			.solace-product-gallery-main {
				width: 100%;
				margin-bottom: 10px;
				text-align: center;
				position: relative;
			}

			.solace-product-gallery-main img {
				max-width: 100%;
				height: auto;
				display: block;
				margin: 0 auto;
				cursor: zoom-in;
			}

			.onsale {
				position: absolute;
				top: 10px;
				left: 10px;
				background: red;
				color: #fff;
				padding: 5px 10px;
				font-weight: bold;
				z-index: 2;
			}

			.layout-row-reverse span.onsale {
				right: 10px;
				left: unset;
			}
			
			.solace-lightbox-overlay {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: rgba(0, 0, 0, 0.8);
				display: flex;
				justify-content: center;
				align-items: center;
				opacity: 0;
				visibility: hidden;
				transition: opacity 0.3s ease, visibility 0.3s ease;
				z-index: 9999;
			}

			.solace-lightbox-overlay.visible {
				opacity: 1;
				visibility: visible;
			}

			.solace-lightbox-content img {
				max-width: 90%;
				max-height: 90%;
				box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
				border-radius: 8px;
				background: #fff;
			}

			.solace-image-fade {
				opacity: 0;
				transition: opacity 0.3s ease;
			}

			#solace-main-image.fade-in {
				opacity: 1;
				transition: opacity 0.3s ease;
			}
			.woocommerce-product-gallery ol.flex-control-nav.flex-control-thumbs {
				display: flex;
				flex-wrap: wrap;
				gap: 10px;
			}
		</style>
		<?php
	}

	protected function render() {
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		// add_theme_support( 'wc-product-gallery-slider' );
		$settings = $this->get_settings_for_display();

		$product = solace_get_preview_product();
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( function_exists( 'wc_get_product' ) && isset( $_GET['post'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$product = wc_get_product( intval( $_GET['post'] ) );
			}

			$checkempty = solace_check_empty_product( $product );
			if ( $checkempty ) {
				echo '<div class="solace-product-gallery-empty" style="display:flex; align-items:center; justify-content:center; background: #bdbdbd;width: 100%;aspect-ratio: 1 / 1;text-align: center;border-radius: 50px;">';
				echo '<span style="color:#555; font-size:18px;">' . esc_html( $checkempty ) . '</span>';
				echo '</div>';
				return;
			}
		}

		$product_id       = $product->get_id();
		$product_title    = $product->get_title();
		$featured_image_id = $product->get_image_id();
		$gallery_image_ids = $product->get_gallery_image_ids();

		if ( empty( $featured_image_id ) && empty( $gallery_image_ids ) ) {
			return;
		}

		$all_image_ids = array_filter( array_merge( [$featured_image_id], $gallery_image_ids ) );
		$gallery_layout = isset( $settings['gallery_layout'] ) ? $settings['gallery_layout'] : 'column';
		?>
		<div class="solace-product-gallery layout-<?php echo esc_attr($gallery_layout); ?>">
			<div class="solace-product-gallery-main">
				<?php if ( $product->is_on_sale() ) : ?>
					<span class="onsale"><?php esc_html_e( 'Sale!', 'solace-extra' ); ?></span>
				<?php endif; ?>
				<img id="solace-main-image"
						src="<?php echo esc_url( wp_get_attachment_image_url( $featured_image_id, 'large' ) ); ?>"
						data-full="<?php echo esc_url( wp_get_attachment_image_url( $featured_image_id, 'full' ) ); ?>"
						alt="<?php echo esc_attr( $product_title ); ?>" />
			</div>

			<?php if ( 'yes' === $settings['show_thumbnails'] ) : ?>
				<?php if ( ! empty( $gallery_image_ids ) ) : ?>
					<div class="solace-product-gallery-thumbnails">
						<?php foreach ( $all_image_ids as $image_id ) :
							$image_url = wp_get_attachment_image_url( $image_id, 'large' );
							if ( ! $image_url ) continue;
							?>
							<div class="thumbnail" data-full="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>">
								<img src="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'thumbnail' ) ); ?>" alt="<?php echo esc_attr( $product_title ); ?>">
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<script>
			document.addEventListener("DOMContentLoaded", function () {
				const image = document.getElementById("solace-main-image");

				if (!image) return;

				image.addEventListener("click", function () {
					const fullSrc = this.dataset.full || this.src;

					const overlay = document.createElement("div");
					overlay.className = "solace-lightbox-overlay";

					overlay.innerHTML = `
						<div class="solace-lightbox-content">
							<img src="${fullSrc}" alt="Full Image" />
						</div>
					`;

					document.body.appendChild(overlay);
					document.body.style.overflow = 'hidden';

					// Force reflow before adding class to trigger transition
					void overlay.offsetWidth;
					overlay.classList.add('visible');

					overlay.addEventListener("click", () => {
						overlay.classList.remove('visible');
						setTimeout(() => {
							overlay.remove();
							document.body.style.overflow = '';
						}, 300);
					});
				});
			});

			document.addEventListener("DOMContentLoaded", function () {
				const mainImage = document.getElementById("solace-main-image");
				const thumbnails = document.querySelectorAll(".solace-product-gallery-thumbnails .thumbnail");

				thumbnails.forEach(thumbnail => {
					thumbnail.addEventListener("click", function () {
						const newSrc = this.dataset.full;
						if (!newSrc || mainImage.src === newSrc) return;

						mainImage.classList.remove("fade-in");
						mainImage.classList.add("solace-image-fade");

						setTimeout(() => {
							mainImage.src = newSrc;
							mainImage.setAttribute("data-full", newSrc);
							mainImage.classList.remove("solace-image-fade");
							mainImage.classList.add("fade-in");
						}, 150); 
					});
				});
			});


		</script>
		<style>
			.solace-product-gallery-thumbnails {
				gap: 10px; 
				justify-items: center;
				width: 100%;
				flex: 3;
			}

			.solace-product-gallery-thumbnails .thumbnail {
				width: 100%;
			}

			.solace-product-gallery-thumbnails .thumbnail img {
				width: 100%;
				height: auto;
				cursor: pointer;
			}

			.solace-product-gallery-main {
				width: 100%;
				margin-bottom: 10px;
				text-align: center;
				position: relative;
				flex: 7;
			}

			.solace-product-gallery-main img {
				max-width: 100%;
				height: auto;
				display: block;
				margin: 0 auto;
				cursor: zoom-in;
			}

			.onsale {
				position: absolute;
				top: 10px;
				left: 10px;
				background: red;
				color: #fff;
				padding: 5px 10px;
				font-weight: bold;
				z-index: 2;
			}

			.layout-row-reverse span.onsale {
				right: 10px;
				left: unset;
			}
			
			.solace-lightbox-overlay {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: rgba(0, 0, 0, 0.8);
				display: flex;
				justify-content: center;
				align-items: center;
				opacity: 0;
				visibility: hidden;
				transition: opacity 0.3s ease, visibility 0.3s ease;
				z-index: 9999;
			}

			.solace-lightbox-overlay.visible {
				opacity: 1;
				visibility: visible;
			}

			.solace-lightbox-content img {
				max-width: 90%;
				max-height: 90%;
				box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
				border-radius: 8px;
				background: #fff;
			}

			.solace-image-fade {
				opacity: 0;
				transition: opacity 0.3s ease;
			}

			#solace-main-image.fade-in {
				opacity: 1;
				transition: opacity 0.3s ease;
			}
		</style>
		<?php
	}

}
