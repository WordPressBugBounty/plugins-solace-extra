<?php
/**
 * @since   1.0.0
 *
 * @package Solace Form Builder
 */

namespace Solaceform;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;
use Solaceform\Widgets;

/**
 * Class Elementor
 */
class Elementor {
	/**
	 * Instance
	 *
	 * @var string
	 */
	private static $instance = null;

	/**
	 * Dir path
	 *
	 * @var string
	 */
	private static $dir;

	/**
	 * URL path
	 *
	 * @var string
	 */
	private static $url;

	/**
	 * Instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new Elementor();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function __construct() {
		$this->definitions();
		$this->load_dependencies();
		$this->hooks();
	}

	/**
	 * Load the dependencies
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function load_dependencies() {
		require_once self::$dir . 'form/form-handler.php';
	}

	/**
	 * Init hooks
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function hooks() {
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
	}

	/**
	 * Definitions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function definitions() {
		self::$dir = plugin_dir_path( __FILE__ );
		self::$url = plugin_dir_url( __FILE__ );
	}

	/**
	 * Register Scripts
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function register_scripts() {

		wp_register_script( 'solaceform', self::$url . 'assets/form/form-builder.js?v=' . time(), array( 'jquery' ), SOLACE_EXTRA_VERSION, true );

		// Enqueue the SweetAlert JavaScript library.
		wp_register_script( 'solaceform-sweetalert', self::$url . 'assets/form/sweetalert.min.js?v=' . time(), array(), SOLACE_EXTRA_VERSION, true );		

		wp_localize_script(
			'solaceform',
			'elementor_form_builder_obj',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'elementor_form_builder_form' ),
			)
		);
	}

	/**
	 * Register Styles
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function register_styles() {
		wp_register_style( 'solaceform', self::$url . 'assets/form/form-builder.css', array(), '1.0.0', 'all' );
	}

	/**
	 * Include Widgets files
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function include_widgets_files() {
		require_once self::$dir . 'widget/form-builder.php';
	}

	/**
	 * Register Widgets
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function register_widgets() {
		// Widget Base
		require_once self::$dir . 'widget-base/widget-base.php';

		// Form Builder Base
		require_once self::$dir . 'form/form-base.php';

		$this->include_widgets_files();

		// Register Widgets
		// Plugin::instance()->widgets_manager->register_widget_type( new Widgets\FormBuilder() );
		Plugin::instance()->widgets_manager->register_widget_type( new Widgets\SolaceFormBuilder() );
	}
}

Elementor::instance();
