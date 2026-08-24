<?php
/**
 * Shared hero layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$title   = $section['title'] ?? get_the_title( $args['post_id'] ?? 0 );
$text    = $section['text'] ?? $section['description'] ?? '';
$is_private_yard_page = is_page_template( 'page-ukladka-trotuarnoj-plitki-vo-dvore.php' );
$is_walkways_page     = is_page_template( 'page-trotuarnye-dorozhki.php' );
$post_id              = (int) ( $args['post_id'] ?? get_the_ID() );
$breadcrumb_current   = get_the_title( $post_id );
$breadcrumb_parent    = '';
$breadcrumb_items     = array();

if ( $is_private_yard_page ) {
	$breadcrumb_parent = __( 'Услуги укладки тротуарной плитки', 'ukladka-trotuarnoy-plitki' );
} elseif ( $is_walkways_page ) {
	$breadcrumb_parent = __( 'Дорожки', 'ukladka-trotuarnoy-plitki' );
} elseif ( is_page( 'stoimost-ukladki-trotuarnoj-plitki' ) ) {
	$breadcrumb_parent = __( 'Цены укладки тротуарной плитки', 'ukladka-trotuarnoy-plitki' );
} elseif ( is_page( 'o-kompanii' ) ) {
	$breadcrumb_parent = get_the_title( $post_id );
	$title_parts        = preg_split( '/[—–]/u', wp_strip_all_tags( (string) $title ), 2 );
	$breadcrumb_current = trim( (string) ( $title_parts[0] ?? $title ) );
} elseif ( is_page( 'portfolio' ) || 'portfolio' === ( $args['context'] ?? '' ) ) {
	$breadcrumb_items = array(
		__( 'Портфолио', 'ukladka-trotuarnoy-plitki' ),
		__( 'По типам объектов', 'ukladka-trotuarnoy-plitki' ),
		__( 'Дворы', 'ukladka-trotuarnoy-plitki' ),
	);
}
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="hero__wrapper">
			<?php if ( ! is_front_page() ) : ?>
				<nav class="hero__breadcrumbs" aria-label="<?php esc_attr_e( 'Хлебные крошки', 'ukladka-trotuarnoy-plitki' ); ?>">
					<a class="hero__breadcrumb-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><svg class="hero__breadcrumb-home" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3.5 12.6286H5.75V9.2C5.75 9.00571 5.822 8.84297 5.966 8.71177C6.11 8.58057 6.288 8.51474 6.5 8.51428H9.5C9.7125 8.51428 9.89075 8.58011 10.0347 8.71177C10.1787 8.84343 10.2505 9.00617 10.25 9.2V12.6286H12.5V6.45714L8 3.37143L3.5 6.45714V12.6286ZM2 12.6286V6.45714C2 6.24 2.05325 6.03429 2.15975 5.84C2.26625 5.64571 2.413 5.48571 2.6 5.36L7.1 2.27429C7.3625 2.09143 7.6625 2 8 2C8.3375 2 8.6375 2.09143 8.9 2.27429L13.4 5.36C13.5875 5.48571 13.7345 5.64571 13.841 5.84C13.9475 6.03429 14.0005 6.24 14 6.45714V12.6286C14 13.0057 13.853 13.3287 13.559 13.5975C13.265 13.8663 12.912 14.0005 12.5 14H9.5C9.2875 14 9.1095 13.9342 8.966 13.8025C8.8225 13.6709 8.7505 13.5081 8.75 13.3143V9.88571H7.25V13.3143C7.25 13.5086 7.178 13.6715 7.034 13.8032C6.89 13.9349 6.712 14.0005 6.5 14H3.5C3.0875 14 2.7345 13.8658 2.441 13.5975C2.1475 13.3291 2.0005 13.0062 2 12.6286Z" fill="currentColor"/></svg><?php esc_html_e( 'Главная', 'ukladka-trotuarnoy-plitki' ); ?></a>
					<span class="hero__breadcrumb-separator" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3.33333V12.6667M3.33333 8L8 12.6667L12.6667 8" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
					<?php if ( $breadcrumb_items ) : ?>
						<?php foreach ( $breadcrumb_items as $breadcrumb_index => $breadcrumb_item ) : ?>
							<?php $is_last_breadcrumb = count( $breadcrumb_items ) - 1 === $breadcrumb_index; ?>
							<span class="<?php echo $is_last_breadcrumb ? 'hero__breadcrumb-current' : 'hero__breadcrumb-parent'; ?>"<?php echo $is_last_breadcrumb ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $breadcrumb_item ); ?></span>
							<?php if ( ! $is_last_breadcrumb ) : ?>
								<span class="hero__breadcrumb-separator" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3.33333V12.6667M3.33333 8L8 12.6667L12.6667 8" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php else : ?>
						<?php if ( $breadcrumb_parent ) : ?>
							<span class="hero__breadcrumb-parent"><?php echo esc_html( $breadcrumb_parent ); ?></span>
							<span class="hero__breadcrumb-separator" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3.33333V12.6667M3.33333 8L8 12.6667L12.6667 8" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
						<?php endif; ?>
						<span class="hero__breadcrumb-current" aria-current="page"><?php echo esc_html( $breadcrumb_current ? $breadcrumb_current : wp_strip_all_tags( $title ) ); ?></span>
					<?php endif; ?>
				</nav>
			<?php endif; ?>

			<div class="hero__content">
				<h1 class="hero__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
				<?php if ( ! empty( $section['price'] ) ) : ?>
					<div class="hero__price"><?php echo esc_html( $section['price'] ); ?></div>
				<?php endif; ?>
				<?php if ( $text ) : ?>
					<div class="hero__text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
				<?php endif; ?>
				<?php ukladka_trotuarnoy_plitki_render_button( $section, 'hero__button' ); ?>
			</div>

			<?php if ( ! empty( $section['side_text'] ) ) : ?>
				<div class="hero__side-text"><?php echo wp_kses_post( wpautop( $section['side_text'] ) ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $section['main_image'] ) || ! empty( $section['background_image'] ) ) : ?>
				<picture class="hero__media">
					<?php ukladka_trotuarnoy_plitki_render_image( $section['main_image'] ?? $section['background_image'], 'hero__image', 'full' ); ?>
				</picture>
			<?php endif; ?>

			<?php if ( ! empty( $section['contact_card'] ) ) : ?>
				<?php $contact = $section['contact_card']; ?>
				<?php $contact_button = ukladka_trotuarnoy_plitki_resolve_button( $contact ); ?>
				<?php $contact_phone = ! empty( $contact['phone'] ) ? $contact['phone'] : ( is_front_page() ? '' : ukladka_trotuarnoy_plitki_get_option( 'local_shortcode_phone', '+7 (999) 999-99-99' ) ); ?>
				<aside class="hero__contact-card">
					<div class="hero__contact-main">
						<?php if ( ! empty( $contact['text'] ) ) : ?>
							<div class="hero__contact-text"><?php echo wp_kses_post( wpautop( $contact['text'] ) ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $contact['benefits'] ) ) : ?>
							<div class="hero__benefits">
								<?php foreach ( $contact['benefits'] as $benefit ) : ?>
									<div class="hero__benefit">
										<?php ukladka_trotuarnoy_plitki_render_image( $benefit['icon'] ?? 0, 'hero__benefit-icon', 'thumbnail' ); ?>
										<span><?php echo ukladka_trotuarnoy_plitki_format_heading( $benefit['text'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<?php if ( ( ! empty( $contact_button['text'] ) && ! empty( $contact_button['url'] ) ) || $contact_phone ) : ?>
							<div class="hero__phone-box">
								<?php if ( ! empty( $contact['phone_title'] ) ) : ?><strong><?php echo esc_html( $contact['phone_title'] ); ?></strong><?php endif; ?>
								<?php if ( ! empty( $contact_button['text'] ) && ! empty( $contact_button['url'] ) ) : ?>
									<?php ukladka_trotuarnoy_plitki_render_button( $contact, 'hero__phone' ); ?>
								<?php else : ?>
									<a class="hero__phone" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>">
										<?php ukladka_trotuarnoy_plitki_render_image( $contact['phone_icon'] ?? 0, 'hero__phone-icon', 'thumbnail' ); ?>
										<span><?php echo esc_html( $contact_phone ); ?></span>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
					<div class="hero__review-summary">
						<?php if ( ! empty( $contact['reviews_count'] ) ) : ?><span class="hero__review-count"><?php echo esc_html( $contact['reviews_count'] ); ?></span><?php endif; ?>
						<?php if ( ! empty( $contact['rating'] ) ) : ?>
							<strong><?php echo esc_html( $contact['rating'] ); ?>
								<span class="hero__stars" aria-hidden="true">
									<?php for ( $ukladka_star_index = 0; $ukladka_star_index < 5; $ukladka_star_index++ ) : ?>
										<svg class="hero__star" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.49963 12.5662L3.86408 14.8593C3.70348 14.9664 3.53557 15.0122 3.36036 14.9969C3.18515 14.9816 3.03185 14.9205 2.90044 14.8135C2.76904 14.7065 2.66683 14.5729 2.59383 14.4126C2.52083 14.2524 2.50623 14.0727 2.55003 13.8733L3.51367 9.53933L0.294239 6.62708C0.148233 6.4895 0.0571255 6.33265 0.020916 6.15654C-0.0152934 5.98043 -0.00448903 5.8086 0.0533293 5.64105C0.111148 5.4735 0.198751 5.33591 0.31614 5.22829C0.433528 5.12066 0.594135 5.05187 0.797959 5.02191L5.04673 4.63208L6.68929 0.550346C6.7623 0.366898 6.8756 0.229311 7.0292 0.137586C7.18279 0.045862 7.3396 0 7.49963 0C7.65965 0 7.81646 0.045862 7.97006 0.137586C8.12366 0.229311 8.23696 0.366898 8.30996 0.550346L9.95252 4.63208L14.2013 5.02191C14.4057 5.05248 14.5663 5.12128 14.6831 5.22829C14.7999 5.3353 14.8875 5.47289 14.9459 5.64105C15.0043 5.80921 15.0154 5.98135 14.9792 6.15746C14.943 6.33357 14.8516 6.49011 14.705 6.62708L11.4856 9.53933L12.4492 13.8733C12.493 14.072 12.4784 14.2518 12.4054 14.4126C12.3324 14.5735 12.2302 14.7071 12.0988 14.8135C11.9674 14.9199 11.8141 14.981 11.6389 14.9969C11.4637 15.0128 11.2958 14.967 11.1352 14.8593L7.49963 12.5662Z" fill="#FFCC00"/></svg>
									<?php endfor; ?>
								</span>
							</strong>
						<?php endif; ?>
						<?php if ( ! empty( $contact['avatars'] ) ) : ?>
							<div class="hero__avatars">
								<?php foreach ( $contact['avatars'] as $avatar ) : ?>
									<?php ukladka_trotuarnoy_plitki_render_image( $avatar['image'] ?? 0, 'hero__avatar', 'thumbnail' ); ?>
								<?php endforeach; ?>
								<?php if ( ! empty( $contact['more_text'] ) ) : ?><span class="hero__avatar-more"><?php echo esc_html( $contact['more_text'] ); ?></span><?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</aside>
			<?php endif; ?>

			<?php $hero_items = $section['advantages'] ?? $section['badges'] ?? $section['price_cards'] ?? $section['cards'] ?? array(); ?>
			<?php if ( $hero_items ) : ?>
				<div class="hero__items">
					<?php foreach ( (array) $hero_items as $index => $item ) : ?>
						<div class="hero__item">
							<?php if ( ! empty( $item['number'] ) ) : ?><span class="hero__item-number"><?php echo esc_html( $item['number'] ); ?></span><?php endif; ?>
							<div class="hero__item-content">
								<?php if ( ! empty( $item['title'] ) ) : ?><h3 class="hero__item-title"><?php echo esc_html( $item['title'] ); ?></h3><?php endif; ?>
								<?php if ( ! empty( $item['price'] ) ) : ?><strong class="hero__item-price"><?php echo esc_html( $item['price'] ); ?></strong><?php endif; ?>
								<?php if ( ! empty( $item['text'] ) ) : ?><p class="hero__item-text"><?php echo wp_kses_post( $item['text'] ); ?></p><?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php $hero_cta = is_array( $section['cta'] ?? null ) ? $section['cta'] : array(); ?>
			<?php if ( array_filter( $hero_cta ) ) : ?>
				<div class="hero__bottom-cta">
					<?php if ( ! empty( $hero_cta['icon'] ) ) : ?>
						<span class="hero__bottom-icon"><?php ukladka_trotuarnoy_plitki_render_image( $hero_cta['icon'], 'hero__bottom-icon-image', 'thumbnail' ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $hero_cta['title'] ) || ! empty( $hero_cta['text'] ) ) : ?>
						<div class="hero__bottom-text">
							<?php if ( ! empty( $hero_cta['title'] ) ) : ?><strong><?php echo esc_html( $hero_cta['title'] ); ?></strong><?php endif; ?>
							<?php if ( ! empty( $hero_cta['text'] ) ) : ?><p><?php echo wp_kses_post( $hero_cta['text'] ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>
					<?php ukladka_trotuarnoy_plitki_render_button( $hero_cta, 'hero__bottom-button' ); ?>
				</div>
			<?php endif; ?>

			<?php $hero_note = is_array( $section['note'] ?? null ) ? $section['note'] : array(); ?>
			<?php if ( array_filter( $hero_note ) ) : ?>
				<div class="hero__note">
					<?php if ( ! empty( $hero_note['icon'] ) ) : ?>
						<span class="hero__note-icon"><?php ukladka_trotuarnoy_plitki_render_image( $hero_note['icon'], 'hero__note-icon-image', 'thumbnail' ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $hero_note['text'] ) ) : ?>
						<div class="hero__note-text"><?php echo wp_kses_post( $hero_note['text'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
