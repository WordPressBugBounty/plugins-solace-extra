<?php
defined( 'ABSPATH' ) || exit;

/**
 * Service class to handle Elementor sitebuilder image updates.
 *
 * This class provides a general and flexible approach to update images
 * in Elementor elements based on widget type, data_id, and control_id.
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/includes
 */
class Solace_Extra_Sitebuilder_Images {

	/**
	 * Configuration for widget types and their default behaviors.
	 *
	 * @var array
	 */
	private $widget_config = array(
		'solace-extra-site-logo' => array(
			'default_control_id' => 'solace_extra_site_logo',
			'use_attachment'       => true,
		),
		'image' => array(
			'default_control_id' => 'image',
			'use_attachment'       => true,
		),
		'container' => array(
			'default_control_id' => 'background_image',
			'use_attachment'       => false,
		),
	);

	/**
	 * Download an image from a remote URL and upload it to the WordPress media library.
	 *
	 * @param string $image_url The URL of the image to download.
	 * @param string $filename  Optional filename for the uploaded image.
	 * @return int|false Attachment ID on success, false on failure.
	 */
	public function download_and_upload_image( $image_url, $filename = '' ) {
		// Sanitize and validate URL.
		$image_url = esc_url_raw( $image_url );

		if ( empty( $image_url ) || ! wp_http_validate_url( $image_url ) ) {
			return false;
		}

		$scheme = wp_parse_url( $image_url, PHP_URL_SCHEME );
		if ( ! in_array( $scheme, array( 'http', 'https' ), true ) ) {
			return false;
		}

		// Fetch the remote image.
		$response = wp_remote_get(
			$image_url,
			array(
				'timeout'   => 20,
				'sslverify' => true,
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body         = wp_remote_retrieve_body( $response );
		$content_type = wp_remote_retrieve_header( $response, 'content-type' );

		if ( empty( $body ) ) {
			return false;
		}

		// Allow only common image mime types.
		$allowed_mimes = array(
			'image/jpeg',
			'image/png',
			'image/gif',
			'image/webp',
			'image/svg+xml',
		);

		if ( $content_type && ! in_array( $content_type, $allowed_mimes, true ) ) {
			return false;
		}

		// Determine filename.
		if ( empty( $filename ) ) {
			$path     = wp_parse_url( $image_url, PHP_URL_PATH );
			$filename = $path ? basename( $path ) : '';

			if ( empty( $filename ) ) {
				$filename = 'image-' . time() . '.png';
			}
		}

		// Upload the image to the media library.
		$upload = wp_upload_bits( $filename, null, $body );

		if ( ! empty( $upload['error'] ) ) {
			return false;
		}

		// Create the attachment post.
		$attachment = array(
			'post_mime_type' => $content_type ? $content_type : 'image/png',
			'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $upload['file'] );

		if ( is_wp_error( $attach_id ) || ! $attach_id ) {
			return false;
		}

		// Generate and store attachment metadata.
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}

	/**
	 * Get element ID from Elementor element.
	 *
	 * @param array $element Elementor element array.
	 * @return string Element ID.
	 */
	private function get_element_id( $element ) {
		if ( isset( $element['_id'] ) ) {
			return $element['_id'];
		} elseif ( isset( $element['id'] ) ) {
			return $element['id'];
		}
		return '';
	}

	/**
	 * Get widget configuration.
	 *
	 * @param string $widget_type Widget type.
	 * @return array|false Widget configuration or false if not found.
	 */
	private function get_widget_config( $widget_type ) {
		return isset( $this->widget_config[ $widget_type ] ) ? $this->widget_config[ $widget_type ] : false;
	}

	/**
	 * Check if widget type should use attachment (download and upload) or just URL.
	 *
	 * @param string $widget_type Widget type.
	 * @return bool True if should use attachment, false if only URL.
	 */
	private function should_use_attachment( $widget_type ) {
		$config = $this->get_widget_config( $widget_type );
		return $config ? $config['use_attachment'] : false;
	}

	/**
	 * Get default control ID for widget type.
	 *
	 * @param string $widget_type Widget type.
	 * @return string Default control ID.
	 */
	private function get_default_control_id( $widget_type ) {
		$config = $this->get_widget_config( $widget_type );
		return $config ? $config['default_control_id'] : '';
	}

	/**
	 * Validate control_id against element settings.
	 *
	 * @param array  $element         Elementor element.
	 * @param string $target_control_id Target control ID to validate.
	 * @param string $widget_type     Widget type.
	 * @return bool True if control is valid, false otherwise.
	 */
	private function validate_control_id( $element, $target_control_id, $widget_type ) {
		if ( empty( $target_control_id ) ) {
			return true; // No validation needed if control_id is empty.
		}

		// Get default control_id for widget type.
		$default_control_id = $this->get_default_control_id( $widget_type );

		// For widget types, check if control_id matches default.
		if ( ! empty( $default_control_id ) && $target_control_id === $default_control_id ) {
			return true;
		}

		// For container/section elements, check if control exists in settings or element type.
		if ( 'container' === $widget_type || ( isset( $element['elType'] ) && in_array( $element['elType'], array( 'section', 'column', 'container' ), true ) ) ) {
			// Check if control exists in settings.
			if ( isset( $element['settings'][ $target_control_id ] ) ) {
				return true;
			}
			// For background_image, allow if element is container/section.
			if ( 'background_image' === $target_control_id ) {
				return true;
			}
		}

		// Check if control exists in settings.
		if ( isset( $element['settings'][ $target_control_id ] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Update element image/control value.
	 *
	 * @param array  $element         Elementor element (by reference).
	 * @param string $control_id      Control ID to update.
	 * @param mixed  $value           Value to set (can be attachment ID or URL).
	 * @param bool   $use_attachment  Whether value is attachment ID (true) or URL (false).
	 * @return bool True if updated, false otherwise.
	 */
	private function update_element_control( array &$element, $control_id, $value, $use_attachment = false ) {
		if ( ! isset( $element['settings'] ) || ! is_array( $element['settings'] ) ) {
			$element['settings'] = array();
		}

		// Initialize control if it doesn't exist.
		if ( ! isset( $element['settings'][ $control_id ] ) || ! is_array( $element['settings'][ $control_id ] ) ) {
			$element['settings'][ $control_id ] = array();
		}

		if ( $use_attachment ) {
			// Value is attachment ID.
			$attachment_id = absint( $value );
			$image_url     = wp_get_attachment_image_url( $attachment_id, 'full' );

			$element['settings'][ $control_id ] = array(
				'id'  => $attachment_id,
				'url' => $image_url ? $image_url : '',
			);
		} else {
			// Value is URL.
			$image_url = esc_url_raw( $value );

			$element['settings'][ $control_id ]['url'] = $image_url;
			// Remove id if exists to force using URL instead of attachment.
			if ( isset( $element['settings'][ $control_id ]['id'] ) ) {
				unset( $element['settings'][ $control_id ]['id'] );
			}
		}

		return true;
	}

	/**
	 * Update container/section background image controls.
	 *
	 * @param array  $element    Elementor element (by reference).
	 * @param string $image_url   Image URL to set.
	 * @param string $control_id Target control ID (optional).
	 * @return bool True if updated, false otherwise.
	 */
	private function update_container_background( array &$element, $image_url, $control_id = '' ) {
		if ( ! isset( $element['settings'] ) || ! is_array( $element['settings'] ) ) {
			$element['settings'] = array();
		}

		$updated = false;

		// If specific control_id is provided, update only that control.
		if ( ! empty( $control_id ) && isset( $element['settings'][ $control_id ] ) ) {
			if ( is_array( $element['settings'][ $control_id ] ) ) {
				$element['settings'][ $control_id ]['url'] = esc_url_raw( $image_url );
				if ( isset( $element['settings'][ $control_id ]['id'] ) ) {
					unset( $element['settings'][ $control_id ]['id'] );
				}
				$updated = true;
			}
		} else {
			// Update background controls in priority order.
			$background_controls = array( 'background_overlay_image', 'background_image' );

			foreach ( $background_controls as $bg_control ) {
				if ( isset( $element['settings'][ $bg_control ] ) && is_array( $element['settings'][ $bg_control ] ) ) {
					$element['settings'][ $bg_control ]['url'] = esc_url_raw( $image_url );
					if ( isset( $element['settings'][ $bg_control ]['id'] ) ) {
						unset( $element['settings'][ $bg_control ]['id'] );
					}
					$updated = true;
					break; // Update first found.
				}
			}

			// If no background control found, create background_image.
			if ( ! $updated ) {
				$element['settings']['background_image'] = array(
					'url' => esc_url_raw( $image_url ),
				);
				$updated = true;
			}
		}

		return $updated;
	}

	/**
	 * General method to update element image based on widget type, data_id, and control_id.
	 *
	 * @param array  $elements          Elementor elements array (by reference).
	 * @param string $widget_type       Widget type to match.
	 * @param mixed  $image_value       Image value (attachment ID or URL).
	 * @param string $target_data_id    Target Elementor `_id` to match. If empty, all matching widgets are updated.
	 * @param string $target_control_id Target control ID to match. If empty, uses default control for widget type.
	 * @return bool True if at least one element was updated, false otherwise.
	 */
	public function update_element_image( array &$elements, $widget_type, $image_value, $target_data_id = '', $target_control_id = '' ) {
		$updated = false;
		$use_attachment = $this->should_use_attachment( $widget_type );

		// Get default control_id if not provided.
		if ( empty( $target_control_id ) ) {
			$target_control_id = $this->get_default_control_id( $widget_type );
		}

		foreach ( $elements as &$el ) {
			$current_id = $this->get_element_id( $el );
			$matches_id = ( empty( $target_data_id ) || $current_id === $target_data_id );

			// Check if element matches widget type.
			$matches_widget = false;
			if ( 'container' === $widget_type ) {
				// For container, check elType.
				$matches_widget = isset( $el['elType'] ) && in_array( $el['elType'], array( 'section', 'column', 'container' ), true );
			} else {
				// For widgets, check widgetType.
				$matches_widget = isset( $el['widgetType'] ) && $el['widgetType'] === $widget_type;
			}

			if ( $matches_id && $matches_widget ) {
				// Validate control_id.
				if ( ! $this->validate_control_id( $el, $target_control_id, $widget_type ) ) {
					// Continue recursively even if control doesn't match.
					if ( ! empty( $el['elements'] ) && is_array( $el['elements'] ) ) {
						if ( $this->update_element_image( $el['elements'], $widget_type, $image_value, $target_data_id, $target_control_id ) ) {
							$updated = true;
						}
					}
					continue;
				}

				// Update element based on widget type.
				if ( 'container' === $widget_type ) {
					$local_updated = $this->update_container_background( $el, $image_value, $target_control_id );
				} else {
					$local_updated = $this->update_element_control( $el, $target_control_id, $image_value, $use_attachment );
				}

				if ( $local_updated ) {
					$updated = true;
				}
			}

			// Recursively process nested elements.
			if ( ! empty( $el['elements'] ) && is_array( $el['elements'] ) ) {
				if ( $this->update_element_image( $el['elements'], $widget_type, $image_value, $target_data_id, $target_control_id ) ) {
					$updated = true;
				}
			}
		}

		return $updated;
	}

	/**
	 * Trigger Elementor CSS regeneration for a specific post.
	 *
	 * @param int $post_id The post ID to regenerate CSS for.
	 * @return void
	 */
	public function trigger_elementor_css_regeneration( $post_id ) {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		// Delete all CSS-related post meta to force regeneration.
		delete_post_meta( $post_id, '_elementor_css_status' );
		delete_post_meta( $post_id, '_elementor_post_css' );

		// Clear Elementor cache files.
		if ( isset( \Elementor\Plugin::$instance->files_manager ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		// Regenerate CSS immediately using Elementor's CSS Post class.
		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			try {
				$css_file = \Elementor\Core\Files\CSS\Post::create( $post_id );
				if ( $css_file && method_exists( $css_file, 'update' ) ) {
					$css_file->update();
				}
			} catch ( Exception $e ) {
				return;
			}
		}
	}

	/**
	 * Find post by ID or slug fallback.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $link    Optional link to extract slug from.
	 * @return WP_Post|false Post object or false if not found.
	 */
	private function find_post( $post_id, $link = '' ) {
		$post = get_post( $post_id );

		// If post found and correct type, return it.
		if ( $post && 'solace-sitebuilder' === $post->post_type ) {
			return $post;
		}

		// Try to find by slug from link.
		if ( ! empty( $link ) ) {
			$path = wp_parse_url( $link, PHP_URL_PATH );
			if ( $path ) {
				$slug     = basename( untrailingslashit( $path ) );
				$fallback = get_page_by_path( $slug, OBJECT, 'solace-sitebuilder' );
				if ( $fallback instanceof WP_Post ) {
					return $fallback;
				}
			}
		}

		return false;
	}

	/**
	 * Validate API item data.
	 *
	 * @param array $item API item data.
	 * @return array|false Validated data or false if invalid.
	 */
	private function validate_api_item( $item ) {
		// Check required fields.
		if ( empty( $item['id'] ) || empty( $item['widget_type'] ) || empty( $item['data_id'] ) ) {
			return false;
		}

		// Check image_url.
		$image_url = '';
		if ( ! empty( $item['image_url'] ) ) {
			$image_url = esc_url_raw( $item['image_url'] );
		} elseif ( ! empty( $item['logo_url'] ) ) {
			$image_url = esc_url_raw( $item['logo_url'] );
		}

		if ( empty( $image_url ) ) {
			return false;
		}

		return array(
			'post_id'     => absint( $item['id'] ),
			'widget_type' => sanitize_text_field( $item['widget_type'] ),
			'data_id'     => sanitize_text_field( $item['data_id'] ),
			'control_id'  => ! empty( $item['control_id'] ) ? sanitize_text_field( $item['control_id'] ) : '',
			'image_url'   => $image_url,
			'link'        => ! empty( $item['link'] ) ? esc_url_raw( $item['link'] ) : '',
		);
	}

	/**
	 * Process a single API item and update the post.
	 *
	 * @param array $item_data Validated API item data.
	 * @return array Result array with 'success', 'attachment_id', and 'error' keys.
	 */
	private function process_api_item( $item_data ) {
		$result = array(
			'success'      => false,
			'attachment_id' => 0,
			'error'        => '',
		);

		// Find post.
		$post = $this->find_post( $item_data['post_id'], $item_data['link'] );

		if ( ! $post || 'solace-sitebuilder' !== $post->post_type ) {
			$result['error'] = 'Post not found or not of type solace-sitebuilder.';
			return $result;
		}

		$post_id = $post->ID;

		// Get Elementor data.
		$elementor_data = get_post_meta( $post_id, '_elementor_data', true );

		if ( empty( $elementor_data ) ) {
			$result['error'] = 'No Elementor data (_elementor_data) found.';
			return $result;
		}

		$elements = json_decode( $elementor_data, true );

		if ( empty( $elements ) || ! is_array( $elements ) ) {
			$result['error'] = 'Failed to decode Elementor data JSON.';
			return $result;
		}

		// Check if widget type is supported.
		$widget_config = $this->get_widget_config( $item_data['widget_type'] );
		if ( ! $widget_config && 'container' !== $item_data['widget_type'] ) {
			$result['error'] = 'Unsupported widget_type: ' . $item_data['widget_type'];
			return $result;
		}

		// Determine if we need attachment or just URL.
		$use_attachment = $this->should_use_attachment( $item_data['widget_type'] );
		$image_value    = $item_data['image_url'];

		// Download and upload image if needed.
		if ( $use_attachment ) {
			$attachment_id = $this->download_and_upload_image( $item_data['image_url'] );

			if ( ! $attachment_id ) {
				$result['error'] = 'Failed to download or upload image.';
				return $result;
			}

			$image_value           = $attachment_id;
			$result['attachment_id'] = $attachment_id;
		}

		// Update element image.
		$updated = $this->update_element_image(
			$elements,
			$item_data['widget_type'],
			$image_value,
			$item_data['data_id'],
			$item_data['control_id']
		);

		// Fallback: try without data_id if update failed.
		if ( ! $updated && ! empty( $item_data['data_id'] ) ) {
			$updated = $this->update_element_image(
				$elements,
				$item_data['widget_type'],
				$image_value,
				'',
				$item_data['control_id']
			);
		}

		if ( ! $updated ) {
			$result['error'] = 'Widget found but update failed (no changes applied). Widget type, data_id, or control_id may not match.';
			return $result;
		}

		// Save updated Elementor data.
		$updated_data = wp_slash( wp_json_encode( $elements ) );
		update_post_meta( $post_id, '_elementor_data', $updated_data );

		// Trigger CSS regeneration.
		try {
			$this->trigger_elementor_css_regeneration( $post_id );
		} catch ( Exception $e ) {
			return;
		}

		$result['success'] = true;
		return $result;
	}

	/**
	 * Update images in `solace-sitebuilder` posts using a remote API.
	 *
	 * Expected API response per item:
	 * - id          : (int)  Post ID (solace-sitebuilder).
	 * - image_url   : (string) Image URL to use (preferred).
	 * - logo_url    : (string) Optional fallback for legacy APIs.
	 * - widget_type : (string) Widget type (e.g., 'solace-extra-site-logo', 'container', 'image').
	 * - data_id     : (string) Elementor `_id` of the element to update.
	 * - control_id  : (string) Control ID to match (e.g., 'background_image', 'image', 'solace_extra_site_logo').
	 * - link        : (string) Optional post link for slug fallback.
	 *
	 * @param string $api_url The API endpoint URL.
	 * @return array Results of the update process with 'success', 'failed', and 'skipped' keys.
	 */
	public function update_sitebuilder_images_from_api( $api_url = '' ) {
		$results = array(
			'success' => array(),
			'failed'  => array(),
			'skipped' => array(),
		);

		if ( empty( $api_url ) ) {
			$results['failed'][] = array(
				'error' => 'API URL is required.',
			);
			return $results;
		}

		// Fetch API data securely.
		$response = wp_remote_get(
			esc_url_raw( $api_url ),
			array(
				'timeout'   => 20,
				'sslverify' => true,
			)
		);

		if ( is_wp_error( $response ) ) {
			$results['failed'][] = array(
				'error' => 'Failed to fetch API: ' . $response->get_error_message(),
			);
			return $results;
		}

		$body     = wp_remote_retrieve_body( $response );
		$api_data = json_decode( $body, true );

		if ( empty( $api_data ) || ! is_array( $api_data ) ) {
			$results['failed'][] = array(
				'error' => 'Invalid API response or empty data.',
			);
			return $results;
		}

		// Process each API item.
		foreach ( $api_data as $item ) {
			// Validate item.
			$item_data = $this->validate_api_item( $item );

			if ( ! $item_data ) {
				$results['skipped'][] = array(
					'reason' => 'Missing required fields or invalid data.',
					'item'   => $item,
				);
				continue;
			}

			// Process item.
			$process_result = $this->process_api_item( $item_data );

			if ( $process_result['success'] ) {
				$results['success'][] = array(
					'post_id'       => $item_data['post_id'],
					'title'         => get_the_title( $item_data['post_id'] ),
					'attachment_id' => $process_result['attachment_id'],
					'image_url'     => $item_data['image_url'],
					'widget_type'   => $item_data['widget_type'],
					'data_id'       => $item_data['data_id'],
					'control_id'    => $item_data['control_id'],
				);
			} else {
				$results['failed'][] = array(
					'post_id'     => $item_data['post_id'],
					'title'       => get_the_title( $item_data['post_id'] ),
					'reason'      => $process_result['error'],
					'widget_type' => $item_data['widget_type'],
					'data_id'     => $item_data['data_id'],
					'control_id'  => $item_data['control_id'],
				);
			}
		}

		return $results;
	}

	/**
	 * Register a new widget type configuration.
	 *
	 * This allows extending the class to support new widget types dynamically.
	 *
	 * @param string $widget_type       Widget type identifier.
	 * @param string $default_control_id Default control ID for this widget type.
	 * @param bool   $use_attachment    Whether to download and upload image as attachment.
	 * @return void
	 */
	public function register_widget_type( $widget_type, $default_control_id, $use_attachment = true ) {
		$this->widget_config[ $widget_type ] = array(
			'default_control_id' => $default_control_id,
			'use_attachment'    => $use_attachment,
		);
	}
}
