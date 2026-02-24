<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'elementor/documents/register_controls', function( $document ) {
    $post_id = $document->get_main_id();
    $template_post_type = get_post_type( $post_id );
    // error_log('template_post_type:'.$template_post_type);

    $document_type_name = $document::get_type();
    $template_type = get_post_meta( $post_id, '_solace_template', true );
    // error_log('template_type:'.$template_type);

    $settings = $document->get_settings();
    $target_post_type = isset( $settings['post_type'] ) ? $settings['post_type'] : 'not set';

    if ( 'solace-sitebuilder' === $template_post_type && $template_type === '_solace_blogarchive_status' ) {
        // error_log('rico-_solace_blogarchive_status');
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
    } elseif ( 'solace-sitebuilder' === $template_post_type && $template_type === '_solace_blogsinglepost_status' ) {
        // error_log('rico-_solace_blogsinglepost_status');
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
    } elseif ( 'solace-sitebuilder' === $template_post_type && $template_type === '_solace_singleproduct_status' ) {
        // error_log('rico-_solace_singleproduct_status');
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
    }elseif ( 'solace-sitebuilder' === $template_post_type && $template_type === '_solace_shopproduct_status' ) {
        // error_log('rico-_solace_shopproduct_status');
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
    //     error_log('overview-all');
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


add_action('wp_ajax_solace_save_custom_preview_id', function() {
    if ( ! current_user_can('edit_posts') ) {
        wp_send_json_error('Unauthorized');
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $product_id         = isset($_POST['product_id']) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;

    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $previewproduct_id  = isset($_POST['previewproduct_id']) ? absint( wp_unslash( $_POST['previewproduct_id'] ) ) : 0;

    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $post_id            = isset($_POST['post_id']) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;

    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $previewpostId      = isset($_POST['previewpostId']) ? absint( wp_unslash( $_POST['previewpostId'] ) ) : 0;

    if ( ! $product_id || ! $post_id ) {
        wp_send_json_error('Invalid data');
    }

    // error_log('product_id:'.$product_id);
    // error_log('previewproduct_id:'.$previewproduct_id);

    // error_log('post_id:'.$post_id);
    // error_log('previewpostId:'.$previewpostId);

    delete_post_meta($product_id, '_elementor_preview_settings');
    delete_post_meta($post_id, '_elementor_preview_settings_post');

    update_post_meta($product_id, '_elementor_preview_settings', [
        'preview_id' => $previewproduct_id,
    ]);

    update_post_meta($post_id, '_elementor_preview_settings_post', [
        'preview_id' => $previewpostId,
    ]);


    wp_send_json_success('Preview ID saved.');
});

add_action('wp_ajax_solace_save_blogarchive_preview_category', function() {
    if ( ! current_user_can('edit_posts') ) {
        wp_send_json_error('Unauthorized');
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $post_id                = isset($_POST['post_id']) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;

    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $preview_blog_category  = isset($_POST['preview_blog_category']) ? absint( wp_unslash( $_POST['preview_blog_category'] ) ) : 0;

    if ( ! $post_id  ) {
        wp_send_json_error('Invalid data');
    }

    // error_log('preview_blog_category: ' . $preview_blog_category);
    
    if ( ! $preview_blog_category ) {
        // error_log('delete');
        delete_post_meta( $post_id, '_elementor_preview_settings_category' );
    }else {
        update_post_meta($post_id, '_elementor_preview_settings_category', [
            'preview_category' => $preview_blog_category,
        ]);
    }

    wp_send_json_success('Preview blog category saved.');
});

add_action('wp_ajax_solace_save_shopproduct_preview_category', function() {
    if ( ! current_user_can('edit_posts') ) {
        wp_send_json_error('Unauthorized');
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $post_id                = isset($_POST['post_id']) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;

    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $preview_shop_category  = isset($_POST['preview_shop_category']) ? absint( wp_unslash( $_POST['preview_shop_category'] ) ) : 0;

    if ( ! $post_id  ) {
        wp_send_json_error('Invalid data');
    }
    // error_log('preview_shop_category:'.$preview_shop_category);
    
    if ( ! $preview_shop_category ) {
        // error_log('delete');
        // delete_post_meta( $post_id, '_solace_template' );
        delete_post_meta( $post_id, '_elementor_preview_settings_category_shop' );
        
    } else {
        // error_log('update post_id:'.$post_id);
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
        '1.0',
        true
    );
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

if ( ! function_exists( 'solace_get_preview_product' ) ) {
    function solace_get_preview_product() {
        if ( function_exists( 'is_product' ) && is_product() ) {
            global $product;
            if ( $product && is_a( $product, 'WC_Product' ) ) {
                return $product;
            }
            return wc_get_product( get_the_ID() );
        }

        $template_id = get_the_ID();
        $preview_settings = get_post_meta( $template_id, '_elementor_preview_settings', true );

        if ( ! empty( $preview_settings['preview_id'] ) ) {
            $product_id = absint( $preview_settings['preview_id'] );
        } else {
            $products = wc_get_products( [
                'limit'   => 1,
                'status'  => 'publish',
                'orderby' => 'rand',
            ] );

            if ( ! empty( $products ) ) {
                $product_id = $products[0]->get_id();

                update_post_meta( $template_id, '_elementor_preview_settings', [
                    'preview_type' => 'product',
                    'preview_id'   => $product_id,
                ] );
            } else {
                return false;
            }
        }

        return wc_get_product( $product_id );
    }
}



// NORMAL
if ( ! function_exists( 'solace_get_preview_post' ) ) {
	function solace_get_preview_post() {
        // if ( ! defined( 'ELEMENTOR_VERSION' ) || ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {			
            $template_id = get_the_ID();

            $preview_settings = get_post_meta( $template_id, '_elementor_preview_settings_post', true );

            if ( ! empty( $preview_settings['preview_id'] ) ) {
                $post_id = absint( $preview_settings['preview_id'] );
            } else {
                $random_post = get_posts( [
                    'numberposts' => 1,
                    'post_type'   => 'post',
                    'post_status' => 'publish',
                    'orderby'     => 'rand',
                ] );

                if ( ! empty( $random_post ) ) {
                    $post_id = $random_post[0]->ID;

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
// 		error_log( '[solace_get_preview_post] Template ID: ' . $template_id );

// 		$preview_settings = get_post_meta( $template_id, '_elementor_preview_settings_post', true );
// 		error_log( '[solace_get_preview_post] Preview settings: ' . print_r( $preview_settings, true ) );

// 		if ( ! empty( $preview_settings['preview_id'] ) ) {
// 			$post_id = absint( $preview_settings['preview_id'] );
// 			error_log( '[solace_get_preview_post] Using preview_id: ' . $post_id );
// 		} else {
// 			$random_post = get_posts( [
// 				'numberposts' => 1,
// 				'post_type'   => 'post',
// 				'post_status' => 'publish',
// 				'orderby'     => 'rand',
// 			] );

// 			error_log( '[solace_get_preview_post] Random post query: ' . print_r( $random_post, true ) );

// 			if ( ! empty( $random_post ) ) {
// 				$post_id = $random_post[0]->ID;

// 				update_post_meta( $template_id, '_elementor_preview_settings_post', [
// 					'preview_id' => $post_id,
// 				] );

// 				error_log( '[solace_get_preview_post] Using random post ID: ' . $post_id );
// 			} else {
// 				// $post_id = $template_id;
// 				// error_log( '[solace_get_preview_post] No random post, fallback to template ID: ' . $post_id );
//                 error_log( '[solace_get_preview_post] No random post, return false.' );
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

function solace_render_minicart_html2() {

    if ( function_exists( 'wc_load_cart' ) && ! WC()->cart ) {
        wc_load_cart();
    }

    if ( ! WC()->cart || WC()->cart->is_empty() ) {
        echo '<p class="woocommerce-mini-cart__empty-message">No products in the cart.</p>';
        return;
    }

    echo '<div class="elementor-menu-cart__products woocommerce-mini-cart">';

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];

        if ( ! $product || ! $product->exists() ) {
            continue;
        }

        echo '<div class="elementor-menu-cart__product">';
        echo '<span class="name">' . esc_html( $product->get_name() ) . '</span>';
        echo '<span class="qty">' . esc_html( $cart_item['quantity'] ) . '</span>';
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce price HTML is safe
        echo '<span class="price">' . WC()->cart->get_product_price( $product ) . '</span>';
        echo '</div>';
    }

    echo '</div>';

    echo '<div class="elementor-menu-cart__subtotal">';
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce subtotal HTML is safe
    echo '<strong>Subtotal:</strong> ' . WC()->cart->get_cart_subtotal();
    echo '</div>';
}


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

            $remove_style = 'display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;line-height: 20px;border:1px solid #d5d8dc;border-radius:20px;';
            echo '<div class="elementor-menu-cart__product-remove product-remove" style="' . esc_attr( $remove_style ) . '">';
            
            $solace_extra_icon_html = '<i class="fas fa-times solace-remove-icon"></i>';

            echo '<a href="' . esc_url( $remove_url ) . '" 
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