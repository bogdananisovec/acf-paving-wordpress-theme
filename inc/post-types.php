<?php
/**
 * Custom post type registrations.
 *
 * @package ukladka-trotuarnoy-plitki
 */

/**
 * Registers the portfolio post type.
 */
function ukladka_trotuarnoy_plitki_register_portfolio_post_type() {
	register_post_type(
		'portfolio',
		array(
			'labels'             => array(
				'name'               => 'Портфолио',
				'singular_name'      => 'Объект портфолио',
				'menu_name'          => 'Портфолио',
				'add_new_item'       => 'Добавить объект портфолио',
				'edit_item'          => 'Редактировать объект',
				'all_items'          => 'Все объекты',
				'not_found'          => 'Объекты не найдены',
				'not_found_in_trash' => 'В корзине объекты не найдены',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-portfolio',
			'rewrite'            => array(
				'slug'       => 'portfolio',
				'with_front' => false,
			),
			'has_archive'        => true,
			'menu_position'      => 20,
			'supports'           => array( 'title', 'thumbnail', 'excerpt', 'revisions' ),
		)
	);
}
add_action( 'init', 'ukladka_trotuarnoy_plitki_register_portfolio_post_type' );

/**
 * Registers reusable calculators.
 */
function ukladka_trotuarnoy_plitki_register_calculator_post_type() {
	ukladka_trotuarnoy_plitki_register_private_content_type(
		'calculator',
		'Калькуляторы',
		'Калькулятор',
		'dashicons-calculator',
		21
	);
}
add_action( 'init', 'ukladka_trotuarnoy_plitki_register_calculator_post_type' );

/**
 * Registers internal client reviews.
 */
function ukladka_trotuarnoy_plitki_register_client_review_post_type() {
	register_post_type(
		'client_review',
		array(
			'labels'              => array(
				'name'               => 'Отзывы',
				'singular_name'      => 'Отзыв',
				'menu_name'          => 'Отзывы',
				'add_new_item'       => 'Добавить отзыв',
				'edit_item'          => 'Редактировать отзыв',
				'all_items'          => 'Все отзывы',
				'not_found'          => 'Отзывы не найдены',
				'not_found_in_trash' => 'В корзине отзывы не найдены',
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'show_in_nav_menus'   => false,
			'menu_icon'           => 'dashicons-testimonial',
			'query_var'           => false,
			'rewrite'             => false,
			'has_archive'         => false,
			'menu_position'       => 22,
			'supports'            => array( 'title', 'thumbnail', 'excerpt', 'revisions' ),
		)
	);
}
add_action( 'init', 'ukladka_trotuarnoy_plitki_register_client_review_post_type' );

/**
 * Registers reusable paving collections.
 */
function ukladka_trotuarnoy_plitki_register_paving_collection_post_type() {
	ukladka_trotuarnoy_plitki_register_private_content_type(
		'paving_collection',
		'Коллекции тротуарной плитки',
		'Коллекция тротуарной плитки',
		'dashicons-screenoptions',
		23,
		array( 'title', 'thumbnail', 'page-attributes' )
	);
}
add_action( 'init', 'ukladka_trotuarnoy_plitki_register_paving_collection_post_type' );

/**
 * Registers reusable forms.
 */
function ukladka_trotuarnoy_plitki_register_site_form_post_type() {
	ukladka_trotuarnoy_plitki_register_private_content_type(
		'site_form',
		'Формы',
		'Форма',
		'dashicons-feedback',
		24
	);
}
add_action( 'init', 'ukladka_trotuarnoy_plitki_register_site_form_post_type' );

/**
 * Flushes rewrite rules once when the public content model changes.
 */
function ukladka_trotuarnoy_plitki_maybe_flush_rewrite_rules() {
	$rewrite_version = UKLADKA_THEME_VERSION . '-content-types-2';

	if ( $rewrite_version !== get_option( 'ukladka_theme_rewrite_version' ) ) {
		flush_rewrite_rules();
		update_option( 'ukladka_theme_rewrite_version', $rewrite_version, false );
	}
}
add_action( 'init', 'ukladka_trotuarnoy_plitki_maybe_flush_rewrite_rules', 99 );

/**
 * Removes internal reviews from XML sitemap providers.
 *
 * @param array $post_types Sitemap post types.
 * @return array
 */
function ukladka_trotuarnoy_plitki_exclude_client_reviews_from_sitemaps( $post_types ) {
	unset( $post_types['client_review'] );

	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'ukladka_trotuarnoy_plitki_exclude_client_reviews_from_sitemaps' );

/**
 * Removes internal reviews from Yoast XML sitemap.
 *
 * @param bool   $excluded Whether post type is excluded.
 * @param string $post_type Post type.
 * @return bool
 */
function ukladka_trotuarnoy_plitki_yoast_exclude_client_reviews( $excluded, $post_type ) {
	return 'client_review' === $post_type ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'ukladka_trotuarnoy_plitki_yoast_exclude_client_reviews', 10, 2 );

/**
 * Removes internal reviews from Rank Math XML sitemap.
 *
 * @param bool   $excluded Whether post type is excluded.
 * @param string $post_type Post type.
 * @return bool
 */
function ukladka_trotuarnoy_plitki_rank_math_exclude_client_reviews( $excluded, $post_type ) {
	return 'client_review' === $post_type ? true : $excluded;
}
add_filter( 'rank_math/sitemap/exclude_post_type', 'ukladka_trotuarnoy_plitki_rank_math_exclude_client_reviews', 10, 2 );

/**
 * Registers a non-public reusable content type.
 *
 * @param string $post_type Post type name.
 * @param string $plural Plural label.
 * @param string $singular Singular label.
 * @param string $icon Dashicon.
 * @param int    $position Menu position.
 * @param array  $supports Editor supports.
 */
function ukladka_trotuarnoy_plitki_register_private_content_type( $post_type, $plural, $singular, $icon, $position, $supports = array( 'title', 'revisions' ) ) {
	register_post_type(
		$post_type,
		array(
			'labels'              => array(
				'name'               => $plural,
				'singular_name'      => $singular,
				'menu_name'          => $plural,
				'add_new_item'       => sprintf( 'Добавить: %s', $singular ),
				'edit_item'          => sprintf( 'Редактировать: %s', $singular ),
				'all_items'          => $plural,
				'not_found'          => sprintf( '%s не найдены', $plural ),
				'not_found_in_trash' => sprintf( 'В корзине ничего не найдено: %s', $plural ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'show_in_nav_menus'   => false,
			'menu_icon'           => $icon,
			'query_var'           => false,
			'rewrite'             => false,
			'has_archive'         => false,
			'menu_position'       => $position,
			'supports'            => $supports,
		)
	);
}
