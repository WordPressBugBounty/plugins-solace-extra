<?php

namespace SolaceExtraElementorAddons;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Plugin;
use Exception;
use SolaceExtraElementorAddonsPro\Libs\Solace_Extra_Elementor_License;

defined( 'ABSPATH' ) || die();

class Solace_Extra_Elementor_Library_Manager {

	protected static $source = null;

	public static function init() {
		// Enqueue editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'elementor/editor/footer', array( __CLASS__, 'print_template_views' ) );
		add_action( 'elementor/ajax/register_actions', array( __CLASS__, 'register_ajax_actions' ) );
	}

	public static function print_template_views() {
		include_once SOLACE_EXTRA_ELEMENTOR_ADDONS_DIR_PATH . 'inc/templates/template-library.php';
	}

	public static function enqueue_assets() {

		wp_enqueue_style(
			'solace-extra-elementor-template-library',
			SOLACE_EXTRA_ELEMENTOR_ADDONS_ASSETS . 'admin/css/template-library.css',
			array(
				'elementor-editor',
			),
			SOLACE_EXTRA_VERSION
		);

		wp_enqueue_script(
			'solace-extra-elementor-template-library',
			SOLACE_EXTRA_ELEMENTOR_ADDONS_ASSETS . 'admin/js/template-library.js',
			array(
				'elementor-editor',
				'jquery',
			),
			SOLACE_EXTRA_VERSION,
			true
		);

		// Check license status using the same logic as submenu-progress.php
		$saved_key = get_option( 'solace_license_key', '' );
		$license_info = false;
		$is_license_valid = false;

		// Check license if function exists (from solace-extra-pro plugin)
		if ( function_exists( 'solace_check_license' ) && ! empty( $saved_key ) ) {
			$license_info = solace_check_license( $saved_key );

			if ( $license_info && isset( $license_info->license ) ) {
				// Check if license status is valid
				$license_status = $license_info->license;
				$is_license_valid = ( 'valid' === $license_status || 'active' === $license_status );
			}
		}

		wp_localize_script(
			'solace-extra-elementor-template-library',
			'SolaceExtraElementorAddonsEditor',
			array(
				'hasPro' => $is_license_valid,
			)
		);
	}

	public static function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action(
			'get_solace_extra_elementor_library_data',
			function ( $data ) {
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new Exception( 'Access Denied' );
				}

				if ( ! empty( $data['editor_post_id'] ) ) {
					$editor_post_id = absint( $data['editor_post_id'] );

					if ( ! get_post( $editor_post_id ) ) {
						throw new Exception( esc_html__( 'Post not found.', 'solace-extra' ) );
					}

					Plugin::instance()->db->switch_to_post( $editor_post_id );
				}

				$result = self::get_library_data( $data );

				return $result;
			}
		);

		$ajax->register_ajax_action(
			'get_solace_extra_elementor_template_data',
			function ( $data ) {
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new Exception( 'Access Denied' );
				}

				if ( ! empty( $data['editor_post_id'] ) ) {
					$editor_post_id = absint( $data['editor_post_id'] );

					if ( ! get_post( $editor_post_id ) ) {
						throw new Exception( esc_html__( 'Post not found', 'solace-extra' ) );
					}

					Plugin::instance()->db->switch_to_post( $editor_post_id );
				}

				if ( empty( $data['template_id'] ) ) {
					throw new Exception( esc_html__( 'Template id missing', 'solace-extra' ) );
				}

				$result = self::get_template_data( $data );

				return $result;
			}
		);
	}

	/**
	 * Get library data from cache or remote
	 *
	 * type_tags has been added in version 2.15.0
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function get_library_data( array $args ) {
		$source = self::get_source();

		if ( ! empty( $args['sync'] ) ) {
			Library_Source::get_library_data( true );
		}

		return array(
			'templates' => $source->get_items(),
			'tags'      => $source->get_tags(),
			'type_tags' => $source->get_type_tags()
		);
	}

	/**
	 * Undocumented function
	 *
	 * @return Library_Source
	 */
	public static function get_source() {
		if ( is_null( self::$source ) ) {
			self::$source = new Library_Source();
		}

		return self::$source;
	}

	public static function get_template_data( array $args ) {
		$source = self::get_source();
		$data   = $source->get_data( $args );

		return $data;
	}
}

Solace_Extra_Elementor_Library_Manager::init();
