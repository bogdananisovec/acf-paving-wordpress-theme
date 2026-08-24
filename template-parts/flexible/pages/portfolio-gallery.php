<?php
/**
 * About-page portfolio gallery layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section       = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$source_post   = absint( $args['post_id'] ?? get_the_ID() );
$section_index = absint( $args['section_index'] ?? 0 );
$is_about      = 'o-kompanii' === get_post_field( 'post_name', $source_post ) && 9 === $section_index;

if ( ! $is_about ) {
	load_template( get_template_directory() . '/template-parts/flexible/global/_generic.php', false, $args );
	return;
}

$block       = 'portfolio-gallery';
$gallery     = array_values( array_filter( array_map( 'absint', (array) ( $section['gallery'] ?? array() ) ) ) );
$active      = min( 2, max( 0, count( $gallery ) - 1 ) );
$meta_prefix = sanitize_key( (string) ( $args['field_name'] ?? 'page_sections' ) ) . '_' . $section_index . '_';
$previous    = ukladka_trotuarnoy_plitki_get_image_id( $section['previous_icon'] ?? get_post_meta( $source_post, $meta_prefix . 'previous_icon', true ) );
$next        = ukladka_trotuarnoy_plitki_get_image_id( $section['next_icon'] ?? get_post_meta( $source_post, $meta_prefix . 'next_icon', true ) );
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'portfolio_gallery' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<?php if ( $gallery ) : ?>
				<div class="<?php echo esc_attr( $block ); ?>__slider" data-portfolio-gallery data-active-index="<?php echo esc_attr( $active ); ?>">
					<figure class="<?php echo esc_attr( $block ); ?>__main">
						<?php
						echo wp_get_attachment_image(
							$gallery[ $active ],
							'full',
							false,
							array(
								'class'                       => $block . '__main-image',
								'data-portfolio-gallery-main' => '',
							)
						); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>

						<?php if ( $previous ) : ?>
							<button class="<?php echo esc_attr( $block ); ?>__arrow <?php echo esc_attr( $block ); ?>__arrow--previous" type="button" data-portfolio-gallery-previous aria-label="Предыдущая фотография">
								<?php ukladka_trotuarnoy_plitki_render_image( $previous, $block . '__arrow-icon', 'full' ); ?>
							</button>
						<?php endif; ?>

						<?php if ( $next ) : ?>
							<button class="<?php echo esc_attr( $block ); ?>__arrow <?php echo esc_attr( $block ); ?>__arrow--next" type="button" data-portfolio-gallery-next aria-label="Следующая фотография">
								<?php ukladka_trotuarnoy_plitki_render_image( $next, $block . '__arrow-icon', 'full' ); ?>
							</button>
						<?php endif; ?>
					</figure>

					<div class="<?php echo esc_attr( $block ); ?>__thumbnails">
						<?php foreach ( $gallery as $index => $image_id ) : ?>
							<?php
							$full_src = wp_get_attachment_image_url( $image_id, 'full' );
							$alt      = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
							?>
							<button
								class="<?php echo esc_attr( $block ); ?>__thumbnail<?php echo $active === $index ? ' is-active' : ''; ?>"
								type="button"
								data-portfolio-gallery-thumbnail
								data-full-src="<?php echo esc_url( $full_src ); ?>"
								data-full-alt="<?php echo esc_attr( $alt ); ?>"
								aria-label="Показать фотографию <?php echo esc_attr( $index + 1 ); ?>"
								aria-current="<?php echo $active === $index ? 'true' : 'false'; ?>"
							>
								<?php echo wp_get_attachment_image( $image_id, 'medium', false, array( 'class' => $block . '__thumbnail-image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
