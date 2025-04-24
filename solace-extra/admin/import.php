<?php
defined( 'ABSPATH' ) || exit;

/**
 * Backend submenu Import
 *
 * @link       https://solacewp.com
 * @since      1.0.0
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/admin
 */

/**
 * The admin-specific functionality of the plugin (import).
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/import
 * @author     Solace <solacewp@gmail.com>
 */
require_once plugin_dir_path(__FILE__) . 'wp-background-processing.php';

class Solace_Import_Elementor_Process extends XWP_Background_Process {
    protected $action = 'import_posts_process';
    private $demo_name;

    protected function task($item) {
        if (isset($item['action']) && $item['action'] === 'demo_name' && isset($item['demo_name'])) {
            $this->import_elementor_kit($item['demo_name']);
        }
        return false;
    }

    protected function import_elementor_kit($demo_name) {
        // Set elementor settings
        $demo = 'https://solacewp.com/' . $demo_name . '/wp-json/solace/v1/elementor';        
        $this->demo_name = $demo_name;

        // Remote get data
        $response = wp_remote_get($demo);
        $datas = wp_remote_retrieve_body($response);
        $data = json_decode($datas);
        update_option( 'elementor_disable_color_schemes', 'yes' );
        update_option( 'elementor_disable_typography_schemes', 'yes' );
        update_option( 'elementor_experiment-nested-elements', sanitize_key( $data->nested_elements ) );        

        $zip_url = "https://solacewp.com/wp-content/uploads/demolist/" . $demo_name . "/" . $demo_name . ".zip";
        \Elementor\Plugin::$instance->uploads_manager->enable_unfiltered_files_upload();
        \Elementor\Plugin::$instance->uploads_manager->set_elementor_upload_state(true);
        $upload_dir = wp_upload_dir();
        $response = wp_remote_get($zip_url);

        $zip_data = wp_upload_bits(basename($zip_url), null, wp_remote_retrieve_body(wp_remote_get($zip_url)));
        if (!$zip_data['error']) {
            if (class_exists('Elementor\Plugin')) {
                $zip_file_path = $zip_data['file'];
                $zip = new ZipArchive();
                if ($zip->open($zip_file_path, ZipArchive::CREATE) === true) {

                    \Elementor\Plugin::$instance->uploads_manager->set_elementor_upload_state(true);
                    \Elementor\Plugin::$instance->uploads_manager->enable_unfiltered_files_upload();

                    add_filter('elementor/template_library/import_images/new_attachment', array($this, 'handle_images_import'));

                    add_action('wp_import_insert_post', array($this, 'handle_import_post'), 10, 4);

                    add_action('wp_import_set_post_terms', array($this, 'handle_import_post_terms'), 10, 5);

                    add_filter('elementor/document/save/data', array($this, 'handle_pages_import'), 10, 2);

                    \Elementor\Plugin::$instance->app->get_component('import-export')->import_kit($zip_file_path, array('include', 'selected_plugins', 'selected_cpt', 'selected_override_conditions'));

                    $zip->close();

                    // $this->set_latest_cart_page_as_default();
                    $this->set_latest_page_as_default('CART');
                    $this->set_latest_page_as_default('CHECKOUT');
                    $this->set_latest_page_as_default('MY ACCOUNT');

                } else {
                    esc_html_e('Failed opening file ZIP.', 'solace-extra');
                }
            } else {
                esc_html_e('Plugin Elementor Not Activated.', 'solace-extra');
            }
        } else {
            esc_html_e('Failed downloading file ZIP.', 'solace-extra');
        }

        if ( class_exists( 'Elementor\Plugin' ) ) {
            $elementor_active_kit = get_option( 'elementor_active_kit' );
            update_post_meta( $elementor_active_kit, 'solace_import_elementor_kit', true );
		} 
    }

    protected function complete() {
        parent::complete();
        update_option('solace_extra_import_zip_complete', true);
        set_theme_mod('solace_extra_import_zip_complete', true);
    }

    public function set_latest_page_as_default($page_title) {
        $pages = get_posts([
            'post_type'   => 'page',
            'post_status' => 'publish',
            'title'       => $page_title,
            'numberposts' => -1,
            'fields'      => 'ids', 
        ]);
    
        if (!empty($pages)) {
            $latest_page_id = max($pages);
    
            if ($page_title === 'CART') {
                update_option('woocommerce_cart_page_id', $latest_page_id);
            } elseif ($page_title === 'CHECKOUT') {
                update_option('woocommerce_checkout_page_id', $latest_page_id);
            } elseif ($page_title === 'MY ACCOUNT') {
                update_option('woocommerce_myaccount_page_id', $latest_page_id);
            }
        }
    }
    
    /**
     * Handles the import of pages.
     *
     * @param mixed        $data      The data being processed during import.
     * @param WP_Document  $document  The document being imported.
     *
     * @return mixed The modified or unmodified data after handling the pages import.
     */
    public function handle_pages_import($data, $document)
    {
        // Get the post object from the document
        $post_object = $document->get_post();

        // Ensure the post object is an instance of WP_Post
        if ($post_object instanceof WP_Post) {
            // Check if the post type is 'page'
            if ($post_object->post_type === 'page') {
                // Get the post ID
                $post_id = $post_object->ID;

                // Ensure the post ID is available before updating the meta
                if ($post_id) {
                    // Get demo_name.
                    $imported_demo_name = 'solace_extra_' . $this->demo_name;

                    // Update the 'solace_import_pages' meta to true to mark the page as imported
                    update_post_meta($post_id, 'solace_import_pages', true);
                    update_post_meta($post_id, $imported_demo_name, true);
                }
            }
        }

        // Return the unmodified data
        return $data;
    }

    /**
     * Handles the import of posts and updates the post meta 'solace_import_posts'.
     *
     * @param int $post_id The ID of the imported post.
     * @param int $original_post_id The ID of the original post.
     * @param object $postdata The post data.
     * @param object $post The post object.
     */
    public function handle_import_post($post_id, $original_post_id, $postdata, $post)
    {

        // Get demo_name.
        $imported_demo_name = 'solace_extra_' . $this->demo_name;

        update_post_meta($post_id, $imported_demo_name, true);

        update_post_meta($post_id, 'solace_import_posts', true);

        if ($post['post_type'] === 'product') {
            update_post_meta($post_id, 'solace_import_products', true);
        }
    }
    /**
     * Handles the import of images and updates the post meta 'solace_import_images'.
     *
     * @param int $post_id The ID of the post associated with the imported image.
     */
    public function handle_images_import($post_id)
    {
        // Update the post meta 'solace_import_images' with the value 'true'
        update_post_meta($post_id, 'solace_import_images', true);
        return $post_id;
    }

    /**
     * Handle the import of terms for a post.
     *
     * This function processes the imported terms for a post, updating term metadata
     * for categories and post tags with the 'solace_imported_term' flag.
     *
     * @param array    $tt_ids   Term taxonomy IDs.
     * @param array    $ids      Term IDs.
     * @param string   $tax      Taxonomy name.
     * @param int      $post_id  Post ID.
     * @param WP_Post  $post     The post object.
     *
     * @return void
     */
    public function handle_import_post_terms($tt_ids, $ids, $tax, $post_id, $post)
    {

        foreach ($post['terms'] as $data) {
            if ($data['domain'] === 'category') {

                $category = get_term_by('slug', $data['slug'], 'category');
                if ($category) {
                    if ($data['slug'] !== 'uncategorized') {
                        $category_id = $category->term_id;
                        update_term_meta($category_id, 'solace_imported_term_category', true);
                    }
                }
            }

            if ($data['domain'] === 'post_tag') {
                $tag = get_term_by('slug', $data['slug'], 'post_tag');
                if ($tag) {
                    $tag_id = $tag->term_id;
                    update_term_meta($tag_id, 'solace_imported_term_post_tag', true);
                }
            }

            // Products cat
            if ($data['domain'] === 'product_cat') {
                $product_cat = get_term_by('slug', $data['slug'], 'product_cat');
                if ($product_cat) {
                    if ($data['slug'] !== 'uncategorized') {
                        $product_cat_id = $product_cat->term_id;
                        update_term_meta($product_cat_id, 'solace_imported_term_product_cat', true);
                    }
                }
            }

            // Product tag
            if ($data['domain'] === 'product_tag') {
                $tag = get_term_by('slug', $data['slug'], 'product_tag');
                if ($tag) {
                    $tag_id = $tag->term_id;
                    update_term_meta($tag_id, 'solace_imported_term_product_tag', true);
                }
            }
        }
    }
}


class Thumbnail_Generator extends XWP_Background_Process {

    protected $action = 'generate_thumbnails';

    /**
     * Proses setiap item
     *
     * @param mixed $item Data attachment ID.
     * @return mixed
     */
    protected function task($item) {
        if (!wp_attachment_is_image($item)) {
            return false;
        }

        $file_path = get_attached_file($item);

        if (!$file_path || !file_exists($file_path)) {
            return false; 
        }

        $sizes = get_intermediate_image_sizes();

        $metadata = wp_generate_attachment_metadata($item, $file_path);

        foreach ($sizes as $size) {
            if (!isset($metadata['sizes'][$size])) {
                $editor = wp_get_image_editor($file_path);
                if (!is_wp_error($editor)) {
                    $editor->resize(
                        get_option("{$size}_size_w"),
                        get_option("{$size}_size_h"),
                        apply_filters("{$size}_crop", false) 
                    );
                    $editor->save();
                }
            }
        }

        wp_update_attachment_metadata($item, $metadata);

        return false; 
    }

    /**
     * Selesai proses
     *
     * @return void
     */
    protected function complete() {
        parent::complete();
    }

}


class Solace_Extra_Import {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
    private $import_process; 

    /**
     * The demo_name of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $demo_name    The current demo_name of this plugin.
     */
    private $demo_name;

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
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->import_process = new Solace_Import_Elementor_Process();

    }

    /**
     * Checks to see whether a string is an image url or not.
     *
     * @since 0.1
     * @access private
     * @param string $string The string to check.
     * @return bool Whether the string is an image url or not.
     */
    static private function _is_image_url($string = '')
    {
        if (is_string($string)) {

            if (preg_match('/\.(jpg|jpeg|png|gif)/i', $string)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Solace Ajax Import
     */
    public function call_ajax_import_customizer()
    {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
            $response = array('error' => 'Invalid nonce!');
            echo wp_json_encode($response);
            // wp_die();
        }

        // Check current user
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'error' => 'Unauthorized' ) );
        }

        // Get menus data from the API
        $get_demo_name = ! empty( $_POST['getDemo'] ) ? sanitize_title( wp_unslash( $_POST['getDemo'] ) ) : '';

        $demo = 'https://solacewp.com/' . $get_demo_name . "/wp-json/solace/v1/customizer-setting";        

        // Remote and local API URLs
        $url_customizer = $demo;
        $url_local_customizer = plugin_dir_url(__FILE__) . 'demo/customizer/casanova/casanova.json';

        // Make remote request using wp_remote_get
        $response_customizer = wp_remote_get($url_customizer);

        // Check for errors
        if (is_wp_error($response_customizer)) {
            echo esc_html('Error: ' . $response_customizer->get_error_message());
            wp_die();
        }

        // Get the HTTP response code
        $http_code_customizer = wp_remote_retrieve_response_code($response_customizer);

        // Check for errors based on HTTP response code
        if ($http_code_customizer >= 400) {
            // The response code is 400 or greater, switch to the backup URL
            $response_customizer = wp_remote_get($url_local_customizer);
        }

        // Decode the response body
        $data = json_decode(wp_remote_retrieve_body($response_customizer), true);

        // Data checks.
        if (!is_array($data)) {
            esc_html_e('An error occurred while importing the customizer! The imported data is not valid.', 'solace-extra');
            wp_die();
        }

        // If wp_css is set then import it.
        if (function_exists('wp_update_custom_css_post') && isset($data['wp_css']) && '' !== $data['wp_css']) {
            wp_update_custom_css_post($data['wp_css']);
        }

        // Loop through the mods.
        foreach ($data as $key => $val) {
            if ($key === 'solace_menu_locations') {
                continue;
            }

            // Save the mod.
            set_theme_mod($key, $val);
        }

        // Set menu locations
        if (isset($data['solace_menu_locations'])) {
            foreach ($data['solace_menu_locations'] as $menuLocation) {
                if ($menuLocation['location'] && $menuLocation['menu_slug']) {
                    $get_slug_menu = $menuLocation['menu_slug'];
                    $menu = wp_get_nav_menu_object($get_slug_menu);
                    if ($menu && $menu->slug == $get_slug_menu) {
                        $locations = get_nav_menu_locations();
                        $locations[$menuLocation['location']] = $menu->term_id;
                        set_theme_mod('nav_menu_locations', $locations);
                    }
                }
            }
        }

        $api_url = 'https://solacewp.com/' . $get_demo_name . '/wp-json/solace/v1/customizer-setting?timestamp=' . time();
        $response = wp_remote_get($api_url);

        if (!is_wp_error($response)) {
            $data = json_decode(wp_remote_retrieve_body($response), true);
            if ($data) {
                $remote_logo_header_url = $data['logourl'];
                $this->download_and_update_logo_logo_from_live_url($remote_logo_header_url);

                $remote_logo_footer_url = $data['logofooterurl'];
                $this->download_and_update_logo_footer_logo_from_live_url($remote_logo_footer_url);
            }
        }

        // Remote and local API URLs
        $url_setting_options = 'https://solacewp.com/' . $get_demo_name . 'wp-json/solace/v1/setting-options';

        // Make remote request using wp_remote_get
        $response_setting_options = wp_remote_get($url_setting_options);

        // Check for errors
        if ( is_wp_error( $response_setting_options ) ) {
            echo esc_html( 'Error setting options: ' . $response_setting_options->get_error_message() );
            wp_die();
        }

        // Decode the response body
        $data_setting_options = json_decode( wp_remote_retrieve_body( $response_setting_options ), true );

        // Data checks.
        if ( ! is_array( $data_setting_options ) ) {
            esc_html_e( 'An error occurred while importing the setting options!', 'solace-extra' );
            wp_die();
        }

        // Fix products sale shortcode.
        if ( class_exists( 'WooCommerce' ) ) {
            global $wpdb;

            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here.        
            $post_ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='solace_import_posts'");

            // looping products
            foreach ( $post_ids as $id ) {
                $product = wc_get_product( $id );
                if ( $product && !is_wp_error( $product ) ) {

                    $sale_price = $product->get_sale_price();

                    if ( $sale_price ) {
                        $new_regular_price = $product->get_regular_price();
                        $product->set_regular_price( $new_regular_price + 1 );

                        $product->save();

                        $product->set_regular_price( $new_regular_price - 1 );

                        $product->save();
                    }
                }
            }
        }

        // Ensure the 'plugin.php' file is loaded to use WordPress plugin functions.
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        // Define the plugin path for LiteSpeed Cache.
        $plugin = 'litespeed-cache/litespeed-cache.php';
        $status_plugin_litespeed = get_option( 'solace_extra_deactivate_litespeed', false );

        // Check status plugin litespeed.
        if ( $status_plugin_litespeed ) {
            // Check if the plugin is already active.
            if ( ! is_plugin_active( $plugin ) ) {
                // Attempt to activate the plugin.
                $result = activate_plugin( $plugin );

                // Check if the activation was successful or if an error occurred.
                if ( is_wp_error( $result ) ) {
                    // Output the error message if activation failed.
                    // esc_html_e( 'Failed to activate the plugin: ', 'solace-extra' ) . $result->get_error_message();
                } else {
                    // Output a success message if activation was successful.
                    // esc_html_e( 'LiteSpeed Cache plugin has been successfully activated.', 'solace-extra' );
                }
            } else {
                // Output a message if the plugin is already active.
                // esc_html_e( 'LiteSpeed Cache plugin is already active.', 'solace-extra' );
            }
        }

        esc_html_e('Success! Import Customizer...', 'solace-extra');

        wp_die();
    }

    public function download_and_update_logo_logo_from_live_url($remote_url) {
        $remote_url = esc_url_raw($remote_url);
    
        if (!filter_var($remote_url, FILTER_VALIDATE_URL)) {
            esc_html_e('Invalid URL.', 'solace-extra');
            return;
        }
    
        $allowed_domains = ['solacewp.com', 'www.solacewp.com'];
        $parsed_url = wp_parse_url($remote_url);
        if (!in_array($parsed_url['host'], $allowed_domains, true)) {
            esc_html_e('Domain not allowed.', 'solace-extra');
            return;
        }
    
        $response = wp_remote_get($remote_url, ['timeout' => 10]);
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            esc_html_e('Failed to download file.', 'solace-extra');
            return;
        }
    
        $file_content = wp_remote_retrieve_body($response);
        $upload_dir = wp_upload_dir();
        $file_name = sanitize_file_name(basename($remote_url));
        $file_path = $upload_dir['path'] . '/' . $file_name;
    
        if (!function_exists('wp_filesystem')) {
            esc_html_e('Failed to initialize WP_Filesystem.', 'solace-extra');
            return;
        }
    
        global $wp_filesystem;
        WP_Filesystem();
        $file_saved = $wp_filesystem->put_contents($file_path, $file_content, FS_CHMOD_FILE);
    
        if ($file_saved === false) {
            esc_html_e('Failed to save file.', 'solace-extra');
            return;
        }
    
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = wp_check_filetype($file_name);
        if (!in_array($file_type['type'], $allowed_types, true)) {
            esc_html_e('File type not allowed.', 'solace-extra');
            wp_delete_file($file_path); 
            return;
        }
    
        $attachment = [
            'post_title'     => pathinfo($file_name, PATHINFO_FILENAME),
            'post_mime_type' => $file_type['type'],
            'post_status'    => 'inherit',
        ];
    
        $attachment_id = wp_insert_attachment($attachment, $file_path);
        if (!$attachment_id) {
            wp_delete_file($file_path);
            esc_html_e('Failed to save attachment.', 'solace-extra');
            return;
        }
    
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
    
        set_theme_mod('logo_logo', json_encode(['light' => $attachment_id, 'dark' => $attachment_id, 'same' => true]));
    
        echo esc_html__('Media successfully downloaded and saved.', 'solace-extra');
    }
    

    public function download_and_update_logo_footer_logo_from_live_url($remote_url) {
        $remote_url = esc_url_raw($remote_url);
    
        if (!filter_var($remote_url, FILTER_VALIDATE_URL)) {
            esc_html_e('Invalid URL.', 'solace-extra');
            return;
        }
    
        $allowed_domains = ['solacewp.com', 'www.solacewp.com']; 
        $parsed_url = wp_parse_url($remote_url);
        if (!in_array($parsed_url['host'], $allowed_domains, true)) {
            esc_html_e('Domain not allowed.', 'solace-extra');
            return;
        }
    
        $response = wp_remote_get($remote_url, ['timeout' => 10]);
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            esc_html_e('Failed to download file.', 'solace-extra');
            return;
        }
    
        $file_content = wp_remote_retrieve_body($response);
        $upload_dir = wp_upload_dir();
        $file_name = sanitize_file_name(basename($remote_url));
        $file_path = $upload_dir['path'] . '/' . $file_name;
    
        if (!function_exists('wp_filesystem')) {
            esc_html_e('Failed to initialize WP_Filesystem.', 'solace-extra');
            return;
        }
    
        global $wp_filesystem;
        WP_Filesystem();
        $file_saved = $wp_filesystem->put_contents($file_path, $file_content, FS_CHMOD_FILE);
    
        if ($file_saved === false) {
            esc_html_e('Failed to save file.', 'solace-extra');
            return;
        }
    
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = wp_check_filetype($file_name);
        if (!in_array($file_type['type'], $allowed_types, true)) {
            esc_html_e('File type not allowed.', 'solace-extra');
            wp_delete_file($file_path);
            return;
        }
    
        $attachment = [
            'post_title'     => pathinfo($file_name, PATHINFO_FILENAME),
            'post_mime_type' => $file_type['type'],
            'post_status'    => 'inherit',
        ];
    
        $attachment_id = wp_insert_attachment($attachment, $file_path);
        if (!$attachment_id) {
            wp_delete_file($file_path);
            esc_html_e('Failed to save attachment.', 'solace-extra');
            return;
        }
    
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
    
        $theme_mods = get_theme_mod('theme_mods_solace');
        $logo_data = isset($theme_mods['logo-footer_logo']) && is_string($theme_mods['logo-footer_logo']) 
            ? json_decode($theme_mods['logo-footer_logo'], true) 
            : [];
    
        $logo_logo_data = json_encode(['light' => $attachment_id, 'dark' => $attachment_id, 'same' => true]);
    
        if ($attachment_id) {
            update_post_meta($attachment_id, 'solace_import_images', true);
        }
    
        set_theme_mod('logo-footer_logo', $logo_logo_data);
    
        echo esc_html__('Media successfully downloaded and saved.', 'solace-extra');
    }
    
    

    /**
     * Available widgets
     *
     * Gather site's widgets into array with ID base, name, etc.
     * Used by export and import functions.
     *
     * @since 0.4
     * @global array $wp_registered_widget_updates
     * @return array Widget information
     */
    public function available_widgets()
    {
        global $wp_registered_widget_controls;

        $widget_controls = $wp_registered_widget_controls;

        $available_widgets = array();

        foreach ($widget_controls as $widget) {
            // No duplicates.
            if (!empty($widget['id_base']) && !isset($available_widgets[$widget['id_base']])) {
                $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
                $available_widgets[$widget['id_base']]['name']    = $widget['name'];
            }
        }

        return apply_filters('available_widgets', $available_widgets);
    }

    /**
     * Solace Ajax Import Widget
     */
    public function call_ajax_import_widget()
    {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
            $response = array('error' => 'Invalid nonce!');
            echo wp_json_encode($response);
            wp_die();
        }        

        // Check current user
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'error' => 'Unauthorized' ) );
        }

        // Get menus data from the API
        $get_demo_name = ! empty( $_POST['getDemo'] ) ? sanitize_title( wp_unslash( $_POST['getDemo'] ) ) : '';

        $demo = 'https://solacewp.com/' . $get_demo_name . "/wp-json/solace/v1/widgets";          

        // Remote API URL
        $url_widget = $demo;

        // Make remote request using wp_remote_get
        $response_widget = wp_remote_get($url_widget);

        // Check for errors
        if (is_wp_error($response_widget)) {
            echo esc_html( 'Error: ' . $response_widget->get_error_message() );
            wp_die();
        }

        // Get the HTTP response code
        $http_code_widget = wp_remote_retrieve_response_code($response_widget);

        // Check for errors based on HTTP response code
        if ($http_code_widget >= 400) {
            // The response code is 400 or greater, handle accordingly
            // For example, switch to a backup URL
            // $url_local_widget = plugin_dir_url(__FILE__) . 'demo/customizer/casanova/casanova.json';
            // $response_widget = wp_remote_get($url_local_widget);
        }

        // Decode the response body
        $data = json_decode(wp_remote_retrieve_body($response_widget), true);
        $data = (object) $data;

        // Filter Widget menu
        foreach ($data as $widget_area => $widgets) {
            foreach ($widgets as $widget_id => $item) {
                // Check if the 'nav_menu_slug' index exists in the $item array
                if (isset($item['nav_menu_slug'])) {
                    // Get the menu slug from the data
                    $menu_slug = $item['nav_menu_slug'];

                    // Get the menu object based on the menu slug
                    $menu = get_term_by('slug', $menu_slug, 'nav_menu');

                    // Check if the menu is found before updating the values
                    if ($menu) {
                        // Update 'nav_menu' and 'nav_menu_id' with the term_id of the menu
                        $data->$widget_area[$widget_id]['nav_menu'] = $menu->term_id;
                        $data->$widget_area[$widget_id]['nav_menu_id'] = $menu->term_id;
                    }
                }
            }
        }

        $this->import_widgets($data);

        esc_html_e('Success! Import Widgets...', 'solace-extra');

        wp_die();
    }

    /**
     * Import widget JSON data
     *
     * @since 0.4
     * @global array $wp_registered_sidebars
     * @param object $data JSON widget data from .wie file.
     * @return array Results array
     */
    function import_widgets($data)
    {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
            $response = array('error' => 'Invalid nonce!');
			echo wp_json_encode($response);
            wp_die();
        }        

        global $wp_registered_sidebars;

        // Have valid data?
        // If no data or could not decode.
        if (empty($data) || !is_object($data)) {
            wp_die(
                esc_html__('Import data is invalid.', 'solace-extra'),
                '',
                array(
                    'back_link' => true,
                )
            );
        }

        // Hook before import.
        do_action('before_import');
        $data = apply_filters('import_widgets', $data);

        // Get all available widgets site supports.
        $available_widgets = $this->available_widgets();

        // Get all existing widget instances.
        $widget_instances = array();
        foreach ($available_widgets as $widget_data) {
            $widget_instances[$widget_data['id_base']] = get_option('widget_' . $widget_data['id_base']);
        }

        // Begin results.
        $results = array();

        // Loop import data's sidebars.
        foreach ($data as $sidebar_id => $widgets) {
            // Skip inactive widgets (should not be in export file).
            if ('wp_inactive_widgets' === $sidebar_id) {
                continue;
            }

            // Check if sidebar is available on this site.
            // Otherwise add widgets to inactive, and say so.
            if (isset($wp_registered_sidebars[$sidebar_id])) {
                $sidebar_available    = true;
                $use_sidebar_id       = $sidebar_id;
                $sidebar_message_type = 'success';
                $sidebar_message      = '';
            } else {
                $sidebar_available    = false;
                $use_sidebar_id       = 'wp_inactive_widgets'; // Add to inactive if sidebar does not exist in theme.
                $sidebar_message_type = 'error';
                $sidebar_message      = esc_html__('Widget area does not exist in theme (using Inactive)', 'solace-extra');
            }

            // Result for sidebar
            // Sidebar name if theme supports it; otherwise ID.
            $results[$sidebar_id]['name']         = !empty($wp_registered_sidebars[$sidebar_id]['name']) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id;
            $results[$sidebar_id]['message_type'] = $sidebar_message_type;
            $results[$sidebar_id]['message']      = $sidebar_message;
            $results[$sidebar_id]['widgets']      = array();

            // Loop widgets.
            foreach ($widgets as $widget_instance_id => $widget) {
                $fail = false;

                // Get id_base (remove -# from end) and instance ID number.
                $id_base            = preg_replace('/-[0-9]+$/', '', $widget_instance_id);
                $instance_id_number = str_replace($id_base . '-', '', $widget_instance_id);

                // Does site support this widget?
                if (!$fail && !isset($available_widgets[$id_base])) {
                    $fail                = true;
                    $widget_message_type = 'error';
                    $widget_message      = esc_html__('Site does not support widget', 'solace-extra'); // Explain why widget not imported.
                }

                // Filter to modify settings object before conversion to array and import
                // Leave this filter here for backwards compatibility with manipulating objects (before conversion to array below)
                // Ideally the newer wie_widget_settings_array below will be used instead of this.
                $widget = apply_filters('wie_widget_settings', $widget);

                // Convert multidimensional objects to multidimensional arrays
                // Some plugins like Jetpack Widget Visibility store settings as multidimensional arrays
                // Without this, they are imported as objects and cause fatal error on Widgets page
                // If this creates problems for plugins that do actually intend settings in objects then may need to consider other approach: https://wordpress.org/support/topic/problem-with-array-of-arrays
                // It is probably much more likely that arrays are used than objects, however.
                $widget = json_decode(wp_json_encode($widget), true);

                // Filter to modify settings array
                // This is preferred over the older wie_widget_settings filter above
                // Do before identical check because changes may make it identical to end result (such as URL replacements).
                $widget = apply_filters('wie_widget_settings_array', $widget);

                // Does widget with identical settings already exist in same sidebar?
                if (!$fail && isset($widget_instances[$id_base])) {
                    // Get existing widgets in this sidebar.
                    $sidebars_widgets = get_option('sidebars_widgets');
                    $sidebar_widgets  = isset($sidebars_widgets[$use_sidebar_id]) ? $sidebars_widgets[$use_sidebar_id] : array(); // Check Inactive if that's where will go.

                    // Loop widgets with ID base.
                    $single_widget_instances = !empty($widget_instances[$id_base]) ? $widget_instances[$id_base] : array();
                    foreach ($single_widget_instances as $check_id => $check_widget) {
                        // Is widget in same sidebar and has identical settings?
                        if (in_array("$id_base-$check_id", $sidebar_widgets, true) && (array) $widget === $check_widget) {
                            $fail                = true;
                            $widget_message_type = 'warning';

                            // Explain why widget not imported.
                            $widget_message = esc_html__('Widget already exists', 'solace-extra');

                            break;
                        }
                    }
                }

                // No failure.
                if (!$fail) {
                    // Add widget instance
                    $single_widget_instances   = get_option('widget_' . $id_base); // All instances for that widget ID base, get fresh every time.
                    $single_widget_instances   = !empty($single_widget_instances) ? $single_widget_instances : array(
                        '_multiwidget' => 1,   // Start fresh if have to.
                    );
                    $single_widget_instances[] = $widget; // Add it.

                    // Get the key it was given.
                    end($single_widget_instances);
                    $new_instance_id_number = key($single_widget_instances);

                    // If key is 0, make it 1
                    // When 0, an issue can occur where adding a widget causes data from other widget to load,
                    // and the widget doesn't stick (reload wipes it).
                    if ('0' === strval($new_instance_id_number)) {
                        $new_instance_id_number = 1;
                        $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                        unset($single_widget_instances[0]);
                    }

                    // Move _multiwidget to end of array for uniformity.
                    if (isset($single_widget_instances['_multiwidget'])) {
                        $multiwidget = $single_widget_instances['_multiwidget'];
                        unset($single_widget_instances['_multiwidget']);
                        $single_widget_instances['_multiwidget'] = $multiwidget;
                    }

                    // Update option with new widget.
                    update_option('widget_' . $id_base, $single_widget_instances);

                    // Assign widget instance to sidebar.
                    // Which sidebars have which widgets, get fresh every time.
                    $sidebars_widgets = get_option('sidebars_widgets');

                    // Avoid rarely fatal error when the option is an empty string
                    // https://github.com/churchthemes/widget-importer-exporter/pull/11.
                    if (!$sidebars_widgets) {
                        $sidebars_widgets = array();
                    }

                    // Use ID number from new widget instance.
                    $new_instance_id = $id_base . '-' . $new_instance_id_number;

                    // Add new instance to sidebar.
                    $sidebars_widgets[$use_sidebar_id][] = $new_instance_id;

                    // Save the amended data.
                    update_option('sidebars_widgets', $sidebars_widgets);

                    // After widget import action.
                    $after_widget_import = array(
                        'sidebar'           => $use_sidebar_id,
                        'sidebar_old'       => $sidebar_id,
                        'widget'            => $widget,
                        'widget_type'       => $id_base,
                        'widget_id'         => $new_instance_id,
                        'widget_id_old'     => $widget_instance_id,
                        'widget_id_num'     => $new_instance_id_number,
                        'widget_id_num_old' => $instance_id_number,
                    );
                    do_action('after_widget_import', $after_widget_import);

                    // Success message.
                    if ($sidebar_available) {
                        $widget_message_type = 'success';
                        $widget_message      = esc_html__('Imported', 'solace-extra');
                    } else {
                        $widget_message_type = 'warning';
                        $widget_message      = esc_html__('Imported to Inactive', 'solace-extra');
                    }
                }

                // Result for widget instance
                $results[$sidebar_id]['widgets'][$widget_instance_id]['name']         = isset($available_widgets[$id_base]['name']) ? $available_widgets[$id_base]['name'] : $id_base;      // Widget name or ID if name not available (not supported by site).
                $results[$sidebar_id]['widgets'][$widget_instance_id]['title']        = !empty($widget['title']) ? $widget['title'] : esc_html__('No Title', 'solace-extra');  // Show "No Title" if widget instance is untitled.
                $results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
                $results[$sidebar_id]['widgets'][$widget_instance_id]['message']      = $widget_message;
            }
        }

        // Hook after import.
        do_action('after_import');
    }

    /**
     * Deletes attachments imported during the Solace import process.
     */
    public function delete_sidebars_widgets()
    {
        update_option('sidebars_widgets', array());
    }

    /**
     * Deletes customizers imported during the Solace import process.
     */
    public function delete_customizers()
    {
        // Remove all them mods
        remove_theme_mods();
    }    

    /**
     * Deletes attachments imported during the Solace import process.
     */
    public function delete_imported_attachments()
    {
        global $wpdb;

        $meta_key = 'solace_import_images';

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here. 
        $post_ids = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
				 FROM {$wpdb->prefix}postmeta
				 WHERE CONVERT(meta_key USING utf8) LIKE %s",
                '%' . $wpdb->esc_like($meta_key) . '%'
            )
        );

        // Remove attachment
        if (!empty($post_ids)) {
            foreach ($post_ids as $id) {
                wp_delete_attachment($id->post_id, true);
            }
        }
    }

    /**
     * Deletes imported terms from the 'category' taxonomy based on a specific term meta key.
     */
    public function delete_imported_terms_category()
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here.
        $term_ids = $wpdb->get_col("SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key='solace_imported_term_category'");

        foreach ($term_ids as $id) {
            wp_delete_term($id, 'category');
        }
    }

    /**
     * Deletes imported terms from the 'product_cat' taxonomy based on a specific term meta key.
     */
    public function delete_imported_terms_product_cat()
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here.
        $term_ids = $wpdb->get_col("SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key='solace_imported_term_product_cat'");

        foreach ($term_ids as $id) {
            wp_delete_term($id, 'product_cat');
        }
    }

    /**
     * Deletes imported terms from the 'product_tag' taxonomy based on a specific term meta key.
     */
    public function delete_imported_terms_product_tag()
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here.
        $term_ids = $wpdb->get_col("SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key='solace_imported_term_product_tag'");

        foreach ($term_ids as $id) {
            wp_delete_term($id, 'product_tag');
        }
    }

    /**
     * Deletes imported terms from the 'post_tag' taxonomy based on a specific term meta key.
     */
    public function delete_imported_terms_post_tag()
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here.        
        $term_ids = $wpdb->get_col("SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key='solace_imported_term_post_tag'");

        foreach ($term_ids as $id) {
            wp_delete_term($id, 'post_tag');
        }
    }

    /**
     * Deletes posts imported during the Solace import process.
     */
    public function delete_imported_posts()
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here.        
        $post_ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='solace_import_posts'");

        // Remove posts
        foreach ($post_ids as $id) {
            $thumbnail_id = get_post_thumbnail_id($id);
            wp_delete_attachment($thumbnail_id, true);
            wp_delete_post($id, true);
        }
    }

    /**
     * Deletes menu items imported during the Solace import process.
     */
    public function delete_imported_menu_items()
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here.        
        $post_ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='solace_import_menu_item'");

        // Remove menu items.
        foreach ($post_ids as $id) {
            wp_delete_post($id, true);
        }
    }    

    /**
     * Deletes products imported during the Solace import process.
     */
    public function delete_imported_products()
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here.        
        $post_ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='solace_import_products'");

        // Remove products
        foreach ($post_ids as $id) {
            $thumbnail_id = get_post_thumbnail_id($id);
            wp_delete_attachment($thumbnail_id, true);
            wp_delete_post($id, true);
        }
    }

    /**
     * Deletes pages imported during the Solace import process.
     */
    public function delete_imported_pages()
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here.        
        $post_ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='solace_import_pages'");

        // Remove pages
        foreach ($post_ids as $id) {
            $thumbnail_id = get_post_thumbnail_id($id);
            wp_delete_attachment($thumbnail_id, true);
            wp_delete_post($id, true);
        }
    }

    /**
     * Deletes all Elementor templates.
     *
     * This function retrieves all Elementor template IDs from the WordPress database
     * and deletes each template using the wp_delete_post function.
     *
     * @return void
     */    
    function delete_all_elementor_templates() {
        global $wpdb;

        remove_all_actions( 'wp_trash_post' );
        remove_all_actions( 'before_delete_post' );

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all elementor templates.
        $template_ids = $wpdb->get_col("
            SELECT {$wpdb->posts}.ID 
            FROM {$wpdb->posts}
            INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
            WHERE {$wpdb->posts}.post_type = 'elementor_library' 
            AND {$wpdb->postmeta}.meta_key = 'solace_import_elementor_kit'
        ");
        // $template_ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='solace_import_elementor_kit'");

        // Loop through each template ID and delete the template.
        foreach ( $template_ids as $id ) {
            wp_delete_post( $id, true );
        }
    }     

    /**
     * Deletes previously imported attachments and posts as part of the cleanup process.
     */
    public function delete_previously_imported()
    {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
            $response = array('error' => 'Invalid nonce!');
			echo wp_json_encode($response);
            wp_die();
        }        

        $this->delete_imported_terms_category();
        $this->delete_imported_terms_post_tag();
        $this->delete_imported_attachments();
        $this->delete_imported_posts();
        $this->delete_imported_pages();

        $this->delete_imported_terms_product_cat();
        $this->delete_imported_terms_product_tag();
        $this->delete_imported_products();

        $this->delete_elementor_current_setting();


        wp_die();
    }

    function delete_elementor_current_setting(){
        $custom_typography = [
			[
				'_id' => 'primary',
				'title' => 'Smaller',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'secondary',
				'title' => 'Logo Title / Subtitle',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'text',
				'title' => 'Solace Base',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'accent',
				'title' => 'Button',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'solace_body_font_family',
				'title' => 'Solace Base',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'solace_h1_font_family_general',
				'title' => 'Solace H1',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'solace_h2_font_family_general',
				'title' => 'Solace H2',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'solace_h3_font_family_general',
				'title' => 'Solace H3',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'solace_h4_font_family_general',
				'title' => 'Solace H4',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'solace_h5_font_family_general',
				'title' => 'Solace H5',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => 'px',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'solace_h6_font_family_general',
				'title' => 'Solace H6',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'solace_smaller_font_family',
				'title' => 'Smaller',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'solace_logotitle_font_family',
				'title' => 'Logo Title / Subtitle',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			],
			[
				'_id' => 'solace_button_font_family',
				'title' => 'Button',
				'typography_typography' => 'custom',
				'typography_font_family' => '',
				'typography_font_size' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_weight' => '',
				'typography_text_transform' => '',
				'typography_line_height' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_font_size_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_line_height_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_tablet' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				],
				'typography_letter_spacing_mobile' => [
					'unit' => '',
					'size' => '',
					'sizes' => []
				]
			]
		];

		if (class_exists('Elementor\Plugin')) {
            $elementor_active_kit = get_option( 'elementor_active_kit' );

            // Retrieve the Elementor page settings meta data.
            $meta = get_post_meta( $elementor_active_kit, '_elementor_page_settings', true );
            if ( $meta ) {
                \Elementor\Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'system_typography', $custom_typography );		
            }
		}
    }

    function install_and_activate_theme() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
            $response = array('error' => 'Invalid nonce!');
            echo wp_json_encode($response);
            wp_die();
        }           

        solace_disable_image_processing();

        // Set import zip complete option to false
        update_option('solace_extra_import_zip_complete', false);
        set_theme_mod('solace_extra_import_zip_complete', false);

        $theme_slug = 'solace';

        if (!(get_option('template') === $theme_slug || get_option('stylesheet') === $theme_slug)) {

            if (!(file_exists(trailingslashit(WP_CONTENT_DIR) . 'themes/' . $theme_slug))) {
                $api = themes_api('theme_information', array('slug' => $theme_slug));

                if (is_wp_error($api)) {
                    esc_html_e('Failed to retrieve theme information from WordPress.org.', 'solace-extra');
                    return;
                }

                $theme_zip = download_url($api->download_link);

                if (!is_wp_error($theme_zip)) {
                    $theme_dir = trailingslashit(WP_CONTENT_DIR) . 'themes';
                    $zip = new ZipArchive;

                    if ($zip->open($theme_zip) === true) {
                        $zip->extractTo($theme_dir);
                        $zip->close();
                        wp_delete_file($theme_zip);
                    } else {
                        esc_html_e('Failed to open ZIP file.', 'solace-extra');
                    }
                }
            }

            switch_theme($theme_slug);

            if (!(get_option('template') === $theme_slug || get_option('stylesheet') === $theme_slug)) {
                esc_html_e('Theme Installed and Activated', 'solace-extra');
            } else {
                esc_html_e('Failed to activate theme.', 'solace-extra');
            }
        } else {
            esc_html_e('Theme is already installed and active.', 'solace-extra');
        }
    }


    function install_and_activate_plugins()
    {
        // Verify user permissions and nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
            wp_send_json_error( [ 'error' => 'Invalid nonce!' ] );
        }
        if ( ! current_user_can( 'install_plugins' ) ) {
            wp_send_json_error( [ 'error' => 'Unauthorized action!' ] );
        }

        update_option( 'elementor_onboarded', true );

        $demo_name = ! empty( $_POST['getDemo'] ) ? sanitize_text_field( wp_unslash( $_POST['getDemo'] ) ) : '';

		// Remote and local API URLs
		$url = trailingslashit('https://solacewp.com/' . $demo_name) . 'wp-json/solace/v1/required-plugin';

        // Make remote request using wp_remote_get
        $response = wp_remote_get($url);
        if ( is_wp_error( $response ) ) {
            wp_send_json_error( [ 'error' => 'Failed to fetch plugins data.' ] );
        }

		$decoded_data = json_decode( wp_remote_retrieve_body( $response ), true );
        $plugins_to_install = null;
        if ( $decoded_data['page_builder'] && $decoded_data['ecommerce'] ) {
            $plugins_to_install = array(
                'elementor',
                'woocommerce',
            );
        } else if ( $decoded_data['page_builder'] && ! $decoded_data['ecommerce'] ) {
            $plugins_to_install = array(
                'elementor',
            );
        } else {
            $plugins_to_install = array(
                'elementor',
            );
        }

        if ( ! function_exists( 'plugins_api' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }
        if ( ! class_exists( 'WP_Upgrader' ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }

        foreach ( $plugins_to_install as $plugin_slug ) {
            if ( ! is_plugin_active( "$plugin_slug/$plugin_slug.php" ) && ! file_exists( WP_PLUGIN_DIR . "/$plugin_slug/$plugin_slug.php" ) ) {
                $api = plugins_api( 'plugin_information', [ 'slug' => $plugin_slug ] );
                if ( isset( $api->download_link ) ) {
                    $plugin_zip = download_url( $api->download_link );
                    if ( ! is_wp_error( $plugin_zip ) ) {
                        $zip = new ZipArchive;
                        if ( $zip->open( $plugin_zip ) === true ) {
                            $zip->extractTo( WP_PLUGIN_DIR );
                            $zip->close();
                            wp_delete_file( $plugin_zip );
                        }
                    }
                }
            }
        }

        foreach ( $plugins_to_install as $plugin_slug ) {
            activate_plugin( "$plugin_slug/$plugin_slug.php" );
        }

        update_option( 'permalink_structure', '/%postname%/' );
        flush_rewrite_rules();          

        $url = admin_url( 'admin.php?page=elementor-app#onboarding' );
        
        $response = wp_remote_get( $url );

        if ( is_wp_error( $response ) ) {
            esc_html_e( 'Onboarding True', 'solace-extra');
        } else {
            esc_html_e( 'Onboarding False', 'solace-extra');
        }    

        // Check if the LiteSpeed Cache plugin is active by looking in the 'active_plugins' option.
        $active_plugins = get_option( 'active_plugins' );
        if ( in_array( 'litespeed-cache/litespeed-cache.php', $active_plugins ) ) {
            update_option( 'solace_extra_deactivate_litespeed', true );

            // Ensure the 'plugin.php' file is loaded to use WordPress plugin functions.
            require_once ABSPATH . 'wp-admin/includes/plugin.php';

            // Define the plugin path for LiteSpeed Cache.
            $plugin = 'litespeed-cache/litespeed-cache.php';

            // Check if the plugin is active.
            if ( is_plugin_active( $plugin ) ) {
                // Deactivate the plugin.
                deactivate_plugins( $plugin );

                // Check if the plugin was successfully deactivated.
                if ( ! is_plugin_active( $plugin ) ) {
                    // Output a success message.
                    // esc_html_e( 'LiteSpeed Cache plugin has been successfully deactivated.', 'solace-extra');
                } else {
                    // Output an error message if the plugin is still active.
                    // esc_html_e( 'Failed to deactivate the LiteSpeed Cache plugin.', 'solace-extra');
                }
            } else {
                // Output a message if the plugin is not active.
                // esc_html_e( 'LiteSpeed Cache plugin is already inactive.', 'solace-extra');
            }

        } else {
            update_option( 'solace_extra_deactivate_litespeed', false );
        }
    }


    public function import_zip()
    {

        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
            $response = array('error' => 'Invalid nonce!');
			echo wp_json_encode($response);
            wp_die();
        }         

        set_theme_mod('solace_extra_import_zip_complete', false); // Menyimpan status impor sebagai false

        // Delete menu items.
        $this->delete_imported_menu_items();

        // Delete menu previously.
        $prevDemo = ! empty( $_POST['prevDemo'] ) ? sanitize_text_field( wp_unslash ( $_POST['prevDemo'] ) ) : '';
        if (!empty($prevDemo) && $prevDemo !== 'blank') {

            // Split the string into an array using commas as separators
            $arrayPrevDemo = explode(',', $prevDemo);

            // Remove leading and trailing whitespaces from each element in the array
            $arrayPrevDemo = array_map('trim', $arrayPrevDemo);

            // Remove duplicate values from the array, keeping only unique values
            $arrayPrevDemo = array_unique($arrayPrevDemo);


            foreach ($arrayPrevDemo as $get_demo) {
                $demo = "https://solacewp.com/" . $get_demo . "/wp-json/solace/v1/menus";
                $response = wp_remote_get($demo);
                $menus_data = wp_remote_retrieve_body($response);
                $menus = json_decode($menus_data);

                if (empty($get_demo)) {
                    esc_html_e('Erorr remove menu', 'solace-extra');
                    die;
                }

                // Loop through each menu
                if (!empty($menus)) {
                    $menus_local = wp_get_nav_menus();
                    foreach ($menus as $menu) {
                        wp_delete_nav_menu($menu->slug);

                        // Loop through each menu to be deleted
                        foreach ($menus_local as $menu_local) {
                            // Check if there is a duplicate by appending 'duplicate' at the end of slug
                            if (strpos($menu_local->slug, $menu->slug . '-duplicate') !== false) {
                                // If found, delete the menu
                                wp_delete_nav_menu($menu_local->slug);
                            }
                        }
                    }
                }
            }
        }        

        if ( class_exists( 'Elementor\Plugin' ) ) {
            // Delete Elementor templates kit.
            $this->delete_all_elementor_templates();

            // Delete widgets.
            $this->delete_sidebars_widgets();

            // Delete customizers.
            $this->delete_customizers();
		}

        // $this->install_and_activate_elementor();
        $demo_name = isset($_POST['getDemoName']) ? sanitize_text_field(wp_unslash($_POST['getDemoName'])) : '';
        $this->import_process->push_to_queue(array(
            'action' => 'demo_name',
            'demo_name' => $demo_name
        ));

        $this->import_process->save()->dispatch();

    }

    

    /**
     * Get unique posts by title or slug, filtering by a specific post meta.
     *
     * @param string $title The title or slug to search for.
     * @return array|null An array of posts with the specified title/slug and meta, or null if none found.
     */
    public function get_unique_posts_by_title( $title, $demo_name ) {

		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here.
        $post_ids = $wpdb->get_col(
            $wpdb->prepare(
                "
                SELECT p.ID 
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id 
                WHERE pm.meta_key = %s
                AND p.post_title = %s
                ",
                $demo_name,
                $title
            )
        );

		// Check if the array has more than one element.
		if (count($post_ids) > 1) {
			// Get the last element of the array.
			$last_post_id = end($post_ids);
		} else {
			// If there's only one element or the array is empty
			$last_post_id = !empty($post_ids) ? $post_ids[0] : null;
		}

		return $last_post_id;
    }

    // Function to update the WordPress menu
    public function call_ajax_import_menu()
    {

        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
            $response = array('error' => 'Invalid nonce!');
			echo wp_json_encode($response);
            wp_die();
        }         

        // Check current user
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'error' => 'Unauthorized' ) );
        }

        // Get menus data from the API
        $get_demo_url = ! empty( $_POST['getDemo'] ) ? sanitize_title( wp_unslash( $_POST['getDemo'] ) ) : '';
        $demo_name = ! empty( $_POST['getDemo'] ) ? sanitize_title( 'solace_extra_' . wp_unslash( $_POST['getDemo'] ) ) : '';

        $demo = 'https://solacewp.com/' . $get_demo_url . "/wp-json/solace/v1/menus";
        $response = wp_remote_get($demo);
        $menus_data = wp_remote_retrieve_body($response);
        $menus = json_decode($menus_data);

        if (empty($get_demo_url)) {
            esc_html_e('Error menu demo url', 'solace-extra');
            die;
        }

        // Get the list of menus
        $menu_local = wp_get_nav_menus();
        // Check if there are any menus available
        if ( ! empty( $menu_local ) ) {
            // Loop through the menus and check if any slug contains "-duplicate"
            foreach ( $menu_local as $menu ) {

                // Check if the slug contains '-duplicate'
                if ( strpos( $menu->slug, '-duplicate' ) !== false ) {
                    // Delete the menu that has '-duplicate' in its slug
                    wp_delete_nav_menu( $menu->slug );
                }
            }
        }

        // Set Shop page.
        update_option( 'woocommerce_shop_page_id', null );        

        // Loop through each menu
        if (!empty($menus)) {
            foreach ($menus as $menu) {
                // Skip if the menu name is not 'test_mymenu'
                // if ('test_mymenu' !== $menu->name) {
                //     continue;
                // }

                // Get the internal menu by slug
                $internal_menu = get_term_by('slug', $menu->slug, 'nav_menu');

                // Return if internal menu is not found
                if ($internal_menu === false) {
                    return;
                }

                // Remove existing menu items
                $menu_items = wp_get_nav_menu_items($internal_menu->term_id);
                foreach ($menu_items as $menu_item) {
                    wp_delete_post($menu_item->ID, true);
                }

                // if ( isset( $internal_menu->term_id ) ) {
                //     $new_slug = 'solace-extra-' . $internal_menu->slug;
                //     wp_update_term( $internal_menu->term_id, 'nav_menu', array(
                //         'slug' => $new_slug,
                //     ));
                // }

                // Build arguments for new menu items
                $args = [];
                $index = 0;
                $index_menu_item = 0;

                if (!empty($menu->terms)) {
                    foreach ($menu->terms as $menu_item) {
                        // Skip if the post status is 'draft'
                        if ('draft' === $menu_item->post_status) {
                            continue;
                        }

                        // Process menu item based on its type
                        $args_menu_item = $this->processMenuItem($menu_item, $demo_name, $index_menu_item);

                        // Update nav menu item and get the updated ID
                        $nav_menu_item_update = wp_update_nav_menu_item($internal_menu->term_id, 0, $args_menu_item);

                        update_post_meta($nav_menu_item_update, 'solace_import_menu_item', true);

                        // Update the object ID in the arguments
                        $args_menu_item['menu-item-object-id'] = $nav_menu_item_update;

                        // Output for debugging
                        // echo '<pre style="background: yellow;">';
                        // $this->outputDebuggingInfo($args_menu_item);
                        // echo "</pre>";

                        $args[$index++] = $args_menu_item;

                        $index_menu_item++;
                    }
                }

                // Update parent ID for menu items
                $this->updateParentID($internal_menu->term_id, $args);

                // Output for debugging
                // echo '<pre style="background: lightgreen;">';
                // outputDebuggingInfo($args);
                // echo "</pre>";
            }
        }

        wp_die();
    }

    // Function to process menu item based on its type
    public function processMenuItem($menu_item, $demo_name, $index_menu_item)
    {
        $args = [];

        // Common properties for all types
        $common_args = array(
            'menu-item-title'       => $menu_item->title,
            'menu-item-object'      => $menu_item->object,
            'menu-item-object-id'   => 0,
            'menu-item-position'    => $menu_item->menu_order,
            'menu-item-type'        => $menu_item->type,
            'menu-item-url'         => $menu_item->url,
            'menu-item-description' => $menu_item->description,
            'menu-item-attr-title'  => $menu_item->attr_title,
            'menu-item-target'      => $menu_item->target,
            'menu-item-xfn'         => $menu_item->xfn,
            'menu-item-status'      => $menu_item->post_status,
            'menu-item-parent-id'   => 0,
            'menu-item-parent-title' => $menu_item->menu_item_parent_title,
            'menu-item-parent-type'  => $menu_item->menu_item_parent_type,
        );

        if ($menu_item->type === 'taxonomy') {
            // Process taxonomy type menu item
            $url = $menu_item->url;
            $path = wp_parse_url($url, PHP_URL_PATH);
            $slug = basename($path);
            $term = get_term_by('slug', $slug, 'category');
            if ($term && is_object($term)) {
                $args = array_merge($common_args, array(
                    'menu-item-object-id' => $term->term_id, // ID Category
                ));
            }
        } elseif ($menu_item->type === 'post_type') {
            // Process taxonomy type menu item
            $menu_item_url = $menu_item->url;
            $path = wp_parse_url($menu_item_url, PHP_URL_PATH);
            $slug = basename($path);

            // Define arguments for querying a post based on the slug
            $args = array(
                'name'           => $slug,
                'post_status'    => 'publish',
                'posts_per_page' => -1,
            );

            // Retrieve the post based on the arguments
            $post = get_posts($args);

            // Check if a post with the specified slug exists
            if ($post && $post[0]->post_title) {
                // If found, merge common arguments with additional information
                $args = array_merge($common_args, array(
                    'menu-item-object-id' => $post[0]->ID,
                ));
            } else {
                // Second approach if the first one doesn't yield results
                // Check if a post with the menu item title exists
                $post_exists = post_exists($menu_item->title);

                // Parse the URL and get the path
                $path = wp_parse_url($menu_item->url, PHP_URL_PATH);
                
                // Get the last segment from the path
                $path_segments = explode('/', trim($path, '/'));
                $last_segment = end($path_segments);
                
                // Remove dashes and slashes, convert to desired format
                $clean_slug = str_replace('-', ' ', $last_segment);

                // Remove the prefix 'solace_extra_'
                $get_demo_name = str_replace( 'solace_extra_', '', $demo_name );

                // Set default variable $unique_posts.
                $unique_posts = false;

                if ( $get_demo_name === $clean_slug ) {
                    // Check property demo name.
                    $unique_posts = absint( $this->get_unique_posts_by_title( $menu_item->title, $demo_name ) );
                } else {
                    // Check property demo name.
                    $unique_posts = absint( $this->get_unique_posts_by_title( $clean_slug, $demo_name ) );
                }

                // Check if the word "shop" exists in the text
                $page_shop = stripos( $menu_item->title, 'shop' ) !== false;

                if ( $page_shop ) {

                    // Check dan ganti "pages" dengan "page"
                    $clean_slug = str_replace( 'pages', 'page', $clean_slug );

                    $unique_posts = absint( $this->get_unique_posts_by_title( $clean_slug, $demo_name ) );              

                }

                // Fix page blog.
                if ( 'Blog' === $menu_item->title || 'blog' === $menu_item->title ) {
                    if ( 'post_type' === $menu_item->type && $menu_item->is_posts_page ) {
                        $post = get_post( $unique_posts );
                        if ( ! $post ) {
                            $new_page = array(
                                'post_title'   => 'Blog',
                                'post_status'  => 'publish',
                                'post_type'    => 'page'
                            );
                    
                            // Insert new page.
                            $page_id = wp_insert_post( $new_page );
    
                            $unique_posts = $page_id;
                        }
                    }
                }

                // Check if any unique posts were found and display their details.
                if ($unique_posts) {

                    $post_exists = $unique_posts;                    

                    if ( $menu_item->is_posts_page ) {
                        $page_for_posts = $post_exists;
                        update_option( 'page_for_posts', $page_for_posts );
                    }

                    if ( $menu_item->is_shop_page && $menu_item->woocommerce_shop_page_id ) {
                        // Set Shop page.
                        update_option( 'woocommerce_shop_page_id', $post_exists );
                    }

                }

                if ($post_exists) {
                    // If found, merge common arguments with additional information
                    $args = array_merge($common_args, array(
                        'menu-item-object-id' => $post_exists,
                    ));
                } else {
                    $page = get_page_by_path($slug, OBJECT, 'page');
                    $post = get_page_by_path($slug, OBJECT, 'post');
                    $singular = get_page_by_path($slug, OBJECT, 'any');
                    if ($page && $page->ID) {
                        $args = array_merge($common_args, array(
                            'menu-item-object-id' => $page->ID,
                        ));
                    } elseif ($post && $post->ID) {
                        $args = array_merge($common_args, array(
                            'menu-item-object-id' => $post->ID,
                        ));
                    } elseif ($singular && $singular->ID) {
                        $args = array_merge($common_args, array(
                            'menu-item-object-id' => $singular->ID,
                        ));
                    }
                }
            }
        } else {
            // Process other types of menu item
            $args = array_merge($common_args, array(
                'menu-item-object-id' => $menu_item->object_id,
            ));
        }

        return $args;
    }

    // Function to update parent ID for menu items
    public function updateParentID($menu_id, &$args)
    {
        $self_items = wp_get_nav_menu_items($menu_id);
        $index = 0;

        foreach ($args as &$args_menu_item) {
            foreach ($self_items as $self_menu_item) {
                // Update parent ID if the titles match
                if (array_key_exists('menu-item-parent-title', $args_menu_item)) {
                    if ($args_menu_item['menu-item-parent-title'] === $self_menu_item->title) {
                        $args_menu_item['menu-item-parent-id'] = $self_menu_item->ID;
                        // wp_update_nav_menu_item($menu_id, $args_menu_item['menu-item-object-id'], $args_menu_item);
                        update_post_meta($args_menu_item['menu-item-object-id'], '_menu_item_menu_item_parent', $self_menu_item->ID);
                    }
                }
            }
            $index++;
        }
    }

    // Function to output debugging information
    public function outputDebuggingInfo($data)
    {
        // Output debugging information
        // print_r($data);
    }
}
