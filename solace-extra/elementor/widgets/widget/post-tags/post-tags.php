<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;

class Solace_Extra_Post_Tags extends Widget_Base {

	public function get_name() {
		return 'solace_post_tags';
	}

	public function get_title() {
		return __( 'Post Tags', 'solace-extra' );
	}

	public function get_icon() {
		return 'eicon-meta-data solace-extra';
	}

	public function get_categories() {
		return [ 'solace-extra-single-post' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'solace-extra' ),
			]
		);

		$this->add_responsive_control(
			'align',		
			[
				'label' => __( 'Alignment', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'solace-extra' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'solace-extra' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'solace-extra' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .solace-post-tags' => 'display: flex; justify-content: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Icon', 'solace-extra' ),
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
					'{{WRAPPER}} .solace-post-tags i' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .solace-post-tags i' => 'font-size: {{SIZE}}px;',
				],
			]
		);

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => esc_html__('Icon Spacing', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0, 'max' => 5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-tags i' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_font_style',
			[
				'label' => __( 'Style', 'solace-extra' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .solace-post-tags a',
			]
		);

		$this->add_control(
			'tag_color',
			[
				'label' => __( 'Text Color', 'solace-extra' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-post-tags a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
            'separator_spacing',
            [
                'label' => esc_html__('Separator Spacing', 'solace-extra'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0, 'max' => 5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-tags a' => 'margin-right: {{SIZE}}{{UNIT}};margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );




		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
        $post_id = solace_get_preview_post();

        $post_object = get_post( $post_id );

        if ( ! $post_object || empty( $post_object->post_title ) ) return;


        $tags = get_the_tags( $post_id );
        if ( ! $tags || is_wp_error($tags) ) return;

        echo '<div class="solace-post-tags">';
		$icon_html = '';

        if ( ! empty( $settings['style_icon']['value'] ) ) {
            $icon_html = '<span class="separator"><i class="' . esc_attr( $settings['style_icon']['value'] ) . '"></i></span>';
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $icon_html;
        }

        $tag_links = [];
        $last_index = count($tags) - 1;
        foreach ( $tags as $index => $tag ) {
            $comma = $index < $last_index ? ', ' : ' ';
            $tag_links[] = '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" rel="tag">' . esc_html( $tag->name ) . $comma . '</a>';
        }
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo implode( ' ', $tag_links );

        echo '</div>';?>
        <style>
            .solace-post-tags {
                border-left: none !important;
                border-right: none  !important;
                display: flex;
                align-items: center;
            }

        </style>

        <?php
    }

}
