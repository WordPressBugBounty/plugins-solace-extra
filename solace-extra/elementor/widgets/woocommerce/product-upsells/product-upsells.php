<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Solace_Extra_Product_Upsells extends Widget_Base {

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
            'section_content',
            [
                'label' => __('Settings', 'solace-extra'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading Text', 'solace-extra'),
                'type' => Controls_Manager::TEXT,
                'default' => __('You may also like…', 'solace-extra'),
            ]
        );

        $this->add_control(
            'products_per_page',
            [
                'label' => __('Products to Show', 'solace-extra'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 4,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'solace-extra'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                    '5' => '5 Columns',
                    '6' => '6 Columns',
                ],
            ]
        );


        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
    
        // Get current product context
        $product = solace_get_preview_product();
    
        $checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
			return;
		}
    
        // Enqueue WooCommerce default styles
        if (function_exists('WC')) {
            wp_enqueue_style('woocommerce-general');
            wp_enqueue_style('woocommerce-layout');
            wp_enqueue_style('woocommerce-smallscreen');
        }
    
        $product_id = $product->get_id();
        $heading = $settings['heading'];
        $limit = intval($settings['products_per_page']);
        $columns = intval($settings['columns']);
        $columns = ($columns >= 2 && $columns <= 6) ? $columns : 4;
    
        // Get upsell product IDs
        $upsell_ids = $product->get_upsell_ids();
    
        if (empty($upsell_ids)) {
            return;
        }
    
        $args = apply_filters('woocommerce_upsell_display_args', [
            'post_type'           => 'product',
            'ignore_sticky_posts' => 1,
            'no_found_rows'       => 1,
            'posts_per_page'      => $limit,
            'post__in'            => $upsell_ids,
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
            'post__not_in'        => [$product_id],
        ]);
    
        $query = new WP_Query($args);
    
        if ($query->have_posts()) {
    
            // Apply WooCommerce column filter for ul.products
            add_filter('loop_shop_columns', function() use ($columns) {
                return $columns;
            });
    
            echo '<section class="upsells products woocommerce">';
    
            if (!empty($heading)) {
                echo '<h2>' . esc_html($heading) . '</h2>';
            }
    
            woocommerce_product_loop_start();
    
            while ($query->have_posts()) {
                $query->the_post();
                wc_get_template_part('content', 'product');
            }
    
            woocommerce_product_loop_end();
    
            echo '</section>';
    
            // Clean up filter after rendering
            remove_all_filters('loop_shop_columns');
        }
    
        wp_reset_postdata();
    }

}
