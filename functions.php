<?php
/**
 * Theme bootstrap.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$ukladka_theme_files = array(
	'/inc/config.php',
	'/inc/setup.php',
	'/inc/acf.php',
	'/inc/post-types.php',
	'/inc/components.php',
	'/inc/flexible.php',
	'/inc/reviews.php',
	'/inc/forms.php',
	'/inc/ajax/reviews.php',
	'/inc/ajax/forms.php',
	'/inc/template-tags.php',
	'/inc/template-functions.php',
);

foreach ( $ukladka_theme_files as $ukladka_theme_file ) {
	require_once get_template_directory() . $ukladka_theme_file;
}

unset( $ukladka_theme_file, $ukladka_theme_files );
