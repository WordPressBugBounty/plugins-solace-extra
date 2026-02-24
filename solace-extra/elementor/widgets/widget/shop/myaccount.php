<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Solace_Extra_WooCommerce_MyAccount extends Widget_Base {

    private static $add_body_class = false;

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        add_filter( 'body_class', function( $classes ) {
            if ( self::$add_body_class ) {
                if ( ! in_array( 'solex-myaccount', $classes, true ) ) {
                    $classes[] = 'solex-myaccount';
                }
            }
            return $classes;
        });
    }

    public function get_name() {
        return 'solace-extra-woocommerce-myaccount';
    }

    public function get_title() {
        return __('My Account', 'solace-extra');
    }

    public function get_icon() {
        return 'eicon-person solace-extra';
    }

    public function get_categories() {
        return ['solace-extra-woocommerce'];
    }

    public function get_keywords() {
        return ['woocommerce', 'my account', 'profile', 'login', 'register'];
    }

	public function get_style_depends(): array {
		return [ 'solace-my-account' ];
	}      

	/**
	 * @param array $args {
	 *     An array of values for the button adjustments.
	 *
	 *     @type array  $section_condition  Set of conditions to hide the controls.
	 *     @type string $alignment_default  Default position for the button.
	 *     @type string $alignment_control_prefix_class  Prefix class name for the button position control.
	 *     @type string $content_alignment_default  Default alignment for the button content.
	 * }
	 */
	protected function register_button_style_controls( $args = [] ) {
        $selector = '
        {{WRAPPER}} .woocommerce .button:not(header .button):not(footer .button),
        {{WRAPPER}} .woocommerce .woocommerce-EditAccountForm button.woocommerce-Button,
        {{WRAPPER}} .woocommerce .edit-account .woocommerce-info a.button,
        {{WRAPPER}} .woocommerce .woocommerce-MyAccount-content .woocommerce-info a.button,
        {{WRAPPER}} .woocommerce .account-orders-table .woocommerce-button,
        {{WRAPPER}} .woocommerce .woocommerce-order-downloads .woocommerce-MyAccount-downloads-file,
        {{WRAPPER}} .woocommerce .woocommerce-order-details .order-again a,
        {{WRAPPER}} .woocommerce .woocommerce-MyAccount-content .woocommerce-address-fields button,
        {{WRAPPER}} .woocommerce .edit-account .woocommerce-button,
        {{WRAPPER}} .woocommerce .woocommerce-address-fields button,
        {{WRAPPER}} .woocommerce-button,
        {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .woocommerce-MyAccount-content section.woocommerce-order-details p.order-again a.button,
        {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .solace-extra-button';
    
        $selector_hover = '
        {{WRAPPER}} .woocommerce .button:not(header .button):not(footer .button):hover,        
        {{WRAPPER}} .woocommerce .woocommerce-EditAccountForm button.woocommerce-Button:hover,
        {{WRAPPER}} .woocommerce .edit-account .woocommerce-info a.button:hover,
        {{WRAPPER}} .woocommerce .woocommerce-MyAccount-content .woocommerce-info a.button:hover,
        {{WRAPPER}} .woocommerce .account-orders-table .woocommerce-button:hover,
        {{WRAPPER}} .woocommerce .woocommerce-order-downloads .woocommerce-MyAccount-downloads-file:hover,
        {{WRAPPER}} .woocommerce .woocommerce-order-details .order-again a:hover,
        {{WRAPPER}} .woocommerce .woocommerce-MyAccount-content .woocommerce-address-fields button:hover,
        {{WRAPPER}} .woocommerce .edit-account .woocommerce-button:hover,
        {{WRAPPER}} .woocommerce .woocommerce-address-fields button:hover,
        {{WRAPPER}} .woocommerce-button:hover,
        {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .woocommerce-MyAccount-content section.woocommerce-order-details p.order-again a.button:hover,
        {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .solace-extra-button:hover'; 

		$default_args = [
			'section_condition' => [],
			'alignment_default' => '',
			'alignment_control_prefix_class' => 'elementor%s-align-',
			'content_alignment_default' => '',
		];

		$args = wp_parse_args( $args, $default_args );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => $selector,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style', [
			// 'condition' => $args['section_condition'],
		] );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'solace-extra' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude' => [ 'image' ],
				'selector' => $selector,
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => $selector,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'solace-extra' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [ $selector_hover => 'color: {{VALUE}};' ],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'types' => [ 'classic', 'gradient' ],
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude' => [ 'image' ],
				'selector' => $selector_hover,
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector_hover => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => $selector_hover,
			]
		);

		$this->add_control(
			'button_hover_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms', 'custom' ],
				'default' => [
					'unit' => 's',
				],
				'selectors' => [
					$selector_hover => 'transition-duration: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'solace-extra' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => $selector,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'solace-extra' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'solace-extra' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					$selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
	}    

    protected function register_controls() {
        /**
         * =======================
         * TAB: CONTENT
         * =======================
         */
        $this->start_controls_section(
            'section_general_content',
            [
                'label' => __('General', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'solace-extra'),
                'type' => Controls_Manager::SELECT,
                'default' => 'layout-1',
                'options' => [
                    'layout-1' => __('Layout 1', 'solace-extra'),
                    'layout-2' => __('Layout 2', 'solace-extra'),
                ],
            ]
        );

        $this->end_controls_section();

        // Section: Navigation Style
        $this->start_controls_section(
            'section_navigation_style',
            [
                'label' => __('Navigation', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'navigation_gap',
            [
                'label' => esc_html__( 'Navigation Gap', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account.layout-2 .woocommerce-MyAccount-navigation ul' =>
                        'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account.layout-1 .woocommerce-MyAccount-navigation ul li:not(:last-child)' =>
                        'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'myaccount_nav_width',
            [
                'label'      => esc_html__( 'Navigation Width (%)', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 25,
                ],
                'tablet_default' => [
                    'unit' => '%',
                    'size' => 35,
                ],
                'mobile_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'layout' => 'layout-1',
                ],
            ]
        );



        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'navigation_typography',
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a',
            ]
        );

        $this->add_responsive_control(
            'navigation_alignment',
            [
                'label' => __('Alignment', 'solace-extra'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'solace-extra'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'solace-extra'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'solace-extra'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account.layout-2 ul' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // BACKGROUND NORMAL / HOVER / ACTIVE
        $this->start_controls_tabs('navigation_background_tabs');

        $this->start_controls_tab(
            'navigation_bg_normal',
            [
                'label' => __('Normal', 'solace-extra'),
            ]
        );
        $this->add_control(
            'navigation_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'navigation_background_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'navigation_border',
                'label' => __('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li',
            ]
        );
        $this->add_responsive_control(
            'navigation_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'navigation_bg_hover',
            [
                'label' => __('Hover', 'solace-extra'),
            ]
        );
        $this->add_control(
            'navigation_hover_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:hover a' => 'color: {{VALUE}} !important;',

                ],
            ]
        );
        $this->add_control(
            'navigation_background_hover_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'navigation_border_hover',
                'label' => __('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:hover',
            ]
        );
        $this->add_responsive_control(
            'navigation_border_radius_hover',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'navigation_bg_active',
            [
                'label' => __('Active', 'solace-extra'),
            ]
        );
        $this->add_control(
            'navigation_active_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'navigation_background_active_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'navigation_border_active',
                'label' => __('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active',
            ]
        );
        $this->add_responsive_control(
            'navigation_border_radius_active',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-navigation ul li.is-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs(); // end background tabs

        $this->end_controls_section();

         $this->start_controls_section(
            'section__content_style',
            [
                'label' => __('Content', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'account_typography',
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-my-account, 
                {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .edit-account p, 
                {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .woocommerce-MyAccount-content p, 
                {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .edit-account .title h2',
                
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account,{{WRAPPER}} .solace-extra-box-woocommerce-my-account p, {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .edit-account p, {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .edit-account .title h2, .solace-not-logged-in {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce h2' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color_hover',
            [
                'label' => __('Link Hover Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_background',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-content' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'label' => __('Border', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content',
            ]
        );
        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        // Section: Button Style
        $this->start_controls_section(
            'section_button_style',
            [
                'label' => __('Buttons', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_button_style_controls();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_notice_style',
            [
                'label' => __('Notice', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'notice_bg_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Text Color
        $this->add_control(
            'notice_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'notice_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-info',
            ]
        );

        $this->add_responsive_control(
            'notice_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'notice_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'notice_border',
                'selector' => '{{WRAPPER}} .woocommerce-info',
            ]
        );

        // Border Radius
        $this->add_control(
            'notice_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
            
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_orders_style',
            [
                'label' => __('Orders', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'orders_typography',
                'label' => __('Heading Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .account-orders-table th',
            ]
        );

        $this->add_control(
            'orders_color',
            [
                'label' => __('Heading Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce .account-orders-table th' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'orders_content_typography',
                'label' => __('Text Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .account-orders-table td,
                {{WRAPPER}} .account-orders-table td span.woocommerce-Price-amount.amount,
                {{WRAPPER}} .woocommerce-order-details, 
                {{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title, 
                {{WRAPPER}} .woocommerce-order-details th, 
                {{WRAPPER}} .woocommerce-order-details td, 
                {{WRAPPER}} .woocommerce-order-details td span.woocommerce-Price-amount.amount, 
                {{WRAPPER}} .woocommerce-customer-details h2,
                {{WRAPPER}} .woocommerce-customer-details',
            ]
        );

        $this->add_control(
            'orders_content_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .account-orders-table td,
                {{WRAPPER}} .woocommerce-order-details, 
                {{WRAPPER}} .account-orders-table td span.woocommerce-Price-amount.amount,
                {{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title, 
                {{WRAPPER}} .woocommerce-order-details th, 
                {{WRAPPER}} .woocommerce-order-details td, 
                {{WRAPPER}} .woocommerce-customer-details h2,
                {{WRAPPER}} .woocommerce-order-details td span.woocommerce-Price-amount.amount, 
                {{WRAPPER}} .woocommerce-customer-details' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_download_style',
            [
                'label' => __('Downloads', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'download_typography',
                'label' => __('Heading Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-order-downloads th',
            ]
        );

        $this->add_control(
            'download_color',
            [
                'label' => __('Heading Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-order-downloads th' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'download_content_typography',
                'label' => __('Text Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-order-downloads td',
            ]
        );

        $this->add_control(
            'download_content_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-order-downloads td' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_address_style',
            [
                'label' => __('Addresses', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_address_style');

        // ===== LABEL TAB =====
        $this->start_controls_tab(
            'tab_address_heading',
            [
                'label' => __('Heading', 'solace-extra'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_address_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content h2',
            ]
        );

        $this->add_control(
            'heading_address_color',
            [
                'label' => __('Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-MyAccount-content h2' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_address_label',
            [
                'label' => __('Label', 'solace-extra'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_address_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .woocommerce-address-fields label',
            ]
        );

        $this->add_control(
            'label_address_color',
            [
                'label' => __('Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-address-fields label' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        // ===== ADDRESS TAB =====
        $this->start_controls_tab(
            'tab_address_input',
            [
                'label' => __('Input', 'solace-extra'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'address_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="text"],
                            {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="email"],
                            {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="tel"],
                            {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields .select2-selection,
                            {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="password"]',
            ]
        );

        $this->add_control(
            'address_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="text"],
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="email"],
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="tel"],
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields .select2-selection,
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="password"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'address_bg_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="text"],
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="email"],
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="tel"],
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields .select2-selection,
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="password"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'address_border',
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="text"],
                            {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="email"],
                            {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="tel"],
                            {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields .select2-selection,
                            {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="password"]',
            ]
        );

        $this->add_control(
            'address_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="text"],
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="email"],
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="tel"],
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields .select2-selection,
                    {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce-address-fields input[type="password"]' =>
                        'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_form_style',
            [
                'label' => __('Account Details', 'solace-extra'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_form_style' );

        // ===== NORMAL TAB =====
        $this->start_controls_tab(
            'tab_form_label',
            [
                'label' => __( 'Label', 'solace-extra' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .edit-account label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __('Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .edit-account label' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_form_input',
            [
                'label' => __( 'Input', 'solace-extra' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'label' => __('Typography', 'solace-extra'),
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="text"],
                     {{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="email"],
                     {{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="password"]',
            ]
        );

        
        $this->add_control(
            'input_text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="text"],
                     {{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="email"],
                     {{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="password"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .edit-account input[type="text"],
                     {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .edit-account input[type="email"],
                     {{WRAPPER}} .solace-extra-box-woocommerce-my-account .woocommerce .edit-account input[type="password"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'input_border',
                'selector' => '{{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="text"],
                               {{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="email"],
                               {{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="password"]',
            ]
        );

        $this->add_control(
            'input_border_radius',
            [
                'label' => __('Border Radius', 'solace-extra'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="text"],
                     {{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="email"],
                     {{WRAPPER}} .solace-extra-box-woocommerce-my-account .edit-account input[type="password"]' =>
                        'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        



    }


    protected function solace_extra_render_myaccount_html() {
        if ( ! is_user_logged_in() ) {
            echo '<p>' . esc_html__( 'You must be logged in to view this page.', 'solace-extra' ) . '</p>';
            wc_get_template( 'myaccount/form-login.php' );
            return;
        }

        $current_user = wp_get_current_user();

        ob_start();
        ?>
        <div class="woocommerce">
            <div class="woocommerce-notices-wrapper"></div>
            <nav class="woocommerce-MyAccount-navigation">
                <ul>
                    <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard is-active">
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
                            <?php esc_html_e( 'Dashboard', 'solace-extra' ); ?>
                        </a>
                    </li>
                    <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--orders">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">
                            <?php esc_html_e( 'Orders', 'solace-extra' ); ?>
                        </a>
                    </li>
                    <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--downloads">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'downloads' ) ); ?>">
                            <?php esc_html_e( 'Downloads', 'solace-extra' ); ?>
                        </a>
                    </li>
                    <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-address">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>">
                            <?php esc_html_e( 'Addresses', 'solace-extra' ); ?>
                        </a>
                    </li>
                    <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-account">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>">
                            <?php esc_html_e( 'Account details', 'solace-extra' ); ?>
                        </a>
                    </li>
                    <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-logout">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'customer-logout' ) ); ?>">
                            <?php esc_html_e( 'Logout', 'solace-extra' ); ?>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="woocommerce-MyAccount-content">
                <p>
                    <?php
                    printf(
                        wp_kses(
                            /* translators: 1: recent orders link, 2: addresses link, 3: account details link */
                            __( 'From your account dashboard you can view your %1$s, manage your %2$s, and %3$s.', 'solace-extra' ),
                            array(
                                'a' => array(
                                    'href' => array(),
                                ),
                            )
                        ),
                        '<a href="' . esc_url( wc_get_account_endpoint_url( 'orders' ) ) . '">' . esc_html__( 'recent orders', 'solace-extra' ) . '</a>',
                        '<a href="' . esc_url( wc_get_account_endpoint_url( 'edit-address' ) ) . '">' . esc_html__( 'shipping and billing addresses', 'solace-extra' ) . '</a>',
                        '<a href="' . esc_url( wc_get_account_endpoint_url( 'edit-account' ) ) . '">' . esc_html__( 'edit your password and account details', 'solace-extra' ) . '</a>'
                    );
                    ?>
                </p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }


    protected function style() {
        echo '<style>
            .solace-extra-box-woocommerce-my-account .woocommerce-form__label-for-checkbox.woocommerce-form-login__rememberme {
                margin-top: 9px;
            }
            .solace-not-logged-in .solex-myaccount {
                display: flex;
                flex-direction: column;
            }
            .solace-extra-box-woocommerce-my-account ul {
                padding: 0;
            }
            .woocommerce-MyAccount-content
            .solace-extra-box-woocommerce-my-account li {
                margin: 0;
            }
            .solace-extra-box-woocommerce-my-account li a {
                transition: background-color 0.3s ease, color 0.3s ease;
                width: 100%;
                display: block;
            }
            .solace-extra-box-woocommerce-my-account li:hover,
            .solace-extra-box-woocommerce-my-account li:hover a {
                cursor: pointer;
                transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            }
            .solace-extra-box-woocommerce-my-account .woocommerce {
                display: flex;
                justify-content: start;
            }
            .solace-extra-box-woocommerce-my-account .edit-account {
                width: 100%;
            }
            .solace-extra-box-woocommerce-my-account .woocommerce-MyAccount-navigation li {
                display: flex;
            }
            .solace-extra-box-woocommerce-my-account.layout-2 .woocommerce {
                flex-direction: column;
            }
            body.solace-not-logged-in .solace-extra-box-woocommerce-my-account form.woocommerce-form.woocommerce-form-login.login {
                margin: 0 auto;
            }
            body.solace-not-logged-in .solace-extra-box-woocommerce-my-account .woocommerce h2 {
                text-align:center;
            }

            @media(max-width: 767px) {
                .solace-extra-box-woocommerce-my-account.layout-1 .woocommerce {
                    display: flex;
                    flex-direction: column;
                }
            }


        </style>';
    }

    protected function render2() {
        self::$add_body_class = true;
        $settings = $this->get_settings_for_display();
        $this->style();
        $kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();

		if ( $kit_id ) {

			$kit_settings = get_post_meta( $kit_id, '_elementor_page_settings', true );

			if ( is_array( $kit_settings ) ) {

				$text_color = $kit_settings['form_field_text_color'] ?? '';
				$bg_color   = $kit_settings['form_field_background_color'] ?? '';

				if ( ! $text_color && ! $bg_color ) {
					return;
				}

				static $style_printed = [];

				$widget_id = $this->get_id();

				if ( isset( $style_printed[ $widget_id ] ) ) {
					return;
				}
				echo '<style>';
				echo '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-my-account form.woocommerce-EditAccountForm .form-row input.input-text,';
				echo '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-my-account form .woocommerce-address-fields .form-row input.input-text,';
				echo '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-my-account select.country_select,';
				echo '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-my-account .woocommerce-address-fields select.country_select,';
				echo '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-my-account select.state_select,';
				echo '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-my-account .woocommerce-address-fields .select2-selection,';
				echo '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-my-account form.woocommerce-EditAccountForm .form-row textarea.input-text,';
				echo '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-my-account form .form-row input.input-text,';
				echo '.elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-my-account .woocommerce-address-fields .select2-selection span.select2-selection__placeholder {';

				if ( $text_color ) {
					echo 'color:' . esc_attr( $text_color ) . ';';
				}

				if ( $bg_color ) {
					echo 'background-color:' . esc_attr( $bg_color ) . ';';
				}

				echo '}';
				echo '</style>';

				$style_printed[ $widget_id ] = true;
			}
		}

        
        $layout_class = isset($settings['layout']) ? $settings['layout'] : 'layout-1';

        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';        

        echo '<div id="solace-my-account-' . esc_attr( $this->get_id() ) . '" class="solace-extra-box-woocommerce-my-account ' . esc_attr($layout_class) . '">';

        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $this->solace_extra_render_myaccount_html();
        }else {
			// echo do_shortcode('[woocommerce_my_account]');
			$output = do_shortcode('[woocommerce_my_account]');
			// Ensure our body marker class on the first woocommerce container
			$output = preg_replace(
				'/class="([^"]*woocommerce[^"]*)"/',
				'class="$1 solex-myaccount"',
				$output,
				1
			);
			// Replace class token "button" with "elementor-button" and also add "solace-extra-button"
			$output = preg_replace_callback(
				'/class="([^"]*)"/',
				function ($matches) {
					$original = $matches[1];
					$tokens = preg_split('/\s+/', trim($original));
					if (!is_array($tokens)) {
						$tokens = [$original];
					}
					$hadButton = false;
					$normalized = [];
					foreach ($tokens as $cls) {
						if ($cls === 'button') {
							$hadButton = true;
							$normalized[] = 'elementor-button';
							continue;
						}
						$normalized[] = $cls;
					}
					if ($hadButton && !in_array('solace-extra-button', $normalized, true)) {
						$normalized[] = 'solace-extra-button';
					}
					$normalized = array_values(array_unique($normalized));
					return 'class="' . trim(implode(' ', $normalized)) . '"';
				},
				$output
			);
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $output;

        }
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>';

       // Add JavaScript to ensure body class is added
       echo '<script>
       (function() {

           // Apply Elementor hover animation class to WooCommerce my-account buttons inside this widget
           var widgetRoot = document.getElementById("solace-my-account-' . esc_js( $this->get_id() ) . '");
           var animationClass = "' . esc_js( $hover_animation ? ( 'elementor-animation-' . $settings['hover_animation'] ) : '' ) . '";
           function applyHoverAnimation() {
               if (!widgetRoot || !animationClass) return;
               var buttons = widgetRoot.querySelectorAll(".solace-extra-box-woocommerce-my-account button.button, .solace-extra-box-woocommerce-my-account a.button, .solace-extra-box-woocommerce-my-account a.my-account-button, .solace-extra-box-woocommerce-my-account .wc-proceed-to-my-account .button, .solace-extra-box-woocommerce-my-account .wc-proceed-to-my-account a.button");
               buttons.forEach(function(btn){
                   if (!btn.classList.contains(animationClass)) {
                       btn.classList.add(animationClass);
                   }
               });
           }
           applyHoverAnimation();
           if (typeof jQuery !== "undefined") {
               jQuery(document).on("updated_wc_div wc_fragments_loaded wc_fragments_refreshed", applyHoverAnimation);
           }            

       })();
       </script>';          
    }

    private function get_myaccount_field_selectors( $widget_id, $is_focus = false ) {
        $prefix = '.woocommerce-account .elementor-element-' . esc_attr( $widget_id ) . ' .solace-extra-box-woocommerce-my-account';
        
        $selectors = [
            $prefix . ' form.woocommerce-EditAccountForm .form-row input.input-text',
            $prefix . ' form.woocommerce-EditAccountForm .form-row textarea.input-text',
            $prefix . ' .woocommerce-address-fields .form-row input.input-text',
            $prefix . ' form .form-row input.input-text',
            $prefix . ' select.country_select',
            $prefix . ' select.state_select',
            $prefix . ' .select2-selection',
            // $prefix . ' .select2-selection__rendered',
            // $prefix . ' .select2-selection span.select2-selection__placeholder',
        ];

        if ( $is_focus ) {
            $selectors = array_map( function( $selector ) {
                return $selector . ':focus';
            }, $selectors );

            $selectors[] = $prefix . ' .select2-container--focus .select2-selection';
            $selectors[] = $prefix . ' .select2-container--open .select2-selection';
        }

        return implode( ',', $selectors );
    }

    private function generate_typography_style( $settings, $prefix ) {
        $css = '';
        $family = $settings[ $prefix . 'typography_font_family' ] ?? '';
        if ( $family ) $css .= "font-family: \"{$family}\", Sans-serif;";

        $size = $settings[ $prefix . 'typography_font_size' ] ?? '';
        if ( is_array( $size ) && ! empty( $size['size'] ) ) $css .= "font-size: {$size['size']}{$size['unit']};";

        $weight = $settings[ $prefix . 'typography_font_weight' ] ?? '';
        if ( $weight ) $css .= "font-weight: {$weight};";

        $lh = $settings[ $prefix . 'typography_line_height' ] ?? '';
        if ( is_array( $lh ) && ! empty( $lh['size'] ) ) $css .= "line-height: {$lh['size']}{$lh['unit']};";

        return $css;
    }

    private function generate_field_style( $settings, $state = 'normal' ) {
        $css = '';
        $prefix = ( $state === 'focus' ) ? 'form_field_focus_' : 'form_field_';

        // Color
        $text_color = $settings[ $prefix . 'text_color' ] ?? '';
        $bg_color   = $settings[ $prefix . 'background_color' ] ?? '';
        if ( $text_color ) $css .= "color: {$text_color};";
        if ( $bg_color )   $css .= "background-color: {$bg_color};";

        if ( $state === 'normal' ) $css .= $this->generate_typography_style( $settings, 'form_field_' );

        // Border
        $border_type = $settings[ $prefix . 'border_border' ] ?? '';
        if ( $border_type ) {
            $css .= "border-style: {$border_type};";
            $border_width = $settings[ $prefix . 'border_width' ] ?? '';
            if ( is_array( $border_width ) && ! empty( $border_width['top'] ) ) {
                $unit = $border_width['unit'] ?? 'px';
                $css .= "border-width: {$border_width['top']}{$unit} {$border_width['right']}{$unit} {$border_width['bottom']}{$unit} {$border_width['left']}{$unit};";
            }
            $border_color = $settings[ $prefix . 'border_color' ] ?? '';
            if ( $border_color ) $css .= "border-color: {$border_color};";
        }

        // Radius & Padding
        foreach ( ['border_radius' => 'border-radius', 'padding' => 'padding'] as $key => $prop ) {
            $val = $settings[ $prefix . $key ] ?? '';
            if ( is_array( $val ) && ! empty( $val['top'] ) ) {
                $unit = $val['unit'] ?? 'px';
                $css .= "{$prop}: {$val['top']}{$unit} {$val['right']}{$unit} {$val['bottom']}{$unit} {$val['left']}{$unit};";
            }
        }

        // Shadow
        $shadow = $settings[ $prefix . 'box_shadow_box_shadow' ] ?? '';
        if ( is_array( $shadow ) && ! empty( $shadow['color'] ) ) {
            $ins = ( isset( $shadow['outline'] ) && $shadow['outline'] === 'inset' ) ? 'inset' : '';
            $css .= "box-shadow: {$shadow['horizontal']}px {$shadow['vertical']}px {$shadow['blur']}px {$shadow['spread']}px {$shadow['color']} {$ins};";
        }

        // Transition (Focus)
        if ( $state === 'focus' ) {
            $transition = $settings['form_field_focus_transition_duration'] ?? '';
            if ( is_array( $transition ) && ! empty( $transition['size'] ) ) {
                $css .= "transition: all {$transition['size']}{$transition['unit']} ease-in-out;";
            }
        }

        return $css;
    }

    private function generate_label_style( $settings ) {
        $css = '';
        
        $label_color = $settings['form_label_color'] ?? '';
        if ( $label_color ) {
            $css .= "color: {$label_color};";
        }

        $css .= $this->generate_typography_style( $settings, 'form_label_' );

        return $css;
    }

    private function get_label_selectors( $widget_id ) {
        $prefix = '.elementor-element-' . esc_attr( $widget_id );
        
        return "
            $prefix .solace-extra-box-woocommerce-my-account form .form-row label,
            $prefix .solace-extra-box-woocommerce-my-account .woocommerce-address-fields label
        ";
    }

    protected function render() {
        self::$add_body_class = true;
        $settings = $this->get_settings_for_display();
        $this->style();
        $kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();

		if ( ! $kit_id ) return;

        $kit_settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
        if ( ! is_array( $kit_settings ) ) return;

        static $style_printed = [];
        $widget_id = $this->get_id();
        if ( isset( $style_printed[ $widget_id ] ) ) return;

        $normal_styles = $this->generate_field_style( $kit_settings, 'normal' );
        $focus_styles  = $this->generate_field_style( $kit_settings, 'focus' );
        $label_styles  = $this->generate_label_style( $kit_settings );

        if ( empty( $normal_styles ) && empty( $focus_styles ) && empty( $label_styles ) ) return;

        echo '<style>';

        if ( ! empty( $label_styles ) ) {
            echo esc_html( $this->get_label_selectors( $widget_id ) . ' { ' . $label_styles . ' }' );
        }
        if ( ! empty( $normal_styles ) ) {
            echo esc_html( $this->get_myaccount_field_selectors( $widget_id, false ) . ' { ' . $normal_styles . ' }' );
        }
        if ( ! empty( $focus_styles ) ) {
            echo esc_html( $this->get_myaccount_field_selectors( $widget_id, true ) . ' { ' . $focus_styles . ' }' );
        }
        echo '</style>';

        $style_printed[ $widget_id ] = true;

        
        $layout_class = isset($settings['layout']) ? $settings['layout'] : 'layout-1';

        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';        

        echo '<div id="solace-my-account-' . esc_attr( $this->get_id() ) . '" class="solace-extra-box-woocommerce-my-account ' . esc_attr($layout_class) . '">';

        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $this->solace_extra_render_myaccount_html();
        }else {
			// echo do_shortcode('[woocommerce_my_account]');
			$output = do_shortcode('[woocommerce_my_account]');
			// Ensure our body marker class on the first woocommerce container
			$output = preg_replace(
				'/class="([^"]*woocommerce[^"]*)"/',
				'class="$1 solex-myaccount"',
				$output,
				1
			);
			// Replace class token "button" with "elementor-button" and also add "solace-extra-button"
			$output = preg_replace_callback(
				'/class="([^"]*)"/',
				function ($matches) {
					$original = $matches[1];
					$tokens = preg_split('/\s+/', trim($original));
					if (!is_array($tokens)) {
						$tokens = [$original];
					}
					$hadButton = false;
					$normalized = [];
					foreach ($tokens as $cls) {
						if ($cls === 'button') {
							$hadButton = true;
							$normalized[] = 'elementor-button';
							continue;
						}
						$normalized[] = $cls;
					}
					if ($hadButton && !in_array('solace-extra-button', $normalized, true)) {
						$normalized[] = 'solace-extra-button';
					}
					$normalized = array_values(array_unique($normalized));
					return 'class="' . trim(implode(' ', $normalized)) . '"';
				},
				$output
			);
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $output;

        }
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>';

       // Add JavaScript to ensure body class is added
       echo '<script>
       (function() {

           // Apply Elementor hover animation class to WooCommerce my-account buttons inside this widget
           var widgetRoot = document.getElementById("solace-my-account-' . esc_js( $this->get_id() ) . '");
           var animationClass = "' . esc_js( $hover_animation ? ( 'elementor-animation-' . $settings['hover_animation'] ) : '' ) . '";
           function applyHoverAnimation() {
               if (!widgetRoot || !animationClass) return;
               var buttons = widgetRoot.querySelectorAll(".solace-extra-box-woocommerce-my-account button.button, .solace-extra-box-woocommerce-my-account a.button, .solace-extra-box-woocommerce-my-account a.my-account-button, .solace-extra-box-woocommerce-my-account .wc-proceed-to-my-account .button, .solace-extra-box-woocommerce-my-account .wc-proceed-to-my-account a.button");
               buttons.forEach(function(btn){
                   if (!btn.classList.contains(animationClass)) {
                       btn.classList.add(animationClass);
                   }
               });
           }
           applyHoverAnimation();
           if (typeof jQuery !== "undefined") {
               jQuery(document).on("updated_wc_div wc_fragments_loaded wc_fragments_refreshed", applyHoverAnimation);
           }            

       })();
       </script>';          
    }




}
