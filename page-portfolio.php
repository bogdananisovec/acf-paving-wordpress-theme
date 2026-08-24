<?php
/**
 * Template Name: Портфолио
 * Template Post Type: page
 *
 * @package ukladka-trotuarnoy-plitki
 */

get_header();
get_template_part( 'template-parts/page-flexible', null, array( 'context' => 'portfolio' ) );
get_footer();
