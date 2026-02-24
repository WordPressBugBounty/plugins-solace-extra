<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Solace_Extra_Post_Comments extends Widget_Base {

    public function get_name() {
        return 'solace_extra_post_comments';
    }

    public function get_title() {
        return __( 'Post Comments', 'solace-extra' );
    }

    public function get_icon() {
        return 'eicon-comments solace-extra';
    }

    public function get_categories() {
		return [ 'solace-extra-single-post' ];
    }

    public function get_keywords() {
        return [ 'comments', 'comment', 'post', 'discussion' ];
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
		$selector = '{{WRAPPER}} .solace-comments input[type="submit"]';

		$selector_hover = '{{WRAPPER}} .solace-comments input[type="submit"]:hover'; 

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

    protected function register_controls() {


        $this->start_controls_section(
            'title_style',
            [
                'label' => __( 'Title', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // === Group: Form Text ===
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_text_typography',
                'label' => __( 'Typography', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .solace-comments .comments-title',
                
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .solace-comments .comments-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'comments_title_format',
            [
                'label' => __( 'Title Format', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '%d thoughts on "%s"',
                /* translators: 1: number of comments placeholder, 2: post title placeholder */
                'description' => __( 'Use %1$d for number of comments and %2$s for post title.', 'solace-extra' ),
            ]
        );

        $this->add_responsive_control(
            'comments_title_alignment',
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
                    '{{WRAPPER}} .solace-comments .comments-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => 80,
                    'left' => '',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-comments .comments-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        

        $this->end_controls_section();

        // === Section: Style ===
        $this->start_controls_section(
            'author_style',
            [
                'label' => __( 'Author', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // === Group: Form Text ===
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_text_typography',
                'label' => __( 'Typography', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .solace-comments .comment-author a',
                
            ]
        );

        $this->add_control(
            'author_text_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .solace-comments .comment-author a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'author_text_color_hover',
            [
                'label' => __( 'Text Hover Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .solace-comments .comment-author a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'date_style',
            [
                'label' => __( 'Date', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // === Group: Form Text ===
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'date_text_typography',
                'label' => __( 'Typography', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .comment-metadata',
            ]
        );

        $this->add_control(
            'date_text_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-comments .comment-metadata a' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .solace-comments .comment-metadata a time' => 'color: {{VALUE}} !important;',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'comments_style',
            [
                'label' => __( 'Comments', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'comments_text_typography',
                'label' => __( 'Typography', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .solace-comments .comment-list .comment-content',
            ]
        );

        $this->add_control(
            'comments_text_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-comments .comment-list .comment-content p' => 'color: {{VALUE}} !important;',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'reply_style',
            [
                'label' => __( 'Reply Title', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // === Group: Form Reply ===
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => '_reply_typography',
                'label' => __( 'Typography', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .solace-comments .comment-reply-title',
            ]
        );

        $this->add_control(
            'form_reply_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-comments .comment-respond .comment-reply-title,
                    {{WRAPPER}} .solace-comments .comment-respond p,
                    {{WRAPPER}} .solace-comments .comment-respond p label,
                    {{WRAPPER}} .solace-comments .comment-respond label' => 'color: {{VALUE}} !important;',
                ],
            ]
        );



        $this->end_controls_section();

        $this->start_controls_section(
            'indent_style',
            [
                'label' => __( 'Indent', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'reply_indent',
            [
                'label' => __( 'Reply Indent', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ,'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-comments .children' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // === Section: Style ===
        $this->start_controls_section(
            'button_style',
            [
                'label' => __( 'Button', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_button_style_controls();

        $this->end_controls_section();
    }

    protected function render() {
        $post_id = solace_get_preview_post();
        $settings = $this->get_settings_for_display();
        $post_object = get_post( $post_id );

        if ( ! $post_object || empty( $post_object->post_title ) ) {
            echo '<div class="solace-comments-message">' . esc_html__( 'No valid post found.', 'solace-extra' ) . '</div>';
            return;
        }

        $comments_number = get_comments_number( $post_id );
        $post_title = get_the_title( $post_id );

        $format = ! empty( $settings['comments_title_format'] ) ? $settings['comments_title_format'] : '%d thoughts on "%s"';
        $comments_title = sprintf( $format, $comments_number, $post_title );

        global $post;
        $previous_post = $post;
        $post = $post_object;
        setup_postdata( $post );

        echo '<div class="solace-comments">';

        echo '<h2 class="comments-title">';
        echo esc_html( $comments_title );
        echo '</h2>';

        ob_start();
        // comments_template();
        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';

        $filter = function( $defaults ) use ( $hover_animation ) {
            $extra_classes = ' solace-extra-button elementor-button';
            if ( $hover_animation ) {
                $extra_classes .= ' elementor-animation-' . $hover_animation;
            }
            if ( isset( $defaults['class_submit'] ) ) {
                $defaults['class_submit'] .= $extra_classes;
            } else {
                $defaults['class_submit'] = trim( $extra_classes );
            }
            return $defaults;
        };

        add_filter( 'comment_form_defaults', $filter );

        comments_template();

        remove_filter( 'comment_form_defaults', $filter );
        $comments_html = ob_get_clean();

        $comments_html = preg_replace( '/<h2 class="comments-title">.*?<\/h2>/', '', $comments_html );

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $comments_html;

        echo '</div>';

        wp_reset_postdata();
        $post = $previous_post;
        ?>
        <style>
            .solace-comments .comments-area .comments-title {
                display: none;
            }
        </style>
    <?php
    }



}
