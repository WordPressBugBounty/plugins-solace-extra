<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Global "Solace Custom CSS" control for all Elementor widgets.
 *
 * Adds a free-form CSS field to the Advanced tab of every widget, replacing
 * the need for Elementor Pro's Custom CSS. Supports the `selector` and
 * `{{WRAPPER}}` placeholders, both replaced with the widget's unique
 * `.elementor-element-*` selector at render time.
 */

if ( ! function_exists( 'solace_extra_register_custom_css_controls' ) ) {
	/**
	 * Register the Solace Custom CSS section on every Elementor widget.
	 *
	 * @param \Elementor\Controls_Stack $element Element instance.
	 * @param array                     $args    Section args.
	 */
	function solace_extra_register_custom_css_controls( $element, $args ) {
		if ( ! $element instanceof \Elementor\Widget_Base ) {
			return;
		}

		$element->start_controls_section(
			'solace_custom_css_section',
			[
				'label' => esc_html__( 'Solace Custom CSS', 'solace-extra' ),
				'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'solace_custom_css_help',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => '<div style="font-size:12px;line-height:1.7;color:#7a7a7a;padding:4px 0 8px;">'
					. esc_html__( 'Add custom CSS scoped to this widget. Use selector to target the widget wrapper.', 'solace-extra' )
					. '<br><code style="background:#f1f1f1;padding:2px 4px;border-radius:3px;">selector { border-radius: 12px; }</code>'
					. '</div>',
			]
		);

		$element->add_control(
			'solace_custom_css',
			[
				'label'       => esc_html__( 'Custom CSS', 'solace-extra' ),
				'type'        => \Elementor\Controls_Manager::CODE,
				'language'    => 'css',
				'rows'        => 10,
				'description' => esc_html__( 'Example: selector { border-radius: 12px; }', 'solace-extra' ),
				'placeholder' => "selector {\n    border-radius: 12px;\n}",
			]
		);

		$element->end_controls_section();
	}
}
add_action( 'elementor/element/common/section_custom_css_pro/after_section_end', 'solace_extra_register_custom_css_controls', 10, 2 );

if ( ! function_exists( 'solace_extra_render_custom_css' ) ) {
	/**
	 * Output the widget's custom CSS inline, scoped to the widget.
	 *
	 * @param string                 $content Widget content HTML.
	 * @param \Elementor\Widget_Base $widget  Widget instance.
	 * @return string
	 */
	function solace_extra_render_custom_css( $content, $widget ) {
		$custom_css = $widget->get_settings_for_display( 'solace_custom_css' );

		if ( empty( $custom_css ) || ! is_string( $custom_css ) ) {
			return $content;
		}

		$selector = $widget->get_unique_selector();
		$css      = str_replace(
			[ '{{WRAPPER}}', '{{wrapper}}', 'selector' ],
			$selector,
			trim( $custom_css )
		);

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Raw CSS authored by an administrator.
		return $content . '<style id="' . esc_attr( $widget->get_id() ) . '-solace-custom-css">' . $css . '</style>';
	}
}
add_filter( 'elementor/widget/render_content', 'solace_extra_render_custom_css', 10, 2 );
