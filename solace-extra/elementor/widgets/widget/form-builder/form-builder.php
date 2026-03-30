<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Solace_Form_Builder extends \Elementor\Widget_Base {

	public function get_name() {
		return 'solace_form_builder';
	}

	public function get_title() {
		return esc_html__( 'Bentuk Builder', 'solace-extra' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_fields',
			[
				'label' => esc_html__( 'Fields', 'solace-extra' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'field_label',
			[
				'label'   => esc_html__( 'Label', 'solace-extra' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Email',
			]
		);

		$repeater->add_responsive_control(
			'field_column_width',
			[
				'label'      => esc_html__( 'Width (%)', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default'    => [ 'size' => 60 ],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
			]
		);

		$this->add_control(
			'fields',
			[
				'type'    => \Elementor\Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [ [] ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'solace-extra' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label'      => esc_html__( 'Button Width (%)', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default'    => [ 'size' => 40 ],
				'selectors'  => [
					'{{WRAPPER}} .solaceform-button-column.inline' =>
						'width: {{SIZE}}%;',
					'{{WRAPPER}} .solaceform-button-column.newline .submit-button' =>
						'width: {{SIZE}}%;',
				],
			]
		);

		$this->add_responsive_control(
			'button_position',
			[
				'label'   => esc_html__( 'Button Position', 'solace-extra' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'none' => [
						'title' => esc_html__( 'Inline', 'solace-extra' ),
						'icon'  => 'eicon-close',
					],
					'flex-start' => [
						'title' => esc_html__( 'New Line: Left', 'solace-extra' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'New Line: Center', 'solace-extra' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'New Line: Right', 'solace-extra' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default' => 'none',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => esc_html__( 'Field', 'solace-extra' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'field_padding',
			[
				'label'      => esc_html__( 'Padding', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .solaceform-input' =>
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_margin',
			[
				'label'      => esc_html__( 'Margin', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .solaceform-input' =>
						'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'solace-extra' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .submit-button' =>
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => esc_html__( 'Margin', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .submit-button' =>
						'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings  = $this->get_settings_for_display();
		$is_inline = ( $settings['button_position'] === 'none' );
		?>

		<form class="solaceform-container">
			<?php foreach ( $settings['fields'] as $i => $item ) :
				$key = $this->get_repeater_setting_key( 'field_label', 'fields', $i );
				$this->add_render_attribute(
					$key,
					'class',
					[
						'solaceform-field-column',
						'elementor-repeater-item-' . $item['_id'],
					]
				);
			?>
				<div <?php 
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
				echo $this->get_render_attribute_string( $key ); ?>>
					<input
						type="email"
						class="solaceform-input"
						placeholder="<?php echo esc_attr( $item['field_label'] ); ?>">
				</div>
			<?php endforeach; ?>

			<div
				class="solaceform-button-column <?php echo $is_inline ? 'inline' : 'newline'; ?>"
				style="<?php echo $is_inline ? '' : 'width:100%;display:flex;justify-content:' . esc_attr( $settings['button_position'] ) . ';'; ?>">
				<button type="submit" class="submit-button">
					<?php echo esc_html__( 'Submit', 'solace-extra' ); ?>
				</button>
			</div>
		</form>

		<style>
			.solaceform-container {
				display: flex;
				flex-wrap: wrap;
				width: 100%;
			}

			.solaceform-field-column,
			.solaceform-button-column,
			.solaceform-input,
			.submit-button {
				box-sizing: border-box;
			}

			.solaceform-input,
			.submit-button {
				width: 100%;
				white-space: nowrap;
			}
		</style>

		<?php
	}
	
}