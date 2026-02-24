<?php

namespace SolaceExtraAddons\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Solace_Extra_Site_Tagline extends Tag
{
	public function get_name()
	{
		return 'solace-extra-site-tagline';
	}

	public function get_title()
	{
		return esc_html__('Site Tagline', 'solace-extra' );
	}

	public function get_group()
	{
		return 'site';
	}

	public function get_categories()
	{
		return [TagsModule::TEXT_CATEGORY];
	}

	public function render()
	{
		echo wp_kses_post(get_bloginfo('description'));
	}
}
