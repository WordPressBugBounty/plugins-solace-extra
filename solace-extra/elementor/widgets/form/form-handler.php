<?php
/**
 * @since   1.0.0
 *
 * @package Solace Form Builder
 */

namespace Solace_Extra_Form;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;
use Elementor\Utils;

/**
 * Class FormHandler
 */
class FormHandler {

	private static $instance = null;

	private $to;

	private $subject;

	private $from;

	private $name;

	private $message;

	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new FormHandler();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since  1.0.0
	 *
	 * @access private
	 */
	private function hooks() {
		add_action( 'wp_ajax_elementor_form_builder_form_ajax', array( $this, 'elementor_form_builder_form_ajax' ) );
		add_action( 'wp_ajax_nopriv_elementor_form_builder_form_ajax', array( $this, 'elementor_form_builder_form_ajax' ) );
	}

	/**
	 * Form data.
	 *
	 * @since  1.0.0
	 *
	 * @access private
	 */
	private function form_data( string $to, string $subject, string $from, string $name ) {
		$this->to      = $to;
		$this->subject = $subject;
		$this->from    = $from;
		$this->name    = $name;
	}

	/**
	 * Send email.
	 *
	 * @since  1.0.0
	 *
	 * @access private
	 */
	private function send_email() {
		$headers = array('Content-Type: text/html; charset=UTF-8');

		return wp_mail( $this->to, $this->subject, $this->message, $headers );
	}

	/**
	 * Handle Elementor form file upload and send email with attachments.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function elementor_form_builder_form_ajax() {
		check_ajax_referer( 'elementor_form_builder_form', 'nonce' );

		$data    = isset( $_POST['dataSerialize'] ) ? wp_kses_post( wp_unslash( $_POST['dataSerialize'] ) ) : '';
		$post_id = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : '';
		$el_id   = isset( $_POST['el_id'] ) ? sanitize_text_field( wp_unslash( $_POST['el_id'] ) ) : '';
		$max_file_size = 10 * 1024 * 1024; // 10 MB.

		$document = Plugin::$instance->documents->get( $post_id );
		if ( ! $document ) {
			wp_send_json_error( [ 'message' => 'Invalid post document.' ], 400 );
		}

		$form     = Utils::find_element_recursive( $document->get_elements_data(), $el_id );
		$settings = $form['settings'] ?? [];

		if ( empty( $data ) || empty( $settings['email_to'] ) ) {
			wp_send_json_error( [ 'message' => 'Missing email recipient or form data.' ], 400 );
		}

		// Extract per-field MIME validation rules
		$field_mime_map = $this->map_field_extensions_to_mimes( $settings['fields'] ?? [] );

		// Process uploaded files with per-field MIME validation
		$attachments = $this->process_uploaded_files_with_field_validation( $field_mime_map, $max_file_size, $settings['fields'] );

		// Email configuration
		$headers = [ 'Content-Type: text/html; charset=UTF-8' ];
		$send    = wp_mail(
			sanitize_email( $settings['email_to'] ),
			sanitize_text_field( $settings['email_subject'] ?? 'Form Submission' ),
			$data,
			$headers,
			$attachments
		);

		// Clean up temporary uploaded files
		foreach ( $attachments as $attachment ) {
			wp_delete_file( $attachment );
		}

		$response = [
			'error_message'   => sanitize_text_field( $settings['error_message'] ?? '' ),
			'success_message' => sanitize_text_field( $settings['success_message'] ?? '' ),
		];

		if ( is_wp_error( $send ) ) {
			wp_send_json_error( $response, 500 );
		} else {
			wp_send_json_success( $response, 200 );
		}

		wp_die();
	}

	/**
	 * Map each file field to its allowed MIME types.
	 *
	 * @param array $fields Elementor field settings.
	 * @return array Associative array: field_name => [allowed_mime_types]
	 */
	private function map_field_extensions_to_mimes( array $fields ): array {
		$mime_map = [];
		$allowed_mimes = get_allowed_mime_types();

		foreach ( $fields as $field ) {
			if ( isset( $field['field_type'] ) && strtolower( $field['field_type'] ) === 'file' ) {
				$field_name = $field['field_name'] ?? $field['_id'];
				$extensions = [];

				if ( ! empty( $field['file_types'] ) ) {
					$types = explode( ',', $field['file_types'] );
					foreach ( $types as $type ) {
						$type = strtolower( trim( ltrim( $type, '.' ) ) );
						if ( preg_match( '/^[a-z0-9]+$/', $type ) ) {
							$extensions[] = $type;
						}
					}
				}

				$mimes = [];
				foreach ( $extensions as $ext ) {
					foreach ( $allowed_mimes as $key => $mime ) {
						$exts = explode( '|', $key );
						if ( in_array( $ext, $exts, true ) ) {
							$mimes[] = $mime;
						}
					}
				}

				$mime_map[ $field_name ] = array_unique( $mimes );
			}
		}

		return $mime_map;
	}

	/**
	 * Validate and process uploaded files with field-based MIME rules.
	 *
	 * @param array $field_mime_map Field name to allowed MIME types mapping.
	 * @param int   $max_size       Maximum file size allowed.
	 * @param array $settings       get settings.
	 * @return array Array of uploaded file paths.
	 */
	private function process_uploaded_files_with_field_validation( array $field_mime_map, int $max_size, array $settings ): array {
		check_ajax_referer( 'elementor_form_builder_form', 'nonce' );

		$attachments = [];

		foreach ( $_FILES as $field_name => $file_group ) {
			$indexes = isset( $file_group['tmp_name'] ) && is_array( $file_group['tmp_name'] )
				? array_keys( $file_group['tmp_name'] )
				: [ null ];

			foreach ( $indexes as $i ) {
				$tmp  = is_null( $i ) ? $file_group['tmp_name'] : $file_group['tmp_name'][ $i ];
				$name = is_null( $i ) ? $file_group['name']     : $file_group['name'][ $i ];
				$type = is_null( $i ) ? $file_group['type']     : $file_group['type'][ $i ];
				$err  = is_null( $i ) ? $file_group['error']    : $file_group['error'][ $i ];
				$size = is_null( $i ) ? $file_group['size']     : $file_group['size'][ $i ];

				if ( $err !== UPLOAD_ERR_OK || ! is_uploaded_file( $tmp ) ) {
					continue;
				}

				if ( $size > $max_size ) {
					wp_send_json_error( [ 'message' => 'File size exceeds 10 MB.' ], 400 );
				}

				// Get the real MIME type of the uploaded temporary file
				$real_mime = mime_content_type( $tmp );

				// Filter out only fields with type 'File' from the settings array
				$file_fields = array_filter( $settings, function( $field ) {
					return isset( $field['field_type'] ) && $field['field_type'] === 'File';
				});

				// Reindex the array to ensure numeric keys start from 0
				$file_fields = array_values( $file_fields );

				// Get the field identifier, prioritizing 'field_name' if available
				$field_id = isset( $file_fields[ $i ]['field_name'] ) ? $file_fields[ $i ]['field_name'] : $file_fields[ $i ]['_id'];

				// Fetch the list of allowed MIME types for the current field from the MIME map
				$allowed_mimes = isset( $field_mime_map[ $field_id ] ) ? $field_mime_map[ $field_id ] : [];

				// Validate if the real MIME type is among the allowed types for this field
				if ( ! in_array( $real_mime, $allowed_mimes, true ) ) {
					// Return an error response if the MIME type is not allowed
					// wp_send_json_error( [ 'message' => 'Unsupported file type. Allowed types: ' . implode( ', ', $allowed_mimes ) ], 400 );
					wp_send_json_error( [ 'message' => 'Unsupported file extension' ], 400 );
				}
				
				$file = [
					'name'     => sanitize_file_name( $name ),
					'type'     => $type,
					'tmp_name' => $tmp,
					'error'    => $err,
					'size'     => $size,
				];

				$movefile = wp_handle_upload( $file, [ 'test_form' => false ] );

				if ( isset( $movefile['error'] ) ) {
					wp_send_json_error( [ 'message' => 'Upload failed: ' . $movefile['error'] ], 400 );
				} else {
					$attachments[] = $movefile['file'];
				}
			}
		}

		return $attachments;
	}

}

FormHandler::instance();
