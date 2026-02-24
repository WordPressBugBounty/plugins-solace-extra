<?php

namespace SolaceExtraAddons\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Solace_Extra_Post_ID extends Tag
{

	public function get_name()
	{
		return 'solace-extra-post-id';
	}

	public function get_title()
	{
		return esc_html__('Post ID', 'solace-extra' );
	}

	public function get_group()
	{
		return 'post';
	}

	public function get_categories()
	{
		return [TagsModule::TEXT_CATEGORY];
	}

	public function render()
	{
		echo absint( get_the_ID() );
	}
}
