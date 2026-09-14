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
			<div class="calculator-form__price-heading">
				<span class="calculator-form__price-icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none" focusable="false">
						<rect width="36" height="36" rx="18" fill="#F0F0F0" fill-opacity="0.1"/>
						<path d="M9.86272 12.3749H10.8672V13.3793C10.8672 13.7954 11.2045 14.1327 11.6205 14.1327C12.0366 14.1327 12.3739 13.7954 12.3739 13.3793V12.3749H13.3783C13.7944 12.3749 14.1317 12.0376 14.1317 11.6215C14.1317 11.2055 13.7944 10.8682 13.3783 10.8682H12.3739V9.8637C12.3739 9.44765 12.0366 9.11035 11.6205 9.11035C11.2045 9.11035 10.8672 9.44765 10.8672 9.8637V10.8682H9.86272C9.44667 10.8682 9.10938 11.2055 9.10938 11.6215C9.10938 12.0376 9.44667 12.3749 9.86272 12.3749Z" fill="#C5A880"/>
						<path d="M21.5658 12.3749H24.5792C24.9953 12.3749 25.3326 12.0376 25.3326 11.6215C25.3326 11.2055 24.9953 10.8682 24.5792 10.8682H21.5658C21.1498 10.8682 20.8125 11.2055 20.8125 11.6215C20.8125 12.0376 21.1498 12.3749 21.5658 12.3749Z" fill="#C5A880"/>
						<path d="M24.8295 23.5742H21.3139C20.8978 23.5742 20.5605 23.9115 20.5605 24.3276C20.5605 24.7436 20.8978 25.0809 21.3139 25.0809H24.8295C25.2456 25.0809 25.5829 24.7436 25.5829 24.3276C25.5829 23.9115 25.2456 23.5742 24.8295 23.5742Z" fill="#C5A880"/>
						<path d="M24.8295 21.0635H21.3139C20.8978 21.0635 20.5605 21.4008 20.5605 21.8168C20.5605 22.2329 20.8978 22.5702 21.3139 22.5702H24.8295C25.2456 22.5702 25.5829 22.2329 25.5829 21.8168C25.5829 21.4008 25.2456 21.0635 24.8295 21.0635Z" fill="#C5A880"/>
						<path d="M13.4105 21.2842C13.1163 20.99 12.6393 20.99 12.3451 21.2842L11.6222 22.007L10.8993 21.2841C10.6052 20.9899 10.1281 20.9899 9.83394 21.2841C9.53973 21.5783 9.53973 22.0553 9.83394 22.3495L10.5569 23.0724L9.83394 23.7953C9.53973 24.0896 9.53973 24.5665 9.83394 24.8607C9.98104 25.0078 10.1738 25.0814 10.3667 25.0814C10.5595 25.0814 10.7523 25.0078 10.8993 24.8607L11.6222 24.1378L12.3451 24.8607C12.4922 25.0078 12.685 25.0814 12.8778 25.0814C13.0706 25.0814 13.2634 25.0078 13.4105 24.8607C13.7047 24.5665 13.7047 24.0895 13.4105 23.7953L12.6876 23.0724L13.4105 22.3495C13.7047 22.0554 13.7047 21.5783 13.4105 21.2842Z" fill="#C5A880"/>
						<path d="M29.5511 18.7616V7.40262C29.5511 6.15643 28.5372 5.14258 27.291 5.14258H7.40262C6.15643 5.14258 5.14258 6.15643 5.14258 7.40262V27.291C5.14258 28.5372 6.15643 29.5511 7.40262 29.5511H18.7615C19.9965 30.3755 21.4791 30.8569 23.0723 30.8569C27.3647 30.8569 30.8569 27.3647 30.8569 23.0723C30.8569 21.4792 30.3755 19.9965 29.5511 18.7616ZM28.0444 7.40262V17.0872C26.6948 15.9641 24.9611 15.2877 23.0723 15.2877C21.1834 15.2877 19.4497 15.9641 18.1002 17.0872V6.64927H27.291C27.7064 6.64927 28.0444 6.98723 28.0444 7.40262ZM7.40262 6.64927H16.5935V16.5935H6.64927V7.40262C6.64927 6.98723 6.98723 6.64927 7.40262 6.64927ZM6.64927 27.291V18.1002H16.5935V18.7616C15.769 19.9965 15.2877 21.4792 15.2877 23.0723C15.2877 24.9611 15.9641 26.6948 17.0872 28.0444H7.40262C6.98723 28.0444 6.64927 27.7064 6.64927 27.291ZM23.0723 29.3502C19.6106 29.3502 16.7944 26.5339 16.7944 23.0723C16.7944 19.6106 19.6106 16.7944 23.0723 16.7944C26.5339 16.7944 29.3502 19.6106 29.3502 23.0723C29.3502 26.5339 26.5339 29.3502 23.0723 29.3502Z" fill="#C5A880"/>
					</svg>
				</span>
				<span class="calculator-form__price-label"><?php echo esc_html( get_field( 'price_label', $calculator_id ) ?: 'Предварительная стоимость' ); ?></span>
			</div>
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
