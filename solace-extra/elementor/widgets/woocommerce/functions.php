<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'solace_force_woocommerce_ready' ) ) {
	function solace_force_woocommerce_ready() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Ensure WooCommerce functions are loaded.
		if ( ! did_action( 'woocommerce_loaded' ) && file_exists( WC_ABSPATH . 'includes/wc-core-functions.php' ) ) {
			include_once WC_ABSPATH . 'includes/wc-core-functions.php';
		}

		// Initialize session.
		if ( is_null( WC()->session ) ) {
			include_once WC_ABSPATH . 'includes/class-wc-session-handler.php';
			WC()->session = new WC_Session_Handler();
			WC()->session->init();
		}

		// Initialize customer.
		if ( is_null( WC()->customer ) ) {
			include_once WC_ABSPATH . 'includes/class-wc-customer.php';
			WC()->customer = new WC_Customer( get_current_user_id(), true );
		}

		// Initialize cart.
		if ( is_null( WC()->cart ) ) {
			if ( function_exists( 'wc_load_cart' ) ) {
				wc_load_cart(); // WooCommerce 4.6+
			} else {
				include_once WC_ABSPATH . 'includes/class-wc-cart.php';
				WC()->cart = new WC_Cart();
			}
		}

		// Initialize checkout.
		if ( is_null( WC()->checkout ) ) {
			include_once WC_ABSPATH . 'includes/class-wc-checkout.php';
			WC()->checkout = new WC_Checkout();
		}
	}
}

if( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Gets the site's base URL
 * 
 * @uses get_bloginfo()
 * 
 * @return string $url the site URL
 */
if( ! function_exists( 'solace_extra_site_url' ) ) :
function solace_extra_site_url() {
	$url = get_bloginfo( 'url' );

	return $url;
}
endif;

/**
 * List of Solace Extra widgets
 *
 * @icons https://elementor.github.io/elementor-icons/
 *
 * @since 1.0
 */
if( ! function_exists( 'solace_extra_widgets' ) ) :
function solace_extra_widgets() {

	$branding_class = ' wlbi solace-extra';
	$demo_base      = solace_extra_home_link();
	$single_product_url = 'single-product';

	$widgets = [
		/**
		 * Shop widgets
		 */
		'shop-classic'      => [
			'title'         => __( 'Shop Classic', 'solace-extra' ),
			'icon'          => 'eicon-gallery-grid' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-classic/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'regular' ],
		],
		'shop-standard'     => [
			'title'         => __( 'Shop Standard', 'solace-extra' ),
			'icon'          => 'eicon-apps' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-standard/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'regular' ],
		],
		'shop-flip'         => [
			'title'         => __( 'Shop Flip', 'solace-extra' ),
			'icon'          => 'eicon-flip-box' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-flip/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'elegant-horizontal' ],
			'pro_feature'   => true,
		],
		'shop-trendy'       => [
			'title'         => __( 'Shop Trendy', 'solace-extra' ),
			'icon'          => 'eicon-products' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-trendy/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'elegant-horizontal' ],
			'pro_feature'   => true,
		],
		'shop-curvy'        => [
			'title'         => __( 'Shop Curvy', 'solace-extra' ),
			'icon'          => 'eicon-posts-grid' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-curvy/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'elegant' ],
		],
		'shop-curvy-horizontal' => [
			'title'         => __( 'Shop Curvy Horizontal', 'solace-extra' ),
			'icon'          => 'eicon-posts-group' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-curvy-horizontal/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'elegant-horizontal' ],
			'pro_feature'   => true,
		],
		'shop-slider'       => [
			'title'         => __( 'Shop Slider', 'solace-extra' ),
			'icon'          => 'eicon-slider-device' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-slider/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'slider' ],
		],
		'shop-accordion'    => [
			'title'         => __( 'Shop Accordion', 'solace-extra' ),
			'icon'          => 'eicon-accordion' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-accordion/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'accordion' ],
			'pro_feature'   => true,
		],
		'shop-table'        => [
			'title'         => __( 'Shop Table', 'solace-extra' ),
			'icon'          => 'eicon-table' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-table/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'elegant-horizontal' ],
			'pro_feature'   => true,
		],
		'shop-beauty'       => [
			'title'         => __( 'Shop Beauty', 'solace-extra' ),
			'icon'          => 'eicon-thumbnails-half' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-beauty/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'elegant-horizontal' ],
			'pro_feature'   => true,
		],
		'shop-smart'        => [
			'title'         => __( 'Shop Smart', 'solace-extra' ),
			'icon'          => 'eicon-thumbnails-half' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-smart/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'elegant-horizontal' ],
			'pro_feature'   => true,
		],
		'shop-minimal'      => [
			'title'         => __( 'Shop Minimal', 'solace-extra' ),
			'icon'          => 'eicon-thumbnails-half' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-minimal/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'elegant-horizontal' ],
			'pro_feature'   => true,
		],
		'shop-wix'          => [
			'title'         => __( 'Shop Wix', 'solace-extra' ),
			'icon'          => 'eicon-thumbnails-half' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-wix/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'elegant-horizontal' ],
			'pro_feature'   => true,
		],
		'shop-shopify'      => [
			'title'         => __( 'Shop Shopify', 'solace-extra' ),
			'icon'          => 'eicon-thumbnails-half' . $branding_class,
			'categories'    => [ 'solace-extra-shop' ],
			'demo'          => "{$demo_base}/shop-shopify/",
			'keywords'      => [ 'cart', 'store', 'products', 'shop', 'grid', 'elegant-horizontal' ],
			'pro_feature'   => true,
		],

		/**
		 * Shop filter
		 */
		'filter-horizontal' => [
			'title'         => __( 'Filter Horizontal', 'solace-extra' ),
			'icon'          => 'eicon-ellipsis-h' . $branding_class,
			'categories'    => [ 'solace-extra-filter' ],
			'demo'          => "{$demo_base}/filter-horizontal/",
			'keywords'      => [ 'cart', 'store', 'products', 'product', 'single', 'single-product', 'filter', 'horizontal' ],
		],
		'filter-vertical'   => [
			'title'         => __( 'Filter Vertical', 'solace-extra' ),
			'icon'          => 'eicon-ellipsis-v' . $branding_class,
			'categories'    => [ 'solace-extra-filter' ],
			'demo'          => "{$demo_base}/filter-vertical/",
			'keywords'      => [ 'cart', 'store', 'products', 'product', 'single', 'single-product', 'filter', 'vertical' ],
			'pro_feature'   => true,
		],
		'filter-advance'    => [
			'title'         => __( 'Filter Advance', 'solace-extra' ),
			'icon'          => 'eicon-filter' . $branding_class,
			'categories'    => [ 'solace-extra-filter' ],
			'demo'          => "{$demo_base}/filter-advance/",
			'keywords'      => [ 'cart', 'store', 'products', 'product', 'single', 'single-product', 'filter', 'horizontal', 'advance' ],
			'pro_feature'   => true,
		],

		/*
		* Single Product
		*/
		'product-title'     => [
			'title'         => __( 'Product Title', 'solace-extra' ),
			'icon'          => 'eicon-post-title' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'store', 'products', 'product-title', 'single', 'single-product' ],
		],
		'product-price'     => [
			'title'         => __( 'Product Price', 'solace-extra' ),
			'icon'          => 'eicon-product-price' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'store', 'products', 'product-price', 'single', 'single-product' ],
		],
		'product-rating'    => [
			'title'         => __( 'Product Rating', 'solace-extra' ),
			'icon'          => 'eicon-product-rating' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'store', 'products', 'product-rating', 'single', 'single-product' ],
		],
		'product-breadcrumbs'   => [
			'title'         => __( 'Product Breadcrumbs', 'solace-extra' ),
			'icon'          => 'eicon-post-navigation' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'breadcrumbs', 'single', 'product' ],
		],
		'product-short-description' => [
			'title'         => __( 'Product Short Description', 'solace-extra' ),
			'icon'          => 'eicon-product-description' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'products', 'product', 'short', 'description', 'single', 'product' ],
		],
		'product-long-description' => [
			'title'         => __( 'Product Long Description', 'solace-extra' ),
			'icon'          => 'eicon-product-description' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'products', 'product', 'short', 'description', 'single', 'product' ],
		],		
		'product-sku'       => [
			'title'         => __( 'Product SKU', 'solace-extra' ),
			'icon'          => 'eicon-anchor' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'add to cart', 'sku', 'short', 'single', 'product' ],
		],
		'product-stock'     => [
			'title'         => __( 'Product Stock', 'solace-extra' ),
			'icon'          => 'eicon-product-stock' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'add to cart', 'product-stock', 'short', 'single', 'product' ],
		],
		'product-additional-information'    => [
			'title'         => __( 'Additional Information', 'solace-extra' ),
			'icon'          => 'eicon-product-info' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'add to cart', 'product-additional-information', 'short', 'single', 'product' ],
		],
		'product-meta'      => [
			'title'         => __( 'Product Meta', 'solace-extra' ),
			'icon'          => 'eicon-product-tabs' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'add to cart', 'product-meta', 'short', 'single', 'product' ],
		],
		'product-categories'    => [
			'title'         => __( 'Product Categories', 'solace-extra' ),
			'icon'          => 'eicon-menu-card' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'category', 'categories', 'short', 'single', 'product' ],
		],
		'product-tags'      => [
			'title'         => __( 'Product Tags', 'solace-extra' ),
			'icon'          => 'eicon-meta-data' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'add to cart', 'tags', 'short', 'single', 'product' ],
		],
		'product-gallery'       => [
			'title'         => __( 'Product Gallery', 'solace-extra' ),
			'icon'          => 'eicon-featured-image' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product', 'category', 'product-gallery' ],
		],
		'product-upsells'       => [
			'title'         => __( 'Product Upsells', 'solace-extra' ),
			'icon'          => 'eicon-products' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}",
			'keywords'      => [ 'cart', 'store', 'products', 'single', 'single-product', 'category', 'product-gallery', 'upsells' ],
		],		
		'product-purchase-summary'     => [
			'title'         => __( 'Purchase Summary', 'solace-extra' ),
			'icon'          => 'eicon-purchase-summary' . $branding_class,
			'categories'    => [ 'solace-extra-single' ],
			'demo'          => "{$demo_base}/{$single_product_url}/",
			'keywords'      => [ 'cart', 'store', 'products', 'product-title', 'single', 'single-product', 'woocommerce', 'summary', 'thank you', 'confirmation', 'purchase' ],
		],

		/**
		 * Pricing tables
		 */
		'pricing-table-advanced'    => [
			'title'         => __( 'Pricing Table Advanced', 'solace-extra' ),
			'icon'          => 'eicon-price-table' . $branding_class,
			'categories'    => [ 'solace-extra-pricing' ],
			'demo'          => "{$demo_base}/pricing-table-advanced/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing' ],
		],
		'pricing-table-basic'   => [
			'title'         => __( 'Pricing Table Basic', 'solace-extra' ),
			'icon'          => 'eicon-price-table' . $branding_class,
			'categories'    => [ 'solace-extra-pricing' ],
			'demo'          => "{$demo_base}/pricing-table-basic/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing' ],
		],
		'pricing-table-regular' => [
			'title'         => __( 'Pricing Table Regular', 'solace-extra' ),
			'icon'          => 'eicon-price-table' . $branding_class,
			'categories'    => [ 'solace-extra-pricing' ],
			'demo'          => "{$demo_base}/pricing-table-regular/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing' ],
			'pro_feature'   => true,
		],
		'pricing-table-smart'   => [
			'title'         => __( 'Pricing Table Smart', 'solace-extra' ),
			'icon'          => 'eicon-price-table' . $branding_class,
			'categories'    => [ 'solace-extra-pricing' ],
			'demo'          => "{$demo_base}/pricing-table-smart/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing' ],
			'pro_feature'   => true,
		],
		'pricing-table-fancy'   => [
			'title'         => __( 'Pricing Table Fancy', 'solace-extra' ),
			'icon'          => 'eicon-price-table' . $branding_class,
			'categories'    => [ 'solace-extra-pricing' ],
			'demo'          => "{$demo_base}/pricing-table-fancy/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing' ],
			'pro_feature'   => true,
		],

		/**
		 * Related Products
		 */
		'related-products-classic'  => [
			'title'         => __( 'Related Products Classic', 'solace-extra' ),
			'icon'          => 'eicon-gallery-grid' . $branding_class,
			'categories'    => [ 'solace-extra-related' ],
			'demo'          => "{$demo_base}/related-products-classic/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing', 'related-products-classic' ],
		],
		'related-products-standard' => [
			'title'         => __( 'Related Products Standard', 'solace-extra' ),
			'icon'          => 'eicon-apps' . $branding_class,
			'categories'    => [ 'solace-extra-related' ],
			'demo'          => "{$demo_base}/related-products-standard/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing', 'related-products-standard' ],
		],
		'related-products-flip' => [
			'title'         => __( 'Related Products Flip', 'solace-extra' ),
			'icon'          => 'eicon-flip-box' . $branding_class,
			'categories'    => [ 'solace-extra-related' ],
			'demo'          => "{$demo_base}/related-products-flip/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing', 'related-products-flip' ],
			'pro_feature'   => true,
		],
		'related-products-trendy'   => [
			'title'         => __( 'Related Products Trendy', 'solace-extra' ),
			'icon'          => 'eicon-products' . $branding_class,
			'categories'    => [ 'solace-extra-related' ],
			'demo'          => "{$demo_base}/related-products-trendy/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing', 'related-products-trendy' ],
			'pro_feature'   => true,
		],
		'related-products-curvy'    => [
			'title'         => __( 'Related Products Curvy', 'solace-extra' ),
			'icon'          => 'eicon-posts-grid' . $branding_class,
			'categories'    => [ 'solace-extra-related' ],
			'demo'          => "{$demo_base}/related-products-curvy/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing', 'related-products-curvy' ],
		],
		'related-products-accordion'    => [
			'title'         => __( 'Related Products Accordion', 'solace-extra' ),
			'icon'          => 'eicon-accordion' . $branding_class,
			'categories'    => [ 'solace-extra-related' ],
			'demo'          => "{$demo_base}/related-products-accordion/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing', 'related-products-accordion' ],
			'pro_feature'   => true,
		],
		'related-products-table'    => [
			'title'         => __( 'Related Products Table', 'solace-extra' ),
			'icon'          => 'eicon-table' . $branding_class,
			'categories'    => [ 'solace-extra-related' ],
			'demo'          => "{$demo_base}/related-products-table/",
			'keywords'      => [ 'cart', 'single', 'pricing-table', 'pricing', 'related-products-table' ],
			'pro_feature'   => true,
		],

		/**
		 * Photo gallery
		 */
		'gallery-fancybox'  => [
			'title'         => __( 'Gallery Fancybox', 'solace-extra' ),
			'icon'          => 'eicon-slider-push' . $branding_class,
			'categories'    => [ 'solace-extra-gallery' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'cart', 'single', 'product-gallery-fancybox' ],
		],
		'gallery-lc-lightbox'   => [
			'title'         => __( 'Gallery LC Lightbox', 'solace-extra' ),
			'icon'          => 'eicon-gallery-group' . $branding_class,
			'categories'    => [ 'solace-extra-gallery' ],
			'demo'          => "{$demo_base}/",
			'keywords'      => [ 'cart', 'single', 'product-gallery-lightbox' ],
		],
		'gallery-box-slider'    => [
			'title'         => __( 'Gallery Box Slider', 'solace-extra' ),
			'icon'          => 'eicon-slider-album' . $branding_class,
			'categories'    => [ 'solace-extra-gallery' ],
			'demo'          => "{$demo_base}/",
			'keywords'      => [ 'cart', 'single', 'product-gallery-adaptor' ],
		],

		/**
		 * Cart widgets
		 */
		'cart-items'        => [
			'title'         => __( 'Cart Items', 'solace-extra' ),
			'icon'          => 'eicon-products' . $branding_class,
			'categories'    => [ 'solace-extra-cart' ],
			'demo'          => "{$demo_base}/cart/",
			'keywords'      => [ 'cart', 'store', 'products', 'cart-items-standard' ],
		],
		'cart-items-classic'=> [
			'title'         => __( 'Cart Items Classic', 'solace-extra' ),
			'icon'          => 'eicon-products' . $branding_class,
			'categories'    => [ 'solace-extra-cart' ],
			'demo'          => "{$demo_base}/cart/",
			'keywords'      => [ 'cart', 'store', 'products', 'cart-items-standard' ],
		],
		'cart-overview'     => [
			'title'         => __( 'Cart Overview', 'solace-extra' ),
			'icon'          => 'eicon-product-price' . $branding_class,
			'categories'    => [ 'solace-extra-cart' ],
			'demo'          => "{$demo_base}/cart/",
			'keywords'      => [ 'cart', 'store', 'products', 'cart-overview-standard' ],
		],
		'coupon-form'       => [
			'title'         => __( 'Coupon Form', 'solace-extra' ),
			'icon'          => 'eicon-product-meta' . $branding_class,
			'categories'    => [ 'solace-extra-cart' ],
			'demo'          => "{$demo_base}/cart/",
			'keywords'      => [ 'cart', 'store', 'products', 'coupon-form-standard' ],
		],
		'floating-cart'       => [
			'title'         => __( 'Floating Cart', 'solace-extra' ),
			'icon'          => 'eicon-product-meta' . $branding_class,
			'categories'    => [ 'solace-extra-cart' ],
			'demo'          => "{$demo_base}/cart/",
			'keywords'      => [ 'cart', 'checkout', 'products', 'coupon-form-standard' ],
			'pro_feature'   => true,
		],

		/*
		*Checkout Page items
		*/
		'billing-address'   => [
			'title'         => __( 'Billing Address', 'solace-extra' ),
			'icon'          => 'eicon-google-maps' . $branding_class,
			'categories'    => [ 'solace-extra-checkout' ],
			'demo'          => "{$demo_base}/checkout/",
			'keywords'      => [ 'cart', 'single', 'billing', 'address', 'form' ],
			'pro_feature'   => true,
		],
		'shipping-address'  => [
			'title'         => __( 'Shipping Address', 'solace-extra' ),
			'icon'          => 'eicon-google-maps' . $branding_class,
			'categories'    => [ 'solace-extra-checkout' ],
			'demo'          => "{$demo_base}/checkout/",
			'keywords'      => [ 'cart', 'single', 'shipping', 'address', 'form' ],
			'pro_feature'   => true,
		],
		'order-notes'       => [
			'title'         => __( 'Order Notes', 'solace-extra' ),
			'icon'          => 'eicon-table-of-contents' . $branding_class,
			'categories'    => [ 'solace-extra-checkout' ],
			'demo'          => "{$demo_base}/checkout/",
			'keywords'      => [ 'cart', 'single', 'shipping', 'address', 'form', 'order', 'notes' ],
			'pro_feature'   => true,
		],
		'order-review'      => [
			'title'         => __( 'Order Review', 'solace-extra' ),
			'icon'          => 'eicon-product-info' . $branding_class,
			'categories'    => [ 'solace-extra-checkout' ],
			'demo'          => "{$demo_base}/checkout/",
			'keywords'      => [ 'cart', 'single', 'shipping', 'address', 'form', 'order', 'notes', 'review' ],
			'pro_feature'   => true,
		],
		'order-pay'         => [
			'title'         => __( 'Order Pay', 'solace-extra' ),
			'icon'          => 'eicon-product-info' . $branding_class,
			'categories'    => [ 'solace-extra-checkout' ],
			'demo'          => "{$demo_base}/checkout/",
			'keywords'      => [ 'cart', 'single', 'shipping', 'address', 'form', 'order', 'notes', 'review', 'pay' ],
			'pro_feature'   => true,
		],
		'payment-methods'   => [
			'title'         => __( 'Payment Methods', 'solace-extra' ),
			'icon'          => 'eicon-product-upsell' . $branding_class,
			'categories'    => [ 'solace-extra-checkout' ],
			'demo'          => "{$demo_base}/checkout/",
			'keywords'      => [ 'cart', 'single', 'shipping', 'address', 'form', 'order', 'notes', 'review', 'payment', 'methods' ],
			'pro_feature'   => true,
		],
		'thankyou'          => [
			'title'         => __( 'Thank You', 'solace-extra' ),
			'icon'          => 'eicon-nerd' . $branding_class,
			'categories'    => [ 'solace-extra-checkout' ],
			'demo'          => "{$demo_base}/checkout/",
			'keywords'      => [ 'cart', 'single', 'shipping', 'address', 'form', 'order', 'notes', 'review', 'thank', 'you', 'thankyou' ],
			'pro_feature'   => true,
		],
		'checkout-login'    => [
			'title'         => __( 'Checkout Login', 'solace-extra' ),
			'icon'          => 'eicon-lock-user' . $branding_class,
			'categories'    => [ 'solace-extra-checkout' ],
			'demo'          => "{$demo_base}/checkout/",
			'keywords'      => [ 'cart', 'single', 'shipping', 'address', 'form', 'order', 'notes', 'review', 'thank', 'you', 'thankyou' ],
			'pro_feature'   => true,
		],

		/*
		* Email Widgets
		*/
		'email-header'      => [
			'title'         => __( 'Email Header', 'solace-extra' ),
			'icon'          => 'eicon-header' . $branding_class,
			'categories'    => [ 'solace-extra-email' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'email', 'email-header' ],
			'pro_feature'   => true,
		],
		'email-footer'      => [
			'title'         => __( 'Email Footer', 'solace-extra' ),
			'icon'          => 'eicon-footer' . $branding_class,
			'categories'    => [ 'solace-extra-email' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'email', 'email-footer' ],
			'pro_feature'   => true,
		],
		'email-item-details'        => [
			'title'         => __( 'Email Item Details', 'solace-extra' ),
			'icon'          => 'eicon-kit-details' . $branding_class,
			'categories'    => [ 'solace-extra-email' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'email', 'email-item-details' ],
			'pro_feature'   => true,
		],
		'email-billing-addresses'       => [
			'title'         => __( 'Email Billing Addresses', 'solace-extra' ),
			'icon'          => 'eicon-table-of-contents' . $branding_class,
			'categories'    => [ 'solace-extra-email' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'email', 'email-billing-addresses' ],
			'pro_feature'   => true,
		],
		'email-shipping-addresses'      => [
			'title'         => __( 'Email Shipping Addresses', 'solace-extra' ),
			'icon'          => 'eicon-purchase-summary' . $branding_class,
			'categories'    => [ 'solace-extra-email' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'email', 'email-shipping-addresses' ],
			'pro_feature'   => true,
		],
		'email-customer-note'       => [
			'title'         => __( 'Email Customer Note', 'solace-extra' ),
			'icon'          => 'eicon-document-file' . $branding_class,
			'categories'    => [ 'solace-extra-email' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'email', 'email-customer-note' ],
			'pro_feature'   => true,
		],
		'email-order-note'      => [
			'title'         => __( 'Email Order Note', 'solace-extra' ),
			'icon'          => 'eicon-document-file' . $branding_class,
			'categories'    => [ 'solace-extra-email' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'email', 'email-customer-note', 'email-order-note' ],
			'pro_feature'   => true,
		],
		'email-description'     => [
			'title'         => __( 'Email Description', 'solace-extra' ),
			'icon'          => 'eicon-menu-toggle' . $branding_class,
			'categories'    => [ 'solace-extra-email' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'email', 'email-description' ],
			'pro_feature'   => true,
		],
		'email-reminder'     => [
			'title'         => __( 'Email Reminder', 'solace-extra' ),
			'icon'          => 'eicon-menu-toggle' . $branding_class,
			'categories'    => [ 'solace-extra-email' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'email', 'email-reminder' ],
			'pro_feature'   => true,
		],

		/*
		* Others Widgets
		*/
		'my-account'        => [
			'title'         => __( 'My Account', 'solace-extra' ),
			'icon'          => 'eicon-call-to-action' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/my-account/",
			'keywords'      => [ 'my', 'account', 'cart', 'customer' ],
		],
		'my-account-advanced'       => [
			'title'         => __( 'My Account Advanced', 'solace-extra' ),
			'icon'          => 'eicon-call-to-action' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/my-account/",
			'keywords'      => [ 'my', 'account', 'cart', 'customer' ],
			'pro_feature'   => true,
		],
		'wishlist'          => [
			'title'         => __( 'Wishlist', 'solace-extra' ),
			'icon'          => 'eicon-heart-o' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/wishlist/",
			'keywords'      => [ 'cart', 'store', 'products', 'coupon-form-standard', 'wish', 'list' ],
			'pro_feature'   => true,
		],
		'customer-reviews-classic'      => [
			'title'         => __( 'Customer Reviews Classic', 'solace-extra' ),
			'icon'          => 'eicon-product-rating' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/customer-reviews-classic/",
			'keywords'      => [ 'cart', 'single', 'shipping', 'address', 'form', 'order', 'notes', 'review', 'customer-reviews-vertical', 'customer-reviews', 'vertical' ],
		],
		'customer-reviews-standard'     => [
			'title'         => __( 'Customer Reviews Standard', 'solace-extra' ),
			'icon'          => 'eicon-review' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/customer-reviews-standard/",
			'keywords'      => [ 'cart', 'single', 'shipping', 'address', 'form', 'order', 'notes', 'review', 'customer-reviews-horizontal', 'customer-reviews', 'horizontal' ],
			'pro_feature'   => true,
		],
		'customer-reviews-trendy'       => [
			'title'         => __( 'Customer Reviews Trendy', 'solace-extra' ),
			'icon'          => 'eicon-rating' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/customer-reviews-trendy/",
			'keywords'      => [ 'cart', 'single', 'shipping', 'address', 'form', 'order', 'notes', 'review', 'customer-reviews-horizontal', 'customer-reviews', 'horizontal' ],
			'pro_feature'   => true,
		],
		'faqs-accordion'        => [
			'title'         => __( 'FAQs Accordion', 'solace-extra' ),
			'icon'          => 'eicon-accordion' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/faqs-accordion",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product' ],
			'pro_feature'   => true,
		],
		'tabs-basic'        => [
			'title'         => __( 'Tabs Basic', 'solace-extra' ),
			'icon'          => 'eicon-tabs' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/tabs-basic/",
			'keywords'      => [ 'tab', 'tabs', 'content tab', 'menu', 'tabs basic' ],
		],
		'tabs-classic'      => [
			'title'         => __( 'Tabs Classic', 'solace-extra' ),
			'icon'          => 'eicon-tabs' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/tabs-classic",
			'keywords'      => [ 'tab', 'tabs', 'content tab', 'menu', 'tabs classic' ],
		],
		'tabs-fancy'        => [
			'title'         => __( 'Tabs Fancy', 'solace-extra' ),
			'icon'          => 'eicon-tabs' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/tabs-fancy",
			'keywords'      => [ 'tab', 'tabs', 'content tab', 'menu', 'tabs fancy' ],
		],
		'tabs-beauty'       => [
			'title'         => __( 'Tabs Beauty', 'solace-extra' ),
			'icon'          => 'eicon-tabs' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/tabs-beauty",
			'keywords'      => [ 'tab', 'tabs', 'content tab', 'menu', 'tabs beauty' ],
		],
		'gradient-button'       => [
			'title'         => __( 'Gradient Button', 'solace-extra' ),
			'icon'          => 'eicon-button' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/gradient-button",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product' ],
		],
		'sales-notification'        => [
			'title'         => __( 'Sales Notification', 'solace-extra' ),
			'icon'          => 'eicon-posts-ticker' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/sales-notification",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product' ],
			'pro_feature'   => true,
		],
		'category'          => [
			'title'         => __( 'Shop Categories', 'solace-extra' ),
			'icon'          => 'eicon-flow' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/shop-categories",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product', 'category' ],
			'pro_feature'   => true,
		],
		'basic-menu'        => [
			'title'         => __( 'Basic Menu', 'solace-extra' ),
			'icon'          => 'eicon-nav-menu' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/basic-menu",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product', 'category', 'basic-menu' ],
			'pro_feature'   => true,
		],
		'dynamic-tabs'      => [
			'title'         => __( 'Dynamic Tabs', 'solace-extra' ),
			'icon'          => 'eicon-tabs' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product', 'category', 'dynamic-tabs' ],
			'pro_feature'   => true,
		],
		// 'google-review'      => [
		//  'title'         => __( 'Google Review', 'solace-extra' ),
		//  'icon'          => 'eicon-review' . $branding_class,
		//  'categories'    => [ 'solace_extra' ],
		//  'demo'          => "{$demo_base}/google-review",
		//  'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product', 'category', 'google-review' ],
		//  'pro_feature'   => true,
		// ],
		'menu-cart'         => [
			'title'         => __( 'Menu Cart', 'solace-extra' ),
			'icon'          => 'eicon-cart' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/menu-cart",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product', 'category', 'menu-cart' ],
			'pro_feature'   => true,
		],
		'product-comparison'        => [
			'title'         => __( 'Product Comparison', 'solace-extra' ),
			'icon'          => 'eicon-cart' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/product-comparison",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product', 'category', 'product-comparison' ],
			'pro_feature'   => true,
		],
		'product-barcode'       => [
			'title'         => __( 'Product Barcode', 'solace-extra' ),
			'icon'          => 'eicon-cart' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/product-comparison",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product', 'category', 'product-comparison' ],
			'pro_feature'   => true,
		],
		'image-comparison'      => [
			'title'         => __( 'Image Comparison', 'solace-extra' ),
			'icon'          => 'eicon-cart' . $branding_class,
			'categories'    => [ 'solace_extra' ],
			'demo'          => "{$demo_base}/product-comparison",
			'keywords'      => [ 'cart', 'store', 'products', 'tabs-beauty', 'single', 'single-product', 'category', 'product-comparison' ],
		],
	];

	return apply_filters( 'solace_extra_widgets', $widgets );
}
endif;

if( ! function_exists( 'solace_extra_modules' ) ) :
function solace_extra_modules() {
	$modules = [

		'email-templates'    => [
			'id'            => 'email-templates',
			'title'         => __( 'Email Designer', 'solace-extra' ),
			'desc'          => __( 'This module is a great tool to improve the look and feel of your WooCommerce emails. With this module, you can create WooCommerce emails that are more engaging and more likely to get attention from your users.', 'solace-extra' ),
			'class'         => 'Email_Templates',
			'pro'           => true,
		],

		'invoice-builder'    => [
			'id'            => 'invoice-builder',
			'title'         => __( 'Invoice Builder', 'solace-extra' ),
			'desc'          => __( 'The WooCommerce Invoice Builder module allows you to create custom invoices for your WooCommerce store easily. You can also change the design of your invoices. It provides you with the option to add styles and information to make the invoice more personalized.', 'solace-extra' ),
			'class'         => 'Invoice_Builder',
			'pro'           => true,
		],
		
		'product-brands'    => [
			'id'            => 'product-brands',
			'title'         => __( 'Product Brands', 'solace-extra' ),
			'desc'          => __( 'Let the buyers get the sense of the brand with Solace Extra’s Product Brand Module. This module seamlessly categorizes and showcases your products along with their brand names making it easy for shoppers to discover what they love.', 'solace-extra' ),
			'class'         => 'Product_Brands',
			'pro'           => false,
		],

		'cart-button-text'    => [
			'id'            => 'cart-button-text',
			'title'         => __( 'Add To Cart Text', 'solace-extra' ),
			'desc'          => __( 'Tired of the generic "Add to Cart"? With CoDesginer, your "Add to Cart" button becomes a powerful conversion tool. Personalize the add to cart button for your WooCommerce shop and single product page with custom text.', 'solace-extra' ),
			'class'         => 'Add_To_Cart_Text',
			'pro'           => false,
		],

		'checkout-builder'    => [
			'id'            => 'checkout-builder',
			'title'         => __( 'Checkout Builder', 'solace-extra' ),
			'desc'          => __( 'Offer the best user experience by customizing the WooCommerce checkout page using Solace Extra. And, break down the checkout page to make it more manageable with the multistep checkout template.', 'solace-extra' ),
			'class'         => 'Checkout_Builder',
			'pro'           => true,
		],

		'skip-cart-page'    => [
			'id'            => 'skip-cart-page',
			'title'         => __( 'Skip Cart Page', 'solace-extra' ),
			'desc'          => __( 'Let your buyers skip the WooCommerce cart page and seamlessly redirect them straight to checkout for a faster, frictionless buying experience. This powerful module streamlines the customer journey by encouraging purchases and maximizing conversions.', 'solace-extra' ),
			'class'         => 'Skip_Cart_Page',
			'pro'           => false,
		],

		'variation-swatches'    => [
			'id'            => 'variation-swatches',
			'title'         => __( 'Variation Swatches', 'solace-extra' ),
			'desc'          => __( 'Solace Extra variation swatches ensure a refreshing user experience. This module provides an alternative to the default WooCommerce dropdown fields. The variations on your products can be their actual colors, images, labels, etc.', 'solace-extra' ),
			'class'         => 'variation_swatches',
			'pro'           => false,
		],

		'flash-sale'    => [
			'id'            => 'flash-sale',
			'title'         => __( 'Flash Sale', 'solace-extra' ),
			'desc'          => __( 'The flash sale module helps to create buzz and anticipation. You can engage customers to purchase products at a discount rate by showing a Flash Sale timer. This creates a sense of urgency among the shoppers to purchase products as fast as possible.', 'solace-extra' ),
			'class'         => 'Flash_Sale',
			'pro'           => false,
		],

		'partial-payment'    => [
			'id'            => 'partial-payment',
			'title'         => __( 'Partial Payment', 'solace-extra' ),
			'desc'          => __( 'CoDesginer\'s "Partial Payment" module helps your customers break down the costs. This makes the larger purchases more attainable. You will be able to get more conversions by providing users this flexibility to make partial payments on your WooCommerce store.', 'solace-extra' ),
			'class'         => 'Partial_Payment',
			'pro'           => false,
		],

		'backorder'    => [
			'id'            => 'backorder',
			'title'         => __( 'Backorder', 'solace-extra' ),
			'desc'          => __( 'This module lets you confidently sell products even when out of stock. Add an extra capability to set a backorder availability date for your WooCommerce products. Accept orders now, deliver later, and keep conversions going.', 'solace-extra' ),
			'class'         => 'Backorder',
			'pro'           => false,
		],

		'preorder'    => [
			'id'            => 'preorder',
			'title'         => __( 'Preorder', 'solace-extra' ),
			'desc'          => __( 'With this module, you can create buzz and secure sales before products even launch. Set the pre-order availability date and specific release dates for your products in WooCommerce product management dashboard. Build anticipation, drive engagement, and manage releases effortlessly.', 'solace-extra' ),
			'class'         => 'Preorder',
			'pro'           => false,
		],

		'bulk-purchase-discount'    => [
			'id'            => 'bulk-purchase-discount',
			'title'         => __( 'Bulk Purchase Discount', 'solace-extra' ),
			'desc'          => __( 'Turn casual shoppers into bulk buyers with CoDesginer\'s "Bulk Purchase Discount" module. Set compelling tiered discounts that make it irresistible to stock up. Great technique for driving conversions and exceeding expectations.', 'solace-extra' ),
			'class'         => 'Bulk_Purchase_Discount',
			 'pro'           => false,
		],

		'single-product-ajax'   => [
			'id'            => 'single-product-ajax',
			'title'         => __( 'Single Product Ajax Add To Cart', 'solace-extra' ),
			'desc'          => __( 'CoDesginer\'s "Single Product Ajax Add To Cart" module creates a frictionless shopping experience. Let users instantly add their products to the cart without any page reload. This helps to guide customers smoothly towards checkout and drive sales.', 'solace-extra' ),
			'class'         => 'Single_Product_Ajax',
			 'pro'           => false,
		],

		'badges'    => [
			'id'            => 'badges',
			'title'         => __( 'Badges', 'solace-extra' ),
			'desc'          => __( 'Highlight your most popular items, new releases, or unique offerings with eye-catching badges that instantly capture attention. Using this module you can use images, icons, or text as product badges to highlight a product on a shop page.', 'solace-extra' ),
			'class'         => 'Badges',
			'pro'           => false,
		],

		'currency-switcher'    => [
			'id'            => 'currency-switcher',
			'title'         => __( 'Currency Switcher', 'solace-extra' ),
			'desc'          => __( 'Show customers you value their preferences with Solace Extra’s "Currency Switcher". Let customers view prices in their familiar currencies on all WooCommerce pages including the shop, cart, product, and checkout page. This helps to create a seamless shopping experience that drives international sales.', 'solace-extra' ),
			'class'         => 'Currency_Switcher',
			'pro'           => false,
		],
	];

	return $modules;
}
endif;

/**
 * 
 * Home Url Link
 */

if( ! function_exists( 'solace_extra_home_link' ) ) :
function solace_extra_home_link() {
	return 'https://solacewp.com/solace-extra';
}
endif;


/**
 * List widgets enabled by the admin
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_active_widgets' ) ) :
function solace_extra_active_widgets() {
	$active_widgets = get_option( 'solace_extra_widgets' ) ? : [];

	return apply_filters( 'solace_extra_active_widgets', array_keys( $active_widgets ) );
}
endif;

/**
 * List of Solace Extra widget categories
 *
 * @since 1.0
 */
if( ! function_exists( 'solace_extra_widget_categories' ) ) :
function solace_extra_widget_categories() {
	$categories = [
		'solace-extra-woocommerce' => [
			'title' => __( 'Solace Extra - WooCommerce', 'solace-extra' ),
			'icon'  => 'eicon-cart',
		],
		'solace-extra-shop' => [
			'title' => __( 'Solace Extra - Shop', 'solace-extra' ),
			'icon'  => 'eicon-cart',
		],
		// 'solace-extra-filter' => [
		// 	'title' => __( 'Solace Extra - Filter', 'solace-extra' ),
		// 	'icon'  => 'eicon-search',
		// ],
		'solace-extra-archive' => [
			'title' => __( 'Solace Extra - Blog', 'solace-extra' ),
			'icon'  => 'eicon-cart',
		],
		'solace-extra-single-post' => [
			'title' => __( 'Solace Extra - Single Post', 'solace-extra' ),
			'icon'  => 'eicon-cart',
		],
		'solace-extra-single' => [
			'title' => __( 'Solace Extra - Single Product', 'solace-extra' ),
			'icon'  => 'eicon-cart',
		],
		// 'solace-extra-pricing' => [
		// 	'title' => __( 'Solace Extra - Pricing Table', 'solace-extra' ),
		// 	'icon'  => 'eicon-cart',
		// ],
		// 'solace-extra-related' => [
		// 	'title' => __( 'Solace Extra - Related Products', 'solace-extra' ),
		// 	'icon'  => 'eicon-cart',
		// ],
		// 'solace-extra-gallery' => [
		// 	'title' => __( 'Solace Extra - Image Gallery', 'solace-extra' ),
		// 	'icon'  => 'eicon-photo-library',
		// ],
		'solace-extra-cart' => [
			'title' => __( 'Solace Extra - Cart', 'solace-extra' ),
			'icon'  => 'eicon-cart',
		],
		'solace-extra-checkout' => [
			'title' => __( 'Solace Extra - Checkout', 'solace-extra' ),
			'icon'  => 'eicon-cart',
		],
		// 'solace-extra-email' => [
		// 	'title' => __( 'Solace Extra - Email', 'solace-extra' ),
		// 	'icon'  => 'eicon-cart',
		// ],
		// 'solace_extra' => [
		// 	'title' => __( 'Solace Extra - Others', 'solace-extra' ),
		// 	'icon'  => 'eicon-skill-bar',
		// ],
	];

	return apply_filters( 'solace_extra_widget_categories', $categories );
}
endif;

/**
 * Determines if the pro version is activated
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_is_pro_activated' ) ) :
function solace_extra_is_pro_activated() {
	global $solace_extra_pro;
	return isset( $solace_extra_pro['license'] ) && $solace_extra_pro['license']->_is_activated();
}
endif;

/**
 * Wc Help Link
 *
 * @since 1.0
 */

if( ! function_exists( 'solace_extra_help_link' ) ) :
function solace_extra_help_link() {
	if( solace_extra_is_pro() ) {
		return 'https://solacewp.com/solace-extra/docs/general/';
	}

	return 'https://wordpress.org/support/plugin/solace-extra';
}
endif;

/**
 * Determines if the pro version is installed
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_is_pro' ) ) :
function solace_extra_is_pro() {
	return apply_filters( 'solace-extra-is_pro', false );
}
endif;

/**
 * Determines if a widget is a pro feature or not
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_is_pro_feature' ) ) :
function solace_extra_is_pro_feature( $id ) {
	$widgets = solace_extra_widgets();
	return isset( $widgets[ $id ]['pro_feature'] ) && $widgets[ $id ]['pro_feature'];
}
endif;

/**
 * Get widget $id from __CLASS__ name
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_get_widget_id' ) ) :
function solace_extra_get_widget_id( $__CLASS__ ) {
	
	// if it's under a namespace
	if( strpos( $__CLASS__, '\\' ) !== false ) {
		$path = explode( '\\', $__CLASS__ );
		$__CLASS__ = array_pop( $path );
	}

	return strtolower( str_replace( '_', '-', $__CLASS__ ) );
}
endif;

/**
 * Get a widget data by $id or __CLASS__
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_get_widget' ) ) :
function solace_extra_get_widget( $id ) {
	$widgets = solace_extra_widgets();

	// if a __CLASS__ name was supplied as $id
	$id = solace_extra_get_widget_id( $id );

	return isset( $widgets[ $id ] ) ? $widgets[ $id ] : false;
}
endif;

/**
 * Checks either we're in the preview mode
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_is_preview_mode' ) ) :
function solace_extra_is_preview_mode( $post_id = 0 ) {
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( !current_user_can( 'edit_post', $post_id ) ) {
		return false;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( !isset( $_GET['preview_id'] ) || $post_id !== (int) $_GET['preview_id'] ) {
		return false;
	}

	return true;
}
endif;

/**
 * Checks either we're in the edit mode
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_is_edit_mode' ) ) :
function solace_extra_is_edit_mode( $post_id = 0 ) {
	return \Elementor\Plugin::$instance->editor->is_edit_mode( $post_id );
}
endif;

/**
 * Check if WooCommerce is activated
 *
 * phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Public API, used by theme/other plugins
 */
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

/**
 * Return recently sold products
 *
 * @since 3.0
 * @author Al Imran Akash <alimranakash.bd@gmail.com>
 */
if( !function_exists( 'solace_extra_recently_sold_products' ) ) :
function solace_extra_recently_sold_products( $product_limit ) {
	$product_ids = [];

	$args = [
		'post_type'         => 'shop_order',
		'post_status'       => 'wc-completed',
		'numberposts'       => -1,
	];

	$posts = get_posts( $args );

	$count = 1;
	foreach( $posts as $post ) {
		$order  = new WC_Order( $post->ID );
		$items  = $order->get_items();

		foreach ( $items as $item ) {
			if ( $count > $product_limit ) {
				break;
			}

			$product        = $item->get_product();
			$product_ids[]  = $product->get_id();
			$count++;
		}
	}

	return apply_filters( 'solace_extra_recently_sold_products', $product_ids );
}
endif;

/**
 * Return product source type
 *
 * @since 3.0
 * @author Al Imran Akash <alimranakash.bd@gmail.com>
 */
if( !function_exists( 'solace_extra_product_source_type' ) ) :
function solace_extra_product_source_type() {
	$options = [
		'shop'                  => __( 'Shop', 'solace-extra' ),
		'related-products'      => __( 'Related Products', 'solace-extra' ),
		'upsells'               => __( 'Up Sells', 'solace-extra' ),
		'cross-sells'           => __( 'Cross Sells', 'solace-extra' ),
		'cart-upsells'          => __( 'Cart Up Sells', 'solace-extra' ),
		'cart-cross-sells'      => __( 'Cart Cross Sells', 'solace-extra' ),
		'cart-related-products' => __( 'Cart Related Products', 'solace-extra' ),
	];

	return apply_filters( 'solace_extra_product_source_type', $options );
}
endif;

/**
 * Order options used for product query
 *
 * @since 1.0
 *
 * @return []
 */
if( !function_exists( 'solace_extra_order_options' ) ) :
function solace_extra_order_options() {
	$options = [
		'none'                  => __( 'None', 'solace-extra' ),
		'ID'                    => __( 'ID', 'solace-extra' ),
		'title'                 => __( 'Title', 'solace-extra' ),
		'name'                  => __( 'Name', 'solace-extra' ),
		'date'                  => __( 'Date', 'solace-extra' ),
		'rand'                  => __( 'Random', 'solace-extra' ),
		'menu_order'            => __( 'Menu Order', 'solace-extra' ),
		'_price'                => __( 'Product Price', 'solace-extra' ),
		'total_sales'           => __( 'Top Seller', 'solace-extra' ),
		'comment_count'         => __( 'Most Reviewed', 'solace-extra' ),
		'_wc_average_rating'    => __( 'Top Rated', 'solace-extra' ),
	];

	return apply_filters( 'solace-extra-order_options', $options );
}
endif;

/**
 * List product categories
 *
 * @since 1.0
 *
 * @return array
 */
if( !function_exists( 'solace_extra_get_terms' ) ) :
function solace_extra_get_terms( $taxonomy = 'product_cat' ) {

	$terms = get_terms( [ 'taxonomy' => $taxonomy, 'hide_empty' => false ] );
	$cats = [];
	if ( is_array( $terms ) ) {     
		foreach ( $terms as $term ) {
			if ( isset( $term->term_id ) ) {
				$cats[ $term->term_id ] = $term->name;
			}
		}
	}
	return $cats;
}
endif;

/**
 * Returns the text (Pro) if pro version is not activated.
 *
 * @return boolean
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_pro_text' ) ) :
function solace_extra_pro_text() {
	return ( solace_extra_is_pro_activated() ? '' : '<span class="wl-pro-text"> ('. __( 'PRO', 'solace-extra' ) .')</span>' );
}
endif;

/**
 * Get list of taxonomies
 *
 * @return []
 */
if( !function_exists( 'solace_extra_get_taxonomies' ) ) :
function solace_extra_get_taxonomies() {
	$_taxonomies = get_object_taxonomies( 'product' );
	$taxonomies = [];
	foreach ( $_taxonomies as $_taxonomy ) {
		$taxonomy = get_taxonomy( $_taxonomy );
		if( $taxonomy->show_ui ) {
			$taxonomies[ $_taxonomy ] = $taxonomy->label;
		}
	}
	
	return $taxonomies;
}
endif;

/**
 * Return the template types
 *
 * @since 3.0
 * @param $args, give array value. unset field
 * @author Al Imran Akash <alimranakash.bd@gmail.com>
 */
if( !function_exists( 'solace_extra_get_shop_options' ) ) :
function solace_extra_get_shop_options( $args = [] ) {
	$widgets = solace_extra_widgets_by_category();

	$options = [ '' => __( "Select a shop", 'solace-extra' ) ];
	foreach ( $widgets as $key => $widget ) {
		$options[ $key ] = $widget['title'];
	}
	return $options;
}
endif;

/**
 * List of Solace Extra widget of a single category
 * 
 * 
 * @author Jakaria Istauk <jakariamd35@gmail.com>
 * 
 * @since 3.0
 */
if( !function_exists( 'solace_extra_widgets_by_category' ) ) :
function solace_extra_widgets_by_category( $category = 'solace-extra-shop' ) {
	$all_widgets = solace_extra_widgets();
	$category_widgets = [];
	foreach ( $all_widgets as $name => $widget ) {
		if ( in_array( $category, $widget['categories'] ) ) {
			$category_widgets[ $name ] = $widget;
		}
	}

	return $category_widgets;
}
endif;

/**
 * Get meta keys
 *
 * @return array
 */
if ( !function_exists( 'solace_extra_get_product_id' ) ) :
	function solace_extra_get_product_id( $type = '' ) {

		global $post;

		if ( ! is_woocommerce_activated() ) return false; 

		if ( $post && $post->post_type == 'product' ) {
			return $post->ID;
		}

		$args = [  
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 1, 
			'order'          => 'DESC',
			'orderby'        => 'rand',
		];

		if ( $type != '' ) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			$args['tax_query'] = [
				[
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => $type, 
				],
			];
		}

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			$query->the_post();
			$id = get_the_ID();
			wp_reset_postdata();
			return $id;
		}

		wp_reset_postdata();
		return false;
	}
endif;


/**
 * Get the attributes which are not in variations
 *
 * @var int $attachment_id
 *
 * @return []
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_attrs_notin_variations' ) ) :
function solace_extra_attrs_notin_variations( $attributes, $product ) {

	if ( count( $attributes ) < 1 ) return;
	
	$extra_attrs = [];
	foreach ( $attributes as $vkey => $variation_attr ) {
		if( $attributes[ $vkey ] == '' ){
			$term_key = explode( 'attribute_', $vkey );
			$get_attrs = $product->get_attribute( $term_key[1] );
			$attrs = explode( '|', $get_attrs );
			$extra_attrs[ $vkey ] = $attrs;
		}
	}

	return $extra_attrs;
}
endif;

/**
 * Sanitize number input
 * 
 * @param mix $value the value
 * 
 * @uses sanitize_text_field()
 * 
 * @return int The sanitized value
 */
if( ! function_exists( 'solace_extra_sanitize_number' ) ) :
function solace_extra_sanitize_number( $value, $type = 'int' ){
	if ( $type == 'float' ) {
		return (float) sanitize_text_field( $value );
	}
	else{
		return (int) sanitize_text_field( $value );
	}
}
endif;

/**
 * Gets list of gallery images from a product
 *
 * @var int $product_id
 *
 * @return []
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_product_gallery_images' ) ) :
function solace_extra_product_gallery_images( $product_id ) {

	if( !function_exists( 'WC' ) ) return;

	if( get_post_type( $product_id ) !== 'product' ) return;

	$product    = wc_get_product( $product_id );
	$image_ids  = $product->get_gallery_image_ids();

	$images     = [];
	foreach ( $image_ids as $image_id ) {
		$images[] = [
			'id'    => $image_id,
			'url'   => wp_get_attachment_url( $image_id ),
		];
	}

	return $images;
}
endif;

/**
 * Get an attachment with additional data
 *
 * @var int $attachment_id
 *
 * @return []
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_get_attachment' ) ) :
function solace_extra_get_attachment( $attachment_id ) {

	$attachment = get_post( $attachment_id );

	if( !$attachment ) return false;

	return [
		'alt'           => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption'       => $attachment->post_excerpt,
		'description'   => $attachment->post_content,
		'href'          => get_permalink( $attachment->ID ),
		'src'           => $attachment->guid,
		'title'         => $attachment->post_title
	];
}
endif;

/**
 * Checkout form fields
 *
 * @var string $section billing, shipping or order
 *
 * @return []
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_checkout_fields' ) ) :
	function solace_extra_checkout_fields( $section = 'billing' ) {
		if( !function_exists( 'WC' ) ) return [];

		if ( is_admin() ) {
			WC()->session = new \WC_Session_Handler();
			WC()->session->init();
		}

		$get_fields = WC()->checkout->get_checkout_fields();

		$fields = [];
		foreach ( $get_fields[ $section ] as $key => $field ) {
			if( isset( $field['label'] ) ) {
				$fields[] = [
					"{$section}_input_label"        => $field['label'],
					"{$section}_input_name"         => $key,
					"{$section}_input_required"     => isset( $field['required'] ) ? $field['required'] : false,
					"{$section}_input_type"         => isset( $field['type'] ) ? $field['type'] : 'text',
					"{$section}_input_class"        => $field['class'] ,
					"{$section}_input_autocomplete" => isset( $field['autocomplete'] ) ? $field['autocomplete'] : '' ,
					"{$section}_input_placeholder"  => isset( $field['placeholder'] ) ? $field['placeholder'] : '' ,
				];
			}
		}

		return $fields;
	}
endif;

/**
 * Populates a notice
 *
 * @var string $text the text to show
 * @var string $heading the heading
 * @var array $modes available screens [ live, preview, edit ]
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_notice' ) ) :
function solace_extra_notice( $text, $heading = null, $modes = [ 'edit', 'preview' ] ) {
	if(
		solace_extra_is_preview_mode() && !in_array( 'preview', $modes ) ||
		solace_extra_is_edit_mode() && !in_array( 'edit', $modes ) ||
		solace_extra_is_live_mode() && !in_array( 'live', $modes )
	) return;

	if( is_null( $heading ) ) {
		$heading = '<i class="eicon-warning"></i> ' . __( 'Admin Notice', 'solace-extra' );
	}
	
	$notice = "
	<div class='wl-notice'>
		<h3>{$heading}</h3>
		<p>{$text}</p>
	</div>";

	return $notice;
}
endif;

/**
 * Checks either we're in the live mode
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_is_live_mode' ) ) :
function solace_extra_is_live_mode( $post_id = 0 ) {
	return !solace_extra_is_edit_mode( $post_id ) && !solace_extra_is_preview_mode( $post_id );
}
endif;

/**
 * Return elementor template library list
 * 
 * @param string $template_type ex: 'wl-tab'
 *  
 */
if( !function_exists( 'solace_extra_get_template_list' ) ) :
function solace_extra_get_template_list( $template_type = 'wl-tab' ){

	$args = [  
		'post_type'      => 'elementor_library',
		'post_status'    => 'publish',
		'posts_per_page' => -1, 
		'order'          => 'DESC',
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		'meta_query'     => [
			'relation'   => 'AND',
			[
				'key'       => '_elementor_template_type',
				'value'     => $template_type,
			]
		]
	];

	$result = new \WP_Query( $args ); 
	$_tabs  = $result->posts;

	$tabs = [];
	foreach ( $_tabs as $tab ) {
		$tabs[ $tab->ID ] = $tab->post_title;
	}   

	return $tabs;
}
endif;

/**
 * Get Solace Extra logo
 *
 * @param boolean $img either we want to return an <img /> tag
 *
 * @since 1.0
 *
 * @return string image url or tag
 */
if( !function_exists( 'solace_extra_get_icon' ) ) :
function solace_extra_get_icon( $img = false ) {
	$url = SOLACE_EXTRA_ASSETS_URL . '/img/icon.png';

	if( $img ) return "<img src='{$url}'>";

	return $url;
}
endif;

/**
 * Set wishlist of the user
 *
 * @var array $wishlist a set of product IDs
 * @var int $user_id user ID
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_set_wishlist' ) ) :
function solace_extra_set_wishlist( $wishlist, $user_id = 0 ) {
	$_wishlist_key = 'solace-extra-wishlist';
	$_wishlist = [];

	if( $user_id != 0 ) {
		update_user_meta( $user_id, sanitize_key( $_wishlist_key ), $wishlist );
	}
	else {
		setcookie( sanitize_key( $_wishlist_key ), json_encode( $wishlist ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}
}
endif;

/**
 * Gets a random order ID
 *
 * @return int
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_get_random_order_id' ) ) :
function solace_extra_get_random_order_id(){
	if( !function_exists( 'WC' ) ) return false;

	$query = new \WC_Order_Query( array(
		'limit' => 1,
		'orderby' => 'rand',
		'order' => 'DESC',
		'return' => 'ids',
	) );
	$orders = $query->get_orders();

	if ( count( $orders ) > 0 ) {
		return $orders[0];
	}

	return false;
}
endif;

/**
 * Default checkout fields
 *
 * @param string $section form section billing|shipping|order
 *
 * @since 1.0
 */
if( !function_exists( 'solace_extra_wc_fields' ) ) :
function solace_extra_wc_fields( $section = '' ) {
	$fields = [
		'billing' => [ 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_country', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode', 'billing_phone', 'billing_email' ],
		'shipping' => [ 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_country', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_postcode' ],
		'order' => [ 'order_comments' ]
	];

	if( $section != '' && isset( $fields[ $section ] ) ) {
		return apply_filters( 'solace_extra_wc_fields', $fields[ $section ] );
	}

	return apply_filters( 'solace_extra_wc_fields', $fields );
}
endif;

/**
 * Get related_product_ids in cart
 *
 * @return array
 */
if( !function_exists( 'solace_extra_get_cart_related_products' ) ) :
function solace_extra_get_cart_related_products( $product_limit, $relation_type = 'related-products', $exclude_products = [] ) {

	$product_ids = [];
	if( is_null( WC()->cart ) ) {
		include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
		include_once WC_ABSPATH . 'includes/class-wc-cart.php';
		wc_load_cart();
	}

	if( WC()->cart->is_empty() ) return $product_ids;

	if ( $relation_type == 'cross-sells' ) {
		$product_ids = WC()->cart->get_cross_sells();
	}
	else{
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product_ids = [];
			if ( $relation_type == 'upsells' ) {
				$product     = wc_get_product( $cart_item['product_id'] );
				$_product_ids = $product->get_upsell_ids();
			}else{
				$_product_ids = wc_get_related_products( $cart_item['product_id'] );
			}
			$product_ids = array_merge( $product_ids, $_product_ids );
		}
	}
	$related_product_ids = array_unique( $product_ids );
	if ( !empty( $exclude_products ) ) {
		foreach ( $exclude_products as $key => $pid ) {
			if ( in_array( $pid, $related_product_ids ) ) {
				unset($related_product_ids[array_search( $pid, $related_product_ids )]);
			}
		}
	}

	shuffle( $related_product_ids );
	$related_product_ids = array_slice( $related_product_ids, 0, $product_limit );
	return $related_product_ids;
}
endif;

/**
 * Populates rating html with start icons.
 *
 * @var int|float $rating the rating value
 * @return html
 *
 * @author Jakaria Istauk <jakariamd35@gmail.com>
 * @since 3.0
 */
if( !function_exists( 'solace_extra_rating_html' ) ) :
function solace_extra_rating_html( $rating ) {

	$half_rating = $rating - floor($rating);
	$rating_html = '';
	
	for ( $i = 0; $i < (int)$rating; $i++ ) { 
		$rating_html .= "<span class='dashicons dashicons-star-filled'></span>";
	}
	
	if ( $half_rating > 0 ) {
		$rating += 1;
		$rating_html .= "<span class='dashicons dashicons-star-half'></span>";
	}

	for ( $i = 0; $i < 5 - (int)$rating; $i++ ) { 
		$rating_html .= "<span class='dashicons dashicons-star-empty'></span>";
	}

	return $rating_html;
}
endif;


/**
 * Product Compare Cookie Key
 *  
 * @uses solace_extra_compare_cookie_key()
 * 
 * @return string the cookie key
 * 
 * @author Jakaria Istauk <jakariamd35@gmail.com>
 * @since 3.0.1
 */
if( ! function_exists( 'solace_extra_compare_cookie_key' ) ) :
function solace_extra_compare_cookie_key(){
	return '_solace-extra-compare';
}
endif;

/**
 * Return the template types
 *
 * @since 3.0
 * @param $args, give array value. unset field
 * @author Al Imran Akash <alimranakash.bd@gmail.com>
 */
if( !function_exists( 'solace_extra_hextorgb' ) ) :
function solace_extra_hextorgb( $hex = '#000000' ) {
	list($r, $g, $b) = sscanf( $hex, "#%02x%02x%02x" );
	$rgb = "$r, $g, $b";
	return $rgb;
}
endif;

/**
 * 
 * @return string sale text with discount percentage
 * 
 * @author Tanvir <naymulhasantanvir10@gmail.com>
 * @since 3.0.1
 */
if( ! function_exists( 'solace_extra_get_sale_text_with_discount_percentage' ) ) :
	function solace_extra_get_sale_text_with_discount_percentage( $product, $sale_text ){

		if ( 'simple' == $product->get_type() ) {
			$regular_price          = $product->get_regular_price();
			$sale_price             = $product->get_sale_price(); 
			$discount_percentage 	= round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 ).'%';
			$sale_text              = str_replace( '%%discount_percentage%%', $discount_percentage, $sale_text );
		}
		else{
			$sale_text              = str_replace( '%%discount_percentage%%', '', $sale_text );  
		}

		return $sale_text;
	}
endif;

/**
 * 
 * @return Return true if start date & end date are match
 * 
 * @author Mahbub <mahbubmr500@@gmail.com>
 * @since 4.3.3.1
 *
 * phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Promo callback, name is intentional
 */
if( ! function_exists( 'mothers_day_promo_start_and_end_time' ) ) :
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
	function mothers_day_promo_start_and_end_time( $current_time ){
		$nextSunday     = new \DateTime( '2024-08-27' );
		$nextFriday     = new \DateTime( '2024-08-28' );
		$next_sunday    = $nextSunday->getTimestamp();
		$next_friday    = $nextFriday->getTimestamp();

		return $current_time >= $next_sunday && $current_time <= $next_friday;      
	}
endif;


/**
 * 
 * 
 * @author Soikut <shadekur.rahman60@gmail.com>
 * @since 4.5.6
 */

if( ! function_exists( 'solace_extra_notices_values' ) ) :
	function solace_extra_notices_values(){
		$current_time 	= date_i18n( 'U' );


		// return [
		// 	'checkout_notice'=> [
		// 		'text'		=> __( '<strong>70%</strong> of shoppers leave their cart before completing checkout. Make your Checkout conversion optimized and never lose a customer again.', 'solace-extra' ),
		// 		'from'		=> $current_time,
		// 		'to'		=> $current_time + 48 * HOUR_IN_SECONDS,
		// 		'button'	=> __( 'Customize Your Checkout', 'solace-extra' ),
		// 		'url'		=> "https://solacewp.com/solace-extra/pricing/?utm_source=In-plugin&utm_medium=offer+notice&utm_campaign=Checkout"
		// 	],
		// 	'email_notice'=> [
		// 		'text'		=> __( '<strong>9%</strong> of the total ecommerce website traffic comes from emails. Create and send awesome branded email campaigns with Solace Extra.', 'solace-extra' ),
		// 		'from'		=> $current_time + 120 * HOUR_IN_SECONDS,
		// 		'to'		=> $current_time + 168 * HOUR_IN_SECONDS,
		// 		'button'	=> __( 'Start Sending Awesome Emails', 'solace-extra' ),
		// 		'url'		=> "https://solacewp.com/solace-extra/pricing/?utm_source=In-plugin&utm_medium=offer+notice&utm_campaign=Email"
		// 	],
		// 	'invoice_notice'=> [
		// 		'text'			=> __( '<strong>57%</strong> of invoice data is entered manually. Automate your WooCommerce store invoicing with Solace Extra.', 'solace-extra' ),
		// 		'from'			=> $current_time + 240 * HOUR_IN_SECONDS,
		// 		'to'			=>  $current_time + 288 * HOUR_IN_SECONDS,
		// 		'button'		=> __( 'Automate Your Invoicing', 'solace-extra' ),
		// 		'url'		=> "https://solacewp.com/solace-extra/pricing/?utm_source=In-plugin&utm_medium=offer+notice&utm_campaign=Invoice"
		// 	],
		// ];
        return [
            'solace_extra_promotional_campain' => [
                'from'   => $current_time,
                'to'     => strtotime( '2025-01-20 00:00:00' ),
                'button' => __('Grab Now', 'solace-extra'),
                'url'    => "https://solacewp.com/solace-extra/pricing?utm_source=in+plugin&utm_medium=notice&utm_campaign=new-year-2025",
            ],
        ];
    }
endif;

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Function name includes plugin prefix
if( ! function_exists( 'get_solace_extra_countdown_html' ) ) :
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
	function get_solace_extra_countdown_html( $from, $to ) {
		$to = date_i18n( 'Y/m/d H:i:s', $to );
		return '
		<div class="solace-extra-countdown" id="solace-extra-countdown" data-countdown-end="'.$to.'">
			<div class="cx-count">
				<span id="days"></span>
				<label>DAYS</label>
			</div>
			<div class="cx-count">
				<span id="hours"></span>
				<label>HRS</label>
			</div>
			<div class="cx-count">
				<span id="minutes"></span>
				<label>MINS</label>
			</div>
			<div class="cx-count">
				<span id="seconds"></span>
				<label>SEC</label>
			</div>
		</div>';
	}
	
endif;


/**
 * 
 * 
 * @author Soikut <shadekur.rahman60@gmail.com>
 * @since 4.7.1
 */

 if( ! function_exists( 'Solace_Extra_promotional_widgets' ) ) :
 function Solace_Extra_promotional_widgets() {
	$wlbi_promo = 'wlbi-promo'; 
	return [
		[
			'name'       => 'solace-extra-shop-flip',
			'title'      => __( 'Shop Flip', 'solace-extra' ),
			'icon'       => 'eicon-flip-box' . ' ' . $wlbi_promo, 
			'category' 	 => 'solace-extra-shop',
		],
		[
			'name'       => 'solace-extra-shop-shopify',
			'title'      => __( 'Shop Shopify', 'solace-extra' ),
			'icon'       => 'eicon-thumbnails-half' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-shop',
		],
		[
			'name'       => 'solace-extra-shop-trendy',
			'title'      => __( 'Shop Trendy', 'solace-extra' ),
			'icon'       => 'eicon-products' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-shop',
		],
		[
			'name'       => 'solace-extra-shop-curvy-horizontal',
			'title'      => __( 'Shop Curvy Horizontal', 'solace-extra' ),
			'icon'       => 'eicon-posts-group',
			'category' 	 => 'solace-extra-shop',
		],
		[
			'name'       => 'solace-extra-shop-accordion',
			'title'      => __( 'Shop Accordion', 'solace-extra' ),
			'icon'       => 'eicon-accordion' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-shop',
		],
		[
			'name'       => 'solace-extra-shop-table',
			'title'      => __( 'Shop Table', 'solace-extra' ),
			'icon'       => 'eicon-table' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-shop',
		],
		[
			'name'       => 'solace-extra-shop-beauty',
			'title'      => __( 'Shop Beauty', 'solace-extra' ),
			'icon'       => 'eicon-thumbnails-half' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-shop' . ' ' . $wlbi_promo,
		],
		[
			'name'       => 'solace-extra-shop-smart',
			'title'      => __( 'Shop Smart', 'solace-extra' ),
			'icon'       => 'eicon-thumbnails-half' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-shop',
		],
		[
			'name'       => 'solace-extra-shop-minimal',
			'title'      => __( 'Shop Minimal', 'solace-extra' ),
			'icon'       => 'eicon-thumbnails-half' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-shop',
		],
		[
			'name'       => 'solace-extra-shop-wix',
			'title'      => __( 'Shop Wix', 'solace-extra' ),
			'icon'       => 'eicon-thumbnails-half' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-shop',
		],
		// [
		// 	'name'       => 'solace-extra-shop-shopify',
		// 	'title'      => __( 'Shop Shopify', 'solace-extra' ),
		// 	'icon'       => 'eicon-thumbnails-half',
		// 	'category' 	 => 'solace-extra-shop',
		// ],
		[
			'name'       => 'solace-extra-filter-vertical',
			'title'      => __( 'Filter Vertical', 'solace-extra' ),
			'icon'       => 'eicon-ellipsis-v' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-filter',
		],
		[
			'name'       => 'solace-extra-filter-advance',
			'title'      => __( 'Filter Advance', 'solace-extra' ),
			'icon'       => 'eicon-filter' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-filter',
		],
		// [
		// 	'name'       => 'solace-extra-product-dynamic-tabs',
		// 	'title'      => __( 'Product Dynamic Tabs', 'solace-extra' ),
		// 	'icon'       => 'eicon-product-tabs' . ' ' . $wlbi_promo,
		// 	'category' 	 => 'solace-extra-single',
		// ],
		// [
		// 	'name'       => 'solace-extra-product-comparison-button',
		// 	'title'      => __( 'Add to Compare', 'solace-extra' ),
		// 	'icon'       => 'eicon-cart' . ' ' . $wlbi_promo,
		// 	'category' 	 => 'solace-extra-single',
		// ],
		// [
		// 	'name'       => 'solace-extra-ask-for-price',
		// 	'title'      => __( 'Ask for Price', 'solace-extra' ),
		// 	'icon'       => 'eicon-cart' . ' ' . $wlbi_promo,
		// 	'category' 	 => 'solace-extra-single',
		// ],
		// [
		// 	'name'       => 'solace-extra-quick-checkout-button',
		// 	'title'      => __( 'Quick Checkout Button', 'solace-extra' ),
		// 	'icon'       => 'eicon-cart' . ' ' . $wlbi_promo,
		// 	'category' 	 => 'solace-extra-single',
		// ],
		// [
		// 	'name'       => 'solace-extra-product-barcode',
		// 	'title'      => __( 'Product Barcode', 'solace-extra' ),
		// 	'icon'       => 'eicon-barcode' . ' ' . $wlbi_promo,
		// 	'category' 	 => 'solace-extra-single',
		// ],
		[
			'name'       => 'solace-extra-pricing-table-regular',
			'title'      => __( 'Pricing Table Regular', 'solace-extra' ),
			'icon'       => 'eicon-price-table' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-pricing',
		],
		[
			'name'       => 'solace-extra-pricing-table-smart',
			'title'      => __( 'Pricing Table Smart', 'solace-extra' ),
			'icon'       => 'eicon-price-table' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-pricing',
		],
		[
			'name'       => 'solace-extra-pricing-table-fancy',
			'title'      => __( 'Pricing Table Fancy', 'solace-extra' ),
			'icon'       => 'eicon-price-table' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-pricing',
		],
		[
			'name'       => 'solace-extra-related-products-flip',
			'title'      => __( 'Related Products Flip', 'solace-extra' ),
			'icon'       => 'eicon-flip-box' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-related',
		],
		[
			'name'       => 'solace-extra-related-products-trendy',
			'title'      => __( 'Related Products Trendy', 'solace-extra' ),
			'icon'       => 'eicon-products' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-related',
		],
		[
			'name'       => 'solace-extra-related-products-accordion',
			'title'      => __( 'Related Products Accordion', 'solace-extra' ),
			'icon'       => 'eicon-accordion' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-related',
		],
		[
			'name'       => 'solace-extra-related-products-table',
			'title'      => __( 'Related Products Table', 'solace-extra' ),
			'icon'       => 'eicon-accordion' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-related',
		],
		[
			'name'       => 'solace-extra-floating-cart',
			'title'      => __( 'Floating Cart', 'solace-extra' ),
			'icon'       => 'eicon-product-meta ' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-cart',
		],
		[
			'name'       => 'solace-extra-billing-address',
			'title'      => __( 'Billing Address', 'solace-extra' ),
			'icon'       => 'eicon-google-maps' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-checkout',
		],
		[
			'name'       => 'solace-extra-shipping-address',
			'title'      => __( 'Shipping Address', 'solace-extra' ),
			'icon'       => 'eicon-google-maps' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-checkout',
		],
		[
			'name'       => 'solace-extra-order-notes',
			'title'      => __( 'Order Notes', 'solace-extra' ),
			'icon'       => 'eicon-table-of-contents' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-checkout',
		],
		[
			'name'       => 'solace-extra-order-review',
			'title'      => __( 'Order Review', 'solace-extra' ),
			'icon'       => 'eicon-product-info' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-checkout',
		],
		[
			'name'       => 'solace-extra-order-pay',
			'title'      => __( 'Order Pay', 'solace-extra' ),
			'icon'       => 'eicon-product-info' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-checkout',
		],
		[
			'name'       => 'solace-extra-payment-methods',
			'title'      => __( 'Payment Methods', 'solace-extra' ),
			'icon'       => 'eicon-product-upsell' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-checkout',
		],
		[
			'name'       => 'solace-extra-thankyou',
			'title'      => __( 'Thank You', 'solace-extra' ),
			'icon'       => 'eicon-nerd' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-checkout',
		],
		[
			'name'       => 'solace-extra-checkout-login',
			'title'      => __( 'Checkout Login', 'solace-extra' ),
			'icon'       => 'eicon-lock-user' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-checkout',
		],
		[
			'name'       => 'solace-extra-email-header',
			'title'      => __( 'Email Header', 'solace-extra' ),
			'icon'       => 'eicon-header' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-email',
		],
		[
			'name'       => 'solace-extra-email-footer',
			'title'      => __( 'Email Footer', 'solace-extra' ),
			'icon'       => 'eicon-footer' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-email',
		],
		[
			'name'       => 'solace-extra-email-item-details',
			'title'      => __( 'Email Item Details', 'solace-extra' ),
			'icon'       => 'eicon-kit-details' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-email',
		],
		[
			'name'       => 'solace-extra-email-billing-addresses',
			'title'      => __( 'Email Billing Addresses', 'solace-extra' ),
			'icon'       => 'eicon-table-of-contents' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-email',
		],
		[
			'name'       => 'solace-extra-email-shipping-addresses',
			'title'      => __( 'Email Shipping Addresses', 'solace-extra' ),
			'icon'       => 'eicon-purchase-summary' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-email',
		],
		[
			'name'       => 'solace-extra-email-customer-note',
			'title'      => __( 'Email Customer Note', 'solace-extra' ),
			'icon'       => 'eicon-document-file' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-email',
		],
		[
			'name'       => 'solace-extra-email-order-note',
			'title'      => __( 'Email Order Note', 'solace-extra' ),
			'icon'       => 'eicon-document-file' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-email',
		],
		[
			'name'       => 'solace-extra-email-description',
			'title'      => __( 'Email Description', 'solace-extra' ),
			'icon'       => 'eicon-menu-toggle' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-email',
		],
		[
			'name'       => 'solace-extra-email-reminder',
			'title'      => __( 'Email Reminder', 'solace-extra' ),
			'icon'       => 'eicon-menu-toggle' . ' ' . $wlbi_promo,
			'category' 	 => 'solace-extra-email',
		],
		[
			'name'       => 'solace-extra-my-account-advanced',
			'title'      => __( 'My Account Advanced', 'solace-extra' ),
			'icon'       => 'eicon-call-to-action' . ' ' . $wlbi_promo,
			'category' 	 => 'solace_extra',
		],
		[
			'name'       => 'solace-extra-wishlist',
			'title'      => __( 'Wishlist', 'solace-extra' ),
			'icon'       => 'eicon-heart-o' . ' ' . $wlbi_promo,
			'category' 	 => 'solace_extra',
		],
		[
			'name'       => 'solace-extra-customer-reviews-standard',
			'title'      => __( 'Customer Reviews Standard', 'solace-extra' ),
			'icon'       => 'eicon-review' . ' ' . $wlbi_promo,
			'category' 	 => 'solace_extra',
		],
		[
			'name'       => 'solace-extra-customer-reviews-trendy',
			'title'      => __( 'Customer Reviews Trendy', 'solace-extra' ),
			'icon'       => 'eicon-rating' . ' ' . $wlbi_promo,
			'category' 	 => 'solace_extra',
		],
		[
			'name'       => 'solace-extra-faqs-accordion',
			'title'      => __( 'FAQs Accordion', 'solace-extra' ),
			'icon'       => 'eicon-accordion' . ' ' . $wlbi_promo,
			'category' 	 => 'solace_extra',
		],
		[
			'name'       => 'solace-extra-sales-notification',
			'title'      => __( 'Sales Notification', 'solace-extra' ),
			'icon'       => 'eicon-posts-ticker' . ' ' . $wlbi_promo,
			'category' 	 => 'solace_extra',
		],
		[
			'name'       => 'solace-extra-category',
			'title'      => __( 'Shop Categories', 'solace-extra' ),
			'icon'       => 'eicon-flow' . ' ' . $wlbi_promo,
			'category' 	 => 'solace_extra',
		],
		[
			'name'       => 'solace-extra-basic-menu',
			'title'      => __( 'Basic Menu', 'solace-extra' ),
			'icon'       => 'eicon-nav-menu' . ' ' . $wlbi_promo,
			'category' 	 => 'solace_extra',
		],
		[
			'name'       => 'solace-extra-dynamic-tabs',
			'title'      => __( 'Dynamic Tabs', 'solace-extra' ),
			'icon'       => 'eicon-tabs' . ' ' . $wlbi_promo,
			'category' 	 => 'solace_extra',
		],
		[
			'name'       => 'solace-extra-menu-cart',
			'title'      => __( 'Menu Cart', 'solace-extra' ),
			'icon'       => 'eicon-cart' . ' ' . $wlbi_promo,
			'category' 	 => 'solace_extra',
		],
		// [
		// 	'name'       => 'solace-extra-product-add-to-wishlist',
		// 	'title'      => __( 'Add to Wishlist', 'solace-extra' ),
		// 	'icon'       => 'eicon-tags' . ' ' . $wlbi_promo,
		// 	'category' 	 => 'solace-extra-single',
		// ]
	];
}
endif;

/**
 * Recursively includes all PHP files from the specified directory,
 * excluding 'functions.php', and registers any new classes found with the widgets manager.
 *
 * @param string $directory       The directory path to scan for PHP files.
 * @param object $widgets_manager The instance of the widgets manager to register classes with.
 */
function solace_extra_register_woocommerce_widgets_recursively( $directory, $widgets_manager ) {
	// Retrieve all files and directories within the specified directory.
	$files = scandir( $directory );

	foreach ( $files as $file ) {
		// Skip special files like '.' and '..'.
		if ( $file === '.' || $file === '..' ) {
			continue;
		}

		$file_path = $directory . DIRECTORY_SEPARATOR . $file;

		// If the file is a directory, recursively call this function.
		if ( is_dir( $file_path ) ) {
			solace_extra_register_woocommerce_widgets_recursively( $file_path, $widgets_manager );
		} else {
			// Exclude functions.php and ensure the file is a PHP file.
			if ( $file !== 'functions.php' && pathinfo( $file_path, PATHINFO_EXTENSION ) === 'php' ) {
				// Get declared classes before requiring the file.
				$declared_classes_before = get_declared_classes();

				// Require the file.
				require_once $file_path;

				// Get declared classes after requiring the file.
				$declared_classes_after = get_declared_classes();

				// Find new classes declared by the file.
				$new_classes = array_diff( $declared_classes_after, $declared_classes_before );

				if ( ! empty( $new_classes ) ) {
					foreach ( $new_classes as $class ) {
						$widgets_manager->register( new $class );
					}
				}
			}	
		}
	}
}