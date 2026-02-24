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
				'separator' => 'before',
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
				'label'      => __( 'Column Width', 'solace-extra' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => '100',
				'options'    => array(
					'20'  => __( '20%', 'solace-extra' ),
					'25'  => __( '25%', 'solace-extra' ),
					'33'  => __( '33%', 'solace-extra' ),
					'40'  => __( '40%', 'solace-extra' ),
					'50'  => __( '50%', 'solace-extra' ),
					'60'  => __( '60%', 'solace-extra' ),
					'66'  => __( '66%', 'solace-extra' ),
					'75'  => __( '75%', 'solace-extra' ),
					'80'  => __( '80%', 'solace-extra' ),
					'100' => __( '100%', 'solace-extra' ),
				),
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

		// $this->add_control(
		// 	'button_text_align',
		// 	array(
		// 		'label'     => __( 'Alignment', 'solace-extra' ),
		// 		'type'      => Controls_Manager::CHOOSE,
		// 		'options'   => array(
		// 			'left'   => array(
		// 				'title' => __( 'Left', 'solace-extra' ),
		// 				'icon'  => 'fa fa-align-left',
		// 			),
		// 			'center' => array(
		// 				'title' => __( 'Center', 'solace-extra' ),
		// 				'icon'  => 'fa fa-align-center',
		// 			),
		// 			'right'  => array(
		// 				'title' => __( 'Right', 'solace-extra' ),
		// 				'icon'  => 'fa fa-align-right',
		// 			),
		// 		),
		// 		'default'   => 'center',
		// 		'toggle'    => true,
		// 		'selectors' => array(
		// 			'{{WRAPPER}} .solaceform-form-button-wrap' => 'text-align: {{VALUE}}',
		// 		),
		// 	)
		// );

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
				'label'      => __( 'Column Width', 'solace-extra' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => '33',
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
			)
		);		

		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Position', 'solace-extra' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'solace-extra' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'solace-extra' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'solace-extra' ),
						'icon' => 'eicon-h-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'solace-extra' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .solaceform-form-button-wrap .box-button' => 'text-align: {{VALUE}};',
				),
				'render_type' => 'template',
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

		// $this->start_controls_section(
		// 	'section_redirect',
		// 	array(
		// 		'label' => __( 'Redirect', 'solace-extra' ),
		// 	)
		// );

		// $this->add_control(
		// 	'redirect',
		// 	array(
		// 		'label'        => __( 'Redirect to another URL', 'solace-extra' ),
		// 		'type'         => Controls_Manager::SWITCHER,
		// 		'label_on'     => __( 'Yes', 'solace-extra' ),
		// 		'label_off'    => __( 'No', 'solace-extra' ),
		// 		'return_value' => 'yes',
		// 	)
		// );

		// $this->add_control(
		// 	'redirect_url',
		// 	array(
		// 		'label'       => __( 'Redirect To', 'solace-extra' ),
		// 		'type'        => Controls_Manager::TEXT,
		// 		'placeholder' => __( 'https://your-link.com', 'solace-extra' ),
		// 		'condition'   => array(
		// 			'redirect' => 'yes',
		// 		),
		// 	)
		// );

		// $this->end_controls_section();

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
					'{{WRAPPER}} .solaceform-fields label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Typography', 'solace-extra' ),
				'selector' => '{{WRAPPER}} .solaceform-fields label',
			]
		);

		$this->add_responsive_control(
			'label_margin',
			[
				'label' => esc_html__( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .solaceform-fields label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		
		$field_selector = '{{WRAPPER}} .solaceform-style-field, {{WRAPPER}} .solace-rico';
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => $field_selector,
			]
		);
		
		$this->add_control(
			'field_color',
			[
				'label' => __( 'Color', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000', 
				'selectors' => [
					$field_selector => 'color: {{VALUE}} !important;',
				],
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
				'selectors' => [
					'{{WRAPPER}} .solaceform-fields' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'field_border',
				'selector' => $field_selector,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'field_background',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => $field_selector,
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

	/**
	 * Render the widget output on the frontend.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render2() {
		$settings = $this->get_settings_for_display();
		$fields   = $settings['fields'];

		
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
				echo '.elementor-element-' . esc_attr( $widget_id ) . ' select.solaceform-style-field {';

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
		?>

		<form class="solaceform-form" method="post" data-post_id="<?php echo esc_attr( get_the_ID() ); ?>" data-el_id="<?php echo esc_attr( $this->get_id() ); ?>" enctype="multipart/form-data">
		<?php
		if ( $fields ) {
			foreach ( $fields as $field ) {

				$width  = isset( $field['field_width'] ) ? $field['field_width'] : '';
				$width_tablet  = isset( $field['field_width_tablet'] ) ? $field['field_width_tablet'] : '';
				$width_mobile  = isset( $field['field_width_mobile'] ) ? $field['field_width_mobile'] : '';

				$params = array(
					'type'           => $field['field_type'] ? strtolower( $field['field_type'] ) : '',
					'field_acceptance' => $field['field_acceptance'] ? strtolower( $field['field_acceptance'] ) : '',
					'field_checked_by_default' => $field['field_checked_by_default'] ? strtolower( $field['field_checked_by_default'] ) : '',
					'label'          => $field['field_label'] ? $field['field_label'] : '',
					'placeholder'    => $field['field_placeholder'] ? $field['field_placeholder'] : '',
					'value'          => $field['field_default_value'] ? $field['field_default_value'] : '',
					'name'           => $field['field_name'] ? $field['field_name'] : '',
					'width'          => isset( $field['field_width'] ) ? $field['field_width'] : '',
					'width_tablet'   => isset( $field['field_width_tablet'] ) ? $field['field_width_tablet'] : '',
					'width_mobile' => isset( $field['field_width_mobile'] ) ? $field['field_width_mobile'] : '',
					'required'       => $field['field_required'] ? $field['field_required'] : '',
					'id'             => $field['field_id'] ? $field['field_id'] : '',
					'class'          => $field['field_class'] ? $field['field_class'] : '',
					'rows'           => $field['rows'] ? $field['rows'] : '',
					'options'        => $field['field_options'] ? $field['field_options'] : '',
					'inline_list'     => $field['field_inline_list'] ? $field['field_inline_list'] : '',
					'multiple_files' => $field['multiple_files'] ? $field['multiple_files'] : '',
					'file_types'     => $field['file_types'] ? $field['file_types'] : '',
					'html'           => $field['field_html'] ? $field['field_html'] : '',
					'is_label'       => $settings['show_label'] ? true : false,
					'is_mark'        => $settings['show_required_mark'] ? true : false,
				);

				echo '<div class="solaceform-fields elementor-repeater-item-' . esc_attr( $field['_id'] ) . ' efb-field-width-' . esc_attr( $width ) . ' efb-field-width-tablet-' . esc_attr( $width_tablet) . ' efb-field-width-mobile-' . esc_attr( $width_mobile) .'">';



				switch ( $field['field_type'] ) {
					case 'Text':
					case 'URL':
					case 'Tel':
					case 'Number':
					case 'Date':
					case 'Time':
					case 'File':
					case 'Password':
					case 'Email': {
						$this->input( $params );
						break;
					}

					case 'Textarea': {
						$this->textarea( $params );
						break;
					}

					case 'Select':
					case 'Checkbox':
					case 'Radio': {
						$this->multi( $params );
						break;
					}

					case 'Acceptance': {
						$this->acceptance( $params );
						break;
					}

					case 'HTML': {
						$this->html( $params['html'], $params['label'], $params['is_label'] );
						break;
					}

					case 'Hidden': {
						$this->hidden( $params['value'], $params['name'], $params['id'], );
						break;
					}

					case 'reCAPTCHA': {
						$this->reCAPTCHA( $field['site_key'] );

						break;
					}

					default:
						break;
				}

				echo '</div>';
			}
		}

		$this->button(
			$settings,
			$settings['button_text'],
			$settings['button_icon'],
			$settings['button_icon_position'],
			$settings['button_id'],
			$settings['button_column_width']
		);
		?>

		</form>
		<?php
	}

	private function get_form_builder_field_selectors( $widget_id, $is_focus = false ) {
		$prefix = '.elementor-element-' . esc_attr( $widget_id );
		
		$selectors = [
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


	protected function render() {
		$settings = $this->get_settings_for_display();
		$fields   = $settings['fields'];

		
		$kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();

		if ( ! $kit_id ) return;

		$kit_settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
		if ( ! is_array( $kit_settings ) ) return;

		static $style_printed = [];
		$widget_id = $this->get_id();
		if ( isset( $style_printed[ $widget_id ] ) ) return;

		$normal_styles = $this->generate_field_style( $kit_settings, 'normal' );
		$focus_styles  = $this->generate_field_style( $kit_settings, 'focus' );

		if ( empty( $normal_styles ) && empty( $focus_styles ) ) return;

		echo '<style>';
		if ( ! empty( $normal_styles ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->get_form_builder_field_selectors( $widget_id, false ) . " { {$normal_styles} }";
		}
		if ( ! empty( $focus_styles ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->get_form_builder_field_selectors( $widget_id, true ) . " { {$focus_styles} }";
		}
		echo '</style>';

		$style_printed[ $widget_id ] = true;
		?>

		<form class="solaceform-form" method="post" data-post_id="<?php echo esc_attr( get_the_ID() ); ?>" data-el_id="<?php echo esc_attr( $this->get_id() ); ?>" enctype="multipart/form-data">
		<?php
		if ( $fields ) {
			foreach ( $fields as $field ) {

				$width  = isset( $field['field_width'] ) ? $field['field_width'] : '';
				$width_tablet  = isset( $field['field_width_tablet'] ) ? $field['field_width_tablet'] : '';
				$width_mobile  = isset( $field['field_width_mobile'] ) ? $field['field_width_mobile'] : '';

				$params = array(
					'type'           => $field['field_type'] ? strtolower( $field['field_type'] ) : '',
					'field_acceptance' => $field['field_acceptance'] ? strtolower( $field['field_acceptance'] ) : '',
					'field_checked_by_default' => $field['field_checked_by_default'] ? strtolower( $field['field_checked_by_default'] ) : '',
					'label'          => $field['field_label'] ? $field['field_label'] : '',
					'placeholder'    => $field['field_placeholder'] ? $field['field_placeholder'] : '',
					'value'          => $field['field_default_value'] ? $field['field_default_value'] : '',
					'name'           => $field['field_name'] ? $field['field_name'] : '',
					'width'          => isset( $field['field_width'] ) ? $field['field_width'] : '',
					'width_tablet'   => isset( $field['field_width_tablet'] ) ? $field['field_width_tablet'] : '',
					'width_mobile' => isset( $field['field_width_mobile'] ) ? $field['field_width_mobile'] : '',
					'required'       => $field['field_required'] ? $field['field_required'] : '',
					'id'             => $field['field_id'] ? $field['field_id'] : '',
					'class'          => $field['field_class'] ? $field['field_class'] : '',
					'rows'           => $field['rows'] ? $field['rows'] : '',
					'options'        => $field['field_options'] ? $field['field_options'] : '',
					'inline_list'     => $field['field_inline_list'] ? $field['field_inline_list'] : '',
					'multiple_files' => $field['multiple_files'] ? $field['multiple_files'] : '',
					'file_types'     => $field['file_types'] ? $field['file_types'] : '',
					'html'           => $field['field_html'] ? $field['field_html'] : '',
					'is_label'       => $settings['show_label'] ? true : false,
					'is_mark'        => $settings['show_required_mark'] ? true : false,
				);

				echo '<div class="solaceform-fields elementor-repeater-item-' . esc_attr( $field['_id'] ) . ' efb-field-width-' . esc_attr( $width ) . ' efb-field-width-tablet-' . esc_attr( $width_tablet) . ' efb-field-width-mobile-' . esc_attr( $width_mobile) .'">';



				switch ( $field['field_type'] ) {
					case 'Text':
					case 'URL':
					case 'Tel':
					case 'Number':
					case 'Date':
					case 'Time':
					case 'File':
					case 'Password':
					case 'Email': {
						$this->input( $params );
						break;
					}

					case 'Textarea': {
						$this->textarea( $params );
						break;
					}

					case 'Select':
					case 'Checkbox':
					case 'Radio': {
						$this->multi( $params );
						break;
					}

					case 'Acceptance': {
						$this->acceptance( $params );
						break;
					}

					case 'HTML': {
						$this->html( $params['html'], $params['label'], $params['is_label'] );
						break;
					}

					case 'Hidden': {
						$this->hidden( $params['value'], $params['name'], $params['id'], );
						break;
					}

					case 'reCAPTCHA': {
						$this->reCAPTCHA( $field['site_key'] );

						break;
					}

					default:
						break;
				}

				echo '</div>';
			}
		}

		$this->button(
			$settings,
			$settings['button_text'],
			$settings['button_icon'],
			$settings['button_icon_position'],
			$settings['button_id'],
			$settings['button_column_width']
		);
		?>

		</form>
		<?php
	}
}
