<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Post Categories Widget.
 *
 * Widget Elementor yang menampilkan daftar kategori yang terkait dengan pos atau halaman.
 *
 * @since 1.0.0
 */
class Solace_Extra_Post_Categories extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @since 1.0.0
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'solace-extra-post-categories';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Post Categories', 'solace-extra' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list solace-extra';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve widget categories.
	 *
	 * @since 1.0.0
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'solace-extra-single-post' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'solace', 'extra', 'post', 'excerpt' ];
	}	

	/**
	 * Register widget controls.
	 *
	 * Add kontrol categories, setting HTML tag, dan layout.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {

		// $this->start_controls_section(
		// 	'content_section',
		// 	[
		// 		'label' => esc_html__( 'Content', 'solace-extra' ),
		// 		'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		// 	]
		// );

		// $this->end_controls_section();

		$this->start_controls_section(
			'style_content_section',
			[
				'label' => esc_html__( 'Style', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		// Alignment Control
		$this->add_responsive_control(
			'categories_alignment',
			[
				'label' => esc_html__( 'Alignment', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'solace-extra' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'solace-extra' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'solace-extra' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .solace-post-categories' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		// Text Color Control
		$this->add_control(
			'categories_text_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-post-categories a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'categories_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-post-categories li.solace-category-item a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		// Typography Control
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .solace-post-categories',
			]
		);

		$this->add_control(
			'categories_border_radius',
			[
				'label' => __( 'Border Radius', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%'],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 200 ],
				],
				'default' => [
					'size' => 5,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-post-categories ul li a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'categories_content_spacing',
			[
				'label' => __( 'Spacing', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%'],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'default' => [
					'size' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-post-categories ul.solace-categories-list' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
            'categories_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-categories ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'categories_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-categories ul li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		
		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$post_id = solace_get_preview_post();
		$post_object = get_post( $post_id );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$checkempty = solace_check_empty_post( $post_id );
			if ( $checkempty ) {
				echo '<div class="solace-empty-post">' . esc_html( $checkempty ) . '</div>';
				return;
			}
		}

		global $post;
		$previous_post = $post;
		$post = $post_object;
		setup_postdata( $post );
		$categories = get_the_category();?>
		<div class='solace-post-categories'>
			<ul class="solace-categories-list">
				<?php 
				if ( ! empty( $categories ) ) {
					foreach ( $categories as $category ) {
						printf(
							'<li class="solace-category-item"><a href="%s">%s</a></li>',
							esc_url( get_category_link( $category->term_id ) ),
							esc_html( $category->name )
						);
					}
				} else {
					echo '<p>' . esc_html__( 'No categories assigned.', 'solace-extra' ) . '</p>';
				}?>
			</ul>
		</div>
		<style>
			.solace-post-categories {
				font-weight: 600;
			}
			ul.solace-categories-list {
				display: flex;
				margin: 0;
				padding: 0;
				list-style:none;
				gap: 10px;
			}
			li.solace-category-item {
				list-style: none;
				margin: 0;
				padding: 0;
			}
			li.solace-category-item a {
				align-items: center;
    			display: flex;
				background-color: #000000;
				border-radius: 5px;
				padding: 2px 10px 2px 10px;
    			color: #FFFFFF;
			}
			
		</style>
		<?php
	}

}
