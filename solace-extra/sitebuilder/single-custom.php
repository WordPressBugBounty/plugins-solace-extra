<?php
/*
Template Name: Solace Site Builder
Template Post Type: page
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// if ( \Elementor\Plugin::instance()->documents->get( get_the_ID() )->is_built_with_elementor() ) {
//     the_content();
//     return; 
// }

get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
endif;

get_footer();
