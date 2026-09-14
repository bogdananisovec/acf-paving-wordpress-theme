<?php
/**
 * Front page template.
 * Template Name: Главная
 * Template Post Type: page
 *
 * @package ukladka-trotuarnoy-plitki
 */

get_header();
get_template_part(
	'template-parts/page-flexible',
	null,
	array(
		'field_name' => 'home_sections',
		'context'    => 'pages',
	)
);
get_footer();
