<?php
/** Modal presentation; shared quiz data and renderer scope supplied by hero-quiz.php. */
if ( ! isset( $is_modal ) ) {
	get_template_part( 'template-parts/flexible/pages/hero-quiz', null, $args );
	return;
}
$hero = is_array( $section['modal_hero'] ?? null ) ? $section['modal_hero'] : array();
$modal_id = $section_id . '-dialog';
$cta = ukladka_trotuarnoy_plitki_resolve_button( array( 'global_button_id' => $hero['cta_button'] ?? '' ) );
$classes = ukladka_trotuarnoy_plitki_get_css_classes( 'hero-quiz-launch', implode( ' ', array_diff( $args['classes'] ?? array(), array( 'hero-quiz-modal', 'hero-quiz' ) ) ) );
?>
<section id="<?php echo esc_attr( $section_id ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<picture class="hero-quiz-launch__media">
		<?php if ( $mobile_url ) : ?><source media="(max-width: 767px)" srcset="<?php echo esc_url( $mobile_url ); ?>"><?php endif; ?>
		<?php ukladka_trotuarnoy_plitki_render_image( $section['background_image'] ?? 0, 'hero-quiz__image', 'full' ); ?>
	</picture>
	<div class="container">
		<div class="hero-quiz-launch__body">
			<div class="hero-quiz-launch__intro">
				<h1 class="hero-quiz-launch__headline"><span><?php echo esc_html( $section['title_accent'] ?? '' ); ?></span><span><?php echo esc_html( $section['title'] ?? '' ); ?></span><span class="hero-quiz-launch__price"><?php echo esc_html( $hero['price'] ?? '' ); ?></span></h1>
				<p class="hero-quiz-launch__description"><?php echo esc_html( $hero['description'] ?? '' ); ?></p>
			</div>
			<div class="hero-quiz-launch__actions">
				<?php if ( ! empty( $cta['text'] ) ) : ?><button type="button" class="hero-quiz-launch__cta" data-modal-target="<?php echo esc_attr( $modal_id ); ?>" aria-haspopup="dialog"><span><?php echo esc_html( $cta['text'] ); ?></span><?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'] ?? 0, 'hero-quiz__control-icon', 'full' ); ?></button><?php endif; ?>
				<?php require get_theme_file_path( '/template-parts/components/hero-quiz-reviews.php' ); ?>
			</div>
		</div>
	</div>
	<?php if ( ! empty( $hero['benefits'] ) ) : ?>
	<div class="hero-quiz-launch__benefits"><div class="container"><div class="hero-quiz-launch__benefit-list">
		<?php foreach ( $hero['benefits'] as $benefit ) : ?>
		<div class="hero-quiz-launch__benefit">
			<div class="hero-quiz-launch__benefit-meta"><?php ukladka_trotuarnoy_plitki_render_image( $benefit['icon'] ?? 0, 'hero-quiz-launch__benefit-icon', 'full' ); ?><span><?php echo esc_html( $benefit['number'] ?? '' ); ?></span></div>
			<div class="hero-quiz-launch__benefit-copy"><h2><?php echo esc_html( $benefit['title'] ?? '' ); ?></h2><p><?php echo esc_html( $benefit['description'] ?? '' ); ?></p></div>
		</div>
		<?php endforeach; ?>
	</div></div></div>
	<?php endif; ?>
</section>
<div class="site-modal hero-quiz hero-quiz-modal" id="<?php echo esc_attr( $modal_id ); ?>" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php echo esc_attr( $hero['modal_title'] ?? '' ); ?>" data-hero-quiz data-step="1">
	<div class="site-modal__backdrop" data-modal-close></div>
	<div class="site-modal__dialog hero-quiz__panel">
		<button class="site-modal__close" type="button" data-modal-close aria-label="Закрыть"><img class="hero-quiz-modal__close-icon" src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/hero-quiz-close.svg' ); ?>" width="18" height="18" alt="" aria-hidden="true"><span>Закрыть</span></button>
		<div class="hero-quiz-modal__heading"><h2 tabindex="-1"><?php echo esc_html( $hero['modal_title'] ?? '' ); ?></h2><p><?php echo esc_html( $hero['disclaimer'] ?? '' ); ?></p></div>
		<?php require get_theme_file_path( '/template-parts/components/hero-quiz-form.php' ); ?>
		<?php require get_theme_file_path( '/template-parts/components/hero-quiz-reviews.php' ); ?>
	</div>
</div>
