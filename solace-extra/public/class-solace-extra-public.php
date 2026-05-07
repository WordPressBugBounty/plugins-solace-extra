<?php
defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://solacewp.com
 * @since      1.0.0
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/public
 * @author     Solace <solacewp@gmail.com>
 */
class Solace_Extra_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( is_singular() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/solace-extra-shortcodes.css', array(), $this->version, 'all' );
		}

		if ( is_single() ) {
			wp_enqueue_style( $this->plugin_name . 'public', plugin_dir_url( __FILE__ ) . 'css/solace-extra-public.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the stylesheets for elementor front end.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles_elementor_frond_end() {

		wp_enqueue_style( 'solace-extra-elementor-frond-end', SOLACE_EXTRA_ASSETS_URL . 'css/elementor-frond-end.min.css', array(), $this->version, 'all' );

	}	

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( is_single() ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/solace-extra-public.js', array( 'jquery' ), $this->version, true );
		}

	}

	/**
	 * Register the shortcode solace year for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_year_shortcode() {

		// Get current year
		$current_year = gmdate('Y');

		return $current_year;

	}

	/**
	 * Register the shortcode solace blog posts.
	 *
	 * @since    1.0.0
	 */
	public function solace_recent_posts_shortcode( $atts , $content = null ) {
		global $solace_is_run_in_shortcode;

		$solace_is_run_in_shortcode = true;

		// Attributes
		$atts = shortcode_atts(
			array(
				'posts' => '5',
				'col' => '2',
			),
			$atts,
			'recent-posts'
		);

		// Query
		$the_query = new WP_Query( array ( 'posts_per_page' => $atts['posts'], 'paged' => 1, 'order' => 'DESC', 'orderby' => 'date' ) );
		
		// Posts
		$classes[] = sanitize_html_class( 'sol_recent_posts_shortcode' );
		
		$col = (int)$atts['col'];
		if ( ( $col >= 1 ) and ( $col <= 5 ) ) {
			$classes[] = sanitize_html_class( 'sol_col_' . $col );
		} else {
			$classes[] = sanitize_html_class( 'sol_col_2' );
		}

		$css_class = implode( ' ', $classes );

		$output = sprintf( '<div class="%s">', $css_class );
		ob_start();
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			get_template_part('template-parts/blog', get_post_format());
		endwhile;
		$output .= ob_get_contents();
		$output .= '</div>';
		ob_end_clean();
		
		// Reset post data
		wp_reset_postdata();

		$solace_is_run_in_shortcode = false;

		return $output;
	}

	/**
	 * Register the shortcode solace blog posts.
	 *
	 * @since    1.0.0
	 */
	// public function solace_recent_posts_shortcode( $atts , $content = null ) {
	// 	wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/solace-extra-shortcodes.css', array(), $this->version, 'all' );

	// 	// Attributes
	// 	$atts = shortcode_atts(
	// 		array(
	// 			'posts' => '5',
	// 			'col' => '2',
	// 		),
	// 		$atts,
	// 		'recent-posts'
	// 	);

	// 	// Query
	// 	$the_query = new WP_Query( array ( 'posts_per_page' => $atts['posts'] ) );
		
	// 	// Posts
	// 	$classes[] = sanitize_html_class( 'sol_recent_posts_shortcode' );
		
	// 	$col = (int)$atts['col'];
	// 	if ( ( $col >= 1 ) and ( $col <= 5 ) ) {
	// 		$classes[] = sanitize_html_class( 'sol_col_' . $col );
	// 	} else {
	// 		$classes[] = sanitize_html_class( 'sol_col_2' );
	// 	}

	// 	$css_class = implode( ' ', $classes );

	// 	$formatted_css_class = sprintf( '<div class="%s">', $css_class );
	// 	echo $formatted_css_class;
	// 	while ( $the_query->have_posts() ) :
	// 		$the_query->the_post();
	// 		get_template_part('template-parts/blog', get_post_format());
	// 	endwhile;
	// 	echo '</div>';
		
	// 	// Reset post data
	// 	wp_reset_postdata();
	// }

	/**
	 * Add custom CSS rule to set the text color of elements with class '.solaceform-form-button' to inherit.
	 * This function is intended to be used in the WordPress footer to apply the styles globally.
	 */
	public function add_color_style_soalceform() {
		// Define the custom CSS rule
		$style = ".solaceform-form-button-wrap { color: #fff; }";

		// Add the custom CSS rule to the inline styles of the specified stylesheet
		wp_add_inline_style( $this->plugin_name, $style );
	}

	/**
	* Render social share on single post
	*
	* @since    1.0.0
	*/

	public function solace_render_customizer_social_share() {
		$default_social_share = array(
			'facebook',
			'instagram',
			'twitter',
			'copylink',
		);
		$get_social_share = get_theme_mod('solace_layout_single_post_social_order', wp_json_encode( $default_social_share ));
		if (!empty($get_social_share)){
			$array = json_decode($get_social_share, true);
			$title = get_the_title();
			$urlpost = get_permalink();
			$baseUrl = '';
			?>
			<div class='solace-social-share'>
				<div class="notif-clipboard msg animate slide-in-down"></div>
				<?php
				foreach ($array as $item) {
					if ( $item === "copylink" ) {
						?>
						<a href="#" id="copy-clipboard">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/></svg>
							<p><?php esc_html_e( 'Copy to Clipboard', 'solace-extra' ); ?></p>
						</a>
						<?php
					} else if ( $item === "facebook" ) {
						$link_share = "https://www.facebook.com/sharer.php?u=" . esc_url( get_the_permalink() );
						?>
						<a href="<?php echo esc_url( $link_share ); ?>" id="facebook">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64h98.2V334.2H109.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H255V480H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"/></svg>
							<p><?php esc_html_e( 'Share on Facebook', 'solace-extra' ); ?></p>
						</a>
						<?php
					} else if ( $item === "twitter" ) {
						$baseUrl = "https://twitter.com/intent/tweet?text=" . urlencode($title) . "&url=" . urlencode($urlpost);
						$link_share = $baseUrl . '?u=' . urlencode($urlpost) . '&t=' . urlencode($title);
						?>
						<a href="<?php echo esc_url( $link_share ); ?>" id="twitter">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm297.1 84L257.3 234.6 379.4 396H283.8L209 298.1 123.3 396H75.8l111-126.9L69.7 116h98l67.7 89.5L313.6 116h47.5zM323.3 367.6L153.4 142.9H125.1L296.9 367.6h26.3z"/></svg>
							<p><?php esc_html_e( 'Share on X', 'solace-extra' ); ?></p>
						</a>
						<?php						
					} else if ( $item === "instagram" ) {
						$urlpost = get_permalink();
						$title = get_the_title();
						$link_share = "https://www.instagram.com/share?text=" . urlencode($title . ' ' . $urlpost);
						?>
						<a href="<?php echo esc_url( $link_share ); ?>" id="instagram">
							<svg xmlns="http://www.w3.org/2000/svg" width="102" height="102" viewBox="0 0 102 102" id="instagram"><defs><radialGradient id="a" cx="6.601" cy="99.766" r="129.502" gradientUnits="userSpaceOnUse"><stop offset=".09" stop-color="#fa8f21"></stop><stop offset=".78" stop-color="#d82d7e"></stop></radialGradient><radialGradient id="b" cx="70.652" cy="96.49" r="113.963" gradientUnits="userSpaceOnUse"><stop offset=".64" stop-color="#8c3aaa" stop-opacity="0"></stop><stop offset="1" stop-color="#8c3aaa"></stop></radialGradient></defs><path fill="url(#a)" d="M25.865,101.639A34.341,34.341,0,0,1,14.312,99.5a19.329,19.329,0,0,1-7.154-4.653A19.181,19.181,0,0,1,2.5,87.694,34.341,34.341,0,0,1,.364,76.142C.061,69.584,0,67.617,0,51s.067-18.577.361-25.14A34.534,34.534,0,0,1,2.5,14.312,19.4,19.4,0,0,1,7.154,7.154,19.206,19.206,0,0,1,14.309,2.5,34.341,34.341,0,0,1,25.862.361C32.422.061,34.392,0,51,0s18.577.067,25.14.361A34.534,34.534,0,0,1,87.691,2.5a19.254,19.254,0,0,1,7.154,4.653A19.267,19.267,0,0,1,99.5,14.309a34.341,34.341,0,0,1,2.14,11.553c.3,6.563.361,8.528.361,25.14s-.061,18.577-.361,25.14A34.5,34.5,0,0,1,99.5,87.694,20.6,20.6,0,0,1,87.691,99.5a34.342,34.342,0,0,1-11.553,2.14c-6.557.3-8.528.361-25.14.361s-18.577-.058-25.134-.361"></path><path fill="url(#b)" d="M25.865,101.639A34.341,34.341,0,0,1,14.312,99.5a19.329,19.329,0,0,1-7.154-4.653A19.181,19.181,0,0,1,2.5,87.694,34.341,34.341,0,0,1,.364,76.142C.061,69.584,0,67.617,0,51s.067-18.577.361-25.14A34.534,34.534,0,0,1,2.5,14.312,19.4,19.4,0,0,1,7.154,7.154,19.206,19.206,0,0,1,14.309,2.5,34.341,34.341,0,0,1,25.862.361C32.422.061,34.392,0,51,0s18.577.067,25.14.361A34.534,34.534,0,0,1,87.691,2.5a19.254,19.254,0,0,1,7.154,4.653A19.267,19.267,0,0,1,99.5,14.309a34.341,34.341,0,0,1,2.14,11.553c.3,6.563.361,8.528.361,25.14s-.061,18.577-.361,25.14A34.5,34.5,0,0,1,99.5,87.694,20.6,20.6,0,0,1,87.691,99.5a34.342,34.342,0,0,1-11.553,2.14c-6.557.3-8.528.361-25.14.361s-18.577-.058-25.134-.361"></path><path fill="#fff" d="M461.114,477.413a12.631,12.631,0,1,1,12.629,12.632,12.631,12.631,0,0,1-12.629-12.632m-6.829,0a19.458,19.458,0,1,0,19.458-19.458,19.457,19.457,0,0,0-19.458,19.458m35.139-20.229a4.547,4.547,0,1,0,4.549-4.545h0a4.549,4.549,0,0,0-4.547,4.545m-30.99,51.074a20.943,20.943,0,0,1-7.037-1.3,12.547,12.547,0,0,1-7.193-7.19,20.923,20.923,0,0,1-1.3-7.037c-.184-3.994-.22-5.194-.22-15.313s.04-11.316.22-15.314a21.082,21.082,0,0,1,1.3-7.037,12.54,12.54,0,0,1,7.193-7.193,20.924,20.924,0,0,1,7.037-1.3c3.994-.184,5.194-.22,15.309-.22s11.316.039,15.314.221a21.082,21.082,0,0,1,7.037,1.3,12.541,12.541,0,0,1,7.193,7.193,20.926,20.926,0,0,1,1.3,7.037c.184,4,.22,5.194.22,15.314s-.037,11.316-.22,15.314a21.023,21.023,0,0,1-1.3,7.037,12.547,12.547,0,0,1-7.193,7.19,20.925,20.925,0,0,1-7.037,1.3c-3.994.184-5.194.22-15.314.22s-11.316-.037-15.309-.22m-.314-68.509a27.786,27.786,0,0,0-9.2,1.76,19.373,19.373,0,0,0-11.083,11.083,27.794,27.794,0,0,0-1.76,9.2c-.187,4.04-.229,5.332-.229,15.623s.043,11.582.229,15.623a27.793,27.793,0,0,0,1.76,9.2,19.374,19.374,0,0,0,11.083,11.083,27.813,27.813,0,0,0,9.2,1.76c4.042.184,5.332.229,15.623.229s11.582-.043,15.623-.229a27.8,27.8,0,0,0,9.2-1.76,19.374,19.374,0,0,0,11.083-11.083,27.716,27.716,0,0,0,1.76-9.2c.184-4.043.226-5.332.226-15.623s-.043-11.582-.226-15.623a27.786,27.786,0,0,0-1.76-9.2,19.379,19.379,0,0,0-11.08-11.083,27.748,27.748,0,0,0-9.2-1.76c-4.041-.185-5.332-.229-15.621-.229s-11.583.043-15.626.229" transform="translate(-422.637 -426.196)"></path></svg>
							<p><?php esc_html_e( 'Share on Instagram', 'solace-extra' ); ?></p>
						</a>
						<?php					
					}
				}
				?>
			</div>
			<?php
		}
	}

	/**
	 * Register styles and scripts for Elementor widgets.
	 *
	 * This function registers third-party styles and scripts 
	 * that will be used by custom Elementor widgets.
	 *
	 * @since 1.0.0
	 */
	public function register_elementor_widget_assets() {
		// Register Fancybox styles.
		wp_register_style(
			'solace-extra-fancybox',
			SOLACE_EXTRA_ASSETS_URL . 'third-party/fancybox/jquery.fancybox.min.css',
			[],
			'3.5.7',
			'all'
		);

		// Register Fancybox script.
		wp_register_script(
			'solace-extra-fancybox',
			SOLACE_EXTRA_ASSETS_URL . 'third-party/fancybox/jquery.fancybox.min.js',
			[ 'jquery' ],
			'3.5.7',
			true
		);
	}	

	/**
	 * Adds custom CSS classes dynamically based on active plugins or conditions.
	 *
	 * This method checks if Elementor is active and adds a specific class to the array.
	 * In the future, more conditions can be added to append additional classes.
	 *
	 * @param array $classes The existing array of CSS classes.
	 * @return array The modified array with additional classes.
	 */
	public function add_dynamic_classes( $classes ) {

		// Check if Elementor is active
		if ( class_exists( 'Elementor\Plugin' ) ) {
			$classes[] = 'wl';
		}

		// Future conditions can be added here
		return $classes;
	}

	/**
	 * Add custom body classes for Solace Extra WooCommerce widgets
	 * 
	 * This function detects if specific Solace Extra WooCommerce widgets (cart, checkout)
	 * are present on the current page and adds corresponding body classes.
	 * This allows for targeted CSS styling based on widget presence.
	 * 
	 * @param array $classes Array of body classes
	 * @return array Modified array of body classes
	 */
	public function add_solace_woocommerce_widget_body_classes( $classes ) {
		// Only process on singular pages (posts, pages, etc.)
		if ( ! is_singular() ) {
			return $classes;
		}

		global $post;

		// Get Elementor data from post meta
		$elementor_data = get_post_meta( $post->ID, '_elementor_data', true );
		$elementor_data = json_decode( $elementor_data, true );

		// Initialize widget detection flags
		$has_cart_widget = false;
		$has_checkout_widget = false;

		/**
		 * Recursive function to search for specific widgets in Elementor data
		 * 
		 * @param array $elements Array of Elementor elements to search through
		 */
		$check_widgets = function( $elements ) use ( &$check_widgets, &$has_cart_widget, &$has_checkout_widget ) {
			foreach ( $elements as $element ) {
				// Check if element has a widget type
				if ( isset( $element['widgetType'] ) ) {
					// Detect Solace Extra WooCommerce Cart widget
					if ( $element['widgetType'] === 'solace-extra-woocommerce-cart' ) {
						$has_cart_widget = true;
					}
					// Detect Solace Extra WooCommerce Checkout widget
					if ( $element['widgetType'] === 'solace-extra-woocommerce-checkout' ) {
						$has_checkout_widget = true;
					}
				}
				
				// Recursively check nested elements
				if ( ! empty( $element['elements'] ) ) {
					$check_widgets( $element['elements'] );
				}
			}
		};

		// Process Elementor data if it exists
		if ( ! empty( $elementor_data ) ) {
			$check_widgets( $elementor_data );
		}

		// Add body classes based on detected widgets
		if ( $has_cart_widget ) {
			$classes[] = 'has-solace-cart-widget';
		}
		
		if ( $has_checkout_widget ) {
			$classes[] = 'has-solace-checkout-widget';
		}

		return $classes;
	}	


	/**
	 * Bridge Elementor's atomic styles flow so the overriding site-builder document
	 * receives the same asset pipeline as a natively rendered singular page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function bridge_atomic_assets_for_override() {
		if ( is_admin() ) {
			return;
		}

		$sitebuilder_ids = $this->resolve_sitebuilder_post_ids_for_asset_bridge();
		if ( empty( $sitebuilder_ids ) ) {
			return;
		}

		$this->bridge_atomic_assets_for_post_ids( $sitebuilder_ids );
	}

	/**
	 * Bridge Elementor's atomic styles flow for an explicit list of site-builder post IDs.
	 *
	 * Use this entry point when the rendering target is known directly (for example
	 * AJAX previews) and the request-based resolution used by
	 * bridge_atomic_assets_for_override() does not apply.
	 *
	 * @since 1.0.0
	 * @param int[] $post_ids Site-builder post IDs to bridge.
	 * @return void
	 */
	public function bridge_atomic_assets_for_post_ids( $post_ids ) {
		if ( ! class_exists( 'Elementor\Plugin' ) ) {
			return;
		}

		$sitebuilder_ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $post_ids ) ) ) );
		if ( empty( $sitebuilder_ids ) ) {
			return;
		}

		$elementor = \Elementor\Plugin::$instance;

		$sitebuilder_ids = array_values( array_filter( $sitebuilder_ids, function ( $sitebuilder_id ) use ( $elementor ) {
			$document = $elementor->documents->get( $sitebuilder_id );

			return $document && $document->is_built_with_elementor();
		} ) );

		if ( empty( $sitebuilder_ids ) ) {
			return;
		}

		$bridge_callback = function () use ( $sitebuilder_ids ) {
			static $done = array();

			foreach ( $sitebuilder_ids as $sitebuilder_id ) {
				if ( isset( $done[ $sitebuilder_id ] ) ) {
					continue;
				}
				$done[ $sitebuilder_id ] = true;

				// Feed the atomic styles manager with our site-builder post.
				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				do_action( 'elementor/post/render', $sitebuilder_id );

				// Replicate Elementor\Frontend::handle_page_assets() which is private.
				$this->enqueue_sitebuilder_page_assets( $sitebuilder_id );

				// Enqueue the legacy per-post CSS (mirrors what core does on singular).
				if ( class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
					$css_file = \Elementor\Core\Files\CSS\Post::create( $sitebuilder_id );
					$css_file->enqueue();
				}
			}
		};

		add_action( 'elementor/frontend/after_enqueue_styles', $bridge_callback );

		// Force Elementor's enqueue_styles() to run now (during wp_enqueue_scripts)
		$elementor->frontend->enqueue_styles();
	}

	/**
	 * Collect all site-builder documents expected to render on this request.
	 *
	 * @return int[]
	 */
	private function resolve_sitebuilder_post_ids_for_asset_bridge() {
		$post_ids = array();

		$override_post_id = $this->resolve_active_sitebuilder_post_id();
		if ( $override_post_id ) {
			$post_ids[] = $override_post_id;
		}

		foreach ( array( 'header', 'footer' ) as $part ) {
			$post_ids = array_merge( $post_ids, $this->resolve_conditioned_sitebuilder_post_ids( $part ) );
		}

		return array_values( array_unique( array_filter( array_map( 'absint', $post_ids ) ) ) );
	}

	/**
	 * Replicates the private Elementor\Frontend::handle_page_assets() behaviour
	 * so runtime elements and page-level asset metadata still load for the
	 * overriding site-builder document.
	 *
	 * @param int $post_id Site-builder post ID.
	 * @return void
	 */
	private function enqueue_sitebuilder_page_assets( $post_id ) {
		if ( ! class_exists( 'Elementor\Plugin' ) ) {
			return;
		}

		$elementor = \Elementor\Plugin::$instance;

		$page_assets = get_post_meta( $post_id, '_elementor_page_assets', true );
		if ( ! empty( $page_assets ) && isset( $elementor->assets_loader ) ) {
			$elementor->assets_loader->enable_assets( $page_assets );
			return;
		}

		$document = $elementor->documents->get( $post_id );
		if ( ! $document ) {
			return;
		}

		if ( method_exists( $document, 'update_runtime_elements' ) ) {
			$document->update_runtime_elements();
		}
	}

	/**
	 * Determine the site-builder post ID that is overriding the current request,
	 * if any. Returns 0 when no override applies so the caller can bail cheaply.
	 *
	 * @return int
	 */
	private function resolve_active_sitebuilder_post_id() {
		$part = $this->resolve_current_override_part();
		if ( ! $part ) {
			return 0;
		}

		$status = $this->solace_get_part_status( $part );
		if ( empty( $status['is_checked'] ) ) {
			return 0;
		}

		$posts = get_posts( array(
			'post_type'      => 'solace-sitebuilder',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key,WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'meta_key'       => '_solace_' . $part . '_status',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key,WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'meta_value'     => '1',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		) );

		return $posts ? (int) $posts[0] : 0;
	}

	/**
	 * Resolve published header/footer site-builder IDs whose conditions match
	 * the current request using the same page-type tokens as the free plugin.
	 *
	 * @param string $part Site-builder part.
	 * @return int[]
	 */
	private function resolve_conditioned_sitebuilder_post_ids( $part ) {
		$status = $this->solace_get_part_status( $part );
		if ( empty( $status['is_checked'] ) ) {
			return array();
		}

		$post_ids = get_posts( array(
			'post_type'      => 'solace-sitebuilder',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key,WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'meta_key'       => '_solace_' . $part . '_status',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key,WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'meta_value'     => '1',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'orderby'        => 'ID',
			'order'          => 'DESC',
		) );

		return array_values( array_filter( $post_ids, function ( $post_id ) use ( $part ) {
			return $this->sitebuilder_part_conditions_match_request( $post_id, $part );
		} ) );
	}

	/**
	 * Match a header/footer document against the current request.
	 *
	 * @param int    $post_id Site-builder post ID.
	 * @param string $part    Site-builder part.
	 * @return bool
	 */
	private function sitebuilder_part_conditions_match_request( $post_id, $part ) {
		$conditions = maybe_unserialize( get_post_meta( $post_id, '_solace_' . $part . '_conditions', true ) );
		$page_type  = $this->resolve_current_sitebuilder_page_type();

		if ( 'publish' !== get_post_status( $post_id ) || empty( $conditions ) || ! is_array( $conditions ) || '' === $page_type ) {
			return false;
		}

		foreach ( $conditions as $condition ) {
			if ( ! is_array( $condition ) ) {
				continue;
			}

			if (
				isset( $condition['type'], $condition['value'] ) &&
				'exclude' === $condition['type'] &&
				$page_type === $condition['value']
			) {
				return false;
			}
		}

		foreach ( $conditions as $condition ) {
			if ( ! is_array( $condition ) ) {
				continue;
			}

			if (
				isset( $condition['type'], $condition['value'] ) &&
				'include' === $condition['type'] &&
				$this->sitebuilder_condition_value_matches_request( $condition['value'], $page_type )
			) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Resolve the request into the page-type tokens used by the free plugin.
	 *
	 * @return string
	 */
	private function resolve_current_sitebuilder_page_type() {
		if ( function_exists( 'check_current_page_type' ) ) {
			$page_type = check_current_page_type();

			return is_string( $page_type ) ? $page_type : '';
		}

		if ( class_exists( 'WooCommerce' ) ) {
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				return 'product|all';
			} elseif ( function_exists( 'is_product' ) && is_product() ) {
				return 'product|all';
			} elseif ( function_exists( 'is_cart' ) && is_cart() ) {
				return 'product|all';
			} elseif ( function_exists( 'is_checkout' ) && is_checkout() ) {
				return 'product|all';
			} elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
				return 'product|all';
			} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
				return 'product|all|taxarchive|product_cat';
			} elseif ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
				return 'product|all|taxarchive|product_tag';
			} elseif ( is_search() && 'product' === get_query_var( 'post_type' ) ) {
				return 'product|all|archive';
			} elseif ( function_exists( 'is_order_received_page' ) && is_order_received_page() ) {
				return 'product|all';
			}
		}

		if ( is_front_page() ) {
			return 'special-front';
		} elseif ( is_home() ) {
			return 'special-blog';
		} elseif ( is_404() ) {
			return 'special-404';
		} elseif ( is_author() ) {
			return 'special-author';
		} elseif ( is_search() ) {
			return 'special-search';
		} elseif ( is_date() ) {
			return 'special-date';
		}

		if ( is_archive() ) {
			if ( is_category() ) {
				return 'post|all|taxarchive|category';
			} elseif ( is_tag() ) {
				return 'post|all|taxarchive|post_tag';
			} elseif ( is_post_type_archive() ) {
				return 'post|all|archives';
			} elseif ( is_tax() ) {
				return 'basic-archives';
			}

			return 'basic-archives';
		}

		if ( is_singular() ) {
			if ( is_page() ) {
				return 'page|all';
			} elseif ( is_attachment() ) {
				return 'basic-archives';
			} elseif ( is_single() ) {
				return 'post|all';
			}

			return 'basic-singulars';
		}

		if ( is_feed() || is_trackback() || is_embed() || is_privacy_policy() ) {
			return 'basic-archives';
		}

		return 'basic-archives';
	}

	/**
	 * Check whether an include condition matches the current page type.
	 *
	 * @param string $condition_value Stored include condition.
	 * @param string $page_type       Current request page type.
	 * @return bool
	 */
	private function sitebuilder_condition_value_matches_request( $condition_value, $page_type ) {
		$valid_archive_types = array(
			'post|all|taxarchive|post_tag',
			'post|all|taxarchive|category',
			'post|all|archives',
			'basic-archives',
			'special-author',
			'special-date',
			'special-search',
			'author',
		);

		$valid_singular_types = array(
			'post',
			'attachment',
			'page',
			'basic-singulars',
			'page|all',
			'post|all',
			'author',
		);

		switch ( $condition_value ) {
			case 'basic-global':
				return true;

			case 'page|all':
				if ( 'special-front' === $page_type && is_home() ) {
					return false;
				}

				return ! in_array(
					$page_type,
					array(
						'basic-singular',
						'basic-archive',
						'special-author',
						'post|all|taxarchive|category',
						'post|all|taxarchive|post_tag',
						'post|all',
						'special-date',
						'special-search',
						'special-404',
					),
					true
				);

			case 'basic-archives':
				return in_array( $page_type, $valid_archive_types, true );

			case 'basic-singulars':
				return in_array( $page_type, $valid_singular_types, true );

			default:
				return $condition_value === $page_type;
		}
	}

	/**
	 * Mirror the part-matching logic used by the various override_*_template()
	 * filters so we can decide, at wp_enqueue_scripts time, which site-builder
	 * document will be rendered for this request.
	 *
	 * Keep this in sync with the override_*() methods above.
	 *
	 * @return string|null
	 */
	private function resolve_current_override_part() {
		$has_woocommerce = class_exists( 'WooCommerce' );

		if ( $has_woocommerce ) {
			if ( function_exists( 'is_product' ) && is_product() ) {
				return 'singleproduct';
			}

			if ( ( function_exists( 'is_product_category' ) && is_product_category() ) ||
				( function_exists( 'is_product_tag' ) && is_product_tag() ) ) {
				return 'shopproduct';
			}

			global $wp;
			if ( isset( $wp->query_vars['order-received'] ) &&
				function_exists( 'wc_get_order' ) &&
				wc_get_order( intval( $wp->query_vars['order-received'] ) ) ) {
				return 'purchase-summary';
			}
		}

		if ( is_404() ) {
			return '404';
		}

		if ( is_singular( 'post' ) ) {
			return 'blogsinglepost';
		}

		$is_woo_shop = $has_woocommerce && function_exists( 'is_shop' ) && is_shop();
		if ( $is_woo_shop ) {
			return null;
		}

		$posts_page_id = absint( get_option( 'page_for_posts' ) );
		$current_id    = get_queried_object_id();

		if ( is_archive() || ( $posts_page_id && $current_id === $posts_page_id ) ) {
			return 'blogarchive';
		}

		return null;
	}

	/**
	 * Retrieve the status information of a specific UI part for the Solace Site Builder.
	 *
	 * This function checks the metadata of posts with the post type 'solace-sitebuilder'
	 * to determine the visual and logical status of a given UI part (e.g., if it's active,
	 * checked, disabled, etc.). It returns an array containing image filename, lock status,
	 * checkbox state, and whether the element is disabled or not.
	 *
	 * @param string $part The identifier of the part to check (e.g., 'blogsinglepost').
	 *
	 * @return array {
	 *     An associative array of part status data.
	 *
	 *     @type string  $image        Image filename to use based on status.
	 *     @type string  $lock_class   CSS class indicating if the element is locked.
	 *     @type bool    $is_checked   Whether the part should be marked as "checked".
	 *     @type string  $active_blue  Optional class for active UI highlighting.
	 *     @type bool    $is_disabled  Whether the part is disabled due to missing data.
	 * }
	 */	
	public function solace_get_part_status( $part ) {
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

}
