<?php

namespace SolaceExtraAddons\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Solace_Extra_Author_Info extends Tag
{

	public function get_name()
	{
		return 'solace-extra-author-info';
	}

	public function get_title()
	{
		return esc_html__('Author Info', 'solace-extra' );
	}

	public function get_group()
	{
		return 'author';
	}

	public function get_categories()
	{
		return [TagsModule::TEXT_CATEGORY];
	}

	public function render()
	{
		$key = $this->get_settings('key');

		if (empty($key)) {
			return;
		}

		$value = get_the_author_meta($key);

		echo wp_kses_post($value);
	}

	public function get_panel_template_setting_key()
	{
		return 'key';
	}

	protected function register_controls()
	{
		$this->add_control(
			'key',
			[
				'label' => esc_html__('Field', 'solace-extra' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'description',
				'options' => [
					'description' => esc_html__('Bio', 'solace-extra' ),
					'email' => esc_html__('Email', 'solace-extra' ),
					'url' => esc_html__('Website', 'solace-extra' ),
				],
			]
		);
	}
}
