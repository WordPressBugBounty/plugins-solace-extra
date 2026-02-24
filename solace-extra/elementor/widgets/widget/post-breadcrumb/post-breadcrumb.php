<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) exit;

class Solace_Extra_Post_Breadcrumb extends Widget_Base {

    public function get_name() {
        return 'solace-post-breadcrumb';
    }

    public function get_title() {
        return __( 'Post Breadcrumb', 'solace-extra' );
    }

    public function get_icon() {
        return 'eicon-post-navigation solace-extra';
    }

    public function get_categories() {
		return [ 'solace-extra-single-post' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'solace-extra' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => __( 'Alignment', 'solace-extra' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'solace-extra' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'solace-extra' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'solace-extra' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .solace-post-breadcrumb' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => __( 'Post Name Color', 'solace-extra' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-breadcrumb' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'path_color',
            [
                'label' => __( 'Path Color', 'solace-extra' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-breadcrumb a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'breadcrumb_typography',
                'label' => __( 'Typography', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .solace-post-breadcrumb',
            ]
        );


        $this->add_control(
            'separator_icon',
            [
                'label' => __( 'Separator Icon', 'solace-extra' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => __( 'Separator Color', 'solace-extra' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-breadcrumb .separator' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_spacing',
            [
                'label' => __( 'Separator Spacing', 'solace-extra' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ,'em'],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-breadcrumb .separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-breadcrumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-breadcrumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();
    }


    public function solace_render_post_breadcrumb_empty() {
        echo '
        <div class="solace-post-breadcrumb">
            <a href="#">Home</a>
            <span class="separator"><i class="fas fa-angle-right"></i></span>
            <a href="#">Post</a>
            <span class="separator"><i class="fas fa-angle-right"></i></span>
            No post are available
        </div>';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $post_id = solace_get_preview_post();
        $post_object = get_post( $post_id );

        if ( ! $post_object || empty( $post_object->post_title ) ) return;

        $icon_html = '';

        if ( ! empty( $settings['separator_icon']['value'] ) ) {
            $icon_html = '<span class="separator"><i class="' . esc_attr( $settings['separator_icon']['value'] ) . '"></i></span>';
        }

        echo '<div class="solace-post-breadcrumb">';

        // Home link
        echo '<a href="' . esc_url( home_url( '/' ) ) . '">Home</a>';

        // Category (first only)
        $categories = get_the_category( $post_id );
        if ( ! empty( $categories ) ) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $icon_html . '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
        }

        // Post title
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $icon_html . esc_html( get_the_title( $post_id ) );

        echo '</div>';
    }

}
