<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;


class Solace_Extra_WooCommerce_ProductTab extends Widget_Base {

    public function get_name() {
        return 'solace_extra_product_tabs';
    }

    public function get_title() {
        return __( 'Product Tabs', 'solace-extra' );
    }

    public function get_icon() {
        return 'eicon-tabs solace-extra'; 
    }

    public function get_categories() {
        return [ 'solace-extra-single' ]; 
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
        // {{WRAPPER}} .solace-add-to-cart button
		$selector = '{{WRAPPER}} .solace-woocommerce-tabs-panel .solace-extra-button';
		$selector_hover = '{{WRAPPER}} .solace-woocommerce-tabs-panel .solace-extra-button:hover'; 

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

        

        // TAB: STYLE
        $this->start_controls_section(
            'section_tabs_style',
            [
                'label' => __( 'Tab', 'solace-extra' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );   

        $this->add_control(
            'tab_alignment',
            [
                'label' => __( 'Tab Alignment', 'solace-extra' ),
                'type' => Controls_Manager::CHOOSE,
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
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .solace-woocommerce-tabs .wc-tabs' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tabs_label_typography',
                'selector' => '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li a',
            ]
        );



        $this->add_responsive_control(
            'tabs_spacing',
            [
                'label' => __( 'Gap Between Tabs', 'solace-extra' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li:last-child' => 'margin-right: 0;',
                ],
            ]
        );

        $this->add_control(
            'content_background_color',
            [
                'label'     => __( 'Content Background Color', 'solace-extra' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-woocommerce-tabs-panel' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_states');

        // === Normal ===
        $this->start_controls_tab(
            'tab_normal',
            [
                'label' => __('Normal', 'solace-extra'),
            ]
        );

        $this->add_control(
            'tabs_normal_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_normal_bg_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_normal_border',
                'selector' => '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li a',
            ]
        );

        $this->end_controls_tab();

        // === Hover ===
        $this->start_controls_tab(
            'tab_hover',
            [
                'label' => __('Hover', 'solace-extra'),
            ]
        );

        $this->add_control(
            'tabs_hover_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_hover_bg_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_hover_border',
                'selector' => '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li:hover',
            ]
        );

        $this->end_controls_tab();

        // === Active ===
        $this->start_controls_tab(
            'tab_active',
            [
                'label' => __('Active', 'solace-extra'),
            ]
        );

        $this->add_control(
            'tabs_active_color',
            [
                'label' => __('Text Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li.active a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_active_bg_color',
            [
                'label' => __('Background Color', 'solace-extra'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li.active a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_active_border',
                'selector' => '{{WRAPPER}} .solace-woocommerce-tabs ul.tabs li.active',
                
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        

        $this->end_controls_section();

        // TAB: DESCRIPTION
        $this->start_controls_section(
            'section_description_style',
            [
                'label' => __( 'Description', 'solace-extra' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'description_text_color',
            [
                'label' => __( 'Color', 'solace-extra' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #tab-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} #tab-description',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'description_border',
                'selector' => '{{WRAPPER}} #tab-description',
            ]
        );

        $this->add_responsive_control(
            'description_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} #tab-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} #tab-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // TAB: ADDITIONAL INFO
        $this->start_controls_section(
            'section_additional_info_style',
            [
                'label' => __( 'Additional Information', 'solace-extra' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'additional_info_text_color',
            [
                'label' => __( 'Color', 'solace-extra' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #tab-additional_information' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'additional_info_typography',
                'selector' => '{{WRAPPER}} #tab-additional_information',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'additional_info_border',
                'selector' => '{{WRAPPER}} #tab-additional_information',
            ]
        );

        $this->add_responsive_control(
            'additional_info_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} #tab-additional_information' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};:',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'additional_info_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} #tab-additional_information' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // TAB: REVIEW
        $this->start_controls_section(
            'section_review_style',
            [
                'label' => __( 'Review', 'solace-extra' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // TAB: CONTENT COLOR
        $this->start_controls_tabs( 'review_content_color_tabs' );

        $this->start_controls_tab(
            'review_content_color_tab',
            [
                'label' => __( 'Content Color', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'review_text_color',
            [
                'label' => __( 'Text Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #tab-reviews' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'review_typography',
                'selector' => '{{WRAPPER}} #tab-reviews',
            ]
        );

        $this->end_controls_tab();

        // TAB: HEADING COLOR
        $this->start_controls_tab(
            'review_heading_color_tab',
            [
                'label' => __( 'Heading Color', 'solace-extra' ),
            ]
        );

        $this->add_control(
            'review_h3_color',
            [
                'label' => __( 'H3 Color', 'solace-extra' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #tab-reviews h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'review_h3_typography',
                'label' => __( 'H3 Typography', 'solace-extra' ),
                'selector' => '{{WRAPPER}} #tab-reviews h3',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'review_border',
                'selector' => '{{WRAPPER}} #tab-reviews',
            ]
        );


        $this->add_responsive_control(
            'review_padding',
            [
                'label' => __( 'Padding', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} #tab-reviews' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'review_margin',
            [
                'label' => __( 'Margin', 'solace-extra' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} #tab-reviews' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Button', 'solace-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

        $this->register_button_style_controls();

		$this->end_controls_section();
    }

    public function solace_custom_additional_info_html() {
        global $product;

        if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
            return;
        }

        $attributes = $product->get_attributes();

        if ( empty( $attributes ) ) {
            return;
        }

        echo '<div class="solace-product-additional-information">';
        // echo '<h2 class="woocommerce-product-details__title">' . esc_html__( 'Additional Information', 'woocommerce' ) . '</h2>';
        echo '<table class="shop_attributes">';

        foreach ( $attributes as $attribute ) {
            if ( ! $attribute->get_visible() ) {
                continue;
            }

            $label = wc_attribute_label( $attribute->get_name() );

            echo '<tr>';
            echo '<th>' . esc_html( $label ) . '</th>';
            echo '<td>';

            if ( $attribute->is_taxonomy() ) {
                $values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), [
                    'fields' => 'names',
                ] );
                echo esc_html( implode( ', ', $values ) );
            } else {
                echo esc_html( implode( ', ', $attribute->get_options() ) );
            }

            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';
    }

    public function solace_custom_additional_info_empty() {
        echo '<div class="solace-woocommerce-tabs empty wc-tabs-wrapper tabs-layout-horizontal">
            <ul class="tabs wc-tabs" role="tablist">
                <li class="description_tab active" id="tab-title-description" role="tab" aria-controls="tab-description">
                    <a href="#tab-description" aria-selected="true" tabindex="0">Description</a>
                </li>
                <li class="additional_information_tab" id="tab-title-additional_information" role="tab" aria-controls="tab-additional_information">
                    <a href="#tab-additional_information">Additional Information</a>
                </li>
                <li class="reviews_tab" id="tab-title-reviews" role="tab" aria-controls="tab-reviews">
                    <a href="#tab-reviews">Reviews (0)</a>
                </li>
            </ul>

            <div class="tab-content">

                <div class="solace-woocommerce-tabs-panel solace-woocommerce-tabs-panel--description panel entry-content wc-tab active" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description" style="">
                    <p>Sorry, no products are available.</p>
                </div>

                <div class="solace-woocommerce-tabs-panel solace-woocommerce-tabs-panel--additional_information panel entry-content wc-tab" id="tab-additional_information" role="tabpanel" aria-labelledby="tab-title-additional_information" style="display: none;">
                    <div class="solace-product-additional-information">
                        <table class="shop_attributes">
                            <tbody>
                                <tr><th>Sorry, no products are available.</th></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="solace-woocommerce-tabs-panel solace-woocommerce-tabs-panel--reviews panel entry-content wc-tab" id="tab-reviews" role="tabpanel" aria-labelledby="tab-title-reviews" style="display: none;">
                    <div class="reviews-wrapper">
                        <div class="reviews-list">
                            <h3>Customer Reviews</h3>
                            <div id="comments">
                                <p class="woocommerce-noreviews">There are no reviews yet.</p>
                            </div>
                        </div>

                        <div class="review-form-wrapper">
                            <h3>Write a Review</h3>
                            <div id="review_form_wrapper">
                                <div id="review_form">
                                    <div id="respond" class="comment-respond">
                                        <form action="https://ricoboy.djavaweb.com/wp-comments-post.php" method="post" id="commentform" class="comment-form" novalidate="">
                                            <div class="comment-form-rating">
                                                <label for="rating" id="comment-form-rating-label">Your rating&nbsp;<span class="required">*</span></label>
                                                <p class="stars">
                                                    <span role="group" aria-labeledby="comment-form-rating-label">
                                                        <a role="radio" tabindex="0" aria-checked="false" class="star-1" href="#">1 of 5 stars</a>
                                                        <a role="radio" tabindex="-1" aria-checked="false" class="star-2" href="#">2 of 5 stars</a>
                                                        <a role="radio" tabindex="-1" aria-checked="false" class="star-3" href="#">3 of 5 stars</a>
                                                        <a role="radio" tabindex="-1" aria-checked="false" class="star-4" href="#">4 of 5 stars</a>
                                                        <a role="radio" tabindex="-1" aria-checked="false" class="star-5" href="#">5 of 5 stars</a>
                                                    </span>
                                                </p>
                                                <select name="rating" id="rating" required="" style="display: none;">
                                                    <option value="">Rate…</option>
                                                    <option value="5">Perfect</option>
                                                    <option value="4">Good</option>
                                                    <option value="3">Average</option>
                                                    <option value="2">Not that bad</option>
                                                    <option value="1">Very poor</option>
                                                </select>
                                            </div>
                                            <p class="comment-form-comment">
                                                <label for="comment">Your review&nbsp;<span class="required">*</span></label>
                                                <textarea id="comment" name="comment" cols="25" rows="3" required=""></textarea>
                                            </p>
                                            <p class="form-submit">
                                                <input name="submit" type="submit" id="submit" class="submit" value="Submit">
                                                <input type="hidden" name="comment_post_ID" value="5901" id="comment_post_ID">
                                                <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                                            </p>
                                        </form>
                                    </div><!-- #respond -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>';
    }

    public function solace_custom_additional_info_empty_style() {
        echo '<style>
                .empty .tab-content .solace-woocommerce-tabs-panel--description {
                    padding-top: 30px;
                }
                .solace-woocommerce-tabs.wc-tabs-wrapper.tabs-layout-vertical-left {
                    display: flex;
                }
                .solace-woocommerce-tabs.wc-tabs-wrapper.tabs-layout-vertical-right {
                    display: flex;
                    flex-direction: row-reverse;
                }
                .solace-woocommerce-tabs.wc-tabs-wrapper.tabs-layout-vertical-left li,
                .solace-woocommerce-tabs.wc-tabs-wrapper.tabs-layout-vertical-left li a,
                .solace-woocommerce-tabs.wc-tabs-wrapper.tabs-layout-vertical-right li,
                .solace-woocommerce-tabs.wc-tabs-wrapper.tabs-layout-vertical-right li a {
                    width: 100%;
                }
                /* Layout control */
                .tabs-layout-horizontal ul.tabs {
                    flex-direction: row;
                    align-items: center;
                }
                .tabs-layout-vertical-left ul.tabs {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .tabs-layout-vertical-right ul.tabs {
                    flex-direction: column;
                    align-items: flex-end;
                }

                div#tab-reviews {
                    padding-top: 20px;
                }
                .woocommerce #review_form #respond p {
                    margin: 0 0 0px;
                    padding-top: 5px;
                }
                .solace-woocommerce-tabs ul.tabs {
                    display: flex;
                    border-bottom: 1px solid #ddd;
                    margin-bottom: 20px;
                    list-style: none;
                    padding-left: 0;
                    margin-bottom: 0;
                }
                .solace-woocommerce-tabs ul.tabs li {
                    margin-right: 10px;
                    list-style-type: none;
                }
                .solace-woocommerce-tabs ul.tabs li a {
                    display: inline-block;
                    padding: 10px 20px;
                    background: #f7f7f7;
                    border: 1px solid #ddd;
                    border-radius: 5px 5px 0 0;
                    text-decoration: none;
                    color: #333;
                    font-weight: bold;
                }

                .solace-woocommerce-tabs-panel {
                    display: none;
                    padding: 20px;
                    /* border: 1px solid #ddd; */
                    /* border-radius: 0 5px 5px 5px; */
                    background: #fff;
                    padding-top: 0;
                    margin-top: 0;
                    border-top: none;
                }
                .solace-woocommerce-tabs-panel.active {
                    display: block;
                }
                .comment-form-rating {
                    display: flex;
                }
                p.stars {
                    margin-left: 20px;
                }
                p.stars a {
                    position: relative;
                    height: 1em;
                    width: 1em;
                    text-indent: -999em;
                    display: inline-block;
                    text-decoration: none;
                }
                p.stars a::before {
                    display: block;
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 1em;
                    height: 1em;
                    line-height: 1;
                    font-family: WooCommerce;
                    content: "\e021";
                    content: "\e021" / "";
                    text-indent: 0;
                    color: #ffc107;
                }
    
                .reviews-wrapper {
                    display: flex;
                    justify-content: space-between;
                    gap: 2%;
                    flex-wrap: wrap;
                }
    
                .woocommerce .elementor-widget-solace_extra_product_tabs .reviews-list {
                    flex: 1;
                    min-width: 300px;
                }
    
                .woocommerce .elementor-widget-solace_extra_product_tabs .review-form-wrapper {
                    flex: 1;
                    min-width: 300px;
                }

                .review-item {
                    margin-bottom: 20px;
                    padding-bottom: 15px;
                    border-bottom: 1px solid #ddd;
                }

                .review-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    margin-bottom: 5px;
                }

                .review-meta {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    font-size: 1em;
                    font-weight: 600;
                }

                .review-date {
                    font-size: 0.9em;
                    color: var(--sol-color-base-font);
                }

                .review-rating {
                    font-size: 1.1em;
                }
                .reviews-list {
                    width: 49%;
                }
                .review-form-wrapper {
                    width: 49%;
                }



    
                @media (max-width: 768px) {
                    .reviews-wrapper {
                        flex-direction: column;
                        gap: 20px;
                    }
                }
            </style>';
    }


    protected function render() {

		$settings = $this->get_settings_for_display();
        $hover_animation = ! empty( $settings['hover_animation'] ) ? $settings['hover_animation'] : '';
        $hover_class     = $hover_animation ? ' elementor-animation-' . $hover_animation : '';
		$product = solace_get_preview_product();
        $this->solace_custom_additional_info_empty_style();
		
        $checkempty = solace_check_empty_product( $product );
		if ( $checkempty ) {
			// echo '<div class="solace-container"><div class="solace-price-amount">' . esc_html( $checkempty ) . '</div></div>';
            $this->solace_custom_additional_info_empty();
			return;
		}

		$product_id    = $product->get_id();
		$product_title = $product->get_title(); 
        $product_id = $product ? $product->get_id() : 'not set';

        $has_variations = $product->is_type('variable'); 
        $has_attributes = count($product->get_attributes()) > 0; 


        if (function_exists('comments_template')) {
            global $product;
    
            $post_object = get_post($product_id);

            $settings = $this->get_settings_for_display();
            $tabs_layout_class = isset($settings['tabs_layout']) ? 'tabs-layout-' . esc_attr($settings['tabs_layout']) : 'tabs-layout-horizontal';

            setup_postdata($GLOBALS['post'] =& $post_object);?>
            <div class="solace-woocommerce-tabs wc-tabs-wrapper 
            <?php 
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $tabs_layout_class; ?>">

                <ul class="tabs wc-tabs" role="tablist">
                    <li class="description_tab active" id="tab-title-description" role="tab" aria-controls="tab-description">
                        <a href="#tab-description">Description</a>
                    </li>
                    <?php if ($has_variations || $has_attributes) : ?>
                        <li class="additional_information_tab" id="tab-title-additional_information" role="tab" aria-controls="tab-additional_information">
                            <a href="#tab-additional_information">Additional Information</a>
                        </li>
                    <?php endif; ?>
                    <?php 
                    $review_count = $product && is_a( $product, 'WC_Product' ) ? $product->get_review_count() : 0;?>
                    <li class="reviews_tab" id="tab-title-reviews" role="tab" aria-controls="tab-reviews">
                        <a href="#tab-reviews">Reviews (<?php 
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo $review_count; ?>)</a>
                    </li>
                </ul>

                <div class="tab-content">
    
                <div class="solace-woocommerce-tabs-panel solace-woocommerce-tabs-panel--description panel entry-content wc-tab active" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">
                    <?php the_content(); ?>
                </div>

                <div class="solace-woocommerce-tabs-panel solace-woocommerce-tabs-panel--additional_information panel entry-content wc-tab" id="tab-additional_information" role="tabpanel" aria-labelledby="tab-title-additional_information">
                    <?php $this->solace_custom_additional_info_html(); ?>
                </div>
        
                <div class="solace-woocommerce-tabs-panel solace-woocommerce-tabs-panel--reviews panel entry-content wc-tab" id="tab-reviews" role="tabpanel" aria-labelledby="tab-title-reviews">
                    <div class="reviews-wrapper">
                            <div class="reviews-list">
                                <h3>Customer Reviews</h3>
                                <div id="comments">
                                    <?php
                                    $comments = get_comments(array(
                                        'post_id' => $product_id,
                                        'status'  => 'approve' 
                                    ));
        
                                    if (!empty($comments)) {
                                        foreach ($comments as $comment) {
                                            ?>
                                            <div class="review-item">
                                                <div class="review-header">
                                                    <div class="review-meta">
                                                        <strong><?php 
                                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                        echo get_comment_author($comment); ?></strong> - 
                                                        <span class="review-date"><?php 
                                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                        echo get_comment_date('', $comment); ?></span>
                                                    </div>
                                                    <div class="review-rating">
                                                        <?php
                                                        $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                                                        $rating_percentage = ($rating / 5) * 100;
                                                        echo '<div class="woocommerce-product-rating custom-star-rating">';
                                                        echo '<div class="star-rating" role="img" aria-label="Rated ' . esc_attr($rating) . ' out of 5">';
                                                        echo '<span style="width:' . esc_attr($rating_percentage) . '%"></span>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="review-content">
                                                    <p><?php 
                                                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                    echo get_comment_text($comment); ?></p>
                                                </div>
                                            </div>


                                            <?php
                                        }
                                    } else {
                                        echo '<p class="woocommerce-noreviews">There are no reviews yet.</p>';
                                    }
                                    ?>
                                </div>
                            </div>
        
                            <div class="review-form-wrapper">
                                <h3>Write a Review</h3>
                                <div id="review_form_wrapper">
                                    <div id="review_form">
                                        <div id="respond" class="comment-respond">
                                            <form action="<?php 
                                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            echo site_url('/wp-comments-post.php'); ?>" method="post" id="commentform" class="comment-form" novalidate="">
                                                <div class="comment-form-rating">
                                                    <label for="rating" id="comment-form-rating-label">Your rating&nbsp;<span class="required">*</span></label>
                                                    <select name="rating" id="rating" required="" style="display: none;">
                                                        <option value="">Rate…</option>
                                                        <option value="5">Perfect</option>
                                                        <option value="4">Good</option>
                                                        <option value="3">Average</option>
                                                        <option value="2">Not that bad</option>
                                                        <option value="1">Very poor</option>
                                                    </select>
                                                    <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) : ?>
                                                        <p class="stars">
                                                            <span role="group" aria-labeledby="comment-form-rating-label">
                                                                <a role="radio" class="star-1" href="#">1 of 5 stars</a>
                                                                <a role="radio" class="star-2" href="#">2 of 5 stars</a>
                                                                <a role="radio" class="star-3" href="#">3 of 5 stars</a>
                                                                <a role="radio" class="star-4" href="#">4 of 5 stars</a>
                                                                <a role="radio" class="star-5" href="#">5 of 5 stars</a>
                                                            </span>
                                                        </p>
                                                    <?php endif; ?>

                                                </div>
                                                <p class="comment-form-comment">
                                                    <label for="comment">Your review&nbsp;<span class="required">*</span></label>
                                                    <textarea id="comment" name="comment" cols="25" rows="3" required=""></textarea>
                                                </p>
                                                <p class="form-submit">
                                                    <input name="submit" type="submit" id="solace-extra-submit" class="solace-extra-button elementor-button submit <?php echo esc_attr( $hover_class );?>" value="Submit">
                                                    <input type="hidden" name="comment_post_ID" value="<?php 
                                                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                    echo $product_id; ?>" id="comment_post_ID">
                                                    <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                                                </p>
                                            </form>
                                        </div><!-- #respond -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            
    
            <script>
                function initCustomStarRating() {
                    const stars = document.querySelectorAll('.stars a');
                    const ratingInput = document.getElementById('rating');

                    if (!stars.length || !ratingInput) return;

                    stars.forEach(function(star) {
                        star.addEventListener('click', function(e) {
                            e.preventDefault();
                            const value = this.getAttribute('data-value');
                            
                            stars.forEach(s => s.classList.remove('selected'));
                            this.classList.add('selected');
                            
                            ratingInput.value = value;
                        });
                    });
                }
                document.addEventListener('DOMContentLoaded', function() {
                    const stars = document.querySelectorAll('.stars a');
                    const ratingInput = document.getElementById('rating');
    
                    stars.forEach(function(star) {
                        star.addEventListener('click', function() {
                            const value = this.getAttribute('data-value');
                            
                            stars.forEach(star => star.classList.remove('selected'));
                            this.classList.add('selected');
    
                            ratingInput.value = value;
                        });
                    });
                });
                // Jalankan saat DOM siap (frontend biasa)
                document.addEventListener('DOMContentLoaded', initCustomStarRating);

                // Jalankan juga di editor Elementor
                jQuery(window).on('elementor/frontend/init', function () {
                    elementorFrontend.hooks.addAction('frontend/element_ready/global', function () {
                        initCustomStarRating();
                    });
                });

            </script>

    
            <?php
            wp_reset_postdata();
        }
    }



}
