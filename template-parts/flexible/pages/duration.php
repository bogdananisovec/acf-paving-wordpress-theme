<?php
/**
 * Project duration layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'duration';
$headers = is_array( $section['headers'] ?? null ) ? $section['headers'] : array();
$rows    = is_array( $section['rows'] ?? null ) ? $section['rows'] : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="duration__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<div class="duration__content">
				<?php if ( $rows ) : ?>
					<div class="duration__table-wrap">
						<table class="duration__table">
							<?php if ( $headers ) : ?>
								<thead>
									<tr>
										<?php foreach ( $headers as $header ) : ?>
											<th>
												<span class="duration__table-heading">
													<?php if ( ! empty( $header['icon'] ) ) : ?>
														<?php ukladka_trotuarnoy_plitki_render_image( $header['icon'], 'duration__table-icon', 'thumbnail' ); ?>
													<?php endif; ?>
													<span><?php echo esc_html( $header['title'] ?? '' ); ?></span>
												</span>
											</th>
										<?php endforeach; ?>
									</tr>
								</thead>
							<?php endif; ?>
							<tbody>
								<?php foreach ( $rows as $row ) : ?>
									<tr>
										<td data-label="<?php echo esc_attr( $headers[0]['title'] ?? 'Объект' ); ?>"><?php echo esc_html( $row['object'] ?? '' ); ?></td>
										<td data-label="<?php echo esc_attr( $headers[1]['title'] ?? 'Площадь' ); ?>"><?php echo esc_html( $row['area'] ?? '' ); ?></td>
										<td data-label="<?php echo esc_attr( $headers[2]['title'] ?? 'Срок' ); ?>"><?php echo esc_html( $row['term'] ?? '' ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>

				<?php if ( array_filter( $cta ) ) : ?>
					<aside class="duration__aside duration__aside--cta">
						<div class="duration-aside__body">
							<?php if ( ! empty( $cta['icon'] ) ) : ?>
								<span class="duration-aside__icon">
									<?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], 'duration-aside__icon-image', 'thumbnail' ); ?>
								</span>
							<?php endif; ?>

							<div class="duration-aside__content">
								<?php if ( ! empty( $cta['title'] ) ) : ?>
									<h3 class="duration-aside__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $cta['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $cta['text'] ) ) : ?>
									<div class="duration-aside__text"><?php echo wp_kses_post( wpautop( $cta['text'] ) ); ?></div>
								<?php endif; ?>
							</div>

							<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'duration-aside__button' ); ?>
						</div>
					</aside>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
