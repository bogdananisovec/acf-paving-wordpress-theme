<?php
/**
 * Interactive before and after comparison.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'before-after';
$title   = $section['title'] ?? '';
$text    = $section['text'] ?? '';
$before  = $section['before_image'] ?? 0;
$after   = $section['after_image'] ?? 0;
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="before-after__wrapper">
			<?php if ( $title ) : ?>
				<header class="before-after__header">
					<h2 class="before-after__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				</header>
			<?php endif; ?>

			<div class="before-after__content">
				<div class="before-after__info">
					<?php if ( $text ) : ?>
						<div class="before-after__text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $section['pre_button_text'] ) || ! empty( $section['button_id'] ) || ! empty( $section['button_text'] ) ) : ?>
						<div class="before-after__action">
							<?php if ( ! empty( $section['pre_button_text'] ) ) : ?>
								<div class="before-after__action-text"><?php echo wp_kses_post( wpautop( $section['pre_button_text'] ) ); ?></div>
							<?php endif; ?>
							<?php ukladka_trotuarnoy_plitki_render_button( $section, 'before-after__button' ); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( $before || $after ) : ?>
					<div class="before-after__comparison" data-before-after style="--before-after-position: 50%;">
						<figure class="before-after__panel before-after__panel--after">
							<?php ukladka_trotuarnoy_plitki_render_image( $after ?: $before, 'before-after__image', 'full' ); ?>
							<figcaption class="before-after__label before-after__label--after"><?php echo esc_html( $section['after_label'] ?: 'После' ); ?></figcaption>
						</figure>

						<figure class="before-after__panel before-after__panel--before">
							<?php ukladka_trotuarnoy_plitki_render_image( $before ?: $after, 'before-after__image', 'full' ); ?>
							<figcaption class="before-after__label before-after__label--before"><?php echo esc_html( $section['before_label'] ?: 'До' ); ?></figcaption>
						</figure>

						<div class="before-after__labels" aria-hidden="true">
							<span class="before-after__label before-after__label--mobile-after"><?php echo esc_html( $section['after_label'] ?: 'После' ); ?></span>
							<span class="before-after__label before-after__label--mobile-before"><?php echo esc_html( $section['before_label'] ?: 'До' ); ?></span>
						</div>

						<div class="before-after__divider" aria-hidden="true">
							<span class="before-after__handle">
								<span class="before-after__handle-arrow before-after__handle-arrow--left"></span>
								<span class="before-after__handle-arrow before-after__handle-arrow--right"></span>
							</span>
						</div>

						<input
							class="before-after__range"
							type="range"
							min="0"
							max="100"
							value="50"
							aria-label="<?php esc_attr_e( 'Сравнить фотографии до и после', 'ukladka-trotuarnoy-plitki' ); ?>"
						>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
