<?php

namespace SolaceExtraAddons\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use SolaceExtraAddons\Inc\Helper\Solace_Extra_Addons_Helper;


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Solace_Extra_Archive_Title extends Tag
{
	public function get_name()
	{
		return 'solace-extra-archive-title';
	}

	public function get_title()
	{
		return esc_html__('Archive Title', 'solace-extra' );
	}

	public function get_group()
	{
		return 'archive';
	}

	public function get_categories()
	{
		return [TagsModule::TEXT_CATEGORY];
	}

	public function render()
	{
		$include_context = 'yes' === $this->get_settings('include_context');

		$title = Solace_Extra_Addons_Helper::solace_extra_get_page_title($include_context);

		echo wp_kses_post($title);
	}

	protected function register_controls()
	{
		$this->add_control(
			'include_context',
			[
				'label' => esc_html__('Include Context', 'solace-extra' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
	}
}
