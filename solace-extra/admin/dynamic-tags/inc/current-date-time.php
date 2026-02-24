<?php

namespace SolaceExtraAddons\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Solace_Extra_Current_Date_Time extends Tag
{

	public function get_name()
	{
		return 'solace-extra-current-date-time';
	}

	public function get_title()
	{
		return esc_html__('Current Date Time', 'solace-extra' );
	}

	public function get_group()
	{
		return 'site';
	}

	public function get_categories()
	{
		return [TagsModule::TEXT_CATEGORY];
	}

	protected function register_controls()
	{
		$this->add_control(
			'date_format',
			[
				'label' => esc_html__('Date Format', 'solace-extra' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__('Default', 'solace-extra' ),
					'' => esc_html__('None', 'solace-extra' ),
					'F j, Y' => gmdate('F j, Y'),
					'Y-m-d' => gmdate('Y-m-d'),
					'm/d/Y' => gmdate('m/d/Y'),
					'd/m/Y' => gmdate('d/m/Y'),
					'custom' => esc_html__('Custom', 'solace-extra' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'time_format',
			[
				'label' => esc_html__('Time Format', 'solace-extra' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__('Default', 'solace-extra' ),
					'' => esc_html__('None', 'solace-extra' ),
					'g:i a' => gmdate('g:i a'),
					'g:i A' => gmdate('g:i A'),
					'H:i' => gmdate('H:i'),
				],
				'default' => 'default',
				'condition' => [
					'date_format!' => 'custom',
				],
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label' => esc_html__('Custom Format', 'solace-extra' ),
				'default' => get_option('date_format') . ' ' . get_option('time_format'),
				'description' => sprintf('<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', esc_html__('Documentation on date and time formatting', 'solace-extra' )),
				'condition' => [
					'date_format' => 'custom',
				],
			]
		);
	}

	public function render()
	{
		$settings = $this->get_settings();

		if ('custom' === $settings['date_format']) {
			$format = $settings['custom_format'];
		} else {
			$date_format = $settings['date_format'];
			$time_format = $settings['time_format'];
			$format = '';

			if ('default' === $date_format) {
				$date_format = get_option('date_format');
			}

			if ('default' === $time_format) {
				$time_format = get_option('time_format');
			}

			if ($date_format) {
				$format = $date_format;
				$has_date = true;
			} else {
				$has_date = false;
			}

			if ($time_format) {
				if ($has_date) {
					$format .= ' ';
				}
				$format .= $time_format;
			}
		}

		$value = date_i18n($format);

		echo wp_kses_post($value);
	}
}
