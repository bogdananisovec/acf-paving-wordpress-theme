<?php
/**
 * Universal multi-step calculator driven by the existing calculator ACF group.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$calculator_id = absint( $args['calculator_id'] ?? 0 );

if ( ! $calculator_id || ! function_exists( 'get_field' ) ) {
	return;
}

$steps         = get_field( 'calculator_steps', $calculator_id );
$base_price    = (float) get_field( 'base_price', $calculator_id );
$minimum_price = (float) get_field( 'minimum_price', $calculator_id );
$maximum_price = (float) get_field( 'maximum_price', $calculator_id );
$rounding      = get_field( 'price_rounding', $calculator_id ) ?: 'none';
$price_prefix  = get_field( 'price_prefix', $calculator_id ) ?: 'от';
$price_suffix  = get_field( 'price_suffix', $calculator_id ) ?: '₽';
$show_progress = (bool) get_field( 'show_progress', $calculator_id );
$show_result   = (bool) get_field( 'show_result_panel', $calculator_id );
$consent_text  = get_field( 'consent_text', $calculator_id );
$consent_icon  = ukladka_trotuarnoy_plitki_get_image_id( get_field( 'consent_icon', $calculator_id ) );
$total_steps   = is_array( $steps ) ? count( $steps ) : 0;
$use_last_step_badge = (bool) get_field( 'use_last_step_badge', $calculator_id );
$last_step_badge     = get_field( 'last_step_label', $calculator_id );
$result_button = ukladka_trotuarnoy_plitki_resolve_button( array( 'global_button_id' => get_field( 'global_button_id', $calculator_id ) ) );
$result_button_text_override = get_field( 'result_button_text_override', $calculator_id );
if ( $result_button_text_override ) {
	$result_button['text'] = $result_button_text_override;
}
$result_icon   = ukladka_trotuarnoy_plitki_get_image_id( $result_button['icon'] ?? 0 );
$result_panel_icon = ukladka_trotuarnoy_plitki_get_image_id( get_field( 'result_icon', $calculator_id ) );

if ( ! is_array( $steps ) || ! $steps ) {
	return;
}
?>
<form
	class="calculator-form calculator-form--<?php echo esc_attr( sanitize_html_class( get_field( 'calculator_theme', $calculator_id ) ?: 'light' ) ); ?>"
	data-site-form
	data-form-id="<?php echo esc_attr( 'calculator-' . $calculator_id ); ?>"
	data-calculator-id="<?php echo esc_attr( $calculator_id ); ?>"
	data-base-price="<?php echo esc_attr( $base_price ); ?>"
	data-min-price="<?php echo esc_attr( $minimum_price ); ?>"
	data-max-price="<?php echo esc_attr( $maximum_price ); ?>"
	data-rounding="<?php echo esc_attr( $rounding ); ?>"
	data-price-prefix="<?php echo esc_attr( $price_prefix ); ?>"
	data-price-suffix="<?php echo esc_attr( $price_suffix ); ?>"
>
	<div class="calculator-form__main">
		<?php if ( $show_progress ) : ?>
			<div class="calculator-form__progressbar" aria-hidden="true"><span></span></div>
		<?php endif; ?>

		<?php foreach ( $steps as $index => $step ) : ?>
			<?php
			$type      = sanitize_key( $step['step_type'] ?? 'text' );
			$field_id  = sanitize_title( $step['step_id'] ?? 'step-' . ( $index + 1 ) );
			$field_name = 'calculator_' . $field_id;
			$contact_phone_label = $step['contact_phone_label'] ?? get_post_meta( $calculator_id, 'calculator_steps_' . $index . '_contact_phone_label', true );
			?>
			<fieldset
				class="calculator-form__step"
				data-calculator-step="<?php echo esc_attr( $index + 1 ); ?>"
				data-step-type="<?php echo esc_attr( $type ); ?>"
				data-result-label="<?php echo esc_attr( $step['result_label'] ?? $step['title'] ?? '' ); ?>"
				<?php echo ! empty( $step['show_in_result'] ) ? 'data-show-in-result="true"' : ''; ?>
				<?php echo ! empty( $step['required'] ) ? 'data-required="true"' : ''; ?>
			>
				<span class="calculator-form__step-count"><?php echo esc_html( $use_last_step_badge && $index + 1 === $total_steps && $last_step_badge ? $last_step_badge : sprintf( 'Шаг %1$d из %2$d', $index + 1, $total_steps ) ); ?></span>
				<?php if ( ! empty( $step['title'] ) ) : ?>
					<h3 class="calculator-form__title"><?php echo esc_html( $step['title'] ); ?></h3>
				<?php endif; ?>
				<?php if ( ! empty( $step['description'] ) ) : ?>
					<p class="calculator-form__description"><?php echo esc_html( $step['description'] ); ?></p>
				<?php endif; ?>

				<?php if ( 'number' === $type ) : ?>
					<label class="calculator-form__number">
						<input
							type="number"
							name="<?php echo esc_attr( $field_name ); ?>"
							value="<?php echo esc_attr( $step['number_placeholder'] ?? '' ); ?>"
							placeholder="<?php echo esc_attr( $step['number_placeholder'] ?? '' ); ?>"
							min="<?php echo esc_attr( $step['number_min'] ?? 0 ); ?>"
							max="<?php echo esc_attr( $step['number_max'] ?? '' ); ?>"
							data-price-per-unit="<?php echo esc_attr( $step['number_price_per_unit'] ?? 0 ); ?>"
							<?php echo ! empty( $step['required'] ) ? 'required' : ''; ?>
						>
						<?php if ( ! empty( $step['number_unit'] ) ) : ?><span><?php echo esc_html( $step['number_unit'] ); ?></span><?php endif; ?>
					</label>
					<?php if ( ! empty( $step['number_ranges'] ) ) : ?>
						<div class="calculator-form__ranges">
							<?php foreach ( (array) $step['number_ranges'] as $range_index => $range ) : ?>
								<label class="calculator-form__range">
									<input
										type="radio"
										name="<?php echo esc_attr( $field_name . '_range' ); ?>"
										value="<?php echo esc_attr( $range['value'] ?? $range_index ); ?>"
										data-number-value="<?php echo esc_attr( $range['value'] ?? '' ); ?>"
										data-price-type="<?php echo esc_attr( $range['price_change_type'] ?? 'none' ); ?>"
										data-price-value="<?php echo esc_attr( $range['price_change_value'] ?? 0 ); ?>"
										<?php checked( ! empty( $range['selected_by_default'] ) || ( 'ploshchad-uchastka' === $field_id && 1 === $range_index ) ); ?>
									>
									<span><?php echo esc_html( $range['label'] ?? '' ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				<?php elseif ( in_array( $type, array( 'single_choice', 'multiple_choice' ), true ) ) : ?>
					<div class="calculator-form__options">
						<?php foreach ( (array) ( $step['options'] ?? array() ) as $option_index => $option ) : ?>
							<label class="calculator-form__option calculator-form__option--<?php echo esc_attr( 'multiple_choice' === $type ? 'multiple' : 'single' ); ?>">
								<input
									type="<?php echo esc_attr( 'single_choice' === $type ? 'radio' : 'checkbox' ); ?>"
									name="<?php echo esc_attr( $field_name . ( 'multiple_choice' === $type ? '[]' : '' ) ); ?>"
									value="<?php echo esc_attr( $option['value'] ?? $option_index ); ?>"
									data-result-label="<?php echo esc_attr( $option['result_label'] ?? '' ); ?>"
									data-price-type="<?php echo esc_attr( $option['price_change_type'] ?? 'none' ); ?>"
									data-price-value="<?php echo esc_attr( $option['price_change_value'] ?? 0 ); ?>"
									<?php checked( ! empty( $option['selected_by_default'] ) ); ?>
								>
								<span class="calculator-form__option-card">
									<?php ukladka_trotuarnoy_plitki_render_image( $option['icon'] ?? 0, 'calculator-form__option-icon', 'thumbnail' ); ?>
									<strong><?php echo esc_html( $option['label'] ?? '' ); ?></strong>
									<?php if ( 'multiple_choice' === $type ) : ?>
										<span class="calculator-form__option-check" aria-hidden="true"></span>
									<?php endif; ?>
								</span>
							</label>
						<?php endforeach; ?>
					</div>
				<?php elseif ( 'contacts' === $type ) : ?>
					<div class="calculator-form__contact-methods">
						<?php foreach ( (array) ( $step['contact_methods'] ?? array() ) as $method_index => $method ) : ?>
							<label class="calculator-form__contact-method">
								<input type="radio" name="<?php echo esc_attr( $field_name . '_method' ); ?>" value="<?php echo esc_attr( $method['value'] ?? $method_index ); ?>" <?php checked( ! empty( $method['selected_by_default'] ) ); ?>>
								<span>
									<?php ukladka_trotuarnoy_plitki_render_image( $method['icon'] ?? 0, 'calculator-form__contact-icon', 'thumbnail' ); ?>
									<?php echo esc_html( $method['label'] ?? '' ); ?>
								</span>
							</label>
						<?php endforeach; ?>
						<?php if ( $contact_phone_label ) : ?>
							<span class="calculator-form__contact-phone-label"><?php echo esc_html( $contact_phone_label ); ?></span>
						<?php endif; ?>
						<input type="tel" name="<?php echo esc_attr( $field_name . '_phone' ); ?>" placeholder="<?php echo esc_attr( $step['contact_phone_placeholder'] ?? '+7 (___) ___-__-__' ); ?>" autocomplete="tel" <?php echo ! empty( $step['required'] ) ? 'required' : ''; ?>>
					</div>
				<?php else : ?>
					<input
						class="calculator-form__input"
						type="<?php echo esc_attr( 'phone' === $type ? 'tel' : ( 'email' === $type ? 'email' : 'text' ) ); ?>"
						name="<?php echo esc_attr( $field_name ); ?>"
						placeholder="<?php echo esc_attr( $step['input_placeholder'] ?? '' ); ?>"
						<?php echo 'phone' === $type ? 'autocomplete="tel"' : ''; ?>
						<?php echo ! empty( $step['required'] ) ? 'required' : ''; ?>
					>
				<?php endif; ?>
			</fieldset>
		<?php endforeach; ?>

		<div class="calculator-form__navigation">
			<button class="calculator-form__previous" type="button"><?php echo esc_html( get_field( 'back_button_text', $calculator_id ) ?: 'Назад' ); ?></button>
			<span class="calculator-form__progress" aria-live="polite"></span>
			<button class="calculator-form__next button" type="button" data-next-text="<?php echo esc_attr( get_field( 'next_button_text', $calculator_id ) ?: 'Далее' ); ?>" data-last-text="<?php echo esc_attr( get_field( 'last_step_label', $calculator_id ) ?: 'Получить расчёт' ); ?>">Далее</button>
		</div>
	</div>

	<?php if ( $show_result ) : ?>
		<aside class="calculator-form__result">
			<h3><?php echo esc_html( get_field( 'result_title', $calculator_id ) ?: 'Ваш расчёт' ); ?></h3>
			<div class="calculator-form__summary" data-calculator-summary></div>
			<?php if ( $result_panel_icon ) : ?>
				<span class="calculator-form__result-icon"><?php ukladka_trotuarnoy_plitki_render_image( $result_panel_icon, 'calculator-form__result-icon-image', 'full' ); ?></span>
			<?php endif; ?>
			<span class="calculator-form__price-label"><?php echo esc_html( get_field( 'price_label', $calculator_id ) ?: 'Предварительная стоимость' ); ?></span>
			<strong class="calculator-form__price" data-calculator-price><?php echo esc_html( $price_prefix . ' ' . number_format_i18n( $base_price, 0 ) . ' ' . $price_suffix ); ?></strong>
			<?php if ( get_field( 'price_description', $calculator_id ) ) : ?>
				<p><?php echo esc_html( get_field( 'price_description', $calculator_id ) ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $result_button['text'] ) ) : ?>
				<button
					class="<?php echo esc_attr( implode( ' ', ukladka_trotuarnoy_plitki_get_css_classes( 'button', $result_icon ? 'button--has-icon' : '', 'calculator-form__result-button', (string) ( $result_button['css_class'] ?? '' ) ) ) ); ?>"
					type="submit"
					<?php echo ! empty( $result_button['aria_label'] ) ? 'aria-label="' . esc_attr( $result_button['aria_label'] ) . '"' : ''; ?>
				>
					<span class="button__text"><?php echo esc_html( $result_button['text'] ); ?></span>
					<?php if ( $result_icon ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $result_icon, 'button__icon', 'thumbnail' ); ?><?php endif; ?>
				</button>
			<?php endif; ?>
			<?php if ( $consent_text ) : ?>
				<label class="calculator-form__consent">
					<input type="checkbox" name="consent" value="yes" checked required>
					<span class="calculator-form__consent-box" aria-hidden="true"><?php if ( $consent_icon ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $consent_icon, 'calculator-form__consent-icon', 'full' ); ?><?php endif; ?></span>
					<span class="calculator-form__consent-text"><?php echo wp_kses_post( $consent_text ); ?></span>
				</label>
			<?php endif; ?>
		</aside>
	<?php endif; ?>

	<?php if ( ! $show_result && $consent_text ) : ?>
		<label class="calculator-form__consent calculator-form__consent--standalone">
			<input type="checkbox" name="consent" value="yes" checked required>
			<span class="calculator-form__consent-box" aria-hidden="true"></span>
			<span class="calculator-form__consent-text"><?php echo wp_kses_post( $consent_text ); ?></span>
		</label>
	<?php endif; ?>
</form>
