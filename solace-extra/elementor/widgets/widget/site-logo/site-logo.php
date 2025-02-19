<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor List Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Solace_Extra_Site_Logo extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'solace-extra-site-logo';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Solace Site Logo', 'solace-extra' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-site-logo';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'solace-extra' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'solace', 'extra', 'site', 'logo' ];
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	// public function get_custom_help_url() {
	// 	return 'https://developers.elementor.com/docs/widgets/';
	// }

	/**
	 * Register list widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$default_logo_id = get_theme_mod( 'custom_logo', get_option('solace_uploaded_image_id') );
		$default_logo_url = $default_logo_id ? wp_get_attachment_image_url( $default_logo_id, 'full' ) : \Elementor\Utils::get_placeholder_image_src();		

		$this->start_controls_section(
			'solace_extra_content_section',
			[
				'label' => esc_html__( 'Site Logo', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'solace_extra_site_logo',
			[
				'label' => esc_html__( 'Site Logo', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'id' => $default_logo_id,
					'url' => $default_logo_url,
				],
			]
		);

		$this->add_control(
			'solace_extra_image_resolution',
			[
				'label' => esc_html__( 'Image Resolution', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'thumbnail' => 'Thumbnail - 150 x 150',
					'medium' => 'Medium - 300 x 300',
					'medium_large' => 'Medium Large - 768 x 0',
					'large' => 'Large - 1024 x 1024',
					'full' => 'Full Size',
				],
				'default' => 'full',
			]
		);

		$this->add_control(
			'solace_extra_caption',
			[
				'label' => esc_html__( 'Caption', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'solace-extra' ),
					'attachment' => esc_html__( 'Attachment Caption', 'solace-extra' ),
				],
				'default' => 'none',
			]
		);

		$this->add_control(
			'solace_extra_custom_link',
			[
				'label' => esc_html__( 'Custom Link', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'Paste URL or type', 'solace-extra' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'show_external' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'solace_extra_style_content_section',
			[
				'label' => esc_html__( 'Image', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'solace_extra_alignment',
			[
				'label' => esc_html__( 'Alignment', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'solace-extra' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'solace-extra' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'solace-extra' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-site-logo' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'solace_extra_width',
			[
				'label' => esc_html__( 'Width', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-site-logo img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'solace_extra_max_width',
			[
				'label' => esc_html__( 'Max Width', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-site-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'solace_extra_height',
			[
				'label' => esc_html__( 'Height', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-site-logo img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Add action to update custom logo when solace_extra_site_logo is saved
		add_action( 'elementor/widget/before_render_content', [ $this, 'update_custom_logo' ] );
	}

	/**
	 * Updates the custom logo theme mod based on the selected site logo in the widget settings.
	*
	* @since 1.0.0
	*
	* @param \Elementor\Widget_Base $widget The widget instance.
	*
	* @return void
	*/
	public function update_custom_logo( $widget ) {

		// Check if the current widget is an instance of Solace_Extra_Site_Logo
		if ( $widget->get_name() === $this->get_name() ) {
			// Get the widget settings for display
			$settings = $widget->get_settings_for_display();

			// Check if solace_extra_site_logo is set in the widget settings
			if ( isset( $settings['solace_extra_site_logo']['id'] ) ) {
				// Get the ID of the selected site logo
				$logo_id = $settings['solace_extra_site_logo']['id'];

				// Update the custom_logo theme mod with the selected site logo ID
				set_theme_mod( 'custom_logo', $logo_id );
			}
		}
	}

	/**
	 * Render list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
	
		// Get the image URL based on the selected resolution
		$image_id = $settings['solace_extra_site_logo']['id'];
		$image_size = $settings['solace_extra_image_resolution'];
		$image_url = wp_get_attachment_image_url( $image_id, $image_size );
	
		// Get the caption if selected
		$caption = '';
		if ( 'attachment' === $settings['solace_extra_caption'] ) {
			$caption = wp_get_attachment_caption( $image_id );
		}
	
		// Get the custom link settings
		$custom_link = $settings['solace_extra_custom_link']['url'];
		$is_external = $settings['solace_extra_custom_link']['is_external'] ? 'target="_blank"' : '';
		$nofollow = $settings['solace_extra_custom_link']['nofollow'] ? 'rel="nofollow"' : '';
	
		if ( $image_url ) : ?>
			<div class="elementor-site-logo">
				<?php if ( $custom_link ) : ?>
					<a href="<?php echo esc_url( $custom_link ); ?>" <?php echo esc_attr( $is_external ); ?> <?php echo esc_attr( $nofollow ); ?>>
				<?php endif; ?>
				<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
				<?php if ( $custom_link ) : ?>
					</a>
				<?php endif; ?>
				<?php if ( $caption ) : ?>
					<p class="site-logo-caption"><?php echo esc_html( $caption ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif;
	}

}