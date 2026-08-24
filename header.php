<?php
/**
 * Site header.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$company_name = ukladka_trotuarnoy_plitki_get_option( 'company_name', get_bloginfo( 'name' ) );
$tagline      = ukladka_trotuarnoy_plitki_get_option( 'company_tagline', 'В Краснодаре' );
$phone        = ukladka_trotuarnoy_plitki_get_option( 'local_shortcode_phone', '+7 (999) 999-99-99' );
$phone_url    = preg_replace( '/[^0-9+]/', '', (string) ukladka_trotuarnoy_plitki_get_option( 'local_shortcode_phone_url', $phone ) );
$phone_icon   = ukladka_trotuarnoy_plitki_get_option( 'header_phone_icon', 0 );
$hours        = ukladka_trotuarnoy_plitki_get_option( 'work_hours', 'Ежедневно с 9:00 до 22:00' );
$email        = sanitize_email( ukladka_trotuarnoy_plitki_get_option( 'site_email', get_option( 'admin_email' ) ) );
$email_icon   = ukladka_trotuarnoy_plitki_get_option( 'header_email_icon', 0 );
$show_copy_email = function_exists( 'get_field' ) ? (bool) get_field( 'show_copy_email_button', 'option' ) : true;
$callback_button_id = sanitize_key( (string) ukladka_trotuarnoy_plitki_get_option( 'contact_callback_button_id', '' ) );
$socials      = (array) ukladka_trotuarnoy_plitki_get_option( 'social_links', array() );
$site_logo    = ukladka_trotuarnoy_plitki_get_option( 'site_logo', 0 );
$ratings      = (array) ukladka_trotuarnoy_plitki_get_option( 'rating_sources', array() );
$socials      = array_slice( array_values( array_filter( $socials, static fn( $social ) => ! empty( $social['is_active'] ) ) ), 0, 2 );
$ratings      = array_slice( array_values( array_filter( $ratings, static fn( $rating ) => ! empty( $rating['is_active'] ) ) ), 0, 2 );
$office_name  = ukladka_trotuarnoy_plitki_get_option( 'office_name', 'Краснодар, Головной офис' );
$office_hours = ukladka_trotuarnoy_plitki_get_option( 'office_work_hours', $hours );
$address      = ukladka_trotuarnoy_plitki_get_option( 'site_address', '' );
$privacy      = ukladka_trotuarnoy_plitki_get_option( 'footer_privacy', array() );
$personal     = ukladka_trotuarnoy_plitki_get_option( 'footer_copyright', array() );

$footer_menu_locations = get_nav_menu_locations();
$footer_menu_id        = $footer_menu_locations['footer-menu'] ?? 0;
$footer_menu_items     = $footer_menu_id ? wp_get_nav_menu_items( $footer_menu_id ) : array();
$mobile_menu_tree      = array();

foreach ( (array) $footer_menu_items as $menu_item ) {
	$parent_id = (int) $menu_item->menu_item_parent;
	if ( 0 === $parent_id ) {
		$mobile_menu_tree[ $menu_item->ID ] = array(
			'item'     => $menu_item,
			'children' => array(),
		);
		continue;
	}

	foreach ( $mobile_menu_tree as &$mobile_menu_column ) {
		if ( (int) $mobile_menu_column['item']->ID === $parent_id ) {
			$mobile_menu_column['children'][ $menu_item->ID ] = array(
				'item'     => $menu_item,
				'children' => array(),
			);
			continue 2;
		}

		foreach ( $mobile_menu_column['children'] as &$mobile_menu_group ) {
			if ( (int) $mobile_menu_group['item']->ID === $parent_id ) {
				$mobile_menu_group['children'][ $menu_item->ID ] = array(
					'item'     => $menu_item,
					'children' => array(),
				);
				continue 3;
			}
		}
		unset( $mobile_menu_group );
	}
	unset( $mobile_menu_column );
}

$mobile_menu_sections = array();
$menu_columns         = array_values( $mobile_menu_tree );

if ( $menu_columns ) {
	$services_column = array_shift( $menu_columns );
	$service_groups  = array_filter(
		$services_column['children'],
		static fn( $child ) => ! empty( $child['children'] ) && 'Другие материалы' !== trim( $child['item']->title )
	);
	$services_column['children'] = array_filter(
		$services_column['children'],
		static fn( $child ) => empty( $child['children'] ) || 'Другие материалы' === trim( $child['item']->title )
	);
	$mobile_menu_sections[] = array_merge( $services_column, array( 'icon' => 'services' ) );

	foreach ( $service_groups as $service_group ) {
		$mobile_menu_sections[] = array(
			'item'     => $service_group['item'],
			'children' => $service_group['children'],
			'icon'     => count( $mobile_menu_sections ) === 1 ? 'paths' : 'prices',
		);
	}
}

$remaining_icons = array( 'portfolio', 'useful', 'about' );
foreach ( $menu_columns as $menu_index => $column ) {
	$mobile_menu_sections[] = array_merge(
		$column,
		array( 'icon' => $remaining_icons[ $menu_index ] ?? 'useful' )
	);
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Перейти к содержимому', 'ukladka-trotuarnoy-plitki' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-header__top">
			<div class="site-header__container container">
				<a class="site-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php if ( $site_logo ) : ?>
						<span class="site-header__logo"><?php ukladka_trotuarnoy_plitki_render_image( $site_logo, 'site-header__logo-image', 'thumbnail' ); ?></span>
					<?php elseif ( has_custom_logo() ) : ?>
						<span class="site-header__logo"><?php the_custom_logo(); ?></span>
					<?php else : ?>
						<span class="site-header__logo-mark" aria-hidden="true">К</span>
					<?php endif; ?>
					<span class="site-header__brand-copy">
						<strong class="site-header__name"><?php echo esc_html( $company_name ); ?></strong>
						<span class="site-header__tagline"><?php echo esc_html( $tagline ); ?></span>
					</span>
				</a>

				<?php if ( $ratings ) : ?>
					<div class="site-header__ratings" aria-label="<?php esc_attr_e( 'Рейтинг компании', 'ukladka-trotuarnoy-plitki' ); ?>">
						<?php foreach ( $ratings as $rating ) : ?>
							<?php $rating_label = $rating['admin_title'] ?? $rating['source_id'] ?? __( 'Рейтинг компании', 'ukladka-trotuarnoy-plitki' ); ?>
							<?php if ( ! empty( $rating['link'] ) ) : ?>
								<a class="site-header__rating" href="<?php echo esc_url( $rating['link'] ); ?>" aria-label="<?php echo esc_attr( $rating_label ); ?>">
							<?php else : ?>
								<span class="site-header__rating" aria-label="<?php echo esc_attr( $rating_label ); ?>">
							<?php endif; ?>
								<?php if ( ! empty( $rating['icon'] ) ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $rating['icon'], 'site-header__rating-icon', 'thumbnail' ); ?><?php endif; ?>
								<span class="site-header__rating-stars" aria-hidden="true">
									<?php for ( $star_index = 0; $star_index < 5; $star_index++ ) : ?>
										<svg viewBox="0 0 14 14" focusable="false">
											<path d="M6.99965 11.7285L3.60648 13.8687C3.45658 13.9686 3.29986 14.0114 3.13634 13.9971C2.97281 13.9829 2.82972 13.9258 2.70708 13.8259C2.58443 13.726 2.48904 13.6013 2.42091 13.4518C2.35277 13.3023 2.33915 13.1345 2.38003 12.9484L3.27942 8.90338L0.274623 6.18528C0.138351 6.05686 0.0533171 5.91047 0.0195216 5.7461C-0.0142739 5.58173 -0.00418977 5.42136 0.049774 5.26498C0.103738 5.1086 0.185501 4.98018 0.295064 4.87974C0.404627 4.77929 0.554526 4.71508 0.744762 4.68711L4.71028 4.32327L6.24334 0.513656C6.31148 0.342438 6.41722 0.214023 6.56058 0.128414C6.70394 0.0428046 6.8503 0 6.99965 0C7.14901 0 7.29536 0.0428046 7.43872 0.128414C7.58208 0.214023 7.68783 0.342438 7.75596 0.513656L9.28902 4.32327L13.2545 4.68711C13.4453 4.71565 13.5952 4.77986 13.7042 4.87974C13.8133 4.97961 13.895 5.10803 13.9495 5.26498C14.004 5.42193 14.0144 5.58259 13.9806 5.74696C13.9468 5.91133 13.8615 6.05744 13.7247 6.18528L10.7199 8.90338L11.6193 12.9484C11.6602 13.1339 11.6465 13.3017 11.5784 13.4518C11.5103 13.6019 11.4149 13.7266 11.2922 13.8259C11.1696 13.9252 11.0265 13.9823 10.863 13.9971C10.6994 14.012 10.5427 13.9692 10.3928 13.8687L6.99965 11.7285Z" fill="#FFCC00"/>
										</svg>
									<?php endfor; ?>
								</span>
								<strong><?php echo esc_html( $rating['rating'] ?? '' ); ?></strong>
							<?php if ( ! empty( $rating['link'] ) ) : ?>
								</a>
							<?php else : ?>
								</span>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( $phone ) : ?>
					<a class="site-header__phone" href="<?php echo esc_url( 'tel:' . $phone_url ); ?>">
						<?php if ( $phone_icon ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $phone_icon, 'site-header__phone-icon', 'thumbnail' ); ?><?php endif; ?>
						<span class="site-header__phone-copy">
							<strong><?php echo esc_html( $phone ); ?></strong>
							<span><?php echo esc_html( $hours ); ?></span>
						</span>
					</a>
				<?php endif; ?>

				<div class="site-header__socials">
					<?php foreach ( $socials as $social ) : ?>
						<?php if ( ! empty( $social['url']['url'] ) ) : ?>
							<a class="site-header__social" href="<?php echo esc_url( $social['url']['url'] ); ?>" aria-label="<?php echo esc_attr( $social['label'] ?? $social['social_id'] ?? '' ); ?>">
								<?php ukladka_trotuarnoy_plitki_render_image( $social['icon'] ?? 0, 'site-header__social-icon', 'thumbnail' ); ?>
								<span><?php echo esc_html( mb_substr( $social['label'] ?? $social['social_id'] ?? '', 0, 1 ) ); ?></span>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

				<?php if ( $email ) : ?>
					<div class="site-header__email">
						<a class="site-header__email-link" href="<?php echo esc_url( 'mailto:' . $email ); ?>">
							<?php if ( $email_icon ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $email_icon, 'site-header__email-icon', 'thumbnail' ); ?><?php endif; ?>
							<span><?php echo esc_html( $email ); ?></span>
						</a>
						<?php if ( $show_copy_email ) : ?>
							<button class="site-header__copy" type="button" data-copy-value="<?php echo esc_attr( $email ); ?>">
								<?php if ( $email_icon ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $email_icon, 'site-header__email-icon', 'thumbnail' ); ?><?php endif; ?>
								<span>Скопировать почту</span>
							</button>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $callback_button_id ) : ?>
					<?php ukladka_trotuarnoy_plitki_render_button( array( 'button_id' => $callback_button_id ), 'site-header__callback' ); ?>
				<?php endif; ?>
				<button class="menu-toggle site-header__menu-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
					<span></span><span></span><span></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Открыть меню', 'ukladka-trotuarnoy-plitki' ); ?></span>
				</button>
			</div>
		</div>

		<nav id="site-navigation" class="main-navigation">
			<div class="container main-navigation__container">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'main-navigation__list',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 2,
					)
				);
				?>
				<a class="main-navigation__mobile-phone" href="<?php echo esc_url( 'tel:' . $phone_url ); ?>"><?php echo esc_html( $phone ); ?></a>

				<div class="main-navigation__mobile" aria-label="<?php esc_attr_e( 'Мобильное меню', 'ukladka-trotuarnoy-plitki' ); ?>">
					<div class="main-navigation__ratings" aria-label="<?php esc_attr_e( 'Рейтинг компании', 'ukladka-trotuarnoy-plitki' ); ?>">
						<?php foreach ( $ratings as $rating ) : ?>
							<?php $rating_label = $rating['admin_title'] ?? $rating['source_id'] ?? __( 'Рейтинг компании', 'ukladka-trotuarnoy-plitki' ); ?>
							<a class="main-navigation__rating" href="<?php echo esc_url( $rating['link'] ?? '#' ); ?>" aria-label="<?php echo esc_attr( $rating_label ); ?>">
								<?php if ( ! empty( $rating['icon'] ) ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $rating['icon'], 'main-navigation__rating-icon', 'thumbnail' ); ?><?php endif; ?>
								<span class="main-navigation__stars" aria-hidden="true">★★★★★</span>
								<strong><?php echo esc_html( $rating['rating'] ?? '' ); ?></strong>
							</a>
						<?php endforeach; ?>
					</div>

					<div class="main-navigation__cards">
						<div class="main-navigation__card main-navigation__card--menu">
							<a class="main-navigation__menu-row main-navigation__menu-row--home" href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<span class="main-navigation__menu-icon" aria-hidden="true"></span>
								<span><?php esc_html_e( 'Главная', 'ukladka-trotuarnoy-plitki' ); ?></span>
							</a>
							<?php foreach ( $mobile_menu_sections as $column ) : ?>
								<?php
								$column_item = $column['item'];
								$panel_id    = 'mobile-menu-panel-' . (int) $column_item->ID;
								$icon_name   = $column['icon'];
								?>
								<div class="main-navigation__group main-navigation__group--<?php echo esc_attr( $icon_name ); ?>">
									<div class="main-navigation__menu-row">
										<span class="main-navigation__menu-icon" aria-hidden="true"></span>
										<a href="<?php echo esc_url( $column_item->url ); ?>"><?php echo esc_html( $column_item->title ); ?></a>
										<?php if ( $column['children'] ) : ?>
											<button class="main-navigation__submenu-toggle" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
												<span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Раскрыть раздел %s', 'ukladka-trotuarnoy-plitki' ), $column_item->title ) ); ?></span>
											</button>
										<?php endif; ?>
									</div>
									<?php if ( $column['children'] ) : ?>
										<div id="<?php echo esc_attr( $panel_id ); ?>" class="main-navigation__submenu" hidden>
											<?php $direct_items = array_filter( $column['children'], static fn( $child ) => empty( $child['children'] ) ); ?>
											<?php if ( $direct_items ) : ?>
												<ul>
													<?php foreach ( $direct_items as $child ) : ?>
														<li><a href="<?php echo esc_url( $child['item']->url ); ?>"><?php echo esc_html( $child['item']->title ); ?></a></li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>
											<?php foreach ( $column['children'] as $child ) : ?>
												<?php if ( $child['children'] ) : ?>
													<h3><?php echo esc_html( $child['item']->title ); ?></h3>
													<ul>
														<?php foreach ( $child['children'] as $group_child ) : ?>
															<li><a href="<?php echo esc_url( $group_child['item']->url ); ?>"><?php echo esc_html( $group_child['item']->title ); ?></a></li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
							<a class="main-navigation__menu-row main-navigation__menu-row--contacts" href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>">
								<span class="main-navigation__menu-icon" aria-hidden="true"></span>
								<span><?php esc_html_e( 'Контакты', 'ukladka-trotuarnoy-plitki' ); ?></span>
							</a>
						</div>

						<div class="main-navigation__card main-navigation__card--contacts">
							<a class="main-navigation__contact main-navigation__contact--phone" href="<?php echo esc_url( 'tel:' . $phone_url ); ?>">
								<span class="main-navigation__contact-icon" aria-hidden="true"></span><strong><?php echo esc_html( $phone ); ?></strong>
							</a>
							<div class="main-navigation__contact main-navigation__contact--hours">
								<span class="main-navigation__contact-icon" aria-hidden="true"></span><span><?php echo esc_html( $hours ); ?></span>
							</div>
							<?php foreach ( $socials as $social ) : ?>
								<?php if ( ! empty( $social['url']['url'] ) ) : ?>
									<?php
									$social_id     = strtolower( (string) ( $social['social_id'] ?? '' ) );
									$social_names  = array(
										'tg'       => 'Телеграм',
										'telegram' => 'Телеграм',
										'max'      => 'Макс',
									);
									$social_label  = ! empty( $social['label'] ) ? $social['label'] : ( $social_names[ $social_id ] ?? $social_id );
									?>
									<a class="main-navigation__contact" href="<?php echo esc_url( $social['url']['url'] ); ?>">
										<?php ukladka_trotuarnoy_plitki_render_image( $social['icon'] ?? 0, 'main-navigation__contact-image', 'thumbnail' ); ?>
										<span><?php echo esc_html( $social_label ); ?></span>
									</a>
								<?php endif; ?>
							<?php endforeach; ?>
							<?php if ( $callback_button_id ) : ?>
								<?php ukladka_trotuarnoy_plitki_render_button( array( 'button_id' => $callback_button_id ), 'main-navigation__callback' ); ?>
							<?php endif; ?>
						</div>

						<div class="main-navigation__card main-navigation__card--address">
							<h2><?php esc_html_e( 'Адрес', 'ukladka-trotuarnoy-plitki' ); ?></h2>
							<address>
								<?php if ( $office_name ) : ?><strong><?php echo esc_html( $office_name ); ?></strong><?php endif; ?>
								<ul>
									<?php if ( $address ) : ?><li><?php echo esc_html( $address ); ?></li><?php endif; ?>
									<?php if ( $office_hours ) : ?><li><?php echo esc_html( $office_hours ); ?></li><?php endif; ?>
									<?php if ( $phone ) : ?><li><a href="<?php echo esc_url( 'tel:' . $phone_url ); ?>"><?php echo esc_html( $phone ); ?></a></li><?php endif; ?>
								</ul>
							</address>
							<div class="main-navigation__legal">
								<?php if ( is_array( $privacy ) && ! empty( $privacy['url'] ) ) : ?><a href="<?php echo esc_url( $privacy['url'] ); ?>"><?php echo esc_html( $privacy['title'] ?? 'Политика конфиденциальности' ); ?></a><?php endif; ?>
								<?php if ( is_array( $personal ) && ! empty( $personal['url'] ) ) : ?><a href="<?php echo esc_url( $personal['url'] ); ?>"><?php echo esc_html( $personal['title'] ?? 'Согласие на обработку персональных данных' ); ?></a><?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</nav>
	</header>
