<?php
/**
 * @since   1.0.0
 *
 * @package Solace Form Builder
 */

namespace Solaceform;

defined( 'ABSPATH' ) || exit;

use Elementor\Widget_Base;
use Elementor\Icons_Manager;

abstract class SolaceFormBase extends WidgetBase {

	protected function input( array $params ) {
		$generated_field_name = 'field_' . '_' . wp_rand(10, 99999) . '_' . wp_rand(10, 9999);
		$placeholder    = $params['placeholder'] ? $params['placeholder'] : '';
		$value          = $params['value'] ? 'value=' . $params['value'] . '' : '';
		$required       = $params['required'] ? 'required' : '';
		$id             = $params['id'] ? 'id=' . $params['id'] . '' : '';
		$class          = $params['class'] ? $params['class'] : '';
		$multiple_files = $params['multiple_files'] ? 'multiple' : '';
		$file_types     = $params['file_types'] ? 'accept=' . $params['file_types'] . '' : 'accept=""';
		$name           = $params['name'] ? $params['name'] : $generated_field_name;
		$type           = $params['type'] ? $params['type'] : '';
		$label          = $params['label'] ? $params['label'] : '';
		$is_mark        = $params['is_mark'] ? $params['is_mark'] : '';
		$is_label       = $params['is_label'] ? $params['is_label'] : '';

		if ( $is_label ) {
			$this->label( $label );
		}

		if ( $required && $is_mark) {
			$this->mark();
		}
		?>
			<input
				<?php if ( 'file' === $type ) { ?>
					<?php echo esc_attr( $file_types ); ?>
					name="<?php echo esc_attr( 'solaceFile[]' ); ?>"
				<?php } else { ?>
						name="<?php echo esc_attr( $name ); ?>"
				<?php } ?>
				<?php if ( $label ) { ?>
					get_label="<?php echo esc_attr( $label ); ?>"
				<?php } ?>
				type="<?php echo esc_attr( $type ); ?>"
				class="<?php echo esc_attr( $class ); ?> solaceform-style-field"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
		<?php echo esc_attr( $value ); ?>
		<?php echo esc_attr( $required ); ?>
		<?php echo esc_attr( $id ); ?>
		<?php echo esc_attr( $multiple_files ); ?>
		<?php echo esc_attr( $file_types ); ?>
			>
		<?php
		if ( 'file' === $type ) {
			if ( ! empty( $params['file_types'] ) ) {
				echo '<span class="info-file-types" style="
					display: block;
					font-size: 15px;
				">';
					esc_html_e( 'Only the following formats are allowed for upload: ', 'solace-extra' );
					echo esc_html( str_replace( ',', ', ', $params['file_types'] ) );
				echo "</span>";
			}
		}
	}

	protected function textarea( array $params ) {
		$generated_field_name = 'field_' . '_' . wp_rand(10, 99999) . '_' . wp_rand(10, 9999);
		$placeholder    = $params['placeholder'] ? $params['placeholder']  : '';		
		$required    = $params['required'] ? 'required' : '';
		$id          = $params['id'] ? 'id=' . $params['id'] . '' : '';
		$class       = $params['class'] ? $params['class'] : '';
		$name        = $params['name'] ? $params['name'] : $generated_field_name;
		$label       = $params['label'] ? $params['label'] : '';
		$rows        = $params['rows'] ? $params['rows'] : '';
		$is_mark     = $params['is_mark'] ? $params['is_mark'] : '';
		$is_label    = $params['is_label'] ? $params['is_label'] : '';

		if ( $is_label ) {
			$this->label( $label );
		}

		if ( $required && $is_mark) {
			$this->mark();
		}
		?>
			<textarea
				name="<?php echo esc_attr( $name ); ?>"
				class="<?php echo esc_attr( $class ); ?> solaceform-style-field"
				rows="<?php echo esc_attr( $rows ); ?>"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
			<?php if ( $label ) { ?>
				get_label="<?php echo esc_attr( $label ); ?>"
			<?php } ?>
		<?php echo esc_attr( $id ); ?>
		<?php echo esc_attr( $required ); ?>
			></textarea>
		<?php
	}

	protected function multi( array $params ) {
		$generated_field_name = 'field_' . '_' . wp_rand(10, 99999) . '_' . wp_rand(10, 9999);
		$options     = $params['options'] ? $params['options'] : '';
		$inline_list = $params['inline_list'] ? $params['inline_list'] : '';
		$required    = $params['required'] ? 'required' : '';
		$name        = $params['name'] ? $params['name'] : $generated_field_name;
		$type        = $params['type'] ? $params['type'] : '';
		$label       = $params['label'] ? $params['label'] : '';
		$is_mark     = $params['is_mark'] ? $params['is_mark'] : '';
		$is_label    = $params['is_label'] ? $params['is_label'] : '';

		$items = preg_split( '/\r\n|\r|\n/', $options );

		if ( $is_label ) {
			$this->label( $label );
		}

		if ( $required && $is_mark) {
			$this->mark();
		}

		if ( isset( $items ) ) {
			?>
				<div class="solaceform-multi-fields <?php echo $inline_list ? 'inline' : 'block'; ?>">
			<?php
			if ( $type === 'select' ) {
				?>
							<select name="<?php echo esc_attr( $name ); ?>" class="solaceform-style-field" <?php echo $label ? 'get_label="' . esc_attr( $label ) . '"' : 'get_label=""'; ?>>
				<?php
			}

			if ( $type === 'radio' ) {
				?>
							<div class="solaceform-radio-warp">
				<?php
			}

			if ( $type === 'checkbox' ) {
				?>
							<div class="solaceform-checkbox-warp">
				<?php
			}

			?>
			<?php
			foreach ( $items as $item ) {
				if ( $type === 'radio' ) {
					?>
							<div class="box">
								<input type="radio" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $item ); ?>" <?php echo $label ? 'get_label="' . esc_attr( $label ) . '"' : 'get_label=""'; ?> <?php echo esc_attr( $required ); ?>>
								<span><?php echo esc_html( $item ); ?></span>
							</div>
						 <?php
				}

				if ( $type === 'checkbox' ) {
					?>
							<div class="box">
								<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $item ); ?>" <?php echo $label ? 'get_label="' . esc_attr( $label ) . '"' : 'get_label=""'; ?> <?php echo $required ? 'data-required="true" class="required-checkbox"' : ''; ?> <?php echo esc_attr( $required ); ?>>
								<span><?php echo esc_attr( $item ); ?></span>
							</div>
						<?php
				}

				if ( $type === 'select' ) {
					?>
								<option value="<?php echo esc_attr( $item ); ?>"><?php echo esc_attr( $item ); ?></option>
					  <?php
				}
			}
			?>
			<?php
			if ( $type === 'select' ) {
				?>
							</select>
				<?php
			}

			if ( $type === 'radio' ) {
				?>
							</div>
				<?php
			}

			if ( $type === 'checkbox' ) {
				?>
							</div>
				<?php
			}
			?>
				</div>
			<?php
		}
	}

	protected function acceptance( array $params ) {
		$generated_field_name = 'field_' . '_' . wp_rand(10, 99999) . '_' . wp_rand(10, 9999);
		$name        = $params['name'] ? $params['name'] : $generated_field_name;
		$acceptance  = $params['field_acceptance'] ? $params['field_acceptance'] : '';
		$label       = $params['label'] ? $params['label'] : '';
		$is_mark     = $params['is_mark'] ? $params['is_mark'] : '';
		$field_acceptance    = $params['field_acceptance'] ? $params['field_acceptance'] : '';
		$required       = $params['required'] ? $params['required'] : '';
		$field_checked_by_default    = $params['field_checked_by_default'] ? $params['field_checked_by_default'] : '';

		?>
			<div class="solaceform-multi-fields">
				<div class="solaceform-checkbox-warp">
					<div class="box">
						<label for="acceptance" style="display: block;">
							<?php echo esc_attr( $label ); ?>
							<?php 
							if ( $required && $is_mark) {
								$this->mark();
							}
							?>
						</label>
						<input id="acceptance" type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="<?php echo '✅ ' . esc_attr( $field_acceptance ); ?>" <?php echo $label ? 'get_label="' . esc_attr( $label ) . '"' : 'get_label=""'; ?> <?php echo esc_attr( $field_checked_by_default ? 'checked' : '' ); ?> <?php echo esc_attr( $required ? 'required' : '' ); ?> />
						<span style="margin-left: 3px;"><?php echo esc_attr( $acceptance ); ?></span>
					</div>
				</div>
			</div>
			<?php
	}	

	protected function html( string $html, string $label, string $is_label ) {

		if ( $is_label ) {
			$this->label( $label );
		}

		?>
			<div class="solaceform-field-html">
		<?php

		if ( $html ) {
			echo wp_kses_post( do_shortcode( $html ) );
		}

		?>
			</div>
		<?php
	}

	protected function hidden( string $value, string $name, string $id ) {

		$id    = $id ? 'id=' . $id . '' : '';
		$value = $value ? 'value=' . $value . '' : '';
		$name  = $name ? 'name=' . $name . '' : '';

		?>
			<input
				type="hidden"
		<?php echo esc_attr( $id ); ?>
		<?php echo esc_attr( $value ); ?>
		<?php echo esc_attr( $name ); ?>
			>
		<?php
	}

	protected function reCAPTCHA( string $site_key ) {
		if ( $site_key ) {
			// Enqueue the reCAPTCHA script from Google
			wp_enqueue_script(
				'google-recaptcha',
				'https://www.google.com/recaptcha/api.js',
				array(),  // Dependencies
				'1.1.2',     // Version
				true      // Load in footer
			);
			
			// Output the reCAPTCHA HTML
			echo '<div class="g-recaptcha" data-sitekey="' . esc_attr( $site_key ) . '"></div>';
		}
	}
	

	protected function button(
		array $settings,
		string $text,
		array $icon,
		string $position,
		string $id,
		string $class
	) {
		$settings = $this->get_settings_for_display();
        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';

		$id = $id ? 'id="' . $id . '"' : '';
		
		// Get responsive column width values
		$column_width = $settings['button_column_width'] ?? $class;
		$column_width_tablet = $settings['button_column_width_tablet'] ?? '';
		$column_width_mobile = $settings['button_column_width_mobile'] ?? '';
		
		// If tablet/mobile not set, use desktop value as fallback (Elementor behavior)
		if ( empty( $column_width_tablet ) ) {
			$column_width_tablet = $column_width;
		}
		if ( empty( $column_width_mobile ) ) {
			$column_width_mobile = $column_width_tablet ?: $column_width;
		}
		
		// Build responsive width classes
		$width_classes = [];
		if ( ! empty( $column_width ) ) {
			$width_classes[] = 'efb-field-width-' . $column_width;
		}
		if ( ! empty( $column_width_tablet ) ) {
			$width_classes[] = 'efb-field-width-tablet-' . $column_width_tablet;
		}
		if ( ! empty( $column_width_mobile ) ) {
			$width_classes[] = 'efb-field-width-mobile-' . $column_width_mobile;
		}
		$class = ! empty( $width_classes ) ? implode( ' ', $width_classes ) : '';
		
		// Set display style based on button width
		$display_style = ($class && strpos($class, 'efb-field-width-auto') === false) ? 'display: inline-block;' : 'display: block;';
		
		// Check if stretch is selected for button alignment (desktop, tablet, mobile)
		$button_align = $settings['button_align'] ?? '';
		$button_align_tablet = $settings['button_align_tablet'] ?? '';
		$button_align_mobile = $settings['button_align_mobile'] ?? '';
		
		// If tablet/mobile not set, use desktop value as fallback (Elementor behavior)
		if ( empty( $button_align_tablet ) ) {
			$button_align_tablet = $button_align;
		}
		if ( empty( $button_align_mobile ) ) {
			$button_align_mobile = $button_align_tablet ?: $button_align;
		}
		
		$is_stretch = 'stretch' === $button_align;
		$is_stretch_tablet = 'stretch' === $button_align_tablet;
		$is_stretch_mobile = 'stretch' === $button_align_mobile;
		
		// Build stretch classes for responsive breakpoints
		// Add all classes, CSS media queries will handle which one is active
		$stretch_classes = [];
		if ( $is_stretch ) {
			$stretch_classes[] = 'stretch-desktop';
		}
		if ( $is_stretch_tablet ) {
			$stretch_classes[] = 'stretch-tablet';
		}
		if ( $is_stretch_mobile ) {
			$stretch_classes[] = 'stretch-mobile';
		}
		$stretch_class = ! empty( $stretch_classes ) ? ' ' . implode( ' ', $stretch_classes ) : '';
		
		?>
			<div style="<?php echo esc_attr( $display_style ); ?>" class="solaceform-form-button-wrap <?php echo esc_attr( $class ); ?>" <?php echo esc_attr( $id ); ?>>
				<div class="box-button">
					<?php
					if (
						isset( $settings['button_align'] ) || 
						isset( $settings['button_align_tablet'] ) || 
						isset( $settings['button_align_mobile'] )
					) {
						$alignments = [
							'desktop' => $settings['button_align'] ?? '',
							'tablet' => $settings['button_align_tablet'] ?? '',
							'mobile' => $settings['button_align_mobile'] ?? ''
						];

						$classes = array_map(
							fn($key, $value) => 'justify' === $value ? "$key-full-width" : '',
							array_keys($alignments),
							$alignments
						);

						$button_classes = implode(' ', array_filter($classes));
						?>
						<button class="<?php echo esc_attr( $hover_class ); ?> elementor-button solaceform-form-button <?php echo esc_attr($button_classes . $stretch_class); ?>" type="submit">
					<?php } else { ?>
						<button class="<?php echo esc_attr( $hover_class ); ?> elementor-button solaceform-form-button<?php echo esc_attr( $stretch_class ); ?>" type="submit">
					<?php } ?>
						<?php if ( $position === 'left' ) : ?>
							<?php Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ); ?>
						<?php endif; ?>
						<?php echo esc_html( $text ); ?>
						<?php if ( $position === 'right' ) : ?>
							<?php Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ); ?>
						<?php endif; ?>
						<div class="solace-spinner"></div>
					</button>
				</div>
				<div class="solaceform-form-msg" style="display: none;"><span></span></div>
			</div>
		<?php
	}

	public function label( string $label ) {

		if ( $label ) {
			?>
				<label><?php echo esc_attr( $label ); ?></label>
			<?php
		}
	}

	public function mark() {
		?>
			<span style="color: red; ">*</span>
		<?php
	}
}
