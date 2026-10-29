<?php
/**
 * Solace Extra — WooCommerce page options integration for Site Builder parts.
 *
 * Integrates WooCommerce > Settings > Advanced > Page setup (Cart / Checkout /
 * My Account page) with the Solace Site Builder templates:
 *  - dropdowns show all "(Solace Sitebuilder)" templates (active or not),
 *  - the stored option is the template ID when selected, and Elementor Site
 *    Settings (same option) can resolve and search the templates,
 *  - selecting a template turns its switch on; choosing any other page turns
 *    it off; unchanged saves never touch the switches,
 *  - "WooCommerce Settings takes priority": when a real page is selected the
 *    part is skipped (front-end and Site Builder dashboard notices),
 *  - the dashboard lock and "Elementor Site Settings conflict" notices are
 *    replaced with the priority / skip message,
 *  - an empty Cart/Checkout/My Account page falls back to its base page at
 *    runtime, so the store keeps working (section 14).
 *
 * Marker `solace_extra_woo_integration_marker()` lets older standalone
 * mu-plugin copies detect this integration and stand down automatically.
 */
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce settings field ID => Site Builder part slug.
 *
 * @return array
 */
function solace_extra_woo_field_map() {
	return array(
		'woocommerce_cart_page_id'      => 'cart',
		'woocommerce_checkout_page_id'  => 'checkout',
		'woocommerce_myaccount_page_id' => 'myaccount',
		'woocommerce_shop_page_id'      => 'shopproduct',
	);
}

/**
 * Part types offered as options per WooCommerce page field.
 *
 * @return array Field ID => part slugs.
 */
function solace_extra_woo_field_part_types() {
	return array(
		'woocommerce_cart_page_id'      => array( 'cart' ),
		'woocommerce_checkout_page_id'  => array( 'checkout' ),
		'woocommerce_myaccount_page_id' => array( 'myaccount' ),
		'woocommerce_shop_page_id'      => array( 'shopproduct' ),
	);
}

/**
 * Option label for a Site Builder part in the WooCommerce page selectors.
 *
 * @param int|WP_Post $item Part post.
 * @return string
 */
function solace_extra_woo_part_option_label( $item ) {
	$label = get_the_title( $item );
	if ( false === stripos( $label, 'Solace Sitebuilder' ) ) {
		$label .= ' (Solace Sitebuilder)';
	}
	return $label;
}

/**
 * The real page the shop archive lives at while a Shop part is selected as the
 * Shop page.
 *
 * WooCommerce derives the product archive address (/shop/) from the Shop page.
 * When the option stores a Shop part (CPT `solace-sitebuilder`), the archive
 * keeps using the page that was the Shop page before the part was chosen.
 *
 * @return int Page ID, or 0.
 */
function solace_extra_woo_shop_base_page_id() {
	$base = absint( get_option( 'solace_extra_woo_shop_base_page', 0 ) );

	if ( $base && 'page' === get_post_type( $base ) && 'publish' === get_post_status( $base ) ) {
		return $base;
	}

	$page = get_page_by_path( 'shop' );

	return ( $page && 'publish' === $page->post_status ) ? (int) $page->ID : 0;
}

/**
 * wc_get_page_id( 'shop' ): resolve a selected Shop part to its base page, so
 * the product archive, is_shop() and the shop permalink keep working.
 * WooCommerce Settings still reads the raw option and shows the part.
 */
add_filter( 'woocommerce_get_shop_page_id', 'solace_extra_woo_resolve_shop_page_id' );

function solace_extra_woo_resolve_shop_page_id( $page_id ) {
	$page_id = (int) $page_id;

	if ( $page_id <= 0 || 'solace-sitebuilder' !== get_post_type( $page_id ) ) {
		return $page_id;
	}

	$base = solace_extra_woo_shop_base_page_id();

	return $base ? $base : $page_id;
}

/**
 * The Site Builder parts relevant to the WooCommerce pages.
 *
 * @return string[]
 */
function solace_extra_woo_part_slugs() {
	return array( 'cart', 'checkout', 'myaccount', 'purchase-summary' );
}

/**
 * All Site Builder posts of one part type (regardless of active status).
 *
 * @param string $part cart|checkout|myaccount|purchase-summary.
 * @return WP_Post[]
 */
function solace_extra_woo_get_type_parts( $part ) {
	return get_posts(
		array(
			'post_type'      => 'solace-sitebuilder',
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => 20,
			'orderby'        => 'ID',
			'order'          => 'DESC',
			'meta_key'       => '_solace_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $part, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'no_found_rows'  => true,
		)
	);
}

/**
 * Whether a post ID is a Site Builder part of the given type.
 *
 * @param int    $post_id Post ID.
 * @param string $part    Part slug.
 * @return bool
 */
function solace_extra_woo_is_part_post( $post_id, $part ) {
	$post_id = absint( $post_id );
	if ( ! $post_id || 'solace-sitebuilder' !== get_post_type( $post_id ) ) {
		return false;
	}
	return $part === (string) get_post_meta( $post_id, '_solace_template', true );
}

/**
 * Why a Cart/Checkout/My Account part cannot serve as its WooCommerce page yet,
 * or '' when it can. The same requirements as solace_get_woo_page_part_id()
 * (published, designed, carrying the page widget), so a part is never switched
 * on or selected while the store would not use it.
 *
 * @param int    $post_id Site Builder part.
 * @param string $part    cart|checkout|myaccount.
 * @return string Message for the store owner, or ''.
 */
function solace_extra_woo_part_unusable_reason( $post_id, $part ) {
	if ( ! in_array( $part, array( 'cart', 'checkout', 'myaccount' ), true ) ) {
		return '';
	}

	$labels = array(
		'cart'      => __( 'Cart', 'solace-extra' ),
		'checkout'  => __( 'Checkout', 'solace-extra' ),
		'myaccount' => __( 'My Account', 'solace-extra' ),
	);

	if ( 'publish' !== get_post_status( $post_id ) ) {
		return __( 'This template is not published yet. Publish it in Elementor first.', 'solace-extra' );
	}

	if ( function_exists( 'solace_part_is_renderable' ) && ! solace_part_is_renderable( $post_id ) ) {
		return __( 'This template has no design yet. Design it in Elementor first.', 'solace-extra' );
	}

	if ( function_exists( 'solace_part_has_required_widget' ) && ! solace_part_has_required_widget( $post_id, $part ) ) {
		/* translators: %s: page type, e.g. Cart. */
		return sprintf( __( 'This template has no %s widget, so WooCommerce cannot use it. Add the widget in Elementor first.', 'solace-extra' ), $labels[ $part ] );
	}

	return '';
}

/**
 * Write a Site Builder part status (safe and canonical).
 *
 * @param int    $post_id Post ID.
 * @param string $part    Part slug.
 * @param bool   $on      Whether to turn the part on.
 * @return bool
 */
function solace_extra_woo_set_part_status( $post_id, $part, $on ) {
	if ( function_exists( 'solace_set_part_status' ) ) {
		return (bool) solace_set_part_status( $post_id, $part, $on );
	}
	update_post_meta( $post_id, '_solace_' . $part . '_status', $on ? '1' : '0' );
	return true;
}

/**
 * 1) UNLOCK the WooCommerce page dropdowns.
 *
 * Solace Extra adds `disabled` + the `solace-page-locked` class while a part
 * is active. This filter (priority 20) removes them again so the dropdown is
 * always selectable.
 */
add_filter( 'woocommerce_settings_pages', 'solace_extra_woo_page_setup_unlock', 20 );
// The Shop page selector lives under Products, not Advanced.
add_filter( 'woocommerce_product_settings', 'solace_extra_woo_page_setup_unlock', 20 );

function solace_extra_woo_page_setup_unlock( $settings ) {
	if ( ! is_admin() || wp_doing_ajax() || ! is_array( $settings ) ) {
		return $settings;
	}

	$map = solace_extra_woo_field_map();

	foreach ( $settings as $key => $field ) {
		if ( ! is_array( $field ) || empty( $field['id'] ) || ! isset( $map[ $field['id'] ] ) ) {
			continue;
		}

		$part = $map[ $field['id'] ];

		// Remove the disabled attribute Solace Extra added.
		if ( isset( $settings[ $key ]['custom_attributes'] ) && is_array( $settings[ $key ]['custom_attributes'] ) ) {
			unset( $settings[ $key ]['custom_attributes']['disabled'] );
		}

		// Clear the "locked" row class.
		$row = isset( $field['row_class'] ) ? (string) $field['row_class'] : '';
		$row = trim( str_replace( 'solace-page-locked', '', $row ) );
		$settings[ $key ]['row_class'] = $row;

		// Informative description / notice. With desc_tip => true WooCommerce
		// would print desc inside the (?) tooltip instead of under the dropdown,
		// so its own text moves into the tooltip and the notice takes desc.
		if ( isset( $settings[ $key ]['desc_tip'] ) && true === $settings[ $key ]['desc_tip'] ) {
			$settings[ $key ]['desc_tip'] = isset( $field['desc'] ) ? (string) $field['desc'] : '';
		}
		$settings[ $key ]['desc'] = solace_extra_woo_page_notice( $part );
	}

	return $settings;
}

/**
 * Notice shown under the WooCommerce page dropdown.
 *
 * @param string $part cart|checkout|myaccount.
 * @return string HTML.
 */
function solace_extra_woo_page_notice( $part ) {
	$notice = solace_extra_woo_page_notice_text( $part );

	if ( 'shopproduct' === $part ) {
		return $notice;
	}

	$selected = absint( get_option( 'woocommerce_' . $part . '_page_id', 0 ) );

	// Empty Cart/Checkout/My Account page: one short line, the same for all
	// three, plus a link to the template of this type when there is one.
	if ( ! $selected ) {
		$labels = array(
			'cart'      => __( 'Cart', 'solace-extra' ),
			'checkout'  => __( 'Checkout', 'solace-extra' ),
			'myaccount' => __( 'My Account', 'solace-extra' ),
		);
		$line = '<strong style="color:#b32d2e;">' . esc_html__( 'No page selected.', 'solace-extra' ) . '</strong>';

		$built     = solace_extra_woo_get_type_parts( $part );
		$published = array_values(
			array_filter(
				$built,
				function ( $item ) {
					return 'publish' === $item->post_status;
				}
			)
		);
		if ( ! empty( $published ) ) {
			$line .= ' <a href="' . esc_url( admin_url( 'admin.php?page=dashboard-sitebuilder&part=' . $part ) ) . '">' . sprintf(
				/* translators: %s: template title. */
				esc_html__( 'Activate %s', 'solace-extra' ),
				'<strong>' . esc_html( get_the_title( $published[0] ) ) . '</strong>'
			) . '</a>';
		} else {
			$line .= ' ' . sprintf(
				/* translators: %s: Cart, Checkout or My Account. */
				esc_html__( 'Choose a %s page here.', 'solace-extra' ),
				esc_html( isset( $labels[ $part ] ) ? $labels[ $part ] : $part )
			);
		}

		return $line;
	} elseif ( solace_extra_woo_is_part_post( $selected, $part ) ) {
		// A template chosen here that the store cannot use: the default WooCommerce
		// page is shown instead. Say why, with a link to fix it.
		$reason = solace_extra_woo_part_unusable_reason( $selected, $part );
		if ( '' !== $reason ) {
			$notice = '<strong style="color:#b32d2e;">' . sprintf(
				/* translators: 1: template title, 2: reason. */
				esc_html__( '%1$s is selected but not used: %2$s', 'solace-extra' ),
				esc_html( get_the_title( $selected ) ),
				esc_html( $reason )
			) . '</strong> <a href="' . esc_url( admin_url( 'post.php?post=' . $selected . '&action=elementor' ) ) . '">' . esc_html__( 'Edit template', 'solace-extra' ) . '</a>';
		}
	}

	return $notice;
}

/**
 * Notice text for one WooCommerce page dropdown (see solace_extra_woo_page_notice()).
 *
 * @param string $part cart|checkout|myaccount|shopproduct.
 * @return string HTML.
 */
function solace_extra_woo_page_notice_text( $part ) {
	$labels = array(
		'cart'      => __( 'Cart', 'solace-extra' ),
		'checkout'  => __( 'Checkout', 'solace-extra' ),
		'myaccount' => __( 'My Account', 'solace-extra' ),
		'shopproduct' => __( 'Shop', 'solace-extra' ),
	);
	$label = isset( $labels[ $part ] ) ? $labels[ $part ] : $part;

	$active = function_exists( 'solace_get_active_part_id' ) ? (int) solace_get_active_part_id( $part ) : 0;

	// Several Shop parts can be on for other archives; only the one chosen here
	// is "the" Shop page template.
	if ( 'shopproduct' === $part ) {
		$selected = absint( get_option( 'woocommerce_shop_page_id', 0 ) );
		$active   = solace_extra_woo_is_part_post( $selected, $part ) ? $selected : 0;

		if ( ! $active ) {
			return sprintf(
				/* translators: 1: Site Builder link. */
				esc_html__( 'Choose an option labeled "(Solace Sitebuilder)" to use a Shop template as the Shop page. Shop templates switched on in %1$s still apply wherever their conditions match.', 'solace-extra' ),
				'<a href="' . esc_url( admin_url( 'admin.php?page=dashboard-sitebuilder&part=shopproduct' ) ) . '">' . esc_html__( 'Site Builder', 'solace-extra' ) . '</a>'
			);
		}
	}

	if ( $active && 'shopproduct' === $part ) {
		return sprintf(
			/* translators: 1: part title 2: Site Builder link. */
			esc_html__( 'Site Builder template %1$s is ACTIVE as the Shop page. If you choose another page here, Shop templates switched on in %2$s still apply wherever their conditions match.', 'solace-extra' ),
			'<strong>' . esc_html( get_the_title( $active ) ) . '</strong>',
			'<a href="' . esc_url( admin_url( 'admin.php?page=dashboard-sitebuilder&part=shopproduct' ) ) . '">' . esc_html__( 'Site Builder', 'solace-extra' ) . '</a>'
		);
	}

	if ( $active ) {
		return sprintf(
			/* translators: 1: part title 2: page type label 3: Site Builder link. */
			esc_html__( 'Site Builder template %1$s is ACTIVE for the %2$s page. If you choose another page here, that page is shown instead and the template stays on, unused, in %3$s.', 'solace-extra' ),
			'<strong>' . esc_html( get_the_title( $active ) ) . '</strong>',
			esc_html( $label ),
			'<a href="' . esc_url( admin_url( 'admin.php?page=dashboard-sitebuilder&part=' . $part ) ) . '">' . esc_html__( 'Site Builder', 'solace-extra' ) . '</a>'
		);
	}

	// Nothing switched on, but a template of this type has been built: point the
	// store owner at it. Switching it on in Site Builder selects it here. Site
	// Builder only lists published templates, so a draft is pointed at its editor.
	$built = solace_extra_woo_get_type_parts( $part );
	$published = array_values(
		array_filter(
			$built,
			function ( $item ) {
				return 'publish' === $item->post_status;
			}
		)
	);
	if ( ! empty( $published ) ) {
		return sprintf(
			/* translators: 1: page type label, 2: template title, 3: link to the part in Site Builder. */
			esc_html__( 'A %1$s template built with Solace Site Builder already exists: %2$s. %3$s; it is then selected here automatically.', 'solace-extra' ),
			esc_html( $label ),
			'<strong>' . esc_html( get_the_title( $published[0] ) ) . '</strong>',
			'<a href="' . esc_url( admin_url( 'admin.php?page=dashboard-sitebuilder&part=' . $part ) ) . '">' . esc_html__( 'Click here to activate it', 'solace-extra' ) . '</a>'
		);
	}
	if ( ! empty( $built ) ) {
		$edit_url = class_exists( '\Elementor\Plugin' ) ? admin_url( 'post.php?post=' . $built[0]->ID . '&action=elementor' ) : get_edit_post_link( $built[0]->ID, 'raw' );
		return sprintf(
			/* translators: 1: page type label, 2: template title, 3: link to the template editor. */
			esc_html__( 'A %1$s template built with Solace Site Builder is still a draft: %2$s. %3$s and switch it on in Site Builder; it is then selected here automatically.', 'solace-extra' ),
			esc_html( $label ),
			'<strong>' . esc_html( get_the_title( $built[0] ) ) . '</strong>',
			'<a href="' . esc_url( $edit_url ) . '">' . esc_html__( 'Publish it', 'solace-extra' ) . '</a>'
		);
	}

	return sprintf(
		/* translators: 1: page type label. */
		esc_html__( 'This dropdown is not locked. Choose an option labeled "(Solace Sitebuilder)" to turn on the %1$s template, or pick any regular page.', 'solace-extra' ),
		esc_html( $label )
	);
}

/**
 * Switching a Cart/Checkout/My Account part on in Site Builder selects it as
 * that page in WooCommerce Settings (the reverse of section 6).
 *
 * Only a part the store can actually use (published, designed, with the page
 * widget) is selected: the cart page would otherwise point at a template
 * nothing can show.
 *
 * @param int    $post_id Site Builder part.
 * @param string $part    Part slug.
 * @return void
 */
function solace_extra_woo_select_part_as_page( $post_id, $part ) {
	if ( ! in_array( $part, array( 'cart', 'checkout', 'myaccount' ), true ) || ! class_exists( 'WooCommerce' ) || ! function_exists( 'solace_get_active_part_id' ) ) {
		return;
	}

	$post_id = absint( $post_id );

	if ( ! $post_id || (int) solace_get_active_part_id( $part ) !== $post_id || '' !== solace_extra_woo_part_unusable_reason( $post_id, $part ) ) {
		return;
	}

	// update_option fires solace_extra_woo_sync_kit_option_hooks(), so Elementor Site Settings follows.
	update_option( 'woocommerce_' . $part . '_page_id', $post_id );
}

/**
 * Switching a selected Cart/Checkout/My Account part off hands the page back to
 * its base page (the WooCommerce page it replaced). An empty option would send
 * "View Cart", "Proceed to checkout" and the account links to the homepage. The
 * option is only emptied when no base page can be found; the notice under the
 * dropdown then points back to the template (solace_extra_woo_page_notice()).
 *
 * @param int    $post_id Site Builder part.
 * @param string $part    Part slug.
 * @return void
 */
function solace_extra_woo_unselect_part_as_page( $post_id, $part ) {
	$option = 'woocommerce_' . $part . '_page_id';

	if ( absint( $post_id ) && (int) get_option( $option, 0 ) === absint( $post_id ) ) {
		$base = solace_extra_woo_base_page_id( $part );
		update_option( $option, $base ? $base : '' );
	}
}

/**
 * A selected Cart/Checkout/My Account/Shop part that is trashed or deleted is
 * treated like switching it off: the WooCommerce page goes back to its base page
 * instead of pointing at a post that no longer renders.
 *
 * @param int $post_id Post being trashed or deleted.
 * @return void
 */
function solace_extra_woo_part_removed( $post_id ) {
	if ( 'solace-sitebuilder' !== get_post_type( $post_id ) ) {
		return;
	}

	$part = (string) get_post_meta( $post_id, '_solace_template', true );

	if ( in_array( $part, array( 'cart', 'checkout', 'myaccount' ), true ) ) {
		solace_extra_woo_unselect_part_as_page( $post_id, $part );
		// Its address goes with it.
		$own = absint( get_post_meta( $post_id, '_solace_woo_page_id', true ) );
		if ( $own && (int) solace_extra_woo_page_owner( $own ) === (int) $post_id && 'trash' !== get_post_status( $own ) ) {
			wp_trash_post( $own );
		}
	} elseif ( 'shopproduct' === $part && (int) get_option( 'woocommerce_shop_page_id', 0 ) === (int) $post_id ) {
		$base = solace_extra_woo_shop_base_page_id();
		update_option( 'woocommerce_shop_page_id', $base ? $base : '' );
	}
}
add_action( 'wp_trash_post', 'solace_extra_woo_part_removed' );
add_action( 'before_delete_post', 'solace_extra_woo_part_removed' );

/**
 * "Add New" on a Cart/Checkout/My Account part keeps the current template live
 * while the new one is a draft (solace_extra_create_and_edit_page() flags it with
 * _solace_activate_on_publish). Once the new template is published and has a
 * design, it is switched on, which selects it in WooCommerce Settings, and the
 * other templates of that type are switched off.
 *
 * Elementor saves the post status before the design, so the first publish from
 * the editor is picked up by elementor/document/after_save.
 *
 * @param int $post_id Site Builder part.
 * @return void
 */
function solace_extra_woo_activate_on_publish( $post_id ) {
	$post_id = absint( $post_id );

	if ( ! $post_id || 'solace-sitebuilder' !== get_post_type( $post_id ) || 'publish' !== get_post_status( $post_id ) || ! get_post_meta( $post_id, '_solace_activate_on_publish', true ) ) {
		return;
	}

	$part = (string) get_post_meta( $post_id, '_solace_template', true );

	if ( ! in_array( $part, array( 'cart', 'checkout', 'myaccount' ), true ) ) {
		delete_post_meta( $post_id, '_solace_activate_on_publish' );
		return;
	}

	if ( '' !== solace_extra_woo_part_unusable_reason( $post_id, $part ) ) {
		return; // No design or page widget yet: wait for the save that adds it.
	}

	delete_post_meta( $post_id, '_solace_activate_on_publish' );

	// On first: the newest active part wins, so it is selected as the page right
	// away and switching the others off no longer touches the option.
	solace_extra_woo_set_part_status( $post_id, $part, true );
	foreach ( solace_extra_woo_get_type_parts( $part ) as $item ) {
		if ( (int) $item->ID !== $post_id ) {
			solace_extra_woo_set_part_status( (int) $item->ID, $part, false );
		}
	}
}
add_action(
	'transition_post_status',
	function ( $new_status, $old_status, $post ) {
		if ( 'publish' === $new_status && $post instanceof WP_Post ) {
			solace_extra_woo_activate_on_publish( $post->ID );
		}
	},
	10,
	3
);
add_action(
	'elementor/document/after_save',
	function ( $document ) {
		if ( is_object( $document ) && method_exists( $document, 'get_main_id' ) ) {
			solace_extra_woo_activate_on_publish( $document->get_main_id() );
		}
	}
);

/**
 * Keep WooCommerce Settings in step with a part status, whichever switch wrote
 * it: the template card switch, the part's global switch
 * (solace_update_sitebuilder_all_status) or anything else.
 *
 * @param int    $meta_id    Meta ID.
 * @param int    $post_id    Post ID.
 * @param string $meta_key   Meta key.
 * @param mixed  $meta_value Meta value.
 * @return void
 */
function solace_extra_woo_part_status_changed( $meta_id, $post_id, $meta_key, $meta_value ) {
	// Fires for every post meta write: match the key before touching the value.
	if ( ! is_string( $meta_key ) || ! preg_match( '/^_solace_(cart|checkout|myaccount|shopproduct)_status$/', $meta_key, $m ) || ! is_scalar( $meta_value ) ) {
		return;
	}

	if ( 'shopproduct' === $m[1] ) {
		solace_extra_woo_shop_part_status_changed( $post_id, '1' === (string) $meta_value );
		return;
	}

	if ( '1' === (string) $meta_value ) {
		solace_extra_woo_select_part_as_page( $post_id, $m[1] );
	} else {
		solace_extra_woo_unselect_part_as_page( $post_id, $m[1] );
	}
}
add_action( 'added_post_meta', 'solace_extra_woo_part_status_changed', 10, 4 );
add_action( 'updated_post_meta', 'solace_extra_woo_part_status_changed', 10, 4 );

/**
 * Shop parts follow the Cart rules only where they cover the shop page itself.
 * Other Shop parts serve category/tag/attribute archives through their
 * conditions, so the Shop page setting never switches them.
 *  - Switching on the part that covers the shop page (condition "Shop Page", or
 *    no condition; see solace_sitebuilder_shopproduct_shop_page_part_id())
 *    selects it as the Shop page.
 *  - Switching off the part selected as the Shop page hands the setting back to
 *    the base page.
 *  - An empty Shop page switches nothing off: /shop/ still exists without it.
 *
 * @param int  $post_id Shop part.
 * @param bool $on      New status.
 * @return void
 */
function solace_extra_woo_shop_part_status_changed( $post_id, $on ) {
	$post_id  = absint( $post_id );
	$selected = (int) get_option( 'woocommerce_shop_page_id', 0 );

	if ( ! $on ) {
		if ( $post_id && $selected === $post_id ) {
			$base = solace_extra_woo_shop_base_page_id();
			update_option( 'woocommerce_shop_page_id', $base ? $base : '' );
		}
		return;
	}

	if ( ! $post_id || $selected === $post_id || ! function_exists( 'solace_sitebuilder_shopproduct_shop_page_part_id' ) || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	if ( function_exists( 'solace_part_is_renderable' ) && ! solace_part_is_renderable( $post_id ) ) {
		return;
	}

	if ( (int) solace_sitebuilder_shopproduct_shop_page_part_id() === $post_id ) {
		update_option( 'woocommerce_shop_page_id', $post_id );
	}
}

/**
 * Remember the real page the Shop part replaces, however the option changes
 * (WooCommerce Settings, Elementor Site Settings or the switch above).
 */
add_action(
	'update_option_woocommerce_shop_page_id',
	function ( $old_value, $value ) {
		$old = absint( $old_value );
		if ( $old && 'page' === get_post_type( $old ) && 'publish' === get_post_status( $old ) && solace_extra_woo_is_part_post( $value, 'shopproduct' ) ) {
			update_option( 'solace_extra_woo_shop_base_page', $old, false );
		}
	},
	10,
	2
);

/**
 * Emptying the Cart/Checkout/My Account page (WooCommerce Settings, Elementor
 * Site Settings or anything else) switches that part type off, so the Site
 * Builder switch never shows ON for a template the store does not use. The
 * notice under the empty dropdown then offers to activate it again.
 */
function solace_extra_woo_page_option_emptied_hooks() {
	foreach ( array( 'cart', 'checkout', 'myaccount' ) as $part ) {
		add_action(
			'update_option_woocommerce_' . $part . '_page_id',
			function ( $old_value, $value ) use ( $part ) {
				if ( absint( $value ) ) {
					return;
				}
				foreach ( solace_extra_woo_get_type_parts( $part ) as $item ) {
					if ( '1' === (string) get_post_meta( $item->ID, '_solace_' . $part . '_status', true ) ) {
						solace_extra_woo_set_part_status( (int) $item->ID, $part, false );
					}
				}
			},
			10,
			2
		);
	}
}
solace_extra_woo_page_option_emptied_hooks();

/**
 * Same rule for a page that is already empty: a part switched on while its
 * WooCommerce page is empty (left over from older versions, or emptied before
 * the hook above existed) is switched off when WooCommerce Settings or the
 * Site Builder dashboard is opened. Such a part never renders anyway.
 */
add_action( 'admin_init', 'solace_extra_woo_switch_off_parts_without_page' );

function solace_extra_woo_switch_off_parts_without_page() {
	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only screen check.

	if ( ! in_array( $page, array( 'wc-settings', 'dashboard-sitebuilder' ), true ) || ! class_exists( 'WooCommerce' ) || ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}

	foreach ( array( 'cart', 'checkout', 'myaccount' ) as $part ) {
		if ( absint( get_option( 'woocommerce_' . $part . '_page_id', 0 ) ) ) {
			continue;
		}
		foreach ( solace_extra_woo_get_type_parts( $part ) as $item ) {
			// A draft or empty part is never selected as the page, so switching
			// it on must not be undone here.
			if ( 'publish' !== $item->post_status || ( function_exists( 'solace_part_is_renderable' ) && ! solace_part_is_renderable( $item->ID ) ) ) {
				continue;
			}
			if ( '1' === (string) get_post_meta( $item->ID, '_solace_' . $part . '_status', true ) ) {
				solace_extra_woo_set_part_status( (int) $item->ID, $part, false );
			}
		}
	}
}

/**
 * 2) Show ALL Site Builder templates (active or not) in the WooCommerce page
 * search results — the source of options for the select2 dropdowns.
 */
add_filter( 'woocommerce_json_search_found_pages', 'solace_extra_woo_ajax_add_sitebuilder_pages', 10, 1 );

function solace_extra_woo_ajax_add_sitebuilder_pages( $pages ) {
	if ( ! is_array( $pages ) || ! class_exists( 'WooCommerce' ) || ! current_user_can( 'manage_woocommerce' ) ) {
		return $pages;
	}

	$slugs = array_merge( solace_extra_woo_part_slugs(), array( 'shopproduct' ) );

	// The settings screen tells which selector is searching (see the admin_footer
	// script), so each one only offers its own part types.
	$field = isset( $_GET['solace_field'] ) ? sanitize_key( wp_unslash( $_GET['solace_field'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$types = solace_extra_woo_field_part_types();
	if ( isset( $types[ $field ] ) ) {
		$slugs = $types[ $field ];
	} elseif ( '' !== $field ) {
		return $pages;
	}

	// Drafts are left out: a draft selected as the page renders nothing and the
	// page would stop being recognized as the cart / checkout / account page.
	$args = array(
		'post_type'      => 'solace-sitebuilder',
		'post_status'    => 'publish',
		'posts_per_page' => 30,
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query, WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			array(
				'key'     => '_solace_template',
				'value'   => $slugs,
				'compare' => 'IN',
			),
		),
		'orderby'        => 'ID',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	);

	$term = isset( $_GET['term'] ) ? wc_clean( wp_unslash( $_GET['term'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	if ( '' !== $term ) {
		$args['s'] = $term;
	}

	foreach ( get_posts( $args ) as $item ) {
		$pages[ $item->ID ] = solace_extra_woo_part_option_label( $item );
	}

	return $pages;
}

/**
 * 3) Classic dropdown (wp_dropdown_pages): also add the template options.
 */
add_filter( 'wp_dropdown_pages', 'solace_extra_woo_dropdown_sitebuilder_options', 10, 3 );

function solace_extra_woo_dropdown_sitebuilder_options( $output, $r, $pages ) {
	if ( ! is_admin() || wp_doing_ajax() || ! is_array( $r ) || empty( $r['id'] ) ) {
		return $output;
	}

	$targets = solace_extra_woo_field_part_types();

	if ( ! isset( $targets[ $r['id'] ] ) || false === strpos( $output, '</select>' ) ) {
		return $output;
	}

	$selected = isset( $r['selected'] ) ? (int) $r['selected'] : 0;
	$html     = '';

	foreach ( $targets[ $r['id'] ] as $part ) {
		foreach ( solace_extra_woo_get_type_parts( $part ) as $item ) {
			// Drafts are not offered (see the search above), unless one is already
			// stored as the page: it must stay visible to be replaced.
			if ( ! is_object( $item ) || ( 'publish' !== $item->post_status && (int) $item->ID !== $selected ) ) {
				continue;
			}
			$label = solace_extra_woo_part_option_label( $item );
			$sel = selected( $selected, (int) $item->ID, false );
			$html .= '<option value="' . esc_attr( $item->ID ) . '"' . $sel . '>' . esc_html( $label ) . '</option>';
		}
	}

	return str_replace( '</select>', $html . '</select>', $output );
}

/**
 * 4) Front-end: a Site Builder part selected directly as the page is still
 * recognized as the cart / checkout / my-account page, and the base pages
 * (solace_extra_woo_base_page_id(): /cart/, /checkout/, /my-account/) stay recognized while that
 * part is active and selected — even though the WooCommerce option stores the
 * template ID (post `solace-sitebuilder`) as its value.
 */
function solace_extra_woo_part_is_selected_page( $part, $part_id ) {
	return (int) get_option( 'woocommerce_' . $part . '_page_id', 0 ) === (int) $part_id;
}

function solace_extra_woo_queried_is_active_part( $part ) {
	if ( ! is_singular( 'solace-sitebuilder' ) ) {
		return false;
	}
	$id = get_queried_object_id();
	if ( ! $id || 'publish' !== get_post_status( $id ) ) {
		return false;
	}
	if ( '1' !== (string) get_post_meta( $id, '_solace_' . $part . '_status', true ) ) {
		return false;
	}
	// A part only counts as that page type when it is selected in WooCommerce Settings.
	return solace_extra_woo_part_is_selected_page( $part, $id );
}

/**
 * Content that marks a page as the WooCommerce Cart / Checkout / My Account page.
 *
 * @return array Part => block comments / shortcodes.
 */
function solace_extra_woo_base_page_markers() {
	return array(
		'cart'      => array( '<!-- wp:woocommerce/cart', '[woocommerce_cart' ),
		'checkout'  => array( '<!-- wp:woocommerce/checkout', '[woocommerce_checkout' ),
		'myaccount' => array( '[woocommerce_my_account' ),
	);
}

/**
 * The real page a Cart/Checkout/My Account part renders at while it is selected
 * as that page, and the page WooCommerce goes back to when the part is switched
 * off. Page IDs differ per site, so it is resolved, in this order:
 *  1. the page remembered when the part was selected (option
 *     solace_extra_woo_base_pages, see solace_extra_woo_remember_base_page_hooks()),
 *  2. the page holding the WooCommerce block or shortcode for that type,
 *  3. the page at the WooCommerce default address (/cart/, /checkout/, /my-account/).
 * A page found by 2 or 3 is remembered.
 *
 * @param string $part cart|checkout|myaccount.
 * @return int Page ID, or 0.
 */
function solace_extra_woo_base_page_id( $part ) {
	static $missing = array();

	$markers = solace_extra_woo_base_page_markers();
	if ( ! isset( $markers[ $part ] ) ) {
		return 0;
	}

	$stored = (array) get_option( 'solace_extra_woo_base_pages', array() );
	$id     = isset( $stored[ $part ] ) ? absint( $stored[ $part ] ) : 0;

	if ( $id && 'page' === get_post_type( $id ) && 'publish' === get_post_status( $id ) && ! solace_extra_woo_page_owner( $id ) ) {
		return $id;
	}

	if ( isset( $missing[ $part ] ) ) {
		return 0;
	}

	global $wpdb;

	$id = 0;
	foreach ( $markers[ $part ] as $marker ) {
		$id = (int) $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Resolved once, then remembered in an option.
			$wpdb->prepare(
				"SELECT p.ID FROM {$wpdb->posts} p LEFT JOIN {$wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = '_solace_part_owner' WHERE p.post_type = 'page' AND p.post_status = 'publish' AND m.meta_id IS NULL AND p.post_content LIKE %s ORDER BY p.ID ASC LIMIT 1",
				'%' . $wpdb->esc_like( $marker ) . '%'
			)
		);
		if ( $id ) {
			break;
		}
	}

	if ( ! $id ) {
		$slugs = array(
			'cart'      => 'cart',
			'checkout'  => 'checkout',
			'myaccount' => 'my-account',
		);
		$page  = get_page_by_path( $slugs[ $part ] );
		$id    = ( $page && 'publish' === $page->post_status && ! solace_extra_woo_page_owner( $page->ID ) ) ? (int) $page->ID : 0;
	}

	if ( ! $id ) {
		$missing[ $part ] = true;
		return 0;
	}

	$stored[ $part ] = $id;
	update_option( 'solace_extra_woo_base_pages', $stored );

	return $id;
}

/**
 * Remember the page a Cart/Checkout/My Account part replaces: when the option
 * changes from a real page to a Site Builder part, that page becomes the base
 * page of the type (the Shop equivalent is solace_extra_woo_shop_base_page).
 */
function solace_extra_woo_remember_base_page_hooks() {
	foreach ( array( 'cart', 'checkout', 'myaccount' ) as $part ) {
		add_action(
			'update_option_woocommerce_' . $part . '_page_id',
			function ( $old_value, $value ) use ( $part ) {
				$old = absint( $old_value );
				if ( ! $old || 'page' !== get_post_type( $old ) || 'publish' !== get_post_status( $old ) || solace_extra_woo_page_owner( $old ) || ! solace_extra_woo_is_part_post( $value, $part ) ) {
					return;
				}
				$stored = (array) get_option( 'solace_extra_woo_base_pages', array() );
				// Keep a base page that is still valid: the part's address must only
				// change through Rename, not because another page was picked in
				// WooCommerce Settings for a while.
				$current = isset( $stored[ $part ] ) ? absint( $stored[ $part ] ) : 0;
				if ( $current && 'page' === get_post_type( $current ) && 'publish' === get_post_status( $current ) ) {
					return;
				}
				$stored[ $part ] = $old;
				update_option( 'solace_extra_woo_base_pages', $stored );
			},
			10,
			2
		);
	}
}
solace_extra_woo_remember_base_page_hooks();

/**
 * Base page per part type (see solace_extra_woo_base_page_id()).
 *
 * @return array Base page per part.
 */
function solace_extra_woo_base_pages() {
	/**
	 * Filter the base pages that stay recognized as cart/checkout/my-account
	 * pages even when the option stores the template ID.
	 *
	 * @param array $map Part => page ID.
	 */
	// A part selected as the page renders at its own page (section 15); with a
	// regular page selected, the WooCommerce page it replaced.
	$map = array();
	foreach ( array( 'cart', 'checkout', 'myaccount' ) as $type ) {
		$selected     = absint( get_option( 'woocommerce_' . $type . '_page_id', 0 ) );
		$map[ $type ] = solace_extra_woo_is_part_post( $selected, $type )
			? solace_extra_woo_part_page_id( $selected, $type, true )
			: solace_extra_woo_base_page_id( $type );
	}

	return (array) apply_filters( 'solace_sitebuilder_woo_base_pages', $map );
}

function solace_extra_woo_front_page_is( $part ) {
	if ( 'publish' !== get_post_status( get_queried_object_id() ) ) {
		return false;
	}
	$page_id = absint( get_queried_object_id() );
	$bases   = solace_extra_woo_base_pages();
	if ( empty( $bases[ $part ] ) || (int) $bases[ $part ] !== $page_id ) {
		return false;
	}
	$active = (int) solace_get_active_part_id( $part );
	if ( ! $active ) {
		return false;
	}
	// The base pages /cart/, /checkout/, /my-account/ are only recognized when
	// the template itself is selected as the page in WooCommerce Settings.
	return solace_extra_woo_part_is_selected_page( $part, $active );
}

add_filter( 'woocommerce_is_cart', function ( $is ) {
	return $is || solace_extra_woo_queried_is_active_part( 'cart' ) || solace_extra_woo_front_page_is( 'cart' );
} );

add_filter( 'woocommerce_is_checkout', function ( $is ) {
	return $is || solace_extra_woo_queried_is_active_part( 'checkout' ) || solace_extra_woo_front_page_is( 'checkout' );
} );

add_filter( 'woocommerce_is_account_page', function ( $is ) {
	return $is || solace_extra_woo_queried_is_active_part( 'myaccount' ) || solace_extra_woo_front_page_is( 'myaccount' );
} );

/**
 * 5) Elementor compatibility: the "WooCommerce Pages" fields in Elementor Site
 * Settings only query posts of type `page` (QueryModule). Site Builder template
 * IDs (CPT `solace-sitebuilder`) are therefore not resolved ("Select a page")
 * and do not show up in its search. Here, each Elementor editor AJAX request
 * for a query-control object=post with post_type page is extended to include
 * `solace-sitebuilder` — so (a) a stored template ID resolves to its title and
 * (b) searching "cart..." finds the template.
 *
 * Elementor editor requests use `wp_ajax_elementor_ajax`; hooking at priority 5
 * runs before Elementor's handler and rewrites the actions payload (JSON) it is
 * about to decode.
 */
add_action( 'wp_ajax_elementor_ajax', 'solace_extra_woo_elementor_ajax_extend_page_query', 5 );

function solace_extra_woo_elementor_ajax_extend_page_query() {
	if ( empty( $_REQUEST['actions'] ) || ! is_string( $_REQUEST['actions'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$raw     = wp_unslash( $_REQUEST['actions'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
	$actions = json_decode( $raw, true );

	if ( ! is_array( $actions ) ) {
		return;
	}

	$target_actions = array(
		'query_control_value_titles'                      => 'get_titles',
		'query_control_value_titles_deprecated'           => 'get_titles',
		'pro_panel_posts_control_filter_autocomplete'     => 'autocomplete',
		'pro_panel_posts_control_filter_autocomplete_deprecated' => 'autocomplete',
	);

	$touched = false;

	foreach ( $actions as $id => $action_data ) {
		if ( ! is_array( $action_data ) || empty( $action_data['action'] ) ) {
			continue;
		}

		if ( ! isset( $target_actions[ $action_data['action'] ] ) ) {
			continue;
		}

		$section_key = $target_actions[ $action_data['action'] ];
		$data        = isset( $action_data['data'] ) ? $action_data['data'] : array();

		if ( ! is_array( $data ) || empty( $data[ $section_key ] ) || ! is_array( $data[ $section_key ] ) ) {
			continue;
		}

		$query = isset( $data[ $section_key ]['query'] ) ? $data[ $section_key ]['query'] : array();
		if ( empty( $query['post_type'] ) ) {
			continue;
		}

		$post_types = (array) $query['post_type'];
		if ( ! in_array( 'page', $post_types, true ) ) {
			continue; // Not a page picker — leave it alone.
		}

		if ( ! in_array( 'solace-sitebuilder', $post_types, true ) ) {
			$post_types[] = 'solace-sitebuilder';
			$actions[ $id ]['data'][ $section_key ]['query']['post_type'] = $post_types;
			$touched = true;
		}
	}

	if ( $touched ) {
		$encoded           = wp_json_encode( $actions );
		$_REQUEST['actions'] = $encoded;
		$_POST['actions']    = $encoded;
	}
}

/**
 * 6) SAVE: selecting a template turns its switch on (and the other parts of
 * that type off). Choosing a regular page leaves the template on; it is only
 * skipped on the front end while that page is selected.
 */
add_action( 'woocommerce_settings_save_advanced', 'solace_extra_woo_capture_page_option_snapshot' );
add_action( 'woocommerce_settings_save_products', 'solace_extra_woo_capture_page_option_snapshot' );

function solace_extra_woo_capture_page_option_snapshot() {
	$GLOBALS['solace_extra_woo_prev_pages'] = array();
	foreach ( solace_extra_woo_field_map() as $field => $part ) {
		$GLOBALS['solace_extra_woo_prev_pages'][ $field ] = (int) get_option( $field, 0 );
	}

	// Switching the Shop page from a real page to a Shop part: remember that page,
	// the product archive keeps living at its address.
	if ( isset( $_POST['woocommerce_shop_page_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified by WooCommerce.
		$prev     = (int) $GLOBALS['solace_extra_woo_prev_pages']['woocommerce_shop_page_id'];
		$selected = absint( wp_unslash( $_POST['woocommerce_shop_page_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( $prev && 'page' === get_post_type( $prev ) && solace_extra_woo_is_part_post( $selected, 'shopproduct' ) ) {
			update_option( 'solace_extra_woo_shop_base_page', $prev, false );
		}
	}
}

add_action( 'woocommerce_settings_saved', 'solace_extra_woo_page_save_sync_status' );

function solace_extra_woo_page_save_sync_status() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}

	$map = solace_extra_woo_field_map();

	foreach ( $map as $field => $part ) {
		if ( ! isset( $_POST[ $field ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified by WooCommerce.
			continue; // Field was not submitted (not this page).
		}

		$selected = isset( $_POST[ $field ] ) ? absint( wp_unslash( $_POST[ $field ] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		// A part's own page chosen in the dropdown means the part (section 15).
		$owner = function_exists( 'solace_extra_woo_page_owner' ) ? solace_extra_woo_page_owner( $selected ) : 0;
		if ( $owner ) {
			$selected = $owner;
		}

		if ( $selected && solace_extra_woo_is_part_post( $selected, $part ) ) {
			// Other Shop parts keep serving the archives their conditions name;
			// the selected one wins on the shop page itself.
			if ( 'shopproduct' === $part ) {
				solace_extra_woo_set_part_status( $selected, $part, true );
				continue;
			}
			// The user explicitly chose a template part => turn it on, others off.
			foreach ( solace_extra_woo_get_type_parts( $part ) as $item ) {
				$id = (int) $item->ID;
				if ( $id !== $selected ) {
					solace_extra_woo_set_part_status( $id, $part, false );
				}
			}
			solace_extra_woo_set_part_status( $selected, $part, true );
			continue;
		}

		// A regular page or an empty field leaves the part switched on: it is
		// skipped on the front end while another page is selected ("WooCommerce
		// Settings takes priority"), and the Site Builder dashboard says so.
	}
}

/**
 * 7) (no option remap) The value chosen in WooCommerce Settings or Elementor
 * Site Settings is the same value both screens read, so they always stay in
 * sync. Choosing a "(Solace Sitebuilder)" option stores the template ID as the
 * page (safe: the Solace override still renders the part on the related page).
 */

/**
 * 8) Polish the label of the selected option on the settings screen so it
 * reads "(Solace Sitebuilder)".
 */
add_action( 'admin_footer', 'solace_extra_woo_settings_init_js' );

function solace_extra_woo_settings_init_js() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'woocommerce_page_wc-settings' !== $screen->id ) {
		return;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only.
	$tab     = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : '';
	$section = isset( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : '';
	// phpcs:enable

	if ( ! in_array( $tab, array( 'advanced', 'products' ), true ) || '' !== $section ) {
		return;
	}

	$labels = array();
	foreach ( array_merge( solace_extra_woo_part_slugs(), array( 'shopproduct' ) ) as $pt ) {
		foreach ( solace_extra_woo_get_type_parts( $pt ) as $item ) {
			$labels[ (int) $item->ID ] = solace_extra_woo_part_option_label( $item );
		}
	}
	?>
	<script>
	jQuery( function ( $ ) {
		var solaceExtraLabels = <?php echo wp_json_encode( (object) $labels ); ?>;
		var solaceExtraFields = <?php echo wp_json_encode( array_keys( solace_extra_woo_field_part_types() ) ); ?>;
		var solaceExtraField  = '';

		// Mark the selected part with "(Solace Sitebuilder)"; WooCommerce prints
		// the bare title ("Cart #1 (ID: 61)").
		solaceExtraFields.forEach( function ( id ) {
			var $s = $( '#' + id );
			if ( ! $s.length ) {
				return;
			}
			$s.on( 'select2:opening', function () {
				solaceExtraField = id;
			} );
			var v = $s.val();
			if ( v && solaceExtraLabels[ v ] ) {
				$s.find( 'option[value="' + v + '"]' ).text( solaceExtraLabels[ v ] + ' (ID: ' + v + ')' );
				$s.trigger( 'change.select2' );
			}
		} );

		// Tell the page search which selector is asking, so the Shop selector
		// only offers Shop parts and Cart only Cart parts.
		$.ajaxPrefilter( function ( options ) {
			if ( solaceExtraField && options.url && -1 !== String( options.url + ( options.data || '' ) ).indexOf( 'woocommerce_json_search_pages' ) ) {
				options.url += ( -1 === options.url.indexOf( '?' ) ? '?' : '&' ) + 'solace_field=' + encodeURIComponent( solaceExtraField );
			}
		} );
	} );
	</script>
	<?php
}

/**
 * 9) FRONT-END — "WooCommerce Settings takes priority".
 *
 * Runs AFTER the Solace Extra Pro override (99999) and its own restore (100000).
 * When the WooCommerce option for cart/checkout/my-account points to a real
 * page (not the selected template), the original template is restored so that
 * the page itself renders — the Site Builder part is skipped.
 */
add_filter( 'template_include', 'solace_extra_woo_woocommerce_settings_priority', 100001 );

function solace_extra_woo_woocommerce_settings_priority( $template ) {
	$part = '';

	if ( function_exists( 'solace_sitebuilder_current_woo_page_part' ) ) {
		$part = (string) solace_sitebuilder_current_woo_page_part();
	}

	if ( ! in_array( $part, array( 'cart', 'checkout', 'myaccount' ), true ) ) {
		return $template;
	}

	if ( ! function_exists( 'solace_get_active_part_id' ) ) {
		return $template;
	}

	$active = (int) solace_get_active_part_id( $part );
	if ( ! $active ) {
		return $template;
	}

	// The active template is the selected page => let the override stand.
	if ( solace_extra_woo_part_is_selected_page( $part, $active ) ) {
		return $template;
	}

	// WooCommerce Settings uses a different real page => restore original template.
	$before = isset( $GLOBALS['solace_sitebuilder_template_before_override'] )
		? (string) $GLOBALS['solace_sitebuilder_template_before_override']
		: '';

	if ( '' === $before || $before === $template ) {
		return $template;
	}

	return $before;
}

/**
 * 10) DASHBOARD — replace the "Elementor Site Settings conflict" notice with
 * "WooCommerce Settings takes priority / this part is skipped" for
 * cart/checkout/my-account (and the All Layout badge).
 */
add_action( 'admin_init', 'solace_extra_woo_dashboard_ob_start', 1 );

function solace_extra_woo_dashboard_ob_start() {
	if ( isset( $_GET['page'] ) && 'dashboard-sitebuilder' === sanitize_key( wp_unslash( $_GET['page'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		ob_start();
	}
}

add_action( 'admin_footer', 'solace_extra_woo_dashboard_ob_end', 9999 );

function solace_extra_woo_dashboard_ob_end() {
	if ( ! isset( $_GET['page'] ) || 'dashboard-sitebuilder' !== sanitize_key( wp_unslash( $_GET['page'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$html = ob_get_clean();
	if ( false === $html ) {
		return;
	}

	if ( false !== strpos( $html, 'Elementor Site Settings conflict' ) ) {
		$html = solace_extra_woo_rewrite_dashboard_notices( $html );
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Processed admin HTML.
}

function solace_extra_woo_rewrite_dashboard_notices( $html ) {
	// 1) The notice box on the part page (part=cart/checkout/myaccount).
	$html = preg_replace_callback(
		'/<div class="solace-ep-conflict-box">(.*?)<\/div>/s',
		function ( $m ) {
			$body = $m[1];

			// Only for the WooCommerce pages (Cart/Checkout/My Account).
			if ( ! preg_match( '/The (Cart|Checkout|My Account) page \(([^)]+)\)/', $body, $mm ) ) {
				return $m[0];
			}

			$label = $mm[1];
			$page  = str_replace( array( '&amp;', '&quot;' ), array( '&', '"' ), html_entity_decode( (string) wp_strip_all_tags( $mm[2] ), ENT_QUOTES ) );

			return '<div class="solace-ep-conflict-box" style="border-left-color:#64748b;"><p><strong>' .
				esc_html__( 'WooCommerce Settings takes priority', 'solace-extra' ) .
				'</strong></p><p style="margin:0;">' .
				sprintf(
					/* translators: 1: selected page title 2: page type (Cart, Checkout, My Account). */
					esc_html__( '"%1$s" is selected as the %2$s page in WooCommerce Settings, so this part is skipped. Choose the part option there (or turn it off here) to change it.', 'solace-extra' ),
					'<strong>' . esc_html( $page ) . '</strong>',
					esc_html( $label )
				) .
				'</p></div>';
		},
		$html
	);

	// 2) The All Layout badge/tooltip and any remaining "Elementor Site Settings conflict" heading.
	$html = str_replace(
		array(
			'Elementor Site Settings conflict',
			'is designed with Elementor, but this part replaces it.',
			'Pick another page or turn off this part.',
		),
		array(
			'WooCommerce Settings takes priority',
			'is selected as the page, and this part is skipped.',
			'Choose the part option in WooCommerce Settings to activate it, or turn it off here.',
		),
		$html
	);

	return $html;
}

/**
 * 11) Elementor Site Settings sync.
 *
 * The "WooCommerce Pages" panel in Elementor Site Settings reads its values
 * from the Elementor kit document, while WooCommerce Settings reads the same
 * option name from the database. Mirror every change of the WooCommerce page
 * options into the kit so the two screens always agree — even when Elementor
 * Pro (which normally mirrors these itself via update_kit_settings_based_on_option)
 * is inactive.
 */
function solace_extra_woo_sync_kit_page_option( $page_key, $value ) {
	if ( class_exists( '\Elementor\Plugin' ) && method_exists( \Elementor\Plugin::instance()->kits_manager, 'update_kit_settings_based_on_option' ) ) {
		\Elementor\Plugin::instance()->kits_manager->update_kit_settings_based_on_option( $page_key, $value );
		return;
	}

	$kits = get_posts(
		array(
			'post_type'      => 'elementor_library',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_elementor_template_type', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => 'kit', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	if ( empty( $kits ) ) {
		return;
	}

	$settings = get_post_meta( (int) $kits[0], '_elementor_page_settings', true );
	if ( ! is_array( $settings ) ) {
		$settings = array();
	}
	$settings[ $page_key ] = $value;
	update_post_meta( (int) $kits[0], '_elementor_page_settings', $settings );
}

function solace_extra_woo_sync_kit_option_hooks() {
	foreach ( array( 'woocommerce_cart_page_id', 'woocommerce_checkout_page_id', 'woocommerce_myaccount_page_id', 'woocommerce_shop_page_id' ) as $field ) {
		add_action(
			'update_option_' . $field,
			function ( $old_value, $value ) use ( $field ) {
				solace_extra_woo_sync_kit_page_option( $field, $value );
			},
			10,
			2
		);
	}
}
solace_extra_woo_sync_kit_option_hooks();
/**
 * 12) URL RESOLUTION — "View Cart" / "Proceed to checkout" never point at the
 * template permalink.
 *
 * The WooCommerce option may store a Site Builder template ID (CPT
 * `solace-sitebuilder`) as the page. `wc_get_page_permalink()` then resolves
 * to the template permalink (/solace-sitebuilder/cart-1/), which is not the
 * page a customer expects. When the stored value is such a template, point
 * the URL at the real base page (solace_extra_woo_base_page_id()) that renders the part when the
 * template is active and selected (or the default WooCommerce block when it
 * is not) — the template stays the renderer, never the link target.
 */
function solace_extra_woo_resolve_page_permalink( $permalink, $page ) {
	$options = array(
		'cart'      => 'woocommerce_cart_page_id',
		'checkout'  => 'woocommerce_checkout_page_id',
		'myaccount' => 'woocommerce_myaccount_page_id',
	);

	if ( ! isset( $options[ $page ] ) ) {
		return $permalink;
	}

	$stored = absint( get_option( $options[ $page ], 0 ) );

	// A real page (or an empty value) is already the right link target.
	if ( ! $stored || 'solace-sitebuilder' !== get_post_type( $stored ) ) {
		return $permalink;
	}

	$bases = solace_extra_woo_base_pages();

	if ( empty( $bases[ $page ] ) ) {
		return $permalink;
	}

	$base = absint( $bases[ $page ] );

	if ( $base && 'publish' === get_post_status( $base ) ) {
		return get_permalink( $base );
	}

	return $permalink;
}

foreach ( array( 'cart', 'checkout', 'myaccount' ) as $solace_extra_woo_page ) {
	add_filter(
		'woocommerce_get_' . $solace_extra_woo_page . '_page_permalink',
		function ( $permalink ) use ( $solace_extra_woo_page ) {
			return solace_extra_woo_resolve_page_permalink( $permalink, $solace_extra_woo_page );
		},
		10,
		1
	);
}
unset( $solace_extra_woo_page );

/**
 * 13) OLD SLUG REDIRECT — renaming a selected Cart/Checkout/My Account part
 * also renames its base page (Solace_Extra_Admin::sync_woo_base_page_slug()),
 * e.g. /cart/ => /your-cart/. Pages get no _wp_old_slug from WordPress, so the
 * old address would 404 and redirect_canonical() would guess some other page
 * starting with "cart". Send it to the page's current address instead.
 * Runs before redirect_canonical() (priority 10).
 */
add_action( 'template_redirect', 'solace_extra_woo_redirect_old_page_slug', 9 );

function solace_extra_woo_redirect_old_page_slug() {
	if ( ! is_404() ) {
		return;
	}

	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path = trim( (string) wp_parse_url( $uri, PHP_URL_PATH ), '/' );
	$home = trim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );

	if ( '' !== $home && 0 === strpos( $path, $home ) ) {
		$path = trim( substr( $path, strlen( $home ) ), '/' );
	}

	if ( '' === $path || false !== strpos( $path, '/' ) ) {
		return;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_solace_old_slug', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => sanitize_title( $path ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);

	if ( empty( $pages ) ) {
		return;
	}

	wp_safe_redirect( get_permalink( (int) $pages[0] ), 301, 'Solace Extra' );
	exit;
}

/**
 * 14) EMPTY PAGE FALLBACK — an empty Cart/Checkout/My Account page uses its
 * base page at runtime.
 *
 * With the option empty, wc_get_page_id() returns -1: the Cart block's
 * "Proceed to checkout" button gets no link (storePages permalink false) and
 * spins forever, is_cart()/is_checkout() stay false on the real pages, and
 * PHP links fall back to the homepage. While a base page exists (the page the
 * part renders at), wc_get_page_id() returns that page instead, whatever it
 * holds. The option itself stays empty, so WooCommerce Settings still shows
 * what was chosen and the notice under the dropdown names the fallback.
 *
 * @param string $part cart|checkout|myaccount.
 * @return int Page ID, or 0 when there is nothing to fall back to.
 */
function solace_extra_woo_empty_page_fallback_id( $part ) {
	static $cache = array();

	if ( isset( $cache[ $part ] ) ) {
		return $cache[ $part ];
	}

	// The base page whatever it holds: a WooCommerce block or shortcode, or an
	// Elementor design with the Solace Cart/Checkout/My Account widgets.
	$markers = solace_extra_woo_base_page_markers();
	$id      = isset( $markers[ $part ] ) ? solace_extra_woo_base_page_id( $part ) : 0;

	$cache[ $part ] = (int) $id;

	return $cache[ $part ];
}

foreach ( array( 'cart', 'checkout', 'myaccount' ) as $solace_extra_woo_page ) {
	add_filter(
		'woocommerce_get_' . $solace_extra_woo_page . '_page_id',
		function ( $page_id ) use ( $solace_extra_woo_page ) {
			if ( (int) $page_id > 0 ) {
				return $page_id;
			}
			$fallback = solace_extra_woo_empty_page_fallback_id( $solace_extra_woo_page );
			return $fallback ? $fallback : $page_id;
		}
	);
}
unset( $solace_extra_woo_page );

/**
 * 15) OWN PAGE PER PART — every Cart/Checkout/My Account part has its own page,
 * like a page designed with Elementor. The page holds the part's address (slug
 * from its title, renamed through Rename or Quick Edit) and nothing else; the
 * part renders there while it is selected in WooCommerce Settings. Pages of
 * different parts, and the WooCommerce pages, never share an address.
 *
 * Part meta `_solace_woo_page_id` points at the page; page meta
 * `_solace_part_owner` points back at the part.
 */

/**
 * The part that owns a page, or 0.
 *
 * @param int $page_id Page ID.
 * @return int
 */
function solace_extra_woo_page_owner( $page_id ) {
	$page_id = absint( $page_id );
	$owner   = $page_id ? absint( get_post_meta( $page_id, '_solace_part_owner', true ) ) : 0;

	// Both ways: a copied or imported page whose part points elsewhere (or no
	// longer exists) is an ordinary page.
	if ( ! $owner || 'solace-sitebuilder' !== get_post_type( $owner ) || absint( get_post_meta( $owner, '_solace_woo_page_id', true ) ) !== $page_id ) {
		return 0;
	}

	return $owner;
}

/**
 * A page slug no other page or public post uses: -2, -3, ... is appended.
 *
 * wp_unique_post_slug() only compares a page with other pages, while with a
 * /%postname%/ permalink structure a post with the same slug answers at the
 * same address.
 *
 * @param string $slug    Wanted slug.
 * @param int    $page_id Page being named (excluded), 0 for a new page.
 * @return string
 */
function solace_extra_woo_unique_page_slug( $slug, $page_id = 0 ) {
	global $wpdb;

	$slug  = sanitize_title( $slug );
	$types = array_values( array_diff( get_post_types( array( 'public' => true ) ), array( 'attachment', 'solace-sitebuilder' ) ) );
	if ( '' === $slug || empty( $types ) ) {
		return $slug;
	}
	$in = implode( ',', array_fill( 0, count( $types ), '%s' ) );

	$candidate = $slug;
	for ( $i = 2; $i < 100; $i++ ) {
		$taken = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-off check while naming a page.
			$wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- Replacements are passed as one array.
				"SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND ID <> %d AND post_status NOT IN ( 'trash', 'auto-draft', 'inherit' ) AND post_type IN ( {$in} ) LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $in is a list of %s placeholders.
				array_merge( array( $candidate, absint( $page_id ) ), $types )
			)
		);
		if ( ! $taken ) {
			return $candidate;
		}
		$candidate = $slug . '-' . $i;
	}

	return $candidate;
}

/**
 * The page a part owns, created on first use.
 *
 * @param int    $part_id Site Builder part.
 * @param string $part    cart|checkout|myaccount.
 * @param bool   $create  Create the page when it is missing.
 * @return int Page ID, or 0.
 */
function solace_extra_woo_part_page_id( $part_id, $part, $create = true ) {
	$part_id = absint( $part_id );
	if ( ! $part_id || ! in_array( $part, array( 'cart', 'checkout', 'myaccount' ), true ) || 'solace-sitebuilder' !== get_post_type( $part_id ) ) {
		return 0;
	}

	$page_id = absint( get_post_meta( $part_id, '_solace_woo_page_id', true ) );
	// A page trashed from the Pages list while its part lives on comes back,
	// with its address, rather than a second page being made.
	if ( $create && $page_id && 'trash' === get_post_status( $page_id ) && 'trash' !== get_post_status( $part_id ) && (int) solace_extra_woo_page_owner( $page_id ) === $part_id ) {
		wp_untrash_post( $page_id );
	}
	if ( $page_id && 'page' === get_post_type( $page_id ) && 'trash' !== get_post_status( $page_id ) && (int) solace_extra_woo_page_owner( $page_id ) === $part_id ) {
		if ( 'publish' !== get_post_status( $page_id ) && $create ) {
			wp_update_post( array( 'ID' => $page_id, 'post_status' => 'publish' ) );
		}
		return $page_id;
	}

	if ( ! $create || 'publish' !== get_post_status( $part_id ) ) {
		return 0;
	}

	$title   = get_the_title( $part_id );
	$page_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => solace_extra_woo_unique_page_slug( '' !== $title ? $title : $part ),
			'post_content' => '',
			'post_author'  => (int) get_post_field( 'post_author', $part_id ),
			'meta_input'   => array( '_solace_part_owner' => $part_id ),
		),
		true
	);
	if ( is_wp_error( $page_id ) || ! $page_id ) {
		return 0;
	}

	update_post_meta( $part_id, '_solace_woo_page_id', (int) $page_id );

	return (int) $page_id;
}

/**
 * A published Cart/Checkout/My Account part gets its page right away, so its
 * address exists before it is ever switched on.
 */
add_action(
	'transition_post_status',
	function ( $new_status, $old_status, $post ) {
		if ( 'publish' !== $new_status || 'solace-sitebuilder' !== $post->post_type ) {
			return;
		}
		$part = (string) get_post_meta( $post->ID, '_solace_template', true );
		if ( in_array( $part, array( 'cart', 'checkout', 'myaccount' ), true ) ) {
			solace_extra_woo_part_page_id( $post->ID, $part, true );
		}
	},
	20,
	3
);

/**
 * Choosing a part's own page in WooCommerce Settings means choosing the part.
 */
foreach ( array( 'cart', 'checkout', 'myaccount' ) as $solace_extra_woo_page ) {
	add_filter(
		'pre_update_option_woocommerce_' . $solace_extra_woo_page . '_page_id',
		function ( $value ) {
			$owner = solace_extra_woo_page_owner( $value );
			return $owner ? $owner : $value;
		}
	);
}
unset( $solace_extra_woo_page );

/**
 * Pages list: label a part's page.
 */
add_filter(
	'display_post_states',
	function ( $states, $post ) {
		$owner = solace_extra_woo_page_owner( $post->ID );
		if ( $owner ) {
			/* translators: %s: Site Builder part title. */
			$states['solace_part_page'] = sprintf( __( 'Solace Site Builder: %s', 'solace-extra' ), get_the_title( $owner ) );
		}
		return $states;
	},
	10,
	2
);

/**
 * Remember the old address when a part's page is renamed (Rename or Quick
 * Edit), for solace_extra_woo_redirect_old_page_slug(). At most 5 are kept.
 */
add_action(
	'post_updated',
	function ( $post_id, $after, $before ) {
		if ( 'page' !== $after->post_type || ! solace_extra_woo_page_owner( $post_id ) ) {
			return;
		}
		if ( 'publish' !== $before->post_status || '' === $before->post_name || $before->post_name === $after->post_name ) {
			return;
		}
		delete_post_meta( $post_id, '_solace_old_slug', $before->post_name );
		add_post_meta( $post_id, '_solace_old_slug', $before->post_name );
		delete_post_meta( $post_id, '_solace_old_slug', $after->post_name );
		$old = (array) get_post_meta( $post_id, '_solace_old_slug' );
		foreach ( array_slice( $old, 0, max( 0, count( $old ) - 5 ) ) as $stale ) {
			delete_post_meta( $post_id, '_solace_old_slug', $stale );
		}
	},
	10,
	3
);

/**
 * Send visitors to the page the store uses:
 *  - a part's page while another page is selected in WooCommerce Settings;
 *  - the WooCommerce page a selected part replaced, to the part's page.
 * 302, because the selection can change back.
 */
add_action(
	'template_redirect',
	function () {
		if ( is_admin() || ! class_exists( 'WooCommerce' ) || ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) ) {
			return;
		}
		$page_id = is_page() ? (int) get_queried_object_id() : 0;

		// An endpoint under the page a selected part replaced (old links such as
		// /my-account/orders/) is a 404 there: match it by its address.
		if ( ! $page_id && is_404() ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Compared only.
			$request = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
			foreach ( array( 'cart', 'checkout', 'myaccount' ) as $type ) {
				$base = (int) solace_extra_woo_base_page_id( $type );
				$from = $base ? (string) wp_make_link_relative( get_permalink( $base ) ) : '';
				if ( '' !== $from && '/' !== $from && 0 === strpos( $request, $from ) && solace_extra_woo_is_part_post( absint( get_option( 'woocommerce_' . $type . '_page_id', 0 ) ), $type ) ) {
					$page_id = $base;
					break;
				}
			}
		}
		if ( ! $page_id ) {
			return;
		}
		$owner   = solace_extra_woo_page_owner( $page_id );

		foreach ( array( 'cart', 'checkout', 'myaccount' ) as $type ) {
			$selected = absint( get_option( 'woocommerce_' . $type . '_page_id', 0 ) );
			$target   = 0;

			if ( $owner && (string) get_post_meta( $owner, '_solace_template', true ) === $type && $selected !== $owner ) {
				$target = (int) wc_get_page_id( $type );
				$target = $target > 0 ? $target : 0;
				if ( solace_extra_woo_is_part_post( $target, $type ) ) {
					$target = solace_extra_woo_part_page_id( $target, $type, true );
				}
			} elseif ( ! $owner && solace_extra_woo_is_part_post( $selected, $type ) && $page_id === (int) solace_extra_woo_base_page_id( $type ) ) {
				$target = solace_extra_woo_part_page_id( $selected, $type, true );
			}

			if ( $target && $target !== $page_id && 'publish' === get_post_status( $target ) ) {
				// Swap the page part of the address only, so endpoints
				// (/my-account/orders/, /checkout/order-received/123/) and the
				// query string survive.
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Only re-assembled into a same-site URL.
				$request = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
				$from    = (string) wp_make_link_relative( get_permalink( $page_id ) );
				$to      = (string) wp_make_link_relative( get_permalink( $target ) );
				$url = get_permalink( $target );
				if ( '' !== $from && 0 === strpos( $request, $from ) ) {
					// Relative links carry the full path (subdirectory included).
					$home = wp_parse_url( home_url() );
					$url  = $home['scheme'] . '://' . $home['host'] . ( isset( $home['port'] ) ? ':' . $home['port'] : '' ) . $to . substr( $request, strlen( $from ) );
				}
				wp_safe_redirect( $url, 302, 'Solace Extra' );
				exit;
			}
		}
	},
	8
);

/**
 * Restoring a part from the trash restores its page too (it was trashed with
 * the part), so the part gets its own address back.
 */
add_action(
	'untrashed_post',
	function ( $post_id ) {
		if ( 'solace-sitebuilder' !== get_post_type( $post_id ) ) {
			return;
		}
		$own = absint( get_post_meta( $post_id, '_solace_woo_page_id', true ) );
		if ( $own && 'trash' === get_post_status( $own ) && (int) solace_extra_woo_page_owner( $own ) === (int) $post_id ) {
			wp_untrash_post( $own );
			if ( 'publish' === get_post_status( $post_id ) ) {
				wp_update_post( array( 'ID' => $own, 'post_status' => 'publish' ) );
			}
		}
	}
);

/**
 * A post (or any public type other than pages) may not take the slug of a
 * part's page: with a /%postname%/ permalink structure the page wins that
 * address and the post could never be reached.
 */
add_filter(
	'wp_unique_post_slug',
	function ( $slug, $post_id, $post_status, $post_type ) {
		if ( 'page' === $post_type || 'solace-sitebuilder' === $post_type || 'attachment' === $post_type || ! is_post_type_viewable( $post_type ) ) {
			return $slug;
		}
		$base = $slug;
		for ( $i = 2; $i < 100; $i++ ) {
			$page = get_page_by_path( $slug, OBJECT, 'page' );
			if ( ! $page || ! solace_extra_woo_page_owner( $page->ID ) ) {
				return $slug;
			}
			$slug = $base . '-' . $i;
		}
		return $slug;
	},
	10,
	4
);

/**
 * Pages of parts that are not in use hold no content of their own (they
 * forward to the page the store uses): keep them out of site search and the
 * core sitemap.
 *
 * @return int[]
 */
function solace_extra_woo_unused_part_pages() {
	global $wpdb;

	$ids = $wpdb->get_col( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_solace_part_owner'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Small set, read once per search/sitemap request.
	$out = array();
	foreach ( (array) $ids as $id ) {
		$owner = solace_extra_woo_page_owner( $id );
		if ( ! $owner ) {
			continue;
		}
		$type = (string) get_post_meta( $owner, '_solace_template', true );
		if ( (int) get_option( 'woocommerce_' . $type . '_page_id', 0 ) !== (int) $owner ) {
			$out[] = (int) $id;
		}
	}

	return $out;
}

add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
			return;
		}
		$unused = solace_extra_woo_unused_part_pages();
		if ( $unused ) {
			$query->set( 'post__not_in', array_merge( (array) $query->get( 'post__not_in' ), $unused ) );
		}
	}
);

add_filter(
	'wp_sitemaps_posts_query_args',
	function ( $args, $post_type ) {
		if ( 'page' === $post_type ) {
			$unused = solace_extra_woo_unused_part_pages();
			if ( $unused ) {
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- Sitemap query, a handful of unused part pages.
				$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), $unused );
			}
		}
		return $args;
	},
	10,
	2
);

/**
 * Keep a part's title and its page's title the same, both ways: editing the
 * page in Pages (title field or Quick Edit) renames the part, and renaming
 * the part anywhere renames its page. Only titles: addresses change through
 * the slug field or Rename.
 */
add_action(
	'post_updated',
	function ( $post_id, $after, $before ) {
		static $syncing = false;

		if ( $syncing || $after->post_title === $before->post_title || 'trash' === $after->post_status ) {
			return;
		}

		if ( 'page' === $after->post_type ) {
			$target = solace_extra_woo_page_owner( $post_id );
		} elseif ( 'solace-sitebuilder' === $after->post_type ) {
			$own    = absint( get_post_meta( $post_id, '_solace_woo_page_id', true ) );
			$target = ( $own && (int) solace_extra_woo_page_owner( $own ) === (int) $post_id ) ? $own : 0;
		} else {
			return;
		}

		if ( ! $target || (string) get_post_field( 'post_title', $target ) === $after->post_title ) {
			return;
		}

		$syncing = true;
		wp_update_post(
			array(
				'ID'         => $target,
				'post_title' => $after->post_title,
			)
		);
		$syncing = false;
	},
	20,
	3
);
