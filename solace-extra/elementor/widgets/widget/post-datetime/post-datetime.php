<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Solace_Extra_Post_Date_Time extends Widget_Base {

    public function get_name() {
        return 'solace_post_time_created';
    }

    public function get_title() {
        return 'Post Date Time';
    }

    public function get_icon() {
        return 'eicon-countdown solace-extra'; // Outline-style clock icon
    }

    public function get_categories() {
		return [ 'solace-extra-single-post' ];
    }

    protected function register_controls() {

        // === CONTENT TAB === //
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label'   => __( 'HTML Tag', 'solace-extra' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'span',
                'options' => [
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                    'h1'   => 'h1',
                    'h2'   => 'h2',
                    'h3'   => 'h3',
                    'h4'   => 'h4',
                    'h5'   => 'h5',
                    'h6'   => 'h6',
                ],
            ]
        );

        
        // === Show/Hide Date ===
        $this->add_control(
            'show_date',
            [
                'label' => __( 'Show Date', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'solace-extra' ),
                'label_off' => __( 'Hide', 'solace-extra' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'show_time',
            [
                'label' => __( 'Show Time', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'solace-extra' ),
                'label_off' => __( 'Hide', 'solace-extra' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // === Date Format ===
        $this->add_control(
            'date_format',
            [
                'label' => __( 'Date Format', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => get_option( 'date_format' ),
                'options' => [
                    get_option( 'date_format' ) => sprintf( 
                        /* translators: %s: WordPress date format example */
                        __( 'WordPress Default (%s)', 'solace-extra' ), 
                        date_i18n( get_option( 'date_format' ) ) 
                    ),
                    'F j, Y' => date_i18n( 'F j, Y' ), // July 1, 2025
                    'Y-m-d' => date_i18n( 'Y-m-d' ),   // 2025-07-01
                    'M j, Y' => date_i18n( 'M j, Y' ), // Jul 1, 2025
                    'm/d/Y'  => date_i18n( 'm/d/Y' ),  // 07/01/2025
                    'd/m/Y'  => date_i18n( 'd/m/Y' ),  // 01/07/2025
                    'd.m.Y'  => date_i18n( 'd.m.Y' ),  // 01.07.2025
                    'custom' => __( 'Custom', 'solace-extra' ),
                ],
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        // === Custom Date Format ===
        $this->add_control(
            'custom_date_format',
            [
                'label' => __( 'Custom Date Format', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => sprintf(
                    /* translators: %s: URL to PHP date formatting documentation */
                    __( 'See <a href="%s" target="_blank">date() formatting</a>.', 'solace-extra' ),
                    'https://www.php.net/manual/en/function.date.php'
                ),
                'condition' => [
                    'show_date' => 'yes',
                    'date_format' => 'custom',
                ],
            ]
        );

        // === Time Format ===
        $this->add_control(
            'time_format',
            [
                'label' => __( 'Time Format', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => get_option( 'time_format' ),
                'options' => [
                    get_option( 'time_format' ) => sprintf( 
                        /* translators: %s: WordPress time format example */
                        __( 'WordPress Default (%s)', 'solace-extra' ), 
                        date_i18n( get_option( 'time_format' ) ) 
                    ),
                    'H:i' => date_i18n( 'H:i' ),     // 14:35
                    'g:i a' => date_i18n( 'g:i a' ), // 2:35 pm
                    'h:i A' => date_i18n( 'h:i A' ), // 02:35 PM
                    'custom'  => __( 'Custom', 'solace-extra' ),
                ],
                'condition' => [
                    'show_time' => 'yes',
                ],
            ]
        );

        // === Custom Time Format ===
        $this->add_control(
            'custom_time_format',
            [
                'label' => __( 'Custom Time Format', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => sprintf(
                    /* translators: %s: URL to PHP date formatting documentation */
                    __( 'See <a href="%s" target="_blank">date() formatting</a>.', 'solace-extra' ),
                    'https://www.php.net/manual/en/function.date.php'
                ),
                'condition' => [
                    'show_time' => 'yes',
                    'time_format' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();

        // === STYLE TAB === //
        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'solace-extra' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'style_icon',
            [
                'label' => __( 'Icon Style', 'solace-extra' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-post-time span i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'solace-extra' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ,'em'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .solace-post-time i' => 'font-size: {{SIZE}}px;',
				],
			]
		);

        $this->add_control(
            'icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'solace-extra' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ,'em'],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default' => [
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-time' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-time' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .solace-post-time svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .solace-post-time',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $post_id = solace_get_preview_post();
        $post_object = get_post( $post_id );

        if ( ! $post_object ) {
            return;
        }

        $tag = $settings['html_tag'] ?: 'span';

        echo '<' . esc_attr( $tag ) . ' class="solace-post-time" style="display: flex; align-items: center;">';

        if ( ! empty( $settings['style_icon']['value'] ) ) {
            echo '<span class="separator"><i class="' . esc_attr( $settings['style_icon']['value'] ) . '"></i></span>';
        }

        $timestamp = get_post_time( 'U', true, $post_id );

        $output = [];

        // === Date ===
        if ( 'yes' === $settings['show_date'] ) {
            $date_format = ( 'custom' === $settings['date_format'] && ! empty( $settings['custom_date_format'] ) )
                ? $settings['custom_date_format']
                : $settings['date_format'];

            $output[] = date_i18n( $date_format, $timestamp );
        }

        // === Time ===
        $time_format = ( 'custom' === $settings['time_format'] && ! empty( $settings['custom_time_format'] ) )
            ? $settings['custom_time_format']
            : $settings['time_format'];

        if ( ! empty( $time_format ) ) {
            $output[] = date_i18n( $time_format, $timestamp );
        }

        // Output final
        echo esc_html( implode( ' ', $output ) );

        echo '</' . esc_attr( $tag ) . '>';
    }

}
