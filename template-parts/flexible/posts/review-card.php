<?php
/**
 * Single reusable review card layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section       = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$post_id       = absint( $args['post_id'] ?? get_the_ID() );
$source_label  = ukladka_trotuarnoy_plitki_get_review_source_label( $section['review_source'] ?? '', $section['review_source_custom'] ?? '' );
$is_fragment   = ! empty( $args['is_fragment'] );
$wrapper_tag   = $is_fragment ? 'article' : 'section';
$source_key     = sanitize_html_class( (string) ( $section['review_source'] ?? '' ) );
$source_icon    = '';
$source_label_lc = function_exists( 'mb_strtolower' ) ? mb_strtolower( $source_label ) : strtolower( $source_label );

if ( 'yandex' === $source_key || str_contains( $source_label_lc, 'яндекс' ) ) {
	$source_icon = content_url( '/uploads/2026/08/frame-1119.png' );
} elseif ( '2gis' === $source_key || str_contains( $source_label_lc, '2гис' ) || str_contains( $source_label_lc, '2gis' ) ) {
	$source_icon = content_url( '/uploads/2026/08/frame-1119-1.png' );
}
?>
<<?php echo tag_escape( $wrapper_tag ); ?> id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>">
	<?php if ( ! $is_fragment ) : ?><div class="container"><?php endif; ?>
	<div class="review-card__wrapper">
		<?php if ( ! empty( $section['before_image'] ) || ! empty( $section['after_image'] ) ) : ?>
			<div class="review-card__media" data-before-after style="--before-after-position: 50%;">
				<?php ukladka_trotuarnoy_plitki_render_image( ( $section['after_image'] ?? 0 ) ?: ( $section['before_image'] ?? 0 ), 'review-card__image review-card__image--after' ); ?>
				<?php ukladka_trotuarnoy_plitki_render_image( ( $section['before_image'] ?? 0 ) ?: ( $section['after_image'] ?? 0 ), 'review-card__image review-card__image--before' ); ?>
				<div class="review-card__divider" aria-hidden="true">
					<span class="review-card__handle">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
							<rect x="24" y="24" width="24" height="24" rx="12" transform="rotate(180 24 24)" fill="#F9F9FB"/>
							<path d="M14.2661 17.0541C14.3504 17.1426 14.4505 17.2128 14.5608 17.2607C14.671 17.3086 14.7892 17.3333 14.9085 17.3333C15.0279 17.3333 15.146 17.3086 15.2563 17.2607C15.3665 17.2128 15.4666 17.1426 15.5509 17.0541L19.7335 12.6727C19.818 12.5844 19.885 12.4795 19.9307 12.364C19.9765 12.2485 20 12.1247 20 11.9997C20 11.8747 19.9765 11.7509 19.9307 11.6354C19.885 11.52 19.818 11.4151 19.7335 11.3268L15.5509 6.94534C15.4666 6.85696 15.3664 6.78686 15.2562 6.73903C15.146 6.6912 15.0278 6.66659 14.9085 6.66659C14.7892 6.66659 14.6711 6.6912 14.5608 6.73903C14.4506 6.78686 14.3505 6.85696 14.2661 6.94534C14.1817 7.03371 14.1148 7.13863 14.0692 7.25409C14.0235 7.36956 14 7.49332 14 7.6183C14 7.74328 14.0235 7.86704 14.0692 7.9825C14.1148 8.09797 14.1817 8.20289 14.2661 8.29126L17.8017 12.0045L14.2661 15.7082C13.9198 16.0805 13.9198 16.6914 14.2661 17.0541Z" fill="#111111"/>
							<path d="M9.7339 17.0541C9.6496 17.1426 9.54946 17.2128 9.43923 17.2607C9.32899 17.3086 9.21082 17.3333 9.09148 17.3333C8.97214 17.3333 8.85396 17.3086 8.74373 17.2607C8.63349 17.2128 8.53336 17.1426 8.44906 17.0541L4.26649 12.6727C4.18201 12.5844 4.11499 12.4795 4.06926 12.364C4.02354 12.2485 4 12.1247 4 11.9997C4 11.8747 4.02354 11.7509 4.06926 11.6354C4.11499 11.52 4.18201 11.4151 4.26649 11.3268L8.44906 6.94534C8.53342 6.85696 8.63358 6.78686 8.7438 6.73903C8.85403 6.6912 8.97217 6.66659 9.09148 6.66659C9.21079 6.66659 9.32893 6.6912 9.43915 6.73903C9.54938 6.78686 9.64953 6.85696 9.7339 6.94534C9.81826 7.03371 9.88518 7.13863 9.93084 7.25409C9.9765 7.36956 10 7.49332 10 7.6183C10 7.74328 9.9765 7.86704 9.93084 7.9825C9.88518 8.09797 9.81826 8.20289 9.7339 8.29126L6.1983 12.0045L9.7339 15.7082C10.0802 16.0805 10.0802 16.6914 9.7339 17.0541Z" fill="#111111"/>
						</svg>
					</span>
				</div>
				<input
					class="review-card__range"
					type="range"
					min="0"
					max="100"
					value="50"
					data-before-after-range
					aria-label="<?php esc_attr_e( 'Сравнить фотографии до и после', 'ukladka-trotuarnoy-plitki' ); ?>"
				>
			</div>
		<?php endif; ?>
		<div class="review-card__body">
			<h2 class="review-card__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( get_the_title( $post_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<?php if ( ! empty( $section['client_name'] ) || ! empty( $section['city_address'] ) ) : ?>
				<div class="review-card__meta">
					<?php if ( ! empty( $section['client_name'] ) ) : ?>
						<span class="review-card__client">
							<span class="review-card__meta-icon" aria-hidden="true">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
									<path d="M4.00023 23C4.00023 23 3.99972 20.5556 4.00023 18.9259C4.001 16.4407 6.62233 14.0358 9.33348 14.037C12.0001 14.0382 12.0001 14.037 14.6667 14.037C17.6122 14.037 20 16.2259 20 18.9259C20 20.5556 20 23 20 23M12.0001 10.7778C14.9455 10.7777 17.3331 8.58889 17.3331 5.88889C17.3331 3.18889 14.9455 1.00011 12.0001 1C9.05457 0.999886 6.66663 3.18877 6.66663 5.88889C6.66663 8.589 9.05457 10.7779 12.0001 10.7778Z" stroke="#C5A880" stroke-width="2"/>
								</svg>
							</span>
							<span><?php echo esc_html( $section['client_name'] ); ?></span>
						</span>
					<?php endif; ?>
					<?php if ( ! empty( $section['city_address'] ) ) : ?>
						<span class="review-card__address">
							<span class="review-card__meta-icon" aria-hidden="true">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M11.999 5.37988C11.5087 5.37988 11.0232 5.47901 10.5703 5.6716C10.1173 5.8642 9.70577 6.14649 9.35909 6.50236C9.01242 6.85823 8.73743 7.2807 8.54981 7.74567C8.36219 8.21063 8.26563 8.70898 8.26562 9.21225C8.26563 9.71553 8.36219 10.2139 8.54981 10.6788C8.73743 11.1438 9.01242 11.5663 9.35909 11.9222C9.70577 12.278 10.1173 12.5603 10.5703 12.7529C11.0232 12.9455 11.5087 13.0446 11.999 13.0446C12.9891 13.0446 13.9387 12.6409 14.6388 11.9222C15.339 11.2034 15.7323 10.2287 15.7323 9.21225C15.7323 8.19585 15.339 7.22107 14.6388 6.50236C13.9387 5.78365 12.9891 5.37988 11.999 5.37988ZM10.399 9.21225C10.399 8.77665 10.5675 8.35889 10.8676 8.05087C11.1676 7.74285 11.5746 7.56981 11.999 7.56981C12.4233 7.56981 12.8303 7.74285 13.1303 8.05087C13.4304 8.35889 13.599 8.77665 13.599 9.21225C13.599 9.64786 13.4304 10.0656 13.1303 10.3736C12.8303 10.6817 12.4233 10.8547 11.999 10.8547C11.5746 10.8547 11.1676 10.6817 10.8676 10.3736C10.5675 10.0656 10.399 9.64786 10.399 9.21225Z" fill="#C5A880"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M12 1C7.54987 1 4 4.81376 4 9.44436C4 11.8412 5.29493 14.2501 6.736 16.3273C7.72053 17.7464 8.88 19.1501 9.89013 20.3721C10.3595 20.9415 10.7968 21.4703 11.1701 21.9445L12 23L12.8299 21.9445C13.2032 21.4703 13.6405 20.9415 14.1099 20.3721C15.12 19.1501 16.2795 17.7464 17.264 16.3273C18.7051 14.2491 20 11.8401 20 9.44436C20 4.81376 16.4501 1 12 1ZM6.13333 9.44436C6.13333 5.9569 8.79147 3.18993 12 3.18993C15.2085 3.18993 17.8667 5.9569 17.8667 9.44436C17.8667 11.1076 16.9333 13.0293 15.5264 15.056C14.5909 16.4039 13.5477 17.6664 12.5696 18.8501C12.3762 19.0822 12.1863 19.3122 12 19.5399L11.4304 18.8501C10.4523 17.6675 9.40907 16.4039 8.4736 15.056C7.06773 13.0304 6.13333 11.1076 6.13333 9.44436Z" fill="#C5A880"/>
								</svg>
							</span>
							<span><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['city_address'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $section['work_text'] ) ) : ?>
				<div class="review-card__work">
					<span class="review-card__work-icon" aria-hidden="true">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
							<g clip-path="url(#clip0_review_card_tool)">
								<path d="M18.0631 9.5619H14.4381V5.93694L18.6672 1.70782C17.3143 1.0617 15.7944 0.850891 14.3168 1.10442C12.8391 1.35794 11.4764 2.06333 10.4163 3.12346C9.35618 4.18358 8.65078 5.54629 8.39726 7.02395C8.14373 8.5016 8.35455 10.0215 9.00067 11.3744L1.75075 18.6243C1.27005 19.105 1 19.757 1 20.4368C1 21.1166 1.27005 21.7685 1.75075 22.2492C2.23145 22.7299 2.88342 23 3.56323 23C4.24304 23 4.89501 22.7299 5.37571 22.2492L12.6256 14.9993C13.9785 15.6455 15.4984 15.8563 16.9761 15.6027C18.4537 15.3492 19.8164 14.6438 20.8765 13.5837C21.9367 12.5236 22.6421 11.1609 22.8956 9.68321C23.1491 8.20556 22.9383 6.68565 22.2922 5.33278L18.0631 9.5619Z" stroke="#C5A880" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</g>
							<defs>
								<clipPath id="clip0_review_card_tool">
									<rect width="24" height="24" fill="white"/>
								</clipPath>
							</defs>
						</svg>
					</span>
					<span><?php echo wp_kses_post( $section['work_text'] ); ?></span>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $section['review_text'] ) ) : ?><div class="review-card__text"><?php echo wp_kses_post( $section['review_text'] ); ?></div><?php endif; ?>
			<?php if ( ! empty( $section['rating'] ) || $source_label ) : ?>
				<div class="review-card__footer">
					<?php if ( ! empty( $section['rating'] ) ) : ?>
						<span class="review-card__rating">
							<span class="review-card__stars" aria-hidden="true">
								<?php for ( $star_index = 0; $star_index < 5; $star_index++ ) : ?>
									<svg width="14" height="14" viewBox="0 0 14 14" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
										<g clip-path="url(#clip0_review_card_star_<?php echo esc_attr( $star_index ); ?>)">
											<path d="M6.99965 11.7285L3.60648 13.8687C3.45658 13.9686 3.29986 14.0114 3.13634 13.9971C2.97281 13.9829 2.82972 13.9258 2.70708 13.8259C2.58443 13.726 2.48904 13.6013 2.42091 13.4518C2.35277 13.3023 2.33915 13.1345 2.38003 12.9484L3.27942 8.90338L0.274623 6.18528C0.138351 6.05686 0.0533171 5.91047 0.0195216 5.7461C-0.0142739 5.58173 -0.00418977 5.42136 0.049774 5.26498C0.103738 5.1086 0.185501 4.98018 0.295064 4.87974C0.404627 4.77929 0.554526 4.71508 0.744762 4.68711L4.71028 4.32327L6.24334 0.513656C6.31148 0.342438 6.41722 0.214023 6.56058 0.128414C6.70394 0.0428046 6.8503 0 6.99965 0C7.14901 0 7.29536 0.0428046 7.43872 0.128414C7.58208 0.214023 7.68783 0.342438 7.75596 0.513656L9.28902 4.32327L13.2545 4.68711C13.4453 4.71565 13.5952 4.77986 13.7042 4.87974C13.8133 4.97961 13.895 5.10803 13.9495 5.26498C14.004 5.42193 14.0144 5.58259 13.9806 5.74696C13.9468 5.91133 13.8615 6.05744 13.7247 6.18528L10.7199 8.90338L11.6193 12.9484C11.6602 13.1339 11.6465 13.3017 11.5784 13.4518C11.5103 13.6019 11.4149 13.7266 11.2922 13.8259C11.1696 13.9252 11.0265 13.9823 10.863 13.9971C10.6994 14.012 10.5427 13.9692 10.3928 13.8687L6.99965 11.7285Z" fill="#FFCC00"/>
										</g>
										<defs>
											<clipPath id="clip0_review_card_star_<?php echo esc_attr( $star_index ); ?>">
												<rect width="14" height="14" fill="white"/>
											</clipPath>
										</defs>
									</svg>
								<?php endfor; ?>
							</span>
							<span><?php echo esc_html( number_format_i18n( (float) $section['rating'], 1 ) ); ?></span>
						</span>
					<?php endif; ?>
					<?php if ( $source_label ) : ?>
						<span class="review-card__source-wrap">
							<span class="review-card__source-divider" aria-hidden="true"></span>
							<?php if ( ! empty( $section['review_source_url'] ) ) : ?>
								<a class="review-card__source review-card__source--<?php echo esc_attr( $source_key ); ?>" href="<?php echo esc_url( $section['review_source_url'] ); ?>" target="_blank" rel="noopener noreferrer">
									<?php if ( $source_icon ) : ?>
										<img class="review-card__source-icon" src="<?php echo esc_url( $source_icon ); ?>" alt="" loading="lazy" decoding="async">
									<?php endif; ?>
									<span><?php echo esc_html( $source_label ); ?></span>
								</a>
							<?php else : ?>
								<span class="review-card__source review-card__source--<?php echo esc_attr( $source_key ); ?>">
									<?php if ( $source_icon ) : ?>
										<img class="review-card__source-icon" src="<?php echo esc_url( $source_icon ); ?>" alt="" loading="lazy" decoding="async">
									<?php endif; ?>
									<span><?php echo esc_html( $source_label ); ?></span>
								</span>
							<?php endif; ?>
						</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( ! $is_fragment ) : ?></div><?php endif; ?>
</<?php echo tag_escape( $wrapper_tag ); ?>>
