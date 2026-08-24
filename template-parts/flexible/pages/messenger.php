<?php
/**
 * Messenger process call to action layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="messenger__wrapper">
			<div class="messenger__content">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="messenger__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<div class="messenger__text"><?php echo wp_kses_post( wpautop( $section['text'] ) ); ?></div>
				<?php endif; ?>
				<?php ukladka_trotuarnoy_plitki_render_button( $section, 'messenger__button' ); ?>
			</div>

			<?php if ( ! empty( $section['image'] ) ) : ?>
				<figure class="messenger__media">
					<?php ukladka_trotuarnoy_plitki_render_image( $section['image'], 'messenger__image', 'full' ); ?>
				</figure>
			<?php endif; ?>
		</div>
	</div>
</section>
