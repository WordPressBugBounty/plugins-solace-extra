<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use SolaceExtra\App\Controls\Group_Control_Gradient_Text;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Solace_Extra_My_Account extends Widget_Base {

	public $id;
	protected $form_close = '';

	public function __construct( $data = array(), $args = null ) {
		$this->id = $this->get_name();
		parent::__construct( $data, $args );
		wp_register_style( "solace-extra-{$this->id}", plugins_url( "assets/css/style.min.css", __FILE__ ), array(), '1.1' );
	}

	public function get_script_depends() {
		return array( "solace-extra-{$this->id}" );
	}

	public function get_style_depends() {
		return array( "solace-extra-{$this->id}" );
	}

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
		return 'solace-extra-my-account';
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
		return esc_html__( 'My Account', 'solace-extra' );
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
		return 'eicon-call-to-action wlbi solace-extra';
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
		return ['solace-extra-woocommerce'];
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
		return [ 'solace', 'extra', 'my', 'account', 'cart', 'customer' ];
	}	

	protected function register_controls() {

		$this->start_controls_section(
			'content',
			array(
				'label' => __( 'Content', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_img',
			array(
				'label'        => __( 'Show Image', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'solace-extra' ),
				'label_off'    => __( 'Hide', 'solace-extra' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_name',
			array(
				'label'        => __( 'Show Name', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'solace-extra' ),
				'label_off'    => __( 'Hide', 'solace-extra' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'user_data',
			array(
				'label'     => __( 'Display User\'s ', 'solace-extra' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'display_name',
				'options'   => array(
					'display_name' => __( 'Display Name', 'solace-extra' ),
					'nickname'     => __( 'Nick Name', 'solace-extra' ),
					'user_login'   => __( 'Username', 'solace-extra' ),
					'user_email'   => __( 'Email', 'solace-extra' ),
				),
				'condition' => array(
					'show_name' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Card styling
		$this->start_controls_section(
			'card_style',
			array(
				'label'     => __( 'Profile Card', 'solace-extra' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_name' => 'yes',
					'show_img'  => 'yes',
				),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'     => __( 'Layout', 'solace-extra' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'flex;',
				'options'   => array(
					'flex;'                            => __( 'Flex', 'solace-extra' ),
					'flex;flex-direction: row-reverse' => __( 'Reverse Flex', 'solace-extra' ),
					''                                 => __( 'Normal', 'solace-extra' ),
				),
				'selectors' => array(
					'.wl {{WRAPPER}} .wcd-customer-box' => 'display:{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'content_position',
			array(
				'label'     => __( 'Position', 'solace-extra' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => __( 'Top', 'solace-extra' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => __( 'Center', 'solace-extra' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'end'    => array(
						'title' => __( 'Bottom', 'solace-extra' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'   => 'center',
				'condition' => array(
					'layout!' => '',
				),
				'selectors' => array(
					'.wl {{WRAPPER}} .wcd-customer-box' => 'align-items:{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'gap',
			array(
				'label'      => __( 'Content Gap', 'solace-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 15,
				),
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box' => 'gap: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout!' => '',
				),
			)
		);

		$this->add_control(
			'Card_width',
			array(
				'label'      => __( 'Width', 'solace-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'card_background',
				'label'     => __( 'Background', 'solace-extra' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '.wl {{WRAPPER}} .wcd-customer-box',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'label'    => __( 'Border', 'solace-extra' ),
				'selector' => '.wl {{WRAPPER}} .wcd-customer-box',
			)
		);

		$this->add_control(
			'card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_padding',
			array(
				'label'      => __( 'Padding', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_margin',
			array(
				'label'      => __( 'Margin', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// image styling
		$this->start_controls_section(
			'img_style',
			array(
				'label'     => __( 'Image', 'solace-extra' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_img' => 'yes',
				),
			)
		);

		$this->add_control(
			'img_default_style',
			array(
				'label'     => __( 'Display', 'solace-extra' ),
				'type'      => Controls_Manager::HIDDEN,
				'selectors' => array(
					'.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-img img' => 'height: 100%; width:100%;',
					'.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-img' => 'overflow:hidden;',
				),
				'default'   => 'traditional',
			)
		);

		$this->add_control(
			'img_width',
			array(
				'label'      => __( 'Width', 'solace-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 70,
				),
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'img_height',
			array(
				'label'      => __( 'Height', 'solace-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 70,
				),
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'img_margin',
			array(
				'label'      => __( 'Margin', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'img_padding',
			array(
				'label'      => __( 'Padding', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'img_background',
				'label'     => __( 'Background', 'solace-extra' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-img',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'img_border',
				'label'    => __( 'Border', 'solace-extra' ),
				'selector' => '.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-img',
			)
		);

		$this->add_control(
			'img_border_radius',
			array(
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'unit'   => '%',
					'top'    => 50,
					'right'  => 50,
					'bottom' => 50,
					'left'   => 50,
				),
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// content part styling
		$this->start_controls_section(
			'name_style',
			array(
				'label'     => __( 'Name', 'solace-extra' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_name' => 'yes',
				),
			)
		);

		$this->add_control(
			'name_width',
			array(
				'label'      => __( 'Width', 'solace-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-name' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);		

		$this->add_control(
			'content_alignment',
			array(
				'label'     => __( 'Position', 'solace-extra' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'solace-extra' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'solace-extra' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'solace-extra' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'.wl {{WRAPPER}} .wcd-ab-name' => 'text-align:{{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'label'    => __( 'Typography', 'solace-extra' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-name',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Color', 'solace-extra' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'.wl {{WRAPPER}} .wcd-customer-box .wcd-ab-name' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		// section title style
		$this->start_controls_section(
			'tab_area_style',
			array(
				'label' => __( 'Tab Area', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'tab_area_background',
				'label'     => __( 'Background', 'solace-extra' ),
				'types'     => array( 'classic', 'gradient' ),
				'separator' => 'before',
				'selector'  => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation',
			)
		);

		$this->add_control(
			'tab_area_margin',
			array(
				'label'      => __( 'Margin', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tab_area_padding',
			array(
				'label'      => __( 'Padding', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'tab_area_border',
				'label'     => __( 'Border', 'solace-extra' ),
				'separator' => 'before',
				'selector'  => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation',
			)
		);

		$this->add_control(
			'tab_area_border_raidus',
			array(
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-navigation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tab_area_box_shadow',
				'label'    => __( 'Box Shadow', 'solace-extra' ),
				'selector' => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation',
			)
		);

		$this->add_control(
			'gap_with_content',
			array(
				'label'      => __( 'Gap with content', 'solace-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
				),
				'separator'  => 'before',
				'selectors'  => array(
					'.wl {{WRAPPER}} .wl-my-account-left .woocommerce-MyAccount-navigation' => 'margin-right: {{SIZE}}{{UNIT}};',
					'.wl {{WRAPPER}} .wl-my-account-top .woocommerce-MyAccount-navigation' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// section title style
		$this->start_controls_section(
			'menu_style',
			array(
				'label' => __( 'Tab Design', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tab_position',
			array(
				'label'   => __( 'Tab Position', 'solace-extra' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => __( 'Left', 'solace-extra' ),
						'icon'  => 'eicon-h-align-left',
					),
					'top'  => array(
						'title' => __( 'Top', 'solace-extra' ),
						'icon'  => 'eicon-v-align-top',
					),
				),
				'default' => 'left',
				'toggle'  => true,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_typography',
				'label'    => __( 'Typography', 'solace-extra' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li',
			)
		);

		$this->add_group_control(
			Solace_Extra_Group_Control_Gradient_Text::get_type(),
			array(
				'name'     => 'menu_text_color',
				'selector' => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li a',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'menu_background',
				'label'     => __( 'Background', 'solace-extra' ),
				'types'     => array( 'classic', 'gradient' ),
				'separator' => 'before',
				'selector'  => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li',
			)
		);

		$this->add_control(
			'menu_margin',
			array(
				'label'      => __( 'Margin', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'menu_padding',
			array(
				'label'      => __( 'Padding', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'menu_border',
				'label'     => __( 'Border', 'solace-extra' ),
				'separator' => 'before',
				'selector'  => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li',
			)
		);

		$this->add_control(
			'menu_border_raidus',
			array(
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'label'    => __( 'Box Shadow', 'solace-extra' ),
				'selector' => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li',
			)
		);
		$this->end_controls_section();

		// active tab design
		$this->start_controls_section(
			'active_tab_style',
			array(
				'label' => __( 'Active Tab Design', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'active_menu_typography',
				'label'    => __( 'Typography', 'solace-extra' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active',
			)
		);

		$this->add_group_control(
			Solace_Extra_Group_Control_Gradient_Text::get_type(),
			array(
				'name'     => 'active_menu_text_color',
				'selector' => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active a',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'active_menu_background',
				'label'     => __( 'Background', 'solace-extra' ),
				'types'     => array( 'classic', 'gradient' ),
				'separator' => 'before',
				'selector'  => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active',
			)
		);

		$this->add_control(
			'active_menu_margin',
			array(
				'label'      => __( 'Margin', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'active_menu_padding',
			array(
				'label'      => __( 'Padding', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'active_menu_border',
				'label'     => __( 'Border', 'solace-extra' ),
				'separator' => 'before',
				'selector'  => '.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active',
			)
		);

		$this->add_control(
			'active_menu_border_raidus',
			array(
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// content part design
		$this->start_controls_section(
			'content_tab_style',
			array(
				'label' => __( 'Content Section', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'tab_content_background',
				'label'     => __( 'Background', 'solace-extra' ),
				'types'     => array( 'classic', 'gradient' ),
				'separator' => 'before',
				'selector'  => '.wl {{WRAPPER}} .woocommerce-MyAccount-content',
			)
		);

		$this->add_control(
			'tab_content_margin',
			array(
				'label'      => __( 'Margin', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tab_content_padding',
			array(
				'label'      => __( 'Padding', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'tab_content_border',
				'label'     => __( 'Border', 'solace-extra' ),
				'separator' => 'before',
				'selector'  => '.wl {{WRAPPER}} .woocommerce-MyAccount-content',
			)
		);

		$this->add_control(
			'tab_content_border_raidus',
			array(
				'label'      => __( 'Border Radius', 'solace-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'.wl {{WRAPPER}} .woocommerce-MyAccount-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tab_content_box_shadow',
				'label'    => __( 'Box Shadow', 'solace-extra' ),
				'selector' => '.wl {{WRAPPER}} .woocommerce-MyAccount-content',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		extract( $settings );
		?>

		<div class="wl-my-account wl-my-account-<?php echo esc_attr( $tab_position ); ?>">
			<?php
			$user_id = get_current_user_id();
			if ( $user_id ) {
				$image_html = $name_html = '';

				if ( $settings['show_img'] == 'yes' ) {
					$avatar_url = get_avatar_url( $user_id );
					$image_html = "<div class='wcd-ab-img'><img src='{$avatar_url}'></div>";
				}

				if ( $settings['show_name'] == 'yes' ) {
					$data_type = $settings['user_data'];
					if ( $data_type == 'nickname' ) {
						$name = get_user_meta( $user_id, 'nickname', true );
					} else {
						$user = get_user_by( 'ID', $user_id )->data;
						$name = $user->$data_type;
					}
					$name_html = "<div class='wcd-ab-name'>{$name}</div>";
				}
				?>
				<div class='wcd-customer-box'><?php echo wp_kses_post( $image_html . $name_html ); ?></div>
				<?php
			}
			if ( solace_extra_is_edit_mode() ) {
				?>
				<div class="woocommerce">
					<?php
					wc()->frontend_includes();
					wc_get_template(
						'myaccount/my-account.php',
						array(
							'current_user' => get_user_by( 'id', get_current_user_id() ),
							'order_count'  => -1,
						)
					);
					?>
				</div>
				<?php					
			} else {
				echo do_shortcode( '[woocommerce_my_account]' );
			}
			?>
		</div>

		<?php

		do_action( 'solace_extra_after_main_content', $this );
	}
}

