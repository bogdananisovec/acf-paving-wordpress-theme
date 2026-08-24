<?php
/**
 * AJAX handlers for review lists.
 *
 * @package ukladka-trotuarnoy-plitki
 */

/**
 * Loads the next review page.
 */
function ukladka_trotuarnoy_plitki_load_more_reviews() {
	check_ajax_referer( 'load_more_reviews', 'nonce' );

	$page     = max( 1, absint( $_POST['page'] ?? 1 ) );
	$per_page = min( 50, max( 1, absint( $_POST['per_page'] ?? 6 ) ) );
	$query    = new WP_Query(
		array(
			'post_type'      => 'client_review',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	);
	$html     = '';

	foreach ( $query->posts as $review_post ) {
		$html .= ukladka_trotuarnoy_plitki_get_review_card_html( $review_post->ID );
	}

	wp_send_json_success(
		array(
			'html'        => $html,
			'has_more'    => $page < (int) $query->max_num_pages,
			'next_page'   => $page + 1,
			'found_posts' => (int) $query->found_posts,
		)
	);
}
add_action( 'wp_ajax_load_more_reviews', 'ukladka_trotuarnoy_plitki_load_more_reviews' );
add_action( 'wp_ajax_nopriv_load_more_reviews', 'ukladka_trotuarnoy_plitki_load_more_reviews' );
