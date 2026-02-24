<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Post Author Widget.
 *
 * Widget Elementor yang menampilkan nama penulis dari pos atau halaman.
 *
 * @since 1.0.0
 */
class Solace_Extra_Post_Author extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @since 1.0.0
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'solace-extra-post-author';
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
		return esc_html__( 'Post Author', 'solace-extra' );
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
		return 'eicon-person solace-extra';
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
		return [ 'solace', 'extra', 'post', 'author' ];
	}	    

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'solace_extra_html_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'div' => 'DIV',
					'span' => 'SPAN',
					'p' => 'P',
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'div',
			]
		);

		$this->add_control(
			'show_bio',
			[
				'label' => __( 'Show Author Bio', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'solace-extra' ),
				'label_off' => __( 'No', 'solace-extra' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label' => __( 'Show Author Avatar', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'solace-extra' ),
				'label_off' => __( 'No', 'solace-extra' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'view_all_label',
			[
				'label' => __( 'View All Articles Label', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'View All Articles', 'solace-extra' ),
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_layout_name',
			[
				'label' => __( 'Avatar', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'avatar_border_radius',
			[
				'label' => __( 'Border Radius', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 200 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-avatar img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'avatar_content_spacing',
			[
				'label' => __( 'Spacing', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','em'],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'avatar_width',
			[
				'label' => __( 'Width', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ,'em'],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 200 ],
					
				],
				'default' => [
					'size' => 68,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-avatar img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_vertical_alignment',
			[
				'label' => __( 'Vertical Alignment', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Top', 'solace-extra' ),
						'icon' => 'eicon-align-start-v',
					],
					'center' => [
						'title' => __( 'Middle', 'solace-extra' ),
						'icon' => 'eicon-align-center-v',
					],
					'flex-end' => [
						'title' => __( 'Bottom', 'solace-extra' ),
						'icon' => 'eicon-align-end-v',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .solace-widget-elementor' => 'display: flex; align-items: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_author_name',
			[
				'label' => __( 'Author Name', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'author_name_typography',
				'label' => __( 'Typography', 'solace-extra' ),
				'selector' => '{{WRAPPER}} .solace-post-author-name',
			]
		);

		$this->start_controls_tabs( 'tabs_author_name_style' );

		// Normal
		$this->start_controls_tab(
			'tab_author_name_normal',
			[
				'label' => __( 'Normal', 'solace-extra' ),
			]
		);

		$this->add_control(
			'author_name_color',
			[
				'label' => __( 'Text Color', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-name a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab(
			'tab_author_name_hover',
			[
				'label' => __( 'Hover', 'solace-extra' ),
			]
		);

		$this->add_control(
			'author_name_hover_color',
			[
				'label' => __( 'Hover Color', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-name a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Margin
		$this->add_responsive_control(
			'author_name_margin',
			[
				'label' => __( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'author_name_padding',
			[
				'label' => __( 'Padding', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// =======================================================
		// BIOGRAPHY
		// =======================================================
		$this->start_controls_section(
			'section_style_bio',
			[
				'label' => __( 'Biography', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'bio_typography',
				'label' => __( 'Typography', 'solace-extra' ),
				'selector' => '{{WRAPPER}} .solace-post-author-bio',
			]
		);

		$this->add_control(
			'bio_color',
			[
				'label' => __( 'Text Color', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-bio' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'bio_margin',
			[
				'label' => __( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-bio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'bio_padding',
			[
				'label' => __( 'Padding', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-bio' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// =======================================================
		// VIEW ALL ARTICLES
		// =======================================================
		$this->start_controls_section(
			'section_style_view_all',
			[
				'label' => __( 'View All Articles', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'view_all_typography',
				'label' => __( 'Typography', 'solace-extra' ),
				'selector' => '{{WRAPPER}} .solace-post-author-view-all a',
			]
		);

		$this->add_control(
			'view_all_color',
			[
				'label' => __( 'Text Color', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-view-all a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'view_all_margin',
			[
				'label' => __( 'Margin', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-view-all' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'view_all_padding',
			[
				'label' => __( 'Padding', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .solace-post-author-view-all' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);



		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$html_tag = $settings['solace_extra_html_tag'];
		$author_id = get_the_author_meta('ID');
		$author_name = get_the_author();
		$author_bio = get_the_author_meta('description');
		$author_avatar = get_avatar( $author_id, 96 );
		$author_url = get_author_posts_url( $author_id );
		$view_all_label = $settings['view_all_label'];

		$spacing = isset( $settings['avatar_content_spacing']['size'] ) ? $settings['avatar_content_spacing']['size'] . $settings['avatar_content_spacing']['unit'] : '20px';

		echo '<div class="solace-widget-elementor solace-post-author-wrapper" style="display: flex;">';

		if ( 'yes' === $settings['show_avatar'] ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<div class="solace-post-author-avatar">' . $author_avatar . '</div>';
		}

		echo '<div class="solace-post-author-content">';

		echo '<' . esc_attr( $html_tag ) . ' class="solace-post-author-name">';
		printf(
			'<a href="%s">%s</a>',
			esc_url( $author_url ),
			esc_html( $author_name )
		);
		echo '</' . esc_attr( $html_tag ) . '>';

		// Bio
		if ( 'yes' === $settings['show_bio'] && ! empty( $author_bio ) ) {
			echo '<div class="solace-post-author-bio">' . esc_html( $author_bio ) . '</div>';
		}

		// Link View All
		echo '<div class="solace-post-author-view-all">';
		printf(
			'<a href="%s">%s</a>',
			esc_url( $author_url ),
			esc_html( $view_all_label )
		);
		echo '</div>';

		echo '</div>'; 

		echo '</div>'; // Wrapper

	?>
	<style>
		.solace-post-author-avatar {
			line-height: initial;
		}
	</style>
	<?php
	}


}
