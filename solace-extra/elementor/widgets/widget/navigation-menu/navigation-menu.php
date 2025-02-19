<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Navigation Menu Widget.
 *
 * Elementor widget that allows users to select and display a WordPress menu.
 *
 * @since 1.0.0
 */
class Solace_Extra_Navigation_Menu extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'solace-extra-navigation-menu';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Solace Nav Menu', 'solace-extra' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
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
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'solace', 'extra', 'menu', 'navigation' ];
	}	

	/**
	 * Register widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'solace_extra_content_section',
			[
				'label' => esc_html__( 'Layout', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'menu',
			[
				'label' => esc_html__( 'Select Menu', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_available_menus(),
				'default' => '',
			]
		);

		$this->add_control(
			'layout_menu',
			[
				'label' => esc_html__( 'Menu Layout', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'vertical' => esc_html__( 'Vertical', 'solace-extra' ),
					'horizontal' => esc_html__( 'Horizontal', 'solace-extra' ),
				],
				'default' => 'horizontal',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'solace_extra_style_content_section',
			[
				'label' => esc_html__( 'Main Menu', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
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
					'justify' => [
						'title' => esc_html__( 'Justify', 'solace-extra' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-navigation-menu' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-navigation-menu' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .elementor-navigation-menu',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get available menus.
	 *
	 * Retrieve the list of available WordPress menus.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return array List of available menus.
	 */
	private function get_available_menus() {
		$menus = wp_get_nav_menus();
		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->term_id ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['menu'] ) ) {
			echo esc_html__( 'Please select a menu.', 'solace-extra' );
			return;
		}

		$menu_class = 'elementor-navigation-menu';
		$menu_class .= ( 'vertical' === $settings['layout_menu'] ) ? '-vertical' : '-horizontal';

		wp_nav_menu( [
			'menu' => $settings['menu'],
			'menu_class' => $menu_class,
		] );
	}
}
