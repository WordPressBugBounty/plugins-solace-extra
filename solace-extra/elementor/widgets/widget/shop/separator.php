<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

class Solace_Extra_WooCommerce_Separator extends Widget_Base {

    public function get_name() {
        return 'solace_extra_separator';
    }

    public function get_title() {
        return __( 'Separator', 'solace-extra' );
    }

    public function get_icon() {
        return 'eicon-divider solace-extra'; 
    }

    public function get_categories() {
        return [ 'solace-extra' ]; 
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'separator_style_section',
            [
                'label' => __( 'Separator Style', 'solace-extra' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'separator_border_color',
                'label' => __( 'Border', 'solace-extra' ),
                'selector' => '{{WRAPPER}} hr',
            ]
        );

        $this->add_responsive_control(
            'separator_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} hr' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} hr' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        ?>
        <hr class="separator" />
        <?php
    }
}
