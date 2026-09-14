<?php
/**
 * Shared semantic renderer for ACF layouts with the same content primitives.
 *
 * Individual Flexible Content layouts keep their own entry file and use this
 * renderer only for common structures: media, cards, tables, lists and CTAs.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$layout  = sanitize_key( (string) ( $args['layout'] ?? $section['acf_fc_layout'] ?? 'section' ) );
$block   = str_replace( '_', '-', $layout );
$items   = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$rows    = $section['rows'] ?? $section['price_rows'] ?? array();
$headers = $section['headers'] ?? $section['column_headers'] ?? array();
$image   = $section['main_image'] ?? $section['image'] ?? $section['car_image'] ?? $section['background_image'] ?? 0;
$related = $section['selected_collections'] ?? $section['selected'] ?? $section['portfolio_items'] ?? array();
$gallery = $section['gallery'] ?? array();
$button_rendered = false;

if ( 'price_table' === $layout && isset( $headers['work'], $headers['includes'], $headers['suitable'], $headers['price'] ) ) {
	$headers = array(
		$headers['work'],
		$headers['includes'],
		$headers['suitable'],
		$headers['price'],
	);
}

if ( 'price_table' === $layout && ! $headers ) {
	$headers = array_filter(
		array(
			array(
				'title' => $section['table_header_work_title'] ?? '',
				'icon'  => $section['table_header_work_icon'] ?? ( $section['column_headers_work_icon'] ?? 0 ),
			),
			array(
				'title' => $section['table_header_includes_title'] ?? '',
				'icon'  => $section['table_header_includes_icon'] ?? ( $section['column_headers_includes_icon'] ?? 0 ),
			),
			array(
				'title' => $section['table_header_suitable_title'] ?? '',
				'icon'  => $section['table_header_suitable_icon'] ?? ( $section['column_headers_suitable_icon'] ?? 0 ),
			),
			array(
				'title' => $section['table_header_price_title'] ?? '',
				'icon'  => $section['table_header_price_icon'] ?? ( $section['column_headers_price_icon'] ?? 0 ),
			),
		),
		static function ( $header ) {
			return ! empty( $header['title'] );
		}
	);
}

if ( 'price_table' === $layout && 'portfolio' === ( $args['context'] ?? '' ) ) {
	$prices_section            = $section;
	$prices_section['headers'] = $headers;
	$prices_section['rows']    = $rows;

	if ( empty( $prices_section['note_text'] ) && ! empty( $section['info_text'] ) ) {
		$prices_section['note_text'] = $section['info_text'];
	}

	if ( empty( $prices_section['note_icon'] ) && ! empty( $section['info_icon'] ) ) {
		$prices_section['note_icon'] = $section['info_icon'];
	}

	if ( empty( $prices_section['cta'] ) || ! is_array( $prices_section['cta'] ) ) {
		$prices_section['cta'] = array(
			'icon'             => $section['cta_icon'] ?? 0,
			'title'            => $section['cta_title'] ?? '',
			'text'             => $section['cta_text'] ?? '',
			'button_id'        => $section['button_id'] ?? '',
			'global_button_id' => $section['global_button_id'] ?? '',
			'button_text'      => $section['button_text'] ?? '',
			'button_link'      => $section['button_link'] ?? '',
			'button_class'     => $section['button_class'] ?? '',
			'modal_title'      => $section['modal_title'] ?? '',
		);
	}

	$prices_args            = $args;
	$prices_args['section'] = $prices_section;
	$prices_args['classes'] = array_values( array_unique( array_merge( $args['classes'] ?? array(), array( 'prices', 'prices--background-black' ) ) ) );

	load_template( get_template_directory() . '/template-parts/flexible/pages/prices.php', false, $prices_args );
	return;
}

if ( ! $items && is_array( $section['offices'] ?? null ) ) {
	$items = $section['offices'];
}

$secondary_repeaters = array(
	'top_benefits'     => 'Преимущества',
	'checklist'        => $section['checklist_title'] ?? '',
	'quality_checklist' => $section['checklist_title'] ?? '',
	'problems'         => $section['problems_title'] ?? '',
	'equipment'        => $section['equipment_title'] ?? '',
	'importance_items' => $section['importance_title'] ?? '',
	'bottom_benefits'  => '',
	'points'           => '',
);

$groups = array(
	'side_card',
	'cta',
	'note',
	'consultation',
	'guarantee_card',
	'team_card',
	'bottom_block',
	'bottom_cta',
	'questions',
	'check_note',
	'info',
	'try_before_buy',
);

$handled_fields = array_merge(
	array(
		'acf_fc_layout',
		'admin_title',
		'section_id',
		'section_class',
		'hide_section',
		'section_background',
		'padding_top',
		'padding_bottom',
		'margin_top',
		'margin_bottom',
		'use_external_content',
		'source_page',
		'source_section_id',
		'show_breadcrumbs',
		'title',
		'text',
		'description',
		'items',
		'rows',
		'price_rows',
		'headers',
		'column_headers',
		'table_header_work_icon',
		'table_header_work_title',
		'table_header_includes_icon',
		'table_header_includes_title',
		'table_header_suitable_icon',
		'table_header_suitable_title',
		'table_header_price_icon',
		'table_header_price_title',
		'main_image',
		'image',
		'car_image',
		'background_image',
		'before_image',
		'after_image',
		'before_label',
		'after_label',
		'selected_collections',
		'selected',
		'portfolio_items',
		'gallery',
		'offices',
		'map_code',
		'map_height',
		'small_image',
		'side_image',
		'small_title',
		'side_title',
		'button_id',
		'global_button_id',
		'button_text',
		'button_link',
		'button_class',
		'modal_title',
		'info_icon',
		'info_text',
		'cta_icon',
		'cta_title',
		'cta_text',
		'background_type',
		'calculator_id',
		'calculator_theme',
		'collections_count',
		'collections_source',
		'columns_desktop',
		'enable_lightbox',
		'image_position',
		'insert_after',
		'load_more_count',
		'map_type',
		'number_format',
		'opened_by_default',
		'portfolio_count',
		'portfolio_source',
		'review_source',
		'reviews_source',
		'section_type',
		'show',
		'show_all_cities_button',
		'show_load_more',
		'show_note',
		'source',
		'target_blank',
		'type',
	),
	array_keys( $secondary_repeaters ),
	$groups
);
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<div class="<?php echo esc_attr( $block ); ?>__content">
				<?php if ( 'before_after' === $layout && ( ! empty( $section['before_image'] ) || ! empty( $section['after_image'] ) ) ) : ?>
					<div class="before-after__comparison" data-before-after>
						<figure class="before-after__panel">
							<?php ukladka_trotuarnoy_plitki_render_image( $section['before_image'] ?? 0, 'before-after__image', 'full' ); ?>
							<figcaption class="before-after__label"><?php echo esc_html( $section['before_label'] ?? 'До' ); ?></figcaption>
						</figure>
						<figure class="before-after__panel">
							<?php ukladka_trotuarnoy_plitki_render_image( $section['after_image'] ?? 0, 'before-after__image', 'full' ); ?>
							<figcaption class="before-after__label"><?php echo esc_html( $section['after_label'] ?? 'После' ); ?></figcaption>
						</figure>
					</div>
				<?php elseif ( $image ) : ?>
					<figure class="<?php echo esc_attr( $block ); ?>__media">
						<?php ukladka_trotuarnoy_plitki_render_image( $image, $block . '__image', 'full' ); ?>
					</figure>
				<?php endif; ?>

				<?php if ( $rows ) : ?>
					<?php ukladka_trotuarnoy_plitki_render_table( $headers, $rows, $block ); ?>
				<?php endif; ?>

				<?php if ( $items ) : ?>
					<div class="<?php echo esc_attr( $block ); ?>__items" data-mobile-slider>
						<?php foreach ( $items as $index => $item ) : ?>
							<?php ukladka_trotuarnoy_plitki_render_card( $item, $block, $index ); ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( is_array( $related ) && $related ) : ?>
					<div class="<?php echo esc_attr( $block ); ?>__items" data-mobile-slider>
						<?php foreach ( $related as $related_post ) : ?>
							<?php $related_id = is_object( $related_post ) ? absint( $related_post->ID ?? 0 ) : absint( $related_post ); ?>
							<?php if ( $related_id ) : ?>
								<article class="<?php echo esc_attr( $block ); ?>__item">
									<a class="<?php echo esc_attr( $block ); ?>__item-link" href="<?php echo esc_url( get_permalink( $related_id ) ); ?>">
										<?php echo get_the_post_thumbnail( $related_id, 'large', array( 'class' => $block . '__image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<div class="<?php echo esc_attr( $block ); ?>__item-body">
											<h3 class="<?php echo esc_attr( $block ); ?>__item-title"><?php echo esc_html( get_the_title( $related_id ) ); ?></h3>
										</div>
									</a>
								</article>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( is_array( $gallery ) && $gallery ) : ?>
					<div class="<?php echo esc_attr( $block ); ?>__gallery" data-mobile-slider>
						<?php foreach ( $gallery as $gallery_image ) : ?>
							<figure class="<?php echo esc_attr( $block ); ?>__gallery-item">
								<?php ukladka_trotuarnoy_plitki_render_image( $gallery_image, $block . '__gallery-image', 'large' ); ?>
							</figure>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $section['map_code'] ) ) : ?>
					<div class="<?php echo esc_attr( $block ); ?>__map" style="--map-height: <?php echo esc_attr( max( 280, absint( $section['map_height'] ?? 480 ) ) ); ?>px">
						<?php
						echo wp_kses(
							$section['map_code'],
							array(
								'iframe' => array(
									'src'             => true,
									'width'           => true,
									'height'          => true,
									'style'           => true,
									'frameborder'     => true,
									'allowfullscreen' => true,
									'loading'         => true,
									'title'           => true,
								),
							)
						);
						?>
					</div>
				<?php endif; ?>

				<?php foreach ( $secondary_repeaters as $field => $field_title ) : ?>
					<?php $field_items = is_array( $section[ $field ] ?? null ) ? $section[ $field ] : array(); ?>
					<?php if ( $field_items ) : ?>
						<div class="<?php echo esc_attr( $block ); ?>__subsection <?php echo esc_attr( $block ); ?>__subsection--<?php echo esc_attr( str_replace( '_', '-', $field ) ); ?>">
							<?php if ( $field_title ) : ?>
								<h3 class="<?php echo esc_attr( $block ); ?>__subtitle"><?php echo ukladka_trotuarnoy_plitki_format_heading( $field_title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
							<?php endif; ?>
							<div class="<?php echo esc_attr( $block ); ?>__items <?php echo esc_attr( $block ); ?>__items--<?php echo esc_attr( str_replace( '_', '-', $field ) ); ?>" data-mobile-slider>
								<?php foreach ( $field_items as $index => $item ) : ?>
									<?php ukladka_trotuarnoy_plitki_render_card( $item, $block, $index ); ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php foreach ( $groups as $field ) : ?>
					<?php $group = is_array( $section[ $field ] ?? null ) ? $section[ $field ] : array(); ?>
					<?php if ( array_filter( $group ) ) : ?>
						<aside class="<?php echo esc_attr( $block ); ?>__aside <?php echo esc_attr( $block ); ?>__aside--<?php echo esc_attr( str_replace( '_', '-', $field ) ); ?>">
							<?php ukladka_trotuarnoy_plitki_render_card( $group, $block . '-aside' ); ?>
						</aside>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php if ( 'price_table' === $layout && ( ! empty( $section['info_icon'] ) || ! empty( $section['info_text'] ) ) ) : ?>
					<div class="<?php echo esc_attr( $block ); ?>__info">
						<?php if ( ! empty( $section['info_icon'] ) ) : ?>
							<span class="<?php echo esc_attr( $block ); ?>__info-icon"><?php ukladka_trotuarnoy_plitki_render_image( $section['info_icon'], $block . '__info-image', 'full' ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $section['info_text'] ) ) : ?>
							<div class="<?php echo esc_attr( $block ); ?>__info-text"><?php echo wp_kses_post( wpautop( $section['info_text'] ) ); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( 'price_table' === $layout && ( ! empty( $section['cta_icon'] ) || ! empty( $section['cta_title'] ) || ! empty( $section['cta_text'] ) ) ) : ?>
					<div class="<?php echo esc_attr( $block ); ?>__cta">
						<?php if ( ! empty( $section['cta_icon'] ) ) : ?>
							<span class="<?php echo esc_attr( $block ); ?>__cta-icon"><?php ukladka_trotuarnoy_plitki_render_image( $section['cta_icon'], $block . '__cta-image', 'full' ); ?></span>
						<?php endif; ?>
						<div class="<?php echo esc_attr( $block ); ?>__cta-body">
							<?php if ( ! empty( $section['cta_title'] ) ) : ?>
								<h3 class="<?php echo esc_attr( $block ); ?>__cta-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['cta_title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $section['cta_text'] ) ) : ?>
								<div class="<?php echo esc_attr( $block ); ?>__cta-text"><?php echo wp_kses_post( wpautop( $section['cta_text'] ) ); ?></div>
							<?php endif; ?>
						</div>
						<?php ukladka_trotuarnoy_plitki_render_button( $section, $block . '__button' ); ?>
						<?php $button_rendered = true; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $section['small_image'] ) || ! empty( $section['side_image'] ) ) : ?>
					<figure class="<?php echo esc_attr( $block ); ?>__secondary-media">
						<?php ukladka_trotuarnoy_plitki_render_image( $section['small_image'] ?? $section['side_image'], $block . '__secondary-image', 'large' ); ?>
						<?php if ( ! empty( $section['small_title'] ) || ! empty( $section['side_title'] ) ) : ?>
							<figcaption><?php echo esc_html( $section['small_title'] ?? $section['side_title'] ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>

				<?php foreach ( $section as $field => $value ) : ?>
					<?php if ( ! in_array( $field, $handled_fields, true ) ) : ?>
						<?php ukladka_trotuarnoy_plitki_render_content_value( $value, $field, $block ); ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<?php if ( ! $button_rendered ) : ?>
				<?php ukladka_trotuarnoy_plitki_render_button( $section, $block . '__button' ); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
