<?php
/**
 * Theme setup, widget areas and assets.
 *
 * @package ukladka-trotuarnoy-plitki
 */

/**
 * Registers theme supports and navigation.
 */
function ukladka_trotuarnoy_plitki_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus(
		array(
			'menu-1'      => esc_html__( 'Primary', 'ukladka-trotuarnoy-plitki' ),
			'footer-menu' => esc_html__( 'Footer', 'ukladka-trotuarnoy-plitki' ),
		)
	);

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'ukladka_trotuarnoy_plitki_setup' );

/**
 * Registers the primary sidebar.
 */
function ukladka_trotuarnoy_plitki_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'ukladka-trotuarnoy-plitki' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'ukladka-trotuarnoy-plitki' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'ukladka_trotuarnoy_plitki_widgets_init' );

/**
 * Enqueues public theme assets.
 */
function ukladka_trotuarnoy_plitki_scripts() {
	wp_enqueue_style(
		'ukladka-trotuarnoy-plitki-fonts',
		'https://fonts.googleapis.com/css2?family=Manrope:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'ukladka-trotuarnoy-plitki-style',
		get_stylesheet_uri(),
		array( 'ukladka-trotuarnoy-plitki-fonts' ),
		ukladka_trotuarnoy_plitki_asset_version( 'style.css' )
	);

	wp_enqueue_style(
		'ukladka-trotuarnoy-plitki-theme',
		get_template_directory_uri() . '/assets/css/theme.css',
		array( 'ukladka-trotuarnoy-plitki-style' ),
		ukladka_trotuarnoy_plitki_asset_version( 'assets/css/theme.css' )
	);

	wp_enqueue_script(
		'ukladka-trotuarnoy-plitki-navigation',
		get_template_directory_uri() . '/js/navigation.js',
		array(),
		ukladka_trotuarnoy_plitki_asset_version( 'js/navigation.js' ),
		true
	);

	wp_enqueue_script(
		'ukladka-trotuarnoy-plitki-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		ukladka_trotuarnoy_plitki_asset_version( 'assets/js/theme.js' ),
		true
	);

	wp_localize_script(
		'ukladka-trotuarnoy-plitki-theme',
		'ukladkaTheme',
		array(
			'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
			'formNonce'      => wp_create_nonce( 'submit_site_form' ),
			'successMessage' => ukladka_trotuarnoy_plitki_get_option( 'leads_success_message', 'Спасибо! Мы свяжемся с вами в ближайшее время.' ),
			'errorMessage'   => 'Не удалось отправить заявку. Позвоните нам или попробуйте ещё раз.',
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ukladka_trotuarnoy_plitki_scripts' );
