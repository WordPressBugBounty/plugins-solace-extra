<?php
/**
 * Shared helpers for the Solace Site Builder.
 *
 * These functions are the single source of truth for three things that used to be
 * reimplemented in several places and had drifted apart:
 *
 *  1. How a part status is written   -> solace_set_part_status()
 *  2. Which part of a type is active -> solace_get_active_part_id()
 *  3. Whether a part applies to the current request
 *     -> solace_current_condition_set() + solace_resolve_conditions_part()
 *
 * @package Solace_Extra
 * @since   1.7.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'solace_sitebuilder_part_slugs' ) ) {
	/**
	 * The part slugs the Site Builder ships.
	 *
	 * Used to validate anything that arrives from a request before it is turned
	 * into a meta key or a taxonomy term.
	 *
	 * @since 1.7.2
	 * @return string[]
	 */
	function solace_sitebuilder_part_slugs() {
		return array(
			'header',
			'footer',
			'singleproduct',
			'shopproduct',
			'purchase-summary',
			'blogsinglepost',
			'blogarchive',
			'404',
			'cart',
			'checkout',
			'myaccount',
		);
	}
}

if ( ! function_exists( 'solace_set_part_status' ) ) {
	/**
	 * Write a Site Builder part status.
	 *
	 * This is the ONLY supported way to write `_solace_{part}_status`. Every reader
	 * in the plugin compares against the literal strings '1' and '0', but the various
	 * writers used to store int 1, bool true, and bool false (which WordPress persists
	 * as an empty string). Routing every write through here keeps the meta canonical.
	 *
	 * @since 1.7.2
	 * @param int    $post_id Site Builder post ID.
	 * @param string $part    Part slug, e.g. 'header', 'footer', 'singleproduct'.
	 * @param bool   $on      Whether the part should be active.
	 * @return bool True when the meta holds the requested value afterwards.
	 */
	function solace_set_part_status( $post_id, $part, $on ) {
		$post_id = absint( $post_id );
		$part    = sanitize_key( $part );

		if ( ! $post_id || '' === $part ) {
			return false;
		}

		$value = $on ? '1' : '0';

		// update_post_meta() returns false when the stored value is already identical,
		// which is not an error for our callers.
		if ( get_post_meta( $post_id, '_solace_' . $part . '_status', true ) === $value ) {
			return true;
		}

		return (bool) update_post_meta( $post_id, '_solace_' . $part . '_status', $value );
	}
}

if ( ! function_exists( 'solace_part_is_renderable' ) ) {
	/**
	 * Whether a Site Builder post actually has an Elementor design to render.
	 *
	 * A part that was created but never opened in Elementor has no `_elementor_data`.
	 * Rendering it produces a blank page, so callers treat it as inactive and fall
	 * back to the theme template instead.
	 *
	 * @since 1.7.2
	 * @param int $post_id Site Builder post ID.
	 * @return bool
	 */
	function solace_part_is_renderable( $post_id ) {
		$post_id = absint( $post_id );

		if ( ! $post_id ) {
			return false;
		}

		$data = get_post_meta( $post_id, '_elementor_data', true );

		if ( empty( $data ) || '[]' === $data ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'solace_get_active_part_id' ) ) {
	/**
	 * Resolve the active, published, renderable part of a given type.
	 *
	 * Used by BOTH the `override_*` template decision and the template that renders,
	 * so the two can never disagree about which part is in play.
	 *
	 * @since 1.7.2
	 * @param string $part Part slug, e.g. 'singleproduct', 'shopproduct', '404'.
	 * @return int|null Post ID, or null when nothing renderable is active.
	 */
	function solace_get_active_part_id( $part ) {
		$part = sanitize_key( $part );

		if ( '' === $part ) {
			return null;
		}

		$ids = get_posts(
			array(
				'post_type'        => 'solace-sitebuilder',
				'post_status'      => 'publish',
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Part status lookup is the whole point of this query.
				'meta_key'         => '_solace_' . $part . '_status',
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Only active parts are eligible.
				'meta_value'       => '1',
				'posts_per_page'   => 1,
				'orderby'          => 'ID',
				'order'            => 'DESC',
				'fields'           => 'ids',
				'no_found_rows'    => true,
				'suppress_filters' => false,
			)
		);

		if ( empty( $ids ) ) {
			return null;
		}

		if ( ! solace_part_is_renderable( $ids[0] ) ) {
			return null;
		}

		return (int) $ids[0];
	}
}

if ( ! function_exists( 'solace_current_condition_set' ) ) {
	/**
	 * Classify the current request into the set of condition values it satisfies.
	 *
	 * The old code asked "what single page type is this?" and then compared that one
	 * value against each saved condition. That never worked, because one request
	 * legitimately satisfies several conditions at once: a single product page is
	 * "Entire Website" AND "All Products" AND "All Singulars".
	 *
	 * Returning a set makes matching a plain `in_array()` and lets
	 * solace_condition_specificity() decide which of the matches wins.
	 *
	 * @since 1.7.2
	 * @return string[] Condition values satisfied by this request. Always contains 'basic-global'.
	 */
	function solace_current_condition_set() {
		$set = array( 'basic-global' );

		if ( class_exists( 'WooCommerce' ) ) {
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				$set[] = 'product|all';
				$set[] = 'special-shop';
				$set[] = 'basic-archives';
			} elseif ( function_exists( 'is_product' ) && is_product() ) {
				$set[] = 'product|all';
				$set[] = 'basic-singulars';
			} elseif (
				( function_exists( 'is_cart' ) && is_cart() ) ||
				( function_exists( 'is_checkout' ) && is_checkout() ) ||
				( function_exists( 'is_account_page' ) && is_account_page() )
			) {
				$set[] = 'product|all';
				$set[] = 'basic-singulars';
				$set[] = 'page|all';
			} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
				$set[] = 'product|all';
				$set[] = 'product|all|taxarchive|product_cat';
				$set[] = 'basic-archives';
			} elseif ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
				$set[] = 'product|all';
				$set[] = 'product|all|taxarchive|product_tag';
				$set[] = 'basic-archives';
			} elseif ( function_exists( 'is_tax' ) && is_tax( 'product_brand' ) ) {
				$set[] = 'product|all';
				$set[] = 'product|all|taxarchive|product_brand';
				$set[] = 'basic-archives';
			} elseif ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) {
				// Any other product taxonomy: attributes, shipping classes, or a
				// custom one. Mirrors check_current_page_type(), which names the
				// taxonomy in the token so a condition can target it.
				$queried = get_queried_object();
				$set[]   = 'product|all';

				if ( $queried && isset( $queried->taxonomy ) ) {
					$set[] = 'product|all|taxarchive|' . $queried->taxonomy;
				} else {
					$set[] = 'product|all|taxarchive';
				}

				$set[] = 'basic-archives';
			} elseif ( is_search() && 'product' === get_query_var( 'post_type' ) ) {
				$set[] = 'product|all';
				$set[] = 'product|all|archive';
				$set[] = 'special-search';
				$set[] = 'basic-archives';
			}

			// A WooCommerce surface was matched above; nothing else applies.
			if ( count( $set ) > 1 ) {
				return $set;
			}
		}

		if ( is_404() ) {
			$set[] = 'special-404';
			return $set;
		}

		if ( is_front_page() ) {
			$set[] = 'special-front';

			// A static front page is still a Page; the blog-as-front-page is not.
			if ( ! is_home() ) {
				$set[] = 'page|all';
				$set[] = 'basic-singulars';
			}

			return $set;
		}

		if ( is_home() ) {
			$set[] = 'special-blog';
			$set[] = 'basic-archives';
			return $set;
		}

		if ( is_search() ) {
			$set[] = 'special-search';
			$set[] = 'basic-archives';
			return $set;
		}

		if ( is_author() ) {
			$set[] = 'special-author';
			$set[] = 'basic-archives';
			return $set;
		}

		if ( is_date() ) {
			$set[] = 'special-date';
			$set[] = 'basic-archives';
			return $set;
		}

		if ( is_category() ) {
			$set[] = 'post|all|taxarchive|category';
			$set[] = 'basic-archives';
			return $set;
		}

		if ( is_tag() ) {
			$set[] = 'post|all|taxarchive|post_tag';
			$set[] = 'basic-archives';
			return $set;
		}

		if ( is_post_type_archive() ) {
			$set[] = 'post|all|archives';
			$set[] = 'basic-archives';
			return $set;
		}

		if ( is_archive() ) {
			$set[] = 'basic-archives';
			return $set;
		}

		if ( is_page() ) {
			$set[] = 'page|all';
			$set[] = 'basic-singulars';
			return $set;
		}

		if ( is_single() ) {
			$set[] = 'post|all';
			$set[] = 'basic-singulars';
			return $set;
		}

		if ( is_singular() ) {
			$set[] = 'basic-singulars';
			return $set;
		}

		// Feeds, trackbacks, embeds and anything unclassified keep the legacy fallback.
		$set[] = 'basic-archives';

		return $set;
	}
}

if ( ! function_exists( 'solace_condition_specificity' ) ) {
	/**
	 * Score how specific a condition value is. Higher wins.
	 *
	 * This is what makes "Entire Website" lose to "All Pages" on a page, which is
	 * the behaviour users expect and the old renderer never delivered.
	 *
	 * @since 1.7.2
	 * @param string $value Saved condition value.
	 * @return int 0 = entire site, 1 = broad group, 2 = post type, 3 = specific surface.
	 */
	function solace_condition_specificity( $value ) {
		if ( 'basic-global' === $value ) {
			return 0;
		}

		if ( in_array( $value, array( 'basic-singulars', 'basic-archives' ), true ) ) {
			return 1;
		}

		if ( in_array( $value, array( 'page|all', 'post|all', 'product|all' ), true ) ) {
			return 2;
		}

		// special-* and the taxarchive values.
		return 3;
	}
}

if ( ! function_exists( 'solace_condition_set_most_specific' ) ) {
	/**
	 * Reduce a condition set back to its single most specific value.
	 *
	 * Only for the legacy callers that still expect check_current_page_type() to
	 * return one string. New code should use the set directly.
	 *
	 * @since 1.7.2
	 * @param string[]|null $set Condition set, or null to classify the current request.
	 * @return string
	 */
	function solace_condition_set_most_specific( $set = null ) {
		if ( null === $set ) {
			$set = solace_current_condition_set();
		}

		if ( empty( $set ) || ! is_array( $set ) ) {
			return 'basic-global';
		}

		$best       = 'basic-global';
		$best_score = -1;

		foreach ( $set as $value ) {
			$score = solace_condition_specificity( $value );

			if ( $score > $best_score ) {
				$best_score = $score;
				$best       = $value;
			}
		}

		return $best;
	}
}

if ( ! function_exists( 'solace_resolve_conditions_parts' ) ) {
	/**
	 * Resolve every header/footer part that applies to this request.
	 *
	 * Parts are allowed to stack: a site-wide header and a page-specific one can
	 * both render. They come back ordered from most general to most specific, so
	 * a `basic-global` part sits above a `page|all` part, which sits above a
	 * `special-shop` part. That matches how a site-wide announcement bar reads
	 * above a section header, and it is the order customers already see.
	 *
	 * A part still only qualifies once: any matching `exclude` removes it, and
	 * its position comes from its single most specific matching `include`.
	 *
	 * @since 1.7.2
	 * @param string $part Either 'header' or 'footer'.
	 * @return int[] Post IDs in render order. Empty when nothing applies.
	 */
	function solace_resolve_conditions_parts( $part ) {
		$part = sanitize_key( $part );

		if ( ! in_array( $part, array( 'header', 'footer' ), true ) ) {
			return array();
		}

		if ( 'footer' === $part ) {
			$rows = function_exists( 'get_solace_footer_conditions' ) ? get_solace_footer_conditions() : array();
		} else {
			$rows = function_exists( 'get_solace_header_conditions' ) ? get_solace_header_conditions() : array();
		}

		if ( empty( $rows ) || ! is_array( $rows ) ) {
			return array();
		}

		$page_set = solace_current_condition_set();
		$matched  = array();

		foreach ( $rows as $row ) {
			if ( empty( $row['post_id'] ) ) {
				continue;
			}

			$post_id = (int) $row['post_id'];

			// Draft, pending, private and trashed parts never render on the front end.
			if ( 'publish' !== get_post_status( $post_id ) ) {
				continue;
			}

			$conditions = isset( $row['conditions'] ) && is_array( $row['conditions'] ) ? $row['conditions'] : array();

			if ( empty( $conditions ) ) {
				continue;
			}

			// Excludes win over includes. Because $page_set holds every value the
			// request satisfies, an umbrella exclude such as "All Archives" now
			// actually removes the part on a category archive.
			$excluded = false;

			foreach ( $conditions as $condition ) {
				if ( ! is_array( $condition ) || ! isset( $condition['type'], $condition['value'] ) ) {
					continue;
				}

				if ( 'exclude' === $condition['type'] && in_array( $condition['value'], $page_set, true ) ) {
					$excluded = true;
					break;
				}
			}

			if ( $excluded ) {
				continue;
			}

			// The most specific matching include decides where this part sits.
			$score = -1;

			foreach ( $conditions as $condition ) {
				if ( ! is_array( $condition ) || ! isset( $condition['type'], $condition['value'] ) ) {
					continue;
				}

				if ( 'include' === $condition['type'] && in_array( $condition['value'], $page_set, true ) ) {
					$score = max( $score, solace_condition_specificity( $condition['value'] ) );
				}
			}

			if ( $score < 0 ) {
				continue;
			}

			$matched[ $post_id ] = $score;
		}

		if ( empty( $matched ) ) {
			return array();
		}

		// General first, specific last. Same score keeps the older part above the
		// newer one, so adding a part never reshuffles what is already there.
		$ids = array_keys( $matched );

		usort(
			$ids,
			function ( $a, $b ) use ( $matched ) {
				if ( $matched[ $a ] === $matched[ $b ] ) {
					return $a <=> $b;
				}

				return $matched[ $a ] <=> $matched[ $b ];
			}
		);

		return $ids;
	}
}

if ( ! function_exists( 'solace_resolve_conditions_part' ) ) {
	/**
	 * The single most specific part that applies to this request.
	 *
	 * Issue #1: the header/footer renderer shows exactly one part, so both the
	 * renderer and the asset bridge resolve through this function. The plural
	 * variant is kept for callers that need to enumerate every match.
	 *
	 * @since 1.7.2
	 * @param string $part Either 'header' or 'footer'.
	 * @return int|null
	 */
	function solace_resolve_conditions_part( $part ) {
		$ids = solace_resolve_conditions_parts( $part );

		return $ids ? (int) end( $ids ) : null;
	}
}

if ( ! function_exists( 'solace_migrate_status_meta' ) ) {
	/**
	 * One-time migration that canonicalises legacy Site Builder meta.
	 *
	 * Two formats accumulated over time:
	 *
	 *  - `_solace_{part}_status` was written as int 1, '1', bool true, bool false
	 *    (persisted as an empty string) and '0', while every reader compares
	 *    against the literal strings '1' and '0'.
	 *  - `_solace_template` stores the part slug when saved through the conditions
	 *    flow, but the full meta key (`_solace_singleproduct_status`) when saved
	 *    through the create flow.
	 *
	 * Runs once, guarded by the `solace_status_meta_v2` option.
	 *
	 * @since 1.7.2
	 * @return void
	 */
	function solace_migrate_status_meta() {
		if ( get_option( 'solace_status_meta_v2' ) ) {
			return;
		}

		global $wpdb;

		$status_like = $wpdb->esc_like( '_solace_' ) . '%' . $wpdb->esc_like( '_status' );

		// Which posts the three statements below will touch. Collected first,
		// because once the values are rewritten these WHERE clauses no longer
		// match them, and their caches would be left holding the old values.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time migration; the cache is cleared below.
		$affected = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT pm.post_id
				 FROM {$wpdb->postmeta} pm
				 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id AND p.post_type = 'solace-sitebuilder'
				 WHERE ( pm.meta_key LIKE %s AND pm.meta_value IN ('', '0', 'false', '1', 'true') )
				 OR ( pm.meta_key = '_solace_template' AND pm.meta_value LIKE %s )",
				$status_like,
				$status_like
			)
		);

		// Falsy legacy values -> '0'.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time migration; the cache is cleared below.
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->postmeta} pm
				 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id AND p.post_type = 'solace-sitebuilder'
				 SET pm.meta_value = '0'
				 WHERE pm.meta_key LIKE %s
				 AND pm.meta_value IN ('', '0', 'false')",
				$status_like
			)
		);

		// Truthy legacy values -> '1'.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time migration; the cache is cleared below.
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->postmeta} pm
				 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id AND p.post_type = 'solace-sitebuilder'
				 SET pm.meta_value = '1'
				 WHERE pm.meta_key LIKE %s
				 AND pm.meta_value IN ('1', 'true')",
				$status_like
			)
		);

		// `_solace_template` holding a meta key -> the bare part slug.
		// '_solace_' is 8 characters and '_status' is 7, so the slug starts at
		// offset 9 and is (length - 15) characters long.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time migration; the cache is cleared below.
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->postmeta} pm
				 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id AND p.post_type = 'solace-sitebuilder'
				 SET pm.meta_value = SUBSTRING( pm.meta_value, 9, CHAR_LENGTH( pm.meta_value ) - 15 )
				 WHERE pm.meta_key = '_solace_template'
				 AND pm.meta_value LIKE %s",
				$status_like
			)
		);

		// These rows were changed behind WordPress's back, so anything already
		// cached for them still holds the pre-migration values. Without this, a
		// site running a persistent object cache would keep reading the old
		// status until that cache expired -- and the flag set below means the
		// migration would never run again to correct it.
		foreach ( $affected as $affected_post_id ) {
			clean_post_cache( (int) $affected_post_id );
		}

		update_option( 'solace_status_meta_v2', 1, false );
	}
}
add_action( 'admin_init', 'solace_migrate_status_meta' );

if ( ! function_exists( 'solace_find_widget_settings_in_elementor_data' ) ) {
	/**
	 * Find the first widget of a given type inside an Elementor document.
	 *
	 * @since 1.7.2
	 * @param int    $post_id     Post holding `_elementor_data`.
	 * @param string $widget_type Elementor widget name, e.g. 'solace-extra-post-archive'.
	 * @return array|null The widget's settings array, or null when not present.
	 */
	function solace_find_widget_settings_in_elementor_data( $post_id, $widget_type ) {
		$raw = get_post_meta( absint( $post_id ), '_elementor_data', true );

		if ( empty( $raw ) ) {
			return null;
		}

		$data = is_string( $raw ) ? json_decode( $raw, true ) : $raw;

		if ( ! is_array( $data ) ) {
			return null;
		}

		$stack = $data;

		while ( $stack ) {
			$node = array_shift( $stack );

			if ( ! is_array( $node ) ) {
				continue;
			}

			if ( isset( $node['widgetType'] ) && $widget_type === $node['widgetType'] ) {
				return isset( $node['settings'] ) && is_array( $node['settings'] ) ? $node['settings'] : array();
			}

			if ( ! empty( $node['elements'] ) && is_array( $node['elements'] ) ) {
				foreach ( $node['elements'] as $child ) {
					$stack[] = $child;
				}
			}
		}

		return null;
	}
}

if ( ! function_exists( 'solace_align_archive_posts_per_page' ) ) {
	/**
	 * Keep the main archive query paginating in step with the Blog Archive widget.
	 *
	 * The widget renders its own WP_Query, but pagination links point at the
	 * canonical /page/2/ URLs, and those are served by the MAIN query. If the
	 * widget shows 2 posts per page while the site setting is 10, /page/2/ of a
	 * four-post category is a 404 and the widget's own links lead nowhere.
	 *
	 * Aligning the main query's posts_per_page with the widget's makes the two
	 * agree. Only runs when a Blog Archive part is actually active, so a site
	 * without one is untouched.
	 *
	 * @since 1.7.2
	 * @param WP_Query $query The query being prepared.
	 * @return void
	 */
	function solace_align_archive_posts_per_page( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( ! $query->is_archive() && ! $query->is_home() && ! $query->is_search() ) {
			return;
		}

		$part_id = solace_get_active_part_id( 'blogarchive' );

		if ( ! $part_id ) {
			return;
		}

		$settings = solace_find_widget_settings_in_elementor_data( $part_id, 'solace-extra-post-archive' );

		if ( null === $settings ) {
			return;
		}

		// The widget only inherits when its own switcher says so; when it does not,
		// it paginates through its own query arg and the main query is irrelevant.
		if ( isset( $settings['inherit_archive_query'] ) && 'yes' !== $settings['inherit_archive_query'] ) {
			return;
		}

		$per_page = ! empty( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 0;

		if ( $per_page > 0 ) {
			$query->set( 'posts_per_page', $per_page );
		}
	}
}
add_action( 'pre_get_posts', 'solace_align_archive_posts_per_page' );

if ( ! function_exists( 'solace_get_part_for_post' ) ) {
	/**
	 * Work out which Site Builder part a solace-sitebuilder post is.
	 *
	 * The dashboard tells the editor which part it is opening through a URL flag,
	 * which is lost the moment a part is opened any other way -- from the post
	 * list, a bookmark, Elementor's own "Edit with Elementor". The post itself
	 * knows, though.
	 *
	 * _solace_template is authoritative when it names a real part, in either the
	 * normalised form ("blogsinglepost") or the older status-key form
	 * ("_solace_blogsinglepost_status") that is still present in some databases.
	 * Only when it does not is the part guessed from which _solace_{part}_status
	 * meta exists. A post can carry several of those, so a caller that only cares
	 * about some parts passes them as $fallback_parts to keep the guess inside that
	 * set.
	 *
	 * @since 1.7.2
	 * @param int           $post_id        Site Builder post ID.
	 * @param string[]|null $fallback_parts Parts the status-meta guess may return;
	 *                                      null for every part.
	 * @return string Part slug, or '' when the post is not recognisably a part.
	 */
	function solace_get_part_for_post( $post_id, $fallback_parts = null ) {
		$post_id = absint( $post_id );

		if ( ! $post_id ) {
			return '';
		}

		$slugs    = solace_sitebuilder_part_slugs();
		$template = get_post_meta( $post_id, '_solace_template', true );

		if ( is_string( $template ) && '' !== $template ) {
			$part = str_replace( array( '_solace_', '_status' ), '', $template );

			if ( in_array( $part, $slugs, true ) ) {
				return $part;
			}
		}

		$candidates = null === $fallback_parts
			? $slugs
			: array_values( array_intersect( (array) $fallback_parts, $slugs ) );

		foreach ( $candidates as $part ) {
			if ( metadata_exists( 'post', $post_id, '_solace_' . $part . '_status' ) ) {
				return $part;
			}
		}

		return '';
	}
}

if ( ! function_exists( 'solace_get_editor_post_id' ) ) {
	/**
	 * The ID of the post open in the Elementor editor, whichever route opened it.
	 *
	 * Covers the editor page itself (post.php?action=elementor&post=ID), the
	 * preview iframe (?elementor-preview=ID) and Elementor's editor AJAX
	 * (editor_post_id). Read-only: it only ever chooses a UI default, never
	 * authorises anything, so there is no nonce to check.
	 *
	 * @since 1.7.2
	 * @return int Post ID, or 0 outside an editor request.
	 */
	function solace_get_editor_post_id() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing
		if ( isset( $_GET['action'], $_GET['post'] ) && 'elementor' === sanitize_key( wp_unslash( $_GET['action'] ) ) ) {
			return absint( wp_unslash( $_GET['post'] ) );
		}

		if ( isset( $_GET['elementor-preview'] ) ) {
			return absint( wp_unslash( $_GET['elementor-preview'] ) );
		}

		if ( isset( $_POST['editor_post_id'] ) ) {
			return absint( wp_unslash( $_POST['editor_post_id'] ) );
		}
		// phpcs:enable

		return 0;
	}
}

if ( ! function_exists( 'solace_woo_page_parts_enabled' ) ) {
	/**
	 * Whether the cart, checkout and my account part types are available.
	 *
	 * On by default. Returning false from the filter below hides the cart,
	 * checkout and my account parts again, and reaches every place the feature
	 * shows up (dashboard, Pro overrides, asset bridge, notices). Hiding only the
	 * dashboard would leave a part that is already switched on replacing its
	 * page, with nowhere visible to switch it off.
	 *
	 * @since 1.7.2
	 * @return bool
	 */
	function solace_woo_page_parts_enabled() {
		/**
		 * Filters whether the cart, checkout and my account part types are available.
		 *
		 * @param bool $enabled Default true.
		 */
		return (bool) apply_filters( 'solace_sitebuilder_woo_page_parts_enabled', true );
	}
}

if ( ! function_exists( 'solace_woo_page_parts' ) ) {
	/**
	 * The parts that replace a whole WooCommerce page: cart, checkout, account.
	 *
	 * @since 1.7.2
	 * @return string[]
	 */
	function solace_woo_page_parts() {
		return array( 'cart', 'checkout', 'myaccount' );
	}
}

if ( ! function_exists( 'solace_sitebuilder_required_widgets' ) ) {
	/**
	 * The widgets a WooCommerce page part must contain before it may take over.
	 *
	 * These are the pages that take money. A Checkout part that replaces the page
	 * but has no checkout widget in it leaves the customer with nowhere to pay, so
	 * a part only takes over when it carries the widget that does the page's job.
	 * Any one of the listed widgets is enough.
	 *
	 * @since 1.7.2
	 * @param string $part Part slug.
	 * @return string[] Widget types, or an empty array when the part has no requirement.
	 */
	function solace_sitebuilder_required_widgets( $part ) {
		/**
		 * Filters the widgets each WooCommerce page part must contain.
		 *
		 * @param array $map Part slug => list of acceptable Elementor widget types.
		 */
		$map = apply_filters(
			'solace_sitebuilder_required_widgets',
			array(
				'cart'      => array( 'solace-extra-woocommerce-cart', 'woocommerce-cart' ),
				'checkout'  => array( 'solace-extra-woocommerce-checkout', 'woocommerce-checkout-page' ),
				'myaccount' => array( 'solace-extra-woocommerce-myaccount', 'woocommerce-my-account' ),
			)
		);

		return isset( $map[ $part ] ) ? array_values( (array) $map[ $part ] ) : array();
	}
}

if ( ! function_exists( 'solace_widget_type_is_placeholder' ) ) {
	/**
	 * Whether an Elementor widget type would render nothing useful.
	 *
	 * Without Elementor Pro, Elementor still registers "Go Pro" promotion
	 * stand-ins under the Pro widget names -- woocommerce-cart,
	 * woocommerce-checkout-page and so on. Dropped into a part they render an
	 * upsell, not a cart, so they must not satisfy the requirement. An
	 * unregistered type counts as a placeholder for the same reason.
	 *
	 * @since 1.7.2
	 * @param string $widget_type Elementor widget type.
	 * @return bool
	 */
	function solace_widget_type_is_placeholder( $widget_type ) {
		if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
			return true;
		}

		$widget = \Elementor\Plugin::$instance->widgets_manager->get_widget_types( $widget_type );

		if ( ! $widget ) {
			return true;
		}

		return is_a( $widget, 'Elementor\Modules\Promotions\Widgets\Pro_Widget_Promotion' );
	}
}

if ( ! function_exists( 'solace_part_has_required_widget' ) ) {
	/**
	 * Whether a part contains a working widget its page depends on.
	 *
	 * @since 1.7.2
	 * @param int    $post_id Site Builder post ID.
	 * @param string $part    Part slug.
	 * @return bool True when the part carries one, or when the part has no requirement.
	 */
	function solace_part_has_required_widget( $post_id, $part ) {
		$required = solace_sitebuilder_required_widgets( $part );

		if ( empty( $required ) ) {
			return true;
		}

		foreach ( $required as $widget_type ) {
			if ( null === solace_find_widget_settings_in_elementor_data( $post_id, $widget_type ) ) {
				continue;
			}

			if ( solace_widget_type_is_placeholder( $widget_type ) ) {
				continue;
			}

			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'solace_get_woo_page_part_id' ) ) {
	/**
	 * The part that should replace a WooCommerce page, or 0 to leave the page alone.
	 *
	 * On top of what solace_get_active_part_id() already requires -- published,
	 * active, with an Elementor design -- the part must carry the widget the page
	 * depends on. Both the Pro template override and the asset bridge ask this, so
	 * the two can never disagree about whether the part is in play.
	 *
	 * @since 1.7.2
	 * @param string $part 'cart', 'checkout' or 'myaccount'.
	 * @return int
	 */
	function solace_get_woo_page_part_id( $part ) {
		if ( ! solace_woo_page_parts_enabled() || ! in_array( $part, solace_woo_page_parts(), true ) ) {
			return 0;
		}

		// Elementor Pro Site Settings uses the page: Elementor Pro takes priority.
		if ( function_exists( 'solace_sitebuilder_elementor_pro_woo_page' ) && solace_sitebuilder_elementor_pro_woo_page( $part ) ) {
			return 0;
		}

		$part_id = (int) solace_get_active_part_id( $part );

		if ( ! $part_id || ! solace_part_has_required_widget( $part_id, $part ) ) {
			return 0;
		}

		return $part_id;
	}
}
