<?php
namespace Solaceform\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use Solaceform\SolaceFormBase;

/**
 * Solace Form Builder Widget.
 *
 * @since 1.0.0
 */
class SolaceFormBuilder extends SolaceFormBase {
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'solaceform';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Form Builder', 'solace-extra' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal solace-extra';
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
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array( 'solaceform', 'solaceform-sweetalert' );
	}

	/**
	 * Retrieve the list of styles the widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		return array( 'solaceform' );
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
		$selector = '{{WRAPPER}} .solaceform-form-button';

		$selector_hover = '{{WRAPPER}} .solaceform-form-button:hover'; 

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
				'name' => 'border',
				'selector' => $selector,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'border_radius',
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
				// 'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => esc_html__( 'Margin', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .submit-button' =>
						'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}	

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_fields',
			array(
				'label' => __( 'Form Fields', 'solace-extra' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'field_type',
			array(
				'label'   => __( 'Type', 'solace-extra' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'Text',
				'options' => array(
					'Text'       => __( 'Text', 'solace-extra' ),
					'Email'      => __( 'Email', 'solace-extra' ),
					'Textarea'   => __( 'Textarea', 'solace-extra' ),
					'URL'        => __( 'URL', 'solace-extra' ),
					'Tel'        => __( 'Tel', 'solace-extra' ),
					'Radio'      => __( 'Radio', 'solace-extra' ),
					'Select'     => __( 'Select', 'solace-extra' ),
					'Checkbox'   => __( 'Checkbox', 'solace-extra' ),
					'Acceptance' => __( 'Acceptance', 'solace-extra' ),
					'Number'     => __( 'Number', 'solace-extra' ),
					'Date'       => __( 'Date', 'solace-extra' ),
					'Time'       => __( 'Time', 'solace-extra' ),
					'File'       => __( 'File', 'solace-extra' ),
					'Password'   => __( 'Password', 'solace-extra' ),
					'HTML'       => __( 'HTML', 'solace-extra' ),
					'Hidden'     => __( 'Hidden', 'solace-extra' ),
					'reCAPTCHA'  => __( 'reCAPTCHA', 'solace-extra' ),
				),
			)
		);

		$repeater->add_control(
			'rows',
			array(
				'label'      => __( 'Rows', 'solace-extra' ),
				'type'       => Controls_Manager::NUMBER,
				'default'    => 4,
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'field_type',
							'value' => 'Textarea',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_options',
			array(
				'label'       => __( 'Options', 'solace-extra' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => __( 'Enter each option in a new line.', 'solace-extra' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'Select',
								'Checkbox',
								'Radio',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_acceptance',
			array(
				'label'       => __( 'Acceptance Text', 'solace-extra' ),
				'type'        => Controls_Manager::TEXTAREA,
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'Acceptance',
							),
						),
					),
				),
			)
		);		

		$repeater->add_control(
			'field_inline_list',
			array(
				'label'       => __( 'Inline List', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'solace-extra' ),
				'label_off'    => __( 'No', 'solace-extra' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'Checkbox',
								'Radio',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'file_types',
			array(
				'label'       => __( 'Allowed File Types', 'solace-extra' ),
				'default'     => '.png,.jpg,.jpeg,.gif',
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( '.pdf,.jpg,.txt', 'solace-extra' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'  => 'field_type',
							'value' => 'File',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'multiple_files',
			array(
				'label'        => __( 'Multiple Files', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'multiple_files',
				'conditions'   => array(
					'terms' => array(
						array(
							'name'  => 'field_type',
							'value' => 'File',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_html',
			array(
				'label'      => __( 'HTML', 'solace-extra' ),
				'type'       => Controls_Manager::TEXTAREA,
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'field_type',
							'value' => 'HTML',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'type_hr',
			array(
				'type'       => Controls_Manager::DIVIDER,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'reCAPTCHA',
								'Hidden',
								'HTML',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_label',
			array(
				'label'      => __( 'Label', 'solace-extra' ),
				'type'       => Controls_Manager::TEXT,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'Hidden',
								'reCAPTCHA',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'site_key',
			array(
				'label'      => __( 'Site Key', 'solace-extra' ),
				'type'       => Controls_Manager::TEXT,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'reCAPTCHA',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_placeholder',
			array(
				'label'      => __( 'Placeholder', 'solace-extra' ),
				'type'       => Controls_Manager::TEXT,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'reCAPTCHA',
								'Hidden',
								'HTML',
								'Radio',
								'Checkbox',
								'Acceptance',
								'Select',
								'Date',
								'Time',
								'File',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_default_value',
			array(
				'label'      => __( 'Default value', 'solace-extra' ),
				'type'       => Controls_Manager::TEXT,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'Text',
								'Email',
								'URL',
								'Tel',
								'Number',
								'Hidden',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_name',
			array(
				'label'       => __( 'Name', 'solace-extra' ),
				'type'        => Controls_Manager::HIDDEN,
				'description' => __( 'Name is required. It is used to send the data to your email.', 'solace-extra' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'reCAPTCHA',
								'HTML',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'width_hr',
			array(
				'type'       => Controls_Manager::DIVIDER,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'reCAPTCHA',
								'Hidden',
							),
						),
					),
				),
			)
		);


		$repeater->add_responsive_control(
			'field_width',
			array(
				'label'      => __( 'Width (%)', 'solace-extra' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min'  => 10,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;', 
				],
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'Hidden',
								'reCAPTCHA',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'required_hr',
			array(
				'type'       => Controls_Manager::DIVIDER,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'Checkbox',
								'reCAPTCHA',
								'Hidden',
								'HTML',
								'Radio',
								'Checkbox',
								'Select',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_required',
			array(
				'label'        => __( 'Required', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'solace-extra' ),
				'label_off'    => __( 'No', 'solace-extra' ),
				'return_value' => 'yes',
				'conditions'   => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'reCAPTCHA',
								'Hidden',
								'HTML',
								'Select',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_checked_by_default',
			array(
				'label'        => __( 'Checked by default', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'solace-extra' ),
				'label_off'    => __( 'No', 'solace-extra' ),
				'return_value' => 'yes',
				'conditions'   => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'Acceptance',
							),
						),
					),
				),
			)
		);		

		$repeater->add_control(
			'class_hr',
			array(
				'type'       => Controls_Manager::DIVIDER,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'reCAPTCHA',
								'Hidden',
								'HTML',
								'Radio',
								'Checkbox',
								'Select',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_class',
			array(
				'label'      => __( 'Custom Class', 'solace-extra' ),
				'type'       => Controls_Manager::TEXT,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'reCAPTCHA',
								'Hidden',
								'HTML',
								'Radio',
								'Checkbox',
								'Acceptance',
								'Select',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_id',
			array(
				'label'      => __( 'Custom ID', 'solace-extra' ),
				'type'       => Controls_Manager::TEXT,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
								'HTML',
								'Radio',
								'Checkbox',
								'Acceptance',
								'Select',
								'reCAPTCHA',
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'fields',
			array(
				'label'       => __( 'Fields', 'solace-extra' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'field_type'  => 'Text',
						'field_name'  => 'name',
						'field_label' => 'Name',
						'field_placeholder' => esc_html__( 'Please enter your name', 'solace-extra' ),
					),
					array(
						'field_type'  => 'Email',
						'field_name'  => 'email',
						'field_label' => 'Email',
						'field_placeholder' => esc_html__( 'Please enter your email', 'solace-extra' ),
					),
					array(
						'field_type'  => 'Textarea',
						'field_name'  => 'message',
						'field_label' => 'Message',
						'field_placeholder' => esc_html__( 'Please enter your message', 'solace-extra' ),
					),
				),
				'title_field' => '{{{ field_type }}}',
			)
		);

		$this->add_control(
			'list_hr',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'show_label',
			array(
				'label'        => __( 'Label', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'solace-extra' ),
				'label_off'    => __( 'Hide', 'solace-extra' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_required_mark',
			array(
				'label'        => __( 'Required Mark', 'solace-extra' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'solace-extra' ),
				'label_off'    => __( 'Hide', 'solace-extra' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'Button', 'solace-extra' ),
			)
		);

	

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Text', 'solace-extra' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Send',
			)
		);

		$this->add_control(
			'button_icon',
			array(
				'label'   => __( 'Icon', 'solace-extra' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-star',
					'library' => 'solid',
				),
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'   => __( 'Icon Position', 'solace-extra' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'  => array(
						'title' => __( 'Left', 'solace-extra' ),
						'icon'  => 'fa fa-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'solace-extra' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default' => 'left',
				'toggle'  => true,
			)
		);

		$this->add_responsive_control(
			'button_column_width',
			array(
				'label'      => __( 'Button Width (%)', 'solace-extra' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => '40',
				'options'    => array(
					'20'   => __( '20%', 'solace-extra' ),
					'25'   => __( '25%', 'solace-extra' ),
					'33'   => __( '33%', 'solace-extra' ),
					'40'   => __( '40%', 'solace-extra' ),
					'50'   => __( '50%', 'solace-extra' ),
					'60'   => __( '60%', 'solace-extra' ),
					'66'   => __( '66%', 'solace-extra' ),
					'75'   => __( '75%', 'solace-extra' ),
					'80'   => __( '80%', 'solace-extra' ),
					'100'  => __( '100%', 'solace-extra' ),
				),
				'selectors'  => [
					'{{WRAPPER}} .solaceform-button-column.inline' =>
						'width: {{VALUE}}%;',
					'{{WRAPPER}} .solaceform-button-column.newline .submit-button' =>
						'width: {{VALUE}}%;',
				],
			)
		);		

		$this->add_responsive_control(
			'button_position',
			[
				'label'   => esc_html__( 'Button Position', 'solace-extra' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'none' => [
						'title' => esc_html__( 'Inline', 'solace-extra' ),
						'icon'  => 'eicon-nowrap',
					],
					'flex-start' => [
						'title' => esc_html__( 'New Line: Left', 'solace-extra' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'New Line: Center', 'solace-extra' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'New Line: Right', 'solace-extra' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default' => 'none',
			]
		);


		$this->add_control(
			'button_id',
			array(
				'label' => __( 'Button ID', 'solace-extra' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_messages',
			array(
				'label' => __( 'Messages', 'solace-extra' ),
			)
		);

		$this->add_control(
			'success_message',
			array(
				'label'   => __( 'Success Message', 'solace-extra' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Your message has been sent', 'solace-extra' ),
				'rows'    => 5,
			)
		);

		$this->add_control(
			'error_message',
			array(
				'label'   => __( 'Error Message', 'solace-extra' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Can\'t send the email', 'solace-extra' ),
				'rows'    => 5,
			)
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_email',
			array(
				'label' => __( 'Email', 'solace-extra' ),
			)
		);

		$this->add_control(
			'email_to',
			array(
				'label' => __( 'To', 'solace-extra' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'email_subject',
			array(
				'label' => __( 'Subject', 'solace-extra' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'subject_hr',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'email_from',
			array(
				'label' => __( 'From Email', 'solace-extra' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'email_name',
			array(
				'label' => __( 'From Name', 'solace-extra' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_label_style',
			[
				'label' => esc_html__( 'Label', 'solace-extra' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Color', 'solace-extra' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solaceform-form label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Typography', 'solace-extra' ),
				'selector' => '{{WRAPPER}} .solaceform-form label',
			]
		);

		$this->add_responsive_control(
			'label_margin',
			[
				'label' => esc_html__( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '5',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false, 
                ],
				'selectors' => [
					'{{WRAPPER}} .solaceform-form label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				// 'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			array(
				'label' => __( 'Fields', 'solace-extra' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		$field_selector = '{{WRAPPER}} .solaceform-style-field';
		$field_selector_focus = '{{WRAPPER}} .solaceform-style-field:focus';
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => $field_selector,
			]
		);

		$this->start_controls_tabs( 'solace_fields_tabs' );

        $this->start_controls_tab(
            'solace_field_tab',
            [
                'label' => __( 'Normal', 'solace-extra' ),
            ]
        );
        


        $this->add_control(
            'solace_field_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $field_selector => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'solace_field_bg_color',
            [
                'label' => __( 'Background Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $field_selector => 'background-color: {{VALUE}}',
                ],
            ]
        );



        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'solace_field_border',
                'label'    => __( 'Border', 'solace-extra' ),
                'selector' => $field_selector,
            ]
        );

        $this->add_control(
            'solace_field_border_radius',
            [
                'label'      => __( 'Border Radius', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                   $field_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'solace_field_box_shadow',
                'selector' => $field_selector,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'solace_field_focus_tab',
            [
                'label' => __( 'Focus', 'solace-extra' ),
            ]
        );
        

        $this->add_control(
            'solace_field_focus_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $field_selector_focus => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'solace_field_focus_bg_color',
            [
                'label' => __( 'Background Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $field_selector_focus => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'solace_field_focus_border',
                'label'    => __( 'Border', 'solace-extra' ),
                'selector' => $field_selector_focus,
            ]
        );

        $this->add_control(
            'solace_field_focus_border_radius',
            [
                'label'      => __( 'Border Radius', 'solace-extra' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    $field_selector_focus => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'solace_field_focus_box_shadow',
                'selector' => $field_selector_focus,
            ]
        );

        $this->add_control(
            'solace_field_transition',
            [
                'label' => __( 'Transition Duration (ms)', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                        'step' => 100,
                    ],
                ],
                'selectors' => [
                   $field_selector_focus => 'transition: all {{SIZE}}ms ease-in-out;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->add_control(
            'field_separator',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

		$this->add_responsive_control(
			'field_padding',
			[
				'label' => __( 'Padding', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					$field_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		

		$this->add_responsive_control(
			'field_margin',
			[
				'label' => __( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '20',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false, 
                ],
				'selectors' => [
					$field_selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		

		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => __( 'Button', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_button_style_controls();

		$this->end_controls_section();
	}
		
	private function get_form_builder_field_selectors( $widget_id, $is_focus = false ) {
		$prefix = '.elementor-element-' . esc_attr( $widget_id );
		
		$selectors = [
			$prefix . ' .solaceform-style-field',
			$prefix . ' input.solaceform-style-field',
			$prefix . ' textarea.solaceform-style-field',
			$prefix . ' select.solaceform-style-field',
			$prefix . ' .solaceform-field-group input',
			$prefix . ' .solaceform-field-group textarea',
			$prefix . ' .solaceform-field-group select',
		];

		if ( $is_focus ) {
			$selectors = array_map( function( $selector ) {
				return $selector . ':focus';
			}, $selectors );
		}

		return implode( ',', $selectors );
	}

	private function generate_typography_style( $settings, $prefix ) {
		$css = '';
		$family = $settings[ $prefix . 'typography_font_family' ] ?? '';
		if ( $family ) $css .= "font-family: \"{$family}\", Sans-serif;";

		$size = $settings[ $prefix . 'typography_font_size' ] ?? '';
		if ( is_array( $size ) && ! empty( $size['size'] ) ) {
			$css .= "font-size: {$size['size']}{$size['unit']};";
		}

		$weight = $settings[ $prefix . 'typography_font_weight' ] ?? '';
		if ( $weight ) $css .= "font-weight: {$weight};";

		$lh = $settings[ $prefix . 'typography_line_height' ] ?? '';
		if ( is_array( $lh ) && ! empty( $lh['size'] ) ) {
			$css .= "line-height: {$lh['size']}{$lh['unit']};";
		}

		return $css;
	}

	private function generate_field_style( $settings, $state = 'normal' ) {
		$css = '';
		$prefix = ( $state === 'focus' ) ? 'form_field_focus_' : 'form_field_';

		// 1. Colors
		$text_color = $settings[ $prefix . 'text_color' ] ?? '';
		$bg_color   = $settings[ $prefix . 'background_color' ] ?? '';
		if ( $text_color ) $css .= "color: {$text_color};";
		if ( $bg_color )   $css .= "background-color: {$bg_color};";

		// 2. Typography (Normal)
		if ( $state === 'normal' ) {
			$css .= $this->generate_typography_style( $settings, 'form_field_' );
		}

		// 3. Border & Radius
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

		$radius = $settings[ $prefix . 'border_radius' ] ?? '';
		if ( is_array( $radius ) && ! empty( $radius['top'] ) ) {
			$unit = $radius['unit'] ?? 'px';
			$css .= "border-radius: {$radius['top']}{$unit} {$radius['right']}{$unit} {$radius['bottom']}{$unit} {$radius['left']}{$unit};";
		}

		// 4. Padding
		$padding = $settings[ $prefix . 'padding' ] ?? '';
		if ( is_array( $padding ) && ! empty( $padding['top'] ) ) {
			$unit = $padding['unit'] ?? 'px';
			$css .= "padding: {$padding['top']}{$unit} {$padding['right']}{$unit} {$padding['bottom']}{$unit} {$padding['left']}{$unit};";
		}

		// 5. Box Shadow
		$shadow = $settings[ $prefix . 'box_shadow_box_shadow' ] ?? '';
		if ( is_array( $shadow ) && ! empty( $shadow['color'] ) ) {
			$ins = ( isset( $shadow['outline'] ) && $shadow['outline'] === 'inset' ) ? 'inset' : '';
			$css .= "box-shadow: {$shadow['horizontal']}px {$shadow['vertical']}px {$shadow['blur']}px {$shadow['spread']}px {$shadow['color']} {$ins};";
		}

		// 6. Transition (Focus)
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
            $prefix .solaceform-form label
        ";
    }


	protected function render() {
		$settings  = $this->get_settings_for_display();
		$is_inline = ( $settings['button_position'] === 'none' );
		?>

		<form class="solaceform-container solaceform-form" method="post" data-post_id="<?php echo esc_attr( get_the_ID() ); ?>" data-el_id="<?php echo esc_attr( $this->get_id() ); ?>" enctype="multipart/form-data">
			<?php foreach ( $settings['fields'] as $i => $item ) :
				$key = $this->get_repeater_setting_key( 'field_label', 'fields', $i );
				$this->add_render_attribute(
					$key,
					'class',
					[
						'solaceform-field-column',
						'elementor-repeater-item-' . $item['_id'],
					]
				);

				$params = array(
					'type'                     => $item['field_type'] ? strtolower( $item['field_type'] ) : '',
					'field_acceptance'         => $item['field_acceptance'] ? strtolower( $item['field_acceptance'] ) : '',
					'field_checked_by_default' => $item['field_checked_by_default'] ? strtolower( $item['field_checked_by_default'] ) : '',
					'label'                    => $item['field_label'] ? $item['field_label'] : '',
					'placeholder'              => $item['field_placeholder'] ? $item['field_placeholder'] : '',
					'value'                    => $item['field_default_value'] ? $item['field_default_value'] : '',
					'name'                     => $item['field_name'] ? $item['field_name'] : '',
					'required'                 => $item['field_required'] ? $item['field_required'] : '',
					'id'                       => $item['field_id'] ? $item['field_id'] : '',
					'class'                    => 'solaceform-input ' . ($item['field_class'] ? $item['field_class'] : ''),
					'rows'                     => $item['rows'] ? $item['rows'] : '',
					'options'                  => $item['field_options'] ? $item['field_options'] : '',
					'inline_list'              => $item['field_inline_list'] ? $item['field_inline_list'] : '',
					'multiple_files'           => $item['multiple_files'] ? $item['multiple_files'] : '',
					'file_types'               => $item['file_types'] ? $item['file_types'] : '',
					'html'                     => $item['field_html'] ? $item['field_html'] : '',
					'is_label'                 => $settings['show_label'] ? true : false,
					'is_mark'                  => $settings['show_required_mark'] ? true : false,
				);
			?>
				<div <?php
				// Elementor generates a pre-escaped attribute string for rendering.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->get_render_attribute_string( $key );
				?>>
					<?php
					switch ( $item['field_type'] ) {
						case 'Text':
						case 'URL':
						case 'Tel':
						case 'Number':
						case 'Date':
						case 'Time':
						case 'File':
						case 'Password':
						case 'Email':
							$this->input( $params );
							break;
						case 'Textarea':
							$this->textarea( $params );
							break;
						case 'Select':
						case 'Checkbox':
						case 'Radio':
							$this->multi( $params );
							break;
						case 'Acceptance':
							$this->acceptance( $params );
							break;
						case 'HTML':
							$this->html( $params['html'], $params['label'], $params['is_label'] );
							break;
						case 'Hidden':
							$this->hidden( $params['value'], $params['name'], $params['id'] );
							break;
						case 'reCAPTCHA':
							$this->reCAPTCHA( $item['site_key'] );
							break;
					}
					?>
				</div>
			<?php endforeach; ?>

			<div
				class="solaceform-button-column <?php echo $is_inline ? 'inline' : 'newline'; ?>"
				style="<?php echo $is_inline ? '' : 'width:100%;display:flex;justify-content:' . esc_attr( $settings['button_position'] ) . ';'; ?>">
				<button type="submit" class="elementor-button submit-button solaceform-form-button" id="<?php echo esc_attr($settings['button_id']); ?>">
					<span class="elementor-button-content-wrapper">
						
						<?php if ( ! empty( $settings['button_icon']['value'] ) && 'left' === $settings['button_icon_position'] ) : ?>
							<span class="elementor-button-icon elementor-align-icon-left">
								<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</span>
						<?php endif; ?>

						<span class="elementor-button-text">
							<?php echo esc_html( $settings['button_text'] ); ?>
						</span>

						<?php if ( ! empty( $settings['button_icon']['value'] ) && 'right' === $settings['button_icon_position'] ) : ?>
							<span class="elementor-button-icon elementor-align-icon-right">
								<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</span>
						<?php endif; ?>

					</span>
				</button>
			</div>
		</form>

		<style>
			.solaceform-container {
				display: flex;
				flex-wrap: wrap;
				width: 100%;
			}

			.solaceform-field-column,
			.solaceform-button-column,
			.solaceform-input,
			.submit-button {
				box-sizing: border-box;
			}

			.solaceform-input,
			.submit-button {
				width: 100%;
				white-space: nowrap;
			}
		</style>
		<?php
	}
}
