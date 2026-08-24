<?php
/** Call to action banner layout. */

$section       = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$source_post   = absint( $args['post_id'] ?? get_the_ID() );
$section_index = absint( $args['section_index'] ?? 0 );
$is_about      = 'o-kompanii' === get_post_field( 'post_name', $source_post ) && 11 === $section_index;

if ( ! $is_about ) {
	load_template( get_template_directory() . '/template-parts/flexible/global/_generic.php', false, $args );
	return;
}

$block      = 'cta-banner';
$background = ukladka_trotuarnoy_plitki_get_image_id( $section['background_image'] ?? 0 );
$note_icon  = ukladka_trotuarnoy_plitki_get_image_id( $section['note_icon'] ?? 0 );
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'cta_banner' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper <?php echo esc_attr( $block ); ?>__wrapper--about">
			<?php if ( $background ) : ?>
				<figure class="<?php echo esc_attr( $block ); ?>__media">
					<?php ukladka_trotuarnoy_plitki_render_image( $background, $block . '__image', 'full' ); ?>
				</figure>
			<?php endif; ?>

			<div class="<?php echo esc_attr( $block ); ?>__body">
				<header class="<?php echo esc_attr( $block ); ?>__header">
					<?php if ( ! empty( $section['title'] ) ) : ?>
						<h2 class="<?php echo esc_attr( $block ); ?>__title"><?php echo esc_html( $section['title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<div class="<?php echo esc_attr( $block ); ?>__text"><?php echo wp_kses_post( $section['text'] ); ?></div>
					<?php endif; ?>
				</header>

				<div class="<?php echo esc_attr( $block ); ?>__actions">
					<?php ukladka_trotuarnoy_plitki_render_button( $section, $block . '__button' ); ?>

					<?php if ( $note_icon || ! empty( $section['note'] ) ) : ?>
						<div class="<?php echo esc_attr( $block ); ?>__note">
							<?php if ( $note_icon ) : ?>
								<span class="<?php echo esc_attr( $block ); ?>__note-icon">
									<?php ukladka_trotuarnoy_plitki_render_image( $note_icon, $block . '__note-image', 'full' ); ?>
								</span>
							<?php endif; ?>
							<?php if ( ! empty( $section['note'] ) ) : ?>
								<p class="<?php echo esc_attr( $block ); ?>__note-text"><?php echo esc_html( $section['note'] ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
