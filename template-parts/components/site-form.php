<?php
/**
 * Routes a reusable form entity to its own template.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$form_id      = sanitize_key( (string) ( $args['form_id'] ?? '' ) );
$form_post_id = ukladka_trotuarnoy_plitki_get_form_post_id( $form_id );
$template     = function_exists( 'get_field' ) ? sanitize_file_name( (string) get_field( 'template_name', $form_post_id ) ) : '';
$template     = $template ?: 'master-visit';

if ( $form_post_id && is_file( get_template_directory() . '/template-parts/forms/' . $template . '.php' ) ) {
	get_template_part( 'template-parts/forms/' . $template, null, array( 'form_post_id' => $form_post_id ) );
}
