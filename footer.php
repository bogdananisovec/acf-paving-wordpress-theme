<?php
/**
 * Site footer and shared dialogs.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$company_name = ukladka_trotuarnoy_plitki_get_option( 'company_name', get_bloginfo( 'name' ) );
$tagline      = ukladka_trotuarnoy_plitki_get_option( 'company_tagline', 'В Краснодаре' );
$phone        = ukladka_trotuarnoy_plitki_get_option( 'local_shortcode_phone', '+7 (999) 999-99-99' );
$phone_url    = ukladka_trotuarnoy_plitki_get_option( 'local_shortcode_phone_url', preg_replace( '/[^0-9+]/', '', $phone ) );
$phone_icon   = ukladka_trotuarnoy_plitki_get_option( 'footer_phone_icon', 0 );
$hours        = ukladka_trotuarnoy_plitki_get_option( 'work_hours', 'Ежедневно с 9:00 до 22:00' );
$address      = ukladka_trotuarnoy_plitki_get_option( 'site_address', '' );
$modals       = (array) ukladka_trotuarnoy_plitki_get_option( 'site_modals', array() );
$email        = sanitize_email( ukladka_trotuarnoy_plitki_get_option( 'site_email', get_option( 'admin_email' ) ) );
$email_icon   = ukladka_trotuarnoy_plitki_get_option( 'footer_email_icon', 0 );
$show_copy_email = function_exists( 'get_field' ) ? (bool) get_field( 'show_copy_email_button', 'option' ) : true;
$callback_button_id = sanitize_key( (string) ukladka_trotuarnoy_plitki_get_option( 'contact_callback_button_id', '' ) );
$socials      = (array) ukladka_trotuarnoy_plitki_get_option( 'social_links', array() );
$ratings      = (array) ukladka_trotuarnoy_plitki_get_option( 'rating_sources', array() );
$footer_logo  = ukladka_trotuarnoy_plitki_get_option( 'footer_logo', ukladka_trotuarnoy_plitki_get_option( 'site_logo', 0 ) );
$office_name  = ukladka_trotuarnoy_plitki_get_option( 'office_name', 'Краснодар, Головной офис' );
$office_hours = ukladka_trotuarnoy_plitki_get_option( 'office_work_hours', $hours );
$footer_menu_locations = get_nav_menu_locations();
$footer_menu_id = $footer_menu_locations['footer-menu'] ?? 0;
$footer_menu_items = $footer_menu_id ? wp_get_nav_menu_items( $footer_menu_id ) : array();

$footer_menu_tree = array();
foreach ( (array) $footer_menu_items as $menu_item ) {
	$parent_id = (int) $menu_item->menu_item_parent;
	if ( 0 === $parent_id ) {
		$footer_menu_tree[ $menu_item->ID ] = array(
			'item'     => $menu_item,
			'children' => array(),
		);
		continue;
	}

	foreach ( $footer_menu_tree as &$footer_menu_column ) {
		if ( (int) $footer_menu_column['item']->ID === $parent_id ) {
			$footer_menu_column['children'][ $menu_item->ID ] = array(
				'item'     => $menu_item,
				'children' => array(),
			);
			continue 2;
		}

		foreach ( $footer_menu_column['children'] as &$footer_menu_group ) {
			if ( (int) $footer_menu_group['item']->ID === $parent_id ) {
				$footer_menu_group['children'][ $menu_item->ID ] = array(
					'item'     => $menu_item,
					'children' => array(),
				);
				continue 3;
			}
		}
		unset( $footer_menu_group );
	}
	unset( $footer_menu_column );
}

$footer_render_menu_link = static function ( $menu_item ) {
	?>
	<a href="<?php echo esc_url( $menu_item->url ); ?>"><?php echo esc_html( $menu_item->title ); ?></a>
	<?php
};
?>
	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="site-footer__top">
				<div class="site-footer__brand">
					<?php if ( $footer_logo ) : ?>
						<span class="site-footer__logo"><?php ukladka_trotuarnoy_plitki_render_image( $footer_logo, 'site-footer__logo-image', 'thumbnail' ); ?></span>
					<?php else : ?>
						<span class="site-footer__logo-mark" aria-hidden="true">К</span>
					<?php endif; ?>
					<span>
						<strong class="site-footer__name"><?php echo esc_html( $company_name ); ?></strong>
						<span class="site-footer__tagline"><?php echo esc_html( $tagline ); ?></span>
					</span>
				</div>

				<div class="site-footer__ratings" aria-label="<?php esc_attr_e( 'Рейтинг компании', 'ukladka-trotuarnoy-plitki' ); ?>">
					<?php if ( $ratings ) : ?>
						<?php foreach ( array_slice( $ratings, 0, 2 ) as $rating ) : ?>
							<?php if ( ! empty( $rating['is_active'] ) ) : ?>
								<a class="site-footer__rating" href="<?php echo esc_url( $rating['link'] ?? '#' ); ?>">
									<?php ukladka_trotuarnoy_plitki_render_image( $rating['icon'] ?? 0, 'site-footer__rating-icon', 'thumbnail' ); ?>
									<span class="site-footer__stars" aria-hidden="true">
										<?php for ( $star_index = 0; $star_index < 5; $star_index++ ) : ?>
											<svg width="14" height="14" viewBox="0 0 14 14" fill="none" focusable="false"><path d="M6.99965 11.7285L3.60648 13.8687C3.45658 13.9686 3.29986 14.0114 3.13634 13.9971C2.97281 13.9829 2.82972 13.9258 2.70708 13.8259C2.58443 13.726 2.48904 13.6013 2.42091 13.4518C2.35277 13.3023 2.33915 13.1345 2.38003 12.9484L3.27942 8.90338L0.274623 6.18528C0.138351 6.05686 0.0533171 5.91047 0.0195216 5.7461C-0.0142739 5.58173 -0.00418977 5.42136 0.049774 5.26498C0.103738 5.1086 0.185501 4.98018 0.295064 4.87974C0.404627 4.77929 0.554526 4.71508 0.744762 4.68711L4.71028 4.32327L6.24334 0.513656C6.31148 0.342438 6.41722 0.214023 6.56058 0.128414C6.70394 0.0428046 6.8503 0 6.99965 0C7.14901 0 7.29536 0.0428046 7.43872 0.128414C7.58208 0.214023 7.68783 0.342438 7.75596 0.513656L9.28902 4.32327L13.2545 4.68711C13.4453 4.71565 13.5952 4.77986 13.7042 4.87974C13.8133 4.97961 13.895 5.10803 13.9495 5.26498C14.004 5.42193 14.0144 5.58259 13.9806 5.74696C13.9468 5.91133 13.8615 6.05744 13.7247 6.18528L10.7199 8.90338L11.6193 12.9484C11.6602 13.1339 11.6465 13.3017 11.5784 13.4518C11.5103 13.6019 11.4149 13.7266 11.2922 13.8259C11.1696 13.9252 11.0265 13.9823 10.863 13.9971C10.6994 14.012 10.5427 13.9692 10.3928 13.8687L6.99965 11.7285Z" fill="#FFCC00"/></svg>
										<?php endfor; ?>
									</span>
									<strong><?php echo esc_html( $rating['rating'] ?? '' ); ?></strong>
									<span class="site-footer__rating-arrow" aria-hidden="true">
										<svg width="4" height="8" viewBox="0 0 4 8" fill="none" focusable="false">
											<path d="M0.5 0.5L3.5 4L0.5 7.5" stroke="#F9F9FB" stroke-linecap="round"/>
										</svg>
									</span>
								</a>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php else : ?>
						<span class="site-footer__rating"><span class="site-footer__stars">★★★★★</span> <strong>5,0</strong></span>
						<span class="site-footer__rating"><span class="site-footer__stars">★★★★★</span> <strong>4,9</strong></span>
					<?php endif; ?>
				</div>

				<a class="site-footer__phone" href="<?php echo esc_url( 'tel:' . $phone_url ); ?>">
					<?php if ( $phone_icon ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $phone_icon, 'site-footer__phone-icon', 'thumbnail' ); ?><?php endif; ?>
					<span class="site-footer__phone-copy">
						<strong><?php echo esc_html( $phone ); ?></strong>
						<span><?php echo esc_html( $hours ); ?></span>
					</span>
				</a>

				<div class="site-footer__socials">
					<?php foreach ( $socials as $social ) : ?>
						<?php if ( ! empty( $social['is_active'] ) && ! empty( $social['url']['url'] ) ) : ?>
							<a href="<?php echo esc_url( $social['url']['url'] ); ?>" aria-label="<?php echo esc_attr( $social['label'] ?? '' ); ?>">
								<?php ukladka_trotuarnoy_plitki_render_image( $social['icon'] ?? 0, 'site-footer__social-icon', 'thumbnail' ); ?>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

				<div class="site-footer__email">
					<a class="site-footer__email-link" href="<?php echo esc_url( 'mailto:' . $email ); ?>">
						<?php if ( $email_icon ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $email_icon, 'site-footer__email-icon', 'thumbnail' ); ?><?php endif; ?>
						<span><?php echo esc_html( $email ); ?></span>
					</a>
					<?php if ( $show_copy_email ) : ?>
						<button class="site-footer__copy" type="button" data-copy-value="<?php echo esc_attr( $email ); ?>">
							<?php if ( $email_icon ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $email_icon, 'site-footer__email-icon', 'thumbnail' ); ?><?php endif; ?>
							<span>Скопировать почту</span>
						</button>
					<?php endif; ?>
				</div>

				<?php if ( $callback_button_id ) : ?>
					<?php ukladka_trotuarnoy_plitki_render_button( array( 'button_id' => $callback_button_id ), 'site-footer__callback' ); ?>
				<?php endif; ?>
			</div>

			<?php if ( $footer_menu_tree ) : ?>
			<nav class="site-footer__navigation" aria-label="<?php esc_attr_e( 'Навигация в подвале', 'ukladka-trotuarnoy-plitki' ); ?>">
				<?php foreach ( $footer_menu_tree as $column ) : ?>
					<div class="site-footer__column">
						<?php $column_title = $column['title'] ?? $column['item']->title; ?>
						<h2 class="site-footer__column-title"><?php echo esc_html( $column_title ); ?></h2>
						<?php $direct_footer_items = array_filter( $column['children'], static fn( $child ) => empty( $child['children'] ) ); ?>
						<?php if ( $direct_footer_items ) : ?>
							<ul>
								<?php foreach ( $direct_footer_items as $child ) : ?>
									<li><?php $footer_render_menu_link( $child['item'] ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<?php foreach ( $column['children'] as $child ) : ?>
							<?php if ( $child['children'] ) : ?>
								<h3 class="site-footer__group-title"><?php echo esc_html( $child['item']->title ); ?></h3>
								<ul>
									<?php foreach ( $child['children'] as $group_child ) : ?>
										<li><?php $footer_render_menu_link( $group_child['item'] ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php if ( 'О компании' === $column_title ) : ?>
							<h3 class="site-footer__address-title">Контакты</h3>
							<h4 class="site-footer__office-title">Адрес</h4>
							<?php if ( $office_name || $address || $office_hours || $phone ) : ?>
								<address class="site-footer__address">
									<?php if ( $office_name ) : ?><strong><?php echo esc_html( $office_name ); ?></strong><?php endif; ?>
									<ul>
										<?php if ( $address ) : ?><li><?php echo esc_html( $address ); ?></li><?php endif; ?>
										<?php if ( $office_hours ) : ?><li><?php echo esc_html( $office_hours ); ?></li><?php endif; ?>
										<?php if ( $phone ) : ?><li><a href="<?php echo esc_url( 'tel:' . $phone_url ); ?>"><?php echo esc_html( $phone ); ?></a></li><?php endif; ?>
									</ul>
								</address>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</nav>
			<?php endif; ?>

			<div class="site-footer__bottom">
				<?php
				$privacy = ukladka_trotuarnoy_plitki_get_option( 'footer_privacy', array() );
				if ( is_array( $privacy ) && ! empty( $privacy['url'] ) ) :
					?>
					<a href="<?php echo esc_url( $privacy['url'] ); ?>"><?php echo esc_html( $privacy['title'] ?? 'Политика конфиденциальности' ); ?></a>
				<?php else : ?>
					<span>Политика конфиденциальности</span>
				<?php endif; ?>
				<?php
				$copyright = ukladka_trotuarnoy_plitki_get_option( 'footer_copyright', array() );
				if ( is_array( $copyright ) && ! empty( $copyright['url'] ) ) :
					?>
					<a href="<?php echo esc_url( $copyright['url'] ); ?>"><?php echo esc_html( $copyright['title'] ?? 'Согласие на обработку персональных данных' ); ?></a>
				<?php else : ?>
					<span>Согласие на обработку персональных данных</span>
				<?php endif; ?>
			</div>
		</div>
	</footer>

	<?php
	if ( ! $modals ) {
		$modals = array(
			array(
				'modal_id'         => 'callback',
				'title'            => 'Закажите бесплатный выезд мастера!',
				'phone_label'      => 'Введите ваш номер телефона',
				'messenger_label'  => 'Какой способ связи удобнее?',
				'phone_placeholder' => '+7 (___) ___-__-__',
				'messengers'       => array(
					array(
						'label'    => 'Звонок',
						'value'    => 'phone',
						'selected' => true,
					),
					array(
						'label' => 'Телеграм',
						'value' => 'telegram',
					),
					array(
						'label' => 'Макс',
						'value' => 'max',
					),
					array(
						'label' => 'Вотсап',
						'value' => 'whatsapp',
					),
				),
				'privacy_text'     => 'Я согласен(-на) на обработку моих персональных данных в соответствии с Политикой конфиденциальности.',
				'submit_text'      => 'Оставить заявку',
				'is_active'        => true,
			),
		);
	}

	foreach ( $modals as $modal ) :
		if ( empty( $modal['is_active'] ) || empty( $modal['modal_id'] ) ) {
			continue;
		}
		$modal_id = sanitize_title( $modal['modal_id'] );
		?>
		<div class="site-modal" id="<?php echo esc_attr( $modal_id ); ?>" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="<?php echo esc_attr( $modal_id ); ?>-title">
			<div class="site-modal__backdrop" data-modal-close></div>
			<div class="site-modal__dialog">
				<button class="site-modal__close" type="button" data-modal-close aria-label="<?php esc_attr_e( 'Закрыть', 'ukladka-trotuarnoy-plitki' ); ?>">× <span>Закрыть</span></button>
				<h2 class="site-modal__title" id="<?php echo esc_attr( $modal_id ); ?>-title"><?php echo esc_html( $modal['title'] ?? '' ); ?></h2>
				<form class="site-modal__form" method="post" data-site-form data-form-id="<?php echo esc_attr( $modal_id ); ?>">
					<label class="screen-reader-text" aria-hidden="true">Сайт<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
					<label class="site-modal__field">
						<span class="site-modal__field-label"><b>01</b><?php echo esc_html( $modal['phone_label'] ?? 'Номер телефона' ); ?></span>
						<input type="tel" name="phone" placeholder="<?php echo esc_attr( $modal['phone_placeholder'] ?? '+7 (___) ___-__-__' ); ?>" required>
					</label>
					<?php if ( ! empty( $modal['messengers'] ) ) : ?>
						<fieldset class="site-modal__messengers">
							<legend class="site-modal__field-label"><b>02</b><?php echo esc_html( $modal['messenger_label'] ?? 'Какой способ связи удобнее?' ); ?></legend>
							<div class="site-modal__messenger-options">
								<?php foreach ( (array) $modal['messengers'] as $messenger_index => $messenger ) : ?>
									<?php
									$messenger_value = sanitize_key( $messenger['value'] ?? $messenger['contact_id'] ?? 'messenger-' . $messenger_index );
									$is_selected     = ! empty( $messenger['selected'] ) || ( 0 === $messenger_index && ! array_filter( array_column( (array) $modal['messengers'], 'selected' ) ) );
									?>
									<label class="site-modal__messenger">
										<input type="radio" name="messenger" value="<?php echo esc_attr( $messenger_value ); ?>" <?php checked( $is_selected ); ?>>
										<span>
											<?php if ( ! empty( $messenger['icon'] ) ) : ?>
												<?php ukladka_trotuarnoy_plitki_render_image( $messenger['icon'], 'site-modal__messenger-icon', 'thumbnail' ); ?>
											<?php endif; ?>
											<?php echo esc_html( $messenger['label'] ?? $messenger_value ); ?>
										</span>
									</label>
								<?php endforeach; ?>
							</div>
						</fieldset>
					<?php endif; ?>
					<?php if ( ! empty( $modal['privacy_text'] ) ) : ?>
						<label class="site-modal__privacy">
							<input type="checkbox" name="privacy" value="1" checked required>
							<span><?php echo wp_kses_post( $modal['privacy_text'] ); ?></span>
						</label>
					<?php endif; ?>
					<button class="button site-modal__submit" type="submit"><?php echo esc_html( $modal['submit_text'] ?? 'Отправить' ); ?></button>
				</form>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
