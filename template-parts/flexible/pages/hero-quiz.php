<?php
/**
 * Page-local hero quiz using the shared form delivery and button data.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
if ( ! empty( $section['hide_section'] ) ) {
	return;
}
$section_id = $args['section_id'] ?? ( ( $section['section_id'] ?? '' ) ?: wp_unique_id( 'hero-quiz-' ) );
$is_modal   = 'hero_quiz_modal' === ( $section['acf_fc_layout'] ?? '' );
$classes    = ukladka_trotuarnoy_plitki_get_css_classes( 'hero-quiz', implode( ' ', $args['classes'] ?? array() ) );
$form_post  = $section['contact_form'] ?? 0;
$form_id    = $form_post instanceof WP_Post ? $form_post->ID : absint( $form_post );
$form       = $form_id && function_exists( 'get_fields' ) ? ( get_fields( $form_id ) ?: array() ) : array();
$phone_placeholder = (string) ( $form['phone_placeholder'] ?? '' );
preg_match( '/^(\+\d+\s*)(.*)$/u', $phone_placeholder, $phone_placeholder_parts );
$reviews    = is_array( $section['reviews'] ?? null ) ? $section['reviews'] : array();
if ( ! empty( $reviews['source_id'] ) ) {
	foreach ( (array) ukladka_trotuarnoy_plitki_get_option( 'rating_sources', array() ) as $rating_source ) {
		if ( ! empty( $rating_source['is_active'] ) && (string) ( $rating_source['source_id'] ?? '' ) === (string) $reviews['source_id'] ) {
			foreach ( array( 'rating', 'reviews_count' ) as $rating_key ) {
				if ( isset( $rating_source[ $rating_key ] ) && '' !== (string) $rating_source[ $rating_key ] ) {
					$reviews[ $rating_key ] = $rating_source[ $rating_key ];
				}
			}
			break;
		}
	}
}
$mobile_id  = ukladka_trotuarnoy_plitki_get_image_id( $section['mobile_image'] ?? 0 );
$mobile_url = $mobile_id ? wp_get_attachment_image_url( $mobile_id, 'full' ) : '';
$titles     = array( $section['zone_title'] ?? '', $section['area_title'] ?? '', $section['foundation_title'] ?? '', $section['load_title'] ?? '', $form['contact_step_title'] ?? '' );
$buttons    = array();
foreach ( array( 'next', 'back', 'submit' ) as $action ) {
	$buttons[ $action ] = ukladka_trotuarnoy_plitki_resolve_button( array( 'global_button_id' => $section[ $action . '_button' ] ?? '' ) );
}
$render_control = static function ( $action, $compact = false ) use ( $buttons, $section ) {
	$button = $buttons[ $action ];
	if ( empty( $button['text'] ) ) {
		return;
	}
	?>
	<button type="<?php echo 'submit' === $action ? 'submit' : 'button'; ?>" class="hero-quiz__control hero-quiz__control--<?php echo esc_attr( $action ); ?><?php echo $compact ? ' hero-quiz__control--compact' : ''; ?>" data-hero-quiz-action="<?php echo esc_attr( $action ); ?>"<?php echo 'submit' === $action ? ' disabled' : ''; ?>>
		<span<?php echo 'submit' === $action && ! empty( $section['submit_text_mobile'] ) ? ' class="hero-quiz__desktop-copy"' : ''; ?>><?php echo esc_html( $button['text'] ); ?></span>
		<?php if ( 'submit' === $action && ! empty( $section['submit_text_mobile'] ) ) : ?><span class="hero-quiz__mobile-copy"><?php echo esc_html( $section['submit_text_mobile'] ); ?></span><?php endif; ?>
		<?php ukladka_trotuarnoy_plitki_render_image( $button['icon'] ?? 0, 'hero-quiz__control-icon', 'full' ); ?>
	</button>
	<?php
};
if ( $is_modal ) {
	require get_theme_file_path( '/template-parts/flexible/pages/hero-quiz-modal.php' );
	return;
}
?>
<section id="<?php echo esc_attr( $section_id ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-hero-quiz data-step="1">
	<picture class="hero-quiz__media">
		<?php if ( $mobile_url ) : ?><source media="(max-width: 767px)" srcset="<?php echo esc_url( $mobile_url ); ?>"><?php endif; ?>
		<?php ukladka_trotuarnoy_plitki_render_image( $section['background_image'] ?? 0, 'hero-quiz__image', 'full' ); ?>
	</picture>
	<div class="container">
	<div class="hero-quiz__panel">
		<h1 class="hero-quiz__title"><span class="hero-quiz__accent"><?php echo esc_html( $section['title_accent'] ?? '' ); ?></span><span><?php echo esc_html( $section['title'] ?? '' ); ?></span></h1>
		<?php require get_theme_file_path( '/template-parts/components/hero-quiz-form.php' ); ?>
		<?php require get_theme_file_path( '/template-parts/components/hero-quiz-reviews.php' ); ?>
	</div>
	</div>
</section>
