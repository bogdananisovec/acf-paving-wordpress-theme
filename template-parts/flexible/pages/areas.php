<?php
/**
 * Paving application areas layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items   = is_array( $section['items'] ?? null ) ? array_filter(
	$section['items'],
	static function ( $item ) {
		return is_array( $item ) && ( ! empty( $item['number'] ) || ! empty( $item['title'] ) || ! empty( $item['text'] ) );
	}
) : array();
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="areas__wrapper">
			<header class="areas__header">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="areas__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="areas__text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
			</header>

			<div class="areas__media">
				<?php if ( ! empty( $section['main_image'] ) ) : ?>
					<?php ukladka_trotuarnoy_plitki_render_image( $section['main_image'], 'areas__main-image', 'full' ); ?>
				<?php endif; ?>
				<?php if ( ! empty( $section['small_image'] ) ) : ?>
					<figure class="areas__small-media">
						<?php ukladka_trotuarnoy_plitki_render_image( $section['small_image'], 'areas__small-image', 'medium_large' ); ?>
					</figure>
				<?php endif; ?>
				<?php if ( ! empty( $section['decor_icon'] ) ) : ?>
					<?php ukladka_trotuarnoy_plitki_render_image( $section['decor_icon'], 'areas__decor-icon', 'full' ); ?>
				<?php endif; ?>
				<?php if ( ! empty( $section['small_title'] ) ) : ?>
					<strong class="areas__small-title"><?php echo esc_html( $section['small_title'] ); ?></strong>
				<?php endif; ?>
			</div>

			<?php if ( $items ) : ?>
				<div class="areas__items">
					<?php foreach ( $items as $index => $item ) : ?>
						<article class="areas__item">
							<span class="areas__number"><?php echo esc_html( $item['number'] ?? str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
							<span class="areas__item-line" aria-hidden="true"></span>
							<div class="areas__item-body">
								<?php if ( ! empty( $item['title'] ) ) : ?>
									<h3 class="areas__item-title"><?php echo esc_html( $item['title'] ); ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $item['text'] ) ) : ?>
									<p class="areas__item-text"><?php echo esc_html( $item['text'] ); ?></p>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
