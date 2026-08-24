<?php
/**
 * Single client review template.
 *
 * @package ukladka-trotuarnoy-plitki
 */

get_header();
get_template_part(
	'template-parts/page-flexible',
	null,
	array(
		'field_name' => 'review_sections',
		'context'    => 'posts',
	)
);
get_footer();
