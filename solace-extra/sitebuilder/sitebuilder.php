<?php
defined( 'ABSPATH' ) || exit;

/**
 * The sitebuilder functionality of the plugin.
 *
 * @link       https://solacewp.com
 * @since      1.0.0
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/public
 */

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, kept for backward compatibility.
function check_current_page_type() {
    $message = ''; 

    if (class_exists('WooCommerce')) {
        if (is_shop()) {
            $message = "Mendeteksi halaman shop.";
            return 'product|all';
        } elseif (is_product()) {
            $message = "Mendeteksi halaman produk.";
            return 'product|all';
        } elseif (is_cart()) {
            $message = "Mendeteksi halaman keranjang.";
            return 'product|all';
        } elseif (is_checkout()) {
            $message = "Mendeteksi halaman checkout.";
            return 'product|all';
        } elseif (is_account_page()) {
            $message = "Mendeteksi halaman akun.";
            return 'product|all';
        } elseif (is_product_category()) {
            $message = "Mendeteksi halaman kategori produk.";
            return 'product|all|taxarchive|product_cat';
        } elseif (is_product_tag()) {
            $message = "Mendeteksi halaman tag produk.";
            return 'product|all|taxarchive|product_tag';
        } elseif (is_search() && get_query_var('post_type') == 'product') {
            $message = "Mendeteksi halaman pencarian produk.";
            return 'product|all|archive';
        } elseif (is_order_received_page()) {
            $message = "Mendeteksi halaman penerimaan pesanan.";
            return 'product|all';
        }
    }

    if (is_front_page()) {
        $message = "Mendeteksi halaman depan.";
        return 'special-front';
    } elseif (is_home()) {
        $message = "Mendeteksi halaman blog.";
        return 'special-blog';
    } elseif (is_404()) {
        $message = "Mendeteksi halaman tidak ditemukan (404).";
        return 'special-404';
    } elseif (is_author()) {
        $message = "Mendeteksi halaman penulis.";
        return 'special-author';
    } elseif (is_search()) {
        $message = "Mendeteksi halaman pencarian umum.";
        return 'special-search';
    } elseif (is_date()) {
        $message = "Mendeteksi halaman arsip berdasarkan tanggal.";
        return 'special-date';
    }

    if (is_archive()) {
        if (is_category()) {
            $message = "Mendeteksi halaman arsip kategori.";
            return 'post|all|taxarchive|category';
        } elseif (is_tag()) {
            $message = "Mendeteksi halaman arsip tag.";
            return 'post|all|taxarchive|post_tag';
        } elseif (is_post_type_archive()) {
            $message = "Mendeteksi halaman arsip custom post type.";
            return 'post|all|archives'; 
        } elseif (is_tax()) {
            $message = "Mendeteksi halaman arsip taxonomy.";
            return 'basic-archives'; 
        } else {
            $message = "Mendeteksi halaman arsip umum.";
            return 'basic-archives'; 
        }
    }

    if (is_singular()) {
        if (is_page()) {
            $message = "Mendeteksi halaman singular (page).";
            return 'page|all'; 
        } elseif (is_attachment()) {
            $message = "Mendeteksi halaman lampiran.";
            return 'basic-archives'; 
        } elseif (is_single()) {
            $message = "Mendeteksi halaman single post.";
            return 'post|all'; 
        } else {
            $message = "Mendeteksi halaman singular umum.";
            return 'basic-singulars'; 
        }
    }

    // Cek fallback lainnya
    if (is_feed()) {
        $message = "Mendeteksi halaman feed.";
        return 'basic-archives';
    } elseif (is_trackback()) {
        $message = "Mendeteksi halaman trackback.";
        return 'basic-archives';
    } elseif (is_embed()) {
        $message = "Mendeteksi halaman embed.";
        return 'basic-archives';
    } elseif (is_privacy_policy()) {
        $message = "Mendeteksi halaman kebijakan privasi.";
        return 'basic-archives';
    }

    $message = "Mendeteksi halaman fallback.";
    return 'basic-archives';
}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, kept for backward compatibility.
function get_solace_header_conditions() {
    global $wpdb;

    // Prepare the SQL query to get posts that have the '_solace_footer_conditions' meta key with status 1
    $sql = "
        SELECT p.ID as post_id, 
            pm1.meta_value as header_conditions, 
            pm2.meta_value as header_status
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = %s
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = %s
        WHERE p.post_type = %s
        AND pm2.meta_value = %s
        ORDER BY p.ID DESC
    ";

    // Prepare the query using placeholders
    $meta_key1 = '_solace_header_conditions';
    $meta_key2 = '_solace_header_status';
    $post_type = 'solace-sitebuilder';
    $status_value = '1';

    
    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this.
    $sql = $wpdb->prepare($sql, $meta_key1, $meta_key2, $post_type, $status_value);

    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this.
    $results = $wpdb->get_results($sql);

    // Process the results to unserialize conditions
    $final_results = [];
    if ($results) {
        foreach ($results as $row) {
            $final_results[] = [
                'post_id'       => $row->post_id,
                'header_status' => $row->header_status,
                'conditions'    => maybe_unserialize($row->header_conditions)
            ];
        }
    }

    return $final_results;

}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, kept for backward compatibility.
function get_solace_footer_conditions() {
    global $wpdb;

    // Prepare the SQL query to get posts that have the '_solace_footer_conditions' meta key with status 1
    $sql = "
        SELECT p.ID as post_id, 
            pm1.meta_value as footer_conditions, 
            pm2.meta_value as footer_status
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = %s
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = %s
        WHERE p.post_type = %s
        AND pm2.meta_value = %s
        ORDER BY p.ID DESC
    ";

    // Prepare the query using placeholders
    $meta_key1 = '_solace_footer_conditions';
    $meta_key2 = '_solace_footer_status';
    $post_type = 'solace-sitebuilder';
    $status_value = '1';

    
    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this.
    $sql = $wpdb->prepare($sql, $meta_key1, $meta_key2, $post_type, $status_value);

    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this.
    $results = $wpdb->get_results($sql);

    // Process the results to unserialize conditions
    $final_results = [];
    if ($results) {
        foreach ($results as $row) {
            $final_results[] = [
                'post_id'       => $row->post_id,
                'footer_status' => $row->footer_status,
                'conditions'    => maybe_unserialize($row->footer_conditions)
            ];
        }
    }
    // error_log('Debug: Kondisi footer didapatkan: ' . print_r($final_results, true));
    return $final_results;

}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, kept for backward compatibility.
function get_solace_single_product_conditions() {
    global $wpdb;

    // Prepare the SQL query to get posts that have the '_solace_footer_conditions' meta key with status 1
    $sql = "
        SELECT p.ID as post_id, 
            pm1.meta_value as header_conditions, 
            pm2.meta_value as header_status
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = %s
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = %s
        WHERE p.post_type = %s
        AND pm2.meta_value = %s
    ";

    // Prepare the query using placeholders
    $meta_key1 = '_solace_single_product_conditions';
    $meta_key2 = '_solace_single_product_status';
    $post_type = 'solace-sitebuilder';
    $status_value = '1';

    
    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this.
    $sql = $wpdb->prepare($sql, $meta_key1, $meta_key2, $post_type, $status_value);

    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this.
    $results = $wpdb->get_results($sql);

    // Process the results to unserialize conditions
    $final_results = [];
    if ($results) {
        foreach ($results as $row) {
            $final_results[] = [
                'post_id'       => $row->post_id,
                'single_product_status' => $row->single_product_status,
                'conditions'    => maybe_unserialize($row->single_product_conditions)
            ];
        }
    }

    return $final_results;

}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, kept for backward compatibility.
function get_solace_footer_conditionsx() {
    global $wpdb;

    // Prepare the SQL query to get posts that have the '_solace_footer_conditions' meta key with status 1
    $sql = "
        SELECT p.ID as post_id, 
               pm1.meta_value as footer_conditions, 
               pm2.meta_value as footer_status
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = %s
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = %s
        WHERE p.post_type = %s
        AND pm2.meta_value = %s
    ";

    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- We need this.
    $prepared_sql = $wpdb->prepare($sql,
        '_solace_footer_conditions', // for pm1.meta_key
        '_solace_footer_status',     // for pm2.meta_key
        'solace-sitebuilder',        // for post_type
        '1'                          // for pm2.meta_value
    );

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this.
    $results = $wpdb->get_results($prepared_sql);

    // Process the results to unserialize conditions
    $final_results = [];
    if ($results) {
        foreach ($results as $row) {
            $final_results[] = [
                'post_id'       => $row->post_id,
                'footer_status' => $row->footer_status,
                'conditions'    => maybe_unserialize($row->footer_conditions)
            ];
        }
    }

    return $final_results;
}


function solace_is_elementor_editor() {
    if (class_exists('\Elementor\Plugin')) {
        return \Elementor\Plugin::$instance->editor->is_edit_mode();
    }
    return false;
}

function solace_display_custom_header() {
    if (did_action('elementor/loaded')) {
        \Elementor\Plugin::$instance->frontend->enqueue_styles();
    }
    $page_type = check_current_page_type();
    // error_log('halaman terdeteksi: '.$page_type);

    $solace_conditions = get_solace_header_conditions();

    $post_id = null;

    foreach ( $solace_conditions as $item ) {
        if (
            isset( $item['footer_status'], $item['post_id'] ) &&
            $item['footer_status'] == 1
        ) {
            $post_id = $item['post_id'];
            break;
        }
    }
    echo "<div style='display: none !important;' class='solace-custom-header-footer solace-custom-header' post-id='" . absint( $post_id ) ."'></div>";    

    $valid_archive_types = [
        'post|all|taxarchive|post_tag',
        'post|all|taxarchive|category',
        'post|all|archives',
        'basic-archives',
        'special-author',
        'special-date',
        'special-search',
        'author' 
    ];

    $valid_singular_types = [
        'post',
        'attachment',
        'page',
        'basic-singulars',
        'page|all',
        'post|all',
        'author' 
    ];
    $is_elementor_preview = solace_is_elementor_editor();

    $content_displayed = false;

    foreach ($solace_conditions as $condition) {
        
        $exclude = false;

        if (!empty($condition['conditions'])) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'exclude' && $include_condition['value'] === $page_type) {
                    $exclude = true;
                    break;
                }
            }

            if (!$exclude) {
                foreach ($condition['conditions'] as $include_condition) {
                    if ($include_condition['type'] === 'include' && $include_condition['value'] === 'basic-global') {
                        $matched_post_id = $condition['post_id'];
                        if (get_post_status($matched_post_id) !== 'publish') {
                            continue; // Skip if post is not published
                        }
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {   
                                echo '<div class="delayed-content">';
                                $elementor_instance = \Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display($matched_post_id) );
                                echo '</div>';
                            } else {
                                $output = '<div class="custom-header-content basic-global">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output ); 
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    }
                }
            } else {
            }
        } else {
        }
    }

    // Special Case: Page|ALL Conditions

    foreach ($solace_conditions as $condition) {
        
        $exclude = false;

        if (!empty($condition['conditions'])) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'exclude' && $include_condition['value'] === $page_type) {
                    $exclude = true;
                    break;
                }
            }

            if (!$exclude) {
                foreach ($condition['conditions'] as $include_condition) {
                    if ($include_condition['type'] === 'include' && $include_condition['value'] === 'page|all') {
                        if ($page_type === 'special-front') {
                            if (is_home()) { 
                                continue;
                            }
                        }
                        
                        if (in_array($page_type, [ 'basic-singular', 'basic-archive', 'special-author', 'post|all|taxarchive|category', 'post|all|taxarchive|post_tag', 'post|all', 'special-date', 'special-search','special-404'])) {
                            continue;
                        }
                        
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                $output = '<div class="custom-header-content page-all">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    }
                }
            } else {
            }
        } else {
        }
    }

   

    foreach ($solace_conditions as $condition) {

        $exclude = false;

        if (!empty($condition['conditions'])) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'exclude' && $include_condition['value'] === $page_type) {
                    $exclude = true;
                    break;
                }
            }

            if (!$exclude) {
                foreach ($condition['conditions'] as $include_condition) {
                    if ($include_condition['type'] === 'include' && $include_condition['value'] === 'basic-archives') {

                        if (in_array($page_type, $valid_archive_types)) {
                            $matched_post_id = $condition['post_id'];
                            $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                            if ( !$is_elementor_preview ) {
                                if (!empty($meta_content)) {
                                    $elementor_instance = Elementor\Plugin::instance();
                                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                                } else {
                                    // Fallback to post content if Elementor data is not available
                                    $output = '<div class="custom-header-content basic-archives">';
                                    $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                    $output .= '</div>';
                                    echo wp_kses_post( $output );
                                }  
                                $content_displayed = true; 
                                break; 
                            }
                        } else {
                        }
                    }
                }
            } else {
            }
        } else {
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'post|all') {

                    if ($page_type === 'post|all') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-header-content post-all">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-front') {

                    if ($page_type === 'special-front') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-header-content special-front">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-date') {

                    if ($page_type === 'special-date') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-header-content special-date">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-blog') {

                    if ($page_type === 'special-blog') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-header-content special-blog">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-search') {

                    if ($page_type === 'special-search') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-header-content special-search">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-404') {

                    if ($page_type === 'special-404') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-header-content special-404">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'post|all|taxarchive|post_tag') {

                    if ($page_type === 'post|all|taxarchive|post_tag') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-header-content post-tag">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-author') {

                    if ($page_type === 'special-author') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-header-content special-author">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'post|all|taxarchive|category') {

                    if ($page_type === 'post|all|taxarchive|category') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-header-content post-category">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'basic-singulars') {

                    if (in_array($page_type, $valid_singular_types)) {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-header-content basic-singulars">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                            break; 
                        }   
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'product|all') {
                    
                    if ($page_type === 'product|all') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content product-all">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        } 
        
        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'product|all|taxarchive|product_cat') {
                    if ($page_type === 'product|all|taxarchive|product_cat') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content product-category">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        } 

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'product|all|taxarchive|product_tag') {
                    if ($page_type === 'product|all|taxarchive|product_tag') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content product-all">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }
    }

    // Return true if content was displayed, false otherwise
    return $content_displayed;
}


/**
 * Check if a post contains an icon-list widget in its Elementor data
 *
 * @param int $post_id The post ID to check
 * @return bool True if icon-list widget is found, false otherwise
 */
function solace_post_has_icon_list_widget( $post_id ) {
    if ( ! $post_id ) {
        return false;
    }

    $elementor_data = get_post_meta( $post_id, '_elementor_data', true );
    
    if ( empty( $elementor_data ) ) {
        return false;
    }

    $elements = json_decode( $elementor_data, true );
    
    if ( empty( $elements ) || ! is_array( $elements ) ) {
        return false;
    }

    $has_icon_list = false;

    /**
     * Recursive function to search for icon-list widget in Elementor data
     * 
     * @param array $elements Array of Elementor elements to search through
     */
    $check_widgets = function( $elements ) use ( &$check_widgets, &$has_icon_list ) {
        foreach ( $elements as $element ) {
            // Check if element has a widget type
            if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'icon-list' ) {
                $has_icon_list = true;
                return; // Early return if found
            }
            
            // Recursively check nested elements
            if ( ! empty( $element['elements'] ) ) {
                $check_widgets( $element['elements'] );
            }
        }
    };

    $check_widgets( $elements );

    return $has_icon_list;
}

/**
 * Enqueue icon-list widget CSS if post has the widget
 *
 * @param int $post_id The post ID to check
 */
function solace_enqueue_icon_list_css_if_needed( $post_id ) {
    static $css_enqueued = false;
    
    // Only enqueue once per page load
    if ( $css_enqueued ) {
        return;
    }
    
    if ( $post_id && solace_post_has_icon_list_widget( $post_id ) ) {
        wp_enqueue_style( 
            'solace-extra-widget-icon-list', 
            SOLACE_EXTRA_ASSETS_URL . 'css/widget-icon-list.min.css', 
            array(), 
            SOLACE_EXTRA_VERSION, 
            'all' 
        );
        $css_enqueued = true;
    }
}

function solace_display_custom_footer() {
    if (did_action('elementor/loaded')) {
        \Elementor\Plugin::$instance->frontend->enqueue_styles();
    }
    $page_type = check_current_page_type();

    $solace_conditions = get_solace_footer_conditions();

    $post_id = null;

    foreach ( $solace_conditions as $item ) {
        if (
            isset( $item['footer_status'], $item['post_id'] ) &&
            $item['footer_status'] == 1
        ) {
            $post_id = $item['post_id'];
            break;
        }
    }
    echo "<div style='display: none !important;' class='solace-custom-header-footer solace-custom-footer' post-id='" . absint( $post_id ) ."'></div>";
    
    // Check if post has icon-list widget and enqueue CSS
    solace_enqueue_icon_list_css_if_needed( $post_id );
    
    $valid_archive_types = [
        'post|all|taxarchive|post_tag',
        'post|all|taxarchive|category',
        'post|all|archives',
        'basic-archives',
        'special-author',
        'special-date',
        'special-search',
        'author' 
    ];

    $valid_singular_types = [
        'post',
        'attachment',
        'page',
        'basic-singulars',
        'page|all',
        'post|all',
        'author' 
    ];
    $is_elementor_preview = solace_is_elementor_editor();
    $content_displayed = false;

    foreach ($solace_conditions as $condition) {
        
        $exclude = false;

        if (!empty($condition['conditions'])) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'exclude' && $include_condition['value'] === $page_type) {
                    $exclude = true;
                    break;
                }
            }

            if (!$exclude) {
                foreach ($condition['conditions'] as $include_condition) {
                    if ($include_condition['type'] === 'include' && $include_condition['value'] === 'basic-global') {
                        $matched_post_id = $condition['post_id'];
                        if (get_post_status($matched_post_id) !== 'publish') {
                            continue; // Skip if post is not published
                        }
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                echo '<div class="delayed-content">';
                                $elementor_instance = Elementor\Plugin::instance();
			                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                                echo '</div>';
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content basic-global">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }  
                            $content_displayed = true; 
                        }
                    }
                }
            } else {
            }
        } else {
        }
    }

    foreach ($solace_conditions as $condition) {

        $exclude = false;

        if (!empty($condition['conditions'])) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'exclude' && $include_condition['value'] === $page_type) {
                    $exclude = true;
                    break;
                }
            }

            if (!$exclude) {
                foreach ($condition['conditions'] as $include_condition) {
                    if ($include_condition['type'] === 'include' && $include_condition['value'] === 'basic-archives') {

                        if (in_array($page_type, $valid_archive_types)) {
                            $matched_post_id = $condition['post_id'];
                            $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                            if ( !$is_elementor_preview ) {
                                if (!empty($meta_content)) {
                                    $elementor_instance = Elementor\Plugin::instance();
                                    echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                                } else {
                                    // Fallback to post content if Elementor data is not available
                                    $output = '<div class="custom-footer-content basic-archives">';
                                    $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                    $output .= '</div>';
                                    echo wp_kses_post( $output );
                                }
                                $content_displayed = true; 
                                break; 
                            }
                        } else {
                        }
                    }
                }
            } else {
            }
        } else {
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'page|all') {

                    if ($page_type === 'page|all') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content page-all">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'post|all') {

                    if ($page_type === 'post|all') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content post-all">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-front') {

                    if ($page_type === 'special-front') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content special-front">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-date') {

                    if ($page_type === 'special-date') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-foter-content special-date">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true;
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-blog') {

                    if ($page_type === 'special-blog') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content special-blog">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-search') {

                    if ($page_type === 'special-search') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content special-search">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-404') {

                    if ($page_type === 'special-404') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content special-404">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'post|all|taxarchive|post_tag') {

                    if ($page_type === 'post|all|taxarchive|post_tag') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content post-tag">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'special-author') {

                    if ($page_type === 'special-author') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content special-author">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'post|all|taxarchive|category') {

                    if ($page_type === 'post|all|taxarchive|category') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content post-category">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'basic-singulars') {
                    // error_log('Kondisi include basic-singular ditemukan untuk post ID: ' . $condition['post_id']);

                    if (in_array($page_type, $valid_singular_types)) {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content basic-singulars">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'product|all') {
                    
                    if ($page_type === 'product|all') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content product-all">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        } 
        
        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'product|all|taxarchive|product_cat') {
                    if ($page_type === 'product|all|taxarchive|product_cat') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content product-category">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        } 

        if (!$exclude) {
            foreach ($condition['conditions'] as $include_condition) {
                if ($include_condition['type'] === 'include' && $include_condition['value'] === 'product|all|taxarchive|product_tag') {
                    if ($page_type === 'product|all|taxarchive|product_tag') {
                        $matched_post_id = $condition['post_id'];
                        $meta_content = get_post_meta($matched_post_id, '_elementor_data', true);
                        if ( !$is_elementor_preview ) {
                            if (!empty($meta_content)) {
                                $elementor_instance = Elementor\Plugin::instance();
                                echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $matched_post_id ) );
                            } else {
                                // Fallback to post content if Elementor data is not available
                                $output = '<div class="custom-footer-content product-all">';
                                $output .= apply_filters('the_content', get_post_field('post_content', $matched_post_id)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
                                $output .= '</div>';
                                echo wp_kses_post( $output );
                            }
                            $content_displayed = true; 
                            break; 
                        }
                    } else {
                    }
                }
            }
        }
    }

    return $content_displayed;
}



function solace_rename_post_title() {
    if (!isset($_POST['security']) || !wp_verify_nonce(sanitize_key(wp_unslash($_POST['security'])), 'solace_conditions_nonce_action')) {
        wp_send_json_error('Invalid nonce9.');
        return;
    }

    if (!isset($_POST['post_id']) || !current_user_can('edit_post', intval($_POST['post_id']))) {
        wp_send_json_error('User does not have permission to edit this post.');
        return;
    }

    // Proses update post title
    $post_id = intval($_POST['post_id']);
    // $new_title = sanitize_text_field(wp_unslash($_POST['new_title']));
    if ( isset($_POST['new_title']) ) {
        $new_title = sanitize_text_field(wp_unslash($_POST['new_title']));
    } else {
        wp_send_json_error('Title is missing.');
        return;
    }

    $updated_post = array('ID' => $post_id, 'post_title' => $new_title,);

    $result = wp_update_post($updated_post, true);

    if (is_wp_error($result)) {
        wp_send_json_error('Failed to update the post.');
    } else {
        wp_send_json_success('Post title updated successfully.');
    }
}
add_action('wp_ajax_rename_post_title', 'solace_rename_post_title');



/**
* Hook into the save_post action to set taxonomy terms based on URL parameter
*
* @param int $post_id The ID of the post being saved.
*/
function solace_set_taxonomy_based_on_part($post_id) {
    if (isset($_GET['security']) && wp_verify_nonce(sanitize_key(wp_unslash($_GET['security'])), 'template_preview_nonce')) {
        wp_send_json_error('Nonce verification failed.');
    }
    // Check if this is an autosave or a revision
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the post type
    if (get_post_type($post_id) !== 'solace-sitebuilder') {
        return;
    }

    // Check if 'part' parameter is set
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (isset($_GET['part'])) {
        $part = sanitize_text_field(wp_unslash($_GET['part']));
        $taxonomy = 'sitebuilder_part'; // The taxonomy you are using

        // Check if the term exists, if not create it
        if (!term_exists($part, $taxonomy)) {
            wp_insert_term($part, $taxonomy);
        }

        // Set the taxonomy term for the post
        wp_set_post_terms($post_id, array($part), $taxonomy, false);
    }
}
add_action('save_post', 'solace_set_taxonomy_based_on_part');

function solace_update_sitebuilder_status() {
    // Verifikasi nonce
    if (isset($_GET['security']) && wp_verify_nonce(sanitize_key(wp_unslash($_GET['security'])), 'template_preview_nonce')) {
        wp_send_json_error('Nonce verification failed.');
    }

    // Check if the required parameters are present.
    if (!isset($_POST['post_id']) || !isset($_POST['status']) || !isset($_POST['part'])) {
        wp_send_json_error('Invalid data sent.');
    }

    $post_id = intval($_POST['post_id']);
    $part = sanitize_text_field(wp_unslash($_POST['part']) );
    $status = sanitize_text_field(wp_unslash($_POST['status']) );

    // Updated status sitebuilder part.
    if ( $part ) {

        // Set up the arguments for the custom query.
        $args = array(
            'post_type'      => 'solace-sitebuilder',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
            'meta_key'       => '_solace_'.$part .'_status',
            'posts_per_page' => -1,
        );

        // Create a new custom query.
        $query = new WP_Query( $args );

        // Check if there are any posts found.
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                // Updated the value of the custom meta key.
                update_post_meta( get_the_ID(), '_solace_'.$part.'_status', false );
            }
        }

        // Reset post data after custom query to avoid interfering with other queries.
        wp_reset_postdata();    

    }

    // Update the post meta.
    if (update_post_meta($post_id, '_solace_'.$part.'_status', $status)) {
        wp_send_json_success('Status updated.');
    } else {
        wp_send_json_error('Failed to update status.');
    }
}
add_action('wp_ajax_solace_update_sitebuilder_status', 'solace_update_sitebuilder_status');



function solace_update_sitebuilder_all_status() {
    // Verifikasi nonce
    if (isset($_GET['security']) && wp_verify_nonce(sanitize_key(wp_unslash($_GET['security'])), 'template_preview_nonce')) {
        wp_send_json_error('Nonce verification failed.');
    }

    // Check if the required parameters are present.
    if ( !isset($_POST['status']) || !isset($_POST['part'])) {
        wp_send_json_error('Invalid data sent.');
    }

    $part = sanitize_text_field(wp_unslash($_POST['part']));
    $status = (string) sanitize_text_field(wp_unslash($_POST['status']));

    $meta_key = '_solace_' . $part . '_status';

    $posts = get_posts([
        'post_type'      => 'solace-sitebuilder',
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'meta_key'       => '_solace_' . $part . '_status',
        'posts_per_page' => -1,
        'fields'         => 'ids'
    ]);

    if ( ! $status ) {
        foreach ($posts as $post_id) {
            update_post_meta($post_id, $meta_key, false);
        }
        wp_send_json_success('Status updated to 0 for all posts.');
    } elseif ($status === '1') {
        foreach ($posts as $post_id) {
            update_post_meta($post_id, $meta_key, true);
            break;
        }
        wp_send_json_success('Status updated to 1 for all posts.');
    } else {
        wp_send_json_error('Invalid status value. Only 0 or 1 is allowed.');
    }
}


function solace_update_sitebuilder_all_status2() {
    // Verifikasi nonce
    if (isset($_GET['security']) && wp_verify_nonce(sanitize_key(wp_unslash($_GET['security'])), 'template_preview_nonce')) {
        wp_send_json_error('Nonce verification failed.');
    }

    // Check if the required parameters are present.
    if ( !isset($_POST['status']) || !isset($_POST['part'])) {
        wp_send_json_error('Invalid data sent.');
    }
    

    $part = sanitize_text_field(wp_unslash($_POST['part']) );
    $status = (string) sanitize_text_field(wp_unslash($_POST['status']));

    // error_log('part:'.$part);
    // error_log('status:'.$status);

    $meta_key = '_solace_' . $part . '_status';

    $posts = get_posts([
        'post_type'      => 'solace-sitebuilder',
        'posts_per_page' => -1,
        'fields'         => 'ids'
    ]);

    if ($status === '0') {
        foreach ($posts as $post_id) {
            $current_value = get_post_meta($post_id, $meta_key, true);

            if ($current_value !== '0') {
                update_post_meta($post_id, $meta_key, '0');
                // error_log("Updated post ID $post_id to 0");
            } else {
                // error_log("Post ID $post_id already has value 0 or meta key does not exist.");
            }
        }

        wp_send_json_success('Status updated to 0 for all posts.');
    } elseif ($status === '1') {
        foreach ($posts as $post_id) {
            $current_value = get_post_meta($post_id, $meta_key, true);

            if ($current_value !== '1') {
                update_post_meta($post_id, $meta_key, '1');
                // error_log("Updated post ID $post_id to 1");
            } else {
                // error_log("Post ID $post_id already has value 1.");
            }
        }

        wp_send_json_success('Status updated to 1 for all posts.');
    } else {
        wp_send_json_error('Invalid status value. Only 0 or 1 is allowed.');
    }
}
add_action('wp_ajax_solace_update_sitebuilder_all_status', 'solace_update_sitebuilder_all_status');


// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, kept for backward compatibility.
function load_template_preview() {
    
    if (isset($_GET['preview']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['preview']))) === 'true') {
        // Optional: Check the nonce to verify it's a valid request
        if (isset($_GET['security']) && wp_verify_nonce(sanitize_key(wp_unslash($_GET['security'])), 'template_preview_nonce')) {
            
            // Nonce is valid, proceed to include the template
            include get_template_directory() . '/template-preview.php';
            exit;
        } else {
            wp_die('Nonce verification failed. You are not authorized to view this preview.');
        }
    }
}
add_action('template_redirect', 'load_template_preview');

add_action('init', function() {
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (isset($_GET['post_id']) && is_numeric($_GET['post_id']) && isset($_GET['security'])) {
        // Verify the nonce
        if (!wp_verify_nonce(sanitize_key(wp_unslash($_GET['security'])), 'solace_preview_nonce_action')) {
            wp_die('Security check failed. Invalid nonce10.');
        }        // Sanitize post_id
        $post_id = intval($_GET['post_id']);  // Casting to integer ensures the value is safe

        // Optional: Validate that the post ID exists and is valid in the database
        if (get_post($post_id)) {
            // Ensure that the requested post exists
            include get_template_directory() . '/template-preview.php';
            exit;
        } else {
            // Optionally handle invalid post_id or redirect
            wp_die('Invalid post ID or post not found.');
        }
    }
});



function solace_sitebuilder_add_meta_boxes() {
add_meta_box(
    'solace_sitebuilder_conditions_meta_box', 
    esc_html__('SiteBuilder Conditions', 'solace-extra'), 
    'solace_sitebuilder_conditions_meta_box_callback', 
    'solace-sitebuilder', 
    'normal', 
    'default' 
);
}
// add_action('add_meta_boxes', 'solace_sitebuilder_add_meta_boxes');

function solace_sitebuilder_add_custom_columns($columns) {
    $columns['solace_conditions'] = esc_html__('Conditions', 'solace-extra');
    $columns['solace_header_status'] = esc_html__('Status', 'solace-extra');
    return $columns;
}
add_filter('manage_solace-sitebuilder_posts_columns', 'solace_sitebuilder_add_custom_columns');

function solace_sitebuilder_custom_column_content($column, $post_id) {
    if ($column === 'solace_conditions') {

        $conditions = get_post_meta($post_id, '_solace_header_conditions', true);

        if (!empty($conditions) && is_string($conditions)) {
            $conditions = maybe_unserialize($conditions);
        }

        if (!empty($conditions)) {
            foreach($conditions as $condition) {
                echo '<strong>'
                    .esc_html($condition['type'])
                    .':</strong> '
                    .esc_html($condition['value'])
                    .'<br>';
            }
        } else {
            esc_html_e('No conditions', 'solace-extra');
        }

    }
    elseif($column === 'solace_header_status') {

        $header_status = get_post_meta($post_id, '_solace_header_status', true);
        echo $header_status ? esc_html__( 'Enabled', 'solace-extra' ) : esc_html__( 'Disabled', 'solace-extra' );
    }
}
add_action('manage_solace-sitebuilder_posts_custom_column', 'solace_sitebuilder_custom_column_content', 10, 2);



add_action('save_post', 'solace_sitebuilder_save_meta_box_data');

function solace_sitebuilder_save_meta_box_data($post_id) {


    // Check if current user has permissions to save data
    if (!current_user_can('edit_post', $post_id)) {
        // error_log( 'User does not have permission to edit post');
        return;
    }

    // Check if it's an autosave and return if true
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        // error_log( 'Autosave detected');
        return;
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- We need this. 
    if ( ! isset( $_POST['solace_sitebuilder_conditions'] ) || ! is_array( $_POST['solace_sitebuilder_conditions'] ) ) {
        // error_log( 'No data submitted');
        return;
    }
    // phpcs:ignore WordPress.Security.NonceVerification.Missing --- We need this.
    $include_conditions = isset($_POST['solace_sitebuilder_conditions']['include']) ? array_map('sanitize_text_field', sanitize_text_field(wp_unslash($_POST['solace_sitebuilder_conditions']['include']))) : array();

    // phpcs:ignore WordPress.Security.NonceVerification.Missing --- We need this.
    $exclude_conditions = isset($_POST['solace_sitebuilder_conditions']['exclude']) ? array_map('sanitize_text_field', sanitize_text_field(wp_unslash($_POST['solace_sitebuilder_conditions']['exclude']))) : array();

    $data = array();

    foreach ($include_conditions as $include_value) {
        if (!empty($include_value)) {
            $data[] = array('type' => 'include', 'value' => sanitize_text_field($include_value));
        }
    }

    foreach ($exclude_conditions as $exclude_value) {
        if (!empty($exclude_value)) {
            $data[] = array('type' => 'exclude', 'value' => sanitize_text_field($exclude_value));
        }
    }

    // Update the post meta with the processed data
    update_post_meta($post_id,  '_solace_header_conditions', $data);
}


function solace_sitebuilder_conditions_meta_box_callback($post) {

    $conditions = get_post_meta($post -> ID, '_solace_header_conditions', true);
    $header_status = get_post_meta($post -> ID, '_solace_header_status', true);
    if ($header_status == '1') {
        $parts = 'header';
    } else {
        $parts = 'footer';
    }
    // $current_url = $_SERVER['REQUEST_URI'];
    if (isset($_SERVER['REQUEST_URI'])) {
        $current_url = sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']));
    } else {
        $current_url = ''; 
    }

    $includes = array();
    $excludes = array();

    if (!empty($conditions) && is_array($conditions)) {
        // Filter conditions into includes and excludes
        foreach($conditions as $condition) {
            if ($condition['type'] === 'include') {
                $includes[] = $condition['value'];
            }
            elseif($condition['type'] === 'exclude') {
                $excludes[] = $condition['value'];
            }
        }
    }
    
    $parts_options = array(
        'select' => esc_html__('Select', 'solace-extra'),
        'header' => esc_html__('Header', 'solace-extra'),
        'footer' => esc_html__('Footer', 'solace-extra')
    );
    if (function_exists('solace_pro_part_404_option')) {
        $parts_options = array_merge($parts_options, solace_pro_part_404_option());
    }
    
    echo '<table class="form-table solace-conditions-table">';
    echo '<tr>';
    echo '<td><label for="solace_parts">'
        .esc_html__('Parts', 'solace-extra')
        .'</label></td>';
    echo '<td colspan="2">';
    echo '<select id="solace_parts" name="solace_sitebuilder_conditions[parts]">';
    foreach($parts_options as $value => $label) {
        $selected = ($parts === $value)
            ? 'selected'
            : '';
        echo '<option value="'
            .esc_attr($value)
            .'" '
            .esc_attr($selected)
            .'>'
            .esc_html($label)
            .'</option>';
    }
    echo '</select>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><label>'
        .esc_html__('Include', 'solace-extra')
        .'</label></td>';
    echo '<td>';
    echo '<div id="include-conditions-container">';
    if (!empty($includes)) {
        foreach($includes as $include_value) {
            echo '<div class="condition-row">';
            echo '<select name="solace_sitebuilder_conditions[include][]">';
            echo wp_kses_post( solace_generate_options_html($include_value) );
            echo '</select>';
            echo '</div>';
        }
    } else {
        echo '<div class="condition-row">';
        echo '<select name="solace_sitebuilder_conditions[include][]">';
        echo wp_kses_post( solace_generate_options_html() );
        echo '</select>';
        echo '</div>';
    }
    echo '</div>';
    echo '</td>';
    echo '<td><button type="button" id="add-include-condition" class="button">'
        .esc_html__(
            'Add Condition',
            'solace-extra'
        )
        .'</button></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><label>'
        .esc_html__('Exclude', 'solace-extra')
        .'</label></td>';
    echo '<td>';
    echo '<div id="exclude-conditions-container">';
    if (!empty($excludes)) {
        foreach($excludes as $exclude_value) {
            echo '<div class="condition-row">';
            echo '<select name="solace_sitebuilder_conditions[exclude][]">';
            echo wp_kses_post( solace_generate_options_html($exclude_value) );
            echo '</select>';
            echo '</div>';
        }
    } else {
        echo '<div class="condition-row">';
        echo '<select name="solace_sitebuilder_conditions[exclude][]">';
        echo wp_kses_post( solace_generate_options_html() ); 
        echo '</select>';
        echo '</div>';
    }
    echo '</div>';
    echo '</td>';
    echo '<td><button type="button" id="add-exclude-condition" class="button">'
        .esc_html__(
            'Add Condition',
            'solace-extra'
        )
        .'</button></td>';
    echo '</tr>';

    echo '</table>';
    ?> <script type = "text/javascript" > jQuery(document).ready(function ($) {
        $('#add-include-condition').on('click', function () {
            var newCondition = $('#include-conditions-container .condition-row')
                .first()
                .clone();
            newCondition
                .find('select')
                .val(''); 
            var newRow = $('<div class="condition-row"></div>');
            newRow.append(newCondition.html()); 
            $('#include-conditions-container').append(newRow); 
        });

        $('#add-exclude-condition').on('click', function () {
            var newCondition = $('#exclude-conditions-container .condition-row')
                .first()
                .clone();
            newCondition
                .find('select')
                .val(''); 
            var newRow = $('<div class="condition-row"></div>');
            newRow.append(newCondition.html()); 
            $('#exclude-conditions-container').append(newRow); 
        });
    });
    </script><?php
}


function solace_generate_options_html($selected_value = '') {
    $options = '
        <option value="">' . esc_html__('Select', 'solace-extra') . '</option>
        <optgroup label="Basic">
            <option value="basic-global"' . selected($selected_value, 'basic-global', false) . '>Entire Website</option>
            <option value="basic-singulars"' . selected($selected_value, 'basic-singulars', false) . '>All Singulars</option>
            <option value="basic-archives"' . selected($selected_value, 'basic-archives', false) . '>All Archives</option>
        </optgroup>
        <optgroup label="Special Pages">
            <option value="special-404"' . selected($selected_value, 'special-404', false) . '>404 Page</option>
            <option value="special-search"' . selected($selected_value, 'special-search', false) . '>Search Page</option>
            <option value="special-blog"' . selected($selected_value, 'special-blog', false) . '>Blog / Posts Page</option>
            <option value="special-front"' . selected($selected_value, 'special-front', false) . '>Front Page</option>
            <option value="special-date"' . selected($selected_value, 'special-date', false) . '>Date Archive</option>
            <option value="special-author"' . selected($selected_value, 'special-author', false) . '>Author Archive</option>
        </optgroup>
        <optgroup label="Posts">
            <option value="post|all"' . selected($selected_value, 'post|all', false) . '>All Posts</option>
            <option value="post|all|taxarchive|category"' . selected($selected_value, 'post|all|taxarchive|category', false) . '>All Categories Archive</option>
            <option value="post|all|taxarchive|post_tag"' . selected($selected_value, 'post|all|taxarchive|post_tag', false) . '>All Tags Archive</option>
        </optgroup>
        <optgroup label="Pages">
            <option value="page|all"' . selected($selected_value, 'page|all', false) . '>All Pages</option>
        </optgroup>
    ';

    if (class_exists('WooCommerce')) {
        $options .= '
        <optgroup label="Products">
            <option value="product|all"' . selected($selected_value, 'product|all', false) . '>All Products</option>
            <option value="product|all|taxarchive|product_cat"' . selected($selected_value, 'product|all|taxarchive|product_cat', false) . '>All Product Categories Archive</option>
            <option value="product|all|taxarchive|product_tag"' . selected($selected_value, 'product|all|taxarchive|product_tag', false) . '>All Product Tags Archive</option>
        </optgroup>
        ';
    }
    return $options;
}

add_action('wp_ajax_save_conditions', 'solace_save_conditions');
add_action('wp_ajax_nopriv_save_conditions', 'solace_save_conditions');
add_action('wp_ajax_save_edit_conditions', 'solace_save_edit_conditions');
add_action('wp_ajax_nopriv_save_edit_conditions', 'solace_save_edit_conditions');

function solace_save_conditions() {
    if (!isset($_POST['security']) || !wp_verify_nonce(sanitize_key(wp_unslash($_POST['security'])), 'solace_conditions_nonce_action')) {
        wp_send_json_error('Invalid nonce11.');
        return;
    }

    if (!isset($_POST['from']) || !isset($_POST['conditions'])) {
        wp_send_json_error('Invalid request.');
        return;
    }
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized	 --- We need this.
    $conditions = isset($_POST['conditions']) ? $_POST['conditions'] : array();
    
    if (!is_array($conditions)) {
        wp_send_json_error('Invalid conditions data format.');
        return;
    }
    
    $from = isset($_POST['from']) ? sanitize_text_field(wp_unslash($_POST['from'])) : '';
    $part = isset($_POST['part']) ? sanitize_text_field(wp_unslash($_POST['part'])) : '';

    $args = [
        'post_type'      => 'solace-sitebuilder', 
        'post_status'    => 'publish',           
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
        'meta_query'     => [
            [
                'key'     => '_solace_' . $part . '_status', 
                'compare' => 'EXISTS',                             
            ],
        ],
        'fields'         => 'ids',              
        'posts_per_page' => -1,                  
    ];

    $query = new WP_Query($args);
    $count = $query->found_posts;
    $count++;

    if ($from === 'add-new') {
        $post_id = wp_insert_post(array(
            'post_title' => $part . ' #' . $count,
            'post_type' => 'solace-sitebuilder',
            'post_status' => 'draft'
        ));

        if (is_wp_error($post_id)) {
            wp_send_json_error('Failed to create new post.');
            return;
        }
    } elseif ($from === 'edit') {
        if (!isset($_POST['post_id'])) {
            wp_send_json_error('Post ID is missing.');
            return;
        }

        $post_id = intval(wp_unslash($_POST['post_id']));
    } else {
        wp_send_json_error('Invalid action.');
        return;
    }

    if (isset($part)){
        update_post_meta($post_id, '_solace_'.$part.'_status', 1);
    }
    update_post_meta($post_id, '_solace_template', $part);

    $processed_conditions = array();
    foreach ($conditions as $condition) {
        $condition_type = sanitize_text_field(wp_unslash($condition['type']));
        $condition_value = sanitize_text_field(wp_unslash($condition['value']));

        $processed_conditions[] = array(
            'type'  => $condition_type,
            'value' => $condition_value
        );
    }

    update_post_meta($post_id, '_solace_'.$part.'_conditions', $processed_conditions);
    $response = array('post_id' => $post_id);
    wp_send_json_success($response);

}

function solace_save_edit_conditions() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_key(wp_unslash($_POST['nonce'])), 'solace_conditions_nonce_action')) {
        wp_send_json_error('Invalid nonce.');
        return;
    }
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized	 --- We need this.
    if (!isset($_POST['post_id']) || !isset($_POST['conditions'])) {
        wp_send_json_error('Invalid post_id / condition.');
        return;
    }
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized	 --- We need this.
    $post_id = isset($_POST['post_id']) ? intval(wp_unslash($_POST['post_id'])) : null;
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized	 --- We need this.
    $conditions = isset($_POST['conditions']) ? wp_unslash($_POST['conditions']) : array();

    if (!is_array($conditions)) {
        wp_send_json_error('Conditions data is not valid.');
        return;
    }

    $processed_conditions = array();
    foreach ($conditions as $condition) {
        if (isset($condition['type']) && isset($condition['value'])) {
            $condition_type = sanitize_text_field($condition['type']);
            $condition_value = sanitize_text_field($condition['value']);

            $processed_conditions[] = array(
                'type'  => $condition_type,
                'value' => $condition_value
            );
        } else {
            wp_send_json_error('Invalid condition structure.');
        }
    }

    update_post_meta($post_id, '_solace_header_conditions', $processed_conditions);

    $response = array('post_id' => $post_id);
    wp_send_json_success($response);
}

/**
 * Generalized function to create and set active a new custom 'solace-sitebuilder' post.
 *
 * @param string $meta_key      The meta key to mark active/inactive.
 * @param string $title_prefix  Prefix for the post title.
 * @return void Outputs JSON success or error response.
 */
function solace_extra_create_and_edit_page( $meta_key, $title_prefix ) {
    // Query existing posts with the specific meta key.
    $args = array(
        'post_type'      => 'solace-sitebuilder',
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'meta_key'       => $meta_key,
        'posts_per_page' => -1,
    );
    $query = new WP_Query( $args );
    $post_count = count( $query->posts ) + 1;

    // Reset all existing posts' meta key to false.
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            update_post_meta( get_the_ID(), $meta_key, false );
        }
    }
    wp_reset_postdata();

    // Create a new draft post.
    $new_post = array(
        'post_title'   => $title_prefix . $post_count,
        'post_content' => '',
        'post_status'  => 'draft',
        'post_type'    => 'solace-sitebuilder',
    );

    $post_id = wp_insert_post( $new_post );
    // error_log('rico-post_id'.$post_id);
    // error_log('rico-metakey'.$meta_key);
    update_post_meta($post_id, '_solace_template', $meta_key);

    if ( $post_id ) {
        update_post_meta( $post_id, $meta_key, 1 );

        wp_send_json_success( array(
            'post_id'    => $post_id,
            'post_title' => esc_html( $title_prefix . $post_count ),
        ) );
        // error_log('rico3 meta_key'.$meta_key);
        
        update_post_meta($post_id, '_solace_template', $meta_key);
        
    } else {
        // error_log('rico-error'.$post_id);
        wp_send_json_error( 'Failed to create new post' );
    }
    // error_log('rico4 meta_key'.$meta_key);
}

/**
 * Unified AJAX handler for creating a custom 'solace-sitebuilder' post.
 * Accepts POST parameter 'type': (e.g., 'shopproduct', 'blogsinglepost', 'blogarchive', '404' )
 */
function solace_extra_create_and_edit_page_ajax_handler() {
    // Verify nonce.
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'solace_conditions_nonce_action' ) ) {
        wp_send_json_error( array( 'error' => 'Invalid nonce!' ) );
    }

    // Check current user.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'error' => 'Unauthorized' ) );
    }

    // Validate and map the type to meta key and title prefix.
    $type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

    $types = array(
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'singleproduct'  => array( 'meta_key' => '_solace_singleproduct_status', 'title_prefix' => 'Single Product #' ),
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'shopproduct'  => array( 'meta_key' => '_solace_shopproduct_status', 'title_prefix' => 'Shop #' ),
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'purchase-summary'  => array( 'meta_key' => '_solace_purchase-summary_status', 'title_prefix' => 'Purchase Summary #' ),
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'blogsinglepost'  => array( 'meta_key' => '_solace_blogsinglepost_status', 'title_prefix' => 'Single Post #' ),
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'blogarchive'  => array( 'meta_key' => '_solace_blogarchive_status', 'title_prefix' => 'Blog Archive #' ),
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        '404'          => array( 'meta_key' => '_solace_404_status', 'title_prefix' => '404 #' ),
    );

    // Check if provided type is valid
    if ( empty( $type ) || ! array_key_exists( $type, $types ) ) {
        wp_send_json_error( array( 'error' => 'Invalid type provided', 'allowed_types' => array_keys( $types ) ) );
    }    

    $meta_key     = $types[ $type ]['meta_key'];
    $title_prefix = $types[ $type ]['title_prefix'];



    solace_extra_create_and_edit_page( $meta_key, $title_prefix );
}
add_action( 'wp_ajax_create_and_edit_page', 'solace_extra_create_and_edit_page_ajax_handler' );
add_action( 'wp_ajax_nopriv_create_and_edit_page', 'solace_extra_create_and_edit_page_ajax_handler' );


function solace_extra_delete_action_ajax_handler() {
    // Check if the nonce is set and valid
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'solace_conditions_nonce_action' ) ) {
        wp_send_json_error( array( 'error' => 'Invalid nonce!' ) );
    }

    // Check if the current user has the required capability
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'error' => 'Unauthorized' ) );
    }

    // Get and sanitize the ID from the request
    $id = isset( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : 0;

    if ( empty( $id ) ) {
        wp_send_json_error( array( 'error' => 'Invalid ID.' ) );
    }

    // Optional: Log the ID for debugging
    // error_log( '$id: ' . print_r( $id, true ) );

    // Attempt to delete the post
    $result = wp_delete_post( $id, true ); // true to force delete, bypass trash

    if ( $result ) {
        wp_send_json_success( array( 'message' => 'Item deleted successfully.' ) );
    } else {
        wp_send_json_error( array( 'error' => 'Failed to delete item.' ) );
    }
}
add_action('wp_ajax_delete_action', 'solace_extra_delete_action_ajax_handler');
add_action('wp_ajax_nopriv_delete_action', 'solace_extra_delete_action_ajax_handler');

function solace_get_condition_label($value) {
    switch ($value) {
        // Basic
        case 'basic-global':
            return 'Entire Website';
        case 'basic-singulars':
            return 'All Singulars';
        case 'basic-archives':
            return 'All Archives';

        // Special Pages
        case 'special-404':
            return '404 Page';
        case 'special-search':
            return 'Search Page';
        case 'special-blog':
            return 'Blog / Posts Page';
        case 'special-front':
            return 'Front Page';
        case 'special-date':
            return 'Date Archive';
        case 'special-author':
            return 'Author Archive';

        // Posts
        case 'post|all':
            return 'All Posts';
        case 'post|all|archive':
            return 'All Posts Archive';
        case 'post|all|taxarchive|category':
            return 'All Categories Archive';
        case 'post|all|taxarchive|post_tag':
            return 'All Tags Archive';

        // Pages
        case 'page|all':
            return 'All Pages';

        // My Templates
        case 'elementor_library|all':
            return 'All My Templates';
        case 'elementor_library|all|archive':
            return 'All My Templates Archive';

        // Products (if WooCommerce is active)
        case 'product|all':
            return 'All Products';
        case 'product|all|archive':
            return 'All Products Archive';
        case 'product|all|taxarchive|product_cat':
            return 'All Product Categories Archive';
        case 'product|all|taxarchive|product_tag':
            return 'All Product Tags Archive';
        case 'product|all|taxarchive|product_shipping_class':
            return 'All Product Shipping Classes Archive';

        // Default if no match found
        default:
            return 'Unknown Condition';
    }
}

// add_filter('template_include', 'solace_sitebuilder_custom_template');
function solace_sitebuilder_custom_template($template) {

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (is_singular('solace-sitebuilder') || (isset($_GET['action']) && sanitize_text_field(wp_unslash($_GET['action'])) === 'elementor')) {
        if (isset($_GET['security'])) {
            $security_nonce = sanitize_key(wp_unslash($_GET['security']));
            if (wp_verify_nonce($security_nonce, 'solace_sitebuilder_nonce_action')) {
                $plugin_template = plugin_dir_path(__FILE__) . 'single-custom.php';
            
                if (file_exists($plugin_template)) {
                    return $plugin_template; 
                }
            } else {
                wp_die('Nonce verification failed.');
            }
        }
    }
    
    return $template;
}

// DISABLE THIS TEMPLATE FOR TEMPORARY
function solace_custom_archive_template($archive_template) {
    $plugin_template = plugin_dir_path(__FILE__) . 'archive.php';
    
    // error_log('Checking custom archive template from plugin');

    if (file_exists($plugin_template)) {
        // error_log('Using custom archive template from plugin: ' . $plugin_template);
        return $plugin_template;
    }

    return $archive_template;
}
// add_filter('archive_template', 'solace_custom_archive_template', 999);

function solace_register_archive_template_cpt() {
    $args = array(
        'public'              => true,  
        'publicly_queryable'  => true,  
        'show_ui'             => true,
        'show_in_menu'        => true,
        'label'               => 'Archive Templates',
        'supports'            => array('title', 'editor', 'elementor'),
        'rewrite'             => array('slug' => 'solace-archive'), 
    );
    register_post_type('solace_archive', $args);
}
// add_action('init', 'solace_register_archive_template_cpt');

add_action('admin_post_delete_post', 'solace_handle_delete_post');

function solace_handle_delete_post() {

    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.InputNotValidated --- We need this.
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'delete_post_' . $_GET['id'])) {
        wp_die('Nonce verification failed.');
    }    

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $post_id = intval($_GET['id']);
        
        if (current_user_can('delete_post', $post_id)) {
            wp_delete_post($post_id, true); 
        } else {
            wp_die('You do not have permission to delete this post.');
        }
    } else {
        wp_die('Invalid post ID.');
    }
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized	 --- We need this.
    $part = isset($_GET['part']) ? sanitize_text_field(wp_unslash($_GET['part'])) : 'header';
    
    wp_safe_redirect(admin_url('admin.php?page=dashboard-sitebuilder&part=' . $part));
    exit;
}

function solace_check_elementor_plugin_status() {
    if ( is_plugin_active( 'elementor/elementor.php' ) ) {
        wp_send_json_success( 'Elementor is active.' );
    } else {
        wp_send_json_error( 'Elementor is not active.' );
    }
}
add_action( 'wp_ajax_check_elementor_status', 'solace_check_elementor_plugin_status' );


function solace_get_part_status($part) {
    $is_checked = false;
    $image = $part . '.svg'; // Default image
    $lock_class = 'lock';    // Default lock class
    $active_blue = '';
    $is_disabled = false;    // Default to not disabled

    $posts = get_posts([
        'post_type'      => 'solace-sitebuilder',
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'meta_key'       => '_solace_' . $part . '_status',
        'posts_per_page' => -1,
        'fields'         => 'ids'
    ]);

    if (empty($posts)) {
        $is_disabled = true; // Disable the switch if no posts found

        return [
            'image' => $part . '.svg', // Default image
            'lock_class' => 'lock',    // Lock class
            'is_checked' => false,
            'active_blue' => $active_blue,
            'is_disabled' => $is_disabled
        ];
    }

    $found_value_1 = false;
    $found_value_0 = true; // Default, assume all are 0

    foreach ($posts as $post_id) {
        $meta_value = get_post_meta($post_id, '_solace_' . $part . '_status', true);

        if ($meta_value === '1') {
            $found_value_1 = true;
        }

        if ($meta_value !== '0') {
            $found_value_0 = false; // At least one value is not 0
        }
    }

    if ($found_value_1) {
        $active_blue = 'active';
        $image = $part . '-active.svg'; 
        $lock_class = ''; 
        $is_checked = true;
    } elseif ($found_value_0) {
        $image = $part . '-dark.svg'; 
        $is_checked = false;
    }

    return [
        'image' => $image,
        'lock_class' => $lock_class,
        'is_checked' => $is_checked,
        'active_blue' => $active_blue,
        'is_disabled' => $is_disabled
    ];
}



add_action('wp_ajax_get_elementor_content', 'solace_get_elementor_content_via_ajax');
add_action('wp_ajax_nopriv_get_elementor_content', 'solace_get_elementor_content_via_ajax');  

function solace_get_elementor_content_via_ajax() {
    if (!did_action('elementor/loaded')) {
        wp_die(esc_html__('Elementor is not active. Please activate Elementor to view this content.', 'solace-extra'));
    }

    // $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;
     // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $post_id = isset($_GET['post_id']) ? absint(sanitize_text_field(wp_unslash($_GET['post_id']))) : 0;


    if (!$post_id || get_post_type($post_id) !== 'solace-sitebuilder') {
        wp_die(esc_html__('Invalid post ID or post type.', 'solace-extra'));
    }

    // Load Elementor content
    $elementor_content = \Elementor\Plugin::$instance->frontend->get_builder_content($post_id, true);

    // Start output buffering
    ob_start();

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Preview</title>
        <?php
        // Enqueue Elementor and dependent styles
        \Elementor\Plugin::$instance->frontend->enqueue_styles();
        \Elementor\Plugin::$instance->frontend->enqueue_scripts();

        // Load global styles from Default Kit
        $kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();
        $kit_content = \Elementor\Plugin::$instance->frontend->get_builder_content( $kit_id, true );
        echo wp_kses_post($kit_content);

        // Other theme or plugin styles
        wp_enqueue_style('solace-theme', get_template_directory_uri() . '/assets-solace/css/theme.min.css', array(), SOLACE_EXTRA_VERSION);
        wp_enqueue_style(
            'solace-widget-nav-menu',
            plugin_dir_url(__FILE__) . '../assets/css/widget-nav-menu.min.css',
            array(),
            SOLACE_EXTRA_VERSION
        );

        wp_head();
        ?>
        <!-- Custom inline styles -->
        <style>
            body {
                overflow: hidden;
            }
            body p.woocommerce-store-notice.demo_store {
                display: none !important;
            }
            
        </style>
    </head>
    <body>
        <?php
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $elementor_content;
        ?>
        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo ob_get_clean();

    die();
}

add_action('wp_head', function() {
    ?>
    <style>
        .delayed-content {
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s linear .5s, opacity .5s ease-in-out; 
        }
        .delayed-content.show {
            visibility: visible;
            opacity: 1;
            transition-delay: 0s; 
        }
    </style>
    <?php
});



add_action('wp_footer', function() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var delayedElements = document.querySelectorAll('.delayed-content');
                delayedElements.forEach(function(el) {
                    el.classList.add('show');
                });
            }, 1000); 
        });
    </script>
    <?php
});


function solace_debug_full_page_info() {
    $current_id = 0;
    $title = 'N/A';
    $post_type = 'N/A';
    $is_editor = 'NO';

    if ( is_admin() ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin debug context, reading GET for display only.
        if ( isset( $_GET['post'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin context, value sanitized with intval().
            $current_id = intval( $_GET['post'] );
            $title = get_the_title( $current_id );
            $post_type = get_post_type( $current_id );
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin context, reading GET for editor check.
            $is_editor = ( isset( $_GET['action'] ) && $_GET['action'] == 'elementor' ) ? 'YES' : 'NO';
        }
    } else {
        $queried_object = get_queried_object();
        
        if ( is_singular() ) {
            $current_id = get_the_ID();
            $title = get_the_title( $current_id );
            $post_type = get_post_type( $current_id );
        } elseif ( is_category() || is_tag() || is_tax() ) {
            if ( $queried_object ) {
                $current_id = $queried_object->term_id;
                $title = $queried_object->name;
                $post_type = 'Taxonomy: ' . $queried_object->taxonomy;
            }
        } elseif ( is_post_type_archive() ) {
            if ( $queried_object ) {
                $title = $queried_object->label;
                $post_type = 'Archive: ' . $queried_object->name;
            }
        }
    }

    if ( ! $current_id && ! is_post_type_archive() ) return;

    $solace_template_id = 'None';
    if ( ! is_admin() && ( is_post_type_archive( 'product' ) || is_tax( 'product_cat' ) ) ) {
        $solace_query = get_posts( [
            'post_type'      => 'solace-sitebuilder',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
            'meta_key'       => '_solace_shopproduct_status',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
            'meta_value'     => '1',
            'fields'         => 'ids',
            'posts_per_page' => 1
        ] );
        if ( ! empty( $solace_query ) ) {
            $solace_template_id = $solace_query[0];
        }
    }
    
}


function solace_custom_body_class( $classes ) {
    $template_id = null;

    if ( is_post_type_archive('product') || is_tax('product_cat') || is_tax('product_tag') ) {
        $solace_query = get_posts([
            'post_type'      => 'solace-sitebuilder',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
            'meta_key'       => '_solace_shopproduct_status',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
            'meta_value'     => '1',
            'fields'         => 'ids',
            'posts_per_page' => 1
        ]);
        if ( ! empty( $solace_query ) ) {
            $template_id = $solace_query[0];
        }
    } 
    elseif ( is_singular() ) {
        $template_id = get_the_ID();
    }

    if ( $template_id ) {
        $layout = get_post_meta( $template_id, '_wp_page_template', true );
        $layout_class = $layout ? $layout : 'default';
        $classes[] = 'solace_' . sanitize_html_class( $layout_class );
    }

    return $classes;
}


function solace_render_custom_styles() {
    ?>
    <style type="text/css">
        body.solace_elementor_header_footer .container,
        body.solace_elementor_canvas .container {
            max-width: none !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin: 0 !important;
        }

        body.solace_elementor_header_footer .elementor,
        body.solace_elementor_canvas .elementor {
            width: 100%;
        }
        body.theme-solace span.onsale {
            z-index: 2;
        }
        body.solace-sitebuilder-singleproduct.single-product .container.shop-container .woocommerce-message {
            margin-bottom: 0;
        }
        body.solace-sitebuilder-singleproduct.single-product .container.shop-container {
            padding-left: 0;
            padding-right: 0;
        }
    </style>
    <?php
}

// add_action( 'wp', 'solace_debug_full_page_info' );
// add_action( 'admin_init', 'solace_debug_full_page_info' );

add_filter( 'body_class', 'solace_custom_body_class' );
add_action( 'wp_head', 'solace_render_custom_styles', 100 );

