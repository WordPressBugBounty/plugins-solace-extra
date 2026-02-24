<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Solace_Extra_Post_Full_Content extends Widget_Base {

    public function get_name() {
        return 'solace_extra_post_full_content';
    }

    public function get_title() {
        return __('Full Content', 'solace-extra');
    }

    public function get_icon() {
        return 'eicon-post-content solace-extra';
    }

    public function get_categories() {
        return [ 'solace-extra-single-post' ];
    }

    public function get_keywords() {
        return ['post', 'content', 'full', 'single', 'solace'];
    }

    protected function register_controls() {
        // Content Controls
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'solace-extra'),
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Show Excerpt Instead?', 'solace-extra'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'solace-extra'),
                'label_off' => __('No', 'solace-extra'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'excerpt_style_section',
            [
                'label' => __( 'Excerpt', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .solace-post-excerpt',
            ]
        );

        // Text Color
        $this->add_control(
            'excerpt_color',
            [
                'label'     => __( 'Text Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Text Alignment
        $this->add_responsive_control(
            'excerpt_alignment',
            [
                'label'     => __( 'Alignment', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __( 'Left', 'solace-extra' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'solace-extra' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'solace-extra' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-excerpt' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'excerpt_border',
                'selector' => '{{WRAPPER}} .solace-post-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_border_radius',
            [
                'label' => __( 'Border Radius', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-excerpt' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'excerpt_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        // Style Controls
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'solace-extra'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label' => esc_html__('Alignment', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'solace-extra'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'solace-extra'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__('Right', 'solace-extra'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-content' => 'text-align: {{VALUE}};',
                    // '{{WRAPPER}} .solace-post-content' => 'justify-content: {{VALUE}};',
                ],
                'default' => 'center',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .solace-post-content',
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .solace-post-content',
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

        if ( isset( $settings['show_excerpt'] ) && $settings['show_excerpt'] === 'yes' ) {
            $excerpt = $post_object->post_excerpt;

            if ( empty( $excerpt ) ) {
                $excerpt = wp_trim_words( $post_object->post_content, 55, '...' );
            }

            echo '<div class="solace-widget-elementor solace-post-excerpt">';
            echo wp_kses_post( $excerpt );
            echo '</div>';
        }

        $content = wpautop( do_shortcode( $post_object->post_content ) );

        echo '<div class="solace-widget-elementor solace-post-content">';
        echo wp_kses_post( $content );
        echo '</div>';
    }



}
