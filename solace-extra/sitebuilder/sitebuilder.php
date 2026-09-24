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
            $message = "Detected shop page.";
            return 'product|all';
        } elseif (is_product()) {
            $message = "Detected product page.";
            return 'product|all';
        } elseif (is_cart()) {
            $message = "Detected cart page.";
            return 'product|all';
        } elseif (is_checkout()) {
            $message = "Detected checkout page.";
            return 'product|all';
        } elseif (is_account_page()) {
            $message = "Detected account page.";
            return 'product|all';
        } elseif (is_product_category()) {
            $message = "Detected product category page.";
            return 'product|all|taxarchive|product_cat';
        } elseif (is_product_tag()) {
            $message = "Detected product tag page.";
            return 'product|all|taxarchive|product_tag';
        } elseif ( is_tax( 'product_brand' ) ) {
            $message = "Detected product brand page.";
            return 'product|all|taxarchive|product_brand';
        } elseif ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) {
            $message = "Detected other product taxonomy page (attribute/shipping class).";
            $queried = get_queried_object();
            return $queried && isset( $queried->taxonomy ) ? 'product|all|taxarchive|' . $queried->taxonomy : 'product|all|taxarchive';
        } elseif (is_search() && get_query_var('post_type') == 'product') {
            $message = "Detected product search page.";
            return 'product|all|archive';
        } elseif (is_order_received_page()) {
            $message = "Detected order received page.";
            return 'product|all';
        }
    }

    if (is_front_page()) {
        $message = "Detected front page.";
        return 'special-front';
    } elseif (is_home()) {
        $message = "Detected blog page.";
        return 'special-blog';
    } elseif (is_404()) {
        $message = "Detected not found page (404).";
        return 'special-404';
    } elseif (is_author()) {
        $message = "Detected author page.";
        return 'special-author';
    } elseif (is_search()) {
        $message = "Detected general search page.";
        return 'special-search';
    } elseif (is_date()) {
        $message = "Detected date archive page.";
        return 'special-date';
    }

    if (is_archive()) {
        if (is_category()) {
            $message = "Detected category archive page.";
            return 'post|all|taxarchive|category';
        } elseif (is_tag()) {
            $message = "Detected tag archive page.";
            return 'post|all|taxarchive|post_tag';
        } elseif (is_post_type_archive()) {
            $message = "Detected custom post type archive page.";
            return 'post|all|archives'; 
        } elseif (is_tax()) {
            $message = "Detected taxonomy archive page.";
            return 'basic-archives'; 
        } else {
            $message = "Detected general archive page.";
            return 'basic-archives'; 
        }
    }

    if (is_singular()) {
        if (is_page()) {
            $message = "Detected singular page.";
            return 'page|all'; 
        } elseif (is_attachment()) {
            $message = "Detected attachment page.";
            return 'basic-archives'; 
        } elseif (is_single()) {
            $message = "Detected single post page.";
            return 'post|all'; 
        } else {
            $message = "Detected general singular page.";
            return 'basic-singulars'; 
        }
    }

    // Check other fallbacks.
    if (is_feed()) {
        $message = "Detected feed page.";
        return 'basic-archives';
    } elseif (is_trackback()) {
        $message = "Detected trackback page.";
        return 'basic-archives';
    } elseif (is_embed()) {
        $message = "Detected embed page.";
        return 'basic-archives';
    } elseif (is_privacy_policy()) {
        $message = "Detected privacy policy page.";
        return 'basic-archives';
    }

    $message = "Detected fallback page.";
    return 'basic-archives';
}

/**
 * Normalize a saved conditions array.
 *
 * The display logic is include-driven: a template only renders when one of its
 * `include` conditions matches the current page. That means a rule containing
 * ONLY `exclude` conditions would never render anywhere, so excluding a page type
 * on an otherwise site-wide template appears to do nothing.
 *
 * An exclude-only rule is meant to read as "show on the entire website EXCEPT the
 * excluded page types", so we inject an implicit `basic-global` include for it.
 *
 * @param mixed $conditions Unserialized conditions array.
 * @return array
 */
function solace_sitebuilder_normalize_conditions( $conditions ) {
    if ( ! is_array( $conditions ) ) {
        return array();
    }

    $has_include = false;
    $has_exclude = false;

    foreach ( $conditions as $condition ) {
        if ( ! isset( $condition['type'] ) ) {
            continue;
        }
        if ( $condition['type'] === 'include' ) {
            $has_include = true;
        } elseif ( $condition['type'] === 'exclude' ) {
            $has_exclude = true;
        }
    }

    if ( $has_exclude && ! $has_include ) {
        array_unshift( $conditions, array( 'type' => 'include', 'value' => 'basic-global' ) );
    }

    return $conditions;
}

/**
 * Map a Solace Site Builder part to its Elementor Pro Theme Builder template
 * type. Header/Footer are direct; the other parts map to the WooCommerce /
 * blog / 404 template types. Purchase Summary has no Elementor Pro theme
 * builder equivalent.
 *
 * @param string $part Solace part (header, footer, singleproduct, shopproduct,
 *                     blogsinglepost, blogarchive, 404).
 * @return string Elementor Pro template type, or '' when none exists.
 */
function solace_sitebuilder_elementor_pro_part_map( $part ) {
    $map = array(
        'header'         => 'header',
        'footer'         => 'footer',
        'singleproduct'  => 'product',
        'shopproduct'    => 'product-archive',
        'blogsinglepost' => 'single',
        'blogarchive'    => 'archive',
        '404'            => 'error-404',
    );
    return isset( $map[ $part ] ) ? $map[ $part ] : '';
}

/**
 * Human-readable label for a Solace Site Builder part.
 *
 * @param string $part Solace part type.
 * @return string
 */
function solace_sitebuilder_part_label( $part ) {
    $labels = array(
        'header'         => __( 'Header', 'solace-extra' ),
        'footer'         => __( 'Footer', 'solace-extra' ),
        'singleproduct'  => __( 'Shop Single Product', 'solace-extra' ),
        'shopproduct'    => __( 'Shop Product Archive', 'solace-extra' ),
        'blogsinglepost' => __( 'Blog Single Post', 'solace-extra' ),
        'blogarchive'    => __( 'Blog Archive', 'solace-extra' ),
        '404'            => __( '404 Page', 'solace-extra' ),
        'purchase-summary' => __( 'Purchase Summary', 'solace-extra' ),
    );
    return isset( $labels[ $part ] ) ? $labels[ $part ] : $part;
}

/**
 * List active Elementor Pro Theme Builder templates that overlap with a Solace
 * Site Builder part.
 *
 * Elementor Pro (Theme Builder) stores its templates as `elementor_library`
 * posts with `_elementor_template_type` (header/footer/product/product-archive/
 * single/archive/error-404) and their display conditions in
 * `_elementor_conditions` (e.g. ["include/general"] for the whole website).
 * When one of those is active for the same surface this Site Builder part
 * renders, only one of them shows on the frontend.
 *
 * @param string $part Solace part (header, footer, singleproduct, shopproduct,
 *                     blogsinglepost, blogarchive, 404).
 * @return array{id:int,title:string,edit_url:string,labels:string[],is_entire:bool}[]
 */
function solace_sitebuilder_get_elementor_pro_parts( $part ) {
    $ep_type = solace_sitebuilder_elementor_pro_part_map( $part );
    if ( ! $ep_type ) {
        return array();
    }
    if ( ! class_exists( '\ElementorPro\Plugin' ) && ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
        return array();
    }

    $entire_conditions = array(
        'header'         => array( 'include/general' ),
        'footer'         => array( 'include/general' ),
        'singleproduct'  => array( 'include/product' ),
        'shopproduct'    => array( 'include/product_archive' ),
        'blogsinglepost' => array( 'include/singular' ),
        'blogarchive'    => array( 'include/archive' ),
        '404'            => array( 'include/error_404' ),
    );

    $templates = get_posts(
        array(
            'post_type'      => 'elementor_library',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Filter by Elementor Pro template type.
            'meta_key'       => '_elementor_template_type',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Filter by Elementor Pro template type.
            'meta_value'     => $ep_type,
            'fields'         => 'ids',
        )
    );

    $result = array();
    foreach ( (array) $templates as $id ) {
        $conditions = get_post_meta( $id, '_elementor_conditions', true );
        if ( ! is_array( $conditions ) ) {
            $conditions = array();
        }

        $broad = isset( $entire_conditions[ $part ] ) ? $entire_conditions[ $part ] : array();
        $is_entire = ! empty( array_intersect( $broad, $conditions ) );

        $result[] = array(
            'id'        => absint( $id ),
            'title'     => get_the_title( $id ),
            'edit_url'  => admin_url( 'post.php?post=' . absint( $id ) . '&action=elementor' ),
            'labels'    => array_map( 'solace_sitebuilder_elementor_condition_label', $conditions ),
            'is_entire' => $is_entire,
        );
    }

    return $result;
}

/**
 * Human-readable label for one Elementor Pro Theme Builder condition value.
 *
 * @param string $condition Raw condition value, e.g. "include/general".
 * @return string
 */
function solace_sitebuilder_elementor_condition_label( $condition ) {
    $map = array(
        'include/general'        => __( 'Entire Website', 'solace-extra' ),
        'include/archive'        => __( 'All Archives', 'solace-extra' ),
        'include/singular'       => __( 'All Singulars', 'solace-extra' ),
        'include/front_page'     => __( 'Front Page', 'solace-extra' ),
        'include/error_404'      => __( '404 Page', 'solace-extra' ),
        'include/search_results' => __( 'Search Results', 'solace-extra' ),
        'include/author'         => __( 'Author Archives', 'solace-extra' ),
        'include/date'           => __( 'Date Archives', 'solace-extra' ),
        // WooCommerce Theme Builder conditions.
        'include/product'        => __( 'All Products', 'solace-extra' ),
        'include/product_archive' => __( 'All Products Archive', 'solace-extra' ),
        'include/product_cat'    => __( 'All Product Categories', 'solace-extra' ),
        'include/product_tag'    => __( 'All Product Tags', 'solace-extra' ),
    );

    if ( isset( $map[ $condition ] ) ) {
        return $map[ $condition ];
    }

    return str_replace(
        array( 'include/', 'exclude/' ),
        array( '', __( 'Exclude: ', 'solace-extra' ) ),
        $condition
    );
}

/**
 * Whether the Solace Site Builder has an active (status 1) published part of
 * the given type.
 *
 * @param string $part 'header' | 'footer'.
 * @return bool
 */
function solace_sitebuilder_part_is_active( $part ) {
    if ( ! in_array( $part, array( 'header', 'footer', 'singleproduct', 'shopproduct', 'blogsinglepost', 'blogarchive', '404', 'purchase-summary' ), true ) ) {
        return false;
    }
    $posts = get_posts(
        array(
            'post_type'      => 'solace-sitebuilder',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Active status per part.
            'meta_key'       => '_solace_' . $part . '_status',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Active status per part.
            'meta_value'     => '1',
            'fields'         => 'ids',
        )
    );
    return ! empty( $posts );
}

/**
 * Shared conflict content for the Elementor Pro Theme Builder notice and the
 * All Layout card tooltip: heading, explanation, template list, note.
 *
 * @param string $part_label 'Header' or 'Footer'.
 * @param array  $templates  Templates from solace_sitebuilder_get_elementor_pro_parts().
 * @param bool   $whole_site Whether any template covers the entire website.
 * @param bool   $plain      Render template titles as text (no links) for the card tooltip.
 * @param bool   $include_heading Whether to include the "…conflict" heading (the card overlay
 *                                already shows it, so the tooltip skips it).
 * @return string Escaped HTML.
 */
function solace_sitebuilder_elementor_pro_conflict_content( $part_label, $templates, $whole_site, $plain = false, $include_heading = true ) {
    $html = '';
    if ( $include_heading ) {
        $html .= '<p><strong>' . esc_html__( 'Elementor Pro Theme Builder conflict', 'solace-extra' ) . '</strong></p>';
    }
    $html .= '<p>' . esc_html(
        sprintf(
            /* translators: %s: Header or Footer */
            __( 'A %s from Elementor Pro Theme Builder is also active for this page. Only one of the two can render on the frontend.', 'solace-extra' ),
            $part_label
        )
    ) . '</p>';
    $html .= '<ul style="list-style:disc;padding-left:22px;margin:0 0 8px;">';
    foreach ( $templates as $t ) {
        $labels = ! empty( $t['labels'] ) ? implode( ', ', $t['labels'] ) : __( 'no conditions', 'solace-extra' );
        if ( $plain ) {
            // Tooltip lives inside the card's <a>, so nested anchors would
            // break the markup and scramble the grid. Render text only.
            $html .= '<li>' . esc_html( $t['title'] ) . ' &mdash; ' . esc_html( $labels ) . '</li>';
        } else {
            $html .= '<li><a href="' . esc_url( $t['edit_url'] ) . '" target="_blank">' . esc_html( $t['title'] ) . '</a> &mdash; ' . esc_html( $labels ) . '</li>';
        }
    }
    $html .= '</ul>';
    if ( $whole_site ) {
        $html .= '<p style="margin:0;">' . esc_html(
            __( 'Draft or Trash the Elementor Pro part, or turn off this Solace Site Builder part, so the intended one takes effect.', 'solace-extra' )
        ) . '</p>';
    }
    return $html;
}

/**
 * Render an admin notice when Elementor Pro Theme Builder has an active
 * header/footer template that conflicts with this Site Builder part.
 *
 * Shown on the Site Builder dashboard for the header/footer part views.
 *
 * @param string $part 'header' | 'footer'.
 * @return void
 */
function solace_sitebuilder_elementor_pro_notice( $part ) {
    if ( ! in_array( $part, array( 'header', 'footer', 'singleproduct', 'shopproduct', 'blogsinglepost', 'blogarchive', '404' ), true ) ) {
        return;
    }
    if ( ! solace_sitebuilder_part_is_active( $part ) ) {
        return;
    }

    $templates = solace_sitebuilder_get_elementor_pro_parts( $part );
    if ( empty( $templates ) ) {
        return;
    }

    solace_sitebuilder_conflict_box_css();

    $part_label = solace_sitebuilder_part_label( $part );
    $whole_site = array_filter(
        $templates,
        static function ( $t ) {
            return ! empty( $t['is_entire'] );
        }
    );

    echo '<div class="solace-ep-conflict-box">';
    echo solace_sitebuilder_elementor_pro_conflict_content( $part_label, $templates, ! empty( $whole_site ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper.
    echo '</div>';
}

/**
 * Print the conflict box style once per request.
 *
 * Both dashboard notices -- Theme Builder and Site Settings -- use the same
 * box, and either may be the first one on the page.
 *
 * @return void
 */
function solace_sitebuilder_conflict_box_css() {
    static $done = false;
    if ( $done ) {
        return;
    }
    $done = true;
    // Custom class: the dashboard admin CSS hides every `.notice` with
    // !important on these pages (body.solace_page_dashboard-sitebuilder
    // #wpbody-content .notice { display:none !important; }).
    echo '<style>.solace-ep-conflict-box{background:#fff;border-left:4px solid #dba617;box-shadow:0 1px 1px rgba(0,0,0,.04);padding:1px 12px;margin:16px 0;}</style>';
}

/**
 * Whether an active Shop Product Archive part replaces the WooCommerce Shop page.
 *
 * Mirrors solace_extra_pro_resolve_shopproduct_post_id() in Solace Extra Pro,
 * which renders the part: a published, switched-on part applies when its
 * conditions include "Shop Page" (product|shop) and do not exclude it, or when
 * it has no conditions at all, which Pro still honours everywhere. The other
 * values Pro understands never match the Shop page -- "All Products Archive"
 * (product|all|archive) deliberately leaves it out -- so only product|shop is
 * checked here.
 *
 * @return bool
 */
function solace_sitebuilder_shopproduct_replaces_shop_page() {
    $posts = get_posts(
        array(
            'post_type'      => 'solace-sitebuilder',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Active Shop Product Archive parts.
            'meta_key'       => '_solace_shopproduct_status',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Active Shop Product Archive parts.
            'meta_value'     => '1',
            'fields'         => 'ids',
        )
    );

    foreach ( (array) $posts as $post_id ) {
        $conditions = maybe_unserialize( get_post_meta( $post_id, '_solace_shopproduct_conditions', true ) );

        if ( empty( $conditions ) || ! is_array( $conditions ) ) {
            return true;
        }

        $included = false;
        $excluded = false;

        foreach ( $conditions as $condition ) {
            if ( ! is_array( $condition ) || ! isset( $condition['type'], $condition['value'] ) || 'product|shop' !== $condition['value'] ) {
                continue;
            }
            if ( 'exclude' === $condition['type'] ) {
                $excluded = true;
            } elseif ( 'include' === $condition['type'] ) {
                $included = true;
            }
        }

        if ( $included && ! $excluded ) {
            return true;
        }
    }

    return false;
}

/**
 * A WooCommerce page chosen in Elementor that a Solace part silently replaces.
 *
 * Elementor Site Settings > WooCommerce Pages has two fields that overlap a
 * Site Builder part, and neither overlap is visible anywhere else:
 *
 *  - Purchase Summary (Elementor Pro, option
 *    elementor_woocommerce_purchase_summary_page_id). Elementor Pro rewrites the
 *    order-received URL to that page, but Solace Extra Pro's purchase summary
 *    override keys on the order-received query var alone, which that URL still
 *    carries -- so the Solace part renders at the Elementor page's address and
 *    the page's own design is never shown. Needs both Elementor Pro, which
 *    installs the redirect, and an active Purchase Summary part.
 *  - Shop (WooCommerce's own woocommerce_shop_page_id, which Elementor Pro
 *    merely mirrors). Every shop has one, so choosing a Shop page is not by
 *    itself a conflict. It becomes one when that page was designed with
 *    Elementor and a Shop Product Archive part replaces the Shop page: Solace
 *    Extra Pro's shop template renders only the part, so the design disappears.
 *
 * Both overrides live in Solace Extra Pro, so nothing is reported without it.
 *
 * @param string $part 'shopproduct' | 'purchase-summary'.
 * @return array{setting:string,location:string,page_title:string,edit_url:string}|array Empty when nothing conflicts.
 */
function solace_sitebuilder_elementor_site_settings_conflict( $part ) {
    if ( ! in_array( $part, array( 'shopproduct', 'purchase-summary' ), true ) ) {
        return array();
    }
    if ( ! defined( 'SOLACE_EXTRA_PRO_VERSION' ) || ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    $has_elementor_pro = defined( 'ELEMENTOR_PRO_VERSION' ) || class_exists( '\ElementorPro\Plugin' );

    if ( 'purchase-summary' === $part ) {
        if ( ! $has_elementor_pro ) {
            return array();
        }

        $page_id = absint( get_option( 'elementor_woocommerce_purchase_summary_page_id' ) );

        if ( ! $page_id || ! solace_sitebuilder_part_is_active( 'purchase-summary' ) ) {
            return array();
        }

        $setting = __( 'Purchase Summary', 'solace-extra' );
    } else {
        $page_id = function_exists( 'wc_get_page_id' ) ? absint( wc_get_page_id( 'shop' ) ) : 0;

        if ( ! $page_id ) {
            return array();
        }

        // Designed with Elementor: builder mode with actual content, not the
        // empty '[]' Elementor leaves behind on a page opened but never built.
        $elementor_data = (string) get_post_meta( $page_id, '_elementor_data', true );

        if ( 'builder' !== get_post_meta( $page_id, '_elementor_edit_mode', true ) || '' === trim( $elementor_data ) || '[]' === trim( $elementor_data ) ) {
            return array();
        }

        if ( ! solace_sitebuilder_shopproduct_replaces_shop_page() ) {
            return array();
        }

        $setting = __( 'Shop', 'solace-extra' );
    }

    $page_status = get_post_status( $page_id );

    // The chosen page must actually be a real, published page. An attachment
    // (e.g. a media file) or any other non-page post type selected in Site
    // Settings is a stale/incorrect choice and must not trigger the conflict
    // notice (get_post_status() reports 'inherit' attachments as publish).
    if ( 'page' !== get_post_type( $page_id ) || ! $page_status || 'trash' === $page_status ) {
        return array();
    }

    return array(
        'setting'    => $setting,
        // Elementor Pro shows these fields under Site Settings; without it the
        // Shop page is only reachable through WooCommerce's own settings.
        'location'   => $has_elementor_pro
            ? __( 'Elementor Site Settings > WooCommerce Pages', 'solace-extra' )
            : __( 'WooCommerce > Settings > Products', 'solace-extra' ),
        'page_title' => get_the_title( $page_id ),
        'edit_url'   => admin_url( 'post.php?post=' . $page_id . '&action=elementor' ),
    );
}

/**
 * Conflict content for a page chosen in Elementor Site Settings.
 *
 * @param string $part            'shopproduct' | 'purchase-summary'.
 * @param array  $conflict        From solace_sitebuilder_elementor_site_settings_conflict().
 * @param bool   $plain           Render the page title as text (card tooltip, inside an <a>).
 * @param bool   $include_heading Whether to print the heading line.
 * @return string Escaped HTML.
 */
function solace_sitebuilder_site_settings_conflict_content( $part, $conflict, $plain = false, $include_heading = true ) {
    $html = '';

    if ( $include_heading ) {
        $html .= '<p><strong>' . esc_html__( 'Elementor Site Settings conflict', 'solace-extra' ) . '</strong></p>';
    }

    $page = $plain
        ? esc_html( $conflict['page_title'] )
        : '<a href="' . esc_url( $conflict['edit_url'] ) . '" target="_blank">' . esc_html( $conflict['page_title'] ) . '</a>';

    $html .= '<p>' . sprintf(
        /* translators: 1: page title (may be a link), 2: settings location, 3: setting name */
        esc_html__( '%1$s is selected in %2$s as the %3$s page.', 'solace-extra' ),
        $page,
        esc_html( $conflict['location'] ),
        esc_html( $conflict['setting'] )
    ) . ' ';

    if ( 'purchase-summary' === $part ) {
        $html .= esc_html__( 'After checkout, customers are sent to that page, but this Solace Purchase Summary part replaces its content, so the page design is never shown.', 'solace-extra' ) . '</p>';
        $html .= '<p style="margin:0;">' . esc_html__( 'Clear the Purchase Summary page in Elementor Site Settings, or turn off this Solace Site Builder part, so the intended one takes effect.', 'solace-extra' ) . '</p>';
    } else {
        $html .= esc_html__( 'That page is designed with Elementor, but this Solace Shop Product Archive part replaces the Shop page, so the page design is never shown.', 'solace-extra' ) . '</p>';
        $html .= '<p style="margin:0;">' . esc_html__( 'Choose a Shop page that is not designed with Elementor, or turn off this Solace Site Builder part, so the intended one takes effect.', 'solace-extra' ) . '</p>';
    }

    return $html;
}

/**
 * Dashboard notice for a page chosen in Elementor Site Settings that a Solace
 * part replaces. Rendered alongside the Theme Builder notice on the part page.
 *
 * @param string $part 'shopproduct' | 'purchase-summary'.
 * @return void
 */
function solace_sitebuilder_elementor_site_settings_notice( $part ) {
    $conflict = solace_sitebuilder_elementor_site_settings_conflict( $part );

    if ( empty( $conflict ) ) {
        return;
    }

    solace_sitebuilder_conflict_box_css();

    echo '<div class="solace-ep-conflict-box">';
    echo solace_sitebuilder_site_settings_conflict_content( $part, $conflict ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper.
    echo '</div>';
}

/**
 * Compact conflict badge + hover tooltip for the All Layout part cards.
 *
 * The badge is a short warning line on the card; hovering the card shows a
 * tooltip with the same information as the part page notices. It covers both
 * Elementor Pro Theme Builder templates and pages chosen in Elementor Site
 * Settings > WooCommerce Pages, for whichever of them apply.
 *
 * @param string $part Solace part slug.
 * @return void
 */
function solace_sitebuilder_elementor_pro_card_badge( $part ) {
    $ep_parts  = array( 'header', 'footer', 'singleproduct', 'shopproduct', 'blogsinglepost', 'blogarchive', '404' );
    $templates = array();

    if ( in_array( $part, $ep_parts, true ) && solace_sitebuilder_part_is_active( $part ) ) {
        $templates = solace_sitebuilder_get_elementor_pro_parts( $part );
    }

    // Pages picked in Elementor Site Settings > WooCommerce Pages that the part
    // replaces. Checked independently of Theme Builder: either can apply alone.
    $site_conflict = solace_sitebuilder_elementor_site_settings_conflict( $part );

    if ( empty( $templates ) && empty( $site_conflict ) ) {
        return;
    }

    static $css_done = false;
    if ( ! $css_done ) {
        $css_done = true;
        // Static selector list — inlined (no variables) so the PHPCS
        // escape-output sniff stays quiet for this literal <style> block.
        //
        // Stacking and hover. The card's toggle (.slider) sits at z-index 9 in
        // the dashboard stylesheet, so the overlay has to clear it or the open
        // tooltip is painted underneath the switch. Lifting the overlay alone
        // would trade one bug for another: the tooltip used to open on hover of
        // the whole card, and the switch cannot be reached without hovering the
        // card -- it would sit permanently under the tooltip. So the tooltip now
        // opens only on hover of the warning strip, and ignores the pointer
        // itself (it holds plain text only), so moving down across it to the
        // switch closes it rather than keeping it open. A hovered strip rises
        // one more level so its tooltip is not covered by a neighbouring card's.
        echo '<style>'
            . '.part-header,.part-footer,.part-singleproduct,.part-shopproduct,.part-purchase-summary,.part-blogsinglepost,.part-blogarchive,.part-404{position:relative;}'
            . '.solace-ep-card-overlay{position:absolute;top:0;left:0;right:0;z-index:10;background:#000f44;color:#fff;font-size:11px;font-weight:600;line-height:1.4;padding:6px 10px;text-align:left;border-bottom:3px solid #ff9400;box-sizing:border-box;border-radius:8px 8px 0 0;overflow:visible;white-space:nowrap;}'
            . '.solace-ep-card-overlay .solace-ep-detail{position:absolute;top:100%;left:0;right:0;z-index:3;background:#fff;border:2px solid #000f44;border-top:none;border-radius:0 0 8px 8px;box-shadow:0 6px 16px rgba(0,15,68,.25);padding:10px 12px;font-size:11px;font-weight:400;line-height:1.5;color:#1d2327;text-align:left;white-space:normal;pointer-events:none;opacity:0;visibility:hidden;transform:translateY(-4px);transition:opacity .35s ease-in-out,visibility .35s ease-in-out,transform .35s ease-in-out;}'
            . '.solace-ep-card-overlay .solace-ep-detail ul{margin:0 0 6px;padding-left:18px;}'
            . '.solace-ep-card-overlay .solace-ep-detail strong{color:#000f44;}'
            . '.solace-ep-card-overlay .solace-ep-detail a{color:#ff8e00;}'
            . '.solace-ep-card-overlay:hover{z-index:11;}'
            . '.solace-ep-card-overlay:hover .solace-ep-detail{opacity:1;visibility:visible;transform:translateY(0);}'
            . '</style>';
    }

    $both = ! empty( $templates ) && ! empty( $site_conflict );

    if ( $both ) {
        $short = __( 'Elementor conflicts', 'solace-extra' );
    } elseif ( ! empty( $templates ) ) {
        $short = __( 'Elementor Pro Theme Builder conflict', 'solace-extra' );
    } else {
        $short = __( 'Elementor Site Settings conflict', 'solace-extra' );
    }

    // Short always-visible overlay inside the card; the full conflict detail
    // expands below it on hover. Plain text throughout: the tooltip sits inside
    // the card's <a>, and nested anchors would break the grid.
    echo '<div class="solace-ep-card-overlay">&#9888; ' . esc_html( $short );
    echo '<div class="solace-ep-detail">';

    if ( ! empty( $templates ) ) {
        $whole_site = array_filter(
            $templates,
            static function ( $t ) {
                return ! empty( $t['is_entire'] );
            }
        );
        // With a single conflict the overlay strip already names it, so the
        // detail skips the heading; with two, each section needs its own.
        echo solace_sitebuilder_elementor_pro_conflict_content( solace_sitebuilder_part_label( $part ), $templates, ! empty( $whole_site ), true, $both ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper.
    }

    if ( ! empty( $site_conflict ) ) {
        echo solace_sitebuilder_site_settings_conflict_content( $part, $site_conflict, true, $both ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper.
    }

    echo '</div>';
    echo '</div>';
}

/**
 * Rows describing every published Site Builder part of one kind whose status
 * meta is enabled, newest first.
 *
 * The header and footer variants below used to be two separate copies of this
 * body, differing only in the word "header"/"footer". Keeping them in step by
 * hand is what produced the mismatched status key that used to sit in
 * solace_display_custom_header().
 *
 * @param string $part 'header' or 'footer'.
 * @return array<int,array{post_id:string,conditions:array}> Each row also
 *               carries a "{$part}_status" key, which callers rely on.
 */
function solace_get_part_conditions( $part ) {
    global $wpdb;

    $part = ( 'footer' === $part ) ? 'footer' : 'header';

    $sql = "
        SELECT p.ID as post_id,
            pm1.meta_value as part_conditions,
            pm2.meta_value as part_status
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = %s
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = %s
        WHERE p.post_type = %s
        AND p.post_status = 'publish'
        AND pm2.meta_value = %s
        ORDER BY p.ID DESC
    ";

    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this.
    $sql = $wpdb->prepare( $sql, '_solace_' . $part . '_conditions', '_solace_' . $part . '_status', 'solace-sitebuilder', '1' );

    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this.
    $results = $wpdb->get_results( $sql );

    $final_results = array();
    if ( $results ) {
        foreach ( $results as $row ) {
            $final_results[] = array(
                'post_id'           => $row->post_id,
                $part . '_status'   => $row->part_status,
                'conditions'        => solace_sitebuilder_normalize_conditions( maybe_unserialize( $row->part_conditions ) ),
            );
        }
    }

    return $final_results;
}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, kept for backward compatibility.
function get_solace_header_conditions() {
    return solace_get_part_conditions( 'header' );
}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, kept for backward compatibility.
function get_solace_footer_conditions() {
    return solace_get_part_conditions( 'footer' );
}


function solace_is_elementor_editor() {
    if (class_exists('\Elementor\Plugin')) {
        return \Elementor\Plugin::$instance->editor->is_edit_mode();
    }
    return false;
}

/**
 * Detect whether the current request is the Elementor editor or its preview
 * iframe rendering a HEADER or FOOTER Site Builder part itself.
 *
 * The header/footer wrappers run inside the Elementor PREVIEW iframe, where
 * editor->is_edit_mode() is false and only preview->is_preview_mode() is true.
 * When a header/footer part is open in the editor, the wrappers must NOT inject
 * the other active header/footer parts — only the part being edited should
 * appear on the canvas.
 *
 * This deliberately matches header/footer parts ONLY. Other parts (single
 * product, single post, archive, 404, ...) still need the header and footer to
 * render around them for editing context.
 *
 * @return int The header/footer Site Builder post ID being edited, or 0 when not applicable.
 */
function solace_get_editing_header_or_footer_part_id() {
    if ( ! class_exists( '\Elementor\Plugin' ) ) {
        return 0;
    }

    $elementor = \Elementor\Plugin::$instance;

    $is_editor_context = (
        ( isset( $elementor->preview ) && method_exists( $elementor->preview, 'is_preview_mode' ) && $elementor->preview->is_preview_mode() )
        || ( isset( $elementor->editor ) && method_exists( $elementor->editor, 'is_edit_mode' ) && $elementor->editor->is_edit_mode() )
    );

    if ( ! $is_editor_context ) {
        return 0;
    }

    $editing_id = 0;
    if ( isset( $elementor->preview ) && method_exists( $elementor->preview, 'get_post_id' ) ) {
        $editing_id = (int) $elementor->preview->get_post_id();
    }
    if ( ! $editing_id && isset( $elementor->editor ) && method_exists( $elementor->editor, 'get_post_id' ) ) {
        $editing_id = (int) $elementor->editor->get_post_id();
    }

    if ( ! $editing_id || 'solace-sitebuilder' !== get_post_type( $editing_id ) ) {
        return 0;
    }

    // Only header/footer parts carry these meta keys. Match by key existence so
    // disabled parts are still recognised as header/footer parts.
    $is_header_or_footer = (
        metadata_exists( 'post', $editing_id, '_solace_header_status' )
        || metadata_exists( 'post', $editing_id, '_solace_footer_status' )
    );

    return $is_header_or_footer ? $editing_id : 0;
}

/**
 * Render the Site Builder header or footer for this request.
 *
 * Replaces the two ~540-line copy-paste renderers. Those ran three sequential
 * loops over about fifteen near-identical buckets; `break` only left the inner
 * conditions loop, and `$content_displayed` was set but never tested, so a
 * site-wide part and a page-specific part could both render on the same page.
 * Whichever bucket happened to be written first won, rather than the most
 * specific condition, only one bucket checked post status, and two of the
 * skip-lists held typo'd values (`basic-singular`, `basic-archive`) that could
 * never match anything.
 *
 * solace_resolve_conditions_parts() now answers "which parts apply here, and in
 * what order?" and this function just renders the answer. Parts stack on
 * purpose (product decision, 10dc264f): most general first, most specific last,
 * published parts only.
 *
 * @since 1.7.2
 * @param string $part Either 'header' or 'footer'.
 * @return bool True when a part was rendered, false to let the theme fall back.
 */
function solace_render_sitebuilder_part( $part ) {
    // When editing a Site Builder part in the Elementor editor/preview, render
    // nothing here — only the edited part should show on canvas. Return true so
    // the theme wrapper does not fall back to the default builder either.
    if ( solace_get_editing_header_or_footer_part_id() ) {
        return true;
    }

    if ( did_action( 'elementor/loaded' ) ) {
        \Elementor\Plugin::$instance->frontend->enqueue_styles();
    }

    // Issue #1: exactly one part renders — the most specific match wins.
    // solace_resolve_conditions_parts() returns general-first, specific-last,
    // so the last ID is the one with the highest specificity score. The old
    // renderer echoed every matching part, which showed two headers/footers
    // at once whenever a basic-global part and a page-specific part both
    // applied.
    $post_id = solace_resolve_conditions_part( $part );

    // Nothing applies: still emit the marker the theme and form-builder.js look
    // for, then hand back to the theme's own header/footer builder.
    if ( ! $post_id ) {
        printf(
            "<div style='display: none !important;' class='solace-custom-header-footer solace-custom-%s' post-id='0'></div>",
            esc_attr( $part )
        );

        return false;
    }

    if ( solace_is_elementor_editor() ) {
        return false;
    }

    // Single marker — form-builder.js reads it with .attr().
    printf(
        "<div style='display: none !important;' class='solace-custom-header-footer solace-custom-%s' post-id='%d'></div>",
        esc_attr( $part ),
        absint( $post_id )
    );

    if ( 'footer' === $part ) {
        solace_enqueue_icon_list_css_if_needed( $post_id );
        solace_enqueue_social_icons_css_if_needed( $post_id );
    }

    $meta_content = get_post_meta( $post_id, '_elementor_data', true );

    // solace-custom-{part}-content is what the canvas-layout CSS hides.
    printf( '<div class="solace-custom-%s-content">', esc_attr( $part ) );

    if ( ! empty( $meta_content ) && did_action( 'elementor/loaded' ) ) {
        echo do_shortcode( \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id ) );
    } else {
        // Fallback for a part that was never built with Elementor.
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress filter.
        echo wp_kses_post( apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) ) );
    }

    echo '</div>';

    return true;
}

function solace_display_custom_header() {
    return solace_render_sitebuilder_part( 'header' );
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

/**
 * Check if a post contains a social-icons widget in its Elementor data
 *
 * @param int $post_id The post ID to check
 * @return bool True if social-icons widget is found, false otherwise
 */
function solace_post_has_social_icons_widget( $post_id ) {
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

    $has_social_icons = false;

    /**
     * Recursive function to search for social-icons widget in Elementor data
     *
     * @param array $elements Array of Elementor elements to search through
     */
    $check_widgets = function( $elements ) use ( &$check_widgets, &$has_social_icons ) {
        foreach ( $elements as $element ) {
            // Check if element has a widget type
            if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'social-icons' ) {
                $has_social_icons = true;
                return; // Early return if found
            }

            // Recursively check nested elements
            if ( ! empty( $element['elements'] ) ) {
                $check_widgets( $element['elements'] );
            }
        }
    };

    $check_widgets( $elements );

    return $has_social_icons;
}

/**
 * Enqueue social-icons widget CSS if post has the widget
 *
 * @param int $post_id The post ID to check
 */
function solace_enqueue_social_icons_css_if_needed( $post_id ) {
    static $css_enqueued = false;

    // Only enqueue once per page load
    if ( $css_enqueued ) {
        return;
    }

    if ( $post_id && solace_post_has_social_icons_widget( $post_id ) ) {
        wp_enqueue_style(
            'solace-extra-widget-social-icons',
            SOLACE_EXTRA_ASSETS_URL . 'css/widget-social-icons.min.css',
            array(),
            SOLACE_EXTRA_VERSION,
            'all'
        );
        $css_enqueued = true;
    }
}

function solace_display_custom_footer() {
    return solace_render_sitebuilder_part( 'footer' );
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

    // Process the post title update.
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
    // Bail on an invalid nonce. The original condition was inverted -- it fired
    // the failure branch when the nonce was VALID -- and it answered with JSON,
    // which corrupts an ordinary post save because save_post is not an AJAX
    // context. Absent nonce is tolerated: the editor URL does not always carry one.
    if (
        isset($_GET['security']) &&
        ! wp_verify_nonce(sanitize_key(wp_unslash($_GET['security'])), 'template_preview_nonce')
    ) {
        return;
    }

    // Check if this is an autosave or a revision
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the post type
    if (get_post_type($post_id) !== 'solace-sitebuilder') {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if 'part' parameter is set
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (isset($_GET['part'])) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $part = sanitize_key(wp_unslash($_GET['part']));

        // Only the parts the Site Builder actually ships. Without this any value
        // in the URL becomes a taxonomy term, so the editor URL could be used to
        // fill the term table with junk.
        if (!in_array($part, solace_sitebuilder_part_slugs(), true)) {
            return;
        }

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

// function solace_update_sitebuilder_status2() {
//     // Verifikasi nonce
//     if (isset($_GET['security']) && wp_verify_nonce(sanitize_key(wp_unslash($_GET['security'])), 'template_preview_nonce')) {
//         wp_send_json_error('Nonce verification failed.');
//     }

//     // Check if the required parameters are present.
//     if (!isset($_POST['post_id']) || !isset($_POST['status']) || !isset($_POST['part'])) {
//         wp_send_json_error('Invalid data sent.');
//     }

//     $post_id = intval($_POST['post_id']);
//     $part = sanitize_text_field(wp_unslash($_POST['part']) );
//     $status = sanitize_text_field(wp_unslash($_POST['status']) );

//     // Updated status sitebuilder part.
//     if ( $part ) {

//         // Set up the arguments for the custom query.
//         $args = array(
//             'post_type'      => 'solace-sitebuilder',
//             // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
//             'meta_key'       => '_solace_'.$part .'_status',
//             'posts_per_page' => -1,
//         );

//         // Create a new custom query.
//         $query = new WP_Query( $args );

//         // Check if there are any posts found.
//         if ( $query->have_posts() ) {
//             while ( $query->have_posts() ) {
//                 $query->the_post();

//                 // Updated the value of the custom meta key.
//                 update_post_meta( get_the_ID(), '_solace_'.$part.'_status', false );
//             }
//         }

//         // Reset post data after custom query to avoid interfering with other queries.
//         wp_reset_postdata();    

//     }

//     // Update the post meta.
//     if (update_post_meta($post_id, '_solace_'.$part.'_status', $status)) {
//         wp_send_json_success('Status updated.');
//     } else {
//         wp_send_json_error('Failed to update status.');
//     }
// }

function solace_update_sitebuilder_status() {
    // Verify nonce.
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'solace_conditions_nonce_action' ) ) {
        wp_send_json_error( 'Nonce verification failed.' );
    }

    // Check user capability.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized.' );
    }

    if ( !isset($_POST['post_id']) || !isset($_POST['status']) || !isset($_POST['part']) ) {
        wp_send_json_error('Invalid data sent.');
    }

    $post_id = intval($_POST['post_id']);
    $part    = sanitize_key(wp_unslash($_POST['part']));
    $status  = sanitize_text_field(wp_unslash($_POST['status']));

    // The part name is built into a meta key below, so it has to be one we ship.
    if ( ! in_array( $part, solace_sitebuilder_part_slugs(), true ) ) {
        wp_send_json_error( 'Invalid part.' );
    }

    // singleproduct / shopproduct templates each carry their own conditions, so
    // multiple templates may be active at once. Only enforce one-active for the
    // parts that have no conditions (purchase-summary, blog, archive, 404).
    if ( $part && $part !== 'header' && $part !== 'footer' && 'singleproduct' !== $part && 'shopproduct' !== $part ) {

        $args = array(
            'post_type'      => 'solace-sitebuilder',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Required to find active template for selected part.
            'meta_key'       => '_solace_' . $part . '_status',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Required to target currently enabled template.
            'meta_value'     => '1',
            'posts_per_page' => -1,
            // Drafts must be included: a part created as draft still carries
            // status = 1, so leaving them out lets two parts stay active at once.
            'post_status'    => 'any',
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- Current post must be excluded when toggling a single active template.
            'post__not_in'   => array($post_id), 
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                solace_set_part_status( get_the_ID(), $part, false );
            }
        }
        wp_reset_postdata();    
    }

    // Normalise here: the browser may send '1', 1, 'true' or 'on'. Every reader
    // in the plugin compares against the literal strings '1' and '0'.
    solace_set_part_status( $post_id, $part, in_array( $status, array( '1', 'true', 'on' ), true ) );

    wp_send_json_success('Status updated.');
}
add_action('wp_ajax_solace_update_sitebuilder_status', 'solace_update_sitebuilder_status');



function solace_update_sitebuilder_all_status() {
    // Verifikasi nonce
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Bulk action is secured via AJAX nonce below.
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'solace_conditions_nonce_action' ) ) {
        wp_send_json_error( 'Nonce verification failed.' );
    }

    // Check user capability. A valid nonce only proves the request was not
    // forged; it says nothing about who sent it, and this toggles every Site
    // Builder part at once. The single-part handler above already checks this.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized.' );
    }

    // Check if the required parameters are present.
    if ( !isset($_POST['status']) || !isset($_POST['part'])) {
        wp_send_json_error('Invalid data sent.');
    }

    $part = sanitize_key(wp_unslash($_POST['part']));
    $status = (string) sanitize_text_field(wp_unslash($_POST['status']));

    // The part name is built into a meta key below, so it has to be one we
    // ship -- same guard the conditions handlers apply.
    if ( ! in_array( $part, solace_sitebuilder_part_slugs(), true ) ) {
        wp_send_json_error( 'Invalid part.' );
    }

    $posts = get_posts([
        'post_type'      => 'solace-sitebuilder',
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'meta_key'       => '_solace_' . $part . '_status',
        'posts_per_page' => -1,
        // Include drafts so a draft part cannot stay active after a bulk toggle.
        'post_status'    => 'any',
        'fields'         => 'ids'
    ]);

    if ( ! $status ) {
        foreach ($posts as $post_id) {
            solace_set_part_status( $post_id, $part, false );
        }
        wp_send_json_success('Status updated to 0 for all posts.');
    } elseif ($status === '1') {
        foreach ($posts as $post_id) {
            solace_set_part_status( $post_id, $part, true );
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
            <option value="special-shop"' . selected($selected_value, 'special-shop', false) . '>Shop Page</option>
            <option value="product|all"' . selected($selected_value, 'product|all', false) . '>All Products</option>
            <option value="product|all|taxarchive|product_cat"' . selected($selected_value, 'product|all|taxarchive|product_cat', false) . '>All Product Categories Archive</option>
            <option value="product|all|taxarchive|product_tag"' . selected($selected_value, 'product|all|taxarchive|product_tag', false) . '>All Product Tags Archive</option>
        </optgroup>
        ';
    }
    return $options;
}

/**
 * Default display conditions for a part when none were saved.
 *
 * Shop Product Archive templates default to "All Product Archive" and Shop
 * Single Product templates to "All Products". Without this an empty condition
 * list is treated as "applies everywhere" (backward compatibility) and the
 * dashboard shows no Instances for the template.
 *
 * @param string $part Part type (shopproduct, singleproduct, ...).
 * @return array{type:string,value:string}[] Empty for other parts.
 */
function solace_sitebuilder_default_conditions( $part ) {
    if ( 'shopproduct' === $part ) {
        // All Product Archive - the shopproduct template covers every
        // product archive (shop, category, tag, brand) by default.
        return array( array( 'type' => 'include', 'value' => 'product|all|archive' ) );
    }
    if ( 'singleproduct' === $part ) {
        return array( array( 'type' => 'include', 'value' => 'product|all' ) );
    }
    return array();
}

add_action('wp_ajax_save_conditions', 'solace_save_conditions');
add_action('wp_ajax_save_edit_conditions', 'solace_save_edit_conditions');

function solace_save_conditions() {
    if (!isset($_POST['security']) || !wp_verify_nonce(sanitize_key(wp_unslash($_POST['security'])), 'solace_conditions_nonce_action')) {
        wp_send_json_error('Invalid nonce11.');
        return;
    }

    // Check user capability — saving site builder conditions is admin-only.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized.' );
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
    $part = isset($_POST['part']) ? sanitize_key(wp_unslash($_POST['part'])) : '';

    // The part name is built into meta keys below, so it has to be one we ship.
    if (!in_array($part, solace_sitebuilder_part_slugs(), true)) {
        wp_send_json_error('Invalid part.');
        return;
    }

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

    // Only a brand new part gets switched on. Editing conditions on an existing
    // part used to force it back to active, undoing a toggle the user had
    // deliberately turned off.
    if ('add-new' === $from) {
        solace_set_part_status( $post_id, $part, true );
        update_post_meta($post_id, '_solace_template', $part);
    }

    $processed_conditions = array();
    foreach ($conditions as $condition) {
        $condition_type = sanitize_text_field(wp_unslash($condition['type']));
        $condition_value = sanitize_text_field(wp_unslash($condition['value']));

        $processed_conditions[] = array(
            'type'  => $condition_type,
            'value' => $condition_value
        );
    }

    // No conditions saved — fall back to the part's default (All Product
    // Archive / All Products) so the template always has an instance.
    if ( empty( $processed_conditions ) ) {
        $processed_conditions = solace_sitebuilder_default_conditions( $part );
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

    // Check user capability — editing site builder conditions is admin-only.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized.' );
        return;
    }
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized	 --- We need this.
    if (!isset($_POST['post_id']) || !isset($_POST['conditions'])) {
        wp_send_json_error('Invalid post_id / condition.');
        return;
    }
    $part = isset($_POST['part']) ? sanitize_key(wp_unslash($_POST['part'])) : 'header';

    // The part name is built into a meta key below, so it has to be one we ship.
    if (!in_array($part, solace_sitebuilder_part_slugs(), true)) {
        wp_send_json_error('Invalid part.');
        return;
    }

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

    // No conditions saved — fall back to the part's default (All Product
    // Archive / All Products) so the template always has an instance.
    if ( empty( $processed_conditions ) ) {
        $processed_conditions = solace_sitebuilder_default_conditions( $part );
    }

    update_post_meta( $post_id, '_solace_' . $part . '_conditions', $processed_conditions );

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
function solace_extra_create_and_edit_page( $part, $title_prefix ) {
    $part     = sanitize_key( $part );
    $meta_key = '_solace_' . $part . '_status';

    // Query existing parts of this type. Drafts included: a draft part still
    // carries status = 1, so skipping them would leave two parts active at once.
    $args = array(
        'post_type'      => 'solace-sitebuilder',
        'post_status'    => 'any',
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'meta_key'       => $meta_key,
        'posts_per_page' => -1,
    );
    $query = new WP_Query( $args );
    $post_count = count( $query->posts ) + 1;

    // Deactivate every existing part of this type.
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            solace_set_part_status( get_the_ID(), $part, false );
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

    if ( $post_id && ! is_wp_error( $post_id ) ) {
        // Store the PART NAME, never the meta key. The conditions flow already
        // stores the part name, and code that reads _solace_template has to see
        // one format.
        update_post_meta( $post_id, '_solace_template', $part );

        solace_set_part_status( $post_id, $part, true );

        // New Shop Product Archive / Shop Single Product templates get their
        // default display condition so Instances are never empty.
        $defaults = solace_sitebuilder_default_conditions( $part );
        if ( ! empty( $defaults ) && ! metadata_exists( 'post', $post_id, '_solace_' . $part . '_conditions' ) ) {
            update_post_meta( $post_id, '_solace_' . $part . '_conditions', $defaults );
        }

        wp_send_json_success( array(
            'post_id'    => $post_id,
            'post_title' => esc_html( $title_prefix . $post_count ),
        ) );
    } else {
        wp_send_json_error( 'Failed to create new post' );
    }
}

/**
 * One-time backfill: give existing Shop Product Archive / Shop Single Product
 * templates without any saved condition their default instance.
 *
 * @return void
 */
function solace_sitebuilder_backfill_default_conditions() {
    if ( get_option( 'solace_sitebuilder_conditions_backfill_v1' ) ) {
        return;
    }

    foreach ( array( 'shopproduct' => 'product|all|archive', 'singleproduct' => 'product|all' ) as $part => $value ) {
        $posts = get_posts(
            array(
                'post_type'      => 'solace-sitebuilder',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'no_found_rows'  => true,
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Backfill active parts only.
                'meta_key'       => '_solace_' . $part . '_status',
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Backfill active parts only.
                'meta_value'     => '1',
                'fields'         => 'ids',
            )
        );

        foreach ( (array) $posts as $post_id ) {
            $existing = get_post_meta( $post_id, '_solace_' . $part . '_conditions', true );
            if ( ! is_array( $existing ) || empty( $existing ) ) {
                update_post_meta(
                    $post_id,
                    '_solace_' . $part . '_conditions',
                    array( array( 'type' => 'include', 'value' => $value ) )
                );
            }
        }
    }

    update_option( 'solace_sitebuilder_conditions_backfill_v1', 1 );
}
add_action( 'admin_init', 'solace_sitebuilder_backfill_default_conditions' );

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

    // Keys are the part slugs; the status meta key is always '_solace_{part}_status'.
    $types = array(
        'singleproduct'    => array( 'title_prefix' => 'Single Product #' ),
        'shopproduct'      => array( 'title_prefix' => 'Shop #' ),
        'purchase-summary' => array( 'title_prefix' => 'Purchase Summary #' ),
        'blogsinglepost'   => array( 'title_prefix' => 'Single Post #' ),
        'blogarchive'      => array( 'title_prefix' => 'Blog Archive #' ),
        '404'              => array( 'title_prefix' => '404 #' ),
        'cart'             => array( 'title_prefix' => 'Cart #' ),
        'checkout'         => array( 'title_prefix' => 'Checkout #' ),
        'myaccount'        => array( 'title_prefix' => 'My Account #' ),
    );

    // Check if provided type is valid
    if ( empty( $type ) || ! array_key_exists( $type, $types ) ) {
        wp_send_json_error( array( 'error' => 'Invalid type provided', 'allowed_types' => array_keys( $types ) ) );
    }    

    $title_prefix = $types[ $type ]['title_prefix'];

    solace_extra_create_and_edit_page( $type, $title_prefix );
}
add_action( 'wp_ajax_create_and_edit_page', 'solace_extra_create_and_edit_page_ajax_handler' );


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

    // The delete below is a force delete, so a wrong ID destroys a page, a
    // product or an order with no way back. Confirm the target really is a Site
    // Builder part, and that this user may delete that particular one, before
    // touching it. manage_options above says nothing about either.
    if ( 'solace-sitebuilder' !== get_post_type( $id ) ) {
        wp_send_json_error( array( 'error' => 'Invalid target.' ) );
    }

    if ( ! current_user_can( 'delete_post', $id ) ) {
        wp_send_json_error( array( 'error' => 'Unauthorized' ) );
    }

    // Force delete, bypassing the trash: the confirmation dialog this comes
    // from tells the user the item is deleted permanently.
    $result = wp_delete_post( $id, true );

    if ( $result ) {
        wp_send_json_success( array( 'message' => 'Item deleted successfully.' ) );
    } else {
        wp_send_json_error( array( 'error' => 'Failed to delete item.' ) );
    }
}
add_action('wp_ajax_delete_action', 'solace_extra_delete_action_ajax_handler');

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
        case 'special-shop':
            return 'Shop Page';

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
            return 'All Product Archives';
        case 'product|all|taxarchive|product_cat':
            return 'All Product Categories Archive';
        case 'product|all|taxarchive|product_tag':
            return 'All Product Tags Archive';
        case 'product|all|taxarchive|product_shipping_class':
            return 'All Product Shipping Classes Archive';

        // Products Archive terms (Shop Product Archive part — Elementor-style)
        case 'product|shop':
            return 'Shop Page';
        case 'product|search':
            return 'Search Results';

        // All <Type> archives (Shop Product Archive part — bare term type)
        case 'product|brand':
            return 'All Brands';
        case 'product|brandchild':
            return 'All Child Brands';
        case 'product|cat':
            return 'All Categories';
        case 'product|catchild':
            return 'All Child Categories';
        case 'product|tag':
            return 'All Tags';
        default:
            if ( 0 === strpos( $value, 'product|product|' ) ) {
                $product_id = absint( substr( $value, strlen( 'product|product|' ) ) );
                $product    = $product_id ? wc_get_product( $product_id ) : null;
                if ( $product ) {
                    return sprintf( 'In Product: %s', $product->get_name() );
                }
            }
            if ( 0 === strpos( $value, 'product|brandchild|' ) ) {
                $term_id = absint( substr( $value, strlen( 'product|brandchild|' ) ) );
                $term    = $term_id ? get_term( $term_id, 'product_brand' ) : null;
                if ( $term && ! is_wp_error( $term ) ) {
                    return sprintf( 'In Child Product Brand: %s', $term->name );
                }
            }
            if ( 0 === strpos( $value, 'product|brand|' ) ) {
                $term_id = absint( substr( $value, strlen( 'product|brand|' ) ) );
                $term    = $term_id ? get_term( $term_id, 'product_brand' ) : null;
                if ( $term && ! is_wp_error( $term ) ) {
                    return sprintf( 'In Brand: %s', $term->name );
                }
            }
            if ( 0 === strpos( $value, 'product|catchild|' ) ) {
                $term_id = absint( substr( $value, strlen( 'product|catchild|' ) ) );
                $term    = $term_id ? get_term( $term_id, 'product_cat' ) : null;
                if ( $term && ! is_wp_error( $term ) ) {
                    return sprintf( 'In Child Product Category: %s', $term->name );
                }
            }
            if ( 0 === strpos( $value, 'product|cat|' ) ) {
                $term_id = absint( substr( $value, strlen( 'product|cat|' ) ) );
                $term    = $term_id ? get_term( $term_id, 'product_cat' ) : null;
                if ( $term && ! is_wp_error( $term ) ) {
                    return sprintf( 'In Category: %s', $term->name );
                }
            }
            if ( 0 === strpos( $value, 'product|tag|' ) ) {
                $term_id = absint( substr( $value, strlen( 'product|tag|' ) ) );
                $term    = $term_id ? get_term( $term_id, 'product_tag' ) : null;
                if ( $term && ! is_wp_error( $term ) ) {
                    return sprintf( 'In Tag: %s', $term->name );
                }
            }
            if ( 0 === strpos( $value, 'product|author|' ) ) {
                $author_id = absint( substr( $value, strlen( 'product|author|' ) ) );
                $author    = $author_id ? get_userdata( $author_id ) : null;
                if ( $author ) {
                    return sprintf( 'Product by Author: %s', $author->display_name );
                }
            }
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

// The wp_ajax_check_elementor_status endpoint was removed here. It checked
// neither a nonce nor a capability, so any authenticated user -- a Subscriber
// included -- could ask it whether Elementor was active, which is exactly the
// reconnaissance an attacker wants before trying a known plugin CVE.
//
// It had no caller. The only reference left was a commented-out $.ajax block in
// admin/js/solace-extra-admin.js, replaced long ago by a plain redirect to the
// Site Builder page. Deleting the endpoint closes the leak outright, which
// beats adding checks to something nothing calls.


function solace_get_part_status($part) {
    // Cart, checkout and account have no artwork of their own yet, so their cards
    // borrow the Purchase Summary set until the design team supplies one.
    $icon_fallbacks = array( 'cart' => 'purchase-summary', 'checkout' => 'purchase-summary', 'myaccount' => 'purchase-summary' );
    $icon = isset( $icon_fallbacks[ $part ] ) ? $icon_fallbacks[ $part ] : $part;

    $is_checked = false;
    $image = $icon . '.svg'; // Default image
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
            'image' => $icon . '.svg', // Default image
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
        $image = $icon . '-active.svg'; 
        $lock_class = ''; 
        $is_checked = true;
    } elseif ($found_value_0) {
        $image = $icon . '-dark.svg'; 
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



/**
 * Drop the back-compat emoji-styles hook that admin-side rendering leaves armed.
 *
 * Core registers print_emoji_styles on both wp_print_styles and
 * admin_print_styles, and wp_enqueue_emoji_styles() only unhooks the one
 * matching is_admin(). Anything that renders front-end markup from
 * admin-ajax.php therefore reaches wp_head() with the wp_print_styles copy
 * still armed, calls the deprecated function, and writes a notice to
 * debug.log on every single request.
 */
function solace_sitebuilder_unhook_stale_emoji_styles() {
    if ( is_admin() ) {
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
    }
}

/**
 * Answer a Site Builder preview request with a real 404, using the theme.
 *
 * Left to itself admin-ajax.php answers an action it has no nopriv handler for
 * with a bare "0" and HTTP 400, which reads as a broken page rather than a
 * closed door. Routing the logged-out case here returns the honest status: the
 * preview URL is not public. It says "not found" rather than "forbidden" on
 * purpose, so the response never confirms whether the requested part exists.
 *
 * admin-ajax.php never runs the template hierarchy, so the theme's 404.php has
 * to be invoked by hand -- and the main query has to be told it is a 404 first,
 * or is_404() reads false inside the template and the header/footer parts
 * resolve against the wrong page type. If the theme has no 404 template, or
 * rendering it throws, we fall back to a plain body: a broken 404 page must
 * never turn into a 200 that leaks markup.
 */
function solace_sitebuilder_preview_404() {
    if ( ! headers_sent() ) {
        status_header( 404 );
        nocache_headers();
    }

    $solace_extra_output = '';
    $solace_extra_template = function_exists( 'get_404_template' ) ? get_404_template() : '';

    if ( $solace_extra_template && is_readable( $solace_extra_template ) ) {
        global $wp_query;
        if ( $wp_query instanceof WP_Query ) {
            $wp_query->set_404();
        }

        solace_sitebuilder_unhook_stale_emoji_styles();

        try {
            ob_start();
            load_template( $solace_extra_template, false );
            $solace_extra_output = trim( (string) ob_get_clean() );
        } catch ( Throwable $solace_extra_e ) {
            if ( ob_get_level() ) {
                ob_end_clean();
            }
            $solace_extra_output = '';
        }
    }

    if ( '' !== $solace_extra_output ) {
        echo $solace_extra_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered theme template.
        die();
    }

    wp_die(
        '<h1>' . esc_html__('Not Found', 'solace-extra') . '</h1><p>' . esc_html__('The requested content could not be found.', 'solace-extra') . '</p>',
        esc_html__('Not Found', 'solace-extra'),
        array('response' => 404)
    );
}


// Logged-in only. Every caller of this preview URL sits on the Site Builder
// dashboard, which is itself behind manage_options, so there is no logged-out
// consumer to serve -- and a published part is a template, not public content.
// Issue #3: registered for logged-in users only. The nopriv variant used to
// point at the 404 renderer — harmless, but the PDF asks for every nopriv
// registration to be removed so the attack surface is closed, not just fenced.
add_action('wp_ajax_get_elementor_content', 'solace_get_elementor_content_via_ajax');

function solace_get_elementor_content_via_ajax() {
    if (!did_action('elementor/loaded')) {
        wp_die(esc_html__('Elementor is not active. Please activate Elementor to view this content.', 'solace-extra'));
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $post_id = isset($_GET['post_id']) ? absint(sanitize_text_field(wp_unslash($_GET['post_id']))) : 0;

    if (!$post_id) {
        solace_sitebuilder_preview_404();
    }

    $solace_extra_post = get_post($post_id);
    if (!$solace_extra_post || $solace_extra_post->post_type !== 'solace-sitebuilder') {
        solace_sitebuilder_preview_404();
    }

    // Previewing a part is an editorial action, whatever its post status.
    if (!current_user_can('edit_posts')) {
        solace_sitebuilder_preview_404();
    }

    // Draft, pending, private, scheduled and trashed parts are only rendered for
    // a user who can edit that exact part. No nonce: this is a read-only GET that
    // changes nothing, and the nonce it used to require was never issued by any
    // caller, so the check only ever turned admins away from their own drafts.
    if ('publish' !== $solace_extra_post->post_status && !current_user_can('edit_post', $post_id)) {
        solace_sitebuilder_preview_404();
    }

    // to the live frontend. Register the filter manually here.
    $solace_extra_kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();
    if ( ! has_filter( 'body_class', [ \Elementor\Plugin::$instance->frontend, 'body_class' ] ) ) {
        add_filter( 'body_class', [ \Elementor\Plugin::$instance->frontend, 'body_class' ] );
    }

    // AJAX context.
    add_filter( 'body_class', function( $classes ) use ( $solace_extra_kit_id ) {
        if ( $solace_extra_kit_id ) {
            $kit_class = 'elementor-kit-' . $solace_extra_kit_id;
            if ( ! in_array( $kit_class, $classes, true ) ) {
                $classes[] = $kit_class;
            }
        }
        foreach ( [ 'elementor-default', 'elementor-page' ] as $extra_class ) {
            if ( ! in_array( $extra_class, $classes, true ) ) {
                $classes[] = $extra_class;
            }
        }
        return $classes;
    });

    // Use `get_builder_content_for_display()` so all CSS/fonts/inline styles
    // for this post are registered for output during `wp_head()`.
    $elementor_content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $post_id );

    // Mirror the front-end atomic styles bridge so the AJAX preview gets the
    // same asset pipeline (atomic styles, runtime elements, per-post CSS) as a
    // natively rendered singular page.
    if ( class_exists( 'Solace_Extra_Public' ) ) {
        $solace_extra_public = new Solace_Extra_Public(
            'solace-extra',
            defined( 'SOLACE_EXTRA_VERSION' ) ? SOLACE_EXTRA_VERSION : '1.0.0'
        );
        $solace_extra_public->bridge_atomic_assets_for_post_ids( array( $post_id ) );
    }

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Preview</title>
        <?php
        \Elementor\Plugin::$instance->frontend->enqueue_styles();
        \Elementor\Plugin::$instance->frontend->enqueue_scripts();

        // Explicitly enqueue the active Kit CSS file (`post-{kit_id}.css`) so
        // global button/typography styles load on the preview.
        if ( $solace_extra_kit_id ) {
            $kit_css_file = \Elementor\Core\Files\CSS\Post::create( $solace_extra_kit_id );
            $kit_css_file->enqueue();
        }

        wp_enqueue_style('solace-theme', get_template_directory_uri() . '/assets-solace/css/theme.min.css', array(), SOLACE_EXTRA_VERSION);
        wp_enqueue_style(
            'solace-widget-nav-menu',
            plugin_dir_url(__FILE__) . '../assets/css/widget-nav-menu.min.css',
            array(),
            SOLACE_EXTRA_VERSION
        );
        wp_enqueue_style(
            'solace-widget-icon-list',
            plugin_dir_url(__FILE__) . '../assets/css/widget-icon-list.min.css',
            array(),
            SOLACE_EXTRA_VERSION
        );

        solace_sitebuilder_unhook_stale_emoji_styles();
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
    <body <?php body_class(); ?>>
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










add_action( 'wp_head', 'solace_render_custom_styles', 99 );

/**
 * Front-end corrections printed in wp_head.
 *
 * body.solace_elementor_header_footer is added by Solace Extra Pro when a Single
 * Product or Shop Product Archive part uses the Elementor "Header & Footer" page
 * template, so the two rules naming it are live.
 *
 * The body.solace_elementor_canvas selectors that used to sit beside them were
 * not: nothing in the theme or in either plugin ever added that class. Pro
 * renders canvas templates without calling get_header()/get_footer() at all, so
 * there is no theme container left to widen. They were removed together with
 * solace_custom_body_class(), which was written to add both classes and was
 * never hooked to body_class -- kept out of the <style> block itself so the
 * explanation is not shipped to every visitor.
 *
 * @return void
 */
function solace_render_custom_styles() {
    ?>
    <style type="text/css">
        body.solace_elementor_header_footer .container:not(.header .container):not(.site-footer .container) {
            max-width: none !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin: 0 !important;
        }

        body.solace_elementor_header_footer .elementor {
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



/**
 * The part that will actually render for this request.
 *
 * This decides which template's layout the container CSS below has to match,
 * so it has to agree with whatever renders the page. Solace Extra Pro owns
 * those templates, and where it exposes its resolver that resolver is used --
 * it is the same call its own template files make, conditions and all.
 *
 * The fallback covers Pro being absent or not exposing one. It replaces a raw
 * postmeta query that asked only for meta_value = '1' with no join and no
 * order: that query could return a draft or trashed part, a row left behind by
 * a deleted post, or -- with several parts switched on -- a different one from
 * request to request. Published solace-sitebuilder posts only, newest first,
 * matching the order Pro's own resolvers use.
 *
 * @since 1.7.3
 * @param string $part Part slug, e.g. 'singleproduct'.
 * @return int Post ID, or 0 when nothing applies.
 */
function solace_sitebuilder_active_part_id( $part ) {
    $resolver = 'solace_extra_pro_resolve_' . str_replace( '-', '_', $part ) . '_post_id';

    if ( function_exists( $resolver ) ) {
        return (int) call_user_func( $resolver );
    }

    $posts = get_posts(
        array(
            'post_type'      => 'solace-sitebuilder',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'no_found_rows'  => true,
            'orderby'        => 'ID',
            'order'          => 'DESC',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Active part lookup.
            'meta_key'       => '_solace_' . $part . '_status',
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Active part lookup.
            'meta_value'     => '1',
            'fields'         => 'ids',
        )
    );

    return empty( $posts ) ? 0 : (int) $posts[0];
}

function solace_override_container_var() {

    if ( is_admin() ) {
        return;
    }

    $checks = [];

    if ( is_singular('product') ) {
        $checks[] = 'singleproduct';
    }

    if ( is_singular('post') ) {
        $checks[] = 'blogsinglepost';
    }

    if ( empty($checks) ) {
        return;
    }

    // handle CSS
    // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
    wp_register_style( 'solace-container-layout', false );
    wp_enqueue_style( 'solace-container-layout' );

    foreach ( $checks as $part ) {

        $template_id = solace_sitebuilder_active_part_id( $part );

        if ( ! $template_id ) {
            continue;
        }

        $layout = get_post_meta( $template_id, '_wp_page_template', true );

        if ( $layout === 'elementor_canvas' ) {
            $layout_slug = 'canvas';
        } elseif ( $layout === 'elementor_full_width' ) {
            $layout_slug = 'fullwidth';
        } else {
            $layout_slug = 'default';
        }

        $page_type = $part;

        add_filter('body_class', function ($classes) use ($layout_slug, $page_type) {
            $classes[] = 'solace-elementor-pagelayout-' . $page_type . '-' . $layout_slug;
            return $classes;
        });


        if ( $layout_slug === 'canvas' ) {

            add_action('wp_head', function() {
                echo '<style id="solace-canvas-hide">.solace-custom-header-content, .solace-custom-footer-content { display: none !important; }</style>';
            }, 1); 

            wp_add_inline_style(
                'solace-container-layout',
                'body .solace-custom-header-content,
                body .solace-custom-footer-content {
                    display: none !important;
                }'
            );

            continue;
        }

        if ( ! in_array( $layout, ['elementor_canvas', 'elementor_full_width', 'elementor_theme'] ) ) {
            continue;
        }

        $selector = '.elementor.elementor-' . $template_id;

        $container_list_layout = get_theme_mod( 'solace_container_layout', 'custom' );

        $css = '';


        if ( $container_list_layout === 'fullwidth' ) {

            $css = "{$selector} { max-width: 100%; width: 100%; }";
            // Braces are legal inside a custom property, so this declaration was
            // never dropped -- but every var(--container) using it then resolved
            // to "{100%}", which is invalid where it lands, so those properties
            // fell back to their initial value (max-width: none). Full width
            // came out right by accident; say it properly instead.
            $css .= ":root { --container: 100%; }";
            $css .= "body.single-product .container.shop-container { 
                max-width: 100%; 
                width: 100%; 
                margin: 0 auto; 
                padding-left: 0;
                padding-right: 0;
            }";

        } elseif ( $container_list_layout === 'boxed' ) {

            $css = "{$selector} { max-width: 708px; width: 100%; margin: 0 auto; }";
            $css .= "body.single-product .container.shop-container { 
                max-width: 708px; 
                width: 100%; 
                margin: 0 auto; 
            }";

        } elseif ( $container_list_layout === 'left' ) {

            $css = "{$selector} { max-width: 1280px; width: 100%; }";

        } elseif ( $container_list_layout === 'right' ) {

            $css = "{$selector} { max-width: 1280px; width: 100%; }";

        }

        else {

            $container_custom_layout_width = get_theme_mod(
                'solace_container_width',
                '{ "mobile": 748, "tablet": 992, "desktop": 1280 }'
            );

            // error_log('[Solace] RAW customizer value: ' . $container_custom_layout_width);

            $arrayDataCustom = json_decode( $container_custom_layout_width, true );

            // error_log('[Solace] Decoded value: ' . print_r($arrayDataCustom, true));

            $desktop = intval($arrayDataCustom['desktop'] ?? 1280);
            $tablet  = intval($arrayDataCustom['tablet']  ?? 992);
            $mobile  = intval($arrayDataCustom['mobile']  ?? 748);

            // error_log('[Solace] Desktop: ' . $desktop);
            // error_log('[Solace] Tablet: ' . $tablet);
            // error_log('[Solace] Mobile: ' . $mobile);

            $css  = "{$selector} { 
                        max-width: {$desktop}px; 
                        width: 100%; 
                        margin: 0 auto;
                    }";

            $css .= "@media (max-width: 992px) {
                        {$selector} { max-width: {$tablet}px; }
                    }";

            $css .= "@media (max-width: 748px) {
                        {$selector} { max-width: {$mobile}px; }
                    }";
        }

        // error_log('[Solace] layout_slug: ' . $layout_slug);
        // error_log('[Solace] selector: ' . $selector);
        // error_log('[Solace] CSS: ' . $css);

        wp_add_inline_style( 'solace-container-layout', $css );
    }
}
add_action( 'wp_enqueue_scripts', 'solace_override_container_var', 99 );
/**
 * Explain, on WooCommerce's Page setup screen, what Site Builder is doing to the
 * cart, checkout and account pages.
 *
 * Those pages can end up with two designs: the page's own, edited in Elementor
 * from Pages, and a Site Builder part that replaces it. Nothing on either says
 * which one is showing, so a store owner can edit the page and see no change, or
 * switch a part on and still see the page. This puts the answer under each page
 * selector -- the only place a store owner configures these pages.
 *
 * Admin screen only: the same settings array feeds WooCommerce's REST settings
 * API, which should not carry this HTML.
 */
add_filter( 'woocommerce_settings_pages', 'solace_woo_page_setup_part_notices' );

function solace_woo_page_setup_part_notices( $settings ) {
    if ( ! is_array( $settings ) || ! is_admin() || wp_doing_ajax() || ! function_exists( 'solace_woo_page_parts_enabled' ) || ! solace_woo_page_parts_enabled() ) {
        return $settings;
    }

    $fields = array(
        'woocommerce_cart_page_id'      => 'cart',
        'woocommerce_checkout_page_id'  => 'checkout',
        'woocommerce_myaccount_page_id' => 'myaccount',
    );

    foreach ( $settings as $key => $field ) {
        if ( ! is_array( $field ) || empty( $field['id'] ) || ! isset( $fields[ $field['id'] ] ) ) {
            continue;
        }

        $notice = solace_woo_page_part_notice( $fields[ $field['id'] ] );

        if ( '' === $notice ) {
            continue;
        }

        $notice = sprintf( '<span class="solace-page-part-notice" data-field="%1$s">%2$s</span>', esc_attr( $field['id'] ), $notice );

        // While a part is replacing a published page, lock the selector: the page
        // is only the address the part is shown at, and picking another one here
        // is how a store owner ends up believing the part has been undone. This
        // is safe to save around -- a disabled select is not submitted, WooCommerce
        // then sees a null value and skips the option, so the page stays set.
        // A page that is missing or unpublished is left selectable, so a broken
        // setup can still be fixed from this screen.
        if ( solace_woo_page_part_locks_page( $fields[ $field['id'] ] ) ) {
            $attributes = isset( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ? $field['custom_attributes'] : array();

            $attributes['disabled'] = 'disabled';
            $settings[ $key ]['custom_attributes'] = $attributes;
            $settings[ $key ]['row_class']         = trim( ( isset( $field['row_class'] ) ? $field['row_class'] : '' ) . ' solace-page-locked' );
        }

        $original = isset( $field['desc'] ) ? $field['desc'] : '';

        // WooCommerce shows these fields' own text as a tooltip (desc_tip => true).
        // Keep that tooltip, and use desc for the notice so it renders as visible
        // text under the selector.
        if ( isset( $field['desc_tip'] ) && true === $field['desc_tip'] ) {
            $settings[ $key ]['desc_tip'] = $original;
            $settings[ $key ]['desc']     = $notice;
        } else {
            $settings[ $key ]['desc'] = '' !== $original ? $original . '<br>' . $notice : $notice;
        }
    }

    return $settings;
}

/**
 * Whether the Page setup selector for a WooCommerce page should be locked.
 *
 * True exactly when the notice says Site Builder is replacing the page: Pro can
 * apply the part, the part is in use, and the selected page is published.
 *
 * @param string $part 'cart', 'checkout' or 'myaccount'.
 * @return bool
 */
function solace_woo_page_part_locks_page( $part ) {
    if ( ! class_exists( 'Solace_Extra_Pro_Public' ) || ! method_exists( 'Solace_Extra_Pro_Public', 'override_' . $part . '_template' ) ) {
        return false;
    }

    if ( ! (int) solace_get_woo_page_part_id( $part ) ) {
        return false;
    }

    $page_id = (int) wc_get_page_id( $part );

    return $page_id > 0 && 'publish' === get_post_status( $page_id );
}

/**
 * The Page setup notice for one WooCommerce page, or '' when there is nothing to say.
 *
 * Silent when no part of the type is switched on -- the page then shows its own
 * design, which is what anyone would expect -- and when Solace Extra Pro has no
 * override for the type, because then no part can replace the page whatever its
 * status says.
 *
 * @param string $part 'cart', 'checkout' or 'myaccount'.
 * @return string HTML, passed through wp_kses_post() by WooCommerce.
 */
function solace_woo_page_part_notice( $part ) {
    $labels = array(
        'cart'      => __( 'Cart', 'solace-extra' ),
        'checkout'  => __( 'Checkout', 'solace-extra' ),
        'myaccount' => __( 'My Account', 'solace-extra' ),
    );

    if ( ! isset( $labels[ $part ] ) ) {
        return '';
    }

    // While the part types are switched off the resolver answers 0 for every
    // part, which the reasons below would misread as a missing widget.
    if ( ! function_exists( 'solace_woo_page_parts_enabled' ) || ! solace_woo_page_parts_enabled() ) {
        return '';
    }

    if ( ! class_exists( 'Solace_Extra_Pro_Public' ) || ! method_exists( 'Solace_Extra_Pro_Public', 'override_' . $part . '_template' ) ) {
        return '';
    }

    $switched_on = function ( $statuses ) use ( $part ) {
        $ids = get_posts( array(
            'post_type'      => 'solace-sitebuilder',
            'post_status'    => $statuses,
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key, WordPress.DB.SlowDBQuery.slow_db_query_meta_value
            'meta_key'       => '_solace_' . $part . '_status',
            'meta_value'     => '1',
            'posts_per_page' => 1,
            'orderby'        => 'ID',
            'order'          => 'DESC',
            'fields'         => 'ids',
            'no_found_rows'  => true,
        ) );

        return $ids ? (int) $ids[0] : 0;
    };

    $label     = $labels[ $part ];
    $part_link = function ( $post_id ) {
        return sprintf(
            '<a href="%1$s"><strong>%2$s</strong></a>',
            esc_url( admin_url( 'post.php?post=' . $post_id . '&action=elementor' ) ),
            esc_html( get_the_title( $post_id ) )
        );
    };
    $site_builder_link = sprintf(
        '<a href="%1$s">%2$s</a>',
        esc_url( admin_url( 'admin.php?page=dashboard-sitebuilder&part=' . $part ) ),
        esc_html__( 'Site Builder', 'solace-extra' )
    );

    $in_use = (int) solace_get_woo_page_part_id( $part );

    if ( $in_use ) {
        // The part replaces whichever page is saved here, so what it is doing
        // depends on that page being set and published.
        $page_id = (int) wc_get_page_id( $part );

        if ( $page_id <= 0 || ! get_post( $page_id ) ) {
            /* translators: %s: linked part title. */
            return sprintf( __( 'Site Builder part %s is on, but no page is selected.', 'solace-extra' ), $part_link( $in_use ) );
        }

        if ( 'publish' !== get_post_status( $page_id ) ) {
            /* translators: %s: linked part title. */
            return sprintf( __( 'Site Builder part %s is on, but this page is not published.', 'solace-extra' ), $part_link( $in_use ) );
        }

        /* translators: 1: linked part title, 2: link to Site Builder. */
        return sprintf( __( 'Using Site Builder part %1$s. Turn it off in %2$s to change this page.', 'solace-extra' ), $part_link( $in_use ), $site_builder_link );
    }

    // Mirror solace_get_active_part_id(): the newest published, switched-on part is
    // the one that would be used, so explain why that one is not.
    $published = $switched_on( 'publish' );

    if ( ! $published ) {
        $post_id = $switched_on( array( 'draft', 'pending', 'private', 'future' ) );

        if ( ! $post_id ) {
            return '';
        }

        $reason = __( 'not published', 'solace-extra' );
    } elseif ( ! solace_part_is_renderable( $published ) ) {
        $post_id = $published;
        $reason  = __( 'no design', 'solace-extra' );
    } else {
        $post_id = $published;
        /* translators: %s: page type, e.g. Cart. */
        $reason  = sprintf( __( 'no %s widget', 'solace-extra' ), $label );
    }

    /* translators: 1: linked part title, 2: short reason, e.g. "not published". */
    return sprintf( __( 'Site Builder part %1$s is not used: %2$s.', 'solace-extra' ), $part_link( $post_id ), esc_html( $reason ) );
}

/**
 * Keep the Page setup notices honest while the store owner is choosing a page.
 *
 * The notices describe the page that is saved. The moment a different page is
 * picked in the dropdown they describe the wrong one, and until the form is saved
 * the server cannot say anything about the new choice. So say that, and put the
 * original notice back if the saved page is picked again.
 */
add_action( 'admin_footer', 'solace_woo_page_setup_notice_script' );

function solace_woo_page_setup_notice_script() {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

    if ( ! $screen || 'woocommerce_page_wc-settings' !== $screen->id ) {
        return;
    }

    // phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only screen routing.
    $tab     = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : '';
    $section = isset( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : '';
    // phpcs:enable

    if ( 'advanced' !== $tab || '' !== $section ) {
        return;
    }

    if ( ! function_exists( 'solace_woo_page_parts_enabled' ) || ! solace_woo_page_parts_enabled() ) {
        return;
    }
    ?>
    <style>
        /* selectWoo's own disabled state is a faint grey that reads as a normal
           field; make a locked page selector look locked. WooCommerce renders
           row_class as wc-settings-row-{class}; the bare class covers older
           versions that print it as given. */
        tr.wc-settings-row-solace-page-locked .select2-container--disabled .select2-selection--single,
        tr.solace-page-locked .select2-container--disabled .select2-selection--single {
            background: #f0f0f1;
            border-color: #dcdcde;
            cursor: not-allowed;
        }
        tr.wc-settings-row-solace-page-locked .select2-container--disabled .select2-selection__rendered,
        tr.solace-page-locked .select2-container--disabled .select2-selection__rendered {
            color: #8c8f94;
        }
        tr.wc-settings-row-solace-page-locked .select2-container--disabled .select2-selection__rendered::before,
        tr.solace-page-locked .select2-container--disabled .select2-selection__rendered::before {
            content: "\f160";
            font-family: dashicons;
            margin-right: 6px;
            vertical-align: -2px;
        }
        tr.wc-settings-row-solace-page-locked .select2-container--disabled .select2-selection__arrow,
        tr.solace-page-locked .select2-container--disabled .select2-selection__arrow {
            display: none;
        }
    </style>
    <script>
    jQuery( function ( $ ) {
        var pending = <?php echo wp_json_encode( __( 'Save to update the Site Builder status.', 'solace-extra' ) ); ?>;

        $( '.solace-page-part-notice' ).each( function () {
            var $notice  = $( this );
            var $select  = $( '#' + $notice.data( 'field' ) );
            var saved    = String( $select.val() || '' );
            var original = $notice.html();

            $select.on( 'change', function () {
                if ( String( $select.val() || '' ) === saved ) {
                    $notice.html( original );
                } else {
                    $notice.text( pending );
                }
            } );
        } );
    } );
    </script>
    <?php
}
