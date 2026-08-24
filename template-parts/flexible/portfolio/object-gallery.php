<?php
/**
 * Portfolio object gallery.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$gallery = (array) ( $section['gallery'] ?? array() );
$lightbox = ! empty( $section['enable_lightbox'] );
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="object-gallery__wrapper">
			<h1 class="object-gallery__title"><?php echo esc_html( $section['title'] ?? get_the_title() ); ?></h1>
			<?php if ( ! empty( $section['description'] ) ) : ?><div class="object-gallery__text"><?php echo wp_kses_post( wpautop( $section['description'] ) ); ?></div><?php endif; ?>
			<div class="object-gallery__grid">
				<?php foreach ( $gallery as $image ) : ?>
					<?php $image_id = ukladka_trotuarnoy_plitki_get_image_id( $image ); ?>
					<figure class="object-gallery__item">
						<?php if ( $lightbox && $image_id ) : ?>
							<button class="object-gallery__lightbox" type="button" data-lightbox-url="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>">
								<?php ukladka_trotuarnoy_plitki_render_image( $image, 'object-gallery__image', 'large' ); ?>
							</button>
						<?php else : ?>
							<?php ukladka_trotuarnoy_plitki_render_image( $image, 'object-gallery__image', 'large' ); ?>
						<?php endif; ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
