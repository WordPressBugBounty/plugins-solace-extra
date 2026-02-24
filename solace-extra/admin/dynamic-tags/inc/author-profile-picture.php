<?php

namespace SolaceExtraAddons\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use SolaceExtraAddons\Inc\Helper\Solace_Extra_Addons_Helper;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Solace_Extra_Author_Profile_Picture extends Data_Tag
{

	public function get_name()
	{
		return 'solace-extra-author-profile-picture';
	}

	public function get_title()
	{
		return esc_html__('Author Profile Picture', 'solace-extra' );
	}

	public function get_group()
	{
		return 'author';
	}

	public function get_categories()
	{
		return [TagsModule::IMAGE_CATEGORY];
	}

	public function get_value(array $options = [])
	{
		Solace_Extra_Addons_Helper::solace_extra_set_global_authordata();

		return [
			'id' => '',
			'url' => get_avatar_url((int) get_the_author_meta('ID')),
		];
	}
}
