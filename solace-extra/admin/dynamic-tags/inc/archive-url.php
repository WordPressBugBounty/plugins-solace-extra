<?php

namespace SolaceExtraAddons\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use SolaceExtraAddons\Inc\Helper\Solace_Extra_Addons_Helper;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Solace_Extra_Archive_URL extends Data_Tag
{

	public function get_name()
	{
		return 'solace-extra-archive-url';
	}

	public function get_group()
	{
		return 'archive';
	}

	public function get_categories()
	{
		return [TagsModule::URL_CATEGORY];
	}

	public function get_title()
	{
		return esc_html__('Archive URL', 'solace-extra' );
	}

	public function get_panel_template()
	{
		return ' ({{ url }})';
	}

	public function get_value(array $options = [])
	{
		return Solace_Extra_Addons_Helper::solace_extra_get_the_archive_url();
	}
}
