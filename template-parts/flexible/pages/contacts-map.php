<?php
/**
 * Contact map and office cards.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section  = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$offices  = is_array( $section['offices'] ?? null ) ? $section['offices'] : array();
$map_code = trim( (string) ( $section['map_code'] ?? '' ) );
$map_link = $section['all_cities_button_link'] ?? array();
$map_url  = is_array( $map_link ) ? ( $map_link['url'] ?? '' ) : $map_link;
$map_text = $section['all_cities_button_text'] ?? 'Смотреть все города';
$map_tag  = $map_url ? 'a' : 'button';

$render_contact_icon = static function ( $icon, $type ) {
	if ( $icon ) {
		ukladka_trotuarnoy_plitki_render_image( $icon, 'contacts-map__icon', 'thumbnail' );
		return;
	}

	$icons = array(
		'address'  => '<svg class="contacts-map__icon contacts-map__icon-fallback" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 21s7-5.1 7-11a7 7 0 1 0-14 0c0 5.9 7 11 7 11Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.4" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>',
		'schedule' => '<svg class="contacts-map__icon contacts-map__icon-fallback" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="8.2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M12 7.5V12l3 2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'phone'    => '<svg class="contacts-map__icon contacts-map__icon-fallback" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M7.3 4.8 9 8.7l-1.6 1.2c1.2 2.5 3.2 4.5 5.7 5.7l1.2-1.6 3.9 1.7-.5 3.2c-.1.6-.6 1-1.2 1A12.5 12.5 0 0 1 4 7.5c0-.6.4-1.1 1-1.2l2.3-.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	);

	echo $icons[ $type ] ?? '';
};
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="contacts-map__wrapper">
			<div class="contacts-map__map<?php echo false === stripos( $map_code, '<iframe' ) ? ' contacts-map__map--empty' : ''; ?>" style="--map-height: <?php echo esc_attr( max( 320, absint( $section['map_height'] ?? 540 ) ) ); ?>px">
				<?php if ( false !== stripos( $map_code, '<iframe' ) ) : ?>
					<?php
					echo wp_kses(
						$map_code,
						array(
							'iframe' => array(
								'src'             => true,
								'width'           => true,
								'height'          => true,
								'style'           => true,
								'frameborder'     => true,
								'allowfullscreen' => true,
								'loading'         => true,
								'title'           => true,
							),
						)
					);
					?>
				<?php endif; ?>

				<?php if ( ! empty( $section['show_all_cities_button'] ) && $map_text ) : ?>
					<<?php echo esc_attr( $map_tag ); ?>
						class="button contacts-map__button"
						<?php if ( $map_url ) : ?>
							href="<?php echo esc_url( $map_url ); ?>"
							<?php if ( is_array( $map_link ) && ! empty( $map_link['target'] ) ) : ?>target="<?php echo esc_attr( $map_link['target'] ); ?>"<?php endif; ?>
						<?php else : ?>
							type="button"
						<?php endif; ?>
					><?php echo esc_html( $map_text ); ?></<?php echo esc_attr( $map_tag ); ?>>
				<?php endif; ?>
			</div>

			<?php if ( $offices ) : ?>
				<div class="contacts-map__items">
					<?php foreach ( $offices as $office ) : ?>
						<article class="contacts-map__item">
							<div class="contacts-map__item-heading">
								<?php if ( ! empty( $office['city'] ) ) : ?><span class="contacts-map__city"><?php echo esc_html( $office['city'] ); ?></span><?php endif; ?>
								<?php if ( ! empty( $office['office_title'] ) ) : ?><h2 class="contacts-map__item-title"><?php echo esc_html( $office['office_title'] ); ?></h2><?php endif; ?>
							</div>

							<div class="contacts-map__details">
								<?php if ( ! empty( $office['address'] ) ) : ?>
									<div class="contacts-map__detail">
										<span class="contacts-map__detail-icon">
											<?php $render_contact_icon( $office['address_icon'] ?? 0, 'address' ); ?>
										</span>
										<div><?php echo wp_kses_post( wpautop( $office['address'] ) ); ?></div>
									</div>
								<?php endif; ?>
								<?php if ( ! empty( $office['schedule'] ) ) : ?>
									<div class="contacts-map__detail">
										<span class="contacts-map__detail-icon">
											<?php $render_contact_icon( $office['schedule_icon'] ?? 0, 'schedule' ); ?>
										</span>
										<div><?php echo wp_kses_post( wpautop( $office['schedule'] ) ); ?></div>
									</div>
								<?php endif; ?>
								<?php if ( ! empty( $office['phone'] ) ) : ?>
									<a class="contacts-map__detail contacts-map__detail--phone" href="<?php echo esc_url( $office['phone_link'] ?: 'tel:' . preg_replace( '/[^0-9+]/', '', $office['phone'] ) ); ?>">
										<span class="contacts-map__detail-icon">
											<?php $render_contact_icon( $office['phone_icon'] ?? 0, 'phone' ); ?>
										</span>
										<strong><?php echo esc_html( $office['phone'] ); ?></strong>
									</a>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
