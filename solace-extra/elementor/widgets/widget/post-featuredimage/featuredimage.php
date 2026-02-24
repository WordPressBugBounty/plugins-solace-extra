<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit;

class Solace_Extra_Post_Featured_Image extends Widget_Base {

    public function get_name() {
        return 'solace_extra_single_featured_image';
    }

    public function get_title() {
        return __( 'Featured Image', 'solace-extra' );
    }

    public function get_icon() {
        return 'eicon-image solace-extra';
    }

    public function get_categories() {
        return [ 'solace-extra-single-post' ];
    }

    public function get_style_depends() {
        return [ 'solace-featured-image-style' ];
    }

    protected function register_controls() {

        // Content Tab
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'solace-extra' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

         $this->add_control(
            'show_post_title',
            [
                'label' => __( 'Show Post Title', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'solace-extra' ),
                'label_off' => __( 'Hide', 'solace-extra' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
			'title_html_tag',
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
                'condition' => [
                    'show_post_title' => 'yes',
                ],
			]
		);

        $this->add_control(
            'show_post_categories',
            [
                'label' => __( 'Show Categories', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'solace-extra' ),
                'label_off' => __( 'Hide', 'solace-extra' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_post_meta',
            [
                'label' => __( 'Show Post Meta', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'solace-extra' ),
                'label_off' => __( 'Hide', 'solace-extra' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );


        $this->add_control(
            'fallback_image',
            [
                'label' => __( 'Fallback Image', 'solace-extra' ),
                'type' => Controls_Manager::MEDIA,
                'description' => __( 'Used if post has no featured image.', 'solace-extra' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'default' => 'full',
            ]
        );

       



        $this->end_controls_section();

        // Style Tab
        $this->start_controls_section(
            'style_section',
            [
                'label' => __( 'Layout', 'solace-extra' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => __( 'Horizontal Alignment', 'solace-extra' ),
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .solace-featured-content .solace-post-title,
                    {{WRAPPER}} .solace-featured-content .solace-category-wrapper,
                    {{WRAPPER}} .solace-featured-content .solace-post-meta' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .solace-featured-content .solace-post-title h1' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'vertical_alignment',
            [
                'label' => __( 'Vertical Alignment', 'solace-extra' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Top', 'solace-extra' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __( 'Middle', 'solace-extra' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __( 'Bottom', 'solace-extra' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .solace-featured-wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'overlay_color',
            [
                'label' => __( 'Overlay Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-featured-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => __( 'Height', 'solace-extra' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ,'em'],
                'range' => [
                    'px' => [ 'min' => 200, 'max' => 1200 ],
                ],
                'default' => [
                    'size' => 400,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-featured-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => __( 'Width', 'solace-extra' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-featured-content' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );        

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .solace-featured-wrapper',
            ]
        );

        $this->add_control(
            'layout_border_radius',
            [
                'label' => __( 'Border Radius', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-featured-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_post_title_style',
            [
                'label' => __( 'Post Title', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'post_title_color',
            [
                'label'     => __( 'Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-title *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'post_title_typography',
                'label'    => __( 'Typography', 'solace-extra' ),
                'selector' => '{{WRAPPER}}  .solace-post-title-text',
            ]
        );

        $this->add_responsive_control(
            'post_title_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'post_title_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_post_category_style',
            [
                'label' => __( 'Post Categories', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'post_category_color',
            [
                'label'     => __( 'Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-category a, {{WRAPPER}} .solace-cat-label' => 'color: {{VALUE}};',
                    
                ],
            ]
        );

        $this->add_control(
            'category_bg_color',
            [
                'label' => __( 'Background Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-category' => 'background-color: {{VALUE}};',
                ],
            ]
        );

         $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'post_category_typography',
                'label'    => __( 'Typography', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .solace-post-category a, {{WRAPPER}} .solace-cat-label',
            ]
        );

        $this->add_control(
			'category_all_label',
			[
				'label' => __( 'Posted in Label', 'solace-extra' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Posted in :', 'solace-extra' ),
			]
		);

        $this->add_responsive_control(
            'category_label_spacing',
            [
                'label' => __( 'Spacing between label and categories', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-category span.solace-cat-label' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_cat_spacing',
            [
                'label' => __( 'Spacing between categories', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    // '{{WRAPPER}} .solace-post-category span.solace-cat-cat a' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .solace-cat-cat' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-category-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'category_border',
                'label' => __( 'Border', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .solace-post-category',
            ]
        );

        $this->add_control(
            'category_border_radius',
            [
                'label' => __( 'Border Radius', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_post_meta_style',
            [
                'label' => __( 'Post Meta', 'solace-extra' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'post_meta_color',
            [
                'label'     => __( 'Color', 'solace-extra' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-post-meta, {{WRAPPER}} .solace-post-meta a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .solace-post-meta .solace-author-avatar img' => 'border-radius: 50%;',
                ],
            ]
        );



        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'post_meta_typography',
                'label'    => __( 'Typography', 'solace-extra' ),
                'selector' => '{{WRAPPER}} .solace-post-meta',
            ]
        );

        $this->add_responsive_control(
            'post_meta_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'post_meta_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .solace-post-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();


    }

    public function solace_featuredimage_empty() {
        echo '
        <div class="solace-featured-wrapper" style="background-image: url(\'https://placehold.co/1250x300?text=Sorry,%20no%20post%20are%20available\');">
            <div class="solace-featured-overlay"></div>
            <div class="solace-featured-content">
                <h2 class="solace-post-title">Sorry, no posts are available</h2>
                <div class="solace-post-category">
                    Posted in : 
                    <a href="#" rel="category tag">Uncategorized</a>
                </div>
                <div class="solace-post-meta">
                    <div class="solace-author-avatar">
                        <img alt="" src="https://placehold.co/300x300?text=Sorry,%20no%20post%20are%20available" srcset="https://secure.gravatar.com/avatar/42d3b6730ac4cc4fd634c7b9cd64836fff4b52d94cf93694ac072c34c50715c2?s=64&amp;d=mm&amp;r=g 2x" class="avatar avatar-32 photo" height="32" width="32" decoding="async">
                    </div>
                    <div class="solace-author-info">
                        <span class="solace-author-name">no author</span> • 
                        <span class="solace-post-date">December 27, 2035</span>
                    </div>
                </div>
            </div>
        </div>';
    }

    public function solace_featuredimage_style(){
        echo '<style>
            .solace-featured-wrapper {
                position: relative;
                background-size: cover;
                background-position: center;
                display: flex;
                justify-content: center;
                align-items: center;
                overflow: hidden;
                color: white;
                padding: 40px 20px;
            }

            .solace-featured-overlay {
                position: absolute;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.4); 
                z-index: 1;
                pointer-events: none;
            }

            .solace-featured-content {
                position: relative;
                z-index: 2;
                max-width: 800px;
                text-align: center;
            }


            .solace-post-title {
                font-size: 2.5em;
                margin-bottom: 10px;
            }

            .solace-post-category {
                font-size: 0.9em;
                margin-bottom: 8px;
                font-weight: 500;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                background-color: #ffffff2b;
                margin: 0 auto;
                max-width: max-content;
                padding-top: 8px;
                padding-right: 24px;
                padding-bottom: 8px;
                padding-left: 24px;
                font-weight: 600;
            }

            .solace-post-meta {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                font-size: 0.85em;
            }

            .solace-featured-wrapper .solace-author-avatar img {
                border-radius: 50%;
            }

        </style>';
    }




    protected function renderx() {
        if ( ! is_singular() ) return;

        $settings = $this->get_settings_for_display();
        
        $image_url = get_the_post_thumbnail_url( get_the_ID(), $settings['image_size'] );
        $this->solace_featuredimage_style();
        $post_id = solace_get_preview_post();
		$post_object = get_post( $post_id );

		$checkempty = solace_check_empty_post( $post_id );
        if ( $checkempty ) {
            // echo '<div class="solace-empty-post">' . esc_html( $checkempty ) . '</div>';
            $this->solace_featuredimage_empty();
            return;
        }

		$title = $post_object->post_title;
        
        if ( ! $image_url && ! empty( $settings['fallback_image']['url'] ) ) {
            $image_url = $settings['fallback_image']['url'];
        }

        $author_id = get_post_field( 'post_author', get_the_ID() );

        ?>
        <div class="solace-featured-wrapper" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
            <div class="solace-featured-overlay"></div>
            <div class="solace-featured-content">
                <?php if ( 'yes' === $settings['show_post_title'] ) {?>
                    <h2 class="solace-post-title"><?php echo wp_kses_post( $title ); ?></h2>
                <?php }?>
                <?php if ( 'yes' === $settings['show_post_categories'] ) {?>
                <div class="solace-post-category">
                    <?php 
                    esc_html_e('Posted in :', 'solace-extra');

                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo get_the_category_list(', '); 
                    ?>
                </div>
                <?php }?>
                <?php if ( 'yes' === $settings['show_post_meta'] ) {?>
                <div class="solace-post-meta">
                    <div class="solace-author-avatar">
                        <?php echo get_avatar( $author_id, 32 ); ?>
                    </div>
                    <div class="solace-author-info">
                        <span class="solace-author-name"><?php the_author(); ?></span> • 
                        <span class="solace-post-date"><?php echo get_the_date(); ?></span>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        

        <?php
    }

    protected function render() {
        if ( ! is_singular() ) return;

        $settings = $this->get_settings_for_display();
        
        $post_id = solace_get_preview_post(); 
        $category_all_label = ! empty( $settings['category_all_label'] ) ? $settings['category_all_label'] : '';
        $image_size = ! empty( $settings['image_size'] ) ? $settings['image_size'] : 'full';
        $image_url = get_the_post_thumbnail_url( $post_id, $image_size );

        // $image_url = get_the_post_thumbnail_url( $post_id, $settings['image_size'] );
        // error_log('Post ID: ' . $post_id);
        // error_log('Image size: ' . $settings['image_size']);
        // error_log('Featured image: ' . $image_url);

        $categories = get_the_category( $post_id );
        $category_output = [];

        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            foreach ( $categories as $category ) {
                $category_output[] = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
            }
        }
        
		$post_object = get_post( $post_id );

		if ( ! $post_object || empty( $post_object->post_title ) ) return;

		$title = $post_object->post_title;
        
        if ( ! $image_url && ! empty( $settings['fallback_image']['url'] ) ) {
            $image_url = $settings['fallback_image']['url'];
        }
        $show_title     = isset( $settings['show_post_title'] ) && $settings['show_post_title'] === 'yes';
        $show_category  = isset( $settings['show_post_categories'] ) && $settings['show_post_categories'] === 'yes';
        $show_meta      = isset( $settings['show_post_meta'] ) && $settings['show_post_meta'] === 'yes';

        $author_id = get_post_field( 'post_author', get_the_ID() );
        $html_tag = $settings['title_html_tag'];
        ?>
        <div class="solace-featured-wrapper" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
            <div class="solace-featured-overlay"></div>
            <div class="solace-featured-content">
                <?php if ( $show_title ) { ?>
                    <div class="solace-post-title" style="display: flex;">
                       <<?php echo esc_attr( $html_tag ); ?> class="solace-post-title-text">
                            <?php echo wp_kses_post( $title ); ?>
                        </<?php echo esc_attr( $html_tag ); ?>>
                    </div>
                <?php }?>
                <?php if ( $show_category ) { ?>
                    <div class="solace-category-wrapper">
                        <div class="solace-post-category">
                            <span class="solace-cat-label"><?php echo esc_html( $category_all_label ); ?></span>
                            <span class="solace-cat-cat">
                                <?php 
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                echo implode( '', $category_output ); 
                                ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>
                <?php if ( $show_meta ) {?>
                <div class="solace-post-meta">
                    <div class="solace-author-avatar">
                        <?php echo get_avatar( $author_id, 32 ); ?>
                    </div>
                    <div class="solace-author-info">
                        <span class="solace-author-name"><?php the_author(); ?></span> • 
                        <span class="solace-post-date"><?php echo get_the_date(); ?></span>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <style>
            .solace-cat-cat {
                display: inline-flex;
                flex-wrap: wrap;
                gap: 8px; /* bisa dikontrol dari Elementor */
            }
            .solace-cat-cat a::after {
                content: ",";
            }

            .solace-cat-cat a:last-child::after {
                content: "";
            }



            .solace-category-wrapper {
                display: flex;
                justify-content: center; /* default */
            }
            .solace-featured-wrapper {
                position: relative;
                background-size: cover;
                background-position: center;
                display: flex;
                justify-content: center;
                align-items: center;
                overflow: hidden;
                color: white;
                padding: 40px 20px;
            }

            .solace-featured-overlay {
                position: absolute;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.4); 
                z-index: 1;
                pointer-events: none;
            }

            .solace-featured-content {
                position: relative;
                z-index: 2;
                max-width: 800px;
                text-align: center;
            }


            .solace-post-title {
                font-size: 2.5em;
                margin-bottom: 10px;
            }

            .solace-post-category {
                font-size: 0.9em;
                margin-bottom: 8px;
                font-weight: 500;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                background-color: #ffffff2b;
                padding-top: 8px;
                padding-right: 24px;
                padding-bottom: 8px;
                padding-left: 24px;
                font-weight: 600;
            }

            .solace-post-meta {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                font-size: 0.85em;
            }

            .solace-featured-wrapper .solace-author-avatar img {
                border-radius: 50%;
            }

        </style>

        <?php
    }
}