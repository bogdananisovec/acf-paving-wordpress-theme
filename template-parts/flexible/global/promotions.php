<?php
/** Shared promotions layout. */

if ( 'pages' === ( $args['context'] ?? '' ) && is_file( get_template_directory() . '/template-parts/flexible/pages/promotions.php' ) ) {
	load_template( get_template_directory() . '/template-parts/flexible/pages/promotions.php', false, $args );
	return;
}

load_template( get_template_directory() . '/template-parts/flexible/global/_generic.php', false, $args );
