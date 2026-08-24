<?php
/**
 * Paving collections with card galleries and visualizer CTA.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$source  = $section['collections_source'] ?? 'latest';
$posts   = array();

if ( 'manual' === $source ) {
	$posts = array_values( array_filter( array_map( 'absint', (array) ( $section['selected_collections'] ?? array() ) ) ) );
} else {
	$posts = get_posts(
		array(
			'post_type'      => 'paving_collection',
			'post_status'    => 'publish',
			'posts_per_page' => ! empty( $section['query_all_collections'] ) ? -1 : max( 1, absint( $section['collections_count'] ?? 8 ) ),
			'orderby'        => 'date',
			'order'          => 'DESC',
			'fields'         => 'ids',
		)
	);
}

$cta = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();
$cta_has_media = ! empty( $cta['background_image'] ) || ( 'video' === ( $cta['background_type'] ?? '' ) && ! empty( $cta['background_video'] ) );
$initial_visible_count = max( 1, absint( $section['initial_visible_count'] ?? count( $posts ) ) );
$form_counts = array();

if ( ! empty( $section['show_filters'] ) ) {
	foreach ( $posts as $post_id ) {
		$form = trim( (string) get_field( 'form', $post_id ) );
		if ( '' !== $form ) {
			$form_counts[ $form ] = ( $form_counts[ $form ] ?? 0 ) + 1;
		}
	}
	uksort( $form_counts, 'strnatcasecmp' );
}

$active_form = $posts ? trim( (string) get_field( 'form', $posts[0] ) ) : '';
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'products' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( 'products' ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="products__wrapper">
			<?php if ( ! empty( $section['title'] ) ) : ?>
				<header class="products__header">
					<h2 class="products__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				</header>
			<?php endif; ?>

			<?php if ( $form_counts ) : ?>
				<div class="products__filters" aria-label="<?php esc_attr_e( 'Фильтр по форме плитки', 'ukladka-trotuarnoy-plitki' ); ?>">
					<strong class="products__filters-title"><?php esc_html_e( 'Форма:', 'ukladka-trotuarnoy-plitki' ); ?></strong>
					<div class="products__filter-list">
						<button class="products__filter" type="button" data-product-filter="all">
							<span class="products__filter-mark" aria-hidden="true"></span>
							<span><?php esc_html_e( 'Все плитки', 'ukladka-trotuarnoy-plitki' ); ?></span>
							<small>(<?php echo esc_html( count( $posts ) ); ?>)</small>
						</button>
						<?php foreach ( $form_counts as $form => $form_count ) : ?>
							<button class="products__filter<?php echo $form === $active_form ? ' is-active' : ''; ?>" type="button" data-product-filter="<?php echo esc_attr( $form ); ?>">
								<span class="products__filter-mark" aria-hidden="true"></span>
								<span><?php echo esc_html( $form ); ?></span>
								<small>(<?php echo esc_html( $form_count ); ?>)</small>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $posts ) : ?>
				<div class="products__items" data-mobile-slider>
					<?php foreach ( $posts as $post_index => $post_id ) : ?>
						<?php
						$gallery = function_exists( 'get_field' ) ? (array) get_field( 'gallery', $post_id ) : array();
						$price   = function_exists( 'get_field' ) ? (string) get_field( 'price', $post_id ) : '';
						$tooltip = function_exists( 'get_field' ) ? (string) get_field( 'tooltip_text', $post_id ) : '';

						if ( ! $gallery && has_post_thumbnail( $post_id ) ) {
							$gallery = array( get_post_thumbnail_id( $post_id ) );
						}
						?>
						<article class="products__item" data-product-form="<?php echo esc_attr( (string) get_field( 'form', $post_id ) ); ?>"<?php echo $post_index >= $initial_visible_count ? ' hidden' : ''; ?>>
							<?php if ( $gallery ) : ?>
								<div class="products__media" data-card-slider>
									<div class="products__slides">
										<?php foreach ( $gallery as $image_index => $gallery_image ) : ?>
											<figure class="products__slide<?php echo 0 === $image_index ? ' is-active' : ''; ?>" data-card-slide>
												<?php ukladka_trotuarnoy_plitki_render_image( $gallery_image, 'products__image', 'large' ); ?>
											</figure>
										<?php endforeach; ?>
									</div>

									<?php if ( count( $gallery ) > 1 ) : ?>
										<button class="products__slide-control products__slide-control--previous" type="button" data-card-previous aria-label="<?php esc_attr_e( 'Предыдущее изображение', 'ukladka-trotuarnoy-plitki' ); ?>"></button>
										<button class="products__slide-control products__slide-control--next" type="button" data-card-next aria-label="<?php esc_attr_e( 'Следующее изображение', 'ukladka-trotuarnoy-plitki' ); ?>"></button>
										<div class="products__pagination" aria-hidden="true">
											<?php foreach ( $gallery as $image_index => $_gallery_image ) : ?>
												<span class="products__pagination-item<?php echo 0 === $image_index ? ' is-active' : ''; ?>"></span>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<div class="products__item-body">
								<h3 class="products__item-title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h3>
								<?php if ( $price ) : ?>
									<div class="products__price">
										<?php echo esc_html( $price ); ?>
										<?php if ( $tooltip ) : ?>
											<span class="products__tooltip" tabindex="0" role="note" aria-label="<?php echo esc_attr( $tooltip ); ?>" data-tooltip="<?php echo esc_attr( $tooltip ); ?>">?</span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $section['show_load_more'] ) && count( $posts ) > $initial_visible_count ) : ?>
				<button class="products__button button" type="button" data-load-more="products" data-load-count="<?php echo esc_attr( $section['load_more_count'] ?? 4 ); ?>">
					<?php echo esc_html( $section['load_more_text'] ?? __( 'Загрузить ещё', 'ukladka-trotuarnoy-plitki' ) ); ?>
				</button>
			<?php else : ?>
				<?php ukladka_trotuarnoy_plitki_render_button( $section, 'products__button' ); ?>
			<?php endif; ?>

			<?php if ( array_filter( $cta ) ) : ?>
				<div class="products__cta<?php echo $cta_has_media ? '' : ' products__cta--without-media'; ?>">
					<?php if ( 'video' === ( $cta['background_type'] ?? '' ) && ! empty( $cta['background_video'] ) ) : ?>
						<?php $video_url = wp_get_attachment_url( absint( $cta['background_video'] ) ); ?>
						<?php if ( $video_url ) : ?>
							<video class="products__cta-media" autoplay muted loop playsinline>
								<source src="<?php echo esc_url( $video_url ); ?>">
							</video>
						<?php endif; ?>
					<?php elseif ( ! empty( $cta['background_image'] ) ) : ?>
						<?php ukladka_trotuarnoy_plitki_render_image( $cta['background_image'], 'products__cta-media', 'full' ); ?>
					<?php endif; ?>

					<div class="products__cta-card">
						<div class="products__cta-copy">
							<?php if ( ! empty( $cta['title'] ) ) : ?>
								<h3 class="products__cta-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $cta['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $cta['text'] ) ) : ?>
								<div class="products__cta-text"><?php echo wp_kses_post( wpautop( $cta['text'] ) ); ?></div>
							<?php endif; ?>
						</div>
						<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'products__cta-button' ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
