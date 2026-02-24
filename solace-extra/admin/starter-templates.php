<?php
defined( 'ABSPATH' ) || exit;

/**
 * Backend submenu Starter Templates
 *
 * @link       https://solacewp.com
 * @since      1.0.0
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/admin
 */

/**
 * The admin-specific functionality of the plugin (starter templates).
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/starter-templates
 * @author     Solace <solacewp@gmail.com>
 */

class Solace_Extra_Starter_Templates {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Valid demo types
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $valid_types
	 */
	private $valid_types = array( 'elementor', 'gutenberg' );

	/**
	 * Posts per page
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int    $posts_per_page
	 */
	private $posts_per_page = 9;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version      = $version;
	}

	/**
	 * Enqueue dash icons
	 *
	 * @since    1.0.0
	 */
	public function enqueue_admin_dashicons() {
		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Get demo data from API
	 *
	 * @since    1.0.0
	 * @return   array|false    Demo data array or false on error
	 */
	private function get_demo_data() {
		$api_url = SOLACE_EXTRA_DEMO_IMPORT_URL . 'api/wp-json/solace/v1/demo/';

		$response = wp_remote_get( $api_url );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data ) || ! is_array( $data ) ) {
			return false;
		}

		// Filter draft/pending demos for non-production domains
		$domain = ! empty( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		if ( 'solacewp.com' !== $domain ) {
			$data = array_filter(
				$data,
				function( $demo ) {
					return ! empty( $demo['demo_status'] ) && 'draft' !== $demo['demo_status'] && 'pending' !== $demo['demo_status'];
				}
			);
		}

		return $data;
	}

	/**
	 * Check if demo matches license filter
	 *
	 * @since    1.0.0
	 * @param    array  $demo          Demo data.
	 * @param    string $filter_license License filter (all, free, pro).
	 * @return   bool
	 */
	private function matches_license_filter( $demo, $filter_license ) {
		if ( 'all' === $filter_license || empty( $filter_license ) ) {
			return true;
		}

		// Check for is_pro field (can be boolean or string)
		$is_pro = false;
		if ( isset( $demo['is_pro'] ) ) {
			$is_pro = (bool) $demo['is_pro'];
		} elseif ( isset( $demo['isPro'] ) ) {
			$is_pro = (bool) $demo['isPro'];
		} elseif ( isset( $demo['license'] ) ) {
			$is_pro = ( 'pro' === strtolower( $demo['license'] ) );
		}

		if ( 'free' === $filter_license ) {
			return ! $is_pro;
		}

		if ( 'pro' === $filter_license ) {
			return $is_pro;
		}

		return true;
	}

	/**
	 * Check if demo matches type filter
	 *
	 * @since    1.0.0
	 * @param    array  $demo    Demo data.
	 * @param    string $getType Type filter.
	 * @return   bool
	 */
	private function matches_type_filter( $demo, $getType ) {
		if ( empty( $getType ) || ! in_array( $getType, $this->valid_types, true ) ) {
			return false;
		}

		return ! empty( $demo['demo_type'] ) && $demo['demo_type'] === $getType;
	}

	/**
	 * Get demo labels
	 *
	 * @since    1.0.0
	 * @param    array $demo Demo data.
	 * @return   array
	 */
	private function get_demo_labels( $demo ) {
		$categories = ! empty( $demo['demo_category'] ) ? $demo['demo_category'] : array();

		return array(
			'new'          => in_array( 'New', $categories, true ),
			'recommended' => in_array( 'Recommended', $categories, true ),
		);
	}

	/**
	 * Render demo item HTML
	 *
	 * @since    1.0.0
	 * @param    array $demo Demo data.
	 * @param    int   $index Item index.
	 */
	private function render_demo_item( $demo, $index = 1 ) {
		$labels = $this->get_demo_labels( $demo );
		
		// Check if demo is pro
		$is_pro = false;
		if ( isset( $demo['is_pro'] ) ) {
			$is_pro = (bool) $demo['is_pro'];
		} elseif ( isset( $demo['isPro'] ) ) {
			$is_pro = (bool) $demo['isPro'];
		} elseif ( isset( $demo['license'] ) ) {
			$is_pro = ( 'pro' === strtolower( $demo['license'] ) );
		}
		
		// Build class string
		$demo_classes = 'demo demo' . esc_attr( $index );
		if ( $is_pro ) {
			$demo_classes .= ' is-pro';
		}
		?>
		<div class='<?php echo esc_attr( $demo_classes ); ?>' data-url='<?php echo esc_attr( $demo['demo_link'] ); ?>' data-name='<?php echo esc_attr( $demo['title'] ); ?>'>
			<div class="box-image">
				<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
				<img src="<?php echo esc_url( $demo['demo_image'] ); ?>" alt="Demo Image" />
			</div>
			<div class="box-content">
				<div class="top-content">
					<?php if ( ! empty( $demo['title'] ) ) : ?>
						<span class="title"><?php echo esc_html( $demo['title'] ); ?></span>
					<?php endif; ?>
					<?php if ( $labels['recommended'] ) : ?>
						<span class="label-recommended"><?php esc_html_e( 'Recommended', 'solace-extra' ); ?></span>
					<?php endif; ?>
					<?php if ( $labels['new'] ) : ?>
						<span class="label"><?php esc_html_e( 'New', 'solace-extra' ); ?></span>
					<?php endif; ?>
					<?php if ( $is_pro ) : ?>
						<span class="label pro"><?php esc_html_e( 'PRO', 'solace-extra' ); ?></span>
					<?php endif; ?>
				</div>
				<div class="bottom-content">
					<p><strong><?php echo esc_html__( 'Ideal for: ', 'solace-extra' ); ?></strong><?php echo esc_html( $demo['demo_desc'] ); ?></p>
				</div>
			</div>
		</div>
		<?php
		echo '<span class="count-demo" style="display: none;">' . absint( $index ) . '</span>';
	}

	/**
	 * Filter demos based on criteria
	 *
	 * @since    1.0.0
	 * @param    array  $demos          All demos.
	 * @param    string $getType        Type filter.
	 * @param    string $filter_license License filter.
	 * @param    string $keyword        Search keyword.
	 * @param    array  $categories     Selected categories.
	 * @return   array
	 */
	private function filter_demos( $demos, $getType = '', $filter_license = 'all', $keyword = '', $categories = array() ) {
		$filtered = array();

		foreach ( $demos as $demo ) {
			// Must have image
			if ( empty( $demo['demo_image'] ) ) {
				continue;
			}

			// Type filter
			if ( ! $this->matches_type_filter( $demo, $getType ) ) {
				continue;
			}

			// License filter
			if ( ! $this->matches_license_filter( $demo, $filter_license ) ) {
				continue;
			}

			// Category filter
			if ( ! empty( $categories ) && 'show-all-demos' !== $categories[0] ) {
				$demo_categories = ! empty( $demo['demo_category'] ) ? $demo['demo_category'] : array();
				if ( empty( array_intersect( $categories, $demo_categories ) ) ) {
					continue;
				}
			}

			// Keyword search
			if ( ! empty( $keyword ) && 'empty' !== $keyword ) {
				$title = ! empty( $demo['title'] ) ? $demo['title'] : '';
				$demo_search = ! empty( $demo['demo_search'] ) ? $demo['demo_search'] : '';
				$demo_desc = ! empty( $demo['demo_desc'] ) ? $demo['demo_desc'] : '';
				$search_text = strtolower( $title . ' ' . $demo_search . ' ' . $demo_desc );
				$keyword_lower = strtolower( $keyword );
				if ( strpos( $search_text, $keyword_lower ) === false ) {
					continue;
				}
			}

			$filtered[] = $demo;
		}

		return $filtered;
	}

	/**
	 * Get cookie key for pagination (single cookie for page number)
	 *
	 * @since    1.0.0
	 * @return   string
	 */
	private function get_pagination_cookie_key() {
		return 'solaceLoadMore_page';
	}

	/**
	 * Get current page from cookie
	 *
	 * @since    1.0.0
	 * @return   int
	 */
	private function get_current_page() {
		$cookie_key = $this->get_pagination_cookie_key();
		return ! empty( $_COOKIE[ $cookie_key ] ) ? absint( $_COOKIE[ $cookie_key ] ) : 1;
	}

	/**
	 * Set current page to cookie
	 *
	 * @since    1.0.0
	 * @param    int    $page          Page number to save.
	 */
	private function set_current_page( $page ) {
		$cookie_key = $this->get_pagination_cookie_key();
		setcookie( $cookie_key, $page, time() + 86400, '/' ); // 1 day
	}

	/**
	 * Ajax Search
	 *
	 * @since    1.0.0
	 */
	public function action_ajax_search_server() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			wp_send_json_error( array( 'error' => 'Invalid nonce!' ) );
			return;
		}

		$keyword        = ! empty( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';
		$getType        = ! empty( $_POST['getType'] ) ? sanitize_text_field( wp_unslash( $_POST['getType'] ) ) : '';
		$filter_license = ! empty( $_POST['filter_license'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_license'] ) ) : 'all';
		$checked        = ! empty( $_POST['checked'] ) ? sanitize_text_field( wp_unslash( $_POST['checked'] ) ) : '';
		$list_checkbox  = ! empty( $checked ) && 'show-all-demos' !== $checked ? explode( ', ', $checked ) : array();

		if ( empty( $keyword ) ) {
			$keyword = 'empty';
		}

		$demos = $this->get_demo_data();
		if ( false === $demos ) {
			wp_send_json_error( array( 'error' => 'Failed to fetch demo data' ) );
			return;
		}

		$filtered_demos = $this->filter_demos( $demos, $getType, $filter_license, $keyword, $list_checkbox );
		$total_filtered = count( $filtered_demos );

		// Render results with pagination support for all combinations
		if ( empty( $filtered_demos ) ) {
			echo '<span class="not-found" style="font-size:17px;">No demo found...</span>';
		} else {
			// Use pagination based on page number
			// Display all items from page 1 to current page
			$current_page = $this->get_current_page();
			$limit = $current_page * $this->posts_per_page;
			$index_all_demos = 1;
			$rendered_count  = 0;

			foreach ( $filtered_demos as $demo ) {
				if ( $index_all_demos <= $limit ) {
					$this->render_demo_item( $demo, $index_all_demos );
					$rendered_count++;
				}
				$index_all_demos++;
			}

			// Output total count for JavaScript to check if load more should be shown
			echo '<span class="total-filtered-count" style="display:none;">' . absint( $total_filtered ) . '</span>';
		}

		wp_die();
	}

	/**
	 * Ajax checkbox
	 *
	 * @since    1.0.0
	 */
	public function action_ajax_checkbox() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			wp_send_json_error( array( 'error' => 'Invalid nonce!' ) );
			return;
		}

		$getType         = ! empty( $_POST['getType'] ) ? sanitize_text_field( wp_unslash( $_POST['getType'] ) ) : '';
		$checked         = ! empty( $_POST['checked'] ) ? sanitize_text_field( wp_unslash( $_POST['checked'] ) ) : '';
		$filter_license  = ! empty( $_POST['filter_license'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_license'] ) ) : 'all';
		$keyword         = ! empty( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';
		$list_checkbox   = ! empty( $checked ) && 'show-all-demos' !== $checked ? explode( ', ', $checked ) : array();

		if ( empty( $keyword ) ) {
			$keyword = 'empty';
		}

		$demos = $this->get_demo_data();
		if ( false === $demos ) {
			wp_send_json_error( array( 'error' => 'Failed to fetch demo data' ) );
			return;
		}

		// Apply all filters together
		$filtered_demos = $this->filter_demos( $demos, $getType, $filter_license, $keyword, $list_checkbox );
		$total_filtered = count( $filtered_demos );

		// Render results with pagination support
		if ( empty( $filtered_demos ) ) {
			echo '<span class="not-found" style="font-size:17px;">No demo found...</span>';
		} else {
			// Use pagination based on page number
			// Display all items from page 1 to current page
			$current_page = $this->get_current_page();
			$limit = $current_page * $this->posts_per_page;
			$index_all_demos = 1;
			$rendered_count  = 0;

			foreach ( $filtered_demos as $demo ) {
				if ( $index_all_demos <= $limit ) {
					$this->render_demo_item( $demo, $index_all_demos );
					$rendered_count++;
				}
				$index_all_demos++;
			}

			// Output total count for JavaScript to check if load more should be shown
			echo '<span class="total-filtered-count" style="display:none;">' . absint( $total_filtered ) . '</span>';
		}

		wp_die();
	}

	/**
	 * Ajax Load More
	 *
	 * @since    1.0.0
	 */
	public function call_ajax_load_more() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			wp_send_json_error( array( 'error' => 'Invalid nonce!' ) );
			return;
		}

		$getType         = ! empty( $_POST['getType'] ) ? sanitize_text_field( wp_unslash( $_POST['getType'] ) ) : '';
		$totalPosts      = ! empty( $_POST['totalPosts'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['totalPosts'] ) ) : 0;
		$filter_license  = ! empty( $_POST['filter_license'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_license'] ) ) : 'all';
		$keyword         = ! empty( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';
		$checked         = ! empty( $_POST['checked'] ) ? sanitize_text_field( wp_unslash( $_POST['checked'] ) ) : '';
		$list_checkbox   = ! empty( $checked ) && 'show-all-demos' !== $checked ? explode( ', ', $checked ) : array();

		if ( empty( $keyword ) ) {
			$keyword = 'empty';
		}

		$demos = $this->get_demo_data();
		if ( false === $demos ) {
			wp_send_json_error( array( 'error' => 'Failed to fetch demo data' ) );
			return;
		}

		// Apply all filters together
		$filtered_demos = $this->filter_demos( $demos, $getType, $filter_license, $keyword, $list_checkbox );
		$total_filtered = count( $filtered_demos );
		
		// Get current page and calculate next page
		$current_page = $this->get_current_page();
		$next_page = $current_page + 1;
		
		// Calculate offset and limit for next page
		$offset = ( $next_page - 1 ) * $this->posts_per_page;
		$limit = $next_page * $this->posts_per_page;
		$index = 1;
		$rendered_count = 0;

		foreach ( $filtered_demos as $demo ) {
			if ( $index > $offset && $index <= $limit ) {
				$this->render_demo_item( $demo, $index );
				$rendered_count++;
			}
			$index++;
		}

		// Update cookie with next page number
		if ( $rendered_count > 0 ) {
			$this->set_current_page( $next_page );
		}

		// Output total count and current page for JavaScript
		echo '<span class="total-filtered-count" style="display:none;">' . absint( $total_filtered ) . '</span>';
		echo '<span class="current-page" style="display:none;">' . absint( $next_page ) . '</span>';

		wp_die();
	}

	/**
	 * Add cookie page access.
	 *
	 * @since    1.0.0
	 */
	public function add_cookie_page_access() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			wp_send_json_error( array( 'error' => 'Invalid nonce!' ) );
			return;
		}

		// Set cookie
		if ( empty( $_COOKIE['solace_page_access'] ) ) {
			setcookie( 'solace_page_access', true, time() + 86400 );
		}

		wp_die();
	}
}
