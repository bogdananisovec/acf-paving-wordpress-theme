<?php
/**
 * Shared foundation comparison layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$headers = is_array( $section['headers'] ?? null ) ? $section['headers'] : array();
$rows    = is_array( $section['rows'] ?? null ) ? $section['rows'] : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();

$note_icon = $section['note_icon'] ?? 0;
$note_text = $section['note_text'] ?? '';

if ( $cta ) {
	$cta['button_id']    = $cta['button_id'] ?? ( $cta['cta_button_id'] ?? '' );
	$cta['button_text']  = $cta['button_text'] ?? ( $cta['cta_button_text'] ?? '' );
	$cta['button_link']  = $cta['button_link'] ?? ( $cta['cta_button_link'] ?? '' );
	$cta['modal_title']  = $cta['modal_title'] ?? ( $cta['cta_modal_title'] ?? '' );
	$cta['button_class'] = $cta['button_class'] ?? 'btn btn-cta';
}
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="foundation__wrapper">
			<header class="foundation__header">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="foundation__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="foundation__text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
			</header>

			<?php if ( $rows ) : ?>
				<div class="foundation__table-wrap">
					<table class="foundation__table">
						<?php if ( $headers ) : ?>
							<thead>
								<tr>
									<?php foreach ( $headers as $header ) : ?>
										<th scope="col">
											<span class="foundation__table-heading">
												<?php if ( ! empty( $header['icon'] ) ) : ?>
													<?php ukladka_trotuarnoy_plitki_render_image( $header['icon'], 'foundation__header-icon', 'thumbnail' ); ?>
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
									<th scope="row"><?php echo wp_kses_post( $row['foundation_type'] ?? ( $row['base'] ?? '' ) ); ?></th>
									<td><?php echo wp_kses_post( $row['where_use'] ?? ( $row['suitable'] ?? '' ) ); ?></td>
									<td><?php echo wp_kses_post( $row['includes'] ?? '' ); ?></td>
									<td><?php echo wp_kses_post( $row['advantages'] ?? ( $row['pros'] ?? '' ) ); ?></td>
									<td><?php echo wp_kses_post( $row['limitations'] ?? ( $row['limits'] ?? '' ) ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $note_icon ) || ! empty( $note_text ) ) : ?>
				<aside class="foundation__note">
					<?php if ( ! empty( $note_icon ) ) : ?>
						<?php ukladka_trotuarnoy_plitki_render_image( $note_icon, 'foundation__note-icon', 'thumbnail' ); ?>
					<?php endif; ?>
					<?php if ( ! empty( $note_text ) ) : ?>
						<div class="foundation__note-text"><?php echo wp_kses_post( wpautop( $note_text ) ); ?></div>
					<?php endif; ?>
				</aside>
			<?php endif; ?>

			<?php if ( array_filter( $cta ) ) : ?>
				<aside class="foundation__cta">
					<?php if ( ! empty( $cta['icon'] ) ) : ?>
						<span class="foundation__cta-icon">
							<?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], 'foundation__cta-icon-image', 'thumbnail' ); ?>
						</span>
					<?php endif; ?>
					<div class="foundation__cta-copy">
						<?php if ( ! empty( $cta['title'] ) ) : ?><h3 class="foundation__cta-title"><?php echo esc_html( $cta['title'] ); ?></h3><?php endif; ?>
						<?php if ( ! empty( $cta['text'] ) ) : ?>
							<div class="foundation__cta-text"><?php echo wp_kses_post( wpautop( $cta['text'] ) ); ?></div>
						<?php endif; ?>
					</div>
					<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'foundation__cta-button' ); ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
