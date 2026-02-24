<?php

namespace SolaceExtraAddons\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Solace_Extra_Post_Date extends Tag
{
	public function get_name()
	{
		return 'solace-extra-post-date';
	}

	public function get_title()
	{
		return esc_html__('Post Date', 'solace-extra' );
	}

	public function get_group()
	{
		return 'post';
	}

	public function get_categories()
	{
		return [TagsModule::TEXT_CATEGORY];
	}

	protected function register_controls()
	{
		$this->add_control(
			'type',
			[
				'label' => esc_html__('Type', 'solace-extra' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'post_date_gmt' => esc_html__('Post Published', 'solace-extra' ),
					'post_modified_gmt' => esc_html__('Post Modified', 'solace-extra' ),
				],
				'default' => 'post_date_gmt',
			]
		);

		$this->add_control(
			'format',
			[
				'label' => esc_html__('Format', 'solace-extra' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__('Default', 'solace-extra' ),
					'F j, Y' => gmdate('F j, Y'),
					'Y-m-d' => gmdate('Y-m-d'),
					'm/d/Y' => gmdate('m/d/Y'),
					'd/m/Y' => gmdate('d/m/Y'),
					'human' => esc_html__('Human Readable', 'solace-extra' ),
					'custom' => esc_html__('Custom', 'solace-extra' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label' => esc_html__('Custom Format', 'solace-extra' ),
				'default' => '',
				'description' => sprintf('<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', esc_html__('Documentation on date and time formatting', 'solace-extra' )),
				'condition' => [
					'format' => 'custom',
				],
			]
		);
	}

	public function render()
	{
		$date_type = $this->get_settings('type');
		$format = $this->get_settings('format');

		if ('human' === $format) {
			/* translators: %s: Human readable date/time. */
			$value = sprintf(esc_html__('%s ago', 'solace-extra' ), human_time_diff(strtotime(get_post()->{$date_type})));
		} else {
			switch ($format) {
				case 'default':
					$date_format = '';
					break;
				case 'custom':
					$date_format = $this->get_settings('custom_format');
					break;
				default:
					$date_format = $format;
					break;
			}

			if ('post_date_gmt' === $date_type) {
				$value = get_the_date($date_format);
			} else {
				$value = get_the_modified_date($date_format);
			}
		}
		echo wp_kses_post($value);
	}
}
