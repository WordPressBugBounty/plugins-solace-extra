<?php
defined( 'ABSPATH' ) || exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://solacewp.com
 * @since      1.0.0
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/admin
 */

use Codexpert\Plugin\Base;
use Codexpert\CoDesigner\Helper;
use \Elementor\Plugin as Elementor_Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Scheme_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Box_Shadow;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/admin
 * @author     Solace <solacewp@gmail.com>
 */

class Solace_Extra_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		if ( is_customize_preview() ) {
			return;
		}

		// Global.
		wp_enqueue_style( 'solace-extra-disable-menu', plugin_dir_url( __FILE__ ) . 'css/disable-menu.min.css', array(), $this->version, 'all' );

		if ( 
			'toplevel_page_solace' === $hook ||
			'solace_page_dashboard' === $hook ||
			'solace_page_dashboard-video' === $hook ||
			'solace_page_dashboard-sitebuilder' === $hook ||
			'solace_page_dashboard-customization' === $hook ||
			'solace_page_dashboard-starterlink' === $hook ||
			'solace_page_dashboard-type' === $hook ||
			'solace_page_dashboard-starter-templates' === $hook ||
			'solace_page_dashboard-step5' === $hook ||
			'solace_page_dashboard-step6' === $hook ||
			'solace_page_dashboard-progress' === $hook ||
			'solace_page_dashboard-congratulations' === $hook ||
			'appearance_page_tgmpa-install-plugins' === $hook
		) {
			wp_enqueue_style( 'solace-extra-admin-style', plugin_dir_url( __FILE__ ) . 'css/admin-style.min.css', array(), $this->version, 'all' );

			// Fancybox
			wp_enqueue_style( 'solace-extra-fancybox-style', SOLACE_EXTRA_ASSETS_URL . 'css/fancybox.min.css', array(), $this->version, 'all' );
		}

		if ( 'solace_page_dashboard-sitebuilder' === $hook ) {
			// Sweetalert2.
			wp_enqueue_style( 'solace-extra-sweetalert2-style', SOLACE_EXTRA_ASSETS_URL . 'css/sweetalert2.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the stylesheets for elementor editor.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles_elementor_editor() {
		wp_enqueue_style( 'solace-extra-elementor-editor', SOLACE_EXTRA_ASSETS_URL . 'css/elementor-editor.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Registers and enqueues the style for the Solace Extra plugin's widget nav menu.
	 *
	 * This function registers a style for the Solace Extra plugin's widget nav menu.
	 * The style is enqueued for the frontend of the website and is dependent on the 'elementor-frontend' style.
	 *
	 * @since 1.1.6
	 *
	 * @return void
	 */
	public function solace_register_styles() {
		// Register style widget nav menu.
		wp_register_style(
			'solace-widget-nav-menu',
			SOLACE_EXTRA_ASSETS_URL . 'css/widget-nav-menu.min.css',
			[ 'elementor-frontend' ],
			SOLACE_EXTRA_VERSION,
			'all'
		);	
		wp_register_style(
			'solace-fix-widget-nav-menu',
			SOLACE_EXTRA_ASSETS_URL . 'css/elementor-widget-nav-menu.min.css',
			[ 'elementor-frontend' ],
			SOLACE_EXTRA_VERSION,
			'all'
		);	
		// Register style for Shop Widget
		wp_register_style(
			'solace-shop-style',
			SOLACE_EXTRA_ASSETS_URL . 'css/shop/shop.css',
			[ 'elementor-frontend' ],
			SOLACE_EXTRA_VERSION,
			'all'
		);
		// Register style for Shop Widget
		wp_register_style(
			'solace-shop',
			SOLACE_EXTRA_DIR . 'elementor/widgets/widget/shop/assets/css/style.css',
			[ 'elementor-frontend' ],
			SOLACE_EXTRA_VERSION,
			'all'
		);

		// Register style for Block Archive.
		wp_register_style(
			'solace-extra-block-archive',
			SOLACE_EXTRA_ASSETS_URL . 'css/widget-block-archive.css',
			[ 'elementor-frontend' ],
			SOLACE_EXTRA_VERSION,
			'all'
		);

		// Register style for Purchase Summary.
		wp_register_style(
			'solace-extra-purchase-summary',
			SOLACE_EXTRA_DIR . 'elementor/widgets/widget/purchase-summary/assets/css/style.css',
			[ 'elementor-frontend' ],
			SOLACE_EXTRA_VERSION,
			'all'
		);		

		// Register style for Social Share.
		wp_register_style(
			'solace-social-share-style',
			SOLACE_EXTRA_DIR . 'elementor/widgets/widget/social-share/assets/css/style.css',
			[ 'elementor-frontend' ],
			SOLACE_EXTRA_VERSION,
			'all'
		);		

		// Register style for Cart.
		wp_register_style(
			'solace-cart',
			SOLACE_EXTRA_DIR . 'elementor/widgets/widget/shop/assets/css/cart.css',
			[ 'elementor-frontend' ],
			SOLACE_EXTRA_VERSION,
			'all'
		);			

		// Register style for Checkout.
		wp_register_style(
			'solace-checkout',
			SOLACE_EXTRA_DIR . 'elementor/widgets/widget/shop/assets/css/checkout.css',
			[ 'elementor-frontend' ],
			SOLACE_EXTRA_VERSION,
			'all'
		);		

		// Register style for My Account.
		wp_register_style(
			'solace-my-account',
			SOLACE_EXTRA_DIR . 'elementor/widgets/widget/shop/assets/css/my-account.css',
			[ 'elementor-frontend' ],
			SOLACE_EXTRA_VERSION,
			'all'
		);			
	
		// Enqueue Shop Widget CSS
		wp_enqueue_style('solace-shop-style');
	}

	/**
	 * Registers frontend scripts for the Solace theme.
	*
	* This function registers a script for the SmartMenus jQuery plugin,
	* which is only enqueued if Elementor is not active.
	*
	* @since 1.0.0
	*
	* @return void
	*/
	public function solace_register_frontend_scripts() {

		// Enqueue script for frontend.
		$elementor_frontend_script_deps = array( 'jquery' );
		if ( wp_script_is( 'elementor-frontend', 'registered' ) ) {
			$elementor_frontend_script_deps[] = 'elementor-frontend';
		}

		wp_enqueue_script(
			'solace-elementor-frontend-nav-menu',
			SOLACE_EXTRA_ASSETS_URL . 'js/solace-nav-menu.js',
			$elementor_frontend_script_deps,
			'1.3.0',
			true
		);

		// Register style for Social Share.
		wp_register_script(
			'solace-social-share-script',
			SOLACE_EXTRA_DIR . 'elementor/widgets/widget/social-share/assets/js/script.js',
			['jquery'],
			SOLACE_EXTRA_VERSION,
			true
		);				

		// Register style for Nav menu.
		wp_register_script(
			'solace-smartmenus',
			SOLACE_EXTRA_DIR . 'elementor/widgets/widget/nav-menu/assets/js/script.js',
			[
				'jquery',
			],
			SOLACE_EXTRA_VERSION,
			true
		);		

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		wp_enqueue_media();


		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/solace-extra-admin.js', array( 'jquery' ), $this->version, true );

		wp_localize_script( $this->plugin_name, 'ajax_object', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('ajax-nonce')
		));

		wp_localize_script(
			$this->plugin_name,
			'solaceSitebuilderI18n',
			array(
				// Delete confirmation dialog
				'delete_confirm_title' => __('Are you sure?', 'solace-extra'),
				'delete_confirm_text' => __('This item will be permanently deleted.', 'solace-extra'),
				'delete_confirm_button' => __('Yes, delete it!', 'solace-extra'),
				'delete_cancel_button' => __('Cancel', 'solace-extra'),
				'delete_failed_message' => __('Failed to delete the item.', 'solace-extra'),
				'delete_error_message' => __('An error occurred while deleting the item.', 'solace-extra'),
				
				// Rename functionality
				'rename_success_title' => __('Title Updated', 'solace-extra'),
				'rename_success_text' => __('The title has been renamed successfully.', 'solace-extra'),
				'rename_failed_title' => __('Rename Failed', 'solace-extra'),
				'rename_failed_text' => __('Failed to rename post.', 'solace-extra'),
				'ajax_error_title' => __('AJAX Error', 'solace-extra'),
				'ajax_error_text' => __('An unexpected error occurred while renaming the post.', 'solace-extra'),
			)
		);		

		$site_url = site_url();
		wp_localize_script( $this->plugin_name, 'step5', array(
			'site_url' => $site_url
		));
	
		$plugin_url = plugins_url();
		$plugin_dir_url = plugins_url();
		$admin_url = admin_url();
		wp_localize_script( $this->plugin_name, 'pluginUrl', array(
			'plugins_url'    => $plugin_url,
			'plugin_dir_url' => $plugin_dir_url,
			'admin_url' => $admin_url,
		));
		$timestamp = time();

		// Only page Site Builder
		if ( $hook === 'solace_page_dashboard-sitebuilder' ) {

			wp_enqueue_script(
				'solace-sitebuilder',  // Handle of the script
				plugin_dir_url(__FILE__) . 'js/sitebuilder.js',  // Path to the script
				array('jquery'),  // Dependencies
				'1.0.0',  // Version number
				true  // Load in the footer
			);

			// Fancybox
			wp_enqueue_script( 'solace-extra-fancybox-script', SOLACE_EXTRA_ASSETS_URL . 'js/fancybox.min.js', array('jquery'), $this->version, true );			

			// Check if WooCommerce is active
			$is_woocommerce_active = class_exists('WooCommerce') ? true : false;
    
			// Log WooCommerce status to error log
		   

			// Localize the script
			wp_localize_script(
				'solace-sitebuilder',  // This should match the handle in wp_enqueue_script
				'solaceSitebuilderParams',  // Object name that will be available in JS
				array(
					'assetsUrl' => esc_url(SOLACE_EXTRA_ASSETS_URL),
					'ajaxurl' => admin_url('admin-ajax.php'),  // Ajax URL
					'nonce'   => wp_create_nonce('solace_conditions_nonce_action'),  // Nonce for security
					'admin_url' => $admin_url,
					'new_page_url' => admin_url('post-new.php?post_type=page&action=elementor'),  // URL to create new page
					'edit_url' => admin_url('post.php?post={post_id}&action=edit'),  // URL to edit post
					'redirect_url' => esc_url(admin_url('post-new.php?post_type=solace-sitebuilder')),  // Redirect URL
					'part_header' => esc_url(admin_url('admin.php?page=dashboard-sitebuilder&part=header')),  // Redirect URL
					'part_footer' => esc_url(admin_url('admin.php?page=dashboard-sitebuilder&part=footer')),  // Redirect URL
					'part_singleblog' => esc_url(admin_url('admin.php?page=dashboard-sitebuilder&part=singleblog')),  // Redirect URL
					'part_404' => esc_url(admin_url('admin.php?page=dashboard-sitebuilder&part=404')),  // Redirect URL
					'woocommerce'   => $is_woocommerce_active,  // WooCommerce active check
					'upgradeUrl' => SOLACE_UPGRADE_URL

				)
			);
			
			$redirect_url = admin_url( 'admin.php?page=solace' );

			// Data untuk JavaScript
			$solace_conditions = array(
				'includeLabel' => __('Include', 'solace-extra'),
				'excludeLabel' => __('Exclude', 'solace-extra'),
				'options' => array(
					'Basic' => array(
						'basic-global' => __('Entire Website', 'solace-extra'),
						'basic-singulars' => __('All Singulars', 'solace-extra'),
						'basic-archives' => __('All Archives', 'solace-extra'),
					),
					'Special Pages' => array(
						'special-404' => __('404 Page', 'solace-extra'),
						'special-search' => __('Search Page', 'solace-extra'),
						'special-blog' => __('Blog / Posts Page', 'solace-extra'),
						'special-front' => __('Front Page', 'solace-extra'),
						'special-date' => __('Date Archive', 'solace-extra'),
						'special-author' => __('Author Archive', 'solace-extra'),
					),
					'Posts' => array(
						'post|all' => __('All Posts', 'solace-extra'),
						'post|all|archives' => __('All Posts Archive', 'solace-extra'),
						'post|all|taxarchive|category' => __('All Categories Archive', 'solace-extra'),
						'post|all|taxarchive|post_tag' => __('All Tags Archive', 'solace-extra'),
					),
					'Pages' => array(
						'page|all' => __('All Pages', 'solace-extra'),
					),
					
				),
			);
			// Tambahkan opsi WooCommerce jika plugin WooCommerce aktif
			if (class_exists('WooCommerce')) {
				$solace_conditions['options']['Products'] = array(
					'product|all' => __('All Products', 'solace-extra'),
					// 'product|all|archive' => __('All Products Archive', 'solace-extra'),
					'product|all|taxarchive|product_cat' => __('All Product Categories Archive', 'solace-extra'),
					'product|all|taxarchive|product_tag' => __('All Product Tags Archive', 'solace-extra'),
				);
			}
		
			// Kirim data ke JavaScript
			wp_localize_script('solace-sitebuilder', 'solaceConditions', $solace_conditions);

			// Sweetalert2.
			wp_enqueue_script( 'solace-extra-sweetalert', SOLACE_EXTRA_ASSETS_URL . 'js/sweetalert2.min.js', array(), $this->version, true );

		}		

		// Only page progress
		if ( $hook === 'solace_page_dashboard-progress' ) {
			wp_enqueue_script( 'solace-extra-import', plugin_dir_url( __FILE__ ) . 'js/import.js', array( 'jquery' ), $timestamp, true );
			wp_localize_script( 'solace-extra-import', 'solaceDemoImport', array(
				'demo_import_url' => SOLACE_EXTRA_DEMO_IMPORT_URL,
			));
		}

		// Only page starter templates
		if ( $hook === 'solace_page_dashboard-starter-templates' ) {
			// Starter Templates
			wp_enqueue_script( 'solace-extra-starter-templates', plugin_dir_url( __FILE__ ) . 'js/starter-templates.js', array( 'jquery' ), '1.0.0', true );
		}		

		// Only page preview
		if ( $hook === 'solace_page_dashboard-step5' ) {
			// preview
			wp_enqueue_script( 'solace-extra-preview', plugin_dir_url( __FILE__ ) . 'js/preview.js', array( 'jquery' ), '1.0.0', true );

			// Sweetalert
			wp_enqueue_script( 'solace-extra-sweetalert', plugin_dir_url( __FILE__ ) . 'js/sweetalert.min.js', array(), '1.0.0', true );

			// Register dan enqueue script JavaScript untuk postMessage
			wp_enqueue_script('solace-iframe-loader', plugin_dir_url( __FILE__ ) . 'js/solace-iframe-loader.js', array( 'jquery' ), $this->version, true );

			// Localize preview
			wp_localize_script( 'solace-extra-preview', 'required_plugin', array(
				'plugins'    => Solace_Extra_Admin::get_required_plugin()
			));			
		}

		// Only page step6
		if ( 'solace_page_dashboard-step6' === $hook ) {
			// Form email
			wp_enqueue_script( 'solace-extra-form-email', plugin_dir_url( __FILE__ ) . 'js/form-email.js', array( 'jquery' ), $this->version, true );			

			// Sweetalert
			wp_enqueue_script( 'solace-extra-form-email-sweetalert', plugin_dir_url( __FILE__ ) . 'js/sweetalert.min.js', array(), $this->version, true );
		}			

	}

	/**
	 * Adds a memory limit to the wp-config.php file.
	 *
	 * This function checks if the wp-config.php file is writable and if the WP_MEMORY_LIMIT constant is not already defined.
	 * If both conditions are met, it adds the WP_MEMORY_LIMIT and WP_MAX_MEMORY_LIMIT constants with a value of '768' to the file.
	 *
	 * @return void
	 */
	function add_memory_limit_to_wp_config() {
		global $wp_filesystem;

		// Initialize the WP_Filesystem
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			return;
		}

		WP_Filesystem();

		$wp_config_file = ABSPATH . 'wp-config.php';

		// Check if wp-config.php exists and is writable
		if ( $wp_filesystem->is_writable( $wp_config_file ) ) {
			// Get the content of wp-config.php
			$config_content = $wp_filesystem->get_contents( $wp_config_file );

			// Check if the WP_MEMORY_LIMIT is already defined
			if ( strpos( $config_content, "define('WP_MEMORY_LIMIT'" ) === false ) {
				// Modify the content
				$config_content = str_replace(
					"/* That's all, stop editing! Happy publishing. */",
					"define('WP_MEMORY_LIMIT', '768M');\ndefine('WP_MAX_MEMORY_LIMIT', '768M');\n\n/* That's all, stop editing! Happy publishing. */",
					$config_content
				);

				// Write the updated content back to wp-config.php
				$wp_filesystem->put_contents( $wp_config_file, $config_content, FS_CHMOD_FILE );
			}
		}
	}

	/**
	 * 
	 * Adds the WP Fastest Cache (WPFC) clear cache option to the wp-config.php file.
	*
	* This function initializes the WP_Filesystem, checks if the wp-config.php file exists and is writable,
	* retrieves its content, checks if the WPFC_CLEAR_CACHE_AFTER_PLUGIN_UPDATE constant is already defined,
	* modifies the content if necessary, and writes the updated content back to the wp-config.php file.
	*
	* @since 1.0.0
	*
	* @return void
	*/
	public function add_wpfc_clear_cache_to_wp_config() {
		global $wp_filesystem;

		// Initialize the WP_Filesystem
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			return;
		}

		WP_Filesystem();

		$wp_config_file = ABSPATH . 'wp-config.php';

		// Check if wp-config.php exists and is writable
		if ( $wp_filesystem->is_writable( $wp_config_file ) ) {
			// Get the content of wp-config.php
			$config_content = $wp_filesystem->get_contents( $wp_config_file );

			// Check if the WPFC_CLEAR_CACHE_AFTER_PLUGIN_UPDATE is already defined
			if ( strpos( $config_content, "define('WPFC_CLEAR_CACHE_AFTER_PLUGIN_UPDATE'" ) === false ) {
				// Modify the content
				$config_content = str_replace(
					"/* That's all, stop editing! Happy publishing. */",
					"define('WPFC_CLEAR_CACHE_AFTER_PLUGIN_UPDATE', true);\n/* That's all, stop editing! Happy publishing. */",
					$config_content
				);

				// Write the updated content back to wp-config.php
				$wp_filesystem->put_contents( $wp_config_file, $config_content, FS_CHMOD_FILE );
			}
		}
	}

	/**
	 * Register Solace page for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_register_theme_parentmenu() {

		add_menu_page(
			__( 'Solace', 'solace-extra' ),
			__( 'Solace', 'solace-extra' ),
			'manage_options',
			'solace',
			array( $this, 'solace_template_theme_submenu_dashboard' ),
			'data:image/svg+xml;base64,' . base64_encode('<svg width="55" height="69" viewBox="0 0 55 69" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M20.8883 30.9094V39.3014L28.1559 43.4973V35.1053L20.8883 30.9094ZM18.7759 29.2657C18.7656 29.2058 18.7604 29.1441 18.7602 29.0807C18.7601 28.8849 18.8098 28.7032 18.9026 28.547C18.9914 28.3861 19.1249 28.2506 19.296 28.1518C19.3513 28.1199 19.4078 28.0933 19.4653 28.0721L28.6929 22.7445L37.5361 17.6388L37.5362 9.24695L19.9744 19.3862L11.5083 24.2741V43.4973L16.7825 40.4521L18.7759 39.3014V29.2657ZM31.3247 23.6643L38.9592 28.0721C39.0167 28.0934 39.0733 28.12 39.1286 28.1519C39.2987 28.2503 39.4314 28.3847 39.5201 28.5443L39.5208 28.5453C39.6142 28.7021 39.6643 28.8843 39.6641 29.0807C39.6641 29.1439 39.6588 29.2058 39.6486 29.2657V50.7423V50.7471L39.6485 50.756C39.6467 51.1358 39.4584 51.4612 39.1298 51.6516L11.1408 67.8112C11.0935 67.8504 11.042 67.8863 10.9864 67.9183C10.8175 68.0155 10.636 68.0629 10.4549 68.0607C10.2711 68.0641 10.0866 68.0163 9.91517 67.917C9.86017 67.8853 9.80925 67.8498 9.7624 67.8109L0.540822 62.4868C0.205562 62.2957 0.013635 61.9637 0.0153829 61.5778L0.0154995 50.9279C0.00536126 50.8683 0.000117352 50.8067 8.20725e-07 50.7438C-0.000232242 50.5482 0.049177 50.3667 0.141819 50.2104C0.230616 50.0492 0.364161 49.9133 0.535811 49.8142C0.591047 49.7823 0.647681 49.7557 0.705131 49.7345L8.33944 45.3269L0.540356 40.824C0.205445 40.6329 0.0137515 40.3011 0.0154995 39.9154V18.4338C0.00536126 18.3738 0.000117352 18.312 0.000117352 18.2487C0.000117352 18.0544 0.049177 17.8739 0.140887 17.7184C0.229684 17.5566 0.363578 17.42 0.535695 17.3206C0.591163 17.2885 0.647914 17.262 0.705597 17.2406L28.6902 1.08371C29.0205 0.892246 29.399 0.891314 29.73 1.08126L38.9596 6.40988C39.017 6.43109 39.0735 6.45766 39.1286 6.48959C39.2993 6.58817 39.4324 6.72323 39.5211 6.88346C39.6143 7.03985 39.6641 7.22152 39.6641 7.41753C39.6641 7.48092 39.6588 7.54291 39.6487 7.60304L39.6486 18.2486V18.2534V18.2624C39.6468 18.6423 39.4585 18.9675 39.1299 19.158L31.3247 23.6643ZM37.5361 30.9094L30.2685 35.1053V45.1412C30.2788 45.2016 30.284 45.2637 30.284 45.3273C30.2839 45.524 30.2335 45.7063 30.1397 45.8629L30.1393 45.8638C30.0501 46.0233 29.9172 46.1577 29.7468 46.2557C29.6921 46.2871 29.6363 46.3134 29.5796 46.3344L16.7382 53.7483L11.5082 56.7679V65.1598L37.5361 50.1325V30.9094ZM36.4799 29.08L29.2123 24.8839L25.6056 26.9663L21.9446 29.08L29.2123 33.2759L36.4799 29.08ZM27.0997 45.3267L19.8321 41.1308L11.1412 46.1486C11.094 46.1877 11.0428 46.2233 10.9876 46.2551C10.9324 46.2871 10.8759 46.3135 10.8184 46.3349L3.18422 50.7424L4.26492 51.3663L10.4519 54.9383L27.0997 45.3267ZM9.39568 65.1598V56.7678L2.12809 52.5718V60.9638L9.39568 65.1598ZM9.39568 43.4973V24.2741L2.12809 20.0782V39.3014L9.39568 43.4973ZM3.18433 18.2487L10.4519 22.4447L14.7702 19.9516L36.48 7.41753L29.2123 3.22159L3.18433 18.2487Z" fill="#FF8C00"/>
</svg>')
		);
	}

	/**
	 * Register page dashboard for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_register_theme_submenu_dashboard() {

		add_submenu_page(
			'solace',
			__( 'Dashboard', 'solace-extra' ),
			__( 'Dashboard', 'solace-extra' ),
			'manage_options',
			'dashboard',
			array( $this, 'solace_template_theme_submenu_dashboard' )
		);

	}

	public function solace_add_custom_class_to_submenu( $menu ) {
		$menu = str_replace('dashboard-sitebuilder', 'dashboard-sitebuilder solace-sitebuilder-custom-class', $menu);
		return $menu;
	}
	
	public function solace_register_theme_submenu_dashboard_customization() {
		add_submenu_page(
			'solace',
			__( 'Customizer', 'solace-extra' ),
			__( 'Customization', 'solace-extra' ),
			'manage_options',
			'dashboard-customization',
			array( $this, 'solace_template_theme_submenu_dashboard_customization' )
		);
	}
	
	public function solace_template_theme_submenu_dashboard_customization() {
		$return_url = admin_url( 'admin.php?page=solace' );
		$customizer_url = add_query_arg(
			'return', 
			urlencode( $return_url ), 
			admin_url( 'customize.php' )
		);
	
		echo '<script type="text/javascript">window.location = "' . esc_url_raw( $customizer_url ) . '";</script>';
		exit;
	}	
	

	public function solace_register_theme_submenu_dashboard_starterlink() {
		add_submenu_page(
			'solace',
			__( 'Starter Template', 'solace-extra' ),
			__( 'Starter Template', 'solace-extra' ),
			'manage_options',
			'dashboard-starterlink',
			array( $this, 'solace_template_theme_submenu_dashboard_starterlink' )
		);
	}
	
	public function solace_template_theme_submenu_dashboard_starterlink() {
		$base_url = admin_url( 'admin.php' );
		$params = array(
			'page' => 'dashboard-starter-templates',
			'type' => 'elementor',
		);
		
		$query_string = http_build_query( $params, '', '&' );
		$url = $base_url . '?' . $query_string;

		echo '<script type="text/javascript">window.location = "' . esc_url_raw($url) . '";</script>';
		exit;
	}
	
	/**
	 * Register page sitebuilder for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_register_theme_submenu_dashboard_sitebuilder() {

		add_submenu_page(
			'solace',
			__( 'Site Builder', 'solace-extra' ),
			__( 'Site Builder', 'solace-extra' ),
			'manage_options',
			'dashboard-sitebuilder',
			array( $this, 'solace_template_theme_submenu_dashboard_sitebuilder' )
		);

	}

	/**
	 * Register page dashboard video for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_register_theme_submenu_dashboard_step1() {
		
		add_submenu_page(
			'solace',
			__( 'Dashboard Video', 'solace-extra' ),
			__( 'Dashboard Video', 'solace-extra' ),
			'manage_options',
			'dashboard-video',
			array( $this, 'solace_template_theme_submenu_dashboard_step1' )
		);
		
	}
	
	/**
	 * Register page dashboard type for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_register_theme_submenu_dashboard_step2() {
		
		add_submenu_page(
			'solace',
			__( 'Dashboard Type', 'solace-extra' ),
			__( 'Dashboard Type', 'solace-extra' ),
			'manage_options',
			'dashboard-type',
			array( $this, 'solace_template_theme_submenu_dashboard_step2' )
		);
		
	}
	
	/**
	 * Register page dashboard step 5 for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_register_theme_submenu_dashboard_step5() {
		
		add_submenu_page(
			'solace',
			__( 'Solace Step 5', 'solace-extra' ),
			__( 'Solace Step 5', 'solace-extra' ),
			'manage_options',
			'dashboard-step5',
			array( $this, 'solace_template_theme_submenu_dashboard_step5' )
		);
		
	}
	
	/**
	 * Register page dashboard step 6 for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_register_theme_submenu_dashboard_step6() {
		
		add_submenu_page(
			'solace',
			__( 'Solace Step 6', 'solace-extra' ),
			__( 'Solace Step 6', 'solace-extra' ),
			'manage_options',
			'dashboard-step6',
			array( $this, 'solace_template_theme_submenu_dashboard_step6' )
		);
		
	}
	
	
	/**
	 * Register page dashboard congratulations for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_register_theme_submenu_dashboard_congratulations() {

		add_submenu_page(
			'solace',
			__( 'Congratulations', 'solace-extra' ),
			__( 'Congratulations', 'solace-extra' ),
			'manage_options',
			'dashboard-congratulations',
			array( $this, 'solace_template_theme_submenu_dashboard_congratulations' )
		);

	}

	/**
	 * Register page dashboard starter templates for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_register_theme_submenu_dashboard_starter_templates() {

		add_submenu_page(
			'solace',
			__( 'Dashboard Starter Templates', 'solace-extra' ),
			__( 'Dashboard Starter Templates', 'solace-extra' ),
			'manage_options',
			'dashboard-starter-templates',
			array( $this, 'solace_template_theme_submenu_dashboard_starter_templates' )
		);

	}

	/**
	 * Register page dashboard progress for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_register_theme_submenu_dashboard_progress() {

		add_submenu_page(
			'solace',
			__( 'Dashboard Progress', 'solace-extra' ),
			__( 'Dashboard Progress', 'solace-extra' ),
			'manage_options',
			'dashboard-progress',
			array( $this, 'solace_template_theme_submenu_dashboard_progress' )
		);

	}	

	/**
	 * Template page dashboard for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_template_theme_submenu_dashboard() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/submenu-dashboard.php';
    
	}

	/**
	 * Template page dashboard for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_template_theme_submenu_dashboard_sitebuilder() {
		add_action('admin_enqueue_scripts', function() {
			wp_enqueue_script('jquery');
		});
		wp_enqueue_script('dotlottie-player-component', plugin_dir_url(__FILE__) . 'js/dotlottie-player.js', array(), '1.0.0', true);

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/submenu-dashboardsitebuilder.php';
    
	}

	/**
	 * Template page dashboard for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_template_theme_submenu_dashboard_step1() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/submenu-video.php';
    
	}

	/**
	 * Template page dashboard for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_template_theme_submenu_dashboard_step2() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/submenu-type.php';
    
	}	

	/**
	 * Template page dashboard for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_template_theme_submenu_dashboard_step5() {
        add_action('admin_enqueue_scripts', function() {
			wp_enqueue_script('jquery');
		});
		// Source code can be found at readme.txt and solace-extra-admin/js/src/index.js
		wp_enqueue_script('dotlottie-player-component', plugin_dir_url(__FILE__) . 'js/dotlottie-player.js', array(), $this->version, true);		
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/submenu-dashboardstep5.php';
	}	

	/**
	 * Template page dashboard for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_template_theme_submenu_dashboard_step6() {
		add_action('admin_enqueue_scripts', function() {
			wp_enqueue_script('jquery');
		});
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/submenu-dashboardstep6.php';
    
	}	

	public function solace_thumbnail_generation() {

        $thumbnail_generator = new Thumbnail_Generator();

        $args = array(
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
            'fields'         => 'ids', 
        );

        $attachments = get_posts($args);

        foreach ($attachments as $attachment_id) {
            $thumbnail_generator->push_to_queue($attachment_id);
        }

        $thumbnail_generator->save()->dispatch();
    }

    public function solace_enable_image_processing() {
		// error_log('menyalakan kembali image processing');
        remove_filter('intermediate_image_sizes_advanced', [$this, 'disable_sizes']);
        remove_filter('wp_image_editors', [$this, 'disable_editors']);
        remove_filter('wp_generate_attachment_metadata', [$this, 'disable_metadata'], 10);
        remove_filter('big_image_size_threshold', '__return_false');
    }

	/**
	 * Template page dashboard for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_template_theme_submenu_dashboard_congratulations() {
		add_action('admin_enqueue_scripts', function() {
			wp_enqueue_script('jquery');
		});
		$this->solace_enable_image_processing();
        $this->solace_thumbnail_generation();
		add_action('elementor/init', function () {
				$global_settings = \Elementor\Core\Settings\Manager::get_settings('global');
				
				\Elementor\Core\Settings\Manager::save_settings('global', $global_settings);
			
		});
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/submenu-dashboardcongratulations.php';
    
	}	

	/**
	 * Template page dashboard for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_template_theme_submenu_dashboard_starter_templates() {
		// Source code can be found at readme.txt and solace-extra-admin/js/src/index.js
		wp_enqueue_script('dotlottie-player-component', plugin_dir_url(__FILE__) . 'js/dotlottie-player.js', array(), $this->version, true);
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/submenu-starter.php';
    
	}

	public function solace_custom_max_execution_time($time) {
		return 420; // 7 menit
	}
	
	/**
	 * Template page dashboard for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function solace_template_theme_submenu_dashboard_progress() {
		// Source code can be found at readme.txt and solace-extra-admin/js/src/index.js
		add_filter('wp_max_execution_time', array($this, 'solace_custom_max_execution_time'));
		// $this->solace_disable_image_processing();

		wp_enqueue_script('dotlottie-player-component', plugin_dir_url(__FILE__) . 'js/dotlottie-player.js', array(), $this->version, true);
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/submenu-progress.php';
    
	}	

	public function solace_disable_image_processing() {
        add_filter('intermediate_image_sizes_advanced', [$this, 'disable_sizes']);
        add_filter('wp_image_editors', [$this, 'disable_editors']);
        add_filter('wp_generate_attachment_metadata', [$this, 'disable_metadata'], 10, 2);
        add_filter('big_image_size_threshold', '__return_false');
    }
    // Callback methods for filters
    public function disable_sizes($sizes) {
        return [];
    }

    public function disable_editors() {
        return [];
    }

    public function disable_metadata($metadata, $attachment_id) {
        return [];
    }

    /**
     * Remove notif
     */		
	public function hide_notifications_for_solace_page() {
		if ( is_admin() && get_admin_page_parent() === 'solace' ) {
			remove_all_actions('admin_notices');
		}
	}

	
	
	function update_sol_color_base_font_elementor_system_color(){
		$solace_global_colors = get_theme_mod( 'solace_global_colors' );

		$border_color = strtoupper($solace_global_colors['palettes']['base']['colors']['sol-color-border']);
		if (empty($solace_global_colors['palettes']['base']['colors']['sol-color-bg-menu-dropdown'])){
			$solace_global_colors['palettes']['base']['colors']['sol-color-bg-menu-dropdown'] = $border_color;	
		}
		
		set_theme_mod( 'solace_global_colors' , $solace_global_colors );
		$active_palette = $solace_global_colors['activePalette'];

		$primary = strtoupper($solace_global_colors['palettes'][$active_palette]['colors']['sol-color-button-initial']);
		$secondary = strtoupper($solace_global_colors['palettes'][$active_palette]['colors']['sol-color-page-title-background']);
		$text = strtoupper($solace_global_colors['palettes'][$active_palette]['colors']['sol-color-base-font']);
		$border_color = strtoupper($solace_global_colors['palettes'][$active_palette]['colors']['sol-color-border']);
		$accent = strtoupper($solace_global_colors['palettes'][$active_palette]['colors']['sol-color-bg-menu-dropdown']);
		$accent = empty($accent)?$border_color:$accent;

		if (defined('ELEMENTOR_PATH') && class_exists('\Elementor\Plugin')) {
			$system_colors = array(
				array(
					'_id' => 'primary',
					'color' => isset($primary)?$primary:'',
				),
				array(
					'_id' => 'secondary',
					'color' => isset($secondary)?$secondary:'',
				),
				array(
					'_id' => 'text',
					'color' => isset($text)?$text:'',
				),
				array(
					'_id' => 'accent',
					'color' => isset($accent)?$accent:'',
				),
			);

			\Elementor\Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'system_colors', $system_colors );

			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}
	}

	

	function update_solace_font_and_color(){

		// Verify nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
			$response = array('error' => 'Invalid nonce 2!');
			wp_die();
		}			
		
		// =========== BEGIN GET NEW FONT AND COLOR ===========
		$new_solace_body_font_family = isset( $_POST['new_solace_body_font_family'] ) ? sanitize_text_field( wp_unslash( $_POST['new_solace_body_font_family'] ) ) : '';
		$new_solace_heading_font_family_general = isset( $_POST['new_solace_heading_font_family_general'] ) ? sanitize_text_field( wp_unslash( $_POST['new_solace_heading_font_family_general'] ) ) : '';
		$new_base_color = ! empty( $_POST['new_base_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_base_color'] ) ) : '';
		$new_heading_color = ! empty( $_POST['new_heading_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_heading_color'] ) ) : '';
		$new_link_button_color = ! empty( $_POST['new_link_button_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_link_button_color'] ) ) : '';
		$new_link_button_hover_color = ! empty( $_POST['new_link_button_hover_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_link_button_hover_color'] ) ) : '';
		$new_button_color = ! empty( $_POST['new_button_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_button_color'] ) ) : '';
		$new_button_hover_color = ! empty( $_POST['new_button_hover_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_button_hover_color'] ) ) : '';
		$new_text_selection_color = ! empty( $_POST['new_text_selection_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_text_selection_color'] ) ) : '';
		$new_text_selection_hover_color = ! empty( $_POST['new_text_selection_bg_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_text_selection_bg_color'] ) ) : '';
		$new_border_color = ! empty( $_POST['new_border_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_border_color'] ) ) : '';
		$new_background_color = ! empty( $_POST['new_background_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_background_color'] ) ) : '';
		$new_page_title_text_color = ! empty( $_POST['new_page_title_text_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_page_title_text_color'] ) ) : '';
		$new_page_title_bg_color = ! empty( $_POST['new_page_title_bg_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_page_title_bg_color'] ) ) : '';
		$new_bg_menu_dropdown_color = ! empty( $_POST['new_bg_menu_dropdown_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['new_bg_menu_dropdown_color'] ) ) : '';
		// $new_bg_menu_dropdown_color = !empty($new_bg_menu_dropdown_color)?$new_bg_menu_dropdown_color:$new_border_color;
		
		// =========== END OF NEW FONT AND COLOR ===========

		// =========== BEGIN GET CURRENT SETTING ELEMENTOR COLOR ==============
		$current = \Elementor\Plugin::$instance->kits_manager->get_current_settings();
		$base_color = strtoupper($current['solace_colors'][0]['color']);
		$heading_color = strtoupper($current['solace_colors'][1]['color']);
		$link_button_color = strtoupper($current['solace_colors'][2]['color']);
		$link_button_hover_color = strtoupper($current['solace_colors'][3]['color']);
		$button_color = strtoupper($current['solace_colors'][4]['color']);
		$button_hover_color = strtoupper($current['solace_colors'][5]['color']);
		$text_selection_color = strtoupper($current['solace_colors'][6]['color']);
		$text_selection_bg_color = strtoupper($current['solace_colors'][7]['color']);
		$border_color = strtoupper($current['solace_colors'][8]['color']);
		$background_color = strtoupper($current['solace_colors'][9]['color']);
		$page_title_text_color = strtoupper($current['solace_colors'][10]['color']);
		$page_title_bg_color = strtoupper($current['solace_colors'][11]['color']);
		$bg_menu_dropdown_color = strtoupper($current['solace_colors'][12]['color']);
		// $bg_menu_dropdown_color = empty($bg_menu_dropdown_color)?$border_color:$bg_menu_dropdown_color;

		// IF Current Elementor Color is empty, then set Default Solace Color
		$base_color = isset($base_color)?$base_color:'#000000';
		$heading_color = isset($heading_color)?$heading_color:'#1D70DB';
		$link_button_color = isset($link_button_color)?$link_button_color:'#1D70DB';
		$link_button_hover_color = isset($link_button_hover_color)?$link_button_hover_color:'#1D70DB';
		$button_color = isset($button_color)?$button_color:'#1D70DB';
		$button_hover_color = isset($button_hover_color)?$button_hover_color:'#1D70DB';
		$text_selection_color = isset($text_selection_color)?$text_selection_color:'#FF9500';
		$text_selection_bg_color = isset($text_selection_bg_color)?$text_selection_bg_color:'#FF9500';
		$border_color = isset($border_color)?$border_color:'#DEDEDE';
		$background_color = isset($background_color)?$background_color:'#EBEBEB';
		$page_title_text_color = isset($page_title_text_color)?$page_title_text_color:'#FFFFFF';
		$page_title_bg_color = isset($page_title_bg_color)?$page_title_bg_color:'#000F44';
		$bg_menu_dropdown_color = isset($bg_menu_dropdown_color)?$bg_menu_dropdown_color:'#DEDEDE';
		// =========== END OF GET CURRENT SETTING ELEMENTOR COLOR ==============
		
		// =========== BEGIN SAVE FONT AND COLOR TO CUSTOMIZER ==============
		set_theme_mod('solace_body_font_family', $new_solace_body_font_family);
		set_theme_mod('solace_smaller_font_family', $new_solace_body_font_family);
		set_theme_mod('solace_logotitle_font_family', $new_solace_body_font_family);
		set_theme_mod('solace_button_font_family', $new_solace_body_font_family);
		set_theme_mod('solace_h1_font_family_general',$new_solace_heading_font_family_general);
		set_theme_mod('solace_h2_font_family_general',$new_solace_heading_font_family_general);
		set_theme_mod('solace_h3_font_family_general',$new_solace_heading_font_family_general);
		set_theme_mod('solace_h4_font_family_general',$new_solace_heading_font_family_general);
		set_theme_mod('solace_h5_font_family_general',$new_solace_heading_font_family_general);
		set_theme_mod('solace_h6_font_family_general',$new_solace_heading_font_family_general);
		$solace_base_font	= get_theme_mod('solace_body_font_family','Manrope' );

		$solace_global_colors['activePalette'] = 'base';
		$solace_global_colors['palettes']['base']['name'] = 'Base';
		$solace_global_colors['palettes']['base']['allowDeletion'] = '';
		$solace_global_colors['palettes']['base']['colors']['sol-color-base-font'] = isset($new_base_color)?strtoupper($new_base_color):$base_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-heading'] = isset($new_heading_color)?strtoupper($new_heading_color):$heading_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-link-button-initial'] = isset($new_link_button_color)?strtoupper($new_link_button_color):$link_button_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-link-button-hover'] = isset($new_link_button_hover_color)?strtoupper($new_link_button_hover_color):$link_button_hover_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-button-initial'] = isset($new_button_color)?strtoupper($new_button_color):$button_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-button-hover'] = isset($new_button_hover_color)?strtoupper($new_button_hover_color):$button_hover_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-selection-initial'] = isset($new_text_selection_color)?strtoupper($new_text_selection_color):$text_selection_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-selection-high'] = isset($new_text_selection_hover_color)?strtoupper($new_text_selection_hover_color):$text_selection_bg_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-border'] = isset($new_border_color)?strtoupper($new_border_color):$border_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-background'] = isset($new_background_color)?strtoupper($new_background_color):$background_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-page-title-text'] = isset($new_page_title_text_color)?strtoupper($new_page_title_text_color):$page_title_text_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-page-title-background'] = isset($new_page_title_bg_color)?strtoupper($new_page_title_bg_color):$page_title_bg_color;
		$solace_global_colors['palettes']['base']['colors']['sol-color-bg-menu-dropdown'] = isset($new_bg_menu_dropdown_color)?strtoupper($new_bg_menu_dropdown_color):$bg_menu_dropdown_color;
		set_theme_mod( 'solace_global_colors' , $solace_global_colors );
		$solace_global_colors = get_theme_mod( 'solace_global_colors' );
		// =========== END OF SAVE FONT AND COLOR TO CUSTOMIZER ==============

		$attachment_id_cookie = isset( $_COOKIE['solace_step5_logoid'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['solace_step5_logoid'] ) ) : null;

		if (!empty($attachment_id_cookie)){
			// $logo_url = esc_url( esc_url_raw( $_POST['logo_url'] ) );
			$logo_id = $attachment_id_cookie;
			$theme_mods = get_theme_mod('theme_mods_solace');  
			$logo_data = json_decode($theme_mods['logo_logo'], true);  
			$logo_logo_data = '{"light":'.$logo_id.',"dark":'.$logo_id.',"same":true}';
			set_theme_mod('logo_logo',$logo_logo_data);
			set_theme_mod('logo-footer_logo', $logo_logo_data);

		}
		// SET LOGO



		// =========== BEGIN SAVE FONT AND COLOR TO ELEMENTOR ===========
		$custom_typography = [
			[
				'_id' => 'primary',
				'title' => 'Smaller',
				'typography_font_family' => $new_solace_body_font_family
			],
			[
				'_id' => 'secondary',
				'title' => 'Logo Title / Subtitle',
				'typography_font_family' => $new_solace_body_font_family
			],
			[
				'_id' => 'text',
				'title' => 'Solace Base',
				'typography_font_family' => $new_solace_body_font_family
			],
			[
				'_id' => 'accent',
				'title' => 'Button',
				'typography_font_family' => $new_solace_body_font_family
			],
			['
				_id' => 'solace_body_font_family',
				'typography_font_family' => $new_solace_body_font_family
			],
			[
				'typography_font_family' => $new_solace_heading_font_family_general
			],
			[
				'typography_font_family' => $new_solace_heading_font_family_general
			],
			[
				'typography_font_family' => $new_solace_heading_font_family_general
			],
			[
				'typography_font_family' => $new_solace_heading_font_family_general
			],
			[
				'typography_font_family' => $new_solace_heading_font_family_general
			],
			[
				'typography_font_family' => $new_solace_heading_font_family_general
			]
		];
		
		$system_colors = array(
			array(
				'_id' => 'primary',
				'color' => isset($new_button_color)?strtoupper($new_button_color):$button_color,
			),
			array(
				'_id' => 'secondary',
				'color' => isset($new_page_title_bg_color)?strtoupper($new_page_title_bg_color):$page_title_bg_color,
			),
			array(
				'_id' => 'text',
				'color' => isset($new_base_color)?strtoupper($new_base_color):$base_color,
			),
			array(
				'_id' => 'accent',
				'color' => isset($new_bg_menu_dropdown_color)?strtoupper($new_bg_menu_dropdown_color):$bg_menu_dropdown_color,
			),
		);
		
		$theme_colors = array(
			array(
				'_id' => 'sol-color-base-font',
				'title'  => __( 'Base Font', 'solace-extra' ),
				'color' => isset($new_base_color)?strtoupper($new_base_color):$base_color,
			),
			array(
				'_id' => 'sol-color-heading',
				'title'  => __( 'Heading', 'solace-extra' ),
				'color' => isset($new_heading_color)?strtoupper($new_heading_color):$heading_color,
				
			),
			array(
				'_id' => 'sol-color-link-button-initial',
				'title'  => __( 'Link', 'solace-extra' ),
				'color' => isset($new_link_button_color)?strtoupper($new_link_button_color):$link_button_color,
			),
			array(
				'_id' => 'sol-color-link-button-hover',
				'title'  => __( 'Link Hover', 'solace-extra' ),
				'color' => isset($new_link_button_hover_color)?strtoupper($new_link_button_hover_color):$link_button_hover_color,
			),
			array(
				'_id' => 'sol-color-button-initial',
				'title'  => __( 'Button', 'solace-extra' ),
				'color' => isset($new_button_color)?strtoupper($new_button_color):$button_color,
			),
			array(
				'_id' => 'sol-color-button-hover',
				'title'  => __( 'Button Hover', 'solace-extra' ),
				'color' => isset($new_button_hover_color)?strtoupper($new_button_hover_color):$button_hover_color,
			),
			array(
				'_id' => 'sol-color-selection',
				'title'  => __( 'Text Selection', 'solace-extra' ),
				'color' => isset($new_text_selection_color)?strtoupper($new_text_selection_color):$text_selection_color,
			),
			array(
				'_id' => 'sol-color-selection-high',
				'title'  => __( 'Text Selection Background', 'solace-extra' ),
				'color' => isset($new_text_selection_hover_color)?strtoupper($new_text_selection_hover_color):$text_selection_bg_color,
			),
			array(
				'_id' => 'sol-color-border',
				'title'  => __( 'Border', 'solace-extra' ),
				'color' => isset($new_border_color)?strtoupper($new_border_color):$border_color,
			),
			array(
				'_id' => 'sol-color-background',
				'title'  => __( 'Background', 'solace-extra' ),
				'color' => isset($new_background_color)?strtoupper($new_background_color):$background_color,
			),
			array(
				'_id' => 'sol-color-page-title-text',
				'title'  => __( 'Page Title', 'solace-extra' ),
				'color' => isset($new_page_title_text_color)?strtoupper($new_page_title_text_color):$page_title_text_color,
			),
			array(
				'_id' => 'sol-color-page-title-background',
				'title'  => __( 'Page Title Background', 'solace-extra' ),
				'color' => isset($new_page_title_bg_color)?strtoupper($new_page_title_bg_color):$page_title_bg_color,
			),
			array(
				'_id' => 'sol-color-bg-menu-dropdown',
				'title'  => __( 'Submenu Background', 'solace-extra' ),
				'color' => isset($new_bg_menu_dropdown_color)?strtoupper($new_bg_menu_dropdown_color):$bg_menu_dropdown_color,
			),
		);
		if (class_exists('Elementor\Plugin')) {
			\Elementor\Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'system_colors', $system_colors );
			\Elementor\Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'solace_colors', $theme_colors );
			\Elementor\Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'system_typography', $custom_typography );
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}
		// =========== END OF SAVE FONT AND COLOR TO ELEMENTOR ===========


		wp_send_json_success('Color and Font updated: '.$new_solace_body_font_family.' & '. $new_solace_heading_font_family_general.' successfully.');
	}


	function update_logo_url_callback() {
		if (isset($_POST['logo_url'])) {

			// Verify nonce
			if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
				$response = array('error' => 'Invalid nonce3!');
				echo wp_json_encode($response);
				wp_die();
			}
			
			$logo_url = ! empty( $_POST['logo_url'] ) ? esc_url_raw( wp_unslash( $_POST['logo_url'] ) ) : '';

			$logo_id = $logo_url;
			$theme_mods = get_theme_mod('theme_mods_solace');  
			$logo_data = json_decode($theme_mods['logo_logo'], true);  
			$logo_logo_data = '{"light":'.$logo_id.',"dark":'.$logo_id.',"same":true}';
			set_theme_mod('logo_logo',$logo_logo_data);
			set_theme_mod('logo-footer_logo', $logo_logo_data);

			wp_send_json_success('Logo URL updated successfully.');
		} else {
			wp_send_json_error('No logo URL provided.');
		}
	}

    /**
     * Get required plugin.
     */
    public static function get_required_plugin()
    {

		// Initialize response data
		$data = array();

        // Verify nonce
        if (!isset($_GET['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_GET['nonce'] ) ), 'ajax-nonce' )) {
			$data['error'] = esc_html__( 'Invalid nonce4!', 'solace-extra' );
			return $data;
        }		

        // Demo name
		$get_demo = ! empty( $_GET['demo'] ) ? sanitize_text_field( wp_unslash( $_GET['demo'] ) ) : '';
        if (empty($get_demo)) {
			$data['error'] = esc_html__( 'Error demo URL', 'solace-extra' );
			return $data;
        }

		// Remote and local API URLs
		$url = trailingslashit('https://solacewp.com/' . $get_demo) . 'wp-json/solace/v1/required-plugin';

        // Make remote request using wp_remote_get
        $response = wp_remote_get($url);

        // Check for errors
        if (is_wp_error($response)) {
			$data['error'] = esc_html__( 'Error response', 'solace-extra' );
			return $data;
        }

		// Decode the response body
		$body = wp_remote_retrieve_body($response);
		$decoded_data = json_decode($body, true);

		// Data checks
		if (!is_array($decoded_data)) {
			$data['error'] = 'Required plugin not found.';
			return $data;
		}

		return $decoded_data;		
    }	
	
    /**
     * Remove cookie
     */	
	function remove_cookies_continue_page_access() {

        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
            $response = array('error' => 'Invalid nonce5!');
			echo wp_json_encode($response);
            wp_die();
        }   
		
		// Page congratulations
		$mypage = ! empty( $_POST['mypage'] ) ? sanitize_text_field( wp_unslash( $_POST['mypage'] ) ) : '';
		if ( 'dashboard-congratulations' === $mypage || 'dashboard-step6' === $mypage ) {
			// Set cookie with expired time to delete the cookie
			setcookie( 'solace_page_access', '', time() - 3600 );
			setcookie( 'solace_step5_font', '', time() - 3600 );
			setcookie( 'solace_step5_color', '', time() - 3600 );
			setcookie( 'solace_step5_logoid', '', time() - 3600 );
			
		}

		wp_die();
	}

	/**
	 * Redirects user after theme/plugin activation if option is set.
	 *
	 * This function checks if the 'solace_extra_redirect_after_activation_option' is set to true.
	 * If true, it deletes the option and redirects the user to 'admin.php?page=solace'.
	 */
	public function activation_redirect() {
		// Check if the 'solace_extra_redirect_after_activation_option' is set to true
		if ( get_option( 'solace_extra_redirect_after_activation_option', false ) ) {
			// Delete the option to prevent redirection on subsequent activations
			delete_option( 'solace_extra_redirect_after_activation_option' );

			// Construct the URL
			$redirect_url = admin_url( 'admin.php?page=solace' );

			// Make a GET request to the URL and check the HTTP response code
			$response = wp_remote_get( $redirect_url );

			if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
				// If the response code is 200, redirect the user
				wp_safe_redirect( $redirect_url );
				exit;
			} else {
				// If there is an error or the response code is not 200, handle accordingly
				// You can log the error, display a message, or take other actions
				// Handle the error, for example, redirect to another page
				wp_safe_redirect( admin_url() );
				exit;
			}
		}
	}	

	/**
	 * Handles the theme switch redirection.
	 *
	 * This function is triggered when a theme switch occurs. It checks if the new theme is 'Solace' and the new theme name is also 'Solace'.
	 * If these conditions are met, it constructs a redirect URL to the 'Solace' admin page and makes a GET request to check the response code.
	 * If the response code is 200, it redirects the user to the 'Solace' admin page.
	 *
	 * @param string $new_name The new theme name.
	 * @param WP_Theme $new_theme The new theme object.
	 * @param WP_Theme $old_theme The old theme object.
	 */	
	public function switch_theme_redirect($new_name, $new_theme, $old_theme) {
	
		if ( 'Solace' === $new_theme->get('Name') && 'Solace' === $new_name ) {
			// Construct the URL
			$redirect_url = admin_url( 'admin.php?page=solace' );
	
			// Make a GET request to the URL and check the HTTP response code
			$response = wp_remote_get( $redirect_url );
	
			if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
				// If the response code is 200, redirect the user
				wp_safe_redirect( $redirect_url );
				exit;
			}
		}
	
	}

	/**
	 * Registers custom controls for Elementor.
	 *
	 * This function includes the necessary control files and registers them
	 * with Elementor's controls manager. Additional controls can be added
	 * to the `$controls` array in the future.
	 *
	 * @return void
	 */
	public function register_controls_elementor() {

		$file_path = SOLACE_EXTRA_DIR_PATH . 'elementor/widgets/controls/gradient-text.php';

		require_once( $file_path );

        Elementor_Plugin::instance()->controls_manager->add_group_control( Solace_Extra_Group_Control_Gradient_Text::get_type(), new Solace_Extra_Group_Control_Gradient_Text() );		

	}

	/**
	 * Sets the error reporting level to only show fatal errors and parse errors.
	 *
	 * This method limits error reporting to avoid displaying warnings and notices.
	 * Useful in production environments where minor errors should not be exposed.
	 *
	 * @return void
	 */
	public function set_error_reporting_level() {
		// Limit error reporting to fatal errors (E_ERROR) and parse errors (E_PARSE)
		// error_reporting( E_ERROR | E_PARSE );
	}

	/**
	 * Handle AJAX request to rename a post title.
	 *
	 * This function verifies the nonce, sanitizes and validates input,
	 * updates the post title using wp_update_post(), and returns a JSON response.
	 *
	 * @return void
	 */
	public function handle_ajax_rename_post_title() {
		// Verify nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'solace_conditions_nonce_action' ) ) {
			wp_send_json_error( array( 'error' => 'Invalid nonce!' ) );
		}	

		// Sanitize and prepare input
		$post_id   = absint($_POST['post_id'] ?? 0);
		$new_title = '';
		if ( isset( $_POST['new_title'] ) ) {
			$new_title = sanitize_text_field( wp_unslash( $_POST['new_title'] ) );
		}

		// Validate required parameters
		if (!$post_id || !$new_title) {
			wp_send_json_error('Invalid data.');
		}

		// Attempt to update the post title
		$updated = wp_update_post([
			'ID'         => $post_id,
			'post_title' => $new_title,
		], true);

		// Return error if update fails
		if (is_wp_error($updated)) {
			wp_send_json_error($updated->get_error_message());
		}

		// Return success response
		wp_send_json_success('Post title updated.');
	}

	/**
	 * Set up single product preview context for Elementor.
	 *
	 * This function replaces the global post object with the selected product
	 * to simulate a single product view using a query string.
	 */
	public function preview_single_product() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET['solace-single-product-preview'], $_GET['product_id'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$product_id = absint($_GET['product_id']);
			$product = get_post($product_id);

			// Only proceed if the post exists and is a WooCommerce product
			if ($product && $product->post_type === 'product') {
				global $post, $wp_query;

				// Override the global post and query to behave like a product single page
				$post = $product;
				setup_postdata($post);

				$wp_query->post = $post;
				$wp_query->posts = [$post];
				$wp_query->queried_object = $post;
				$wp_query->is_singular = true;
				$wp_query->is_page = false;
				$wp_query->is_single = true;
			}
		}
	}

	/**
	 * Hide header and footer when previewing a single product with Elementor.
	 *
	 * Outputs inline CSS to hide header/footer elements during preview mode.
	 */
	public function hide_header_footer_on_product_preview() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET['solace-single-product-preview'], $_GET['product_id'])) {
			echo '<style>
				header.header,
				footer.site-footer,
				.delayed-content {
					display: none !important;
				}
			</style>';
		}
	}

	/**
	 * Handles the rendering of a purchase summary preview via Elementor,
	 * triggered by the `solace-single-product-preview` query parameter.
	 *
	 * Example: ?solace-single-product-preview=123
	 */
	public function handle_preview_single_product() {
		// Check if the preview parameter is set in the URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['solace-single-product-preview'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$single_product_id = absint( $_GET['solace-single-product-preview'] );

			// Validate the ID
			if ( $single_product_id > 0 ) {

				// Ensure Elementor is active and the method exists
				if (
					class_exists( '\Elementor\Plugin' ) &&
					class_exists( 'WooCommerce' ) &&
					method_exists( Elementor\Plugin::instance()->frontend, 'get_builder_content_for_display' )
				) {
					// Optional: Ensure the post exists and is of allowed type
					$post = get_post( $single_product_id );
					if ( $post ) {

						// Output HTML structure
						?><!DOCTYPE html>
						<html <?php language_attributes(); ?>>
						<head>
							<meta charset="<?php bloginfo( 'charset' ); ?>">
							<meta name="viewport" content="width=device-width, initial-scale=1">
							<?php wp_head(); ?>
							<style>
								#wpadminbar,
								header.header,
								footer.site-footer,
								.delayed-content {
									display: none !important;
								}
								body {
									overflow: hidden !important;
								}
								<?php
								// phpcs:ignore WordPress.Security.NonceVerification.Recommended
								if ( isset( $_GET['solace-hide-overflow'] ) ) {
									?>
									body.single-solace-sitebuilder {
										overflow: hidden !important;
									}
									<?php
								}
								?>
							</style>
						</head>
						<body <?php body_class(); ?>>
							<?php
							// Render the Elementor content for the given ID
							$elementor_instance = Elementor\Plugin::instance();
							echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $single_product_id ) );
							wp_footer();
							?>
						</body>
						</html>
						<?php
						exit;
					}
				}
			}
		}
	}	

	/**
	 * Handles the rendering of a purchase summary preview via Elementor,
	 * triggered by the `solace-purchase-summary-preview` query parameter.
	 *
	 * Example: ?solace-purchase-summary-preview=123
	 */
	public function handle_preview_purchase_summary() {
		// Check if the preview parameter is set in the URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['solace-purchase-summary-preview'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$purchase_summary_id = absint( $_GET['solace-purchase-summary-preview'] );

			// Validate the ID
			if ( $purchase_summary_id > 0 ) {

				// Ensure Elementor is active and the method exists
				if (
					class_exists( '\Elementor\Plugin' ) &&
					class_exists( 'WooCommerce' ) &&
					method_exists( Elementor\Plugin::instance()->frontend, 'get_builder_content_for_display' )
				) {
					// Optional: Ensure the post exists and is of allowed type
					$post = get_post( $purchase_summary_id );
					if ( $post ) {

						// Check if preview mode is active via query parameter
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						if ( isset( $_GET['solace-purchase-summary-preview'] ) ) {
							// Output CSS to hide specific elements for cleaner preview
							echo '<style>
								#wpadminbar,
								header.header,
								footer.site-footer,
								.delayed-content,
								.main-page,
								.wrapper {
									display: none !important;
								}
								body {
									background: #fff !important;
									overflow: hidden !important;
								}
							</style>';
						}

						// Output CSS to hide specific elements overflow
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						if ( isset( $_GET['solace-hide-overflow'] ) ) {
							echo '<style>
							.woocommerce-order-received {
								overflow: hidden !important;
							}
							</style>';
						}

						// Render the Elementor content for the given ID
						$elementor_instance = Elementor\Plugin::instance();
						echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $purchase_summary_id ) );
	
					}
				}
			}
		}
	}

	/**
	 * Handles the rendering of a purchase summary preview via Elementor,
	 * triggered by the `solace-single-post-preview` query parameter.
	 *
	 * Example: ?solace-single-post-preview=123
	 */
	public function handle_preview_single_post() {
		// Check if the preview parameter is set in the URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['solace-single-post-preview'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$single_post_id = absint( $_GET['solace-single-post-preview'] );

			// Validate the ID
			if ( $single_post_id > 0 ) {

				// Ensure Elementor is active and the method exists
				if (
					class_exists( '\Elementor\Plugin' ) &&
					method_exists( Elementor\Plugin::instance()->frontend, 'get_builder_content_for_display' )
				) {
					// Optional: Ensure the post exists and is of allowed type
					$post = get_post( $single_post_id );
					if ( $post ) {

						// Output HTML structure
						?><!DOCTYPE html>
						<html <?php language_attributes(); ?>>
						<head>
							<meta charset="<?php bloginfo( 'charset' ); ?>">
							<meta name="viewport" content="width=device-width, initial-scale=1">
							<?php wp_head(); ?>
							<style>
								#wpadminbar,
								header.header,
								footer.site-footer,
								.delayed-content {
									display: none !important;
								}
								body {
									overflow: hidden !important;
								}								
								<?php
								// phpcs:ignore WordPress.Security.NonceVerification.Recommended
								if ( isset( $_GET['solace-hide-overflow'] ) ) {
									?>
									body.single-solace-sitebuilder {
										overflow: hidden !important;
									}
									<?php
								}
								?>
							</style>
						</head>
						<body <?php body_class(); ?>>
							<?php
							// Render the Elementor content for the given ID
							$elementor_instance = Elementor\Plugin::instance();
							echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $single_post_id ) );
							wp_footer();
							?>
						</body>
						</html>
						<?php
						exit;
					}
				}
			}
		}
	}	

	/**
	 * Handles the rendering of a 404 page preview via Elementor,
	 * triggered by the `solace-404-preview` query parameter.
	 *
	 * Example: ?solace-404-preview=123
	 */
	public function handle_preview_404() {
		// Check if the preview parameter is set in the URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['solace-404-preview'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$template_404_id = absint( $_GET['solace-404-preview'] );

			// Validate the ID
			if ( $template_404_id > 0 ) {

				// Ensure Elementor is active and the method exists
				if (
					class_exists( '\Elementor\Plugin' ) &&
					method_exists( Elementor\Plugin::instance()->frontend, 'get_builder_content_for_display' )
				) {
					// Ensure the post exists and is of allowed type
					$post = get_post( $template_404_id );
					if ( $post ) {

						// Output HTML structure
						?><!DOCTYPE html>
						<html <?php language_attributes(); ?>>
						<head>
							<meta charset="<?php bloginfo( 'charset' ); ?>">
							<meta name="viewport" content="width=device-width, initial-scale=1">
							<?php wp_head(); ?>
							<style>
								#wpadminbar,
								header.header,
								footer.site-footer,
								.delayed-content {
									display: none !important;
								}

								body {
									overflow: hidden !important;
								}

								.elementor-invisible {
									visibility: hidden;
									opacity: 0;
									transform: translateY(-45px);
								}

								.elementor-invisible.solace-animate {
									visibility: visible;
									opacity: 1;
									transform: translateY(0);
									transition: opacity 0.6s ease, transform 0.6s ease;
								}
								<?php
								// phpcs:ignore WordPress.Security.NonceVerification.Recommended
								if ( isset( $_GET['solace-hide-overflow'] ) ) {
									?>
									body.single-solace-sitebuilder {
										overflow: hidden !important;
									}
									<?php
								}
								?>
							</style>
						</head>
						<body <?php body_class(); ?>>
							<?php
							// Render the Elementor content for the given ID
							$elementor_instance = Elementor\Plugin::instance();
							echo do_shortcode( $elementor_instance->frontend->get_builder_content_for_display( $template_404_id ) );
							wp_footer();
							?>
							<script>
							document.addEventListener('DOMContentLoaded', () => {
								document.querySelectorAll('.elementor-invisible').forEach(el => {
									el.classList.add('solace-animate');
								});
							});
							</script>
						</body>
						</html>
						<?php
						exit;
					}
				}
			}
		}
	}

	public static function is_plugin_installed( $plugin_slug ) {
		$plugin_file = WP_PLUGIN_DIR . '/' . $plugin_slug;
		return file_exists( $plugin_file );
	}

	/**
	 * Register dedicated Elementor categories and move them to the top.
	 *
	 * Runs only inside the Elementor editor when a Solace preview/edit flag is present
	 * in the editor URL. Adds several Solace categories and reorders them to appear
	 * at the top for better visibility.
	 *
	 * @param Elementor\Elements_Manager $elements_manager Elementor Elements Manager instance.
	 * @return void
	 */
	function solace_extra_register_priority_category( $elements_manager ) {
		// Only apply when the preview/editing flag exists in the Elementor editor URL.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$requested_priority_id = null;

		// Define Solace categories to ensure they exist and are surfaced at the top.
		$solace_categories = [
			'solace-extra-woocommerce' => [
				'title' => __( 'Solace Extra - WooCommerce', 'solace-extra' ),
				'icon'  => 'eicon-woocommerce',
			],
			'solace-extra-shop' => [
				'title' => __( 'Solace Extra - Shop', 'solace-extra' ),
				'icon'  => 'eicon-products',
			],
			'solace-extra-archive' => [
				'title' => __( 'Solace Extra - Blog', 'solace-extra' ),
				'icon'  => 'eicon-archive',
			],
			'solace-extra-single-post' => [
				'title' => __( 'Solace Extra - Single Post', 'solace-extra' ),
				'icon'  => 'eicon-post',
			],
			'solace-extra-single' => [
				'title' => __( 'Solace Extra - Single Product', 'solace-extra' ),
				'icon'  => 'eicon-single-post',
			],
			'solace-extra-cart' => [
				'title' => __( 'Solace Extra - Cart', 'solace-extra' ),
				'icon'  => 'eicon-cart',
			],
			'solace-extra-checkout' => [
				'title' => __( 'Solace Extra - Checkout', 'solace-extra' ),
				'icon'  => 'eicon-checkout',
			],
			'solace-extra' => [
				'title' => __( 'Solace Extra', 'solace-extra' ),
				'icon'  => 'eicon-star',
			],
		];

		// Determine if any specific Solace category key is present in the URL and set it as priority.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		foreach ( array_keys( $solace_categories ) as $cat_id ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( ! empty( $_GET[ $cat_id ] ) ) {
				$requested_priority_id = $cat_id;
				break;
			}
		}

		// If no GET parameter matches any Solace category ID, bail out.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( null === $requested_priority_id ) {
			return;
		}

		// Add or ensure each category exists.
		// Only the requested category (if any) is given a top position.
		foreach ( $solace_categories as $id => $data ) {
			$position = ( $requested_priority_id === $id ) ? 1 : null;
			$elements_manager->add_category( $id, [
				'title' => $data['title'],
				'icon'  => $data['icon'],
			], $position );
		}

		// If a specific category is requested, move ONLY that category to the very top.
		if ( $requested_priority_id ) {
			$categories = $elements_manager->get_categories();
			if ( isset( $categories[ $requested_priority_id ] ) ) {
				$requested = [ $requested_priority_id => $categories[ $requested_priority_id ] ];
				unset( $categories[ $requested_priority_id ] );
				$reordered = $requested + $categories;

				$reflection = new ReflectionObject( $elements_manager );
				$property   = $reflection->getProperty( 'categories' );
				$property->setAccessible( true );
				$property->setValue( $elements_manager, $reordered );
			}
		}
	}

    /**
     * Enqueue inline script to customize Elementor editor menu (popover) items.
     * Adds a custom dashboard link and renames "Exit to WordPress" text.
     */
    public function enqueue_elementor_editor_menu_customization_script() {
        // Valid part values
        $valid_parts = ['header', 'footer', 'singleproduct', 'shopproduct', 'purchase-summary', 'blogsinglepost', 'blogarchive', '404'];
        
        // Get part parameter from URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $part_param = isset($_GET['part']) ? sanitize_text_field( wp_unslash( $_GET['part'] ) ) : '';
        
        // Return early if part parameter is not valid
        if (empty($part_param) || !in_array($part_param, $valid_parts)) {
            return;
        }
    ?>
	<style>
		a.solace-custom-menu-item {
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			gap: 15px;
			padding: 4px 16px;
			color: #fff;
			font-size: 0.875rem;
			line-height: 1.43;
		}
	</style>
    <script>
    (function(){
        'use strict';
        // Gate modifier so it only runs when Elementor Logo button triggers the menu
        var shouldModifyNextPopover = false;
        function modifyPopover(pop) {
            if (!pop || pop.dataset.solaceModified === '1' || !shouldModifyNextPopover) return;

            // const exitSpan = Array.from(pop.querySelectorAll('span, .MuiTypography-root, .MuiListItemText-primary'))
            //     .find(function(el){ return el.textContent && el.textContent.trim().match(/^Exit to WordPress$/i); });
            // if (exitSpan) {
            //     exitSpan.textContent = 'Kembali ke Dashboard';
            // }

            if (!pop.querySelector('.solace-custom-menu-item')) {
                var listRoot = pop.querySelector('.MuiList-root');
                if (listRoot) {
                    var item = document.createElement('a');
                    item.className = 'MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters solace-custom-menu-item';
                    item.setAttribute('role','menuitem');
                    item.setAttribute('tabindex','-1');
                    item.href = '#';
                    item.innerHTML = '\n                        <div class="MuiListItemIcon-root">\n                            <svg width="14" height="24" viewBox="0 0 14 24" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\n<rect width="14" height="24" fill="url(#pattern0_1_4)"/>\n<defs>\n<pattern id="pattern0_1_4" patternContentUnits="objectBoundingBox" width="1" height="1">\n<use xlink:href="#image0_1_4" transform="scale(0.0714286 0.0416667)"/>\n</pattern>\n<image id="image0_1_4" width="14" height="24" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAYCAYAAADKx8xXAAAD/0lEQVR4AXSUDUyVVRzGf+8BLldkVxAS+RjoBn6UWiqb+U2Z2nJdnTaRtkyZiZIwJUzAlpSFQ42SmYplEXWxMFRQnMLQFFiKGIEtFYYIUsZHECFfF+59O+cqbbn87zzvOf/n/zzvec/HXsHjw1Gb7odb6x5K9e/ouxhN+EO5cBQfJkOd4jSZ2K8nc7Aim87cqyRp4RgDJxBhz6Qjbh7+SiQ1jqbEKrdfeJNwvRDdaKBFC8NlQw5lUuE8NpZlnZ2UfBRFoxJKDif50NOWMKbne9qfCSUqNBFDSBLJgIuErSqR1/Xz6Od/4vP6XzgrJMnMAAydR7gSFcnlQ5lM9lzLc9euqQp6+lJCBov52+sJlr73PE4rviLfNIJRoiyWVZd20lxeTsrwFYyKO02rspjHY+z6luuRqyj6eD/BAXGYk3HMDgaGi1nTOdreyumFGeRLgyZhrU3lcF4Gf54qYJN7BP5bT/AXMtaEMaz3JPWDfVSKhnosmSWky82wV8UTL9dh72qjSQvD8OrXlEq9kLBWbOPAkVhqMy2Ej04iQpiM+G87xWVtEdqEaXy4NhK3aXvYKcXOErbSGN6QL9X9g5jU3UzD8WqaJI/QwaYGCrYO2pqdkRT66gmM6MmmMTCQSSsX4ewbzby0k6w/k0Dl1c1sV5+hPA8g+DefM4W3hrnhaROMPAZ2JQj0xEXvpSt0HIlCEf8Hbze8C/IxV1aRo5/B9nsKl155CYshmpCGPygXg7YHb+ORsA7S42pg7PIs8tYmMdLLn9Gm1QRLme5mwCS8ApksE0fTBGpDUDEtmKdDApiqxr/14mbvZUCNhyCOFxGjF6NnRzCz/R43zaH46he5332fMox49x/jnocrrpqVviGT6sXKLHK1BWjL5pDqN4XZEfMp2rSVoKl7eWdMPBHmHTyVs4saq/ERo3Tr1TuwDPNlymELEz02EvJpOV2SJ3EBXjkJ/NxSww+L3+cFyQkJBuxYxeA3dLoPUKctxyMql3pZUEXr3b2cSHmXhqxclvhsYeGPTajztn9ixscvmBmitZZbQRNZXZeGRZr6K95mg16Cfvc2Z7X5uMfkcUPyWlgY9raDFG1cx83N+/ATmisdTq8xxthOtTqvAF/ma3PRZh3gM2lwkhis2UXShQQG7tzgS1cznvuu0CLQ6ZdF/D8gtesOjaO3oP4rLpKzF0QxWz9Hv64zVnsRLTSdo5JXS9GFTxAvpy/nWUng5IIHMtZPZ0R3Hk1zZ3BoaSze45NYJ2k1u7rHjusnFu/Gfc1Ksur3U9jWSF1jKl9k7Oa2JYvFpkiezL9FjzSppjZH9Q6Iwmp6TasYl3uO7YE++BZXUSjP1bQ+l1+lQpP4j0HmjvYPAAAA///LG/44AAAABklEQVQDAHjRZxs0e4iWAAAAAElFTkSuQmCC"/>\n</defs>\n</svg>\n                        </div>\n                        <div class="MuiListItemText-root">\n                            <span class="MuiTypography-root MuiTypography-body2 MuiListItemText-primary">' + SolaceEditorData.text_submenu + '</span>\n                        </div>\n                    ';
                    item.addEventListener('click', function(e){
                        e.preventDefault();
                        
                        // Extract part parameter from current URL
                        var currentUrl = new URL(window.location.href);
                        var partParam = currentUrl.searchParams.get('part');
                        
                        // Build dashboard URL with part parameter if it exists
                        var dashboardUrl = SolaceEditorData.admin_url + 'admin.php?page=dashboard-sitebuilder';
                        if (partParam) {
                            dashboardUrl += '&part=' + encodeURIComponent(partParam);
                        }
                        
                        window.location.href = dashboardUrl;
                    });
                    var hr = pop.querySelector('hr.MuiDivider-root');
                    if (hr && hr.parentNode) {
                        hr.parentNode.insertBefore(item, hr);
                    } else if (listRoot) {
                        listRoot.appendChild(item);
                    }
                }
            }
            pop.dataset.solaceModified = '1';
            // Reset the gate after successful modification
            shouldModifyNextPopover = false;
        }
        var observer = new MutationObserver(function(mutations){
            mutations.forEach(function(m){
                Array.prototype.forEach.call(m.addedNodes, function(node){
                    if (node.nodeType !== 1) return;
                    if (node.matches && (node.matches('.MuiPaper-root') || node.matches('.MuiPopover-paper') || node.matches('.MuiMenu-paper') || node.querySelector('.MuiList-root'))) {
                        var pop = node.matches('.MuiPaper-root') ? node : (node.querySelector('.MuiPaper-root, .MuiPopover-paper, .MuiMenu-paper, .MuiList-root'));
                        if (pop) modifyPopover(pop);
                    }
                });
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
        document.addEventListener('click', function(e){
            var btn = e.target.closest('button');
            if (!btn) return;

            // Only react to the Elementor Logo toggle button
            var titleEl = btn.querySelector('svg title');
            var isElementorLogoButton = !!(titleEl && titleEl.textContent && titleEl.textContent.trim() === 'Elementor Logo');
            if (!isElementorLogoButton) {
                // Ensure we do not modify for other buttons' menus
                shouldModifyNextPopover = false;
                return;
            }

            // Arm the gate; the next popover opening should be modified
            shouldModifyNextPopover = true;

            var tries = 0;
            var interval = setInterval(function(){
                var pop = document.querySelector('.MuiPaper-root.MuiPopover-paper, .MuiPopover-paper, .MuiMenu-paper, .MuiPaper-root');
                if (pop) { modifyPopover(pop); clearInterval(interval); }
                tries++; if (tries > 20) clearInterval(interval);
            }, 100);
        }, true);
    })();
    </script>
    <?php
    }

	/**
	 * Enqueue custom scripts for Elementor editor
	 * 
	 * Registers and enqueues custom JavaScript for the Elementor editor interface
	 * with localized data for AJAX functionality.
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_elementor_editor_scripts() {
		// Register custom JavaScript for Elementor editor
		wp_register_script(
			'solace-elementor-editor',
			SOLACE_EXTRA_ASSETS_URL . 'js/solace-editor.js',
			[ 'jquery' ],
			SOLACE_EXTRA_VERSION,
			true
		);

		wp_enqueue_script( 'solace-elementor-editor' );

		// Localize script data for AJAX functionality
		$localize_data = [
			'ajax_url'     => admin_url( 'admin-ajax.php' ),
			'admin_url'    => admin_url(),
			'nonce'        => wp_create_nonce( 'solace_editor_nonce' ),
			'text_submenu' => __( 'Solace Site Builder', 'solace-extra' ),
			'editorMode'   => 'elementor', // Flag to indicate Elementor editor context
		];

		wp_localize_script( 'solace-elementor-editor', 'SolaceEditorData', $localize_data );
	}

	/**
	 * Global counter to track if products shortcode is being processed
	 * Using counter to handle multiple shortcodes on same page
	 */
	private static $products_shortcode_counter = 0;

	/**
	 * Detect when WooCommerce [products] shortcode starts processing
	 *
	 * @param array  $query_args Query arguments.
	 * @param array  $atts       Shortcode attributes.
	 * @param string $type       Shortcode type.
	 * @return array Modified query arguments.
	 */
	public function detect_products_shortcode_start( $query_args, $atts, $type ) {
		// Increment counter when any products-based shortcode is being processed (products, sale_products, etc.)
		if ( strpos( $type, 'products' ) !== false ) {
			self::$products_shortcode_counter++;
		}
		return $query_args;
	}

	/**
	 * Wrap products shortcode to reset counter after output
	 *
	 * @param string $output Shortcode output.
	 * @param string $tag    Shortcode tag.
	 * @return string Modified output.
	 */
	public function wrap_products_shortcode( $output, $tag ) {
		if ( $tag === 'products' && self::$products_shortcode_counter > 0 ) {
			// Decrement counter after shortcode output
			self::$products_shortcode_counter--;
		}
		return $output;
	}

	/**
	 * Modify WooCommerce add to cart button class ONLY for products shortcode
	 * Changes 'button' class to 'solace-extra-button' and ensures 'elementor-button' class exists
	 *
	 * @param string $link    Add to cart link HTML.
	 * @param object $product Product object.
	 * @return string Modified link HTML.
	 */
	public function modify_add_to_cart_button_class( $link, $product ) {
		// Only modify if products shortcode is being processed (counter > 0)
		if ( self::$products_shortcode_counter <= 0 ) {
			return $link;
		}

		// Extract the class attribute value
		if ( preg_match( '/class="([^"]*)"/', $link, $matches ) ) {
			$classes = $matches[1];

			// Replace 'button' with 'solace-extra-button'
			$classes = preg_replace( '/\bbutton\b/', 'solace-extra-button', $classes );

			// Add 'elementor-button' if it doesn't exist
			if ( strpos( $classes, 'elementor-button' ) === false ) {
				$classes .= ' elementor-button';
			}

			// Replace the class attribute in the link
			$link = preg_replace( '/class="[^"]*"/', 'class="' . esc_attr( trim( $classes ) ) . '"', $link );
		}

		return $link;
	}
}
