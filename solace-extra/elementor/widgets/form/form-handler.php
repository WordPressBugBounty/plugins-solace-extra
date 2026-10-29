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

	/**
	 * Prefix for a file field's input name; the field's key follows it.
	 *
	 * @since 1.7.3
	 */
	const FILE_INPUT_PREFIX = 'solaceFile_';

	/**
	 * The single input name every file field shared before 1.7.3.
	 *
	 * Still accepted so a page served from a full-page cache, carrying markup
	 * rendered before the change, keeps working until that cache turns over.
	 *
	 * @since 1.7.3
	 */
	const LEGACY_FILE_INPUT = 'solaceFile';

	private static $instance = null;

	/**
	 * Output buffer level before the AJAX handler started its own buffer.
	 *
	 * @var int|null
	 */
	private $ob_level = null;

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
	 * The key identifying a file field in its input name and in the MIME map.
	 *
	 * Rendering and validation both call this, so the name a browser posts a
	 * file under and the rule it is checked against can never drift apart.
	 * field_name wins when set; stored forms leave it empty on file fields, so
	 * in practice the repeater row's _id -- which Elementor always assigns --
	 * is what carries it. sanitize_key() keeps the result safe as a $_FILES key.
	 *
	 * @since 1.7.3
	 * @param array $field One row of the widget's 'fields' repeater.
	 * @return string Key, or '' when the row has neither value.
	 */
	public static function file_field_key( array $field ): string {
		if ( isset( $field['field_name'] ) && '' !== (string) $field['field_name'] ) {
			return sanitize_key( (string) $field['field_name'] );
		}

		return isset( $field['_id'] ) ? sanitize_key( (string) $field['_id'] ) : '';
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
	 * Handle Elementor form file upload and send email with attachments.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function elementor_form_builder_form_ajax() {
		// This endpoint answers with JSON and nothing else. On a site running
		// with WP_DEBUG_DISPLAY on -- or any plugin/theme that emits a notice,
		// deprecation or stray echo during the request -- that text is printed
		// into the response body and prepended to the JSON. The browser then
		// cannot parse the reply: the submit silently fails with an "AJAX error"
		// in the console and the visitor never sees a success or error message.
		//
		// Everything printed during the request is buffered and thrown away
		// right before the reply is sent (send_json_success/error()), so the body
		// is pure JSON. Nothing is hidden from the developer -- WP_DEBUG_LOG still
		// records every notice -- it is only kept out of the bytes the browser
		// must parse. Changing display_errors at runtime is not allowed in
		// WordPress.org plugins, so output buffering does the job instead.
		$this->ob_level = ob_get_level();
		ob_start();

		check_ajax_referer( 'elementor_form_builder_form', 'nonce' );

		// Both run before the document lookup and the mail send, so a flood is
		// turned away at the cost of a single transient read.
		//
		// Order matters: the rate limiter goes first so a bot tripping the
		// honeypot still spends its budget. Checking the honeypot first would be
		// marginally cheaper but would let it hammer that path for free.
		$this->check_rate_limit();
		$this->check_honeypot();

		$post_id       = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;
		$post_id2      = isset( $_POST['post_id2'] ) ? absint( wp_unslash( $_POST['post_id2'] ) ) : 0;
		$el_id         = isset( $_POST['el_id'] ) ? sanitize_text_field( wp_unslash( $_POST['el_id'] ) ) : '';
		$max_file_size = 10 * 1024 * 1024; // 10 MB

		$document = Plugin::$instance->documents->get( $post_id );
		if ( ! $document ) {
			$this->send_json_error( [ 'message' => 'Invalid post document.' ], 400 );
		}

		$form     = Utils::find_element_recursive( $document->get_elements_data(), $el_id );
		$settings = [];

		// Ensure $form contains valid settings
		if ( is_array( $form ) && isset( $form['settings'] ) && is_array( $form['settings'] ) ) {
			$settings = $form['settings'];
		}

		// Fallback to raw _elementor_data if $settings is empty
		if ( empty( $settings ) ) {
			$content  = get_post_meta( $post_id, '_elementor_data', true );
			$elements = json_decode( $content, true );

			if ( ! function_exists( 'solace_extra_find_elementor_widget_by_id' ) ) {
				function solace_extra_find_elementor_widget_by_id( $elements, $el_id ) {
					foreach ( $elements as $element ) {
						if ( isset( $element['id'] ) && $element['id'] === $el_id ) {
							return $element;
						}
						if ( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
							$result = solace_extra_find_elementor_widget_by_id( $element['elements'], $el_id );
							if ( $result ) {
								return $result;
							}
						}
					}
					return null;
				}
			}

			$widget_data = solace_extra_find_elementor_widget_by_id( $elements, $el_id );

			if ( ! empty( $widget_data ) ) {
				// Use fallback settings if widget found
				if ( $widget_data && isset( $widget_data['settings'] ) && is_array( $widget_data['settings'] ) ) {
					$settings = $widget_data['settings'];
				}
			} else {
				$content2  = get_post_meta( $post_id2, '_elementor_data', true );
				$elements2 = json_decode( $content2, true );
				$widget_data2 = solace_extra_find_elementor_widget_by_id( $elements2, $el_id );
				// Use fallback settings if widget found
				if ( $widget_data2 && isset( $widget_data2['settings'] ) && is_array( $widget_data2['settings'] ) ) {
					$settings = $widget_data2['settings'];
				}
			}
		}

		// Safe default response messages
		$response = [
			'error_message'   => sanitize_text_field( $settings['error_message'] ?? 'Form error.' ),
			'success_message' => sanitize_text_field( $settings['success_message'] ?? 'Thank you!' ),
		];

		// Ensure fields is always an array
		$fields = isset( $settings['fields'] ) && is_array( $settings['fields'] ) ? $settings['fields'] : [];

		// Validate required values. Both come from the widget's stored settings,
		// so a form whose definition could not be resolved is refused outright
		// rather than falling back to anything the request supplied.
		if ( empty( $fields ) || empty( $settings['email_to'] ) ) {
			// A missing recipient or unresolved field set is a handled, expected
			// outcome, not a server fault. Answering with HTTP 500 made the browser
			// log "POST admin-ajax.php 500 (Internal Server Error)" in the console
			// and pushed the reply down the jQuery error() path. Returning the
			// default 200 with success:false keeps the body (error_message) intact,
			// clears the console error, and lets the front-end success() handler show
			// the message the normal way.
			$this->send_json_error( $response );
		}

		$data = $this->build_email_body( $fields );

		// Every field arrived empty, or none of them was attributable. Nothing
		// worth mailing, and an empty body is the shape a probe would produce.
		if ( '' === $data ) {
			$this->send_json_error( $response, 400 );
		}

		// Prepare MIME rules and file uploads
		$field_mime_map = $this->map_field_extensions_to_mimes( $fields );
		$attachments    = $this->process_uploaded_files_with_field_validation( $field_mime_map, $max_file_size, $fields );

		// Email setup
		$email  = array_map( 'trim', explode( ',', $settings['email_to'] ) );
		$headers = [ 'Content-Type: text/html; charset=UTF-8' ];
		$subject = sanitize_text_field( $settings['email_subject'] ?? 'Form Submission' );

		// $attachments maps display name => PHP upload temporary. wp_mail() uses a
		// string key as the attachment's filename, so nothing had to be moved to
		// give the recipient a sensible name. No cleanup loop either: PHP owns
		// those temporaries and discards them when the request ends, on every
		// path out of here rather than only the one that sent successfully.
		$send = wp_mail( $email, $subject, $data, $headers, $attachments );

		// wp_mail() answers true or false; it never returns a WP_Error. Testing
		// it with is_wp_error() was always false, so a message that failed to
		// leave -- mail server down, recipient refused -- was reported to the
		// visitor as sent, and the submission was lost with nobody the wiser.
		// The reason for a failure is broadcast on the wp_mail_failed action.
		if ( ! $send ) {
			// Mail delivery failed (mail server down, recipient refused, no SMTP
			// configured). The visitor still needs the error_message, but surfacing
			// this as HTTP 500 only produced a red "500 (Internal Server Error)" in
			// the console while the message was already carried in the JSON body.
			// Return the default 200 with success:false so the front-end success()
			// handler shows error_message and the console stays clean. The real
			// delivery failure is still recorded via the wp_mail_failed action.
			$this->send_json_error( $response );
		}

		$this->send_json_success( $response, 200 );
	}

	/**
	 * Discard whatever was printed during the request (notices, deprecations,
	 * stray echoes) so the JSON reply is the only thing in the response body.
	 *
	 * @return void
	 */
	private function discard_stray_output() {
		if ( null === $this->ob_level ) {
			return;
		}
		while ( ob_get_level() > $this->ob_level ) {
			ob_end_clean();
		}
	}

	/**
	 * wp_send_json_success() with the stray output discarded first.
	 *
	 * @param mixed    $data        Data to encode as JSON.
	 * @param int|null $status_code HTTP status code.
	 * @return void
	 */
	private function send_json_success( $data = null, $status_code = null ) {
		$this->discard_stray_output();
		wp_send_json_success( $data, $status_code );
	}

	/**
	 * wp_send_json_error() with the stray output discarded first.
	 *
	 * @param mixed    $data        Data to encode as JSON.
	 * @param int|null $status_code HTTP status code.
	 * @return void
	 */
	private function send_json_error( $data = null, $status_code = null ) {
		$this->discard_stray_output();
		wp_send_json_error( $data, $status_code );
	}

	/**
	 * Refuse a submission that filled the honeypot field.
	 *
	 * The widget renders an input that sits off-screen, carries tabindex="-1"
	 * and aria-hidden, and is never reachable by keyboard or screen reader. A
	 * person cannot put a value in it; something parsing the markup and filling
	 * every input it finds will.
	 *
	 * This answers with an error rather than a fake success on purpose. A fake
	 * success teaches a bot nothing, but it would also silently swallow the
	 * submission of a real visitor whose password manager filled the field
	 * despite autocomplete="off" -- they would believe the form had sent.
	 *
	 * @since 1.7.3
	 * @return void
	 */
	private function check_honeypot() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified by the caller.
		if ( ! empty( $_POST['solaceform_hp'] ) ) {
			$this->send_json_error( [ 'error_message' => __( 'Your submission could not be sent.', 'solace-extra' ) ], 400 );
		}
	}

	/**
	 * Cap how often one client may submit.
	 *
	 * This endpoint is registered for wp_ajax_nopriv and sends mail, so without
	 * a cap a single caller can empty a mail quota, bury the site owner, or --
	 * combined with the upload path below -- fill the disk.
	 *
	 * Logged-in users are exempt: the abuse case is anonymous, and an editor
	 * testing their own form should not be able to lock themselves out.
	 *
	 * Buckets are keyed on REMOTE_ADDR, which is the only address a client
	 * cannot forge. Behind a reverse proxy or CDN that value is the proxy's, so
	 * every visitor lands in one shared bucket -- sites in that position should
	 * hook `solace_form_client_ip` and return the real client address from
	 * whichever forwarded header their proxy is trusted to set. The window start
	 * is stored alongside the count so a slow trickle cannot hold a bucket open
	 * by repeatedly refreshing the transient's expiry.
	 *
	 * @since 1.7.3
	 * @return void
	 */
	private function check_rate_limit() {
		if ( is_user_logged_in() ) {
			return;
		}

		$remote_addr = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

		/**
		 * Filters the client address the form rate limiter buckets on.
		 *
		 * @since 1.7.3
		 * @param string $remote_addr Value of REMOTE_ADDR.
		 */
		$client_ip = (string) apply_filters( 'solace_form_client_ip', $remote_addr );

		// No address to attribute the request to. Rather than put every such
		// caller in one shared bucket, let it through -- the honeypot and the
		// upload limits still apply.
		if ( '' === $client_ip ) {
			return;
		}

		/**
		 * Filters how many submissions one client may make per window.
		 *
		 * @since 1.7.3
		 * @param int $max Submissions allowed.
		 */
		$max = (int) apply_filters( 'solace_form_rate_limit_max', 10 );

		/**
		 * Filters the length of the rate-limit window, in seconds.
		 *
		 * @since 1.7.3
		 * @param int $window Window length.
		 */
		$window = (int) apply_filters( 'solace_form_rate_limit_window', 5 * MINUTE_IN_SECONDS );

		if ( $max < 1 || $window < 1 ) {
			return;
		}

		$key   = 'solace_form_rl_' . md5( $client_ip );
		$now   = time();
		$state = get_transient( $key );

		if ( ! is_array( $state ) || ! isset( $state['count'], $state['start'] ) || ( $now - (int) $state['start'] ) >= $window ) {
			$state = [
				'count' => 0,
				'start' => $now,
			];
		}

		if ( (int) $state['count'] >= $max ) {
			$this->send_json_error(
				[ 'error_message' => __( 'Too many submissions. Please try again in a few minutes.', 'solace-extra' ) ],
				429
			);
		}

		$state['count'] = (int) $state['count'] + 1;

		// Expiry is padded so the record outlives its own window; the stored
		// start time, not the TTL, is what decides when the count resets.
		set_transient( $key, $state, $window + MINUTE_IN_SECONDS );
	}

	/**
	 * Compose the email body from the widget's own field definitions.
	 *
	 * The body used to be built in the browser and posted as 'dataSerialize',
	 * which this handler passed to wp_mail() after nothing more than
	 * wp_kses_post(). Because the endpoint is registered for wp_ajax_nopriv and
	 * its nonce is printed on every public page carrying the form, that made the
	 * entire message -- links, images, whatever wp_kses_post() lets through --
	 * attacker-controlled, and sent it from the site's own mail server.
	 *
	 * The request already carries each field under its own name, so the body is
	 * rebuilt here instead: the widget's stored settings decide which fields
	 * exist and what they are called, the request only supplies values, and
	 * every label and value is escaped on the way in. A name the settings do not
	 * list contributes nothing.
	 *
	 * @since 1.7.3
	 * @param array $fields Repeater rows from the widget's 'fields' setting.
	 * @return string HTML body, or '' when nothing usable was submitted.
	 */
	private function build_email_body( array $fields ): string {
		$rows = [];

		foreach ( $fields as $field ) {
			$type = isset( $field['field_type'] ) ? strtolower( (string) $field['field_type'] ) : '';

			// Nothing a visitor typed: HTML blocks are the widget's own copy,
			// reCAPTCHA is a token, and files travel as attachments instead.
			if ( in_array( $type, [ 'html', 'recaptcha', 'file' ], true ) ) {
				continue;
			}

			$name = isset( $field['field_name'] ) ? (string) $field['field_name'] : '';

			// A field saved without a name is rendered under a random one
			// generated at paint time, which nothing on this side can match. It
			// was never attributable, so leave it out rather than guess.
			if ( '' === $name || ! isset( $_POST[ $name ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified by the caller.
				continue;
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Verified by the caller; sanitized below.
			$raw = wp_unslash( $_POST[ $name ] );

			$value = is_array( $raw )
				? implode( ', ', array_map( 'sanitize_text_field', $raw ) )
				: sanitize_textarea_field( $raw );

			if ( '' === trim( $value ) ) {
				continue;
			}

			$label = ( isset( $field['field_label'] ) && '' !== $field['field_label'] )
				? (string) $field['field_label']
				: $name;

			$rows[] = '<p><strong>' . esc_html( $label ) . ':</strong><br />' . nl2br( esc_html( $value ) ) . '</p>';
		}

		return implode( "\n", $rows );
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
				$field_name = self::file_field_key( $field );
				$extensions = [];

				if ( '' === $field_name ) {
					continue;
				}

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

				// Two rows resolving to one key share an input name, so a file
				// posted under it may belong to either: accept what both allow.
				$mime_map[ $field_name ] = array_values(
					array_unique( array_merge( $mime_map[ $field_name ] ?? [], $mimes ) )
				);
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
	/**
	 * Validate every uploaded file, then hand them back as mail attachments.
	 *
	 * The previous version validated and moved in a single pass, calling
	 * wp_handle_upload() per file as it went. Three things followed from that:
	 *
	 *  - Nothing capped how many files a submission could carry, or their
	 *    combined size. Only each file on its own was checked, and the endpoint
	 *    takes no authentication, so filling the disk cost an attacker one
	 *    request.
	 *  - Files landed in the public uploads directory, reachable by URL.
	 *  - Cleanup ran only after a successful send, so any request that tripped a
	 *    rule part-way -- a bad MIME on the third of four files -- left the
	 *    first two in that public directory for good.
	 *
	 * Now the whole submission is validated before anything is accepted, and the
	 * files are never moved at all: PHP's own upload temporaries are attached
	 * directly, under a display name, so nothing ever reaches a web-readable
	 * path and PHP discards them when the request ends whatever the outcome.
	 *
	 * @since  1.0.0
	 * @param array $field_mime_map Field identifier => allowed MIME types.
	 * @param int   $max_size       Largest single file, in bytes.
	 * @param array $fields         Repeater rows from the widget's 'fields' setting.
	 * @return array Display name => temporary path, as wp_mail() accepts.
	 */
	private function process_uploaded_files_with_field_validation( array $field_mime_map, int $max_size, array $fields ): array {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified by check_ajax_referer() in the caller.
		if ( empty( $_FILES ) ) {
			return [];
		}

		/**
		 * Filters how many files one submission may carry.
		 *
		 * @since 1.7.3
		 * @param int $max_files Number of files allowed.
		 */
		$max_files = (int) apply_filters( 'solace_form_max_files', 5 );

		/**
		 * Filters the combined size of one submission's files, in bytes.
		 *
		 * @since 1.7.3
		 * @param int $max_total_size Combined size allowed.
		 */
		$max_total_size = (int) apply_filters( 'solace_form_max_total_upload_size', 25 * 1024 * 1024 );

		$declared_mimes = [];
		foreach ( $field_mime_map as $mimes ) {
			$declared_mimes = array_merge( $declared_mimes, (array) $mimes );
		}
		$declared_mimes = array_unique( $declared_mimes );

		// A form that declares no file field accepts no file. Say so plainly
		// rather than letting the per-file check reject each one in turn.
		if ( empty( $declared_mimes ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified by check_ajax_referer() in the caller.
			foreach ( $_FILES as $file_group ) {
				foreach ( $this->normalize_file_group( $file_group ) as $file ) {
					if ( UPLOAD_ERR_NO_FILE !== $file['error'] ) {
						$this->send_json_error( [ 'error_message' => __( 'This form does not accept file uploads.', 'solace-extra' ) ], 400 );
					}
				}
			}

			return [];
		}

		$accepted   = [];
		$total_size = 0;

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified by check_ajax_referer() in the caller.
		foreach ( $_FILES as $input_name => $file_group ) {
			$input_name = (string) $input_name;

			// Each file field posts under its own name, so the rule a file must
			// satisfy is read straight off the input it arrived in -- no guessing
			// from where it sits among the others.
			if ( 0 === strpos( $input_name, self::FILE_INPUT_PREFIX ) ) {
				$field_key     = substr( $input_name, strlen( self::FILE_INPUT_PREFIX ) );
				$allowed_mimes = isset( $field_mime_map[ $field_key ] ) ? (array) $field_mime_map[ $field_key ] : [];
			} elseif ( self::LEGACY_FILE_INPUT === $input_name ) {
				// Markup from before per-field names, served out of a page cache.
				// Which field each file came from cannot be known, so hold it to
				// the union of what the form declares: still bounded by the form.
				$allowed_mimes = $declared_mimes;
			} else {
				// Not an input this widget renders. Never attached, so ignore it.
				continue;
			}

			foreach ( $this->normalize_file_group( $file_group ) as $file ) {
				if ( UPLOAD_ERR_OK !== $file['error'] || ! is_uploaded_file( $file['tmp_name'] ) ) {
					continue;
				}

				if ( count( $accepted ) >= $max_files ) {
					$this->send_json_error(
						[
							'error_message' => sprintf(
								/* translators: %d: maximum number of files. */
								__( 'Please attach no more than %d files.', 'solace-extra' ),
								$max_files
							),
						],
						400
					);
				}

				if ( $file['size'] > $max_size ) {
					$this->send_json_error(
						[
							'error_message' => sprintf(
								/* translators: %s: maximum file size, already formatted. */
								__( 'Each file must be %s or smaller.', 'solace-extra' ),
								size_format( $max_size )
							),
						],
						400
					);
				}

				$total_size += $file['size'];

				if ( $total_size > $max_total_size ) {
					$this->send_json_error(
						[
							'error_message' => sprintf(
								/* translators: %s: maximum combined size, already formatted. */
								__( 'Your attachments come to more than %s in total.', 'solace-extra' ),
								size_format( $max_total_size )
							),
						],
						400
					);
				}

				// Trust the file itself, not the Content-Type the browser sent. An
				// input name matching no declared field leaves $allowed_mimes
				// empty, so anything posted under one is refused here.
				$real_mime = function_exists( 'mime_content_type' ) ? mime_content_type( $file['tmp_name'] ) : false;

				if ( ! $real_mime || ! in_array( $real_mime, $allowed_mimes, true ) ) {
					$this->send_json_error( [ 'error_message' => __( 'Unsupported file type.', 'solace-extra' ) ], 400 );
				}

				// The extension has to agree with the contents too, or a script
				// could ride in under a name the mail client treats as harmless.
				$display_name = sanitize_file_name( $file['name'] );
				$checked      = wp_check_filetype_and_ext( $file['tmp_name'], $display_name );

				if ( empty( $checked['ext'] ) || empty( $checked['type'] ) || ! in_array( $checked['type'], $allowed_mimes, true ) ) {
					$this->send_json_error( [ 'error_message' => __( 'Unsupported file type.', 'solace-extra' ) ], 400 );
				}

				if ( ! empty( $checked['proper_filename'] ) ) {
					$display_name = sanitize_file_name( $checked['proper_filename'] );
				}

				if ( '' === $display_name ) {
					$display_name = 'attachment.' . $checked['ext'];
				}

				// wp_mail() takes a display name as the array key. Two files of
				// the same name would collapse into one, so keep them apart.
				$unique = $display_name;
				$suffix = 1;

				while ( isset( $accepted[ $unique ] ) ) {
					$suffix++;
					$unique = preg_replace( '/(\.[^.]+)$/', '-' . $suffix . '$1', $display_name );

					if ( $unique === $display_name ) {
						$unique = $display_name . '-' . $suffix;
					}
				}

				// Deliberately not moved. PHP's upload temporary is outside the
				// web root, PHPMailer reads it during send(), and PHP removes it
				// when the request ends -- including every error path above, so
				// a rejected submission can no longer strand a file anywhere.
				$accepted[ $unique ] = $file['tmp_name'];
			}
		}

		return $accepted;
	}

	/**
	 * Flatten one $_FILES entry into a list of per-file arrays.
	 *
	 * PHP shapes the entry differently depending on whether the input was named
	 * with brackets, and the widget can render either. Normalising once here
	 * keeps the caller from branching on is_array() for all five keys.
	 *
	 * @since 1.7.3
	 * @param array $file_group One $_FILES entry.
	 * @return array[] Each with tmp_name, name, error and size.
	 */
	private function normalize_file_group( $file_group ): array {
		if ( ! is_array( $file_group ) || ! isset( $file_group['tmp_name'] ) ) {
			return [];
		}

		if ( ! is_array( $file_group['tmp_name'] ) ) {
			return [
				[
					'tmp_name' => (string) $file_group['tmp_name'],
					'name'     => isset( $file_group['name'] ) ? (string) $file_group['name'] : '',
					'error'    => isset( $file_group['error'] ) ? (int) $file_group['error'] : UPLOAD_ERR_NO_FILE,
					'size'     => isset( $file_group['size'] ) ? (int) $file_group['size'] : 0,
				],
			];
		}

		$files = [];

		foreach ( array_keys( $file_group['tmp_name'] ) as $i ) {
			$files[] = [
				'tmp_name' => (string) $file_group['tmp_name'][ $i ],
				'name'     => isset( $file_group['name'][ $i ] ) ? (string) $file_group['name'][ $i ] : '',
				'error'    => isset( $file_group['error'][ $i ] ) ? (int) $file_group['error'][ $i ] : UPLOAD_ERR_NO_FILE,
				'size'     => isset( $file_group['size'][ $i ] ) ? (int) $file_group['size'][ $i ] : 0,
			];
		}

		return $files;
	}


}

FormHandler::instance();
