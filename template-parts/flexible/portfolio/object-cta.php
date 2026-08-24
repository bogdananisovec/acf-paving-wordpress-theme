<?php
/**
 * Portfolio object call to action.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="object-cta__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_image( $section['icon'] ?? 0, 'object-cta__icon', 'thumbnail' ); ?>
			<div class="object-cta__content">
				<?php if ( ! empty( $section['title'] ) ) : ?><h2 class="object-cta__title"><?php echo esc_html( $section['title'] ); ?></h2><?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?><div class="object-cta__text"><?php echo wp_kses_post( wpautop( $section['text'] ) ); ?></div><?php endif; ?>
			</div>
			<div class="object-cta__actions">
				<?php ukladka_trotuarnoy_plitki_render_button( $section, 'object-cta__button' ); ?>
				<?php if ( ! empty( $section['button_note'] ) ) : ?>
					<span class="object-cta__note">
						<?php ukladka_trotuarnoy_plitki_render_image( $section['button_note_icon'] ?? 0, 'object-cta__note-icon', 'thumbnail' ); ?>
						<?php echo esc_html( $section['button_note'] ); ?>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
