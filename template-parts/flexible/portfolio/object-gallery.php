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
			<nav class="object-gallery__breadcrumbs" aria-label="<?php esc_attr_e( 'Хлебные крошки', 'ukladka-trotuarnoy-plitki' ); ?>">
				<a class="object-gallery__breadcrumb-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<svg class="object-gallery__breadcrumb-home" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3.5 12.6286H5.75V9.2C5.75 9.00571 5.822 8.84297 5.966 8.71177C6.11 8.58057 6.288 8.51474 6.5 8.51428H9.5C9.7125 8.51428 9.89075 8.58011 10.0347 8.71177C10.1787 8.84343 10.2505 9.00617 10.25 9.2V12.6286H12.5V6.45714L8 3.37143L3.5 6.45714V12.6286ZM2 12.6286V6.45714C2 6.24 2.05325 6.03429 2.15975 5.84C2.26625 5.64571 2.413 5.48571 2.6 5.36L7.1 2.27429C7.3625 2.09143 7.6625 2 8 2C8.3375 2 8.6375 2.09143 8.9 2.27429L13.4 5.36C13.5875 5.48571 13.7345 5.64571 13.841 5.84C13.9475 6.03429 14.0005 6.24 14 6.45714V12.6286C14 13.0057 13.853 13.3287 13.559 13.5975C13.265 13.8663 12.912 14.0005 12.5 14H9.5C9.2875 14 9.1095 13.9342 8.966 13.8025C8.8225 13.6709 8.7505 13.5081 8.75 13.3143V9.88571H7.25V13.3143C7.25 13.5086 7.178 13.6715 7.034 13.8032C6.89 13.9349 6.712 14.0005 6.5 14H3.5C3.0875 14 2.7345 13.8658 2.441 13.5975C2.1475 13.3291 2.0005 13.0062 2 12.6286Z" fill="currentColor"/></svg>
					<?php esc_html_e( 'Главная', 'ukladka-trotuarnoy-plitki' ); ?>
				</a>
				<span class="object-gallery__breadcrumb-separator" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3.33333V12.6667M3.33333 8L8 12.6667L12.6667 8" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
				<span class="object-gallery__breadcrumb-parent"><?php esc_html_e( 'Портфолио', 'ukladka-trotuarnoy-plitki' ); ?></span>
				<span class="object-gallery__breadcrumb-separator" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3.33333V12.6667M3.33333 8L8 12.6667L12.6667 8" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
				<span class="object-gallery__breadcrumb-current" aria-current="page"><?php echo esc_html( $section['title'] ?? get_the_title() ); ?></span>
			</nav>
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
