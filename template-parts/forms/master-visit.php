<?php
/**
 * Master visit request form.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$form_post_id = absint( $args['form_post_id'] ?? 0 );
$form         = function_exists( 'get_fields' ) ? get_fields( $form_post_id ) : array();

if ( ! $form_post_id || ! is_array( $form ) || empty( $form['is_active'] ) ) {
	return;
}
?>
<form class="master-visit-form <?php echo esc_attr( sanitize_text_field( (string) ( $form['css_class'] ?? '' ) ) ); ?>" method="post" data-site-form data-form-id="<?php echo esc_attr( sanitize_key( $form['form_id'] ?? 'master-visit' ) ); ?>">
	<label class="screen-reader-text" aria-hidden="true">Сайт<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
	<?php if ( ! empty( $form['heading'] ) ) : ?><h2 class="master-visit-form__title"><?php echo wp_kses_post( nl2br( esc_html( $form['heading'] ) ) ); ?></h2><?php endif; ?>
	<?php if ( ! empty( $form['subheading'] ) ) : ?><p class="master-visit-form__text"><?php echo esc_html( $form['subheading'] ); ?></p><?php endif; ?>

	<label class="master-visit-form__field">
		<span class="master-visit-form__label"><?php echo esc_html( $form['phone_step_title'] ?? 'Телефон' ); ?></span>
		<input class="master-visit-form__input" type="tel" name="phone" placeholder="<?php echo esc_attr( $form['phone_placeholder'] ?? '' ); ?>" required>
	</label>

	<?php if ( ! empty( $form['contact_methods'] ) ) : ?>
		<fieldset class="master-visit-form__methods">
			<legend class="master-visit-form__legend"><?php echo esc_html( $form['contact_step_title'] ?? '' ); ?></legend>
			<?php foreach ( $form['contact_methods'] as $method ) : ?>
				<label class="master-visit-form__method">
					<input type="radio" name="contact_method" value="<?php echo esc_attr( sanitize_key( $method['value'] ?? '' ) ); ?>">
					<span><?php echo esc_html( $method['label'] ?? '' ); ?></span>
				</label>
			<?php endforeach; ?>
		</fieldset>
	<?php endif; ?>

	<?php if ( ! empty( $form['privacy_text'] ) ) : ?><div class="master-visit-form__privacy"><?php echo wp_kses_post( $form['privacy_text'] ); ?></div><?php endif; ?>
	<button class="master-visit-form__submit" type="submit"><?php echo esc_html( $form['submit_text'] ?? 'Отправить' ); ?></button>
</form>
