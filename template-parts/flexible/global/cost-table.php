<?php
/**
 * Shared cost table layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$headers = is_array( $section['headers'] ?? null ) ? $section['headers'] : array();
$rows    = is_array( $section['rows'] ?? null ) ? $section['rows'] : array();
$note    = is_array( $section['note'] ?? null ) ? $section['note'] : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();

if ( isset( $headers[0]['title'] ) && 'Рассчитать стоимость двора' === $headers[0]['title'] ) {
	$headers[0]['title'] = 'Вид работ';
}
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'cost_table' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( 'cost-table' ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="cost-table__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, 'cost-table' ); ?>

			<div class="cost-table__content">
				<?php ukladka_trotuarnoy_plitki_render_table( $headers, $rows, 'cost-table' ); ?>

				<?php if ( array_filter( $note ) ) : ?>
					<aside class="cost-table__note">
						<?php if ( ! empty( $note['icon'] ) ) : ?>
							<span class="cost-table__note-icon">
								<?php ukladka_trotuarnoy_plitki_render_image( $note['icon'], 'cost-table__note-icon-image', 'thumbnail' ); ?>
							</span>
						<?php endif; ?>
						<?php if ( ! empty( $note['text'] ) ) : ?>
							<div class="cost-table__note-text"><?php echo wp_kses_post( wpautop( $note['text'] ) ); ?></div>
						<?php endif; ?>
					</aside>
				<?php endif; ?>

				<?php if ( array_filter( $cta ) ) : ?>
					<aside class="cost-table__cta">
						<?php if ( ! empty( $cta['icon'] ) ) : ?>
							<span class="cost-table__cta-icon">
								<?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], 'cost-table__cta-icon-image', 'thumbnail' ); ?>
							</span>
						<?php endif; ?>

						<div class="cost-table__cta-copy">
							<?php if ( ! empty( $cta['title'] ) ) : ?>
								<h3 class="cost-table__cta-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $cta['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $cta['list'] ) && is_array( $cta['list'] ) ) : ?>
								<ul class="cost-table__cta-list">
									<?php foreach ( $cta['list'] as $item ) : ?>
										<?php $text = is_array( $item ) ? ( $item['text'] ?? '' ) : $item; ?>
										<?php if ( $text ) : ?>
											<li><?php echo esc_html( $text ); ?></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>

						<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'cost-table__cta-button' ); ?>
					</aside>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
