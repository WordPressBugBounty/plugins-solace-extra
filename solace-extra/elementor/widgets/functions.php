<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'elementor/documents/register_controls', function( $document ) {
    $post_id = $document->get_main_id();
    $template_post_type = get_post_type( $post_id );

    $document_type_name = $document::get_type();
    $template_type = get_post_meta( $post_id, '_solace_template', true );
    // Only these four parts have preview controls. The shared helper reads
    // _solace_template first and keeps its status-meta guess inside this set.
    $template_part = solace_get_part_for_post( $post_id, array( 'blogarchive', 'blogsinglepost', 'singleproduct', 'shopproduct' ) );

    $settings = $document->get_settings();
    $target_post_type = isset( $settings['post_type'] ) ? $settings['post_type'] : 'not set';

    if ( 'solace-sitebuilder' === $template_post_type && 'blogarchive' === $template_part ) {
        $document->start_controls_section(
            'solace_preview_section',
            [
                'label' => __( 'Preview Settings', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
            ]
        );

        $categories = get_categories([
            'hide_empty' => false,
        ]);

        $category_options = [];
        foreach ( $categories as $category ) {
            $category_options[ $category->term_id ] = $category->name;
        }

        $document->add_control(
            'preview_settings_category',
            [
                'label'       => __( 'Preview Blog Category', 'solace-extra' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'options'     => $category_options,
                'multiple'    => false,
                'label_block' => true,
            ]
        );

        $document->add_control(
            'preview_apply_button',
            [
                'raw'      => '<button id="category-blog-preview-apply"  data-apply="blogarchive" class="elementor-button elementor-button-success">Apply Preview</button>',
                'type'     => \Elementor\Controls_Manager::RAW_HTML,
                'separator'=> 'before',
            ]
        );

        $document->end_controls_section();
    } elseif ( 'solace-sitebuilder' === $template_post_type && 'blogsinglepost' === $template_part ) {
        $document->start_controls_section(
            'solace_preview_section',
            [
                'label' => __( 'Preview Settings', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
            ]
        );

        $document->add_control(
            'preview_settings_post',
            [
                'label' => __( 'Preview Post', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => solace_get_all_posts_for_elementor(),
                'multiple' => false,
                'label_block' => true,
            ]
        );

        $document->add_control(
            'preview_apply_button',
            [
                'raw'      => '<button id="custom-preview-apply" data-apply="singlepost" class="elementor-button elementor-button-success">Apply Preview</button>',
                'type'     => \Elementor\Controls_Manager::RAW_HTML,
                'separator'=> 'before',
            ]
        );

        $document->end_controls_section();
    } elseif ( 'solace-sitebuilder' === $template_post_type && 'singleproduct' === $template_part ) {
        $document->start_controls_section(
            'solace_preview_section',
            [
                'label' => __( 'Preview Settings', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
            ]
        );

        $document->add_control(
            'preview_settings_product',
            [
                'label' => __( 'Preview Product', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => solace_get_all_woocommerce_products_for_elementor(),
                'multiple' => false,
                'label_block' => true,
            ]
        );

        $document->add_control(
            'preview_apply_button',
            [
                'raw'      => '<button id="custom-preview-apply" data-apply="singleproduct" class="elementor-button elementor-button-success">Apply Preview</button>',
                'type'     => \Elementor\Controls_Manager::RAW_HTML,
                'separator'=> 'before',
            ]
        );

        $document->end_controls_section();
    } elseif ( 'solace-sitebuilder' === $template_post_type && 'shopproduct' === $template_part ) {
        $document->start_controls_section(
            'solace_preview_section',
            [
                'label' => __( 'Preview Settings', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
            ]
        );

        $product_categories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ]);

        $category_options = [];
        foreach ( $product_categories as $category ) {
            $category_options[ $category->term_id ] = $category->name;
        }

        $document->add_control(
            'preview_settings_product_category',
            [
                'label'       => __( 'Preview Product Category', 'solace-extra' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'options'     => $category_options,
                'multiple'    => false,
                'label_block' => true,
            ]
        );

        $document->add_control(
            'preview_apply_button',
            [
                'raw'      => '<button id="category-shop-preview-apply" data-apply="shopproduct" class="elementor-button elementor-button-success">Apply Preview</button>',
                'type'     => \Elementor\Controls_Manager::RAW_HTML,
                'separator'=> 'before',
            ]
        );

        $document->end_controls_section();
    }
    // elseif ( 'solace-sitebuilder' === get_post_type( $document->get_main_id() ) ) {
    //     $document->start_controls_section(
    //         'solace_preview_section',
    //         [
    //             'label' => __( 'Preview Settings', 'solace-extra' ),
    //             'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
    //         ]
    //     );

    //     $document->add_control(
    //         'preview_settings_product',
    //         [
    //             'label' => __( 'Preview Product', 'solace-extra' ),
    //             'type' => \Elementor\Controls_Manager::SELECT2,
    //             'options' => solace_get_all_woocommerce_products_for_elementor(),
    //             'multiple' => false,
    //             'label_block' => true,
    //         ]
    //     );

    //     $document->add_control(
    //         'preview_settings_post',
    //         [
    //             'label' => __( 'Preview Post', 'solace-extra' ),
    //             'type' => \Elementor\Controls_Manager::SELECT2,
    //             'options' => solace_get_all_posts_for_elementor(),
    //             'multiple' => false,
    //             'label_block' => true,
    //         ]
    //     );

        
    //     $document->add_control(
    //         'preview_apply_button',
    //         [
    //             'raw' => '<button id="custom-preview-apply" class="elementor-button elementor-button-success">Apply Preview</button>',
    //             'type' => \Elementor\Controls_Manager::RAW_HTML,
    //             'separator' => 'before',
    //         ]
    //     );

    //     $document->end_controls_section();
    // }
} );


// add_action('wp_ajax_solace_save_custom_preview_id', function() {
//     if ( ! current_user_can('edit_posts') ) {
//         wp_send_json_error('Unauthorized');
//     }

//     // phpcs:ignore WordPress.Security.NonceVerification.Missing
//     $product_id         = isset($_POST['product_id']) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;

//     // phpcs:ignore WordPress.Security.NonceVerification.Missing
//     $previewproduct_id  = isset($_POST['previewproduct_id']) ? absint( wp_unslash( $_POST['previewproduct_id'] ) ) : 0;

//     // phpcs:ignore WordPress.Security.NonceVerification.Missing
//     $post_id            = isset($_POST['post_id']) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;

//     // phpcs:ignore WordPress.Security.NonceVerification.Missing
//     $previewpostId      = isset($_POST['previewpostId']) ? absint( wp_unslash( $_POST['previewpostId'] ) ) : 0;

//     if ( ! $product_id || ! $post_id ) {
//         wp_send_json_error('Invalid data');
//     }



//     delete_post_meta($product_id, '_elementor_preview_settings');
//     delete_post_meta($post_id, '_elementor_preview_settings_post');

//     update_post_meta($product_id, '_elementor_preview_settings', [
//         'preview_id' => $previewproduct_id,
//     ]);

//     update_post_meta($post_id, '_elementor_preview_settings_post', [
//         'preview_id' => $previewpostId,
//     ]);


//     wp_send_json_success('Preview ID saved.');
// });

// add_filter('pre_option_posts_per_page', function($value) {
//     $is_applicable = (!is_admin() && (is_product_category() || is_shop() || is_product_tag()));
    
//     if ($is_applicable) {
//         return 6; 
//     }
    
//     return $value;
// });

add_filter('redirect_canonical', function($redirect_url) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return $redirect_url;
    }

    if ( is_shop() || is_product_category() || is_product_tag() ) {
        return false;
    }

    return $redirect_url;
}, 10, 1);

// add_action('pre_get_posts', function($query) {
//     if (!is_admin() && $query->is_main_query() && (is_product_category() || is_shop() || is_product_tag())) {
//         $query->set('posts_per_page', 1);
//     }
// }, 1);

// add_action('template_redirect', function() {
//     if (is_product_category() || is_shop() || is_product_tag()) {
//         global $wp_query;

//         $paged = get_query_var('paged') ? get_query_var('paged') : get_query_var('page');
        
//         if ($wp_query->is_404 && $paged > 1) {
            
//             $document = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( get_queried_object_id() );
            
//             $has_widget = false;
            
//             if ( $document ) {
//                 $elementor_data = get_post_meta( $document->get_main_id(), '_elementor_data', true );
//                 if ( ! empty( $elementor_data ) && strpos( $elementor_data, 'solace-extra-woocommerce-shop' ) !== false ) {
//                     $has_widget = true;
//                 }
//             }

//             if ( ! $has_widget ) {
//                 return;
//             }

//             status_header(200);
//             $wp_query->is_404 = false;
//             $wp_query->is_archive = true;
//             $wp_query->is_tax = ( is_product_category() || is_product_tag() );
            
//         }
//     }
// }, 5); 

add_action('pre_get_posts', function($query) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    if (!is_admin() && $query->is_main_query() && (is_product_category() || is_shop() || is_product_tag())) {
        $paged = max(1, get_query_var('paged'), get_query_var('page'));
        if ($paged > 1) {
            $query->set('posts_per_page', 1);            
            $query->set('no_found_rows', false); 
        }
    }
}, 1);

// add_action( 'wp', function() {
//     if ( ! is_admin() && (is_product_category() || is_shop()) && get_query_var('paged') > 1 ) {
//         global $wp_query;
        
//         if ( $wp_query->is_404 ) {
//             status_header( 200 );
//             $wp_query->is_404 = false;
//             $wp_query->is_archive = true; 
//         }
//     }
// }, 1 );

// add_filter( 'redirect_canonical', function( $redirect_url ) {
//     if ( is_product_category() || is_shop() ) {
//         return false; 
//     }
//     return $redirect_url;
// }, 10 );

// function solace_custom_blog_posts_per_pagex( $value ) {
//     if ( ! is_admin() && ( is_product_category() || is_shop() || is_product_tag() ) ) {
        
//         $paged = max(1, get_query_var('paged'), get_query_var('page'));
        
//         if ($paged > 1) {
//             return 1; 
//         }
//     }

//     return $value;
// }
// add_filter( 'option_posts_per_page', 'solace_custom_blog_posts_per_pagex' );

if ( ! function_exists( 'solace_extra_can_edit_preview_target' ) ) {
    /**
     * Whether the current user may write preview settings onto this part.
     *
     * The three preview handlers below all write post meta onto an ID the
     * caller chooses, so that ID has to be authorised on its own. A bare
     * `edit_posts` check is not enough: every Contributor has it, and it says
     * nothing about the post actually being targeted. The post-type check keeps
     * the `_elementor_preview_settings*` meta keys from landing on an unrelated
     * post -- the controls that drive these requests are only ever registered
     * on a solace-sitebuilder document (see the register_controls handler at the
     * top of this file), so nothing legitimate is turned away by it.
     *
     * @since 1.7.3
     * @param int $post_id Site Builder part the request wants to write to.
     * @return bool
     */
    function solace_extra_can_edit_preview_target( $post_id ) {
        $post_id = absint( $post_id );

        return $post_id
            && 'solace-sitebuilder' === get_post_type( $post_id )
            && current_user_can( 'edit_post', $post_id );
    }
}

add_action('wp_ajax_solace_save_custom_preview_id', function() {

    check_ajax_referer( 'solace_save_preview', 'nonce' );

    $template_product_id = isset($_POST['product_id']) ? absint( wp_unslash($_POST['product_id']) ) : 0;

    $preview_product_id = isset($_POST['previewproduct_id']) ? absint( wp_unslash($_POST['previewproduct_id']) ): 0;

    $template_post_id = isset($_POST['post_id']) ? absint( wp_unslash($_POST['post_id']) ) : 0;

    $preview_post_id = isset($_POST['previewpostId']) ? absint( wp_unslash($_POST['previewpostId']) ) : 0;

    // One click on #custom-preview-apply fires this action twice -- once with
    // product_id, once with post_id -- and only one of the two carries a
    // template. Authorise whichever arrived; refuse the request outright when
    // neither did, so a bare nonce can never reach the writes below.
    if ( ! $template_product_id && ! $template_post_id ) {
        wp_send_json_error( 'Invalid data.', 400 );
    }

    if ( $template_product_id && ! solace_extra_can_edit_preview_target( $template_product_id ) ) {
        wp_send_json_error( 'Unauthorized.', 403 );
    }

    if ( $template_post_id && ! solace_extra_can_edit_preview_target( $template_post_id ) ) {
        wp_send_json_error( 'Unauthorized.', 403 );
    }

    if ( $template_product_id && $preview_product_id ) {

        delete_post_meta($template_product_id, '_elementor_preview_settings');

        update_post_meta($template_product_id, '_elementor_preview_settings', [
            'preview_id' => $preview_product_id,
        ]);
    }

    if ( $template_post_id && $preview_post_id ) {

        delete_post_meta($template_post_id, '_elementor_preview_settings_post');

        update_post_meta($template_post_id, '_elementor_preview_settings_post', [
            'preview_id' => $preview_post_id,
        ]);
    }

    wp_send_json_success('Preview saved');
});

add_action('wp_ajax_solace_save_blogarchive_preview_category', function() {

    check_ajax_referer( 'solace_save_preview', 'nonce' );

    $post_id                = isset($_POST['post_id']) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;

    $preview_blog_category  = isset($_POST['preview_blog_category']) ? absint( wp_unslash( $_POST['preview_blog_category'] ) ) : 0;

    if ( ! $post_id  ) {
        wp_send_json_error('Invalid data');
    }

    if ( ! solace_extra_can_edit_preview_target( $post_id ) ) {
        wp_send_json_error( 'Unauthorized.', 403 );
    }

    
    if ( ! $preview_blog_category ) {
        delete_post_meta( $post_id, '_elementor_preview_settings_category' );
    }else {
        update_post_meta($post_id, '_elementor_preview_settings_category', [
            'preview_category' => $preview_blog_category,
        ]);
    }

    wp_send_json_success('Preview blog category saved.');
});

add_action('wp_ajax_solace_save_shopproduct_preview_category', function() {

    check_ajax_referer( 'solace_save_preview', 'nonce' );

    $post_id                = isset($_POST['post_id']) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;

    $preview_shop_category  = isset($_POST['preview_shop_category']) ? absint( wp_unslash( $_POST['preview_shop_category'] ) ) : 0;

    if ( ! $post_id  ) {
        wp_send_json_error('Invalid data');
    }

    if ( ! solace_extra_can_edit_preview_target( $post_id ) ) {
        wp_send_json_error( 'Unauthorized.', 403 );
    }
    
    if ( ! $preview_shop_category ) {
        // delete_post_meta( $post_id, '_solace_template' );
        delete_post_meta( $post_id, '_elementor_preview_settings_category_shop' );
        
    } else {
        update_post_meta( $post_id, '_elementor_preview_settings_category_shop', [
            'preview_category' => $preview_shop_category,
        ]);
    }

    wp_send_json_success('Preview product category saved.');
});



add_action( 'save_post_solace-sitebuilder', function( $post_id ) {
    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    if ( isset($_POST['elementor_document_settings']['preview_settings_product']) ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $product_id = absint( $_POST['elementor_document_settings']['preview_settings_product'] );

        update_post_meta( $post_id, '_elementor_preview_settings', [
            'preview_type' => 'post',
            'preview_id'   => $product_id,
        ] );
    }
});

add_action('elementor/editor/after_enqueue_scripts', function() {
    wp_enqueue_script(
        'solace-preview-button',
        plugin_dir_url(__FILE__) . 'assets/js/admin-preview-button.js',
        ['jquery'],
        SOLACE_EXTRA_VERSION,
        true
    );

    // The nonce the three preview handlers verify. This hook only fires inside
    // the Elementor editor, which a user cannot open without edit access to the
    // document, so issuing it here does not widen who can reach those handlers.
    wp_localize_script(
        'solace-preview-button',
        'solaceExtraPreview',
        array(
            'nonce' => wp_create_nonce( 'solace_save_preview' ),
        )
    );

    // Shop widget: an empty Tablet/Mobile "Products per Page" uses the larger
    // device's value; show that value as the field's placeholder so the field
    // never looks empty, and keep it current while the values are edited.
    $solace_per_page_js = <<<'JS'
(function ($) {
	var DEFAULT_PER_PAGE = 4;
	function num(v) { v = parseInt(v, 10); return v >= 1 ? v : 0; }
	function sync(panel, model) {
		var s = model.get('settings');
		var d = num(s.get('posts_per_page')) || DEFAULT_PER_PAGE;
		var t = num(s.get('posts_per_page_tablet')) || d;
		panel.$el.find('input[data-setting="posts_per_page_tablet"]').attr('placeholder', d);
		panel.$el.find('input[data-setting="posts_per_page_mobile"]').attr('placeholder', t);
	}
	var current = null;
	$(window).on('elementor:init', function () {
		if (elementor.channels && elementor.channels.deviceMode) {
			elementor.channels.deviceMode.on('change', function () { if (current) { current(); } });
		}
		elementor.hooks.addAction('panel/open_editor/widget/solace-extra-woocommerce-shop', function (panel, model) {
			var later = function () { setTimeout(function () { sync(panel, model); }, 0); };
			later();
			var s = model.get('settings');
			if (!s._solacePerPageSync) {
				s._solacePerPageSync = true;
				s.on('change:posts_per_page change:posts_per_page_tablet change:posts_per_page_mobile', later);
			}
			// Device switches and section/tab changes re-render the controls.
			panel.$el.off('.solacePerPage').on('click.solacePerPage', later);
			current = later;
		});
	});
})(jQuery);
JS;
    wp_add_inline_script( 'solace-preview-button', $solace_per_page_js );
});


if ( ! function_exists( 'solace_get_all_posts_for_elementor' ) ) {
    
    function solace_get_all_posts_for_elementor() {
        $posts = get_posts([
            'numberposts' => 100,
            'post_type'   => 'post',
            'post_status' => 'publish',
        ]);
        
        $options = [];
        foreach ( $posts as $post ) {
            $options[ $post->ID ] = $post->post_title;
        }
        
        return $options;
    }
}


if ( ! function_exists( 'solace_get_all_woocommerce_products_for_elementor' ) ) {
    function solace_get_all_woocommerce_products_for_elementor() {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        $products = wc_get_products([
            'limit' => 100,
            'status' => 'publish',
        ]);

        $options = [];
        foreach ($products as $product) {
            $options[$product->get_id()] = $product->get_name();
        }

        return $options;
    }
}

if ( ! function_exists( 'solace_check_empty_product' ) ) {
	function solace_check_empty_product( $product ) {
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return 'Sorry, no products are available.';
            
		}

		return false; 
	}
}


if ( ! function_exists( 'solace_check_empty_post' ) ) {
	function solace_check_empty_post( $post ) {
		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

		if ( ! $post || ! is_a( $post, 'WP_Post' ) ) {
			return 'Sorry, no posts are available.';
		}

		return false; 
	}
}

// if ( ! function_exists( 'solace_get_preview_product' ) ) {
//     function solace_get_preview_product() {
//         if ( function_exists( 'is_product' ) && is_product() ) {
//             global $product;
//             if ( $product && is_a( $product, 'WC_Product' ) ) {
//                 return $product;
//             }
//             return wc_get_product( get_the_ID() );
//         }

//         $template_id = get_the_ID();
//         $preview_settings = get_post_meta( $template_id, '_elementor_preview_settings', true );

//         if ( ! empty( $preview_settings['preview_id'] ) ) {
//             $product_id = absint( $preview_settings['preview_id'] );
//         } else {
//             $products = wc_get_products( [
//                 'limit'   => 1,
//                 'status'  => 'publish',
//                 'orderby' => 'rand',
//             ] );

//             if ( ! empty( $products ) ) {
//                 $product_id = $products[0]->get_id();

//                 update_post_meta( $template_id, '_elementor_preview_settings', [
//                     'preview_type' => 'product',
//                     'preview_id'   => $product_id,
//                 ] );
//             } else {
//                 return false;
//             }
//         }

//         return wc_get_product( $product_id );
//     }
// }


if ( ! function_exists( 'solace_extra_placeholder_product' ) ) {
	/**
	 * Build a virtual WooCommerce product populated with lorem-ipsum demo data.
	 *
	 * Used as a display-only fallback when the store has no products yet (e.g.
	 * right after a site reset), so the Single Product / Shop Site Builder
	 * templates render demo content instead of "Sorry, no products" or errors.
	 *
	 * The returned object is never persisted — it only exists in memory for the
	 * current render, so widgets can read title/price/description/image from it
	 * exactly like from a real product.
	 *
	 * @return \WC_Product_Simple|false
	 */
	function solace_extra_placeholder_product() {
		if ( ! class_exists( 'WC_Product_Simple' ) ) {
			return false;
		}

		$product = new \WC_Product_Simple();

		$product->set_name( 'Lorem Ipsum Dolor Sit Amet' );
		$product->set_slug( 'lorem-ipsum-dolor-sit-amet' );
		$product->set_short_description( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' );
		$product->set_description( '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>' );
		$product->set_regular_price( '129.00' );
		$product->set_sale_price( '99.00' );
		// WC_Product only syncs the derived `price` prop when the product is
		// saved to the data store; set it explicitly so get_price_html() works
		// on this in-memory fallback product.
		$product->set_props( array( 'price' => '99.00' ) );
		$product->set_sku( 'DEMO-001' );
		$product->set_stock_status( 'instock' );
		// Track stock so WC's stock availability text ("100 in stock") renders
		// for the Product Stock widget on this in-memory fallback product.
		$product->set_manage_stock( true );
		$product->set_stock_quantity( 100 );
		$product->set_average_rating( 4.5 );
		$product->set_review_count( 12 );
		$product->update_meta_data( '_solace_extra_placeholder', '1' );

		return $product;
	}
}

if ( ! function_exists( 'solace_extra_is_placeholder_product' ) ) {
	/**
	 * Whether the given product is the virtual lorem-ipsum fallback product.
	 *
	 * @param mixed $product
	 * @return bool
	 */
	function solace_extra_is_placeholder_product( $product ) {
		return $product && is_a( $product, 'WC_Product' ) && '1' === $product->get_meta( '_solace_extra_placeholder' );
	}
}


if ( ! function_exists( 'solace_extra_placeholder_product_card' ) ) {
	/**
	 * Render a single WooCommerce-style product card populated with lorem-ipsum
	 * demo data (placeholder image, title, price, add-to-cart).
	 *
	 * Used by the upsells / related-products widgets as a fallback when the
	 * store has no products yet, so their sections aren't blank in the Site
	 * Builder editor.
	 *
	 * @param string $title       Product title to display.
	 * @param string $hover_class Optional Elementor hover animation class.
	 * @return void
	 */
	function solace_extra_placeholder_product_card( $title, $hover_class = '' ) {
		$product = function_exists( 'solace_extra_placeholder_product' ) ? solace_extra_placeholder_product() : false;
		if ( ! $product ) {
			return;
		}

		echo '<li class="product ' . esc_attr( implode( ' ', wc_get_product_class( '', $product ) ) ) . '">';
		echo '<a href="#" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
		echo '<span class="onsale">' . esc_html__( 'Sale!', 'solace-extra' ) . '</span>';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC placeholder image markup.
		echo function_exists( 'wc_placeholder_img' ) ? wc_placeholder_img( 'woocommerce_thumbnail' ) : '';
		echo '<h4 class="woocommerce-loop-product__title">' . esc_html( $title ) . '</h4>';
		echo '</a>';
		echo '<span class="price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
		echo '<a href="#" data-quantity="1" data-product_id="0" class="elementor-button solace-extra-button product_type_simple add_to_cart_button ajax_add_to_cart' . esc_attr( $hover_class ) . '" rel="nofollow">' . esc_html__( 'Add to cart', 'solace-extra' ) . '</a>';
		echo '</li>';
	}
}

if ( ! function_exists( 'solace_get_preview_product' ) ) {

    function solace_get_preview_product() {

        if ( ! function_exists( 'wc_get_product' ) ) {
            return false;
        }

        // Live single-product page: use the main query product, not the Site Builder
        // document ID that Elementor may set as the current post during rendering.
        if ( function_exists( 'is_product' ) && is_product() ) {
            global $product;

            if ( $product && is_a( $product, 'WC_Product' ) ) {
                return $product;
            }

            $queried_id = get_queried_object_id();
            if ( $queried_id && 'product' === get_post_type( $queried_id ) ) {
                $queried_product = wc_get_product( $queried_id );
                if ( $queried_product && is_a( $queried_product, 'WC_Product' ) ) {
                    return $queried_product;
                }
            }

            $current_id = get_the_ID();
            if ( $current_id && 'product' === get_post_type( $current_id ) ) {
                $current_product = wc_get_product( $current_id );
                if ( $current_product && is_a( $current_product, 'WC_Product' ) ) {
                    return $current_product;
                }
            }
        }

        $template_id = get_the_ID();

        $preview_settings = get_post_meta( $template_id, '_elementor_preview_settings', true );

        if ( ! empty( $preview_settings['preview_id'] ) ) {
            $product_id      = absint( $preview_settings['preview_id'] );
            $preview_product = wc_get_product( $product_id );
            if ( $preview_product && is_a( $preview_product, 'WC_Product' ) ) {
                return $preview_product;
            }
        }

        // Fallback: no preview product selected yet (e.g. the first time a product
        // widget is dropped in the editor) — use the most recent published product.
        $products = wc_get_products(
            array(
                'limit'   => 1,
                'status'  => 'publish',
                'orderby' => 'date',
                'order'   => 'DESC',
            )
        );

        if ( ! empty( $products ) ) {
            return $products[0];
        }

        // Fresh store with no products yet: fall back to a virtual lorem-ipsum
        // product so Site Builder / Elementor previews render demo content
        // (lorem ipsum + placeholder image) instead of "Sorry, no products".
        return solace_extra_placeholder_product();
    }
}


// NORMAL
if ( ! function_exists( 'solace_get_preview_post' ) ) {
	function solace_get_preview_post() {
        // if ( ! defined( 'ELEMENTOR_VERSION' ) || ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {			
            $template_id = get_the_ID();

            $preview_settings = get_post_meta( $template_id, '_elementor_preview_settings_post', true );
            $post_id          = 0;

            // A saved preview is only honoured while it still points at a
            // published post; one left pointing at a deleted post used to be
            // returned as-is.
            if ( ! empty( $preview_settings['preview_id'] ) ) {
                $saved_id = absint( $preview_settings['preview_id'] );

                if ( $saved_id && 'publish' === get_post_status( $saved_id ) ) {
                    $post_id = $saved_id;
                }
            }

            // Otherwise default to the newest post. This used to be
            // orderby => rand, saved permanently on first open, so each template
            // ended up previewing an arbitrary post nobody had chosen. Newest is
            // predictable, and the Preview Post control still picks any other.
            if ( ! $post_id ) {
                $latest = get_posts( [
                    'numberposts' => 1,
                    'post_type'   => 'post',
                    'post_status' => 'publish',
                    'orderby'     => 'date',
                    'order'       => 'DESC',
                    'fields'      => 'ids',
                ] );

                if ( ! empty( $latest ) ) {
                    $post_id = (int) $latest[0];

                    update_post_meta( $template_id, '_elementor_preview_settings_post', [
                        'preview_id' => $post_id,
                    ] );
                } else {
                    $post_id = $template_id;
                }
            }

            return $post_id;
        }
        return false;
        // return $post_id;
	}
}

// NORMAL ADD DEBUG
// if ( ! function_exists( 'solace_get_preview_post' ) ) {
// 	function solace_get_preview_post() {
// 		$template_id = get_the_ID();

// 		$preview_settings = get_post_meta( $template_id, '_elementor_preview_settings_post', true );

// 		if ( ! empty( $preview_settings['preview_id'] ) ) {
// 			$post_id = absint( $preview_settings['preview_id'] );
// 		} else {
// 			$random_post = get_posts( [
// 				'numberposts' => 1,
// 				'post_type'   => 'post',
// 				'post_status' => 'publish',
// 				'orderby'     => 'rand',
// 			] );


// 			if ( ! empty( $random_post ) ) {
// 				$post_id = $random_post[0]->ID;

// 				update_post_meta( $template_id, '_elementor_preview_settings_post', [
// 					'preview_id' => $post_id,
// 				] );

// 			} else {
// 				// $post_id = $template_id;
// 				return false; 
// 			}
// 		}

// 		return $post_id;
// 	}
// }


// if ( ! function_exists( 'solace_get_preview_post' ) ) {
// 	function solace_get_preview_post() {
// 		$template_id = get_the_ID();

// 		$preview_settings = get_post_meta( $template_id, '_elementor_preview_settings_post', true );

// 		if ( ! empty( $preview_settings['preview_id'] ) ) {
// 			$post_id = absint( $preview_settings['preview_id'] );
// 		} else {
// 			$random_post = get_posts( [
// 				'numberposts' => 1,
// 				'post_type'   => 'post',
// 				'post_status' => 'publish',
// 				'orderby'     => 'rand',
// 			] );

// 			if ( ! empty( $random_post ) ) {
// 				$post_id = $random_post[0]->ID;

// 				update_post_meta( $template_id, '_elementor_preview_settings_post', [
// 					'preview_id' => $post_id,
// 				] );

// 				update_post_meta( $template_id, '_elementor_preview_settings', [
// 					'preview_type' => 'post',
// 					'preview_id'   => $post_id,
// 				] );
// 			} else {
// 				$post_id = $template_id;
// 			}
// 		}

// 		return $post_id;
// 	}
// }

function solace_add_not_logged_in_body_class( $classes ) {
    if ( ! is_user_logged_in() ) {
        $classes[] = 'solace-not-logged-in';
    } else {
        $classes[] = 'solace-logged-in-user';
    }
    return $classes;
}
add_filter( 'body_class', 'solace_add_not_logged_in_body_class' );

add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {

    ob_start();
    ?>
    <span class="solace-cart-count">
        <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce cart count is safe ?>
        <?php echo WC()->cart->get_cart_contents_count(); ?>
    </span>
    <?php
    $fragments['span.solace-cart-count'] = ob_get_clean();

    ob_start();
    ?>
    <div class="solace-minicart-content">
        <?php
        if ( class_exists( 'Solace_Minicart_Widget_Class' ) ) {
            Solace_Minicart_Widget_Class::render_minicart_static();
        }
        ?>
    </div>
    <?php
    $fragments['div.solace-minicart-content'] = ob_get_clean();

    return $fragments;
});

add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {

    // BADGE COUNT
    ob_start();
    ?>
    <span class="solace-cart-count">
        <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce cart count is safe ?>
        <?php echo WC()->cart->get_cart_contents_count(); ?>
    </span>
    <?php
    $fragments['span.solace-cart-count'] = ob_get_clean();

    // SUBTOTAL
    ob_start();
    ?>
    <span class="solace-cart-subtotal">
        <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce subtotal HTML is safe ?>
        <?php echo WC()->cart->get_cart_subtotal(); ?>
    </span>
    <?php
    $fragments['span.solace-cart-subtotal'] = ob_get_clean();

    // MINICART CONTENT
    ob_start();
    ?>
    <div class="solace-minicart-content">
        <?php solace_render_minicart_html(); ?>
    </div>
    <?php
    $fragments['div.solace-minicart-content'] = ob_get_clean();

    return $fragments;
});



function solace_render_minicart_html( $settings = []) {
    if ( function_exists( 'wc_load_cart' ) && ! WC()->cart ) {
        wc_load_cart();
    }

    if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) {
        echo '<p class="woocommerce-mini-cart__empty-message">' . esc_html__( 'No products in the cart.', 'solace-extra' ) . '</p>';
        return;
    }

    $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
    $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';

    $cart_items = WC()->cart->get_cart();

    echo '<div class="elementor-menu-cart__products woocommerce-mini-cart cart woocommerce-cart-form__contents">';

    foreach ( $cart_items as $cart_item_key => $cart_item ) {
        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
            $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
            $solace_extra_thumbnail         = $_product->get_image( 'woocommerce_thumbnail' );
            $product_name      = $_product->get_name();
            $solace_extra_product_price     = WC()->cart->get_product_price( $_product );
            $remove_url        = wc_get_cart_remove_url( $cart_item_key );

            echo '<div class="elementor-menu-cart__product woocommerce-cart-form__cart-item cart_item">';

            echo '<div class="elementor-menu-cart__product-image product-thumbnail">';
            if ( $product_permalink ) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce product image HTML is safe
                echo '<a href="' . esc_url( $product_permalink ) . '">' . $solace_extra_thumbnail . '</a>';
            } else {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce product image HTML is safe
                echo $solace_extra_thumbnail;
            }
            echo '</div>';

            echo '<div class="elementor-menu-cart__product-name product-name" data-title="' . esc_attr__( 'Product', 'solace-extra' ) . '">';
            if ( $product_permalink ) {
                echo '<a href="' . esc_url( $product_permalink ) . '">' . esc_html( $product_name ) . '</a>';
            } else {
                echo esc_html( $product_name );
            }
            echo '</div>';

            echo '<div class="elementor-menu-cart__product-price product-price" data-title="' . esc_attr__( 'Price', 'solace-extra' ) . '">';
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce price HTML is safe
            echo '<span class="quantity"><span class="product-quantity">' . esc_html( $cart_item['quantity'] ) . ' ×</span> ' . $solace_extra_product_price . '</span>';

            $remove_style = 'display:flex;align-items:center;justify-content:center;width:20px;height:20px;line-height: 20px;border:1px solid #d5d8dc;border-radius:20px;';
            echo '<div class="elementor-menu-cart__product-remove product-remove" style="' . esc_attr( $remove_style ) . '">';
            
            $solace_extra_icon_html = '<i style="display: none;" class="fas fa-times solace-remove-icon"></i>';
            $solace_extra_icon_html .= '<svg style="width: 15px;fill: #69727d;transition: color .3s;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/></svg>';

            echo '<a style="display: flex; align-items: center; justify-content: center;" href="' . esc_url( $remove_url ) . '" 
                    class="elementor_remove_from_cart_button remove_from_cart_button" 
                    aria-label="' . esc_attr__( 'Remove this item', 'solace-extra' ) . '" 
                    data-product_id="' . esc_attr( $product_id ) . '" 
                    data-cart_item_key="' . esc_attr( $cart_item_key ) . '" 
                    data-product_sku="' . esc_attr( $_product->get_sku() ) . '">' 
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icon HTML is safe
                    . $solace_extra_icon_html .
                '</a>';

            echo '</div>'; // end remove
            echo '</div>'; // end price

            echo '</div>'; // end product row
        }
    }

    echo '</div>'; // end products container

    echo '<div class="elementor-menu-cart__subtotal">';
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce subtotal HTML is safe
    echo '<strong>' . esc_html__( 'Subtotal:', 'solace-extra' ) . '</strong> ' . WC()->cart->get_cart_subtotal();
    echo '</div>';

    echo '<div class="elementor-menu-cart__footer-buttons">';
    echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="solace-extra-button elementor-button elementor-button--view-cart '. esc_attr($hover_class) .'">' . esc_html__( 'View Cart', 'solace-extra' ) . '</a>';
    echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="solace-extra-button elementor-button elementor-button--checkout '. esc_attr($hover_class) .'">' . esc_html__( 'Checkout', 'solace-extra' ) . '</a>';
    echo '</div>';
}