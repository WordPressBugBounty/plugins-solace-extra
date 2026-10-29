<?php defined('ABSPATH') || exit; ?>
<?php
/**
 * Build a nonce-protected preview URL for the Elementor content AJAX endpooint.
 *
 * @param int $post_id Sitebuilder part post ID.
 * @return string
 */
function solace_extra_get_elementor_content_preview_url( $post_id ) {
	return add_query_arg(
		array(
			'action'  => 'get_elementor_content',
			'post_id' => absint( $post_id ),
			'nonce'   => wp_create_nonce( 'solace_sitebuilder_preview' ),
		),
		admin_url( 'admin-ajax.php' )
	);
}

$solace_extra_customizer_link = admin_url('customize.php'); 
$solace_extra_comingsoon = false; ?>
<div class="wrap">
<?php require_once plugin_dir_path(dirname(__FILE__)) . 'partials/header.php'; ?>

<?php 
/**
 * Generate a dynamic preview URL for the latest WooCommerce order.
 *
 * Automatically resolves the correct checkout and order-received paths,
 * and allows control over scroll overflow behavior via query string.
 *
 * @param int    $template_id ID of the Elementor purchase summary template.
 * @param string $overflow    Set to 'hidden' to append solace-hide-overflow=1 to URL. Default is 'auto'.
 * @return string|false       The full preview URL or false if no recent order or user not logged in.
 */
function solace_extra_get_purchase_summary_preview_url( $template_id, $overflow = 'auto' ) {
	$template_id = absint( $template_id );
	if ( ! $template_id ) {
		return false;
	}

	// Ensure user is logged in
	$user_id = get_current_user_id();
	if ( ! $user_id ) {
		return false;
	}

	// Get the latest order for the current user
	$customer_orders = wc_get_orders( array(
		'limit'        => 1,
		'customer_id'  => $user_id,
		'orderby'      => 'date',
		'order'        => 'DESC',
		'return'       => 'ids',
	) );

	if ( empty( $customer_orders ) ) {
		return false;
	}

	$order_id  = $customer_orders[0];
	$order     = wc_get_order( $order_id );
	$order_key = $order->get_order_key();

	// Get the thank you (order received) URL dynamically
	$thank_you_url = $order->get_checkout_order_received_url();

	// Base query args for preview
	$solace_extra_query_args = array(
		'nt' => 1,
		'solace-purchase-summary-preview' => $template_id,
	);

	// Conditionally add overflow query
	if ( $overflow === 'hidden' ) {
		$solace_extra_query_args['solace-hide-overflow'] = 1;
	}

	// Build full preview URL with query args
	$preview_url = add_query_arg( $solace_extra_query_args, $thank_you_url );

	return $preview_url;
}
?>

<?php if ( ! class_exists( '\Elementor\Plugin' ) ) : ?>
    <div class="container">
        <div class="coming-soon-container">
            <h1><?php esc_html_e( 'Elementor Plugin is Not Active', 'solace-extra' ); ?></h1>
            <p><?php esc_html_e( 'Please activate the Elementor Plugin first to start Solace SiteBuilder.', 'solace-extra' ); ?></p>
        </div>
    </div>
<?php endif; ?>

<?php if ( $solace_extra_comingsoon ) { ?>
    <div class="container">
        <div class="coming-soon-container">
            <h1><?php esc_html_e( 'Coming Soon', 'solace-extra' ); ?></h1>
            <p><?php esc_html_e( "We're working on something amazing. Stay tuned!", 'solace-extra' ); ?></p>
        </div>
    </div>
<?php } else {

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended --- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here. 
    $solace_extra_part = isset($_GET['part']) ? sanitize_text_field(wp_unslash($_GET['part'])) : '';

    $solace_extra_args = array(
        'post_type' => 'solace-sitebuilder',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );

    if ( $solace_extra_part ) {
        if ( $solace_extra_part == '404' ) {
            if ( function_exists('solace_pro_parts') ) {
            } else {
                $solace_extra_part = "";
            }
        }
        $solace_extra_key = '_solace_' . $solace_extra_part . '_status';
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- We need this
        $solace_extra_args['meta_query'] = array(
            array(
                'key'     => $solace_extra_key,
                'compare' => 'EXISTS',
            ),
        );
    }

    $solace_extra_allowed_parts = array( '', 'header', 'footer' );

    if ( ! in_array( $solace_extra_part, $solace_extra_allowed_parts, true ) ) {
        
        if ( ! function_exists( 'solace_pro_card' ) ) {
            wp_safe_redirect( admin_url( 'admin.php?page=dashboard-sitebuilder' ) );
            exit;
        }

        if ( ! solace_pro_card() ) {
            wp_safe_redirect( admin_url( 'admin.php?page=dashboard-sitebuilder' ) );
            exit;
        }
    }

    $solace_extra_query = new WP_Query( $solace_extra_args );

    if ( $solace_extra_query->have_posts() ) {
    } else {
    }
    ?>

    <div class='solace-sitebuilder'>
        <div class='parts'>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dashboard-sitebuilder')); ?>" class="button all <?php echo (empty($solace_extra_part) ? 'active' : ''); ?>">
                <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/all-layouts.png'); ?>" />
                <span><?php esc_html_e( 'All Layout', 'solace-extra' ); ?></span>
            </a>
            <span class='label'><?php esc_html_e( 'Website Parts', 'solace-extra' ); ?></span>

            <a href="<?php echo esc_url(add_query_arg('part', 'header', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>" class="button <?php echo (isset($solace_extra_part) && $solace_extra_part === 'header' ? 'active' : ''); ?>">
                <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/header.png'); ?>" />
                <span><?php esc_html_e( 'Header', 'solace-extra' ); ?></span>
            </a>

            <a href="<?php echo esc_url(add_query_arg('part', 'footer', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>" class="button <?php echo (isset($solace_extra_part) && $solace_extra_part === 'footer' ? 'active' : ''); ?>">
                <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/footer.png'); ?>" />
                <span><?php esc_html_e( 'Footer', 'solace-extra' ); ?></span>
            </a>

            <?php if (function_exists('solace_pro_parts') && solace_pro_card()) {
                // Pro's menu borrows other parts' icons for these entries
                // (blogsinglepost.png for Purchase Summary, purchase-summary.png for
                // Cart, Checkout and My Account). Give each its own icon.
                $solace_extra_pro_parts_html = preg_replace(
                    '#(part=(purchase-summary|cart|checkout|myaccount)"[^>]*>\s*<img\s+src="[^"]*/images/)[^"/]+\.png#',
                    '$1$2.png',
                    (string) solace_pro_parts( $solace_extra_part )
                );
                echo wp_kses_post( $solace_extra_pro_parts_html );
            } else { ?>
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="#" class="button nf">
                    <div class='desc'>
                        <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/singleproduct.png'); ?>" />
                        <span><?php esc_html_e( 'Shop Single Product', 'solace-extra' ); ?></span>
                    </div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span>
                </a>
                <a href="#" class="button nf">
                    <div class='desc'>
                        <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/shopproductcategories.png'); ?>" />
                        <span><?php esc_html_e( 'Shop Product Archive', 'solace-extra' ); ?></span>
                    </div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span>
                </a>
                <a href="#" class="button nf">
                    <div class='desc'>
                        <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/purchase-summary.png'); ?>" />
                        <span><?php esc_html_e( 'Purchase Summary', 'solace-extra' ); ?></span>
                    </div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span>
                </a>
                <?php if ( function_exists( 'solace_woo_page_parts_enabled' ) && solace_woo_page_parts_enabled() ) : // Cart, checkout and account parts; off when the solace_sitebuilder_woo_page_parts_enabled filter returns false. ?>
                <a href="#" class="button nf">
                    <div class='desc'>
                        <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/cart.png'); ?>" />
                        <span><?php esc_html_e( 'Cart', 'solace-extra' ); ?></span>
                    </div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span>
                </a>
                <a href="#" class="button nf">
                    <div class='desc'>
                        <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/checkout.png'); ?>" />
                        <span><?php esc_html_e( 'Checkout', 'solace-extra' ); ?></span>
                    </div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span>
                </a>
                <a href="#" class="button nf">
                    <div class='desc'>
                        <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/myaccount.png'); ?>" />
                        <span><?php esc_html_e( 'My Account', 'solace-extra' ); ?></span>
                    </div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
                <a href="#" class="button nf">
                    <div class='desc'>
                        <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/blogsinglepost.png'); ?>" />
                        <span><?php esc_html_e( 'Blog Single Post', 'solace-extra' ); ?></span>
                    </div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span>
                </a>
                <a href="#" class="button nf">
                    <div class='desc'>
                        <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/blogarchive.png'); ?>" />
                        <span><?php esc_html_e( 'Blog Archive', 'solace-extra' ); ?></span>
                    </div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span>
                </a>
                <a href="#" class="button nf">
                    <div class='desc'>
                        <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/404.png'); ?>" />
                        <span><?php esc_html_e( '404', 'solace-extra' ); ?></span>
                    </div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span>
                </a>
            <?php } ?>


        </div>
        <div class='list'>
            <div class="headlist">
                <span class='label'><?php esc_html_e( 'Start customizing every part of your website', 'solace-extra' ); ?></span>


                <?php if ($solace_extra_part) : ?>
                    <a href="#" class="button addnew">
                        <?php echo esc_html__('Add New', 'solace-extra'); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php
            // Elementor Pro Theme Builder conflict info for every part that has
            // an Elementor Pro theme builder counterpart.
            $solace_extra_ep_parts = array( 'header', 'footer', 'singleproduct', 'shopproduct', 'blogsinglepost', 'blogarchive', '404' );
            if ( in_array( $solace_extra_part, $solace_extra_ep_parts, true ) ) {
                if ( function_exists( 'solace_sitebuilder_elementor_pro_notice' ) ) {
                    solace_sitebuilder_elementor_pro_notice( $solace_extra_part );
                }
            }

            // Pages chosen in Elementor Site Settings > WooCommerce Pages that
            // this part replaces (Shop, Purchase Summary, Cart, Checkout, My Account).
            if ( in_array( $solace_extra_part, array( 'shopproduct', 'purchase-summary', 'cart', 'checkout', 'myaccount' ), true ) && function_exists( 'solace_sitebuilder_elementor_site_settings_notice' ) ) {
                solace_sitebuilder_elementor_site_settings_notice( $solace_extra_part );
            }
            ?>

            <?php if ($solace_extra_part) : 
                $solace_extra_is_there_post = FALSE;
                ?>
                <div class="solace-list-view <?php echo esc_attr($solace_extra_part); ?>">
                    <?php while ($solace_extra_query->have_posts()) : $solace_extra_query->the_post(); 
                    $post_id = get_the_ID(); 
                    if (!empty($post_id)){
                        $solace_extra_is_there_post = TRUE;
                    }
                    $solace_extra_get_the_title = get_the_title(); 
                    $solace_extra_meta_key = '_solace_' . $solace_extra_part . '_conditions';
                    $solace_extra_conditions = get_post_meta($post_id, $solace_extra_meta_key, true);

                    // Corrupt/missing conditions meta — fall back to the part's
                    // default (Shop Page / All Products) so cards, the hidden
                    // conditions-data and the edit popup always get a valid array.
                    if ( ! is_array( $solace_extra_conditions ) ) {
                        $solace_extra_conditions = function_exists( 'solace_sitebuilder_default_conditions' )
                            ? solace_sitebuilder_default_conditions( $solace_extra_part )
                            : array();
                    }

                    // Single Product
                    $solace_extra_url = null;
                    $solace_extra_url_fancybox = null;
                    if ('singleproduct' === $solace_extra_part ) {
                        $solace_extra_args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => 1,
                            'orderby'        => 'date',
                            'order'          => 'DESC'
                        );
                        
                        $solace_extra_loop = new WP_Query( $solace_extra_args );
                        
                        while ( $solace_extra_loop->have_posts() ) : $solace_extra_loop->the_post();
                            global $product;
                            $solace_extra_actual_product_id = get_the_ID();
                            $solace_extra_url = get_permalink( $solace_extra_actual_product_id ) . '?solace-single-product-preview=' . $post_id;
                            $solace_extra_url_fancybox = get_permalink( $solace_extra_actual_product_id ) . '?solace-single-product-preview=' . $post_id . '&solace-hide-overflow=1';
                        endwhile;
                        
                        wp_reset_postdata();
                    }

                    if ( 'blogsinglepost' === $solace_extra_part ) {
                        $solace_extra_args = array(
                            'post_type'      => 'post',
                            'posts_per_page' => 1,
                            'orderby'        => 'date',
                            'order'          => 'DESC'
                        );
                        
                        $solace_extra_loop = new WP_Query( $solace_extra_args );
                        
                        while ( $solace_extra_loop->have_posts() ) : $solace_extra_loop->the_post();
                            $solace_extra_actual_post_id = get_the_ID();
                            $solace_extra_url           = get_permalink( $solace_extra_actual_post_id ) . '?solace-single-post-preview=' . $post_id;
                            $solace_extra_url_fancybox  = get_permalink( $solace_extra_actual_post_id ) . '?solace-single-post-preview=' . $post_id . '&solace-hide-overflow=1';
                        endwhile;

                        wp_reset_postdata();
                    }

                    if ( '404' === $solace_extra_part ) {
                        // Generate a 404 preview URL using a non-existent page path
                        // This will trigger WordPress's 404 page with our template preview parameter
                        $solace_extra_home_url = home_url( '/this-page-does-not-exist-404-preview/' );
                        $solace_extra_url = add_query_arg( 'solace-404-preview', $post_id, $solace_extra_home_url );
                        $solace_extra_url_fancybox = add_query_arg( 'solace-hide-overflow', '1', $solace_extra_url );
                    }
                    ?>
                    <div class="solace-template-item">
                        <div class="solace-list-item">
                            <div style="display: none;" class="conditions-data" data-conditions='<?php echo wp_json_encode($solace_extra_conditions); ?>'></div>

                            <?php if ( $solace_extra_part == 'header' || $solace_extra_part == 'footer' || 'shopproduct' === $solace_extra_part || 'singleproduct' === $solace_extra_part ) { ?>
                                <div class="box-group-instances" style="display: flex;">
                            <?php } ?>
                            <?php
                            // Cart / Checkout / My Account: the part's own address, under the
                            // title. Live when this template is on and selected as the page;
                            // otherwise that address forwards to the page the store uses.
                            $solace_extra_page_labels = array(
                                'cart'      => __( 'Cart', 'solace-extra' ),
                                'checkout'  => __( 'Checkout', 'solace-extra' ),
                                'myaccount' => __( 'My Account', 'solace-extra' ),
                            );
                            $solace_extra_page_url  = '';
                            $solace_extra_page_live = false;
                            if ( isset( $solace_extra_page_labels[ $solace_extra_part ] ) && function_exists( 'wc_get_page_id' ) ) {
                                // Each part's own address (woo-pages-integration.php section 15).
                                $solace_extra_own_page = function_exists( 'solace_extra_woo_part_page_id' ) ? solace_extra_woo_part_page_id( $post_id, $solace_extra_part, true ) : 0;
                                if ( $solace_extra_own_page ) {
                                    $solace_extra_page_url = get_permalink( $solace_extra_own_page );
                                }
                                $solace_extra_page_live = '1' === (string) get_post_meta( $post_id, '_solace_' . $solace_extra_part . '_status', true )
                                    && (int) get_option( 'woocommerce_' . $solace_extra_part . '_page_id', 0 ) === (int) $post_id;
                            }
                            ?>
                            <?php if ( $solace_extra_page_url ) : ?>
                                <div class="solace-part-title-wrap" style="display:flex;flex-direction:column;gap:2px;">
                            <?php endif; ?>
                            <div class="list-column title-column">
                                <?php echo esc_html( $solace_extra_get_the_title ); ?>
                            </div>
                            <?php if ( $solace_extra_page_url ) : ?>
                                <?php
                                $solace_extra_page_text = preg_replace( '#^https?://#', '', $solace_extra_page_url );
                                $solace_extra_page_style = 'padding-left:16px;font-size:12px;font-weight:400;text-decoration:none;';
                                if ( $solace_extra_page_live ) :
                                    ?>
                                    <a href="<?php echo esc_url( $solace_extra_page_url ); ?>" class="solace-part-url is-live" target="_blank" rel="noopener"
                                        title="<?php /* translators: %s: Cart, Checkout or My Account. */ echo esc_attr( sprintf( __( 'Open the %s page on the frontend', 'solace-extra' ), $solace_extra_page_labels[ $solace_extra_part ] ) ); ?>"
                                        style="<?php echo esc_attr( $solace_extra_page_style ); ?>color:#2271b1;"><?php echo esc_html( $solace_extra_page_text ); ?> &#8599;</a>
                                <?php else : ?>
                                    <?php
                                    // Not a link: that address shows another design (the page chosen
                                    // in WooCommerce Settings, or another template), not this part.
                                    $solace_extra_page_tip = '1' === (string) get_post_meta( $post_id, '_solace_' . $solace_extra_part . '_status', true )
                                        ? __( 'WooCommerce Settings takes priority, frontend preview not available.', 'solace-extra' )
                                        : __( 'Template is off, frontend preview not available.', 'solace-extra' );
                                    ?>
                                    <span class="solace-part-url" title="<?php echo esc_attr( $solace_extra_page_tip ); ?>"
                                        style="<?php echo esc_attr( $solace_extra_page_style ); ?>color:#8c8f94;cursor:help;"><?php echo esc_html( $solace_extra_page_text ); ?></span>
                                <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php
                            if ( $solace_extra_part == 'header' || $solace_extra_part == 'footer' || 'shopproduct' === $solace_extra_part || 'singleproduct' === $solace_extra_part ) {?>
                                <div class="group instances">
                                    <span class="label">Instances:&nbsp;</span>
                                    <span class="label listinstances">
                                        <?php
                                        $solace_extra_label = "";
                                        if ( empty( $solace_extra_conditions ) ) {
                                            // Not an error: the part simply has no
                                            // conditions yet, so it never renders.
                                            ?>
                                            <span class="text-no-conditions"><?php echo esc_html__( 'No conditions set', 'solace-extra' ); ?></span>
                                            <?php
                                        } else {
                                            $solace_extra_is_first = true;
                                    
                                            foreach ($solace_extra_conditions as $solace_extra_condition) {
                                                
                                                $solace_extra_value = $solace_extra_condition['value'];
                                                $type = $solace_extra_condition['type'];
                                    
                                                $solace_extra_class = ($type === 'include') ? 'text-include' : 'text-exclude';

                                                if (!$solace_extra_is_first) {
                                                    echo ', ';
                                                }
                                                $solace_extra_is_first = false;
                                                ?>
                                                <span class="<?php echo esc_attr($solace_extra_class); ?>">
                                                    <?php echo esc_html(solace_get_condition_label($solace_extra_value)); ?>
                                                </span>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </span>
                                    <div class="list-column conditions-column">
                                    <a href="#" class="button edit-conditions-button" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-conditions='<?php echo wp_json_encode($solace_extra_conditions); ?>'>
                                        <?php echo esc_html__('Edit Conditions', 'solace-extra'); ?>
                                    </a>
                                    </div>
                                </div>
                                </div>
                            <?php }?>
                            <div class="group action">
                                <div class="list-column delete-column">
                                    <a href="#" class="button delete" data-id="<?php echo esc_attr( $post_id ); ?>">
                                        <span><?php echo esc_html__('Delete', 'solace-extra'); ?></span>
                                    </a>                                        
                                </div>
                                <div class="list-column rename-column">
                                    <a href="#" data-post-id="<?php echo esc_attr( $post_id ); ?>" class="solace-rename-button button rename">
                                    <span><?php echo esc_html__('Rename', 'solace-extra'); ?></span>
                                    </a>
                                </div>
                                <div class="list-column edit-column">
                                    <?php
                                    if ( 'header' === $solace_extra_part || 'footer' === $solace_extra_part || '404' === $solace_extra_part ) {
                                        $solace_extra_link_preview = admin_url('post.php?post=' . $post_id . '&action=elementor&solace-extra=1');
                                    } else if ( 'singleproduct' === $solace_extra_part ) {
                                        $solace_extra_link_preview = admin_url('post.php?post=' . $post_id . '&action=elementor&solace-extra-single=1');
                                    } else if ( 'shopproduct' === $solace_extra_part || 'purchase-summary' === $solace_extra_part ) {
                                        $solace_extra_link_preview = admin_url('post.php?post=' . $post_id . '&action=elementor&solace-extra-woocommerce=1');
                                    } else if ( 'blogsinglepost' === $solace_extra_part ) {
                                        $solace_extra_link_preview = admin_url('post.php?post=' . $post_id . '&action=elementor&solace-extra-single-post=1');
                                    } else if ( 'blogarchive' === $solace_extra_part ) {
                                        $solace_extra_link_preview = admin_url('post.php?post=' . $post_id . '&action=elementor&solace-extra-archive=1');
                                    } else if ( in_array( $solace_extra_part, array( 'cart', 'checkout', 'myaccount' ), true ) ) {
                                        // Their widgets live in solace-extra-woocommerce; the cart and
                                        // checkout categories are registered but empty.
                                        $solace_extra_link_preview = admin_url('post.php?post=' . $post_id . '&action=elementor&solace-extra-woocommerce=1');
                                    } else {
                                        $solace_extra_link_preview = admin_url('post.php?post=' . $post_id . '&action=elementor');
                                    }
                                    // Append a global GET param: key 'part' and value $solace_extra_part
                                    if ( ! empty( $solace_extra_part ) ) {
                                        $solace_extra_link_preview = add_query_arg( 'part', $solace_extra_part, $solace_extra_link_preview );
                                    }
                                    ?>

                                    <a href="<?php echo esc_url( $solace_extra_link_preview ); ?>" class="button edit-page">
                                        <span><?php echo esc_html__('Edit Part', 'solace-extra'); ?></span>
                                    </a>
                                </div>
                                <div class="list-column edit-column">
                                    <?php                                    
                                    $solace_extra_link_preview = solace_extra_get_elementor_content_preview_url( $post_id );
                                    if ( 'purchase-summary' === $solace_extra_part ) {
                                        $solace_extra_link_preview = solace_extra_get_purchase_summary_preview_url( $post_id, 'auto' );
                                    }

                                    if ( 'singleproduct' === $solace_extra_part || 'blogsinglepost' === $solace_extra_part || '404' === $solace_extra_part ) {
                                        if ( ! empty( $solace_extra_url_fancybox ) ) {
                                            $solace_extra_link_preview = $solace_extra_url_fancybox;
                                        }
                                    }
                                    // Append a global GET param: key 'part' and value $solace_extra_part
                                    if ( ! empty( $solace_extra_part ) ) {
                                        $solace_extra_has_product = false;
                                        if ( class_exists( 'WooCommerce' ) ) {
                                            $solace_extra_prod_q = new WP_Query( array(
                                                'post_type'      => 'product',
                                                'posts_per_page' => 1,
                                                'post_status'    => 'publish',
                                                'fields'         => 'ids',
                                            ) );
                                            $solace_extra_has_product = $solace_extra_prod_q->have_posts();
                                            wp_reset_postdata();
                                        }

                                        if ( $solace_extra_has_product && ! empty( $solace_extra_link_preview ) && is_string( $solace_extra_link_preview ) ) {
                                            $solace_extra_link_preview = add_query_arg( 'part', $solace_extra_part, $solace_extra_link_preview );
                                        }
                                    }
                                    ?>
                                    <a 
                                        href="<?php echo esc_url( $solace_extra_link_preview ); ?>"
                                        class="button edit-page"
                                        data-fancybox
                                        data-type="iframe"
                                    >
                                        <span><?php echo esc_html__('Preview', 'solace-extra'); ?></span>
                                    </a>
                                </div>
                                <div class="list-column status-column">
                                    <label class="switch">
                                        <?php
                                        $solace_extra_show_status = get_post_meta($post_id, '_solace_'.$solace_extra_part.'_status', true);
                                        echo '<!-- Show Status: ' . esc_html($solace_extra_show_status) . ' -->'; // Debugging output
                                        ?>
                                        <label class="switch">
                                        <input type="checkbox" class="status-switch" data-part="<?php echo esc_html($solace_extra_part);?>" data-post-id="<?php echo esc_attr( $post_id ); ?>" <?php checked($solace_extra_show_status, '1'); ?> />
                                        <span class="slider round"></span>
                                        </label>

                                    </label>
                                </div>
                            </div>

                        </div>
                        <?php if ($solace_extra_part == 'header' || $solace_extra_part == 'footer') { ?>
                            <div class="preview-container" style="position: relative; overflow: hidden;">
                            <div class="iframe-overlay"></div> 

                            <div class="custom-header-content" 
                                    id="custom-header-content" 
                                    style="position: relative; width: 100%;height: 100%; overflow: hidden;">
                                    <iframe 
                                        id="responsive-iframe"
                                        src="<?php echo esc_url( solace_extra_get_elementor_content_preview_url( $post_id ) ); ?>" 
                                        style="
                                            width: 1400px; /* Fixed iframe width */
                                            height: 254.7125px; /* Fixed iframe height for 1400px width */
                                            border: none;
                                            transform-origin: top left; /* Maintain scaling origin */
                                        ">
                                    </iframe>
                                </div>
                            </div>
                        <?php } else if ('singleproduct' === $solace_extra_part ) { ?>
                            <section class="template-demo">
                                <article 
                                    class="template-card"
                                    href="<?php echo esc_url( $solace_extra_url_fancybox ); ?>"
                                    data-fancybox
                                    data-type="iframe"
                                >
                                    <div class="iframe-wrapper">
                                        <iframe 
                                            id="iframe-preview-<?php echo esc_attr( $solace_extra_part ); ?>"
                                            class="iframe-preview"
                                            title="Template Preview" 
                                            src="<?php echo esc_url( $solace_extra_url_fancybox ); ?>">
                                        </iframe>
                                    </div>
                                </article>
                            </section>                                   
                        <?php } else if ('purchase-summary' === $solace_extra_part ) { ?>
                            <section class="template-demo">
                                <article 
                                    class="template-card"
                                    href="<?php echo esc_url( solace_extra_get_purchase_summary_preview_url( $post_id, 'hidden' ) ); ?>"
                                    data-fancybox
                                    data-type="iframe"
                                >
                                    <div class="iframe-wrapper">
                                        <iframe 
                                            id="iframe-preview-<?php echo esc_attr( $solace_extra_part ); ?>"
                                            class="iframe-preview"
                                            title="Template Preview" 
                                            src="<?php echo esc_url( solace_extra_get_purchase_summary_preview_url( $post_id, 'hidden' ) ); ?>">
                                        </iframe>
                                    </div>
                                </article>
                            </section>                                
                            <?php 
                        } else if ('blogsinglepost' === $solace_extra_part ) { ?>
                            <section class="template-demo">
                                <article 
                                    class="template-card"
                                    href="<?php echo esc_url( $solace_extra_url_fancybox ); ?>"
                                    data-fancybox
                                    data-type="iframe"
                                >
                                    <div class="iframe-wrapper">
                                        <iframe 
                                            id="iframe-preview-<?php echo esc_attr( $solace_extra_part ); ?>"
                                            class="iframe-preview aaaaa"
                                            title="Template Preview" 
                                            src="<?php echo esc_url( $solace_extra_url_fancybox ); ?>">
                                        </iframe>
                                    </div>
                                </article>
                            </section>                                  
                        <?php } else if ('404' === $solace_extra_part ) { ?>
                            <section class="template-demo">
                                <article 
                                    class="template-card"
                                    href="<?php echo esc_url( $solace_extra_url_fancybox ); ?>"
                                    data-fancybox
                                    data-type="iframe"
                                >
                                    <div class="iframe-wrapper">
                                        <iframe 
                                            id="iframe-preview-<?php echo esc_attr( $solace_extra_part ); ?>"
                                            class="iframe-preview"
                                            title="Template Preview" 
                                            src="<?php echo esc_url( $solace_extra_url_fancybox ); ?>">
                                        </iframe>
                                    </div>
                                </article>
                            </section>                                  
                        <?php } else { ?>
                            <section class="template-demo">
                                <article 
                                    class="template-card"
                                    href="<?php echo esc_url( solace_extra_get_elementor_content_preview_url( $post_id ) ); ?>"
                                    data-fancybox
                                    data-type="iframe"
                                >
                                    <div class="iframe-wrapper">
                                        <iframe 
                                            id="iframe-preview-<?php echo esc_attr( $solace_extra_part ); ?>"
                                            class="iframe-preview"
                                            title="Template Preview" 
                                            src="<?php echo esc_url( solace_extra_get_elementor_content_preview_url( $post_id ) ); ?>">
                                        </iframe>
                                    </div>
                                </article>
                            </section>
                        <?php } ?>
                    </div>
                    
                    <?php endwhile; 

                    if ( !$solace_extra_is_there_post ) {
                        $solace_extra_part_labels = array(
                            'header'           => __( 'Header', 'solace-extra' ),
                            'footer'           => __( 'Footer', 'solace-extra' ),
                            'singleproduct'    => __( 'Shop Single Product', 'solace-extra' ),
                            'shopproduct'      => __( 'Shop Product', 'solace-extra' ),
                            'purchase-summary' => __( 'Purchase Summary', 'solace-extra' ),
                            'blogsinglepost'   => __( 'Blog Single Post', 'solace-extra' ),
                            'blogarchive'      => __( 'Blog Archive', 'solace-extra' ),
                            '404'              => __( '404', 'solace-extra' ),
                            'cart'             => __( 'Cart', 'solace-extra' ),
                            'checkout'         => __( 'Checkout', 'solace-extra' ),
                            'myaccount'        => __( 'My Account', 'solace-extra' ),
                        );

                        $solace_extra_part_label = isset( $solace_extra_part_labels[ $solace_extra_part ] ) ? $solace_extra_part_labels[ $solace_extra_part ] : ucfirst( $solace_extra_part );

                        ?>
                        <div class="banner_part_empty">
                            <div class="banner_left">
                                <span class="title">
                                    <?php echo esc_html__( 'Start Building', 'solace-extra' ); ?>
                                </span>
                                <span class="desc">
                                    <?php echo esc_html__( 'Build you blank ', 'solace-extra' ) . esc_html( $solace_extra_part_label ) . ' Part'; ?>
                                </span>
                                <a href="#" class="button addnew">
                                    <?php echo esc_html__( 'BUILD NOW ', 'solace-extra' ); ?>
                                </a>
                            </div>
                        </div>

                    <?php
                    }
            ?>
                </div>

                <!-- Overlay -->
                <div id="edit-conditions-overlay" class="solace-overlay" style="display:none;"></div>
                <div id="add-new-overlay" class="solace-overlay" style="display:none;"></div>


                <!-- Popup "Add New" -->
                <div id="add-new-popup" class="solace-popup" data-popup-type="add" style="display:none;">
                    <?php wp_nonce_field('solace_conditions_nonce_action', 'solace_conditions_nonce_action'); ?>
                    <div class="solace-popup-content">
                        <span class="close-popup">&times;</span>
                        <span class="addnew-title"><?php echo esc_html__('Where do you want to display your template?', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('Set the conditions that determine where your template is used throughout your website.', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('For example, choose "Entire Site" to display the template accross your site.', 'solace-extra'); ?></span>
                        <div id="new-conditions-container">
                            <div class="condition-item">
                                <span class="icon-container">
                                <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/plus-include.svg' ); ?>" />
                                </span>
                                <select name="condition_1_exclude_include" class="condition-exclude-include-select">
                                    <option value="include"><?php echo esc_html__('Include', 'solace-extra'); ?></option>
                                    <option value="exclude"><?php echo esc_html__('Exclude', 'solace-extra'); ?></option>
                                </select>
                                <select name="condition_1_include[]" class="condition-select rico1">
                                    <option value=""><?php esc_html_e( '— Select —', 'solace-extra' ); ?></option>

                                    <optgroup label="<?php esc_attr_e( 'Basic', 'solace-extra' ); ?>" class="counts-undefined">
                                        <option value="basic-global"><?php esc_html_e( 'Entire Website', 'solace-extra' ); ?></option>
                                        <option value="basic-singulars"><?php esc_html_e( 'All Singulars', 'solace-extra' ); ?></option>
                                        <option value="basic-archives"><?php esc_html_e( 'All Archives', 'solace-extra' ); ?></option>
                                    </optgroup>

                                    <optgroup label="<?php esc_attr_e( 'Special Pages', 'solace-extra' ); ?>" class="counts-undefined">
                                        <option value="special-404"><?php esc_html_e( '404 Page', 'solace-extra' ); ?></option>
                                        <option value="special-search"><?php esc_html_e( 'Search Page', 'solace-extra' ); ?></option>
                                        <option value="special-blog"><?php esc_html_e( 'Blog / Posts Page', 'solace-extra' ); ?></option>
                                        <option value="special-front"><?php esc_html_e( 'Front Page', 'solace-extra' ); ?></option>
                                        <option value="special-date"><?php esc_html_e( 'Date Archive', 'solace-extra' ); ?></option>
                                        <option value="special-author"><?php esc_html_e( 'Author Archive', 'solace-extra' ); ?></option>
                                    </optgroup>

                                    <optgroup label="<?php esc_attr_e( 'Posts', 'solace-extra' ); ?>" class="counts-undefined">
                                        <option value="post|all"><?php esc_html_e( 'All Posts', 'solace-extra' ); ?></option>
                                        <option value="post|all|taxarchive|category"><?php esc_html_e( 'All Categories Archive', 'solace-extra' ); ?></option>
                                        <option value="post|all|taxarchive|post_tag"><?php esc_html_e( 'All Tags Archive', 'solace-extra' ); ?></option>
                                    </optgroup>

                                    <optgroup label="<?php esc_attr_e( 'Pages', 'solace-extra' ); ?>" class="counts-undefined">
                                        <option value="page|all"><?php esc_html_e( 'All Pages', 'solace-extra' ); ?></option>
                                    </optgroup>

                                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                                        <optgroup label="<?php esc_attr_e( 'Products', 'solace-extra' ); ?>" class="counts-undefined">
                                            <option value="special-shop"><?php esc_html_e( 'Shop Page', 'solace-extra' ); ?></option>
                                            <option value="product|all"><?php esc_html_e( 'All Products', 'solace-extra' ); ?></option>
                                            <!-- <option value="product|all|archive"><?php esc_html_e( 'All Products Archive', 'solace-extra' ); ?></option> -->
                                            <option value="product|all|taxarchive|product_cat"><?php esc_html_e( 'All Product Categories Archive', 'solace-extra' ); ?></option>
                                            <option value="product|all|taxarchive|product_tag"><?php esc_html_e( 'All Product Tags Archive', 'solace-extra' ); ?></option>
                                        </optgroup>
                                    <?php endif; ?>
                                </select>


                                <a href="#" class="delete-condition dashicons dashicons-trash hide" title="Delete Condition"></a>
                            </div>
                            
                        </div>
                        <div class="solace-popup-footer">
                            <button id="addnew-add-condition" class="button newaddcondition"><?php echo esc_html__('Add Condition', 'solace-extra'); ?></button>
                        </div>
                        <button id="save-new-conditions" class="button"><?php echo esc_html__('Save and Add Template', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json' ); ?>" background="transparent" speed="1" style="width: 350px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Popup "Edit Conditions" -->
                <div id="edit-conditions-popup" class="solace-popup" data-popup-type="" style="display:none;">
                    <div class="solace-popup-content">
                        <span class="close-popup">&times;</span>
                        <span class="addnew-title"><?php echo esc_html__('Where do you want to display your template?', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('Set the conditions that determine where your template is used throughout your website.', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('For example, choose "Entire Site" to display the template across your site.', 'solace-extra'); ?></span>
                        <div id="edit-conditions-container">
                            
                        </div>
                        <div class="solace-popup-footer">
                            <button id="add-condition" class="button addcondition"><?php echo esc_html__('Add Condition', 'solace-extra'); ?></button>
                        </div>
                        <button id="save-conditions" class="button savenclose"><?php echo esc_html__('Save Conditions', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json' ); ?>" background="transparent" speed="1" style="width: 350px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    </div>
                </div>
                <div id="solace-rename-popup" class="solace-rename-popup-overlay" style="display:none;">
                    <div class="solace-rename-popup-content">
                        <span class="solace-close-popup">&times;</span>
                        <span class="addnew-title"><?php echo esc_html__('Rename', 'solace-extra'); ?></span>
                        <label><?php echo esc_html__('New Title', 'solace-extra'); ?></label>
                        <input type="text" id="solace-rename-field" placeholder="Enter new title" />
                        <button id="solace-save-rename" class="solace-rename-button solace-rename-button-primary"><?php echo esc_html__('Save', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json' ); ?>" background="transparent" speed="1" style="width: 350px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <?php else : ?>
                <div class="card-grid">
                    <?php /*if ($solace_extra_query->have_posts()) : */
                        $solace_extra_status_header = solace_get_part_status('header');
                        $solace_extra_status_footer = solace_get_part_status('footer');
                        $solace_extra_status_singleproduct = solace_get_part_status('singleproduct');
                        $solace_extra_status_shopproduct = solace_get_part_status('shopproduct');
                        $solace_extra_status_purchase_summary = solace_get_part_status('purchase-summary');
                        $solace_extra_status_blogsinglepost = solace_get_part_status('blogsinglepost');
                        $solace_extra_status_blogarchive = solace_get_part_status('blogarchive');
                        $solace_extra_status_404 = solace_get_part_status('404');
                        ?>
                        <a class="part-header <?php echo esc_attr($solace_extra_status_header['lock_class']); ?>" href="<?php echo esc_url(add_query_arg('part', 'header', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>">
                            <div class="card" style="background-image: url('<?php 
                            echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_status_header['image'] ); 
                            ?>');">
                                <div class="card-body"></div>
                                <div class="card-footer <?php echo esc_attr($solace_extra_status_header['active_blue']);?>">
                                    <span class='title'><?php esc_html_e( 'Header', 'solace-extra' ); ?></span>
                                    <?php
                                    if ( function_exists( 'solace_sitebuilder_elementor_pro_card_badge' ) ) {
                                        solace_sitebuilder_elementor_pro_card_badge( 'header' );
                                    }
                                    ?>
                                    <label class="switch layout">
                                        <input type="checkbox" class="all-status-switch <?php echo $solace_extra_status_header['is_disabled'] ? 'disabled-checkbox' : ''; ?>" data-part-global="header" <?php echo $solace_extra_status_header['is_checked'] ? 'checked' : ''; ?> />
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </a>
                        <a class="part-footer <?php echo esc_attr($solace_extra_status_footer['lock_class']); ?>" href="<?php echo esc_url(add_query_arg('part', 'footer', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>">
                            <div class="card" style="background-image: url('<?php 
                            echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_status_footer['image']); 
                            ?>');">
                                <div class="card-body"></div>
                                <div class="card-footer <?php echo esc_attr($solace_extra_status_footer['active_blue']);?>">
                                    <span class='title'><?php esc_html_e( 'Footer', 'solace-extra' ); ?></span>
                                    <?php
                                    if ( function_exists( 'solace_sitebuilder_elementor_pro_card_badge' ) ) {
                                        solace_sitebuilder_elementor_pro_card_badge( 'footer' );
                                    }
                                    ?>
                                    <label class="switch layout">
                                        <input type="checkbox" class="all-status-switch <?php echo $solace_extra_status_footer['is_disabled'] ? 'disabled-checkbox' : ''; ?>" data-part-global="footer" <?php echo $solace_extra_status_footer['is_checked'] ? 'checked' : ''; ?> />
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </a>
                        
                        <?php 
                        
                        if (function_exists('solace_pro_card') && solace_pro_card()) {
                            // Pro skips the Elementor badge on the cart, checkout and account cards.
                            ob_start();
                            do_action( 'solace_pro_cards_render' );
                            $solace_extra_cards_html = (string) ob_get_clean();
                            echo function_exists( 'solace_sitebuilder_add_woo_page_card_badges' ) ? solace_sitebuilder_add_woo_page_card_badges( $solace_extra_cards_html ) : $solace_extra_cards_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Pro's own card markup, printed as before.
                        } else { ?>
                            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <a class="part-singleproduct lock" target="_blank" href="<?php echo esc_url( SOLACE_UPGRADE_URL ); ?>">
                            <div class="card" style="background-image: url('<?php 
                                    $solace_extra_image = 'singleproduct.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_image ); 
                                ?>');">
                                    <div class="card-body">
                                        <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Upgrade', 'solace-extra' ); ?>
                                        </button>
                                    </div>
                                    <div class="card-footer">
                                        <span class='title'><?php esc_html_e( 'Shop Single Product', 'solace-extra' ); ?></span>
                                    </div>
                                </div>
                            </a>
                            <a class="part-shopproductcategories lock" target="_blank" href="<?php echo esc_url( SOLACE_UPGRADE_URL ); ?>">
                            <div class="card" style="background-image: url('<?php 
                                    $solace_extra_image = 'shopproductcategories.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_image ); 
                                ?>');">
                                    <div class="card-body"> <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Upgrade', 'solace-extra' ); ?>
                                        </button></div>
                                    <div class="card-footer">
                                        <span class='title'><?php esc_html_e( 'Shop Product Archive', 'solace-extra' ); ?></span>
                                    </div>
                                </div>
                            </a>
                            <a class="part-purchase-summary lock" target="_blank" href="<?php echo esc_url( SOLACE_UPGRADE_URL ); ?>">
                            <div class="card" style="background-image: url('<?php 
                                    $solace_extra_image = 'shopproductcategories.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_image ); 
                                ?>');">
                                    <div class="card-body"> <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Upgrade', 'solace-extra' ); ?>
                                        </button></div>
                                    <div class="card-footer">
                                        <span class='title'><?php esc_html_e( 'Purchase Summary', 'solace-extra' ); ?></span>
                                    </div>
                                </div>
                            </a>
                            <?php if ( function_exists( 'solace_woo_page_parts_enabled' ) && solace_woo_page_parts_enabled() ) : // Cart, checkout and account parts; off when the solace_sitebuilder_woo_page_parts_enabled filter returns false. ?>
                            <a class="part-cart lock" target="_blank" href="<?php echo esc_url( SOLACE_UPGRADE_URL ); ?>">
                            <div class="card" style="background-image: url('<?php 
                                    $solace_extra_image = 'purchase-summary.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_image ); 
                                ?>');">
                                    <div class="card-body"> <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Upgrade', 'solace-extra' ); ?>
                                        </button></div>
                                    <div class="card-footer">
                                        <span class='title'><?php esc_html_e( 'Cart', 'solace-extra' ); ?></span>
                                    </div>
                                </div>
                            </a>
                            <a class="part-checkout lock" target="_blank" href="<?php echo esc_url( SOLACE_UPGRADE_URL ); ?>">
                            <div class="card" style="background-image: url('<?php 
                                    $solace_extra_image = 'purchase-summary.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_image ); 
                                ?>');">
                                    <div class="card-body"> <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Upgrade', 'solace-extra' ); ?>
                                        </button></div>
                                    <div class="card-footer">
                                        <span class='title'><?php esc_html_e( 'Checkout', 'solace-extra' ); ?></span>
                                    </div>
                                </div>
                            </a>
                            <a class="part-myaccount lock" target="_blank" href="<?php echo esc_url( SOLACE_UPGRADE_URL ); ?>">
                            <div class="card" style="background-image: url('<?php 
                                    $solace_extra_image = 'purchase-summary.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_image ); 
                                ?>');">
                                    <div class="card-body"> <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Upgrade', 'solace-extra' ); ?>
                                        </button></div>
                                    <div class="card-footer">
                                        <span class='title'><?php esc_html_e( 'My Account', 'solace-extra' ); ?></span>
                                    </div>
                                </div>
                            </a>
                            <?php endif; ?>
                            <?php endif; ?>
                            <a class="part-blogsinglepost lock" target="_blank" href="<?php echo esc_url( SOLACE_UPGRADE_URL ); ?>">
                            <div class="card" style="background-image: url('<?php 
                                    $solace_extra_image = 'blogsinglepost.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_image ); 
                                ?>');">
                                    <div class="card-body">
                                        <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Upgrade', 'solace-extra' ); ?>
                                        </button>
                                    </div>
                                    <div class="card-footer">
                                        <span class='title'><?php esc_html_e( 'Blog Single Post', 'solace-extra' ); ?></span>
                                    </div>
                                </div>
                            </a>
                            <a class="part-blogarchive lock" target="_blank" href="<?php echo esc_url( SOLACE_UPGRADE_URL ); ?>">
                                <div class="card" style="background-image: url('<?php 
                                    $solace_extra_image = 'blogarchive.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_image ); 
                                ?>');">
                                    <div class="card-body"> <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Upgrade', 'solace-extra' ); ?>
                                        </button></div>
                                    <div class="card-footer">
                                        <span class='title'><?php esc_html_e( 'Blog Archive', 'solace-extra' ); ?></span>
                                    </div>
                                </div>
                            </a>
                            <a class="part-404 lock" target="_blank" href="<?php echo esc_url( SOLACE_UPGRADE_URL ); ?>">
                                <div class="card" style="background-image: url('<?php 
                                    $solace_extra_image = '404.svg';
                                    echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_image); 
                                ?>');">
                                    <div class="card-body">
                                        <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Upgrade', 'solace-extra' ); ?>
                                        </button>
                                    </div>
                                    <div class="card-footer ">
                                        <span class='title'><?php esc_html_e( '404', 'solace-extra' ); ?></span>
                                    </div>
                                </div>
                            </a>
                        <?php }?>
                        
                    <?php //endif; ?>
                    

                    <a href="#">
                        <div class="card newpart" style="background-image: url('<?php 
                            $solace_extra_image = 'sitebuilder-addnew.svg';
                            echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $solace_extra_image );?>'); 
                            background-position: center; 
                            background-repeat: no-repeat; 
                            background-size: auto;
                            position: relative;">
                            <span class='label'><?php esc_html_e( 'Add New', 'solace-extra' ); ?></label>
                        </div>
                    </a>
                </div>
                <!-- Popup "Add New" -->
                <div id="all-add-new-overlay" class="solace-overlay" style="display:none;"></div>
                <div id="all-add-new-popup" class="solace-popup" data-popup-type="add" style="display:none;">
                    <?php wp_nonce_field('solace_conditions_nonce_action', 'solace_conditions_nonce_action'); ?>
                    <div class="solace-popup-content">
                        <span class="close-popup">&times;</span>
                        <span class="addnew-title"><?php echo esc_html__('Where do you want to display your template?', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('Set the conditions that determine where your template is used throughout your website.', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('For example, choose "Entire Site" to display the template accross your site.', 'solace-extra'); ?></span>
                        <span class='chooseparts'><?php esc_html_e( 'Choose Parts', 'solace-extra' ); ?><select name="part" class="condition-part-select">
                            <option value="header"><?php echo esc_html__('Header', 'solace-extra'); ?></option>
                            <option value="footer"><?php echo esc_html__('Footer', 'solace-extra'); ?></option>
                            <?php 
                            if (function_exists('solace_pro_option_404')) {
                                echo wp_kses_post( solace_pro_option_404() );
                            } 
                            ?>
                        </select></span>
                        <div id="all-new-conditions-container">
                            <div class="condition-item">
                                <span class="icon-container">
                                <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/plus-include.svg' ); ?>" />
                                </span>
                                <select name="condition_1_exclude_include" class="condition-exclude-include-select">
                                    <option value="include"><?php echo esc_html__('Include', 'solace-extra'); ?></option>
                                    <option value="exclude"><?php echo esc_html__('Exclude', 'solace-extra'); ?></option>
                                </select>
                                <select name="condition_1_include[]" class="condition-select rico1">
                                    <option value=""><?php esc_html_e( '— Select —', 'solace-extra' ); ?></option>

                                    <optgroup label="<?php esc_attr_e( 'Basic', 'solace-extra' ); ?>" class="counts-undefined">
                                        <option value="basic-global"><?php esc_html_e( 'Entire Website', 'solace-extra' ); ?></option>
                                        <option value="basic-singulars"><?php esc_html_e( 'All Singulars', 'solace-extra' ); ?></option>
                                        <option value="basic-archives"><?php esc_html_e( 'All Archives', 'solace-extra' ); ?></option>
                                    </optgroup>

                                    <optgroup label="<?php esc_attr_e( 'Special Pages', 'solace-extra' ); ?>" class="counts-undefined">
                                        <option value="special-404"><?php esc_html_e( '404 Page', 'solace-extra' ); ?></option>
                                        <option value="special-search"><?php esc_html_e( 'Search Page', 'solace-extra' ); ?></option>
                                        <option value="special-blog"><?php esc_html_e( 'Blog / Posts Page', 'solace-extra' ); ?></option>
                                        <option value="special-front"><?php esc_html_e( 'Front Page', 'solace-extra' ); ?></option>
                                        <option value="special-date"><?php esc_html_e( 'Date Archive', 'solace-extra' ); ?></option>
                                        <option value="special-author"><?php esc_html_e( 'Author Archive', 'solace-extra' ); ?></option>
                                    </optgroup>

                                    <optgroup label="<?php esc_attr_e( 'Posts', 'solace-extra' ); ?>" class="counts-undefined">
                                        <option value="post|all"><?php esc_html_e( 'All Posts', 'solace-extra' ); ?></option>
                                        <option value="post|all|taxarchive|category"><?php esc_html_e( 'All Categories Archive', 'solace-extra' ); ?></option>
                                        <option value="post|all|taxarchive|post_tag"><?php esc_html_e( 'All Tags Archive', 'solace-extra' ); ?></option>
                                    </optgroup>

                                    <optgroup label="<?php esc_attr_e( 'Pages', 'solace-extra' ); ?>" class="counts-undefined">
                                        <option value="page|all"><?php esc_html_e( 'All Pages', 'solace-extra' ); ?></option>
                                    </optgroup>

                                    <?php if ( class_exists('WooCommerce') ) : ?>
                                        <optgroup label="<?php esc_attr_e( 'Products', 'solace-extra' ); ?>" class="counts-undefined">
                                            <option value="special-shop"><?php esc_html_e( 'Shop Page', 'solace-extra' ); ?></option>
                                            <option value="product|all"><?php esc_html_e( 'All Products', 'solace-extra' ); ?></option>
                                            <!-- <option value="product|all|archive"><?php esc_html_e( 'All Products Archive', 'solace-extra' ); ?></option> -->
                                            <option value="product|all|taxarchive|product_cat"><?php esc_html_e( 'All Product Categories Archive', 'solace-extra' ); ?></option>
                                            <option value="product|all|taxarchive|product_tag"><?php esc_html_e( 'All Product Tags Archive', 'solace-extra' ); ?></option>
                                        </optgroup>
                                    <?php endif; ?>
                                </select>


                                <a href="#" class="delete-condition dashicons dashicons-trash hide" title="Delete Condition"></a>
                            </div>
                            
                        </div>
                        <div class="solace-popup-footer">
                            <button id="partnew-add-condition" class="button partnewaddcondition"><?php echo esc_html__('Add Condition', 'solace-extra'); ?></button>
                        </div>
                        <button id="partnew-save-new-conditions" class="button"><?php echo esc_html__('Save and Add Template', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json' ); ?>" background="transparent" speed="1" style="width: 350px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    }?>
</div>

<?php wp_reset_postdata(); ?>
