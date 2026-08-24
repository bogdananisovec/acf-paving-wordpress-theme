<?php
/**
 * Materials selection layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section         = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$consultation    = is_array( $section['consultation'] ?? null ) ? $section['consultation'] : array();
$items           = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$bottom_block    = is_array( $section['bottom_block'] ?? null ) ? $section['bottom_block'] : array();
$bottom_items    = is_array( $bottom_block['items'] ?? null ) ? $bottom_block['items'] : array();
$bottom_benefits = is_array( $section['bottom_benefits'] ?? null ) ? $section['bottom_benefits'] : array();

$render_card_icon = static function ( $type ) {
	$icons = array(
		'suitable'   => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M11.9984 12.2081C13.1123 12.2081 14.1806 11.7472 14.9683 10.9267C15.7559 10.1063 16.1984 8.99351 16.1984 7.83321C16.1984 6.67292 15.7559 5.56014 14.9683 4.73969C14.1806 3.91923 13.1123 3.4583 11.9984 3.4583C10.8845 3.4583 9.81624 3.91923 9.02859 4.73969C8.24094 5.56014 7.79844 6.67292 7.79844 7.83321C7.79844 8.99351 8.24094 10.1063 9.02859 10.9267C9.81624 11.7472 10.8845 12.2081 11.9984 12.2081ZM11.9984 13.6664C10.5132 13.6664 9.08884 13.0519 8.03864 11.9579C6.98844 10.864 6.39844 9.38028 6.39844 7.83321C6.39844 6.28615 6.98844 4.80245 8.03864 3.70851C9.08884 2.61457 10.5132 2 11.9984 2C13.4836 2 14.908 2.61457 15.9582 3.70851C17.0084 4.80245 17.5984 6.28615 17.5984 7.83321C17.5984 9.38028 17.0084 10.864 15.9582 11.9579C14.908 13.0519 13.4836 13.6664 11.9984 13.6664Z" fill="#C5A880"/>
  <path d="M11.9992 12.834C12.2114 12.834 12.4149 12.9042 12.5649 13.0292C12.7149 13.1543 12.7992 13.3238 12.7992 13.5006V18.8339C12.7992 19.0107 12.7149 19.1802 12.5649 19.3053C12.4149 19.4303 12.2114 19.5005 11.9992 19.5005C11.787 19.5005 11.5836 19.4303 11.4335 19.3053C11.2835 19.1802 11.1992 19.0107 11.1992 18.8339V13.5006C11.1992 13.3238 11.2835 13.1543 11.4335 13.0292C11.5836 12.9042 11.787 12.834 11.9992 12.834Z" fill="#C5A880"/>
  <path d="M9.33333 15.333V16.7263C6.95417 17.1208 5.33333 17.9675 5.33333 18.5698C5.33333 19.3779 8.24792 20.6276 12 20.6276C15.7521 20.6276 18.6667 19.3779 18.6667 18.5698C18.6667 17.9653 17.0458 17.1208 14.6667 16.7263V15.333C17.7729 15.8046 20 17.0779 20 18.5698C20 20.4626 16.4188 21.9995 12 21.9995C7.58125 21.9995 4 20.4626 4 18.5698C4 17.0757 6.22708 15.8046 9.33333 15.333Z" fill="#C5A880"/>
</svg>',
		'advantages' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M3 11.998H21" stroke="#C5A880" stroke-width="2" stroke-linecap="round"/>
  <path d="M12.0039 3L12.0039 21" stroke="#C5A880" stroke-width="2" stroke-linecap="round"/>
</svg>',
		'note'       => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M22 11.0799V11.9999C21.9988 14.1563 21.3005 16.2545 20.0093 17.9817C18.7182 19.7088 16.9033 20.9723 14.8354 21.5838C12.7674 22.1952 10.5573 22.1218 8.53447 21.3744C6.51168 20.6271 4.78465 19.246 3.61096 17.4369C2.43727 15.6279 1.87979 13.4879 2.02168 11.3362C2.16356 9.18443 2.99721 7.13619 4.39828 5.49694C5.79935 3.85768 7.69279 2.71525 9.79619 2.24001C11.8996 1.76477 14.1003 1.9822 16.07 2.85986M22 3.99986L12 14.0099L9.00001 11.0099" stroke="#C5A880" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>',
	);

	echo $icons[ $type ] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
};
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'materials' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( 'materials' ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="materials__wrapper">
			<div class="materials__main">
				<div class="materials__intro">
					<header class="materials__header">
						<?php if ( ! empty( $section['title'] ) ) : ?><h2 class="materials__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2><?php endif; ?>
						<?php if ( ! empty( $section['text'] ) ) : ?><div class="materials__text"><?php echo wp_kses_post( $section['text'] ); ?></div><?php endif; ?>
					</header>

					<?php if ( array_filter( $consultation ) ) : ?>
						<aside class="materials__consultation">
							<div class="materials__consultation-content">
								<?php if ( ! empty( $consultation['title'] ) ) : ?><h3 class="materials__consultation-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $consultation['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><?php endif; ?>
								<?php if ( ! empty( $consultation['text'] ) ) : ?><div class="materials__consultation-text"><?php echo wp_kses_post( wpautop( $consultation['text'] ) ); ?></div><?php endif; ?>
							</div>
							<div class="materials__consultation-action">
								<?php ukladka_trotuarnoy_plitki_render_button( $consultation, 'materials__consultation-button' ); ?>
								<?php if ( ! empty( $consultation['note_text'] ) ) : ?>
									<div class="materials__consultation-note">
										<?php if ( ! empty( $consultation['note_icon'] ) ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $consultation['note_icon'], 'materials__consultation-note-icon', 'thumbnail' ); ?><?php endif; ?>
										<span><?php echo esc_html( $consultation['note_text'] ); ?></span>
									</div>
								<?php endif; ?>
							</div>
						</aside>
					<?php endif; ?>
				</div>

				<?php if ( $items ) : ?>
					<div class="materials__cards" data-mobile-slider>
						<?php foreach ( $items as $item ) : ?>
							<article class="materials__card">
								<?php if ( ! empty( $item['image'] ) ) : ?><figure class="materials__card-media"><?php ukladka_trotuarnoy_plitki_render_image( $item['image'], 'materials__card-image', 'large' ); ?></figure><?php endif; ?>
								<div class="materials__card-body">
									<?php if ( ! empty( $item['number'] ) ) : ?><span class="materials__card-number"><?php echo esc_html( $item['number'] ); ?></span><?php endif; ?>
									<?php if ( ! empty( $item['title'] ) ) : ?><h3 class="materials__card-title"><?php echo esc_html( $item['title'] ); ?></h3><?php endif; ?>
									<?php if ( ! empty( $item['price'] ) || ! empty( $item['price_label'] ) ) : ?>
										<div class="materials__card-price">
											<?php if ( ! empty( $item['price_label'] ) ) : ?><span class="materials__card-price-label"><?php echo esc_html( $item['price_label'] ); ?></span><?php endif; ?>
											<?php
											$price_value_classes = array( 'materials__card-price-value' );
											if ( ! empty( $item['price'] ) && ! preg_match( '/[0-9₽]/u', (string) $item['price'] ) ) {
												$price_value_classes[] = 'materials__card-price-value--text';
											}
											?>
											<?php if ( ! empty( $item['price'] ) ) : ?><span class="<?php echo esc_attr( implode( ' ', $price_value_classes ) ); ?>"><?php echo esc_html( $item['price'] ); ?></span><?php endif; ?>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $item['text'] ) ) : ?><div class="materials__card-text"><?php echo wp_kses_post( wpautop( $item['text'] ) ); ?></div><?php endif; ?>
									<?php
									$card_sections = array(
										array(
											'type'  => 'suitable',
											'title' => $item['suitable_title'] ?? '',
											'text'  => $item['suitable_text'] ?? '',
										),
										array(
											'type'  => 'advantages',
											'title' => $item['advantages_title'] ?? '',
											'text'  => $item['advantages_text'] ?? '',
										),
										array(
											'type'  => 'note',
											'title' => $item['note_title'] ?? '',
											'text'  => $item['note_text'] ?? '',
										),
									);
									?>
									<?php foreach ( $card_sections as $card_section ) : ?>
										<?php if ( ! empty( $card_section['title'] ) || ! empty( $card_section['text'] ) ) : ?>
											<div class="materials__card-section materials__card-section--<?php echo esc_attr( $card_section['type'] ); ?>">
												<?php if ( ! empty( $card_section['title'] ) ) : ?>
													<h4 class="materials__card-section-title">
														<span class="materials__card-section-icon"><?php $render_card_icon( $card_section['type'] ); ?></span>
														<span><?php echo esc_html( $card_section['title'] ); ?></span>
													</h4>
												<?php endif; ?>
												<?php if ( ! empty( $card_section['text'] ) ) : ?><div class="materials__card-section-text"><?php echo wp_kses_post( wpautop( $card_section['text'] ) ); ?></div><?php endif; ?>
											</div>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $section['button_id'] ) || ! empty( $section['button_text'] ) || ! empty( $section['button_link'] ) ) : ?>
				<div class="materials__action">
					<?php ukladka_trotuarnoy_plitki_render_button( $section, 'materials__button' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $bottom_block['image'] ) || $bottom_items || $bottom_benefits ) : ?>
				<div class="materials__footer">
					<?php if ( ! empty( $bottom_block['image'] ) || $bottom_items ) : ?>
						<div class="materials__bottom">
							<?php if ( ! empty( $bottom_block['image'] ) ) : ?><figure class="materials__bottom-media"><?php ukladka_trotuarnoy_plitki_render_image( $bottom_block['image'], 'materials__bottom-image', 'large' ); ?></figure><?php endif; ?>
							<div class="materials__bottom-items">
								<?php foreach ( $bottom_items as $item ) : ?>
									<article class="materials__bottom-item">
										<?php if ( ! empty( $item['icon'] ) ) : ?><span class="materials__bottom-icon"><?php ukladka_trotuarnoy_plitki_render_image( $item['icon'], 'materials__bottom-icon-image', 'thumbnail' ); ?></span><?php endif; ?>
										<div class="materials__bottom-copy">
											<?php if ( ! empty( $item['title'] ) ) : ?><h3 class="materials__bottom-title"><?php echo esc_html( $item['title'] ); ?></h3><?php endif; ?>
											<?php if ( ! empty( $item['text'] ) ) : ?><div class="materials__bottom-text"><?php echo wp_kses_post( wpautop( $item['text'] ) ); ?></div><?php endif; ?>
											<?php ukladka_trotuarnoy_plitki_render_button( $item, 'materials__bottom-button' ); ?>
										</div>
									</article>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $bottom_benefits ) : ?>
						<div class="materials__benefits" data-mobile-slider>
							<?php foreach ( $bottom_benefits as $benefit ) : ?>
								<div class="materials__benefit">
									<?php if ( ! empty( $benefit['icon'] ) ) : ?><span class="materials__benefit-icon"><?php ukladka_trotuarnoy_plitki_render_image( $benefit['icon'], 'materials__benefit-icon-image', 'thumbnail' ); ?></span><?php endif; ?>
									<span><?php echo esc_html( $benefit['text'] ?? '' ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
