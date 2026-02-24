<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Post Date Widget.
 *
 * Widget Elementor yang menampilkan tanggal pos atau halaman.
 *
 * @since 1.0.0
 */
class Solace_Extra_Post_Date extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @since 1.0.0
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'solace-extra-post-date';
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
		return esc_html__( 'Post Date', 'solace-extra' );
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
		return 'eicon-calendar solace-extra';
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
		return [ 'solace', 'extra', 'post', 'date' ];
	}	    

	/**
	 * Register widget controls.
	 *
	 * Menambahkan kontrol untuk menampilkan tanggal pos.
	 *
	 * @since 1.0.0
	 */
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
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'DIV',
					'span' => 'SPAN',
					'p' => 'P',
				],
				'default' => 'div',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'solace_extra_style_content_section',
			[
				'label' => esc_html__( 'Style', 'solace-extra' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		// Alignment Control
		$this->add_responsive_control(
			'solace_extra_text_alignment',
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
					'justify' => [
						'title' => esc_html__( 'Justify', 'solace-extra' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .solace-post-date' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		// Text Color Control
		$this->add_control(
			'solace_extra_text_color',
			[
				'label' => esc_html__( 'Text Color', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .solace-post-date' => 'color: {{VALUE}};',
				],
			]
		);
		
		// Typography Control
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .solace-post-date',
			]
		);
		
		// Text Shadow Control
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .solace-post-date',
			]
		);
		
		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Menampilkan tanggal untuk pos atau halaman.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$html_tag = $settings['solace_extra_html_tag'];
		$post_date = get_the_date();

		// Menampilkan tanggal pos
		echo '<' . esc_attr( $html_tag ) . ' class="solace-widget-elementor solace-post-date">';
		echo esc_html( $post_date );
		echo '</' . esc_attr( $html_tag ) . '>';
	}
}
