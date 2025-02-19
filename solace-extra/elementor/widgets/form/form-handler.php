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
	 * Form ajax.
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function elementor_form_builder_form_ajax() {

		check_ajax_referer( 'elementor_form_builder_form', 'nonce' );

		$data = !empty( $_POST['dataSerialize'] ) ? wp_kses_post( wp_unslash( $_POST['dataSerialize'] ) ) : '';
		$extension = !empty( $_POST['extension'] ) ? wp_kses_post( wp_unslash( $_POST['extension'] ) ) : '';
		$extension = explode(',', $extension);
		$post_id = !empty( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : '';
		$el_id = !empty( $_POST['el_id'] ) ? sanitize_text_field( wp_unslash( $_POST['el_id'] ) ) : '';
		$max_file_size = 10 * 1024 * 1024; // 10 MB in bytes.

		$attachments = [];

		// Iterate through all files in $_FILES
		foreach ($_FILES as $file_group) {
			if (isset($file_group['tmp_name']) && is_array($file_group['tmp_name'])) {
				foreach ($file_group['tmp_name'] as $key => $tmp_name) {
					if ($file_group['error'][$key] === UPLOAD_ERR_OK) {
						// Check file size
						if ($file_group['size'][$key] > $max_file_size) {
							wp_send_json_error(['message' => 'File size exceeds 10 MB.'], 400);
						}

						// Validate file extension
						$file_extension = strtolower(strrchr($file_group['name'][$key], '.'));
						if (!in_array($file_extension, $extension, true)) {
							$file_extension_message = sprintf(
								/* translators: %s: The file extension */
								esc_html__( 'Uploading files with the extensions %s is not permitted.', 'solace-extra' ),
								esc_html( $file_extension )
							);

							wp_send_json_error(
								[
									'message' => $file_extension_message,
								],
								400
							);
						}						

						$upload_overrides = array( 'test_form' => false );

						$file_array = array(
							'name'     => $file_group['name'][$key],
							'type'     => $file_group['type'][$key],
							'tmp_name' => $tmp_name,
							'error'    => $file_group['error'][$key],
							'size'     => $file_group['size'][$key],
						);
						
						// Upload process using wp_handle_upload
						$movefile = wp_handle_upload($file_array, $upload_overrides);
						
						if ($movefile && !isset($movefile['error'])) {
							$attachments[] = $movefile['file'];
						} else {
							wp_send_json_error(['message' => esc_html__( 'File upload failed.', 'solace-extra') ], 400);
						}

					}
				}
			} elseif (isset($file_group['tmp_name']) && $file_group['error'] === UPLOAD_ERR_OK) {
				// Check file size for single file
				if ($file_group['size'] > $max_file_size) {
					wp_send_json_error(['message' => 'File size exceeds 10 MB.'], 400);
				}

				// Validate file extension
				$file_extension = strtolower(strrchr($file_group['name'], '.'));
				if (!in_array($file_extension, $extension, true)) {
					$file_extension_message = sprintf(
						/* translators: %s: The file extension */
						esc_html__( 'Uploading files with the extensions %s is not permitted.', 'solace-extra' ),
						esc_html( $file_extension )
					);

					wp_send_json_error(
						[
							'message' => $file_extension_message,
						],
						400
					);
				}				

				$upload_overrides = array('test_form' => false);
				$file_array = array(
					'name'     => $file_group['name'],
					'type'     => $file_group['type'],
					'tmp_name' => $file_group['tmp_name'],
					'error'    => $file_group['error'],
					'size'     => $file_group['size'],
				);
		
				$movefile = wp_handle_upload($file_array, $upload_overrides);
		
				if ($movefile && !isset($movefile['error'])) {
					$attachments[] = $movefile['file'];
				} else {
					wp_send_json_error(['message' => esc_html__( 'File upload failed.', 'solace-extra') ], 400);			
				}

			}
		}
		
		if ( $data ) {
			$document = Plugin::$instance->documents->get( $post_id );

			if ( $document ) {
				$form        = Utils::find_element_recursive( $document->get_elements_data(), $el_id );
				$settings    = $form['settings'];
				$redirect    = isset( $settings['redirect'] ) ? true : false;
				$redirect_to = isset( $settings['redirect_url'] ) ? $settings['redirect_url'] : '';
				$to          = isset( $settings['email_to'] ) ? $settings['email_to'] : '';
				$subject     = isset( $settings['email_subject'] ) ? $settings['email_subject'] : '';
				$from        = isset( $settings['email_from'] ) ? $settings['email_from'] : '';
				$name        = isset( $settings['email_name'] ) ? $settings['email_name'] : '';

				$args = array(
					'redirect'        => $redirect,
					'redirect_to'     => $redirect_to,
					'error_message'   => $settings['error_message'],
					'success_message' => $settings['success_message'],
				);

				$this->message = $data;
				$this->form_data( $to, $subject, $from, $name );

				$headers = array('Content-Type: text/html; charset=UTF-8');

				$send = wp_mail( $to, $subject, $data, $headers, $attachments );

				// Cleanup uploaded files
				foreach ($attachments as $attachment) {
					wp_delete_file($attachment);
				}

				if ( is_wp_error( $send ) ) {
					wp_send_json_error( $args, 500 );
				} else {
					wp_send_json_success( $args, 200 );
				}
			}
		}

		wp_die();
	}
}

FormHandler::instance();
