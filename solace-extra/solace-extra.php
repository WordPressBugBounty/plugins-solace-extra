<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://solacewp.com
 * @since             1.1.6
 * @package           Solace_Extra
 *
 * Plugin Name:       Solace Extra
 * Plugin URI:        https://solacewp.com/
 * Description:       Additional features for Solace Theme
 * Version:           1.7.3
 * Requires PHP:      7.4
 * Author:            Solace
 * Author URI:        https://solacewp.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       solace-extra
 * Domain Path:       /languages
 */

use \Elementor\Plugin as Elementor_Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'ABSPATH' ) || exit;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SOLACE_EXTRA_VERSION', '1.7.3' );

/**
 * Bumped whenever a change affects the rewrite rules this plugin contributes.
 *
 * Activation flushes on a fresh install, but updating an already-active plugin
 * does not re-run activation, so existing sites need their own trigger. Raising
 * this makes each site flush exactly once on its next request.
 */
define( 'SOLACE_EXTRA_REWRITE_VERSION', '1' );
define( 'SOLACE_EXTRA_DIR', plugin_dir_url( __FILE__ ) );
define( 'SOLACE_EXTRA_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'SOLACE_EXTRA_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
define( 'SOLACE_EXTRA_SLUG', dirname( plugin_basename( __FILE__ ) ) );
define( 'SOLACE_EXTRA_API_BASE_URL', 'https://solacewp.com/wp-json/solace/v1/' );
define( 'SOLACE_EXTRA_DEMO_IMPORT_URL', 'https://solacewp.com/' );
define( 'SOLACE_EXTRA_ELEMENTOR_ADDONS__FILE__', __FILE__ );
define( 'SOLACE_EXTRA_ELEMENTOR_ADDONS_BASE', plugin_basename( __FILE__ ) );
define( 'SOLACE_EXTRA_ELEMENTOR_ADDONS_DIR_PATH', plugin_dir_path( SOLACE_EXTRA_ELEMENTOR_ADDONS__FILE__ ) );
define( 'SOLACE_EXTRA_ELEMENTOR_ADDONS_DIR_URL', plugin_dir_url( SOLACE_EXTRA_ELEMENTOR_ADDONS__FILE__ ) );
define( 'SOLACE_EXTRA_ELEMENTOR_ADDONS_ASSETS', trailingslashit( SOLACE_EXTRA_ELEMENTOR_ADDONS_DIR_URL . 'assets' ) );
define( 'SOLACE_EXTRA_ELEMENTOR_ADDONS_WIDGET', trailingslashit( SOLACE_EXTRA_ELEMENTOR_ADDONS_DIR_PATH . 'widgets' ) );

// require plugin_dir_path( __FILE__ ) . 'classes/class-library-manager.php';
// require plugin_dir_path( __FILE__ ) . 'classes/class-library-source.php';

define( 'SOLACE_SITE_URL', urlencode( home_url() ) );
define( 'SOLACE_UPGRADE_BASE_URL', 'https://pro.solacewp.com/' );

define( 'SOLACE_UPGRADE_URL',
    add_query_arg(
        array(
            'utm_source'   => 'plugin',
            'utm_medium'   => 'upgrade_link',
            'utm_campaign' => 'solace_free_to_pro',
            'version'      => SOLACE_EXTRA_VERSION,
            'site_url'     => SOLACE_SITE_URL,
        ),
        SOLACE_UPGRADE_BASE_URL
    )
);
define( 'SOLACE_SUPPORT_URL',
    add_query_arg(
        array(
            'utm_source'   => 'plugin',
            'utm_medium'   => 'support_link',
            'utm_campaign' => 'solace_free_to_pro',
            'version'      => SOLACE_EXTRA_VERSION,
            'site_url'     => SOLACE_SITE_URL,
        ),
        SOLACE_UPGRADE_BASE_URL . 'my-account/my-tickets'
    )
);

global $solace_is_run_in_shortcode;

// set_time_limit(0);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-solace-extra-activator.php
 */
function solace_extra_activate() {
    add_option( 'solace_extra_redirect_after_activation_option', true );
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-solace-extra-activator.php';
	Solace_Extra_Activator::activate();

	// init has already fired by the time activation runs, and this plugin was
	// not loaded when it did, so the post type has to be registered by hand
	// here or its rules are missing from the set that gets written.
	solace_register_sitebuilder_post_type();
	flush_rewrite_rules();

	update_option( 'solace_extra_rewrite_version', SOLACE_EXTRA_REWRITE_VERSION, true );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-solace-extra-deactivator.php
 */
function solace_extra_deactivate() {

    // Delete the 'solace_disable_admin_notices' cookie directly
    if ( isset( $_COOKIE['solace_disable_admin_notices'] ) ) {
        // Set the cookie with an expiration time in the past to delete it
        setcookie( 'solace_disable_admin_notices', '', time() - 3600 );
    }

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-solace-extra-deactivator.php';
	Solace_Extra_Deactivator::deactivate();

	// Drop the Site Builder rules; the post type will not be registered on the
	// next request. The version marker goes too, so re-activating rebuilds them.
	flush_rewrite_rules();
	delete_option( 'solace_extra_rewrite_version' );
}

register_activation_hook( __FILE__, 'solace_extra_activate' );
register_deactivation_hook( __FILE__, 'solace_extra_deactivate' );

function solace_hide_hostinger_easy_onboarding_notice() {
    echo '<style>
        .hostinger-onboarding,
		.hostinger-onboarding-reminder,
        .hostinger-onboarding-notice,
        #hostinger-easy-onboarding,
        .hstm-admin-notice {
            display: none !important;
        }
    </style>';
}

function solace_hostinger_easy_onboarding_notice() {
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    if ( is_plugin_active( 'hostinger-easy-onboarding/hostinger-easy-onboarding.php' ) ) {
        add_action( 'admin_head', 'solace_hide_hostinger_easy_onboarding_notice' );
    }
}
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( isset( $_GET['singleproduct'] ) ) {
    add_action( 'admin_init', 'solace_hostinger_easy_onboarding_notice' );
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-solace-extra.php';

if (class_exists('Elementor\Plugin')) {
    require plugin_dir_path( __FILE__ ) . 'elementor/widgets/elementor.php';
    require_once( __DIR__ . '/elementor/widgets/controls/gradient-text.php' );
    require_once( __DIR__ . '/elementor/widgets/functions.php' );
    require_once( __DIR__ . '/elementor/widgets/woocommerce/functions.php' );
    require_once( __DIR__ . '/elementor/widgets/solace-custom-css.php' );


    /**
     * Registers Elementor widgets for the Solace Extra plugin.
     *
     * This function registers the Elementor List Widget and oEmbed Widget for use within the Solace Extra plugin.
     *
     * @param Elementor\Elements_Manager $widgets_manager The Elementor widgets manager.
     */
    function solace_extra_register_widget_elementor( $widgets_manager ) {
        require_once( __DIR__ . '/elementor/widgets/widget/nav-menu/nav-menu.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/purchase-summary/purchase-summary.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/site-logo/site-logo.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/shop/shop.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/shop/cart.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/shop/minicart.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/shop/checkout.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/shop/myaccount.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/shop/related-product.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/shop/product-addtocart.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/shop/producttab.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/shop/separator.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/archive-posts/archive-posts.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-author/post-author.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-categories/post-categories.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-excerpt/post-excerpt.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-title/post-title.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-featuredimage/featuredimage.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-fullcontent/fullcontent.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-tags/post-tags.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-datetime/post-datetime.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-comments/post-comments.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-breadcrumb/post-breadcrumb.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-navigation/post-navigation.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/social-share/social-share.php' );
        require_once( __DIR__ . '/elementor/widgets/widget/post-relatedpost/post-relatedpost.php' );

        // Define the WooCommerce widgets directory.
        $woocommerce_dir = __DIR__ . '/elementor/widgets/woocommerce';

        // Check if the folder exists before proceeding.
        if ( is_dir( $woocommerce_dir ) ) {
            solace_extra_register_woocommerce_widgets_recursively( $woocommerce_dir, $widgets_manager );
        }

        $widgets_manager->register( new \Solace_Extra_Nav_Menu() );
        $widgets_manager->register( new \Solace_Extra_Purchase_Summary() );
        $widgets_manager->register( new \Solace_Extra_Site_Logo() );
        $widgets_manager->register( new \Solace_Extra_WooCommerce_Shop() );
        $widgets_manager->register( new \Solace_Extra_WooCommerce_Cart() );
        $widgets_manager->register( new \Solace_Extra_WooCommerce_MiniCart() );
        $widgets_manager->register( new \Solace_Extra_WooCommerce_MyAccount() );
        $widgets_manager->register( new \Solace_Extra_WooCommerce_Checkout() );
        // $widgets_manager->register( new \Solace_Extra_WooCommerce_AddToCart() );
        $widgets_manager->register( new \Solace_Extra_WooCommerce_Related_Products() );
        $widgets_manager->register( new \Solace_Extra_WooCommerce_ProductAddToCart() );
        $widgets_manager->register( new \Solace_Extra_WooCommerce_ProductTab() );
        $widgets_manager->register( new \Solace_Extra_WooCommerce_Separator() );

        $widgets_manager->register( new \Solace_Extra_Post_Archive() );
        $widgets_manager->register( new \Solace_Extra_Post_Author() );
        $widgets_manager->register( new \Solace_Extra_Post_Categories() );
        $widgets_manager->register( new \Solace_Extra_Post_Excerpt() );
        $widgets_manager->register( new \Solace_Extra_Post_Title() );
        $widgets_manager->register( new \Solace_Extra_Post_Featured_Image() );
        $widgets_manager->register( new \Solace_Extra_Post_Full_Content() );
        $widgets_manager->register( new \Solace_Extra_Post_Tags() );
        $widgets_manager->register( new \Solace_Extra_Post_Date_Time() );
        $widgets_manager->register( new \Solace_Extra_Post_Comments() );
        $widgets_manager->register( new \Solace_Extra_Post_Breadcrumb() );
        $widgets_manager->register( new \Solace_Extra_Post_Navigation() );
        $widgets_manager->register( new \Solace_Extra_Post_RelatedPost() );
        $widgets_manager->register( new \Solace_Social_Share_Widget() );
    }
    add_action( 'elementor/widgets/register', 'solace_extra_register_widget_elementor' );


    /**
     * Adds a new category to the Elementor widgets manager for the Solace Extra plugin.
     *
     * This function adds a new category named 'Solace Extra' with an icon of 'eicon-star' to the Elementor widgets manager.
     *
     * @param Elementor\Elements_Manager $elements_manager The Elementor widgets manager.
     */
    function solace_extra_add_elementor_widget_categories( $elements_manager ) {

        $elements_manager->add_category(
            'solace-extra',
            [
                'title' => esc_html__( 'Solace Extra', 'solace-extra' ),
                'icon' => 'eicon-star',
            ]
        );

		foreach ( solace_extra_widget_categories() as $id => $data ) {
			$elements_manager->add_category(
				$id,
				[
					'title'	=> $data['title'],
					'icon'	=> $data['icon'],
				]
			);
		}        
    }
    add_action( 'elementor/elements/categories_registered', 'solace_extra_add_elementor_widget_categories' );

}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function solace_extra_run() {
    $plugin = new Solace_Extra();
	$plugin->run();

}
solace_extra_run();

// Two logo-upload leftovers were removed here.
//
// solace_extra_handle_logo_upload() ran on init and, for a POST carrying
// action=upload_logo_image, fetched whatever URL the request named with
// wp_remote_get() and stored the response in the media library. Nothing in the
// plugin, the theme or Solace Extra Pro ever sent that request. The URL was
// unrestricted, so it also reached addresses only the server can see --
// 127.0.0.1, a database admin panel, a cloud metadata endpoint -- and put the
// answer where it could be read back. It also trusted the remote
// Content-Type header as the attachment's MIME type. Deleting it closes that
// rather than hardening a feature nobody calls.
//
// add_action( 'wp_ajax_solace_extra_upload_logo', 'solace_extra_upload_logo' )
// pointed at a function defined inside admin/partials/submenu-dashboardstep5.php,
// which is only loaded while that admin page renders -- never during an
// admin-ajax request. Any logged-in user calling that action got a PHP fatal:
// "call_user_func_array(): Argument #1 ($callback) must be a valid callback".
// No caller existed for it either. The function itself is left where it is,
// inert, for the dead-code pass.

if ( ! function_exists( 'solace_is_run_in_shortcode' ) ) {

	/**
	 * solace_is_run_in_shortcode - Returns true when posts run in from shortcode.
	 *
	 * @return bool
	 */
	function solace_is_run_in_shortcode() {
        global $solace_is_run_in_shortcode;
        
		return $solace_is_run_in_shortcode;
	}
}



// CODE FOR SITE-BUILDER IS BEGIN HERE

function solace_register_sitebuilder_post_type() {
    $labels = array(
        'name'                  => _x( 'Site Builder Parts', 'Post type general name', 'solace-extra' ),
        'singular_name'         => _x( 'Site Builder Part', 'Post type singular name', 'solace-extra' ),
        'menu_name'             => _x( 'Website Parts', 'Admin Menu text', 'solace-extra' ),
        'name_admin_bar'        => _x( 'Site Builder Part', 'Add New on Toolbar', 'solace-extra' ),
        'add_new'               => __( 'Create New', 'solace-extra' ),
        'add_new_item'          => __( 'Create New Site Builder Part', 'solace-extra' ),
        'new_item'              => __( 'New Site Builder Part', 'solace-extra' ),
        'edit_item'             => __( 'Edit Site Builder Part', 'solace-extra' ),
        'view_item'             => __( 'View Site Builder Part', 'solace-extra' ),
        'all_items'             => __( 'All Site Builder Parts', 'solace-extra' ),
        'search_items'          => __( 'Search Site Builder Parts', 'solace-extra' ),
        'not_found'             => __( 'No site builder parts found.', 'solace-extra' ),
        'not_found_in_trash'    => __( 'No site builder parts found in Trash.', 'solace-extra' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        // 'show_in_menu'       => true,
        'show_in_menu'       => 'dashboard', // Set as submenu of Solace menu
        'menu_position'      => 25,
        // 'rewrite'            => array('slug' => 'solace-sitebuilder'),
        'capability_type'    => 'post',
        'menu_icon'          => 'dashicons-editor-code',
        'supports' => array('title', 'editor', 'thumbnail'),

        'has_archive'        => false,
        'exclude_from_search'=> true,
        'publicly_queryable' => true,
    );
    solace_add_elementor_cpt_support();

    register_post_type( 'solace-sitebuilder', $args );

}
add_action( 'init', 'solace_register_sitebuilder_post_type' );

/**
 * Persist the rewrite rules once, instead of on every single request.
 *
 * register_post_type() only adds its rules to the in-memory set; they are not
 * written to the rewrite_rules option until something flushes. That used to be
 * handled by calling flush_rewrite_rules() directly from this init callback,
 * which regenerated every rule on the site and rewrote that option -- routinely
 * tens of kilobytes -- on *every* request, front end and admin alike, while
 * also invalidating the alloptions cache each time.
 *
 * Flushing is now driven by SOLACE_EXTRA_REWRITE_VERSION: a site whose stored
 * value already matches does nothing at all. Runs at priority 20 so the post
 * type registered above at the default priority is part of what gets written.
 *
 * The marker option is autoloaded on purpose. It is read on every request, so
 * riding along in the single alloptions query costs nothing, whereas an
 * autoload=false option would add a SELECT of its own each time.
 */
function solace_extra_maybe_flush_rewrite_rules() {
    if ( SOLACE_EXTRA_REWRITE_VERSION === get_option( 'solace_extra_rewrite_version' ) ) {
        return;
    }

    flush_rewrite_rules();

    update_option( 'solace_extra_rewrite_version', SOLACE_EXTRA_REWRITE_VERSION, true );
}
add_action( 'init', 'solace_extra_maybe_flush_rewrite_rules', 20 );

function solace_add_admin_menus() {

    add_submenu_page(
        'solace',                   
        __('Site Builder Parts', 'solace-extra'), 
        __('Site Builder Parts', 'solace-extra'), 
        'manage_options',                      // Capability
        'edit.php?post_type=solace-sitebuilder' // URL submenu
    );
}
// add_action('admin_menu', 'solace_add_admin_menus');

 function solace_add_elementor_cpt_support() {

    if ( ! is_admin() ) {
        return;
    }

    $cpt_support = get_option( 'elementor_cpt_support' );

    if ( ! $cpt_support ) {
        update_option( 'elementor_cpt_support', array( 'post', 'page', 'solace-sitebuilder' ) );
    } elseif ( ! in_array( 'solace-sitebuilder', $cpt_support, true ) ) {
        $cpt_support[] = 'solace-sitebuilder';
        update_option( 'elementor_cpt_support', $cpt_support );
    }

}
// Add Elementor support for the custom post type
function solace_add_elementor_support_for_sitebuilder() {
    \Elementor\Plugin::instance()->elements_manager->add_category(
        'solace-sitebuilder',
        array(
            'title' => __( 'Site Builder Parts', 'solace-extra' ),
            'icon' => 'fa fa-plug',
        ),
        1
    );
}
add_action( 'elementor/elements/categories_registered', 'solace_add_elementor_support_for_sitebuilder' );

// Shared Site Builder helpers. Must load BEFORE sitebuilder.php so the
// resolver, the status writer and the page classifier are available to it.
require plugin_dir_path( __FILE__ ) . 'sitebuilder/helpers.php';

require plugin_dir_path( __FILE__ ) . 'sitebuilder/sitebuilder.php';

// add_action('wp_head', 'solace_display_custom_header');
function solace_check_import_status() {
    // Verify nonce.
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce')) {
        wp_send_json_error('Invalid nonce.');
    }

    // Check user capability.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized.' );
    }

    $status = get_option('solace_extra_import_zip_complete', false); 
    wp_send_json([
        'completed' => (bool) $status 
    ]);
    exit; 
}
add_action('wp_ajax_check_import_status', 'solace_check_import_status');


function solace_disable_image_processing() {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.Security.NonceVerification.Recommended,WordPress.Security.NonceVerification.Missing
        $action = isset($_GET['action']) ? sanitize_key(wp_unslash($_GET['action'])) : 
                (isset($_POST['action']) ? sanitize_key(wp_unslash($_POST['action'])) : 'not_detected');
        // phpcs:enable
        if ($action === 'wp_import_posts_process') {
            add_filter('intermediate_image_sizes_advanced', 'solace_disable_sizes');
        } else {
        }
        
    } 
    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.Security.NonceVerification.Recommended
    else if (isset($_GET['page']) && $_GET['page'] === 'dashboard-progress') {
        add_filter('intermediate_image_sizes_advanced', 'solace_disable_sizes');
    }
    // phpcs:enable
}

function solace_disable_sizes($sizes) {
    return [];
}

solace_disable_image_processing();


// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Used as cron callback, name kept for compatibility
function generate_thumbnails_for_single_image( $image_id ) {
    if ( ! wp_attachment_is_image( $image_id ) ) {
        return false;
    }

    $file_path = get_attached_file( $image_id );
    if ( ! $file_path || ! file_exists( $file_path ) ) {
        return false;
    }

    $sizes = array(
        'solace-single2'                             => array( 2000, 975 ),
        'solace-single-default'                      => array( 1220, 715 ),
        'solace-default-blog'                        => array( 1214, 715 ),
        'solace-related-posts'                       => array( 250, 277 ),
        'solace-wc-shop-layout3-layout4-layout6'     => array( 350, 500 ),

        'thumbnail'       => array( (int) get_option( 'thumbnail_size_w' ), (int) get_option( 'thumbnail_size_h' ) ),
        'medium'          => array( (int) get_option( 'medium_size_w' ), (int) get_option( 'medium_size_h' ) ),
        'medium_large'    => array( (int) get_option( 'medium_large_size_w' ), (int) get_option( 'medium_large_size_h' ) ),
        'large'           => array( (int) get_option( 'large_size_w' ), (int) get_option( 'large_size_h' ) ),
    );

    $metadata = wp_get_attachment_metadata( $image_id );

    if ( isset( $metadata['sizes'] ) ) {
        foreach ( $metadata['sizes'] as $size_name => $size_data ) {
            if ( isset( $sizes[$size_name] ) ) {
                $file = dirname( $file_path ) . '/' . $size_data['file'];
                if ( file_exists( $file ) ) {
                    wp_delete_file( $file );

                }
            }
        }
    }

    foreach ( $sizes as $size_name => $dimensions ) {
        $thumbnail = image_make_intermediate_size( $file_path, $dimensions[0], $dimensions[1], true );
        if ( $thumbnail ) {
            $metadata['sizes'][$size_name] = array(
                'file'      => basename( $thumbnail['file'] ),
                'width'     => $thumbnail['width'],
                'height'    => $thumbnail['height'],
                'mime-type' => get_post_mime_type( $image_id ),
            );
            wp_update_attachment_metadata( $image_id, $metadata );
        } else {
        }
    }
}
    

add_action('wp', function() {
    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.Security.NonceVerification.Recommended
    if (isset($_GET['page']) && $_GET['page'] === 'dashboard-congratulations') {
        if (!wp_next_scheduled('generate_thumbnails_cron')) {
            
            wp_schedule_single_event(time() + 10, 'generate_thumbnails_cron');
            
        }
    }
    // phpcs:enable
});

add_action('generate_thumbnails_cron', function() {
    if (get_transient('thumbnails_cron_running')) {
        return;
    }

    set_transient('thumbnails_cron_running', true, 0);
    // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged
    set_time_limit(0);
    ignore_user_abort(true);


    $unfinished_jobs = get_transient('unfinished_thumbnails');

    if (empty($unfinished_jobs)) {
        $args = array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'nopaging'       => true,
        );
        
        remove_all_filters('posts_clauses');
        delete_transient('unfinished_thumbnails');
        
        $query = new WP_Query($args);
        $unfinished_jobs = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $unfinished_jobs[] = get_the_ID();
            }
            wp_reset_postdata();
        }
        
        set_transient('unfinished_thumbnails', $unfinished_jobs, 3600);
    }

    if (!empty($unfinished_jobs)) {
        $image_id = array_shift($unfinished_jobs);
        set_transient('unfinished_thumbnails', $unfinished_jobs, 3600);

        generate_thumbnails_for_single_image($image_id);

        if (!empty($unfinished_jobs)) {
            wp_schedule_single_event(time() + 10, 'generate_thumbnails_cron');
        } else {
            delete_transient('unfinished_thumbnails');
        }
    }

    delete_transient('thumbnails_cron_running');
});


add_action('admin_init', function() {
    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.Security.NonceVerification.Recommended
    if (isset($_GET['page']) && $_GET['page'] === 'dashboard-progress') {
        foreach (_get_cron_array() as $timestamp => $cron) {
            foreach ($cron as $hook => $events) {
                if (strpos($hook, 'generate_thumbnails_cron') !== false) {
                    wp_unschedule_event($timestamp, $hook);
                }
            }
        }

        delete_transient('unfinished_thumbnails');
        delete_transient('thumbnails_cron_running');

    }
    // phpcs:enable
});

add_action('admin_init', function() {
    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.Security.NonceVerification.Recommended
    if (isset($_GET['page']) && $_GET['page'] === 'dashboard-congratulations') {

        // A loopback POST to admin-ajax.php carries no auth cookie, so the
        // wp_ajax_ (logged-in only) handler never fires for it -- WordPress just
        // answers "0". The scheduled event below is what actually does the work,
        // so the request was pure waste and has been dropped.
        if (!wp_next_scheduled('generate_thumbnails_cron')) {
            wp_schedule_single_event(time(), 'generate_thumbnails_cron');
        }
    }
    // phpcs:enable
});


add_action('wp_ajax_run_generate_thumbnails_cron', function() {
    // Regenerating every thumbnail is expensive. Without these checks any
    // logged-in user, a subscriber included, could fire it in a loop.
    check_ajax_referer('solace_generate_thumbnails', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized.');
    }

    if (get_transient('thumbnails_cron_running')) {
        wp_send_json_error( 'Process already running.' );
        return;
    }

    set_transient('thumbnails_cron_running', true, 3600);

    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Plugin cron hook, name kept for compatibility
    do_action('generate_thumbnails_cron');

    wp_send_json_success( 'Thumbnail generation started in the background.' );
});

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'solace_custom_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'solace_custom_ajax_add_to_cart');

function solace_custom_ajax_add_to_cart() {
    // Verify nonce to prevent CSRF cart manipulation.
    check_ajax_referer( 'solace_add_to_cart', 'nonce' );
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(['error' => true, 'message' => 'Product ID is missing.']);
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $product_id = absint($_POST['product_id']);

    // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

    $added = WC()->cart->add_to_cart($product_id, $quantity);

    if ($added) {
        wp_send_json_success([
            'success' => true,
            'product_url' => wc_get_cart_url()
        ]);
    } else {
        wp_send_json_error([
            'error' => true,
            'product_url' => get_permalink($product_id),
            'message' => 'Failed to add product to cart.'
        ]);
    }

    wp_die();
}
add_action( 'admin_menu', 'solace_register_theme_submenu_dashboard_upgrade_support',999 );

function solace_register_theme_submenu_dashboard_upgrade_support() {
    if ( ! function_exists( 'solace_pro_card' ) ) {
        add_submenu_page(
            'solace',
            __( 'Upgrade', 'solace-extra' ),
            __( 'Upgrade', 'solace-extra' ),
            'manage_options',
            'solace-upgrade-link',
            '__return_null'
        );
    }

    $saved_key = get_option( 'solace_license_key', '' );
    $license_info = false;
    $license_valid = false;

    if ( ! empty( $saved_key ) && function_exists( 'solace_check_license' ) ) {
        $license_info = solace_check_license( $saved_key );

        if ( $license_info && isset( $license_info->license ) && $license_info->license === 'valid' ) {
            $license_valid = true;
        }
    }

    if ( $license_valid ) {
        add_submenu_page(
            'solace',
            __( 'Support', 'solace-extra' ),
            __( 'Support', 'solace-extra' ),
            'manage_options',
            'solace-support-link',
            '__return_null'
        );
    }
}

add_action('admin_footer', function() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('a[href="admin.php?page=solace-upgrade-link"]').closest('li').addClass('upgrade-menu-item');
            $('a[href="admin.php?page=solace-upgrade-link"]')
                .attr('href', '<?php echo esc_js( esc_url( SOLACE_UPGRADE_URL ) ); ?>')
                .attr('target','_blank');
        });
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('a[href="admin.php?page=solace-support-link"]').closest('li').addClass('support-menu-item');
            $('a[href="admin.php?page=solace-support-link"]')
                .attr('href', '<?php echo esc_js( esc_url( SOLACE_SUPPORT_URL ) ); ?>')
                .attr('target','_blank');
        });
    </script>
    <?php
});

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'solace_plugin_action_links' );

function solace_plugin_action_links( $links ) {
    $saved_key = get_option( 'solace_license_key' );

    $license_status = 'inactive';

    if ( ! empty( $saved_key ) && function_exists( 'solace_check_license' ) ) {
        $license_info = solace_check_license( $saved_key );

        if ( $license_info && isset( $license_info->license ) ) {
            $license_status = $license_info->license;
        }
    }

    if ( $license_status !== 'valid' ) {
        $upgrade_link = sprintf(
            '<a href="%s" target="_blank" 
                style="background:#ff8f00; color:#fff; font-weight:bold; padding:4px 8px; margin-right:5px;
                       border-radius:4px; text-decoration:none;">
                 %s
            </a>',
            esc_url( SOLACE_UPGRADE_URL ),
            esc_html__( 'Upgrade', 'solace-extra' )
        );

        $links[] = $upgrade_link;
    }

    return $links;
}