<?php /** Shared inline/modal quiz fields; scope supplied by hero-quiz.php. */ ?>
<form class="hero-quiz__form" method="post" data-site-form data-form-id="<?php echo esc_attr( $form['form_id'] ?? (string) $form_id ); ?>">
			<label class="screen-reader-text" aria-hidden="true">Сайт<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
			<div class="hero-quiz__steps">
				<?php foreach ( $titles as $step => $title ) : ?>
					<div class="hero-quiz__step<?php echo 0 === $step ? ' is-active' : ''; ?>" data-hero-quiz-step="<?php echo esc_attr( $step + 1 ); ?>"<?php echo $step ? ' inert aria-hidden="true"' : ''; ?>>
						<div class="hero-quiz__head<?php echo 4 === $step ? ' hero-quiz__head--last' : ''; ?>">
							<?php if ( 4 === $step ) : ?>
								<?php $render_control( 'back', true ); ?>
							<?php else : ?>
								<h2 class="hero-quiz__question" id="<?php echo esc_attr( $section_id . '-question-' . $step ); ?>" tabindex="-1">
									<span<?php echo 0 === $step && ! empty( $section['zone_title_mobile'] ) ? ' class="hero-quiz__desktop-copy"' : ''; ?>><?php echo esc_html( $title ); ?></span>
									<?php if ( 0 === $step && ! empty( $section['zone_title_mobile'] ) ) : ?><span class="hero-quiz__mobile-copy"><?php echo esc_html( $section['zone_title_mobile'] ); ?></span><?php endif; ?>
								</h2>
							<?php endif; ?>
							<div class="hero-quiz__progress">
								<span class="hero-quiz__progress-label"><?php echo esc_html( 4 === $step ? ( $section['last_step_label'] ?? '' ) : strtr( $section['progress_label'] ?? '', array( '{current}' => (string) ( $step + 1 ), '{total}' => '5' ) ) ); ?></span>
								<progress class="hero-quiz__progress-bar" max="5" value="<?php echo esc_attr( $step + 1 ); ?>" aria-label="<?php echo esc_attr( ! empty( $section['progress_label'] ) ? strtr( $section['progress_label'], array( '{current}' => (string) ( $step + 1 ), '{total}' => '5' ) ) : $title ); ?>"></progress>
							</div>
						</div>
						<?php if ( 0 === $step ) : ?>
							<div class="hero-quiz__zones" role="group" aria-labelledby="<?php echo esc_attr( $section_id . '-question-0' ); ?>">
								<?php foreach ( (array) ( $section['zone_options'] ?? array() ) as $option ) : ?>
									<label class="hero-quiz__zone">
										<?php
										if ( $is_modal ) {
											// Hidden modal images must not wait for lazy-paint/auto-size intersection updates.
											echo wp_get_attachment_image( ukladka_trotuarnoy_plitki_get_image_id( $option['image'] ?? 0 ), 'medium', false, array( 'class' => 'hero-quiz__zone-image', 'loading' => 'eager', 'sizes' => '(max-width: 767px) 163px, 180px' ) );
										} else {
											ukladka_trotuarnoy_plitki_render_image( $option['image'] ?? 0, 'hero-quiz__zone-image', 'medium' );
										}
										?>
										<span class="hero-quiz__zone-caption"><input class="hero-quiz__checkbox" type="checkbox" name="zones[]" value="<?php echo esc_attr( $option['label'] ?? '' ); ?>" <?php checked( ! empty( $option['selected'] ) ); ?>><span><?php echo esc_html( $option['label'] ?? '' ); ?></span></span>
									</label>
								<?php endforeach; ?>
							</div>
						<?php elseif ( 1 === $step ) : ?>
							<div class="hero-quiz__area-group">
							<label class="hero-quiz__area"><input class="hero-quiz__number" type="number" name="area" min="0" step="any" inputmode="decimal" value="<?php echo esc_attr( $section['area_value'] ?? 62 ); ?>" aria-label="<?php echo esc_attr( $title ); ?>"><span><?php echo esc_html( $section['area_unit'] ?? '' ); ?></span></label>
							<div class="hero-quiz__options" role="radiogroup" aria-labelledby="<?php echo esc_attr( $section_id . '-question-1' ); ?>">
								<?php foreach ( (array) ( $section['area_options'] ?? array() ) as $option ) : ?>
									<label class="hero-quiz__option"><input type="radio" name="area_preset" value="<?php echo esc_attr( $option['label'] ?? '' ); ?>" data-min="<?php echo esc_attr( $option['min'] ?? '' ); ?>" data-max="<?php echo esc_attr( $option['max'] ?? '' ); ?>" data-value="<?php echo esc_attr( $option['value'] ?? '' ); ?>" data-unknown="<?php echo ! empty( $option['unknown'] ) ? '1' : '0'; ?>"><span><?php echo esc_html( $option['label'] ?? '' ); ?></span></label>
								<?php endforeach; ?>
							</div>
							</div>
						<?php elseif ( $step < 4 ) : ?>
							<?php $name = 2 === $step ? 'foundation' : 'load'; ?>
							<div class="hero-quiz__options hero-quiz__options--<?php echo esc_attr( $name ); ?>" role="radiogroup" aria-labelledby="<?php echo esc_attr( $section_id . '-question-' . $step ); ?>">
								<?php $options = array_values( (array) ( $section[ $name . '_options' ] ?? array() ) ); ?>
								<?php foreach ( $options as $option_index => $option ) : ?>
									<?php if ( 'load' === $name && 0 === $option_index % 2 ) : ?><div class="hero-quiz__option-row"><?php endif; ?>
									<label class="hero-quiz__option" data-option-index="<?php echo esc_attr( $option_index + 1 ); ?>"><input type="radio" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $option['label'] ?? '' ); ?>" <?php checked( ! empty( $option['selected'] ) ); ?>><?php ukladka_trotuarnoy_plitki_render_image( $option['icon'] ?? 0, 'hero-quiz__option-icon', 'full' ); ?><span><?php echo esc_html( $option['label'] ?? '' ); ?></span></label>
									<?php if ( 'load' === $name && ( 1 === $option_index % 2 || count( $options ) - 1 === $option_index ) ) : ?></div><?php endif; ?>
								<?php endforeach; ?>
							</div>
						<?php else : ?>
							<div class="hero-quiz__contact">
								<h2 class="hero-quiz__question" id="<?php echo esc_attr( $section_id . '-question-4' ); ?>" tabindex="-1"><?php echo esc_html( $title ); ?></h2>
								<div class="hero-quiz__methods" role="radiogroup" aria-labelledby="<?php echo esc_attr( $section_id . '-question-4' ); ?>">
									<?php foreach ( (array) ( $form['contact_methods'] ?? array() ) as $method ) : ?>
										<label class="hero-quiz__option hero-quiz__option--method"><input type="radio" name="contact_method" value="<?php echo esc_attr( $method['value'] ?? '' ); ?>" <?php checked( ! empty( $method['selected'] ) ); ?>><?php ukladka_trotuarnoy_plitki_render_image( $method['icon'] ?? 0, 'hero-quiz__option-icon hero-quiz__method-icon--' . sanitize_html_class( $method['value'] ?? '' ), 'full' ); ?><span><?php echo esc_html( $method['label'] ?? '' ); ?></span></label>
									<?php endforeach; ?>
								</div>
							</div>
							<label class="hero-quiz__phone-label"><span class="hero-quiz__question"><?php echo esc_html( $form['phone_step_title'] ?? '' ); ?></span><span class="hero-quiz__phone-shell"><input class="hero-quiz__phone" type="tel" name="phone" autocomplete="tel" inputmode="tel" pattern="\+7 \([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}" placeholder="<?php echo esc_attr( $phone_placeholder ); ?>" required disabled><span class="hero-quiz__phone-placeholder" aria-hidden="true"><span><?php echo esc_html( $phone_placeholder_parts[1] ?? '' ); ?></span><?php echo esc_html( $phone_placeholder_parts[2] ?? $phone_placeholder ); ?></span></span></label>
							<div class="hero-quiz__send">
								<?php $render_control( 'submit' ); ?>
								<?php if ( ! empty( $form['privacy_text'] ) ) : ?><label class="hero-quiz__privacy"><input class="hero-quiz__checkbox" type="checkbox" name="privacy" value="1" required disabled><span><?php echo wp_kses_post( $form['privacy_text'] ); ?></span></label><?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( $step < 4 || $is_modal ) : ?><div class="hero-quiz__navigation"><?php if ( $step ) { $render_control( 'back' ); } ?><?php if ( $step < 4 ) { $render_control( 'next' ); } ?></div><?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="form-status" role="status" aria-live="polite"></div>
		</form>
