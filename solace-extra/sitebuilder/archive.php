<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$solace_extra_archive_args = array(
    'post_type'      => 'solace_archive',
    'posts_per_page' => 1,
    // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
    'meta_value'     => '1',
);

$solace_extra_archive_query = new WP_Query( $solace_extra_archive_args );

if ( $solace_extra_archive_query->have_posts() ) {
    while ( $solace_extra_archive_query->have_posts() ) {
        $solace_extra_archive_query->the_post();
        $solace_elementor_content = \Elementor\Plugin::$instance->frontend->get_builder_content( get_the_ID(), true );

        if ( ! empty( $solace_elementor_content ) ) {
            echo wp_kses_post( $solace_elementor_content );
        } else {
            echo '<p>No Elementor content found.</p>'; 
        }
    }
    wp_reset_postdata();
} else {
    ?>
    <main id="main" class="site-main">
        <header class="page-header">
            <h1><?php esc_html_e('Archives HERE', 'solace-extra'); ?></h1>
        </header>
        <section class="post-list">
            <?php
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    get_template_part('template-parts/content', get_post_type());
                }
            } else {
                esc_html_e('No posts found', 'solace-extra');
            }
            ?>
        </section>
    </main>
    <?php
}

get_footer();
