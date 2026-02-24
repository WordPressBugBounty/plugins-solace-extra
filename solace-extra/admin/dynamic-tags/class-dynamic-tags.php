<?php

// namespace SolaceExtraAddons\Modules\DynamicTags;

// use SolaceExtraAddons\Inc\Helper\Solace_Extra_Addons_Helper;

// class Solace_Extra_Extension_Dynamic_Tags
// {
// 	private static $_instance = null;

// 	public function __construct()
// 	{
// 		add_action('elementor/dynamic_tags/register', [$this, 'solace_extra_register_dynamic_tags']);
// 	}

// 	/**
// 	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags
// 	 */
// 	public function solace_extra_register_dynamic_tags($dynamic_tags)
// 	{

// 		$tags = array(
// 			'solace-extra-archive-description' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'archive-description.php',
// 				'class' => 'Tags\Solace_Extra_Archive_Description',
// 				'group' => 'archive',
// 				'title' => 'Archive',
// 			),
// 			'solace-extra-archive-meta' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'archive-meta.php',
// 				'class' => 'Tags\Solace_Extra_Archive_Meta',
// 				'group' => 'archive',
// 				'title' => 'Archive',
// 			),
// 			'solace-extra-archive-title' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'archive-title.php',
// 				'class' => 'Tags\Solace_Extra_Archive_Title',
// 				'group' => 'archive',
// 				'title' => 'Archive',
// 			),
// 			'solace-extra-archive-url' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'archive-url.php',
// 				'class' => 'Tags\Solace_Extra_Archive_URL',
// 				'group' => 'archive',
// 				'title' => 'Archive',
// 			),
// 			'solace-extra-author-info' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'author-info.php',
// 				'class' => 'Tags\Solace_Extra_Author_Info',
// 				'group' => 'author',
// 				'title' => 'Author',
// 			),
// 			'solace-extra-author-meta' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'author-meta.php',
// 				'class' => 'Tags\Solace_Extra_Author_Meta',
// 				'group' => 'author',
// 				'title' => 'Author',
// 			),
// 			'solace-extra-author-name' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'author-name.php',
// 				'class' => 'Tags\Solace_Extra_Author_Name',
// 				'group' => 'author',
// 				'title' => 'Author',
// 			),
// 			'solace-extra-author-profile-picture' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'author-profile-picture.php',
// 				'class' => 'Tags\Solace_Extra_Author_Profile_Picture',
// 				'group' => 'author',
// 				'title' => 'Author',
// 			),
// 			'solace-extra-author-url' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'author-url.php',
// 				'class' => 'Tags\Solace_Extra_Author_URL',
// 				'group' => 'author',
// 				'title' => 'Author',
// 			),
// 			'solace-extra-comments-number' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'comments-number.php',
// 				'class' => 'Tags\Solace_Extra_Comments_Number',
// 				'group' => 'comments',
// 				'title' => 'Comments',
// 			),
// 			'solace-extra-comments-url' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'comments-url.php',
// 				'class' => 'Tags\Solace_Extra_Comments_URL',
// 				'group' => 'comments',
// 				'title' => 'Comments',
// 			),
// 			'solace-extra-contact-url' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'contact-url.php',
// 				'class' => 'Tags\Solace_Extra_Contact_URL',
// 				'group' => 'action',
// 				'title' => 'Action',
// 			),
// 			'solace-extra-current-date-time' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'current-date-time.php',
// 				'class' => 'Tags\Solace_Extra_Current_Date_Time',
// 				'group' => 'site',
// 				'title' => 'Site',
// 			),
// 			'solace-extra-featured-image-data' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'featured-image-data.php',
// 				'class' => 'Tags\Solace_Extra_Featured_Image_Data',
// 				'group' => 'media',
// 				'title' => 'Media',
// 			),
// 			'solace-extra-page-title' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'page-title.php',
// 				'class' => 'Tags\Solace_Extra_Page_Title',
// 				'group' => 'site',
// 				'title' => 'Site',
// 			),
// 			'solace-extra-post-custom-field' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'post-custom-field.php',
// 				'class' => 'Tags\Post_Custom_Field',
// 				'group' => 'post',
// 				'title' => 'Post',
// 			),
// 			'solace-extra-pages-url' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'pages-url.php',
// 				'class' => 'Tags\Solace_Extra_Pages_Url',
// 				'group' => 'URL',
// 				'title' => 'URL',
// 			),
// 			'solace-extra-cats-url' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'taxonomies-url.php',
// 				'class' => 'Tags\Solace_Extra_Taxonomies_Url',
// 				'group' => 'URL',
// 				'title' => 'URL',
// 			),
// 			'solace-extra-post-date' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'post-date.php',
// 				'class' => 'Tags\Solace_Extra_Post_Date',
// 				'group' => 'post',
// 				'title' => 'Post',
// 			),
// 			'solace-extra-post-excerpt' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'post-excerpt.php',
// 				'class' => 'Tags\Solace_Extra_Post_Excerpt',
// 				'group' => 'post',
// 				'title' => 'Post',
// 			),
// 			'solace-extra-post-featured-image' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'post-featured-image.php',
// 				'class' => 'Tags\Solace_Extra_Post_Featured_Image',
// 				'group' => 'post',
// 				'title' => 'Post',
// 			),
// 			'solace-extra-post-gallery' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'post-gallery.php',
// 				'class' => 'Tags\Solace_Extra_Post_Gallery',
// 				'group' => 'post',
// 				'title' => 'Post',
// 			),
// 			'solace-extra-post-id' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'post-id.php',
// 				'class' => 'Tags\Solace_Extra_Post_ID',
// 				'group' => 'post',
// 				'title' => 'Post',
// 			),
// 			'solace-extra-post-terms' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'post-terms.php',
// 				'class' => 'Tags\Solace_Extra_Post_Terms',
// 				'group' => 'post',
// 				'title' => 'Post',
// 			),
// 			'solace-extra-post-time' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'post-time.php',
// 				'class' => 'Tags\Solace_Extra_Post_Time',
// 				'group' => 'post',
// 				'title' => 'Post',
// 			),
// 			'solace-extra-post-title' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'post-title.php',
// 				'class' => 'Tags\Solace_Extra_Post_Title',
// 				'group' => 'post',
// 				'title' => 'Post',
// 			),
// 			'solace-extra-post-url' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'post-url.php',
// 				'class' => 'Tags\Solace_Extra_Post_URL',
// 				'group' => 'post',
// 				'title' => 'Post',
// 			),
// 			'solace-extra-request-parameter' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'request-parameter.php',
// 				'class' => 'Tags\Solace_Extra_Request_Parameter',
// 				'group' => 'site',
// 				'title' => 'Site',
// 			),
// 			'solace-extra-shortcode' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'shortcode.php',
// 				'class' => 'Tags\Solace_Extra_Shortcode',
// 				'group' => 'site',
// 				'title' => 'Site',
// 			),
// 			'solace-extra-site-logo' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'site-logo.php',
// 				'class' => 'Tags\Solace_Extra_Site_Logo',
// 				'group' => 'site',
// 				'title' => 'Site',
// 			),
// 			'solace-extra-site-tagline' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'site-tagline.php',
// 				'class' => 'Tags\Solace_Extra_Site_Tagline',
// 				'group' => 'site',
// 				'title' => 'Site',
// 			),
// 			'solace-extra-site-title' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'site-title.php',
// 				'class' => 'Tags\Solace_Extra_Site_Title',
// 				'group' => 'site',
// 				'title' => 'Site',
// 			),
// 			'solace-extra-site-url' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'site-url.php',
// 				'class' => 'Tags\Site_URL',
// 				'group' => 'site',
// 				'title' => 'Site',
// 			),
// 			'solace-extra-user-info' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'user-info.php',
// 				'class' => 'Tags\Solace_Extra_User_Info',
// 				'group' => 'site',
// 				'title' => 'Site',
// 			),
// 			'solace-extra-product-title' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'product-title.php',
// 				'class' => 'Tags\Solace_Extra_Product_Title',
// 				'group' => 'single-products',
// 				'title' => esc_html__( 'Single Products', 'solace-extra'),
// 			),	
// 			'solace-extra-product-excerpt' => array(
// 				'file'  => SOLACE_EXTRA_DYNAMIC_TAGS_PATH_INC . 'product-excerpt.php',
// 				'class' => 'Tags\Solace_Extra_Product_Excerpt',
// 				'group' => 'single-products',
// 				'title' => esc_html__( 'Single Products', 'solace-extra'),
// 			),	
// 		);

// 		foreach ($tags as $tags_type => $tags_info) {
// 			if (!empty($tags_info['file']) && !empty($tags_info['class'])) {
// 				// In our Dynamic Tag we use a group named request-variables so we need
// 				// To register that group as well before the tag
// 				Solace_Extra_Addons_Helper::solace_extra_elementor()->dynamic_tags->register_group($tags_info['group'], [
// 					'title' => $tags_info['title']
// 				]);

// 				include_once($tags_info['file']);

// 				if (class_exists($tags_info['class'])) {
// 					$class_name = $tags_info['class'];
// 				} elseif (class_exists(__NAMESPACE__ . '\\' . $tags_info['class'])) {
// 					$class_name = __NAMESPACE__ . '\\' . $tags_info['class'];
// 				}

// 				$dynamic_tags->register(new $class_name);
// 			}
// 		}
// 	}


// 	public static function get_instance()
// 	{
// 		if (is_null(self::$_instance)) {
// 			self::$_instance = new self();
// 		}
// 		return self::$_instance;
// 	}
// }

// Solace_Extra_Extension_Dynamic_Tags::get_instance();
